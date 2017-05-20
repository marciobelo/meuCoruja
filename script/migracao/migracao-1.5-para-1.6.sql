-- formato preparado para execucao por linha de comando mysql
create table `ResumoApontamentoDiaLetivo` ( \
`idTurma` INT UNSIGNED NOT NULL, \
`matriculaAluno` VARCHAR(15) NOT NULL, \
`data` DATE NOT NULL, \
`resumo` varchar(255) NULL comment 'String onde cada caractere representa um status quanto a presença na aula', \
primary key (`idTurma`,`matriculaAluno`,`data`), \
constraint `fk_ResumoApontamentoDiaLetivo_Inscricao` \
foreign key (`idTurma`,`matriculaAluno`) \
references `Inscricao` (`idTurma`,`matriculaAluno`) \
on delete cascade \
on update cascade \
) ENGINE = InnoDB;
