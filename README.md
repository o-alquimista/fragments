# Fragments
Fragments aims to be a small web authentication solution attached to its own small framework. Third-party dependencies will always be avoided if possible, relying solely on what PHP provides. Routing and autoloading are also developed in-house.

Contributions are appreciated. Beginners looking for an entry point: I encourage you to take a look at the open issues and try to solve those tagged in purple.

## Instructions
- The default database name is `fragments`. Change it at `Fragments/Utility/Connection.php`. The username, password and PDO driver for the database connection can be set there as well.

- Create the table: `CREATE TABLE users (id INT NOT NULL AUTO_INCREMENT, username VARCHAR(255) NOT NULL, hash VARCHAR(255) NOT NULL, PRIMARY KEY(id));`

- The 'root' setting (`DocumentRoot` for Apache) of your server or virtual host must point to the `/public_html` folder.

- Configure the root directory (Apache):
```
AllowOverride None
Require all granted
FallbackResource /index.php
```

## Requirements
- PHP XML Extension. This package is called `php-xml` on Ubuntu.
