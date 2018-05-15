<?php
require_once "../../includes/comum.php";
ini_set("memory_limit","800000M");

require_once "$BASE_DIR/classes/Curso.php";
require_once "$BASE_DIR/classes/Professor.php";
require_once "$BASE_DIR/classes/PeriodoLetivo.php";

require_once "$BASE_DIR/interno/class/AlocacaoProfessorPDF.php";

//para a visão da grade de alocação de professor
$acao = $_REQUEST["action"];
if($acao === "AlocacaoProfessor") 
{
    global $collection1;
    global $collection2;
    global $collection3;

    if(!$login->temPermissao($EXIBIR_ALOCACAO_PROFESSOR)) 
    {
       require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
    }

    $collection1 = Curso::obterCursosOrdemPorSigla();
    
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
        $collection2 = PeriodoLetivo::obterPeriodosLetivoPorSiglaCurso( $siglaCurso);
        $collection3 = Professor::obterProfessoresVigentes();
    }
    require("$BASE_DIR/interno/view/emitir_alocacao_professor/emitir_alocacao_professor.php");
} 
else if($acao=="exibirPDF") 
{
    if(!$login->temPermissao($EXIBIR_ALOCACAO_PROFESSOR)) 
    {
       require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
    }
    $PDF = new AlocacaoProfessorPDF();
    $professor = Professor::getProfessorByIdPessoa( $_REQUEST['professor']);
    $periodoletivo=PeriodoLetivo::obterPeriodoLetivo($_REQUEST['periodo']);
   
    $var="Emitido alocação do professor ";
    $var.=$professor->getNome();
    $var.=', curso '.$_REQUEST['curso'];
    $var.=", do período letivo ";
    $var.=$periodoletivo->getSiglaPeriodoLetivo();

    $login->incluirLog($EXIBIR_ALOCACAO_PROFESSOR, $var);

    $PDF->geraGrade(282,$_REQUEST['curso'], $professor->getNome(), $periodoletivo->getSiglaPeriodoLetivo());
    $PDF->Output();
}