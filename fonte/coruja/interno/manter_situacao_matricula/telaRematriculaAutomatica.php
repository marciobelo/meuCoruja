<?php
require_once "$BASE_DIR/includes/topo.php";
require_once "$BASE_DIR/includes/menu_horizontal.php";
require_once "$BASE_DIR/classes/Util.php";
?>
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
<fieldset id="coruja">
    <legend>Rematr&iacute;cula Autom�tica</legend>
    <p>ATEN��O: observe com aten��o todas as informa��es a seguir:</p>
    <ul>
        <li>
            Essa opera��o afetar� apenas as matr�culas desatualizadas do curso em quest�o.
        </li>
        <li>
            Matr�culas desatualizadas s�o aquelas cuja situa��o � Cursando ou Trancada e a data da �ltima atualiza��o no hist�rico � anterior � data de in�cio do �ltimo per�odo letivo cadastrado.
        </li>
        <li>
            Todos os alunos que tiveram uma ou mais disciplinas deferidas durante o processo de solicita��o de inscri��o em disciplinas do �ltimo per�odo letivo ser�o automaticamente postos como CURSANDO (e os que j� estiverem como CURSANDO ter�o a renova��o da situa��o).
        </li>
        <li>
            Para as demais matr�culas, se passou o prazo para trancamento, ter�o a situa��o colocada como EVADIDO.
        </li>
        <li>
            No campo observa��o do hist�rico de mudan�a de situa��o constar� Rematr�cula Atualizada Automaticamente.
        </li>
    </ul>

    <form method="post" action="/coruja/interno/manter_situacao_matricula/manterSituacaoMatricula_controle.php">
        <input type="hidden" name="acao" value="processarRematriculaAutomatica" />
        <input type="hidden" name="siglaCurso" value="<?php echo $siglaCurso; ?>" />
        <input type="submit" value="Processar Rematr&iacute;cula Autom&aacute;tica" />
    </form>
    <br/>
    <form method="post" action="/coruja/interno/manter_situacao_matricula/manterSituacaoMatricula_controle.php">
        <input type="hidden" name="acao" value="exibirSituacoesMatriculasCurso" />
        <input type="hidden" name="siglaCurso" value="<?php echo $siglaCurso; ?>" />
        <input type="submit" value="Voltar" />
    </form>
</fieldset>
<br/>
<?php
require_once "$BASE_DIR/includes/rodape.php";
?>
