ALTER TABLE ComponenteCurricular ADD COLUMN `posicaoPeriodo` INT UNSIGNED DEFAULT 0 NOT NULL;


CREATE TABLE IF NOT EXISTS `GrupoFuncao` (
  `id` INT NOT NULL AUTO_INCREMENT, 
  `nome` VARCHAR(255) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `FuncaoPorGrupo` (
  `id` INT NOT NULL AUTO_INCREMENT, 
  `idGrupo` INT NOT NULL,
  `idCasoUso` CHAR(16) NOT NULL,
  PRIMARY KEY (`id`),
CONSTRAINT `fk_FuncaoPorGrupo_Funcao`
    FOREIGN KEY (`idCasoUso` )
    REFERENCES `Funcao` (`idCasoUso` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;

CREATE  TABLE IF NOT EXISTS `MatrizCurricularProposta` (
  `siglaCurso` CHAR(6) NOT NULL ,
  `idMatrizVigente` INT UNSIGNED NOT NULL ,
  `totalPeriodos` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`siglaCurso`, `idMatrizVigente`))
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `ComponenteCurricularProposto` (
`siglaCurso` CHAR(6) NOT NULL ,
`idMatriz` INT UNSIGNED NOT NULL ,
`siglaDisciplina` CHAR(6) NOT NULL ,
`nomeDisciplina` VARCHAR(80) NOT NULL ,
`creditos` INT UNSIGNED NOT NULL COMMENT 'Quantidade de horas/aula por semana. A disciplina terá uma carga horária no semestre PREVISTA de 20 vezes o número de créditos.' ,
`cargaHoraria` INT NOT NULL ,
`periodo` INT UNSIGNED NOT NULL COMMENT 'Período na qual a disciplina se encontra na matriz curricular' ,
`tipoComponenteCurricular` ENUM('OBRIGATÓRIA','ELETIVA', 'OPTATIVA') NOT NULL ,
`posicaoPeriodo` INT UNSIGNED NOT NULL ,
PRIMARY KEY (`siglaDisciplina`, `idMatriz`, `siglaCurso`));

CREATE TABLE IF NOT EXISTS `PreRequisitoProposto` (
`siglaCurso` CHAR(6) NOT NULL ,
`idMatriz` INT UNSIGNED NOT NULL ,
`siglaDisciplina` CHAR(6) NOT NULL ,
`siglaPreRequisito` CHAR(6) NOT NULL ,
PRIMARY KEY (`siglaCurso`, `idMatriz`, `siglaDisciplina`, `siglaPreRequisito`));

CREATE TABLE IF NOT EXISTS `EquivalenciaProposta` (
`siglaCurso` CHAR(6) NOT NULL ,
`idMatriz` INT UNSIGNED NOT NULL ,
`siglaDisciplina` CHAR(6) NOT NULL ,
`siglaEquivalencia` CHAR(6) NOT NULL ,
PRIMARY KEY (`siglaDisciplina`, `idMatriz`, `siglaCurso`, `siglaEquivalencia`));




-- carga dados basicos

-- MMC-GPL
-- Novas Permissoes
INSERT INTO Funcao VALUES ('UC09.01.00', 'Manter Permissões', 'SIM');
INSERT INTO Funcao VALUES ('UC09.01.01', 'Atribuir Permissão', 'SIM');
INSERT INTO Funcao VALUES ('UC09.01.02', 'Remover Permissão', 'SIM');
INSERT INTO Funcao VALUES ('UC09.01.03', 'Atribuir Grupo de Permissões', 'SIM');
INSERT INTO Funcao VALUES ('UC09.01.04', 'Remover Grupo de Permissões', 'SIM');

-- Permissoes dos grupos
INSERT INTO Funcao values ('UC09.02.00', 'Manter Grupo de Permissões', 'SIM');
INSERT INTO Funcao values ('UC09.02.01', 'Incluir Grupo de Permissões', 'SIM');
INSERT INTO Funcao values ('UC09.02.02', 'Excluir Grupo de Permissões', 'SIM');
INSERT INTO Funcao values ('UC09.02.03', 'Editar Grupo de Permissões', 'SIM');

-- Gerencia de Log
INSERT INTO Funcao values ('UC10.01.00', 'Buscar Log', 'SIM');

-- Matriz porposta-- 
INSERT INTO Funcao values ('UC11.01.00', 'Manter Matriz Curricular Proposta', 'SIM');
INSERT INTO Funcao values ('UC11.01.01', 'Criar Matriz Curricular Proposta', 'SIM');
INSERT INTO Funcao values ('UC11.01.02', 'Editar Matriz Curricular Proposta', 'SIM');
INSERT INTO Funcao values ('UC11.01.02.00', 'Manter Componente Curricular Proposto', 'SIM');
INSERT INTO Funcao values ('UC11.01.02.01', 'Adicionar Componente Curricular Proposto', 'SIM');
INSERT INTO Funcao values ('UC11.01.02.02', 'Editar Componente Curricular Proposto', 'SIM');
INSERT INTO Funcao values ('UC11.01.02.03', 'Excluir Componente Curricular Proposto', 'SIM');
INSERT INTO Funcao values ('UC11.01.02.04', 'Validar Matriz Curricular Proposta', 'SIM');
INSERT INTO Funcao values ('UC11.01.03', 'Excluir Matriz Curricular Proposta', 'SIM');
INSERT INTO Funcao values ('UC11.01.04', 'Imprimir Matriz Curricular', 'SIM');

insert into Permite (idPessoa,idCasoUso)
 (select 1029,idCasoUso from Funcao f
	where not exists (select * from Permite pe2 where idPessoa=1029 and pe2.idCasoUso=f.idCasoUso) );

commit;

alter table Login
 ADD COLUMN `tentativas` INT UNSIGNED DEFAULT 0 NOT NULL;

create table if not exists `LoginErro`
(
`dataHoraRegistro` TIMESTAMP NOT NULL DEFAULT NOW(),
`texto` TEXT not null
);

