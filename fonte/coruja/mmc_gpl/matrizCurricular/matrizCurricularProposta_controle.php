<?php

require_once "../../includes/comum.php";
require_once "$BASE_DIR/classes/MatrizCurricular.php";
require_once "$BASE_DIR/classes/MatrizCurricularProposta.php";
require_once "$BASE_DIR/classes/Curso.php";
require_once "$BASE_DIR/classes/ComponenteCurricular.php";
require_once "$BASE_DIR/classes/ComponenteCurricularProposto.php";

$acao = $_POST['acao'];
$siglaCurso = $_POST['siglaCurso'];
$idMatrizVigente = $_POST['idMatriz'];

if ($acao !== 'excluir') {
    $matrizAntiga = MatrizCurricular::obterMatrizCurricular($siglaCurso, $idMatrizVigente);
    $todosComponentesCurricularesAntigos = $matrizAntiga->obterComponentesCurriculares();
    $matrizProposta = MatrizCurricularProposta::obter($siglaCurso, $idMatrizVigente);
    $componenteCurricularesAntigosToView = array();
    
    //criar
    if (is_null($matrizProposta)) {
        if(!$login->temPermissao("UC11.01.01")) {
            require_once "../sem_permissao.php";
            exit;
        }
        
        MatrizCurricularProposta::criar($siglaCurso, $idMatrizVigente);
        $matrizProposta = MatrizCurricularProposta::obter($siglaCurso, $idMatrizVigente);
        foreach ($todosComponentesCurricularesAntigos as $key => $cc) {
            $infosComponente['informacoesCc']['siglaDisciplina'] = $cc->getSiglaDisciplina();
            $infosComponente['informacoesCc']['corDaClasse'] = 'vermelho';
            $componenteCurricularesAntigosToView[] = $infosComponente;
        }
        
        $con = BD::conectar();
        $strLog = "Criada a Matriz Curricular Proposta do Curso " . $siglaCurso;
        $login->incluirLog("UC11.01.01", $strLog, $con);
    //editar    
    } else {
        if(!$login->temPermissao("UC11.01.02")) {
            require_once "../sem_permissao.php";
            exit;
        }
        
        //possiveis Pre Requisitos
        $possiveisPreRequisitos = array();
        $componentesCurricularesPropostosToView = $matrizProposta->obterComponentes();
        foreach($componentesCurricularesPropostosToView as $key => $ccp) {
            $possiveisPreRequisitos[$key]['siglaDisciplina'] = $ccp->getSiglaDisciplina();
            $possiveisPreRequisitos[$key]['periodo'] = $ccp->getPeriodo();
        }
        
        //informacoes dos componentes anteriores com suas Equivalencias
        $componentesCurricularesAntigosComEquivalencia = array();
        $componentesCurricularesAntiosSemEquivalencia = array();

        foreach ($todosComponentesCurricularesAntigos as $key => $cc) {
            $informacoesEquivalencia = $cc->obterInformacoesEquivalenciasPropostas();
            
            $cor = 'vermelho';
            if ($informacoesEquivalencia['informacoesCc']['estadoEquivalencia'] === 'total') {
                $cor = 'verde';
            } else if ($informacoesEquivalencia['informacoesCc']['estadoEquivalencia'] === 'parcial') {
                $cor = 'amarelo';
            }

            $informacoesEquivalencia['informacoesCc']['corDaClasse'] = $cor;
            $componenteCurricularesAntigosToView[] = $informacoesEquivalencia;
        }
        
         
        $con = BD::conectar();
        $strLog = "Editada a Matriz Curricular Proposta do Curso " . $siglaCurso;
        $login->incluirLog("UC11.01.02", $strLog, $con);
    }
    
    $totalPeriodos = $matrizProposta->getTotalPeriodos();
    $decimo = utf8_encode('Décimo'); 
    $tituloPeriodos = array('1'=>'Primeiro', 
                      '2'=>'Segundo',
                      '3'=>'Terceiro',
                      '4'=>'Quarto',
                      '5'=>'Quinto',
                      '6'=>'Sexto',
                      '7'=>'Setimo',
                      '8'=>'Oitavo',
                      '9'=>'Nono',
                      '10'=> $decimo);

    $periodosDaMatriz = array('3'=>'Tr&ecirc;s',
                              '4'=>'Quatro',
                              '5'=>'Cinco',
                              '6'=>'Seis',
                              '7'=>'Sete',
                              '8'=>'Oito',
                              '9'=>'Nove',
                              '10'=>'Dez');

    //ordenando Componentes no periodo
    $componentesPorPeriodos = array();
    foreach ($componentesCurricularesPropostosToView as $ccp) {
        $componentesPorPeriodos[$tituloPeriodos[$ccp->getPeriodo()]][] = $ccp; 
    }
    
    foreach($componentesPorPeriodos as $key => $compoenntesDoPeriodo) {
        $posicoesNoperiodo = array();
        foreach($compoenntesDoPeriodo as $componete) {
            $posicoesNoperiodo[] = $componete->getPosicaoPeriodo();
            
        }

        array_multisort($posicoesNoperiodo, $compoenntesDoPeriodo);
        $componentesPorPeriodos[$key] = $compoenntesDoPeriodo;
    }
    //fim da ordnacao

require_once "$BASE_DIR/mmc_gpl/matrizCurricular/matrizCurricularPropostaForm.php";
    
} else {
    if(!$login->temPermissao("UC11.01.03")) {
        require_once "../sem_permissao.php";
        exit;
    }
    
    $matrizProposta = MatrizCurricularProposta::obter($siglaCurso, $idMatrizVigente);
    $matrizProposta->deletar();
    
    $con = BD::conectar();
    $strLog = "Exclu&iacute;da a Matriz Curricular Proposta do Curso " . $siglaCurso;
    $login->incluirLog("UC11.01.03", $strLog, $con);
    
    Header("Location: listaMatrizCurricularProposta_controle.php");
}


