

-- -----------------------------------------------------
-- Table `places`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `places` ;

CREATE TABLE IF NOT EXISTS `places` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `latitude` DECIMAL(17,14) NULL,
  `longitude` DECIMAL(17,14) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'Place on the map.';

-- -----------------------------------------------------
-- Table `place_toponym`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `place_toponym` ;

CREATE TABLE IF NOT EXISTS `place_toponym` (
  `place_id` INT UNSIGNED NOT NULL,
  `toponym_id` INT UNSIGNED NOT NULL,
  INDEX `place_idx` (`place_id` ASC),
  INDEX `toponym_idx` (`toponym_id` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


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
    `wd` INT UNSIGNED NULL DEFAULT NULL COMMENT 'Wikidata identifier',
    PRIMARY KEY (`id`),
    INDEX `fk_region_idx` (`region_id` ASC))
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
    `wd` INT UNSIGNED NULL DEFAULT NULL COMMENT 'Wikidata identifier',
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
    `wd` INT UNSIGNED NULL DEFAULT NULL COMMENT 'Wikidata identifier',
  PRIMARY KEY (`id`),
  INDEX `fk_DISTRICT1926_idx` (`district1926_id` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_bin
COMMENT = 'Справочник сельсоветов по 1926 году';

-- -----------------------------------------------------
-- Table `settlements1926`
-- contains "Хирвиениеми, Хирвинаволок", "Песочки", "Vegarus", ... 
-- -----------------------------------------------------
DROP TABLE IF EXISTS `settlements1926` ;

CREATE TABLE IF NOT EXISTS `settlements1926` (
    `id` SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `selsovet_id` TINYINT UNSIGNED NOT NULL COMMENT 'selsovets1926.id',
    `name_ru` VARCHAR(150) NULL DEFAULT NULL COMMENT 'Russian name',
    `name_en` VARCHAR(150) NULL DEFAULT NULL COMMENT 'English name',
    `name_krl` VARCHAR(150) NULL DEFAULT NULL COMMENT 'Karelian name',
    `wd` INT UNSIGNED NULL DEFAULT NULL COMMENT 'Wikidata identifier',
  PRIMARY KEY (`id`),
  INDEX `fk_SELSOVET1926_idx` (`selsovet_id` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_bin
COMMENT = 'Справочник населенных пунктов по 1926 году';


-- -----------------------------------------------------
-- Table `geotypes` (old S_OBJKIND)
-- contains пожога, село, луда, пролив, плес, гора, гумно, ...
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
-- Metalevel of types of geographic objects, 
-- e.g. all water objects (Hydronym), etc.
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
COMMENT = 'Connection of tables geotypes and geocategories.';



-- -----------------------------------------------------
-- Table `structs` (old S_STRUCTURE)
-- contains dögi, рукав, объезд, против, ухаб, яма, избушка
-- -----------------------------------------------------
DROP TABLE IF EXISTS `structs` ;

CREATE TABLE IF NOT EXISTS `structs` (
  `id` SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `structhier_id` TINYINT UNSIGNED NULL COMMENT 'structhiers.id',
  `name_ru` VARCHAR(150) NULL DEFAULT NULL COMMENT 'Russian name',
  `name_en` VARCHAR(150) NULL DEFAULT NULL COMMENT 'English name',
  PRIMARY KEY (`id`),
  INDEX `structhier_idx` (`structhier_id` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_bin
COMMENT = 'toponyms structures';

-- -----------------------------------------------------
-- Table `structhiers` (old S_STRUCTUREHIERARHY)
-- contains Русские -> Детерминанты (parent_id), 
--          Прибалтийско-финские -> Детерминанты...
-- -----------------------------------------------------
DROP TABLE IF EXISTS `structhiers` ;

CREATE TABLE IF NOT EXISTS `structhiers` (
  `id` TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name_ru` VARCHAR(50) NOT NULL COMMENT 'Russian name',
  `name_en` VARCHAR(50) NULL COMMENT 'English name',
  `parent_id` TINYINT UNSIGNED NULL COMMENT 'ID of parent in the same table',
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_bin
COMMENT = 'Hierarchy tree of structures of toponyms';


-- -----------------------------------------------------
-- Table `struct_toponym` (old T_TOPONIMSTRUCT)
-- Pivot of tables structs and toponyms
-- -----------------------------------------------------
DROP TABLE IF EXISTS `struct_toponym` ;

CREATE TABLE IF NOT EXISTS `struct_toponym` (
  `struct_id` SMALLINT UNSIGNED NOT NULL,
  `toponym_id` INT UNSIGNED NOT NULL COMMENT 'Pivot of tables structs and toponyms',
  INDEX `struct_toponym_idx` (`struct_id` ASC, `toponym_id` ASC),
  INDEX `toponym_idx` (`toponym_id` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_bin
COMMENT = 'Pivot of tables structs and toponyms';



-- -----------------------------------------------------
-- Table `etymology_nations`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `etymology_nations` ;

CREATE TABLE IF NOT EXISTS `etymology_nations` (
  `id` TINYINT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'id',
  `short_ru` VARCHAR(32) NULL DEFAULT NULL COMMENT 'Short description in Russian',
  `name_ru` VARCHAR(64) NOT NULL COMMENT 'Name in Russian',
  `short_en` VARCHAR(32) NULL DEFAULT NULL COMMENT 'Short description in English',
  `name_en` VARCHAR(64) NULL DEFAULT NULL COMMENT 'Name in English',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `short_ru_UNIQUE` (`short_ru` ASC),
  UNIQUE INDEX `short_en_UNIQUE` (`short_en` ASC),
  UNIQUE INDEX `name_en_UNIQUE` (`name_en` ASC),
  UNIQUE INDEX `name_ru_UNIQUE` (`name_ru` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_bin
COMMENT = 'National sign of etymology (Russians, Baltic-Finnish, Sami and obscure).';


-- -----------------------------------------------------
-- Table `ethnos_territories`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ethnos_territories` ;

CREATE TABLE IF NOT EXISTS `ethnos_territories` (
  `id` TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name_ru` VARCHAR(50) NOT NULL COMMENT 'Russian name',
  `name_en` VARCHAR(50) NULL DEFAULT NULL COMMENT 'English name',
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_bin
COMMENT = 'Territory of settlement of ethnolocal groups of the population of Karelia ';




-- -----------------------------------------------------
-- Table `RECORDS`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `RECORDS` ;

CREATE TABLE IF NOT EXISTS `RECORDS` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'id',
  `toponym_id` INT UNSIGNED NOT NULL COMMENT 'region_id',
  `informant` VARCHAR(150) NULL DEFAULT NULL,
  `recorder` VARCHAR(150) NULL DEFAULT NULL COMMENT 'Who wrote down the toponym.',
  `record_year` SMALLINT UNSIGNED NULL DEFAULT NULL COMMENT 'Year of toponym recording.',
  `record_place` VARCHAR(150) NULL DEFAULT NULL COMMENT 'Place of toponym recording.',
  PRIMARY KEY (`id`),
  INDEX `toponym_idx` (`toponym_id` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_bin
COMMENT = 'About the informant, who, where and when wrote down the toponym.';



-- temp table
DROP TABLE IF EXISTS `T_TOPONIM` ;
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
DROP TABLE IF EXISTS `toponyms` ;

CREATE TABLE IF NOT EXISTS `toponyms` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `DISTRICT_ID` TINYINT UNSIGNED NOT NULL,
  `settlement1926_id` SMALLINT UNSIGNED NOT NULL,
  `geotype_id` TINYINT UNSIGNED NULL DEFAULT NULL COMMENT 'Type of geographic objects geotypes.id',
  `name` VARCHAR(150) NULL DEFAULT NULL COMMENT 'Name of toponym in Russian, or Karelian, or...',
  `etymology_nation_id` TINYINT UNSIGNED NULL DEFAULT NULL COMMENT 'National sign of etymology, etymology_nations.id',
  `ethnos_territory_id` TINYINT UNSIGNED NULL DEFAULT NULL COMMENT 'Territory of ethno groups of Karelia, ethnos_territories.id',
  `caseform` VARCHAR(250) NULL DEFAULT NULL COMMENT 'Locative form.',
  `accent` SMALLINT UNSIGNED NULL DEFAULT NULL,
  `legend` VARCHAR(8192) NULL DEFAULT NULL COMMENT 'Legend.',
  `foundation` SMALLINT UNSIGNED NULL DEFAULT NULL COMMENT 'Year of foundation of this object.',
  `abolition` SMALLINT UNSIGNED NULL DEFAULT NULL COMMENT 'Year of abolition (end of life) of this object.',
  `area` VARCHAR(50) NULL DEFAULT NULL COMMENT 'Cell on a map (text)',
  `SETTLEMENT` VARCHAR(150) NULL DEFAULT NULL COMMENT 'Населённый пункт.',
  PRIMARY KEY (`id`),
  INDEX `fk_district_idx` (`DISTRICT_ID` ASC),
  INDEX `fk_settlement1926_idx` (`settlement1926_id` ASC),
  INDEX `fk_geotype_idx` (`geotype_id` ASC),
  INDEX `fk_etymology_nation_idx` (`etymology_nation_id` ASC),
  INDEX `fk_ethnos_territory_idx` (`ethnos_territory_id` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_bin
COMMENT = 'toponyms - main table';


-- -----------------------------------------------------
-- Table `district_toponym`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `district_toponym` ;

CREATE TABLE IF NOT EXISTS `district_toponym` (
  `district_id` TINYINT UNSIGNED NOT NULL,
  `toponym_id` INT UNSIGNED NOT NULL,
  `include_from` SMALLINT UNSIGNED NULL COMMENT 'Start year of inclusion toponym to district.',
  `include_to` SMALLINT UNSIGNED NULL COMMENT 'End year of inclusion toponym to district.',
  INDEX `district_idx` (`district_id` ASC),
  INDEX `toponym_idx` (`toponym_id` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


