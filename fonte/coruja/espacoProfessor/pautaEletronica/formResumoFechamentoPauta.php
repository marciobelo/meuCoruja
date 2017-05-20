<?php
require_once "$BASE_DIR/includes/topo.php";
require_once "$BASE_DIR/includes/menu_horizontal.php";
?>
<script type="text/javascript">
    $(function() {
        $("#botaoCancelar").click(function() {
           window.open("/coruja/espacoProfessor/pautaEletronica/pautaEletronica_controle.php?idTurma=<?php echo $turma->getIdTurma(); ?>", "_top"); 
        });
        $("#botaoLiberarPauta").click(function() {
            $("#formLiberarPauta").submit();
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
    <legend>Pauta Eletr&ocirc;nica - Fechamento de Pauta</legend>
    <?php include "$BASE_DIR/espacoProfessor/pautaEletronica/trechoCabecTurma.php"; ?>
</fieldset>

<div style="padding: 20px 20px">
    <p>Prezado Professor(a), essa opera&ccedil;&atilde;o ir&aacute; bloquear a pauta para altera&ccedil;&otilde;es e sinalizar&aacute; para a secretaria acad&ecirc;mica que ela pode
        importar esses dados para fins de averbamento junto aos hist&oacute;ricos dos alunos.<br/>
        Essa opera&ccedil;&atilde;o deve ser sucedida pela entrega do di&aacute;rio impresso e assinado pelo professor(a).
    </p>
    <p>Pontos de atenção:</p>
    <ul>
        <li>Quantidade de tempos de aula apontados adequada? 
        <?php if($cumprimentoCargaOk) { ?>
            <img src="/coruja/imagens/ok_icon.png" />
        <?php } else { ?>
            <img src="/coruja/imagens/close_2_icon.png" />
        <?php } ?>
        </li>
        <p>Foram apontados <?php echo $qtdeTemposAulaApontados; ?> tempos de aula de um total de <?php echo $qtdeTotalTemposAulaEsperado; ?> esperados, o que perfaz <?php echo $percCumprimentoCarga; ?>&percnt; de aproveitamento da carga horária.</p>
        <li>Todas as notas e situa&ccedil;&otilde;es foram lan&ccedil;adas? 
        <?php if($estaoSituacoesDefinidas) { ?>
            <img src="/coruja/imagens/ok_icon.png" />
        <?php } else { ?>
            <img src="/coruja/imagens/close_2_icon.png" />
        <?php } ?>
        </li>
    </ul>
</div>

<input id="botaoLiberarPauta" type="button" value="Liberar Pauta" />
&nbsp;
<input id="botaoCancelar" type="button" value="Cancelar"  />

<form id="formLiberarPauta" method="post" action="/coruja/espacoProfessor/pautaEletronica/pautaEletronica_controle.php">
    <input type="hidden" name="acao" value="liberarPauta" />
    <input type="hidden" name="idTurma" value="<?php echo $turma->getIdTurma(); ?>" />
</form>

<?php
require_once "$BASE_DIR/includes/rodape.php";
?>