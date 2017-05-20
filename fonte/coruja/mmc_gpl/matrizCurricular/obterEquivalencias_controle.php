<?php
require_once "../../includes/comum.php";
require_once "$BASE_DIR/classes/ComponenteCurricularProposto.php";

$siglaCurso = $_POST['siglaCurso'];
$idMatriz = $_POST['idMatrizVigente'];
$siglaDisciplina = $_POST['siglaDisciplina'];

$ccp = ComponenteCurricularProposto::obterComponeteCurricular($siglaCurso, $idMatriz, $siglaDisciplina);
$equivalencias = $ccp->obterEquivalencias();

echo json_encode($equivalencias);

