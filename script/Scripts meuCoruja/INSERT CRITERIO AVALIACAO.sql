INSERT INTO `criterioavaliacao` (`rotulo`) VALUES ('teste');
INSERT INTO `itemcriterioavaliacao` (`idCriterioAvaliacao`,`rotulo`,`descricao`,`ordem`,`tipo`,`formulaCalculo`) VALUES (2,'TESTE','ItemCriterioAvaliacaoTeste',7,'LANÇADO',NULL);
update turma set idCriterioavaliacao = 2 where idturma = 38