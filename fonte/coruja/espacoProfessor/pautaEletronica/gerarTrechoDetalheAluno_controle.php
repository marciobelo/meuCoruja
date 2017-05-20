<?php
require_once("../../includes/comum.php");
require_once("$BASE_DIR/classes/MatriculaAluno.php");
require_once("$BASE_DIR/classes/Aluno.php");

$usuario = $_SESSION["usuario"];
if( (!$usuario->isProfessor()) && (!$usuario->isAdministrador())) {
    echo "Sem permissão!";
    exit;
}

$numMatriculaAluno = $_GET["numMatriculaAluno"];

$matriculaAluno = MatriculaAluno::obterMatriculaAluno($numMatriculaAluno);
$idPessoa = $matriculaAluno->getIdPessoa();
$aluno = $matriculaAluno->getAluno();
$nomeAluno = $aluno->getNome();
$nomeAluno = htmlentities($aluno->getNome(),ENT_QUOTES,"iso-8859-1");
?>
<table border="0">
    <tr>
        <td>
            <img src="/coruja/baseCoruja/controle/obterFoto_controle.php?idPessoa=<?php echo $idPessoa; ?>" 
                 width='100' height='90' border=0 />
        </td>
        <td>
            <?php
                echo $nomeAluno;
                echo "<br/>\n";
                echo "Matr&iacute;cula: " . $matriculaAluno->getNumMatriculaAluno();
                echo "<br/>\n";
                echo $aluno->getEmail();
            ?>
        </td>
        
    </tr>
</table>