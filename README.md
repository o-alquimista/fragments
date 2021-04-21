# Fragments
Fragments aims to be a small PHP framework for web applications. Keep in mind that this project is merely an experiment, and is not recommended for use in production.

It has its own router component and is heavily inspired by [Symfony](https://symfony.com/).

## Requirements
- [Composer](https://getcomposer.org/)
- PHP XML extension. This package is called `php-xml` on Ubuntu.

## Getting started
1. Create a blank project:
`composer create-project crimsonking/fragments-skeleton <your-project-name>`

2. Configure your web server so that its root directory is `<your-project-name>/public` and the fallback resource is `index.php`.

3. Create `/config/pdo.ini` with the following lines to configure database connection details:
```
driver = mysql
host = localhost
;port = 3306
database = fragments_app
;socket = /path/to/socket
;charset = utf8mb4
username = example
password = example
```

4. Add routes in the file `/config/routes.xml` and start building your first controller at `/src/Controller/`. You can also try our [Fragments application](https://github.com/o-alquimista/fragments-app) to get an idea of how things work.

## License
Copyright 2019-2021 Douglas Silva (0x9fd287d56ec107ac)

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
