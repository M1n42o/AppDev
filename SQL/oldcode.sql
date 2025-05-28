create database test;
use test;

CREATE TABLE login(
  id int(11)AUTO_INCREMENT,
  username varchar(50),
  password varchar(255),
  PRIMARY KEY(id)
);

CREATE TABLE profile(
  id int(11)AUTO_INCREMENT,
  login_id int(11),
  firstname varchar(255),
  lastname varchar(255),
  bio varchar(255),
  image varchar(255),
  PRIMARY KEY(id),
  FOREIGN KEY (login_id) REFERENCES login(id) ON DELETE CASCADE ON UPDATE CASCADE
);
/*DO NOT TOUCH THIS SHIT*