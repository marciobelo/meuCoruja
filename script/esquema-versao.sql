SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

DROP DATABASE IF EXISTS `Coruja`;
CREATE SCHEMA IF NOT EXISTS `Coruja` CHARACTER SET = latin1 COLLATE = latin1_swedish_ci;
use `Coruja`;

-- -----------------------------------------------------
-- Table `Pessoa`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `Pessoa` (
  `idPessoa` BIGINT NOT NULL AUTO_INCREMENT ,
  `nome` VARCHAR(80) NOT NULL COMMENT 'Nome da pessoa' ,
  `sexo` ENUM('M','F') NOT NULL ,
  `enderecoLogradouro` VARCHAR(80) NOT NULL COMMENT 'Endereço com o tipo de logradouro (Rua, Avenida, etc.) e o nome' ,
  `enderecoNumero` VARCHAR(10) NOT NULL COMMENT 'Número da casa, apartamento, ou vila no logradouro especificado. Deixar em branco se não for aplicado. Não colocar S/N, etc.' ,
  `enderecoComplemento` VARCHAR(45) NOT NULL COMMENT 'Complemento do logradouro, para casos como apartamento (no.apto) ou vila (no.casa). Deixar em vazio se não for aplicado.' ,
  `enderecoBairro` VARCHAR(60) NOT NULL ,
  `enderecoMunicipio` VARCHAR(60) NOT NULL ,
  `enderecoEstado` VARCHAR(2) NOT NULL ,
  `enderecoCEP` VARCHAR(10) NOT NULL ,
  `dataNascimento` DATE NOT NULL ,
  `nacionalidade` VARCHAR(45) NOT NULL ,
  `naturalidade` VARCHAR(45) NOT NULL ,
  `telefoneResidencial` VARCHAR(15) NOT NULL COMMENT 'Precedido do código da localidade' ,
  `telefoneComercial` VARCHAR(15) NULL COMMENT 'Precedido do código da localidade' ,
  `telefoneCelular` VARCHAR(15) NULL COMMENT 'Precedido do código da localidade' ,
  `email` VARCHAR(80) NULL ,
  PRIMARY KEY (`idPessoa`) ,
  INDEX `nome` (`nome` ASC) )
ENGINE = InnoDB
COMMENT = 'Pessoa participante da comunidade IST-Rio';


-- -----------------------------------------------------
-- Table `FormaIngresso`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `FormaIngresso` (
  `idFormaIngresso` TINYINT UNSIGNED NOT NULL ,
  `descricao` VARCHAR(45) NOT NULL COMMENT 'Descrição da Forma de Ingresso. Exemplos: Concurso Cota Regular, Concurso Cota Cor Negra, Concurso Cota Deficiente, Transferência, etc.' ,
  PRIMARY KEY (`idFormaIngresso`) )
ENGINE = InnoDB
COMMENT = 'Formas de se obter direito de matrícula na instituição';


-- -----------------------------------------------------
-- Table `Aluno`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `Aluno` (
  `idPessoa` BIGINT NOT NULL ,
  `nomeMae` VARCHAR(80) NOT NULL ,
  `rgMae` VARCHAR(20) NULL ,
  `nomePai` VARCHAR(80) NULL COMMENT 'Nome do pai pode ser nulo' ,
  `rgPai` VARCHAR(20) NULL ,
  `rgNumero` VARCHAR(20) NOT NULL COMMENT 'Número do documento de Identidade' ,
  `rgDataEmissao` DATE NOT NULL COMMENT 'Data de emissão do documento de Identidade' ,
  `rgOrgaoEmissor` VARCHAR(20) NOT NULL COMMENT 'Orgão Emissor do documento de Identidade. Ex: IFP/RJ, DETRAN, EXÉRCITO, etc.' ,
  `cpf` VARCHAR(12) NOT NULL COMMENT 'Cadastro de Pessoa Física, emitido pela Receita Federal. Deve estar com o dígito verificador válido.' ,
  `cpfProprio` ENUM('SIM','NÃO') NOT NULL ,
  `certidaoNascimentoNumero` VARCHAR(20) NULL ,
  `certidaoNascimentoLivro` VARCHAR(10) NULL ,
  `certidaoNascimentoFolha` VARCHAR(10) NULL ,
  `certidaoNascimentoCidade` VARCHAR(40) NULL ,
  `certidaoNascimentoSubdistrito` VARCHAR(40) NULL ,
  `certidaoNascimentoUF` CHAR(2) NULL ,
  `certidaoCasamentoNumero` VARCHAR(20) NULL ,
  `certidaoCasamentoLivro` VARCHAR(10) NULL ,
  `certidaoCasamentoFolha` VARCHAR(10) NULL ,
  `certidaoCasamentoCidade` VARCHAR(40) NULL ,
  `certidaoCasamentoSubdistrito` VARCHAR(40) NULL ,
  `certidaoCasamentoUF` CHAR(2) NULL ,
  `estabCursoOrigem` VARCHAR(80) NOT NULL ,
  `estabCursoOrigemCidade` varchar(40) NOT NULL default 'RIO DE JANEIRO',
  `estabCursoOrigemUF` CHAR(2) NOT NULL ,
  `cursoOrigemAnoConclusao` SMALLINT UNSIGNED NOT NULL ,
  `modalidadeCursoOrigem` ENUM('ENSINO MÉDIO','SUPLETIVO','GRADUAÇÃO','OUTROS') NOT NULL ,
  `ctps` VARCHAR(30) NULL COMMENT 'Número da Carteira de Trabalho e Previdência Social' ,
  `corRaca` ENUM('AMARELA','BRANCA','INDÍGENA','PARDA','NEGRA') NOT NULL ,
  `estadoCivil` ENUM('SOLTEIRO','CASADO','OUTROS') NULL ,
  `deficienciaVisual` ENUM('SIM','NÃO') NULL ,
  `deficienciaMotora` ENUM('SIM','NÃO') NULL ,
  `deficienciaAuditiva` ENUM('SIM','NÃO') NULL ,
  `deficienciaMental` ENUM('SIM','NÃO') NULL ,
  `responsavelLegal` VARCHAR(80) NULL ,
  `rgResponsavel` VARCHAR(20) NULL ,
  `tituloEleitorNumero` VARCHAR(20) NULL ,
  `tituloEleitorData` DATE NULL ,
  `tituloEleitorZona` VARCHAR(10) NULL ,
  `tituloEleitorSecao` VARCHAR(10) NULL ,
  `certificadoAlistamentoMilitarNumero` VARCHAR(20) NULL ,
  `certificadoAlistamentoMilitarSerie` VARCHAR(10) NULL ,
  `certificadoAlistamentoMilitarData` DATE NULL ,
  `certificadoAlistamentoMilitarRM` VARCHAR(10) NULL ,
  `certificadoAlistamentoMilitarCSM` VARCHAR(10) NULL ,
  `certificadoReservistaNumero` VARCHAR(20) NULL COMMENT 'Certificado de Reservista, para alunos do sexo masculino.' ,
  `certificadoReservistaSerie` VARCHAR(10) NULL ,
  `certificadoReservistaData` DATE NULL ,
  `certificadoReservistaCAT` VARCHAR(10) NULL ,
  `certificadoReservistaRM` VARCHAR(10) NULL ,
  `certificadoReservistaCSM` VARCHAR(10) NULL ,
  PRIMARY KEY (`idPessoa`) ,
  INDEX `fk_Aluno_Pessoa` (`idPessoa` ASC) ,
  CONSTRAINT `fk_Aluno_Pessoa`
    FOREIGN KEY (`idPessoa` )
    REFERENCES `Pessoa` (`idPessoa` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `TipoCurso`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `TipoCurso` (
  `idTipoCurso` INT NOT NULL ,
  `descricao` VARCHAR(45) NOT NULL ,
  UNIQUE INDEX `uk_tipo_curso_descricao` (`descricao` ASC) ,
  PRIMARY KEY (`idTipoCurso`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Curso`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `Curso` (
  `siglaCurso` CHAR(6) NOT NULL ,
  `nomeCurso` VARCHAR(60) NOT NULL ,
  `idTipoCurso` INT NOT NULL ,
  `tempoMaximoIntegralizacaoEmMeses` TINYINT UNSIGNED NOT NULL default 60 comment 'Quantidade máxima de meses regulamentar para o aluno concluir o curso',
  UNIQUE KEY `uk_curso_nomeCurso` (`nomeCurso` ASC),
  PRIMARY KEY (`siglaCurso`) ,
  INDEX `fk_Curso_TipoCurso` (`idTipoCurso` ASC) ,
  CONSTRAINT `fk_Curso_TipoCurso`
    FOREIGN KEY (`idTipoCurso` )
    REFERENCES `TipoCurso` (`idTipoCurso` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `MatrizCurricular`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `MatrizCurricular` (
  `siglaCurso` CHAR(6) NOT NULL ,
  `idMatriz` INT UNSIGNED NOT NULL ,
  `dataInicioVigencia` DATE NOT NULL ,
  PRIMARY KEY (`idMatriz`, `siglaCurso`) ,
  INDEX `fk_Matriz_Curso` (`siglaCurso` ASC) ,
  CONSTRAINT `fk_Matriz_Curso`
    FOREIGN KEY (`siglaCurso` )
    REFERENCES `Curso` (`siglaCurso` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `PeriodoLetivo`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `PeriodoLetivo` (
  `idPeriodoLetivo` INT UNSIGNED NOT NULL ,
  `siglaCurso` CHAR(6) NOT NULL ,
  `siglaPeriodoLetivo` VARCHAR(10) NOT NULL ,
  `dataInicio` DATE NOT NULL ,
  `dataFim` DATE NOT NULL ,
  `rodouBloqueioAutomatico` ENUM('SIM','NÃO') NOT NULL default 'NÃO' comment 'Indica se o procedimento de bloqueio automático foi executado nesse período letivo',
  PRIMARY KEY (`idPeriodoLetivo`) ,
  INDEX `fk_PeriodoLetivo_Curso` (`siglaCurso` ASC) ,
  UNIQUE INDEX `uk_sigla` (`siglaCurso` ASC, `siglaPeriodoLetivo` ASC) ,
  CONSTRAINT `fk_PeriodoLetivo_Curso`
    FOREIGN KEY (`siglaCurso` )
    REFERENCES `Curso` (`siglaCurso` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `MatriculaAluno`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `MatriculaAluno` (
  `matriculaAluno` VARCHAR(15) NOT NULL ,
  `idPessoa` BIGINT NOT NULL ,
  `dataMatricula` DATE NOT NULL ,
  `siglaCurso` CHAR(6) NULL ,
  `idMatriz` INT UNSIGNED NULL ,
  `idPeriodoLetivo` INT UNSIGNED NOT NULL ,
  `turnoIngresso` ENUM('MANHÃ','TARDE','NOITE') NOT NULL ,
  `concursoPontos` DECIMAL(12,2) UNSIGNED NULL COMMENT 'Pontos que o aluno obteve na prova do concurso para ingresso na instituição' ,
  `concursoClassificacao` SMALLINT UNSIGNED NULL COMMENT 'Classificação em que o aluno ficou no concurso para ingresso na instituição' ,
  `dataConclusao` DATE NULL COMMENT 'Data em que o aluno integralizou o curso',
  `situacaoMatricula` ENUM('CURSANDO','TRANCADO','EVADIDO','CONCLUÍDO','DESISTENTE','DESLIGADO') NOT NULL COMMENT 'Situação atual da matrícula do aluno na instituição. Ex: CURSANDO, TRANCADO, EVADIDO,CONCLUÍDO' ,
  `idFormaIngresso` TINYINT UNSIGNED NULL COMMENT 'Data em que o aluno realizou a matricula na instituição' ,
  PRIMARY KEY (`matriculaAluno`) ,
  INDEX `fk_Aluno_FormaIngresso` (`idFormaIngresso` ASC) ,
  INDEX `ind_matricula` (`matriculaAluno` ASC) ,
  INDEX `fk_MatriculaAluno_Aluno` (`idPessoa` ASC) ,
  INDEX `fk_MatriculaAluno_MatrizCurricular` (`idMatriz` ASC, `siglaCurso` ASC) ,
  INDEX `fk_MatriculaAluno_PeriodoLetivo` (`idPeriodoLetivo` ASC) ,
  CONSTRAINT `fk_Aluno_FormaIngresso`
    FOREIGN KEY (`idFormaIngresso` )
    REFERENCES `FormaIngresso` (`idFormaIngresso` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_MatriculaAluno_Aluno`
    FOREIGN KEY (`idPessoa` )
    REFERENCES `Aluno` (`idPessoa` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_MatriculaAluno_MatrizCurricular`
    FOREIGN KEY (`idMatriz` , `siglaCurso` )
    REFERENCES `MatrizCurricular` (`idMatriz` , `siglaCurso` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_MatriculaAluno_PeriodoLetivo`
    FOREIGN KEY (`idPeriodoLetivo` )
    REFERENCES `PeriodoLetivo` (`idPeriodoLetivo` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `SituacaoMatriculaHistorico`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `SituacaoMatriculaHistorico` (
  `matriculaAluno` VARCHAR(15) NOT NULL ,
  `dataHistorico` TIMESTAMP NOT NULL DEFAULT NOW() ,
  `situacaoMatricula` ENUM('CURSANDO','TRANCADO','EVADIDO','CONCLUÍDO','DESISTENTE','DESLIGADO') NOT NULL COMMENT 'Situação da Matrícula do Aluno após o lançamento do item de histórico' ,
  `texto` TEXT NULL COMMENT 'Texto complementar do lançamento' ,
  PRIMARY KEY (`dataHistorico`, `matriculaAluno`) ,
  INDEX `fk_SituacaoMatriculaHistorico_MatriculaAluno` (`matriculaAluno` ASC) ,
  CONSTRAINT `fk_SituacaoMatriculaHistorico_MatriculaAluno`
    FOREIGN KEY (`matriculaAluno` )
    REFERENCES `MatriculaAluno` (`matriculaAluno` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ComponenteCurricular`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ComponenteCurricular` (
  `siglaCurso` CHAR(6) NOT NULL ,
  `idMatriz` INT UNSIGNED NOT NULL ,
  `siglaDisciplina` CHAR(6) NOT NULL ,
  `nomeDisciplina` VARCHAR(80) NOT NULL ,
  `creditos` INT UNSIGNED NOT NULL COMMENT 'Quantidade de horas/aula por semana. A disciplina terá uma carga horária no semestre PREVISTA de 20 vezes o número de créditos.' ,
  `cargaHoraria` INT NOT NULL ,
  `periodo` INT UNSIGNED NOT NULL COMMENT 'Período na qual a disciplina se encontra na matriz curricular' ,
  `tipoComponenteCurricular` ENUM('OBRIGATÓRIA','ELETIVA') NOT NULL ,
  `posicaoPeriodo` INT UNSIGNED DEFAULT 0 NOT NULL COMMENT 'Indica a ordem do componente para fim de exibição na grade da matriz',
  PRIMARY KEY (`siglaDisciplina`, `idMatriz`, `siglaCurso`) ,
  INDEX `fk_Disciplina_Matriz` (`idMatriz` ASC, `siglaCurso` ASC) ,
  CONSTRAINT `fk_Disciplina_Matriz`
    FOREIGN KEY (`idMatriz` , `siglaCurso` )
    REFERENCES `MatrizCurricular` (`idMatriz` , `siglaCurso` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ComponentePreRequisito`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ComponentePreRequisito` (
  `siglaCurso` CHAR(6) NOT NULL ,
  `idMatriz` INT UNSIGNED NOT NULL ,
  `siglaDisciplina` CHAR(6) NOT NULL ,
  `siglaCursoPreRequisito` CHAR(6) NOT NULL ,
  `idMatrizPreRequisito` INT UNSIGNED NOT NULL ,
  `siglaDisciplinaPreRequisito` CHAR(6) NOT NULL ,
  PRIMARY KEY (`siglaDisciplina`, `idMatriz`, `siglaCurso`, `siglaDisciplinaPreRequisito`, `idMatrizPreRequisito`, `siglaCursoPreRequisito`) ,
  INDEX `fk_Disciplina_has_Disciplina_Disciplina` (`siglaDisciplina` ASC, `idMatriz` ASC, `siglaCurso` ASC) ,
  INDEX `fk_Disciplina_has_Disciplina_Disciplina1` (`siglaDisciplinaPreRequisito` ASC, `idMatrizPreRequisito` ASC, `siglaCursoPreRequisito` ASC) ,
  CONSTRAINT `fk_Disciplina_has_Disciplina_Disciplina`
    FOREIGN KEY (`siglaDisciplina` , `idMatriz` , `siglaCurso` )
    REFERENCES `ComponenteCurricular` (`siglaDisciplina` , `idMatriz` , `siglaCurso` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Disciplina_has_Disciplina_Disciplina1`
    FOREIGN KEY (`siglaDisciplinaPreRequisito` , `idMatrizPreRequisito` , `siglaCursoPreRequisito` )
    REFERENCES `ComponenteCurricular` (`siglaDisciplina` , `idMatriz` , `siglaCurso` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Professor`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `Professor` (
  `idPessoa` BIGINT NOT NULL ,
  `titulacaoAcademica` ENUM('ESPECIALISTA','MESTRE','DOUTOR') NOT NULL ,
  `cvLattes` VARCHAR(80) NULL ,
  nomeGuerra varchar(15) not null comment 'Nome abreviado pelo qual o professor é conhecido' default '',
  corFundo char(6) not null comment 'Cor de fundo da celúla da grade de horário' default 'FFFFFF',
  PRIMARY KEY (`idPessoa`) ,
  INDEX `fk_Professor_Pessoa` (`idPessoa` ASC) ,
  CONSTRAINT `fk_Professor_Pessoa`
    FOREIGN KEY (`idPessoa` )
    REFERENCES `Pessoa` (`idPessoa` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `MatriculaProfessor`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `MatriculaProfessor` (
  `matriculaProfessor` VARCHAR(15) NOT NULL ,
  `idPessoa` BIGINT NOT NULL ,
  `cargaHoraria` INT UNSIGNED NOT NULL ,
  `dataInicio` DATE NOT NULL ,
  `dataEncerramento` DATE NULL ,
  PRIMARY KEY (`matriculaProfessor`) ,
  INDEX `fk_MatriculaProfessor_Professor` (`idPessoa` ASC) ,
  CONSTRAINT `fk_MatriculaProfessor_Professor`
    FOREIGN KEY (`idPessoa` )
    REFERENCES `Professor` (`idPessoa` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

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

-- -----------------------------------------------------
-- Table `Turma`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `Turma` (
  `idTurma` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `siglaCurso` CHAR(6) NOT NULL ,
  `idMatriz` INT UNSIGNED NOT NULL ,
  `siglaDisciplina` CHAR(6) NOT NULL ,
  `gradeHorario` ENUM('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','X','W','Y','Z') NOT NULL DEFAULT 'A' ,
  `idPeriodoLetivo` INT UNSIGNED NOT NULL ,
  `matriculaProfessor` VARCHAR(15) NULL ,
  `turno` ENUM('MANHÃ','TARDE','NOITE') NOT NULL ,
  `tipoSituacaoTurma` ENUM('PLANEJADA','LIBERADA','CANCELADA','CONFIRMADA','FINALIZADA') NOT NULL ,
  `qtdeTotal` SMALLINT UNSIGNED NOT NULL comment 'Quantidade máxima de ofertas de vagas nessa turma',
  `dataLiberacaoPautaPeloProfessor` TIMESTAMP NULL comment 'Data em que o professor indicou que a pauta está liberada para a secretaria',
  `idCriterioAvaliacao` BIGINT NOT NULL default 1 comment 'Critério de avaliação usado nessa turma',
  PRIMARY KEY (`idTurma`) ,
  INDEX `fk_Turma_ComponenteCurricular` (`siglaDisciplina` ASC, `idMatriz` ASC, `siglaCurso` ASC) ,
  INDEX `fk_Turma_MatriculaProfessor` (`matriculaProfessor` ASC) ,
  INDEX `fk_Turma_PeriodoLetivo` (`idPeriodoLetivo` ASC) ,
  CONSTRAINT `fk_Turma_ComponenteCurricular`
    FOREIGN KEY (`siglaDisciplina` , `idMatriz` , `siglaCurso` )
    REFERENCES `ComponenteCurricular` (`siglaDisciplina` , `idMatriz` , `siglaCurso` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Turma_MatriculaProfessor`
    FOREIGN KEY (`matriculaProfessor` )
    REFERENCES `MatriculaProfessor` (`matriculaProfessor` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Turma_PeriodoLetivo`
    FOREIGN KEY (`idPeriodoLetivo` )
    REFERENCES `PeriodoLetivo` (`idPeriodoLetivo` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Turma_CriterioAvaliacao`
    FOREIGN KEY (`idCriterioAvaliacao` )
    REFERENCES `CriterioAvaliacao` (`idCriterioAvaliacao` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Inscricao`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `Inscricao` (
  `idTurma` INT UNSIGNED NOT NULL ,
  `matriculaAluno` VARCHAR(15) NOT NULL ,
  `situacaoInscricao` ENUM('REQ','DEF','NEG','CUR','EXC','AP','RF','RM','ID') NOT NULL,
  `dataInscricao` TIMESTAMP NOT NULL DEFAULT NOW() ,
  `mediaFinal` DECIMAL(5,1) NULL ,
  `totalFaltas` SMALLINT UNSIGNED NULL ,
  `parecerInscricao` TEXT NULL ,
  PRIMARY KEY (`matriculaAluno`, `idTurma`) ,
  INDEX `fk_Inscricao_MatriculaAluno` (`matriculaAluno` ASC) ,
  INDEX `fk_Inscricao_Turma` (`idTurma` ASC) ,
  CONSTRAINT `fk_Inscricao_MatriculaAluno`
    FOREIGN KEY (`matriculaAluno` )
    REFERENCES `MatriculaAluno` (`matriculaAluno` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Inscricao_Turma`
    FOREIGN KEY (`idTurma` )
    REFERENCES `Turma` (`idTurma` )
    ON DELETE RESTRICT
    ON UPDATE RESTRICT)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Login`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `Login` (
  `idPessoa` BIGINT NOT NULL ,
  `nomeAcesso` VARCHAR(20) NOT NULL ,
  `senha` VARCHAR(32) NOT NULL ,
  `bloqueado` ENUM('SIM','NÃO') NOT NULL DEFAULT 'NÃO' ,
  `foto` LONGBLOB NULL ,
  `motivoBloqueio` VARCHAR(255) NULL comment 'Motivo pelo qual o Login foi bloqueado',
  `tentativas` INT UNSIGNED DEFAULT 0 NOT NULL comment 'Quantidade de vezes que se tentou logar com este login e falhou',
  PRIMARY KEY (`idPessoa`) ,
  INDEX `fk_Login_Pessoa` (`idPessoa` ASC) ,
  UNIQUE INDEX `ind_Login_Nome_Acesso` (`nomeAcesso` ASC) ,
  CONSTRAINT `fk_Login_Pessoa`
    FOREIGN KEY (`idPessoa` )
    REFERENCES `Pessoa` (`idPessoa` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Funcao`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `Funcao` (
  `idCasoUso` CHAR(16) NOT NULL ,
  `descricao` VARCHAR(255) NOT NULL ,
  `critico` ENUM('SIM','NÃO') NOT NULL ,
  PRIMARY KEY (`idCasoUso`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Log`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `Log` (
  `idPessoa` BIGINT NOT NULL ,
  `idCasoUso` CHAR(16) NOT NULL ,
  `dataHora` TIMESTAMP NOT NULL DEFAULT NOW() ,
  `descricao` TEXT NOT NULL ,
  `confere` ENUM('SIM','NÃO') NOT NULL DEFAULT 'NÃO' ,
  PRIMARY KEY (`idPessoa`, `idCasoUso`, `dataHora`) ,
  INDEX `fk_Log_Pessoa` (`idPessoa` ASC) ,
  INDEX `fk_Log_Funcao` (`idCasoUso` ASC) ,
  CONSTRAINT `fk_Log_Pessoa`
    FOREIGN KEY (`idPessoa` )
    REFERENCES `Pessoa` (`idPessoa` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Log_Funcao`
    FOREIGN KEY (`idCasoUso` )
    REFERENCES `Funcao` (`idCasoUso` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ComponenteEquivalente`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ComponenteEquivalente` (
  `siglaCurso` CHAR(6) NOT NULL ,
  `idMatriz` INT UNSIGNED NOT NULL ,
  `siglaDisciplina` CHAR(6) NOT NULL ,
  `siglaCursoEquivalente` CHAR(6) NOT NULL ,
  `idMatrizEquivalente` INT UNSIGNED NOT NULL ,
  `siglaDisciplinaEquivalente` CHAR(6) NOT NULL ,
  PRIMARY KEY (`siglaDisciplina`, `idMatriz`, `siglaCurso`, `siglaDisciplinaEquivalente`, `idMatrizEquivalente`, `siglaCursoEquivalente`) ,
  INDEX `fk_ComponenteEquivalencia_ComponenteCurricular` (`siglaDisciplina` ASC, `idMatriz` ASC, `siglaCurso` ASC) ,
  INDEX `fk_ComponenteEquivalencia_ComponenteCurricular1` (`siglaDisciplinaEquivalente` ASC, `idMatrizEquivalente` ASC, `siglaCursoEquivalente` ASC) ,
  CONSTRAINT `fk_ComponenteEquivalencia_ComponenteCurricular`
    FOREIGN KEY (`siglaDisciplina` , `idMatriz` , `siglaCurso` )
    REFERENCES `ComponenteCurricular` (`siglaDisciplina` , `idMatriz` , `siglaCurso` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ComponenteEquivalencia_ComponenteCurricular1`
    FOREIGN KEY (`siglaDisciplinaEquivalente` , `idMatrizEquivalente` , `siglaCursoEquivalente` )
    REFERENCES `ComponenteCurricular` (`siglaDisciplina` , `idMatriz` , `siglaCurso` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `TipoDocumento`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `TipoDocumento` (
  `idTipoDocumento` INT NOT NULL ,
  `descricao` VARCHAR(60) NOT NULL ,
  PRIMARY KEY (`idTipoDocumento`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `CursoTipoDocumento`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `CursoTipoDocumento` (
  `siglaCurso` CHAR(6) NOT NULL ,
  `idTipoDocumento` INT NOT NULL ,
  PRIMARY KEY (`siglaCurso`, `idTipoDocumento`) ,
  INDEX `fk_CursoTipoDocumento_Curso` (`siglaCurso` ASC) ,
  INDEX `fk_CursoTipoDocumento_TipoDocumento` (`idTipoDocumento` ASC) ,
  CONSTRAINT `fk_CursoTipoDocumento_Curso`
    FOREIGN KEY (`siglaCurso` )
    REFERENCES `Curso` (`siglaCurso` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_CursoTipoDocumento_TipoDocumento`
    FOREIGN KEY (`idTipoDocumento` )
    REFERENCES `TipoDocumento` (`idTipoDocumento` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ExigenciaDocumento`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ExigenciaDocumento` (
  `matriculaAluno` VARCHAR(15) NOT NULL ,
  `siglaCurso` CHAR(6) NOT NULL ,
  `idTipoDocumento` INT NOT NULL ,
  `isento` ENUM('SIM','NÃO') NOT NULL ,
  `dataEntrega` DATE NOT NULL ,
  PRIMARY KEY (`siglaCurso`, `idTipoDocumento`, `matriculaAluno`) ,
  INDEX `fk_TipoDocumentoAluno_CursoTipoDocumento` (`siglaCurso` ASC, `idTipoDocumento` ASC) ,
  INDEX `fk_TipoDocumentoAluno_MatriculaAluno` (`matriculaAluno` ASC) ,
  CONSTRAINT `fk_TipoDocumentoAluno_CursoTipoDocumento`
    FOREIGN KEY (`siglaCurso` , `idTipoDocumento` )
    REFERENCES `CursoTipoDocumento` (`siglaCurso` , `idTipoDocumento` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_TipoDocumentoAluno_MatriculaAluno`
    FOREIGN KEY (`matriculaAluno` )
    REFERENCES `MatriculaAluno` (`matriculaAluno` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Espaco`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `Espaco` (
  `idEspaco` SMALLINT UNSIGNED NOT NULL ,
  `nome` VARCHAR(45) NOT NULL ,
  `capacidade` SMALLINT UNSIGNED NOT NULL ,
  UNIQUE KEY `uk_espaco_nome` (`nome` ASC),
  PRIMARY KEY (`idEspaco`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `TempoSemanal`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `TempoSemanal` (
  `idTempoSemanal` INT UNSIGNED NOT NULL ,
  `siglaCurso` CHAR(6) NOT NULL ,
  `horaInicio` TIME NOT NULL ,
  `horaFim` TIME NOT NULL ,
  `diaSemana` ENUM('SEG','TER','QUA','QUI','SEX','SAB') NOT NULL ,
  INDEX `fk_TempoSemanal_Curso` (`siglaCurso` ASC) ,
  PRIMARY KEY (`idTempoSemanal`) ,
  CONSTRAINT `fk_TempoSemanal_Curso`
    FOREIGN KEY (`siglaCurso` )
    REFERENCES `Curso` (`siglaCurso` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Aloca`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `Aloca` (
  `idTurma` INT UNSIGNED NOT NULL ,
  `idTempoSemanal` INT UNSIGNED NOT NULL ,
  `idEspaco` SMALLINT UNSIGNED NULL ,
  PRIMARY KEY (`idTurma`, `idTempoSemanal`) ,
  INDEX `fk_Aloca_Turma` (`idTurma` ASC) ,
  INDEX `fk_Aloca_TempoSemanal` (`idTempoSemanal` ASC) ,
  INDEX `fk_Aloca_Sala` (`idEspaco` ASC) ,
  CONSTRAINT `fk_Aloca_Turma`
    FOREIGN KEY (`idTurma` )
    REFERENCES `Turma` (`idTurma` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Aloca_TempoSemanal`
    FOREIGN KEY (`idTempoSemanal` )
    REFERENCES `TempoSemanal` (`idTempoSemanal` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Aloca_Sala`
    FOREIGN KEY (`idEspaco` )
    REFERENCES `Espaco` (`idEspaco` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `Permite`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `Permite` (
  `idPessoa` BIGINT NOT NULL ,
  `idCasoUso` CHAR(16) NOT NULL ,
  PRIMARY KEY (`idPessoa`, `idCasoUso`) ,
  INDEX `fk_Permite_Login` (`idPessoa` ASC) ,
  INDEX `fk_Permite_Funcao` (`idCasoUso` ASC) ,
  CONSTRAINT `fk_Permite_Login`
    FOREIGN KEY (`idPessoa` )
    REFERENCES `Login` (`idPessoa` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Permite_Funcao`
    FOREIGN KEY (`idCasoUso` )
    REFERENCES `Funcao` (`idCasoUso` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `EventoPeriodoLetivo`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `EventoPeriodoLetivo` (
  `seqEvento` SMALLINT UNSIGNED NOT NULL ,
  `idPeriodoLetivo` INT UNSIGNED NOT NULL ,
  `data` DATETIME NOT NULL ,
  `tipoEvento` ENUM('FIM_SOLIC_INSCR_TURMA','FIM_INCL_EXCL_DISCIPLINA','FIM_TRANC_MATRICULA') NULL ,
  `descricao` VARCHAR(80) NULL ,
  PRIMARY KEY (`seqEvento`) ,
  INDEX `fk_Evento_PeriodoLetivo` (`idPeriodoLetivo` ASC) ,
  CONSTRAINT `fk_Evento_PeriodoLetivo`
    FOREIGN KEY (`idPeriodoLetivo` )
    REFERENCES `PeriodoLetivo` (`idPeriodoLetivo` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

create table `DiaLetivoTurma` (
	`idTurma` INT UNSIGNED NOT NULL,
	`data` DATE NOT NULL,
	`dataLiberacao` DATETIME NULL,
	`conteudo` TEXT NULL,
	`anotacaoProfessor` TEXT NULL,
	primary key (`idTurma`,`data`),
	constraint `fk_DiaLetivoTurma_Turma`
		foreign key (`idTurma`)
		references `Turma` (`idTurma`)
		on delete no action
		on update no action
) ENGINE = InnoDB;

create table `TempoDiaLetivo` (
	`idTurma` INT UNSIGNED NOT NULL,
	`data` DATE NOT NULL,
	`idTempoSemanal` INT UNSIGNED NOT NULL,
	primary key (`idTurma`,`data`, `idTempoSemanal`),
	constraint `fk_TempoDiaLetivo_DiaLetivoTurma`
		foreign key (`idTurma`,`data`)
		references `DiaLetivoTurma` (`idTurma`,`data`)
	on delete cascade
	on update cascade,
	constraint `fk_TempoDiaLetivo_TempoSemanal`
		foreign key (`idTempoSemanal`)
		references `TempoSemanal` (`idTempoSemanal`)
		on delete cascade
		on update cascade
) ENGINE = InnoDB;

create table `ApontaTempoAula` (
	`idTurma` INT UNSIGNED NOT NULL,
	`matriculaAluno` VARCHAR(15) NOT NULL ,
	`data` DATE NOT NULL,
	`idTempoSemanal` INT UNSIGNED NOT NULL ,
	`situacao` ENUM('P','F') NULL,
	primary key (`idTurma`,`matriculaAluno`,`data`,`idTempoSemanal`),
	constraint `fk_ApontaTempoAula_Inscricao`
		foreign key (`idTurma`,`matriculaAluno`)
		references `Inscricao` (`idTurma`,`matriculaAluno`)
		on delete cascade
		on update cascade,
	constraint `fk_ApontaTempoAula_TempoDiaLetivo`
		foreign key (`idTurma`,`data`, `idTempoSemanal`)
		references `TempoDiaLetivo` (`idTurma`,`data`, `idTempoSemanal`)
		on delete cascade
		on update cascade
) ENGINE = InnoDB;

create table `Mensagem` (
	`idMensagem` BIGINT NOT NULL AUTO_INCREMENT,
	`assunto` VARCHAR(255) NOT NULL comment 'Assunto da mensagem',
	`texto` TEXT NOT NULL comment 'Corpo da mensagem',
	`dataMensagem` TIMESTAMP NOT NULL default NOW() comment 'Data/hora em que a mensagem foi registrada',
	index `ind_mensagem_data` (`dataMensagem` DESC),
	primary key (`idMensagem`)
) ENGINE = InnoDB;

create table `MensagemPessoa` (
	`idMensagem` BIGINT NOT NULL,
    `idPessoa` BIGINT NOT NULL,
	`lido` ENUM('SIM','NÃO') NOT NULL default 'NÃO' comment 'Indica se foi lido na área de mensagens do usuário',
	`tentouEmail` ENUM('SIM','NÃO') NOT NULL default 'NÃO' comment 'Indica se foi feita tentativa de envio de e-mail',
	`dataHoraEnvioEmail` TIMESTAMP NULL comment 'Data/hora em que se tentou enviar a mensagem por e-mail',
	primary key (`idMensagem`, `idPessoa`),
	constraint `fk_MensagemPessoa_Mensagem`
		foreign key (`idMensagem`)
		references `Mensagem` (`idMensagem`),
	constraint `fk_MensagemPessoa_Pessoa`
		foreign key (`idPessoa`)
		references `Pessoa` (`idPessoa`)
) ENGINE = InnoDB;

create table `ResumoApontamentoDiaLetivo` (
`idTurma` INT UNSIGNED NOT NULL,
`matriculaAluno` VARCHAR(15) NOT NULL,
`data` DATE NOT NULL,
`resumo` varchar(255) NULL comment 'String onde cada caractere representa um status quanto a presença na aula',
primary key (`idTurma`,`matriculaAluno`,`data`),
constraint `fk_ResumoApontamentoDiaLetivo_Inscricao`
foreign key (`idTurma`,`matriculaAluno`)
references `Inscricao` (`idTurma`,`matriculaAluno`)
on delete cascade
on update cascade
) ENGINE = InnoDB;

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

create table if not exists `LoginErro`
(
`dataHoraRegistro` TIMESTAMP NOT NULL DEFAULT NOW(),
`texto` TEXT not null
);

