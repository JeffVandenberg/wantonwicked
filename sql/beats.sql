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
