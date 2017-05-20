<?php
require_once "$BASE_DIR/includes/topo.php";
require_once "$BASE_DIR/includes/menu_horizontal.php";
?>
<form id="cadastro" name="cadastro" method="POST" action="nenhuma" >

    <fieldset>
        <legend>Diario de Classe<?php //echo $Par_BuscarTurmas_Titulo  ?></legend>
        <br>
        Pressione o botão para que uma nova janela seja aberta com o documento desejado
        <br><br>
        <INPUT  id="botaoExibirDocumento" TYPE ="button" value="Exibir Diário de Classe"
                onClick="open('./emitirDiarioDeClasse_controle.php?acao=exibirPDF', 'new', 'width=800,height=600,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no')">
    </fieldset>
</form>

