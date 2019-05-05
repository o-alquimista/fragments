# fragments-authentication
A modular web authentication application written in PHP.

- The default database name is `fragments`. Change it at `Utils/Connection.php`. The username, password and PDO driver for the database connection can be set there as well.

- Create the table: `CREATE TABLE users (id INT NOT NULL AUTO_INCREMENT, username VARCHAR(255) NOT NULL, hash VARCHAR(255) NOT NULL, PRIMARY KEY(id));`

- Make sure `log_errors` is enabled on your server. Disable `display_errors` unless you're debugging. The recommended session options are already set on session start.

- The 'root' setting (`DocumentRoot` for Apache) of your webserver must be pointing to the folder where the project files are (where this README file is), not to a parent folder. It will not work properly if you do not configure it like that.