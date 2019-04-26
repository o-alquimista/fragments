# fragments-authentication
A modular web authentication application written in PHP.

- The default database name is `fragments`. Change it at `Utils/Connection.php`. The username and password for the database connection can be set there as well.

- Create the table: `CREATE TABLE users (id INT NOT NULL AUTO_INCREMENT, username VARCHAR(255) NOT NULL, hash VARCHAR(255) NOT NULL, PRIMARY KEY(id));`
