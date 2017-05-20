<?php
    require "../includes/comum.php";
    require_once "$BASE_DIR/classes/MatrizCurricular.php";

    $matriz = MatrizCurricular::obterMatrizCurricular('TASI',4);

    $arrayCC = $matriz->obterComponentesCurriculares();
    foreach($arrayCC as $cc) {
        echo $cc->getSiglaCurso() . " - " . $cc->getIdMatriz() . " - " . $cc->getSiglaDisciplina() . "<br/>";
    }
?>
