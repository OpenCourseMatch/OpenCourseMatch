# Info messages
To display info, warning, error or success messages, you can use the `InfoMessage` class. It's constructor takes a parameter for the message itself and the message type. The message type is an integer that is defined as static values of the `InfoMessage` class.

This is how you can display an info message:
```php
// Info message
new InfoMessage("This is an info message", InfoMessageType::INFO);

// Warning message
new InfoMessage("This is a warning message", InfoMessageType::WARNING);

// Error message
new InfoMessage("This is an error message", InfoMessageType::ERROR);

// Success message
new InfoMessage("This is a success message", InfoMessageType::SUCCESS);
```
To prevent unwanted side effects, it's recommended to only send info messages from an executed website script.

You might also want to display info messages within JavaScript code. This can be done with the JavaScript `InfoMessage` class. Its usage is almost the same as the one in PHP:
```js
// Info message
new InfoMessage("This is an info message", InfoMessage.INFO);

// Warning message
new InfoMessage("This is a warning message", InfoMessage.WARNING);

// Error message
new InfoMessage("This is an error message", InfoMessage.ERROR);

// Success message
new InfoMessage("This is a success message", InfoMessage.SUCCESS);
```

As you can modify the DOM within JavaScript, you can also delete / hide info messages. The following example shows how you can delete all info messages displayed by the backend:
```js
$(document).ready(() => {
    InfoMessage.clearMessages();
});
```
