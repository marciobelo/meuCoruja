<?php
require_once "$BASE_DIR/includes/topo.php";
require_once "$BASE_DIR/includes/menu_horizontal.php";
require_once "$BASE_DIR/classes/Util.php";
?>

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

<script language="JavaScript">
    function validar() {
        /*
         * Nome de acesso deve ter no mínimo de 4 caracteres.
         */
        nomeAcesso = document.getElementById("nomeAcesso");
        if( nomeAcesso.value.length<4 ) {
            alert("Preencha o campo Nome de Acesso (ao menos 4 caracteres)");
            nomeAcesso.focus();
            return false; // impede a submissão do formulário
        }

        return true; // permite a submissão do formulário
    }
</script>

<fieldset id="coruja">
    <legend>Criar Login</legend>
    <form method="post" name="criarLogin" id="criarLogin"
          action="/coruja/interno/manter_login/manterLogin_controle.php"
          onsubmit="return validar();" >
        <input type="hidden" name="acao" value="criarLogin" />
        <input type="hidden" name="idPessoa" value="<?php echo $formLogin->getIdPessoa(); ?>" />

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
                    <input type="text" name="nomeAcesso" id="nomeAcesso" size="20" maxlength="20" autocomplete="off" tabindex="1" value="<?php echo $formLogin->getNomeAcesso(); ?>" />
                </td>
            </tr>

        </table>
        <input type="submit" value="Criar Login" tabindex="4" />
    </form>
</fieldset>
<br/>
<?php
require_once "$BASE_DIR/includes/rodape.php";
?>
<script type="text/javascript">
    document.criarLogin.nomeAcesso.focus();
</script>