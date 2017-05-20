<?php
header('Content-Type: text/html; charset=ISO-8859-1');
require_once "$BASE_DIR/includes/topo.php";
require_once "$BASE_DIR/includes/menu_horizontal.php";
?>
<style>
    table#pauta {
        width: 97%;
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
</style>
<script type="text/javascript"> 
    function apontarNota(campoNota, campoComentario, numMatriculaAluno) {
        var idTurma = <?php echo $turma->getIdTurma(); ?>;
        var idItemCriterioAvaliacao = <?php echo $itemCriterioAvaliacao->getIdItemCriterioAvaliacao(); ?>;
        
        if( ($(campoNota).val() != $(campoNota).data('valorAntes')) ||
            ($(campoComentario).val() != $(campoComentario).data('valorAntes')) ) {
            if( isNotaValida( $(campoNota).val() ) ) {

                $("#nota_" + numMatriculaAluno + "_erro").css("display","none");
                $("#nota_" + numMatriculaAluno + "_ok").css("display","none");
                $("#nota_" + numMatriculaAluno + "_carregando").css("display","inline");

                var posting = $.post(
                    "/coruja/espacoProfessor/pautaEletronica/apontaNota_controle.php",
                    {   acao: "apontarNota",
                        idTurma: idTurma,
                        idItemCriterioAvaliacao: idItemCriterioAvaliacao,
                        numMatriculaAluno: numMatriculaAluno,
                        stringNota: $(campoNota).val(),
                        stringComentario: $(campoComentario).val()
                    },
                    function(msg) {
                        $("#nota_" + numMatriculaAluno + "_carregando").css("display","none");
                        if( msg.substring(0,4) == "erro" ) {
                            $("#nota_" + numMatriculaAluno + "_erro").css("display","inline");
                            $("#nota_" + numMatriculaAluno + "_erro").attr("title",msg.substring(5));
                            $(campoNota).data("valido", false);
                        } else if( msg.substring(0,2) == "ok" ) {
                            $("#nota_" + numMatriculaAluno + "_ok").css("display","inline");
                            $(campoNota).data("valido", true);
                        }
                    }
                ).fail( function() {
                    $("#nota_" + numMatriculaAluno + "_erro").css("display","inline");
                    $("#nota_" + numMatriculaAluno + "_erro").attr("title",msg.substring(5));
                    $("#nota_" + numMatriculaAluno + "_carregando").css("display","none");
                }
                );
            } else {
                $("#nota_" + numMatriculaAluno + "_ok").css("display","none");
                $("#nota_" + numMatriculaAluno + "_erro").css("display","inline");
                $("#nota_" + numMatriculaAluno + "_erro").attr("title","Nota entrada incorreta. Válido de 0,0 a 10,0. Exemplos válidos: 5,5 - 2,0 - 3");
                $(campoNota).data("valido", false);
            }
        }
    }
    
    function isNotaValida(nota) {
        if( nota === "" ) return true;
        reDecimalNota = /^\d{1,2}(\,\d)?$/;
        if(!reDecimalNota.test(nota)) return false;
        var notaFloat = parseFloat( nota.replace(",",".") );
        if( notaFloat > 10.0 || notaFloat < 0.0 ) return false;
        return true;
    }

    // Executa apos o carregamento da pagina
    $(function() {
        $( "#detalheAluno" ).dialog({modal: true, autoOpen: false, closeText: "", width: 600, minWidth: 600 });
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
        $("#cancelar").click(function() {
            window.open("/coruja/espacoProfessor/pautaEletronica/pautaEletronicaAvaliacao_controle.php?idTurma=<?php echo $turma->getIdTurma(); ?>", "_top");
        });
    });
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
    <legend>Pauta Eletr&ocirc;nica - Lan&ccedil;ar Notas</legend>
    
    <?php include "$BASE_DIR/espacoProfessor/pautaEletronica/trechoCabecTurma.php"; ?>
</fieldset>

<table id="pauta">
    <thead>
        <tr>
            <th style="text-align: left; width: 60%;">Aluno</th>
            <th><?php echo $itemCriterioAvaliacao->getRotulo(); ?></th>
            <th>Coment&aacute;rio</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $tabindex = 1;
        foreach($inscricoesDePauta as $inscricao) { 
            if( $inscricao->isReclamadoPeloProfessor() ) continue; // ignora aluno com inscricao nao-deferida
            $numMatriculaAluno = $inscricao->obterMatriculaAluno()->getMatriculaAluno();
        ?>
        <tr>
            <td>
                <a id="nome-<?php echo $numMatriculaAluno; ?>" href="#">
                    <?php echo $inscricao->obterMatriculaAluno()->getAluno()->getNome(); ?></a>
                <script type="text/javascript">
                $("#nome-<?php echo $numMatriculaAluno; ?>").click(function(e){
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

            <!-- monta linha com notas desse aluno -->
            <td>
                <div style="float: left;">
                    <input type="text" 
                           class="TabOnEnter"
                           id="<?php echo $numMatriculaAluno; ?>_nota"
                           tabindex="<?php echo $tabindex; $tabindex++; ?>"
                           value="<?php echo $itemCriterioAvaliacao->exibir( $inscricao ); ?>" 
                           size="5" 
                           onfocus="$('#nota_<?php echo $numMatriculaAluno; ?>').data('valorAntes',$('nota_#<?php echo $numMatriculaAluno; ?>').val()); $('#nome-<?php echo $numMatriculaAluno; ?>').css('font-weight','bold');"
                           onblur="apontarNota(this, $('#<?php echo $numMatriculaAluno; ?>_comentario'), <?php echo "'" . $numMatriculaAluno . "'"; ?>); $('#nome-<?php echo $numMatriculaAluno; ?>').css('font-weight','normal');" />
                </div>
                <div style="float: left; display: none;">
                    <img src="/coruja/imagens/question_mark_icon.png" />
                </div>
                <div id="nota_<?php echo $numMatriculaAluno; ?>_carregando" style="float: left; display: none;">
                    <img src="/coruja/imagens/carregando.gif" />
                </div>
                <div id="nota_<?php echo $numMatriculaAluno; ?>_ok" style="float: left; display: none;">
                    <img src="/coruja/imagens/ok_icon.png" />
                </div>
                <div id="nota_<?php echo $numMatriculaAluno; ?>_erro" style='float: left; display: none;'>
                    <img src="/coruja/imagens/question_mark_icon.png" />
                </div>
            </td>
            <td>
                <textarea id="<?php echo $numMatriculaAluno; ?>_comentario" cols="40" rows="1"
                          onfocus="$('#<?php echo $numMatriculaAluno; ?>_comentario').data('valorAntes',$('#<?php echo $numMatriculaAluno; ?>_comentario').val()); $('#nome-<?php echo $numMatriculaAluno; ?>').css('font-weight','bold');"
                          onblur="apontarNota( $('#<?php echo $numMatriculaAluno; ?>_nota'), this, <?php echo "'" . $numMatriculaAluno . "'"; ?>); $('#nome-<?php echo $numMatriculaAluno; ?>').css('font-weight','normal');"><?php echo $itemCriterioAvaliacao->exibirComentario( $inscricao ); ?></textarea>
            </td>
            <script type="text/javascript">
                $(function() {
                    $("#<?php echo $numMatriculaAluno; ?>_nota").data('valorAntes',$("#<?php echo $numMatriculaAluno; ?>_nota").val());
                    $("#<?php echo $numMatriculaAluno; ?>_comentario").data('valorAntes',$("#<?php echo $numMatriculaAluno; ?>_comentario").val());
                });
            </script>
        </tr>
        <?php } ?>
        <tr>
            <td>&nbsp;</td>
            <td colspan="2">
                <form action="/coruja/espacoProfessor/pautaEletronica/pautaEletronicaAvaliacao_controle.php" method="post">
                    <input type="hidden" name="acao" value="liberarNotasItemCriterio" />
                    <input type="hidden" name="idTurma" value="<?php echo $turma->getIdTurma(); ?>" />
                    <input type="hidden" name="idItemCriterioAvaliacao" value="<?php echo $itemCriterioAvaliacao->getIdItemCriterioAvaliacao(); ?>" />
                    <input type="submit" value="Liberar" 
                           tabindex="<?php echo $tabindex; $tabindex++; ?>"
                           title="Libera a divulga&ccedil;&atilde;o das notas. Cada aluno recebe uma mensagem com a sua respectiva nota." />
                    &nbsp;
                    <input type="button" name="cancelar" id="cancelar" value="Cancelar" />
                </form>
                
            </td>
        </tr>
    </tbody>
</table>
<br/>
<?php
require_once "$BASE_DIR/includes/rodape.php";
?>
<div id="detalheAluno" title="Detalhe do Aluno" style="display: none;">
        <p>...</p> 
</div>