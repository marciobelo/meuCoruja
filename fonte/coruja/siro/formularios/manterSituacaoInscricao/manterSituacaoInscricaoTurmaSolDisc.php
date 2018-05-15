<?php
//CONTROLA A ACAO DO FORM
if($acaoSolicitacao=="solicitacoes") {
    $actionLocal = "manterSolicitacao";
}
if($acaoSolicitacao=="Deferir") {
    $actionLocal = "confirmarDeferirInscricao";
}
if($acaoSolicitacao=="Indeferir") {
    $actionLocal = "confirmarIndeferirInscricao";
}
if($acaoSolicitacao=="Cancelar") {
    $actionLocal = "cancelarInscricao";
}

?>

<script type="text/javascript">

    function cancelar(){
        window.location.href = 'ManterSituacaoInscricaoTurma_controle.php?action=turmaSelecionada';
    }

    function volta(){
        window.location.href = 'ManterSituacaoInscricaoTurma_controle.php?action=listar';
    }


    function finalizar(existeJustificativa)
    {
        if(existeJustificativa){
            if(document.listaSolicitacoesTurma.rnJustificativa.value.length<=3){
                alert('É obrigatório o preenchimento da justificativa!');
                document.listaSolicitacoesTurma.rnJustificativa.focus();
            }else{
                document.listaSolicitacoesTurma.submit();
            }
        }else{
            document.listaSolicitacoesTurma.submit();
        }
    }

    function enviar(matricula,Turma,nomeAcao)
    {
        document.listaSolicitacoesTurma.matriculaAluno.value = matricula;
        document.listaSolicitacoesTurma.nomeAcao.value = nomeAcao;
        document.listaSolicitacoesTurma.idTurma.value = Turma;
        document.listaSolicitacoesTurma.submit();
    }
    
    $(function() 
    {
        $("#deferirAutomatico").on("click", function() 
        {
            $.ajax(
            {
                url: "DeferirAutomatico_controle.php",
                type: "POST",
                data:
                {
                    idTurma: "<?php echo $turmaSelecionada->getIdTurma(); ?>"
                },
                success: function( retornoString)
                {
                    var retorno = parseInt( retornoString);
                    if( retorno === -1) // mais requisicoes do que vagas
                    {
                        window.alert( "Há mais requisições do que vagas restantes na turma");
                    }
                    else if( retorno === -2) // sem permissao
                    {
                        window.alert( "Você não tem permissão para executar essa ação.");
                    }
                    else
                    {
                        window.alert( retorno + " alunos foram deferidos automaticamente.");
                        location.href= "/coruja/siro/controle/ManterSituacaoInscricaoTurma_controle.php?action=turmaSelecionada&idTurma=<?php echo $turmaSelecionada->getIdTurma(); ?>";
                    }
                },
                error: function()
                {
                    window.alert( "Houve algum erro no deferimento automático. Avise o suporte.");
                }
            });
        });
    });
</script>

<form name="listaSolicitacoesTurma" id="listaSolicitacoesTurma" method="post" action="ManterSituacaoInscricaoTurma_controle.php?action=<?php echo $actionLocal;?>">
    <input type="HIDDEN" name="matriculaAluno" value="<?php echo $matriculaAluno; ?>" />
    <input type="HIDDEN" name="idTurma" value="<?php echo $manterInscricaoTurma; ?>" />
    <input type="HIDDEN" name="nomeAcao" value="" />

    <table class="table_lista" width="100%" id="descricaoTurma">
        <tr align="center" class="tr_cabecalho_lista">
            <td>Curso</td>
            <td>Período</td>
            <td>Grade Horária</td>
            <td>Turno</td>
            <td>Disciplina</td>
            <td>Vagas Ofertadas</td>
            <td>Vagas Disponíveis</td>
            <td>Solicitações</td>
        </tr>

        <?php

        $idMatriz=$turmaSelecionada->getIdMatriz();
        $siglaDisciplina=$turmaSelecionada->getSiglaDisciplina();
        $componente=ComponenteCurricular::obterComponenteCurricular($siglaCurso, $idMatriz, $siglaDisciplina);
        $condicao = "'REQ', 'NEG', 'DEF'";
        $inscricoes = Inscricao::obterInscricoesAlunos($turmaSelecionada->getIdTurma(), $condicao);
        $contaInscricoes = count($inscricoes);

        $inscricoesDeferidas = Inscricao::obterInscricoesAlunos($turmaSelecionada->getIdTurma(), "'DEF','CUR'");

        $contaInscricoesDeferidas = count($inscricoesDeferidas);
        $vagasTurma = $turmaSelecionada->getQtdeTotal();
        $vagasDisponiveis = $vagasTurma-$contaInscricoesDeferidas;

        echo  "<tr align='center' class='td_descTurma'>";
        echo  "<td>".$siglaCurso."</td>"; //CURSO
        echo  "<td>".$componente->getPeriodo()."º</td>"; //PERIODO DA TURMA
        echo  "<td>".$turmaSelecionada->getGradeHorario()."</td>"; // GRADE HORARIA
        echo  "<td>".$turmaSelecionada->getTurno()."</td>"; //TURNO
        echo  "<td>".$turmaSelecionada->getSiglaDisciplina() .
                " ( ".$componente->getNomeDisciplina()." )</td>"; //DISCIPLINA
        echo  "<td>".$turmaSelecionada->getQtdeTotal()."</td>"; // VAGAS OFERTADAS
        echo"<td class = 'td_critico'>". $vagasDisponiveis."</td>";//VAGAS DISPONIVEIS
        echo  "<td class = 'td_critico'>".$contaInscricoes."</td>"; // SOLICITACOES
        echo "<td style='background-color: #FFF;'><input class='confirmar' id='deferirAutomatico' type='button' value='Deferir Automático'/></td>";
        echo  "</tr>";
        ?>

    </table>
    
    <br/>

    <table  class="table_lista" width="100%" id="listaAlunosSolicitacoes">
        <tr align=center class="tr_cabecalho_lista">
            <td>Matr&iacute;cula</td>
            <td width="15%">Situa&ccedil;&atilde;o Atual da Matr&iacute;cula</td>
            <td>Nome</td>
            <td width="10%">Turno de Ingresso</td>
            <td width="10%">C.R.</td>
            <td width="15%">Hist&oacute;rico na Disciplina</td>
            <td width="15%">Status da Solicita&ccedil;&atilde;o</td>
            <?php
            if($mostrarAcao) {
                echo  "<td>A&ccedil;&atilde;o</td>";
            }
            ?>
        </tr>

        <?php

        if($acaoSolicitacao=="solicitacoes") {

            $contaLinhaParaZebrar=0;

            // Lista as solicitações dos alunos que são do mesmo turno da turma ofertada
            $inscricoes = Inscricao::obterInscricoesAlunosMesmoTurnoDaOferta($turmaSelecionada->getIdTurma(), "'REQ'");
            require "$BASE_DIR/siro/formularios/manterSituacaoInscricao/manterSituacaoInscricaoTurmaCarregarSolicitacoes.php";

            // LISTA OS ALUNOS COM INSCRICOES PENDENTES DE TURNO DIFERENTE DA TURMA
            // Lista as solicitações dos alunos que são de turno diferente da turma ofertada
            $inscricoes = Inscricao::obterInscricoesAlunosTurnoDiferenteDaOferta($turmaSelecionada->getIdTurma(), "'REQ'");
            require "$BASE_DIR/siro/formularios/manterSituacaoInscricao/manterSituacaoInscricaoTurmaCarregarSolicitacoes.php";

            // LISTA OS ALUNOS COM INSCRICOES DEFERIDAS
            $inscricoes = Inscricao::obterInscricoesAlunos($turmaSelecionada->getIdTurma(), "'DEF'");
            require "$BASE_DIR/siro/formularios/manterSituacaoInscricao/manterSituacaoInscricaoTurmaCarregarSolicitacoes.php";

            //LISTA OS ALUNOS COM INSCRICOES INDEFERIDAS
            $inscricoes = Inscricao::obterInscricoesAlunos($turmaSelecionada->getIdTurma(), "'NEG'");
            require "$BASE_DIR/siro/formularios/manterSituacaoInscricao/manterSituacaoInscricaoTurmaCarregarSolicitacoes.php";

            //TABELA BOTOES (VOLTAR)
            echo"<table border='0' align='right'>";
            echo"<tr>";
            //  echo"<td><input class='confirmar' name='voltar' type='button' value='Voltar' onclick='javascript:history.back();  '/></td>";

            echo"<td><input class='confirmar' name='voltar' type='button'".
                    " value='Voltar' onclick='volta();'/></td>";

            echo"</tr>";
            echo"</table>";
        }

        //CASO JÁ TENHA ESCOLHIDO A AÇÃO
        else {

            $insc = new Inscricao();
            $insc->carregarInscricao($turmaSelecionada->getIdTurma(), $matriculaAluno);

            $inscricoes = array($insc);

            require "$BASE_DIR/siro/formularios/manterSituacaoInscricao/manterSituacaoInscricaoTurmaCarregarSolicitacoes.php";

            // VERIFICANDO RNs - TABELA RNs
            $listaRN='';
            $existeJustificativa=false;
            if($colideRN08 || !$cumpreRequisitosRN09 || $alunoRF_RN10 || ($contaRN11>=3 || $alunoRN12 || $tIngressoRN22)) {

                echo"<table class='table_RN' border=1>";
                echo "<br>";
                echo"<caption class='RN_cabecalho'>";
                $alerta = htmlspecialchars("Porém existem restrições quanto a sua solicitação de ".
                        "inscrição, descritas abaixo, por favor leia atentamente: ", ENT_QUOTES, "iso-8859-1");
                echo $alerta;
                echo"</caption>";

 
                //VERIFICA RN08
                if($colideRN08) {
                    echo"<tr>";
                    echo"<td class='td_fundoRN'>RN08</td>";
                    echo"<td>";
                    echo "<table>";
                    echo "<td  class='td_textoRN'>";

                    $texto=$rn->mensagemRN08();
                    echo $texto;
                    $listaRN.=$rn->mensagemRN08Curta()."\n";

                    
                    $texto=NULL;

                    $texto.= htmlspecialchars("A seguir as turmas e os tempos que colidiram: ", ENT_QUOTES, "iso-8859-1");

                    echo $texto;

                    $listaRN.=$texto."\n";

                    echo "</td>";

                    echo "<td>";

                    //OBTEM A TURMA QUE COLIDIU

                    $turmaRN08 = NULL;
                    $siglaDisciplina= array();
                    $tempoRN08 = array();
                    $tColide = array();

                    for($i=0;$i<=count($turmaColide);$i++) {
                        $turmaRN08 = $turmaColide[$i]["turma"];
                         if((!empty ($turmaRN08)) && ($turmaRN08!=$manterInscricaoTurma)) {
                            $tColide = Turma::getTurmaById($turmaRN08);
                            $siglaDisciplina[]=$tColide->getSiglaDisciplina();
                            $tempoRN08[] = TempoSemanal::getTempoSemanalById($turmaColide[$i]["tempo"]);
                         }
                    }

                    // Horarios das Turmas
                    echo "<table class='table_lista'>";
                    echo "<tr class='tr_cabecalho_lista'><th> Turma </th><th> Dia </th><th> In&iacute;cio </th><th> Fim </th></tr>";
                    $cont=0;

                    foreach ($tempoRN08 as $turmaHorarios) {
                        echo "<tr class='tr_turma'><td  class='td_turmaConflitoGrade'>";
                        echo $siglaDisciplina[$cont];
                        $listaRN.=$siglaDisciplina[$cont].": ";
                        echo "</td><td>";
                        echo $turmaHorarios->getDiaSemana();
                        $listaRN.=$turmaHorarios->getDiaSemana()." » ";
                        echo "</td><td>";
                        echo $turmaHorarios->getHoraInicio();
                        $listaRN.=$turmaHorarios->getHoraInicio()." - ";
                        echo "</td><td>";
                        echo $turmaHorarios->getHoraFim();
                        $listaRN.=$turmaHorarios->getHoraFim().";\n";
                        echo "</td></tr>";
                        $cont++;
                    }
                    echo"</table>";
                    echo "</td>";
                    echo "</td>";
                    echo "</table>";                  
                    echo "</td>";
                    echo"</tr>";
                    $listaRN.="\n";
                }
                //FIM DA RN08

                //VERIFICA RN09
                if(!$cumpreRequisitosRN09) {
                    echo"<tr>";
                    echo"<td class='td_fundoRN'>RN09</td>";
                    echo"<td class='td_textoRN'>";
                    echo $rn->mensagemRN09($listaComponente);
                    $listaRN.=$rn->mensagemRN09Curta($listaComponente)."\n\n";
                    echo "</td>";
                    echo"</tr>";
                }
                //FIM DA RN09

                //VERIFICA RN10
                if($alunoRF_RN10) {
                    echo"<tr>";
                    echo"<td class='td_fundoRN'>RN10</td>";
                    echo"<td class='td_textoRN'>";
                    echo $rn->mensagemRN10();
                    $listaRN.=$rn->mensagemRN10Curta()."\n\n";
                    echo "</td>";
                    echo"</tr>";
                }
                //FIM DA RN10
                /*
                * 3 vezes ou mais (seja por falta ou por m?dia), do mesmo curso,
                */

                //VERIFICA RN11
                if($contaRN11>=3) {
                    echo"<tr>";
                    echo"<td class='td_fundoRN'>RN11</td>";
                    echo"<td class='td_textoRN'>";
                    echo $rn->mensagemRN11();
                    $listaRN.=$rn->mensagemRN11Curta()."\n\n";
                    echo "</td>";
                    echo"</tr>";
                }
                //FIM DA RN11

                // CASO EXISTA RN12
                if($alunoRN12) {
                    echo"<tr>";
                    echo"<td class='td_fundoRN'>RN12</td>";
                    echo"<td class='td_textoRN'>";
                    echo $rn->mensagemRN12();
                    $listaRN.=$rn->mensagemRN12Curta()."\n\n";
                    echo "</td>";
                    echo"</tr>";
                }
                //FIM RN12

                // CASO EXISTA RN22
                if($tIngressoRN22) {
                    echo"<tr>";
                    echo"<td class='td_fundoRN'>RN22</td>";
                    echo"<td class='td_textoRN'>";
                    echo $rn->mensagemRN22();
                    $listaRN.=$rn->mensagemRN22Curta()."\n\n";
                    echo "</td>";
                    echo"</tr>";
                }
                //FIM RN12

                //ENCERRA TABELA RN
                echo"</table>";


                //INSERE O TEXTAREA PARA DEFERIMENTO COM JUSTIFICATIVA
                if($acaoSolicitacao=='Deferir') {

                    echo"<table class='table_RN' border=0 width=100%>";
                    echo "<br>";
                    echo"<caption class='RN_cabecalho'>";
                    $alerta = htmlspecialchars("Caso prossiga com o deferimento,".
                            "justifique aqui a(s) violação(ões) da(s) seguinte(s) norma(s) regimentar(es)", ENT_QUOTES, "iso-8859-1");
                    echo $alerta;
                    echo"</caption>";

                    echo"<tr><td align='center'>";
                    echo "<textarea  class='RN_Justificativa' name='rnJustificativa'>";
                    echo"</textarea>";
                    echo"</tr></td>";
                }

                if($acaoSolicitacao=='Deferir') {
                    $existeJustificativa=true;
                }
            }

            //INSERE O TEXTAREA PARA INDEFERIMENTO COM JUSTIFICATIVA
            if($acaoSolicitacao=='Indeferir') {

                echo"<table class='table_RN' border=0 width=100%>";
                echo "<br>";
                echo"<caption class='RN_cabecalho'>";
                $alerta = htmlspecialchars("Caso prossiga com o indeferimento,".
                        "escreva a seguir a justificativa", ENT_QUOTES, "iso-8859-1");
                echo $alerta;
                echo"</caption>";

                echo"<tr><td align='center'>";
                echo "<textarea  class='RN_Justificativa' name='rnJustificativa'>";
                echo $listaRN;
                echo"</textarea>";
                echo"</tr></td>";
            }

            if($acaoSolicitacao=='Indeferir') {
                $existeJustificativa=true;
            }



            //TABELA BOTOES
            echo"<table border='0' align='center'>";
            echo"<tr>";
            echo"<td><input class='confirmar' name='ConfirmarInscricao' type='button'".
                    "onClick='finalizar(".$existeJustificativa.");' value='Confirmar' /></td>";
            //  echo"<td><input class='cancelar' name='cancelarInscricao' type='button' value='Cancelar' onclick='javascript:history.back();  '/></td>";
            echo"<td><input class='cancelar' name='cancelarInscricao' type='button'".
                    " value='Cancelar' onclick='cancelar();'/></td>";
            echo"</tr>";
            echo"</table>";






        }

        ?>

    </table>
</form>