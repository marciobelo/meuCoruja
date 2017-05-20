<?php

require_once "../../includes/comum.php";
ini_set("memory_limit", "800000M");

require_once "$BASE_DIR/classes/Curso.php";
require_once "$BASE_DIR/classes/PeriodoLetivo.php";
require_once "$BASE_DIR/interno/class/ResumoAlocacaoProfessorPDF.php";

//para a visão da grade de alocação de professor
$acao = $_REQUEST["action"];
if ($acao === "ResumoAlocacaoProfessor") {
    global $collection1;
    global $collection2;
    global $collection3;

    if (!$usuario->temPermissao($RESUMO_ALOCACAO_PROFESSOR)) {
        require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
        exit;
    }

    $collection1 = array();

    $Curso = new Curso();
    $collection1 = $Curso->obterCursosOrdemPorSigla();

    $siglaCursoFiltro = Util::obterFiltroSiglaCurso();
    if( $siglaCursoFiltro === "")
    {
        $siglaCurso = filter_input( INPUT_POST, "curso", FILTER_SANITIZE_STRING);
    }
    else
    {
        $siglaCurso = $siglaCursoFiltro;
    }
    if( $siglaCurso !== NULL) 
    {
        $periodoletivo = new PeriodoLetivo();
        $collection2 = $periodoletivo->listaPeriodos(0, 3000, $siglaCurso);
    }
    require("$BASE_DIR/interno/view/emitir_resumo_alocacao_professor/emitir_alocacao_professor.php");
} else if ($acao == "exibirPDF") {

    if (!$usuario->temPermissao($RESUMO_ALOCACAO_PROFESSOR)) {
        require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
        exit;
    }
    ini_set("memory_limit", "800000M");
    $PDF = new ResumoAlocaoProfessorPDF();

    $periodoletivo = PeriodoLetivo::obterPeriodoLetivo($_REQUEST['periodo']);

    $var = "Emitido o resumo de alocações dos professores para o curso ";
    $var.=', curso ' . $_REQUEST['curso'];
    $var.=", do período letivo ";
    $var.=$periodoletivo->getSiglaPeriodoLetivo();

    $usuario->incluirLog($RESUMO_ALOCACAO_PROFESSOR, $var);

    $PDF->geraGrade(282, $_REQUEST['curso'], $periodoletivo->getSiglaPeriodoLetivo());
    $PDF->Output();
}
?>
