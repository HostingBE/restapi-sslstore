<?php



/**
* @author Constan van Suchtelen van de Haere <constan.vansuchtelenvandehaere@hostingbe.com>
* @copyright 2024 HostingBE
*
* Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation
* files (the “Software”), to deal in the Software without restriction, including without limitation the rights to use, copy,
* modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software
* is furnished to do so, subject to the following conditions:

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
* THE SOFTWARE IS PROVIDED “AS IS”, WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
* OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS
* BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF
* OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*
*/

namespace HostingBE\Api;

use HostingBE\Api\Logger\Logger;
use HostingBE\Api\baseAPI;
use HostingBE\Api\Contact;
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
 * Order a new product by passing in all details like CSR https://www.thesslstore.com/api/new-order
 */
public function NewOrder(Contact $admincontact,Contact $techcontact, Contact $organization, $order) {
    
    $csr = $this->cleanCSR($order['csr']);
    
    $extra = array(
        'CustomOrderID' => '',
        'ProductCode' => $order['productcode'], 
        'ExtraProductCodes' => '', 
        'OrganizationInfo' => array(
            'OrganizationName' => '', 
            'DUNS' => '', 
            'Division' => '', 
            'IncorporatingAgency' => '', 
            'RegistrationNumber' => '', 
            'JurisdictionCity' => '', 
            'JurisdictionRegion' => '', 
            'JurisdictionCountry' => '', 
            'OrganizationAddress' => $organization,
        ),
    'ValidityPeriod' => $order['validityperiod'],
	'ServerCount' => '1',
	'CSR' => $csr,
	'DomainName' => $order['domainname'], 
	'WebServerType' => $order['webservertype'], 
	'isCUOrder' => 'false',
	'isRenewalOrder' => 'false',
	'SpecialInstructions' => 'Requested via API', 
	'RelatedTheSSLStoreOrderID' =>  '', 
	'isTrialOrder' => 'true',
	'AdminContact' => $admincontact,
	'TechnicalContact' => $techcontact,
	'ApproverEmail' => $order['approveremail'], 
	'AddInstallationSupport' => 'false',
	'EmailLanguageCode' => 'en', 
	'FileAuthDVIndicator' => 'false',
	'CNAMEAuthDVIndicator' => 'true',
	'HTTPSFileAuthDVIndicator' => 'true',
	'SignatureHashAlgorithm' => 'SHA2-512'
     );

    $extra = array_merge($extra, $this->checkSAN($order['dnsnames']));

    return $this->api->common('POST','/order/neworder',array_merge($this->createAuthRequest() , $extra));
    }  

    /**
 * Validate Order Parameters is an immutable operation https://www.thesslstore.com/api/validate-order-parameters
 */
public function ValidateOrder(Contact $admincontact,Contact $techcontact, Contact $organization, $order) {
    
    $csr = $this->cleanCSR($order['csr']);
    
    $extra = array(
        'CustomOrderID' => '',
        'ProductCode' => $order['productcode'], 
        'ExtraProductCodes' => '', 
        'OrganizationInfo' => array(
            'OrganizationName' => '', 
            'DUNS' => '', 
            'Division' => '', 
            'IncorporatingAgency' => '', 
            'RegistrationNumber' => '', 
            'JurisdictionCity' => '', 
            'JurisdictionRegion' => '', 
            'JurisdictionCountry' => '', 
            'OrganizationAddress' => $organization,
        ),
    'ValidityPeriod' => $order['validityperiod'],
	'ServerCount' => '1',
	'CSR' => $csr,
	'DomainName' => $order['domainname'], 
	'WebServerType' => $order['webservertype'], 
	'isCUOrder' => 'false',
	'isRenewalOrder' => 'false',
	'SpecialInstructions' => 'Requested via API', 
	'RelatedTheSSLStoreOrderID' =>  '', 
	'isTrialOrder' => 'true',
	'AdminContact' => $admincontact,
	'TechnicalContact' => $techcontact,
	'ApproverEmail' => $order['approveremail'], 
	'AddInstallationSupport' => 'false',
	'EmailLanguageCode' => 'en', 
	'FileAuthDVIndicator' => 'false',
	'CNAMEAuthDVIndicator' => 'true',
	'HTTPSFileAuthDVIndicator' => 'true',
	'SignatureHashAlgorithm' => 'SHA2-512'
     );

    $extra = array_merge($extra, $this->checkSAN($order['dnsnames']));


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
public function CAAgenerator($hostname, $ca) {

    $extra = array(
        'HostName' => $hostname,
        'CA' => [[
            'CAName' => $ca,
            'sNonWildCardDomain' => 'false',
            'IsWildCardDomain' => 'false']]);

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
    $csr = $this->cleanCSR($csr);
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
 * Check if multiple domainnames are passed
 */
private function checkSAN($san) {
 
$sanArr = array();

if (count($san) > 1) {
    $sanArr['DNSNames'] = $san;
    $sanArr['ReserveSANCount'] = '10';
 }
return $sanArr;   
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
 * remove returns from CSR
 */
private function cleanCSR($csr) {
    return str_ireplace(PHP_EOL,'',$csr); 
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