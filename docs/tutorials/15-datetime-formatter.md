# Datetime formatter
To ensure uniformity, there is a class called `DateTimeFormatter` that can be used to format and parse datetimes. The used format can be changed in the `📄 project/config/app-config.php` file.

To format a datetime to display the current date and time in the frontend, you can use the following code:
```php
$datetime = new DateTime();
$formattedDate = DateFormatter::visualDateTime($datetime);
```

In case you want to format a datetime to a date that should be passed to other components of your project (e.g. a JavaScript file), use the following code:
```php
$datetime = new DateTime();
$formattedDate = DateFormatter::technicalDate($datetime);
```

You can also parse a datetime string to a datetime object:
```php
$datetime = new DateTime();
$formattedDate = DateFormatter::visualDateTime($datetime);
$newDatetime = DateFormatter::parseVisualDateTime($formattedDate);
```
