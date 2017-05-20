<?php
require_once "$BASE_DIR/includes/topo.php";
require_once "$BASE_DIR/includes/menu_horizontal.php";
?>
<script type="text/javascript">
    function prepararAlterar(siglaCurso) {
        document.formCurso.siglaCurso.value=siglaCurso;
        document.formCurso.acao.value="prepararAlterar";
        document.formCurso.submit();
    }
    function prepararExcluir(siglaCurso) {
        document.formCurso.siglaCurso.value=siglaCurso;
        document.formCurso.acao.value="prepararExcluir";
        document.formCurso.submit();
    }
    function prepararIncluir() {
        document.formCurso.acao.value="prepararIncluir";
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
        <?php echo htmlspecialchars($msgErro, ENT_QUOTES, "iso-8859-1"); ?>
    </li>
<?php
    }
?>
</ul>
<?php
}
?>
<form name="formCurso" action="/coruja/interno/manter_curso/manterCurso_controle.php" method="post">
    <input type="hidden" name="siglaCurso" />
    <input type="hidden" name="acao" />
<fieldset id="coruja">
    <legend>Cursos</legend>

    <table style="border-style: solid;
border-width: 1px;border-collapse: collapse;
border-color: 660033;">
        <thead>
            <tr>
                <td colspan="2" width="125" style="border:1px solid #660033;"><b>Sigla do Curso</b></td>
                <td width="150" style="border:1px solid #660033;"><b>Nome do Curso</b></td>
                <td width="150" style="border:1px solid #660033;"><b>Tipo do Curso</b></td>
                <td>&nbsp;</td>
            </tr>
        </thead>
        <tbody>
<?php
    foreach($cursos as $curso) {
?>
            <tr>
                <td colspan="2" style="border:1px solid #660033;"><?php echo $curso->getSiglaCurso();  ?></td>
                <td style="border:1px solid #660033;"><?php echo $curso->getNomeCurso();  ?></td>
                <td style="border:1px solid #660033;"><?php echo $curso->getTipoCurso()->getDescricao();  ?></td>
                <td colspan="4" style="border:1px solid #660033;">
                    <input type="button" value="Alterar" onclick="javascript:prepararAlterar('<?php echo $curso->getsiglaCurso(); ?>');" />
                    <input type="button" value="Excluir" onclick="javascript:prepararExcluir('<?php echo $curso->getsiglaCurso(); ?>');" />
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