<?php

namespace Komerci;

/**
 * Client
 *
 * @author Carlos Cima
 */
class Client
{
    const WSDL_URL = 'https://ecommerce.userede.com.br/pos_virtual/wskomerci/cap.asmx?WSDL';
    const WSDL_TEST_URL = 'https://ecommerce.userede.com.br/pos_virtual/wskomerci/cap_teste.asmx?WSDL';
    
    private static $lastRequest = null;
    private static $lastResponse = null;

    /**
     * Make SOAP Request
     * 
     * @param string $methodName
     * @param array $parameters
     * @param boolean $isTest
     * @param boolean $debug
     * 
     * @return \SimpleXMLElement
     */
    public static function SoapRequest($methodName, array $parameters, $isTest = false, $debug = false) {
        $soapClientParams = array();
        $soapClientParams['trace'] = 1;
        if ($isTest) {
            $wsdlUrl = static::WSDL_TEST_URL;
            $methodName .= 'Tst';
        } else {
            $wsdlUrl = static::WSDL_URL;
        }
        $soapClient = new \SoapClient($wsdlUrl, $soapClientParams); // optionally use KomerciSoapClient to format the XML according to the specs
        $soapResult = $soapClient->__soapCall($methodName, $parameters);
        if ($debug) {
            var_dump($soapClient->__getLastRequest());
        }
        $resultNodeName = $methodName . 'Result';
        $xmlResult = $soapResult->$resultNodeName->any;
        self::$lastRequest = $soapClient->__getLastRequest();
        self::$lastResponse = $xmlResult;
        
        return $xmlResult;
    }
    
    public static function getLastRequest()
    {
        return self::$lastRequest;
    }

    public static function getLastResponse()
    {
        return self::$lastResponse;
    }
    
}
