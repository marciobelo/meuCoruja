<?php
require_once("../../includes/comum.php");
require_once "$BASE_DIR/classes/Curso.php";
require_once "$BASE_DIR/classes/Turma.php";
require_once "$BASE_DIR/classes/PeriodoLetivo.php";
require_once "$BASE_DIR/classes/TempoSemanal.php";
require_once "$BASE_DIR/classes/Usuario.php";
require_once "$BASE_DIR/classes/Inscricao.php";
require_once "$BASE_DIR/classes/MatriculaAluno.php";
require_once "$BASE_DIR/classes/Aluno.php";
require_once "$BASE_DIR/classes/EventoPeriodoLetivo.php";
require_once "$BASE_DIR/classes/Mensagem.php";
require_once "$BASE_DIR/siro/classes/funcoesRN.php";

$usuario = $_SESSION["usuario"];

include_once "$BASE_DIR/includes/topo.php";

echo"<link href='../estilos/tabelas.css' rel='stylesheet' type='text/css' />";
echo"<link href='../estilos/botoes.css' rel='stylesheet' type='text/css' />";

echo '<div id="menuprincipal">';
include_once "$BASE_DIR/includes/menu_horizontal.php";
echo '</div>';

$acaoSolicitacao = "vazio";

$inscricao = new Inscricao();

// CONTEÚDO DA PÁGINA - ARQUIVO QUE TEM A FUNÇÃO DE TRATAR AS REQUISIÇÕES
echo '<div id="conteudo">';

$action = filter_input( INPUT_GET, "action", FILTER_SANITIZE_STRING);

if( !empty( $_SESSION["siglaCursoFiltro"]))
{
    $siglaCurso = $_SESSION["siglaCursoFiltro"];
}
else
{
    $siglaCurso = filter_input( INPUT_POST, "siglaCurso", FILTER_SANITIZE_STRING);
}

if( $action === "curso" && $siglaCurso === NULL) {// acao para exibir a pagina de filtro de curso
    
    
    if( !$usuario->temPermissao($MANTER_SITUACAO_INSCRICOES_TURMAS)) // Verifica Permissão
    {
        require_once "$BASE_DIR/baseCoruja/formularios/sem_permissao.php";
        exit;
    }
    $collection = Curso::obterCursosOrdemPorSigla();
    require "$BASE_DIR/siro/formularios/manterSituacaoInscricao/manterSituacaoInscricaoTurmaSelecionaCurso.php";
}
else if( $action === "listar" || ($action === "curso" && $siglaCurso !== ""))
{
    $cursoLista = Curso::obterCurso( $siglaCurso);
    try 
    {
        $periodoLetivo = Periodoletivo::obterPeriodoLetivoAtual($siglaCurso);
    } 
    catch(Exception $e) 
    {
        $msgsErro = array();
        array_push($msgsErro, $e->getMessage());
        $collection = Curso::obterCursosOrdemPorSigla();
        require "$BASE_DIR/siro/formularios/manterSituacaoInscricao/manterSituacaoInscricaoTurmaSelecionaCurso.php";
        include_once "$BASE_DIR/includes/rodape.php";
        exit;
    }

    // OBTEM AS TURMAS LIBERADAS
    $turmasLiberadas = Turma::obterTurmasLiberadasOuConfirmadas( $siglaCurso, 
            $periodoLetivo->getIdPeriodoLetivo());

    echo "<form>";
    echo "<fieldset id='fieldsetGeral'>";
    echo "<legend>Lista de Turmas Liberadas</legend>";
    echo"<b>Curso: ".$cursoLista->getSiglaCurso()." - ".$cursoLista->getNomeCurso();
    echo "<br>Per&iacute;odo Letivo de ".$periodoLetivo->getSiglaPeriodoLetivo()." (".$periodoLetivo->getDataInicio()." - ".$periodoLetivo->getDataFim().") <br />";
    echo"</fieldset>";
    echo"</form>";

    require "$BASE_DIR/siro/formularios/manterSituacaoInscricao/manterSituacaoInscricaoTurmaLista.php";
} 
else if( $action === "turmaSelecionada") 
{
    $mostrarAcao = true;
    $manterInscricaoTurma = filter_input( INPUT_POST, "solicitada", FILTER_SANITIZE_NUMBER_INT);
    if( $manterInscricaoTurma === NULL)
    {
        $manterInscricaoTurma = filter_input( INPUT_GET, "idTurma", FILTER_SANITIZE_NUMBER_INT);
    }
    $mensagem = filter_input( INPUT_GET, "mensagem", FILTER_SANITIZE_STRING);
    
    $acaoSolicitacao = "solicitacoes";
    $turma = Turma::getTurmaById($manterInscricaoTurma);
    $siglaCurso = $turma->getSiglaCurso();
    $periodoLetivo = Periodoletivo::obterPeriodoLetivoAtual( $siglaCurso);

    if( EventoPeriodoLetivo::verificaEncerramentoInscricoes($periodoLetivo->getIdPeriodoLetivo()))
    {
        require_once "$BASE_DIR/siro/formularios/permissao/aindaNoPeriodoSolicitacoes.php";
        include_once "$BASE_DIR/includes/rodape.php";
        exit();
    }
    
    $turmaSelecionada = Turma::getTurmaById( $manterInscricaoTurma);
    $cursoLista = Curso::obterCurso( $siglaCurso);

    if( $mensagem !== null)
    {
        echo "<ul class='erro'><li>" . $mensagem . "</li></ul>";
    }
    
    echo "<form>";
    echo "<fieldset id='fieldsetGeral'>";
    echo "<legend>Solicitações de Inscrição</legend>";
    echo"<b>Curso: ".$cursoLista->getSiglaCurso()." - ".$cursoLista->getNomeCurso();
    echo "<br>Per&iacute;odo Letivo de ".$periodoLetivo->getSiglaPeriodoLetivo()." (".$periodoLetivo->getDataInicio()." - ".$periodoLetivo->getDataFim().") <br />";
    echo"</fieldset>";
    echo"</form>";

    require "$BASE_DIR/siro/formularios/manterSituacaoInscricao/manterSituacaoInscricaoTurmaSolDisc.php";
} 
else if($action === "manterSolicitacao") 
{
    $mostrarAcao = false;
    $acaoSolicitacao = filter_input( INPUT_POST, "nomeAcao", FILTER_SANITIZE_STRING);
    if($acaoSolicitacao === "Indeferir") 
    {
        // Verifica Permissão UC02.01.02
        if( !$usuario->temPermissao($INDEFERIR_SOLICATACAO_INSCRICAO)) {
            require_once "$BASE_DIR/baseCoruja/formularios/sem_permissao.php";
            exit;
        }
    }

    if( $acaoSolicitacao === "Cancelar") 
    {
        if(!$usuario->temPermissao($CANCELAR_SOLICATAÇÃO_INSCRICAO)) {
            require_once "$BASE_DIR/baseCoruja/formularios/sem_permissao.php";
            exit;
        }
    }

    $matriculaAluno = filter_input( INPUT_POST, "matriculaAluno", FILTER_SANITIZE_STRING);
    $manterInscricaoTurma =  filter_input( INPUT_POST, "idTurma", FILTER_SANITIZE_NUMBER_INT);
    $turma = Turma::getTurmaById($manterInscricaoTurma);
    $siglaCurso = $turma->getSiglaCurso();
    $periodoLetivo = PeriodoLetivo::obterPeriodoLetivoAtual( $siglaCurso);
    $_SESSION['matriculaAlunoDeferir'] = $matriculaAluno;

    if( $acaoSolicitacao === "Deferir")
    {
        $inscricao = Inscricao::getInscricao( $manterInscricaoTurma, $matriculaAluno);
        if( $inscricao->possuiDeferimentoMesmaDisciplina() ) { // VERIFICA SE JÁ HOUVE O DEFERIMENTO NESSA DISCIPLINA
            echo "<script>alert('ERRO: Aluno já teve a solicitação de inscrição deferida nessa disciplina! Apenas um deferimento na mesma disciplina é permitido.'); ".
                    "document.location.href ='ManterSituacaoInscricaoTurma_controle.php?action=turmaSelecionada';</script>";
            exit;
        }
    }

    //OBTER TURMA SELECIONADA
    $turmaSelecionada = Turma::getTurmaById($manterInscricaoTurma);

    $rn = new funcoesRN();

    //RNs

    $colideRN08 = $rn->RN08( $manterInscricaoTurma, $matriculaAluno);

    $turmaColide = array(array(),array());
    //OBTEM A TURMA QUE COLIDE - RN08
    if($colideRN08) {

        $turmaColide=$inscricao->obterTurmaComConflitoHorario($manterInscricaoTurma, $matriculaAluno, 'DEF',$periodoLetivo->getIdPeriodoLetivo());
        if( count($turmaColide[0]) == 0 ) // conflito havia entre disciplinas requeridas, mas ainda não deferidas
        {
            $colideRN08 = false;
        }
        else 
        {
            //OBTENDO OS TEMPOS QUE COLIDE PARA A RN08

            $tempoColide=array();
            $tColide = array();

            for($i=0;$i<count($turmaColide);$i++) {
                $tempoColide[]=$turmaColide[$i]["tempo"];
                $tColide[]=$turmaColide[$i]["turma"];

            }
        }
    }

    //RETORNA UMA LISTA DE COMPONENTES CURRICULARES
    $listaComponente=$rn->RN09($manterInscricaoTurma, $matriculaAluno);

    $cumpreRequisitosRN09 = true;
    if(!empty ($listaComponente))
    {
        $cumpreRequisitosRN09 = false;
    }

    //RETURN TRUE OU FALSE
    $alunoRF_RN10=$rn->RN10($matriculaAluno, $manterInscricaoTurma);

    $contaRN11 =$rn->RN11($matriculaAluno, $manterInscricaoTurma);

    $alunoRN12 =$rn->RN12($matriculaAluno);

    $tIngressoRN22 =$rn->RN22($matriculaAluno, $manterInscricaoTurma);


    if(($acaoSolicitacao == 'Deferir') && ($colideRN08 || !$cumpreRequisitosRN09 || $alunoRF_RN10 || ($contaRN11>=3 || $alunoRN12 || $tIngressoRN22))) {
        // Verifica Permissão UC02.01.01
        if(!$usuario->temPermissao($DEFERIR_SOLICATACAO_INSCRICAO_JUSTIFICATIVA)) {
            require_once "$BASE_DIR/baseCoruja/formularios/sem_permissao.php";
            exit;
        }
    }

    require "$BASE_DIR/siro/formularios/manterSituacaoInscricao/manterSituacaoInscricaoTurmaSolDisc.php";
}
else if($action === "confirmarDeferirInscricao") 
{
    $numMatriculaAluno = filter_input( INPUT_POST, "matriculaAluno", FILTER_SANITIZE_NUMBER_INT);
    $idTurma = filter_input( INPUT_POST, "idTurma", FILTER_SANITIZE_NUMBER_INT);
    $parecerInscricao = filter_input( INPUT_POST, "rnJustificativa", FILTER_SANITIZE_STRING);

    $turma = Turma::getTurmaById( $idTurma);

    $inscAluno = Inscricao::getInscricao( $idTurma, $numMatriculaAluno);
   
    // Calcula vagas disponíveis na turma
    $inscricoesDeferidas = Inscricao::obterInscricoesAlunos($idTurma, "'DEF'");
    $inscricoesCursando = Inscricao::obterInscricoesAlunos($idTurma, "'CUR'");
    $vagasTurma = $turma->getQtdeTotal();
    $vagasDisponiveis = $vagasTurma - count($inscricoesDeferidas) - count($inscricoesCursando);

    if( $vagasDisponiveis > 0 ) 
    {
        if( $inscAluno->getSituacaoInscricao() === "DEF") 
        { //VERIFICA SE JÁ HOUVE O DEFERIMENTO
            
            $mensagem = "Aluno já teve a inscrição deferida!";
            echo "<script> ".
                "document.location.href ='ManterSituacaoInscricaoTurma_controle.php?action=turmaSelecionada&idTurma=". $idTurma . "&mensagem=" . urlencode($mensagem) . "';</script>";
        }
        else 
        {
            $UC;
            if($parecerInscricao == NULL) {
                global $MANTER_SITUACAO_INSCRICOES_TURMAS;
                $UC = $MANTER_SITUACAO_INSCRICOES_TURMAS;
            } else {
                global $DEFERIR_SOLICATACAO_INSCRICAO_JUSTIFICATIVA;
                $UC = $DEFERIR_SOLICATACAO_INSCRICAO_JUSTIFICATIVA;
            }

            if( $inscAluno->isReclamadoPeloProfessor() || 
                    $turma->getTipoSituacaoTurma() == Turma::CONFIRMADA ) {
                
                $con = BD::conectar();
                try {
                    mysql_query("BEGIN", $con); // Inicia transação
                    Inscricao::registrarCursando($idTurma, $numMatriculaAluno, $parecerInscricao, $con);
                    
                    $strMsg = sprintf("Confirmada inscrição do aluno %s, matrícula %s, na pauta da turma da disciplina %s, grade %s, turno %s, período letivo %s, curso %s",
                            $inscAluno->getNomeAluno(),
                            $inscAluno->getMatriculaAluno(),
                            $turma->getSiglaDisciplina(),
                            $turma->getGradeHorario(),
                            $turma->getTurno(),
                            $turma->getSiglaPeriodoLetivo(),
                            $turma->getCurso()->getSiglaCurso() );
                    if( $inscAluno->isReclamadoPeloProfessor() ) {
                        $strLog = sprintf( $strMsg . sprintf(", que havia sido reclamado pelo professor %s.",
                                $turma->getProfessor()->getNome() ) );
                    } else {
                        $strLog = $strMsg;
                    }                    
                    
                    $usuario->incluirLog($UC, $strLog, $con);
                    $arrIdPessoa = array();
                    $arrIdPessoa[] = $turma->getProfessor()->getIdPessoa();
                    Mensagem::depositarMensagem("Aluno confirmado em pauta", $strMsg . ".", $arrIdPessoa, $con);
                    mysql_query("COMMIT", $con);
                    $mensagem = "Aluno incluído na turma com sucesso!";
                    echo "<script> ".
                        "document.location.href ='ManterSituacaoInscricaoTurma_controle.php?action=turmaSelecionada&idTurma=". $idTurma . "&mensagem=" . urlencode($mensagem) . "';</script>";
                } 
                catch(Exception $ex) 
                {
                    mysql_query("ROLLBACK", $con);
                    $mensagem = "Erro ao tentar deferir inscrição.";
                    echo "<script> ".
                        "document.location.href ='ManterSituacaoInscricaoTurma_controle.php?action=turmaSelecionada&idTurma=". $idTurma . "&mensagem=" . urlencode($mensagem) . "';</script>";
                }
            } 
            else 
            {
                $inscricao->atualizarInscricao( $UC, 'DEF', $parecerInscricao, 
                        $numMatriculaAluno, $idTurma);
                $mensagem = "Inscrição deferida.";
                echo "<script> ".
                        "document.location.href ='ManterSituacaoInscricaoTurma_controle.php?action=turmaSelecionada&idTurma=". $idTurma . "&mensagem=" . urlencode($mensagem) . "';</script>";
            }
        }
    }
    else 
    {
        $mensagem = "Essa turma já atingiu sua capacidade máxima.";
        echo "<script> ".
            "document.location.href ='ManterSituacaoInscricaoTurma_controle.php?action=turmaSelecionada&idTurma=". $idTurma . "&mensagem=" . urlencode($mensagem) . "';</script>";
    }
} 
else if($action == "confirmarIndeferirInscricao") 
{
    $numMatriculaAluno = filter_input( INPUT_POST, "matriculaAluno", FILTER_SANITIZE_NUMBER_INT);
    $idTurma = filter_input( INPUT_POST, "idTurma", FILTER_SANITIZE_NUMBER_INT);
    $parecerInscricao = filter_input( INPUT_POST, "rnJustificativa", FILTER_SANITIZE_STRING);

    $turma = Turma::getTurmaById($idTurma);

    $inscAluno = Inscricao::getInscricao($idTurma, $numMatriculaAluno);

    if($inscAluno->getSituacaoInscricao() == "NEG") 
    {//VERIFICA SE JÁ HOUVE O INDEFERIMENTO
        $mensagem = "Aluno já teve a inscrição indeferida!";
        echo "<script> ".
            "document.location.href ='ManterSituacaoInscricaoTurma_controle.php?action=turmaSelecionada&idTurma=". $idTurma . "&mensagem=" . urlencode($mensagem) . "';</script>";
    }
    else 
    {
        global $INDEFERIR_SOLICATACAO_INSCRICAO;
        $con = BD::conectar();
        try {
            mysql_query("BEGIN", $con); // Inicia transação

            Inscricao::negarInscricao($idTurma, $numMatriculaAluno, $parecerInscricao, $con);

            $strMsg = sprintf("Negada a inscrição do aluno %s, matrícula %s, na pauta da turma da disciplina %s, grade %s, turno %s, período letivo %s, do curso %s",
                    $inscAluno->getNomeAluno(),
                    $inscAluno->getMatriculaAluno(),
                    $turma->getSiglaDisciplina(),
                    $turma->getGradeHorario(),
                    $turma->getTurno(),
                    $turma->getSiglaPeriodoLetivo(),
                    $turma->getCurso()->getSiglaCurso() );
            if( $inscAluno->isReclamadoPeloProfessor() ) 
            {
                $strLog = sprintf( $strMsg . sprintf(", que havia sido reclamado pelo professor %s.",
                        $turma->getProfessor()->getNome() ) );
            } else {
                $strLog = $strMsg;
            }
            $strMsg .= sprintf(". Motivo: '%s'", $parecerInscricao);

            $usuario->incluirLog($INDEFERIR_SOLICATACAO_INSCRICAO, $strLog, $con);
            
            // Avisa ao professor a negativa
            if( $inscAluno->isReclamadoPeloProfessor() ) {
                $arrIdPessoa = array();
                $arrIdPessoa[] = $turma->getProfessor()->getIdPessoa();
                Mensagem::depositarMensagem("Inscrição indeferida", $strMsg . ".", $arrIdPessoa, $con);
            }
            mysql_query("COMMIT", $con);
            $mensagem = "Inscrição do aluno indeferida com sucesso!";
            echo "<script> ".
                "document.location.href ='ManterSituacaoInscricaoTurma_controle.php?action=turmaSelecionada&idTurma=". $idTurma . "&mensagem=" . urlencode($mensagem) . "';</script>";
        } 
        catch(Exception $ex) 
        {
            mysql_query("ROLLBACK", $con);
            $mensagem = "Erro ao tentar indeferir solicitação de inscrição.";
            echo "<script> ".
                "document.location.href ='ManterSituacaoInscricaoTurma_controle.php?action=turmaSelecionada&idTurma=". $idTurma . "&mensagem=" . urlencode($mensagem) . "';</script>";
        }
    }
} 
else if($action === "cancelarInscricao") 
{
    $numMatriculaAluno = filter_input( INPUT_POST, "matriculaAluno", FILTER_SANITIZE_NUMBER_INT);
    $idTurma = filter_input( INPUT_POST, "idTurma", FILTER_SANITIZE_NUMBER_INT);

    $parecerInscricao = NULL;

    $turmaAux = Turma::getTurmaById( $idTurma);

    $inscAluno = Inscricao::getInscricao( $idTurma, $numMatriculaAluno);

    if( $inscAluno->getSituacaoInscricao() === "REQ") 
    {//VERIFICA SE JÁ HOUVE O DEFERIMENTO
        $mensagem = "Aluno aguardando parecer!";
        echo "<script> ".
            "document.location.href ='ManterSituacaoInscricaoTurma_controle.php?action=turmaSelecionada&idTurma=". $idTurma . "&mensagem=" . urlencode($mensagem) . "';</script>";
    } 
    else 
    {
        global $CANCELAR_SOLICATAÇÃO_INSCRICAO;

        $inscricao->atualizarInscricao($CANCELAR_SOLICATAÇÃO_INSCRICAO,'REQ', $parecerInscricao, $numMatriculaAluno, $idTurma);
        $mensagem = "A inscrição do aluno retornou para o status de Não Avaliada!";
        echo "<script> ".
            "document.location.href ='ManterSituacaoInscricaoTurma_controle.php?action=turmaSelecionada&idTurma=". $idTurma . "&mensagem=" . urlencode($mensagem) . "';</script>";
    }
}
echo '</div>';
include_once "$BASE_DIR/includes/rodape.php";