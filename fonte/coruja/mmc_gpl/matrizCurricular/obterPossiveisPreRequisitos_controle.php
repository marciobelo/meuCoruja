<?php

require_once "../../includes/comum.php";
require_once "$BASE_DIR/classes/MatrizCurricularProposta.php";

$siglaCurso = $_POST['siglaCurso'];
$idMatrizVigente = $_POST['idMatrizVigente'];
$possiveisPreRequisitos = array();

$matrizCurricularProposta = MatrizCurricularProposta::obter($siglaCurso, $idMatrizVigente);
$todosOsComponentes = $matrizCurricularProposta->obterComponentes();

foreach ($todosOsComponentes as $key => $compoennte) {
    $possiveisPreRequisitos[$key]['siglaDisciplina'] = $compoennte->getSiglaDisciplina();
    $possiveisPreRequisitos[$key]['periodo'] = $compoennte->getPeriodo();
}

print json_encode($possiveisPreRequisitos);




