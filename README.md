Alipay API PHP Library
======================

IMPORTANT! This library hasn't been thoroughly tested so use it as a guide mostly.

## About

This is a concise library written in PHP for the Alipay API merchant account. It's very basic and only offers support for creating direct pay by user transactions. I don't read or write Chinese so this was a slight challenge to implement.

## Direct Pay By User

You will need to download Alipay's API zip file with demos on this page (the first download link):
http://club.alipay.com/read.php?tid=9976972

Alternatively, you can download it directly from here:
http://download.alipay.com/public/api/base/alipaydirect.zip

Decompress the file and navigate to a folder with `create_direct_pay_by_user` in the name. Inside the `demo` folder you will find a PHP (UTF-8) folder. Inside this folder are examples and a file called `cacert.pem`. Upload this file to your website because you will need it to make requests using cURL.

The comments in these classes will guide you when building your layer on top of this library.

## Usage

Not all transactions are completed immediately, so Alipay will `POST` data to your specified `notify_url` later. Be sure to check if the trade status is FINISHED or SUCCESSFUL before completing your end of the transaction. Also be sure to store a unique ID to reference later on the return or notify URLs. For exmaple, both the `return_url` and `notify_url` can be set to `https://yoursite.com/account/add_funds/id/<id>`.

The `id` can be generated using a function like `uniqid()`. You can do a look-up in your own database to make sure the transaction hasn't already been completed in the past, and it can be linked to the user that initiated the request, along with other transaction details.

When the user returns to this page, the data will be referenced in `$_GET`. When Alipay notifies your site, the data will be referenced in `$_POST`.

There is a time limit for notifications. If the transaction request exceeds that limit, it will notify the specified URL with the corresponding trade status.