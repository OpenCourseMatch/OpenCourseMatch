# (Re-)Deploying the application
Before deploying the application, make sure that you have changed all settings for the production version such as changing the minimum required log level or the version number.

Now, there are two separate ways to deploy the application.

## Docker
Unfortunately, the Docker deployment option doesn't work with the automatic deployment feature (yet). You have to re-pull and restart the container manually.

## Apache web servers
If you have followed all steps in the installation guide, you can simply commit and push your changes to the repository's `main` branch. A GitHub Action will then call the `/deploy` route which will pull all changes from the repository and install the dependencies.
