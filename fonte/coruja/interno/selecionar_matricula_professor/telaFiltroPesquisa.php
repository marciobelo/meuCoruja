<?php
require_once "$BASE_DIR/includes/topo.php";
require_once "$BASE_DIR/includes/menu_horizontal.php";

?>
<script type="text/javascript" src="/coruja/baseCoruja/javascripts/carrega_div.js"></script>

<!-- seção Mensagens de erro, se houver -->
<?php
if(count($msgsErro)>0) {
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
<!-- fim mensagens de erro -->

<form id="cadastro" method="post" name="cadastro" action="/coruja/interno/selecionar_matricula_professor/selecionarMatricula_controle.php" >

    <fieldset id="fieldsetGeral">
        <input type="hidden" name="acao" value="exibirResultado" />
        <input type="hidden" name="siglaCurso" value="<?php echo $siglaCurso; ?>" />
        <input type="hidden" name="controleDestino" value="<?php echo $controleDestino; ?>" />
        <input type="hidden" name="acaoControleDestino" value="<?php echo $acaoControleDestino; ?>" />
        <input type="hidden" name="controleDestinoTitulo" value="<?php echo $controleDestinoTitulo; ?>" />

        <legend><?php echo $controleDestinoTitulo; ?><br/>
           Cadastro de Professor</legend>

        Escolha o tipo de consulta:
        <br /><br />
                <input type="radio" <?php if($tipoBusca=="nome") echo "checked=\"checked\""; ?> name="tipoBusca" id="tipoBusca" value="nome" onClick="Hide('div3', this); Reveal('didfv1', this); document.getElementById('nome').focus();" >Nome
                <input type="radio" <?php if($tipoBusca=="" || $tipoBusca=="matricula") echo "checked=\"checked\""; ?> name="tipoBusca" id="tipoBusca" value="matricula" onClick="Hide('didfv1', this); Reveal('div3', this); document.getElementById('matricula').focus();">Matr&iacute;cula
        <br /><br />

        <div class="row" id="didfv1" <?php if($tipoBusca!="nome") echo "style=\"display:none\""; ?> >
                <label for="nome">Nome : </label>
            <input name="nome" id="nome" class="obrigatorio" type="text" size="52" style="text-transform: uppercase;" onchange="this.value=this.value.toUpperCase();" />
        <br />
        <div align="right">
        <input type="submit" id="button1" value="Procurar" />
                </div>
        </div>

        <div class="row" id="div3" <?php if($tipoBusca!="" && $tipoBusca!="matricula" ) echo "style=\"display:none\""; ?> >
        <label for="matricula">Matr&iacute;cula : </label>
        <input name="matricula" id="matricula" class="obrigatorio" type="text" size="12" onchange="this.value=this.value.toUpperCase();" />
        <br />
        <div align="right">
        <input type="submit" id="button1" value="Procurar" />
        </div>
        </div>

    </fieldset>
</form>
<?php
require_once "$BASE_DIR/includes/rodape.php";
?>
<script type="text/javascript">
    <?php if($tipoBusca=="matricula" || $tipoBusca=="" ) { ?>
        document.getElementById("matricula").focus();
    <?php } else if($tipoBusca=="nome") {?>
        document.getElementById("nome").focus();
    <?php } ?>
</script>