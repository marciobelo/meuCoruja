<script type="text/javascript" src="/coruja/baseCoruja/javascripts/carrega_div.js"></script>

<script type="text/javascript">
$(function() 
{
        $("#div3").css("display", "none");
        $("#didfv1").css("display", "block");
        $("#nome").focus();    
});
</script>

<link rel="stylesheet" href="/coruja/baseCoruja/estilos/formulario_dica.css" media="screen" type="text/css">
            
<form id="cadastro" method="post" name="cadastro" action="SelecionarAlunoParaInscricao_controle.php?action=exibir" >

    <fieldset id="fieldsetGeral">

        <input type="hidden" name="tipo" value="<?php echo $tipo; ?>" />    <!--// CARREGA O TIPO PARA A PRÓXIMA ETAPA   -->
        <input type="hidden" name="acao" value="novo_cadastro" />
        <input type="hidden" name="passo" value="buscaPessoa" />

        <legend>Selecionar Aluno para Inscrição <?php echo strtoupper($tipo); ?></legend>

        Escolha o tipo de consulta:
        <br /><br />
                <input type="radio" name="tipoBusca" id="tipoBusca" value="nome" checked="checked" onClick="Hide('div3', this); Reveal('didfv1', this)" >Nome
        <input type="radio" name="tipoBusca" id="tipoBusca" value="matricula" onClick="Hide('didfv1', this); Reveal('div3', this)">Matr&iacute;cula
        <br /><br />

                <div class="row" id="didfv1" style="display:none">
                <label for="nome">Nome : </label> 
            <input name="nome" id="nome" class="obrigatorio" style ="text-transform: uppercase;" type="text" size="52" onchange="this.value=this.value.toUpperCase();" />
        <br />
        <div align="right">
        <input type="submit" id="button1" value="Procurar" />
                </div>
        </div>

        <div class="row" id="div3" style="display:none">
        <label for="matricula">Matr&iacute;cula : </label> 
            <input name="matricula" id="matricula" class="obrigatorio" type="text" size="12" onchange="this.value=this.value.toUpperCase();"  />
        <br />
        <div align="right">
        <input type="submit" id="button1" value="Procurar" />
        </div>
        </div>

    </fieldset>
</form>