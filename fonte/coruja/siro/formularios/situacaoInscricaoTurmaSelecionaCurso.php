<!--// Selecionar Curso para periodo letivo -->
<form method="post" name="selecionaCurso" action="ManterSituacaoInscricaoTurma_controle.php?action=listar">
    <fieldset id="fieldsetGeral">
        <legend>Curso</legend>
        <font size="-1" color="#FF0000">Selecione um curso.</font><br />
        <select id="siglaCurso" name="siglaCurso" onChange='document.selecionaCurso.submit();'>
             <option value=''>Selecione o curso</option>
             <?php foreach($collection as $curso){
             echo"<option value='".$curso->getSiglaCurso()."'>".$curso->getNomeCurso()." - ".$curso->getSiglaCurso()."</option>";
             }?>
        </select>
    </fieldset>
</form>