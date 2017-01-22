DROP TABLE IF EXISTS gamingsandbox_wanton.condition_types;
CREATE TABLE gamingsandbox_wanton.condition_types
(
  id   INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(128) NOT NULL
);
INSERT INTO gamingsandbox_wanton.condition_types
(id, name)
VALUES
  (1, 'Condition'),
  (2, 'Tilt');

DROP TABLE IF EXISTS gamingsandbox_wanton.conditions;
CREATE TABLE gamingsandbox_wanton.conditions
(
  id                INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  name              VARCHAR(255)    NOT NULL,
  source            VARCHAR(255)    NOT NULL,
  is_persistent     TINYINT(1)      NOT NULL,
  description       TEXT            NOT NULL,
  condition_type_id INT             NOT NULL,
  resolution        VARCHAR(255),
  beat              VARCHAR(255),
  created_by        INTEGER         NOT NULL,
  created           DATETIME        NOT NULL,
  updated_by        INT             NOT NULL,
  updated           DATETIME        NOT NULL
);

ALTER TABLE gamingsandbox_wanton.conditions 
  ADD COLUMN slug VARCHAR(255) NOT NULL,
  ADD INDEX (slug);

INSERT INTO gamingsandbox_wanton_test.condition_types VALUES (1, 'Condition'), (2, 'Tilt');

INSERT INTO gamingsandbox_wanton_test.permissions (permission_name) VALUES ('Manage DB');

ALTER TABLE characters ADD COLUMN gameline varchar(255) NOT NULL;
UPDATE characters set gameline = 'nwod';

alter table character_powers add column is_public tinyint(1) UNSIGNED NOT NULL;
alter table character_powers MODIFY column power_name varchar(255) not null;
alter table character_powers add column extra text not null;

alter table characters modify column virtue varchar(100), modify column vice varchar(100);
alter table characters add column slug varchar(255) not null;
alter table character_powers modify column is_public tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  modify column extra text;

alter table characters modify column is_suspended tinyint(1) not null default 0;
alter table characters add index (slug);