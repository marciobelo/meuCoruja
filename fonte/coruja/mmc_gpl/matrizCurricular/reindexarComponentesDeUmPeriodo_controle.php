<?php

require_once "../../includes/comum.php";
require_once "$BASE_DIR/classes/ComponenteCurricularProposto.php";
require_once "$BASE_DIR/classes/MatrizCurricularProposta.php";

$idMatriz = $_POST['idMatriz'];
$siglaCurso = $_POST['siglaCurso'];
$periodo = $_POST['periodo'];
$posicoes = $_POST['posicoes'];

foreach ($posicoes as $index => $siglaDisciplina) {
    $ccp = ComponenteCurricularProposto::obterComponeteCurricular($siglaCurso, $idMatriz, $siglaDisciplina);
    $ccp->setPosicaoPeriodo($index);
    $ccp->salva();
}




