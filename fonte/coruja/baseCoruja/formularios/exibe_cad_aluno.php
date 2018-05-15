<?php
    $idPessoa = $_REQUEST["idPessoa"];
    $aba = $_REQUEST["aba"];

    require_once "$BASE_DIR/baseCoruja/classes/formulario.class.php";
    require_once "$BASE_DIR/classes/Pessoa.php";
    require_once "$BASE_DIR/classes/Aluno.php";
    require_once "$BASE_DIR/classes/MatriculaAluno.php";
    require_once "$BASE_DIR/classes/ExigeDocumento.php";
    require_once "$BASE_DIR/classes/TipoDocumento.php";
    require_once "$BASE_DIR/classes/Util.php";
    require_once "$BASE_DIR/classes/Curso.php";
    require_once "$BASE_DIR/classes/PeriodoLetivo.php";
    require_once "$BASE_DIR/classes/MatrizCurricular.php";
    require_once "$BASE_DIR/classes/FormaIngresso.php";
    
    $formExibeAluno = new formularioExibir();
    $infoPessoa = Pessoa::obterPessoaPorId($idPessoa);
    
    $infoAluno = Aluno::getAlunoByIdPessoa( $idPessoa);
    
    $buscaMatriculas = MatriculaAluno::obterMatriculasAlunoPorIdPessoa( $idPessoa);
    
    // Obter dados de usuário do Aluno
    $loginAluno = Login::obterLoginPorIdPessoa($idPessoa);
    
    // Inicia a exibição da página
    require_once("$BASE_DIR/includes/topo.php");
    echo '<div id="menuprincipal">';
    require_once("$BASE_DIR/includes/menu_horizontal.php");
    echo '</div>';
    echo '<div id="conteudo">';
?>

    <!--// OS DOIS SCRIPTS ABAIXO REFEREM-SE AO SISTEMA DE ABAS   -->
    <script type="text/javascript" src="/coruja/baseCoruja/javascripts/jquery_aba_formulario.js"></script>
    <script type="text/javascript" src="/coruja/baseCoruja/javascripts/abre_aba_formulario.js"></script>
    <script type="text/javascript" src="/coruja/baseCoruja/javascripts/carrega_div.js"></script>
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
<script type="text/javascript">
    function editar(aba) {
        document.getElementById("aba").value=aba;
        document.getElementById("cadastro").submit();
    }
</script>

    <!-- seção Mensagens de erro, se houver -->
    <?php
    if(count($msgs)>0) {
    ?>
    <ul class="erro">
    <?php
        foreach($msgs as $msg) {
    ?>
        <li>
            <?php echo $msg; ?>
        </li>
    <?php
        }
    ?>
    </ul>
    <?php
    }
    ?>
    <!-- fim mensagens de erro -->

    <form name="cadastro" id="cadastro" action="/coruja/baseCoruja/controle/manterAluno_controle.php" method="post">
        <input type="hidden" name="acao" value="preparaEdicaoAluno"/>
        <input type="hidden" name="idPessoa" value="<?php echo $infoPessoa->getIdPessoa(); ?>"/>
        <input type="hidden" name="matriculaAluno" value="" />
        <input type="hidden" name="idTipoDocumento" value="" />
        <input type="hidden" name="situacaoDocEntregue" value="" />
        <input type="hidden" name="aba" id="aba" value="<?php echo $aba; ?>" />

        <!--// SISTEMA DE ABAS -->
        <div id="abanav">
    	    <div id="abas">
        	    <a href="#" onclick="abreAba(1);">Dados Pessoais</a>
        	    <a href="#" onclick="abreAba(2);">Contato</a>
           	    <a href="#" onclick="abreAba(3);">Complementar</a>     
                    <a href="#" onclick="abreAba(4);">Documentos</a>
        	    <a href="#" onclick="abreAba(5);">Respons&aacute;veis</a>
           	    <a href="#" onclick="abreAba(6);">Escolar</a>
                    <a href="#" onclick="abreAba(7);">Matr&iacute;cula</a>
        	    <a href="#" onclick="abreAba(8);">Docs. Entregues</a>
            </div>
            
            <div id="conteudo_abas">
                    <!--// DADOS PESSOAIS -->
                    <div> <?php include_once "$BASE_DIR/baseCoruja/formularios/exibe_cad_aluno_passo_1.php";?> </div>
                    <!--// CONTATO -->
                    <div> <?php include_once "$BASE_DIR/baseCoruja/formularios/exibe_cad_aluno_passo_2.php";?> </div>
                    <!--// DADOS COMPLEMENTARES -->
                    <div> <?php include_once "$BASE_DIR/baseCoruja/formularios/exibe_cad_aluno_passo_3.php";?> </div>
                    <!--// DOCUMENTOS -->
                    <div> <?php include_once "$BASE_DIR/baseCoruja/formularios/exibe_cad_aluno_passo_4.php";?> </div>
                    <!--// RESPONSÁVEIS -->
                    <div> <?php include_once "$BASE_DIR/baseCoruja/formularios/exibe_cad_aluno_passo_5.php";?> </div>
                    <!--// DADOS ESCOLARES -->
                    <div> <?php include_once "$BASE_DIR/baseCoruja/formularios/exibe_cad_aluno_passo_6.php";?> </div>
                    <!--// MATRICULA -->
                    <div> <?php include_once "$BASE_DIR/baseCoruja/formularios/exibe_cad_aluno_matricula.php";?> </div>
                    <!--// DOCUMENTOS ENTREGUES -->
                    <div> <?php include_once "$BASE_DIR/baseCoruja/formularios/exibe_cad_aluno_docs_entregues.php";?> </div>
            </div>
        </div>
    </form>
<?php
    echo '</div>';
    require_once("$BASE_DIR/includes/rodape.php");
    if( !isset( $aba))
    {
        $aba = "1";
    }
    echo "<script>abreAba( $aba);</script>";