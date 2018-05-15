<?php

require_once "../../includes/comum.php";
require_once "$BASE_DIR/classes/ComponenteCurricularProposto.php";
require_once "$BASE_DIR/classes/MatrizCurricularProposta.php";

$formData = $_POST['data'];
$action = $_POST['action'];

$preRequisitos = array();
$equivalencias = array();
if($action != 'excluir') {
    foreach($formData as $data) {
        if ($data['name'] === 'preRequisitos') {
            $preRequisitos[] = $data['value'];
        } elseif ($data['name'] === 'equivalencias') {
            $equivalencias[] = $data['value'];
        } else {
            $infos[$data['name']] = $data['value'];
        }   
    }
    
    if (strLen($infos['siglaDisciplina']) < 1) {
        echo 'siglaInvalida';
        exit;
    }
    
    $matrizProposta = MatrizCurricularProposta::obter($infos['siglaCursoMatrizProposta'], (int)$infos['idMatrizVigente']);
    if ($action === 'criar') {
        if(!$login->temPermissao("UC11.01.02.01")) {
            echo "semPermissao";
            exit;
        }
        
        $qtdeComponentesPeriodo = $matrizProposta->obterQtdeComponentesPeriodo((int)$infos['periodo']);
        
        if ((int)$qtdeComponentesPeriodo === 12) {
            echo 'periodoCheio';
            exit;
        }
        
        $ccp = ComponenteCurricularProposto::obterComponeteCurricular($infos['siglaCursoMatrizProposta'], $infos['idMatrizVigente'], strtoupper($infos['siglaDisciplina']));
        if(!is_null($ccp)) {
            echo 'repetido';
            exit;
        }
        
        ComponenteCurricularProposto::criar($infos['siglaCursoMatrizProposta'], (int)$infos['idMatrizVigente'], strtoupper($infos['siglaDisciplina']), utf8_decode($infos['nomeDisciplina']), 
                                             (int)$infos['creditos'], (int)$infos['cargaHoraria'], (int)$infos['periodo'], utf8_decode($infos['tipoComponenteCurricular']), $infos['posicaoPeriodo']);
        
    } else {
        
        $qtdeComponentesPeriodo = $matrizProposta->obterQtdeComponentesPeriodo((int)$infos['periodo']);
        if ((int)$infos['oldPeriodo'] !== (int)$infos['periodo'] && (int)$qtdeComponentesPeriodo === 12) {
            echo 'periodoCheio';
            exit;
        }
        
        $ccp = ComponenteCurricularProposto::obterComponeteCurricular($infos['siglaCursoMatrizProposta'], $infos['idMatrizVigente'], strtoupper($infos['siglaDisciplina']));
        if(!is_null($ccp)) {
            if(strtoupper($infos['siglaDisciplina']) !== strtoupper($infos['oldSiglaDisciplina'])) {
                echo 'repetido';
                exit;
            }
        }
        
        $ccp = ComponenteCurricularProposto::obterComponeteCurricular($infos['siglaCursoMatrizProposta'], $infos['idMatrizVigente'], strtoupper($infos['oldSiglaDisciplina']));
        $ccp->editar($infos['oldSiglaDisciplina'], $infos['siglaCursoMatrizProposta'], (int)$infos['idMatrizVigente'], strtoupper($infos['siglaDisciplina']), utf8_decode($infos['nomeDisciplina']), (int)$infos['creditos'], 
                                                (int)$infos['cargaHoraria'], (int)$infos['periodo'], utf8_decode($infos['tipoComponenteCurricular']));
        
        $ccp->limparPreRequisitos();
        $ccp->setSiglaDisciplina(strtoupper($infos['siglaDisciplina']));
        
        if ((int)$infos['oldPeriodo'] < (int)$infos['periodo'] ) {
            $ccp->setPeriodo($infos['periodo']);
            $ccp->verificaPosicaoComoPrerequisito();
        }
    }
   
    $ccp = (!is_null($ccp)) ? $ccp : ComponenteCurricularProposto::obterComponeteCurricular($infos['siglaCursoMatrizProposta'], $infos['idMatrizVigente'], strtoupper($infos['siglaDisciplina']));
    $ccp->definirEquivalencias($equivalencias);
    $ccp->definirPreRequisitos($preRequisitos);
} else {
    $siglaCurso = $_POST['siglaCurso'];
    $idMatriz = $_POST['idMatrizEquivalente'];
    $siglaDisciplina = strtoupper($_POST['siglaDisciplina']);
    
    if(!$login->temPermissao("UC11.01.02.03")) {
        echo "semPermissao";
        exit;
    }
    
    $ccp = ComponenteCurricularProposto::obterComponeteCurricular($siglaCurso, $idMatriz, $siglaDisciplina);
    $ccp->deletar();
}
