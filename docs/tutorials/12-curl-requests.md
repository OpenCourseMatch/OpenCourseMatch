# cURL requests
You can use the [Curl-Adapter](https://github.com/JensOstertag/curl-adapter) libraries `Curl` class to send HTTP GET or POST requests to other servers. The library is a wrapper for PHPs cURL methods.

The following example code shows how to send a GET request to read an HTML page:
```php
use jensostertag\Curl\Curl;

$curl = new Curl();

// Define the request and headers
$curl->setUrl("URL");
$curl->setMethod(Curl::$METHOD_GET);
$curl->addHeader([
    "accept" => "text/html, application/xhtml+xml"
]);

// Get the response
$response = $curl->execute();
$responseCode = $curl->getHttpCode();
$curl->close();
```
with `URL` being the URL of the server that you want to send the request to.

As a more complex example, let's assume you want to send a POST request to a server that requires a data body. You can do that as follows:
```php
use jensostertag\Curl\Curl;

$curl = new Curl();

// Define the request and headers
$curl->setUrl("URL");
$curl->setMethod(Curl::$METHOD_POST);
$curl->addHeader([
    "accept" => "application/json"
]);

// Add data to the request
$curl->addPostData([
    "key" => "value"
]);

// Get the response
$response = $curl->execute();
$responseCode = $curl->getHttpCode();
$curl->close();
```
Here, the `URL` is also replaced by the URL of the server that you want to send the request to.
