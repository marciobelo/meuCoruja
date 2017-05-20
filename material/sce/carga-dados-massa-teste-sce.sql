-- --------------------------------
-- SCE
-- --------------------------------

USE sce;

SET AUTOCOMMIT=0;
INSERT INTO categoriaevento (id,nome)
VALUES (0001,'Palestra');

INSERT INTO tema (id,nome)
VALUES (0001,'Semana Tecnol�gica');

INSERT INTO `evento` (`id`, `nome`, `dtInicial`, `dtFinal`, `idCategoriaEvento`, `idPessoa`, `idTema`)
VALUES(1, 'XIII Semana Tecnol�gica', '2010-10-09', '2011-10-09', 1, 1029, 1), (2, 'XIV Semana Tecnol�gica', '2010-05-24', '2010-05-28', 1, 1013, 1);

INSERT INTO categoriaatividade(id,nome)
VALUES(0001,'Mini-curso');

INSERT INTO atividade(id,nome,data, horaInicial, horaFinal, horaExtensao,idcategoriaAtividade,idEvento,idPalestrante,idEspaco, tipoPalestrante)
VALUES(0001,'Estrutura de Dados',101009,100000,110000,5,0001,0001,1029,0001,"I");

INSERT INTO avaliacao(id,sugestao,sugestaoevento,status,idPessoa,idAtividade)
VALUES(0001,'Pal','seilatambem',0,1029,0001);

INSERT INTO aspecto (id,nome)
VALUES(0001,'Recep��o'), (0002,'Local'), (0003,'Palestrante'), (0004, 'Hor�rio'), (0005, 'Assunto');

INSERT INTO avaliacaoaspecto (id, idAspecto, idAvaliacao, resposta)
VALUES(0001,0001,0001,1);

--
-- Extraindo dados da tabela `atividade`
--

INSERT INTO `atividade` (`id`, `nome`, `data`, `horaInicial`, `horaFinal`, `horaExtensao`, `idCategoriaAtividade`, `idEvento`, `idPalestrante`, `idEspaco`, `tipoPalestrante`) VALUES
(2, 'Caf� da Manh�; Credenciamento e Abertura', '2010-05-24', '08:00:00', '08:30:00', 5, 1, 2, 1029, 7, "I"),
(3, 'Petr�polis Day', '2010-05-24', '08:30:00', '11:00:00', 5, 1, 2, 1029, 7, "I"),
(4, 'A universidade no s�culo XXI', '2010-05-24', '11:00:00', '11:50:00', 5, 1, 2, 1016, 6, "I"),
(5, 'Abertura Exposi��o de Artes Pl�sticas', '2010-05-24', '11:50:00', '12:00:00', 5, 1, 2, 1029, 7, "I"),
(6, 'Credenciamento e Abertura', '2010-05-24', '18:30:00', '19:00:00', 5, 1, 2, 1029, 7, "I"),
(7, 'V�deo Confer�ncia: mix leitor D', '2010-05-24', '19:00:00', '20:00:00', 5, 1, 2, 1029, 6, "I"),
(8, 'Coffee Break', '2010-05-24', '20:00:00', '20:30:00', 5, 1, 2, 1029, 7, "I"),
(9, 'Uso de Frameworks para constru��o de ambiente', '2010-05-24', '20:30:00', '21:10:00', 5, 1, 2, 1029, 6, "I"),
(10, 'Espa�o V�deos e Curtas', '2010-05-24', '21:10:00', '22:30:00', 5, 1, 2, 1029, 6, "I"),
(11, 'RETIVICO', '2010-05-25', '08:00:00', '09:00:00', 5, 1, 2, 1029, 6, "I"),
(12, 'Scrum Project', '2010-05-25', '09:00:00', '10:00:00', 5, 1, 2, 1029, 6, "I"),
(13, 'Coffee Break', '2010-05-25', '10:00:00', '10:30:00', 5, 1, 2, 1029, 7, "I"),
(14, 'Web e Redes Sociais', '2010-05-25', '10:30:00', '11:30:00', 5, 1, 2, 1029, 6, "I"),
(15, 'SORRENTO', '2010-05-25', '18:30:00', '18:50:00', 5, 1, 2, 1029, 6, "I"),
(16, 'SIRO', '2010-05-25', '18:50:00', '19:10:00', 5, 1, 2, 1029, 6, "I"),
(17, 'T�cnicas para cria��o de jogos educacionais', '2010-05-25', '19:10:00', '19:30:00', 5, 1, 2, 1029, 6, "I"),
(18, 'Coffee Break', '2010-05-25', '19:30:00', '19:50:00', 5, 1, 2, 1029, 7, "I"),
(19, 'Prospec��o e implanta��o de tecnologia de identifica��o biom�trica', '2010-05-25', '19:50:00', '20:00:00', 5, 1, 2, 1029, 6, "I"),
(20, 'Base do Coruja', '2010-05-25', '20:00:00', '20:10:00', 5, 1, 2, 1029, 6, "I"),
(21, 'Data Warehouse e o Ensino Superior da FAETEC', '2010-05-25', '20:10:00', '20:20:00', 5, 1, 2, 1029, 6, "I"),
(22, 'Comp�rio', '2010-05-25', '20:20:00', '20:30:00', 5, 1, 2, 1029, 6, "I"),
(23, 'Intelig�ncia Artificial', '2010-05-25', '20:30:00', '21:30:00', 5, 1, 2, 1029, 6, "I"),
(24, 'Reposit�rio Virtual de Ferramentas e informa��es para leitura e reda��o automatizadas', '2010-05-26', '08:00:00', '09:00:00', 5, 1, 2, 1029, 6, "I"),
(25, 'Mapas mentais e conceituais como apoio a Engenharia de Software', '2010-05-26', '09:00:00', '10:00:00', 5, 1, 2, 1029, 6, "I"),
(26, 'Coffee Break', '2010-05-26', '10:00:00', '11:00:00', 5, 1, 2, 1029, 7, "I"),
(27, 'Instalando, configurando e entendendo as utilidades dos CMS Wordpress e Wikimedia', '2010-05-26', '10:30:00', '11:15:00', 5, 1, 2, 1029, 6, "I"),
(28, 'Solu��o de escalabilidade: um escrit�rio na nuvem', '2010-05-26', '11:15:00', '12:00:00', 5, 1, 2, 1029, 6, "I"),
(29, 'Relat�rios na Web', '2010-05-26', '19:00:00', '20:00:00', 5, 1, 2, 1029, 6, "I"),
(30, 'Coffee Break', '2010-05-26', '20:00:00', '20:30:00', 5, 1, 2, 1029, 6, "I"),
(31, 'Desenvolvimento de jogo para celular em Java', '2010-05-26', '20:30:00', '21:30:00', 5, 1, 2, 1029, 6, "I"),
(32, 'Ontologias para web sem�ntica', '2010-05-27', '08:00:00', '09:00:00', 5, 1, 2, 1029, 6, "I"),
(33, 'SARAU', '2010-05-27', '09:00:00', '09:40:00', 5, 1, 2, 1029, 6, "I"),
(34, 'Coffee Break', '2010-05-27', '09:40:00', '10:00:00', 5, 1, 2, 1029, 7, "I"),
(35, 'Cloud computing com a Amazon Web Services', '2010-05-27', '10:00:00', '10:40:00', 5, 1, 2, 1029, 6, "I"),
(36, 'Sociedade e WEB: Redes sociais', '2010-05-27', '10:40:00', '11:40:00', 5, 1, 2, 1029, 6, "I"),
(37, 'Escola Mandala e sua F�brica de Software', '2010-05-27', '18:30:00', '19:30:00', 5, 1, 2, 1029, 6, "I"),
(38, 'Introdu��o ao Linux para desktop (Ubuntu 10.0)', '2010-05-27', '19:30:00', '20:10:00', 5, 1, 2, 1029, 1, "I"),
(39, 'Boas pr�ticas de programa��o orientada a objetos', '2010-05-27', '19:30:00', '20:10:00', 5, 1, 2, 1029, 6, "I"),
(40, 'Coffee Break', '2010-05-27', '20:10:00', '20:30:00', 5, 1, 2, 1029, 7, "I"),
(41, 'O uso do sexto sentido em sistemas computacionais', '2010-05-27', '20:30:00', '21:00:00', 5, 1, 2, 1029, 6, "I"),
(42, 'Esclarecimentos sobre est�gio e extens�o', '2010-05-28', '08:00:00', '09:00:00', 5, 1, 2, 1029, 6, "I"),
(43, 'LHC - A m�quina da humanidade', '2010-05-28', '09:00:00', '10:00:00', 5, 1, 2, 1029, 6, "I"),
(44, 'Coffee Break', '2010-05-28', '10:00:00', '10:30:00', 5, 1, 2, 1029, 7, "I"),
(45, 'Fahrenheit 451', '2010-05-28', '10:30:00', '12:30:00', 5, 1, 2, 1029, 6, "I"),
(46, 'Ontologias para Web Sem�ntica', '2010-05-28', '18:30:00', '19:10:00', 5, 1, 2, 1029, 6, "I"),
(47, 'Desenvolvimento de aplica��es Web orientadas por teste', '2010-05-28', '19:10:00', '19:40:00', 5, 1, 2, 1029, 6, "I"),
(48, 'Coffee Break', '2010-05-28', '19:40:00', '20:00:00', 5, 1, 2, 1029, 7, "I"),
(49, 'Fahrenheit 451', '2010-05-28', '20:00:00', '21:30:00', 5, 1, 2, 1029, 6, "I"),
(50, 'Encerramento', '2010-05-28', '21:30:00', '22:00:00', 5, 1, 2, 1029, 7, "I"),
(51, 'Visita � exposi��o: "EINSTEIN" Museu Hist�rico Nacional - Pra�a Mal. �ncora - Centro/RJ', '2010-05-29', '14:00:00', '20:00:00', 5, 1, 2, 1029, 7, "I");

INSERT INTO `sce`.`administrador` ( `idPessoa` )
VALUES ('1029');

COMMIT;