CREATE TABLE IF NOT EXISTS `thesaurus`.`word` (
    `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `value` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
    UNIQUE KEY `unique_value` (`value`),
    PRIMARY KEY (`id`)
) ENGINE = InnoDB;
CREATE TABLE `thesaurus`.`synonym` (
    `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `word_id` INT(10) UNSIGNED NOT NULL,
    `value` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
    UNIQUE KEY `unique_value` (`value`),
    PRIMARY KEY (`id`)
) ENGINE = InnoDB;
ALTER TABLE `thesaurus`.`synonym`
  ADD CONSTRAINT `fk_word_id` FOREIGN KEY (`word_id`) REFERENCES `word` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;