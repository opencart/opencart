<?php
class ModelExtensionPaymentPPBraintree extends Model {
	public function generateToken($gateway, $data = array()) {
		try {
			if ($gateway != null) {
				$client_token = $gateway->clientToken()->generate($data);
			} else {
				$client_token = Braintree_ClientToken::generate($data);
			}

			return $client_token;
		} catch (Exception $e) {
			$this->log($e->getMessage());

			return false;
		}
	}

	public function getTransaction($gateway, $transaction_id) {
		try {
			if ($gateway != null) {
				$transaction = $gateway->transaction()->find($transaction_id);
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

	public function getTransactions($gateway, $data = array()) {
		try {
			if ($gateway != null) {
				$transactions = $gateway->transaction()->search($data);
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

	public function voidTransaction($gateway, $transaction_id) {
		try {
			if ($gateway != null) {
				$transaction = $gateway->transaction()->void($transaction_id);
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

	public function settleTransaction($gateway, $transaction_id, $amount) {
		try {
			if ($gateway != null) {
				$transaction = $gateway->transaction()->submitForSettlement($transaction_id, $amount);
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

	public function refundTransaction($gateway, $transaction_id, $amount) {
		try {
			if ($gateway != null) {
				$transaction = $gateway->transaction()->refund($transaction_id, $amount);
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

	public function verifyCredentials($gateway) {
		try {
			//Try API call, if no exception is thrown, the credentials are correct
			if ($gateway != null) {
				$client_token = $gateway->clientToken()->generate();
			} else {
				$client_token = Braintree_ClientToken::generate();
			}

			return $client_token;
		} catch (Exception $e) {
            $this->log($e->getMessage());

			return false;
		}
	}

	public function verifyMerchantAccount($gateway, $merchant_account_id) {
		try {
			//Try API call, if no exception is thrown, the above credentials are correct
			if ($gateway != null) {
				$merchant_account = $gateway->merchantAccount()->find($merchant_account_id);
			} else {
				$merchant_account = Braintree_MerchantAccount::find($merchant_account_id);
			}

			if ($merchant_account && $merchant_account->status == 'active') {
				return $merchant_account;
			} else {
				return false;
			}
		} catch (Exception $e) {
            $this->log($e->getMessage());

			return false;
		}
	}

	public function setGateway($access_token) {
		return new Braintree_Gateway(array('accessToken' => $access_token));
	}

	public function log($data) {
		if ($this->config->get('payment_pp_braintree_debug')) {
			$backtrace = debug_backtrace();
			$log = new Log('braintree.log');
			$log->write('(' . $backtrace[1]['class'] . '::' . $backtrace[1]['function'] . ') - ' . print_r($data, true));
		}
	}
}
