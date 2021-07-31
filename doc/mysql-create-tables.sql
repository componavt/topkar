
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
COLLATE = utf8_bin
COMMENT = 'Types of geographic objects (geo features).';


-- -----------------------------------------------------
-- Table `geocategories`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `geocategories` ;

CREATE TABLE IF NOT EXISTS `geocategories` (
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
COLLATE = utf8_bin
COMMENT = 'Metalevel of types of geographic objects.';


-- -----------------------------------------------------
-- Table `geotype_category` (pivot table)
-- -----------------------------------------------------
DROP TABLE IF EXISTS `geotype_category` ;

CREATE TABLE IF NOT EXISTS `geotype_category` (
  `id` TINYINT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'id',
  `geotype_id` TINYINT UNSIGNED NOT NULL COMMENT 'id',
  `geocategory_id` TINYINT UNSIGNED NOT NULL COMMENT 'id',
  PRIMARY KEY (`id`),
  INDEX `type_category_idx` (`geotype_id` ASC, `geocategory_id` ASC),
  INDEX `fk_geocategory_id_idx` (`geocategory_id` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'Metalevel of types of geographic objects.';

-- -----------------------------------------------------
-- Table `regions` contains Vologda Oblast, Finland, ...
-- -----------------------------------------------------
DROP TABLE IF EXISTS `regions` ;

CREATE TABLE IF NOT EXISTS `regions` (
  `id` TINYINT UNSIGNED NOT NULL,
  `name_ru` VARCHAR(150) NOT NULL COMMENT 'Russian name',
  `name_en` VARCHAR(150) NULL DEFAULT NULL COMMENT 'English name',
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_bin;

-- -----------------------------------------------------
-- Table district (old S_DISTRICT), 
-- contains Коряжма г., Муезерский район, Город Сегежа и Сегежский район, ... 
-- -----------------------------------------------------
DROP TABLE IF EXISTS `districts` ;

CREATE TABLE IF NOT EXISTS `districts` (
    id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT, /* codedistrict */
    region_id TINYINT UNSIGNED NOT NULL, /* coderegion */
    name_ru VARCHAR(150) NOT NULL COMMENT 'Russian name',/* name VARCHAR(84) CHARACTER SET UTF8, */
    name_en VARCHAR(150) NULL DEFAULT NULL COMMENT 'English name',
    `foundation` SMALLINT UNSIGNED NULL COMMENT 'Year of foundation of this region.',
    `abolition` SMALLINT UNSIGNED NULL COMMENT 'Year of abolition (end of life) of this region.',
    `geotype_id` TINYINT UNSIGNED NULL COMMENT 'Type of geographic objects geotypes.id',
    `wd` INT UNSIGNED NULL DEFAULT NULL COMMENT 'Wikidata identifier',
    PRIMARY KEY (`id`),
    INDEX `fk_region_idx` (`region_id` ASC),
    INDEX `fk_geotype_idx` (`geotype_id` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_bin
COMMENT = 'Справочник районов (современное местоположение)';


-- -----------------------------------------------------
-- Table districts1926 (old S_DISTRICT1926)
-- contains Шальский район, Тунгудский район
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
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_bin
COMMENT = 'Справочник районов по 1926 году';

-- -----------------------------------------------------
-- Table selsovets1926 (old S_SELSOVET1926)
-- contains Äyräpää, Sortavala (Sordavala) город, Саримяжский, ... 
-- -----------------------------------------------------
DROP TABLE IF EXISTS `selsovets1926` ;

CREATE TABLE IF NOT EXISTS `selsovets1926` (
  `id` TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `district1926_id` TINYINT UNSIGNED NOT NULL COMMENT 'district1926.id',
  `name_ru` VARCHAR(150) NULL DEFAULT NULL COMMENT 'Russian name',
  `name_en` VARCHAR(150) NULL DEFAULT NULL COMMENT 'English name',
  `name_krl` VARCHAR(150) NULL DEFAULT NULL COMMENT 'Karelian name',
  PRIMARY KEY (`id`),
  INDEX `fk_DISTRICT1926_idx` (`district1926_id` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_bin
COMMENT = 'Справочник сельсоветов по 1926 году';

-- -----------------------------------------------------
-- Table `settlements1926`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `settlements1926` ;

CREATE TABLE IF NOT EXISTS `settlements1926` (
  `id` SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `selsovet_id` TINYINT UNSIGNED NOT NULL COMMENT 'selsovets1926.id',
  `name_ru` VARCHAR(150) NULL DEFAULT NULL COMMENT 'Russian name',
  `name_en` VARCHAR(150) NULL DEFAULT NULL COMMENT 'English name',
  `name_krl` VARCHAR(150) NULL DEFAULT NULL COMMENT 'Karelian name',
  PRIMARY KEY (`id`),
  INDEX `fk_SELSOVET1926_idx` (`selsovet_id` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_bin
COMMENT = 'Справочник населенных пунктов по 1926 году';

-- -----------------------------------------------------
-- Table `structs`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `structs` ;

CREATE TABLE IF NOT EXISTS `structs` (
  `id` SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `structhier_id` TINYINT UNSIGNED NULL COMMENT 'structhiers.id',
  `name_ru` VARCHAR(150) NULL DEFAULT NULL COMMENT 'Russian name',
  `name_en` VARCHAR(150) NULL DEFAULT NULL COMMENT 'English name',
  `name_krl` VARCHAR(150) NULL DEFAULT NULL COMMENT 'Karelian name',
  PRIMARY KEY (`id`),
  INDEX `structhier_idx` (`structhier_id` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_bin
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
COLLATE = utf8_bin
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

-- -----------------------------------------------------
-- Table `toponym`  // old title: T_TOPONIM
-- -----------------------------------------------------
DROP TABLE IF EXISTS `toponym` ;

CREATE TABLE IF NOT EXISTS `toponym` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `district_id` TINYINT UNSIGNED NOT NULL,
  `settlement1926_id` SMALLINT UNSIGNED NOT NULL,
  `geotype_id` TINYINT UNSIGNED NOT NULL COMMENT 'Type of geographic objects geotypes.id',
  `name_en` VARCHAR(150) NULL,
  `foundation` SMALLINT UNSIGNED NULL COMMENT 'Year of foundation of this region.',
  `abolition` SMALLINT UNSIGNED NULL COMMENT 'Year of abolition (end of life) of this region.',
  PRIMARY KEY (`id`),
  INDEX `fk_district_idx` (`district_id` ASC),
  INDEX `fk_settlement1926_idx` (`settlement1926_id` ASC),
  INDEX `fk_geotype_idx` (`geotype_id` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_bin
COMMENT = 'toponyms - main table';
