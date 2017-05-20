<?php
// REQUIRE DO ARQUIVO COMUM
	require_once("../../includes/comum.php");
// INCLUDE DA CLASSE DE EVENTO ADMINISTRATIVO		
	include_once "$BASE_DIR/siro/classes/EventoAdministrativo.php";
// INCLUDE DA CLASSE DE PERIODO LETIVO
	include_once "$BASE_DIR/classes/PeriodoLetivo.php";
// INCLUDE DA CLASSE DE CURSO
        include_once "$BASE_DIR/classes/Curso.php";

// TOPO DA P�GINA
include_once "$BASE_DIR/includes/topo.php";

// MENU HORIZONTAL
    echo '<div id="menuprincipal">';
        include_once "$BASE_DIR/includes/menu_horizontal.php";  
    echo '</div>';

// CONTE�DO DA P�GINA - ARQUIVO QUE TEM A FUN��O DE TRATAR AS REQUISI��ES    
    echo '<div id="conteudo">';
        $action = $_GET['action'];
        if($action == "listar"){// lista a pagina inicial do Emitir Calend�rio Letivo
        	
        	$idPeriodo = $_POST['idPeriodoLetivo'];

                $periodoLetivo = PeriodoLetivo::obterPeriodoLetivo($idPeriodo);

                $classeCurso = Curso::obterCurso($periodoLetivo->getSiglaCurso());
                
        	// executa a lista do manter evento administrativo
        	$calendario = new EventoAdministrativo();
        	$collection = $calendario->listaEvento($idPeriodo,$EMITIR_CALENDARIO_LETIVO);
        	require"$BASE_DIR/siro/formularios/calendarioLetivo.php";
        	
        }
    echo '</div>';

// RODAP� DA P�GINA
include_once "$BASE_DIR/includes/rodape.php";

?>