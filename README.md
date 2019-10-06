# Fragments
Fragments aims to be a small framework for web applications. Keep in mind that this project is merely an experiment, and is not recommended for use in production.

## Requirements
- [Composer](https://getcomposer.org/)
- [Yarn](https://yarnpkg.com/)
- PHP XML extension. This package is called `php-xml` on Ubuntu.

## Getting started
Create a blank project:
`composer create-project -s dev crimsonking/fragments-skeleton <your-project-name>`

Configure your web server so that its root directory is `<your-project-name>/public`. As for the directory settings, here's how you should configure it on Apache:
```
AllowOverride None
Require all granted
FallbackResource /index.php
```

Edit `/config/database.ini` to configure database connection details.

In order to manage assets, run `yarn install` and build them with `yarn build` (development), `yarn build-watch` (development w/ watch) or `yarn build-prod` (production). The output goes to `/public/build/`.

Add routes in `/config/routes.xml` and start building your first controller in `/src/Controller/`. Business logic/data mappers go inside the Model. The View essentially juggles with a couple of templates. Check the Fragments Bundle files, such as [AbstractController](https://github.com/o-alquimista/fragments/blob/master/src/Fragments/Bundle/Controller/AbstractController.php) and [AbstractView](https://github.com/o-alquimista/fragments/blob/master/src/Fragments/Bundle/View/AbstractView.php) to see what methods are there to help you. You can also try our [Fragments Demo](https://github.com/o-alquimista/fragments-demo) application to get an idea of how things work.

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
