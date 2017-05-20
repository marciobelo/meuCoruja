<?php
require_once "$BASE_DIR/includes/topo.php";
require_once "$BASE_DIR/includes/menu_horizontal.php";
?>

<script type="text/javascript">
    function voltar() {
        var form = document.getElementById("formExtratoTurmaParaProfessor");
        form.acao.value = "voltar";
        form.submit();
    }
</script>

<form method="post" id="formExtratoTurmaParaProfessor" action="/coruja/interno/enviarExtratoTurmaParaProfessor/enviarExtratoTurmaParaProfessor_controle.php">
    <fieldset id="fieldsetGeral">
    <input type="hidden" name="acao" id="acao" value="enviarExtratoTurmaParaProfessor" />
    <input type="hidden" name="idTurma" value="<?php echo $idTurma; ?>" />
    <input type="hidden" name="siglaCurso" value="<?php echo $siglaCurso; ?>" />
    <input type="hidden" name="idPeriodoLetivo" value="<?php echo $idPeriodoLetivo; ?>" />
    <input type="hidden" name="turno" value="<?php echo $turno; ?>" />
    <table>
        <?php
        // Exibe mensagem de erro quando não conseguir conectar e/ou autenticar
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
        <tr>
            <td></td>
            <td>
                Um extrato com os alunos, notas e situa&ccedil;&atilde;o dessa turma
                ser&atilde;o enviado ao professor para o e-mail indicado.
            </td>
        </tr>
        <?php } ?>
        <tr>
            <td colspan="2">
                <?php if( !isset($msg) ) { ?>
                <input type="submit" value="Enviar" tabindex="3" />
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