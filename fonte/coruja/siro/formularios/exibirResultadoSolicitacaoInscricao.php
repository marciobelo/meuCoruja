<script type="text/javascript" src="/coruja/siro/javascripts/carrega_div.js"></script>
<script>
    function imprimirSolicitacoes(){
         document.listaTurmas.target="_blank";
         document.listaTurmas.submit();
    }
    </script>

<form name="cadastro" id="cadastro">
    <fieldset id="fieldsetGeral">

        <b>Resultado de Solicitações de Inscrição para o Per&iacute;odo Letivo de
        <?php echo $perLetivo->getSiglaPeriodoLetivo()." (".$perLetivo->getDataInicio()." - ".$perLetivo->getDataFim().") <br />";
        echo"<b>Curso: ".$classeCurso->getSiglaCurso()." - ".$classeCurso->getNomeCurso();
        ?>
        </b><br />
    </fieldset>
</form>

<form name="listaTurmas" id="listaTurmas" action="/coruja/siro/classes/EmitirResultadoSolicitacaoInscricaoPDF.php" method="post">
    <fieldset id="fieldsetGeral">
<?php
        $cor = 1;

        // checa se há resultados
        if(empty($listaSolicitacoes))
        {
            echo "<b>Nenhuma solicitação encontrada com os par&acirc;metros fornecidos.</b>";
            echo "<br />";
        }
        else{

            echo "<table width=100%>";
            echo "<tr align='center'>".
            "<th>Matrícula</th>".
            "<th>Nome</th>".
            "<th>Turma <br>(Disciplina/Grade/Turno)</th>".
            "<th>Resultado e Parecer</th>".
            "</tr>";

            foreach($listaSolicitacoes as $itens)
            {
                if($cor==1){$corfundo='#00BFFF'; $cor=2;}
                elseif($cor==2){$corfundo=''; $cor=1;}

                $lista .= "<tr bgcolor='$corfundo'><td width='7%' align='center'>";
                $lista .= $itens->getMatriculaAluno();

                $lista .= "<input type='hidden' name='listaDeMatriculaAluno[]' value='".$itens->getMatriculaAluno()."'>";

                $lista .= "</td>";
                $lista .= "<td>";
                $lista .= strtoupper($itens->getNomeAluno());

                $lista .= "<input type='hidden' name='listaDeNomeAluno[]' value='".strtoupper($itens->getNomeAluno())."'>";

                $lista .="</td>";
                
                $solicitacoes = $classeInscricao->buscaResultadoSolicitacoes($siglaCurso,$idPeriodoLetivo,$itens->getMatriculaAluno());

                if(empty($solicitacoes))
                {

                    $lista .= "Não há resultado de solicitações<br /><br />";
                }
                else
                {
                    $contador = 1;
                    foreach($solicitacoes as $resultadoSolicitacoes)
                    {

                        $lista .= "<td align='center' width='15%'>";
                        
                        $lista .= $resultadoSolicitacoes->getSiglaDisciplina();
                        
                        $lista .= "<input type='hidden' name='listaDeDisciplinas[]' value='".$resultadoSolicitacoes->getSiglaDisciplina()."'>";

                        $lista .= " - ";

                        $lista .= $resultadoSolicitacoes->getGradeHorario();

                        $lista .= "<input type='hidden' name='listaDeGradeHorarios[]' value='".$resultadoSolicitacoes->getGradeHorario()."'>";

                        //buscar o turno referente a essa turma
                        $detalhesTurma = $classeTurma->getTurmaById($resultadoSolicitacoes->getIdTurma());

                        $turno = $detalhesTurma->getTurno();
                        $lista .= " - ";       
                        $lista .= $turno;

                        $lista .= "<input type='hidden' name='listaDeTurnos[]' value='".$turno."'>";

                        $lista .= "</td><td>";

                        switch($resultadoSolicitacoes->getSituacaoInscricao()){
                            case 'AP':
                                $situacao='APROVADO';
                                break;
                            case 'NEG':
                                $situacao='INDEFERIDO';
                                break;
                            case 'DEF':
                                $situacao='DEFERIDO';
                                break;
                            case 'REQ':
                                $situacao='REQUERIDO';
                                break;
                            case 'EXC':
                                $situacao='EXCLUÍDO';
                                break;
                            case 'CUR':
                                $situacao='CURSANDO';
                                break;
                            case 'RM':
                                $situacao='REPROVADO POR MÉDIA';
                                break;
                            case 'ID':
                                $situacao='ISENTO';
                                break;
                            case 'RF':
                                $situacao='REPROVADO POR FALTA';
                                break;
                        }
                        $lista .= $situacao;
                        $lista .= " - ";   
                        $lista .= "<input type='hidden' name='listaDeSituacoes[]' value='".$situacao."'>";

                        $lista .= $resultadoSolicitacoes->getParecerInscricao();

                        $lista .= "<input type='hidden' name='listaDeParecerInscricao[]' value='".$resultadoSolicitacoes->getParecerInscricao()."'>";

                        $lista .= "</td>";
                        $lista.="</tr>";

                        if($contador < count($solicitacoes)){ 
                            $lista .= "<tr bgcolor='$corfundo'>";
                            $lista .= "<td><input type='hidden' name='listaDeMatriculaAluno[]' value=''></td>";
                            $lista .= "<td><input type='hidden' name='listaDeNomeAluno[]' value=''></td>";
                        }
                        $contador++;
                    }

                }
                $lista.="</tr>";
             }
            $lista .= "</table><br />";

            $lista.= "<input type='hidden' id='siglaCurso' name='siglaCurso' value='$siglaCurso' />";
            $lista.= "<input type='hidden' id='idPeriodoLetivo' name='idPeriodoLetivo' value='$idPeriodoLetivo' />";
            $lista.= "<input type='hidden' id='periodoLetivo' name='periodoLetivo' value='".$perLetivo->getSiglaPeriodoLetivo()."' />";
            
            echo $lista;
        }

?>
    <br />
    <p align="center"><br />
         <input id="button1" type="button" onclick="imprimirSolicitacoes();" name="imprimirResultado" value="  Imprimir Solicitações " />
    </p>
    </fieldset>
</form>

<form name="voltar" id="voltar" action="ExibirResultadoSolicitacaoInscricao_controle.php?act=main" method="post">
    <input id='button1' type='submit' value='  Voltar  '  >
</form>
    