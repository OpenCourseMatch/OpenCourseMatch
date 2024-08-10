# Send emails
You can use the `Mail` class, which is a wrapper for the [PHPMailer](https://github.com/PHPMailer/PHPMailer) library, to send emails. To do that, use
```php
// Initialize the mail object
$mail = new Mail();
$mail->addRecipient("RECIPIENT_EMAIL")
    ->setSubject("SUBJECT")
    ->setTextBody("MESSAGE"),
    ->send();
```
with `RECIPIENT_EMAIL` being the email address the mail should be sent to, `SUBJECT` the mail's subject and `MESSAGE` it's body. The `send` method sends the mail. In this case, a plain-text email is sent, because the HTML body wasn't set. This can be done by calling `setHtmlBody`.
There are other methods that can be used in the initialization of the mail object:
- `setSender("SENDER_EMAIL", "SENDER_NAME")`: Overrides the default (config) values for the sender details
- `setReplyTo("REPLTYTO_EMAIL", "REPLYTO_NAME")`: Overrides the default (config) values for the reply-to details
- `addRecipient("EMAIL", "NAME")`: Adds a recipient
- `addCcRecipient("EMAIL", "NAME")`: Adds a recipient to CC
- `addBccRecipient("EMAIL", "NAME")`: Adds a recipient to BCC
- `setSubject("SUBJECT")`: Sets the subject of the email
- `setHtmlBody("MESSAGE")`: Sets the HTML body of the email and indicates that an HTML email should be sent
- `setTextBody("MESSAGE")`: Sets the plain-text body of the email
- `addAttachment("FILE_PATH", "FILE_NAME")`: Adds a file attachment to the email
