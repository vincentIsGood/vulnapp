# Vulnerable App
Vulnerable App is a simple web application made in PHP used to demonstrate common web application vulnerabilities and some security practices.

Only "Reading" & "Hacking Practices" are available to use in this public project.

"Networking" & "Course" part of the application is missing because they originally contains cisco course info. which should not be redistributed online.

## Common Web Vuln
- Directory Traversal
- Local File Inclusion (LFI)
- Session Hijacking
- SQL Injection
- Cross Site Scripting (XSS)

## Running It
The application originally ran on XAMPP (windows). However, using "php" command **should** also work, as long as a connection can be made to the database defined in `server/lib.php:100` (line 100).

Before everything, create a database named `vulndatabase` in MySQL. `root` user in mysql should not use any password. Otherwise, you will have to modify `server/lib.php:92` to suit your own configuration.

## Note
This is my Final Year Project in my IVE career. It will never be maintained.