# PHP RESTAPI sslstore client by HostingBE

HostingBE's sslstore API has many options for retrieving certificate data and create or renew certificates via your sslstore account. More options are being added all the time. This API is using the modern namespacing and you can easily install with the command below.

**Installing this API** 

`composer require hostingbe/php-restapi-sslstore`

**Capabilities of this API**

First of all, requesting different data via REST API with the answer in JSON format. If the API gets an error, it tries the same command a number of times. There is also logging functionality standard in this app.


**Integrated endpoints**
| getServiceStatus ||
| VAidateCredentials ||
| ValidateToken ||
| getProducts ||
| ApproverList ||
| ValidateCSR ||
| DecodeCSR ||
| CSRgenerator ||
| CAAgenerator ||
| SSLchecker ||
| ServerChecker ||
| listOrders ||
| WhoisCheck ||
| ProductAgreement ||
| ValidateOrder ||
| NewOrder ||
| CheckDCV | | 
| OrderStatus | |
| DownloadCertificate ||
| DownloadCertificateasZip ||
| WhyNoPadLock ||
| CertificateKeyMatcher ||
| CertificateDecoder| |

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