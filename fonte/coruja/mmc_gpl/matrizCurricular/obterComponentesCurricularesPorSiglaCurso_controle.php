<?php
require_once "../../includes/comum.php";
require_once "$BASE_DIR/classes/MatrizCurricular.php";

$siglaCurso = $_POST['siglaCurso'];
$idMatrizVigente = $_POST['idMatrizVigente'];

$matrizCurricular = MatrizCurricular::obterMatrizCurricular($siglaCurso, $idMatrizVigente);

$componenteCurriculares = $matrizCurricular->obterComponentesCurriculares();

$componentesCurricularesToJson = array();
foreach ($componenteCurriculares as $key => $componenteCurricular) {            
      $componentesCurricularesToJson[$key]['siglaCurso'] = utf8_encode($componenteCurricular->getSiglaCurso());
      $componentesCurricularesToJson[$key]['idMatriz'] = utf8_encode($componenteCurricular->getIdMatriz());
      $componentesCurricularesToJson[$key]['siglaDisciplina'] = utf8_encode($componenteCurricular->getSiglaDisciplina());
      $componentesCurricularesToJson[$key]['nomeDisciplina'] = utf8_encode($componenteCurricular->getNomeDisciplina());
      $componentesCurricularesToJson[$key]['creditos'] = utf8_encode($componenteCurricular->getCreditos());
      $componentesCurricularesToJson[$key]['cargaHoraria'] = utf8_encode($componenteCurricular->getCargaHoraria());
      $componentesCurricularesToJson[$key]['periodo'] = utf8_encode($componenteCurricular->getPeriodo());
      $componentesCurricularesToJson[$key]['tipoComponenteCurricular'] = utf8_encode($componenteCurricular->getTipoComponenteCurricular());
}

echo json_encode($componentesCurricularesToJson);
