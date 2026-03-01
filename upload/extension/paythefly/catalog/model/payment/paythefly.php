<?php
namespace Opencart\Catalog\Model\Extension\Paythefly\Payment;

/**
 * Class PayTheFly
 *
 * Catalog model for PayTheFly payment gateway.
 * Handles payment method availability, EIP-712 signature generation,
 * and transaction persistence.
 *
 * @package Opencart\Catalog\Model\Extension\Paythefly\Payment
 */
class Paythefly extends \Opencart\System\Engine\Model {
	/**
	 * Chain configuration constants
	 */
	private const CHAINS = [
		'bsc'  => ['chainId' => 56,        'decimals' => 18, 'nativeToken' => '0x0000000000000000000000000000000000000000'],
		'tron' => ['chainId' => 728126428,  'decimals' => 6,  'nativeToken' => 'T9yD14Nj9j7xAB4dbGeiX9h8unkKHxuWwb'],
	];

	/**
	 * Get Methods - Determine if PayTheFly is available for this order
	 *
	 * @param array<string, mixed> $address
	 *
	 * @return array<string, mixed>
	 */
	public function getMethods(array $address = []): array {
		$this->load->language('extension/paythefly/payment/paythefly');

		if ($this->cart->hasSubscription()) {
			$status = false;
		} elseif (!$this->config->get('config_checkout_payment_address')) {
			$status = true;
		} elseif (!$this->config->get('payment_paythefly_geo_zone_id')) {
			$status = true;
		} else {
			$this->load->model('localisation/geo_zone');

			$results = $this->model_localisation_geo_zone->getGeoZone(
				(int)$this->config->get('payment_paythefly_geo_zone_id'),
				(int)$address['country_id'],
				(int)$address['zone_id']
			);

			$status = (bool)$results;
		}

		$method_data = [];

		if ($status) {
			$option_data['paythefly'] = [
				'code' => 'paythefly.paythefly',
				'name' => $this->language->get('heading_title')
			];

			$method_data = [
				'code'       => 'paythefly',
				'name'       => $this->language->get('heading_title'),
				'option'     => $option_data,
				'sort_order' => $this->config->get('payment_paythefly_sort_order')
			];
		}

		return $method_data;
	}

	/**
	 * Get chain configuration
	 *
	 * @param string $chain Chain identifier ('bsc' or 'tron')
	 *
	 * @return array<string, mixed>
	 */
	public function getChainConfig(string $chain = ''): array {
		if (!$chain) {
			$chain = $this->config->get('payment_paythefly_chain') ?: 'bsc';
		}

		if (!isset(self::CHAINS[$chain])) {
			$chain = 'bsc';
		}

		$config = self::CHAINS[$chain];

		// Override with admin-configured token if set
		$tokenAddress = $this->config->get('payment_paythefly_token_address');
		if ($tokenAddress) {
			$config['nativeToken'] = $tokenAddress;
		}

		$tokenDecimals = $this->config->get('payment_paythefly_token_decimals');
		if ($tokenDecimals !== null && $tokenDecimals !== '') {
			$config['decimals'] = (int)$tokenDecimals;
		}

		return $config;
	}

	/**
	 * Convert human-readable amount to raw wei/sun (uint256 string)
	 *
	 * @param string $amount  Human-readable amount (e.g., "0.01")
	 * @param int    $decimals Token decimals
	 *
	 * @return string Raw amount as decimal string (no 0x prefix)
	 */
	public function toRawAmount(string $amount, int $decimals): string {
		// Use bcmath for precision
		if (!function_exists('bcmul')) {
			// Fallback without bcmath - still precise for reasonable amounts
			$parts = explode('.', $amount);
			$whole = $parts[0] ?? '0';
			$frac  = $parts[1] ?? '';

			// Pad or truncate fractional part to exactly $decimals digits
			$frac = str_pad(substr($frac, 0, $decimals), $decimals, '0');

			$raw = ltrim($whole . $frac, '0') ?: '0';

			return $raw;
		}

		$multiplier = bcpow('10', (string)$decimals, 0);
		$raw = bcmul($amount, $multiplier, 0);

		return $raw;
	}

	/**
	 * Generate EIP-712 PaymentRequest signature
	 *
	 * CRITICAL: Uses kornrunner/keccak for Keccak-256.
	 * PHP's hash('sha3-256') uses NIST SHA3 padding which is INCOMPATIBLE
	 * with Ethereum's Keccak-256.
	 *
	 * @param array<string, mixed> $params Payment parameters
	 *
	 * @return string Hex signature with 0x prefix
	 *
	 * @throws \RuntimeException If kornrunner/keccak is not available
	 */
	public function signPaymentRequest(array $params): string {
		// CRITICAL: Verify kornrunner/keccak is available
		if (!class_exists('\kornrunner\Keccak')) {
			throw new \RuntimeException(
				'kornrunner/keccak library is required for EIP-712 signing. ' .
				'Install it via: composer require kornrunner/keccak. ' .
				'DO NOT use hash("sha3-256") as a fallback — the padding differs from Keccak-256 and signatures will be invalid.'
			);
		}

		$chainConfig = $this->getChainConfig();
		$contractAddress = $this->config->get('payment_paythefly_contract_address');
		$privateKey = $this->config->get('payment_paythefly_private_key');

		// EIP-712 Domain Separator
		$domainSeparator = $this->encodeDomainSeparator(
			'PayTheFlyPro',
			'1',
			$chainConfig['chainId'],
			$contractAddress
		);

		// PaymentRequest type hash
		$typeHash = $this->keccak256(
			'PaymentRequest(string projectId,address token,uint256 amount,string serialNo,uint256 deadline)'
		);

		// Encode struct values
		$structHash = $this->keccak256(
			hex2bin($typeHash) .
			hex2bin($this->keccak256($params['projectId'])) .
			hex2bin($this->abiEncodeAddress($params['token'])) .
			hex2bin($this->abiEncodeUint256($params['amount'])) .
			hex2bin($this->keccak256($params['serialNo'])) .
			hex2bin($this->abiEncodeUint256((string)$params['deadline']))
		);

		// EIP-712 signing hash: \x19\x01 ‖ domainSeparator ‖ structHash
		$signingHash = $this->keccak256(
			"\x19\x01" .
			hex2bin($domainSeparator) .
			hex2bin($structHash)
		);

		// ECDSA sign
		return $this->ecSign($signingHash, $privateKey);
	}

	/**
	 * Encode EIP-712 domain separator
	 *
	 * @param string $name             Domain name
	 * @param string $version          Domain version
	 * @param int    $chainId          Chain ID
	 * @param string $contractAddress  Verifying contract address
	 *
	 * @return string Hex hash (no 0x prefix)
	 */
	private function encodeDomainSeparator(string $name, string $version, int $chainId, string $contractAddress): string {
		$typeHash = $this->keccak256(
			'EIP712Domain(string name,string version,uint256 chainId,address verifyingContract)'
		);

		return $this->keccak256(
			hex2bin($typeHash) .
			hex2bin($this->keccak256($name)) .
			hex2bin($this->keccak256($version)) .
			hex2bin($this->abiEncodeUint256((string)$chainId)) .
			hex2bin($this->abiEncodeAddress($contractAddress))
		);
	}

	/**
	 * Keccak-256 hash (NOT SHA3-256!)
	 *
	 * @param string $data Raw bytes to hash
	 *
	 * @return string 64-char hex string (no 0x prefix)
	 */
	private function keccak256(string $data): string {
		return \kornrunner\Keccak::hash($data, 256);
	}

	/**
	 * ABI-encode an address to 32 bytes (left-padded)
	 *
	 * @param string $address Hex address with or without 0x prefix
	 *
	 * @return string 64-char hex string
	 */
	private function abiEncodeAddress(string $address): string {
		$address = strtolower(ltrim($address, '0x'));

		return str_pad($address, 64, '0', STR_PAD_LEFT);
	}

	/**
	 * ABI-encode a uint256 to 32 bytes
	 *
	 * @param string $value Decimal string
	 *
	 * @return string 64-char hex string
	 */
	private function abiEncodeUint256(string $value): string {
		$hex = $this->decToHex($value);

		return str_pad($hex, 64, '0', STR_PAD_LEFT);
	}

	/**
	 * Convert decimal string to hex string (handles big numbers)
	 *
	 * @param string $dec Decimal string
	 *
	 * @return string Hex string (no prefix)
	 */
	private function decToHex(string $dec): string {
		if ($dec === '0') {
			return '0';
		}

		if (function_exists('gmp_strval')) {
			return gmp_strval(gmp_init($dec, 10), 16);
		}

		// BC math fallback
		$hex = '';
		while (bccomp($dec, '0') > 0) {
			$remainder = bcmod($dec, '16');
			$hex = dechex((int)$remainder) . $hex;
			$dec = bcdiv($dec, '16', 0);
		}

		return $hex ?: '0';
	}

	/**
	 * ECDSA sign a hash with private key (secp256k1)
	 *
	 * @param string $hash       Hex hash (no 0x prefix)
	 * @param string $privateKey Hex private key (with or without 0x prefix)
	 *
	 * @return string Signature with 0x prefix (r + s + v, 132 chars)
	 */
	private function ecSign(string $hash, string $privateKey): string {
		$privateKey = ltrim($privateKey, '0x');

		// Use Elliptic Curve Digital Signature Algorithm
		if (!class_exists('\Elliptic\EC')) {
			// Fallback: use simplito/elliptic-php or mdanter
			if (class_exists('\Mdanter\Ecc\EccFactory')) {
				return $this->ecSignMdanter($hash, $privateKey);
			}

			throw new \RuntimeException(
				'An ECDSA library is required. Install one of: ' .
				'simplito/elliptic-php (recommended) or mdanter/ecc. ' .
				'Run: composer require simplito/elliptic-php'
			);
		}

		$ec = new \Elliptic\EC('secp256k1');
		$key = $ec->keyFromPrivate($privateKey, 'hex');
		$signature = $key->sign(hex2bin($hash), ['canonical' => true]);

		$r = str_pad($signature->r->toString(16), 64, '0', STR_PAD_LEFT);
		$s = str_pad($signature->s->toString(16), 64, '0', STR_PAD_LEFT);
		$v = dechex($signature->recoveryParam + 27);

		return '0x' . $r . $s . $v;
	}

	/**
	 * ECDSA sign using mdanter/ecc library
	 *
	 * @param string $hash       Hex hash
	 * @param string $privateKey Hex private key
	 *
	 * @return string Signature with 0x prefix
	 */
	private function ecSignMdanter(string $hash, string $privateKey): string {
		$adapter = \Mdanter\Ecc\EccFactory::getAdapter();
		$generator = \Mdanter\Ecc\EccFactory::getSecgCurves()->generator256k1();
		$algorithm = new \Mdanter\Ecc\Crypto\Signature\SignHasher('sha256');

		$key = $generator->getPrivateKeyFrom(gmp_init($privateKey, 16));
		$hashGmp = gmp_init($hash, 16);

		$randomK = \Mdanter\Ecc\Random\RandomGeneratorFactory::getHmacRandomGenerator($key, $hashGmp, 'sha256');
		$signer = new \Mdanter\Ecc\Crypto\Signature\Signer($adapter);
		$signature = $signer->sign($key, $hashGmp, $randomK->generate($generator->getOrder()));

		$r = str_pad(gmp_strval($signature->getR(), 16), 64, '0', STR_PAD_LEFT);
		$s = str_pad(gmp_strval($signature->getS(), 16), 64, '0', STR_PAD_LEFT);

		// v = 27 for even y parity, 28 for odd (simplified)
		$v = '1b'; // 27 in hex; recovery param determination requires more context

		return '0x' . $r . $s . $v;
	}

	/**
	 * Build PayTheFly payment URL
	 *
	 * @param array<string, mixed> $params Parameters
	 *
	 * @return string Full payment URL
	 */
	public function buildPaymentUrl(array $params): string {
		$chainConfig = $this->getChainConfig();

		$tokenAddress = $this->config->get('payment_paythefly_token_address')
			?: $chainConfig['nativeToken'];

		$query = http_build_query([
			'chainId'    => $chainConfig['chainId'],
			'projectId'  => $params['projectId'],
			'amount'     => $params['amountDisplay'],  // Human-readable for URL
			'serialNo'   => $params['serialNo'],
			'deadline'   => $params['deadline'],
			'signature'  => $params['signature'],
			'token'      => $tokenAddress,
		]);

		return 'https://pro.paythefly.com/pay?' . $query;
	}

	/**
	 * Verify webhook HMAC signature
	 *
	 * @param string $data      Raw JSON data string
	 * @param string $sign      HMAC hex signature
	 * @param int    $timestamp Unix timestamp
	 *
	 * @return bool
	 */
	public function verifyWebhookSignature(string $data, string $sign, int $timestamp): bool {
		$projectKey = $this->config->get('payment_paythefly_project_key');

		if (!$projectKey) {
			return false;
		}

		// Signature = HMAC-SHA256(data + "." + timestamp, projectKey)
		$payload = $data . '.' . $timestamp;
		$expected = hash_hmac('sha256', $payload, $projectKey);

		return hash_equals($expected, $sign);
	}

	/**
	 * Add a transaction record
	 *
	 * @param array<string, mixed> $data
	 *
	 * @return int Transaction ID
	 */
	public function addTransaction(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "paythefly_transaction` SET
			`order_id`       = '" . (int)$data['order_id'] . "',
			`serial_no`      = '" . $this->db->escape($data['serial_no']) . "',
			`project_id`     = '" . $this->db->escape($data['project_id']) . "',
			`chain_symbol`   = '" . $this->db->escape($data['chain_symbol'] ?? '') . "',
			`chain_id`       = '" . (int)($data['chain_id'] ?? 0) . "',
			`token_address`  = '" . $this->db->escape($data['token_address'] ?? '') . "',
			`amount`         = '" . $this->db->escape($data['amount'] ?? '0') . "',
			`amount_display` = '" . (float)($data['amount_display'] ?? 0) . "',
			`signature`      = '" . $this->db->escape($data['signature'] ?? '') . "',
			`deadline`       = '" . (int)($data['deadline'] ?? 0) . "',
			`payment_url`    = '" . $this->db->escape($data['payment_url'] ?? '') . "',
			`status`         = 'pending',
			`webhook_raw`    = '',
			`date_added`     = NOW(),
			`date_modified`  = NOW()
		");

		return $this->db->getLastId();
	}

	/**
	 * Get transaction by serial number
	 *
	 * @param string $serialNo
	 *
	 * @return array<string, mixed>
	 */
	public function getTransactionBySerialNo(string $serialNo): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "paythefly_transaction` WHERE `serial_no` = '" . $this->db->escape($serialNo) . "'");

		return $query->row;
	}

	/**
	 * Update transaction from webhook data
	 *
	 * @param string $serialNo
	 * @param array<string, mixed> $data
	 *
	 * @return void
	 */
	public function updateTransaction(string $serialNo, array $data): void {
		$sets = [];

		$allowed = ['tx_hash', 'wallet', 'fee', 'chain_symbol', 'status', 'webhook_raw'];
		foreach ($allowed as $field) {
			if (isset($data[$field])) {
				$sets[] = "`{$field}` = '" . $this->db->escape($data[$field]) . "'";
			}
		}

		if (isset($data['tx_type'])) {
			$sets[] = "`tx_type` = '" . (int)$data['tx_type'] . "'";
		}
		if (isset($data['confirmed'])) {
			$sets[] = "`confirmed` = '" . (int)$data['confirmed'] . "'";
		}

		$sets[] = "`date_modified` = NOW()";

		$this->db->query("UPDATE `" . DB_PREFIX . "paythefly_transaction` SET " . implode(', ', $sets) . " WHERE `serial_no` = '" . $this->db->escape($serialNo) . "'");
	}

	/**
	 * Log debug messages (when debug mode is enabled)
	 *
	 * @param string $message
	 *
	 * @return void
	 */
	public function log(string $message): void {
		if ($this->config->get('payment_paythefly_debug')) {
			$log = new \Opencart\System\Library\Log('paythefly.log');
			$log->write($message);
		}
	}
}
