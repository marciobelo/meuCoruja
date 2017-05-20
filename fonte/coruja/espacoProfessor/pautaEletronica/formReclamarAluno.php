<?php
require_once "$BASE_DIR/includes/topo.php";
require_once "$BASE_DIR/includes/menu_horizontal.php";
?>

<?php include "$BASE_DIR/espacoProfessor/pautaEletronica/trechoCabecTurma.php"; ?>

<script type="text/javascript">
function buscarPorMatricula() {
    
    var numMatriculaAluno = $("#numMatriculaAluno").val();
    
    if( numMatriculaAluno.trim() == "") {
        window.alert("Preencha o campo matrícula");
        $("#numMatriculaAluno").focus();
    } else {
        $.get("/coruja/espacoProfessor/pautaEletronica/reclamarAluno_controle.php",
            {
                acao: "buscarAlunoPorMatricula",
                idTurma: <?php echo $turma->getIdTurma(); ?>,
                numMatriculaAluno: numMatriculaAluno
            },
            function(xml) {
                var codigoRetorno = $("codigoRetorno",xml).text();
                if( codigoRetorno == "1" ) { // retornou resultado
                    $("#nomeAluno").val($("nomeAluno",xml).text());
                    $("#situacaoMatricula").val($("situacaoMatricula",xml).text());
                    $("#fotoAluno").attr("src","/coruja/baseCoruja/controle/obterFoto_controle.php?idPessoa=" + $("idPessoa",xml).text());
                    
                } else if( codigoRetorno == "2" ) { // não encontrou
                    $("#nomeAluno").val("Matrícula Não Encontrada.");
                    $("#situacaoMatricula").val("");
                    $("#fotoAluno").attr("src","/coruja/baseCoruja/controle/obterFoto_controle.php?idPessoa=");
                } else { // erro
                    window.open("/coruja","_top");
                }
            }
            );
    }
}

function voltar() {
    window.open("/coruja/espacoProfessor/pautaEletronica/pautaEletronica_controle.php?idTurma=<?php echo $turma->getIdTurma(); ?>&data=<?php echo $data; ?>","_top");
}
</script>

<div id="msgsErro">
    <!-- Mensagens de erro, se houver -->
    <?php
    if(count($msgsErro) > 0) {
    ?>
    <ul class="erro">
    <?php
        foreach($msgsErro as $msgErro) {
    ?>
        <li>
            <?php 
            echo htmlspecialchars($msgErro, ENT_QUOTES, "iso-8859-1");
            ?>
        </li>
    <?php
        }
    ?>
    </ul>
    <?php
    }
    ?>
</div>

<form id="formReclamarAluno" 
      action="/coruja/espacoProfessor/pautaEletronica/reclamarAluno_controle.php"
      method="post">
    <input type="hidden" id="acao" name="acao" value="reclamarAluno" />
    <input type="hidden" id="idTurma" name="idTurma" value="<?php echo $turma->getIdTurma(); ?>" />
    <input type="hidden" id="data" name="data" value="<?php echo $data; ?>" />

    <label>Matr&iacute;cula:</label>
    <input type="text" name="numMatriculaAluno" id="numMatriculaAluno" size="15" maxlength="15"
           tabindex="1" onclick="" />
    <input type="button" value="Buscar" onclick="buscarPorMatricula();" />
    <div id="idAluno">
        <table>
            <tr>
                <td>
                    <img id="fotoAluno" src="/coruja/baseCoruja/controle/obterFoto_controle.php?idPessoa=<?php echo $idPessoa; ?>" 
                         width="100" height="90" />
                </td>
                <td>
                    <label>Nome do Aluno:</label>
                    <input type="text" id="nomeAluno" size="40" disabled="true" 
                           readonly="readonly" />
                    <br/>
                    <label>Situa&ccedil;&atilde;o do Aluno:</label>
                    <input type="text" id="situacaoMatricula" size="40" disabled="true" 
                           readonly="readonly" />
                </td>
            </tr>
        </table>
        
    </div>
    <input type="submit" value="Reclamar" />
    <input type="button" value="Voltar" onclick="voltar();"/>
</form>

<?php
require_once "$BASE_DIR/includes/rodape.php";
?>
