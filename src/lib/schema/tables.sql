# Settings table
CREATE TABLE IF NOT EXISTS `Settings` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `key` VARCHAR(255) NOT NULL,
    `value` VARCHAR(511) NOT NULL,
    `created` DATETIME NOT NULL,
    `updated` DATETIME NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
INSERT INTO Settings VALUE (NULL, 'defaultMaxParticipants', '5', NOW(), NOW());
INSERT INTO Settings VALUE (NULL, 'userLoginAllowed', 'true', NOW(), NOW());
INSERT INTO Settings VALUE (NULL, 'coursesAssigned', 'false', NOW(), NOW());

# Group table
CREATE TABLE IF NOT EXISTS `Group` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `clearance` INT NOT NULL,
    `created` DATETIME NOT NULL,
    `updated` DATETIME NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

# Course table
CREATE TABLE IF NOT EXISTS `Course` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `maxParticipants` INT NOT NULL,
    `minClearance` INT NOT NULL,
    `maxClearance` INT NULL,
    `created` DATETIME NOT NULL,
    `updated` DATETIME NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

# User table
CREATE TABLE IF NOT EXISTS `User` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(255) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `emailVerified` TINYINT NOT NULL DEFAULT 0,
    `permissionLevel` INT NOT NULL,
    `firstName` VARCHAR(63) NOT NULL,
    `lastName` VARCHAR(63) NOT NULL,
    `group` INT NULL,
    `leadingCourse` INT NULL,
    `lastLogin` TIMESTAMP NULL,
    `oneTimePassword` VARCHAR(255) NULL,
    `oneTimePasswordExpiration` DATETIME NULL,
    `created` DATETIME NOT NULL,
    `updated` DATETIME NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY (`username`),
    FOREIGN KEY (`group`) REFERENCES `Group`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`leadingCourse`) REFERENCES `Course`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
