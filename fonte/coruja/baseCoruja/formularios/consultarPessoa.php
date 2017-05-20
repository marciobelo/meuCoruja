<?php
    require_once("$BASE_DIR/includes/topo.php");
    echo '<div id="menuprincipal">';
    require_once("$BASE_DIR/includes/menu_horizontal.php");
    echo '</div>';
    echo '<div id="conteudo">';
?>
    <!--// PRIMEIRA ETAPA DO FORMULARIO DE MATRICULA NOVA   -->
    <script type="text/javascript" src="/coruja/baseCoruja/javascripts/carrega_div.js"></script>
    <script type="text/javascript">
        $(function() 
        {
            $("#div2").css("display", "none");
            $("#div3").css("display", "none");
            $("#didfv1").css("display", "block");
            $("#nome").focus();
        });
    </script>

    <form id="cadastro" method="post" name="cadastro" action="/coruja/baseCoruja/controle/manterAluno_controle.php">

        <fieldset id="fieldsetGeral">
            <input type="hidden" name="acao" value="buscaAluno" />

            <legend>CADASTRO <?php echo strtoupper($tipo); ?></legend>

            Escolha o tipo de consulta:
            <br /><br />
            <input type="radio" name="tipoBusca" id="tipoBusca" value="nome" checked="checked" onClick="Hide('div2', this); Hide('div3', this); Reveal('didfv1', this)" >Nome
            <input type="radio" name="tipoBusca" id="tipoBusca" value="cpf" onClick="Hide('didfv1', this); Hide('div3', this); Reveal('div2', this)" >CPF
            <input type="radio" name="tipoBusca" id="tipoBusca" value="matricula" onClick="Hide('didfv1', this); Hide('div2', this); Reveal('div3', this)">Matr&iacute;cula
            <br /><br />

		    <div class="row" id="didfv1" style="display:none">
		    <label for="nome">Nome : </label>
                    <input name="nome" id="nome" class="obrigatorio" tabindex="0" style ="text-transform: uppercase;" "type="text" size="52" onchange="this.value=this.value.toUpperCase();" maxlength="80" />
            <br />
            <div align="right">
    	    <input type="submit" id="button1" value="Procurar" />
		    </div>
            </div>


            <div class="row" id="div2" style="display:none">
            Apenas n&uacute;meros. N&atilde;o utilize barras ou tra&ccedil;os
            <br />
            <label for="cpf">CPF : </label>

            <input name="cpf" id="cpf" class="obrigatorio" type="text" size="12" maxlength="12" />
            <br />
            <div align="right">
    	    <input type="submit" id="button1" value="Procurar" />
		    </div>
            </div>


            <div class="row" id="div3" style="display:none">
            <label for="matricula">Matr&iacute;cula : </label>
                <input name="matricula" id="matricula" class="obrigatorio" type="text" size="12" onchange="this.value=this.value.toUpperCase();" maxlength="15" />
            <br />
            <div align="right">
            <input type="submit" id="button1" value="Procurar" />
            </div>
            </div>

        </fieldset>
    </form>
<?php
    echo '</div>';
    require_once("$BASE_DIR/includes/rodape.php");
