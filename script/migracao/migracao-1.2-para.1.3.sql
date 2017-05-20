use `Coruja`;

alter table Inscricao
  modify column `situacaoInscricao` ENUM('REQ','DEF','NEG','CUR','EXC','AP','RF','RM','ID') NOT NULL;

create table `DiaLetivoTurma` (
	`idTurma` INT UNSIGNED NOT NULL,
	`data` DATE NOT NULL,
	`dataLiberacao` DATETIME NULL,
	`conteudo` TEXT NULL,
	`anotacaoProfessor` TEXT NULL,
	primary key (`idTurma`,`data`),
	constraint `fk_DiaLetivoTurma_Turma`
		foreign key (`idTurma`)
		references `Coruja`.`Turma` (`idTurma`)
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
		references `Coruja`.`DiaLetivoTurma` (`idTurma`,`data`)
	on delete cascade
	on update cascade,
	constraint `fk_TempoDiaLetivo_TempoSemanal`
		foreign key (`idTempoSemanal`)
		references `Coruja`.`TempoSemanal` (`idTempoSemanal`)
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
		references `Coruja`.`Inscricao` (`idTurma`,`matriculaAluno`)
		on delete cascade
		on update cascade,
	constraint `fk_ApontaTempoAula_TempoDiaLetivo`
		foreign key (`idTurma`,`data`, `idTempoSemanal`)
		references `Coruja`.`TempoDiaLetivo` (`idTurma`,`data`, `idTempoSemanal`)
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

insert into `Funcao` (idCasoUso, descricao,critico) VALUE ('UC08.00.00', 'Apontar Dia Letivo de Turma', 'NÃO');
insert into `Funcao` (idCasoUso, descricao,critico) VALUE ('UC08.00.01', 'Reabrir Dia Letivo de Turma', 'SIM');
insert into `Funcao` (idCasoUso, descricao,critico) VALUE ('UC08.00.02', 'Alterar Dia Letivo de Turma', 'SIM');
insert into `Funcao` (idCasoUso, descricao,critico) VALUE ('UC08.00.03', 'Reclamar aluno omisso na pauta da turma', 'SIM');

drop trigger Turma_VALIDA_UPDATE;

delimiter |

create trigger Turma_VALIDA_UPDATE before update on Turma for each row
begin
    declare cont INT;
    declare var_id_turma INT;
    declare CUR_INSCR_TURMA_FINALIZADA cursor for select count(*) from Inscricao i
        inner join Turma t on t.idTurma = i.idTurma
        where t.idTurma = var_id_turma and
            i.situacaoInscricao not in ('NEG','AP','RM','RF','ID');
    declare CUR_INSCR_TURMA_CONFIRMADA cursor for select count(*) from Inscricao i
        inner join Turma t on t.idTurma = i.idTurma
        where t.idTurma = var_id_turma and
            i.situacaoInscricao not in ('NEG','CUR','AP','RF','RM','ID');
	select new.idTurma into var_id_turma;

    -- Se campo tipoSituacaoTurma for FINALIZADA, nenhuma inscricao
    -- pode estar diferente de AP,RM,RF, NEG ou ID
    if new.tipoSituacaoTurma='FINALIZADA' then
        open CUR_INSCR_TURMA_FINALIZADA;
        fetch CUR_INSCR_TURMA_FINALIZADA into cont;
        close CUR_INSCR_TURMA_FINALIZADA;
        if cont <> 0 then
            call fail('A turma nao pode ser finalizada devido a pendencias.');
        end if;
    end if;

    -- Se campo tipoSituacaoTurma for CONFIRMADA, nenhuma inscricao
    -- pode estar diferente de NEG, ID, CUR
    if new.tipoSituacaoTurma='CONFIRMADA' and old.tipoSituacaoTurma<>'CONFIRMADA'  then
        open CUR_INSCR_TURMA_CONFIRMADA;
        fetch CUR_INSCR_TURMA_CONFIRMADA into cont;
        close CUR_INSCR_TURMA_CONFIRMADA;
        if cont <> 0 then
            call fail('A turma nao pode ser confirmada devido a pendencias.');
        end if;
    end if;
    
    -- Se campo tipoSituacaoTurma for CONFIRMADA ou FINALIZADA,
    -- o campo matriculaProfessor NÃO pode ser nulo
    if new.tipoSituacaoTurma='CONFIRMADA' or new.tipoSituacaoTurma='FINALIZADA' then
        if new.matriculaProfessor is null then
            call fail('O professor da turma deve ser especificado.');
        end if;
    end if;
    
end
