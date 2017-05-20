<?php
require_once "$BASE_DIR/includes/topo.php";
require_once "$BASE_DIR/includes/menu_horizontal.php";
?>
<script type="text/javascript">
    function cancelar(siglaCurso) {
        document.formCurso.siglaCurso.value=siglaCurso;
        document.formCurso.acao.value="listar";
        document.formCurso.submit();
    }
</script>
<!-- Mensagens de erro, se houver -->
<?php
if(count($msgsErro)>0) {
?>
<ul class="erro">
<?php
    foreach($msgsErro as $msgErro) {
?>
    <li>
        <?php echo $msgErro; ?>
    </li>
<?php
    }
?>
</ul>
<?php
}
?>
<form name="formCurso" action="/coruja/interno/manter_curso/manterCurso_controle.php" method="post">
    <input type="hidden" name="acao" value="incluir" />
<fieldset id="coruja">
    <legend>Incluir Curso</legend>
    <input type="hidden" name="siglaCurso" value="" />
    <table width="646">
        <tr>
            <td width="137"><b>Sigla do Curso : </b> </td>
            <td width="499"><input type="text" name="siglaCurso" value="<?php echo $_POST['siglaCurso'];?>" maxlength="6" size="7"/></td>
        </tr>
         <tr>
            <td width="137"><b>Nome do Curso : </b> </td>
            <td width="499"><input type="text" name="nomeCurso" value="<?php echo $_POST['nomeCurso'];?>" maxlength="45" size="40"/></td>
        </tr>
         <tr>
            <td width="137"><b>Tipo de Curso : </b> </td>
            <td width="499">
              <select id="idTipoCurso" name="idTipoCurso">
                <option value=''>Selecione o tipo do curso</option>
                 <?php foreach($boxcursos as $boxcurso){
                   echo"<option value='".$boxcurso->getIdTipoCurso()."'>".$boxcurso->getDescricao()."</option>";
                  }?>
             </select>
            </td>
        </tr>
                <tr>
            <td colspan="2">
                <input type="submit" value="Salvar" />
                &nbsp;
                <input type="button" value="Cancelar" onclick="javascript:cancelar();"/>
            </td>
        </tr>
    </table>
    
</fieldset>
</form>
<br/>
<?php
require_once "$BASE_DIR/includes/rodape.php";
?>