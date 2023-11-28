CREATE DATABASE IF NOT EXISTS cse3241_project;
USE cse3241_project;


CREATE TABLE IF NOT EXISTS ZONES (
	Zone_num INT AUTO_INCREMENT,
	Designated_spots INT,
	Rate SMALLINT,
	Spots_taken SMALLINT DEFAULT 0,
	Zone_date DATE,
	Name varchar(255),
	PRIMARY KEY(Zone_num, Zone_date)
);


CREATE TABLE IF NOT EXISTS RESERVATIONS (
	Confirmation_id int not null primary key,
	Zone_num int,
	Phone varchar(20),
	Cancelled boolean default false,
	Date_reserved date not null,
	Fee decimal(4,2) not null,
	Foreign Key (Zone_num) references ZONE (Zone_num)
);

CREATE TABLE IF NOT EXISTS DISTANCE(
	Zone_num int not null,
	Event varchar(60) not null,
	Distance int not null,
	PRIMARY KEY(Zone_num, Event),
	FOREIGN KEY(Zone_num) references Zone(Zone_num)
);

insert into DISTANCE values (1, "Nationwide Arena", 1), (1, "COSI", 4), (1, "Huntington Park", 3), (2, "Nationwide Arena", 2), (2, "COSI", 5), (1, "Huntington Park", 4), (3, "Nationwide Arena", 2), (3, "COSI", 4), (2, "Huntington Park", 2); 
########## CHANGE TO BE FILE PATH NAME ##########
load data infile 'C:\php\reservation' into table reservations;
load data infile 'C:\php\zone' into table zones;
