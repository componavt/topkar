
-- -----------------------------------------------------
-- Table `geotypes`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `geotypes` ;

CREATE TABLE IF NOT EXISTS `geotypes` (
  `id` TINYINT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'id',
  `short_ru` VARCHAR(32) NULL DEFAULT NULL COMMENT 'Short description in Russian',
  `name_ru` VARCHAR(64) NOT NULL COMMENT 'Name in Russian',
  `desc_ru` VARCHAR(255) NULL DEFAULT NULL COMMENT 'Description in Russian',
  `short_en` VARCHAR(32) NULL DEFAULT NULL COMMENT 'Short description in English',
  `name_en` VARCHAR(64) NULL DEFAULT NULL COMMENT 'Name in English',
  `desc_en` VARCHAR(255) NULL DEFAULT NULL COMMENT 'Description in English',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `short_ru_UNIQUE` (`short_ru` ASC),
  UNIQUE INDEX `short_en_UNIQUE` (`short_en` ASC),
  UNIQUE INDEX `name_en_UNIQUE` (`name_en` ASC),
  UNIQUE INDEX `name_ru_UNIQUE` (`name_ru` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'Types of geographic objects.';

-- -----------------------------------------------------
-- Table `regions`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `regions` ;

CREATE TABLE IF NOT EXISTS `regions` (
  `id` TINYINT UNSIGNED NOT NULL,
  `name_ru` VARCHAR(150) NOT NULL,
  `name_en` VARCHAR(150) CHARACTER SET 'utf8' NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table district (old S_DISTRICT)
-- -----------------------------------------------------
DROP TABLE IF EXISTS `districts` ;

CREATE TABLE IF NOT EXISTS `districts` (
    id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT, /* codedistrict */
    region_id TINYINT UNSIGNED NOT NULL, /* coderegion */
    name_ru VARCHAR(150) NOT NULL,      /* name VARCHAR(84) CHARACTER SET UTF8, */
    `name_en` VARCHAR(150) NULL,
    `foundation` SMALLINT UNSIGNED NULL COMMENT 'Year of foundation of this region.',
    `abolition` SMALLINT UNSIGNED NULL COMMENT 'Year of abolition (end of life) of this region.',
    `type_id` TINYINT UNSIGNED NULL COMMENT 'Type of geographic objects geotypes.id',
    PRIMARY KEY (`id`),
    INDEX `fk_region_idx` (`region_id` ASC),
    INDEX `fk_geotype_idx` (`type_id` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table districts1926 (old S_DISTRICT1926)
-- -----------------------------------------------------
DROP TABLE IF EXISTS `districts1926` ; /* S_DISTRICT1926 */

CREATE TABLE IF NOT EXISTS `districts1926` (
  `id` TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `region_id` TINYINT UNSIGNED NOT NULL,
  `name_ru` VARCHAR(150) NOT NULL,
  `name_en` VARCHAR(150) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_region_idx` (`region_id` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

-- -----------------------------------------------------
-- Table selsovets1926 (old S_SELSOVET1926)
-- -----------------------------------------------------
DROP TABLE IF EXISTS `selsovets1926` ;

CREATE TABLE IF NOT EXISTS `selsovets1926` (
  `id` TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `district1926_id` TINYINT UNSIGNED NOT NULL COMMENT 'district1926_id',
  `name_ru` VARCHAR(150) NOT NULL,
  `name_en` VARCHAR(150) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_DISTRICT1926_idx` (`district1926_id` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

-- -----------------------------------------------------
-- Table `settlements1926`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `settlements1926` ;

CREATE TABLE IF NOT EXISTS `settlements1926` (
  `id` SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `selsovet_id` TINYINT UNSIGNED NOT NULL COMMENT 'selsovets1926.id',
  `name_ru` VARCHAR(150) NOT NULL,
  `name_en` VARCHAR(150) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_SELSOVET1926_idx` (`selsovet_id` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

-- -----------------------------------------------------
-- Table `structs`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `structs` ;

CREATE TABLE IF NOT EXISTS `structs` (
  `id` SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `structhier_id` TINYINT UNSIGNED NULL COMMENT 'structhiers.id',
  `text` VARCHAR(150) NOT NULL COMMENT 'structure temp name',
  `name_ru` VARCHAR(150) NULL DEFAULT NULL,
  `name_en` VARCHAR(150) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `structhier_idx` (`structhier_id` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'toponyms structures';

-- -----------------------------------------------------
-- Table `structhiers`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `structhiers` ;

CREATE TABLE IF NOT EXISTS `structhiers` (
  `id` TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name_ru` VARCHAR(50) NOT NULL COMMENT 'structhiers.id',
  `name_en` VARCHAR(50) NULL COMMENT 'structhiers.id',
  `parent_id` SMALLINT UNSIGNED NULL COMMENT 'structure name',
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'Hierarchy tree of structures of toponyms';


-- temp table
/* Table: T_TOPONIM, Owner: SYSDBA */
CREATE TABLE T_TOPONIM (CODETOPONIM INTEGER NOT NULL,
        CODEDISTRICT INTEGER,
        CODESETT1926 INTEGER,
        CODEKIND INTEGER,
        CODESIGN INTEGER,
        CODETERRITORY INTEGER,
        NAME VARCHAR(150),
        TRANSCRIPTION VARCHAR(150),
        CASEFORM VARCHAR(150),
        VARIANTS VARCHAR(150),
        ACCENT INTEGER,
        SELSOVET VARCHAR(150),
        SETTLEMENT VARCHAR(150),
        MAPSNOMENCLATURE VARCHAR(50) CHARACTER SET UTF8,
        AREA VARCHAR(50) CHARACTER SET UTF8,
        OBJNUM VARCHAR(150),
        PASSPORT VARCHAR(150),
        DETERMINANT VARCHAR(150),
        SOURCE VARCHAR(2048),
        ETIMOLOGY VARCHAR(2048),
        LEGEND VARCHAR(2048),
        MAP SMALLINT,
        SELECTTRANSCRIPTION BOOLEAN,
CONSTRAINT PK_T_TOPONIM PRIMARY KEY (CODETOPONIM));

