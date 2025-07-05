# PHP RESTAPI sslstore client by HostingBE

HostingBE's sslstore API has many options for retrieving certificate data and create or renew certificates via your sslstore account. More options are being added all the time. This API is using the modern namespacing and you can easily install with the command below.

**Installing this API** 

`composer require hostingbe/php-restapi-sslstore`

**Capabilities of this API**

First of all, requesting different data via REST API with the answer in JSON format. If the API gets an error, it tries the same command a number of times. There is also logging functionality standard in this app.


**Integrated endpoints**
| Endpoint API | Description |
| -------------|-------------|
| getServiceStatus | Get the health status of the health status |
| ValidateCredentials | Validate the credentials |
| ValidateToken | Validate the token |
| getProducts | Query the product list |
| ApproverList | get the approval list |
| ValidateCSR | Validate a CSR |
| DecodeCSR | decode your Certificate Signing Request |
| CSRgenerator | CSR Generator is to generate your Certificate Signing Request and Private Key |
| CAAgenerator | CAA (Certification Authority Authorization) Record Generator |
| SSLchecker | SSL CHECKER is to verify that the SSL certificate on your web server |
| ServerChecker | SSL Server Checker tool is to provide an in-depth analysis of the SSL web server  |
| listOrders | query your orders |
| WhoisCheck | whois Check Service |
| ProductAgreement | Show agreements between the vendor and the customer |
| ValidateOrder | Validate Order Parameters is an immutable operation |
| InviteOrder | Short order form which sends invite link to complete order |
| NewOrder | Order a new product by passing in all details like CSR |
| CheckDCV | Use this endpoint to check the domain control validation (DCV) for a pending SSL certificate order | 
| OrderStatus | Returns the Current Status of the Order. You can also check MajorStatus and MinorStatus for orders |
| DownloadCertificate | You can download the certificate by passing in required parameters. The format of the download is generally a BASE64 |
| DownloadCertificateasZip | Download Certificates as Zip |
| WhyNoPadLock | The purpose of the Why No Pad Lock is to quickly check your URL  |
| CertificateKeyMatcher | The purpose of Certificate Key Matcher is to determine whether a private key or CSR file matches an SSL certificate |
| CertificateDecoder| The purpose of Certificate Decoder is to decode your SSL Certificate |

**Howto start with this API**

Fill in the required fields:
* partnercode
* authtoken
* replaytoken

If you omit the replay token a replay token is auto generated

```
use HostingBE\Api\SSLstore;
use HostingBE\Api\Logger\APILogger;

$partnercode = "[your partnercode]";
$authtoken = "[your token]";
$replaytoken = "[your replay token]";

$logger = (new APILogger)->create('my-api-sslstore');
$api = new SSLstore($logger, $partnercode, $authtoken, $replaytoken);
```

***Check the status of the API of SSLstore***

```
$response = $api->getServiceStatus();
```

***Get the details of product code rapidssl***

```
$response = $api->getProducts('rapidssl');
```

***Validate an order before you place an order***
```
/**
* order details
*/
$order = array(
    'productcode' => 'sectigodvucc',
    'csr'  => $csr,
    'domainname'  => 'github.com',
    'webservertype' => 'apache',
    'dnsnames' => array('github.com,www.github.com'),
    'validityperiod'  =>  '12', 
    'approveremail' => 'admin@email.com',

);
/**
* admin contact 
*/
$admin = new Contact();
$admin->setFirstName('First name');
$admin->setLastName('Last name');
$admin->setPhone('31000000000');
$admin->setFax('');
$admin->setTitle('sir');
$admin->setEmail('admin@github.com');
$admin->setOrganizationName('HostingBE');
$admin->setAddressLine1('my address');
$admin->setAddressLine2('');
$admin->setCity('my city');
$admin->setRegion('my state');
$admin->setPostalCode('my zipcode');
$admin->setCountry('NL');

$admincontact = $admin->getContact();

/**
* copy admin to tech or create a seperate one for tech
*/
$techcontact = $admincontact;

$org = new Contact();
$org->setAddressLine1('my address');
$org->setAddressLine2('');
$org->setAddressLine3('');
$org->setCity('my city');
$org->setRegion('my state');
$org->setPostalCode('my zipcode');
$org->setCountry('NL');
$org->setPhone('31000000000');
$org->setFax('');
$org->setLocalityName('');
/**
* organisation details 
*/
$organization = $org->getOrganization();

/**
* send request to SSLstore
*/
$response = $api->ValidateOrder($admincontact, $techcontact, $organization, $order);
```