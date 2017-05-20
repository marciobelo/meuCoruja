<?php

require_once "../../../includes/comum.php";
require_once "$BASE_DIR/classes/MatrizCurricular.php";
require_once "$BASE_DIR/classes/MatrizCurricularProposta.php";
require_once "$BASE_DIR/classes/Curso.php";
require_once "$BASE_DIR/classes/ComponenteCurricular.php";
require_once "$BASE_DIR/classes/ComponenteCurricularProposto.php";

$cursos = Curso::obterListaCurso();
$matrizePropostasExistentes = array();
foreach ($cursos as $key => $curso) {
    $siglaCurso = $curso->getSiglaCurso();
    $matrizesPorSiglaCurso[$siglaCurso] = MatrizCurricular::obterListaMatrizCurricularPorSiglaCurso($siglaCurso);
    
    if (MatrizCurricularProposta::obterPorSiglaCuros($siglaCurso)) {
        $matrizePropostasExistentes[] = $siglaCurso;
    }
}

require_once "$BASE_DIR/mmc_gpl/matrizCurricular/imprimirMatriz/imprimirMatrizForm.php";



