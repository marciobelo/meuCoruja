<?php
include("$BASE_DIR/includes/topo.php");
include("$BASE_DIR/includes/menu_horizontal.php");
?>
<script>
    function atualizarListaPeriodoLetivo() {
        document.form.action = "/coruja/interno/emitir_ocupacao_espelho/emitirOcupacaoEspelho_controle.php";
        document.form.acao.value = "emitirEspaco";
        document.form.operacao.value = "4";
        document.form.target = "_self";
        document.form.submit();
    }
</script>
<form name="form" method="post" id="form" action="/coruja/interno/emitir_ocupacao_espelho/emitirOcupacaoEspelho_controle.php?acao=exibirPDF" onsubmit="return validarRel(this);" target="_blank">
    <p>&nbsp;</p>

    <table width="666" border="0" align="center">

        <tr class="tabela_form2">
            <td width="350" class="label_obrigatorio">Escolha o Curso:</td>
            <td width="245"><input type="hidden" name="TpCriterio" value="0"/>
                <select name="siglaCurso" class="form_obrigatorio" id="siglaCurso" title="O campo Curso &eacute; obrigat&oacute;rio" onchange="atualizarListaPeriodoLetivo();">
                    <option value="" class="textos">Escolha o Curso</option>
                    <?php foreach ($collection1 as $curso) { 
                        $selecionado = ( $siglaCurso === $curso->getSiglaCurso() ? "selected" : "");
                    ?>
                        <option value="<?php echo $curso->getSiglaCurso(); ?>" class="textos" <?php echo $selecionado; ?>>
                            <?php echo $curso->getSiglaCurso() . ' - ' . $curso->getNomeCurso(); ?></option>
                    <?php } ?>
                </select>
                <input name="acao" type="hidden" id="acao" value="exibirPDF" />
                <input name="tipo" type="hidden" id="tipo" value="Hist_concluintes" />
                <input name="passo" type="hidden" id="passo" />
                <input name="operacao" type="hidden" id="criterio2" value="4" /></td>
        </tr>
<?php if( $siglaCurso !== "") { ?>
            <tr class="tabela_form2">
                <td class="label_obrigatorio">Escolha o Per&iacute;odo:</td>
                <td><select name="periodo" class="form_obrigatorio" id="periodo" title="O campo período &eacute; obrigat&oacute;rio" />
            <option value="" class="textos">Escolha o Per&iacute;odo</option>
    <?php foreach ($collection2 as $pl) { ?>
                <option value="<?php echo $pl->getIdPeriodoLetivo(); ?>" class="textos"><?php echo $pl->getSiglaPeriodoLetivo(); ?></option>
            <?php } ?>
            </select></td>
            </tr>

            <tr class="tabela_form2">
                <td class="label_obrigatorio">Escolha o Espaço:</td>
                <td><select name="espaco" class="form_obrigatorio" id="espaco" title="O campo espaço &eacute; obrigat&oacute;rio" />
            <option value="" class="textos">Escolha o tipo de consulta</option>
    <?php foreach ($collection3 as $espaco) { ?>
                <option value="<?php echo $espaco->getIdEspaco(); ?>" class="textos"><?php echo $espaco->getNome(); ?></option>
    <?php } ?>
            </select></td>
            </tr>

            <tr class="tabela_form2">
                <td class="label_obrigatorio">&nbsp;</td>
                <td><input type="submit" value="Emitir Espelho" />
                    <input name="ok" type="hidden" id="ok" /></td>
            </tr>
<?php } ?>
    </table>


    <script>
        document.form.siglaCurso.value = "<?php echo $siglaCurso; ?>";
    </script>
</form>
<?php include_once "$BASE_DIR/includes/rodape.php";