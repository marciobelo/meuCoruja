<?php
/*   UC 01.02.00
 *
 * Controlador responsável pela geração do histórico escolar de um aluno
 * Suas principais passos são
 */
require_once "../../includes/comum.php";
require_once "$BASE_DIR/classes/BD.php";
require_once "$BASE_DIR/nort/classes/historicoEscolar/HistoricoEscolarPDF.php";

// Verifica Permissão
if(!$usuario->temPermissao($EMITIR_HISTORICO)) {
    require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
    exit();
}

$acao = $_REQUEST["acao"];

switch($acao) 
{
    case "buscarMatricula":
        header("Location: /coruja/interno/selecionar_matricula_aluno/selecionarMatricula_controle.php?acao=selecionarCurso&controleDestino=/coruja/nort/controle/emitirHistoricoEscolar_controle.php&acaoControleDestino=selecionarOpcaoGerarPDF&controleDestinoTitulo=" . urlencode('Emitir Histórico Escolar'));
        break;

    case 'selecionarOpcaoGerarPDF':
        $numMatricula = $_REQUEST['matriculaAluno'];
        require "$BASE_DIR/nort/formularios/historicoEscolar/emitirHistoricoEscolar_selecionarOpcaoGerarPDF.php";
        require_once "$BASE_DIR/includes/rodape.php";
        break;

    case 'gerarPDF':

        $exibeComponentesCurricularesPendentes = filter_input(INPUT_GET, "exibeComponentesCurricularesPendentes", FILTER_VALIDATE_BOOLEAN);
        $exibeHistoricoDeSituacaoDeMatricula = filter_input(INPUT_GET, "exibeHistoricoDeSituacaoDeMatricula", FILTER_VALIDATE_BOOLEAN);
        $exibeListaDeDocumentosPendentes = filter_input(INPUT_GET, "exibeListaDeDocumentosPendentes", FILTER_VALIDATE_BOOLEAN);
        $numMatricula = filter_input(INPUT_GET, "numMatricula", FILTER_SANITIZE_NUMBER_INT);
        
        if ($numMatricula != NULL) {

            //Gera desenha todo o documento e o salva em uma variavel, mas ainda nao o exibe
            $pdf = gerarPDF($numMatricula, $exibeComponentesCurricularesPendentes,
                    $exibeHistoricoDeSituacaoDeMatricula,
                    $exibeListaDeDocumentosPendentes);

            registrarLog($numMatricula);
            
            $pdf->Output( "HistoricoMatricula", "I");
            exit;
        }
    default:
        //ERRO - USO INESPERADO
        trigger_error("Não foi possível identificar \"$acao\" como o próximo passo da funcionalide de emissão da lista de alunos por turma", E_USER_ERROR);
        break;
}

function gerarPDF($matricula,$exibeComponentesCurricularesPendentes,
                    $exibeHistoricoDeSituacaoDeMatricula,
                    $exibeListaDeDocumentosPendentes) {
    $pdf=new HistoricoEscolarPDF($matricula);
    $pdf->gerarCabecalho();
    $pdf->gerarDescricaoDoAluno();
    $pdf->gerarListaDisciplinasCusadas();
    if( $exibeComponentesCurricularesPendentes)
    {
        $pdf->gerarComponentesCurricularesPendentes();
    }
    if($exibeHistoricoDeSituacaoDeMatricula)
    {
        $pdf->gerarHistoricoDeSituacaoDeMatricula();
    }
    if($exibeListaDeDocumentosPendentes)
    {
        $pdf->gerarListaDeDocumentosPendentes();
    }
    $pdf->gerarCR();
    return $pdf;
}

function registrarLog($matricula) {

    $con = BD::conectar();

    $query =
        sprintf(
            "select "
            ."    PE.`nome`, MA.`matriculaAluno`, CUR.`siglaCurso`, CUR.`nomeCurso` "
            ."from "
            ."    Pessoa PE, MatriculaAluno MA, Curso CUR "
            ."where "
            ."    MA.`matriculaAluno` = '%s' "
            ."    and PE.`idPessoa` = MA.`idPessoa` "
            ."    and MA.`siglaCurso` = CUR.`siglaCurso` "
            ,  mysql_real_escape_string($matricula));
    $result = mysql_query($query,$con);
    $resDadosLog = mysql_fetch_array($result);
    $nome = Util::formataNome($resDadosLog["nome"]);
    $matriculaAluno = $resDadosLog["matriculaAluno"];
    $siglaCurso = $resDadosLog["siglaCurso"];
    $nomeCurso = $resDadosLog["nomeCurso"];

    $mensagem = "Emitido Histórico do aluno $nome, matrícula $matriculaAluno, do curso $siglaCurso ($nomeCurso)";

    $_SESSION["usuario"]->incluirLog('UC01.02.00', $mensagem);
}