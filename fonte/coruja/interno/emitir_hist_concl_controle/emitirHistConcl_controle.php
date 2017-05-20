<?php
require_once( "../../includes/comum.php");
ini_set("memory_limit", "800000M");
require("$BASE_DIR/interno/class/fpdf/fpdf.php");
require_once "$BASE_DIR/classes/MatriculaAluno.php";
require_once "$BASE_DIR/classes/Aluno.php";
require_once "$BASE_DIR/classes/Curso.php";
require_once "$BASE_DIR/classes/PeriodoLetivo.php";

$action = filter_input( INPUT_GET, "action", FILTER_SANITIZE_STRING);
if( $action === NULL)
{
    $action = filter_input( INPUT_POST, "action", FILTER_SANITIZE_STRING);
}
$operacao = filter_input( INPUT_GET, "operacao", FILTER_SANITIZE_STRING);
if( $operacao === NULL)
{
    $operacao = filter_input( INPUT_POST, "operacao", FILTER_SANITIZE_STRING);
}
$criterio = filter_input( INPUT_POST, "criterio", FILTER_SANITIZE_NUMBER_INT);
if( $criterio === NULL)
{
    $criterio = "2";
}

if ($action === "consultar") {

    if( $operacao === "1") 
    {
        $operacao = 'consultar_matricula';
        consultar($operacao);
    }

    if( $operacao === "2") 
    {
        $operacao = 'consultar_nome';
        consultar( $operacao);
    }

    consultar( $operacao);

    require("$BASE_DIR/interno/view/aluno/consulta.php");
}

function consultar( $operacao) 
{
    switch ($operacao) 
    {
        case 'consultar_matricula':
            global $linha;
            global $resultado;
            global $collection;
            global $msgErro;

            $numMatricula = filter_input( INPUT_POST, "matricula", FILTER_SANITIZE_STRING);
            if( $numMatricula !== "") 
            {
                $collection=array();
                $matriculaAluno = MatriculaAluno::obterMatriculaAluno($numMatricula);
                if( $matriculaAluno === NULL ) 
                {
                    $msgErro = "Nenhum Registro encontrado!";
                }
                else
                {
                    array_push($collection, $matriculaAluno);
                }
            }
            break;

        case 'consultar_nome':
            global $linha;
            global $resultado;
            global $collection;
            global $msgErro;

            $nome = filter_input( INPUT_POST, "nome", FILTER_SANITIZE_STRING);
            if( $nome !== "") 
            {
                $aluno = new Aluno();
                $collection = $aluno->obter_concluinte_nome( $nome);
                if( $collection === NULL || count( $collection) === 0) 
                {
                    $msgErro = "Nenhum Registro encontrado!";
                }
            }
            break;
    }
}