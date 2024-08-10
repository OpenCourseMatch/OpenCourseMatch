# Cronjobs
To create a new cronjob, create a new (PHP or any other executable) file in the `📁 project/cronjobs/` directory. The file should contain the code that should be executed when the cronjob is called. If you're writing a PHP script, make sure to include the `📄 project/cronjobs/.cronjob-setup.php` file at the beginning of the script, which allows you to use the framework's features.

Automatically registering cronjobs is only possible when deploying the application in form of a Docker container. To register the cronjob, add a new entry to the `📄 project/cronjobs/app-cronjobs.php` file with the crontab syntax:
```
┌───────────── minute (0 - 59)
│ ┌─────────── hour (0 - 23)
│ │ ┌───────── day of the month (1 - 31)
│ │ │ ┌─────── month (1 - 12)
│ │ │ │ ┌───── day of the week (0 - 6) (Sunday to Saturday)
│ │ │ │ │
* * * * * command to be executed
```
