CREATE TABLE `tasks` (
  `id` int NOT NULL AUTO_INCREMENT,
  `owner_id` int NOT NULL,
  `label` varchar(60) NOT NULL,
  `description` longtext NOT NULL,
  `due_at` timestamp NOT NULL,
  PRIMARY KEY (`id`)
);
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(150) NOT NULL,
  `password` varchar(60) NOT NULL,
  PRIMARY KEY (`id`)
);