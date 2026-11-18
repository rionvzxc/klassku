CREATE TABLE IF NOT EXISTS user_level (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(64) NOT NULL,
    level INT NOT NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS user (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_user_level INT NOT NULL,
    name VARCHAR(128) NOT NULL,
    username VARCHAR(128) NOT NULL UNIQUE,
    password VARCHAR(256) NOT NULL,
    is_active INT NOT NULL,
    FOREIGN KEY (id_user_level) REFERENCES user_level (id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS class (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    name VARCHAR(128) NOT NULL,
    is_active INT NOT NULL,
    FOREIGN KEY (id_user) REFERENCES user (id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS agenda (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_class INT NOT NULL,
    name VARCHAR(128) NOT NULL DEFAULT ' ',
    date DATE NOT NULL,
    meeting_count INT NOT NULL,
    is_active INT NOT NULL,
    UNIQUE KEY uq_agenda_class_date (id_class, date),
    FOREIGN KEY (id_class) REFERENCES class (id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS student (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_class INT NOT NULL,
    name VARCHAR(128) NOT NULL,
    is_active INT NOT NULL,
    FOREIGN KEY (id_class) REFERENCES class (id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS assessment (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_student INT NOT NULL,
    id_agenda INT NOT NULL,
    attendance TEXT NOT NULL,
    assignment TEXT NOT NULL,
    participation TEXT NOT NULL,
    FOREIGN KEY (id_agenda) REFERENCES agenda (id),
    FOREIGN KEY (id_student) REFERENCES student (id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS point_config (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_class INT NOT NULL,
    attendance TEXT NOT NULL,
    assignment TEXT NOT NULL,
    participation TEXT NOT NULL,
    FOREIGN KEY (id_class) REFERENCES class (id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `rank` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_class INT NOT NULL,
    name VARCHAR(64) NOT NULL,
    position INT NOT NULL,
    is_active INT NOT NULL,
    FOREIGN KEY (id_class) REFERENCES class (id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS config (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(64) NOT NULL,
    data TEXT NOT NULL
) ENGINE=InnoDB;
