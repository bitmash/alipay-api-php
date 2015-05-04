Alipay API PHP Library
======================

## About

This is a concise library written in PHP for the Alipay API merchant account for overseas merchants. It's very basic and only offers support for creating direct pay by user transactions: also known as "Cross-border Website Payment". Alipay provides documentation and code examples at https://globalprod.alipay.com/order/integrationGuide.htm.

This library does not support Transaction Queries, Refunds and Reconciliation. I may add these another time unless someone else wants to take a crack at it.

## Development

Alipay provides a test environment with a dummy account and test links, assuming it's working.

### Account

A test account is provided by Alipay.

* Endpoint: http(s)://mapi.alipay.net/gateway.do.
* Partner ID: 2088101122136241
* Partner Secret: 760bdzec6y9goq7ctyx96ezkz78287de
* Username: alipaytest20091@gmail.com
* Password: alipay
* Payment password: alipay
* CAPTCHA: 8888

### Test Link

#### HTTP
http://mapi.alipay.net/gateway.do?body=test&subject=test&sign_type=MD5&out_trade_no=4403648718928911&currency=USD&total_fee=0.1&partner=2088101122136241&notify_url=http%3A%2F%2Fapi.test.alipay.net%2Fatinterface%2Freceive_notify.htm&sendFormat=normal&return_url=https%3A%2F%2Fdevmobile.inicis.com%2Fsmart%2Ftestmall%2Freturn_url_test.php%3FOID%3D20131008414885731&sign=22a0b5d9fcfa4c4b2633c787aefcb2cc&_input_charset=UTF-8&service=create_forex_trade

#### HTTPS
https://mapi.alipay.net/gateway.do?body=test&subject=test&sign_type=MD5&out_trade_no=4403648718928911&currency=USD&total_fee=0.1&partner=2088101122136241&notify_url=http%3A%2F%2Fapi.test.alipay.net%2Fatinterface%2Freceive_notify.htm&sendFormat=normal&return_url=https%3A%2F%2Fdevmobile.inicis.com%2Fsmart%2Ftestmall%2Freturn_url_test.php%3FOID%3D20131008414885731&sign=22a0b5d9fcfa4c4b2633c787aefcb2cc&_input_charset=UTF-8&service=create_forex_trade

## Usage

All of this information is to the best of my knowledge, so there may be some inaccurate details here. I will continue to update it as I get a better idea of the workflow. In both cases you will need to automatically verify that the transaction is authentic by comparing their `sign` with yours that you compute from the response data. You also need to verify that the `notify_id` Alipay sends back to your URL is valid by send a `GET` request to `https://mapi.alipay.com/gateway.do?service=notify_verify...`. The `notify_id` is set to expire within a minute. If the transaction is authentic, you will receive a response of `true`; otherwise, it will be `false`.

### return_url

The response will be in your `GET` data when Alipay sends the user back to your specified `return_url`. Check the `trade_status` once they return to see if it's already completed (TRADE_FINISHED).

### notify_url

The response will be in your `POST` data when Alipay notifies your specified `notify_url`. Be sure to disable any CSRF security and remove the login requirement you might have in place for the `notify_url`.

### Transaction Response Types

* `WAIT_BUYER_PAY` - waiting for the buyer to pay.
* `TRADE_CLOSED` - transaction closed without payment.
* `TRADE_FINISHED` - payment was successful, and transaction is closed.

### Response Handler

Be sure to store a unique ID to reference later on the return or notify URLs. For example, both the `return_url` and `notify_url` can be set to `https://yoursite.com/account/add_funds/id/<id>`. You can do a look-up in your own database to make sure the transaction hasn't already been completed in the past, and it can be linked to the user that initiated the request, along with other transaction details. After the response is handled and verified, you can then redirect the user to another page.