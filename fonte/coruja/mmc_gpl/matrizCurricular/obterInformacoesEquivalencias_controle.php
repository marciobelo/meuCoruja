<?php
require_once "../../includes/comum.php";
require_once "$BASE_DIR/classes/MatrizCurricular.php";
require_once "$BASE_DIR/classes/ComponenteCurricular.php";

$siglaCurso = $_POST['siglaCurso'];
$idMatriz = $_POST['idMatrizAntiga'];
$siglaDisciplina = (isset($_POST['siglaDisciplina'])) ? $_POST['siglaDisciplina'] : null;

$matriz = MatrizCurricular::obterMatrizCurricular($siglaCurso, $idMatriz);

if (is_null($siglaDisciplina)) {
    $componentesCurriculares = $matriz->obterComponentesCurriculares();
} else {
    $componentesCurriculares[] = ComponenteCurricular::obterComponenteCurricular($siglaCurso, $idMatriz, $siglaDisciplina);
}

$infosEquivalencias = array();
$infosCc = array();

foreach ($componentesCurriculares as $cc) {
    $informacoes = $cc->obterInformacoesEquivalenciasPropostas();
    $infosCc = $informacoes['informacoesCc'];

    if (isset($informacoes['informacoesEquivalencias'])) {
        $infosEquivalencias['comEquivalencia'][] = $infosCc;
        foreach ($informacoes['informacoesEquivalencias'] as $ccp) {
            $infosEquivalencias['informacoesEquivalencias'][$cc->getSiglaDisciplina()][$ccp->getSiglaDisciplina()]['siglaDisciplina'] = $ccp->getSiglaDisciplina();
            $infosEquivalencias['informacoesEquivalencias'][$cc->getSiglaDisciplina()][$ccp->getSiglaDisciplina()]['cargaHoraria'] = $ccp->getCargaHoraria();
            $infosEquivalencias['informacoesEquivalencias'][$cc->getSiglaDisciplina()][$ccp->getSiglaDisciplina()]['creditos'] = $ccp->getCreditos();
        }

    } else {
        $infosEquivalencias['semEquivalencia'][] = $infosCc;
    }
}

echo json_encode($infosEquivalencias);
