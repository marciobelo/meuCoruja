<?php
//OBTEM OS TEMPOS
$todosTempos=TempoSemanal::lista_temposemanal("");
$contaTempo=count($todosTempos);
$linha=NULL;


//MONTAR GRADE DOS 5 DIAS
$grade= "<table class='table_lista' name='gradeManha' align='center' width=90%' id='grade'>";
$grade.="<caption align='top' class='cabecalho'>".
        "Grade Horária com as Disciplinas Solicitadas".
        "</caption> ";

$semana=   "<tr class='tr_cabecalho_grade'> ".
        "<td width=130px><div align='center'>Hor&aacute;rios das Aulas</div></td> ".
        "<td width=17%><div align='center'>Segunda</div></td> ".
        "<td width=17%><div align='center'>Ter&ccedil;a</div></td>".
        "<td width=17%><div align='center'>Quarta</div></td> ".
        "<td width=17%><div align='center'>Quinta</div></td> ".
        "<td width=17%><div align='center'>Sexta</div></td> ".
        "</tr> ";

$grade.=$semana;


$gradeSabado=NULL;
//MONTAR GRADE DO SABADO
$gradeSabado= "<table class='table_lista' name='gradeManha' align='rigth' width=30%' id='grade'>";


$sabado="<tr class='tr_cabecalho_grade'> ".
        "<td width=130px><div align='center'>Horários das Aulas</div></td> ".
        "<td width=40%><div align='center'>Sábado</div></td> ".
        "</tr> ";

$gradeSabado.=$sabado;

$temposSemanais = TempoSemanal::obterTempoSemanalOrdenado( $classeCurso);

//MONTA A GRADE
foreach($temposSemanais as $tempo) {

    //SEGUNDA
    //A SEGUNDA TEM UMA CARACTERISTICA ESPECIAL DE POR SER A PRIMEIRA, TER O INICIO DA LINHA
    if($tempo->getDiaSemana()=='SEG') {
        // A LINAH ABAIXO INICIA A LINAH NA TABLE
        $linha.="<tr class='td_turmaGrade'>";
        //A LINHA ABAIXO EH DOS HORARIOS DAS AULAS
        $linha.="<td class='td_horarioManha'>".$tempo->getHoraInicio()." - ".$tempo->getHoraFim()."</td>";
        
        echo"<input type='hidden' name='listaHorarioDasAulas[]' value='".$tempo->getHoraInicio()." - ".$tempo->getHoraFim()."'>";
        //AQUI SE INICIA A CARREGAR AS TURMAS
        //OBTEM AS TURMAS QUE REFEREM-SE AO TEMPO
        $turmasTempo = TempoSemanal::obterTurmasByTempoSemanal($matriculaAluno->getMatriculaAluno(),'REQ', $tempo->getIdTempoSemanal( ),$periodoLetivo->getIdPeriodoLetivo());
        $class=NULL; //CONTROLA O TIPO DE CSS QUE UTILIZAREMOS NA CELULA NA GRADE
        $rn08Grade=false; // CONTROLA SE ESTÁ SENDO VERIFICADO A RN08, POIS CASO ESTEJA O CSS EH DE OUTRA COR

        //LACO QUE VERIFICA SE OUVE COLISAO EM CASO DE VERIFICACAO DA RN08, NO MOMENTO DA CONFIRMACAO
        //A VARIAVEL $tempoColde EST DECLARADA NO CONTROLE
        foreach($tempoColide as $colisao) {
            if($tempo->getIdTempoSemanal()==$colisao) {
                $rn08Grade=true;
                $class="class = 'td_fundoRN'";
            }
        }

        //VERIFICA SE HA MAIS DE UMA TURMA NO TEMPO, PARA SELECIONAR O CSS, EXCETUANDO O CASO DE VERIFICACAO DA RN08
        if((count($turmasTempo)>1) && (!$rn08Grade)) {
            $class = "class = 'td_turmaConflitoGrade'";
            $total = count($turmasTempo);
        }

        $linha.="<td ".$class.">";

        //IMPRIME AS SIGLAS DAS DISCIPLINAS NA GRADE
        foreach ($turmasTempo as $turma) {
            $linha.=$turma->getSiglaDisciplina()."; ";
            //PARA O PROTOCOLO DA GRADE EM PDF
            $disc.=$turma->getSiglaDisciplina()."; ";
            if($class != "class = 'td_turmaConflitoGrade'"){//SIGNIFICA QUE NÃO TEM COLISÃO DE HORÁRIO
                echo"<input type='hidden' name='listaDisciplinaSegunda[]' value='".$turma->getSiglaDisciplina().";'>";
                $disc = "";
            }
            if($total==1 && $disc !=""){//SIGNIFICA QUE JÁ TEM TODAS AS DISCIPLINAS DO TEMPO SEMANAL
                echo"<input type='hidden' name='listaDisciplinaSegunda[]' value='".$disc."'>";
                $disc ="";
            }
            $total--;
            
        }
        if(count($turmasTempo)==0){
            echo"<input type='hidden' name='listaDisciplinaSegunda[]' value=''>";
        }

        $linha.="</td>";

    }
    
    //TERCA
    if($tempo->getDiaSemana()=='TER') {
        //OBTEM AS TURMAS QUE REFEREM-SE AO TEMPO
        $turmasTempo = TempoSemanal::obterTurmasByTempoSemanal($matriculaAluno->getMatriculaAluno(),'REQ', $tempo->getIdTempoSemanal( ),$periodoLetivo->getIdPeriodoLetivo());
        $class=NULL; //CONTROLA O TIPO DE CSS QUE UTILIZAREMOS NA CELULA NA GRADE
        $rn08Grade=false; // CONTROLA SE ESTÁ SENDO VERIFICADO A RN08, POIS CASO ESTEJA O CSS EH DE OUTRA COR

        //LACO QUE VERIFICA SE OUVE COLISAO EM CASO DE VERIFICACAO DA RN08, NO MOMENTO DA CONFIRMACAO
        //A VARIAVEL $tempoColde EST DECLARADA NO CONTROLE
        foreach($tempoColide as $colisao) {
            if($tempo->getIdTempoSemanal()==$colisao) {
                $rn08Grade=true;
                $class="class = 'td_fundoRN'";
            }
        }

        //VERIFICA SE HA MAIS DE UMA TURMA NO TEMPO, PARA SELECIONAR O CSS, EXCETUANDO O CASO DE VERIFICACAO DA RN08
        if((count($turmasTempo)>1) && (!$rn08Grade)) {
            $class = "class = 'td_turmaConflitoGrade'";
            $total = count($turmasTempo);
        }

        $linha.="<td ".$class.">";

        //IMPRIME AS SIGAS DAS DISCIPLINAS NA GRADE
        foreach ($turmasTempo as $turma) {
            $linha.=$turma->getSiglaDisciplina()."; ";

            //PARA O PROTOCOLO DA GRADE EM PDF
            $disc.=$turma->getSiglaDisciplina()."; ";
            if($class != "class = 'td_turmaConflitoGrade'"){
                echo"<input type='hidden' name='listaDisciplinaTerca[]' value='".$turma->getSiglaDisciplina().";'>";
                $disc = "";
            }
            if($total==1 && $disc !=""){
                echo"<input type='hidden' name='listaDisciplinaTerca[]' value='".$disc."'>";
                $disc ="";
            }
            $total--;

        }
        if(count($turmasTempo)==0){
            echo"<input type='hidden' name='listaDisciplinaTerca[]' value=''>";
        }
        $linha.="</td>";
    }

    //QUARTA
    if($tempo->getDiaSemana()=='QUA') {
        //OBTEM AS TURMAS QUE REFEREM-SE AO TEMPO
        $turmasTempo = TempoSemanal::obterTurmasByTempoSemanal($matriculaAluno->getMatriculaAluno(),'REQ', $tempo->getIdTempoSemanal( ),$periodoLetivo->getIdPeriodoLetivo());
        $class=NULL; //CONTROLA O TIPO DE CSS QUE UTILIZAREMOS NA CELULA NA GRADE
        $rn08Grade=false; // CONTROLA SE ESTÁ SENDO VERIFICADO A RN08, POIS CASO ESTEJA O CSS EH DE OUTRA COR

        //LACO QUE VERIFICA SE OUVE COLISAO EM CASO DE VERIFICACAO DA RN08, NO MOMENTO DA CONFIRMACAO
        //A VARIAVEL $tempoColde EST DECLARADA NO CONTROLE
        foreach($tempoColide as $colisao) {
            if($tempo->getIdTempoSemanal()==$colisao) {
                $rn08Grade=true;
                $class="class = 'td_fundoRN'";
            }
        }

        //VERIFICA SE HA MAIS DE UMA TURMA NO TEMPO, PARA SELECIONAR O CSS, EXCETUANDO O CASO DE VERIFICACAO DA RN08
        if((count($turmasTempo)>1) && (!$rn08Grade)) {
            $class = "class = 'td_turmaConflitoGrade'";
            $total = count($turmasTempo);
        }

        $linha.="<td ".$class.">";

        //IMPRIME AS SIGAS DAS DISCIPLINAS NA GRADE
        foreach ($turmasTempo as $turma) {
            $linha.=$turma->getSiglaDisciplina()."; ";
            //PARA O PROTOCOLO DA GRADE EM PDF
            $disc.=$turma->getSiglaDisciplina()."; ";
            if($class != "class = 'td_turmaConflitoGrade'"){
                echo"<input type='hidden' name='listaDisciplinaQuarta[]' value='".$turma->getSiglaDisciplina().";'>";
                $disc = "";
            }
            if($total==1 && $disc !=""){
                echo"<input type='hidden' name='listaDisciplinaQuarta[]' value='".$disc."'>";
                $disc ="";
            }
            $total--;
        }
        if(count($turmasTempo)==0){
            echo"<input type='hidden' name='listaDisciplinaQuarta[]' value=''>";
        }
        $linha.="</td>";
    }

    //QUINTA
    if($tempo->getDiaSemana()=='QUI') {

        //OBTEM AS TURMAS QUE REFEREM-SE AO TEMPO
        $turmasTempo = TempoSemanal::obterTurmasByTempoSemanal($matriculaAluno->getMatriculaAluno(),'REQ', $tempo->getIdTempoSemanal( ),$periodoLetivo->getIdPeriodoLetivo());
        $class=NULL; //CONTROLA O TIPO DE CSS QUE UTILIZAREMOS NA CELULA NA GRADE
        $rn08Grade=false; // CONTROLA SE ESTÁ SENDO VERIFICADO A RN08, POIS CASO ESTEJA O CSS EH DE OUTRA COR

        //LACO QUE VERIFICA SE OUVE COLISAO EM CASO DE VERIFICACAO DA RN08, NO MOMENTO DA CONFIRMACAO
        //A VARIAVEL $tempoColde EST DECLARADA NO CONTROLE
        foreach($tempoColide as $colisao) {
            if($tempo->getIdTempoSemanal()==$colisao) {
                $rn08Grade=true;
                $class="class = 'td_fundoRN'";
            }
        }

        //VERIFICA SE HA MAIS DE UMA TURMA NO TEMPO, PARA SELECIONAR O CSS, EXCETUANDO O CASO DE VERIFICACAO DA RN08
        if((count($turmasTempo)>1) && (!$rn08Grade)) {
            $class = "class = 'td_turmaConflitoGrade'";
            $total = count($turmasTempo);
        }

        $linha.="<td ".$class.">";

        //IMPRIME AS SIGLAS DAS DISCIPLINAS NA GRADE
        foreach ($turmasTempo as $turma) {
            $linha.=$turma->getSiglaDisciplina()."; ";
            $disc.=$turma->getSiglaDisciplina()."; ";
            if($class != "class = 'td_turmaConflitoGrade'"){
                echo"<input type='hidden' name='listaDisciplinaQuinta[]' value='".$turma->getSiglaDisciplina().";'>";
                $disc = "";
            }
            
            if($total==1 && $disc !=""){
                echo"<input type='hidden' name='listaDisciplinaQuinta[]' value='".$disc."'>";
                $disc ="";
            }
            $total--;
        }
        if(count($turmasTempo)==0){
            echo"<input type='hidden' name='listaDisciplinaQuinta[]' value=''>";
        }
        $linha.="</td>";
    }

    //SEXTA
    //POR SER A ULTIMA DA SEMANA DE 5 DIAS, TEM O FINAL DA LINHA
    if($tempo->getDiaSemana()=='SEX') {
        //OBTEM AS TURMAS QUE REFEREM-SE AO TEMPO
        $turmasTempo = TempoSemanal::obterTurmasByTempoSemanal($matriculaAluno->getMatriculaAluno(),'REQ', $tempo->getIdTempoSemanal( ),$periodoLetivo->getIdPeriodoLetivo());
        $class=NULL; //CONTROLA O TIPO DE CSS QUE UTILIZAREMOS NA CELULA NA GRADE
        $rn08Grade=false; // CONTROLA SE ESTÁ SENDO VERIFICADO A RN08, POIS CASO ESTEJA O CSS EH DE OUTRA COR

        //LACO QUE VERIFICA SE OUVE COLISAO EM CASO DE VERIFICACAO DA RN08, NO MOMENTO DA CONFIRMACAO
        //A VARIAVEL $tempoColde EST DECLARADA NO CONTROLE
        foreach($tempoColide as $colisao) {
            if($tempo->getIdTempoSemanal()==$colisao) {
                $rn08Grade=true;
                $class="class = 'td_fundoRN'";
            }
        }

        //VERIFICA SE HA MAIS DE UMA TURMA NO TEMPO, PARA SELECIONAR O CSS, EXCETUANDO O CASO DE VERIFICACAO DA RN08
        if((count($turmasTempo)>1) && (!$rn08Grade)) {
            $class = "class = 'td_turmaConflitoGrade'";
            $total = count($turmasTempo);
        }

        $linha.="<td ".$class.">";

        //IMPRIME AS SIGAS DAS DISCIPLINAS NA GRADE
        foreach ($turmasTempo as $turma) {
            $linha.=$turma->getSiglaDisciplina()."; ";
            //PARA O PROTOCOLO DA GRADE EM PDF
            $disc.=$turma->getSiglaDisciplina()."; ";
            if($class != "class = 'td_turmaConflitoGrade'"){
                echo"<input type='hidden' name='listaDisciplinaSexta[]' value='".$turma->getSiglaDisciplina()."'>";
                $disc = "";
            }
            
            if($total==1 && $disc !=""){
                echo"<input type='hidden' name='listaDisciplinaSexta[]' value='".$disc."'>";
                $disc ="";
            }
            $total--;
            
        }
        if(count($turmasTempo)==0){
            echo"<input type='hidden' name='listaDisciplinaSexta[]' value=''>";
        }

        $linha.="</td>";
        //A LINHA ABAIXO ENCERRA A LINHA DA TABLE
        $linha.="</tr>";
    }

    if($tempo->getDiaSemana()=='SAB') {
         // A LINAH ABAIXO INICIA A LINAH NA TABLE
        $gradeSabado.="<tr class='td_turmaGrade'>";

        //A LINHA ABAIXO EH DOS HORARIOS DAS AULAS
        $gradeSabado.="<td class='td_horarioManha'>".$tempo->getHoraInicio()." - ".$tempo->getHoraFim()."</td>";
        
        echo"<input type='hidden' name='listaHorarioDasAulasSabado[]' value='".$tempo->getHoraInicio()." - ".$tempo->getHoraFim()."'>";

        //OBTEM AS TURMAS QUE REFEREM-SE AO TEMPO
        $turmasTempo = TempoSemanal::obterTurmasByTempoSemanal($matriculaAluno->getMatriculaAluno(),'REQ', $tempo->getIdTempoSemanal( ),$periodoLetivo->getIdPeriodoLetivo());

        $class=NULL; //CONTROLA O TIPO DE CSS QUE UTILIZAREMOS NA CELULA NA GRADE

        $rn08Grade=false; // CONTROLA SE ESTÁ SENDO VERIFICADO A RN08, POIS CASO ESTEJA O CSS EH DE OUTRA COR

        //LACO QUE VERIFICA SE OUVE COLISAO EM CASO DE VERIFICACAO DA RN08, NO MOMENTO DA CONFIRMACAO
        //A VARIAVEL $tempoColde EST DECLARADA NO CONTROLE
        foreach($tempoColide as $colisao) {
            if($tempo->getIdTempoSemanal()==$colisao) {
                $rn08Grade=true;
                $class="class = 'td_fundoRN'";
            }
        }

        //VERIFICA SE HA MAIS DE UMA TURMA NO TEMPO, PARA SELECIONAR O CSS, EXCETUANDO O CASO DE VERIFICACAO DA RN08
        if((count($turmasTempo)>1) && (!$rn08Grade)) {
            $class = "class = 'td_turmaConflitoGrade'";
            $total = count($turmasTempo);
        }

        $gradeSabado.="<td ".$class.">";

        //IMPRIME AS SIGAS DAS DISCIPLINAS NA GRADE
        foreach ($turmasTempo as $turma) {
            $gradeSabado.=$turma->getSiglaDisciplina()."; ";
            //PARA O PROTOCOLO DA GRADE EM PDF
            $disc.=$turma->getSiglaDisciplina()."; ";
            if($class != "class = 'td_turmaConflitoGrade'"){
                echo"<input type='hidden' name='listaDisciplinaSabado[]' value='".$turma->getSiglaDisciplina().";'>";
                $disc = "";
            }

            if($total==1 && $disc !=""){
                echo"<input type='hidden' name='listaDisciplinaSabado[]' value='".$disc."'>";
                $disc ="";
            }
            $total--;

        }
        if(count($turmasTempo)==0){
            echo"<input type='hidden' name='listaDisciplinaSabado[]' value=''>";
        }
        $gradeSabado.="</td>";
        
        //A LINHA ABAIXO ENCERRA A LINHA DA TABLE
        $gradeSabado.="</tr>";

        
    }

}
$grade.=$linha;
$grade.= "</table>";
$gradeSabado.="</table>";
$grade.="<br>".$gradeSabado."<br>";
//IMPRIME A GRADE

echo $grade;


?>



