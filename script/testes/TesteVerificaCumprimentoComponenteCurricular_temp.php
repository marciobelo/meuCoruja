<?php
    require "../includes/comum.php";
    require_once "$BASE_DIR/classes/ComponenteCurricular.php";
    require_once "$BASE_DIR/classes/MatriculaAluno.php";
    require_once "$BASE_DIR/classes/MatrizCurricular.php";

//    $matriculaAluno = MatriculaAluno::obterMatriculaAluno("0223924010");
//    $componenteCurricular=ComponenteCurricular::obterComponenteCurricular("TASI", 4, "OO2");
//    $quitacao=$componenteCurricular->obterQuitacao($matriculaAluno);
//    if( $quitacao == null ) {
//        echo "n�o encontrou equival�ncia";
//    } else {
//        echo "Media final: " . $quitacao->getMediaFinal() . "<br/>";
//        echo "Cr�ditos: " . $quitacao->getCreditos() . "<br/>";
//    }

    $matriz = MatrizCurricular::obterMatrizCurricular('TASI',5);
    $qtde = $matriz->obterQuantidadePeriodos();
    echo $qtde;

?>

