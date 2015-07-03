CREATE TABLE `plots` (
  `id`                 MEDIUMINT(8) UNSIGNED                                                                     NOT NULL AUTO_INCREMENT,
  `plot_name`          VARCHAR(100)                                                                              NOT NULL DEFAULT '',
  `Site_ID`            SMALLINT(5) UNSIGNED                                                                      NOT NULL DEFAULT '0',
  `Submitter_ID`       INT(10) UNSIGNED                                                                          NOT NULL DEFAULT '0',
  `Submitted_Date`     DATETIME                                                                                  NOT NULL DEFAULT '0000-00-00 00:00:00',
  `Start_Date`         DATE                                                                                      NOT NULL DEFAULT '0000-00-00',
  `End_Date`           DATE                                                                                      NOT NULL DEFAULT '0000-00-00',
  `Plot_Category`      ENUM('One-Shot', 'Adventure', 'Setting', 'Metaplot', 'C/F/S')                             NOT NULL DEFAULT 'One-Shot',
  `Synopsis`           VARCHAR(255)                                                                              NOT NULL DEFAULT '',
  `Public_Information` VARCHAR(255)                                                                              NOT NULL DEFAULT '',
  `Description`        TEXT                                                                                      NOT NULL,
  `Result`             TEXT                                                                                      NOT NULL,
  `Notes`              TEXT                                                                                      NOT NULL,
  `Status`             ENUM('Pending', 'In Progress', 'Suspended', 'Completed', 'Was Used', 'Denied', 'Deleted') NOT NULL DEFAULT 'Pending',
  `May_Post`           ENUM('Y', 'N')                                                                                     DEFAULT 'N',
  `Approver_ID`        INT(10) UNSIGNED                                                                          NOT NULL DEFAULT '0',
  `Approved_Date`      DATETIME                                                                                  NOT NULL DEFAULT '0000-00-00 00:00:00',
  `Thread_ID`          MEDIUMINT(8) UNSIGNED                                                                     NOT NULL DEFAULT '0',
  PRIMARY KEY (`Plot_ID`),
  KEY `Submitted_Date` (`Submitted_Date`),
  KEY `Start_Date` (`Start_Date`),
  KEY `Status` (`Status`),
  KEY `Plot_Name` (`Plot_Name`),
  KEY `Submitter_ID` (`Submitter_ID`),
  KEY `Plot_Category` (`Plot_Category`),
  KEY `End_Date` (`End_Date`)
)
  ENGINE = InnoDB
  AUTO_INCREMENT = 141
  DEFAULT CHARSET = utf8;


CREATE TABLE
  scenes
(
  id            INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  name          VARCHAR(100) NOT NULL,
  summary   varchar(255),
  run_by_id     INT UNSIGNED,
  run_on_date   DATETIME,
  description   TEXT,
  is_closed     TINYINT(1) UNSIGNED,
  created_by_id INT UNSIGNED NOT NULL,
  created_on    DATETIME,
  updated_by_id INT UNSIGNED NOT NULL,
  updated_on    DATETIME,
  index(run_by_id)
);

create table
    scene_characters
    (
        id int unsigned not null auto_increment primary key,
        scene_id int unsigned not null,
        character_id int unsigned not null,
        note text,
        added_on datetime not null,
        index (scene_id),
        index (character_id)
    );