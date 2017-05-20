<html>
    <head>
        <title>Coruja - Validar Log</title>
        <link href="/coruja/estilos/estilo.css" rel="stylesheet" type="text/css" />
        <script>
            function submeter() {
                var form = document.getElementById("formValidarLog");
                form.submit();
            }
        </script>
    </head>
    <body>
        <table>
            <tr>
                <td>
                    <img src="/coruja/imagens/coruja.png" alt="Logo Coruja" />
                </td>
                <td>
                    <img src="/coruja/imagens/logorj.jpg" alt="Logo IST-Rio" />
                </td>
                <td>
                    <form method="post" id="formValidarLog" action="/coruja/autenticar/login_controle.php">
                        <input type="hidden" name="acao" value="validarLog" />
                        <h1><?php echo htmlspecialchars("Log de alterações na conta de: " .
                            $usuario->getNomeAcesso(), ENT_QUOTES, "iso-8859-1"); ?></h1>
                        <?php
                        // Exibe mensagem de erro quando existir
                        if( isset($erro) ) {
                        ?>
                        <p>
                            <span class="erro"><?php echo htmlspecialchars($erro, ENT_QUOTES, "iso-8859-1"); ?></span>
                        </p>
                        <?php
                        }
                        ?>
                        <p>
                            <input type="button" value="Validar" onclick="javascript:submeter();" />
                        </p>
                        <table class="simples">
                            <!-- cabeçalho -->
                            <thead>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>Data/Hora</td>
                                    <td>Descri&ccedil;&atilde;o</td>
                                    <td>Funcionalidade</td>
                                </tr>
                            </thead>
                            <!-- Registro de Log do Usuario -->
                            <tbody>
                                <?php
                                $logs = $usuario->getLogsNaoConferidos();
                                foreach($logs as $log) {
                                    ?>
                                <tr>
                                    <td>
                                        <input type="checkbox" name="confere[]"
                                               value="<?php echo $log->idCasoUso . ";" . $log->dataHora; ?>"
                                                   <?php if($log->critico=="NÃO") echo "checked"; ?>
                                               />
                                    </td>
                                    <td>
                                            <?php echo $log->dataHora; ?>
                                    </td>
                                    <td>
                                            <?php echo $log->descricaoLog; ?>
                                    </td>
                                    <td>
                                            <?php echo $log->descricaoFuncao; ?>
                                    </td>

                                </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                        <input type="button" value="Validar" onclick="javascript:submeter();" />
                    </form>

                </td>
            </tr>
        </table>
    </body>
</html>