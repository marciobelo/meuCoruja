<?php
require_once("../../includes/comum.php");
require_once("$BASE_DIR/classes/PeriodoLetivo.php");
require_once("$BASE_DIR/classes/Curso.php");
require_once("$BASE_DIR/classes/Util.php");
require_once("$BASE_DIR/siro/classes/EventoAdministrativo.php");
	
// Recupera o usuário logado da sessão
if( isset($_SESSION["siglaCursoFiltro"]))
{
    $siglaCursoFiltro = $_SESSION["siglaCursoFiltro"];
}
else
{
    $siglaCursoFiltro = "";
}
    
// TOPO DA PÁGINA
include_once "$BASE_DIR/includes/topo.php";

// MENU HORIZONTAL
    echo '<div id="menuprincipal">';
        include_once "$BASE_DIR/includes/menu_horizontal.php";
    echo '</div>';

// CONTEÚDO DA PÁGINA - ARQUIVO QUE TEM A FUNÇÃO DE TRATAR AS REQUISIÇÕES    
    echo '<div id="conteudo">';
        $action = filter_input( INPUT_GET, "action", FILTER_SANITIZE_STRING);
       
        if($action === "curso" && $siglaCursoFiltro === "") {// acao para exibir a pagina de filtro de curso
        	
			// Verifica Permissão
			if(!$login->temPermissao($MANTER_PERIODO_LETIVO)) {
                            require_once "$BASE_DIR/baseCoruja/formularios/sem_permissao.php";
                            exit;
			}		

        	$collection = Curso::obterCursosOrdemPorSigla();
        	require "$BASE_DIR/siro/formularios/periodoLetivoSelecionaCurso.php";
        } 
        else if($action === "listar" || ($action === "curso" && $siglaCursoFiltro !== "")) 
        { 
            if( $siglaCursoFiltro !== "")
            {
                $siglaCurso = $siglaCursoFiltro;
            }
            else
            {
                $siglaCurso = filter_input( INPUT_POST, "siglaCurso", FILTER_SANITIZE_STRING);                
            }

            $classeCurso = Curso::obterCurso($siglaCurso);

            $perLetivo = new PeriodoLetivo();
            $collection = $perLetivo->listaPeriodos(0,10,$siglaCurso);
            $retorno = $_POST['retorno'];

            if($retorno!=""){
                echo "<form name='msg' id='msg'><fieldset id='fieldsetMsg'>";
                echo "<font><b>". htmlspecialchars($retorno, ENT_QUOTES, "iso-8859-1") ."</b></font>";
                echo "</fieldset></form>";
            }
            require"$BASE_DIR/siro/formularios/periodoLetivo.php";
        	
        } elseif($action == "cadastrar") {// acao para carregar o formulario de cadastro
        	
            // Verifica Permissão
            if(!$login->temPermissao($MANTER_PERIODO_LETIVO_INCLUIR)) {
                require_once "$BASE_DIR/baseCoruja/formularios/sem_permissao.php";
                exit;
            }		
			
            $siglaCurso = $_POST['siglaCurso'];
            $classeCurso = Curso::obterCurso($siglaCurso);
                
            require "$BASE_DIR/siro/formularios/periodoLetivoCadastro.php";
        	
        } elseif($action == "gravar") {// responsavel por gerenciar as informações que serão salvas no banco

            $siglaCurso = $_POST['siglaCurso'];
            $perLetivo = new PeriodoLetivo();
            $perLetivo->setDataInicio($_POST['dtIni']);
            $perLetivo->setDataFim($_POST['dtFim']);
            $perLetivo->setSiglaCurso($siglaCurso);
            $perLetivo->setSiglaPeriodoLetivo($_POST['sigla']);

            $classeCurso = Curso::obterCurso($siglaCurso);
            $maxId = $perLetivo->getMaxId($classeCurso->getSiglaCurso());
            if($perLetivo->isDataMenor($perLetivo->getDataInicio(),$maxId)){
        		echo "<p>" . htmlspecialchars("A data inicial digitada é menor ou igual que a data inicial do último período letivo cadastrado.", ENT_QUOTES, "iso-8859-1")
                                . "</p>";
        		require "$BASE_DIR/siro/formularios/periodoLetivoCadastro.php";
        	}else{
        		$retorno = $perLetivo->inserePeriodo();
        		if($retorno!=""){
        			$msgErro = $retorno;
        			require "$BASE_DIR/siro/formularios/periodoLetivoCadastro.php";
        		}else{
                            $retorno = "Informações Inseridas com Sucesso!.";

                            // volta para a pagina inicial listando novamente os periodos
                            echo"<form id='listar' name='listar' action='PeriodoLetivo_controle.php?action=listar' method='post'>";
                            echo"<input type='hidden' name='siglaCurso' value='".$siglaCurso."'>";
                            echo"<input type='hidden' name='retorno' value='$retorno'>";
                            echo"<script>document.listar.submit();</script></form>";
        		}
        	}
        } elseif($action == "alterar") {// acao para carregar a pagina de alterar
        	// resgata o objeto periodoletivo e envia para a pagina de alterar
        	
        	$idPeriodo = $_POST['idPeriodoLetivo'];
        	$perLetivo = PeriodoLetivo::obterPeriodoLetivo($idPeriodo);

                $classeCurso = Curso::obterCurso($perLetivo->getSiglaCurso());
        	require "$BASE_DIR/siro/formularios/periodoLetivoAlterar.php";
        	
        }elseif($action == "atualizar"){// acao para salvar no banco os dados modificados
        	// pega as informações enviadas e atualiza o objeto
        	
        	$idPeriodo = $_POST['idPeriodoLetivo'];
        	$perLetivo = new PeriodoLetivo();
        	$perLetivo->setDataFim(Util::dataBrParaSQL($_POST['dtFim']));
        	$perLetivo->setDataInicio(Util::dataBrParaSQL($_POST['dtIni']));
        	$perLetivo->setSiglaPeriodoLetivo($_POST['sigla']);
        	$perLetivo->setIdPeriodoLetivo($idPeriodo);
        	$idAnterior = $perLetivo->getIdPeriodoAnterior($idPeriodo);
                
            if($perLetivo->isDataMenor($_POST['dtIni'],$idAnterior)){
        		$msgErro = "A data inicial digitada é menor ou igual que a data inicial do período letivo anterior cadastrado.";
        		$perLetivo = PeriodoLetivo::obterPeriodoLetivo($idPeriodo);
        		$perLetivo->setDataInicio($_POST['dtIni']);
        		$perLetivo->setDataFim($_POST['dtFim']);
        		$perLetivo->setSiglaPeriodoLetivo($_POST['sigla']);
                $classeCurso = Curso::obterCurso($perLetivo->getSiglaCurso());
        		require "$BASE_DIR/siro/formularios/periodoLetivoAlterar.php";
        	}else{
        	$evento = new EventoAdministrativo();
        	if($evento->isAlteracaoInvalida($idPeriodo,Util::dataBrParaSQL($_POST['dtIni']),Util::dataBrParaSQL($_POST['dtFim']))){
        		$msgErro = "A data digitada está fora do período de um dos eventos deste Período Letivo.";
        		$perLetivo = PeriodoLetivo::obterPeriodoLetivo($idPeriodo);
        		$perLetivo->setDataInicio($_POST['dtIni']);
        		$perLetivo->setDataFim($_POST['dtFim']);
        		$perLetivo->setSiglaPeriodoLetivo($_POST['sigla']);
                $classeCurso = Curso::obterCurso($perLetivo->getSiglaCurso());
        		require "$BASE_DIR/siro/formularios/periodoLetivoAlterar.php";
        	}else{	
	        	$retorno=$perLetivo->atualizaPeriodo();
	
	                $siglaCurso = $_POST['siglaCurso'];
	        	if($retorno=="") {
	                    
	                    $retorno = "Informações Atualizadas com Sucesso!.";
	                    
	                    // volta para a pagina inicial listando novamente os periodos
	                    echo"<form id='listar' name='listar' action='PeriodoLetivo_controle.php?action=listar' method='post'>";
	                    echo"<input type='hidden' name='siglaCurso' value='".$siglaCurso."'>";
	                    echo"<input type='hidden' name='retorno' value='$retorno'>";
	                    echo"<script>document.listar.submit();</script></form>";
	                    
	                } else {
	                    $perLetivo = PeriodoLetivo::obterPeriodoLetivo($idPeriodo);
	
	                    $classeCurso = Curso::obterCurso($perLetivo->getSiglaCurso());
	                    
	                    $msgErro = $retorno;
	                    require "$BASE_DIR/siro/formularios/periodoLetivoAlterar.php";
	                }
        	}
        	}
        } elseif( $action == "excluir" ) { // acao para excluir um periodo letivo selecionado
        	
        	$idPeriodo = $_POST['idPeriodoLetivo'];
                $perLetivo = PeriodoLetivo::obterPeriodoLetivo($idPeriodo);
        	$retorno = $perLetivo->excluir();

                $siglaCurso = $_POST['siglaCurso'];
                
                //retorna para a lista de periodos letivos
                echo"<form id='listar' name='listar' action='PeriodoLetivo_controle.php?action=listar' method='post'>";
                echo"<input type='hidden' name='siglaCurso' value='".$siglaCurso."'>";
                echo"<input type='hidden' name='retorno' value='$retorno'>";
                echo"<script>document.listar.submit();</script></form>";
        	
        } 
    echo '</div>';

// RODAPÉ DA PÁGINA
include_once "$BASE_DIR/includes/rodape.php";