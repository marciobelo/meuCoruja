<?php
require_once "../../includes/comum.php";
require_once "$BASE_DIR/classes/Curso.php";

$titulo = filter_input( INPUT_GET, "titulo", FILTER_SANITIZE_STRING);
$destino = filter_input( INPUT_GET, "destino", FILTER_SANITIZE_STRING);

$cursos = Curso::obterCursosOrdemPorSigla();

if( isset($_SESSION["siglaCursoFiltro"]))
{
    $siglaCursoFiltro = $_SESSION["siglaCursoFiltro"];
}
else
{
    $siglaCursoFiltro = "";
}

require_once "$BASE_DIR/interno/selecionar_curso_periodoletivo/telaSelecionarCursoPeriodoLetivo.php";