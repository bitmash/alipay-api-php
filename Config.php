<?php

/*
    https://github.com/bitmash/alipay-api-php
    
    Configuration settings for Alipay API
    Define each variable below according to your API settings.
    Development / test account is provided according to the docs.
*/

class Config {

    /*
        Toggle between testing and production environments
        Valid values are: development, production
    */
    private $_environment = "development";

    /*
        The HTTPS API URL is https://mapi.alipay.com/gateway.do
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
        Currency to trade in. Default is RMB.
        Valid values are: GBP, HKD, USD, CHF, SGD, SEK, DKK, NOK, JPY, CAD, AUD
        EUR, NZD, RUB, MOP
    */
    private $_currency = "";

    /*
        You need to reference this certificate when using cURL.
    */
    private $_ssl_cert = "alipay_cert.pem";

    /*
        Options: utf-8, gbk, gb2312
        This library only supports utf-8
    */
    private $_input_charset = "utf-8";

    public function __construct()
    {
        return $this;
    }

    public function endpoint()
    {
        if ($this->_environment == "development")
        {
            return "https://mapi.alipay.net/gateway.do";
        }
        return $this->_endpoint;
    }

    public function partner_id()
    {
        if ($this->_environment == "development")
        {
            return "2088101122136241";
        }
        return $this->_partner_id;
    }

    public function secret()
    {
        if ($this->_environment == "development")
        {
            return "760bdzec6y9goq7ctyx96ezkz78287de";
        }
        return $this->_api_secret;
    }

    public function currency()
    {
        return $this->_currency;
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