<?php
require_once("../../includes/comum.php");
include_once "$BASE_DIR/siro/classes/EventoAdministrativo.php";
include_once "$BASE_DIR/classes/PeriodoLetivo.php";
include_once "$BASE_DIR/classes/Curso.php";
        
// TOPO DA PÁGINA
include_once "$BASE_DIR/includes/topo.php";

// MENU HORIZONTAL
    echo '<div id="menuprincipal">';
        include_once "$BASE_DIR/includes/menu_horizontal.php";
    echo '</div>';

// CONTEÚDO DA PÁGINA - ARQUIVO QUE TEM A FUNÇÃO DE TRATAR AS REQUISIÇÕES    
    echo '<div id="conteudo">';
        $action = $_GET['action'];
        if($action == "listar"){// lista a pagina inicial do manter evento administrativo
        	
        	$idPeriodo = $_POST['idPeriodoLetivo'];
        	$periodoLetivo = PeriodoLetivo::obterPeriodoLetivo($idPeriodo);

                $classeCurso = Curso::obterCurso($periodoLetivo->getSiglaCurso());
                
        	// executa a lista do manter evento administrativo
        	$evento = new EventoAdministrativo();
        	$collection = $evento->listaEvento($idPeriodo, $MANTER_EVENTOS_PERIODO_LETIVO);

                if($retorno!=""){
                    echo "<form name='msg' id='msg'><fieldset id='fieldsetMsg'>";
                    echo "<font>". htmlspecialchars($retorno, ENT_QUOTES, "iso-8859-1") ."</font>";
                    echo "</fieldset></form>";
        	}

        	require "$BASE_DIR/siro/formularios/eventoAdministrativo.php";
        	
        }elseif($action == "cadastrar") {// acao para carregar o formulario de cadastro 
        	
        	$idPeriodo = $_POST['idPeriodoLetivo'];

                $periodoLetivo = PeriodoLetivo::obterPeriodoLetivo($idPeriodo);

                $classeCurso = Curso::obterCurso($periodoLetivo->getSiglaCurso());
                
        	require "$BASE_DIR/siro/formularios/eventoCadastro.php";
        	
        } elseif ($action == "gravar") {
        	
            $idPeriodo = $_POST['idPeriodoLetivo'];
                
            $msgErro = "";
            $periodoLetivo = PeriodoLetivo::obterPeriodoLetivo( $idPeriodo );
            $classeCurso = Curso::obterCurso($periodoLetivo->getSiglaCurso());
            $evento = new EventoAdministrativo();

            $dataInicio = Util::dataBrParaSQL($_POST['dtIni']);
            $dataFim = Util::dataBrParaSQL($_POST['dtFim']);
            $dataInicialPeriodo = $periodoLetivo->getDataInicio();
            $dataFinalPeriodo = $periodoLetivo->getDataFim();
           
            if( $periodoLetivo->isDataForaPeriodo( $dataInicio ) || 
                    ( $dataFim != "" && $periodoLetivo->isDataForaPeriodo( $dataFim ) ) ) {
                $evento->setData($_POST['dtIni']);
                $evento->setDescricao($_POST['descricaoEvento']);
                $evento->setIdPeriodoLetivo($idPeriodo);
                $evento->setTipoEvento($_POST['tipoEvento']);
                $dataFim = $_POST['dtFim'];
                $siglaCurso = $_POST['siglaCurso'];
                $siglaPeriodo = $_POST['siglaPeriodo'];
                $msgErro = "Datas digitadas estão fora do período letivo!";
                require "$BASE_DIR/siro/formularios/eventoCadastro.php";
                exit;
            }
            
            $evento = new EventoAdministrativo();
            $evento->setData(Util::dataBrParaSQL($_POST['dtIni']));
            $evento->setDescricao($_POST['descricaoEvento']);
            $evento->setIdPeriodoLetivo($idPeriodo);
            $evento->setTipoEvento($_POST['tipoEvento']);
            $retorno = $evento->insereEvento($_POST['difDatas']);
            $siglaCurso = $_POST['siglaCurso'];
            $siglaPeriodo = $_POST['siglaPeriodo'];
            if( $retorno != "") {
                $evento->setData($_POST['dtIni']);
                $evento->setDescricao($_POST['descricaoEvento']);
                $evento->setIdPeriodoLetivo($idPeriodo);
                $evento->setTipoEvento($_POST['tipoEvento']);
                $msgErro = $retorno;
                require "$BASE_DIR/siro/formularios/eventoCadastro.php";
            } else {
                $retorno = "Informações Inseridas com Sucesso!.";

                // volta para a pagina inicial listando novamente os eventos
                echo"<form id='listar' name='listar' action='EventoAdministrativo_controle.php?action=listar' method='post'>";
                echo"<input type='hidden' name='idPeriodoLetivo' value='".$idPeriodo."'>";
                echo"<input type='hidden' name='retorno' value='$retorno'>";
                echo"<script>document.listar.submit();</script></form>";
            }
        	
        } elseif( $action == "alterar" ) { // apresenta tela para alterar evento administrativo
        	$evento = new EventoAdministrativo();
                $seqEvento = $_POST['seqEvento'];

                $evento->getEvento($seqEvento);
                $periodoLetivo = PeriodoLetivo::obterPeriodoLetivo($evento->getIdPeriodoLetivo());
                $classeCurso = Curso::obterCurso($periodoLetivo->getSiglaCurso());

                require "$BASE_DIR/siro/formularios/eventoAlterar.php";
        	
        } elseif( $action == "atualizar") { // acao para salvar no banco os dados modificados

            $evento = new EventoAdministrativo();
            $seqEvento = $_POST['seqEvento'];
            $idPeriodo = $_POST['idPeriodoLetivo'];
            $evento->setData(Util::dataBrParaSQL($_POST['data']));
            $evento->setDescricao($_POST['descricaoEvento']);
            $evento->setIdPeriodoLetivo($idPeriodo);
            $evento->setTipoEvento($_POST['tipoEvento']);
            $evento->setSeqEvento($seqEvento);
            $msgErro = "";
            $periodoLetivo = PeriodoLetivo::obterPeriodoLetivo( $idPeriodo );
            $classeCurso = Curso::obterCurso($periodoLetivo->getSiglaCurso());
            $data = $evento->getData();
            $dataInicialPeriodo = Util::dataBrParaSQL($periodoLetivo->getDataInicio());
            $dataFinalPeriodo = Util::dataBrParaSQL($periodoLetivo->getDataFim());
            if( $periodoLetivo->isDataForaPeriodo( $data ) ) {
                $msgErro = "Data digitada está fora do período letivo escolhido!.";
                $evento->setData($_POST['data']);
                $evento->setDescricao($_POST['descricaoEvento']);
                $evento->setIdPeriodoLetivo($idPeriodo);
                $evento->setTipoEvento($_POST['tipoEvento']);
                require "$BASE_DIR/siro/formularios/eventoAlterar.php";
            }
            if($msgErro=="") {
                $retorno = $evento->atualizaEvento();

                if($retorno!=""){
                    $msgErro = $retorno;
                    $evento->setData($_POST['data']);
                    require "$BASE_DIR/siro/formularios/eventoAlterar.php";
                } else {
                    $retorno = "Informações Atualizadas com Sucesso!.";

                    // volta para a pagina inicial listando novamente os eventos
                    echo"<form id='listar' name='listar' action='EventoAdministrativo_controle.php?action=listar' method='post'>";
                    echo"<input type='hidden' name='idPeriodoLetivo' value='".$idPeriodo."'>";
                    echo"<input type='hidden' name='retorno' value='$retorno'>";
                    echo"<script>document.listar.submit();</script></form>";
                }
            }
        	
        } elseif($action == "excluir") {// acao para excluir um evento selecionado
        	
        	// chama o metodo de excluir para um id especifico
        	
        	$evento = new EventoAdministrativo();
        	$seqEvento = $_POST['seqEvento'];
        	$idPeriodo = $_POST['idPeriodoLetivo'];
                
                $evento->excluir($seqEvento);

                // volta para a pagina inicial listando novamente os eventos
                echo"<form id='listar' name='listar' action='EventoAdministrativo_controle.php?action=listar' method='post'>";
                echo"<input type='hidden' name='idPeriodoLetivo' value='".$idPeriodo."'>";
                echo"<script>document.listar.submit();</script></form>";
        	
        }
    echo '</div>';

// RODAPÉ DA PÁGINA
include_once "$BASE_DIR/includes/rodape.php";

?>