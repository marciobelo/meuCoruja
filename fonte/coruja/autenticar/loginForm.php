<!DOCTYPE html>
<html>
    <script type="text/javascript">
        function validar() {
            /*
             * Nome pelo qual um usuário-ator de identifica ao sistema.
             * Esse nome deve ser único entre os logins.
             * Até 20 caracteres. Mínimo de 4 caracteres.
             * Obrigatório.
             */
            nomeAcesso = document.getElementById("nomeAcesso");
            if( nomeAcesso.value.length<4 ) {
                alert("Preencha o campo Nome de Acesso (ao menos 4 caracteres)");
                nomeAcesso.focus();
                return false; // impede a submissão do formulário
            }
            /*
             * Senha de acesso criptografada, armazenada com o código hash MD5
             * representando em dígitos hexadecimais.
             * Obrigatório.
             * Restrição: a senha real do usuário (não a armazenada),
             * deve ter até 12 caracteres, e no mínimo de 4 caracteres.
             */
            senha = document.getElementById("senha");
            if( senha.value.length<4 ) {
                alert("Preencha o campo Senha (ao menos 4 caracteres)");
                senha.focus();
                return false; // impede a submissão do formulário
            }
            return true; // permite a submissão do formulário
        }
    </script>
    <head>
        <title>Coruja - Autenticar</title>
        <link rel="shortcut icon" href="/coruja/imagens/favicon.ico" type="image/x-icon"/>
        <link href="/coruja/estilos/estilo.css" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <div style="background-color:#b0e0e6;">
        <table>
            <tr>
                <td>
                    <img src="/coruja/imagens/coruja_grande.png" />
                </td>
                <td>
                    <form method="post" id="formLogin" action="/coruja/autenticar/login_controle.php" onsubmit="return validar();">
                        <input type="hidden" name="acao" id="acao" value="autenticar" />
                        <table>
                            <?php
                            // Exibe mensagem de erro quando não conseguir conectar e/ou autenticar
                            if( isset($erro) ) {
                                ?>
                            <tr>
                                <td colspan="2">
                                    <span class="erro"><?php echo htmlspecialchars($erro, ENT_QUOTES, "iso-8859-1"); ?></span>
                                </td>
                            </tr>
                            <?php
                            }
                            ?>
                            <?php
                            // Exibe mensagem de sucesso
                            if( isset($msg) ) {
                                ?>
                            <tr>
                                <td colspan="2">
                                    <span class="destaque"><?php echo htmlspecialchars($msg, ENT_QUOTES, "iso-8859-1"); ?></span>
                                </td>
                            </tr>
                            <?php
                            }
                            ?>
                            <tr>
                                <td>Nome de Acesso:</td>
                                <td><input type="text" id="nomeAcesso" name="nomeAcesso" maxlength="20" autocomplete="off" tabindex="1" />  </td>
                            </tr>
                            <tr>
                                <td>Senha:</td>
                                <td><input type="password" id="senha" name="senha" maxlength="12" autocomplete="off" tabindex="2" />  </td>
                            </tr>
                            <tr>
                                <td>Perfil:</td>
                                <td>
                                    <div id="selecionaPerfil">
                                        <select name="perfil" tabindex="3">
                                            <option value="ALUNO" <?php if($_COOKIE["perfil"] == "ALUNO") echo "selected"; ?>>ALUNO
                                            <option value="PROFESSOR" <?php if($_COOKIE["perfil"] == "PROFESSOR") echo "selected"; ?>>PROFESSOR
                                            <option value="ADMINISTRADOR" <?php if($_COOKIE["perfil"] == "ADMINISTRADOR") echo "selected"; ?>>ADMINISTRADOR
                                        </select>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>
                                    <input type="submit" value="Entrar" tabindex="4" />
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>
                                    <a href="/coruja/autenticar/login_controle.php?acao=prepararRecuperarSenha">Esqueci minha senha</a>
                                </td>
                            </tr>
                        </table>
                    </form>
                </td>
            </tr>
        </table>
        </div>
    </body>
</html>
<script type="text/javascript">
    document.getElementById("nomeAcesso").focus();
</script>