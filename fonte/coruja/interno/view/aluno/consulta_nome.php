<style type="text/css">
    <!--
    .style3 {font-size: small; color: #000000; }
    -->
</style>
<br/>
<br/>
<script>
$(function()
{
    $("#nomeAluno").focus(); 
});
</script>
<form name="form" method="post"  action="/coruja/interno/emitir_hist_concl_controle/emitirHistConcl_controle.php?action=consultar" id="form">
    <table width="605" border="0">
        <tr class="tabela_form2">
            <td width="82" class="label_obrigatorio"><span class="textos">Nome:</span><span class="textos_red">* </span></td>
            <td width="279"><input name="nome" id="nomeAluno" type="text" style="text-transform: uppercase;" class="form_obrigatorio" id="nome" value="" size="40" maxlength="50" title="Campo nome &eacute; obrigat&oacute;rio"/></td>
            <td width="230"><input name="Button2" type="submit" value="Pesquisar" title="Pesquisar"/>
                <input name="operacao" type="hidden" id="operacao" value="consultar_nome" /></td>
        </tr>
    </table>
    <br />
    <?php if ($collection != null) { ?>
        <table width="755" border="0.9" align="center" cellpadding="0" cellspacing="0" bordercolor="#000000" style="border: 1px solid #000000;">
            <tr>
                <td width="82" bgcolor="#B3F0FF" class="FontMenu"><strong>Matr&iacute;cula</strong></td>
                <td width="366" bgcolor="#B3F0FF" class="FontMenu"><strong>Nome do Aluno </strong></td>
                <td width="113" bgcolor="#B3F0FF" class="FontMenu"><strong>Nascimento</strong></td>
                <td colspan="2" bgcolor="#B3F0FF" class="FontMenu"><strong>e-mail </strong></td>
            </tr>
            <?php
            $i = 0;
            foreach ($collection as $concluinte) {
                if ($idcor == 1) { //atribui um linha de cor de um tipo e outra de outro tipo
                    $cor = '#EEEEEE';

                    $idcor = 0;
                } else {
                    $cor = '#FFFFFF';

                    $idcor = 1;
                }
                ?>

                <tr bgcolor="<?php print $cor; ?>">
                    <td height="20" ><span class="style3"><?php echo $concluinte['matriculaAluno']; ?></span></td>
                    <td height="20"><span class="style3"><?php echo $concluinte['nome']; ?></span></td>
                    <td height="20"><span class="style3"><?php echo $concluinte['dataNascimento']; ?></span></td>
                    <td width="200" height="20"><span class="style3"><?php echo $concluinte['email']; ?></span></td>
                    <td width="29" align="right">
        <?PHP if ($concluinte['situacaoMatricula'] == 'CONCLUÍDO') { ?>
                            <a href="/coruja/interno/emitir_hist_concl_controle/exibirFormDadosConcl_controle.php?mat=<?php echo $concluinte['matriculaAluno']; ?>&p=1&operacao='emitirHistConc'&dataInsc=<?php echo $concluinte['dataMatricula']; ?>" target="_self"><img src="/coruja/interno/imagens/pdf.gif" alt="Hist&oacute;rico de Concluintes" width="29" height="18" border="0" /></a><?PHP } ?></td>
                </tr>
        <?php $i++;
    } ?>
        </table>

        <div align="right"><span class="textos_red"><?php echo 'Total de Registros: ' . $i; ?></span>
<?php } else {
    if ($collection == null) { ?>
            </div>

            <table class="textos_red">
                <tr><td><?php if (isset($msgErro)) {
            echo htmlspecialchars($msgErro, ENT_QUOTES, "iso-8859-1");
        } ?></td></tr>
            </table><br>

    <?php }
} ?>	
</form>