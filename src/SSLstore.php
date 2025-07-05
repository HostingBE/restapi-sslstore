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
 * The purpose of Certificate Decoder is to decode your SSL Certificate https://www.thesslstore.com/api/certificate-decoder
 */
public function CertificateDecoder(string $crt) {

    $extra = array(
        'Certificate' => $crt
        );
 
        return $this->api->common('POST','/ssltools/certdecoder',array_merge($this->createAuthRequest() , $extra));
}  

/**
 * The purpose of Certificate Key Matcher is to determine whether a private key or CSR file matches an SSL certificate https://www.thesslstore.com/api/certificate-key-matcher
 */
 public function CertificateKeyMatcher(string $crt, string $csr, string $private) {

    $extra = array(
        'Certificate' => $crt,
        'CSR' => $csr,
        'PrivateKey' => $private 
        );
 
        return $this->api->common('POST','/ssltools/certkeymatcher',array_merge($this->createAuthRequest() , $extra));
}  

/**
 * The purpose of the Why No Pad Lock is to quickly check your URL https://www.thesslstore.com/api/why-no-pad-lock
 */

 public function WhyNoPadLock(string $url) {

    $extra = array(
        'URL' => $url);
 
        return $this->api->common('POST','/ssltools/whynopadlock',array_merge($this->createAuthRequest() , $extra));
     }  

/**
 * Download Certificate as Zip https://www.thesslstore.com/api/download-certificate-as-zip
 */

 public function DownloadCertificateasZip(string $orderid) {

    $extra = array(
        'TheSSLStoreOrderID' => $orderid,
        'ReturnPKCS7Cert' => 'true',
        'DateTimeCulture' => 'en-UK',
        'PlatFormId' => 'apache',
        'FormatType' => '');
 
     return $this->api->common('POST','/order/downloadaszip',array_merge($this->createAuthRequest() , $extra));
     }       

/**
 * Certificate Revoke Request https://www.thesslstore.com/api/certificate-revoke-request
 */
 public function CertificateRevokeRequest(string $orderid, string $ourorder, string $revokereason) {
    
    $extra = array(
     'CustomOrderID' => $ourorder,
     'TheSSLStoreOrderID' => $orderid,
     'RevokeReason' => $revokereason
      );

  return $this->api->common('POST','/order/certificaterevokerequest',array_merge($this->createAuthRequest() , $extra));
 }

/**
 * cancel order or refund order https://www.thesslstore.com/api/refund-request
 */
 public function RefundOrder(string $orderid, string $ourorder, string $refundreason) {
    
    $extra = array(
     'CustomOrderID' => $ourorder,
     'TheSSLStoreOrderID' => $orderid,
     'ResendEmailType' => '',
     'ResendEmail' => '',
     'RefundReason' => $refundreason,
     'RefundRequestID' => $ourorder,
     'ApproverMethod'=> '',
     'DomainNames' => ''
      );

  return $this->api->common('POST','/order/refundrequest',array_merge($this->createAuthRequest() , $extra));
 }

/**
 * You can download the certificate by passing in required parameters. The format of the download is generally a BASE64 https://www.thesslstore.com/api/download-certificate
 */

 public function DownloadCertificate(string $orderid) {

    $extra = array(
        'TheSSLStoreOrderID' => $orderid,
        'ReturnPKCS7Cert' => 'false',
        'DateTimeCulture' => 'en-UK',
        'PlatFormId' => 'apache',
        'FormatType' => '');
 
     return $this->api->common('POST','/order/download',array_merge($this->createAuthRequest() , $extra));
     }  

/**
 * Returns the Current Status of the Order. You can also check MajorStatus and MinorStatus for orders  https://www.thesslstore.com/api/order-status
 */

 public function OrderStatus(string $orderid,string $ourorder) {

    $extra = array(
     'TheSSLStoreOrderID' => $orderid,
     'ResendEmailType' => '',
     'ResendEmail' => '',
     'RefundReason' => '',
     'RefundRequestID' => '',
     'ApproverMethod' => '',
     'DomainNames'=> '',
     'DateTimeCulture' => '',
     'PlatFormId' => '-2',
     'FormatType' => '',
     'CustomOrderID' => $ourorder);
 
     return $this->api->common('POST','/order/status',array_merge($this->createAuthRequest() , $extra));
     }  

/**
 * Use this endpoint to check the domain control validation (DCV) for a pending SSL certificate order  https://www.thesslstore.com/api/check-dcv
 */

 public function CheckDCV(string $orderid,string $domainname,string $ourorder) {

   $extra = array(
	'TheSSLStoreOrderID' => $orderid,
	'DomainName' => $domainname,
	'CustomOrderID' => $ourorder);

    return $this->api->common('POST','/digicert/checkdcv/',array_merge($this->createAuthRequest() , $extra));
    }  

/**
 * This is an alternate way of ordering when you don’t want to provide all the CSR, etc. steps on your front-end. With Invite Order https://www.thesslstore.com/api/invite-order
 */
public function InviteOrder(array $order) :object {

    $extra = array(
    'PreferVendorLink' => 'false',
	'ProductCode'=> $order['productcode'],
	'ExtraProductCode' => '',
	'ServerCount' => '1',
	'RequestorEmail' => $order['approveremail'],
	'ExtraSAN' => '4',
	'CustomOrderID' => $order['ordernr'],
	'ValidityPeriod' => $order['validityperiod'],
    'AddInstallationSupport' => 'false',
	'EmailLanguageCode' => 'EN',
	'PreferSendOrderEmails' => 'true',
	'OrganizationIds ' => [],
	'IsWildcardCSRDomain' =>  'true',
	'ExtraWildcardSAN'=> '0');

     return $this->api->common('POST','/order/inviteorder',array_merge($this->createAuthRequest() , $extra));
     }  

/**
 * Order a new product by passing in all details like CSR https://www.thesslstore.com/api/new-order
 */
public function NewOrder(array $admincontact,array $techcontact, array $organization, array $order, string $ValidationMethod) {

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
	'CSR' => "$csr",
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
    'CNAMEAuthDVIndicator' => 'false',
    'HTTPSFileAuthDVIndicator' => 'false',     
	'SignatureHashAlgorithm' => 'SHA2-512'
     );


   $extra = array_merge($extra,$this->validation($ValidationMethod));
   $extra = array_merge($extra, $this->checkSAN($order['dnsnames']));

   return $this->api->common('POST','/order/neworder',array_merge($this->createAuthRequest() , $extra));
    }  

    /**
 * Validate Order Parameters is an immutable operation https://www.thesslstore.com/api/validate-order-parameters
 */
public function ValidateOrder(array $admincontact,array $techcontact, array $organization, array $order, string $ValidationMethod) {

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
	'AdminContact' =>  $admincontact,
	'TechnicalContact' => $techcontact,
	'ApproverEmail' => $order['approveremail'], 
    'AddInstallationSupport' => 'false',
	'EmailLanguageCode' => 'en', 
    'FileAuthDVIndicator' => 'false',
    'CNAMEAuthDVIndicator' => 'false',
    'HTTPSFileAuthDVIndicator' => 'false',
	'SignatureHashAlgorithm' => 'SHA2-256'
     );
    
    $extra = array_merge($extra,$this->validation($ValidationMethod));

    $extra = array_merge($extra, $this->checkSAN($order['dnsnames']));
    
    // print_r(array_merge($this->createAuthRequest() , $extra));exit;

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
 *
 * Type of products. ALL=0,DV=1,EV=2,OV=3,WILDCARD=4,SCAN=5,SAN_ENABLED=7,CODESIGN=8,DC_SMIME=11,DC_DOCSIGN=12
 * 
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
   return array('AuthRequest' => array('PartnerCode' => $this->partnercode,'AuthToken' => $this->authtoken,'ReplayToken' => $this->replaytoken, 'IsUsedForTokenSystem' => 'false'));
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
    // return rawurlencode($csr);
    return str_ireplace(PHP_EOL,'',$csr); 
}

private function validation($ValidationMethod) {
    array('CNAMEAuthDVIndicator','HTTPSFileAuthDVIndicator','FileAuthDVIndicator');
    if (in_array($ValidationMethod, array('CNAMEAuthDVIndicator','HTTPSFileAuthDVIndicator','FileAuthDVIndicator'))) {
       return array($ValidationMethod => 'true');
       }
    throw new \Exception("invalid Validation Method received stopping!");
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