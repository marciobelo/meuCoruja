-- -------------------------------------------------------------
-- -------------------------------------------------------------
-- SCE
-- -------------------------------------------------------------
-- -------------------------------------------------------------


DROP DATABASE IF EXISTS `sce`;
CREATE SCHEMA IF NOT EXISTS `sce`;
USE `sce`;

-- -----------------------------------------------------
-- Table `sce`.`CategoriaAtividade`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `sce`.`CategoriaAtividade` (
  `id` BIGINT NOT NULL AUTO_INCREMENT ,
  `nome` VARCHAR(45) NOT NULL ,
  UNIQUE KEY `nome` (`nome`), 
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `sce`.`CategoriaEvento`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `sce`.`CategoriaEvento` (
  `id` BIGINT NOT NULL AUTO_INCREMENT ,
  `nome` VARCHAR(45) NOT NULL ,
  UNIQUE KEY `nome` (`nome`), 
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `sce`.`Tema`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `sce`.`Tema` (
  `id` BIGINT NOT NULL AUTO_INCREMENT ,
  `nome` VARCHAR(45) NOT NULL ,
  UNIQUE KEY `nome` (`nome`), 
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `sce`.`Evento`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `sce`.`Evento` (
  `id` BIGINT NOT NULL AUTO_INCREMENT ,
  `nome` VARCHAR(80) NOT NULL ,
  `dtInicial` DATE NOT NULL ,
  `dtFinal` DATE NOT NULL ,
  `idCategoriaEvento` BIGINT NOT NULL ,
  `idPessoa` BIGINT NOT NULL ,
  `idTema` BIGINT NOT NULL ,
  UNIQUE KEY `nome` (`nome`),
  PRIMARY KEY (`id`) ,
  INDEX `fk_evento_Pessoa` (`idPessoa` ASC) ,
  INDEX `fk_CategoriaEvento` (`idCategoriaEvento` ASC) ,
  INDEX `fk_Tema` (`idTema` ASC) ,
  CONSTRAINT `fk_CategoriaEvento`
    FOREIGN KEY (`idCategoriaEvento` )
    REFERENCES `sce`.`CategoriaEvento` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_evento_Pessoa`
    FOREIGN KEY (`idPessoa` )
    REFERENCES `Coruja`.`Pessoa` (`idPessoa` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Tema`
    FOREIGN KEY (`idTema` )
    REFERENCES `sce`.`Tema` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `sce`.`Atividade`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `sce`.`Atividade` (
  `id` BIGINT NOT NULL AUTO_INCREMENT ,
  `nome` VARCHAR(200) NOT NULL ,
  `data` DATE NOT NULL ,
  `horaInicial` TIME NOT NULL ,
  `horaFinal` TIME NOT NULL ,
  `horaExtensao` INT NOT NULL ,
  `idCategoriaAtividade` BIGINT NOT NULL ,
  `idEvento` BIGINT NOT NULL ,
  `idPalestrante` BIGINT NULL DEFAULT NULL ,
  `idEspaco` SMALLINT UNSIGNED NULL ,
  `bloqueado` TINYINT(1) NOT NULL DEFAULT '0',
  `tipoPalestrante` VARCHAR(1) NOT NULL DEFAULT "I",
  `customEspaco` VARCHAR(200) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) ,
  INDEX `fk_CategoriaAtividade` (`idCategoriaAtividade` ASC) ,
  INDEX `fk_atividade_Evento` (`idEvento` ASC) ,
  INDEX `fk_palestra_Pessoa` (`idPalestrante` ASC) ,
  INDEX `fk_Espaco` (`idEspaco` ASC) ,
  CONSTRAINT `fk_CategoriaAtividade`
    FOREIGN KEY (`idCategoriaAtividade` )
    REFERENCES `sce`.`CategoriaAtividade` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_atividade_Evento`
    FOREIGN KEY (`idEvento` )
    REFERENCES `sce`.`Evento` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Espaco`
    FOREIGN KEY (`idEspaco` )
    REFERENCES `Coruja`.`Espaco` (`idEspaco` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `sce`.`Avaliacao`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `sce`.`Avaliacao` (
  `id` BIGINT NOT NULL AUTO_INCREMENT ,
  `sugestao` TEXT NULL ,
  `sugestaoEvento` TEXT NULL ,
  `status` TINYINT(1) NOT NULL DEFAULT '0' ,
  `idPessoa` BIGINT NOT NULL ,
  `idAtividade` BIGINT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_aval_Pessoa` (`idPessoa` ASC) ,
  INDEX `fk_aval_Atividade` (`idAtividade` ASC) ,
  CONSTRAINT `fk_aval_Pessoa`
    FOREIGN KEY (`idPessoa` )
    REFERENCES `Coruja`.`Pessoa` (`idPessoa` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_aval_Atividade`
    FOREIGN KEY (`idAtividade` )
    REFERENCES `sce`.`Atividade` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `sce`.`Aspecto`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `sce`.`Aspecto` (
  `id` BIGINT NOT NULL AUTO_INCREMENT ,
  `nome` VARCHAR(45) NOT NULL ,
  UNIQUE KEY `nome` (`nome`), 
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `sce`.`AvaliacaoAspecto`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `sce`.`AvaliacaoAspecto` (
  `id` BIGINT NOT NULL AUTO_INCREMENT ,
  `idAspecto` BIGINT NOT NULL ,
  `idAvaliacao` BIGINT NOT NULL ,
  `resposta` TINYINT(5) NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_Aspecto` (`idAspecto` ASC) ,
  INDEX `fk_Avaliacao` (`idAvaliacao` ASC) ,
  CONSTRAINT `fk_Aspecto`
    FOREIGN KEY (`idAspecto` )
    REFERENCES `sce`.`Aspecto` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Avaliacao`
    FOREIGN KEY (`idAvaliacao` )
    REFERENCES `sce`.`Avaliacao` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `sce`.`Administrador`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sce`.`Administrador` (
  `idPessoa` BIGINT NOT NULL AUTO_INCREMENT,
  PRIMARY KEY  (`idPessoa`) ,
  INDEX `fk_Pessoa` (`idPessoa` ASC) ,
  CONSTRAINT `fk_Pessoa`
    FOREIGN KEY (`idPessoa` )
    REFERENCES `Coruja`.`Pessoa` (`idPessoa` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
) ENGINE=InnoDB;

-- -----------------------------------------------------
-- Table `sce`.`Externo`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sce`.`Externo` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(45) NOT NULL,
  `instituicao` VARCHAR(100) NOT NULL,
  `telefone` VARCHAR(15) NULL,
  `email` VARCHAR(50) NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB;
