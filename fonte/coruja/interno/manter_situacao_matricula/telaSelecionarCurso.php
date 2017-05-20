<?php
require_once "$BASE_DIR/includes/topo.php";
require_once "$BASE_DIR/includes/menu_horizontal.php";
// TODO: esse arquivo deve mesmo existir? não deveria usar o de /interno/selecionar_matricula?
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
        <?php echo $msgErro; ?>
    </li>
<?php
    }
?>
</ul>
<?php
}
?>
<!-- fim mensagens de erro -->

<form method="post" name="selecionaCurso" action="/coruja/interno/manter_situacao_matricula/manterSituacaoMatricula_controle.php">
    <input type="hidden" name="acao" value="exibirSituacoesMatriculasCurso" />
    <fieldset id="fieldsetGeral">
        <legend>Administrar Matr&iacute;culas</legend>
        <font size="-1" color="#FF0000">Selecione um curso.</font><br />
        <select name="siglaCurso" id="siglaCurso">
             <option value=''>Selecione o curso</option>
             <?php foreach($cursos as $curso){
                echo"<option value='".$curso->getSiglaCurso()."'";
                if( $curso->getSiglaCurso() === $siglaCursoFiltro)
                {
                    echo " selected";
                }
                echo ">" . $curso->getSiglaCurso() . " - " . $curso->getNomeCurso() . "</option>";
             }?>
        </select>
        <input type="submit" value="Selecionar" />
    </fieldset>
</form>
<?php
require_once "$BASE_DIR/includes/rodape.php";
?>
