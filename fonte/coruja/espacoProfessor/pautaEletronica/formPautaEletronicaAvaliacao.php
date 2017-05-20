<?php
header('Content-Type: text/html; charset=ISO-8859-1');
require_once "$BASE_DIR/includes/topo.php";
require_once "$BASE_DIR/includes/menu_horizontal.php";
?>
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
    <legend>Pauta Eletr&ocirc;nica - Avalia&ccedil;&atilde;o</legend>
    
    <?php include "$BASE_DIR/espacoProfessor/pautaEletronica/trechoCabecTurma.php"; ?>
</fieldset>
<div id="visaoPauta">
    <input type="radio" id="radioVisaoPautaPresenca" name="radio" /><label for="radioVisaoPautaPresenca">Presen&ccedil;as</label>
    <input type="radio" id="radioVisaoPautaAvaliacao" name="radio" checked="checked" /><label for="radioVisaoPautaAvaliacao">Avalia&ccedil;&atilde;o</label>
</div>
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
    
    .ui-buttonset {
        padding-left: 5px;
    }
    
    .ui-button-text {
        font-size: small;
    }
</style>
<script type="text/javascript">
    function lancarNotas( idTurma, idItemCriterioAvaliacao ) {
        window.open("/coruja/espacoProfessor/pautaEletronica/pautaEletronicaAvaliacao_controle.php?acao=exibirAvaliacaoLancarNotas&idTurma=" + idTurma + "&idItemCriterioAvaliacao=" + idItemCriterioAvaliacao, "_top");
    }
    
    function reabrirItemCriterioAvaliacaoNotas( idTurma, idItemCriterioAvaliacao ) {
        window.open("/coruja/espacoProfessor/pautaEletronica/pautaEletronicaAvaliacao_controle.php?acao=reabrirItemCriterioAvaliacaoNotas&idTurma=" + idTurma + "&idItemCriterioAvaliacao=" + idItemCriterioAvaliacao, "_top");
    }
    
    $(function() {
        $( "#detalheAluno" ).dialog({modal: true, autoOpen: false, closeText: "", width: 600, minWidth: 600 });
        $( "#visaoPauta" ).buttonset();
        $( "#radioVisaoPautaPresenca" ).click( function() {
            window.open("/coruja/espacoProfessor/pautaEletronica/pautaEletronica_controle.php?idTurma=<?php echo $turma->getIdTurma(); ?>", "_top");
        });
        $("#botaoLiberarPauta").click(function() {
            window.open("/coruja/espacoProfessor/pautaEletronica/pautaEletronica_controle.php?acao=prepararLiberarPauta&idTurma=<?php echo $turma->getIdTurma(); ?>", "_top");
        });
    });
</script>   
<table id="pauta">
    <thead>
        <tr>
            <th style="text-align: left; width: 45%;">Aluno</th>
            <th id="totalFaltas">T.F.</th>
            
            <!-- monta colunas nos itens de critério de avaliação da turma -->
            <?php
            foreach ($itensCriterioAvaliacao as $itemCriterioAvaliacao) {
                echo "<th>";
                echo $itemCriterioAvaliacao->getRotulo();
                echo "<br/>";
                if( $itemCriterioAvaliacao->isLancado() ) {
                    if( ! $turma->isAvaliacaoLiberada( $itemCriterioAvaliacao ) ) {
                        echo "<input id='lancarNotas" . $itemCriterioAvaliacao->getIdItemCriterioAvaliacao() . 
                                "' type='button' value='Apontar' onclick='lancarNotas(" . $turma->getIdTurma() . "," . $itemCriterioAvaliacao->getIdItemCriterioAvaliacao() . ");' />";
                    } else {
                        echo "<img src=\"/coruja/imagens/stock_lock.png\"/>";
                        echo "<input id='reabrirItemCriterioAvaliacaoNotas" . $itemCriterioAvaliacao->getIdItemCriterioAvaliacao() . 
                                "' type='button' value='Reabrir' onclick='reabrirItemCriterioAvaliacaoNotas(" . $turma->getIdTurma() . "," . $itemCriterioAvaliacao->getIdItemCriterioAvaliacao() . ");' />";
                    }
                }
                echo "</th>";
            }
            ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach($inscricoesDePauta as $inscricao) { 
            $numMatriculaAluno = $inscricao->obterMatriculaAluno()->getMatriculaAluno();
        ?>
        <tr>
            <td>
                <a id="nome-<?php echo $numMatriculaAluno; ?>"
                       href="#"
                       style="<?php if($inscricao->isReclamadoPeloProfessor()) echo 'text-decoration: line-through;' ?>" >
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
            <td><?php echo $inscricao->obterFaltasLancadas(); ?></td>
            <!-- monta linha com notas desse aluno -->
            <?php
            foreach ($itensCriterioAvaliacao as $itemCriterioAvaliacao) {
                echo "<td>";
                echo $itemCriterioAvaliacao->exibir( $inscricao );
                echo "</td>";
            }
            ?>
            
        </tr>
        <?php } ?>
    </tbody>
</table>
<br/>
<div>
    <input id="botaoLiberarPauta" type="button" value="Liberar Pauta..." title="Libera a pauta da turma para a secretaria acad&ecirc;mica importar os dados." />
</div>
<br/>
<?php
require_once "$BASE_DIR/includes/rodape.php";
?>
<div id="detalheAluno" title="Detalhe do Aluno" style="display: none;">
        <p>...</p> 
</div>