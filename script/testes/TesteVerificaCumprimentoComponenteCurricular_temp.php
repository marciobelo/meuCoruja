<?php
    require "../includes/comum.php";
    require_once "$BASE_DIR/classes/ComponenteCurricular.php";
    require_once "$BASE_DIR/classes/MatriculaAluno.php";
    require_once "$BASE_DIR/classes/MatrizCurricular.php";

//    $matriculaAluno = MatriculaAluno::obterMatriculaAluno("0223924010");
//    $componenteCurricular=ComponenteCurricular::obterComponenteCurricular("TASI", 4, "OO2");
//    $quitacao=$componenteCurricular->obterQuitacao($matriculaAluno);
//    if( $quitacao == null ) {
//        echo "não encontrou equivalência";
//    } else {
//        echo "Media final: " . $quitacao->getMediaFinal() . "<br/>";
//        echo "Créditos: " . $quitacao->getCreditos() . "<br/>";
//    }

    $matriz = MatrizCurricular::obterMatrizCurricular('TASI',5);
    $qtde = $matriz->obterQuantidadePeriodos();
    echo $qtde;

?>

