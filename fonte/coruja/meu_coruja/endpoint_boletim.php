<?php
    
    $BASE_DIR = __DIR__ . "/..";
    require_once("$BASE_DIR/config.php");
    require_once("$BASE_DIR/meu_coruja/valida_sessao.php");
    require_once("$BASE_DIR/classes/MatriculaAluno.php");
    
    $usuario = $_SESSION["usuario"];
    $numMatriculaAluno = $usuario->getNomeAcesso();
    
    $ma = MatriculaAluno::obterMatriculaAluno($numMatriculaAluno);
    
    $inscricoes = $ma->obterInscricoesCursando();
    $boletim = array();
    
    //var_dump($inscricoes);
    
    foreach($inscricoes as $inscricao)
    {
        $linha = array();
        $avaliacoes = array();
        $dadosUsuario = new stdClass();
        
        $dadosUsuario->siglaDisciplina = $inscricao->getTurma()->getSiglaDisciplina();
        $dadosUsuario->nomeProfessor = $inscricao->getTurma()->getProfessor()->getNome();
        $dadosUsuario->mediaFinal = $inscricao->getMediaFinal();
        $dadosUsuario->faltas = $inscricao->getTotalFaltas();
        $itens = $inscricao->obterItensCriterioAvaliacaoInscricaoNota();
        
        
        foreach($itens as $item)
        {
            
            $avaliacao = new stdClass();
            $avaliacao->nota = $item->getNota();
            $avaliacao->rotulo = $item->getItemCriterioAvaliacao()->getRotulo();
            
            array_push($avaliacoes,$avaliacao);
        }
        
        array_push($linha, $dadosUsuario);
        array_push($linha, $avaliacoes);
        
        
        array_push($boletim, $linha);
        
    }
    
    //var_dump($boletim);

    $jsonBoletim = json_encode($boletim,JSON_UNESCAPED_UNICODE);
    echo($jsonBoletim);
?>