<?php
require_once "$BASE_DIR/includes/topo.php";
require_once "$BASE_DIR/includes/menu_horizontal.php";
require_once "$BASE_DIR/classes/Util.php";
?>
<script type="text/javascript">
    $(function()
    {
        $("#gerarHistorico").click( function() 
        {
            var opcoes = "";
            if( $("#exibeComponentesCurricularesPendentes").is(":checked") )
            {
                opcoes += "&";
                opcoes += "exibeComponentesCurricularesPendentes=1";
            }
            if( $("#exibeHistoricoDeSituacaoDeMatricula").is(":checked") )
            {
                opcoes += "&";
                opcoes += "exibeHistoricoDeSituacaoDeMatricula=1";
            }
            if( $("#exibeListaDeDocumentosPendentes").is(":checked") )
            {
                opcoes += "&";
                opcoes += "exibeListaDeDocumentosPendentes=1";
            }
            window.open( "<?php echo $_SERVER['PHP_SELF'] ?>?acao=gerarPDF&numMatricula=<?php echo $numMatricula; ?>" + opcoes, "_blank");
        });
    });
</script>

<form id="cadastro">
    <fieldset>
        <legend>Selecionar Op&ccedil;&otilde;es de Gera&ccedil;&atilde;o do Hist&oacute;rico</legend>
            <input type="checkbox" id="exibeComponentesCurricularesPendentes" value="SIM" checked="checked" />Exibe Disciplinas Pendentes<br/>
            <input type="checkbox" id="exibeHistoricoDeSituacaoDeMatricula" value="SIM" checked="checked" />Exibe Hist&oacute;rico de Situa&ccedil;&otilde;es de Matr&iacute;cula<br/>
            <input type="checkbox" id="exibeListaDeDocumentosPendentes" value="SIM" checked="checked" />Exibe Documentos Pendentes
        <center><input type="button" value="Gerar Hist&oacute;rico" name="EH" id="gerarHistorico"></center>
    </fieldset>
</form>