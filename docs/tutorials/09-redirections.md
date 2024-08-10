# Redirections
To redirect the user to another page or website, you can use the `Comm` class' `Comm::redirect(String $path)` method. It sets the `Location` header to the given path and stops the execution of the script.

The following example shows how to redirect to another page of your website:
```php
Comm::redirect(Router::generate("ROUTE"));
```
It uses the `Router::generate(String $route)` method to generate the path to the given route automatically.

To redirect to another website, you can simply provide the URL instead of generating a route:
```php
Comm::redirect("https://www.example.com");
```
