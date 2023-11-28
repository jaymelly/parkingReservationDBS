README 
Our Program is organized in the following:
/GroupProject
    README.txt
	project_script.sql
	index.php
	remove.php
	UserInterface.php
	add.php
	cancelReservation.php
	confirmReservation.php
	reservation.php
	admin.css
	user.css
	zone
	reservation


STEPS- For Windows

1. Run project_script.sql to create & fill all relevant tables

2. Create a user for php. Let us create user ‘phpuser’. 
Connect to your mysql as the ‘root’ user. Enter the following SQL command,

mysql> create user phpuser@'%' identified with mysql_native_password by 'phpwd';

Query OK, 0 rows affected (0.08 sec)

mysql> grant all on cse_project3241.* to phpuser@'%';


3. Start at index.php

 