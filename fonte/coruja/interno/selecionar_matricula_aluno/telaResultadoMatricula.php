<?php
require_once "$BASE_DIR/includes/topo.php";
require_once "$BASE_DIR/includes/menu_horizontal.php";

?>
<fieldset id="coruja">
    <legend><?php echo $controleDestinoTitulo; ?><br/>
        Pesquisa por Matr&iacute;culas do Curso <?php echo $siglaCurso; ?></legend>

    <table border="1">
        <thead>
            <tr>
                <td>Foto</td>
                <td>Matr&iacute;cula</td>
                <td>Nome do Aluno</td>
                <td>Data Matr&iacute;cula</td>
                <td>Situa&ccedil;&atilde;o Matr&iacute;cula</td>
                <td>&nbsp;</td>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach($listaMatriculasCurso as $matr) {
            ?>
            <tr>
                <td>
                    <img src="/coruja/baseCoruja/controle/obterFoto_controle.php?idPessoa=<?php echo $matr->getIdPessoa(); ?>" height="70px" width="70px" />
                </td>
                <td>
                    <?php echo $matr->getMatriculaAluno(); ?>
                </td>
                <td>
                    <?php $aluno = $matr->getAluno(); echo $aluno->getNome(); ?>
                </td>
                <td>
                    <?php echo Util::dataSQLParaBr($matr->getDataMatricula()); ?>
                </td>
                <td>
                    <?php echo $matr->getSituacaoMatricula(); ?>
                </td>
                <td>
                    <form action="<?php echo $controleDestino; ?>">
                        <input type="hidden" name="acao" value="<?php echo $acaoControleDestino; ?>" />
                        <input type="hidden" name="matriculaAluno" value="<?php echo $matr->getMatriculaAluno(); ?>" />
                        <input type="submit" value="Selecionar" />
                    </form>
                </td>
            </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
    <form method="GET" action="/coruja/interno/selecionar_matricula_aluno/selecionarMatricula_controle.php">
        <input type="hidden" name="acao" value="exibirFiltroPesquisa" />
        <input type="hidden" name="siglaCurso" value="<?php echo $siglaCurso; ?>" />
        <input type="hidden" name="controleDestino" value="<?php echo $controleDestino; ?>" />
        <input type="hidden" name="acaoControleDestino" value="<?php echo $acaoControleDestino; ?>" />
        <input type="hidden" name="controleDestinoTitulo" value="<?php echo $controleDestinoTitulo; ?>" />
        <input type="hidden" name="tipoBusca" value="<?php echo $tipoBusca; ?>" />
        <input type="submit" value="Voltar" />
    </form>
</fieldset>
<br/>
<?php
require_once "$BASE_DIR/includes/rodape.php";
?>