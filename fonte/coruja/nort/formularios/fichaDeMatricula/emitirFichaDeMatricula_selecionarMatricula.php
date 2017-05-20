<?php
require_once "$BASE_DIR/includes/topo.php";
require_once "$BASE_DIR/includes/menu_horizontal.php";
require_once "$BASE_DIR/classes/Util.php";
?>

<?php
foreach ($listaDeMatriculaAluno as $ma) {
?>
    <form id="cadastro" action="<?php echo $_SERVER['PHP_SELF'] ?>?acao=gerarPDF" method="post">
        <fieldset>

            <table>
                <!-- Matrícula -->
                <tr> <td>Matricula:</td> <td><?php echo $ma->getMatriculaAluno(); ?></td> </tr>
                <!-- Data da Matrícula -->
                <tr> <td>Data da matrícula:</td> <td><?php echo Util::dataSQLParaBr($ma->getDataMatricula()) ?></td> </tr>
                <!-- Situação -->
                <tr> <td>Situação:</td> <td><?php echo $ma->getSituacaoMatricula() ?></td> </tr>
                <!-- Nome -->
                <tr> <td>Nome:</td> <td><?php echo $ma->getAluno()->getNome() ?></td> </tr>
            </table>

            <input type="hidden" value="<?php echo $ma->getMatriculaAluno(); ?>" name="matricula">
            <center><input type="submit" value="Emitir Ficha de Matrícula" name="EFM"></center>
        </fieldset>
    </form>
<?php
}
?>
