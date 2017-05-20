<?php
require_once "$BASE_DIR/includes/topo.php";
require_once "$BASE_DIR/includes/menu_horizontal.php";
require_once "$BASE_DIR/classes/Inscricao.php";

?>

<script type="text/javascript">
    <?php require_once "$BASE_DIR/nort/javascripts/ajax.js";?>
    
    <?php require_once "$BASE_DIR/nort/javascripts/trim.js";?>
</script>

<script type="text/javascript">

    function salvarDadosAJAX(){

        xmlhttp = GetXMLHttp();
        //xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function()
        {
            if (xmlhttp.readyState==4 && xmlhttp.status==200)
            {
                resposta = xmlhttp.responseText;
                if(trim(resposta) == "OK"){ // trim() não é nativo do javascript
                    alert ("Nota, faltas e situação modificadas com sucesso");
                    document.formVoltar.submit();
                }else if(trim(resposta) == "Alunos reprovados por falta devem ter média 0,0"){
                    alert (resposta);
                    document.formEditarNotas.mediaFinal.value = '0,0';
                    document.formEditarNotas.mediaFinal.focus();
                } else {
                    alert (resposta);
                }
            }
        }

        xmlhttp.open("POST","<?php echo $_SERVER['PHP_SELF'] ?>?acao=salvarDadosAJAX",true);
        xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        idTurma = "<?php echo $inscricao->getIdTurma();?>";
        numMatriculaAluno = "<?php echo $inscricao->getMatriculaAluno();?>";
        insc_mediaFinal = document.formEditarNotas.mediaFinal.value;
        insc_totalFaltas = document.formEditarNotas.totalFaltas.value;
        insc_situacao = document.formEditarNotas.situacao.value;
        insc_parecer = document.formEditarNotas.parecerInscricao.value;

        conteudoPost = "";

        conteudoPost+="idTurma="+idTurma+
            "&numMatriculaAluno="+numMatriculaAluno+
            "&insc_mediaFinal="+insc_mediaFinal+
            "&insc_totalFaltas="+insc_totalFaltas+
            "&insc_situacao="+insc_situacao+
            "&insc_parecer="+insc_parecer;

        //alert("POST: "+conteudoPost);
        xmlhttp.send(conteudoPost);
    }

</script>

<script type="text/javascript">
    var ultimaMediaFinalValida;

    function formataMediaFinalOnBlur(){

        mediaFinal = document.formEditarNotas.mediaFinal.value.replace(',', '.'); //Troca virgula por ponto
        document.formEditarNotas.mediaFinal.value = '';
        mediaFinal = parseFloat(mediaFinal);
        mediaFinal = mediaFinal.toFixed(1); //Formata para 00.0
        if(mediaFinal > 10){
            //mediaFinal = '10.0';
            alert("Voce digitou uma média final acima de 10,0");
            document.formEditarNotas.mediaFinal.focus();
        }
        mediaFinal = mediaFinal.replace('.', ','); //Troca ponto por virgula

        if(mediaFinal == 'NaN'){
            mediaFinal = '';
        }

        document.formEditarNotas.mediaFinal.value = mediaFinal;
    }
    
    function formataMediaFinalOnKeyUp(){
        mediaFinal = document.formEditarNotas.mediaFinal.value;

        if(mediaFinal == ''){
            //Nao precisa validar
            return;
        }

        
        if(mediaFinal.search(/\,[0-9]?\,/) >= 0){
            //Formato incorreto ! (ex. 3,,0 ou 1,1,)
            document.formEditarNotas.mediaFinal.value = '';
            return;
        }
    }
    function situacaoOnChange(){
        situacaoSelecionada = document.formEditarNotas.situacao.value;
        divParecer = document.getElementById("divParecer");
        if(situacaoSelecionada == 'ID'){
            divParecer.style.display = 'block';
            desabilitaNotasEFaltas(true); //Habilita notas e total de faltas
        }else{
            divParecer.style.display = 'none';
            desabilitaNotasEFaltas(false); //Desabilita notas e total de faltas
        }
    }

    function desabilitaNotasEFaltas(desabilitar){
        //Desabilita ou reabilita
        document.formEditarNotas.mediaFinal.disabled = desabilitar;
        document.formEditarNotas.totalFaltas.disabled = desabilitar;

        if(desabilitar){
            //Apaga os valores
            document.formEditarNotas.mediaFinal.value = "";
            document.formEditarNotas.totalFaltas.value = "";
        }
    }
</script>

<form id="cadastro" name="formEditarNotas" method="POST" action="editarNotas_controle.php" >
    <input type="hidden" name="idTurma" value="<?php echo $turma->getIdTurma(); ?>">
    <input type="hidden" name="numMatriculaAluno" value="ajustadoPorJavascrit">
    <input type="hidden" name="voltar_turno" value="<?php echo $_POST['voltar_turno']; ?>">
    <fieldset>
        <legend>Editar Notas</legend>
        <table>
            <tr>
                <td width="110">Curso</td>
                <td><?php echo $turma->getCurso()->getNomeCurso(); ?></td>
            </tr>
            <tr>
                <td>Período Letivo</td>
                <td><?php echo $turma->getPeriodoLetivo()->getSiglaPeriodoLetivo(); ?></td>
            </tr>
            <tr>
                <td>Turno</td>
                <td><?php echo $turma->getTurno(); ?></td>
            </tr>
            <tr>
                <td>Grade</td>
                <td><?php echo $turma->getGradeHorario(); ?></td>
            </tr>
            <tr>
                <td>Disciplina</td>
                <td><?php echo $turma->getSiglaDisciplina() . ' - ' . $turma->getComponenteCurricular()->getNomeDisciplina(); ?></td>
            </tr>
            <tr>
                <td>Professor</td>
                <td>
                <?php
                if($turma->getProfessor()==null) {
                    echo "Sem professor";
                } else {
                    echo $turma->getProfessor()->getNome();
                }
                ?>
                </td>
            </tr>

            <tr><td>&nbsp;</td></tr>

            <tr>
                <td>Matrícula</td>
                <td><b><?php echo $inscricao->getMatriculaAluno(); ?></b></td>
            </tr>
            <tr>
                <td>Nome</td>
                <td><b><?php echo $aluno->getNome(); ?></b></td>
            </tr>
            <tr>
                <td>Média Final</td>
                <td>
                    <?php
                        $mf = $inscricao->getMediaFinal();
                        $mf = (String) $mf;
                        $mf = str_replace('.',',',$mf);
                    ?>
                    <input name="mediaFinal" value="<?php echo $mf;?>" type="text" onkeyup="javascript:this.value=this.value.replace(/[^0-9,]/g, '');formataMediaFinalOnKeyUp();" onblur="formataMediaFinalOnBlur();" style="width: 35px" maxlength="4">
                    <font size="2">(Formato: 10,0)</font>
                </td>
            </tr>
            <tr>
                <td>Total de Faltas</td>
                <td><input name="totalFaltas" value="<?php echo $inscricao->getTotalFaltas(); ?>" type="text" onkeyup="javascript:this.value=this.value.replace(/[^0-9]/g, '');" style="width: 35px" maxlength="3"></td>
            </tr>
            <tr>
                <td>Situa&ccedil;&atilde;o</td>
                <td>
                    <select name="situacao" onchange="situacaoOnChange();" >
                        <option value="CUR" <?php echo $inscricao->getSituacaoInscricao() == Inscricao::CUR ? 'SELECTED="SELECTED"':''; ?> >Cursando</option>
                        <option value="AP" <?php echo $inscricao->getSituacaoInscricao() == Inscricao::AP ? 'SELECTED="SELECTED"':''; ?> >Aprovado</option>
                        <option value="RM" <?php echo $inscricao->getSituacaoInscricao() == Inscricao::RM ? 'SELECTED="SELECTED"':''; ?> >Reprovado por Média</option>
                        <option value="RF" <?php echo $inscricao->getSituacaoInscricao() == Inscricao::RF ? 'SELECTED="SELECTED"':''; ?> >Reprovado por Falta</option>
                        <option value="ID" <?php echo $inscricao->getSituacaoInscricao() == Inscricao::ID ? 'SELECTED="SELECTED"':''; ?> >Isento de Disciplina</option>
                    </select>
                </td>
            </tr>
        </table>
        <div id="divParecer" style="display:<?php echo $inscricao->getSituacaoInscricao() == Inscricao::ID ? 'block':'none'; ?>;">
            <table>
                <tr>
                    <td width="110">Parecer</td>
                    <td><textarea cols="3" rows="40" name="parecerInscricao"><?php echo $inscricao->getParecerInscricao(); ?></textarea></td>
                </tr>
            </table>
        </div>
        <table style="width: 90%;" align="center">
            <tr>
                <td><input type="button" value="Editar" onclick="salvarDadosAJAX();" style="width: 100px"></td>
                <td align="right"><input type="button" value="Voltar" onclick="document.formVoltar.submit();" style="width: 100px"></td>
            </tr>
        </table>
    </fieldset>
</form>



<form name="formVoltar" action="lancarNotas_controle.php?acao=consultarNotas" method="POST">
    <input type="hidden" name="idTurma" value = "<?php echo $turma->getIdTurma(); ?>">
    <input type="hidden" name="voltar_turno" value = "<?php echo $_POST['voltar_turno']; ?>">
</form>

