CREATE TABLE `participants` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `sname` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(255) NOT NULL,
  `topic` int NOT NULL,
  `paym` int NOT NULL,
  `soglras` TINYINT(1) UNSIGNED NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NOT NULL,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY(`id`),
  INDEX `deleted_at` (`deleted_at`)
);
