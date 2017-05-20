<?php
require_once "$BASE_DIR/includes/topo.php";
require_once "$BASE_DIR/includes/menu_horizontal.php";
?>
<script type="text/javascript">

    function cancelar(idEspaco) {
        document.formEspaco.idEspaco.value=idEspaco;
        document.formEspaco.acao.value="listar";
        document.formEspaco.submit();
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
<form name="formEspaco" action="/coruja/interno/manter_espaco/manterEspaco_controle.php" method="post">
    <input type="hidden" name="acao" value="alterar" />
<fieldset id="coruja">
    <legend>Alterar Espa&ccedil;o</legend>
    <input type="hidden" name="idEspaco" value="<?php echo $formEspaco->getIdEspaco(); ?>" />
    <table width="646">
        <tr>
            <td width="137"><b>Nome : </b> </td>
            <td width="499"><input type="text" name="nome" value="<?php echo $formEspaco->getNome(); ?>" maxlength="45" size="40"/></td>
        </tr>
        <tr>
            <td width="137"><b>Capacidade : </b> </td>
            <td idth="499"><input type="text" name="capacidade" value="<?php echo $formEspaco->getCapacidade(); ?>" maxlength="3" size="3" onkeypress="return Mascaras_Format(document.formEspaco,'capacidade','9999999999999999999',event);"/></td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="submit" value="Salvar" />
                &nbsp;
                <input type="button" value="Cancelar" onclick="javascript:cancelar();" />
            </td>
        </tr>
    

    </table>
</fieldset>
</form>
<br/>
<?php
require_once "$BASE_DIR/includes/rodape.php";
?>