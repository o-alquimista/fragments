# Fragments
Fragments aims to be a small framework for web applications. Third-party dependencies will always be avoided if possible, relying solely on what PHP provides. Routing and autoloading are also developed in-house.

Keep in mind that this project is merely an experiment, and is not recommended for use in production.

## Requirements
- PHP XML extension. This package is called `php-xml` on Ubuntu.

## Instructions
- The default database name is `fragments`. Change it at `Fragments/Component/Database/PDOConnection.php`. The username, password and PDO driver for the connection can be set there as well.

- Create the table: `CREATE TABLE users (id INT NOT NULL AUTO_INCREMENT, username VARCHAR(255) NOT NULL, hash VARCHAR(255) NOT NULL, PRIMARY KEY(id));`

- The 'root' setting (`DocumentRoot` on Apache) of your server or virtual host must point to the `/Public` folder.

- Configure the root directory (Apache):
```
AllowOverride None
Require all granted
FallbackResource /index.php
```

Since Fragments is not meant for use in production, we already include an application in `/App`, plus some routes in `/Config/routes.xml` and assets in `/Public`.

## License
Copyright 2019 Douglas Silva (0x9fd287d56ec107ac)

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU Affero General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License
along with this program.  If not, see <https://www.gnu.org/licenses/>.
