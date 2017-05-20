<?php
/*   UC 01.09.00 */
require_once "../../includes/comum.php";
require_once "$BASE_DIR/classes/BD.php";
require_once "$BASE_DIR/nort/classes/fichaDeMatricula/FichaDeMatriculaPDF.php";
require_once "$BASE_DIR/classes/MatriculaAluno.php";
require_once "$BASE_DIR/classes/MatrizCurricular.php";
require_once "$BASE_DIR/classes/Curso.php";

$acao = $_REQUEST["acao"];

switch ($acao) {
    case 'buscarMatricula':

        // Verifica Permiss�o
        if(!$usuario->temPermissao($EMITIR_FICHA_DE_MATRICULA)) {
            require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
            exit();
        }

        header("Location: /coruja/interno/selecionar_matricula_aluno/selecionarMatricula_controle.php?acao=selecionarCurso&controleDestino=/coruja/nort/controle/emitirFichaDeMatricula_controle.php&acaoControleDestino=gerarPDF&controleDestinoTitulo=" . urlencode('Emitir Ficha de Matr�cula'));
        break;
        
    case 'gerarPDF':

        // Verifica Permiss�o
        if(!$usuario->temPermissao($EMITIR_FICHA_DE_MATRICULA)) {
            require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
            exit();
        }

        $numMatricula = $_REQUEST['matriculaAluno'];

        require "$BASE_DIR/nort/formularios/fichaDeMatricula/emitirFichaDeMatricula_gerarPDF.php";
        require_once "$BASE_DIR/includes/rodape.php";
        break;
    case 'gerarPDFproprioAluno':

        $numMatricula = $usuario->getNomeAcesso();
        $matricula=MatriculaAluno::obterMatriculaAluno($numMatricula);

        if( (!$usuario->isAluno()) || $matricula==null ) {
            require_once("$BASE_DIR/baseCoruja/formularios/sem_permissao.php");
            exit();
        }

        //MOSTRA P�GINA DE EMISS�O DE LISTAGEM DE ALUNOS POR TURMA
        require "$BASE_DIR/nort/formularios/fichaDeMatricula/emitirFichaDeMatricula_gerarPDF.php";
        require_once "$BASE_DIR/includes/rodape.php";
        break;
    case 'exibirPDF':
        if($usuario->isAluno()) {
            $numMatricula = $usuario->getNomeAcesso();
        } else {
            if(!$usuario->temPermissao($EMITIR_FICHA_DE_MATRICULA)) {
                trigger_error("Usu�rio sem permiss�o.",E_USER_ERROR);
            }
            $numMatricula = $_REQUEST["matriculaAluno"];
        }
        
        //Grava o log do caso de uso
        registrarLog($numMatricula);
        
        $pdf = gerarPDF($numMatricula);

        $pdf->Output();
        exit;
    default:
        trigger_error("N�o foi poss�vel identificar \"$passo\" como o pr�ximo passo da funcionalide de emiss�o da lista de alunos por turma", E_USER_ERROR);
        break;
}
?>

<?php
function gerarPDF($numMatricula) {

    $matriculaAluno=MatriculaAluno::obterMatriculaAluno($numMatricula);

    $pdf = new FichaDeMatriculaPDF($matriculaAluno);
    
    return $pdf;
}

function registrarLog($numMatricula) {

    $con = BD::conectar();
    $matricula=MatriculaAluno::obterMatriculaAluno($numMatricula);
    $curso=$matricula->getMatrizCurricular()->getCurso();

    $aluno = Aluno::getAlunoByNumMatricula($matricula->getMatriculaAluno());

    $nomeAluno = $aluno->getNome();
    $siglaCurso = $curso->getSiglaCurso();
    $nomeCurso = $curso->getNomeCurso();

    $mensagem = "Emitida a Ficha de Matr�cula do aluno ".
        "$nomeAluno, de matr�cula $numMatricula, do curso ".
        "$siglaCurso ($nomeCurso)";

    global $EMITIR_FICHA_DE_MATRICULA;
    $_SESSION["usuario"]->incluirLog($EMITIR_FICHA_DE_MATRICULA, $mensagem);

}
?>