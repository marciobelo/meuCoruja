<?php
require_once( "$BASE_DIR/includes/topo.php");
require_once( "$BASE_DIR/includes/menu_horizontal.php");
?>
<form name="form" method="POST" id="form" action="/coruja/interno/emitir_hist_concl_controle/emitirHistConcl_controle.php?action=consultar">
    <p>&nbsp;</p>
    <table width="533" border="0" align="center">
        <tr>
            <td>
                <table width="605" border="0">
                    <tr class="tabela_form2">
                        <td class="label_obrigatorio">Escolha o tipo de consulta :</td>
                        <td width="278"><input type="hidden" name="TpCriterio" value="0"/>
                            <select name="criterio" class="form_obrigatorio" id="criterio" title="O campo tipo de consulta &eacute; obrigat&oacute;rio" onchange="submit();">
                                <option value="" class="textos">Escolha o tipo de consulta</option>
                                <option value="1" class="textos">Por Matr&iacute;cula</option>
                                <option value="2" class="textos">Por Nome</option>
                            </select>
                            <input name="acao" type="hidden" id="acao" value="novo_cadastro" />
                            <input name="tipo" type="hidden" id="tipo" value="Hist_concluintes" />
                            <input name="passo" type="hidden" id="passo" />
                            <input name="operacao" type="hidden" id="operacao" value="" />
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table width="587" border="0" align="center">
        <tr>
            <td width="594">
                <div class="div2">
                    <?php require_once("$BASE_DIR/interno/includes/consult_aluno.php"); ?>
                </div>
            </td>
        </tr>
    </table>
    <script>
        document.form.criterio.value = "<?php echo $criterio; ?>";
    </script>
</form>
<?php 
include_once "$BASE_DIR/includes/rodape.php";