DROP TABLE IF EXISTS gamingsa_wanton.request_templates;
CREATE TABLE gamingsa_wanton.request_templates (
  id int unsigned not null auto_increment primary key,
  name varchar(50) not null,
  description varchar(200) not null,
  content text not null
) ENGINE=InnoDB;

DROP TABLE IF EXISTS gamingsa_wanton.groups_request_types;
CREATE TABLE gamingsa_wanton.groups_request_types
(
    group_id int NOT NULL,
    request_type_id int NOT NULL,
    PRIMARY KEY ( group_id, request_type_id ),
    INDEX (request_type_id, group_id)
);