<?php

    require_once "../../includes/comum.php";
    require_once "$BASE_DIR/classes/BD.php";
    require_once "$BASE_DIR/classes/MatrizCurricular.php";

    $siglaCurso = trim($_POST["siglaCurso"]);
    $idMatrizVigente = (int)$_POST["idMatrizVigente"];
    
    $matrizVigente = MatrizCurricular::obterMatrizCurricular($siglaCurso, $idMatrizVigente);

    echo(Util::dataSQLParaBr($matrizVigente->getDataInicioVigencia())); 
    
    exit;
