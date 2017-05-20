<?php
include_once "$BASE_DIR/siro/includes/combobox.php";
?>
<script type="text/javascript">
function exibePeriodoSelect(siglaCurso) 
{
    $.ajax(
    {
      type: "POST",
      url: "/coruja/siro/includes/combobox.php",
      data: "acao=exibePeriodoSelect&siglaCurso=" + siglaCurso,
      success: function(txt) 
      {
          // pego o id da div que envolve o select com
          // name="id_modelo" e a substituiu
          // com o texto enviado pelo php, que é um novo
          //select com dados da marca x
          $('#ajax_periodo').html(txt);
      },
      error: function(txt) 
      {
        // em caso de erro você pode dar um alert('erro');
      }
    });
}    
</script>


<?php
if( $siglaCursoFiltro !== "")
{
?>
<script type="text/javascript">
var siglaCursoFiltro = "<?php echo $siglaCursoFiltro; ?>";    
$(function() 
{
    $("#siglaCurso").val( siglaCursoFiltro);
    exibePeriodoSelect( siglaCursoFiltro);
});
</script>
<?php    
}
?>

<!--// Selecionar Curso para periodo letivo -->
<form method="post" name="listarTurmas" action="ManterAlunosQueCursamTurma_controle.php">
    <fieldset id="fieldsetGeral">
        
        <input type='hidden' id='acao' name='acao' value='verTurmas' />
                
        <legend>Listar Turmas</legend>
        <font color=#F00>Os campos marcados com (*) s&atilde;o obrigat&oacute;rios!</font><br />

         <table border=0 width=80% align=center>
            <tr>
                <td> Curso(*) </td>
                <td> Per&iacute;odo Letivo(*) </td>
                <td> Turno(*) </td>
            </tr>
            <tr>
            <td>
                <select id="siglaCurso" name="siglaCurso" onChange='javascript:exibePeriodoSelect(this.value);'>
                     <option value='' selected='selected'>SELECIONE</option>
                     <?php
                     foreach($collection as $curso)
                     {
                        echo"<option value='".$curso->getSiglaCurso()."'>".$curso->getSiglaCurso()."</option>";
                     }
                     ?>
                </select>
            </td>
            <td>
                <div id="ajax_periodo">
                <select id="idPeriodoLetivo" name="idPeriodoLetivo">
                <option value="">Selecione o Curso</option>
                </select>
                </div>
            </td>
            <td>
            <?php
                $escolheTurma = new formulario();
                echo $escolheTurma->inputSelectEnum('turno','Turma','turno','');
            ?>
            </td>

         </table>
        <p align="center"><br />
        <input id="button1" type="submit" name="verTurmas" value="  Listar Turmas  " />
    </fieldset>

</form>