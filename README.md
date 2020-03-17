# Fragments
Fragments aims to be a small framework for web applications. Keep in mind that this project is merely an experiment, and is not recommended for use in production.

## Requirements
- [Composer](https://getcomposer.org/)
- [Yarn](https://yarnpkg.com/)
- PHP XML extension. This package is called `php-xml` on Ubuntu.

## Getting started
Create a blank project:
`composer create-project crimsonking/fragments-skeleton <your-project-name>`

Configure your web server so that its root directory is `<your-project-name>/public`. As for the directory settings, here's how you should configure it on Apache:
```
AllowOverride None
Require all granted
Allow from All
FallbackResource /index.php
```

Create `/config/database.ini` with the following lines to configure database connection details:
```
pdo_driver = mysql
database_name = fragments
host = 127.0.0.1
port = 3306
username = root
password = root
```

Add routes in the file `/config/routes.xml` and start building your first controller at `/src/Controller/`. You can also try our [Fragments Demo](https://github.com/o-alquimista/fragments-demo) application to get an idea of how things work.

## License
Copyright 2019-2020 Douglas Silva (0x9fd287d56ec107ac)

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
