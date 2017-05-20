<?php
require_once "$BASE_DIR/includes/topo.php";
require_once "$BASE_DIR/includes/menu_horizontal.php";
require_once "$BASE_DIR/classes/Util.php";
?>
<script type="text/javascript">
    function validar() {
        if(document.desistirMatricula.texto.value=="") {
            window.alert("Campo Justificativa � obrigat�rio.");
            document.desistirMatricula.texto.focus();
            return false;
        }
        return true;
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
    <legend>Desistir de Matr&iacute;cula</legend>
    <?php require_once "$BASE_DIR/interno/manter_situacao_matricula/verHistoricoMatricula.php"; ?>
    <?php
    if(($ma->getSituacaoMatricula() == "DESISTENTE") || ($ma->getSituacaoMatricula() == "DESLIGADO") ) {
    ?>
    <p class="erro">Essa matr&iacute;cula se encontra em situa&ccedil;&atilde;o que n�o permite essa opera��o. Opera&ccedil;&atilde;o Cancelada.</p>
    <?php
    } else {
    ?>
    <p class="erro"><?php echo htmlspecialchars("ATEN��O: a desist�ncia de matr�cula � uma opera��o sem volta. � obrigat�rio que na pasta do aluno conste o termo de desist�ncia assinado pelo aluno ou pelo respons�vel legal.", ENT_QUOTES, "iso-8859-1"); ?></p>
    <form method="post" name="desistirMatricula"
          action="/coruja/interno/manter_situacao_matricula/manterSituacaoMatricula_controle.php"
          onsubmit="return validar();" >
        <input type="hidden" name="acao" value="desistirMatricula" />
        <input type="hidden" name="matriculaAluno" value="<?php echo $matriculaAluno; ?>" />
        <p>
            Justificativa a Desist&ecirc;ncia da Matr&iacute;cula<br/>
            <textarea name="texto" rows="4" cols="40" tabindex="1">Desist�ncia a pedido do aluno.</textarea>
        </p>
        <input type="submit" value="Desistir de Matr&iacute;cula" />
    </form>
    <?php
    }
    ?>
    <form method="post" action="/coruja/interno/manter_situacao_matricula/manterSituacaoMatricula_controle.php#<?php echo $matriculaAluno; ?>">
        <input type="hidden" name="acao" value="exibirSituacoesMatriculasCurso" />
        <input type="hidden" name="siglaCurso" value="<?php echo $siglaCurso; ?>" />
        <input type="submit" value="Voltar" />
    </form>
</fieldset>
<br/>
<?php
require_once "$BASE_DIR/includes/rodape.php";
?>