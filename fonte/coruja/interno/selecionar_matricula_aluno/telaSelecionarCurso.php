<?php
require_once "$BASE_DIR/includes/topo.php";
require_once "$BASE_DIR/includes/menu_horizontal.php";

?>
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

<script type="text/javascript">
    function submeter() {
        var id = document.getElementById("siglaCurso").value;
        if( id != "" ) {
            document.getElementById("selecionaCurso").submit();
        } else {
            window.alert("Selecione um curso");
        }
    }
</script>

<form method="GET" name="selecionaCurso" id="selecionaCurso" action="/coruja/interno/selecionar_matricula_aluno/selecionarMatricula_controle.php">
    <input type="hidden" name="acao" value="exibirFiltroPesquisa" />
    <input type="hidden" name="controleDestino" value="<?php echo $controleDestino; ?>" />
    <input type="hidden" name="acaoControleDestino" value="<?php echo $acaoControleDestino; ?>" />
    <input type="hidden" name="controleDestinoTitulo" value="<?php echo $controleDestinoTitulo; ?>" />

    <fieldset id="fieldsetGeral">
        <legend><?php echo $controleDestinoTitulo; ?></legend>
        <font size="-1" color="#FF0000">Selecione um curso.</font><br />
        <select name="siglaCurso" id="siglaCurso" onchange="submeter();">
             <option value=''>Selecione o curso</option>
             <?php foreach($cursos as $curso){
                echo"<option value='".$curso->getSiglaCurso()."'>" . $curso->getSiglaCurso() . " - " . $curso->getNomeCurso() . "</option>";
             }?>
        </select>
    </fieldset>
</form>
<?php
require_once "$BASE_DIR/includes/rodape.php";
?>
