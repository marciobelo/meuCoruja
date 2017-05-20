<?php
    
    // incluindo as classes
    include_once "$BASE_DIR/baseCoruja/classes/formulario.class.php";
    include_once "$BASE_DIR/classes/Curso.php";

    // inicializando as classes
    $classeCurso = new Curso();

    require_once("$BASE_DIR/includes/topo.php");
    echo '<div id="menuprincipal">';
    require_once("$BASE_DIR/includes/menu_horizontal.php");
    echo '</div>';
    echo '<div id="conteudo">';

    $escolhe_aba = $_POST['aba'];

?>

    <!--// OS DOIS SCRIPTS ABAIXO REFEREM-SE AO SISTEMA DE ABAS   -->
    <script type="text/javascript" src="/coruja/baseCoruja/javascripts/jquery_aba_formulario.js"></script>
    <script type="text/javascript" src="/coruja/baseCoruja/javascripts/abre_aba_formulario.js"></script>
   
    <!--// O SCRIPT ABAIXO REFERE-SE À VALIDAÇÃO DE CAMPOS  -->
    <script type="text/javascript" src="/coruja/baseCoruja/javascripts/valida_form_cadastro.js"></script>
    <script type="text/javascript" src="/coruja/baseCoruja/javascripts/carrega_div.js"></script>
    <script type="text/javascript">
        function mudaCurso(selectCurso) {
            showP('D' + selectCurso.value );
        }

        function cancelar() {
            document.cadastro.acao.value="exibirAluno";
            document.cadastro.submit();
        }
    </script>

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

    <?php
    echo "<body onload='abreAba($escolhe_aba)'>";
    ?>

    <form name="cadastro" id="cadastro" action="/coruja/baseCoruja/controle/manterAluno_controle.php" method="post" onSubmit="return validaForm()">

        <!--// PARÂMETROS QUE DEFINEM A AÇAO A SER EXECUTADA -->
        <input type="hidden" name="acao" value="salvarAlunoEditado" />
        <input type="hidden" name="idPessoa" value="<?php echo $formAluno->idPessoa; ?>" />
        <input type="hidden" name="aba" value="<?php echo $escolhe_aba; ?>" />
        <input type="hidden" name="modo" value="<?php echo $formAluno->modo; ?>" />

        <!--// ABAS DE NAVEGAÇÃO -->
        <div id="abanav">
    	    <div id="abas">
                <a href="#" onclick="abreAba(1);">Dados Pessoais</a>
                <a href="#" onclick="abreAba(2);">Contato</a>
                <a href="#" onclick="abreAba(3);">Complementar</a>
                <a href="#" onclick="abreAba(4);">Documentos</a>
                <a href="#" onclick="abreAba(5);">Respons&aacute;veis</a>
                <a href="#" onclick="abreAba(6);">Escolar</a>
            </div>
            <div id="conteudo_abas">

                <!--// DADOS PESSOAIS -->
                    <div> <?php include_once "$BASE_DIR/baseCoruja/formularios/novo_aluno_passo_1.php";?> </div>
                <!--// CONTATO -->
                    <div> <?php include_once "$BASE_DIR/baseCoruja/formularios/novo_aluno_passo_2.php";?> </div>
                <!--// DADOS COMPLEMENTARES -->
                    <div> <?php include_once "$BASE_DIR/baseCoruja/formularios/novo_aluno_passo_3.php";?> </div>
                <!--// DOCUMENTOS -->
                    <div> <?php include_once "$BASE_DIR/baseCoruja/formularios/novo_aluno_passo_4.php";?> </div>
                <!--// RESPONSÁVEIS -->
                    <div> <?php include_once "$BASE_DIR/baseCoruja/formularios/novo_aluno_passo_5.php";?> </div>
                <!--// DADOS ESCOLARES -->
                    <div> <?php include_once "$BASE_DIR/baseCoruja/formularios/novo_aluno_passo_6.php";?> </div>

                    <p align="center">
                        <input type="submit" value="Salvar" />&nbsp;&nbsp;
                        <input type="button" value="Cancelar" onclick="javascript:cancelar();" />
                    </p>
            </div>
        </div>
    </form>
<?php
    echo '</div>';
    require_once("$BASE_DIR/includes/rodape.php");
?>
<script>
    document.cadastro.aba.value=<?php echo $escolhe_aba; ?>;
</script>