<?php
require_once "../../includes/comum.php";
require_once "$BASE_DIR/classes/MatrizCurricularProposta.php";

$siglaCurso = $_POST['siglaCurso'];
$idMatrizVigente = $_POST['idMatrizVigente'];
$newMaiorPeriodo = $_POST['newMaiorPeriodo'];

$matrizProposta = MatrizCurricularProposta::obter($siglaCurso, $idMatrizVigente);

$matrizProposta->alterarTotalPeriodos($newMaiorPeriodo); 



