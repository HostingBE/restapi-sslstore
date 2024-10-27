# PHP RESTAPI sslstore client by HostingBE

HostingBE's sslstore API has many options for retrieving certificate data and create or renew certificates via your sslstore account. More options are being added all the time. This API is using the modern namespacing and you can easily install with the command below.

**Installing this API** 

`composer require hostingbe/php-restapi-sslstore`

**Capabilities of this API**

First of all, requesting different data via REST API with the answer in JSON format. If the API gets an error, it tries the same command a number of times. There is also logging functionality standard in this app.

**Howto start with this API**

```
use HostingBE\App\SSLstore;
use HostingBE\App\Logger\APILogger;

$partnercode = "[your partnercode]";
$authtoken = "[your token]";
$replaytoken = "[your replay token]";

$logger = (new APILogger)->create('my-api-sslstore');
$api = new SSLstore($logger, $partnercode, $authtoken, $replaytoken);
```

Check the status of the API of SSLstore

````
$response = $api->getServiceStatus();
```

Get the details of product code rapidssl

```
$response = $api->getProducts('rapidssl');
```



