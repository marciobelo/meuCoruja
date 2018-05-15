<?php
    require_once "../../includes/comum.php";

    $acao = filter_input( INPUT_GET, "acao", FILTER_SANITIZE_STRING);
    if( $acao === NULL)
    {
        $acao = filter_input( INPUT_POST, "acao", FILTER_SANITIZE_STRING);
    }
    if( isset($_SESSION["siglaCursoFiltro"]))
    {
        $siglaCursoFiltro = $_SESSION["siglaCursoFiltro"];
    }
    else
    {
        $siglaCursoFiltro = "";
    }

    if( $acao === "selecionarCurso") 
    {
        require_once "$BASE_DIR/classes/Curso.php";

        // Verifica Permissao
        if(!$login->temPermissao($MANTER_SITUACAO_MATRICULAS)) {
            require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
        }
        
        $cursos = Curso::obterCursosOrdemPorSigla();
        require_once "$BASE_DIR/interno/manter_situacao_matricula/telaSelecionarCurso.php";

    } 
    else if($acao === "exibirSituacoesMatriculasCurso") 
    {
        require_once "$BASE_DIR/classes/Curso.php";
        require_once "$BASE_DIR/classes/MatriculaAluno.php";
        require_once "$BASE_DIR/classes/PeriodoLetivo.php";

        // Verifica Permissao
        if(!$login->temPermissao($MANTER_SITUACAO_MATRICULAS)) {
            require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
        }
        
        // Parâmetros da ação
        $siglaCurso = $_REQUEST["siglaCurso"];
        try {
            $plUltimo = PeriodoLetivo::obterPeriodoLetivoVigenteMaisAntigo($siglaCurso);
        } catch(RuntimeException $e) { // volta a tela de seleção de curso com msg de erro
            $msgsErro = array();
            $msgsErro[] = $e->getMessage();
            $cursos = Curso::obterCursosOrdemPorSigla();
            require_once "$BASE_DIR/interno/manter_situacao_matricula/telaSelecionarCurso.php";
            exit;
        }

        $curso = Curso::obterCurso($siglaCurso);
        $nomeCurso=$curso->getSiglaCurso() . " - " . $curso->getNomeCurso();
        $totalCURSANDO=MatriculaAluno::obterTotalPorSituacao($siglaCurso,"CURSANDO");
        $totalTRANCADO=MatriculaAluno::obterTotalPorSituacao($siglaCurso,"TRANCADO");
        $totalEVADIDO=MatriculaAluno::obterTotalPorSituacao($siglaCurso,"EVADIDO");
        $totalCONCLUIDO=MatriculaAluno::obterTotalPorSituacao($siglaCurso,"CONCLUIDO");
        $totalDESISTENTE=MatriculaAluno::obterTotalPorSituacao($siglaCurso,"DESISTENTE");
        $totalDESLIGADO=MatriculaAluno::obterTotalPorSituacao($siglaCurso,"DESLIGADO");

        $siglaPeriodoLetivoVigente=$plUltimo->getSiglaPeriodoLetivo();
        $dataInicioPeriodoLetivoVigente = $plUltimo->getDataInicio();
        $dataFimPeriodoLetivoVigente = $plUltimo->getDataFim();
        $dataLimiteInscricaoDisciplina = $plUltimo->obterDataFimSolicInscrTurma();
        $dataLimitePedidoTrancamento = $plUltimo->obterDataFimTrancMatricula();
        $matriculasDesatualizadas = $curso->obterMatriculasDesatualizadas($plUltimo->getDataInicio());

        require_once "$BASE_DIR/interno/manter_situacao_matricula/telaSituacaoMatriculasPorCurso.php";

    } 
    else if($acao === "verHistoricoMatricula") 
    {
        require_once "$BASE_DIR/classes/MatriculaAluno.php";
        require_once "$BASE_DIR/classes/Curso.php";
        require_once "$BASE_DIR/classes/Aluno.php";

        // Verifica Permissao
        if(!$login->temPermissao($MANTER_SITUACAO_MATRICULAS)) {
            require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
        }
        
        $matriculaAluno = $_REQUEST["matriculaAluno"];
        $ma = MatriculaAluno::obterMatriculaAluno($matriculaAluno);
        $dataMatricula = $ma->getDataMatricula();
        $curso = Curso::obterCurso($ma->getSiglaCurso());
        $nomeCurso=$curso->getSiglaCurso() . " - " . $curso->getNomeCurso();
        $siglaCurso=$curso->getSiglaCurso();
        $situacaoMatricula=$ma->getSituacaoMatricula();
        $aluno = Aluno::getAlunoByIdPessoa($ma->getIdPessoa());
        $nomeAluno=$aluno->getNome();
        $listaSituacaoMatriculaHistorico = $ma->obterListaSituacaoMatriculaHistorico();

        require_once "$BASE_DIR/interno/manter_situacao_matricula/telaVerHistoricoMatricula.php";

    } 
    else if($acao === "exibirConfirmacaoReativarMatricula") 
    {
        
        require_once "$BASE_DIR/classes/MatriculaAluno.php";
        require_once "$BASE_DIR/classes/Curso.php";
        require_once "$BASE_DIR/classes/Aluno.php";

        // Verifica Permissao
        if(!$login->temPermissao($MANTER_SITUACAO_MATRICULAS_REATIVAR)) {
            require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
        }

        $matriculaAluno = $_REQUEST["matriculaAluno"];
        $ma = MatriculaAluno::obterMatriculaAluno($matriculaAluno);
        $dataMatricula = $ma->getDataMatricula();
        $curso = Curso::obterCurso($ma->getSiglaCurso());
        $nomeCurso=$curso->getSiglaCurso() . " - " . $curso->getNomeCurso();
        $siglaCurso=$curso->getSiglaCurso();
        $situacaoMatricula=$ma->getSituacaoMatricula();
        $aluno = Aluno::getAlunoByIdPessoa($ma->getIdPessoa());
        $nomeAluno=$aluno->getNome();
        $listaSituacaoMatriculaHistorico = $ma->obterListaSituacaoMatriculaHistorico();

        require_once "$BASE_DIR/interno/manter_situacao_matricula/telaReativarMatricula.php";

    } 
    else if($acao === "reativarMatricula") 
    {
        
        require_once "$BASE_DIR/classes/MatriculaAluno.php";
        require_once "$BASE_DIR/classes/Aluno.php";

        // Verifica Permissao
        if(!$login->temPermissao($MANTER_SITUACAO_MATRICULAS_REATIVAR)) {
            require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
        }
        
        $matriculaAluno = $_REQUEST["matriculaAluno"];
        $texto = $_REQUEST["texto"];
        $ma = MatriculaAluno::obterMatriculaAluno($matriculaAluno);
        $aluno = $ma->getAluno();
        $nomeAluno=$aluno->getNome();

        $siglaCurso = $ma->getSiglaCurso();
        $con = BD::conectar();
        try {
            mysql_query("BEGIN", $con); // Inicia transação
            $result = $ma->reativarMatricula($texto,$con);

            $strLog = "Alterada a situação de matrícula do aluno $nomeAluno, matrícula $matriculaAluno, curso $siglaCurso, para CURSANDO,
            com a observação $texto";
            $login->incluirLog($MANTER_SITUACAO_MATRICULAS_REATIVAR,$strLog,$con);

            mysql_query("COMMIT", $con);
        } catch(Exception $ex) {
            mysql_query("ROLLBACK", $con);
            $msgsErro=array();
            array_push($msgsErro, $ex->getMessage());
            require_once "$BASE_DIR/interno/manter_situacao_matricula/telaReativarMatricula.php";
            exit;
        }

        // Exibe mensagem de sucesso e volta para a tela de administração de matrículas
        echo "<html><head><script>window.alert('Matrícula reaberta com sucesso.');</script><meta http-equiv='refresh' content='0;URL=/coruja/interno/manter_situacao_matricula/manterSituacaoMatricula_controle.php?acao=exibirSituacoesMatriculasCurso&siglaCurso=$siglaCurso'/></head>";
        exit;

    } 
    else if( $acao === "exibirConfirmacaoRenovarMatricula") 
    {
        
        require_once "$BASE_DIR/classes/MatriculaAluno.php";
        require_once "$BASE_DIR/classes/Curso.php";
        require_once "$BASE_DIR/classes/Aluno.php";

        // Verifica Permissao
        if(!$login->temPermissao($MANTER_SITUACAO_MATRICULAS_REATIVAR)) {
            require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
        }

        $matriculaAluno = $_REQUEST["matriculaAluno"];
        $ma = MatriculaAluno::obterMatriculaAluno($matriculaAluno);
        $dataMatricula = $ma->getDataMatricula();
        $curso = Curso::obterCurso($ma->getSiglaCurso());
        $nomeCurso = $curso->getSiglaCurso() . " - " . $curso->getNomeCurso();
        $siglaCurso = $curso->getSiglaCurso();
        $situacaoMatricula = $ma->getSituacaoMatricula();
        $aluno = Aluno::getAlunoByIdPessoa($ma->getIdPessoa());
        $nomeAluno=$aluno->getNome();
        $listaSituacaoMatriculaHistorico = $ma->obterListaSituacaoMatriculaHistorico();

        require_once "$BASE_DIR/interno/manter_situacao_matricula/telaRenovarMatricula.php";
        
    } 
    else if( $acao === "renovarMatricula") 
    {
        require_once "$BASE_DIR/classes/MatriculaAluno.php";
        require_once "$BASE_DIR/classes/Aluno.php";

        // Verifica Permissao
        if(!$login->temPermissao($MANTER_SITUACAO_MATRICULAS_REATIVAR)) {
            require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
        }
        
        $matriculaAluno = $_REQUEST["matriculaAluno"];
        $texto = $_REQUEST["texto"];
        $ma = MatriculaAluno::obterMatriculaAluno($matriculaAluno);
        $aluno = $ma->getAluno();
        $nomeAluno=$aluno->getNome();

        $siglaCurso = $ma->getSiglaCurso();
        $con = BD::conectar();
        try {
            mysql_query("BEGIN", $con); // Inicia transação
            $result = $ma->renovarMatricula($texto, $con);

            $strLog = "Renovada a situação de matrícula do aluno $nomeAluno, matrícula $matriculaAluno, curso $siglaCurso, para CURSANDO,
            com a observação $texto";
            $login->incluirLog($MANTER_SITUACAO_MATRICULAS_RENOVAR, $strLog, $con);

            mysql_query("COMMIT", $con);
        } catch(Exception $ex) {
            mysql_query("ROLLBACK", $con);
            $msgsErro = array();
            array_push($msgsErro, $ex->getMessage());
            require_once "$BASE_DIR/interno/manter_situacao_matricula/telaRenovarMatricula.php";
            exit;
        }

        echo "<html><head><script>window.alert('Matrícula renovada com sucesso.');</script><meta http-equiv='refresh' content='0;URL=/coruja/interno/manter_situacao_matricula/manterSituacaoMatricula_controle.php?acao=exibirSituacoesMatriculasCurso&siglaCurso=$siglaCurso'/></head>";
        exit;
        
    } 
    else if( $acao === "exibirConfirmacaoTrancamentoMatricula") 
    {
        require_once "$BASE_DIR/classes/MatriculaAluno.php";
        require_once "$BASE_DIR/classes/Curso.php";
        require_once "$BASE_DIR/classes/Aluno.php";

        // Verifica Permissao
        if(!$login->temPermissao($MANTER_SITUACAO_MATRICULAS_TRANCAR)) {
            require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
        }

        $matriculaAluno = $_REQUEST["matriculaAluno"];
        $ma = MatriculaAluno::obterMatriculaAluno($matriculaAluno);
        $dataMatricula = $ma->getDataMatricula();
        $curso = Curso::obterCurso($ma->getSiglaCurso());
        $nomeCurso=$curso->getSiglaCurso() . " - " . $curso->getNomeCurso();
        $siglaCurso=$curso->getSiglaCurso();
        $situacaoMatricula=$ma->getSituacaoMatricula();
        $aluno = Aluno::getAlunoByIdPessoa($ma->getIdPessoa());
        $nomeAluno=$aluno->getNome();
        $listaSituacaoMatriculaHistorico = $ma->obterListaSituacaoMatriculaHistorico();

        require_once "$BASE_DIR/interno/manter_situacao_matricula/telaTrancarMatricula.php";
    } 
    else if( $acao === "trancarMatricula") 
    {
        require_once "$BASE_DIR/classes/MatriculaAluno.php";
        require_once "$BASE_DIR/classes/Aluno.php";

        // Verifica Permissao
        if(!$login->temPermissao($MANTER_SITUACAO_MATRICULAS_TRANCAR)) {
            require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
        }

        $matriculaAluno = $_REQUEST["matriculaAluno"];
        $texto = $_REQUEST["texto"];
        $ma = MatriculaAluno::obterMatriculaAluno($matriculaAluno);
        $aluno = $ma->getAluno();
        $nomeAluno=$aluno->getNome();
        $siglaCurso = $ma->getSiglaCurso();
        $con = BD::conectar();
        try {
            mysql_query("BEGIN", $con); // Inicia transação
            $result = $ma->trancarMatricula($texto,$con);

            $strLog = "Alterada a situação de matrícula do aluno $nomeAluno, matrícula $matriculaAluno, curso $siglaCurso, para TRANCADO,
            com a observação $texto";
            $login->incluirLog($MANTER_SITUACAO_MATRICULAS_TRANCAR,$strLog,$con);

            mysql_query("COMMIT", $con);
        } catch(Exception $ex) {
            mysql_query("ROLLBACK", $con);
            $msgsErro=array();
            array_push($msgsErro, $ex->getMessage());
            require_once "$BASE_DIR/interno/manter_situacao_matricula/telaTrancarMatricula.php";
            exit;
        }

        // Exibe mensagem de sucesso e volta para a tela de administração de matrículas
        echo "<html><head><script>window.alert('Matrícula trancada com sucesso.');</script><meta http-equiv='refresh' content='0;URL=/coruja/interno/manter_situacao_matricula/manterSituacaoMatricula_controle.php?acao=exibirSituacoesMatriculasCurso&siglaCurso=$siglaCurso'/></head>";
        exit;

    } 
    else if( $acao === "exibirConfirmacaoConcluirMatricula") 
    {
        require_once "$BASE_DIR/classes/MatriculaAluno.php";
        require_once "$BASE_DIR/classes/Curso.php";
        require_once "$BASE_DIR/classes/Aluno.php";
        require_once "$BASE_DIR/classes/MatrizCurricular.php";
        require_once "$BASE_DIR/classes/ComponenteCurricular.php";

        // Verifica Permissao
        if(!$login->temPermissao($MANTER_SITUACAO_MATRICULAS_CONCLUIR)) {
            require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
        }

        $matriculaAluno = $_REQUEST["matriculaAluno"];
        $ma = MatriculaAluno::obterMatriculaAluno($matriculaAluno);
        $dataMatricula = $ma->getDataMatricula();
        $curso = Curso::obterCurso($ma->getSiglaCurso());
        $nomeCurso=$curso->getSiglaCurso() . " - " . $curso->getNomeCurso();
        $siglaCurso=$curso->getSiglaCurso();
        $situacaoMatricula=$ma->getSituacaoMatricula();
        $aluno = Aluno::getAlunoByIdPessoa($ma->getIdPessoa());
        $nomeAluno=$aluno->getNome();
        $listaSituacaoMatriculaHistorico = $ma->obterListaSituacaoMatriculaHistorico();
        
        // Verifica se a matricula já cumpriu todos os componentes curriculares obrigatórios
        $matriz=$ma->getMatrizCurricular();
        $colCC = $matriz->obterComponentesCurriculares();
        $cumpriuCC=true;
        foreach($colCC as $cc) {
            if($cc->obterQuitacao($ma) == null) {
                $cumpriuCC=false;
                break;
            }
        }

        // verifica se está com todos os documentos entregues
        $pendenciaDocs = $ma->temPendenciaDocsEntregues();

        require_once "$BASE_DIR/interno/manter_situacao_matricula/telaConcluirMatricula.php";

    } 
    else if($acao === "concluirMatricula") 
    {
        require_once "$BASE_DIR/classes/MatriculaAluno.php";

        // Verifica Permissao
        if(!$login->temPermissao($MANTER_SITUACAO_MATRICULAS_CONCLUIR)) {
            require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
        }

        $matriculaAluno = $_REQUEST["matriculaAluno"];
        $texto = $_REQUEST["texto"];
        $dataConclusaoDia = $_REQUEST["dataConclusaoDia"];
        $dataConclusaoMes = $_REQUEST["dataConclusaoMes"];
        $dataConclusaoAno = $_REQUEST["dataConclusaoAno"];
        $ma = MatriculaAluno::obterMatriculaAluno($matriculaAluno);
        $dataMatricula = $ma->getDataMatricula();
        $aluno = $ma->getAluno();
        $nomeAluno=$aluno->getNome();
        $curso = Curso::obterCurso($ma->getSiglaCurso());
        $nomeCurso=$curso->getSiglaCurso() . " - " . $curso->getNomeCurso();
        $siglaCurso=$curso->getSiglaCurso();
        $situacaoMatricula=$ma->getSituacaoMatricula();
        $aluno = Aluno::getAlunoByIdPessoa($ma->getIdPessoa());
        $nomeAluno=$aluno->getNome();
        $listaSituacaoMatriculaHistorico = $ma->obterListaSituacaoMatriculaHistorico();
        
        // Valida campo data de emissão do rg
        if(!checkdate($dataConclusaoMes, $dataConclusaoDia, $dataConclusaoAno)) {
            $msgsErro=array();
            array_push($msgsErro, "Data de Conclusão incorreta.");
            require_once "$BASE_DIR/interno/manter_situacao_matricula/telaConcluirMatricula.php";
            exit;
        }
        $dataConclusao = $dataConclusaoAno . "-" . $dataConclusaoMes . "-" . $dataConclusaoDia;

        $con = BD::conectar();
        try {
            mysql_query("BEGIN", $con); // Inicia transação
            $result = $ma->concluirMatricula($texto,$dataConclusao,$con);

            $strLog = "Alterada a situação de matrícula do aluno $nomeAluno, matrícula $matriculaAluno, curso $siglaCurso, para CONCLUÍDO,
            com a observação $texto";
            $login->incluirLog($MANTER_SITUACAO_MATRICULAS_CONCLUIR,$strLog,$con);

            mysql_query("COMMIT", $con);
        } catch(Exception $ex) {
            mysql_query("ROLLBACK", $con);
            $msgsErro=array();
            array_push($msgsErro, $ex->getMessage());
            require_once "$BASE_DIR/interno/manter_situacao_matricula/telaConcluirMatricula.php";
            exit;
        }

        // Exibe mensagem de sucesso e volta para a tela de administração de matrículas
        echo "<html><head><script>window.alert('Matrícula concluída com sucesso.');</script><meta http-equiv='refresh' content='0;URL=/coruja/interno/manter_situacao_matricula/manterSituacaoMatricula_controle.php?acao=exibirSituacoesMatriculasCurso&siglaCurso=$siglaCurso'/></head>";
        exit;

    } 
    else if( $acao === "exibirConfirmacaoDesistirMatricula") 
    {
        require_once "$BASE_DIR/classes/MatriculaAluno.php";
        require_once "$BASE_DIR/classes/Curso.php";
        require_once "$BASE_DIR/classes/Aluno.php";

        // Verifica Permissao
        if(!$login->temPermissao($MANTER_SITUACAO_MATRICULAS_DESISTIR)) {
            require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
        }

        $matriculaAluno = $_REQUEST["matriculaAluno"];
        $ma = MatriculaAluno::obterMatriculaAluno($matriculaAluno);
        $dataMatricula = $ma->getDataMatricula();
        $curso = Curso::obterCurso($ma->getSiglaCurso());
        $nomeCurso=$curso->getSiglaCurso() . " - " . $curso->getNomeCurso();
        $siglaCurso=$curso->getSiglaCurso();
        $situacaoMatricula=$ma->getSituacaoMatricula();
        $aluno = Aluno::getAlunoByIdPessoa($ma->getIdPessoa());
        $nomeAluno=$aluno->getNome();
        $listaSituacaoMatriculaHistorico = $ma->obterListaSituacaoMatriculaHistorico();

        require_once "$BASE_DIR/interno/manter_situacao_matricula/telaDesistirMatricula.php";

    } 
    else if( $acao === "desistirMatricula") 
    {
        require_once "$BASE_DIR/classes/MatriculaAluno.php";

        // Verifica Permissao
        if(!$login->temPermissao($MANTER_SITUACAO_MATRICULAS_DESISTIR)) {
            require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
        }

        $matriculaAluno = $_REQUEST["matriculaAluno"];
        $texto = $_REQUEST["texto"];
        $ma = MatriculaAluno::obterMatriculaAluno($matriculaAluno);
        $aluno = $ma->getAluno();
        $nomeAluno=$aluno->getNome();
        $siglaCurso = $ma->getSiglaCurso();
        $con = BD::conectar();
        try {
            mysql_query("BEGIN", $con); // Inicia transação
            $result = $ma->desistirMatricula($texto,$con);

            $strLog = "Alterada a situação de matrícula do aluno $nomeAluno, matrícula $matriculaAluno, curso $siglaCurso, para DESISTENTE,
            com a observação $texto";
            $login->incluirLog($MANTER_SITUACAO_MATRICULAS_DESISTIR,$strLog,$con);

            mysql_query("COMMIT", $con);
        } catch(Exception $ex) {
            mysql_query("ROLLBACK", $con);
            $msgsErro=array();
            array_push($msgsErro, $ex->getMessage());
            require_once "$BASE_DIR/interno/manter_situacao_matricula/telaDesistirMatricula.php";
            exit;
        }

        // Exibe mensagem de sucesso e volta para a tela de administração de matrículas
        echo "<html><head><script>window.alert('Desistência da Matrícula registrada com sucesso.');</script><meta http-equiv='refresh' content='0;URL=/coruja/interno/manter_situacao_matricula/manterSituacaoMatricula_controle.php?acao=exibirSituacoesMatriculasCurso&siglaCurso=$siglaCurso'/></head>";
        exit;

    } 
    else if( $acao === "exibirConfirmacaoDesligarMatricula") 
    {
        require_once "$BASE_DIR/classes/MatriculaAluno.php";
        require_once "$BASE_DIR/classes/Curso.php";
        require_once "$BASE_DIR/classes/Aluno.php";

        // Verifica Permissao
        if(!$login->temPermissao($MANTER_SITUACAO_MATRICULAS_DESLIGAR)) {
            require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
        }

        $matriculaAluno = $_REQUEST["matriculaAluno"];
        $ma = MatriculaAluno::obterMatriculaAluno($matriculaAluno);
        $dataMatricula = $ma->getDataMatricula();
        $curso = Curso::obterCurso($ma->getSiglaCurso());
        $nomeCurso=$curso->getSiglaCurso() . " - " . $curso->getNomeCurso();
        $siglaCurso=$curso->getSiglaCurso();
        $situacaoMatricula=$ma->getSituacaoMatricula();
        $aluno = Aluno::getAlunoByIdPessoa($ma->getIdPessoa());
        $nomeAluno=$aluno->getNome();
        $listaSituacaoMatriculaHistorico = $ma->obterListaSituacaoMatriculaHistorico();

        require_once "$BASE_DIR/interno/manter_situacao_matricula/telaDesligarMatricula.php";

    } 
    else if( $acao === "desligarMatricula") 
    {
        require_once "$BASE_DIR/classes/MatriculaAluno.php";

        // Verifica Permissao
        if(!$login->temPermissao($MANTER_SITUACAO_MATRICULAS_DESLIGAR)) {
            require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
        }

        $matriculaAluno = $_REQUEST["matriculaAluno"];
        $texto = $_REQUEST["texto"];
        $ma = MatriculaAluno::obterMatriculaAluno($matriculaAluno);
        $aluno = $ma->getAluno();
        $nomeAluno=$aluno->getNome();
        $siglaCurso = $ma->getSiglaCurso();
        $con = BD::conectar();
        try {
            mysql_query("BEGIN", $con); // Inicia transação
            $result = $ma->desligarMatricula($texto,$con);

            $strLog = "Alterada a situação de matrícula do aluno $nomeAluno, matrícula $matriculaAluno, curso $siglaCurso, para DESLIGADO,
            com a observação $texto";
            $login->incluirLog($MANTER_SITUACAO_MATRICULAS_DESLIGAR,$strLog,$con);

            mysql_query("COMMIT", $con);
        } catch(Exception $ex) {
            mysql_query("ROLLBACK", $con);
            $msgsErro=array();
            array_push($msgsErro, $ex->getMessage());
            require_once "$BASE_DIR/interno/manter_situacao_matricula/telaDesligarMatricula.php";
            exit;
        }

        // Exibe mensagem de sucesso e volta para a tela de administração de matrículas
        echo "<html><head><script>window.alert('Desligamento da Matrícula registrada com sucesso.');</script><meta http-equiv='refresh' content='0;URL=/coruja/interno/manter_situacao_matricula/manterSituacaoMatricula_controle.php?acao=exibirSituacoesMatriculasCurso&siglaCurso=$siglaCurso'/></head>";
        exit;

    } 
    else if( $acao === "exibirConfirmacaoRematriculaAutomatica") 
    {
        $siglaCurso = $_REQUEST["siglaCurso"];

        // Verifica Permissao
        if(!$login->temPermissao($MANTER_SITUACAO_MATRICULAS_PROC_REMATR_AUTO)) {
            require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
        }

        require_once "$BASE_DIR/interno/manter_situacao_matricula/telaRematriculaAutomatica.php";

    } 
    else if($acao === "processarRematriculaAutomatica") 
    {
        require_once "$BASE_DIR/classes/Curso.php";

        // Verifica Permissao
        if(!$login->temPermissao($MANTER_SITUACAO_MATRICULAS_PROC_REMATR_AUTO)) {
            require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
        }
        
        $siglaCurso=$_REQUEST["siglaCurso"];
        $curso = Curso::obterCurso($siglaCurso);

        $con = BD::conectar();
        try {
            mysql_query("BEGIN", $con); // Inicia transação

            $curso->processarRematriculaAutomatica($con);

            $strLog = "Processada a rematrícula automatica do curso $siglaCurso";
            $login->incluirLog($MANTER_SITUACAO_MATRICULAS_PROC_REMATR_AUTO,$strLog,$con);

            mysql_query("COMMIT", $con);
        } catch(Exception $ex) {
            mysql_query("ROLLBACK", $con);
            $msgsErro=array();
            array_push($msgsErro, $ex->getMessage());
            require_once "$BASE_DIR/interno/manter_situacao_matricula/telaRematriculaAutomatica.php";
            exit;
        }

        // Exibe mensagem de sucesso e volta para a tela de administração de matrículas
        echo "<html><head><script>window.alert('Rematrícula processada com sucesso.');</script><meta http-equiv='refresh' content='0;URL=/coruja/interno/manter_situacao_matricula/manterSituacaoMatricula_controle.php?acao=exibirSituacoesMatriculasCurso&siglaCurso=$siglaCurso'/></head>";
        exit;

    }
    else 
    {
        trigger_error("Ação não identificada.",E_USER_ERROR);
    }