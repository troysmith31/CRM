DROP TABLE IF EXISTS `survey_definitions`;
CREATE TABLE `survey_definitions` (
  `survey_definition_id` INT NOT NULL auto_increment,
  `name` VARCHAR(256) NOT NULL,
  `definition` text,
  `owner_per_id` mediumint(9) unsigned NOT NULL,
  PRIMARY KEY (`survey_definition_id`)
) ENGINE=InnoDB CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS `survey_responses`;
CREATE TABLE `survey_responses` (
  `survey_response_id` INT NOT NULL auto_increment,
  `date_submitted` DATETIME default NULL,
  `survey_definition_id` INT NOT NULL,
  `response` text,
  PRIMARY KEY (`survey_response_id`)
) ENGINE=InnoDB CHARACTER SET utf8 COLLATE utf8_unicode_ci;

ALTER TABLE tokens
 ADD COLUMN `meta_data` text AFTER `remainingUses`;