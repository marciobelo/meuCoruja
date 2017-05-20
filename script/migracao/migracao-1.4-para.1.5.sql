alter table PeriodoLetivo 
	add column `rodouBloqueioAutomatico` ENUM('SIM','NÃO') NOT NULL default 'NÃO' comment 'Indica se o procedimento de bloqueio automático foi executado nesse período letivo';

alter table Login 
	add column `motivoBloqueio` VARCHAR(255) NULL comment 'Motivo pelo qual o Login foi bloqueado';

alter table Curso
	add column `tempoMaximoIntegralizacaoEmMeses` TINYINT UNSIGNED NOT NULL default 60 comment 'Quantidade máxima de meses regulamentar para o aluno concluir o curso';

-- para ser executado só quando for migrar para produção
-- NÃO ESQUECER DE DAR TODAS AS PERMISSOES DESSA NOVA FUNCAO
-- INSERT INTO `Funcao` (`idCasoUso`, `descricao`, `critico`) VALUE ('UC03.09.05', 'Desbloquear Login','SIM');
-- insert into Permite (idPessoa,idCasoUso) values (1029, 'UC03.09.05');