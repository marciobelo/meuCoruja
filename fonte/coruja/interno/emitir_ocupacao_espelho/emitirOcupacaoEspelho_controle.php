<?php
require_once "../../includes/comum.php";
ini_set("memory_limit","800000M");

require_once "$BASE_DIR/classes/Curso.php";
require_once "$BASE_DIR/classes/Espaco.php";
require_once "$BASE_DIR/classes/PeriodoLetivo.php";
require_once "$BASE_DIR/interno/class/OcupacaoEspelhoPDF.php";

//para a visão da grade de alocação de espaço
$acao = filter_input( INPUT_GET, "acao", FILTER_SANITIZE_STRING);
if( $acao === NULL)
{
    $acao = filter_input( INPUT_POST, "acao", FILTER_SANITIZE_STRING);
}

if( isset($_SESSION["siglaCursoFiltro"]))
{
    $siglaCurso = $_SESSION["siglaCursoFiltro"];
}
else
{
    $siglaCurso = filter_input( INPUT_POST, "siglaCurso", FILTER_SANITIZE_STRING);
}

if( $acao === "emitirEspaco") 
{
    global $collection1;
    global $collection2;
    global $collection3;

    if(!$usuario->temPermissao($EXIBIR_ESPELHO_OCUPACAO)) {
       require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
       exit;
    }

    $Curso = new Curso();
    $collection1 = $Curso->obterCursosOrdemPorSigla();

    $periodoletivo = new PeriodoLetivo();
    $collection2 = $periodoletivo->listaPeriodos(0,3000, $siglaCurso);

    $espaco = new Espaco();
    $collection3 = $espaco->obterEspacos();

    require("$BASE_DIR/interno/view/emitir_ocupacao_espelho/emitir_ocupacao_espelho.php");
} 
else if($acao=="exibirPDF") 
{
    if(!$usuario->temPermissao($EXIBIR_ESPELHO_OCUPACAO)) 
    {
       require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
       exit;
    }
    ini_set("memory_limit","800000M");
    $PDF = new OcupacaoEspelhoPDF();
    $periodoletivo=PeriodoLetivo::obterPeriodoLetivo($_REQUEST['periodo']);
    $espaco= Espaco::obterEspacoPorId($_REQUEST['espaco']);
    $var="Emissão do espelho de alocação do espaço ";
    $var.=$espaco->getNome();
    $var.=', curso '.$_REQUEST['siglaCurso'];
    $var.=", do período letivo ";
    $var.=$periodoletivo->getSiglaPeriodoLetivo();

    $usuario->incluirLog($EXIBIR_ESPELHO_OCUPACAO, $var);

    $PDF->geraGrade(282,$_REQUEST['siglaCurso'], $espaco->getNome(), $periodoletivo->getSiglaPeriodoLetivo());
    $PDF->Output();

}