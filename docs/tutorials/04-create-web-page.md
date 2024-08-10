# Create a new web page
To create a new page for the website, create a new PHP file that should be executed when the user visits the page. The file should be located in the `📁 project/htdocs/` directory. There are no limitations what you can do in the script, but it's not recommended to output HTML code or other content directly (exception: you want to send JSON responses, please take a look at the corresponding tutorial for JSON responses).

Instead, to output content, create a BladeOne template file in the `📁 project/frontend/` directory or a subdirectory. A template file contains the rendering instructions for a page. This separation not only takes care of a better overview, it also separates the logic from the view. For further information about template files, please refer to the [BladeOne documentation](https://github.com/EFTEC/BladeOne).

Have a look at the following example:

`📄 project/htdocs/example.php`:
```php
<?php

// Assign a variable
$variable = "Hello World!";

// Load the template
Blade->run("example", ["variable" => $variable]);
```
This file is the script that gets executed when the user visits the page. In this example, a variable is assigned and the template `example.blade.php` is loaded.

`📄 project/frontend/example.blade.php`:
```bladehtml
@component("components.layout.appshell", ["title" => "Example"])
    {{ $variable }}
@endcomponent
```
This is the template file. It sets a website title and includes the app shell component which contains the HTML head contents, and a generic body with header and footer. The variable that was assigned in the script is then outputted, HTML content is escaped by default.
