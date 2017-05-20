<?php
require_once("../../includes/comum.php");
require_once("$BASE_DIR/classes/MatriculaAluno.php");

$acao = $_POST["acao"];
if($acao=="gerarMatriculaProvisoria") {

    $matriculaAlunoProvisoria = MatriculaAluno::gerarMatriculaProvisoria();

    $xml = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>
            <coruja>
            <matriculaAlunoProvisoria>" . $matriculaAlunoProvisoria . "</matriculaAlunoProvisoria>
        </coruja>";
    header('Content-Type: text/xml; charset=ISO-8859-1');
    echo $xml;
} else {
    "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>
            <coruja>
            <matriculaAlunoProvisoria>ERRO</matriculaAlunoProvisoria>
        </coruja>";
    header('Content-Type: text/xml; charset=ISO-8859-1');
    echo $xml;
}
?>
