<?php
require_once "../../includes/comum.php";
require_once "$BASE_DIR/classes/Aluno.php";
require_once "$BASE_DIR/classes/MatriculaAluno.php";
require_once "$BASE_DIR/classes/Curso.php";
require_once "$BASE_DIR/classes/MatrizCurricular.php";
require_once "$BASE_DIR/classes/Usuario.php";

require_once "$BASE_DIR/interno/emitir_decl_matr_aluno/DeclMatrAlunoPDF.php";

$acao = $_REQUEST["acao"];
if(!isset ($acao)) { // ação inicial

    // Verifica Permissão
    if(!$usuario->temPermissao($EMITIR_DECL_MATR_CURSO)) {
        require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
        exit();
    }

    header("Location: /coruja/interno/selecionar_matricula_aluno/selecionarMatricula_controle.php?acao=selecionarCurso&controleDestino=/coruja/interno/emitir_decl_matr_aluno/emitirDeclMatrAluno_controle.php&acaoControleDestino=exibirResumo&controleDestinoTitulo=" . urlencode('Emitir Declaração de Matrícula em Curso'));
    break;

} else if($acao=="exibirResumo") {

    // Verifica Permissão
    if(!$usuario->temPermissao($EMITIR_DECL_MATR_CURSO)) {
        require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
        exit();
    }

    $numMatriculaAluno = $_REQUEST["matriculaAluno"];
    $aluno = Aluno::getAlunoByNumMatricula($numMatriculaAluno);
    $matriculaAluno = MatriculaAluno::obterMatriculaAluno($numMatriculaAluno);
    $periodoReferencia = $matriculaAluno->getPeriodoReferencia();
    $temPermissaoAlterarPeriodo = $usuario->temPermissao($EMITIR_DECL_MATR_CURSO_ALTERAR_PERIODO);

    // Verifica se o aluno está cursando
    if( $matriculaAluno->getSituacaoMatricula() != 'CURSANDO') {
        $msgsErro = array();
        array_push($msgsErro, "A matrícula não está na situação CURSANDO.");

        require_once("$BASE_DIR/interno/emitir_decl_matr_aluno/telaResumoDeclMatrAluno.php");
        exit;
    }
    
    require_once("$BASE_DIR/interno/emitir_decl_matr_aluno/telaResumoDeclMatrAluno.php");

} else if($acao=="emitirDeclMatrAluno") {
    $numMatriculaAluno = $_POST["numMatriculaAluno"];
    $aluno = Aluno::getAlunoByNumMatricula($numMatriculaAluno);
    $periodoReferencia = $_POST["periodoReferencia"];
    $matriculaAluno = MatriculaAluno::obterMatriculaAluno($numMatriculaAluno);
    $periodoReferenciaReal = $matriculaAluno->getPeriodoReferencia();
    $periodoMaxPermitido = $matriculaAluno->getMatrizCurricular()->obterQuantidadePeriodos();
    $temPermissaoAlterarPeriodo = $usuario->temPermissao($EMITIR_DECL_MATR_CURSO_ALTERAR_PERIODO);

    $matrizCurricular = $matriculaAluno->getMatrizCurricular();
    $curso = $matrizCurricular->getCurso();

    // Verifica Permissão
    if( !$usuario->temPermissao($EMITIR_DECL_MATR_CURSO) ||
        ($periodoReferencia != $periodoReferenciaReal) &&
            (!$usuario->temPermissao($EMITIR_DECL_MATR_CURSO_ALTERAR_PERIODO)) ) {
        require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
        exit();
    }

    // Verifica inconsistência no período referência em relação à matriz do aluno
    if( $periodoReferencia > $periodoMaxPermitido ) {
        $msgsErro = array();
        array_push($msgsErro, "O maior período permitido para a matriz dessa matrícula é " . $periodoMaxPermitido );

        require_once("$BASE_DIR/interno/emitir_decl_matr_aluno/telaResumoDeclMatrAluno.php");
        exit;
    }

    // Verifica se o aluno está cursando
    if($matriculaAluno->getSituacaoMatricula() != 'CURSANDO') {
        $msgsErro = array();
        array_push($msgsErro, "Não é possível gerar declaração para matrícula diferente de CURSANDO.");

        require_once("$BASE_DIR/interno/emitir_decl_matr_aluno/telaResumoDeclMatrAluno.php");
        exit;
    }

    // Gravar mensagem de auditoria
    if($periodoReferencia == $periodoReferenciaReal) {
        $uc = $EMITIR_DECL_MATR_CURSO;
    } else {
        $uc = $EMITIR_DECL_MATR_CURSO_ALTERAR_PERIODO;
    }
    $strLog = "Emitida declaração de matrícula para o aluno " . $aluno->getNome() .
            ", matrícula " . $numMatriculaAluno . ", do curso " .
            $matriculaAluno->getSiglaCurso() . ", com período de referência " . $periodoReferencia;
    $usuario->incluirLog($uc,  $strLog);


    $emitirPDF = true;
    $pdf = new DeclMatrAlunoPDF();
    $_SESSION["relatorio"] = $pdf;

    require_once("$BASE_DIR/interno/emitir_decl_matr_aluno/telaResumoDeclMatrAluno.php");
    exit;
} else if($acao=="gerarPDF") {
    session_write_close();
    session_start();
    $pdf = $_SESSION["relatorio"];
    $pdf->Output();
    $_SESSION[""] = null;
    exit;
} else {
    trigger_error("Ação não identificada.",E_USER_ERROR);
}
?>
