<?php
    require "../includes/comum.php";
    require_once "$BASE_DIR/classes/ComponenteCurricular.php";
    require_once "$BASE_DIR/classes/MatriculaAluno.php";

    $matriculaAluno = MatriculaAluno::obterMatriculaAluno("1328");
    $componenteCurricular=ComponenteCurricular::obterComponenteCurricular("TASI", 4, "OO1");
    $quitacao=$componenteCurricular->obterQuitacao($matriculaAluno);
    if($quitacao->getMediaFinal() != 6.0 || $quitacao->getCreditos() != 6) {
        echo "ERRO!";
    } else {
        echo "SUCESSO!";
    }
?>
