--
-- Import this sql script via phpmyadmin, or load it in with the commandline in mysql.
-- Commandline: mysql -u root; within mysql: source [path]datagenerator.sql 
-- 
-- Jeffrey Oomen, june 2016.
--

-- --------------------------------------------------------
-- Database: `phpchatsystem;`
--
DROP DATABASE IF EXISTS `phpchatsystem`;
CREATE DATABASE `phpchatsystem`;
USE `phpchatsystem`; 

-- --------------------------------------------------------
CREATE TABLE `chat_user` (
	`user_id` INT(11) NOT NULL AUTO_INCREMENT,
	`user_name` VARCHAR(40) NOT NULL,
	`user_mail` VARCHAR(40) NOT NULL,
	`user_pass` VARCHAR(40) NOT NULL,
	`time_mod` INT(100) NOT NULL,
	CONSTRAINT PKU PRIMARY KEY (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

CREATE TABLE `chat_room` (
	`room_id` INT(11) NOT NULL AUTO_INCREMENT,
	`room_name` VARCHAR(40) NOT NULL,
	`room_users_count` TINYINT(2) NOT NULL,
	`room_file` VARCHAR(40) NOT NULL,
	CONSTRAINT PKR PRIMARY KEY (`room_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `chat_users_rooms` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `room_id` INT(11) NOT NULL,
  `time_mod` INT(100) NOT NULL,
  CONSTRAINT PKUR PRIMARY KEY (`id`),
  CONSTRAINT FKU FOREIGN KEY (`user_id`) REFERENCES `chat_user`(`user_id`),
  CONSTRAINT FKR FOREIGN KEY (`room_id`) REFERENCES `chat_room`(`room_id`) 
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

INSERT INTO `chat_user` (`user_id`, `user_name`, `user_mail`, `user_pass`, `time_mod`) VALUES
(NULL, 'person1', 'person1@gmail.com', '123', '0'),
(NULL, 'person2', 'person2@gmail.com', '123', '0'),
(NULL, 'person3', 'person3@gmail.com', '123', '0');

INSERT INTO `chat_room` (`room_id`, `room_name`, `room_users_count`, `room_file`) VALUES
(NULL, 'room1', '0', '../roomfiles/room1.txt'),
(NULL, 'room2', '0', '../roomfiles/room2.txt'),
(NULL, 'room3', '0', '../roomfiles/room3.txt');

