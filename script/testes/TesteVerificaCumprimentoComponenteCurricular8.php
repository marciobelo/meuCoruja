<?php
    require "../includes/comum.php";
    require_once "$BASE_DIR/classes/ComponenteCurricular.php";
    require_once "$BASE_DIR/classes/MatriculaAluno.php";

    $matriculaAluno = MatriculaAluno::obterMatriculaAluno("1328");
    $componenteCurricular=ComponenteCurricular::obterComponenteCurricular("TASI", 4, "TRI");
    $quitacao=$componenteCurricular->obterQuitacao($matriculaAluno);
    if( ($quitacao == null) || $quitacao->getMediaFinal() != 9.3 || $quitacao->getCreditos() != 2) {
        echo "ERRO!";
    } else {
        echo "SUCESSO!";
    }
?>

