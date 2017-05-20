<?php
    require "../includes/comum.php";
    require_once "$BASE_DIR/classes/ComponenteCurricular.php";
    require_once "$BASE_DIR/classes/MatriculaAluno.php";

    $matriculaAluno = MatriculaAluno::obterMatriculaAluno("1328");
    $componenteCurricular=ComponenteCurricular::obterComponenteCurricular("TASI", 4, "AL1");
    $quitacao=$componenteCurricular->obterQuitacao($matriculaAluno);
    if( ($quitacao == null) || $quitacao->getMediaFinal() != 5.5 || $quitacao->getCreditos() != 6) {
        echo $quitacao->getMediaFinal() . "<br/>";
        echo $quitacao->getCreditos() . "<br/>";
        echo "ERRO!";
    } else {
        echo "SUCESSO!";
    }
?>
