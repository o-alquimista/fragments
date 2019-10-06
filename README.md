# Fragments
Fragments aims to be a small framework for web applications. Keep in mind that this project is merely an experiment, and is not recommended for use in production.

## Requirements
- [Composer](https://getcomposer.org/)
- [Yarn](https://yarnpkg.com/)
- PHP XML extension. This package is called `php-xml` on Ubuntu.

## Getting started
Create a blank project:
`composer create-project -s dev crimsonking/fragments-skeleton <your-project-name>`

Fragments doesn't yet provide an easy way to specify database connection settings. Until it does, you must change them at `/vendor/crimsonking/fragments/src/Fragments/Component/Database/PDOConnection.php`. The username, password, database name and PDO driver for the connection must be set there.
- Create the table: `CREATE TABLE users (id INT NOT NULL AUTO_INCREMENT, username VARCHAR(255) NOT NULL, hash VARCHAR(255) NOT NULL, PRIMARY KEY(id));`

Configure your web server so that its root directory is `/public`. As for the directory settings, here's how you should configure it on Apache:
```
AllowOverride None
Require all granted
FallbackResource /index.php
```

In order to manage assets, run `yarn install` and build them with `yarn build` (development), `yarn build-watch` (development w/ watch) or `yarn build-prod` (production).

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