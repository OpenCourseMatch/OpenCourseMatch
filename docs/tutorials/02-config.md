# Config
The framework provides a configuration file that allows you to configure the functions of the framework and your project.

The (static) configuration file is located in the `📁 project/config/` directory and is named `app-config.php`. It should configure settings which are the same for all environments or deployments (e.g. the project settings, time formats, Class Loader settings, ...). Those settings are typically committed to the repository.
Other settings, which might change depending on the deployment, and settings that should not be committed to the repository (e.g. database or SMTP server credentials, API keys, SEO settings, ...) can be overwritten in the `📄 secrets/config.secret.json` file. This file is included in the `📄 project/config/app-config.php` file.

## Router settings
| Field name        | Description                                                                                                                     | Default value |
|-------------------|---------------------------------------------------------------------------------------------------------------------------------|---------------|
| `ROUTER_BASE_URI` | The base URI of the project (e.g. if the project is hosted as a subdirectory of a domain, the base URI would be that directory) | `/`           |

## Project settings
| Field name        | Description                                                   | Default value             |
|-------------------|---------------------------------------------------------------|---------------------------|
| `PROJECT_NAME`    | The project's name that is displayed in the header and footer | `Project`                 |
| `WEBSITE_TITLE`   | The title that is displayed for the browser tab               | `Project`                 |
| `PROJECT_URL`     | The project's URL                                             | `http://localhost:3000`   |
| `PROJECT_FAVICON` | URL to the projects favicon                                   | `/static/img/favicon.png` |
| `PROJECT_AUTHOR`  | The author of the project that is displayed in the footer     | `Author`                  |
| `PROJECT_VERSION` | The version of the project that is displayed in the footer    | `1.0.0`                   |
| `TIMEZONE`        | The timezone that should be used by the project               | `Europe/Berlin`           |

## Time format settings
| Field name           | Description                                                 | Default value |
|----------------------|-------------------------------------------------------------|---------------|
| `DATE_TECHNICAL`     | The format of a date that is used by the backend            | `Y-m-d`       |
| `TIME_TECHNICAL`     | The format of a time that is used by the backend            | `H:i:s`       |
| `DATETIME_TECHNICAL` | The format of a date and time that is used by the backend   | `Y-m-d H:i:s` |
| `DATE_VISUAL`        | The format how a date is displayed in the frontend          | `d.m.Y`       |
| `TIME_VISUAL`        | The format how a time is displayed in the frontend          | `H:i`         |
| `DATETIME_VISUAL`    | The format how a date and time is displayed in the frontend | `d.m.Y H:i`   |

## Logger settings
| Field name         | Description                                                                         | Default value   |
|--------------------|-------------------------------------------------------------------------------------|-----------------|
| `LOG_DIRECTORY`    | The directory where the logfiles should be stored                                   | `📁 logs/`      |
| `LOG_FILENAME`     | The filename format of a logfile                                                    | `log-%date.log` |
| `LOG_LEVEL`        | The minimum importance that is required for a message to get written into a logfile | `trace` / `6`   |
| `LOG_ERROR_REPORT` | The email addresses that should receive an email when an error occurs               | `[]`            |

## Database settings
| Field name | Description                                                          | Default value   |
|------------|----------------------------------------------------------------------|-----------------|
| `DB_HOST`  | Database hostname                                                    | `database`      |
| `DB_USER`  | Database username                                                    | `framework-app` |
| `DB_PASS`  | Database password                                                    | `framework-app` |
| `DB_NAME`  | Database name                                                        | `framework-app` |
| `DB_USE`   | Whether the database connection should be established upon app start | `true`          |

## Mail settings
| Field name                   | Description                                                                                               | Default value    |
|------------------------------|-----------------------------------------------------------------------------------------------------------|------------------|
| `MAIL_SMTP_HOST`             | The hostname of the SMTP server                                                                           | `smtp.localhost` |
| `MAIL_SMTP_PORT`             | The port of the SMTP server                                                                               | `25`             |
| `MAIL_SMTP_USER`             | The username of the SMTP server                                                                           | `username`       |
| `MAIL_SMTP_PASS`             | The password of the SMTP server                                                                           | `password`       |
| `MAIL_SMTP_SECURE`           | The security protocol that should be used for the SMTP connection                                         | `tls`            |
| `MAIL_SMTP_AUTH`             | Whether the SMTP server requires authentication                                                           | `true`           |
| `MAIL_DEFAULT_SENDER_EMAIL`  | The default sender email address                                                                          | `mail@framework` |
| `MAIL_DEFAULT_SENDER_NAME`   | The default sender name                                                                                   | `Framework`      |
| `MAIL_DEFAULT_REPLY_TO`      | The default reply-to email address                                                                        | `mail@framework` |
| `MAIL_DEFAULT_SUBJECT`       | The default subject of an email                                                                           | `Framework`      |
| `MAIL_REDIRECT_ALL_MAILS`    | Whether all mails should be redirected to a specific email address for testing purposes                   | `false`          |
| `MAIL_REDIRECT_ALL_MAILS_TO` | The email address to which all mails should be redirected (if `MAIL_REDIRECT_ALL_MAILS` is set to `true`) | `mail@framework` |

> [!IMPORTANT]
> In Docker deployments, you won't be able to send emails without configuring the mail settings. For Apache web server deployments, you can work around this by configuring another mail service on the machine. Doing this will result in the email wrapper class not working as expected.

## Class Loader settings
| Field name                  | Description                                       | Default value                              |
|-----------------------------|---------------------------------------------------|--------------------------------------------|
| `CLASS_LOADER_IGNORE_FILES` | Files that should be ignored by the class loader  | Read in `📄 project/config/app-config.php` |
| `CLASS_LOADER_IMPORT_PATHS` | Paths that should be imported by the class loader | Read in `📄 project/config/app-config.php` |

## SEO settings
| Field name                            | Description                                                                  | Default value                 |
|---------------------------------------|------------------------------------------------------------------------------|-------------------------------|
| `SEO_DEFAULT_DESCRIPTION`             | The default description of the website                                       | `Description`                 |
| `SEO_KEYWORDS`                        | The keywords of the website                                                  | `[]`                          |
| `SEO_IMAGE_PREVIEW`                   | The image that is shown when the website is shared on social media platforms | `/static/img/seo/preview.png` |
| `SEO_OPENGRAPH`                       | Settings for the OpenGraph meta tags                                         |                               |
| `SEO_OPENGRAPH`.`OPENGRAPH_SITE_NAME` | The value that should be used for the `og:site_name` meta tag                | `null`                        |
| `SEO_TWITTER`                         | Settings for the Twitter meta tags                                           |                               |
| `SEO_TWITTER`.`TWITTER_SITE`          | The value that should be used for the `twitter:site` meta tag                | `null`                        |
| `SEO_TWITTER`.`TWITTER_CREATOR`       | The value that should be used for the `twitter:creator` meta tag             | `null`                        |
| `SEO_ROBOTS`                          | An array of settings for the `robots` meta tag                               | `["index", "follow"]`         |
| `SEO_REVISIT`                         | The value that should be used for the `revisit-after` meta tag               | `1 days`                      |
