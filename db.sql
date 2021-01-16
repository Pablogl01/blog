CREATE TABLE `user` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`username` varchar(100) NOT NULL UNIQUE,
	`email` varchar(100) NOT NULL UNIQUE,
	`passwd` varchar(64) NOT NULL,
	`rol` INT NOT NULL,
	PRIMARY KEY (`id`)
);

CREATE TABLE `comments` (
	`id` INT NOT NULL AUTO_INCREMENT UNIQUE,
	`comment` varchar(45),
	`user` INT(11),
	`post` INT(11) NOT NULL,
	PRIMARY KEY (`id`)
);

CREATE TABLE `post` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`title` varchar(100) NOT NULL,
	`cont` varchar(255) NOT NULL,
	`user` INT(11) NOT NULL,
	`create_date` DATE NOT NULL,
	`modify_date` DATE NOT NULL,
	PRIMARY KEY (`id`)
);

CREATE TABLE `post_has_tags` (
	`post_id` INT(11) NOT NULL,
	`tags_id` INT NOT NULL
);

CREATE TABLE `tags` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`tag` varchar(45) NOT NULL UNIQUE,
	PRIMARY KEY (`id`)
);

CREATE TABLE `roles` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`rol` varchar(45) NOT NULL,
	`desc` varchar(45) NOT NULL,
	PRIMARY KEY (`id`)
);

ALTER TABLE `comments` ADD CONSTRAINT `comments_fk0` FOREIGN KEY (`user`) REFERENCES `user`(`id`);

ALTER TABLE `comments` ADD CONSTRAINT `comments_fk1` FOREIGN KEY (`post`) REFERENCES `post`(`id`);

ALTER TABLE `post` ADD CONSTRAINT `post_fk0` FOREIGN KEY (`user`) REFERENCES `user`(`id`);

ALTER TABLE `post_has_tags` ADD CONSTRAINT `post_has_tags_fk0` FOREIGN KEY (`post_id`) REFERENCES `post`(`id`);

ALTER TABLE `post_has_tags` ADD CONSTRAINT `post_has_tags_fk1` FOREIGN KEY (`tags_id`) REFERENCES `tags`(`id`);

ALTER TABLE `user` ADD CONSTRAINT `user_fk0` FOREIGN KEY (`rol`) REFERENCES `user`(`id`);

