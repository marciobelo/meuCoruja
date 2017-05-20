-- ATENÇÂO: ARQUIVO NO FORMATO CODIFICADO UTF-8 (MANTER ASSIM!)
-- USAR UM EDITOR QUE SUPORTE ESSA CODIFICAÇÃO, COMO O NOTEPAD++ ou GEDIT

-- SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
-- SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
-- SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';


-- -----------------------------------------------------
-- Database Coruja
-- -----------------------------------------------------
use `Coruja`;

-- -----------------------------------------------------
-- Data for table `FormaIngresso`
-- -----------------------------------------------------
SET AUTOCOMMIT=0;
INSERT INTO `FormaIngresso` (`idFormaIngresso`, `descricao`) VALUES (1, 'Concurso Cota Regular');
INSERT INTO `FormaIngresso` (`idFormaIngresso`, `descricao`) VALUES (2, 'Concurso Cota Rede Pública');
INSERT INTO `FormaIngresso` (`idFormaIngresso`, `descricao`) VALUES (3, 'Concurso Cota Cor Negra');
INSERT INTO `FormaIngresso` (`idFormaIngresso`, `descricao`) VALUES (4, 'Concurso Cota Deficiente');
COMMIT;

-- -----------------------------------------------------
-- Data for table `TipoCurso`
-- -----------------------------------------------------
SET AUTOCOMMIT=0;
INSERT INTO `TipoCurso` (`idTipoCurso`, `descricao`) VALUE (1,'Graduação');
INSERT INTO `TipoCurso` (`idTipoCurso`, `descricao`) VALUE (2,'Pós-Graduação');
INSERT INTO `TipoCurso` (`idTipoCurso`, `descricao`) VALUE (3,'Extensão');
COMMIT;

-- -----------------------------------------------------
-- Data for table `Curso`
-- -----------------------------------------------------
SET AUTOCOMMIT=0;
INSERT INTO `Curso` (`siglaCurso`, `nomeCurso`, `idTipoCurso`) VALUE ('TASI', 'Tecnologia em Análise de Sistemas Informatizados',1);
INSERT INTO `Curso` (`siglaCurso`, `nomeCurso`, `idTipoCurso`) VALUE ('PGTIAE', 'Gestão da Tecnologia da Informação em Ambientes Educacionais',2);
COMMIT;

-- -----------------------------------------------------
-- Data for table `MatrizCurricular`
-- -----------------------------------------------------
SET AUTOCOMMIT=0;
INSERT INTO `MatrizCurricular` (`siglaCurso`, `idMatriz`, `dataInicioVigencia`) VALUES ('TASI', 1, '2002-01-01');
INSERT INTO `MatrizCurricular` (`siglaCurso`, `idMatriz`, `dataInicioVigencia`) VALUES ('TASI', 2, '2003-01-01');
INSERT INTO `MatrizCurricular` (`siglaCurso`, `idMatriz`, `dataInicioVigencia`) VALUES ('TASI', 3, '2004-01-01');
INSERT INTO `MatrizCurricular` (`siglaCurso`, `idMatriz`, `dataInicioVigencia`) VALUES ('TASI', 4, '2005-01-01');
INSERT INTO `MatrizCurricular` (`siglaCurso`, `idMatriz`, `dataInicioVigencia`) VALUES ('TASI', 5, '2006-01-01');
INSERT INTO `MatrizCurricular` (`siglaCurso`, `idMatriz`, `dataInicioVigencia`) VALUES ('PGTIAE', 1, '2009-08-01');
COMMIT;

-- -----------------------------------------------------
-- Data for table `ComponenteCurricular`
-- -----------------------------------------------------
SET AUTOCOMMIT=0;
-- Componentes Curriculares do Curso TASI, matriz id 1 - vigentes em 2002.1 e 2002.2 (opta-se por não colocar os pré-requisitos nessa matriz antiga)
INSERT INTO `ComponenteCurricular` (`siglaCurso`, `idMatriz`, `siglaDisciplina`, `nomeDisciplina`, 
	`creditos`, `cargaHoraria`, `periodo`, `tipoComponenteCurricular`) VALUES 
	('TASI', 1,'AC1','Arquitetura de Computadores I',4,60,1,'OBRIGATÓRIA'),
	('TASI', 1,'AL1','Algoritmos I',4,60,1,'OBRIGATÓRIA'),
	('TASI', 1,'LP1','Linguagem de Programação I',4,60,1,'OBRIGATÓRIA'),
	('TASI', 1,'MT1','Matemática I',4,60,1,'OBRIGATÓRIA'),
	('TASI', 1,'RD1','Redes I',4,60,1,'OBRIGATÓRIA'),
	('TASI', 1,'MET','Metodologia de Pesquisa',2,40,1,'OBRIGATÓRIA'),
	('TASI', 1,'LPO','Língua Portuguesa',4,60,1,'OBRIGATÓRIA'),
	('TASI', 1,'EDF','Educação Física',2,40,1,'OBRIGATÓRIA'),
	('TASI', 1,'AL2','Algoritmos II',4,60,2,'OBRIGATÓRIA'),
	('TASI', 1,'LP2','Linguagem de Programação II',4,60,2,'OBRIGATÓRIA'),
	('TASI', 1,'MT2','Matemática II',4,60,2,'OBRIGATÓRIA'),
	('TASI', 1,'RD2','Redes II',4,60,2,'OBRIGATÓRIA'),
	('TASI', 1,'AC2','Arquitetura de Computadores II',4,60,2,'OBRIGATÓRIA'),
	('TASI', 1,'INT','Internet',2,40,2,'OBRIGATÓRIA'),
	('TASI', 1,'ITE','Inglês Técnico para Informática I',2,40,2,'OBRIGATÓRIA'),
	('TASI', 1,'SOP','Sistema Operacional',4,60,2,'OBRIGATÓRIA'),
	('TASI', 1,'ESD','Estrutura de Dados',4,60,3,'OBRIGATÓRIA'),
	('TASI', 1,'LP3','Linguagem de Programação III',4,60,3,'OBRIGATÓRIA'),
	('TASI', 1,'OO1','Programação Orientada a Objetos I',4,60,3,'OBRIGATÓRIA'),
	('TASI', 1,'AND','Análise de Dados',2,40,3,'OBRIGATÓRIA'),
	('TASI', 1,'BD1','Banco de Dados I',4,60,3,'OBRIGATÓRIA'),
	('TASI', 1,'ENG','Engenharia de Software',4,60,3,'OBRIGATÓRIA'),
	('TASI', 1,'EST','Estatística',2,40,3,'OBRIGATÓRIA'),
	('TASI', 1,'PBD','Projeto e Implementação de Banco de Dados',4,60,3,'OBRIGATÓRIA'),
	('TASI', 1,'PRO','Produto de Curso',4,60,4,'OBRIGATÓRIA'),
	('TASI', 1,'OO2','Programação Orientada a Objetos II',4,60,4,'OBRIGATÓRIA'),
	('TASI', 1,'OO3','Programação Orientada a Objetos III',4,60,4,'OBRIGATÓRIA'),
	('TASI', 1,'CEI','Compiladores e Interpretadores',2,40,4,'OBRIGATÓRIA'),
	('TASI', 1,'BD2','Banco de Dados II',6,80,4,'OBRIGATÓRIA'),
	('TASI', 1,'GBD','Gerência de Banco de Dados',2,40,4,'OBRIGATÓRIA'),
	('TASI', 1,'TIL1','Treinamento Individual de Laboratório I',2,40,4,'OBRIGATÓRIA'),
	('TASI', 1,'AP1','Análise e Projeto de Sistemas I',8,100,5,'OBRIGATÓRIA'),
	('TASI', 1,'OEM','Organização e Métodos',2,40,5,'OBRIGATÓRIA'),
	('TASI', 1,'AD1','Administração I',4,60,5,'OBRIGATÓRIA'),
	('TASI', 1,'PF1','PF I',4,60,5,'OBRIGATÓRIA'),
	('TASI', 1,'TIL2','Treinamento Individual de Laboratório II',2,40,5,'OBRIGATÓRIA'),
	('TASI', 1,'AP2','Análise e Projeto de Sistemas II',8,100,6,'OBRIGATÓRIA'),
	('TASI', 1,'SOC','Informática e Sociedade',2,40,6,'OBRIGATÓRIA'),
	('TASI', 1,'AD2','Administração II',4,60,6,'OBRIGATÓRIA'),
	('TASI', 1,'PF2','PF II',4,60,6,'OBRIGATÓRIA'),
	('TASI', 1,'TIL3','Treinamento Individual de Laboratório III',2,40,6,'OBRIGATÓRIA');
-- Componentes Curriculares do Curso TASI, matriz id 2 - vigentes em 2003.1 e 2003.2 (opta-se por não colocar os pré-requisitos nessa matriz antiga)
INSERT INTO `ComponenteCurricular` (`siglaCurso`, `idMatriz`, `siglaDisciplina`, `nomeDisciplina`, 
	`creditos`, `cargaHoraria`, `periodo`, `tipoComponenteCurricular`) VALUES 
	('TASI', 2,'AL1','Algoritmos I',4,60,1,'OBRIGATÓRIA'),
	('TASI', 2,'LP1','Linguagem de Programação I',4,60,1,'OBRIGATÓRIA'),
	('TASI', 2,'RD1','Redes I',4,60,1,'OBRIGATÓRIA'),
	('TASI', 2,'AC1','Arquitetura de Computadores I',4,60,1,'OBRIGATÓRIA'),
	('TASI', 2,'ALG','Álgebra Linear',4,60,1,'OBRIGATÓRIA'),
	('TASI', 2,'LPO','Língua Portuguesa',4,60,1,'OBRIGATÓRIA'),
	('TASI', 2,'ME1','Metodologia de Pesquisa I',2,30,1,'OBRIGATÓRIA'),
	('TASI', 2,'MT1','Matemática I',4,60,1,'OBRIGATÓRIA'),
	('TASI', 2,'AL2','Algoritmos II',4,60,2,'OBRIGATÓRIA'),
	('TASI', 2,'LP2','Linguagem de Programação II',4,60,2,'OBRIGATÓRIA'),
	('TASI', 2,'RD2','Redes II',4,60,2,'OBRIGATÓRIA'),
	('TASI', 2,'AC2','Arquitetura de Computadores II',4,60,2,'OBRIGATÓRIA'),
	('TASI', 2,'SOP','Sistema Operacional',4,60,2,'OBRIGATÓRIA'),
	('TASI', 2,'INT','Internet',4,60,2,'OBRIGATÓRIA'),
	('TASI', 2,'ME2','Metodologia de Pesquisa II',2,30,2,'OBRIGATÓRIA'),
	('TASI', 2,'IIT','Inglês Instrumental',4,60,2,'OBRIGATÓRIA'),
	('TASI', 2,'ESD','Estrutura de Dados',4,60,3,'OBRIGATÓRIA'),
	('TASI', 2,'LP3','Linguagem de Programação III',4,60,3,'OBRIGATÓRIA'),
	('TASI', 2,'OO1','Programação Orientada a Objetos I',4,60,3,'OBRIGATÓRIA'),
	('TASI', 2,'GPS','Gerência e Projeto de Software',2,30,3,'OBRIGATÓRIA'),
	('TASI', 2,'PBD','Projeto e Implementação de Banco de Dados',4,60,3,'OBRIGATÓRIA'),
	('TASI', 2,'SBD','Sistema de Banco de Dados',4,60,3,'OBRIGATÓRIA'),
	('TASI', 2,'ENG','Engenharia de Software',2,40,3,'OBRIGATÓRIA'),
	('TASI', 2,'MT2','Matemática II',4,60,2,'OBRIGATÓRIA'),
	('TASI', 2,'IHM','Interface Homem Máquina',4,60,4,'OBRIGATÓRIA'),
	('TASI', 2,'OO2','Programação Orientada a Objetos II',4,60,4,'OBRIGATÓRIA'),
	('TASI', 2,'PRO','Produto de Curso',4,60,4,'OBRIGATÓRIA'),
	('TASI', 2,'EST','Estatística',4,60,4,'OBRIGATÓRIA'),
	('TASI', 2,'IBD','Implementação de Banco de Dados I',6,90,4,'OBRIGATÓRIA'),
	('TASI', 2,'TRI','Técnicas de Relacionamento Interpessoal',2,30,4,'OBRIGATÓRIA'),
	('TASI', 2,'AP1','Análise e Projeto de Sistemas I',4,60,4,'OBRIGATÓRIA'),
	('TASI', 2,'ADM','Administração',4,60,5,'OBRIGATÓRIA'),
	('TASI', 2,'OO3','Programação Orientada a Objetos III',6,90,5,'OBRIGATÓRIA'),
	('TASI', 2,'PF1','PF I',4,60,5,'OBRIGATÓRIA'),
	('TASI', 2,'TA1','Técnicas Avançadas I',4,60,5,'OBRIGATÓRIA'),
	('TASI', 2,'AP2','Análise e Projeto de Sistemas II',6,90,5,'OBRIGATÓRIA'),
	('TASI', 2,'EMP','Empreendedorismo',4,60,6,'OBRIGATÓRIA'),
	('TASI', 2,'DHQ','Desenvolvimento Humano e Qualidade de Vida',2,30,6,'OBRIGATÓRIA'),
	('TASI', 2,'PF2','PF II',4,60,6,'OBRIGATÓRIA'),
	('TASI', 2,'DIF','Direito em Informática',2,30,6,'OBRIGATÓRIA'),
	('TASI', 2,'TA2','Técnicas Avançadas II',4,60,6,'OBRIGATÓRIA'),
	('TASI', 2,'INS','Informática e Sociedade',2,30,6,'OBRIGATÓRIA'),
	('TASI', 2,'AP3','Análise e Projeto de Sistemas III',6,90,6,'OBRIGATÓRIA');
-- Componentes Curriculares do Curso TASI, matriz id 3 - vigentes em 2004.1 e 2004.2 (opta-se por não colocar os pré-requisitos nessa matriz antiga)
INSERT INTO `ComponenteCurricular` (`siglaCurso`, `idMatriz`, `siglaDisciplina`, `nomeDisciplina`, 
	`creditos`, `cargaHoraria`, `periodo`, `tipoComponenteCurricular`) VALUES 
	('TASI', 3,'AL1','Algoritmos e Linguagens de Programação I',8,120,1,'OBRIGATÓRIA'),
	('TASI', 3,'RD1','Redes I',4,60,1,'OBRIGATÓRIA'),
	('TASI', 3,'AC1','Arquitetura de Computadores I',4,60,1,'OBRIGATÓRIA'),
	('TASI', 3,'MAT','Matemática',6,90,1,'OBRIGATÓRIA'),
	('TASI', 3,'LPO','Língua Portuguesa',4,60,1,'OBRIGATÓRIA'),
	('TASI', 3,'ME1','Metodologia de Pesquisa I',2,30,1,'OBRIGATÓRIA'),
	('TASI', 3,'AL2','Algoritmos e Linguagens de Programação II',8,120,2,'OBRIGATÓRIA'),
	('TASI', 3,'RD2','Redes II',4,60,2,'OBRIGATÓRIA'),
	('TASI', 3,'AC2','Arquitetura de Computadores II',4,60,2,'OBRIGATÓRIA'),
	('TASI', 3,'SOP','Sistema Operacional',4,60,2,'OBRIGATÓRIA'),
	('TASI', 3,'ALG','Álgebra Linear',4,60,2,'OBRIGATÓRIA'),
	('TASI', 3,'ME2','Metodologia de Pesquisa II',2,30,2,'OBRIGATÓRIA'),
	('TASI', 3,'IIT','Inglês Instrumental',4,60,2,'OBRIGATÓRIA'),
	('TASI', 3,'ESD','Estrutura de Dados',2,30,3,'OBRIGATÓRIA'),
	('TASI', 3,'INT','Internet',4,60,3,'OBRIGATÓRIA'),
	('TASI', 3,'OO1','Programação Orientada a Objetos I',6,90,3,'OBRIGATÓRIA'),
	('TASI', 3,'GPS','Gerência e Projeto de Software',2,30,3,'OBRIGATÓRIA'),
	('TASI', 3,'PBD','Projeto e Implementação de Banco de Dados',4,60,3,'OBRIGATÓRIA'),
	('TASI', 3,'SBD','Sistema de Banco de Dados',4,60,3,'OBRIGATÓRIA'),
	('TASI', 3,'EST','Estatística',4,60,3,'OBRIGATÓRIA'),
	('TASI', 3,'DHQ','Desenvolvimento Humano e Qualidade de Vida',2,30,3,'OBRIGATÓRIA'),
	('TASI', 3,'ADM','Administração',2,30,4,'OBRIGATÓRIA'),
	('TASI', 3,'TRI','Técnicas de Relacionamento Interpessoal',2,30,4,'OBRIGATÓRIA'),
	('TASI', 3,'OO2','Programação Orientada a Objetos II',6,90,4,'OBRIGATÓRIA'),
	('TASI', 3,'IHM','Interface Homem Máquina',2,30,4,'OBRIGATÓRIA'),
	('TASI', 3,'PRO','Produto de Curso',4,60,4,'OBRIGATÓRIA'),
	('TASI', 3,'IBD','Implementação de Banco de Dados I',6,90,4,'OBRIGATÓRIA'),
	('TASI', 3,'TAV','Tópicos Avançados',4,60,4,'OBRIGATÓRIA'),
	('TASI', 3,'ENG','Engenharia de Software',4,60,4,'OBRIGATÓRIA'),
	('TASI', 3,'APS','Análise e Projeto de Sistemas',8,120,5,'OBRIGATÓRIA'),
	('TASI', 3,'INS','Informática e Sociedade',2,30,5,'OBRIGATÓRIA'),
	('TASI', 3,'LPW','Linguagem e Programação para WEB',6,90,5,'OBRIGATÓRIA'),
	('TASI', 3,'PJF','Projeto Final',6,90,5,'OBRIGATÓRIA'),
	('TASI', 3,'DIF','Direito em Informática',2,30,5,'OBRIGATÓRIA'),
	('TASI', 3,'EMP','Empreendedorismo',2,30,5,'OBRIGATÓRIA');

-- Componentes Curriculares do Curso TASI, matriz id 4 - vigente em 2005.1 e 2005.2 (matriz autorizada)
INSERT INTO `ComponenteCurricular` (`siglaCurso`, `idMatriz`, `siglaDisciplina`, `nomeDisciplina`, 
	`creditos`, `cargaHoraria`, `periodo`, `tipoComponenteCurricular`) VALUES 
	('TASI', 4,'AL1','Algoritmos e Linguagem de Programação I',6,120,1,'OBRIGATÓRIA'),
	('TASI', 4,'RD1','Redes I',4,80,1,'OBRIGATÓRIA'),
	('TASI', 4,'AC1','Arquitetura de Computadores I',4,80,1,'OBRIGATÓRIA'),
	('TASI', 4,'MAT','Matemática Aplicada',5,100,1,'OBRIGATÓRIA'),
	('TASI', 4,'LPO','Língua Portuguesa',4,80,1,'OBRIGATÓRIA'),
	('TASI', 4,'ME1','Metodologia de Pesquisa I',2,40,1,'OBRIGATÓRIA'),
	('TASI', 4,'AL2','Algoritmos e Linguagem de Programação II',6,120,2,'OBRIGATÓRIA'),
	('TASI', 4,'RD2','Redes II',4,80,2,'OBRIGATÓRIA'),
	('TASI', 4,'AC2','Arquitetura de Computadores II',3,60,2,'OBRIGATÓRIA'),
	('TASI', 4,'SOP','Sistema Operacional',4,80,2,'OBRIGATÓRIA'),
	('TASI', 4,'ALG','Álgebra Linear',4,80,2,'OBRIGATÓRIA'),
	('TASI', 4,'ME2','Metodologia de Pesquisa II',2,40,2,'OBRIGATÓRIA'),
	('TASI', 4,'IIT','Inglês Instrumental',2,40,2,'OBRIGATÓRIA'),
	('TASI', 4,'ESD','Estrutura de Dados',2,40,3,'OBRIGATÓRIA'),
	('TASI', 4,'INT','Internet',4,80,3,'OBRIGATÓRIA'),
	('TASI', 4,'OO1','Programação Orientada a Objeto I',6,120,3,'OBRIGATÓRIA'),
	('TASI', 4,'GPS','Gerência de Projetos de Sistemas',2,40,3,'OBRIGATÓRIA'),
	('TASI', 4,'SPB','Sistema e Projeto de Banco de Dados',6,120,3,'OBRIGATÓRIA'),
	('TASI', 4,'EST','Estatística Aplicada',3,60,3,'OBRIGATÓRIA'),
	('TASI', 4,'DHQ','Desenvolvimento Humano e Qualidade de Vida',2,40,3,'OBRIGATÓRIA'),
	('TASI', 4,'ADM','Administração Aplicada',2,40,4,'OBRIGATÓRIA'),
	('TASI', 4,'ENG','Engenharia de Software',6,120,4,'OBRIGATÓRIA'),
	('TASI', 4,'OO2','Programação Orientada a Objeto II',6,120,4,'OBRIGATÓRIA'),
	('TASI', 4,'IHM','Interface Homem Máquina',2,40,4,'OBRIGATÓRIA'),
	('TASI', 4,'PSW','Produção de Software',3,60,4,'OBRIGATÓRIA'),
	('TASI', 4,'IBD','Implementação de Banco de Dados',6,120,4,'OBRIGATÓRIA'),
	('TASI', 4,'APS','Análise e Projeto de Sistemas',6,120,5,'OBRIGATÓRIA'),
	('TASI', 4,'INS','Informática e Sociedade',2,40,5,'OBRIGATÓRIA'),
	('TASI', 4,'LPW','Linguagem de Programação para WEB',6,120,5,'OBRIGATÓRIA'),
	('TASI', 4,'DIF','Direito em Informática',2,40,5,'OBRIGATÓRIA'),
	('TASI', 4,'EMP','Empreendedorismo',2,40,5,'OBRIGATÓRIA'),
	('TASI', 4,'TRI','Técnicas de Relacionamento Interpessoal',2,40,5,'OBRIGATÓRIA'),
	('TASI', 4,'TAV','Tópicos Avançados',3,60,5,'OBRIGATÓRIA');
	
-- Componentes Curriculares do Curso TASI, matriz id 5 - vigente desde 2006.1
INSERT INTO `ComponenteCurricular` (`siglaCurso`, `idMatriz`, `siglaDisciplina`, `nomeDisciplina`, 
	`creditos`, `cargaHoraria`, `periodo`, `tipoComponenteCurricular`) VALUES 
	('TASI', 5,'AC1','Arquitetura de Computadores I',4,80,1,'OBRIGATÓRIA'),
	('TASI', 5,'AC2','Arquitetura de Computadores II',3,60,2,'OBRIGATÓRIA'),
	('TASI', 5,'ADM','Administração Aplicada',2,40,1,'OBRIGATÓRIA'),
	('TASI', 5,'AL1','Algoritmos e Linguagem de Programação I',6,120,1,'OBRIGATÓRIA'),
	('TASI', 5,'AL2','Algoritmos e Linguagem de Programação II',6,120,2,'OBRIGATÓRIA'),
	('TASI', 5,'ALG','Álgebra Linear',4,80,2,'OBRIGATÓRIA'),
	('TASI', 5,'APS','Análise e Projeto de Sistemas',6,120,5,'OBRIGATÓRIA'),
	('TASI', 5,'DHQ','Desenvolvimento Humano e Qualidade de Vida',2,40,1,'OBRIGATÓRIA'),
	('TASI', 5,'DIF','Direito em Informática',2,40,5,'OBRIGATÓRIA'),
	('TASI', 5,'EMP','Empreendedorismo',2,40,5,'OBRIGATÓRIA'),
	('TASI', 5,'ENG','Engenharia de Software',6,120,4,'OBRIGATÓRIA'),
	('TASI', 5,'ESD','Estrutura de Dados',2,40,3,'OBRIGATÓRIA'),
	('TASI', 5,'EST','Estatística Aplicada',3,60,3,'OBRIGATÓRIA'),
	('TASI', 5,'GPS','Gerência de Projetos de Sistemas',2,40,3,'OBRIGATÓRIA'),
	('TASI', 5,'IBD','Implementação de Banco de Dados',6,120,4,'OBRIGATÓRIA'),
	('TASI', 5,'IHM','Interface Homem Máquina',2,40,4,'OBRIGATÓRIA'),
	('TASI', 5,'IIT','Inglês Instrumental',2,40,2,'OBRIGATÓRIA'),
	('TASI', 5,'INS','Informática e Sociedade',2,40,5,'OBRIGATÓRIA'),
	('TASI', 5,'INT','Internet',4,80,3,'OBRIGATÓRIA'),
	('TASI', 5,'LPO','Língua Portuguesa',4,80,1,'OBRIGATÓRIA'),
	('TASI', 5,'LPW','Linguagem de Programação para WEB',6,120,5,'OBRIGATÓRIA'),
	('TASI', 5,'ME1','Metodologia de Pesquisa I',2,40,1,'OBRIGATÓRIA'),
	('TASI', 5,'ME2','Metodologia de Pesquisa II',2,40,2,'OBRIGATÓRIA'),
	('TASI', 5,'MAT','Matemática Aplicada',5,100,1,'OBRIGATÓRIA'),
	('TASI', 5,'OO1','Programação Orientada a Objeto I',6,120,3,'OBRIGATÓRIA'),
	('TASI', 5,'OO2','Programação Orientada a Objeto II',6,120,4,'OBRIGATÓRIA'),
	('TASI', 5,'PSW','Produção de Software',3,60,4,'OBRIGATÓRIA'),
	('TASI', 5,'RD1','Redes I',4,80,3,'OBRIGATÓRIA'),
	('TASI', 5,'RD2','Redes II',4,80,2,'OBRIGATÓRIA'),
	('TASI', 5,'SPB','Sistema e Projeto de Banco de Dados',6,120,3,'OBRIGATÓRIA'),
	('TASI', 5,'SOP','Sistema Operacional',4,80,2,'OBRIGATÓRIA'),
	('TASI', 5,'TAV','Tópicos Avançados',3,60,5,'OBRIGATÓRIA'),
	('TASI', 5,'TRI','Técnicas de Relacionamento Interpessoal',2,40,2,'OBRIGATÓRIA'),
	('TASI', 5,'TCC','Trabalho de Conclusão de Curso',2,40,5,'OBRIGATÓRIA');
COMMIT;

-- -----------------------------------------------------
-- Data for table `ComponentePreRequisito`
-- -----------------------------------------------------
SET AUTOCOMMIT=0;

-- Equivalências da matriz 2 (2003) para a matriz 1 (2002)
-- Atenção: como só foram oferecidas o 1o. e 2o. período letivo da matriz 1 (2002), só foram estabelecidas as equivalências para esses componentes curriculares
INSERT INTO `ComponenteEquivalente` (`siglaCurso`, `idMatriz`, `siglaDisciplina`, 
	`siglaCursoEquivalente`, `idMatrizEquivalente`, `siglaDisciplinaEquivalente`) VALUES 
('TASI',2,'AC1','TASI',1,'AC1'),
('TASI',2,'AC2','TASI',1,'AC2'),
('TASI',2,'AL1','TASI',1,'AL1'),
('TASI',2,'AL2','TASI',1,'AL2'),
('TASI',2,'DHQ','TASI',1,'EDF'),
('TASI',2,'EMP','TASI',1,'AD1'),
('TASI',2,'ESD','TASI',1,'ESD'),
('TASI',2,'IIT','TASI',1,'ITE'),
('TASI',2,'INT','TASI',1,'INT'),
('TASI',2,'LP1','TASI',1,'LP1'),
('TASI',2,'LP2','TASI',1,'LP2'),
('TASI',2,'LP3','TASI',1,'LP3'),
('TASI',2,'LPO','TASI',1,'LPO'),
('TASI',2,'ME1','TASI',1,'MET'),
('TASI',2,'MT1','TASI',1,'MT1'),
('TASI',2,'MT2','TASI',1,'MT2'),
('TASI',2,'OO1','TASI',1,'OO1'),
('TASI',2,'OO2','TASI',1,'OO2'),
('TASI',2,'OO3','TASI',1,'OO3'),
('TASI',2,'PBD','TASI',1,'PBD'),
('TASI',2,'PRO','TASI',1,'PRO'),
('TASI',2,'RD1','TASI',1,'RD1'),
('TASI',2,'RD2','TASI',1,'RD2'),
('TASI',2,'SBD','TASI',1,'GBD'),
('TASI',2,'SOP','TASI',1,'SOP'),
('TASI',2,'TA2','TASI',1,'TIL2'),
('TASI',2,'TA2','TASI',1,'TIL3');

-- Equivalências da matriz 3 (2004) para a matriz 2 (2003.1 e 2003.2)
-- Atenção: como só foram oferecidas o 1o. e 2o. período letivo da matriz 1 (2002), só foram estabelecidas as equivalências para esses componentes curriculares
INSERT INTO `ComponenteEquivalente` (`siglaCurso`, `idMatriz`, `siglaDisciplina`, 
	`siglaCursoEquivalente`, `idMatrizEquivalente`, `siglaDisciplinaEquivalente`) VALUES 
('TASI',3,'AC1','TASI',2,'AC1'),
('TASI',3,'AC2','TASI',2,'AC2'),
('TASI',3,'ADM','TASI',2,'ADM'),
('TASI',3,'AL1','TASI',2,'AL1'),
('TASI',3,'AL1','TASI',2,'LP1'),
('TASI',3,'AL2','TASI',2,'AL2'),
('TASI',3,'AL2','TASI',2,'LP2'),
('TASI',3,'ALG','TASI',2,'ALG'),
('TASI',3,'APS','TASI',2,'AP1'),
('TASI',3,'APS','TASI',2,'AP2'),
('TASI',3,'DHQ','TASI',2,'DHQ'),
('TASI',3,'DIF','TASI',2,'DIF'),
('TASI',3,'EMP','TASI',2,'EMP'),
('TASI',3,'ENG','TASI',2,'ENG'),
('TASI',3,'ESD','TASI',2,'ESD'),
('TASI',3,'EST','TASI',2,'EST'),
('TASI',3,'GPS','TASI',2,'GPS'),
('TASI',3,'IBD','TASI',2,'IBD'),
('TASI',3,'IHM','TASI',2,'IHM'),
('TASI',3,'IIT','TASI',2,'IIT'),
('TASI',3,'INS','TASI',2,'INS'),
('TASI',3,'INT','TASI',2,'INT'),
('TASI',3,'LPO','TASI',2,'LPO'),
('TASI',3,'LPW','TASI',2,'PF2'),
('TASI',3,'LPW','TASI',2,'TA2'),
('TASI',3,'MAT','TASI',2,'MT1'),
('TASI',3,'MAT','TASI',2,'MT2'),
('TASI',3,'ME1','TASI',2,'ME1'),
('TASI',3,'ME2','TASI',2,'ME2'),
('TASI',3,'OO1','TASI',2,'OO1'),
('TASI',3,'OO2','TASI',2,'OO2'),
('TASI',3,'PBD','TASI',2,'PBD'),
('TASI',3,'PJF','TASI',2,'PF1'),
('TASI',3,'PRO','TASI',2,'PRO'),
('TASI',3,'RD1','TASI',2,'RD1'),
('TASI',3,'RD2','TASI',2,'RD2'),
('TASI',3,'SBD','TASI',2,'SBD'),
('TASI',3,'SOP','TASI',2,'SOP'),
('TASI',3,'TAV','TASI',2,'TA1'),
('TASI',3,'TRI','TASI',2,'TRI');

-- Equivalências da matriz 4 (2005, aprovada) para a matriz 3 (2004.1 e 2004.2)
INSERT INTO `ComponenteEquivalente` (`siglaCurso`, `idMatriz`, `siglaDisciplina`, 
	`siglaCursoEquivalente`, `idMatrizEquivalente`, `siglaDisciplinaEquivalente`) VALUES 
	('TASI',4,'AC1','TASI',3,'AC1'),
	('TASI',4,'AC2','TASI',3,'AC2'),
	('TASI',4,'ADM','TASI',3,'ADM'),
	('TASI',4,'AL1','TASI',3,'AL1'),
	('TASI',4,'AL2','TASI',3,'AL2'),
	('TASI',4,'ALG','TASI',3,'ALG'),
	('TASI',4,'APS','TASI',3,'APS'),
	('TASI',4,'DHQ','TASI',3,'DHQ'),
	('TASI',4,'DIF','TASI',3,'DIF'),
	('TASI',4,'EMP','TASI',3,'EMP'),
	('TASI',4,'ENG','TASI',3,'ENG'),
	('TASI',4,'ESD','TASI',3,'ESD'),
	('TASI',4,'EST','TASI',3,'EST'),
	('TASI',4,'GPS','TASI',3,'GPS'),
	('TASI',4,'IBD','TASI',3,'IBD'),	
	('TASI',4,'IHM','TASI',3,'IHM'),
	('TASI',4,'IIT','TASI',3,'IIT'),
	('TASI',4,'INS','TASI',3,'INS'),
	('TASI',4,'INT','TASI',3,'INT'),
	('TASI',4,'LPO','TASI',3,'LPO'),
	('TASI',4,'LPW','TASI',3,'LPW'),
	('TASI',4,'MAT','TASI',3,'MAT'),
	('TASI',4,'ME1','TASI',3,'ME1'),
	('TASI',4,'ME2','TASI',3,'ME2'),
	('TASI',4,'OO1','TASI',3,'OO1'),
	('TASI',4,'OO2','TASI',3,'OO2'),
	('TASI',4,'PSW','TASI',3,'PRO'),
	('TASI',4,'RD1','TASI',3,'RD1'),
	('TASI',4,'RD2','TASI',3,'RD2'),
	('TASI',4,'SOP','TASI',3,'SOP'),
	('TASI',4,'SPB','TASI',3,'PBD'),
	('TASI',4,'SPB','TASI',3,'SBD'),
	('TASI',4,'TAV','TASI',3,'TAV'),
	('TASI',4,'TRI','TASI',3,'TRI');
COMMIT;

-- Equivalências da matriz 5 (2006.1 em diante) para a matriz 4 (2005, arprovada)
INSERT INTO `ComponenteEquivalente` (`siglaCurso`, `idMatriz`, `siglaDisciplina`, 
	`siglaCursoEquivalente`, `idMatrizEquivalente`, `siglaDisciplinaEquivalente`) VALUES 
	('TASI',5,'AC1','TASI',4,'AC1'),
	('TASI',5,'AC2','TASI',4,'AC2'),
	('TASI',5,'ADM','TASI',4,'ADM'),
	('TASI',5,'AL1','TASI',4,'AL1'),
	('TASI',5,'AL2','TASI',4,'AL2'),
	('TASI',5,'ALG','TASI',4,'ALG'),
	('TASI',5,'APS','TASI',4,'APS'),
	('TASI',5,'DHQ','TASI',4,'DHQ'),
	('TASI',5,'DIF','TASI',4,'DIF'),
	('TASI',5,'EMP','TASI',4,'EMP'),
	('TASI',5,'ENG','TASI',4,'ENG'),
	('TASI',5,'ESD','TASI',4,'ESD'),
	('TASI',5,'EST','TASI',4,'EST'),
	('TASI',5,'GPS','TASI',4,'GPS'),
	('TASI',5,'IBD','TASI',4,'IBD'),	
	('TASI',5,'IHM','TASI',4,'IHM'),
	('TASI',5,'IIT','TASI',4,'IIT'),
	('TASI',5,'INS','TASI',4,'INS'),
	('TASI',5,'INT','TASI',4,'INT'),
	('TASI',5,'LPO','TASI',4,'LPO'),
	('TASI',5,'LPW','TASI',4,'LPW'),
	('TASI',5,'MAT','TASI',4,'MAT'),
	('TASI',5,'ME1','TASI',4,'ME1'),
	('TASI',5,'ME2','TASI',4,'ME2'),
	('TASI',5,'OO1','TASI',4,'OO1'),
	('TASI',5,'OO2','TASI',4,'OO2'),
	('TASI',5,'PSW','TASI',4,'PSW'),
	('TASI',5,'RD1','TASI',4,'RD1'),
	('TASI',5,'RD2','TASI',4,'RD2'),
	('TASI',5,'SOP','TASI',4,'SOP'),
	('TASI',5,'SPB','TASI',4,'SPB'),
	('TASI',5,'TAV','TASI',4,'TAV'),
	('TASI',5,'TRI','TASI',4,'TRI');
COMMIT;


-- -----------------------------------------------------
-- Data for table `ComponentePreRequisito`
-- -----------------------------------------------------
SET AUTOCOMMIT=0;
INSERT INTO `ComponentePreRequisito` (`siglaCurso`, `idMatriz`, `siglaDisciplina`, 
	`siglaCursoPreRequisito`, `idMatrizPreRequisito`, `siglaDisciplinaPreRequisito`) VALUES 
	('TASI', 5,'AL2','TASI', 5,'AL1'),
	('TASI', 5,'AC2','TASI', 5,'AC1'),
	('TASI', 5,'SOP','TASI', 5,'AC1'),
	('TASI', 5,'ME2','TASI', 5,'ME1'),
	('TASI', 5,'ESD','TASI', 5,'AL2'),
	('TASI', 5,'OO1','TASI', 5,'AL2'),
	('TASI', 5,'OO2','TASI', 5,'OO1'),
	('TASI', 5,'PSW','TASI', 5,'OO1'),
	('TASI', 5,'IBD','TASI', 5,'SPB'),
	('TASI', 5,'APS','TASI', 5,'ENG'),
	('TASI', 5,'LPW','TASI', 5,'OO2'),
	('TASI', 5,'LPW','TASI', 5,'INT');
COMMIT;

-- -----------------------------------------------------
-- Data for table `TipoDocumento`
-- -----------------------------------------------------
SET AUTOCOMMIT=0;
INSERT INTO `TipoDocumento` (`idTipoDocumento`, `descricao`) VALUE (1,'Cópia da Certidão Nascimento');
INSERT INTO `TipoDocumento` (`idTipoDocumento`, `descricao`) VALUE (2,'Cópia da Certidão de Casamento');
INSERT INTO `TipoDocumento` (`idTipoDocumento`, `descricao`) VALUE (3,'Cópia do Documento de Identidade');
INSERT INTO `TipoDocumento` (`idTipoDocumento`, `descricao`) VALUE (4,'Cópia do CPF');
INSERT INTO `TipoDocumento` (`idTipoDocumento`, `descricao`) VALUE (5,'Cópia do Comprovante de Residência');
INSERT INTO `TipoDocumento` (`idTipoDocumento`, `descricao`) VALUE (6,'Cópia do Certificado de Alistamento Militar');
INSERT INTO `TipoDocumento` (`idTipoDocumento`, `descricao`) VALUE (7,'Cópia do Certificado de Reservista');
INSERT INTO `TipoDocumento` (`idTipoDocumento`, `descricao`) VALUE (8,'Cópia do Certificado do Ensino Médio');
INSERT INTO `TipoDocumento` (`idTipoDocumento`, `descricao`) VALUE (9,'Cópia do Publicação no D.O. Ensino Médio');
INSERT INTO `TipoDocumento` (`idTipoDocumento`, `descricao`) VALUE (10,'Original do Histório Escolar');
COMMIT;

-- -----------------------------------------------------
-- Data for table `CursoTipoDocumento`
-- -----------------------------------------------------
SET AUTOCOMMIT=0;
INSERT INTO `CursoTipoDocumento` (`siglaCurso`, `idTipoDocumento`) VALUE ('TASI',1);
INSERT INTO `CursoTipoDocumento` (`siglaCurso`, `idTipoDocumento`) VALUE ('TASI',2);
INSERT INTO `CursoTipoDocumento` (`siglaCurso`, `idTipoDocumento`) VALUE ('TASI',3);
INSERT INTO `CursoTipoDocumento` (`siglaCurso`, `idTipoDocumento`) VALUE ('TASI',4);
INSERT INTO `CursoTipoDocumento` (`siglaCurso`, `idTipoDocumento`) VALUE ('TASI',5);
INSERT INTO `CursoTipoDocumento` (`siglaCurso`, `idTipoDocumento`) VALUE ('TASI',6);
INSERT INTO `CursoTipoDocumento` (`siglaCurso`, `idTipoDocumento`) VALUE ('TASI',7);
INSERT INTO `CursoTipoDocumento` (`siglaCurso`, `idTipoDocumento`) VALUE ('TASI',8);
INSERT INTO `CursoTipoDocumento` (`siglaCurso`, `idTipoDocumento`) VALUE ('TASI',9);
INSERT INTO `CursoTipoDocumento` (`siglaCurso`, `idTipoDocumento`) VALUE ('TASI',10);
COMMIT;

-- -----------------------------------------------------
-- Data for table `Funcao`
-- -----------------------------------------------------
SET AUTOCOMMIT=0;

INSERT INTO `Funcao` (`idCasoUso`, `descricao`, `critico`) VALUE ('UC01.09.00','Emitir Ficha de Matrícula','NÃO');
INSERT INTO `Funcao` (`idCasoUso`, `descricao`, `critico`) VALUE ('UC01.02.00','Emitir Histórico de Aluno','NÃO');
INSERT INTO `Funcao` (`idCasoUso`, `descricao`, `critico`) VALUE ('UC01.07.00','Emitir Listagem de Alunos por Turma','NÃO');
INSERT INTO `Funcao` (`idCasoUso`, `descricao`, `critico`) VALUE ('UC01.04.00','Emitir Diário de Classe','NÃO');
INSERT INTO `Funcao` (`idCasoUso`, `descricao`, `critico`) VALUE ('UC01.05.00','Emitir Relatório de Alunos por Situação','NÃO');
INSERT INTO `Funcao` (`idCasoUso`, `descricao`, `critico`) VALUE ('UC01.03.00','Manter Turmas','NÃO');
INSERT INTO `Funcao` (`idCasoUso`, `descricao`, `critico`) VALUE ('UC01.03.01','Editar Turma','NÃO');
INSERT INTO `Funcao` (`idCasoUso`, `descricao`, `critico`) VALUE ('UC01.03.02','Criar Turma','NÃO');
INSERT INTO `Funcao` (`idCasoUso`, `descricao`, `critico`) VALUE ('UC01.03.03','Mudar Situação da Turmas','NÃO');
INSERT INTO `Funcao` (`idCasoUso`, `descricao`, `critico`) VALUE ('UC01.03.04','Reabrir Turma Finalizada','SIM');
INSERT INTO `Funcao` (`idCasoUso`, `descricao`, `critico`) VALUE ('UC01.08.00','Lançar Notas e Situação do Aluno em Turma','NÃO');
INSERT INTO `Funcao` (`idCasoUso`, `descricao`, `critico`) VALUE ('UC01.08.01','Editar Notas e Situação do Aluno em Turma','SIM');
INSERT INTO `Funcao` (`idCasoUso`, `descricao`, `critico`) VALUE ('UC02.02.00','Manter Período Letivo','NÃO');
INSERT INTO `Funcao` (`idCasoUso`, `descricao`, `critico`) VALUE ('UC02.02.03','Incluir Período Letivo','SIM');
INSERT INTO `Funcao` (`idCasoUso`, `descricao`, `critico`) VALUE ('UC02.07.00', 'Manter Alunos que Cursam uma Turma', 'NÃO');
INSERT INTO `Funcao` (`idCasoUso`, `descricao`, `critico`) VALUE ('UC02.06.00', 'Solicitar Inscricao em Turmas', 'NÃO');
INSERT INTO `Funcao` (`idCasoUso`, `descricao`, `critico`) VALUE ('UC02.06.01', 'Emitir Protocolo com Grade Horária Solicitada', 'NÃO');
INSERT INTO `Funcao` (`idCasoUso`, `descricao`, `critico`) VALUE ('UC02.06.02', 'Excluir Solicitação de Inscrição em Turma', 'NÃO');
INSERT INTO `Funcao` (`idCasoUso`, `descricao`, `critico`) VALUE ('UC02.06.03', 'Selecionar Aluno para Inscrição', 'NÃO');
INSERT INTO `Funcao` (`idCasoUso`, `descricao`, `critico`) VALUE ('UC02.07.01', 'Incluir Alunos que Cursam uma Turma', 'NÃO');
INSERT INTO `Funcao` (`idCasoUso`, `descricao`, `critico`) VALUE ('UC02.01.00', 'Manter Situação de Inscrições em Turmas', 'NÃO');
INSERT INTO `Funcao` (`idCasoUso`, `descricao`, `critico`) VALUE ('UC02.01.01', 'Deferir Solicitação de Inscrição Com Justificava', 'NÃO');
INSERT INTO `Funcao` (`idCasoUso`, `descricao`, `critico`) VALUE ('UC02.01.02', 'Indeferir Solicitação de Inscrição', 'NÃO');
INSERT INTO `Funcao` (`idCasoUso`, `descricao`, `critico`) VALUE ('UC02.01.03', 'Cancelar Solicitação de Inscrição', 'NÃO');
INSERT INTO `Funcao` (`idCasoUso`, `descricao`, `critico`) VALUE ('UC02.02.01', 'Manter Eventos de Período Letivo', 'NÃO');
INSERT INTO `Funcao` (`idCasoUso`, `descricao`, `critico`) VALUE ('UC02.02.01.00', 'Incluir Eventos de Período Letivo', 'NÃO');
INSERT INTO `Funcao` (`idCasoUso`, `descricao`, `critico`) VALUE ('UC02.02.01.01', 'Alterar Eventos de Período Letivo', 'NÃO');
INSERT INTO `Funcao` (`idCasoUso`, `descricao`, `critico`) VALUE ('UC02.02.01.02', 'Excluir Eventos de Período Letivo', 'NÃO');
INSERT INTO `Funcao` (`idCasoUso`, `descricao`, `critico`) VALUE ('UC02.02.02', 'Emitir Calendário Letivo', 'NÃO');
INSERT INTO `Funcao` (`idCasoUso`, `descricao`, `critico`) VALUE ('UC02.02.04', 'Alterar Período Letivo', 'NÃO');
INSERT INTO `Funcao` (`idCasoUso`, `descricao`, `critico`) VALUE ('UC02.02.05', 'Excluir Período Letivo', 'NÃO');
INSERT INTO `Funcao` (`idCasoUso`, `descricao`, `critico`) VALUE ('UC02.03.00', 'Exibir Resultado da Solicitação em Inscrição', 'NÃO');

INSERT INTO `Funcao` (`idCasoUso`, `descricao`, `critico`) VALUE ('UC01.01.00','Consultar dados do aluno','NÃO');
INSERT INTO `Funcao` (`idCasoUso`, `descricao`, `critico`) VALUE ('UC01.01.01','Incluir Aluno','SIM');
INSERT INTO `Funcao` (`idCasoUso`, `descricao`, `critico`) VALUE ('UC01.01.02','Editar Aluno','SIM');
INSERT INTO `Funcao` (`idCasoUso`, `descricao`, `critico`) VALUE ('UC01.01.03','Editar Matrícula Aluno','SIM');
INSERT INTO `Funcao` (`idCasoUso`, `descricao`, `critico`) VALUE ('UC01.01.04','Incluir Matrícula Aluno','SIM');
INSERT INTO `Funcao` (`idCasoUso`, `descricao`, `critico`) VALUE ('UC01.01.05','Mudar Situação Entrega de Documento de Matrícula Aluno','NÃO');
INSERT INTO `Funcao` (`idCasoUso`, `descricao`, `critico`) VALUE ('UC03.02.00', 'Manter Situação de Matrículas', 'NÃO');
INSERT INTO `Funcao` (`idCasoUso`, `descricao`, `critico`) VALUE ('UC03.02.01', 'Reativar Matrícula', 'SIM');
INSERT INTO `Funcao` (`idCasoUso`, `descricao`, `critico`) VALUE ('UC03.02.02', 'Trancar Matrícula', 'SIM');
INSERT INTO `Funcao` (`idCasoUso`, `descricao`, `critico`) VALUE ('UC03.02.03', 'Processar Rematrícula Automática', 'SIM');
INSERT INTO `Funcao` (`idCasoUso`, `descricao`, `critico`) VALUE ('UC03.02.04', 'Concluir Matrícula', 'SIM');
INSERT INTO `Funcao` (`idCasoUso`, `descricao`, `critico`) VALUE ('UC03.02.05', 'Desistir de Matrícula', 'SIM');
INSERT INTO `Funcao` (`idCasoUso`, `descricao`, `critico`) VALUE ('UC03.02.06', 'Desligar Matrícula', 'SIM');
INSERT INTO `Funcao` (`idCasoUso`, `descricao`, `critico`) VALUE ('UC03.02.07', 'Renovar Matrícula', 'SIM');
INSERT INTO `Funcao` (`idCasoUso`, `descricao`, `critico`) VALUE ('UC03.03.00', 'Emitir Grade de Horário de Tempos de Aula', 'NÃO');
INSERT INTO `Funcao` (`idCasoUso`, `descricao`, `critico`) VALUE ('UC03.04.00', 'Selecionar Matrícula de Aluno', 'NÃO');
INSERT INTO `Funcao` (`idCasoUso`, `descricao`, `critico`) VALUE ('UC03.05.00', 'Emitir Espelho de Ocupação de Espaço', 'NÃO');
INSERT INTO `Funcao` (`idCasoUso`, `descricao`, `critico`) VALUE ('UC03.06.00', 'Exportar Dados para Carteirinha dos Alunos', 'SIM');
INSERT INTO `Funcao` (`idCasoUso`, `descricao`, `critico`) VALUE ('UC03.07.00', 'Emitir Alocação de Professor por Curso', 'NÃO');
INSERT INTO `Funcao` (`idCasoUso`, `descricao`, `critico`) VALUE ('UC03.08.00', 'Emitir Resumo de Alocações de Professores', 'NÃO');
INSERT INTO `Funcao` (`idCasoUso`, `descricao`, `critico`) VALUE ('UC03.09.00', 'Manter Login', 'NÃO');
INSERT INTO `Funcao` (`idCasoUso`, `descricao`, `critico`) VALUE ('UC03.09.01', 'Criar Login', 'NÃO');
INSERT INTO `Funcao` (`idCasoUso`, `descricao`, `critico`) VALUE ('UC03.09.02', 'Alterar Foto', 'NÃO');
INSERT INTO `Funcao` (`idCasoUso`, `descricao`, `critico`) VALUE ('UC03.09.03', 'Resetar Senha', 'NÃO');
INSERT INTO `Funcao` (`idCasoUso`, `descricao`, `critico`) VALUE ('UC03.09.04', 'Alterar Login','NÃO');
INSERT INTO `Funcao` (`idCasoUso`, `descricao`, `critico`) VALUE ('UC03.09.05', 'Desbloquear Login','SIM');
INSERT INTO `Funcao` (`idCasoUso`, `descricao`, `critico`) VALUE ('UC03.10.00', 'Manter Espaço','NÃO');
INSERT INTO `Funcao` (`idCasoUso`, `descricao`, `critico`) VALUE ('UC03.10.01', 'Incluir Espaço','NÃO');
INSERT INTO `Funcao` (`idCasoUso`, `descricao`, `critico`) VALUE ('UC03.10.02', 'Alterar Espaço','NÃO');
INSERT INTO `Funcao` (`idCasoUso`, `descricao`, `critico`) VALUE ('UC03.10.03', 'Excluir Espaço','NÃO');
INSERT INTO `Funcao` (`idCasoUso`, `descricao`, `critico`) VALUE ('UC03.11.01', 'Bloquear Login','SIM');
INSERT INTO `Funcao` (`idCasoUso`, `descricao`, `critico`) VALUE ('UC03.12.00','Emitir Extrato de Turma para Professor','NÃO');
insert into `Funcao` (idCasoUso, descricao,critico) VALUE ('UC04.11.00','Manter Tipo de Curso','NÃO');
insert into `Funcao` (idCasoUso, descricao,critico) VALUE ('UC04.11.01','Incluir Tipo de Curso','NÃO');
insert into `Funcao` (idCasoUso, descricao,critico) VALUE ('UC04.11.02','Alterar Tipo de Curso','SIM');
insert into `Funcao` (idCasoUso, descricao,critico) VALUE ('UC04.11.03','Excluir Tipo de Curso','NÃO');
insert into `Funcao` (idCasoUso, descricao,critico) VALUE ('UC05.10.00','Manter Curso','NÃO');
insert into `Funcao` (idCasoUso, descricao,critico) VALUE ('UC05.10.01','Incluir Curso','NÃO');
insert into `Funcao` (idCasoUso, descricao,critico) VALUE ('UC05.10.02','Alterar Curso','SIM');
insert into `Funcao` (idCasoUso, descricao,critico) VALUE ('UC05.10.03','Excluir Curso','NÃO');
insert into `Funcao` (idCasoUso, descricao,critico) VALUE ('UC06.10.00','Manter Professor - Consultar','NÃO');
insert into `Funcao` (idCasoUso, descricao,critico) VALUE ('UC06.10.01','Incluir Professor','SIM');
insert into `Funcao` (idCasoUso, descricao,critico) VALUE ('UC06.10.02','Alterar Professor','SIM');
insert into `Funcao` (idCasoUso, descricao,critico) VALUE ('UC07.00.00','Emitir Declaração de Matrícula em Curso','NÃO');
insert into `Funcao` (idCasoUso, descricao,critico) VALUE ('UC07.00.01','Alterar Período Relativo da Declaração de Matrícula em Curso','SIM');
insert into `Funcao` (idCasoUso, descricao,critico) VALUE ('UC08.00.00', 'Apontar Dia Letivo de Turma', 'NÃO');
insert into `Funcao` (idCasoUso, descricao,critico) VALUE ('UC08.00.01', 'Reabrir Dia Letivo de Turma', 'SIM');
insert into `Funcao` (idCasoUso, descricao,critico) VALUE ('UC08.00.02', 'Alterar Dia Letivo de Turma', 'SIM');
insert into `Funcao` (idCasoUso, descricao,critico) VALUE ('UC08.00.03', 'Reclamar aluno omisso na pauta da turma', 'SIM');
insert into `Funcao` (idCasoUso, descricao,critico) VALUE ('UC08.00.04', 'Liberar Notas de Turma', 'SIM');
insert into `Funcao` (idCasoUso, descricao,critico) VALUE ('UC08.00.05', 'Reabrir Notas de Turma', 'SIM');
insert into `Funcao` (idCasoUso, descricao,critico) VALUE ('UC08.00.06', 'Liberar Pauta de Turma', 'SIM');
insert into `Funcao` (idCasoUso, descricao,critico) VALUE ('UC01.03.05', 'Devolver Pauta de Turma ao Professor', 'NÃO');

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



COMMIT;

-- -----------------------------------------------------
-- Data for table `Espaco`
-- -----------------------------------------------------
SET AUTOCOMMIT=0;
INSERT INTO `Espaco` (`idEspaco`, `nome`, `capacidade`) VALUE (1,'Híbrida 1',35);
INSERT INTO `Espaco` (`idEspaco`, `nome`, `capacidade`) VALUE (2,'Híbrida 2',35);
INSERT INTO `Espaco` (`idEspaco`, `nome`, `capacidade`) VALUE (3,'Híbrida 3',30);
INSERT INTO `Espaco` (`idEspaco`, `nome`, `capacidade`) VALUE (4,'Híbrida 4',30);
INSERT INTO `Espaco` (`idEspaco`, `nome`, `capacidade`) VALUE (5,'Híbrida 5',16);
INSERT INTO `Espaco` (`idEspaco`, `nome`, `capacidade`) VALUE (6,'Múltipla',40);
INSERT INTO `Espaco` (`idEspaco`, `nome`, `capacidade`) VALUE (7,'Área de Convivência Fernando Mota',60);
COMMIT;

-- -----------------------------------------------------
-- Data for table `Pessoa`
-- -----------------------------------------------------
-- Os professores ficam, para efeito de carga de dados básicos durante o uso do SisAcadToCoruja, com os códigos reservados de 1000 até 1030
SET AUTOCOMMIT=0;
INSERT INTO `Pessoa` 	(`idPessoa`,	`nome`, 					`sexo`, 	`enderecoLogradouro`, 	`enderecoNumero`, 	`enderecoComplemento`, 	`enderecoBairro`, 		`enderecoMunicipio`, 	`enderecoEstado`, 	`enderecoCEP`, 	`dataNascimento`, 	`nacionalidade`, 	`naturalidade`, 	`telefoneResidencial`, 	`telefoneComercial`, 	`telefoneCelular`, 	`email`)
	VALUE 				(1000,			'Márcio Francisco Campos',	'M',		'Rua das Coves',		'1',				'apto.101',				'Quintino Bocaiúva',	'Rio de Janeiro',		'RJ',				'22222-333',	'1970-01-01',		'BRASILEIRA',		'RIO DE JANEIRO',	'(21)2222-3333',		'(21)4444-5555',		'(21)9999-8888',	'ist-rio@faetec.rj.gov.br');
INSERT INTO `Pessoa` 	(`idPessoa`,	`nome`, 								`sexo`, `enderecoLogradouro`, 	`enderecoNumero`, 	`enderecoComplemento`, 	`enderecoBairro`, 		`enderecoMunicipio`, 	`enderecoEstado`, 	`enderecoCEP`, 	`dataNascimento`, 	`nacionalidade`, 	`naturalidade`, 	`telefoneResidencial`, 	`telefoneComercial`, 	`telefoneCelular`, 	`email`)
	VALUE 				(1001,			'Alfredo Nazareno Pereira Boente',		'M',	'Rua das Coves',		'1',				'apto.101',				'Quintino Bocaiúva',	'Rio de Janeiro',		'RJ',				'22222-333',	'1970-01-01',		'BRASILEIRA',		'RIO DE JANEIRO',	'(21)2222-3333',		'(21)4444-5555',		'(21)9999-8888',	'ist-rio@faetec.rj.gov.br');
INSERT INTO `Pessoa` 	(`idPessoa`,	`nome`, 								`sexo`, `enderecoLogradouro`, 	`enderecoNumero`, 	`enderecoComplemento`, 	`enderecoBairro`, 		`enderecoMunicipio`, 	`enderecoEstado`, 	`enderecoCEP`, 	`dataNascimento`, 	`nacionalidade`, 	`naturalidade`, 	`telefoneResidencial`, 	`telefoneComercial`, 	`telefoneCelular`, 	`email`)
	VALUE 				(1002,			'Cezar Bezerra Teixeira',				'M',	'Rua das Coves',		'1',				'apto.101',				'Quintino Bocaiúva',	'Rio de Janeiro',		'RJ',				'22222-333',	'1970-01-01',		'BRASILEIRA',		'RIO DE JANEIRO',	'(21)2222-3333',		'(21)4444-5555',		'(21)9999-8888',	'ist-rio@faetec.rj.gov.br');
INSERT INTO `Pessoa` 	(`idPessoa`,	`nome`, 								`sexo`, `enderecoLogradouro`, 	`enderecoNumero`, 	`enderecoComplemento`, 	`enderecoBairro`, 		`enderecoMunicipio`, 	`enderecoEstado`, 	`enderecoCEP`, 	`dataNascimento`, 	`nacionalidade`, 	`naturalidade`, 	`telefoneResidencial`, 	`telefoneComercial`, 	`telefoneCelular`, 	`email`)
	VALUE 				(1003,			'Cládice Nóbile Diniz',					'F',	'Rua das Coves',		'1',				'apto.101',				'Quintino Bocaiúva',	'Rio de Janeiro',		'RJ',				'22222-333',	'1970-01-01',		'BRASILEIRA',		'RIO DE JANEIRO',	'(21)2222-3333',		'(21)4444-5555',		'(21)9999-8888',	'ist-rio@faetec.rj.gov.br');
INSERT INTO `Pessoa` 	(`idPessoa`,	`nome`, 								`sexo`, `enderecoLogradouro`, 	`enderecoNumero`, 	`enderecoComplemento`, 	`enderecoBairro`, 		`enderecoMunicipio`, 	`enderecoEstado`, 	`enderecoCEP`, 	`dataNascimento`, 	`nacionalidade`, 	`naturalidade`, 	`telefoneResidencial`, 	`telefoneComercial`, 	`telefoneCelular`, 	`email`)
	VALUE 				(1004,			'Guilherme Teixeira Azeredo Martins',	'M',	'Rua das Coves',		'1',				'apto.101',				'Quintino Bocaiúva',	'Rio de Janeiro',		'RJ',				'22222-333',	'1970-01-01',		'BRASILEIRA',		'RIO DE JANEIRO',	'(21)2222-3333',		'(21)4444-5555',		'(21)9999-8888',	'ist-rio@faetec.rj.gov.br');
INSERT INTO `Pessoa` 	(`idPessoa`,	`nome`, 								`sexo`, `enderecoLogradouro`, 	`enderecoNumero`, 	`enderecoComplemento`, 	`enderecoBairro`, 		`enderecoMunicipio`, 	`enderecoEstado`, 	`enderecoCEP`, 	`dataNascimento`, 	`nacionalidade`, 	`naturalidade`, 	`telefoneResidencial`, 	`telefoneComercial`, 	`telefoneCelular`, 	`email`)
	VALUE 				(1005,			'Horacio da Cunha e Sousa Ribeiro',		'M',	'Rua das Coves',		'1',				'apto.101',				'Quintino Bocaiúva',	'Rio de Janeiro',		'RJ',				'22222-333',	'1970-01-01',		'BRASILEIRA',		'RIO DE JANEIRO',	'(21)2222-3333',		'(21)4444-5555',		'(21)9999-8888',	'ist-rio@faetec.rj.gov.br');
INSERT INTO `Pessoa` 	(`idPessoa`,	`nome`, 								`sexo`, `enderecoLogradouro`, 	`enderecoNumero`, 	`enderecoComplemento`, 	`enderecoBairro`, 		`enderecoMunicipio`, 	`enderecoEstado`, 	`enderecoCEP`, 	`dataNascimento`, 	`nacionalidade`, 	`naturalidade`, 	`telefoneResidencial`, 	`telefoneComercial`, 	`telefoneCelular`, 	`email`)
	VALUE 				(1006,			'Leonardo Soares Vianna',				'M',	'Rua das Coves',		'1',				'apto.101',				'Quintino Bocaiúva',	'Rio de Janeiro',		'RJ',				'22222-333',	'1970-01-01',		'BRASILEIRA',		'RIO DE JANEIRO',	'(21)2222-3333',		'(21)4444-5555',		'(21)9999-8888',	'ist-rio@faetec.rj.gov.br');
INSERT INTO `Pessoa` 	(`idPessoa`,	`nome`, 								`sexo`, `enderecoLogradouro`, 	`enderecoNumero`, 	`enderecoComplemento`, 	`enderecoBairro`, 		`enderecoMunicipio`, 	`enderecoEstado`, 	`enderecoCEP`, 	`dataNascimento`, 	`nacionalidade`, 	`naturalidade`, 	`telefoneResidencial`, 	`telefoneComercial`, 	`telefoneCelular`, 	`email`)
	VALUE 				(1007,			'Márcio Gonçalves da Silva',			'M',	'Rua das Coves',		'1',				'apto.101',				'Quintino Bocaiúva',	'Rio de Janeiro',		'RJ',				'22222-333',	'1970-01-01',		'BRASILEIRA',		'RIO DE JANEIRO',	'(21)2222-3333',		'(21)4444-5555',		'(21)9999-8888',	'ist-rio@faetec.rj.gov.br');
INSERT INTO `Pessoa` 	(`idPessoa`,	`nome`, 								`sexo`, `enderecoLogradouro`, 	`enderecoNumero`, 	`enderecoComplemento`, 	`enderecoBairro`, 		`enderecoMunicipio`, 	`enderecoEstado`, 	`enderecoCEP`, 	`dataNascimento`, 	`nacionalidade`, 	`naturalidade`, 	`telefoneResidencial`, 	`telefoneComercial`, 	`telefoneCelular`, 	`email`)
	VALUE 				(1008,			'Márcia Marques Freire',				'F',	'Rua das Coves',		'1',				'apto.101',				'Quintino Bocaiúva',	'Rio de Janeiro',		'RJ',				'22222-333',	'1970-01-01',		'BRASILEIRA',		'RIO DE JANEIRO',	'(21)2222-3333',		'(21)4444-5555',		'(21)9999-8888',	'ist-rio@faetec.rj.gov.br');
INSERT INTO `Pessoa` 	(`idPessoa`,	`nome`, 								`sexo`, `enderecoLogradouro`, 	`enderecoNumero`, 	`enderecoComplemento`, 	`enderecoBairro`, 		`enderecoMunicipio`, 	`enderecoEstado`, 	`enderecoCEP`, 	`dataNascimento`, 	`nacionalidade`, 	`naturalidade`, 	`telefoneResidencial`, 	`telefoneComercial`, 	`telefoneCelular`, 	`email`)
	VALUE 				(1009,			'Marcio de Albuquerque Vianna',			'M',	'Rua das Coves',		'1',				'apto.101',				'Quintino Bocaiúva',	'Rio de Janeiro',		'RJ',				'22222-333',	'1970-01-01',		'BRASILEIRA',		'RIO DE JANEIRO',	'(21)2222-3333',		'(21)4444-5555',		'(21)9999-8888',	'ist-rio@faetec.rj.gov.br');
INSERT INTO `Pessoa` 	(`idPessoa`,	`nome`, 								`sexo`, `enderecoLogradouro`, 	`enderecoNumero`, 	`enderecoComplemento`, 	`enderecoBairro`, 		`enderecoMunicipio`, 	`enderecoEstado`, 	`enderecoCEP`, 	`dataNascimento`, 	`nacionalidade`, 	`naturalidade`, 	`telefoneResidencial`, 	`telefoneComercial`, 	`telefoneCelular`, 	`email`)
	VALUE 				(1010,			'Marcos Paulo Monteiro',				'M',	'Rua das Coves',		'1',				'apto.101',				'Quintino Bocaiúva',	'Rio de Janeiro',		'RJ',				'22222-333',	'1970-01-01',		'BRASILEIRA',		'RIO DE JANEIRO',	'(21)2222-3333',		'(21)4444-5555',		'(21)9999-8888',	'ist-rio@faetec.rj.gov.br');
INSERT INTO `Pessoa` 	(`idPessoa`,	`nome`, 								`sexo`, `enderecoLogradouro`, 	`enderecoNumero`, 	`enderecoComplemento`, 	`enderecoBairro`, 		`enderecoMunicipio`, 	`enderecoEstado`, 	`enderecoCEP`, 	`dataNascimento`, 	`nacionalidade`, 	`naturalidade`, 	`telefoneResidencial`, 	`telefoneComercial`, 	`telefoneCelular`, 	`email`)
	VALUE 				(1011,			'Paulo Ricardo Galhanone',				'M',	'Rua das Coves',		'1',				'apto.101',				'Quintino Bocaiúva',	'Rio de Janeiro',		'RJ',				'22222-333',	'1970-01-01',		'BRASILEIRA',		'RIO DE JANEIRO',	'(21)2222-3333',		'(21)4444-5555',		'(21)9999-8888',	'ist-rio@faetec.rj.gov.br');
INSERT INTO `Pessoa` 	(`idPessoa`,	`nome`, 								`sexo`, `enderecoLogradouro`, 	`enderecoNumero`, 	`enderecoComplemento`, 	`enderecoBairro`, 		`enderecoMunicipio`, 	`enderecoEstado`, 	`enderecoCEP`, 	`dataNascimento`, 	`nacionalidade`, 	`naturalidade`, 	`telefoneResidencial`, 	`telefoneComercial`, 	`telefoneCelular`, 	`email`)
	VALUE 				(1012,			'Rafael Dias Ribeiro',					'M',	'Rua das Coves',		'1',				'apto.101',				'Quintino Bocaiúva',	'Rio de Janeiro',		'RJ',				'22222-333',	'1970-01-01',		'BRASILEIRA',		'RIO DE JANEIRO',	'(21)2222-3333',		'(21)4444-5555',		'(21)9999-8888',	'ist-rio@faetec.rj.gov.br');
INSERT INTO `Pessoa` 	(`idPessoa`,	`nome`, 								`sexo`, `enderecoLogradouro`, 	`enderecoNumero`, 	`enderecoComplemento`, 	`enderecoBairro`, 		`enderecoMunicipio`, 	`enderecoEstado`, 	`enderecoCEP`, 	`dataNascimento`, 	`nacionalidade`, 	`naturalidade`, 	`telefoneResidencial`, 	`telefoneComercial`, 	`telefoneCelular`, 	`email`)
	VALUE 				(1013,			'Regina Célia de Sousa',				'F',	'Rua das Coves',		'1',				'apto.101',				'Quintino Bocaiúva',	'Rio de Janeiro',		'RJ',				'22222-333',	'1970-01-01',		'BRASILEIRA',		'RIO DE JANEIRO',	'(21)2222-3333',		'(21)4444-5555',		'(21)9999-8888',	'ist-rio@faetec.rj.gov.br');
INSERT INTO `Pessoa` 	(`idPessoa`,	`nome`, 								`sexo`, `enderecoLogradouro`, 	`enderecoNumero`, 	`enderecoComplemento`, 	`enderecoBairro`, 		`enderecoMunicipio`, 	`enderecoEstado`, 	`enderecoCEP`, 	`dataNascimento`, 	`nacionalidade`, 	`naturalidade`, 	`telefoneResidencial`, 	`telefoneComercial`, 	`telefoneCelular`, 	`email`)
	VALUE 				(1014,			'Reginaldo da Silva Figueiredo',		'M',	'Rua das Coves',		'1',				'apto.101',				'Quintino Bocaiúva',	'Rio de Janeiro',		'RJ',				'22222-333',	'1970-01-01',		'BRASILEIRA',		'RIO DE JANEIRO',	'(21)2222-3333',		'(21)4444-5555',		'(21)9999-8888',	'ist-rio@faetec.rj.gov.br');
INSERT INTO `Pessoa` 	(`idPessoa`,	`nome`, 								`sexo`, `enderecoLogradouro`, 	`enderecoNumero`, 	`enderecoComplemento`, 	`enderecoBairro`, 		`enderecoMunicipio`, 	`enderecoEstado`, 	`enderecoCEP`, 	`dataNascimento`, 	`nacionalidade`, 	`naturalidade`, 	`telefoneResidencial`, 	`telefoneComercial`, 	`telefoneCelular`, 	`email`) 
	VALUE 				(1015,			'Ricardo Marciano dos Santos',			'M',	'Rua das Coves',		'1',				'apto.101',				'Quintino Bocaiúva',	'Rio de Janeiro',		'RJ',				'22222-333',	'1970-01-01',		'BRASILEIRA',		'RIO DE JANEIRO',	'(21)2222-3333',		'(21)4444-5555',		'(21)9999-8888',	'ist-rio@faetec.rj.gov.br');
INSERT INTO `Pessoa` 	(`idPessoa`,	`nome`, 								`sexo`, `enderecoLogradouro`, 	`enderecoNumero`, 	`enderecoComplemento`, 	`enderecoBairro`, 		`enderecoMunicipio`, 	`enderecoEstado`, 	`enderecoCEP`, 	`dataNascimento`, 	`nacionalidade`, 	`naturalidade`, 	`telefoneResidencial`, 	`telefoneComercial`, 	`telefoneCelular`, 	`email`) 
	VALUE 				(1016,			'Ronaldo Ribeiro Goldschmidt',			'M',	'Rua das Coves',		'1',				'apto.101',				'Quintino Bocaiúva',	'Rio de Janeiro',		'RJ',				'22222-333',	'1970-01-01',		'BRASILEIRA',		'RIO DE JANEIRO',	'(21)2222-3333',		'(21)4444-5555',		'(21)9999-8888',	'ist-rio@faetec.rj.gov.br');
INSERT INTO `Pessoa` 	(`idPessoa`,	`nome`, 								`sexo`, `enderecoLogradouro`, 	`enderecoNumero`, 	`enderecoComplemento`, 	`enderecoBairro`, 		`enderecoMunicipio`, 	`enderecoEstado`, 	`enderecoCEP`, 	`dataNascimento`, 	`nacionalidade`, 	`naturalidade`, 	`telefoneResidencial`, 	`telefoneComercial`, 	`telefoneCelular`, 	`email`) 
	VALUE 				(1017,			'Rosa Amelita Sá Menezes da Mota',		'F',	'Rua das Coves',		'1',				'apto.101',				'Quintino Bocaiúva',	'Rio de Janeiro',		'RJ',				'22222-333',	'1970-01-01',		'BRASILEIRA',		'RIO DE JANEIRO',	'(21)2222-3333',		'(21)4444-5555',		'(21)9999-8888',	'ist-rio@faetec.rj.gov.br');
INSERT INTO `Pessoa` 	(`idPessoa`,	`nome`, 								`sexo`, `enderecoLogradouro`, 	`enderecoNumero`, 	`enderecoComplemento`, 	`enderecoBairro`, 		`enderecoMunicipio`, 	`enderecoEstado`, 	`enderecoCEP`, 	`dataNascimento`, 	`nacionalidade`, 	`naturalidade`, 	`telefoneResidencial`, 	`telefoneComercial`, 	`telefoneCelular`, 	`email`) 
	VALUE 				(1018,			'Rute Cândida de Freitas',				'F',	'Rua das Coves',		'1',				'apto.101',				'Quintino Bocaiúva',	'Rio de Janeiro',		'RJ',				'22222-333',	'1970-01-01',		'BRASILEIRA',		'RIO DE JANEIRO',	'(21)2222-3333',		'(21)4444-5555',		'(21)9999-8888',	'ist-rio@faetec.rj.gov.br');
INSERT INTO `Pessoa` 	(`idPessoa`,	`nome`, 								`sexo`, `enderecoLogradouro`, 	`enderecoNumero`, 	`enderecoComplemento`, 	`enderecoBairro`, 		`enderecoMunicipio`, 	`enderecoEstado`, 	`enderecoCEP`, 	`dataNascimento`, 	`nacionalidade`, 	`naturalidade`, 	`telefoneResidencial`, 	`telefoneComercial`, 	`telefoneCelular`, 	`email`) 
	VALUE 				(1019,			'Themis Aline Calcavecchia dos Santos',	'F',	'Rua das Coves',		'1',				'apto.101',				'Quintino Bocaiúva',	'Rio de Janeiro',		'RJ',				'22222-333',	'1970-01-01',		'BRASILEIRA',		'RIO DE JANEIRO',	'(21)2222-3333',		'(21)4444-5555',		'(21)9999-8888',	'ist-rio@faetec.rj.gov.br');
INSERT INTO `Pessoa` 	(`idPessoa`,	`nome`, 								`sexo`, `enderecoLogradouro`, 	`enderecoNumero`, 	`enderecoComplemento`, 	`enderecoBairro`, 		`enderecoMunicipio`, 	`enderecoEstado`, 	`enderecoCEP`, 	`dataNascimento`, 	`nacionalidade`, 	`naturalidade`, 	`telefoneResidencial`, 	`telefoneComercial`, 	`telefoneCelular`, 	`email`) 
	VALUE 				(1020,			'Vasco Manoel Martins do Amaral',		'M',	'Rua das Coves',		'1',				'apto.101',				'Quintino Bocaiúva',	'Rio de Janeiro',		'RJ',				'22222-333',	'1970-01-01',		'BRASILEIRA',		'RIO DE JANEIRO',	'(21)2222-3333',		'(21)4444-5555',		'(21)9999-8888',	'ist-rio@faetec.rj.gov.br');
INSERT INTO `Pessoa` 	(`idPessoa`,	`nome`, 								`sexo`, `enderecoLogradouro`, 	`enderecoNumero`, 	`enderecoComplemento`, 	`enderecoBairro`, 		`enderecoMunicipio`, 	`enderecoEstado`, 	`enderecoCEP`, 	`dataNascimento`, 	`nacionalidade`, 	`naturalidade`, 	`telefoneResidencial`, 	`telefoneComercial`, 	`telefoneCelular`, 	`email`) 
	VALUE 				(1021,			'Altemar Sales de Oliveira',			'M',	'Rua das Coves',		'1',				'apto.101',				'Quintino Bocaiúva',	'Rio de Janeiro',		'RJ',				'22222-333',	'1970-01-01',		'BRASILEIRA',		'RIO DE JANEIRO',	'(21)2222-3333',		'(21)4444-5555',		'(21)9999-8888',	'ist-rio@faetec.rj.gov.br');
INSERT INTO `Pessoa` 	(`idPessoa`,	`nome`, 								`sexo`, `enderecoLogradouro`, 	`enderecoNumero`, 	`enderecoComplemento`, 	`enderecoBairro`, 		`enderecoMunicipio`, 	`enderecoEstado`, 	`enderecoCEP`, 	`dataNascimento`, 	`nacionalidade`, 	`naturalidade`, 	`telefoneResidencial`, 	`telefoneComercial`, 	`telefoneCelular`, 	`email`) 
	VALUE 				(1022,			'Andreia De Luca Heredia de Sá',		'F',	'Rua das Coves',		'1',				'apto.101',				'Quintino Bocaiúva',	'Rio de Janeiro',		'RJ',				'22222-333',	'1970-01-01',		'BRASILEIRA',		'RIO DE JANEIRO',	'(21)2222-3333',		'(21)4444-5555',		'(21)9999-8888',	'ist-rio@faetec.rj.gov.br');
INSERT INTO `Pessoa` 	(`idPessoa`,	`nome`, 								`sexo`, `enderecoLogradouro`, 	`enderecoNumero`, 	`enderecoComplemento`, 	`enderecoBairro`, 		`enderecoMunicipio`, 	`enderecoEstado`, 	`enderecoCEP`, 	`dataNascimento`, 	`nacionalidade`, 	`naturalidade`, 	`telefoneResidencial`, 	`telefoneComercial`, 	`telefoneCelular`, 	`email`) 
	VALUE 				(1023,			'André Luiz Avelino Sobral',			'M',	'Rua das Coves',		'1',				'apto.101',				'Quintino Bocaiúva',	'Rio de Janeiro',		'RJ',				'22222-333',	'1970-01-01',		'BRASILEIRA',		'RIO DE JANEIRO',	'(21)2222-3333',		'(21)4444-5555',		'(21)9999-8888',	'ist-rio@faetec.rj.gov.br');
INSERT INTO `Pessoa` 	(`idPessoa`,	`nome`, 								`sexo`, `enderecoLogradouro`, 	`enderecoNumero`, 	`enderecoComplemento`, 	`enderecoBairro`, 		`enderecoMunicipio`, 	`enderecoEstado`, 	`enderecoCEP`, 	`dataNascimento`, 	`nacionalidade`, 	`naturalidade`, 	`telefoneResidencial`, 	`telefoneComercial`, 	`telefoneCelular`, 	`email`) 
	VALUE 				(1024,			'Camilla Lobo Paulino',					'F',	'Rua das Coves',		'1',				'apto.101',				'Quintino Bocaiúva',	'Rio de Janeiro',		'RJ',				'22222-333',	'1970-01-01',		'BRASILEIRA',		'RIO DE JANEIRO',	'(21)2222-3333',		'(21)4444-5555',		'(21)9999-8888',	'ist-rio@faetec.rj.gov.br');
INSERT INTO `Pessoa` 	(`idPessoa`,	`nome`, 								`sexo`, `enderecoLogradouro`, 	`enderecoNumero`, 	`enderecoComplemento`, 	`enderecoBairro`, 		`enderecoMunicipio`, 	`enderecoEstado`, 	`enderecoCEP`, 	`dataNascimento`, 	`nacionalidade`, 	`naturalidade`, 	`telefoneResidencial`, 	`telefoneComercial`, 	`telefoneCelular`, 	`email`) 
	VALUE 				(1025,			'Cláudia Ferlin',						'F',	'Rua das Coves',		'1',				'apto.101',				'Quintino Bocaiúva',	'Rio de Janeiro',		'RJ',				'22222-333',	'1970-01-01',		'BRASILEIRA',		'RIO DE JANEIRO',	'(21)2222-3333',		'(21)4444-5555',		'(21)9999-8888',	'ist-rio@faetec.rj.gov.br');
INSERT INTO `Pessoa` 	(`idPessoa`,	`nome`, 								`sexo`, `enderecoLogradouro`, 	`enderecoNumero`, 	`enderecoComplemento`, 	`enderecoBairro`, 		`enderecoMunicipio`, 	`enderecoEstado`, 	`enderecoCEP`, 	`dataNascimento`, 	`nacionalidade`, 	`naturalidade`, 	`telefoneResidencial`, 	`telefoneComercial`, 	`telefoneCelular`, 	`email`) 
	VALUE 				(1026,			'Heliana Borges',						'F',	'Rua das Coves',		'1',				'apto.101',				'Quintino Bocaiúva',	'Rio de Janeiro',		'RJ',				'22222-333',	'1970-01-01',		'BRASILEIRA',		'RIO DE JANEIRO',	'(21)2222-3333',		'(21)4444-5555',		'(21)9999-8888',	'ist-rio@faetec.rj.gov.br');
INSERT INTO `Pessoa` 	(`idPessoa`,	`nome`, 								`sexo`, `enderecoLogradouro`, 	`enderecoNumero`, 	`enderecoComplemento`, 	`enderecoBairro`, 		`enderecoMunicipio`, 	`enderecoEstado`, 	`enderecoCEP`, 	`dataNascimento`, 	`nacionalidade`, 	`naturalidade`, 	`telefoneResidencial`, 	`telefoneComercial`, 	`telefoneCelular`, 	`email`) 
	VALUE 				(1027,			'Ricardo Portella de Aguiar',			'M',	'Rua das Coves',		'1',				'apto.101',				'Quintino Bocaiúva',	'Rio de Janeiro',		'RJ',				'22222-333',	'1970-01-01',		'BRASILEIRA',		'RIO DE JANEIRO',	'(21)2222-3333',		'(21)4444-5555',		'(21)9999-8888',	'ist-rio@faetec.rj.gov.br');
INSERT INTO `Pessoa` 	(`idPessoa`,	`nome`, 								`sexo`, `enderecoLogradouro`, 	`enderecoNumero`, 	`enderecoComplemento`, 	`enderecoBairro`, 		`enderecoMunicipio`, 	`enderecoEstado`, 	`enderecoCEP`, 	`dataNascimento`, 	`nacionalidade`, 	`naturalidade`, 	`telefoneResidencial`, 	`telefoneComercial`, 	`telefoneCelular`, 	`email`) 
	VALUE 				(1028,			'Claudio Passos',						'M',	'Rua das Coves',		'1',				'apto.101',				'Quintino Bocaiúva',	'Rio de Janeiro',		'RJ',				'22222-333',	'1970-01-01',		'BRASILEIRA',		'RIO DE JANEIRO',	'(21)2222-3333',		'(21)4444-5555',		'(21)9999-8888',	'ist-rio@faetec.rj.gov.br');
INSERT INTO `Pessoa` (`idPessoa`, `nome`, `sexo`, `enderecoLogradouro`, `enderecoNumero`,
`enderecoComplemento`, `enderecoBairro`, `enderecoMunicipio`, `enderecoEstado`,
`enderecoCEP`, `dataNascimento`, `nacionalidade`, `naturalidade`, `telefoneResidencial`,
`telefoneComercial`, `telefoneCelular`, `email`)
 VALUES (1029,'Marcio Belo','M','Rua Teste','40',
  'apto.102','Quintino Bocaiúva','Rio de Janeiro','RJ',
  '21032-231','1975-05-17','Brasileira','Rio de Janeiro','21-2222-2123',
  '21-2938-2392','21-9998-2893','mbelo.ist-rio@faetect.rj.gov.br');
INSERT INTO `Pessoa` (`idPessoa`, `nome`, `sexo`, `enderecoLogradouro`, `enderecoNumero`,
`enderecoComplemento`, `enderecoBairro`, `enderecoMunicipio`, `enderecoEstado`,
`enderecoCEP`, `dataNascimento`, `nacionalidade`, `naturalidade`, `telefoneResidencial`,
`telefoneComercial`, `telefoneCelular`, `email`)
 VALUES (1030,'Jamile Allen','F','Rua Teste','40',
  'apto.102','Quintino Bocaiúva','Rio de Janeiro','RJ',
  '21032-231','1975-05-17','Brasileira','Rio de Janeiro','21-2222-2123',
  '21-2938-2392','21-9998-2893','jamileallen@yahoo.com.br');
update Pessoa set nome = UPPER(nome) limit 1000;
COMMIT;

-- -----------------------------------------------------
-- Data for table `Professor`
-- -----------------------------------------------------
SET AUTOCOMMIT=0;
INSERT INTO `Professor` (`idPessoa`, `titulacaoAcademica`, `cvLattes`) VALUE (1000,'MESTRE',NULL);
INSERT INTO `Professor` (`idPessoa`, `titulacaoAcademica`, `cvLattes`) VALUE (1001,'DOUTOR',NULL);
INSERT INTO `Professor` (`idPessoa`, `titulacaoAcademica`, `cvLattes`) VALUE (1002,'MESTRE',NULL);
INSERT INTO `Professor` (`idPessoa`, `titulacaoAcademica`, `cvLattes`) VALUE (1003,'DOUTOR',NULL);
INSERT INTO `Professor` (`idPessoa`, `titulacaoAcademica`, `cvLattes`) VALUE (1004,'MESTRE',NULL);
INSERT INTO `Professor` (`idPessoa`, `titulacaoAcademica`, `cvLattes`, `nomeGuerra`) VALUE (1005,'MESTRE',NULL,'Horácio');
INSERT INTO `Professor` (`idPessoa`, `titulacaoAcademica`, `cvLattes`) VALUE (1006,'MESTRE',NULL);
INSERT INTO `Professor` (`idPessoa`, `titulacaoAcademica`, `cvLattes`) VALUE (1007,'MESTRE',NULL);
INSERT INTO `Professor` (`idPessoa`, `titulacaoAcademica`, `cvLattes`) VALUE (1008,'MESTRE',NULL);
INSERT INTO `Professor` (`idPessoa`, `titulacaoAcademica`, `cvLattes`) VALUE (1009,'MESTRE',NULL);
INSERT INTO `Professor` (`idPessoa`, `titulacaoAcademica`, `cvLattes`) VALUE (1010,'MESTRE',NULL);
INSERT INTO `Professor` (`idPessoa`, `titulacaoAcademica`, `cvLattes`) VALUE (1011,'DOUTOR',NULL);
INSERT INTO `Professor` (`idPessoa`, `titulacaoAcademica`, `cvLattes`) VALUE (1012,'MESTRE',NULL);
INSERT INTO `Professor` (`idPessoa`, `titulacaoAcademica`, `cvLattes`) VALUE (1013,'MESTRE',NULL);
INSERT INTO `Professor` (`idPessoa`, `titulacaoAcademica`, `cvLattes`) VALUE (1014,'MESTRE',NULL);
INSERT INTO `Professor` (`idPessoa`, `titulacaoAcademica`, `cvLattes`) VALUE (1015,'MESTRE',NULL);
INSERT INTO `Professor` (`idPessoa`, `titulacaoAcademica`, `cvLattes`) VALUE (1016,'DOUTOR',NULL);
INSERT INTO `Professor` (`idPessoa`, `titulacaoAcademica`, `cvLattes`) VALUE (1017,'DOUTOR',NULL);
INSERT INTO `Professor` (`idPessoa`, `titulacaoAcademica`, `cvLattes`) VALUE (1018,'MESTRE',NULL);
INSERT INTO `Professor` (`idPessoa`, `titulacaoAcademica`, `cvLattes`) VALUE (1019,'MESTRE',NULL);
INSERT INTO `Professor` (`idPessoa`, `titulacaoAcademica`, `cvLattes`) VALUE (1020,'MESTRE',NULL);
INSERT INTO `Professor` (`idPessoa`, `titulacaoAcademica`, `cvLattes`) VALUE (1021,'MESTRE',NULL);
INSERT INTO `Professor` (`idPessoa`, `titulacaoAcademica`, `cvLattes`) VALUE (1022,'ESPECIALISTA',NULL);
INSERT INTO `Professor` (`idPessoa`, `titulacaoAcademica`, `cvLattes`) VALUE (1023,'MESTRE',NULL);
INSERT INTO `Professor` (`idPessoa`, `titulacaoAcademica`, `cvLattes`) VALUE (1024,'ESPECIALISTA',NULL);
INSERT INTO `Professor` (`idPessoa`, `titulacaoAcademica`, `cvLattes`) VALUE (1025,'DOUTOR',NULL);
INSERT INTO `Professor` (`idPessoa`, `titulacaoAcademica`, `cvLattes`) VALUE (1026,'ESPECIALISTA',NULL);
INSERT INTO `Professor` (`idPessoa`, `titulacaoAcademica`, `cvLattes`) VALUE (1027,'MESTRE',NULL);
INSERT INTO `Professor` (`idPessoa`, `titulacaoAcademica`, `cvLattes`) VALUE (1028,'MESTRE',NULL);
INSERT INTO `Professor` (`idPessoa`, `titulacaoAcademica`, `cvLattes`) VALUE (1029,'MESTRE',NULL);
COMMIT;

-- -----------------------------------------------------
-- Data for table `MatriculaProfessor`
-- -----------------------------------------------------
SET AUTOCOMMIT=0;
INSERT INTO `MatriculaProfessor` 	(`matriculaProfessor`, 	`idPessoa`, `cargaHoraria`, `dataInicio`, 	`dataEncerramento`)
	VALUE 							('2249258',				1000,		40,				'2005-08-16',	null);
INSERT INTO `MatriculaProfessor` 	(`matriculaProfessor`, 	`idPessoa`, `cargaHoraria`, `dataInicio`, 	`dataEncerramento`)
	VALUE 							('1094184',				1003,		40,				'2006-02-14',	null);
INSERT INTO `MatriculaProfessor` 	(`matriculaProfessor`, 	`idPessoa`, `cargaHoraria`, `dataInicio`, 	`dataEncerramento`)
	VALUE 							('1113299',				1004,		40,				'2007-02-14',	null);
INSERT INTO `MatriculaProfessor` 	(`matriculaProfessor`, 	`idPessoa`, `cargaHoraria`, `dataInicio`, 	`dataEncerramento`)
	VALUE 							('2249225',				1005,		40,				'2005-08-16',	null);
INSERT INTO `MatriculaProfessor` 	(`matriculaProfessor`, 	`idPessoa`, `cargaHoraria`, `dataInicio`, 	`dataEncerramento`)
	VALUE 							('2249233',				1006,		40,				'2005-08-16',	null);
INSERT INTO `MatriculaProfessor` 	(`matriculaProfessor`, 	`idPessoa`, `cargaHoraria`, `dataInicio`, 	`dataEncerramento`)
	VALUE 							('2249266',				1007,		40,				'2005-08-16',	null);
INSERT INTO `MatriculaProfessor` 	(`matriculaProfessor`, 	`idPessoa`, `cargaHoraria`, `dataInicio`, 	`dataEncerramento`)
	VALUE 							('01103043',			1008,		40,				'2006-03-09',	null);
INSERT INTO `MatriculaProfessor` 	(`matriculaProfessor`, 	`idPessoa`, `cargaHoraria`, `dataInicio`, 	`dataEncerramento`)
	VALUE 							('1094168',				1009,		20,				'2006-02-14',	null);
INSERT INTO `MatriculaProfessor` 	(`matriculaProfessor`, 	`idPessoa`, `cargaHoraria`, `dataInicio`, 	`dataEncerramento`)
	VALUE 							('1113281',				1010,		20,				'2007-02-01',	null);
INSERT INTO `MatriculaProfessor` 	(`matriculaProfessor`, 	`idPessoa`, `cargaHoraria`, `dataInicio`, 	`dataEncerramento`)
	VALUE 							('1094192',				1013,		20,				'2007-02-15',	null);
INSERT INTO `MatriculaProfessor` 	(`matriculaProfessor`, 	`idPessoa`, `cargaHoraria`, `dataInicio`, 	`dataEncerramento`)
	VALUE 							('2249282',				1016,		40,				'2005-08-16',	null);
INSERT INTO `MatriculaProfessor` 	(`matriculaProfessor`, 	`idPessoa`, `cargaHoraria`, `dataInicio`, 	`dataEncerramento`)
	VALUE 							('1104298',				1019,		20,				'2005-03-03',	null);
INSERT INTO `MatriculaProfessor` 	(`matriculaProfessor`, 	`idPessoa`, `cargaHoraria`, `dataInicio`, 	`dataEncerramento`)
	VALUE 							('1094200',				1020,		40,				'2003-02-21',	null);
INSERT INTO `MatriculaProfessor` 	(`matriculaProfessor`, 	`idPessoa`, `cargaHoraria`, `dataInicio`, 	`dataEncerramento`)
	VALUE 							('1094201',				1028,		40,				'2007-02-21',	'2008-07-21');
INSERT INTO `MatriculaProfessor` 	(`matriculaProfessor`, 	`idPessoa`, `cargaHoraria`, `dataInicio`, 	`dataEncerramento`)
	VALUE 							('2249241',				1029,			40,				'2005-08-16',	null);
INSERT INTO `MatriculaProfessor` 	(`matriculaProfessor`, 	`idPessoa`, `cargaHoraria`, `dataInicio`, 	`dataEncerramento`)
	VALUE 							('2233831',				1029,			20,				'2002-09-11',	null);
COMMIT;

-- Periodo Letivo
-- ATENÇÃO: 
SET AUTOCOMMIT=0;
INSERT INTO `PeriodoLetivo` (`idPeriodoLetivo`, `siglaCurso`, `siglaPeriodoLetivo`, `dataInicio`, `dataFim`) VALUES
( 7, 'TASI', '2002.1', '2002-02-01', '2002-07-01'),
( 8, 'TASI', '2002.2', '2002-07-02', '2002-12-22'),
( 9, 'TASI', '2003.1', '2003-02-01', '2003-07-01'),
(10, 'TASI', '2003.2', '2003-07-02', '2003-12-22'),
(11, 'TASI', '2004.1', '2004-02-01', '2004-07-01'),
(12, 'TASI', '2004.2', '2004-07-02', '2004-12-22'), 
(13, 'TASI', '2005.1', '2005-02-01', '2005-07-01'),
(14, 'TASI', '2005.2', '2005-07-02', '2005-12-22'), 
(15, 'TASI', '2006.1', '2006-02-01', '2006-07-01'),
(16, 'TASI', '2006.2', '2006-07-02', '2006-12-22'), 
( 6, 'TASI', '2007.1', '2007-02-01', '2007-07-01'),
( 5, 'TASI', '2007.2', '2007-07-02', '2007-12-22'), 
( 4, 'TASI', '2008.1', '2008-02-01', '2008-07-01'),
( 3, 'TASI', '2008.2', '2008-07-02', '2008-12-22'), 
( 1, 'TASI', '2009.1', '2009-02-01', '2009-07-01'),
(17, 'TASI', '2009.2', '2009-07-02', '2009-12-22'), 
(18, 'TASI', '2010.1', '2010-02-01', '2010-07-01'),
(19, 'TASI', '2010.2', '2010-07-02', '2010-12-31'),
(20, 'TASI', '2011.1', '2011-02-01', '2011-07-15'),
( 2, 'PGTIAE','2009.1','2009-02-01', '2009-07-01'); 
COMMIT;

-- 
-- Tabela TempoSemanal
--
SET AUTOCOMMIT=0;
INSERT INTO `TempoSemanal` (`idTempoSemanal`, `siglaCurso`, `horaInicio`, `horaFim`, `diaSemana`) VALUES
(1, 'TASI', '08:00:00', '08:40:00', 'SEG'),
(2, 'TASI', '08:40:00', '09:20:00', 'SEG'),
(3, 'TASI', '09:20:00', '10:00:00', 'SEG'),
-- (4, 'TASI', '09:40:00', '09:50:00', 'SEG'),
(5, 'TASI', '10:10:00', '10:50:00', 'SEG'),
(6, 'TASI',  '10:50:00','11:30:00', 'SEG'),
(7, 'TASI', '11:30:00','12:10:00', 'SEG'),
(8, 'TASI', '18:00:00', '18:40:00', 'SEG'),
(9, 'TASI', '18:40:00', '19:20:00', 'SEG'),
(10, 'TASI', '19:20:00', '20:00:00', 'SEG'),
(11, 'TASI', '20:00:00', '20:40:00', 'SEG'),
(13, 'TASI', '20:50:00', '21:30:00', 'SEG'),
(14, 'TASI', '21:30:00', '22:10:00', 'SEG'),
(15, 'TASI', '08:00:00', '08:40:00', 'TER'),
(16, 'TASI', '08:40:00', '09:20:00', 'TER'),
(17, 'TASI', '09:20:00', '10:00:00', 'TER'),
-- (18, 'TASI', '09:40:00', '09:50:00', 'TER'),
(19, 'TASI', '10:10:00', '10:50:00', 'TER'),
(20, 'TASI',  '10:50:00','11:30:00', 'TER'),
(21, 'TASI', '11:30:00','12:10:00', 'TER'),
(22, 'TASI', '18:00:00', '18:40:00', 'TER'),
(23, 'TASI', '18:40:00', '19:20:00', 'TER'),
(24, 'TASI', '19:20:00', '20:00:00', 'TER'),
(25, 'TASI', '20:00:00', '20:40:00', 'TER'),
(27, 'TASI', '20:50:00', '21:30:00', 'TER'),
(28, 'TASI', '21:30:00', '22:10:00', 'TER'),
(29, 'TASI', '08:00:00', '08:40:00', 'QUA'),
(30, 'TASI', '08:40:00', '09:20:00', 'QUA'),
(31, 'TASI', '09:20:00', '10:00:00', 'QUA'),
-- (32, 'TASI', '09:40:00', '09:50:00', 'QUA'),
(33, 'TASI', '10:10:00', '10:50:00', 'QUA'),
(34, 'TASI',  '10:50:00','11:30:00', 'QUA'),
(35, 'TASI', '11:30:00','12:10:00', 'QUA'),
(36, 'TASI', '18:00:00', '18:40:00', 'QUA'),
(37, 'TASI', '18:40:00', '19:20:00', 'QUA'),
(38, 'TASI', '19:20:00', '20:00:00', 'QUA'),
(39, 'TASI', '20:00:00', '20:40:00', 'QUA'),
(41, 'TASI', '20:50:00', '21:30:00', 'QUA'),
(42, 'TASI', '21:30:00', '22:10:00', 'QUA'),
(43, 'TASI', '08:00:00', '08:40:00', 'QUI'),
(44, 'TASI', '08:40:00', '09:20:00', 'QUI'),
(45, 'TASI', '09:20:00', '10:00:00', 'QUI'),
-- (46, 'TASI', '09:40:00', '09:50:00', 'QUI'),
(47, 'TASI', '10:10:00', '10:50:00', 'QUI'),
(48, 'TASI',  '10:50:00','11:30:00', 'QUI'),
(49, 'TASI', '11:30:00','12:10:00', 'QUI'),
(50, 'TASI', '18:00:00', '18:40:00', 'QUI'),
(51, 'TASI', '18:40:00', '19:20:00', 'QUI'),
(52, 'TASI', '19:20:00', '20:00:00', 'QUI'),
(53, 'TASI', '20:00:00', '20:40:00', 'QUI'),
(55, 'TASI', '20:50:00', '21:30:00', 'QUI'),
(56, 'TASI', '21:30:00', '22:10:00', 'QUI'),
(57, 'TASI', '08:00:00', '08:40:00', 'SEX'),
(58, 'TASI', '08:40:00', '09:20:00', 'SEX'),
(59, 'TASI', '09:20:00', '10:00:00', 'SEX'),
-- (60, 'TASI', '09:40:00', '09:50:00', 'SEX'),
(61, 'TASI', '10:10:00', '10:50:00', 'SEX'),
(62, 'TASI',  '10:50:00','11:30:00', 'SEX'),
(63, 'TASI', '11:30:00','12:10:00', 'SEX'),
(64, 'TASI', '18:00:00', '18:40:00', 'SEX'),
(65, 'TASI', '18:40:00', '19:20:00', 'SEX'),
(66, 'TASI', '19:20:00', '20:00:00', 'SEX'),
(67, 'TASI', '20:00:00', '20:40:00', 'SEX'),
(69, 'TASI', '20:50:00', '21:30:00', 'SEX'),
(70, 'TASI', '21:30:00', '22:10:00', 'SEX'),
(72, 'TASI', '08:00:00', '08:40:00', 'SAB'),
(73, 'TASI', '08:40:00', '09:20:00', 'SAB'),
(74, 'TASI', '09:20:00', '10:00:00', 'SAB'),
(75, 'TASI', '10:10:00', '10:50:00', 'SAB'),
(76, 'TASI', '10:50:00', '11:30:00', 'SAB'),
(77, 'TASI', '11:30:00', '12:10:00', 'SAB'),
(78, 'TASI', '13:15:00', '14:05:00', 'SEG'),
(79, 'TASI', '14:05:00', '14:55:00', 'SEG'),
(80, 'TASI', '14:55:00', '15:45:00', 'SEG'),
-- (81, 'TASI', '15:45:00', '16:00:00', 'SEG'),
(82, 'TASI', '16:00:00', '16:50:00', 'SEG'),
(83, 'TASI', '16:50:00', '17:40:00', 'SEG'),
(84, 'TASI', '13:15:00', '14:05:00', 'TER'),
(85, 'TASI', '14:05:00', '14:55:00', 'TER'),
(86, 'TASI', '14:55:00', '15:45:00', 'TER'),
-- (87, 'TASI', '15:45:00', '16:00:00', 'TER'),
(88, 'TASI', '16:00:00', '16:50:00', 'TER'),
(89, 'TASI', '16:50:00', '17:40:00', 'TER'),
(90, 'TASI', '13:15:00', '14:05:00', 'QUA'),
(91, 'TASI', '14:05:00', '14:55:00', 'QUA'),
(92, 'TASI', '14:55:00', '15:45:00', 'QUA'),
-- (93, 'TASI', '15:45:00', '16:00:00', 'QUA'),
(94, 'TASI', '16:00:00', '16:50:00', 'QUA'),
(95, 'TASI', '16:50:00', '17:40:00', 'QUA'),
(96, 'TASI', '13:15:00', '14:05:00', 'QUI'),
(97, 'TASI', '14:05:00', '14:55:00', 'QUI'),
(98, 'TASI', '14:55:00', '15:45:00', 'QUI'),
-- (99, 'TASI', '15:45:00', '16:00:00', 'QUI'),
(100, 'TASI', '16:00:00', '16:50:00', 'QUI'),
(101, 'TASI', '16:50:00', '17:40:00', 'QUI'),
(102, 'TASI', '13:15:00', '14:05:00', 'SEX'),
(103, 'TASI', '14:05:00', '14:55:00', 'SEX'),
(104, 'TASI', '14:55:00', '15:45:00', 'SEX'),
-- (105, 'TASI', '15:45:00', '16:00:00', 'SEX'),
(106, 'TASI', '16:00:00', '16:50:00', 'SEX'),
(107, 'TASI', '16:50:00', '17:40:00', 'SEX');
COMMIT;

SET AUTOCOMMIT=0;
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
'( AV1 === null && AV2 === null ? null : round( ((AV1 + AV2) / 2) , 1) )' ),
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
'( FALTAS > LIMITE_FALTAS ? "0.0" : ( MÉDIA === null && AVF === null ? "" : ( MÉDIA < 4 || MÉDIA >= 6 ? MÉDIA : ( AVF === null ? null : round( ( MÉDIA + AVF ) / 2 , 1 ) ) ) ) )'),
(6,
1,
'SITUAÇÃO',
'Situação final do aluno',
6,
'CALCULADO',
'(FALTAS > LIMITE_FALTAS ? "RF" : ( AV1 === null || AV2 === null ? "Em avaliação" : ( MÉDIA < 4 ? "RM" : ( MÉDIA >= 6 || FINAL >= 6 ? "AP" : ( AVF === null ? "Em final" : "RM" ) ) ) ) )');
COMMIT;


-- SET SQL_MODE=@OLD_SQL_MODE;
-- SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
-- SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
