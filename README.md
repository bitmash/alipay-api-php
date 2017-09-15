Alipay API PHP Library
======================

## About

This is a concise library written in PHP for the Alipay API merchant account for overseas merchants. It's basic and only offers support for creating direct pay by user transactions - also known as "Cross-border Website Payment". Alipay provides documentation and code examples at https://globalprod.alipay.com/order/integrationGuide.htm.

This library does not support Transaction Queries, Refunds and Reconciliation. I may add these another time unless someone else wants to take a crack at it.

## Development

Alipay provides a test environment with a dummy account and test links assuming it's working.

### Account

A test account is provided by Alipay.

* Endpoint: https://openapi.alipaydev.com/gateway.do
* Partner ID: 2088101122136241
* Partner Secret: 760bdzec6y9goq7ctyx96ezkz78287de
* Username: alipaytest20091@gmail.com
* Password: 111111
* Payment password: 111111
* CAPTCHA: 8888

## Usage

All of this information is to the best of my knowledge, so there may be some inaccurate details here. You will need to automatically verify that the transaction is authentic by comparing their `sign` with yours that you compute from the response. You also need to verify that the `notify_id` Alipay sends to your `return_url` is valid by sending a `GET` request to `https://mapi.alipay.com/gateway.do?service=notify_verify...`. The `notify_id` is set to expire within a minute. If the transaction is authentic, you will receive a response of `true`; otherwise, it will be `false`.

### return_url

The response will be in the `GET` data when Alipay sends the user back to your specified `return_url`. Check the `trade_status` once they return to see if it's already completed (TRADE_FINISHED).

### notify_url

In some cases the payment verification takes longer on Alipay's end so they will ping your specified `notify_url`. The response will be in the `POST` data. Be sure to disable any CSRF security and remove the login requirement you might have in place for the `notify_url`.

### Transaction Response Types

* `WAIT_BUYER_PAY` - waiting for the buyer to pay.
* `TRADE_CLOSED` - transaction closed without payment.
* `TRADE_FINISHED` - payment was successful, and transaction is closed.

### Response Handler

Be sure to store a unique ID to reference later on the return or notify URLs. For example, both the `return_url` and `notify_url` can be set to `https://yoursite.com/account/add_funds/id/<id>`. You can do a look-up in your own database to make sure the transaction hasn't already been completed in the past, and it can be linked to the user that initiated the request, along with other transaction details. After the response is handled and verified, you can then redirect the user to another page.
