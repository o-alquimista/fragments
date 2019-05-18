# Fragments
A modular web authentication solution written in PHP.

- The default database name is `fragments`. Change it at `Utility/Connection.php`. The username, password and PDO driver for the database connection can be set there as well.

- Create the table: `CREATE TABLE users (id INT NOT NULL AUTO_INCREMENT, username VARCHAR(255) NOT NULL, hash VARCHAR(255) NOT NULL, PRIMARY KEY(id));`

- Make sure `log_errors` is enabled on your server. Disable `display_errors` unless you're debugging. The recommended session options are already set on session start.

- The 'root' setting (`DocumentRoot` for Apache) of your server or virtual host must point to the `/Public` folder.

- The following code is responsible for redirecting all requests to `Public/index.php`, which is the entry point of the application. This must go into the Public folder's directory configuration. If you don't use Apache, this code must be converted for your webserver. We should probably write examples for other webservers here in the future.
```
    RewriteEngine on
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^.*$ index.php [L,QSA]
```
