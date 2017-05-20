<?php
require_once "$BASE_DIR/includes/topo.php";
require_once "$BASE_DIR/includes/menu_horizontal.php";
?>
<form id="cadastro" name="cadastro" method="POST" action="<?php echo $_SERVER['PHP_SELF'] ?>" >

    <fieldset>
        <legend>Histórico Escolar<?php //echo $Par_BuscarTurmas_Titulo  ?></legend>
        <br>
        Pressione o botão para que uma nova janela seja aberta com o documento desejado
        <br><br>
        <INPUT  id="botaoExibirDocumento" TYPE ="button" value="Exibir Histórico Escolar"
                onClick="open('./emitirHistoricoEscolar_controle.php?acao=exibirPDF', 'new', 'width=800,height=600,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no')">
    </fieldset>
</form>

<?php
//<script type="text/javascript">
//    /* ESTE SCRIPT CAUSA O BLOQUEIO DA NOVA JANELA PELO BLOQUEADOR DE POP-UP */
//    setTimeout("document.getElementById('botaoExibirDocumento').click()",3000);
//</script>
?>