<?php
header('Content-Type: text/html; charset=ISO-8859-1');
require_once "$BASE_DIR/includes/topo.php";
require_once "$BASE_DIR/includes/menu_horizontal.php";
?>

<script type="text/javascript">
    $(function() {
        $( "#detalheAluno" ).dialog({modal: true, autoOpen: false, closeText: "", width: 600, minWidth: 600 });
        $( "#visaoPauta" ).buttonset();
        $( "#radioVisaoPautaAvaliacao" ).click( function() {
            window.open("/coruja/espacoProfessor/pautaEletronica/pautaEletronicaAvaliacao_controle.php?idTurma=<?php echo $turma->getIdTurma() ?>", "_top" );            
        });
        $( document ).tooltip();
    });
    
    
    function apontarDiaLetivo( campo, idTurma, numMatriculaAluno, data ) {
        if($(campo).val() != $(campo).data('valorAntes')) {

            var acao = "apontarDiaLetivo";
            if( campo.id == "conteudo" ) {
                acao = "apontarDiaLetivoConteudo";
                prefixoIdImagem = "conteudo";
            } else if( campo.id == "anotacaoProfessor" ) {
                acao = "apontarDiaLetivoAnotacao";
                prefixoIdImagem = "anotacao";
            } else {
                prefixoIdImagem = "p-" + idTurma + "_" + numMatriculaAluno;
            }

            $("#" + prefixoIdImagem + "_erro").css("display","none");
            $("#" + prefixoIdImagem + "_ok").css("display","none");
            $("#" + prefixoIdImagem + "_carregando").css("display","inline");

            var posting = $.post(
                "/coruja/espacoProfessor/pautaEletronica/apontaDiaLetivo_controle.php",
                {   acao: acao,
                    idTurma: idTurma,
                    numMatriculaAluno: numMatriculaAluno,
                    data: data,
                    stringValor: campo.value,
                },
                function(msg) {
                    $("#" + prefixoIdImagem + "_carregando").css("display","none");
                    if( msg.substring(0,4) == "erro" ) {
                        $("#" + prefixoIdImagem + "_erro").css("display","inline");
                        $("#" + prefixoIdImagem + "_erro").attr("title",msg.substring(5));
                        $(campo).data("valido", false);
                    } else if( msg.substring(0,2) == "ok" ) {
                        $("#" + prefixoIdImagem + "_ok").css("display","inline");
                        $(campo).data("valido", true);
                    }
                }
            ).fail( function() {
                        $("#" + prefixoIdImagem + "_erro").css("display","inline");
                        $("#" + prefixoIdImagem + "_erro").attr("title",msg.substring(5));
                        $("#" + prefixoIdImagem + "_carregando").css("display","none");                    
                }
            );

        }
    }
    
    function mudarData() {
        window.open("/coruja/espacoProfessor/pautaEletronica/pautaEletronica_controle.php?idTurma=" 
        + "<?php echo $turma->getIdTurma(); ?>&data=" + $("#navData").val(),"_top");
    }
    
    function validarFormLiberar() {
        var erro = false;
        $('input[id|="p"]').each(function() {
            if( !$(this).data("valido") ) erro = true;
        });
        if( !$("#conteudo").data("valido") ) erro = true;
        if( erro ) {
            window.alert("Os lançamentos não estão completos!");
        }
        return !erro;
    }

    function reclamarAluno() {
        window.open("/coruja/espacoProfessor/pautaEletronica/reclamarAluno_controle.php?idTurma=<?php echo $turma->getIdTurma(); ?>&data=<?php echo $d->getData()->format("Y-m-d"); ?>","_top");
    }
    
    function gerarPauta() {
        window.open("/coruja/nort/controle/emitirDiarioDeClasse_controle.php?acao=gerarDiarioProfessor&idTurma=<?php echo $turma->getIdTurma() ?>", "_blank" );
    }
    
    function gerarAta() {
        window.open("/coruja/espacoProfessor/pautaEletronica/emitirAtaAvaliacao_controle.php?idTurma=<?php echo $turma->getIdTurma() ?>", "_new", "width=800, height=600, toolbar=yes, scrollbars=yes, resizable=yes");
    }
</script>

<div id="msgsErro">
    <!-- Mensagens de erro, se houver -->
    <?php
    if(count($msgsErro) > 0) {
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
</div>

<fieldset>
    <legend>Pauta Eletr&ocirc;nica</legend>
    
    <?php include "$BASE_DIR/espacoProfessor/pautaEletronica/trechoCabecTurma.php"; ?>
</fieldset>

<div id="visaoPauta">
    <input type="radio" id="radioVisaoPautaPresenca" name="radio" checked="checked" /><label for="radioVisaoPautaPresenca">Presen&ccedil;as</label>
    <input type="radio" id="radioVisaoPautaAvaliacao" name="radio" /><label for="radioVisaoPautaAvaliacao">Avalia&ccedil;&atilde;o</label>
</div>

<style>
    table#pauta {
        width: 99%;
        border-collapse:collapse;
        margin: 0px 5px;
    }
   
    table#pauta, table#pauta th, table#pauta td {
        border: 1px solid black;
        padding: 5px 5px;
    }
   
    table#pauta tbody tr:nth-child(even) {
        background-color: lightgoldenrodyellow;
    }    

    table#pauta tbody tr:nth-child(odd) {
        background-color: white;
    }

    .ui-buttonset {
        padding-left: 5px;
    }
    
    .ui-button-text {
        font-size: small;
    }
</style>

    <table id="pauta">
        <thead>
            <tr>
                <th style="text-align: left; width: 45%;">Aluno</th>
                <th style="width: 10%;">
                    <?php if( $dmenos1 != null ) { 
                        echo $dmenos1->getData()->format("d/m/y"); 
                        echo ",&nbsp;";
                        echo Util::obterSiglaDiaSemana($dmenos1->getData());
                        echo "<br/>";
                        echo $dmenos1->getQtdeTempos() . "T";
                    } else {
                    ?>
                    <img src="/coruja/imagens/abort_icon.png" />
                    <?php } ?>
                </th>
                <th style="width: 35%;">
                    <table width="100%">
                        <tbody>
                            <tr>
                                <td>
                                    <span><?php echo $qtdeTotalDiasAnterior; ?></span>
                                    <?php if( $dmenos1 != null ) { ?>
                                    <a href="<?php echo '/coruja/espacoProfessor/pautaEletronica/pautaEletronica_controle.php?idTurma=' . 
                                            $d->getTurma()->getIdTurma() . '&data=' . $dmenos1->getData()->format("Y-m-d"); ?>">
                                        <img src="/coruja/imagens/arrow_left.png" />
                                    </a>
                                    <?php } else { ?>
                                        <img src="/coruja/imagens/arrow_left_gray.png" />
                                    <?php } ?>
                                    <span><?php echo $qtdeTotalDiasAnteriorEmAberto; ?></span>
                                </td>
                                <td>
                                    <a href="/coruja/espacoProfessor/pautaEletronica/criarNovoDiaLetivo_controle.php?idTurma=<?php echo $d->getTurma()->getIdTurma() ?>&data=<?php echo $d->getData()->format("Y-m-d"); ?>">
                                        <img src="/coruja/imagens/add_event.png" />
                                    </a>
                                </td>
                                <td>
                                    <select id="navData" name="navData" onchange="mudarData();">
                                        <?php foreach($listaDataDiaLetivo as $dataDiaLetivo) { ?>
                                        <option value="<?php echo $dataDiaLetivo->format("Y-m-d") ?>" 
                                                <?php if($dataDiaLetivo == $d->getData()) echo " selected" ?> >
                                            <?php echo $dataDiaLetivo->format("d/m/y") ?>
                                        </option>
                                        <?php } ?>
                                    </select>
                                    <?php echo Util::obterSiglaDiaSemana($d->getData()) . 
                                            ",&nbsp;" . $d->getQtdeTempos() . "T"; ?>
                                </td>
                                <td>
                                    <span><?php echo $qtdeTotalDiasPosterior; ?></span>
                                    <?php if( $dmais1 != null ) { ?>
                                    <a href="<?php echo '/coruja/espacoProfessor/pautaEletronica/pautaEletronica_controle.php?idTurma=' . 
                                            $d->getTurma()->getIdTurma() . '&data=' . $dmais1->getData()->format("Y-m-d") ; ?>">
                                        <img src="/coruja/imagens/arrow_right.png" /></a>
                                    <?php } else { ?>
                                        <img src="/coruja/imagens/arrow_right_gray.png" /></a>
                                    <?php } ?>
                                    <span><?php echo $qtdeTotalDiasPosteriorEmAberto; ?></span>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                </th>
                <th style="width: 10%;">
                    <?php if( $dmais1 != null ) { 
                        echo $dmais1->getData()->format("d/m/y"); 
                        echo ",&nbsp;";
                        echo Util::obterSiglaDiaSemana($dmais1->getData());
                        echo "<br/>";
                        echo $dmais1->getQtdeTempos() . "T";
                    } else {
                    ?>
                    <img src="/coruja/imagens/abort_icon.png" />
                    <?php } ?>
                </th>
                <th>
                    TF
                </th>
            </tr>
        </thead>
        <tbody>
            <?php
            $tabindex = 1;
            foreach( $inscricoesDePauta as $inscricao ) {
                $idTurma = $inscricao->getIdTurma();
                $numMatriculaAluno = $inscricao->obterMatriculaAluno()->getMatriculaAluno();
                $idInscricao = "p-" . $idTurma  . "_" . $numMatriculaAluno;
            ?>
            <tr>
                <td>
                    <a id="nome-<?php echo $idInscricao; ?>"
                       href="#"
                       style="<?php if($inscricao->isReclamadoPeloProfessor()) echo 'text-decoration: line-through;' ?>" >
                           <?php echo $inscricao->obterMatriculaAluno()->getAluno()->getNome(); ?></a>

                    <script type="text/javascript">
                    $("#nome-<?php echo $idInscricao; ?>").click(function(e){
                        e.preventDefault();
                        $.get("/coruja/espacoProfessor/pautaEletronica/gerarTrechoDetalheAluno_controle.php",
                            {
                                numMatriculaAluno: '<?php echo $inscricao->obterMatriculaAluno()->getNumMatriculaAluno(); ?>'
                            },
                            function(html) {
                                $("#detalheAluno").html(html);
                                $('#detalheAluno').dialog('open');
                            }
                            );
                    });
                    </script>
                </td>
                <td>
                <?php if( $dmenos1 != null ) {
                    $strResumo = $inscricao->obterResumoApontamentoDiaLetivo( $dmenos1 ); 
                    gerarHtmlApontamentoDiaLetivoInscricao($strResumo);
                } ?></td>
                <td>
                    <div style="float: left;">
                    <?php 
                    if( $d->getDataLiberacao() != null ) {
                        $strResumo = $inscricao->obterResumoApontamentoDiaLetivo( $d );
                        gerarHtmlApontamentoDiaLetivoInscricao($strResumo);
                    } else {
                    ?>
                    <input type="text"
                           class="TabOnEnter"
                           name="<?php echo $idInscricao; ?>"
                           id="<?php echo $idInscricao; ?>"
                           size="4"
                           tabindex="<?php echo $tabindex; $tabindex++; ?>"
                           maxlength="<?php echo $d->getQtdeTempos(); ?>"
                           value="<?php echo $inscricao->obterResumoApontamentoDiaLetivo( $d ) ?>"
                           onfocus="$('#<?php echo $idInscricao; ?>').data('valorAntes',$('#<?php echo $idInscricao; ?>').val()); $('#nome-<?php echo $idInscricao; ?>').css('font-weight','bold');"
                           onblur="apontarDiaLetivo(<?php echo 'this,' .$idTurma . ',\'' . $numMatriculaAluno . '\',\'' . $d->getData()->format('Y-m-d') . '\'' ; ?>); $('#nome-<?php echo $idInscricao; ?>').css('font-weight','normal');" />
                    <?php
                    }
                    ?>
                    </div>
                    <div style="float: left; display: none;">
                        <img src="/coruja/imagens/question_mark_icon.png" />
                    </div>
                    <div id="<?php echo $idInscricao .  "_carregando"; ?>" style="float: left; display: none;">
                        <img src="/coruja/imagens/carregando.gif" />
                    </div>
                    <div id="<?php echo $idInscricao .  "_ok"; ?>" style="float: left; display: none;">
                        <img src="/coruja/imagens/ok_icon.png" />
                    </div>
                    <div id="<?php echo $idInscricao .  "_erro"; ?>" style='float: left; display: none;'>
                        <img src="/coruja/imagens/question_mark_icon.png" />
                    </div>
                    <script type="text/javascript">
                        $(function() {
                            $("#<?php echo $idInscricao; ?>").data('valorAntes',$("#<?php echo $idInscricao; ?>").val());
                            if( $("#<?php echo $idInscricao; ?>").val().trim() != "" ) {
                                $("#<?php echo $idInscricao; ?>").data('valido', true);
                            } else {
                                $("#<?php echo $idInscricao; ?>").data('valido', false);
                            }
                            if( !$("#<?php echo $idInscricao; ?>").data('valido') ) {
                                $("#<?php echo $idInscricao . "_erro"; ?>").css("display", "inline");
                                $("#<?php echo $idInscricao . "_erro"; ?>").attr("title", "Não preenchido!");
                            }
                        });
                    </script>
                </td>
                <td>
                <?php 
                if( $dmais1 != null) {
                    $strResumo = $inscricao->obterResumoApontamentoDiaLetivo( $dmais1 );
                    gerarHtmlApontamentoDiaLetivoInscricao($strResumo);
                }
                ?>
                </td>
                <td><?php echo $inscricao->obterFaltasLancadas(); ?></td>
            </tr>
            <?php } ?>

            <script type="text/javascript">
            $(document).on("keypress", ".TabOnEnter" , function(e) {
                if( e.keyCode ==  13 && !e.shiftKey ) {
                    var nextElement = $('[tabindex="' + (this.tabIndex+1)  + '"]');
                    if(nextElement.length ) {
                        nextElement.focus();
                        nextElement.select();
                    } else $('[tabindex="1"]').focus();
                }
            });
            $('[tabindex="1"]').focus();
            $('[tabindex="1"]').select();
            </script>

            <!-- conteudo programático -->
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>
                    <div style="float: left;">
                        <span>Conte&uacute;do Program&aacute;tico:</span>
                        <br/>
                        <textarea id="conteudo" name="conteudo" class="TabOnEnter"
                            tabindex="<?php echo $tabindex; $tabindex++; ?>"
                            onblur="apontarDiaLetivo(<?php echo 'this,' .$idTurma . ',\'' . $numMatriculaAluno . '\',\'' . $d->getData()->format('Y-m-d') . '\'' ; ?>);"
                            <?php if( $d->getDataLiberacao() != null ) echo "readonly=\"true\""; ?>
                            rows="2" cols="50"><?php echo $d->getConteudo(); ?></textarea>
                        <script type="text/javascript">
                            $("#conteudo").data('valorAntes',$("#conteudo").val());
                            if( $("#conteudo").val().trim() != "" ) {
                                $("#conteudo").data('valido',true);
                            } else {
                                $("#conteudo").data('valido',false);
                            }
                        </script>
                    </div>
                    <div id="conteudo_carregando" style="float: left; display: none;">
                        <img src="/coruja/imagens/carregando.gif" />
                    </div>
                    <div id="conteudo_ok" style="float: left; display: none;">
                        <img src="/coruja/imagens/ok_icon.png" />
                    </div>
                    <div id="conteudo_erro" style="float: left; display: none;">
                        <img src="/coruja/imagens/question_mark_icon.png" />
                    </div>
                    <script type="text/javascript">
                    if( !$("#conteudo").data('valido') ) {
                        $("#conteudo_erro").css("display","inline");
                        $("#conteudo_erro").attr("title","Não preenchido!");
                    }
                    </script>
                    <div style="float: left;">
                        <span>Anota&ccedil;&otilde;es do Professor:</span>
                        <br/>
                        <textarea id="anotacaoProfessor" name="anotacaoProfessor" class="TabOnEnter"
                            tabindex="<?php echo $tabindex; $tabindex++; ?>"
                            onblur="apontarDiaLetivo(<?php echo 'this,' .$idTurma . ',\'' . $numMatriculaAluno . '\',\'' . $d->getData()->format('Y-m-d') . '\'' ; ?>);"
                            <?php if( $d->getDataLiberacao() != null ) echo "readonly=\"readonly\""; ?>
                            rows="2" cols="50"><?php echo $d->getAnotacaoProfessor(); ?></textarea>
                        <script type="text/javascript">
                            $("#anotacaoProfessor").data('valorAntes',$("#anotacaoProfessor").val());
                            $("#anotacaoProfessor").data('valido', true);
                        </script>
                    </div>
                    <div id="anotacao_carregando" style="float: left; display: none;">
                        <img src="/coruja/imagens/carregando.gif" />
                    </div>
                    <div id="anotacao_ok" style="float: left; display: none;">
                        <img src="/coruja/imagens/ok_icon.png" />
                    </div>
                </td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td colspan="2">&nbsp;</td>
                <td colspan="3">
                    <?php if( $d->getDataLiberacao() == null ) { ?>
                        <form name="formLiberar" method="post"
                              action="/coruja/espacoProfessor/pautaEletronica/apontaDiaLetivo_controle.php" 
                              onsubmit="return validarFormLiberar();">
                            <input type="hidden" name="acao" value="liberarDiaLetivoTurma" />
                            <input type="hidden" name="idTurma" value="<?php echo $turma->getIdTurma(); ?>" />
                            <input type="hidden" name="data" value="<?php echo $d->getData()->format("Y-m-d"); ?>" />
                            <input type="submit" value="Liberar"
                                   tabindex="<?php echo $tabindex; $tabindex++; ?>" />
                        </form>
                    <?php } else { ?>
                    <form name="formReabrir" method="post"
                          action="/coruja/espacoProfessor/pautaEletronica/apontaDiaLetivo_controle.php">
                        <input type="hidden" name="acao" value="reabrirDiaLetivoTurma" />
                        <input type="hidden" name="idTurma" value="<?php echo $turma->getIdTurma(); ?>" />
                        <input type="hidden" name="data" value="<?php echo $d->getData()->format("Y-m-d"); ?>" />
                        <input type="submit" value="Reabrir" 
                               tabindex="<?php echo $tabindex; $tabindex++; ?>" />
                    </form>
                    <?php } ?>
                </td>
            </tr>
        </tbody>
    </table>
    <br/>
    <input type="button" value="Reclamar Aluno" onclick="reclamarAluno();" 
           title="Algum aluno que comparece n&atilde;o est&aacute; em sua pauta?" />
    &nbsp;
    <input type="button" value="Gerar Pauta" onclick="gerarPauta();" 
           title="Gerar em PDF a pauta já liberada" />
    &nbsp;
    <input type="button" value="Gerar Ata" onclick="gerarAta();" 
           title="Gera uma ata de presença a uma avaliação" />
    &nbsp;
<?php
require_once "$BASE_DIR/includes/rodape.php";
?>

<div id="detalheAluno" title="Detalhe do Aluno" style="display: none;">
        <p>...</p> 
</div>
<?php
    function gerarHtmlApontamentoDiaLetivoInscricao( $strResumo ) {
        for($i=0 ; $i < strlen($strResumo); $i++) {
            if($strResumo[$i] == 'P') {
                echo "<img src=\"/coruja/imagens/letter_p_blue.png\"/>";
            } else if($strResumo[$i] == 'F') {
                echo "<img src=\"/coruja/imagens/letter_f_red.png\"/>";
            } else {
                echo "<img src=\"/coruja/imagens/minus.png\"/>";
            }
        }              
    }
?>