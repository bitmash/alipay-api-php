<?php

/*
    https://github.com/bitmash/alipay-api-php
    
    Configuration settings for Alipay API

    Define each variable below according to your API settings.
*/

class Config {

    /*
        The HTTPS API URL is https://mapi.alipay.com/gateway.do
        There are no Sandbox URLs that I'm aware of.
    */
    private $_endpoint = "https://mapi.alipay.com/gateway.do";

    /*
        This unique number is essentially your account number that is referred
        to as the Partner ID. It begins with '2088' and is 16 numbers.
    */
    private $_partner_id = "";

    /*
        This is your API secret that only you and Alipay need to know.
        It's used for creating the MD5 hash. Don't make it public.
    */
    private $_api_secret = "";

    /*
        This is your Alipay account's e-mail address.
    */
    private $_seller_email = "";

    /*
        You need to reference this certificate when using cURL.
        It's part of the Alipay demo zip under the PHP examples.
    */
    private $_ssl_cert = "/path/to/alipay_ssl_cert.pem";

    /*
        Options: utf-8, gbk, gb2312
    */
    private $_input_charset = "utf-8";

    public function __construct()
    {
        return $this;
    }

    public function endpoint()
    {
        return $this->_endpoint;
    }

    public function partner_id()
    {
        return $this->_partner_id;
    }

    public function secret()
    {
        return $this->_api_secret;
    }

    public function seller_email()
    {
        return $this->_seller_email;
    }

    public function ssl_cert()
    {
        return $this->_ssl_cert;
    }

    public function charset()
    {
        return $this->_input_charset;
    }
}

?>