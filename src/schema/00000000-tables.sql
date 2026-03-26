# System status table
CREATE TABLE IF NOT EXISTS `app\settings\SystemStatus` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `key` VARCHAR(256) NOT NULL,
    `value` VARCHAR(512) NOT NULL,
    `created` DATETIME(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
    `updated` DATETIME(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3),
    PRIMARY KEY (`id`),
    UNIQUE KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
DELETE FROM `app\settings\SystemStatus`;
INSERT INTO `app\settings\SystemStatus` VALUE (NULL, 'userActionsAllowed', 'false', NOW(), NOW());
INSERT INTO `app\settings\SystemStatus` VALUE (NULL, 'algorithmRunning', 'false', NOW(), NOW());
INSERT INTO `app\settings\SystemStatus` VALUE (NULL, 'coursesAssigned', 'false', NOW(), NOW());
INSERT INTO `app\settings\SystemStatus` VALUE (NULL, 'courseAssignmentPublic', 'false', NOW(), NOW());

# System setting table
CREATE TABLE IF NOT EXISTS `app\settings\SystemSetting` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `key` VARCHAR(256) NOT NULL,
    `value` VARCHAR(512) NOT NULL,
    `created` DATETIME(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
    `updated` DATETIME(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3),
    PRIMARY KEY (`id`),
    UNIQUE KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

# Group table
CREATE TABLE IF NOT EXISTS `app\groups\Group` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(256) NOT NULL,
    `clearance` INT NOT NULL,
    `created` DATETIME(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
    `updated` DATETIME(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

# Course table
CREATE TABLE IF NOT EXISTS `app\courses\Course` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(256) NOT NULL,
    `organizer` VARCHAR(256) NULL,
    `minClearance` INT NOT NULL,
    `maxClearance` INT NULL,
    `minParticipants` INT NOT NULL DEFAULT 0,
    `maxParticipants` INT NOT NULL,
    `created` DATETIME(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
    `updated` DATETIME(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

# User table
CREATE TABLE IF NOT EXISTS `app\users\User` (
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
    `showHelpBoxes` TINYINT NOT NULL DEFAULT 1,
    `lastLogin` DATETIME(3) NULL,
    `oneTimePassword` VARCHAR(256) NULL,
    `oneTimePasswordExpiration` DATETIME(3) NULL,
    `created` DATETIME(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
    `updated` DATETIME(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3),
    PRIMARY KEY (`id`),
    UNIQUE KEY (`username`),
    FOREIGN KEY (`groupId`) REFERENCES `app\groups\Group`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`leadingCourseId`) REFERENCES `app\courses\Course`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

# Choice table
CREATE TABLE IF NOT EXISTS `app\choices\Choice` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `userId` INT NOT NULL,
    `courseId` INT NOT NULL,
    `priority` INT NOT NULL,
    `created` DATETIME(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
    `updated` DATETIME(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3),
    PRIMARY KEY (`id`),
    FOREIGN KEY (`userId`) REFERENCES `app\users\User`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`courseId`) REFERENCES `app\courses\Course`(`id`) ON DELETE CASCADE,
    UNIQUE KEY (`userId`, `courseId`),
    UNIQUE KEY (`userId`, `priority`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

# Assignment table
CREATE TABLE IF NOT EXISTS `app\assignments\Assignment` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `userId` INT NOT NULL,
    `courseId` INT NOT NULL,
    `created` DATETIME(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
    `updated` DATETIME(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3),
    PRIMARY KEY (`id`),
    FOREIGN KEY (`userId`) REFERENCES `app\users\User`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`courseId`) REFERENCES `app\courses\Course`(`id`) ON DELETE CASCADE,
    UNIQUE KEY (`userId`, `courseId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
