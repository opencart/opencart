<?php
class ModelExtensionPaymentBraintree extends Model {
	private $gateway = null;

	public function getTransaction($transaction_id) {
		try {
			if ($this->gateway != null) {
				$transaction = $this->gateway->transaction->find($transaction_id);
			} else {
				$transaction = Braintree_Transaction::find($transaction_id);
			}

			if ($transaction) {
				return $transaction;
			} else {
				return false;
			}
		} catch (Exception $e) {
			$this->log($e->getMessage());

			return false;
		}
	}

	public function getTransactions($data = array()) {
		try {
			if ($this->gateway != null) {
				$transactions = $this->gateway->transaction->search($data);
			} else {
				$transactions = Braintree_Transaction::search($data);
			}

			if ($transactions) {
				return $transactions;
			} else {
				return false;
			}
		} catch (Exception $e) {
			$this->log($e->getMessage());

			return false;
		}
	}

	public function voidTransaction($transaction_id) {
		try {
			if ($this->gateway != null) {
				$transaction = $this->gateway->transaction->void($transaction_id);
			} else {
				$transaction = Braintree_Transaction::void($transaction_id);
			}

			if ($transaction) {
				return $transaction;
			} else {
				return false;
			}
		} catch (Exception $e) {
			$this->log($e->getMessage());

			return false;
		}
	}

	public function settleTransaction($transaction_id, $amount) {
		try {
			if ($this->gateway != null) {
				$transaction = $this->gateway->transaction->submitForSettlement($transaction_id, $amount);
			} else {
				$transaction = Braintree_Transaction::submitForSettlement($transaction_id, $amount);
			}

			if ($transaction) {
				return $transaction;
			} else {
				return false;
			}
		} catch (Exception $e) {
			$this->log($e->getMessage());

			return false;
		}
	}

	public function refundTransaction($transaction_id, $amount) {
		try {
			if ($this->gateway != null) {
				$transaction = $this->gateway->transaction->refund($transaction_id, $amount);
			} else {
				$transaction = Braintree_Transaction::refund($transaction_id, $amount);
			}

			if ($transaction) {
				return $transaction;
			} else {
				return false;
			}
		} catch (Exception $e) {
            $this->log($e->getMessage());

			return false;
		}
	}

	public function verifyCredentials() {
		try {
			//Try API call, if no exception is thrown, the credentials are correct
			if ($this->gateway != null) {
				$client_token = $this->gateway->clientToken->generate();
			} else {
				$client_token = Braintree_ClientToken::generate();
			}

			return $client_token;
		} catch (Exception $e) {
            $this->log($e->getMessage());

			return false;
		}
	}

	public function verifyMerchantAccount($merchant_account_id) {
		try {
			//Try API call, if no exception is thrown, the above credentials are correct
			if ($this->gateway != null) {
				$merchant_account = $this->gateway->merchantAccount->find($merchant_account_id);
			} else {
				$merchant_account = Braintree_MerchantAccount::find($merchant_account_id);
			}

			if ($merchant_account && $merchant_account->status == 'active') {
				return true;
			} else {
				return false;
			}
		} catch (Exception $e) {
            $this->log($e->getMessage());

			return false;
		}
	}

	public function setCredentials($data) {
		if ($this->config->get('braintree_access_token') != '') {
			$this->gateway = new Braintree_Gateway([
				'accessToken' => $this->config->get('braintree_access_token'),
			]);
		} else {
			Braintree_Configuration::environment($data['braintree_environment']);
			Braintree_Configuration::merchantId($data['braintree_merchant_id']);
			Braintree_Configuration::publicKey($data['braintree_public_key']);
			Braintree_Configuration::privateKey($data['braintree_private_key']);
		}
	}

	public function log($data) {
		if ($this->config->get('braintree_debug')) {
			$backtrace = debug_backtrace();
			$log = new Log('braintree.log');
			$log->write('(' . $backtrace[1]['class'] . '::' . $backtrace[1]['function'] . ') - ' . print_r($data, true));
		}
	}
}