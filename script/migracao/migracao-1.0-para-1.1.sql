-- SCRIPT DE MIGRAÇÃO 1.0 para 1.1

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

use `CorujaProducao`;

alter table Professor 
 add column nomeGuerra varchar(15) not null comment 'Nome abreviado pelo qual o professor é conhecido' default '';

alter table Professor
 add column corFundo char(6) not null comment 'Cor de fundo da celúla da grade de horário' default 'FFFFFF';

alter table TipoCurso
	add UNIQUE INDEX `uk_tipo_curso_descricao` (`descricao` ASC);

alter table Curso
	add UNIQUE KEY `uk_curso_nomeCurso` (`nomeCurso` ASC);

alter table Espaco
	add UNIQUE KEY `uk_espaco_nome` (`nome` ASC);

alter table MatriculaAluno
 add column `dataConclusao` DATE NULL COMMENT 'Data em que o aluno integralizou o curso';

delimiter |

drop trigger MatriculaAluno_VALIDA_UPDATE
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

delimiter ;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
