<?php
require_once "$BASE_DIR/includes/topo.php";
require_once "$BASE_DIR/includes/menu_horizontal.php";
?>

<script type="text/javascript">
    function GetXMLHttp() {
        if (window.XMLHttpRequest)
        {// code for IE7+, Firefox, Chrome, Opera, Safari
            return new XMLHttpRequest();
        }
        else
        {// code for IE6, IE5
            return new ActiveXObject("Microsoft.XMLHTTP");
        }
    }
</script>

<script type="text/javascript">
    function trim(str) {
        return str.replace(/^\s+|\s+$/g,"");
    }

    //left trim
    function ltrim(str) {
        return str.replace(/^\s+/,"");
    }

    //right trim
    function rtrim(str) {
        return str.replace(/\s+$/,"");
    }
</script>

<script type="text/javascript">
    function mudarSituacaoTurma(){

        xmlhttp = GetXMLHttp();
        xmlhttp.onreadystatechange=function() {
            if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                resposta = xmlhttp.responseText;
                if(trim(resposta) == "OK"){ // trim() n�o � nativo do javascript
                    alert ("Situa��o modificada com sucesso");
                    document.formVoltar.submit();
                } else if(trim(resposta) == "OK_FINALIZADA"){ // trim() n�o � nativo do javascript
                    var resp = window.confirm("Turma finalizada. Deseja tentar enviar extrato da turma para o Professor?");
                    if (resp==true) {
                        document.formEnviarExtrato.submit();                        
                    } else {
                        document.formVoltar.submit();
                    }
                } else {
                    alert(resposta);
                }
            }
        }

        xmlhttp.open("POST","<?php echo $_SERVER['PHP_SELF'] ?>?acao=alterarSituacaoAJAX",true);
        xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        it = document.mudarSituacaoDaTurma.auxIdTurma.value;
        ns = document.mudarSituacaoDaTurma.novaSituacao.value;

        conteudoPost = "";

        conteudoPost+="idTurma="+it+
            "&novaSituacao="+ns;
        
        //alert("POST: "+conteudoPost);
        xmlhttp.send(conteudoPost);
    }
</script>

<link rel="stylesheet" type="text/css" href="<?php echo $RAIZ_CORUJA; ?>/nort/estilos/gradeDeHorario.css" />

<script type="text/javascript">
    function voltar(){
        document.formVoltar.siglaCurso.value = "<?php echo $dadosDaTurma['siglaCurso']; ?>";
        document.formVoltar.idPeriodoLetivo.value = "<?php echo $dadosDaTurma['idPeriodoLetivo']; ?>";
        document.formVoltar.turno.value = "<?php echo $dadosDaTurma['turno']; ?>";
        document.formVoltar.submit();
    }
</script>

<script type="text/javascript">
    function informaImpactoDaMudanca(){
        situacaoSelecionada = document.mudarSituacaoDaTurma.novaSituacao.value;
        paragrafo = document.getElementById("paragrafoInformaImpacto");
        divResumoDeAlunos = document.getElementById("divResumoDeAlunos");
        qntInscricoesReq = <?php echo $qntInscricoesReq; ?>;
        divResumoDeAlunos.style.display = 'none';
        switch (situacaoSelecionada) {
            case 'LIBERADA':
                paragrafo.innerHTML = 'A turma estar� dispon�vel para receber inscri��es.<br> Somente realizar essa opera��o quando existir perspectiva que a turma venha a vingar.';
                break;
            case 'CONFIRMADA':
                paragrafo.innerHTML = 'Todas as requisi��es de inscri��es devem estar respondidas.<br>Existem '+qntInscricoesReq+' inscri��es para a turma selecionada.<br>O professor deve estar indicado<br>O estado dos alunos deferidos ser�o alterados automaticamente para cursando.';
                break;
            case 'FINALIZADA':
                paragrafo.innerHTML = 'OPERA��O SEM VOLTA.<br>Todas as notas, faltas e situa��o final para os alunos em situa��o cursando para essa turma devem estar preenchidas';
                divResumoDeAlunos.style.display = 'block';
                break;
            case 'CANCELADA':
                paragrafo.innerHTML = 'OPERA��O SEM VOLTA.<br>Caso se deseje criar outra turma parecida, dever-se-� criar uma nova turma';
                break;
            default:
                paragrafo.innerHTML = '&nbsp;';
                break;
        }
    }
</script>

<script type="text/javascript">
    function botaoModificar(){
        situacaoSelecionada = document.mudarSituacaoDaTurma.novaSituacao.value;
        if (situacaoSelecionada == '') {
            alert('Nova situa��o n�o foi preenchida');
            return;
        }

        if (situacaoSelecionada == 'FINALIZADA') {
            res = confirm("O di�rio est� fechado e conferido ?");
            if (!res){
                return;
            }
        }

        if (situacaoSelecionada == 'CANCELADA') {
            msg = "-- CANCELAMENTO --\n"+
                  "\n"+
                  "Confirma cancelamento ?";
            res = confirm(msg);
            if (!res){
                return;
            }
        }

        mudarSituacaoTurma();

    }
</script>

<form id="cadastro" name="mudarSituacaoDaTurma" method="POST" action="nenhuma" >
    <fieldset>
        <legend>Alterar Situa��o da Turma</legend>
        <input type="hidden" name="auxIdTurma" value="<?php echo $dadosDaTurma['idTurma'] ?>"><br>
        <label>Curso:</label> <?php echo $dadosDaTurma['siglaCurso'] ?> - <?php echo $dadosDaTurma['nomeCurso'] ?><br>
        <label>Per�odo Letivo:</label> <?php echo $dadosDaTurma['siglaPeriodoLetivo'] ?><br>
        <label>Data da Matriz:</label> <?php echo Util::dataSQLParaBr($dadosDaTurma['dataInicioVigencia']) ?><br>
        <p>
        <label>Disciplina:</label> <?php echo $dadosDaTurma['siglaDisciplina'] ?> - <?php echo $dadosDaTurma['nomeDisciplina'] ?><br>
        <label>Cr�ditos:</label> <?php echo $dadosDaTurma['creditos'] ?><br>
        <label>Carga Hor�ria:</label> <?php echo $dadosDaTurma['cargaHoraria'] ?> horas/aula<br>
        </p>
        <label>Per�odo na matriz:</label> <?php echo $dadosDaTurma['periodo'] ?>� periodo<br>
        <label>Tipo:</label> <?php echo $dadosDaTurma['tipoComponenteCurricular'] ?><br>
        <label>Grade de hor�rio:</label> <?php echo $dadosDaTurma['gradeHorario'] ?><br>
        <p>
        <label>Turno:</label> <?php echo $dadosDaTurma['turno'] ?><br>
        <label>Situa��o da Turma:</label> <?php echo $dadosDaTurma['tipoSituacaoTurma'] ?><br>
        <label>Professor:</label> <?php echo $dadosDaTurma['nomeProfessor'] ?><br>
        </p>
        <label>Qtde de vagas:</label> <?php echo $dadosDaTurma['qtdeTotal'] ?><br/><br/>
        Mudar situa��o para:
        <select name="novaSituacao" onchange="informaImpactoDaMudanca()">
            <option></option>
            <?php
                $situacoesDisponiveis = $turma->obterProximasSituacoesPossiveis();
                foreach ($situacoesDisponiveis as $situacao) {
                    echo "<option value='$situacao'>$situacao</option>";
                }
            ?>
        </select>
        <p style="color: #900000" id="paragrafoInformaImpacto">
            &nbsp;
        </p>

        <div id="divResumoDeAlunos" style="display:none;">
            <?php
                $resumoAlunosAprovados = $turma->getAlunosBySituacao('AP');
                $resumoAlunosReprovadosMedia = $turma->getAlunosBySituacao('RM');
                $resumoAlunosReprovadosFalta = $turma->getAlunosBySituacao('RF');
                $resumoAlunosIsentos = $turma->getAlunosBySituacao('ID');

                // APROVADOS
                if (count($resumoAlunosAprovados) == 0){
                    ?><b>N�o existem alunos aprovados</b><br><?php
                }else{
                    ?><ul><b>Alunos Aprovados</b><?php
                    foreach ($resumoAlunosAprovados as $alu) {
                        echo '<li>'.Util::formataNome($alu->getNomeAluno()).'';
                    }
                    ?></ul><?php
                }

                // REPROVADOS POR M�DIA
                if (count($resumoAlunosReprovadosMedia) == 0){
                    ?><b>N�o existem alunos reprovados por m�dia</b><br><?php
                }else{
                    ?><ul><b>Alunos Reprovados por M�dia</b><?php
                    foreach ($resumoAlunosReprovadosMedia as $alu) {
                        echo '<ul>'.Util::formataNome($alu->getNomeAluno()).'</ul>';
                    }
                    ?></ul><?php
                }

                // REPROVADOS POR FALTA
                if (count($resumoAlunosReprovadosFalta) == 0){
                    ?><b>N�o existem alunos reprovados por falta</b><br><?php
                }else{
                    ?><ul><b>Alunos Reprovados por Falta</b><?php
                    foreach ($resumoAlunosReprovadosFalta as $alu) {
                        echo '<ul>'.Util::formataNome($alu->getNomeAluno()).'</ul>';
                    }
                    ?></ul><?php
                }

                // ISENTOS DE DISCIPLINA
                if (count($resumoAlunosIsentos) == 0){
                    ?><b>N�o existem alunos isentos de disciplina</b><br><?php
                }else{
                    ?><ul><b>Alunos Reprovados por </b><?php
                    foreach ($resumoAlunosIsentos as $alu) {
                        echo '<ul>' . Util::formataNome($alu->getNomeAluno()) . '</ul>';
                    }
                    ?></ul><?php
                }
            ?>
        </div>
            <table style="width: 90%;" align="center">
                <tr>
                    <td>
                        <input id="modificar" type="button" value="Modificar" onclick="botaoModificar();" style="width: 100px">
                    </td>
                    <td align="right">
                        <input type="button" value="Voltar" onclick="document.formVoltar.submit();" style="width: 100px">
                    </td>
                </tr>
            </table>
    </fieldset>
</form>
<form name="formVoltar" action="manterTurmas_controle.php?acao=exibirTurmas" method="POST">
    <input type="hidden" name="siglaCurso" value = "<?php echo $dadosDaTurma['siglaCurso']; ?>">
    <input type="hidden" name="idPeriodoLetivo" value = "<?php echo $dadosDaTurma['idPeriodoLetivo']; ?>">
    <input type="hidden" name="turno" value = "<?php echo $_POST['voltar_turno']; ?>">
</form>
<form name="formEnviarExtrato" action="/coruja/interno/enviarExtratoTurmaParaProfessor/enviarExtratoTurmaParaProfessor_controle.php" method="POST">
    <input type="hidden" name="idTurma" value = "<?php echo $dadosDaTurma['idTurma'] ?>" />
    <input type="hidden" name="siglaCurso" value = "<?php echo $dadosDaTurma['siglaCurso']; ?>">
    <input type="hidden" name="idPeriodoLetivo" value = "<?php echo $dadosDaTurma['idPeriodoLetivo']; ?>">
    <input type="hidden" name="turno" value = "<?php echo $_POST['voltar_turno']; ?>">
</form>