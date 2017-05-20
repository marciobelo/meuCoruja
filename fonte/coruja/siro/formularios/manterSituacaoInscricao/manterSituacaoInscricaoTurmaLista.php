
<script type="text/javascript">
    function obterName(nome){
          document.listaManterInscricao.solicitada.value = nome;
          document.listaManterInscricao.submit();
    }

    function volta(){
          window.location.href = 'ManterSituacaoInscricaoTurma_controle.php?action=curso';
    }
</script>


<form name="listaManterInscricao" method="POST" action="ManterSituacaoInscricaoTurma_controle.php?action=turmaSelecionada">
    <input type="HIDDEN" name="solicitada" value=""/>

    <table class="table_lista" width="100%" border=0 id="Lista">

        <tr align=center class="tr_cabecalho_lista">
            <td>Per&iacute;odo</td>
            <td>Grade Hor&aacute;ria</td>
            <td>Turno</td>
            <td>Disciplina</td>
            <td>Vagas Ofertadas</td>
            <td>Vagas Dispon&iacute;veis</td>
            <td>Deferidos / Inscritos</td>
            <td>Indeferidos</td>
            <td>Solicita&ccedil;&otilde;es Pendentes</td>
        </tr>


 <?php
    
    $controlAcao="solicitacoes";
    foreach($turmasLiberadas as $turmasListadas) {

        //DECLARACAO DE VARIAVEIS UTILIZADAS NA LISTA
        $corLinha; //Usada para alternar o CSS nas linhas
        $componente = ComponenteCurricular::obterComponenteCurricular($turmasListadas->getSiglaCurso(), $turmasListadas->getIdMatriz(), $turmasListadas->getSiglaDisciplina());

        // OBTEM AS INSCRICOES PARA CALCULO
        $inscricoesDeferidas = Inscricao::obterInscricoesAlunos($turmasListadas->getIdTurma(), "'DEF','CUR'");
        $inscricoesIndeferidas = Inscricao::obterInscricoesAlunos($turmasListadas->getIdTurma(), "'NEG'");
        $inscricoesPendentes = Inscricao::obterInscricoesAlunos($turmasListadas->getIdTurma(), "'REQ'");

        $contaInscricoesPendentes = count($inscricoesPendentes);
        $contaInscricoesIndeferidas = count($inscricoesIndeferidas);
        $contaInscricoesDeferidas = count($inscricoesDeferidas);
        $vagasTurma = $turmasListadas->getQtdeTotal();
        $vagasDisponiveis = $vagasTurma-$contaInscricoesDeferidas;

        $turmaInput=$turmasListadas->getIdTurma();


        $link="../formularios/manterSituacaoInscricao/manterSituacaoInscricaoTurmaSolDisc.php";
       //VERIFICA SE A LINHA DEVE RECEBER COR DIFERENCIADA
        if ($contaLinha % 2 ==0) {
            $corLinha="class='tr_turma'";
        }
        else {
            $corLinha="class='tr_turmaSemCor'";
        }

        echo"<tr align=center ".$corLinha." >";
        echo"<td>". $componente->getPeriodo()."º</td>";//PERIODO
        echo"<td>". $turmasListadas->getGradeHorario()."</td>";//GRADE
        echo"<td>". $turmasListadas->getTurno()."</td>";//TURNO
        echo"<td align=left>";
        $title="Clique aqui para visualizar as solicitações";
        echo "<a title='".$title."' href=\"javascript:obterName(".$turmaInput.")\" class = 'a_Disc'>".
                $turmasListadas->getSiglaDisciplina() .
                " ( ".$componente->getNomeDisciplina()." )</a></td>";//DISCIPLINA
        echo"<td>". $turmasListadas->getQtdeTotal() ."</td>";//VAGAS OFERTADAS
        echo"<td>". $vagasDisponiveis."</td>";//VAGAS DISPONIVEIS
        echo"<td class = 'td_critico'>". $contaInscricoesDeferidas."</td>";//INDEFERIDAS
        echo"<td class = 'td_critico'>". $contaInscricoesIndeferidas."</td>";//INDEFERIDAS
        echo"<td class = 'td_critico'>". $contaInscricoesPendentes ."</td>";//PENDENTES
        echo"</tr>";
        $contaLinha++;
    }

    //TABELA BOTOES (VOLTAR)
    echo"<table border='0' align='right'>";
    echo"<tr>";
    echo"<td><input class='confirmar' name='voltar' type='button' value='Voltar' onclick='volta();  '/></td>";

    echo"</tr>";
    echo"</table>";
?>
    
    </table>
</form>