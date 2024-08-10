# Logger
The logger is a helpful tool for developing and maintaining a project with the framework. It allows you to write messages of different importance levels to a logfile. This can be useful for debugging or monitoring the application.

There are the following log levels:
- `trace`
- `debug`
- `info`
- `warn`
- `error`
- `fatal`

You can write a message to the logfile by using
```php
Logger::getLogger("service-name")->trace("MESSAGE");
Logger::getLogger("service-name")->debug("MESSAGE");
Logger::getLogger("service-name")->info("MESSAGE");
Logger::getLogger("service-name")->warn("MESSAGE");
Logger::getLogger("service-name")->error("MESSAGE");
Logger::getLogger("service-name")->fatal("MESSAGE");
```
The `service-name` is the name of the system component that is writing the log message. With this field, you can identify the origin of the log message in the logfile, or you can filter the log messages by the service name.

> [!NOTE]
> Log entries are only written to the logfile if the log level of the message is higher than the log level specified in the logger configuration, under `LOG_LEVEL`.

In the configuration file, you can specify the minimum required log level for a message to be written to the logfile, as well as the directory where the logfiles should be saved and the filename format.

> [!CAUTION]
> For Docker deployments, changing the log directory is discouraged, as that might break preconfigured directory mappings.
