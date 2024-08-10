<div align="center">

<!--![Header](docs/img/header.jpg)-->

# PHP-Framework

### Powerful and feature-rich PHP framework designed to simplify web development

Built-in support for data access and manipulation, routing and various other utilities make it easier to handle common web development tasks.

[Introduction](#introduction) • [Project setup](#project-setup) • [Documentation](#documentation) • [Dependencies](#dependencies) • [License](#license)

</div>

<hr>

## Introduction

This framework is designed to simplify web development by providing a scalable architecture and a set of useful features that are often needed when developing web applications. The most notable features are:
- **GitHub Actions pipeline** to automatically build, test and deploy the application
- The **router** which allows to define specific routes for the website
- **Template files** to strictly separate logic from view - as intended by the MVC pattern
- The **data access object pattern** allows to easily access and manipulate data in the database by using objects (and inheritance)
- A **safe** and **ready-to-use** user management (and login) system which can also handle multiple account types
- A **translator** which helps you to internationalize your web application
- Methods which help to **design RESTful APIs**
- **Info messages** that provide a simple way to display info, warning, error and success messages to the user from PHP and JavaScript
- Accessible **SEO settings** which define how the website should be displayed by search engines and social media platforms

### Running a development environment

To run a development environment, you can use
```sh
composer run dev
```
which will build and run a Docker container with the application and a MySQL databse.
You can then access the website on your browser by visiting `http://localhost:3000`.

> [!IMPORTANT]
> If you don't have the Docker-Compose plugin (`docker compose`) installed on your system and instead use `docker-compose`, you have to change the syntax of the executed command in the `📄 composer.json` file.

### Deployment options
Applications which where built with this framework can be deployed either in form of a Docker container or directly to an Apache web server.

The `📄 docker-compose.yml` file provides a Docker Compose infrastructure, which includes the application itself, a MySQL database and a backup service that creates a backup of the database every four hours, by using the `mysqldump` command.

### Prerequisites
When deploying the application, the following prerequisites have to be met:

For Docker deployment,
- Docker and ideally also Docker Compose need to be installed
- The container has to be accessible from outside (e.g. through port mapping, which is already configured, or a reverse proxy)

For deployments to a web server,
- The server has to run PHP 8.3 or higher
- A MySQL or MariaDB database has to be available (if it's required by the application)

> [!TIP]
> It's recommended to use the Docker deployment, as it's much easier to set up and to maintain because everything is already preconfigured. Also, some of the frameworks features such as the automatic crontab setup or the database backup service are exclusive to the Docker deployment option and, if you want to use them on a web server, you'd have to set them up manually.

## Project setup

### Repository setup
- Use this repository as a template for your project by clicking on the green button "Use this template" on the top of this page. This will create a copy of the repository in your account.
- Under the repository's `Settings` > `Actions` > `General`, change the workflow permissions from "Read repository contents and packages permissions" to "Read and write permissions" to allow the pipeline to add the newly built package to the GitHub Container Registry.
- Create repository variables and secrets for GitHub Actions:

  | Variable name          | Description                                                         |
    |------------------------|---------------------------------------------------------------------|
  | `AUTODEPLOY_ACTIVATED` | Whether or not the automatic deployment feature should be activated |
  | `AUTODEPLOY_BASE_URL`  | The base URL of the website                                         |

  | Secret name                | Description                                                                                          |
    |----------------------------|------------------------------------------------------------------------------------------------------|
  | `AUTODEPLOY_AUTH_USERNAME` | The username that is used to authenticate when calling the website (if not required, leave it empty) |
  | `AUTODEPLOY_AUTH_PASSWORD` | The password that is used to authenticate when calling the website (if not required, leave it empty) |

### Local setup
- Clone the newly created repository onto your local machine.
- Configure the `📄 composer.json` file according to your needs: Change the project's name, description and license, and add required dependencies. If you don't have the Docker Compose plugin installed, but instead use `docker-compose`, you have to change the syntax of the `dev` command.
- Configure `📄 project/config/app-config.php` according to your needs, except for secrets such as database credentials. They are treated in separate, non-tracked configuration files.
- Configure `📄 secrets/config.secret.json.example` with the same values as in the previous step, so **don't** include secrets such as database credentials in this file as well. They are treated in the copies of this file, wich won't be tracked by git.
- Copy `📄 secrets/config.secret.json.example` to `📄 secrets/config.secret.json` and configure it according to your needs. This file is ignored by the `.gitignore` file and therefore not included in the repository. You can also store secrets such as database credentials in this file. It's recommended to remove all fields that are already defined in `📄 project/config/app-config.php` and should not change from this file.
- Configure `📄 htdocs/deployment/deploy-config.json` according to your needs.
- Commit and push the changes to the repository.

### Deployment setup - Docker
- Create and configure the `📄 docker-compose.yml` file according to your requirements. The easiest way to do this is by simply cloning the newly created repository onto the server that the Docker container should run on, and then configuring it.
    - Change the `image` name / link
    - Change the access credentials for the database
- Copy `📄 secrets/config.secret.json` from your local setup to the `📁 secrets/` directory on the server, or configure the file directly on the server, depending on your needs.
    - Change the database access credentials, as configured in the `📄 docker-compose.yml` file
- Run `docker-compose up -d` to start the container. This will pull the image from the GitHub Container Registry and start the container.

### Deployment setup - Apache web server
The deployment to an Apache web server is a bit more complex than the Docker deployment, as you have to set up the server yourself. However, the following steps will guide you through the process:
- Clone the newly created repository onto the web server, e.g. to `/var/www/your-project-name`. This should be done as the web server user, or alternatively, you can set the files' owner afterward through `chown` command.
- Copy `📄 secrets/config.secret.json` from your local setup to the `📁 secrets/` directory on the server, or copy the `📄 secrets/config.secret.json.example` file to `📄 secrets/config.secret.json` and configure it according to the servers needs.
- Run `composer install` within the repository directory to install the required dependencies.
- Set up a virtual host for the website. The `DocumentRoot` should point to the directory where you've cloned the repository to, and then `📁 htdocs/` <sub>Not `📁 project/htdocs/`!</sub>. If you've used the example path from above, the `DocumentRoot` should be set to `/var/www/your-project-name/htdocs`.

## Documentation
There are many helpful tutorials and guides available [here](docs/tutorials). They cover a wide range of topics, from the frameworks basics, setting up a project, using the frameworks features up to deploying the application.

## Dependencies
This framework contains the following dependencies:
- **BladeOne** - GitHub: [EFTEC/BladeOne](https://github.com/EFTEC/BladeOne), licensed under [MIT license](https://github.com/EFTEC/BladeOne/blob/master/LICENSE)
- **TailwindCSS** - GitHub: [tailwindlabs/tailwindcss](https://github.com/tailwindlabs/tailwindcss), licensed under [MIT license](https://github.com/tailwindlabs/tailwindcss/blob/next/LICENSE)
- **pest** - GitHub: [pestphp/pest](https://github.com/pestphp/pest), licensed under [MIT license](https://github.com/pestphp/pest/blob/2.x/LICENSE.md)
- **PHPMailer** - GitHub: [PHPMailer/PHPMailer](https://github.com/PHPMailer/PHPMailer), licensed under [LGPL-2.1 license](https://github.com/PHPMailer/PHPMailer/blob/master/LICENSE)
- **Curl-Adapter** - GitHub: [JensOstertag/curl-adapter](https://github.com/JensOstertag/curl-adapter), licensed under [MIT license](https://github.com/JensOstertag/curl-adapter/blob/main/LICENSE-MIT)
- **GeocodingUtil** - GitHub: [JensOstertag/geocoding-util](https://github.com/JensOstertag/geocoding-util), licensed under [GPL-2.0 license](https://github.com/JensOstertag/geocoding-util/blob/main/LICENSE-GPL2)
- **UploadHelper** - GitHub: [JensOstertag/uploadhelper](https://github.com/JensOstertag/uploadhelper), licensed under [MIT license](https://github.com/JensOstertag/uploadhelper/blob/main/LICENSE-MIT)
- **CSVReader** - GitHub: [JensOstertag/csvreader](https://github.com/JensOstertag/csvreader), licensed under [MIT license](https://github.com/JensOstertag/csvreader/blob/main/LICENSE)

## License
This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.
