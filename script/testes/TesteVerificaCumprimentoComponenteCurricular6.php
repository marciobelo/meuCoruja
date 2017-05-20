<?php
    require "../includes/comum.php";
    require_once "$BASE_DIR/classes/ComponenteCurricular.php";
    require_once "$BASE_DIR/classes/MatriculaAluno.php";

    $matriculaAluno = MatriculaAluno::obterMatriculaAluno("1328");
    $componenteCurricular=ComponenteCurricular::obterComponenteCurricular("TASI", 4, "SPB");
    $quitacao=$componenteCurricular->obterQuitacao($matriculaAluno);
    if( $quitacao != null ) {
        echo "ERRO!";
    } else {
        echo "SUCESSO!";
    }
?>
