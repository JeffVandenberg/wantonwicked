CREATE TABLE character_notes
(
  id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  character_id INT NOT NULL,
  user_id INT NOT NULL,
  created datetime NOT NULL,
  note TEXT
);
CREATE INDEX character_notes_character_id_index ON character_notes (character_id);
CREATE INDEX character_notes_user_id_index ON character_notes (user_id);
