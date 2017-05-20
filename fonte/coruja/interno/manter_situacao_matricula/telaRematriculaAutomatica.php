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
    <legend>Rematr&iacute;cula Automática</legend>
    <p>ATENÇÃO: observe com atenção todas as informações a seguir:</p>
    <ul>
        <li>
            Essa operação afetará apenas as matrículas desatualizadas do curso em questão.
        </li>
        <li>
            Matrículas desatualizadas são aquelas cuja situação é Cursando ou Trancada e a data da última atualização no histórico é anterior à data de início do último período letivo cadastrado.
        </li>
        <li>
            Todos os alunos que tiveram uma ou mais disciplinas deferidas durante o processo de solicitação de inscrição em disciplinas do último período letivo serão automaticamente postos como CURSANDO (e os que já estiverem como CURSANDO terão a renovação da situação).
        </li>
        <li>
            Para as demais matrículas, se passou o prazo para trancamento, terão a situação colocada como EVADIDO.
        </li>
        <li>
            No campo observação do histórico de mudança de situação constará Rematrícula Atualizada Automaticamente.
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
