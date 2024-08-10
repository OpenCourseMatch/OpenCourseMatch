# User uploads
There are, of course, many possible ways to handle user-uploaded content. In some cases, you might want to store the uploaded files to a storage bucket of a cloud provider. In other cases, you might want to store the files directly on the server.

When deploying the application with Docker, the `📁 files/` directory is already prepared to store user-uploaded content. It is mounted from the host system (`📁 app-files/`) to the container, so the files are persistent even if the container is restarted. The files are **not** accessible via a route, so you have to write a script that reads the files and sends them to the user.
