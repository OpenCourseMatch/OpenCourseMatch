# JSON responses
You can send the user an individual JSON response by using
```php
Comm::sendJson(DATA);
```
with `DATA` being an array that should be JSON-encoded.

If you're developing an API, you might want to send a status code and message along with the result. This can be achieved easily by using
```php
Comm::apiSendJson(RESPONSE, DATA);
```
Both `RESPONSE` AND `DATA` are arrays. `DATA` holds the main content that should be returned. `RESPONSE` holds the status code and message and should be formatted as shown in the following scheme:
```php
[
    "code" => STATUS_CODE,
    "message" => "STATUS_MESSAGE"
]
```
There are predefined response arrays located in the `HTTPResponses` class. It contains the most relevant HTTP responses (`200`, `201`, `204`, `400`, `401`, `403`, `404`, `405`, `500`, `501`, `503`) that are also common to occur in API usage.

The returned JSON response will look like this:
```json
{
    "code": STATUS_CODE,
    "message": "STATUS_MESSAGE",
    "data": DATA
}
```
> [!NOTE]
> The `data` field is a JSON object if the passed `DATA` array is an associative array and a JSON array if it is a sequential array.

You can use those responses with `apiSendJson` as shown in the following example:
```php
$userDAO = User::dao();
Comm::apiSendJson(HTTPResponses::$RESPONSE_OK, $userDAO->getObjects());
```
This will return roughly the following response (`data` will hold the information about all users of course):
```json
{
    "code": 200,
    "message": "OK",
    "data": [
        ...
    ]
}
```
Because `$userDAO->getObjects()` returns a sequential array, the `data` field in the JSON response is a JSON array.<br>
> [!CAUTION]
> You wouldn't want this to be a real API call since it will return <b>ALL</b> information about **every** user from the database such as real names, password hashes, ...
