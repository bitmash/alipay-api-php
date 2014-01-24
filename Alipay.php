<?php

/*
	https://github.com/bitmash/alipay-api-php
*/

class Alipay {

	private $config;

	/**
		Alipay does not have a Sandbox that I'm aware of, but I can't read Chinese
		so I'm probably missing out.
	**/
	public function __construct()
	{
		include_once(__DIR__ . "/Config.php");
		$this->config = new Config();
	}

	/**
		We create a transaction URL for Alipay. The user is sent back to the 
		specified return_url after completing the transaction on Alipay's site.
		There are two types of response handlers, and it's recommended to use both
		in case there is a hold on processing the payment:

		return_url - Alipay sends the buyer back to this URL synchronously, along with a GET response.
		notify_url - a POST response is sent asynchronously once the payment is verified.

		The response needs to be verified regardless of the handler.

		@param	sale_id		string(64)	your internal transaction ID for reference
		@param	amount		float		the amount charged to the user (RMB)
		@param	description	string(256)	a description of the goods being sold
		@param	return_url	string(200)	your URL to return to after payment
		@param	notify_url	string(90)	your URL Alipay will ping after payment
		@return	string
	**/
	public function createPayment($sale_id, $amount, $description, $return_url, $notify_url)
	{
		$data = array(
			'service' => 'create_direct_pay_by_user',
			'seller_email' => $this->config->_seller_email,
			'payment_type' => "1",
			'out_trade_no' => $sale_id,
			'subject' => substr($description, 0, 256),
			//'body' => '',
			'total_fee' => $amount,
			'notify_url' => $notify_url,
			'return_url' => $return_url,
			'partner' => $this->config->_partner_id,
			'_input_charset' => $this->config->_input_charset
		);

		return $this->config->_endpoint . "?" . $this->_prepData($data);
	}

	/**
		Compares the signed response data from Alipay with our own key
		using the GET parameters. Then verifies the Notify ID from the 
		transaction is valid by pinging Alipay again.

		@param	data	array	the response GET parameters from Alipay
		@return	mixed
	**/
	public function verifyPayment($data)
	{
		$result = array('result' => false);
		$sign = $data['sign'];
		unset($data['sign'], $data['sign_type']);

		if ($sign != $this->_sign($data))
		{
			return $result;
		}

		$request = array(
			'service' => 'notify_verify',
			'partner' => $this->config->_partner_id,
			'notify_id' => $data['notify_id']
		);
		
		$response = $this->_send("get", http_build_query($request));
		
		/*
			Possible Trade Status:
			WAIT_BUYER_PAY - wait for buyer to pay (notify_url)
			TRADE_CLOSED - transaction timed out (notify_url)
			TRADE_SUCCESS - payment was successful, refunds allowed (return_url)
			TRADE_PENDING - wait for buyer to pay (notify_url)
			TRADE_FINISHED - payment was successful, no refunds allowed (return_url)

			If the result is true, return the trade_no and add funds to balance
			If the result is false, but the response is valid, wait for Alipay
			to ping our notify_url before adding funds to balance.
			Otherwise, don't add funds at all.
		*/
		if (preg_match("/true$/i", $response))
		{
			if (in_array($data['trade_status'], array('TRADE_SUCCESS', 'TRADE_FINISHED')))
			{
				$result = array('result' => true, 'id' => $data['trade_no']);
			}
			else
			{
				$result['id'] = $data['trade_no'];
			}
		}
		else
		{
			$this->_error($response);
		}

		return $result;
	}

	/**
		Uses cURL to send data to Alipay. Can be either a POST or GET request.

		@param	method	string	the type of request: POST or GET
		@param	data	string	the data to send
		@return	string
	**/
	public function _send($method = "get", $data)
	{
		$curl = null;

		if ($method == "get")
		{
			$curl = curl_init($this->config->_endpoint . "?$data");
			curl_setopt($curl, CURLOPT_POST, false);
		}
		else
		{
			$curl = curl_init($this->config->_endpoint . "?_input_charset=" . $this->config->_input_charset);
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		}

		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($curl, CURLOPT_CAINFO, $this->config->_ssl_cert);
		$response = curl_exec($curl);

		if (curl_error($curl))
		{
			$this->_error(curl_error($curl));
		}
		curl_close($curl);

		return $response;
	}

	/**
		Prepares a request for delivery by building and encoding the query.

		@param	data	array	associative array of parameters
		@return	string
	**/
	private function _prepData($data)
	{
		$data['sign'] = $this->_sign($data);
		$data['sign_type'] = "MD5";
		ksort($data);
		return http_build_query($data);
	}

	/**
		Sorts the parameters alphabetically and creates a "secure" hash with the 
		secret key appended. When Alipay receives the request, they perform a 
		similar procedure to verify the data has not been tampered with.

		@param	data	array	associative array of parameters
		@return	string
	**/
	private function _sign($data)
	{
		ksort($data);
		$query = "";
		foreach ($data as $k => $v)
		{
			if ($v == "")
				continue;

			$query .= "$k=$v&";
		}

		return md5(substr($query, 0, -1) . $this->config->_api_secret);
	}

	/**
		Outputs an error. I suggest having it write to a log file instead.
		
		@param	msg	string	the message to output/write
		@return	null
	**/
	private function _error($msg)
	{
		$msg = is_string($msg) ? $msg : serialize($msg);
		echo "ALIPAY: " . $msg;
	}
}