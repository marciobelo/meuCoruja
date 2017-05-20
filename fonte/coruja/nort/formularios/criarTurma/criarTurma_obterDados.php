<?php
require_once "$BASE_DIR/includes/topo.php";
require_once "$BASE_DIR/includes/menu_horizontal.php";

?>

<script type="text/javascript">
    function obtemCapacidade(idEspaco){
        <?php foreach ($listaDeEspacos as $espaco) { ?>
        if(idEspaco == '<?php echo $espaco->getIdEspaco()?>'){
            return <?php echo $espaco->getCapacidade() ?> ;
        }
        <?php } ?>
        alert("Não foi possivel identificar a capacidade do espaço ID#"+idEspaco)
        return -1;
    }

    function prosseguir(){
        if (!validaCampos()){
            //Os campos não foram completamentes preenchidos, abortar.
            return;
        }
        if (!validarVagasSalaMaxTurma()){
            //Foi escolhido um espaço (sala) que não suporta a qnt maxima de alunos para a turma
            return;
        }
        if (!validarGrade()){
            //Nao selecionou a quantidade correta de tempos, retornar.
            return;
        }
        resultado = criarTurma();        
    }

    function validaCampos(){
        //Disciplina
        if(document.formCriarTurma.siglaDisciplina.value == ""){
            alert("Selecione a disciplina");
            return false;
        }
        //Turno
        if(document.formCriarTurma.turno.value == ""){
            alert("Selecione o turno");
            return false;
        }
        //Grade de horario
        if(document.formCriarTurma.gradeHorario.value == ""){
            alert("Selecione a grade de horário");
            return false;
        }
        //Quantidade total
        if (document.formCriarTurma.qtdeTotal.value == ""
            | parseInt(document.formCriarTurma.qtdeTotal.value) == 0){
            alert("Informe a quantidade de vagas");
            return false;
        }
        return true
    }

    function validarVagasSalaMaxTurma(){
        //Flag
        encontrouErro = false;

        //Obtem a quantidade de vagas preechida pelo usuário
        qntVagas = document.formCriarTurma.qtdeTotal.value;

        //Percorre as alocalçoes escolhidas em busca de erros
        tabelaGradeHorario = document.getElementById("tabelaGradeDeHorario");
        temposSemanais = tabelaGradeHorario.getElementsByTagName('SELECT');
        for (i = 0; i < temposSemanais.length; i++) {
            if (temposSemanais[i].value != ""){
                idEspaco = temposSemanais[i].value;
                //Obtem a capacidade do espaço
                capacidade = obtemCapacidade(idEspaco);
                if(qntVagas > capacidade){
                    encontrouErro = true;
                    //temposSemanais[i].value = "";
                    temposSemanais[i].selectedIndex = 0;
                }
            }
        }

        if(encontrouErro){
            pintarCelulaCapacidadeInsuficiente ();
            alert("Foram selecionadas salas em que a capacidade é menor que a quantidade de vagas digitada");
            return false;
        }
        return true;
    }

    function validarGrade(){
        creditos = parseInt(document.formCriarTurma.auxCreditos.value);
        temposSelecionados = obterQuantidadeDeAlocacoes();

        if (creditos > temposSelecionados){
            alert("Quantidade de tempos insuficiente");
            return false;
        }
        if (creditos < temposSelecionados){
            alert("Quantidade de tempos além do necessário");
            return false;
        }
        return true;
    }

    function obterQuantidadeDeAlocacoes(){
        tabelaGradeHorario = document.getElementById("tabelaGradeDeHorario");
        temposSemanais = tabelaGradeHorario.getElementsByTagName('SELECT');

        qtdeAlocacoes = 0;
        for (i = 0; i < temposSemanais.length; i++) {
            if (temposSemanais[i].value != ""){
                qtdeAlocacoes++;
            }
        }
        return qtdeAlocacoes;
    }

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

    function atualizarComponentesCurriculares(){
        //Apaga a grade de horario
        document.getElementById("divGradeHorarioAjax").innerHTML="";

        xmlhttp = GetXMLHttp();
        
        xmlhttp.onreadystatechange=function()
          {
          if (xmlhttp.readyState==4 && xmlhttp.status==200)
            {
            document.getElementById("selDisciplinaAjax").innerHTML=xmlhttp.responseText;
            }
          }
        xmlhttp.open("POST","<?php echo $_SERVER['PHP_SELF'] ?>?acao=listaComponentesCurricularesAJAX",true);
        xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        sc = document.formCriarTurma.siglaCurso.value;
        im = document.formCriarTurma.idMatriz.value;
        xmlhttp.send("siglaCurso="+sc+"&idMatriz="+im);
    }

    function exibirGradeDeHorario(){
        document.getElementById("divGradeHorarioAjax").innerHTML="";

        //disciplina
        if(document.formCriarTurma.siglaDisciplina.value == ""){
            document.getElementById("divGradeHorarioAjax").innerHTML="";
            return;
        }
        //Turno
        if(document.formCriarTurma.turno.value == ""){
            document.getElementById("divGradeHorarioAjax").innerHTML="";
            return;
        }
        //Grade de horario
        if(document.formCriarTurma.gradeHorario.value == ""){
            document.getElementById("divGradeHorarioAjax").innerHTML="";
            return;
        }

        xmlhttp = GetXMLHttp();
        //xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function()
        {
            if (xmlhttp.readyState==4 && xmlhttp.status==200)
            {
                document.getElementById("divGradeHorarioAjax").innerHTML=xmlhttp.responseText;
            }
        }
        xmlhttp.open("POST","<?php echo $_SERVER['PHP_SELF'] ?>?acao=gradeDeHorarioAJAX",true);
        xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        sc = document.formCriarTurma.siglaCurso.value;
        im = document.formCriarTurma.idMatriz.value;
        sd = document.formCriarTurma.siglaDisciplina.value;
        ipl = document.formCriarTurma.idPeriodoLetivo.value;
        turno = document.formCriarTurma.turno.value;
        gh = document.formCriarTurma.gradeHorario.value;
        xmlhttp.send("siglaCurso="+sc+"&idMatriz="+im+"&siglaDisciplina="+sd+"&idPeriodoLetivo="+ipl+"&turno="+turno+"&gradeHorario="+gh);
        
    }

    var fundoOriginal = null;

    function pintarCelula (celula) {
        tableDiv = document.getElementById('celula-'+celula);
        if (fundoOriginal == null){
            fundoOriginal = tableDiv.style.background;
        }
        tableDiv.style.background = "#40E080"; //VERDE
        comboBox = document.getElementById('tempoSemanal-'+celula);
        comboBox.style.background = "#40E080"; //VERDE
        if (comboBox.value == "") {
            //Apaga em caso do usuário retirar a selecao
            tableDiv.style.background = fundoOriginal;
            comboBox.style.background = "#FFFFFF";
        }
    }

    function pintarCelulaErro (celula) {
        tableDiv = document.getElementById('celula-'+celula);
        if (fundoOriginal == null){
            fundoOriginal = tableDiv.style.background;
        }
        tableDiv.style.background = "#777777"; //CINZA
        comboBox = document.getElementById('tempoSemanal-'+celula);
        comboBox.style.background = "#FFFFFF"; //BRANCO
    }

    function pintarCelulaCapacidadeInsuficiente (){
        if (fundoOriginal == null){
            fundoOriginal = tableDiv.style.background;
        }
        tabelaGradeHorario = document.getElementById("tabelaGradeDeHorario");
        listaDeDivisoes = tabelaGradeHorario.getElementsByTagName('TD');        
        for (i = 0; i < listaDeDivisoes.length; i++) {
            listaSelect = listaDeDivisoes[i].getElementsByTagName('SELECT');
            if (listaSelect.length > 0){
                select = listaSelect[0];
                if (select.selectedIndex == 0 && listaDeDivisoes[i].style.background != fundoOriginal){
                    listaDeDivisoes[i].style.background = "#777777"; //CINZA
                    select.style.background = "#FFFFFF"; //BRANCO
                }
            }
        }
    }
    
    function criarTurma(){
        //Tenta inseir o registro da turma e suas alocações.

        // 'OK' - Turma criada com sucesso
        // 'CONFLITO' - Ocorreu conflito de horario durante a criação da turma
        // outros - Ocorreu um erro desconhecido durante a tentativa de criar a turma

        /* Atravez de POST, deve ser enviado:
         *
         * 1 - INFORMAÇÔES DA TURMA
         * siglaCurso
         * idMatriz
         * siglaDisciplina
         * idPeriodoLetivo
         * turno
         * gradeHorario(A / B / C)
         * matriculaProfessor (string vazia ou string com a matricula)
         * qtdeTotal
         * 
         * 2 - INFORMAÇÕES SOBRE AS ALOCACOES
         * idTempoSemanal -> idEspaco ( O controlador recebera cada alocacao na forma $_POST["tempoSemanal-$idTempoSemanal"] -> "idEspaco" )
         *
         */

        xmlhttp = GetXMLHttp();

        xmlhttp.onreadystatechange=function()
        {
            if (xmlhttp.readyState==4 && xmlhttp.status==200)
            {
                resposta = xmlhttp.responseText;
                if(resposta == "OK") {
                    alert ("Turma criada com sucesso");
                    document.formCriarNovaTurma.submit();
                } else if (resposta == "CONFLITO"){
                    alert ("a resposta recebida foi CONFLITO");
                    /* Ocorreu conflito de horario nas alocações,
                     * a grade de horario deve ser recarregada com dados atualizados
                     * e os espaços não conflitantes anteriormente marcados devem ser selecionados
                     *
                     *
                     * Como esta operação será realizada:
                     *      1 - Todos as alocações escolidas serão salvas em uma variavel (conflitantes ou não)
                     *      2 - A grade sera novamente desenhada, para que
                     *      3 - apresente as novas turmas que foram criadas
                     *      naquela grade de horario (conflito de tempo)
                     *      ou elimine os espaços que não estao mais disponiveis para alocação (conflito de espaço)
                     *      4 - Utilizando as alocações que foram anteriormente selecionadas,
                     *      a grade de horario será percorrida, e preenchida (caso possível)
                     */
                } else if (resposta == "JA_EXISTE_TURMA_NAO_CANCELADA_NA_GRADE") {
                    alert ("Erro ao criar Turma: já existe turma não cancelada na grade.");
                } else if (resposta.substring(0,43) == "Conflito de alocação de professor no tempo:"){
                    alert ("O professor selecionado já esta alocado em uma outra turma no tempo que foi marcado em cinza");
                    pintarCelulaErro (parseInt(resposta.substring(43)));
                } else {
                    alert ("Erro desconhecido: " + resposta);
                }
            }
        }

        xmlhttp.open("POST","<?php echo $_SERVER['PHP_SELF'] ?>?acao=inserirRegistroAJAX",true);
        xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        sc = document.formCriarTurma.siglaCurso.value;
        im = document.formCriarTurma.idMatriz.value;
        sd = document.formCriarTurma.siglaDisciplina.value;
        ipl = document.formCriarTurma.idPeriodoLetivo.value;
        turno = document.formCriarTurma.turno.value;
        gh = document.formCriarTurma.gradeHorario.value;
        mp = document.formCriarTurma.matriculaProfessor.value;
        qt = document.formCriarTurma.qtdeTotal.value;

        conteudoPost = "";

        conteudoPost+="siglaCurso="+sc+
            "&idMatriz="+im+
            "&siglaDisciplina="+sd+
            "&idPeriodoLetivo="+ipl+
            "&turno="+turno+
            "&gradeHorario="+gh+
            "&matriculaProfessor="+mp+
            "&qtdeTotal="+qt;

        //Percorre a grade de horario para enviar as alocações via POST
        tabelaGradeHorario = document.getElementById("tabelaGradeDeHorario");
        listaDeDivisoes = tabelaGradeHorario.getElementsByTagName('TD');
        for (i = 0; i < listaDeDivisoes.length; i++) {
            listaSelect = listaDeDivisoes[i].getElementsByTagName('SELECT');
            if (listaSelect.length > 0){ //Apenas executa caso a TD tenha um SELECT dentro
                select = listaSelect[0];
                if (select.selectedIndex != 0){ //Apenas executa caso tenha um esp
                    conteudoPost+= "&" + select.name + "=" + select.value
                }
            }
        }
        xmlhttp.send(conteudoPost);
    }
</script>

<form id="cadastro" name="formCriarTurma" method="POST" onsubmit="return false;">
    <fieldset>

        <legend>Criar Turma</legend>

        <table>
            <tr><td>
                <label>Curso:</label></td>
                <td><?php echo $curso->getSiglaCurso() . " - " . $curso->getNomeCurso(); ?>
                <input type="hidden" name="siglaCurso" value="<?php echo $curso->getSiglaCurso(); ?>">
            </td></tr>
            <tr><td>
                <label>Período Letivo:</label></td>
                <td><?php echo $periodoLetivo->getSiglaPeriodoLetivo(); ?>
                <input type="hidden" name="idPeriodoLetivo" value="<?php echo $periodoLetivo->getIdPeriodoLetivo(); ?>">
            </td></tr>
            <tr><td>
                <label>Matriz:</label></td>
                <td><select name="idMatriz" <?php echo $estaDesabilitado ?> onchange="atualizarComponentesCurriculares();" >
                    <?php
                    foreach ($listaDeMatrizCurricular as $mc) {

                        if ($mc->getIdMatriz() == $matrizCurricularAtual->getIdMatriz()){
                            $selected = 'selected="selected"';
                        } else {
                            $selected = '';
                        }
                        echo '<option ' . $selected . ' value="' . $mc->getIdMatriz() . '" >' . Util::dataSQLParaBr($mc->getDataInicioVigencia()) . '</option>';
                    }
                    ?>
                </select>
            </td></tr>
            <tr><td>
                <label>Disciplina:</label></td>
                <td><select name="siglaDisciplina" id="selDisciplinaAjax" onchange="exibirGradeDeHorario();" >
                    <option></option>
                    <?php
                    foreach ($listaDeDisciplinas as $disciplina) {
                        $selected = $disciplina->getSiglaDisciplina() == $_POST['siglaDisciplina'] ? 'selected' : '';
                        echo '<option ' . $selected . ' value="' . $disciplina->getSiglaDisciplina() . '" >' . $disciplina->getSiglaDisciplina() . ' - ' . $disciplina->getNomeDisciplina() . '</option>';
                    }
                    ?>
                </select>
            </td></tr>
            <tr><td>
                    <label>Turno:</label></td>
                <td><select name="turno" onchange="exibirGradeDeHorario();" >
                    <option></option>
                    <?php
                    foreach ($listaDeTurnos as $turno) {
                        $selected = $turno == $_POST['turno'] ? 'selected' : '';
                        echo '<option ' . $selected . ' value="' . $turno . '" >' . $turno . '</option>';
                    }
                    ?>
                </select>
            </td></tr>
            <tr><td>
                <label>Grade de Hor&aacute;rio:</label></td>
                <td><select name="gradeHorario" onchange="exibirGradeDeHorario();" >
                    <option></option>
                    <?php
                    foreach ($listaDeGrades as $grade) {
                        $selected = $grade == $_POST['grade'] ? 'selected' : '';
                        echo "<option $selected value=\"$grade\" >$grade</option>";
                    }
                    ?>
                </select>
            </td></tr>
            <tr><td>
                <label>Professor:</label></td>
                <td>
                <select name="matriculaProfessor" <?php echo $estaDesabilitado ?> >
                    <option value="">sem professor alocado</option>
                    <?php
                    foreach ($listaDeMatProfessores as $mp) {
                        $selected = $mp->getMatriculaProfessor() == $_POST['matriculaProfessor'] ? 'selected' : '';
                        echo '<option ' . $selected . ' value="' . $mp->getMatriculaProfessor() . '" >' . $mp->getNome() . ' (' . $mp->getCargaHoraria() . 'h ' . $mp->getMatriculaProfessor() . ')</option>';
                    }
                    ?>
                </select>
            </td></tr>
            <tr>
                <td><label>Qtde. Vagas:</label></td>
                <td align="left"><input name="qtdeTotal" type="text" onkeyup="javascript:this.value=this.value.replace(/[^0-9]/g, '');" style="width: 30px" maxlength="2"></td>
            </tr>
        </table>
        
        <div id="divGradeHorarioAjax" ></div>
        
        <table style="width: 90%;" align="center">
            <tr>
                <td><input type="button" value="Criar" name="Prosseguir" onclick="prosseguir();" style="width: 100px"></td>
                <td align="right"><input type="button" value="Voltar" onclick="document.formVoltar.submit();" style="width: 100px"></td>
            </tr>
        </table>
    </fieldset>
</form>
<form name="formVoltar" action="manterTurmas_controle.php?acao=exibirTurmas" method="POST">
    <input type="hidden" name="siglaCurso" value = "<?php echo $curso->getSiglaCurso(); ?>">
    <input type="hidden" name="idPeriodoLetivo" value = "<?php echo $periodoLetivo->getIdPeriodoLetivo(); ?>">
    <input type="hidden" name="turno" value = "<?php echo $_POST['voltar_turno']; ?>">
</form>
<form name="formCriarNovaTurma" action="criarTurma_controle.php" method="POST">
    <input type="hidden" name="siglaCurso" value = "<?php echo $curso->getSiglaCurso(); ?>">
    <input type="hidden" name="idPeriodo" value = "<?php echo $periodoLetivo->getIdPeriodoLetivo(); ?>">
    <input type="hidden" name="voltar_turno" value = "<?php echo $_POST['voltar_turno']; ?>">
</form>