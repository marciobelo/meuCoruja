INSERT INTO `MatrizCurricular` (`siglaCurso`, `idMatriz`, `dataInicioVigencia`) VALUES ('TASI', 6, '2018-01-01');

INSERT INTO `ComponenteCurricular` (`siglaCurso`, `idMatriz`, `siglaDisciplina`, `nomeDisciplina`, 
	`creditos`, `cargaHoraria`, `periodo`, `tipoComponenteCurricular`) VALUES 

	('TASI', 6,'1MAC','Matemática para Computação',4,80,1,'OBRIGATÓRIA'),
	('TASI', 6,'1MAB','Matemática Básica',4,80,1,'OBRIGATÓRIA'),
	('TASI', 6,'1LPO','Língua Portuguesa',4,80,1,'OBRIGATÓRIA'),
	('TASI', 6,'1IAS','Introdução à Análise de Sistemas',4,80,1,'OBRIGATÓRIA'),
	('TASI', 6,'1FAC','Fundamentos de Algoritmos de Computação',4,80,1,'OBRIGATÓRIA'),
	('TASI', 6,'1IHM','Interface Homem-Máquina',2,40,1,'OBRIGATÓRIA'),
	('TASI', 6,'1ORG','Organização de Computadores',4,80,1,'OBRIGATÓRIA'),

	('TASI', 6,'2TPH','Técnicas e Paradigmas Humanos', 4, 80, 2, 'OBRIGATÓRIA'),
	('TASI', 6,'2CAL','Cálculo', 4, 80, 2, 'OBRIGATÓRIA'),
	('TASI', 6,'2LES','Língua Estrangeira', 2, 40, 2, 'OBRIGATÓRIA'),
	('TASI', 6,'2REQ','Engenharia de Requisitos', 4, 80, 2, 'OBRIGATÓRIA'),
	('TASI', 6,'2FPR','Fundamentos de Programação', 4, 80, 2, 'OBRIGATÓRIA'),
	('TASI', 6,'2CAW','Construção de Aplicações Web', 4, 80, 2, 'OBRIGATÓRIA'),
	('TASI', 6,'2SOP','Fundamentos de Sistemas Operacionais', 4, 80, 2, 'OBRIGATÓRIA'),
	('TASI', 6,'2MPA','Métodos e Processos Administrativos', 2, 40, 2, 'OBRIGATÓRIA'),

	('TASI', 6,'3ALG', 'Álgebra Linear', 4, 80, 3, 'OBRIGATÓRIA'),
	('TASI', 6,'3PBD', 'Projeto de Banco de Dados', 4, 80, 3, 'OBRIGATÓRIA'),
	('TASI', 6,'3POB', 'Programação Orientada a Objetos Básica', 4, 80, 3, 'OBRIGATÓRIA'),
	('TASI', 6,'3ESD', 'Estrutura de Dados', 4, 80, 3, 'OBRIGATÓRIA'),
	('TASI', 6,'3RSD', 'Fundamentos de Redes e Sistemas Distribuídos', 4, 80, 3, 'OBRIGATÓRIA'),
	('TASI', 6,'3DAW', 'Desenvolvimento de Tecnologias Web', 4, 80, 3, 'OBRIGATÓRIA'),

	('TASI', 6,'4MET', 'Metodologia de Pesquisa', 2, 40, 4, 'OBRIGATÓRIA'),
	('TASI', 6,'4EST', 'Estatística e Probabilidade', 4, 80, 4, 'OBRIGATÓRIA'),
	('TASI', 6,'4LIB', 'Linguagem Brasileira de Sinais', 2, 40, 4, 'ELETIVA'),
	('TASI', 6,'4MOD', 'Modelagem de Sistemas', 4, 80, 4, 'OBRIGATÓRIA'),
	('TASI', 6,'4ADS', 'Tópicos em Análise e Desenvolvimento de Sistemas', 4, 80, 4, 'OBRIGATÓRIA'),
	('TASI', 6,'4UBD', 'Utilização de Banco de Dados', 4, 80, 4, 'OBRIGATÓRIA'),
	('TASI', 6,'4POA', 'Programação Orientada a Objetos Avançada', 4, 80, 4, 'OBRIGATÓRIA'),
	('TASI', 6,'4SEG', 'Segurança da Informação', 4, 80, 4, 'OBRIGATÓRIA'),
	('TASI', 6,'4EMP', 'Empreendedorismo e Inovação', 2, 40, 4, 'OBRIGATÓRIA'),

	('TASI', 6,'5TFC', 'Orientação de Trabalho Final de Curso', 2, 40, 5, 'OBRIGATÓRIA'),
	('TASI', 6,'5PJS', 'Projeto de Sistemas', 4, 80, 5, 'OBRIGATÓRIA'),
	('TASI', 6,'5SBD', 'Programação de Scripts de Banco de Dados', 4, 80, 5, 'OBRIGATÓRIA'),
	('TASI', 6,'5PDM', 'Programação de Dispositivos Móveis', 4, 80, 5, 'OBRIGATÓRIA'),
	('TASI', 6,'5TAV', 'Tópicos Avançados', 4, 80, 5, 'OBRIGATÓRIA'),
	('TASI', 6,'5GPS', 'Gerência de Projetos de Software', 2, 40, 5, 'OBRIGATÓRIA');


INSERT INTO `ComponentePreRequisito` (`siglaCurso`, `idMatriz`, `siglaDisciplina`, 
	`siglaCursoPreRequisito`, `idMatrizPreRequisito`, `siglaDisciplinaPreRequisito`) VALUES 
	('TASI', 6,'2LES','TASI', 6,'1LPO'),
	('TASI', 6,'2FPR','TASI', 6,'1FAC'),
	('TASI', 6,'2SOP','TASI', 6,'1ORG'),

	('TASI', 6,'3ALG','TASI', 6,'2CAL'),
	('TASI', 6,'3POB','TASI', 6,'2FPR'),
	('TASI', 6,'3ESD','TASI', 6,'2FPR'),
	('TASI', 6,'3RSD','TASI', 6,'2SOP'),
	('TASI', 6,'3DAW','TASI', 6,'2CAW'),

	('TASI', 6,'4EST','TASI', 6,'1MAC'),
	('TASI', 6,'4EST','TASI', 6,'2CAL'),
	('TASI', 6,'4MOD','TASI', 6,'2REQ'),
	('TASI', 6,'4ADS','TASI', 6,'2REQ'),
	('TASI', 6,'4UBD','TASI', 6,'3PBD'),
	('TASI', 6,'4POA','TASI', 6,'3POB'),
	('TASI', 6,'4SEG','TASI', 6,'3RSD'),

	('TASI', 6,'5TFC','TASI', 6,'4MET'),
	('TASI', 6,'5PJS','TASI', 6,'4MOD'),
	('TASI', 6,'5SBD','TASI', 6,'4UBD'),
	('TASI', 6,'5PDM','TASI', 6,'4POA');

-- Equivalências da matriz 6 (2018.1) para a matriz 5 (2006.1)
INSERT INTO `ComponenteEquivalente` (`siglaCurso`, `idMatriz`, `siglaDisciplina`, 
	`siglaCursoEquivalente`, `idMatrizEquivalente`, `siglaDisciplinaEquivalente`) VALUES 
	('TASI',6,'1LPO','TASI',5,'LPO'),
	('TASI',6,'1FAC','TASI',5,'AL1'),
	('TASI',6,'1IHM','TASI',5,'AL1'),
	('TASI',6,'1ORG','TASI',5,'AC1'),

	('TASI',6,'2TPH','TASI',5,'DHQ'),
	('TASI',6,'2TPH','TASI',5,'TRI'),
	('TASI',6,'2LES','TASI',5,'IIT'),
	('TASI',6,'2FPR','TASI',5,'AL2'),
	('TASI',6,'2FPR','TASI',5,'ESD'),
	('TASI',6,'2CAW','TASI',5,'INT'),
	('TASI',6,'2SOP','TASI',5,'SOP'),
	('TASI',6,'2MPA','TASI',5,'ADM'),

	('TASI',6,'3ALG','TASI',5,'ALG'),
	('TASI',6,'3PBD','TASI',5,'SPB'),
	('TASI',6,'3POB','TASI',5,'OO1'),
	('TASI',6,'3RSD','TASI',5,'RD1'),
	('TASI',6,'3ESD','TASI',5,'AL2'),

	('TASI',6,'4MET','TASI',5,'ME2'),
	('TASI',6,'4EST','TASI',5,'EST'),
	('TASI',6,'4UBD','TASI',5,'IBD'),
	('TASI',6,'4POA','TASI',5,'OO2'),
	('TASI',6,'4SEG','TASI',5,'RD2'),
	('TASI',6,'4EMP','TASI',5,'EMP'),

	('TASI',6,'5PJS','TASI',5,'APS'),
	('TASI',6,'5TAV','TASI',5,'TAV'),
	('TASI',6,'5GPS','TASI',5,'GPS');


-- Dados de teste do Mucilon matr.1331

INSERT INTO `Turma` (`idTurma`, `siglaCurso`, `idMatriz`, `siglaDisciplina`, `gradeHorario`, `idPeriodoLetivo`, `matriculaProfessor`, `turno`, `tipoSituacaoTurma`, `qtdeTotal`) VALUES
(116, 'TASI', 6, '3ALG', 'A', 20, '1113299', 'NOITE', 'PLANEJADA', 30);
update Turma set tipoSituacaoTurma='CONFIRMADA' where idTurma=116;
INSERT INTO `Inscricao` (`idTurma`, `matriculaAluno`, `situacaoInscricao`, `dataInscricao`, `mediaFinal`, `totalFaltas`, `parecerInscricao`) VALUES
(116, '1331', 'AP', '2017-01-01 06:34:52', 6.0, 0, 'Cumpriu 3ALG na matriz nova, cumpriu ALG na antiga?');
update Turma set tipoSituacaoTurma='FINALIZADA' where idTurma=116;

INSERT INTO `Turma` (`idTurma`, `siglaCurso`, `idMatriz`, `siglaDisciplina`, `gradeHorario`, `idPeriodoLetivo`, `matriculaProfessor`, `turno`, `tipoSituacaoTurma`, `qtdeTotal`) VALUES
(117, 'TASI', 6, '1FAC', 'A', 20, '1113299', 'NOITE', 'PLANEJADA', 30);
update Turma set tipoSituacaoTurma='CONFIRMADA' where idTurma=117;
INSERT INTO `Inscricao` (`idTurma`, `matriculaAluno`, `situacaoInscricao`, `dataInscricao`, `mediaFinal`, `totalFaltas`, `parecerInscricao`) VALUES
(117, '1331', 'AP', '2017-01-01 06:34:52', 7.0, 0, 'Cumpriu 1FAC na matriz nova, cumpriu AL1 na antiga?');
update Turma set tipoSituacaoTurma='FINALIZADA' where idTurma=117;


INSERT INTO `Turma` (`idTurma`, `siglaCurso`, `idMatriz`, `siglaDisciplina`, `gradeHorario`, `idPeriodoLetivo`, `matriculaProfessor`, `turno`, `tipoSituacaoTurma`, `qtdeTotal`) VALUES
(118, 'TASI', 6, '1IHM', 'A', 20, '1113299', 'NOITE', 'PLANEJADA', 30);
update Turma set tipoSituacaoTurma='CONFIRMADA' where idTurma=118;
INSERT INTO `Inscricao` (`idTurma`, `matriculaAluno`, `situacaoInscricao`, `dataInscricao`, `mediaFinal`, `totalFaltas`, `parecerInscricao`) VALUES
(118, '1331', 'AP', '2017-01-01 06:34:52', 6.0, 0, 'Cumpriu 1FAC e 1IHM na matriz nova, cumpriu AL1 na antiga?');
update Turma set tipoSituacaoTurma='FINALIZADA' where idTurma=118;

INSERT INTO `Turma` (`idTurma`, `siglaCurso`, `idMatriz`, `siglaDisciplina`, `gradeHorario`, `idPeriodoLetivo`, `matriculaProfessor`, `turno`, `tipoSituacaoTurma`, `qtdeTotal`) VALUES
(119, 'TASI', 6, '2FPR', 'A', 20, '1113299', 'NOITE', 'PLANEJADA', 30);
update Turma set tipoSituacaoTurma='CONFIRMADA' where idTurma=119;
INSERT INTO `Inscricao` (`idTurma`, `matriculaAluno`, `situacaoInscricao`, `dataInscricao`, `mediaFinal`, `totalFaltas`, `parecerInscricao`) VALUES
(119, '1331', 'AP', '2017-01-01 06:34:52', 7.5, 0, 'Cumpriu 1FAC e 1IHM na matriz nova, cumpriu AL1 na antiga?');
update Turma set tipoSituacaoTurma='FINALIZADA' where idTurma=119;

--
-- queries de conferencia
--
-- carga total
select sum(cargaHoraria)
 from ComponenteCurricular CC
 where CC.siglaCurso='TASI'
 and CC.idMatriz=6;

-- ch por semestre ordinal
select CC.periodo,sum(cargaHoraria)
 from ComponenteCurricular CC
 where CC.siglaCurso='TASI'
 and CC.idMatriz=6
 group by CC.periodo;

-- creditos por semestre ordinal
select CC.periodo,sum(creditos)
 from ComponenteCurricular CC
 where CC.siglaCurso='TASI'
 and CC.idMatriz=6
 group by CC.periodo;

-- conferencia de equivalencia
select concat('A disciplina ',CE.siglaDisciplina,' (matriz 2018.1) dá equivalência para ', CE.siglaDisciplinaEquivalente,' (2006.1)') 
 from ComponenteEquivalente CE 
 where CE.idMatriz=6 and CE.idMatrizEquivalente=5;

