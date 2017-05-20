<?php
require_once "../../includes/comum.php";
require_once "$BASE_DIR/classes/MatrizCurricularProposta.php";
require_once "$BASE_DIR/classes/ComponenteCurricularProposto.php";

$siglaCurso = $_POST['siglaCurso'];
$idMatrizVigente = $_POST['idMatrizVigente'];
$newMaiorPeriodo = $_POST['newMaiorPeriodo'];

$matrizProposta = MatrizCurricularProposta::obter($siglaCurso, $idMatrizVigente);
$componentesCurricularesPropostos = ComponenteCurricularProposto::obterComponentesCurricularesDeUmaMatriz($matrizProposta);

foreach ($componentesCurricularesPropostos as $ccp) {
    if ($ccp->getPeriodo() > $newMaiorPeriodo) {
        echo "true";
        exit;
    }
}

echo "false";
exit;

