<?php
require_once "$BASE_DIR/includes/topo.php";
require_once "$BASE_DIR/includes/menu_horizontal.php";
require_once "$BASE_DIR/classes/Util.php";
?>
<script type="text/javascript">
function abrirJanelaEditaFoto() {
    var idPessoa = <?php echo $formLogin->getIdPessoa(); ?>;
    var theURL         = "/coruja/interno/manter_login/webcambiblio/WebCamBiblio.php?idPessoa=" + idPessoa;
    var winName        = "WebCamBiblio";
    var features       = "scrollbars=yes,width=520,height=300";
    window.open(theURL,winName,features);
    document.formPrepararAlterarFotoLogin.submit();
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
    <legend>Alterar Foto Login</legend>

        <table>
            <tr>
                <td>Nome:</td>
                <td>
                    <span><?php echo $formLogin->getNome(); ?></span>
                </td>
            </tr>
            <tr>
                <td>Nome Acesso:</td>
                <td>
                    <span><?php echo $formLogin->getNomeAcesso(); ?></span>
                </td>
            </tr>
            <tr>
                <td>Foto Atual:</td>
                <td>
                    <img src="/coruja/baseCoruja/controle/obterFoto_controle.php?idPessoa=<?php echo $formLogin->getIdPessoa(); ?>" width="100" height="90" />
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <form name="formPrepararAlterarFotoLogin" action="/coruja/interno/manter_login/manterLogin_controle.php" method="get">
                        <input type="hidden" name="acao" value="prepararAlterarFotoLogin" />
                        <input type="hidden" name="idPessoa" value="<?php echo $formLogin->getIdPessoa(); ?>" />
                        <input type="button" value="Capturar da WebCam" onclick="javascript:abrirJanelaEditaFoto();" />
                    </form>
            </tr>
            <tr>
                <td>Arquivo nova foto:</td>
                <td>
                    <form enctype="multipart/form-data" method="post" name="alterarFotoLogin" id="alterarFotoLogin"
                          action="/coruja/interno/manter_login/manterLogin_controle.php">
                        <input type="hidden" name="acao" value="alterarFotoLogin" />
                        <input type="hidden" name="idPessoa" id="idPessoa" value="<?php echo $formLogin->getIdPessoa(); ?>" />
                        <input type="hidden" name="nomeAcesso" value="<?php echo $formLogin->getNomeAcesso(); ?>" />
                        <input type="hidden" name="MAX_FILE_SIZE" value="307200" />
                        <input type="file" name="foto" />
                        <input type="submit" value="Alterar Foto" tabindex="4" />
                        <span>Tamanho máximo 300Kb</span>
                    </form>
                </td>
            </tr>
        </table>
</fieldset>
<br/>
<?php
require_once "$BASE_DIR/includes/rodape.php";