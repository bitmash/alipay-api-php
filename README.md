Alipay API PHP Library
======================

IMPORTANT! This library hasn't been thoroughly tested so use it as a guide mostly.

## About

This is a concise library written in PHP for the Alipay API merchant account. It's very basic and only offers support for creating direct pay by user transactions.

## Direct Pay By User

You will need to download Alipay's API zip file with demos on this page (the first download link):
http://club.alipay.com/read.php?tid=9976972. Alternatively, you can download it directly from here:
http://download.alipay.com/public/api/base/alipaydirect.zip

You can also download an English version of the Alipay's Global API documentation. It is somewhat similar to their domestic merchant API.
https://download.alipay.com/ui/doc/global/cross-border_website_payment.zip

Decompress the file and navigate to a folder with `create_direct_pay_by_user` in the name. Inside the `demo` folder you will find a PHP (UTF-8) folder. Inside this folder are examples and a file called `cacert.pem`. Upload this file to your website because you will need it to make requests using cURL.

The comments in these classes will guide you when building your layer on top of this library.

## Usage

All of this information is to the best of my knowledge, so there may be some inaccurate details here. I will continue to update it as I get a better idea of the workflow. In both cases you will need to automatically verify that the transaction is authentic by comparing their `sign` with yours that you compute from the response data. You also need to verify that the `notify_id` Alipay sends back to your URL is valid by send a `GET` request to `https://mapi.alipay.com/gateway.do?service=notify_verify...`. The `notify_id` is set to expire within a minute or so. If the transaction is authentic, you will receive a response of `true`; otherwise, it will be `false`.

### return_url

The response will be in your `GET` data when Alipay sends the user back to your specified `return_url`.

### notify_url

The response will be in your `POST` data when Alipay notifies your specified `notify_url`. Be sure to disable any CSRF security and remove the login requirement you might have in place for the `notify_url`.

### Response Types

`WAIT_BUYER_PAY` - wait for buyer to pay
`TRADE_CLOSED` - transaction timed out
`TRADE_PENDING` - waiting for buyer to pay
`TRADE_SUCCESS` - payment was successful, refunds allowed
`TRADE_FINISHED` - payment was successful, no refunds allowed

### Response Handler

Be sure to store a unique ID to reference later on the return or notify URLs. For example, both the `return_url` and `notify_url` can be set to `https://yoursite.com/account/add_funds/id/<id>`. You can do a look-up in your own database to make sure the transaction hasn't already been completed in the past, and it can be linked to the user that initiated the request, along with other transaction details. After the response is handled, you can then redirect the user to another page.