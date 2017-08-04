<?php
    $BASE_DIR = __DIR__ . "/..";
    require_once("$BASE_DIR/config.php");
    require_once("$BASE_DIR/meu_coruja/valida_sessao.php");
    require_once("$BASE_DIR/classes/Aluno.php");
    require_once("$BASE_DIR/classes/MatriculaAluno.php");
    require_once("$BASE_DIR/classes/MatrizCurricular.php");
    require_once("$BASE_DIR/classes/Curso.php");
    
    $usuario = $_SESSION["usuario"];
    $idPessoa = $usuario->getIdPessoa();
            
    $numMatriculaAluno = $usuario->getNomeAcesso();
    $matriculaAluno = MatriculaAluno::obterMatriculaAluno( $numMatriculaAluno);
    $mc = $matriculaAluno->getMatrizCurricular();
    $curso = $mc->getCurso();
    $aluno = Aluno::getAlunoByIdPessoa( $idPessoa);
    
    $componenteCurricular = ComponenteCurricular::obterComponenteCurricular($curso->getSiglaCurso(), $mc->getIdMatriz(), "AL1");
    
    var_dump($listaPeriodo = PeriodoLetivo::obterPeriodosLetivoPorSiglaCurso($curso->getSiglaCurso()));
    
    var_dump($aloca = Aloca::getListAlocaByIdTurma("74"));
    var_dump(TempoSemanal::obterListaTempoSemanalPorDiaSemana($curso->getSiglaCurso(), "SEG"));
    var_dump(TempoSemanal::obterTurmasByTempoSemanal($numMatriculaAluno, "EXC", "30", "20"));
    //("Não existem período letivos disponíveis na data atual do curso)
    //$periodoLetivoAtual = PeriodoLetivo::obterPeriodoLetivoAtual($curso->getSiglaCurso());
    
    
    
    //var_dump(Turma::obterTurmasLiberadasOuConfirmadas($curso->getSiglaCurso(), "20"));
    
    
    
?>