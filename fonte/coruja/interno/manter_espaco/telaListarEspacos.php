<?php
require_once "$BASE_DIR/includes/topo.php";
require_once "$BASE_DIR/includes/menu_horizontal.php";
?>
<script type="text/javascript">
    function prepararAlterar(idEspaco) {
        document.formEspaco.idEspaco.value=idEspaco;
        document.formEspaco.acao.value="prepararAlterar";
        document.formEspaco.submit();
    }
    function prepararExcluir(idEspaco) {
        document.formEspaco.idEspaco.value=idEspaco;
        document.formEspaco.acao.value="prepararExcluir";
        document.formEspaco.submit();
    }
    function prepararIncluir() {
        document.formEspaco.acao.value="prepararIncluir";
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
        <?php echo htmlspecialchars($msgErro, ENT_QUOTES, "iso-8859-1"); ?>
    </li>
<?php
    }
?>
</ul>
<?php
}
?>
<form name="formEspaco" action="/coruja/interno/manter_espaco/manterEspaco_controle.php" method="post">
    <input type="hidden" name="idEspaco" />
    <input type="hidden" name="acao"  />
<fieldset id="coruja">
    <legend>Listar Espa&ccedil;os</legend>

    <table>
        <thead>
            <tr>
                <td>Nome do Espa&ccedil;o</td>
                <td>Capacidade</td>
                <td>&nbsp;</td>
            </tr>
        </thead>
        <tbody>
<?php
            foreach($espacos as $espaco) {
?>
            <tr>
                <td><?php echo $espaco->getNome();  ?></td>
                <td><?php echo $espaco->getCapacidade();  ?></td>
                <td>
                    <input type="button" value="Alterar" onclick="javascript:prepararAlterar(<?php echo $espaco->getIdEspaco(); ?>);" />
                    <input type="button" value="Excluir" onclick="javascript:prepararExcluir(<?php echo $espaco->getIdEspaco(); ?>);" />
                 </td>
            </tr>
<?php

            }
?>

        </tbody>
    </table>
    <input type="button" value="Incluir" onclick="javascript:prepararIncluir();" />
</fieldset>
</form>
<br/>
<?php
require_once "$BASE_DIR/includes/rodape.php";
?>