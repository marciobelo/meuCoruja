<?php

require_once "../../includes/comum.php";
require_once "$BASE_DIR/classes/ComponenteCurricularProposto.php";
require_once "$BASE_DIR/classes/MatrizCurricularProposta.php";

$siglaCurso = $_POST['siglaCurso'];
$siglaDisciplina = $_POST['siglaDisciplina'];
$idMatriz = $_POST['idMatriz'];

$ccp = ComponenteCurricularProposto::obterComponeteCurricular($siglaCurso, $idMatriz, $siglaDisciplina);

//dados do componente proposto
$dados['ccp']['siglaDisciplina'] = $ccp->getSiglaDisciplina();
$dados['ccp']['nomeDisciplina'] = utf8_encode($ccp->getNomeDisciplina());
$dados['ccp']['cargaHoraria'] = $ccp->getCargaHoraria();
$dados['ccp']['creditos'] = $ccp->getCreditos();
$dados['ccp']['periodo'] = $ccp->getPeriodo();
$dados['ccp']['tipoComponenteCurricular'] = utf8_decode($ccp->getTipoComponenteCurricular());
$dados['ccp']['posicaoPeriodo'] = $ccp->getPosicaoPeriodo();

//equivalencias
$equivalencias = $ccp->obterEquivalencias();
$dados['equivalencias'] = $equivalencias;

//todos os possiveis prerequisitos
$matrizProposta = MatrizCurricularProposta::obter($siglaCurso, $idMatriz);
$possiveisPreRequisitos = $matrizProposta->obterComponentes();

foreach($possiveisPreRequisitos as $key => $possivelPreRequisito) { 
    if ($possivelPreRequisito->getSiglaDisciplina() === $dados['ccp']['siglaDisciplina']) {
        continue;
    }
    
    $dados['possiveisPreRequisitos'][$key]['siglaDisciplina'] = $possivelPreRequisito->getSiglaDisciplina();
    $dados['possiveisPreRequisitos'][$key]['periodo'] = $possivelPreRequisito->getPeriodo();
}

//pre requisitos
foreach($ccp->obterPreRequisitos() as $preRequisito) {
    $dados['preRequisitos'][] = $preRequisito->getSiglaDisciplina();
} 


echo json_encode($dados);
exit;