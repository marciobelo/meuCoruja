INSERT INTO `criterioavaliacao` (`idCriterioAvaliacao`,`rotulo`) VALUES (2,'teste');
INSERT INTO `itemcriterioavaliacao` (`idItemCriterioAvaliacao`,`idCriterioAvaliacao`,`rotulo`,`descricao`,`ordem`,`tipo`,`formulaCalculo`) VALUES (7,2,'TESTE','ItemCriterioAvaliacaoTeste',7,'LANÇADO',NULL);
INSERT INTO `turma` (`idTurma`,`siglaCurso`,`idMatriz`,`siglaDisciplina`,`gradeHorario`,`idPeriodoLetivo`,`matriculaProfessor`,`turno`,`tipoSituacaoTurma`,`qtdeTotal`,`dataLiberacaoPautaPeloProfessor`,`idCriterioAvaliacao`) VALUES (38,'TASI',5,'AC2','A',19,'1094200','NOITE','LIBERADA',15,NULL,2);