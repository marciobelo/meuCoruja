<?php
require_once "$BASE_DIR/includes/topo.php";
require_once "$BASE_DIR/includes/menu_horizontal.php";
?>

<script type="text/javascript">
    function voltar() {
        var form = document.getElementById("formReabrirTurmaFinalizada");
        form.acao.value = "voltar";
        form.submit();
    }
</script>

<form method="POST" id="formReabrirTurmaFinalizada" action="/coruja/nort/controle/reabrirTurmaFinalizada_controle.php">
    <fieldset id="fieldsetGeral">
    <input type="hidden" name="acao" id="acao" value="reabrirTurmaFinalizada" />
    <input type="hidden" name="idTurma" value="<?php echo $idTurma; ?>" />
    <input type="hidden" name="siglaCurso" value="<?php echo $siglaCurso; ?>" />
    <input type="hidden" name="idPeriodoLetivo" value="<?php echo $idPeriodoLetivo; ?>" />
    <input type="hidden" name="turno" value="<?php echo $turno; ?>" />
    <table>
        <?php
        if( isset($msg) ) {
            ?>
        <tr>
            <td colspan="2">
                <span class="erro"><?php echo htmlspecialchars($msg, ENT_QUOTES, "iso-8859-1"); ?></span>
            </td>
        </tr>
        <?php
        }
        ?>
        <tr>
            <td>Curso:</td>
            <td>
                <?php echo $turma->getCurso()->getSiglaCurso(); ?>
                -
                <?php echo $turma->getCurso()->getNomeCurso(); ?>
            </td>
        </tr>
        <tr>
            <td>Per&iacute;odo Letivo:</td>
            <td>
                <?php echo $turma->getPeriodoLetivo()->getSiglaPeriodoLetivo(); ?>
            </td>
        </tr>
        <tr>
            <td>Disciplina:</td>
            <td>
                <?php echo $turma->getSiglaDisciplina(); ?> -
                <?php echo $turma->getComponenteCurricular()->getNomeDisciplina(); ?>
            </td>
        </tr>
        <tr>
            <td>Turno/Grade:</td>
            <td>
                <?php echo $turma->getTurno(); ?> /
                <?php echo $turma->getGradeHorario(); ?>
            </td>
        </tr>
        <?php if($professor!=null) { ?>
        <tr>
            <td>Professor:</td>
            <td>
                <?php echo $professor->getNome(); ?>
            </td>
        </tr>
        <tr>
            <td>E-mail do Professor:</td>
            <td>
                <?php echo $professor->getEmail(); ?>
            </td>
        </tr>
        <tr><td colspan="2">&nbsp;</td></tr>
        <tr>
            <td></td>
            <td><span class="erro" style="font-size: large;">
                    ATEN&Ccedil;&Atilde;O: essa opera&ccedil;&atilde;o é extremamente cr&iacute;tica!</span><br/>
                    <span class="destaque">Ela permitir&aacute; altera&ccedil;&otilde;es nas informa&ccedil;&otilde;es da turma tornando
                        incorretos hist&oacute;ricos, di&aacute;rios e/ou declara&ccedil;&otilde;es que podem j&aacute; ter sido emitidos.
                <br/>
                Se clicar em Reabrir, a turma voltar&aacute; para a situa&ccedil;&atilde;o CONFIRMADA.</span>
            </td>
        </tr>
        <tr><td colspan="2">&nbsp;</td></tr>
        <?php } ?>
        <tr>
            <td colspan="2">
                <?php if( !isset($msg) ) { ?>
                <input type="submit" value="Reabrir" tabindex="3" />
                &nbsp;
                <?php } ?>
                <input type="button" value="Voltar" tabindex="4" onclick="voltar();" />
            </td>
        </tr>
    </table>
    </fieldset>
</form>


<?php
require_once "$BASE_DIR/includes/rodape.php";
?>