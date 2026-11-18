# Klassku

Klassku is a PHP web application for managing classes and recording student assessments by agenda. Teachers can organize students and meetings, configure how points are recorded, and track attendance, assignments, and participation.

## Installation

Klassku requires PHP 8.1 or later with PDO MySQL and Intl, Apache with `mod_rewrite` and support for the project's `.htaccess`, and MySQL 8.0 or later.

Open `/setup/` on your server in a browser and follow the on-screen instructions. Use separate MySQL accounts for installation and for running Klassku. The setup saves the application account in the active env file.

Installation requires [schema.sql](schema.sql) in the project root. Setup uses it to create the MySQL tables and leaves the file in place. You can delete it manually after installation succeeds.

Tested only on Windows with:

- PHP `8.1.10-Win32-vs16-x64`
- Apache `httpd-2.4.54-win64-VS16`
- MySQL `mysql-8.0.30-winx64`

## Features

- Class and student management
- Class agendas with dates and meeting counts
- Attendance, assignment, and participation assessments
- Per-class point configuration and ranking entries
- Account sign-in and profile management

## License

Klassku is licensed under the Apache License 2.0. See [LICENSE](LICENSE) for details.
