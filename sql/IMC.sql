SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema imc-master
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `imc-master` DEFAULT CHARACTER SET utf8mb4;

USE `imc-master`;

-- -----------------------------------------------------
-- Table `imc-master`.`usuario`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `imc-master`.`usuario` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(45) NOT NULL,
  `email` VARCHAR(45) NOT NULL,
  `login` VARCHAR(45) NOT NULL,
  `senha` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8mb4;

-- -----------------------------------------------------
-- Table `imc-master`.`medidas`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `imc-master`.`medidas` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `peso` DECIMAL(5,2) NOT NULL,
  `altura` DECIMAL(3,2) NOT NULL,
  `data` DATE NOT NULL,
  `usuario_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_medidas_usuario` (`usuario_id` ASC),
  CONSTRAINT `fk_medidas_usuario`
    FOREIGN KEY (`usuario_id`)
    REFERENCES `imc-master`.`usuario` (`id`)
) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8mb4;

-- -----------------------------------------------------
-- Efaz referência à coluna `id` na tabela `usuario`. A palavra-chave `ASC` é usada para especificar que os valores da coluna `usuario_id` devem ser ordenados em ordem crescente. A declaração `CONSTRAINT` é usada para dar um nome à chave estrangeira e, assim, identificá-la de forma única na tabela.

-- -----------------------------------------------------



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
