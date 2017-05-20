<?php
require_once "$BASE_DIR/includes/topo.php";
require_once "$BASE_DIR/includes/menu_horizontal.php";
?>
<script type="text/javascript">
    function prepararAlterar(idTipoCurso) {
        document.formTpcurso.idTipoCurso.value=idTipoCurso;
        document.formTpcurso.acao.value="prepararAlterar";
        document.formTpcurso.submit();
    }
    function prepararExcluir(idTipoCurso) {
        document.formTpcurso.idTipoCurso.value=idTipoCurso;
        document.formTpcurso.acao.value="prepararExcluir";
        document.formTpcurso.submit();
    }
    function prepararIncluir() {
        document.formTpcurso.acao.value="prepararIncluir";
        document.formTpcurso.submit();
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
        <?php echo htmlspecialchars($msgErro, ENT_QUOTES, "iso-8859-1"); ?>
    </li>
<?php
    }
?>
</ul>
<?php
}
?>
<form name="formTpcurso" action="/coruja/interno/manter_tipocurso/manterTpcurso_controle.php" method="post">
    <input type="hidden" name="idTipoCurso" />
    <input type="hidden" name="acao" />
<fieldset id="coruja">
    <legend>Tipos de Cursos</legend>

    <table>
        <thead>
            <tr>
                <td><b>Nome do Tipo de Curso</b></td>
                <td>&nbsp;</td>
            </tr>
        </thead>
        <tbody>
<?php
            foreach($tipocursos as $tipocurso) {
?>
            <tr>
                <td><?php echo $tipocurso->getDescricao();  ?></td>
                <td>
                    <input type="button" value="Alterar" onclick="javascript:prepararAlterar(<?php echo $tipocurso->getIdTipoCurso(); ?>);" />
                    <input type="button" value="Excluir" onclick="javascript:prepararExcluir(<?php echo $tipocurso->getIdTipoCurso(); ?>);" />
                 </td>
            </tr>
<?php

            }
?>

        </tbody>
    </table>
    <input type="button" value="Incluir" onclick="javascript:prepararIncluir();" />
</fieldset>
</form>
<br/>
<?php
require_once "$BASE_DIR/includes/rodape.php";
?>