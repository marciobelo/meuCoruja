<form id="formManterAlunosQueCursamTurma_controle" name="formManterAlunosQueCursamTurma_controle" method="post">
    <input type="hidden" name="acao" id="acao" />
    <input type="hidden" name="matriculaAluno" id="matriculaAluno" />
    <input type="hidden" name="idTurma" id="idTurma" />
</form>

<script type="text/javascript">
    function obterAcao(acao,matriculaAluno,idTurma) {
        var form = document.getElementById("formManterAlunosQueCursamTurma_controle");
        form.acao.value = acao;
        form.matriculaAluno.value = matriculaAluno;
        form.idTurma.value = idTurma;
        form.action = "ManterAlunosQueCursamTurma_controle.php";
        form.submit();
    }
</script>

<form name="descricao" id="descricao">
    <fieldset id="fieldsetGeral">
        <b>Listagem de Alunos da Turma: <?php echo "$siglaDisciplina - $nomeDisciplina";?>
        <br>Curso: <?php echo $detalhesTurma->getSiglaCurso()." - ".$nomeCurso;?>
        <br>Per&iacute;odo Letivo: <?php echo $perLetivo->getSiglaPeriodoLetivo();?>&nbsp;&nbsp;&nbsp;&nbsp;
        Turno: <?php echo $detalhesTurma->getTurno();?>&nbsp;&nbsp;&nbsp;&nbsp;
        Grade: <?php echo $detalhesTurma->getGradeHorario();?></b>
    <br />
    </fieldset>
</form>

<?php
    echo '<form name="cadastro" id="cadastro" action="ManterAlunosQueCursamTurma_controle.php" method="post">';
    echo '<fieldset id="fieldsetGeral">';
            // PARÂMETROS QUE DEFINEM A ACAO A SER EXECUTADA
        echo "<input type='hidden' id='acao' name='acao' value='formBusca' />";
        echo "<input type='hidden' id='idTurma' name='idTurma' value='$idTurma' />";
        echo "<input type='hidden' id='siglaDisciplina' name='siglaDisciplina' value='$siglaDisciplina' />";
        echo "<input type='hidden' id='nomeDisciplina' name='nomeDisciplina' value='$nomeDisciplina' />";

            // POR PADRAO UMA NOVA TURMA E CRIADA COMO 'PLANEJADA'
          
        // checa se ha resultados
        if(empty($listaAlunosTurma)) {
            echo "<b>N&atilde;o h&aacute; alunos inscritos que estejam na situação CURSANDO nessa turma.</b>";
            echo "<br /><br />";
        } else {

            echo "<table width=100%>";
            echo "<tr align='center'><th>Matr&iacute;cula</th><th>Nome</th><th>Excluir</th></tr>";
            $cor = 1;
            foreach($listaAlunosTurma as $itens)
            {
                // SCRIPT REFERENTE A EDIÇÃO DA SITUACAO DA TURMA DE FORMA ASSINCRONA

                if($cor==1){$corfundo='#00BFFF'; $cor=2;}
                elseif($cor==2){$corfundo=''; $cor=1;}

                $lista .= "<tr bgcolor='$corfundo'><td width='7%' align='center'>";
                $lista .= $itens->getMatriculaAluno();
                $lista .= "</td>";
                $lista .= "<td>&nbsp;";

                $lista .= strtoupper($itens->getNomeAluno());
                
                $lista .= " </b></a>";

                $lista .="</td>";
               
                $lista .= "<td width='7%' align='center'><a href=\"javascript:obterAcao('excluirAlunoQueCursa','";
                $lista .= $itens->getMatriculaAluno();
                $lista .= "','";
                $lista .= $itens->getIdTurma();
                $lista .= "')\" ";
                $lista .= "onClick='return confirm(\"Tem certeza que deseja excluir esse aluno?\")'>";
                $lista .= "<img src='../imagens/excluir.png' border='0' alt='Excluir Turma'></a></td></tr>";
            }
            $lista .= "</table><br />";

            echo $lista;
        }

        echo '<br />';
        echo '<p align="center"><br />';
        echo '<input id="button1" type="submit" name="incluirAlunoQueCursa" value="  Incluir Novo Aluno  "  ';
        if($detalhesTurma->getQtdeTotal() <= count($listaAlunosTurma)) {
                echo "onClick='return confirm(\"Esta turma tem " . $detalhesTurma->getQtdeTotal() . " vagas e já está " .
            count($listaAlunosTurma) ." inscritos. Deseja incluir um aluno assim mesmo ? \")'";
        }
        echo "/>";
        
        echo '</form>';
        echo '</fieldset>';
        echo '</p>';

?>
<form name="voltar" id="voltar" action="ManterAlunosQueCursamTurma_controle.php" method="post">
    <input type='hidden' id='acao' name='acao' value='verTurmas' />
    <input type='hidden' id='siglaCurso' name='siglaCurso' value='<?php echo $detalhesTurma->getSiglaCurso(); ?>' />
    <input type='hidden' id='idPeriodoLetivo' name='idPeriodoLetivo' value='<?php echo $detalhesTurma->getIdPeriodoLetivo(); ?>' />
    <input type='hidden' id='turno' name='turno' value='<?php echo $detalhesTurma->getTurno(); ?>' />
    <input type='submit' id='button1' value='Voltar' />
</form>