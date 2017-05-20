<?php
require_once "$BASE_DIR/includes/topo.php";
require_once "$BASE_DIR/includes/menu_horizontal.php";
?>
<script type="text/javascript">

    function cancelar(siglaCursoAntes) {
        document.formCurso.siglaCursoAntes.value=siglaCursoAntes;
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
    <input type="hidden" name="acao" value="alterar" />
<fieldset id="coruja">
    <legend>Alterar Curso</legend>
    <input type="hidden" name="siglaCursoAntes" value="<?php echo $formCurso->getSiglaCursoAntes(); ?>" />
    <table width="646">
        <tr>
            <td width="137"><b>Sigla do Curso : </b> </td>
            <td width="499"><input type="text" name="siglaCursoDepois" value="<?php echo $formCurso->getSiglaCursoDepois(); ?>" maxlength="6" size="7"/></td>
        </tr>
         <tr>
            <td width="137"><b>Nome do Curso : </b> </td>
            <td width="499"><input type="text" name="nomeCurso" value="<?php echo $formCurso->getNomeCurso(); ?>" maxlength="45" size="40"/></td>
        </tr>
         <tr>
            <td width="137"><b>Tipo do Curso : </b> </td>
            <td width="499">
               <select id="idTipoCurso" name="idTipoCurso">
                <option value=''>Selecione o tipo do curso</option>
                 <?php foreach($tiposCurso as $tipoCurso){
                   echo"<option value='".$tipoCurso->getIdTipoCurso()."'>".$tipoCurso->getDescricao()."</option>";
                  }?>
             </select>

            </td>
        </tr>
        
        <tr>
            <td colspan="2">
                <input type="submit" value="Salvar" />
                &nbsp;
                <input type="button" value="Cancelar" onclick="javascript:cancelar('<?php echo $formCurso->getSiglaCursoAntes(); ?>');" />
            </td>
        </tr>
    

    </table>
</fieldset>
</form>
<br/>
<script>document.formCurso.idTipoCurso.value='<?php echo $formCurso->getIdTipoCurso();?>';</script>

<?php
require_once "$BASE_DIR/includes/rodape.php";
?>