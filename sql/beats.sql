CREATE TABLE beat_types
(
  id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  name VARCHAR(200) NOT NULL,
  number_of_beats SMALLINT NOT NULL,
  admin_only TINYINT(1) NOT NULL,
  created_by_id INT NOT NULL,
  created DATETIME NOT NULL,
  updated_by_id INT NOT NULL,
  updated DATETIME
);

ALTER TABLE beat_types ADD COLUMN may_rollover tinyint(1) NOT NULL DEFAULT 0;

CREATE TABLE beat_statuses
(
  id INT PRIMARY KEY  NOT NULL AUTO_INCREMENT,
  name varchar(200) not null
);
INSERT INTO beat_statuses (id, name) VALUES
  (1,'New'), (2, 'Staff Awarded'), (3, 'Applied'), (4, 'Invalidated'), (5, 'Expired');

CREATE TABLE character_beats
(
  id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  character_id INT NOT NULL,
  beat_type_id INT NOT NULL,
  beat_status_id INT NOT NULL,
  note TEXT,
  created_by_id INT NOT NULL,
  created DATETIME,
  updated_by_id INT NOT NULL,
  updated DATETIME,
  applied_on DATETIME,
  beats_awarded TINYINT UNSIGNED NOT NULL
);
CREATE INDEX character_beats_character_id_applied_on_index ON character_beats (character_id, applied_on);
CREATE INDEX character_beats_character_id_created_index ON character_beats (character_id, created);
CREATE INDEX character_beats_created_by_id_index ON character_beats (created_by_id);

CREATE TABLE character_beat_records
(
  id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  character_id INT NOT NULL,
  record_month DATE,
  experience_earned FLOAT(4,2)
);
