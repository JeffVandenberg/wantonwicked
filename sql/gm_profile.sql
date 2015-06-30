CREATE TABLE
  staff_profiles
(
  id             INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  user_id        INT UNSIGNED NOT NULL,
  position       VARCHAR(30)  NOT NULL,
  location       VARCHAR(100) NOT NULL,
  availability   TEXT         NOT NULL,
  current_status VARCHAR(100) NOT NULL,
  INDEX `user_index` (user_id)
);

CREATE TABLE
  permissions
(
  id   INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100)
);

INSERT INTO
  permissions
  (id, name)
VALUES
  (1, 'Asst'), (2, 'ST'), (3, 'Admin'), (4, 'Owner'), (5, 'Wiki');

CREATE TABLE
  permissions_users
(
  user_id       INT UNSIGNED NOT NULL PRIMARY KEY,
  permission_id INT UNSIGNED NOT NULL PRIMARY KEY,
  INDEX `permission_index` (permission_id)
);

