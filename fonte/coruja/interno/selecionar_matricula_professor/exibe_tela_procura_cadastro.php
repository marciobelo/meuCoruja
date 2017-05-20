<?php
require_once "$BASE_DIR/includes/topo.php";
require_once "$BASE_DIR/includes/menu_horizontal.php";

?>
<script type="text/javascript" src="/coruja/baseCoruja/javascripts/carrega_div.js"></script>

<!-- seção Mensagens de erro, se houver -->
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



             <form id='novoCadastro' action='/coruja/interno/manter_professor/manterProfessor_controle.php?acao=novoCadastro' method='post'>
             <fieldset id='fieldsetGeral'>

  
             <input type='hidden' name='acao' value='novoCadastro'>
             <center><input type='submit' value='Cadastrar Novo Professor' /></center>
             </fieldset>
             </form>

                 <form id='consultar' action='/coruja/interno/selecionar_matricula_professor/selecionarMatricula_controle.php?acao=exibirFiltroPesquisa' method='post'>
                 <fieldset id='fieldsetGeral'>    


                <center><input  type='submit' value='Realizar Nova Busca' /></center>
               </fieldset>
                </form>

<?php
require_once "$BASE_DIR/includes/rodape.php";
?>