DROP SCHEMA IF EXISTS `db_tienda` ;
CREATE SCHEMA IF NOT EXISTS `db_tienda`;

  CREATE TABLE IF NOT EXISTS `db_tienda`.`usuarios` (
  `usuario` VARCHAR(12) NOT NULL,
  `contrasena` VARCHAR(255) NOT NULL,
  `fechaNacimiento` DATE NOT NULL,
  `rol` VARCHAR(10) NULL DEFAULT 'cliente',
  PRIMARY KEY (`usuario`));

  CREATE TABLE IF NOT EXISTS `db_tienda`.`productos` (
  `idProducto` INT NOT NULL AUTO_INCREMENT,
  `nombreProducto` VARCHAR(40) NOT NULL,
  `precio` DECIMAL(7,2) NOT NULL,
  `descripcion` VARCHAR(255) NOT NULL,
  `cantidad` INT NOT NULL,
  `imagen` VARCHAR(255) NULL DEFAULT NULL,
  PRIMARY KEY (`idProducto`));

CREATE TABLE IF NOT EXISTS `db_tienda`.`cestas` (
  `idCestas` INT NOT NULL AUTO_INCREMENT,
  `usuario` VARCHAR(12) NOT NULL,
  `precioTotal` DECIMAL(7,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`idCestas`),
  INDEX `usuario` (`usuario` ASC) VISIBLE,
  CONSTRAINT `cestas_ibfk_1`
    FOREIGN KEY (`usuario`)
    REFERENCES `db_tienda`.`usuarios` (`usuario`));

CREATE TABLE IF NOT EXISTS `db_tienda`.`pedidos` (
  `idPedido` INT NOT NULL AUTO_INCREMENT,
  `usuario` VARCHAR(12) NOT NULL,
  `precioTotal` DECIMAL(7,2) NOT NULL,
  `fechaPedido` DATE NOT NULL,
  PRIMARY KEY (`idPedido`),
  INDEX `usuario` (`usuario` ASC) VISIBLE,
  CONSTRAINT `pedidos_ibfk_1`
    FOREIGN KEY (`usuario`)
    REFERENCES `db_tienda`.`usuarios` (`usuario`));

CREATE TABLE IF NOT EXISTS `db_tienda`.`productoscestas` (
  `idProducto` INT NOT NULL,
  `idCesta` INT NOT NULL,
  `cantidad` DECIMAL(2,0) NOT NULL,
  PRIMARY KEY (`idProducto`, `idCesta`),
  INDEX `idCesta` (`idCesta` ASC) VISIBLE,
  CONSTRAINT `productoscestas_ibfk_1`
    FOREIGN KEY (`idProducto`)
    REFERENCES `db_tienda`.`productos` (`idProducto`),
  CONSTRAINT `productoscestas_ibfk_2`
    FOREIGN KEY (`idCesta`)
    REFERENCES `db_tienda`.`cestas` (`idCestas`));

