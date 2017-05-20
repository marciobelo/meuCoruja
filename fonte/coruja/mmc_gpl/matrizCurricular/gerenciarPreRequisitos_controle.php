<?php

require_once "../../includes/comum.php";
require_once "$BASE_DIR/classes/ComponenteCurricularProposto.php";
require_once "$BASE_DIR/classes/MatrizCurricularProposta.php";

$idMatriz = $_POST['idMatriz'];
$siglaCurso = $_POST['siglaCurso'];
$acao = $_POST['acao'];

$matrizProposta = MatrizCurricularProposta::obter($siglaCurso, $idMatriz);
$ccps = $matrizProposta->obterComponentes();

if ($acao === 'obter') {
    $preRequisitos = array();

    foreach($ccps as $ccp) {
        if ($ccp->getPeriodo() < 1) {
            continue;
        }

        $preRequisitosCcp = $ccp->obterPreRequisitos();

        if (!empty($preRequisitosCcp)) {
            foreach($preRequisitosCcp as $preRequisito) {
                $preRequisitos[$ccp->getSiglaDisciplina()][] = $preRequisito->getSiglaDisciplina();
            }
        }
    }

    echo json_encode($preRequisitos);
} else if ($acao === 'editar') {
    $periodo = $_POST['periodo'];
    $siglaDisciplina = $_POST['siglaDisciplina'];

    foreach ($ccps as $ccp) {
        if ((int)$ccp->getPeriodo() > (int)$periodo) {
            continue;
        }

        $preRequisitos = $ccp->obterPreRequisitos();
        foreach($preRequisitos as $preRequisito) {
            $siglasPreRequisitos[] = $preRequisito->getSiglaDisciplina();
        }
        
        if (in_array($siglaDisciplina, $siglasPreRequisitos)) {
            $pos = array_search($siglaDisciplina, $siglasPreRequisitos);
            unset($siglasPreRequisitos[$pos]);
            $ccp->limparPreRequisitos();
            $ccp->definirPreRequisitos($siglasPreRequisitos);
        }
    }
}
