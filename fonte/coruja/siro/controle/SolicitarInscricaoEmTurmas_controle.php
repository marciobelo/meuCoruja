<?php
require_once("../../includes/comum.php");
require_once "$BASE_DIR/classes/Inscricao.php";
require_once "$BASE_DIR/classes/ComponenteCurricular.php";
require_once "$BASE_DIR/classes/Aloca.php";
require_once "$BASE_DIR/classes/Turma.php";
require_once "$BASE_DIR/classes/Inscricao.php";
require_once "$BASE_DIR/classes/MatriculaAluno.php";
require_once "$BASE_DIR/classes/TempoSemanal.php";
require_once "$BASE_DIR/classes/PeriodoLetivo.php";
require_once "$BASE_DIR/classes/EventoPeriodoLetivo.php";
require_once "$BASE_DIR/classes/Curso.php";
require_once "$BASE_DIR/classes/Aluno.php";
require_once "$BASE_DIR/siro/classes/funcoesRN.php";

// Recupera o usuário logado da sessão
$usuario = $_SESSION["usuario"];

$act = filter_input( INPUT_GET, "act", FILTER_SANITIZE_STRING);
$action = filter_input( INPUT_GET, "action", FILTER_SANITIZE_STRING);

require_once "$BASE_DIR/includes/topo.php";
require_once "$BASE_DIR/includes/menu_horizontal.php";

if( $act === "main") // NO PRIMEIRO ACESSO VIA UC02.06.03
{ 
    // Verifica Permissao
    if(!$usuario->temPermissao( $SOLICITAR_INSCRICOES_EM_TURMAS)) 
    {
        require_once "$BASE_DIR/baseCoruja/formularios/sem_permissao.php";
        require_once "$BASE_DIR/includes/rodape.php";
        exit;
    }
    require "$BASE_DIR/siro/controle/SelecionarAlunoParaInscricao_controle.php";
    require_once "$BASE_DIR/includes/rodape.php";
    exit();

}
else if( $action === 'idPessoa')
{
    $idPessoa = filter_input( INPUT_POST, "idPessoa", FILTER_SANITIZE_NUMBER_INT);
    $_SESSION['matrAlunoSelec']= filter_input( INPUT_POST, "matriculaAluno", FILTER_SANITIZE_STRING);
    $matriculaAluno=MatriculaAluno::obterMatriculaAluno( $_SESSION['matrAlunoSelec']);
    $action = "listar";
}
else if( !$usuario->isAluno()) 
{
    $matriculaAluno=MatriculaAluno::obterMatriculaAluno($_SESSION['matrAlunoSelec']);
    $idPessoa=$matriculaAluno->getIdPessoa();
}
else 
{ //CASO NÃO VENHA PELO UC02.06.03
    $idPessoa=$usuario->getIdPessoa();
    $matriculaAluno=MatriculaAluno::obterMatriculaAluno($usuario->getNomeAcesso());
}

if( $matriculaAluno->getSituacaoMatricula() !== "CURSANDO") 
{
    require_once "$BASE_DIR/siro/formularios/situacaoMatriculaSemPermissao.php";
    require_once "$BASE_DIR/includes/rodape.php";
    exit();
}

// OBTEM O PERIODO LETIVO ATUAL
try 
{
    $periodoLetivo = Periodoletivo::obterPeriodoLetivoAtual($matriculaAluno->getSiglaCurso());
}
catch (Exception $ex) 
{
    require_once "$BASE_DIR/siro/formularios/periodoLetivoNaoDisponivel.php";
    require_once "$BASE_DIR/includes/rodape.php";
    exit();
}

//VERIFICA SE ESTA NO PERIODO DE SOLICITACAO
if( !EventoPeriodoLetivo::verificaEncerramentoInscricoes($periodoLetivo->getIdPeriodoLetivo()))
{
    require_once "$BASE_DIR/siro/formularios/permissao/periodoSolicitacoesEncerrado.php";
    require_once "$BASE_DIR/includes/rodape.php";
    exit();
}

// OBTEM AS TURMAS LIBERADAS
$turmasLiberadas = Turma::obterTurmasLiberadasOuConfirmadas( $matriculaAluno->getSiglaCurso(),
        $periodoLetivo->getIdPeriodoLetivo());

// CRIA UM OBJETO DE INSCRICAO
$inscricao = new Inscricao();

//OBTEM AS INSCRICOES SOLICITADAS
$turmasSolicitadas = Inscricao::obterTurmasInscricoesAluno($matriculaAluno->getMatriculaAluno(), "REQ", $periodoLetivo->getIdPeriodoLetivo());

//CRIA UM OBJETO DE ALOCA
$aloca = new Aloca();

//CONTROLE QUANTIDADE DE TURMAS QUE O ALUNO PODE SOLICITAR INSCRICAO
$limiteSolicitacoesRN06 = false;

//INICIO RN06
if( Config::MAX_SOLICS_POR_ALUNO <= count($turmasSolicitadas) )
{
    $limiteSolicitacoesRN06 = true;
}
//FIM DA RN06

//INICIO DA RN12
$alunoRN12 = $matriculaAluno->verificaMatriculaAlunoExcedeTempo();
//FIM DA RN12

// TÍTULO DA PAGINA
$titulo = htmlspecialchars("Solicitar Inscrição em Turmas", ENT_QUOTES, "iso-8859-1");

//PARAMETRO PARA A LISTA UNICA EM TESTE
$casoDeUso = "UC02.06.00";

echo "<link href='/coruja/siro/estilos/tabelas.css' rel='stylesheet' type='text/css' />";
echo "<link href='/coruja/siro/estilos/botoes.css' rel='stylesheet' type='text/css' />";

//CONTEÚDO
echo '<div id="conteudo">';

if($action === "confirmarSolicitacao") 
{
    $perLetivo = PeriodoLetivo::obterPeriodoLetivo( $periodoLetivo->getIdPeriodoLetivo() );

    $classeCurso = Curso::obterCurso($perLetivo->getSiglaCurso());
    $aluno = Aluno::getAlunoByIdPessoa($matriculaAluno->getIdPessoa());

    echo "<form>";
    echo "<fieldset id='fieldsetGeral'>";
    echo "<legend>".$titulo." - Confirme a solicita&ccedil;&atilde;o</legend>";
    echo"<b>Curso: ".$classeCurso->getSiglaCurso()." - ".$classeCurso->getNomeCurso();
    echo "<br>Per&iacute;odo Letivo de ".$periodoLetivo->getSiglaPeriodoLetivo()." (".$periodoLetivo->getDataInicio()." - ".$periodoLetivo->getDataFim().") <br />";
    echo "Aluno: ".$matriculaAluno->getMatriculaAluno()." - ".$aluno->getNome()." - Turno: ".$matriculaAluno->getTurnoIngresso()."</b><br />";
    echo"</fieldset>";
    echo"</form>";

    $rn = new funcoesRN();

    $idTurma = $_REQUEST["idTurma"];
   
    //RNs
    $colideRN08 = $rn->RN08( $idTurma, $matriculaAluno->getMatriculaAluno());

    //OBTEM A TURMA QUE COLIDE - RN08
    if( $colideRN08) 
    {
        $turmaColide=$inscricao->obterTurmaComConflitoHorario($idTurma, $matriculaAluno->getMatriculaAluno(), 'REQ',$periodoLetivo->getIdPeriodoLetivo());
        //OBTENDO OS TEMPOS QUE COLIDE PARA A RN08
        $tempoColide=array();
        $tColide = array();

        for($i=0;$i<count($turmaColide);$i++){
            $tempoColide[]=$turmaColide[$i]["tempo"];
            $tColide[]=$turmaColide[$i]["turma"];
        }
    }
    //FIM DA VERIFICACAO DA RN08
   
    //RETORNA UMA LISTA DE COMPONENTES CURRICULARES
    $listaComponente=$rn->RN09($idTurma, $matriculaAluno->getMatriculaAluno());
    $cumpreRequisitosRN09 = true;
    if(!empty ($listaComponente)) 
    {
        $cumpreRequisitosRN09 = false;
    }

    //RETURN TRUE OU FALSE
    $alunoRF_RN10=$rn->RN10($matriculaAluno->getMatriculaAluno(), $idTurma);

    $contaRN11 =$rn->RN11($matriculaAluno->getMatriculaAluno(), $idTurma);

    $alunoRN12 =$rn->RN12($matriculaAluno->getMatriculaAluno());

    $tIngressoRN22 =$rn->RN22($matriculaAluno->getMatriculaAluno(), $idTurma);

    require "$BASE_DIR/siro/formularios/solicitarInscricaoEmTurmasConfirmar.php";
    echo "<br>";
    require "$BASE_DIR/siro/formularios/gradeHoraria/gradeHorariaNovo.php";
   
} 
elseif( $action === "inserir") 
{
    $idTurma = filter_input( INPUT_POST, "idTurma", FILTER_SANITIZE_NUMBER_INT);

    $con = BD::conectar();
    try 
    {
        mysql_query("BEGIN", $con); // Inicia transação
        Inscricao::requererInscricao($idTurma, $matriculaAluno->getMatriculaAluno(),
                'Solicitação dentro do prazo de inscrições em turmas.', $con);

        global $SOLICITAR_INSCRICOES_EM_TURMAS;
        $aluno = $matriculaAluno->getAluno();
        $turma = Turma::getTurmaById($idTurma);
        $componenteCurricular = $turma->getComponenteCurricular();
        $periodoLetivo = $turma->getPeriodoLetivo();
        $curso = $turma->getCurso();
        $strLog = "Solicitada a inscrição do aluno " . $aluno->getNome() . ", de matricula " .
                $matriculaAluno->getMatriculaAluno() . ", na turma de " . $turma->getSiglaDisciplina() .
                " (" . $componenteCurricular->getNomeDisciplina() . "), " .
                "Período Letivo " . $periodoLetivo->getSiglaPeriodoLetivo() . ", turno " .
                $turma->getTurno() . ", grade " . $turma->getGradeHorario() . ", " .
                "do Curso " . $curso->getSiglaCurso() . " (" . $curso->getNomeCurso() . ")";
        $usuario->incluirLog($SOLICITAR_INSCRICOES_EM_TURMAS, $strLog, $con);
        mysql_query("COMMIT", $con);
    } catch(Exception $ex) {
            mysql_query("ROLLBACK", $con);
            trigger_error("Erro ao inserir requisição de inscricao: " . $ex->getMessage(), E_USER_ERROR);
    }

    echo "<html><head><title>Coruja</title></head><body>";
    echo "<p style=\"text-align: center; font-size: larger\">Sua solicita&ccedil;&atilde;o foi registrada com sucesso!</p>";
    echo"<form id='listar' name='listar' action='SolicitarInscricaoEmTurmas_controle.php?action=listar' method='post'>";
    echo"<input type='hidden' name='idPessoa' value='$idPessoa'>";
    echo"<script>document.listar.submit();</script></form>";
    echo"</body></html>";

}
elseif($action == "listar") 
{

    if( filter_input( INPUT_GET, "mostraOfertaTurnoDiferente") == NULL ) {
        $mostraOfertaTurnoDiferente = false;
    } else {
        $mostraOfertaTurnoDiferente = true;
    }

    $perLetivo = PeriodoLetivo::obterPeriodoLetivo( $periodoLetivo->getIdPeriodoLetivo() );

    $classeCurso = Curso::obterCurso($perLetivo->getSiglaCurso());
    $aluno = Aluno::getAlunoByIdPessoa($matriculaAluno->getIdPessoa());

    echo "<form>";
    echo "<fieldset id='fieldsetGeral'>";
    echo "<legend>".$titulo."</legend>";
    echo"<b>Curso: ".$classeCurso->getSiglaCurso()." - ".$classeCurso->getNomeCurso();
    echo "<br>Per&iacute;odo Letivo de ".$periodoLetivo->getSiglaPeriodoLetivo()." (".$periodoLetivo->getDataInicio()." - ".$periodoLetivo->getDataFim().") <br />";
    echo "Aluno: ".$matriculaAluno->getMatriculaAluno()." - ".$aluno->getNome()." - Turno: ".$matriculaAluno->getTurnoIngresso()."</b><br />";
    echo"</fieldset>";
    echo"</form>";

    require "$BASE_DIR/siro/formularios/gradeHoraria/gradeHorariaNovo.php";
    
    echo "<form name='imprimir' id='imprimir' action='EmitirProtocoloComGradeHoraria_controle.php?act=main' method='post'>";
    echo "<input type='hidden' name='idPessoa' value='".$idPessoa."'>";
    echo "<center><input class='confirmar' type='submit' align='center' value='Emitir Protocolo com Grade Horária'  ></center>";
    echo "</form>";

    require "$BASE_DIR/siro/formularios/ListarTurmasInscricoes.php";
}
echo '</div>';

include_once "$BASE_DIR/includes/rodape.php";