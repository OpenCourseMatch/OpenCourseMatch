# System status table
CREATE TABLE IF NOT EXISTS `SystemStatus` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `key` VARCHAR(256) NOT NULL,
    `value` VARCHAR(512) NOT NULL,
    `created` DATETIME NOT NULL,
    `updated` DATETIME NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
INSERT INTO SystemStatus VALUE (NULL, 'userLoginAllowed', 'true', NOW(), NOW());
INSERT INTO SystemStatus VALUE (NULL, 'coursesAssigned', 'false', NOW(), NOW());

# System setting table
CREATE TABLE IF NOT EXISTS `SystemSetting` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `key` VARCHAR(256) NOT NULL,
    `value` VARCHAR(512) NOT NULL,
    `created` DATETIME NOT NULL,
    `updated` DATETIME NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

# Group table
CREATE TABLE IF NOT EXISTS `Group` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(256) NOT NULL,
    `clearance` INT NOT NULL,
    `created` DATETIME NOT NULL,
    `updated` DATETIME NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

# Course table
CREATE TABLE IF NOT EXISTS `Course` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(256) NOT NULL,
    `organizer` VARCHAR(256) NULL,
    `minClearance` INT NOT NULL,
    `maxClearance` INT NULL,
    `minParticipants` INT NOT NULL DEFAULT 0,
    `maxParticipants` INT NOT NULL,
    `created` DATETIME NOT NULL,
    `updated` DATETIME NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

# User table
CREATE TABLE IF NOT EXISTS `User` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(256) NOT NULL,
    `password` VARCHAR(256) NOT NULL,
    `email` VARCHAR(256) NOT NULL,
    `emailVerified` TINYINT NOT NULL DEFAULT 0,
    `permissionLevel` INT NOT NULL,
    `firstName` VARCHAR(64) NOT NULL,
    `lastName` VARCHAR(64) NOT NULL,
    `groupId` INT NULL,
    `leadingCourseId` INT NULL,
    `lastLogin` TIMESTAMP NULL,
    `oneTimePassword` VARCHAR(256) NULL,
    `oneTimePasswordExpiration` DATETIME NULL,
    `created` DATETIME NOT NULL,
    `updated` DATETIME NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY (`username`),
    FOREIGN KEY (`groupId`) REFERENCES `Group`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`leadingCourseId`) REFERENCES `Course`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

# Choice table
CREATE TABLE IF NOT EXISTS `Choice` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `userId` INT NOT NULL,
    `courseId` INT NOT NULL,
    `priority` INT NOT NULL,
    `created` DATETIME NOT NULL,
    `updated` DATETIME NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`userId`) REFERENCES `User`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`courseId`) REFERENCES `Course`(`id`) ON DELETE CASCADE,
    UNIQUE KEY (`userId`, `courseId`),
    UNIQUE KEY (`userId`, `priority`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

# Allocation table
CREATE TABLE IF NOT EXISTS `Allocation` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `userId` INT NOT NULL,
    `courseId` INT NOT NULL,
    `asCourseLeader` TINYINT NOT NULL DEFAULT 0,
    `created` DATETIME NOT NULL,
    `updated` DATETIME NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`userId`) REFERENCES `User`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`courseId`) REFERENCES `Course`(`id`) ON DELETE CASCADE,
    UNIQUE KEY (`userId`, `courseId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
