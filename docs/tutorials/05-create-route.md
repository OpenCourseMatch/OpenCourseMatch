# Create a route for a web page
To create a route for a page, you have to add a new entry to the `📄 project/config/app-routes.php` file. In that file you can already see some examples that use the `Router::addRoute` method to register a new route. The function takes the following parameters:
- `$method` - The HTTP method(s) that should be allowed to access the route

  Multiple methods can be specified by separating them with a pipe (`|`) character. For example: `GET|POST`
- `$route` - The URI that should be used to access the route

  GET parameters can be added to the route by using the following syntax: `{type:name}`

  Supported types are `b` (boolean), `d` (date (without time)), `f` (float), `i` (integer), and `s` (string).

  The name of the parameter is used to identify the parameter within the `$_GET` array.
- `$routeTo` - The PHP file that should be executed when the route is accessed

  The file should be located in the `📁 project/htdocs/` directory.
- `$name` - A unique name for the route

  The name is used to generate the URI for the route.

Assumed you already have a script called `📄 example.php` in the `📁 project/htdocs/` directory (as described in the previous tutorial about setting up a new page), you can use the following code example to add a route for that page:
```php
Router::addRoute("GET|POST", "/example", "example.php", "example");
```
This will allow you to access the page with the URI `/example` with either the `GET` or `POST` method.

Let's have a look at a more complex example: Assumed you've had a script (`api.php`) that represents an API call that requires passing a `GET` parameter called `id` of the integer type. You can add a route for this script as shown in the following code:
```php
Router::addRoute("GET", "/api/{i:id}", "api.php", "api");
```
This will allow you to access the page by the URI `/api/ID` with `ID` being the integer value of the `id` parameter of the `GET` method. The `id` parameter can then be accessed within the `📄 api.php` script by using the `$_GET` array like this:
```php
$id = $_GET["id"];
```

After adding the route to the `📄 project/config/app-routes.php`, you can generate the URI for the route in your template files (or if required, also in the backend scripts) by using the `Router::generate` method. This method takes the route name as specified in the `Router::addRoute` method and an array of parameters that should be bound into the URI:
```php
Router::generate("api", [
    "id" => 42
]);
```
This will return the URI `/api/42` which can be used in an `<a>` tag or similar.
