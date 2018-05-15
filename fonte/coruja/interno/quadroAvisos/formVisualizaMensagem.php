<?php
require_once "$BASE_DIR/includes/topo.php";
require_once "$BASE_DIR/includes/menu_horizontal.php";
?>
<style>
    table #tabelaQuadroAvisos {
        width: 100%;
    }

    table #tabelaQuadroAvisos thead {
        background-color: window;
    }
    
    #tabelaQuadroAvisos tbody {
        background-color: #C2E0FF;
    }
    
    td #ultimasMensagens {
        width: 100px;
    }
    
    td #visualizaMensagem {
        width: 200px;
    }
    
    td #quadroMensagem {
        vertical-align: top;
    }
    
    #dataMensagem {
        float: left;
    }
    
    #proximo {
        float: right;
        padding-right: 20px;
    }

    #anterior {
        float: right;
        padding-right: 20px;
    }

    #posicionador {
        float: right;
        padding-right: 20px;
    }
    
    
</style>

<table id="tabelaQuadroAvisos">
    <thead>
        <tr>
            <td id="ultimasMensagens">
                <span>&Uacute;ltimas Mensagens</span>                
            </td>
            <td id="visualizaMensagem">
                <?php
                if( $mensagem != null ) {
                ?>
                <span id="dataMensagem">
                    <?php echo $mensagem->getDataMensagem()->format("d/M/Y H:i") .
                            " (" . Util::gerarTempoDecorridoTextual($mensagem->getDataMensagem()) . ")";
                            ?>
                </span>
                <?php if( $idMensagemPosterior != null ) { ?>
                    <a href="/coruja/interno/quadroAvisos/index_controle.php?acao=exibir&idMensagem=<?php echo $idMensagemPosterior; ?>"><img id="proximo" src="/coruja/imagens/arrow_right.png" /></a>
                <?php } else { ?>
                    <img id="proximo" src="/coruja/imagens/arrow_right_gray.png" />
                <?php } ?>
                <?php if( $idMensagemAnterior != null ) { ?>
                    <a href="/coruja/interno/quadroAvisos/index_controle.php?acao=exibir&idMensagem=<?php echo $idMensagemAnterior; ?>"><img id="anterior" src="/coruja/imagens/arrow_left.png" /></a>
                <?php } else { ?>
                    <img id="anterior" src="/coruja/imagens/arrow_left_gray.png" />
                <?php } ?>
                <span id="posicionador">
                    <?php echo $posicaoMensagem . " de " . $totalMensagem; ?>
                </span>
                <?php
                }
                ?>
            </td>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>
                
                <ul>
                    <?php 
                    $indice = 1;
                    foreach ($ultimasMensagens as $mensagemUltimas) {
                        echo "<li>";
                        if( $mensagemUltimas->foiLidaPor($login->getIdPessoa()) ) {
                            $cssClass = "lida";
                        } else {
                            $cssClass = "naoLida";
                        }
                        echo "<a href=\"/coruja/interno/quadroAvisos/index_controle.php?acao=exibir&idMensagem=" . $mensagemUltimas->getIdMensagem() . "\">";
                        echo "<span class=\"$cssClass\">";
                        if( $mensagemUltimas->getIdMensagem() == $mensagem->getIdMensagem() ) echo "&NestedGreaterGreater;&nbsp;";
                        echo "[" . $indice++ . "]&nbsp;";
                        echo $mensagemUltimas->getDataMensagem()->format("d/M/Y H:i");
                        echo "</span>";
                        echo "</a>";
                        echo "<br/>";
                        echo "<span>";
                        echo $mensagemUltimas->getAssunto();
                        echo "</span>";
                        echo "</li>";
                    }
                ?>
                </ul>
                
            </td>
            <td id="quadroMensagem" style="vertical-align: top;">
                <?php if( $mensagem == null ) { ?>
                <div id="nenhumaMensagem">
                    <p>Nenhuma mensagem selecionada.</p>
                </div>
                <?php } else { ?>
                <div id="mensagem">
                    <p>
                        <?php echo $mensagem->getAssunto(); ?>
                        <br/>
                        <?php echo str_replace("\n", "<br/>", $mensagem->getTexto() ); ?>
                    </p>
                </div>
                <div id="rodapeMensagem">

                    <?php
                    if( $mensagem->foiLidaPor($login->getIdPessoa()) ) {
                        echo "<span>Estou ciente!</span>";
                    } else {
                    ?>
                    <span>Não estou ciente!</span>&nbsp;
                    <form action="/coruja/interno/quadroAvisos/index_controle.php" method="post">
                        <input type="hidden" name="acao" value="darCiencia"/>
                        <input type="hidden" name="idMensagem" value="<?php echo $mensagem->getIdMensagem(); ?>" />
                        <input type="submit" value="Dar ciência"/>
                    </form>
                    <?php
                    }
                    ?>
                    <br/>
                </div>
                <?php } ?>
            </td>
        </tr>
    </tbody>
</table>

<?php
require_once "$BASE_DIR/includes/rodape.php";
?>