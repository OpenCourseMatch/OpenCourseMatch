# Translate messages
You can translate messages within your application by using the `t` method, wich internally uses the `Translator` class.

## Translation files
The strings that are used in the application should be defined in the `.json` translation files within the `📁 project/translations/` directory. This directory is again separated into subdirectories - one for each locale. When cloning the repository initially, you should have the locales `en_US` and `de_DE`.
You can create other locales simply by creating a new directory with the locale name, and then creating the `.json` files for the strings.

By default, the locale is automatically detected from the clients `Accept-Language` header. If the locale is not available, the default locale is used.
This behaviour is customizable by configuring the `TranslationUtil::getPreferredLocale` method. There, you could implement that the locale is detected by the IP address, from a parameter within the URL, by a cookie or by any other method.

Each locale directory can contain multiple translation files, with the filename being the domain. By default, there is only one domain called "messages", so you can see the file `📄 project/translations/en_US/messages.json` for the English messages.
The domain could be switched by using the `Translator::setDomain` method.

Your translation files should be formatted like this:
```json
{
    "Hello, world!": "Hello, world!",
    "This is an example.": "This is an example."
}
```
You might also want to include parameters into the strings, by encapsulating the parameter name with two dollar signs (`$$`):
```json
{
    "Hello, $$name$$!": "Hello, $$name$$!",
    "It is $$weekday$$.": "It is $$weekday$$."
}
```

## Translate in PHP
After adding a string to a translation file, you can use it in your template files or backend scripts with the `t` method:
```bladehtml
<p>
    {{ t("Hello, world!") }}
</p>
```
```php
$aTranslatedString = t("This is an example.");
```

A string can also include parameters, which are surrounded by two dollar signs (`$$`):
```bladehtml
<p>
    {{ t("Hello, $$name$$!", [ "name" => $name ]) }}
</p>
```
```php
$aTranslatedStringWithParameters = t("It is \$\$wekkday\$\$", [
    "weekday" => $weekday
]);
```

## Translate in JavaScript
You might also find yourself in the need to translate strings in JavaScript. To achieve this, there is also a JavaScript method, `t`, which has the same syntax as the PHP method, with the difference that it runs asynchronously, therefore it requires you to `await` for the response.
The reason for this asynchronous translation is that the method sends an AJAX request to the server which prompts the server to translate the string and send it back to the client:
```js
console.log(await t("Hello, world!"));
```
