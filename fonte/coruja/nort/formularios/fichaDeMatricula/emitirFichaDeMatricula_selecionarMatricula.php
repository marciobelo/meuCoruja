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
                <!-- Matr�cula -->
                <tr> <td>Matricula:</td> <td><?php echo $ma->getMatriculaAluno(); ?></td> </tr>
                <!-- Data da Matr�cula -->
                <tr> <td>Data da matr�cula:</td> <td><?php echo Util::dataSQLParaBr($ma->getDataMatricula()) ?></td> </tr>
                <!-- Situa��o -->
                <tr> <td>Situa��o:</td> <td><?php echo $ma->getSituacaoMatricula() ?></td> </tr>
                <!-- Nome -->
                <tr> <td>Nome:</td> <td><?php echo $ma->getAluno()->getNome() ?></td> </tr>
            </table>

            <input type="hidden" value="<?php echo $ma->getMatriculaAluno(); ?>" name="matricula">
            <center><input type="submit" value="Emitir Ficha de Matr�cula" name="EFM"></center>
        </fieldset>
    </form>
<?php
}
?>
