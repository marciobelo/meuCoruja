<?php
    $arrayOrdenacao =  array();
    foreach ($inscricoes as $inscricao){

        $status;
        $listaCombo;
        $siglaDisciplina=$turmaSelecionada->getSiglaDisciplina();

        if($inscricao->getSituacaoInscricao() == "REQ") {
            $status="Não Avaliada";
            $listaCombo=array("Selecione","Deferir","Indeferir");
        } elseif($inscricao->getSituacaoInscricao() == "NEG") {
            $status="Indeferida";
            $listaCombo=array("Selecione","Deferir","Cancelar");
        } elseif($inscricao->getSituacaoInscricao() == "DEF") {
            $status="Deferida";
            $listaCombo=array("Selecione","Indeferir","Cancelar");
        }

        //OBTEM A MATRICULA DO ALUNO E O OBJETO ALUNO DO MESMO
        $matriculaAluno=MatriculaAluno::obterMatriculaAluno($inscricao->getMatriculaAluno());

        $perAluno=PeriodoLetivo::obterPeriodoLetivo($matriculaAluno->getIdPeriodoLetivo());


        $arrayOrdenacao[$matriculaAluno->getMatriculaAluno()]= $matriculaAluno->calcularCR()." - ".$perAluno->getSiglaPeriodoLetivo();

    }
    arsort($arrayOrdenacao); // ORDENA POR  CR

     //IMPRIME AS SOLICITAÇÕES
     foreach ($arrayOrdenacao as $key => $value) {

         //OBTEM A MATRICULA DO ALUNO E O OBJETO ALUNO DO MESMO
         $matriculaAluno=MatriculaAluno::obterMatriculaAluno($key);
              
         $aluno = Aluno::getAlunoByIdPessoa($matriculaAluno->getIdPessoa());
         $matr=$matriculaAluno->getMatriculaAluno();

         //OBTEM O HISTORICO DO ALUNO NA DISCIPLINA
         $historicoDisc = Inscricao::obterHistoricoDisciplina($siglaDisciplina, $matriculaAluno->getMatriculaAluno());

         //INSERINDO O ALUNO NA TABELA
        if($contaLinhaParaZebrar++ % 2 ==0)
        {
            $classLinha="tr_turma";
        }
        else
        {
            $classLinha="tr_turmaSemCor";
        }
        echo  "<tr align='center' class='$classLinha'>";
        echo  "<td>".$matriculaAluno->getMatriculaAluno()."</td>"; //MATRICULA
        echo  "<td>".$matriculaAluno->getSituacaoMatricula()."</td>"; //Situação Atual da Matrícula
        echo  "<td>".$aluno->getNome()."</td>"; // NOME
        echo  "<td>".$matriculaAluno->getTurnoIngresso()."</td>"; //Turno de Ingresso
        echo  "<td>".number_format($matriculaAluno->calcularCR(),1,',','.')."</td>"; //Turno de Ingresso
        echo  "<td class = 'td_critico'>";//Histórico do Aluno na Disciplina
        echo "<table align='center'>";
        for($j=0; $j<count($historicoDisc);$j++){
          echo"<tr><td>";
          $idTurmaHist = $historicoDisc[$j]->getIdTurma(); //OBTEM O ID DA TURMA
          $turmaHist = Turma::getTurmaById($idTurmaHist); // CRIA UM OBJETO DE TURMA
          $periodoHistorico = Periodoletivo::obterPeriodoLetivo($turmaHist->getIdPeriodoLetivo()); // OBTEM O PERIODO LETIVO
          echo $periodoHistorico->getSiglaPeriodoLetivo()." - ";
          echo $historicoDisc[$j]->getSituacaoInscricao()."; ";
          echo"</td></tr>";
        }
        echo "</table>";
        echo "</td>"; //Histórico do Aluno na Disciplina
        echo  "<td class = 'td_critico'>".$status."</td>"; // Status da Solicitação

         if($mostrarAcao){
         echo  "<td><select name='comboAcao' onChange =\"javascript:enviar('".$matr."',".$turmaSelecionada->getIdTurma().", value);\" >";
             for($i=0;$i<count($listaCombo);$i++)
             {
                echo "<option value ='".$listaCombo[$i]."'>".
                        $listaCombo[$i].
                        "</option>";
             }
             echo  "</select></td>"; // ACAO
         }
         
         echo  "</tr>";

     }