<?php


/**
* @author Constan van Suchtelen van de Haere <constan.vansuchtelenvandehaere@hostingbe.com>
* @copyright 2024 HostingBE
*
*/

namespace HostingBE\Api;

use Psr\Log\LoggerInterface;

use App\coreAPI;

class baseAPI extends coreAPI {

protected $codes = array('404','500','501','502','503');

public function __construct(LoggerInterface $logger) {
    parent::__construct($logger);
}

/**
*  Common API interface handling the get and post requests
*/
public function common(string $method, string $command, array $params = []) :object {
        
        $method = strtolower($method);

        $this->checkmethod($method);

        if ($method == 'get') {
        $response = $this->getUri($method, $command.$this->paramstostring($params),['headers' => ['Content-Type' => 'application/json']]);     
        }
        if ($method == "post") {
        $response = $this->getUri($method, $command, ['headers' => ['Content-Type' => 'application/json'], 'body' => json_encode($params,JSON_UNESCAPED_SLASHES)]);   
        }
        return $response;
}
/**
 *  check the method
 */
protected function checkmethod($method) {
    
    if (!in_array($method, array('get','post'))) {
        throw new \Exception("invalid method received!");
    }
}
/**
 *  create the get extra parameters string
 */
protected function paramstostring($params) {
$paramsstr = '';

if (count($params)!= 0) {
$paramsstr = "/".implode("/",$params);
}
return $paramsstr;
}

/**
* Decode output JSON to an object
*/
protected function output($output) {
        return json_decode($output);
}

/**
* Get the requested URI and return the response
*/
protected function getUri(string $method, string $uri,array $form = array()) :object {
       $response = $this->client->request($method, $this->baseUri . $uri, $form);

       $response->getBody()->rewind();

       if (in_array($response->getStatusCode(),$this->codes)) {
       return (object) array('code' => $response->getStatusCode(), 'message' => $response->getReasonPhrase()); 
       } else {
       return (object) array('code' => $response->getStatusCode(), 'message' => $response->getReasonPhrase(),'data' => $this->output($response->getBody()->getContents()));
       }
    }
}
?>