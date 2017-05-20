<?php
require_once "$BASE_DIR/includes/topo.php";
require_once "$BASE_DIR/includes/menu_horizontal.php";
require_once "$BASE_DIR/classes/Util.php";
?>

<script type="text/javascript">
    function habilitaAlterarPeriodo() {
        var caixa = document.getElementById("periodoReferencia");
        caixa.style.backgroundColor = "white";
        caixa.readOnly = false;
        var botaoAlterar = document.getElementById("alterarPeriodoReferencia");
        botaoAlterar.style.visibility = "hidden";
    }
</script>
<!-- Mensagens de erro, se houver -->
<?php
if(count($msgsErro)>0) {
?>
<ul class="erro">
<?php
    foreach($msgsErro as $msgErro) {
?>
    <li>
        <?php echo $msgErro; ?>
    </li>
<?php
    }
?>
</ul>
<?php
}
?>

<fieldset id="coruja">
    <legend>Emitir Declara&ccedil;&atilde;o de Matr&iacute;cula em Curso</legend>
    <form method="post" id="formDeclMatrAluno"
          action="/coruja/interno/emitir_decl_matr_aluno/emitirDeclMatrAluno_controle.php">
        <input type="hidden" name="acao" id="acao" value="emitirDeclMatrAluno" />
        <input type="hidden" name="numMatriculaAluno" value="<?php echo $numMatriculaAluno; ?>" />

        <table>
            <tr>
                <td width="15%">Matr&iacute;cula:</td>
                <td>
                    <span><?php echo $numMatriculaAluno; ?></span>
                </td>
            </tr>
            <tr>
                <td>Curso:</td>
                <td>
                    <span><?php echo $matriculaAluno->getSiglaCurso(); ?></span>
                </td>
            </tr>
            <tr>
                <td>Nome:</td>
                <td>
                    <span><?php echo $aluno->getNome(); ?></span>
                </td>
            </tr>
            <tr>
                <td>Per&iacute;odo Relativo &agrave; Carga Hor&aacute;ria Cumprida:</td>
                <td><input type="text" name="periodoReferencia" id="periodoReferencia"
                           readonly="true" style="background-color: lightgray;" value="<?php echo $periodoReferencia; ?>" />
                    &nbsp;
                    <?php if($temPermissaoAlterarPeriodo) { ?>
                    <div id="alterarPeriodoReferencia">
                        <input type="button" name="periodoReferencia" value="Alterar" onclick="habilitaAlterarPeriodo();" />
                    </div>
                    <?php } ?>
                </td>
            </tr>

        </table>
        <input type="submit" value="Emitir Declaração de Matrícula" tabindex="4" />
    </form>
</fieldset>
<br/>
<?php
require_once "$BASE_DIR/includes/rodape.php";

if($emitirPDF) {
?>
<script type="text/javascript">
    window.open("/coruja/interno/emitir_decl_matr_aluno/emitirDeclMatrAluno_controle.php?acao=gerarPDF");
</script>
<?php
}
?>
