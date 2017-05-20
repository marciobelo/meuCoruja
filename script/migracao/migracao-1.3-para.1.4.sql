use `Coruja`;

create table `CriterioAvaliacao` (
    `idCriterioAvaliacao` BIGINT NOT NULL AUTO_INCREMENT,
    `rotulo` VARCHAR(40) NOT NULL,
    primary key (`idCriterioAvaliacao`)
) ENGINE = InnoDB;

create table `ItemCriterioAvaliacao` (
    `idItemCriterioAvaliacao` BIGINT NOT NULL AUTO_INCREMENT,
    `idCriterioAvaliacao` BIGINT NOT NULL,
    `rotulo` VARCHAR(20) NOT NULL,
    `descricao` VARCHAR(80) NOT NULL,
    `ordem` INT NOT NULL,
    `tipo` ENUM('LANÇADO','CALCULADO') NOT NULL,
    `formulaCalculo` VARCHAR(255) NULL,
    primary key (`idItemCriterioAvaliacao`),
	constraint `fk_ItemCriterioAvaliacao_CriterioAvaliacao`
		foreign key (`idCriterioAvaliacao`)
		references `CriterioAvaliacao` (`idCriterioAvaliacao`)
		on delete no action
		on update no action
) ENGINE = InnoDB;

create table `ItemCriterioAvaliacaoInscricaoNota` (
	`idItemCriterioAvaliacao` BIGINT NOT NULL,
	`idTurma` INT UNSIGNED NOT NULL ,
	`matriculaAluno` VARCHAR(15) NOT NULL ,
	`nota` DECIMAL(5,1) NULL,
	`comentario` VARCHAR(255) NULL,
    `dataNotificacao` TIMESTAMP NULL comment 'Data/hora em que a nota lançada (e alterada) foi encaminhada para o aluno',
 	primary key (`idItemCriterioAvaliacao`, `idTurma`, `matriculaAluno`),
	constraint `fk_ItemCriterioAvaliacaoInscricaoNota_ItemCriterioAvaliacao`
		foreign key (`idItemCriterioAvaliacao`)
		references `ItemCriterioAvaliacao` (`idItemCriterioAvaliacao`)
		on delete no action
		on update no action,
	constraint `fk_ItemCriterioAvaliacaoInscricaoNota_Inscricao`
		foreign key (`idTurma`,`matriculaAluno`)
		references `Inscricao` (`idTurma`,`matriculaAluno`)
		on delete cascade
		on update cascade
) ENGINE = InnoDB;

create table `ItemCriterioAvaliacaoTurmaLiberada` (
	`idTurma` INT UNSIGNED NOT NULL ,
	`idItemCriterioAvaliacao` BIGINT NOT NULL,
	`dataLiberacao` TIMESTAMP NOT NULL default NOW() comment 'Data/hora em que a categoria de notas foi divulgada',
	primary key (`idTurma`, `idItemCriterioAvaliacao`),
	constraint `fk_ItemCriterioAvaliacaoTurmaLiberada_ItemCriterioAvaliacao`
		foreign key (`idItemCriterioAvaliacao`)
		references `ItemCriterioAvaliacao` (`idItemCriterioAvaliacao`)
		on delete no action
		on update no action,
	constraint `fk_ItemCriterioAvaliacaoTurmaLiberada_Turma`
		foreign key (`idTurma`)
		references `Turma` (`idTurma`)
		on delete no action
		on update no action
) ENGINE = InnoDB;

insert into CriterioAvaliacao (idCriterioAvaliacao,rotulo) values (1,'padrão');

insert into ItemCriterioAvaliacao (idItemCriterioAvaliacao,idCriterioAvaliacao,rotulo,descricao,ordem,tipo,formulaCalculo) values
(1,
1,
'AV1',
'Avaliação 1',
1,
'LANÇADO',
null),
(2,
1,
'AV2',
'Avaliação 2',
2,
'LANÇADO',
null),
(3,
1,
'MÉDIA',
'Média aritmética entre AV1 e AV2',
3,
'CALCULADO',
'( AV1 === null && AV2 === null ? "" : ((AV1 + AV2) / 2) )' ),
(4,
1,
'AVF',
'Avaliação Final',
4,
'LANÇADO',
null),
(5,
1,
'FINAL',
'Média aritmética entre Média AV1-AV2 e AVF',
5,
'CALCULADO',
'( FALTAS > LIMITE_FALTAS ? "0.0" : ( MÉDIA === null && AVF === null ? "" : ( MÉDIA >= 7 ? MÉDIA : ( MÉDIA + AVF ) / 2 ) ) )'),
(6,
1,
'SITUAÇÃO',
'Situação final do aluno',
6,
'CALCULADO',
'(FALTAS > LIMITE_FALTAS ? "RF" : ( AV1 === null || AV2 === null ? "Em avaliação" : ( MÉDIA < 4 ? "RM" : ( MÉDIA >= 7 || FINAL >= 6 ? "AP" : ( AVF === null ? "Em final" : "RM" ) ) ) ) )');


alter table Turma
	add column `dataLiberacaoPautaPeloProfessor` TIMESTAMP NULL comment 'Data em que o professor indicou que a pauta está liberada para a secretaria'; 

alter table Turma
	add column `idCriterioAvaliacao` BIGINT NOT NULL default 1 comment 'Critério de avaliação usado por essa turma'; 

alter table Turma
    add CONSTRAINT `fk_Turma_CriterioAvaliacao`
    FOREIGN KEY (`idCriterioAvaliacao` )
    REFERENCES `CriterioAvaliacao` (`idCriterioAvaliacao` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION;

insert into `Funcao` (idCasoUso, descricao,critico) VALUE ('UC08.00.04', 'Liberar Notas de Turma', 'SIM');
insert into `Funcao` (idCasoUso, descricao,critico) VALUE ('UC08.00.05', 'Reabrir Notas de Turma', 'SIM');
insert into `Funcao` (idCasoUso, descricao,critico) VALUE ('UC08.00.06', 'Liberar Pauta de Turma', 'SIM');

insert into `Funcao` (idCasoUso, descricao,critico) VALUE ('UC01.03.05', 'Devolver Pauta de Turma ao Professor', 'NÃO');

commit;
