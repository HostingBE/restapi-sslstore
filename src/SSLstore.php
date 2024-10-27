<?php

/**
* @author Constan van Suchtelen van de Haere <constan.vansuchtelenvandehaere@hostingbe.com>
* @copyright 2024 HostingBE
*
*/
namespace HostingBE\Api;

use HostingBE\Api\Logger\Logger;
use HostingBE\Api\baseAPI;
use GuzzleHttp\Exception\RequestException;

class SSLStore {

   protected $partnercode;
   protected $authtoken;
   protected $replaytoken;

public function __construct($logger, $partnercode, $authtoken, $replaytoken) {
    $this->partnercode = $partnercode;
    $this->authtoken = $authtoken;
    $this->replaytoken = $replaytoken;
    $this->api = new baseAPI($logger);
    }

/**
 * Validate Order Parameters is an immutable operation https://www.thesslstore.com/api/validate-order-parameters
 */
public function ValidateOrder($order) {

    $extra = array(
        'CustomOrderID' => 'String content',
        'ProductCode' => $order['productcode'], 
        'ExtraProductCodes' => 'String content', 
        'OrganizationInfo' => array(
            'OrganizationName' => 'String content', 
            'DUNS' => 'String content', 
            'Division' => 'String content', 
            'IncorporatingAgency' => 'String content', 
            'RegistrationNumber' => 'String content', 
            'JurisdictionCity' => 'String content', 
            'JurisdictionRegion' => 'String content', 
            'JurisdictionCountry' => 'String content', 
            'OrganizationAddress' => array(
                'AddressLine1' => 'String content', 
                'AddressLine2' => 'String content', 
                'AddressLine3' => 'String content', 
                'City' => 'String content', 
                'Region' => 'String content', 
                'PostalCode' => 'String content', 
                'Country' => 'String content', 
                'Phone' => 'String content', 
                'Fax' => 'String content', 
                'LocalityName' => 'String content'
            ),
        ),
    'ValidityPeriod' => $order['validityperiod'],
	'ServerCount' => '1',
	'CSR' => $order['csr'],
	'DomainName' => $order['domainname'], 
	'WebServerType' => $order['webservertype'], 
	'DNSNames' => array($order['dnsnames']),
	'isCUOrder' => 'true',
	'isRenewalOrder' => 'false',
	'SpecialInstructions' => 'Requested via API', 
	'RelatedTheSSLStoreOrderID' =>  'String content', 
	'isTrialOrder' => 'true',
	'AdminContact' => array(
		'FirstName' => 'String content', 
		'LastName' => 'String content', 
		'Phone' => 'String content', 
		'Fax' => 'String content', 
		'Email' => 'String content', 
		'Title' => 'String content', 
		'OrganizationName' => 'String content', 
		'AddressLine1' => 'String content', 
		'AddressLine2' => 'String content', 
		'City' => 'String content', 
		'Region' => 'String content', 
		'PostalCode' => 'String content', 
		'Country' => 'String content'
	),
	'TechnicalContact' => array(
		'FirstName' => 'String content', 
		'LastName' => 'String content', 
		'Phone' => 'String content', 
		'Fax' => 'String content', 
		'Email' => 'String content', 
		'Title' => 'String content', 
		'OrganizationName' => 'String content', 
		'AddressLine1' => 'String content', 
		'AddressLine2' => 'String content', 
		'City' => 'String content', 
		'Region' => 'String content', 
		'PostalCode' => 'String content', 
		'Country' => 'String content', 
    ),
	'ApproverEmail' => $order['approveremail'], 
	'ReserveSANCount' => '2147483647',
	'AddInstallationSupport' => 'true',
	'EmailLanguageCode' => 'String content', 
	'FileAuthDVIndicator' => 'true',
	'CNAMEAuthDVIndicator' => 'true',
	'HTTPSFileAuthDVIndicator' => 'true',
	'SignatureHashAlgorithm' => 'String content'
     );

    return $this->api->common('POST','/order/validateorderparameters/',array_merge($this->createAuthRequest() , $extra));
    }  

/**
 * Show agreements between the vendor and the customer https://www.thesslstore.com/api/product-agreement
 */
public function ProductAgreement($productcode, $validityperiod = 12) {

    $extra = array(
        'ProductCode' => $productcode,
        'ServerCount' => '1',
        'ValidityPeriod' => $validityperiod,
        'IsUsedForTokenSystem' => 'false');
    return $this->api->common('POST','/order/agreement',array_merge($this->createAuthRequest() , $extra));
    }  

/**
 * whois Check Service https://www.thesslstore.com/api/whois-check-service
 */
public function WhoisCheck($domainname) {

    $extra = array('Domainname' => $domainname);
    return $this->api->common('POST','/whois',array_merge($this->createAuthRequest() , $extra));
    }     


/**
 * query your orders https://www.thesslstore.com/api/query-order
 */
public function listOrders() {

    $extra = array(
        'PageNumber' => "1",
        'PageSize' => "50");

    return $this->api->common('POST','/order/query',array_merge($this->createAuthRequest() , $extra));
    }   
/**
 *  SSL Server Checker tool is to provide an in-depth analysis of the SSL web server https://www.thesslstore.com/api/server-checker
 */
public function ServerChecker($hostname) {
    $extra = array(
        'HostName' => $hostname);

    return $this->api->common('POST','/ssltools/serverchecker',array_merge($this->createAuthRequest() , $extra));
    } 

/**
 * SSL CHECKER is to verify that the SSL certificate on your web server https://www.thesslstore.com/api/ssl-checker
 */
public function SSLChecker($hostname) {
    $extra = array(
        'HostName' => $hostname);

    return $this->api->common('POST','/ssltools/sslchecker',array_merge($this->createAuthRequest() , $extra));
    } 
/**
 * CAA (Certification Authority Authorization) Record Generator https://www.thesslstore.com/api/caa-record-generator
 */
public function CAAgenerator($hostname, $ca = 'Certum') {

    $extra = array(
        'HostName' => $hostname,
        'CA' => array(
            'CAName' => $ca,
            'sNonWildCardDomain' => 'false',
            'IsWildCardDomain' => 'false'));

        return $this->api->common('POST','/ssltools/caarecordgenerator',array_merge($this->createAuthRequest() , $extra));
    }   

/**
 * CSR Generator is to generate your Certificate Signing Request and Private Key  https://www.thesslstore.com/api/csr-generator
 */
public function CSRgenerator($csrdata) {

    $extra = array(
        'CommonName' => $csrdata['cn'],
        'OrganizationName' => $csrdata['o'],
        'OrganizationUnit' => $csrdata['ou'],    
        'Locality' => $csrdata['l'],
        'State' => $csrdata['st'],
        'Country' => $csrdata['c'],
        'Email' => $csrdata['email'],    
        'KeySize' => $csrdata['keysize'],
        'SignatureAlgorithm' => $csrdata['signaturealgorithm']);

        return $this->api->common('POST','/ssltools/csrgenerator',array_merge($this->createAuthRequest() , $extra));
    }   

/**
 * decode your Certificate Signing Request https://www.thesslstore.com/api/csr-decoder
 */
public function DecodeCSR($csr) {
    $csr = str_ireplace(PHP_EOL,'',$csr);
    $extra = array(
        'CSR' => $csr);

    return $this->api->common('POST','/ssltools/csrdecoder',array_merge($this->createAuthRequest() , $extra));
    }   

/**
 * get the approval list https://www.thesslstore.com/api/csr-validation-service
 */
public function ValidateCSR($productcode, $csr) {
    $csr = str_ireplace(PHP_EOL,'',$csr);
    $extra = array(
        'ProductCode' => $productcode,
        'CSR' => $csr);

    return $this->api->common('POST','/csr/',array_merge($this->createAuthRequest() , $extra));
    }   
/**
 * get the approval list https://www.thesslstore.com/api/approver-list
 */
public function ApproverList($productcode, $domainname) {
    $extra = array(
        'ProductCode' => $productcode,
        'DomainName' => $domainname);

    return $this->api->common('POST','/order/approverlist',array_merge($this->createAuthRequest() , $extra));
    }   
/**
 * Query the product list https://www.thesslstore.com/api/query-products
 */
public function getProducts($productcode,$producttype = 0) {
    $extra = array(
        'ProductCode' => $productcode,
        'ProductType' => $producttype,
	    'NeedSortedList' => 'true',
        'IsForceNewSKUs' => 'true');

    return $this->api->common('POST','/product/query',array_merge($this->createAuthRequest() , $extra));
    }   
/**
 * Validate the token https://www.thesslstore.com/api/validate-token
 */
public function ValidateToken() {
    $extra = array('Token' => '','TokenCode' => '','TokenID' => '','IsUsedForTokenSystem' => "true");
    return $this->api->common('POST','/health/validatetoken',array_merge($this->createValidateAuth() , $extra));
    }    

/**
 * Validate the credentials https://www.thesslstore.com/api/validate-credentials
 */
public function ValidateCredentials() {
    return $this->api->common('POST','/health/validate/',$this->createValidateAuth());
    }

/**
 * Get the health status of the health status https://www.thesslstore.com/api/service-status
 */
public function getServiceStatus() {
return $this->api->common('GET','/health/status');
   }

/**
 * Create Authentication with values given from customer
 */
private function createAuthRequest() {
    $this->replaytoken = $this->replaytoken  ?: $this->generateRandomString(32);

    return array('AuthRequest' => array('PartnerCode' => $this->partnercode,'AuthToken' => $this->authtoken,'ReplayToken' => $this->replaytoken));
    }
    
/**
* Create Authentication with values given from customer
*/
private function createValidateAuth() {
    
    $this->replaytoken = $this->replaytoken  ?: $this->generateRandomString(32);

    return array('PartnerCode' => $this->partnercode,'AuthToken' => $this->authtoken,'ReplayToken' => $this->replaytoken);
    }

/**
* Generate Random Strings
*/
private function generateRandomString($length = 32) {
  
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
  
    for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, $charactersLength - 1)];
      }
  return $randomString;
  }
}


?>