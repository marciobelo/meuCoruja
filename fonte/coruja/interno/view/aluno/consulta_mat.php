
<style type="text/css">
    <!--
    .style3 {font-size: small; color: #000000; }
    .style6 {color: #000000; font-weight: bold; }
    -->
</style>
<br>
<br>
<form name="form" method="post" action="/coruja/interno/emitir_hist_concl_controle/emitirHistConcl_controle.php?action=consultar" id="form">
    <table width="597" border="0" align="center">
        <tr>
            <td width="591">

                <table width="617" border="0">

                    <tr class="tabela_form2">
                        <td width="193" class="label_obrigatorio"><span class="textos">Matr&iacute;cula do Aluno:</span><span class="textos_red">* </span></td>
                        <td width="274"><input name="matricula" type="text" class="form_obrigatorio" id="matricula" value="" size="20" maxlength="15" title="Campo matrícula é obrigatório"/></td>
                        <td width="136"><input name="Button2" type="submit" value="Pesquisar" title="Pesquisar"/>
                        <input name="operacao" type="hidden" id="operacao" value="consultar_matricula" /></td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>
    <p>
        <?php
        if ($collection != null) {
            ?>
        </p>
        <table width="729" border="0.9" align="center" cellpadding="0" cellspacing="0" bordercolor="#000000" style="border: 1px solid #000000;">
            <tr>
                <td width="114" bgcolor="#B3F0FF" class="FontMenu"><span class="style6">Matr&iacute;cula</span></td>
                <td width="237" bgcolor="#B3F0FF" class="FontMenu"><span class="style6">Nome</span></td>
                <td width="272" bgcolor="#B3F0FF" class="FontMenu"><span class="style6">Curso </span></td>
                <td colspan="2" bgcolor="#B3F0FF" class="FontMenu"><span class="style6">Situa&ccedil;&atilde;o</span></td>
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
                    <td height="20" ><span class="style3"><?php echo $concluinte->getMatriculaAluno(); ?></span></td>
                    <td><span class="style3"><?php echo $concluinte->getAluno()->getNome(); ?></span></td>
                    <td height="20"><span class="style3"><?php echo $concluinte->getMatrizCurricular()->getCurso()->getNomeCurso(); ?></span></td>
                    <td width="59" height="20"><span class="style3"><?php echo $concluinte->getSituacaoMatricula(); ?></span></td>
                    <td width="45" align="right">
                        <?php if ($concluinte->getSituacaoMatricula() == 'CONCLUÍDO') { ?>
                            <a href="/coruja/interno/emitir_hist_concl_controle/exibirFormDadosConcl_controle.php?mat=<?php echo $concluinte->getMatriculaAluno(); ?>&p=1&operacao='emitirHistConc'&dataInsc='<?php echo $concluinte->getDataMatricula(); ?>'" target="_self"><img src="/coruja/interno/imagens/pdf.gif" alt="Hist&oacute;rico de Concluintes" width="29" height="18" border="0" /></a><?php } ?></td>
                </tr>
                <?php $i++;
            } ?>
        </table>
        <div align="right"><span class="textos_red"><?php echo 'Total de Registros: ' . $i; ?></span>
<?php } else {
    if ($_POST['matricula'] != '') { ?>
            </div>
            <table class="textos_red">
                <tr>
                    <td><?php if (isset($msgErro)) {
            echo htmlspecialchars($msgErro, ENT_QUOTES, "iso-8859-1");
        } ?></td>
                </tr>
            </table>
            <br />
    <?php }
} ?>
</form>