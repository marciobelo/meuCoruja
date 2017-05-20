use `Coruja`;

CREATE TABLE `Error` (
          `ErrorGID` int(10) unsigned NOT NULL auto_increment,
          `Message` varchar(128) default NULL,
          `Created` timestamp NOT NULL default CURRENT_TIMESTAMP 
          on update CURRENT_TIMESTAMP,                                          
          PRIMARY KEY (`ErrorGID`),                                                                                                   
          UNIQUE KEY `MessageIndex` (`Message`)
) ENGINE=MEMORY DEFAULT CHARSET=latin1 ROW_FORMAT=FIXED
COMMENT='Tabela usada pela rotina fail.';

delimiter |

DROP PROCEDURE IF EXISTS `fail`
|

CREATE PROCEDURE `fail`(_Message VARCHAR(128))
BEGIN
  INSERT INTO Error (Message) VALUES (_Message);
  INSERT INTO Error (Message) VALUES (_Message);
END
|

--
-- Realiza validações de consistência na tabela MatriculaAluno
-- Autor: Prof.Marcio Belo
create trigger MatriculaAluno_VALIDA_INSERT before insert on MatriculaAluno for each row
begin
	-- Campo matriculaAluno só deve aceitar dígitos numéricos
	if new.matriculaAluno is not null then
		if new.matriculaAluno REGEXP '[^0-9]+' then
			call fail('Campo matricula do aluno esta incorreto.');
		end if;
	end if;

end
|
create trigger MatriculaAluno_VALIDA_UPDATE before update on MatriculaAluno for each row
begin
	-- Campo matriculaAluno só deve aceitar dígitos numéricos
	if new.matriculaAluno is not null then
		if new.matriculaAluno REGEXP '[^0-9]+' then
			call fail('Campo matricula do aluno esta incorreto.');
		end if;
	end if;

	-- Campo dataConclusao deve estar preenchido para colocar como CONCLUÍDO
	if new.situacaoMatricula='CONCLUÍDO' then
		if new.dataConclusao is null then
			call fail('Campo Data da Conclusao deve ser preenchido.');
		end if;
	end if;
end
|

--
-- Realiza validações de consistência na tabela Inscricao
-- Autor: Prof.Marcio Belo
create trigger INSCRICAO_VALIDA_INSERT before insert on Inscricao for each row
begin
    declare cont INT;
    declare var_id_turma INT;
	declare var_matricula_aluno VARCHAR(15);
    declare CUR_INSCR_TURMA_PLANEJADA cursor for select count(*) from Turma t 
        where t.idTurma = var_id_turma and t.tipoSituacaoTurma='PLANEJADA';
    declare CUR_MATR_NAO_CURSANDO_E_TRANCADO cursor for select count(*) from MatriculaAluno ma 
        where ma.matriculaAluno = var_matricula_aluno and
		ma.situacaoMatricula<>'CURSANDO' and ma.situacaoMatricula<>'TRANCADO';
	select new.idTurma into var_id_turma;
	select new.matriculaAluno into var_matricula_aluno;
    	
	-- Verifica que se o campo Inscricao.situacaoInscricao estiver com os valores 'AP', 'RM' ou 'RF',
	-- o campo mediaFinal e totalFaltas devem obrigatoriamente estar preenchidos	
	if new.situacaoInscricao in ('AP','RM','RF') and
		(new.mediaFinal is null or new.totalFaltas is null) then
		call fail('Os campos media e total de faltas devem estar preenchidos.');
	end if;

	-- Verifica que se o campo Inscricao.mediaFinal, quando estiver preenchido, tem valor entre 0 e 10
	if new.mediaFinal is not null then
		if not (new.mediaFinal >= 0 and new.mediaFinal <= 10) then
			call fail('O campo media final deve ter valor entre 0 e 10.');
		end if;
	end if;
    
    -- Nenhuma inscrição pode ser feita em turma PLANEJADA
    open CUR_INSCR_TURMA_PLANEJADA;
    fetch CUR_INSCR_TURMA_PLANEJADA into cont;
    close CUR_INSCR_TURMA_PLANEJADA;
    if cont <> 0 then
        call fail('Inscricao nao pode ser feita em turma PLANEJADA.');
    end if;
	
    -- A matricula do aluno não está em situação CURSANDO nem TRANCADO, portanto não pode ser inscrito em turma
    open CUR_MATR_NAO_CURSANDO_E_TRANCADO;
    fetch CUR_MATR_NAO_CURSANDO_E_TRANCADO into cont;
    close CUR_MATR_NAO_CURSANDO_E_TRANCADO;
    if cont > 0 then
        call fail('A matricula desse aluno nao consta como cursando nem com trancado.');
    end if;
    
end
|
create trigger INSCRICAO_VALIDA_UPDATE before update on Inscricao for each row
begin
	-- Verifica que se o campo Inscricao.situacaoInscricao estiver com os valores 'AP', 'RM' ou 'RF',
	-- o campo mediaFinal e totalFaltas devem obrigatoriamente estar preenchidos	
	if new.situacaoInscricao in ('AP','RM','RF') and
		(new.mediaFinal is null or new.totalFaltas is null) then
		call fail('Os campos media e total de faltas devem estar preenchidos.');
	end if;
	
	-- Verifica que se o campo Inscricao.mediaFinal, quando estiver preenchido, tem valor entre 0 e 10
	if new.mediaFinal is not null then
		if not (new.mediaFinal >= 0 and new.mediaFinal <= 10) then
			call fail('O campo media final deve ter valor entre 0 e 10.');
		end if;
	end if;
end
|

--
-- Realiza validações de consistência na tabela ComponentePreRequisito
-- Autor: Prof.Marcio Belo
create trigger ComponentePreRequisito_VALIDA_INSERT before insert on ComponentePreRequisito for each row
begin
	-- Campos siglaCurso e idMatriz devem ser iguais aos campos siglaCursoPreRequisito e idMatrizPreRequisito
	if (new.siglaCurso <> new.siglaCursoPreRequisito) or
		(new.idMatriz <> new.idMatrizPreRequisito) then
		call fail('Pre-requisitos so podem ser estabelecidos entre componentes do mesmo curso/matriz');
	end if;
end
|
create trigger ComponentePreRequisito_VALIDA_UPDATE before update on ComponentePreRequisito for each row
begin
	-- Campos siglaCurso e idMatriz devem ser iguais aos campos siglaCursoPreRequisito e idMatrizPreRequisito
	if (new.siglaCurso <> new.siglaCursoPreRequisito) or
		(new.idMatriz <> new.idMatrizPreRequisito) then
		call fail('Pre-requisitos so podem ser estabelecidos entre componentes do mesmo curso/matriz');
	end if;
end
|

-- --
-- -- Realiza validações de consistência na tabela Turma
-- -- Autor: Prof.Marcio Belo
create trigger Turma_VALIDA_INSERT before insert on Turma for each row
begin
    -- Campo tipoSituacaoTurma deve iniciar como PLANEJADA
    if new.tipoSituacaoTurma<>'PLANEJADA' then
        call fail('A turma recem criada deve ser em situacao PLANEJADA.');
    end if;
end
|

create trigger Turma_VALIDA_UPDATE before update on Turma for each row
begin
    declare cont INT;
    declare var_id_turma INT;
    declare CUR_INSCR_TURMA_FINALIZADA cursor for select count(*) from Inscricao i
        inner join Turma t on t.idTurma = i.idTurma
        where t.idTurma = var_id_turma and
            i.situacaoInscricao not in ('NEG','AP','RM','RF','ID', 'EXC');
    declare CUR_INSCR_TURMA_CONFIRMADA cursor for select count(*) from Inscricao i
        inner join Turma t on t.idTurma = i.idTurma
        where t.idTurma = var_id_turma and
            i.situacaoInscricao not in ('NEG','CUR','AP','RF','RM','ID', 'EXC');
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
|

--
-- Realiza validações de consistência na tabela EventoPeriodoLetivo
-- Autor: Prof.Marcio Belo
create trigger EventoPeriodoLetivo_VALIDA_INSERT before insert on EventoPeriodoLetivo for each row
begin
    declare cont INT;
    declare var_id_periodo_letivo INT;
	declare var_tipo_evento VARCHAR(120);
	declare var_data_evento DATETIME;
    declare CUR_TIPO_EVENTO_UNICO cursor for select count(*) from EventoPeriodoLetivo epl
        where epl.idPeriodoLetivo = var_id_periodo_letivo and
			epl.tipoEvento = var_tipo_evento;
    declare CUR_EVENTO_DENTRO_PERIODO cursor for select count(*) from PeriodoLetivo pl
        where pl.idPeriodoLetivo = var_id_periodo_letivo and
			(var_data_evento < pl.dataInicio or var_data_evento > pl.dataFim);
	select new.idPeriodoLetivo into var_id_periodo_letivo;
	select new.tipoEvento into var_tipo_evento;
	select new.`data` into var_data_evento;
	
	-- Valida que se o campo tipoEvento foi preenchido, existe apenas um para um dado período letivo
	if (new.tipoEvento is not NULL) then
        open CUR_TIPO_EVENTO_UNICO;
        fetch CUR_TIPO_EVENTO_UNICO into cont;
        close CUR_TIPO_EVENTO_UNICO;
        if cont > 0 then
            call fail('So pode haver um marco em um dado periodo letivo.');
        end if;		
	end if;
	
	-- Valida que a data do evento está dentro do período letivo
	open CUR_EVENTO_DENTRO_PERIODO;
	fetch CUR_EVENTO_DENTRO_PERIODO into cont;
	close CUR_EVENTO_DENTRO_PERIODO;
	if cont > 0 then
		call fail('Data do evento deve estar dentro do periodo letivo.');
	end if;		
	
end
|
create trigger EventoPeriodoLetivo_VALIDA_UPDATE before update on EventoPeriodoLetivo for each row
begin
    declare cont INT;
    declare var_seq_evento INT;
	declare var_id_periodo_letivo INT;
	declare var_tipo_evento VARCHAR(120);
	declare var_data_evento DATETIME;
    declare CUR_TIPO_EVENTO_UNICO cursor for select count(*) from  EventoPeriodoLetivo epl
        where epl.idPeriodoLetivo = var_id_periodo_letivo and
			epl.tipoEvento = var_tipo_evento and 
			epl.seqEvento <> var_seq_evento;
    declare CUR_EVENTO_DENTRO_PERIODO cursor for select count(*) from PeriodoLetivo pl
        where pl.idPeriodoLetivo = var_id_periodo_letivo and
			(var_data_evento < pl.dataInicio or var_data_evento > pl.dataFim);		
	select new.seqEvento into var_seq_evento;
	select new.idPeriodoLetivo into var_id_periodo_letivo;
	select new.tipoEvento into var_tipo_evento;
	select new.`data` into var_data_evento;
	
	-- Valida que se o campo tipoEvento foi preenchido, existe apenas um para um dado período letivo
	if (new.tipoEvento is not NULL) then
        open CUR_TIPO_EVENTO_UNICO;
        fetch CUR_TIPO_EVENTO_UNICO into cont;
        close CUR_TIPO_EVENTO_UNICO;
        if cont > 0 then
            call fail('So pode haver um marco em um dado periodo letivo.');
        end if;
	end if;
	
	-- Valida que a data do evento está dentro do período letivo
	open CUR_EVENTO_DENTRO_PERIODO;
	fetch CUR_EVENTO_DENTRO_PERIODO into cont;
	close CUR_EVENTO_DENTRO_PERIODO;
	if cont > 0 then
		call fail('Data do evento deve estar dentro do periodo letivo.');
	end if;		
	
end
|

create trigger PeriodoLetivo_VALIDA_UPDATE before update on PeriodoLetivo for each row
begin
    declare cont INT;
	declare var_id_periodo_letivo INT;
	declare var_data_inicio DATE;
	declare var_data_fim DATE;
	declare CUR_ABRANGE_EVENTOS cursor for select count(*) from EventoPeriodoLetivo epl
		where epl.idPeriodoLetivo = var_id_periodo_letivo and
			(epl.data  < var_data_inicio or epl.data > var_data_fim);
	select new.idPeriodoLetivo into var_id_periodo_letivo;
	select new.dataInicio into var_data_inicio;
	select new.dataFim into var_data_fim;
	
	-- Valida que a data do evento está dentro do período letivo
	open CUR_ABRANGE_EVENTOS;
	fetch CUR_ABRANGE_EVENTOS into cont;
	close CUR_ABRANGE_EVENTOS;
	if cont > 0 then
		call fail('Periodo letivo deve abranger todos os eventos dele.');
	end if;		
	
end
|

delimiter ;
