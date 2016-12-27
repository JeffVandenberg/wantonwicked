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
