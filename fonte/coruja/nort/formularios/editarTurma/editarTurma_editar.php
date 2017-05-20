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
    function obtemCapacidade(idEspaco){
        <?php foreach ($listaDeEspacos as $espaco) { ?>
        if(idEspaco == '<?php echo $espaco->getIdEspaco()?>'){
            return <?php echo $espaco->getCapacidade() ?> ;
        }
        <?php } ?>
        alert("N�o foi possivel identificar a capacidade do espa�o ID#"+idEspaco)
        return -1;
    }
</script>

<script type="text/javascript">
    function prosseguir(){
        
        if (!validaCampos()){
            //Os campos n�o foram completamentes preenchidos, abortar.
            return;
        }
        if (!validarVagasSalaMaxTurma()){
            //Foi escolhido um espa�o (sala) que n�o suporta a qnt maxima de alunos para a turma
            return;
        }
        if (!validarGrade()){
            //Nao selecionou a quantidade correta de tempos, retornar.
            return;
        }
        //Validacao ok, inserir
        resultado = editarTurma();

    }

    function validaCampos(){
        /*
        //Disciplina
        if(document.formEditarTurma.siglaDisciplina.value == ""){
            alert("Selecione a disciplina");
            return false;
        }
        //Turno
        if(document.formEditarTurma.turno.value == ""){
            alert("Selecione o turno");
            return false;
        }
        //Grade de horario
        if(document.formEditarTurma.gradeHorario.value == ""){
            alert("Selecione a grade de hor�rio");
            return false;
        }
        */
        //Quantidade total
        if (document.formEditarTurma.qtdeTotal.value == ""
            | parseInt(document.formEditarTurma.qtdeTotal.value) == 0){
            alert("Informe a quantidade de vagas");
            return false;
        }
        return true
    }

    function validarVagasSalaMaxTurma(){
        //Flag
        encontrouErro = false;

        //Obtem a quantidade de vagas preechida pelo usu�rio
        qntVagas = document.formEditarTurma.qtdeTotal.value;

        //Percorre as alocal�oes escolhidas em busca de erros
        tabelaGradeHorario = document.getElementById("tabelaGradeDeHorario");
        temposSemanais = tabelaGradeHorario.getElementsByTagName('SELECT');
        for (i = 0; i < temposSemanais.length; i++) {
            if (temposSemanais[i].value != ""){
                idEspaco = temposSemanais[i].value;
                //Obtem a capacidade do espa�o
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
            alert("Foram selecionadas salas em que a capacidade � menor que a quantidade de vagas digitada");
            return false;
        }
        return true;
    }

    function validarGrade(){
        creditos = parseInt(document.formEditarTurma.auxCreditos.value);
        temposSelecionados = obterQuantidadeDeAlocacoes();
        if (creditos > temposSelecionados){
            alert("Quantidade de tempos insuficiente");
            return false;
        }
        if (creditos < temposSelecionados){
            alert("Quantidade de tempos al�m do necess�rio");
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
</script>

<script type="text/javascript">

    var fundoOriginal = "";

    function pintarCelula (celula){
        tableDiv = document.getElementById('celula-'+celula);
        tableDiv.style.background = "#40E080";
        /* nao consegui alterar a cor de acordo com o gradeDeHorario.css
         * sintam-se livre para alterar
         */
        comboBox = document.getElementById('tempoSemanal-'+celula);
        comboBox.style.background = "#40E080";

        if (comboBox.value == ""){
            //Apaga em caso do usu�rio retirar a selecao
            tableDiv.style.background = fundoOriginal;
            comboBox.style.background = "#FFFFFF";
        }
    }

    function pintarCelulaErro (celula){
        //celula.class="destacado";
        tableDiv = document.getElementById('celula-'+celula);
        if (fundoOriginal == null){
            fundoOriginal = tableDiv.style.background;
        }
        tableDiv.style.background = "#777777"; //CINZA
        /* nao consegui alterar a cor de acordo com o gradeDeHorario.css
         * sintam-se livre para alterar
         */
        comboBox = document.getElementById('tempoSemanal-'+celula);
        comboBox.style.background = "#FFFFFF"; //BRANCO
    }
    
    function pintarCelulaCapacidadeInsuficiente (){
        tabelaGradeHorario = document.getElementById("tabelaGradeDeHorario");
        listaDeDivisoes = tabelaGradeHorario.getElementsByTagName('TD');
        for (i = 0; i < listaDeDivisoes.length; i++) {
            listaSelect = listaDeDivisoes[i].getElementsByTagName('SELECT');
            if (listaSelect.length > 0){
                select = listaSelect[0];
                if (select.selectedIndex == 0 && listaDeDivisoes[i].style.background != fundoOriginal){
                    listaDeDivisoes[i].style.background = "#777777";
                    select.style.background = "#FFFFFF";
                }
            }
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
    function exibirGradeDeHorario(){
        document.getElementById("divGradeHorarioAjax").innerHTML="";

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
        it = document.formEditarTurma.auxIdTurma.value;
        xmlhttp.send("idTurma="+it);
    }
    
    function editarTurma(){
        //Tenta inseir o registro da turma e suas aloca��es.

        // 'OK' - Turma criada com sucesso
        // 'CONFLITO' - Ocorreu conflito de horario durante a cria��o da turma
        // outros - Ocorreu um erro desconhecido durante a tentativa de criar a turma

        /* Atravez de POST, deve ser enviado:
         *
         * 1 - INFORMA��ES DA TURMA
         * idTurma
         * matriculaProfessor (string vazia ou string com a matricula)
         * qtdeTotal
         *
         * 2 - INFORMA��ES SOBRE AS ALOCACOES
         * idTempoSemanal -> idEspaco ( O controlador recebera cada alocacao na forma $_POST["tempoSemanal-$idTempoSemanal"] -> "idEspaco" )
         *
         */

        xmlhttp = GetXMLHttp();
        //xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function()
        {
            if (xmlhttp.readyState==4 && xmlhttp.status==200)
            {
                resposta = xmlhttp.responseText;
                if(trim(resposta) == "OK") {
                    //Turma e aloca��es modificadas com sucesso
                    exibirGradeDeHorario();
                    alert ("Turma editada com sucesso");
                    document.formVoltar.submit();
                } else if (resposta == "CONFLITO"){
                    alert ("a resposta recebida foi CONFLITO");
                    /* Ocorreu conflito de horario nas aloca��es,
                     * a grade de horario deve ser recarregada com dados atualizados
                     * e os espa�os n�o conflitantes anteriormente marcados devem ser selecionados
                     *
                     *
                     * Como esta opera��o ser� realizada:
                     *      1 - Todos as aloca��es escolidas ser�o salvas em uma variavel (conflitantes ou n�o)
                     *      2 - A grade sera novamente desenhada, para que
                     *      3 - apresente as novas turmas que foram criadas
                     *      naquela grade de horario (conflito de tempo)
                     *      ou elimine os espa�os que n�o estao mais disponiveis para aloca��o (conflito de espa�o)
                     *      4 - Utilizando as aloca��es que foram anteriormente selecionadas,
                     *      a grade de horario ser� percorrida, e preenchida (caso poss�vel)
                     */
                } else if (resposta.substring(0,43) == "Conflito de aloca��o de professor no tempo:"){
                    alert ("O professor selecionado j� esta alocado em uma outra turma no tempo que foi marcado em cinza");
                    pintarCelulaErro (parseInt(resposta.substring(43)));
                } else if(resposta.indexOf("Conflito de aloca��o de espa�o")!=-1) {
                    alert ("O espa�o j� esta alocado em uma outra turma no tempo que foi marcado em cinza");
                    var patt=/[0-9]+/;
                    pintarCelulaErro(parseInt(resposta.match(patt)));
                } else {
                    alert ("Erro : "+resposta);
                }
            }
        }

        xmlhttp.open("POST","<?php echo $_SERVER['PHP_SELF'] ?>?acao=editarRegistroAJAX",true);
        xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        //sc = document.formEditarTurma.siglaCurso.value;
        //im = document.formEditarTurma.idMatriz.value;
        //sd = document.formEditarTurma.siglaDisciplina.value;
        //ipl = document.formEditarTurma.idPeriodoLetivo.value;
        //turno = document.formEditarTurma.turno.value;
        //gh = document.formEditarTurma.gradeHorario.value;
        it = document.formEditarTurma.auxIdTurma.value;
        mp = document.formEditarTurma.matriculaProfessor.value;
        qt = document.formEditarTurma.qtdeTotal.value;

        conteudoPost = "";

        conteudoPost+="idTurma="+it+
            "&matriculaProfessor="+mp+
            "&qtdeTotal="+qt;

        //Percorre a grade de horario para enviar as aloca��es via POST
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
        //alert("POST: "+conteudoPost);
        xmlhttp.send(conteudoPost);
    }
</script>

<link rel="stylesheet" type="text/css" href="<?php echo $RAIZ_CORUJA; ?>/nort/estilos/gradeDeHorario.css" />

<form id="cadastro" name="formEditarTurma" method="POST" action="nenhuma" >
    <fieldset>
        <legend>Alterar turma</legend>
        <input type="hidden" name="auxIdTurma" value="<?php echo $dadosDaTurma['idTurma'] ?>">
        <table>
            <tr>
                <td>
                    Curso:
                </td>
                <td>
                    <?php echo $dadosDaTurma['siglaCurso'] ?> - <?php echo $dadosDaTurma['nomeCurso'] ?>
                </td>
            </tr>

            <tr>
                <td>
                    Per�odo Letivo:
                </td>
                <td>
                    <?php echo $dadosDaTurma['siglaPeriodoLetivo'] ?>
                </td>
            </tr>

            <tr>
                <td>
                    Data da Matriz:
                </td>
                <td>
                    <?php echo Util::dataSQLParaBr($dadosDaTurma['dataInicioVigencia']) ?>
                </td>
            </tr>

            <tr>
                <td>
                    Disciplina:
                </td>
                <td>
                    <?php echo $dadosDaTurma['siglaDisciplina'] ?> - <?php echo $dadosDaTurma['nomeDisciplina'] ?>
                </td>
            </tr>

            <tr>
                <td>
                    Turno:
                </td>
                <td>
                    <?php echo $dadosDaTurma['turno'] ?>
                </td>
            </tr>

            <tr>
                <td>
                    Grade de hor�rio:
                </td>
                <td>
                    <?php echo $dadosDaTurma['gradeHorario'] ?>
                </td>
            </tr>

            <tr>
                <td>
                    Cr�ditos:
                </td>
                <td>
                    <?php echo $dadosDaTurma['creditos'] ?> <input type="hidden" name="auxCreditos" value="<?php echo $cc->getCreditos() ?>">
                </td>
            </tr>

            <tr>
                <td>
                    Carga Hor�ria:
                </td>
                <td>
                    <?php echo $dadosDaTurma['cargaHoraria'] ?> horas/aula
                </td>
            </tr>

            <tr>
                <td>
                    Periodo na matriz:
                </td>
                <td>
                    <?php echo $dadosDaTurma['periodo'] ?>� periodo
                </td>
            </tr>

            <tr>
                <td>
                    Tipo:
                </td>
                <td>
                    <?php echo $dadosDaTurma['tipoComponenteCurricular'] ?>
                </td>
            </tr>

            <tr>
                <td>
                    Professor:
                </td>
                <td>
                    <select name="matriculaProfessor">
                        <option value = "" >Sem Professor</option>

                        <?php
                                foreach ($listaDeMatProfessores as $matProf) {
                                    if ($dadosDaTurma['matriculaProfessor'] == $matProf->getMatriculaProfessor()){
                                        $selected = 'SELECTED = "SELECTED"';
                                    } else {
                                        $selected = '';
                                    }
                                    echo '<option value="'.$matProf->getMatriculaProfessor().'" '.$selected.' >';
                                    //echo $matProf->getNome().' - ('.$matProf->getMatriculaProfessor().')';
                                    echo $matProf->getNome() . ' (' . $matProf->getCargaHoraria() . 'h ' . $matProf->getMatriculaProfessor() . ')';
                                    echo '</option>';
                                }

                        ?>
                    </select>
                </td>
            </tr>

            <tr>
                <td>
                    Qtde de vagas:
                </td>
                <td>
                    <input name="qtdeTotal" type="text" value="<?php echo $dadosDaTurma['qtdeTotal'] ?>" onkeyup="javascript:this.value=this.value.replace(/[^0-9]/g, '');" style="width: 30px" maxlength="2">
                </td>
            </tr>

        </table>

        <div id="divGradeHorarioAjax" >
        <table id="tabelaGradeDeHorario" class="gradeDeHorario" align="center">
            <thead>
                <tr>
                    <td colspan="7"  align="center" id="topo-gradeHorario"><?php echo $cc->getPeriodo() ?>� Per�odo - Turno: <?php echo $dadosDaTurma['turno'] ?> - Grade: <?php echo $dadosDaTurma['gradeHorario'] ?></td>
                </tr>
                <tr>
                    <td width="40" align="center">---</td>
                    <td width="80" align="center">SEG</td>
                    <td width="80" align="center">TER</td>
                    <td width="80" align="center">QUA</td>
                    <td width="80" align="center">QUI</td>
                    <td width="80" align="center">SEX</td>
                    <td width="80" align="center">SAB</td>
                </tr>
            </thead>
            <tbody>
                <?php
                //INICIO DA GRADE DE HORARIO

                $tempos = array();
                for ($auxTempo = 1; $auxTempo <= count($matrizTempos['SEG']);$auxTempo++){
                    array_push($tempos, $auxTempo);
                }

                foreach ($tempos as $t) { //TEMPOS (LINHA)
                ?>
                <tr>
                    <td align="center"><?php echo $t; ?></td>
                    <?php
                    $dias = array('SEG','TER','QUA','QUI','SEX','SAB');

                    foreach ($dias as $d) { //DIAS (COLUNA)
                    if ($matrizTempos[$d][$t]['nome'] != null){
                        $texto = $matrizTempos[$d][$t]['siglaDisciplina']; //Sigla da disciplina
                        $texto = $texto.' ('.$matrizTempos[$d][$t]['nome'].')'; //Nome do expa�o (ex. Hibrida 1)
                    }
                    if($matrizTempos[$d][$t]['idTurma'] == $dadosDaTurma['idTurma']){
                        $colorir = 'style="background: #40E080"';
                    } else {
                        $colorir = '';
                    }
                    ?>
                    <td id='celula-<?php echo $matrizTempos[$d][$t]['idTempoSemanal']; ?>' align="center" <?php echo $colorir; ?> >
                        <?php
                        //print_r($matrizTempos[$d][$t]);
                        if ($matrizTempos[$d][$t]['siglaDisciplina'] && $matrizTempos[$d][$t]['idTurma'] <> $_POST['idTurma']){ //Possui turma alocada
                            echo $texto; //Escreve a Disciplina e a Sala naquele tempo de aula
                            //print_r($matrizTempos[$d][$t]);
                        } else { // Nao possui turma alocada
                            if ($matrizTempos[$d][$t]['DURACAO'] > "00:15:00" ){ //caso a duracao seja maior que 15 minutos
                            ?>    <select id='tempoSemanal-<?php echo $matrizTempos[$d][$t]['idTempoSemanal']; ?>' name="tempoSemanal-<?php echo $matrizTempos[$d][$t]['idTempoSemanal']; ?>" onchange="pintarCelula ('<?php echo $matrizTempos[$d][$t]['idTempoSemanal']; ?>');">
                            <option></option>
                            <?php
                                    foreach ($matrizTempos[$d][$t]['espacosLivres'] as $espaco){
                                        if ($espaco['flagSendoEditado'] == TRUE) {
                                            $selected = 'SELECTED = "SELECTED"';
                                        } else {
                                            $selected = '';
                                        }
                                        echo '<option '.$selected.' value="'.$espaco['idEspaco'].'">'.substr($espaco['nome'], 0, 15).'</option>';
                                    }
                            ?>
                            </select>
                              <?php
                            } else {
                                ?><font style="font-size: 14px; font-style: italic;"><!-- intervalo --> ------- </font><?php
                            }
                         } ?>
                    </td>
                    <?php } ?>
                </tr>
                <?php
                } //FIM DA GRADE DE HORARIO
                ?>
            </tbody>
        </table>
        </div>

        <table style="width: 90%;" align="center">
            <tr>
                <td><input type="button" value="Editar" name="Prosseguir" onclick="prosseguir();" style="width: 100px"></td>
                <td align="right"><input type="button" value="Voltar" onclick="document.formVoltar.submit();" style="width: 100px"></td>
            </tr>
        </table>
    </fieldset>
</form>
<form name="formVoltar" action="manterTurmas_controle.php?acao=exibirTurmas" method="POST">
    <input type="hidden" name="siglaCurso" value = "<?php echo $dadosDaTurma['siglaCurso']; ?>">
    <input type="hidden" name="idPeriodoLetivo" value = "<?php echo $dadosDaTurma['idPeriodoLetivo']; ?>">
    <input type="hidden" name="turno" value = "<?php echo $_POST['voltar_turno']; ?>">
</form>