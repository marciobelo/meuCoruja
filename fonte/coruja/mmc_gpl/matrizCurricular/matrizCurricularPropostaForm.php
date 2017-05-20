<?php
    require_once "$BASE_DIR/includes/topo.php";
    require_once "$BASE_DIR/includes/menu_horizontal.php";
?>
<script type="text/javascript" src="/coruja/javascript/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="/coruja/javascript/jquery-ui.min.js"></script>
<link rel="stylesheet" type="text/css" href="matrizCurricularProposta.css">
<link rel="stylesheet" type="text/css" href="../font-awesome/css/font-awesome.min.css">
<script type="text/javascript" src="../jsPlumb/jsPlumb-2.2.3-min.js"></script>
<script type="text/javascript" src="../mmc_gpl_calendar.js"></script>


<div id="dialog-form-componente-curricular">
  <div id="error"></div><br/>
    <font size="-1" color="#FF0000">Os campos marcados com (*) s&atilde;o obrigat&oacute;rios!</font><br /><br />
    <form name="componenteCurricularPropostoForm" id="componenteCurricularPropostoForm">
        <div>
            <label for="nomeDisciplina">Disciplina(*) : </label>
                <input class="hasValidacao" type="text" name="nomeDisciplina" id="nomeDisciplina" maxlength="80">
                <span class="validacao" id="validacaoDisciplina"> *</span>
        </div>  
        <br/>
        <div>
            <label for="siglaDisciplina">Sigla(*) : </label>
            <input class="hasValidacao" type="text" name="siglaDisciplina" id="siglaDisciplina" maxlength="6"">
            <span class="validacao" id="validacaoSiglaDisciplina"> *</span>
        </div>
        <br/>
        <div>
            <label for="tipoComponenteCurricular">Tipo(*) : </label>
            <select id="tipoComponenteCurricular" name="tipoComponenteCurricular">
                <option value="OBRIGAT&Oacute;RIA">Obrigat&oacute;ria</option>
                <option value="ELETIVA">Eletiva</option>
                <option value="OPTATIVA">Optativa</option>
            </select>
        </div>
        <br/>
        <div>
            <label for="perido">Per&iacute;odo(*) : </label>
            <select id="periodo" name="periodo" onchange="montaSelectPreRequisitos(this.value)"></select>
        </div>
        <br/>
        <div>
            <label for="cargaHoraria">Carga Hor&aacute;ria(*) : </label>
            <input class="hasValidacao" name="cargaHoraria" type="text" id="cargaHoraria" maxlength="6">
            <span class="validacao" id="validacaoCargaHoraria"> *</span>
        </div>
        <br/>
        <div>
            <label for="creditos">Cr&eacute;ditos(*) : </label>
            <input class="hasValidacao" type="text" maxlength="6" name="creditos" id="creditos">
            <span class="validacao" id="validacaoCreditos"> *</span>
        </div>
        <br/>
        <div style="float:left">
            <label for="equivalencia">Equival&ecirc;ncias: </label>
            <select id="equivalencias" name="equivalencias" multiple size="5"></select>
        </div>
        <div style="float:left" id="divPreRequisitos">
            <label for="preRequisitos">Pr&eacute;-Requisitos:</label>
            <select id="preRequisitos" name="preRequisitos" multiple size="5"></select>
        </div>
        <input type="hidden" name="siglaCursoMatrizProposta" id="siglaCursoMatrizProposta">
        <input type="hidden" name="idMatrizVigente" id="idMatrizVigente">
        <input type="hidden" name="action" id="action">
        <input type="hidden" name="oldSiglaDisciplina" id="oldSiglaDisciplina">
        <input type="hidden" name="oldPeriodo" id="oldPeriodo">
        <input type="hidden" name="oldCreditos" id="oldCreditos">
        <input type="hidden" name="posicaoPeriodo" id="posicaoPeriodo">
        <input type="hidden" name="possiveisPreRequisitos" id="possiveisPreRequisitos">
    </form>
</div>

<form id="validacaoForm" method="POST" action="listaMatrizCurricularProposta_controle.php">
  <input type="hidden" id="mensagemValidacao" name="mensagemValidacao">
</form>

<div id="dialog-listagem-equivalencias"></div>
<div id="dialog-valida-matriz"></div>

<fieldset class="fieldSet">
    <legend>Matriz Curricular Proposta Para o Curso <?php echo " " . $matrizProposta->getSiglaCurso() ?></legend>
        <div style="margin-left: 2%; width:45%;float:left">
            Quantidade De Per&iacute;odos: 
            <select onChange="redesenharPeriodosMatriz(this);" id="periodosDaMatriz" class="periodosDaMatriz">
            <?php 
                foreach ($periodosDaMatriz as $key => $value) {
            ?>
                    <option value="<?php echo $key ?>"><?php echo $value ?> </option>
            <?php
                }
            ?>
            </select>
        </div>
        <div style=" width:43%;float:left">
                <input type="button" value="+ Componente Curricular" onclick="criarComponenteCurricular()"/>
            <input type="hidden" id="maiorPeriodo" name="maiorPeriodo"/>
        </div>
        <div style="float:left">
            <input type="button" value="Validar Matriz" onclick="validaMatriz()"/>
        </div>
</fieldset>
<?php
    $creditos = 0;
    echo "<div id='matrizProposta' class='matrizProposta'> ";
            foreach ($tituloPeriodos as $key => $value) {
                if ($key <= $totalPeriodos) {
                    echo "<div id='periodo$key' class='periodo'>";
                        echo "<div class='tituloPeriodo'>";
                            echo "<div class='textoTituloPeriodo'>" . utf8_decode($value) . " Per&iacute;odo</div>";
                        echo "</div>";
                       
                        $idBodyPeriodo = "bodyPeriodo". $key;
                        echo "<div class='bodyPeriodo' id='$idBodyPeriodo'>";
                            foreach ($componentesPorPeriodos[$value] as $componenteCurricular) {
                                    $idComponenteCurricular = $componenteCurricular->getSiglaDisciplina();
                                    $siglaComponente = $componenteCurricular->getSiglaDisciplina();  

                                    echo "<div class='linhaCcp' id='linhaCcp'>";
                                        echo "<div class='componenteCurricular' id='ccp" . $idComponenteCurricular . "'>";
                                            echo "<span id='$idComponenteCurricular' class='ccpText'>" . $siglaComponente . "</span>";
?> 
                                                <span class="fa fa-pencil-square-o" id="edit_<?php echo $idComponenteCurricular?>" aria-hidden="true" onclick="editarComponenteCurricularProposto(this)"></span>
                                                <span class="fa fa-trash" id="ex_<?php echo $idComponenteCurricular?>" aria-hidden="true" onclick="excluirComponenteCurricularProposto(this)"></span>
<?php
                                        echo "</div>";
                                    echo "</div>";

                                    $creditos += $componenteCurricular->getCreditos();
                            }
                            
                        echo "</div>";
                        echo "<div class='creditos' id='creditos" . $key . "'> Cr&eacute;ditos: " . $creditos . "</div>";
                    echo "</div>";
                    $creditos = 0;
                }
            }
        echo"</div>";   
?>

<fieldset>
  <legend>Componentes Curriculares da Matriz anterior</legend>
    <?php 
        echo "<div id='ccAnteriores'>";
            foreach ($componenteCurricularesAntigosToView as $componenteCurricular) {
                $siglaDisciplina = $componenteCurricular['informacoesCc']['siglaDisciplina'];
                $idCc = 'cc' . $siglaDisciplina;

                if($componenteCurricular['informacoesCc']['corDaClasse'] === 'vermelho') {
                    echo "<div class='cc vermelho' id='" . $idCc . "'> ";
                        echo "<span class='ccAntigoText'>" . $siglaDisciplina . "</span> ";
                    echo "</div>";
                } else {
                    $class = 'cc ' . $componenteCurricular['informacoesCc']['corDaClasse'];
                    echo "<div class='" . $class . "' id='" . $idCc . "'> ";
                        echo"<span class='antigoText'>" . $siglaDisciplina . "</span> "
                            . "<span id='listEquivalencias_" . $siglaDisciplina . "' class='fa fa-list' aria-hidden='true' onclick=\"listarEquivalencias('" . $siglaDisciplina . "')\"></span>";
                    echo "</div>";
                }
            }
            echo "<div id='legenda'>";
                echo "<div class='linhaLegenda'><div class='quadradoLegenda vermelho'></div><div class='textoLegenda'>Sem equival&ecirc;ncia</div></div>";
                echo "<div class='linhaLegenda'><div class='quadradoLegenda amarelo'></div><div class='textoLegenda'>Equival&ecirc;ncia Parcial</div></div>";
                echo "<div class='linhaLegenda'><div class='quadradoLegenda verde' ></div><div class='textoLegenda'>Equival&ecirc;ncia Completa</div></div>";
            echo "</div>";
                    
        echo "</div>";
        
        
    ?>  
</fieldset>

<script>
    var siglaCurso = <?php echo json_encode($matrizProposta->getSiglaCurso()); ?>;
    var idMatrizVigente = <?php echo json_encode($matrizProposta->getIdMatrizVigente()); ?>;
    var periodosDaMatriz = <?php echo json_encode($tituloPeriodos); ?>;
    var possiveisPreRequisitos = <?php echo json_encode($possiveisPreRequisitos); ?>;
    var componentesCurricularesAntigosSemEquivalencia = <?php echo json_encode($componentesCurricularesAntigosSemEquivalencia); ?>;
    var componentesCurricularesAntigosComEquivalencia = <?php echo json_encode($componentesCurricularesAntigosComEquivalencia); ?>;
    var conexoesPreRequisitos = [];

    $('#siglaCursoMatrizProposta').val(siglaCurso);
    $('#idMatrizVigente').val(idMatrizVigente);
    
    function validaMatriz() {
        if(!confirm("Ap\u00f3s a matriz ser validada, ela n\u00e3o poder\u00e1 mais ser editada, deseja realmente efetuar a valida\u00e7\u00e3o?")){
            return false;
        }
        
        $.ajax({
            type: "POST",
            url:  "validarMatriz.php",
            data: {siglaCurso: siglaCurso, idMatriz: idMatrizVigente, acao: "obter"}
        }).success(function(response){
            var componentesSemValidacaoCompleta = JSON.parse(response);
            var contentValidacaoMatriz ='';
            var equivalenciasIncompletas = '&nbsp;&nbsp;';
            
            if (componentesSemValidacaoCompleta.length > 0) {
                $(componentesSemValidacaoCompleta).each(function(index, siglaDisciplina){                    
                    equivalenciasIncompletas  += siglaDisciplina + ', ';
                });
                equivalenciasIncompletas = equivalenciasIncompletas.substring(0, equivalenciasIncompletas.length -2);
                
                contentValidacaoMatriz +=  "<div id='errorValidacao'>" +
                                            "<div> - Se a Matriz for validada, os seguintes componente ficar\u00e3o sem equival\u00eancia total: </div>" +
                                            "<div>" + equivalenciasIncompletas + "</div>" + 
                                        "</div>";

            } else {
                contentValidacaoMatriz += "<div id='errorValidacao' style='display: none'></div>";
            }
            
            
            
            contentValidacaoMatriz += "<br /><font size='-1' color='#FF0000'>Os campos marcados com (*) s&atilde;o obrigat&oacute;rios!</font><br /><br />";
            contentValidacaoMatriz += "Data Inicial da Vig\u00eancia(*): <input type='text' name='dataInicialVigencia' id='dataInicialVigencia'  size='15'  maxlength='10' readonly placeholder='Selecione uma data'>" +
                                        "<button class='calendar' type='button' onclick=\"displayCalendar(document.getElementById('dataInicialVigencia'),'dd/mm/yyyy',this)\"></button> " +
                                        "<span class='validacao' id='validacaoDataInicialVigencia'>*</span>";
            
            $( "#dialog-valida-matriz" ).dialog({
                title: "Validacao de Matriz Proposta"
            });
            
            validaMatrizModal.html(contentValidacaoMatriz);
            validaMatrizModal.dialog('open');
                
        });
    }
    
    function efetivarValidacaoMatriz() {
        if(!validaFormValidacao()) {
            return false;
        }
        
        dataInicialVigencia = $('#dataInicialVigencia').val();
        
        $.ajax({
            type: "POST",
            url:  "validarMatriz.php",
            data: {siglaCurso: siglaCurso, idMatriz: idMatrizVigente, dataInicialVigencia: dataInicialVigencia, acao: "efetivar"}
        }).success(function(response){
            if(!validaRespostaServidorValidacao(response)) {
                return false;
            }
            
            if(response === "sucesso") {
                $('#mensagemValidacao').val('Matriz Proposta do curso ' + siglaCurso + ' validada com sucesso!');
                $('#validacaoForm').submit();
                $( this ).dialog("close");
            }
        });
    }

    function montaTodosPreRequisitos() {
        excluirTodasConexoesPreRequisito();
        delete conexoesPreRequisitos;
        
        $.ajax({
            type: "POST",
            url:  "gerenciarPreRequisitos_controle.php",
            data: {siglaCurso: siglaCurso, idMatriz: idMatrizVigente, acao: 'obter'}
        }).success(function(response){
            var listaPreRequisitos = JSON.parse(response);
            var targets = (Object.keys(listaPreRequisitos));
            
            for (idx in targets) {
                var target = targets[idx];
                var sources = listaPreRequisitos[target];
                montarPreRequisitosDeUmCcp(target, sources);
            }
        });
    }   
    
    function montarPreRequisitosDeUmCcp(siglaDisciplina, preRequisitos) {
        
        var target = siglaDisciplina;
        var sources = preRequisitos;
        
        var connectionInstance = jsPlumb.getInstance({
            Container: 'matrizProposta'
        });

        connectionInstance.importDefaults({
            Connector : [ "Straight"],
            Anchors : [ "RightMiddle", "LeftMiddle" ]
        });
        
        for (idx in sources) {
            var source = sources[idx];
            
            connectionInstance.connect({
                source:"ccp" + source, 
                target:"ccp" + target,
                endpointStyle:{radius:35},
                overlays:[ 
                    ["Arrow", { width:8, length:8, location:1}]
                ]
            });
        }

        conexoesPreRequisitos[target] = connectionInstance;
    }
    
    function excluirTodasConexoesPreRequisito() {
        for(idx in conexoesPreRequisitos) {
            conexoesPreRequisitos[idx].deleteEveryEndpoint();
        }
    }
            
    function criarLinhaNaMatriz(siglaDisciplina, periodo) {
        var idComponenteCurricular = siglaDisciplina;
        
        var linhaComponenteCurricular = "<div class='linhaCcp'>"
                                            + "<div class='componenteCurricular' id='ccp" + siglaDisciplina + "'>"
                                                + "<span id='" + idComponenteCurricular + "' class='ccpText'>" + siglaDisciplina + "</span>\n"
                                                + "<span class='fa fa-pencil-square-o' id='edit_" + idComponenteCurricular + "' aria-hidden='true' onclick=\"editarComponenteCurricularProposto(this)\"></span>\n"
                                                + "<span class='fa fa-trash' id='ex_" + idComponenteCurricular + "'aria-hidden='true' onclick=\"excluirComponenteCurricularProposto(this)\"></span>"
                                            + "</div>"
                                        + "</div>";
                                       
        $('#bodyPeriodo' + periodo).append(linhaComponenteCurricular);        
    }
    
    function removerLinhaNaMatriz(sigalDisciplina) {
        $('#' + sigalDisciplina).parent().parent().remove();
    }
    
    function alterarTextoCcp(oldSiglaDisciplina, siglaDisciplina) {
        $('#'+ oldSiglaDisciplina).attr("id",siglaDisciplina);
        
        $('#'+ siglaDisciplina).html(siglaDisciplina);
        
        //facilita os testes
        var inputEdit = $('#'+ siglaDisciplina).siblings()[0];
        var inputDelete = $('#'+ siglaDisciplina).siblings()[1];
        
        $(inputEdit).attr("id",'edit_'+siglaDisciplina);
        $(inputDelete).attr("id",'ex_'+siglaDisciplina);

        
        $('#'+ siglaDisciplina).parent().attr("id",'ccp'+siglaDisciplina);
    }
    
    function configuraPosicaoDoElementoNoPeriodo(periodo) {
        var pos = $('#bodyPeriodo' + periodo).children().size();
        $('#posicaoPeriodo').val(pos);
    }
    
    function validaFormValidacao() {
        if ($('#dataInicialVigencia').val().length < 1) {
            $('#dataInicialVigencia').css('border-color', 'red');
            $('#validacaoDataInicialVigencia').show();
            return false;
        }
        return true;
    }
    
    function validaRespostaServidorValidacao(response) {
        if(response === 'semPermissao') {
            window.location.assign("/coruja/baseCoruja/formularios/sem_permissao.php");
            return false;
        }
        
        if (response === 'dataInvalida') {
            if ($('#errorValidacao').text().indexOf("A data inicial de") === -1) {
                $('#dataInicialVigencia').css('border-color', 'red');
                $('#errorValidacao').append('- A data inicial de vig\u00eancia da nova Matriz deve ser maior que a da Matriz anterior');
                $('#errorValidacao').show();
                return false;
            }
        }
        return true;
    }
    
    function resetFormularioValidacao() {
        $('#validacaoDataInicialVigencia').removeAttr('style');
        $('#dataInicialVigencia').removeAttr('style');
        $('#errorValidacao').hide();
        $('#errorValidacao').text('');
    }
    
    function validaFormComponente() {
        resetFormularioComponenteCurricularProposto();
        var isValid = true;
        var onlyNumberRegex = /^[0-9]+$/;
        var siglaRegex = /^[a-zA-z]+[\d]*?$/;
        
        if ($('#nomeDisciplina').val().length < 1) {
            isValid = false;
            $('#nomeDisciplina').css('border-color', 'red');
            $('#validacaoDisciplina').show();
        }
        
        if($('#siglaDisciplina').val().length < 1 || !siglaRegex.test($('#siglaDisciplina').val())) {
            isValid = false;
            $('#siglaDisciplina').css('border-color', 'red');
            $('#validacaoSiglaDisciplina').show();
            if ($('#siglaDisciplina').val().length > 0 && !siglaRegex.test($('#siglaDisciplina').val())) {
                var msg = '- O campo Sigla deve conter apenas letras sem acento e n\u00fameros opcionais, nessa ordem';
                if($('#error').css('display') === 'block') {
                    var msg = '</br>' + msg;
                }

                $('#error').append(msg);
                $('#error').show();
            }
        } 
        
        if($('#cargaHoraria').val().length < 1 || !onlyNumberRegex.test($('#cargaHoraria').val())) {
            isValid = false;
            $('#cargaHoraria').css('border-color', 'red');
            $('#validacaoCargaHoraria').show();
            if ($('#cargaHoraria').val().length > 0 && !onlyNumberRegex.test($('#cargaHoraria').val())) {
                var msg = '- O campo Carga Hor\u00e1ria deve conter apenas n\u00fameros';
                if($('#error').css('display') === 'block') {
                    var msg = '</br>' + msg;
                }

                $('#error').append(msg);
                $('#error').show();
            }
        }
        
        if($('#creditos').val().length < 1 || !onlyNumberRegex.test($('#creditos').val())) {
            isValid = false;
            $('#creditos').css('border-color', 'red');
            $('#validacaoCreditos').show();

            if ($('#creditos').val().length > 0 && !onlyNumberRegex.test($('#creditos').val())) {
                var msg = '- O campo Cr\u00e9ditos deve conter apenas n\u00fameros';
                if($('#error').css('display') === 'block') {
                    var msg = '</br>' + msg;
                }

                $('#error').append(msg);
                $('#error').show();
            }
        }
        
        if(!isValid) {
            return false;
        }
        
        return true;
    }
    
    function validaRespostaServidor(response) {
        if (response === 'semPermissao') {
            window.location.assign("/coruja/baseCoruja/formularios/sem_permissao.php");
        }
        
        $('#error').text();

        if (response === 'repetido') {
            $('#siglaDisciplina').css('border-color', 'red');
            $('#error').append('- Essa sigla j\u00e1 est\u00e1 em uso');
            $('#error').show();
            return false;
        } else if (response === 'siglaInvalida') {
            $('#siglaDisciplina').css('border-color', 'red');
            $('#error').append('- Sigla Inv\u00e1lida');
            $('#error').show();
            return false;
        } else if (response === 'periodoCheio') {
            $('#periodo').css('border-color', 'red');
            $('#error').append('- Esse Per\u00edodo j\u00e1 atingiu o limite de 12 componentes');
            $('#error').show();
            return false;
        }
       
        return true;
    }
    
    function resetFormularioComponenteCurricularProposto() {
        var asteriscos = $('.validacao');
        var hasValidacao = $('.hasValidacao');
        
        $(asteriscos).each(function(index, asterisco){   
            $(asterisco).removeAttr('style');
        });
        
        $(hasValidacao).each(function(index, input){   
            $(input).removeAttr('style');
        });
        
        $('#error').hide();
        $('#error').text('');
    }
    
    function salvarComponenteCurricularProposto() {
        if(!validaFormComponente()) {
            return false;
        }
            
        var action = $('#action').val();
        var siglaDisciplina = $('#siglaDisciplina').val().toUpperCase();
        var oldSiglaDisciplina = $('#oldSiglaDisciplina').val();
        var periodo = $('#periodo').val();
        var oldPeriodo = $('#oldPeriodo').val();
        var creditos = $('#creditos').val();
        var oldCreditos = $('#oldCreditos').val();
        var preRequisitos = $('#preRequisitos').val();
        configuraPosicaoDoElementoNoPeriodo(periodo);
        
        var formData = $(document.componenteCurricularPropostoForm).serializeArray();
        
        $.ajax({
            type: "POST",
            url:  "gerenciarComponenteCurricularProposto_controle.php",
            data: {action: action, data: formData}
        }).success(function(response){
            if (!validaRespostaServidor(response)) {
                return false;
            }

            if (action === 'editar') {
                var modificouPeriodo = oldPeriodo !== periodo;

                if(modificouPeriodo) {
                    removerLinhaNaMatriz(oldSiglaDisciplina);
                    alterarQuantidadeDeCreditosDoPeriodo(creditos, oldPeriodo, 'excluir', oldCreditos);

                    criarLinhaNaMatriz(siglaDisciplina, periodo);
                    alterarQuantidadeDeCreditosDoPeriodo(creditos, periodo, 'criar', oldCreditos);
                    
                    reindexaComponentesDoPeriodo(oldPeriodo);
                    reindexaComponentesDoPeriodo(periodo);                    
                } else {
                    alterarQuantidadeDeCreditosDoPeriodo(creditos, periodo, 'editar', oldCreditos);
                    alterarTextoCcp(oldSiglaDisciplina, siglaDisciplina);
                    reindexaComponentesDoPeriodo(periodo);
                }

                if(siglaDisciplina !== oldSiglaDisciplina){
                    alterarTextoCcp(oldSiglaDisciplina, siglaDisciplina);
                }
                montaTodosPreRequisitos();
            } else {
                criarLinhaNaMatriz(siglaDisciplina, periodo);
                alterarQuantidadeDeCreditosDoPeriodo(creditos, periodo, 'criar', oldCreditos);
                
                if (!!preRequisitos) {
                    montarPreRequisitosDeUmCcp(siglaDisciplina, preRequisitos);
                }
            }
            
            verificarEquivalencias();
            componenteCurricularPropostoDialog.dialog('close');
        }).fail(function(error){
            console.log("Ocorreu o seguinte erro no servidor: " + error.status + " - " + error.statusText);
        });
    }
    
    function alterarQuantidadeDeCreditosDoPeriodo (creditos, periodo, action, oldCreditos) {
        var idCreditos = '#creditos' + periodo;
        
        var textoCreditos = $(idCreditos).html();
        
        var infosCredito = textoCreditos.split(':');
        var textoCredito = infosCredito[0];
        var totalCreditosAtual = infosCredito[1].trim();
        var novoTotalCreditos;
        
        if (action === 'criar') {
            novoTotalCreditos = (parseInt(totalCreditosAtual) + parseInt(creditos));        
        } else if (action === 'editar') {
            novoTotalCreditos = (parseInt(totalCreditosAtual) - parseInt(oldCreditos));
            novoTotalCreditos = (novoTotalCreditos + parseInt(creditos));        
        } else {
            novoTotalCreditos = (parseInt(totalCreditosAtual) - parseInt(oldCreditos));
        }
        
        $(idCreditos).html(textoCredito + ': ' + novoTotalCreditos);
    }
    
    function editarComponenteCurricularProposto(siblingElement) {
        var siglaDisciplina = $(siblingElement).siblings(0).html().toString();
        configuraEquivalencias(siglaDisciplina);
        
        $('#action').val('editar');
        $("#dialog-form-componente-curricular").dialog({
            title: "Editar Componente Curricular Proposto: " + siglaDisciplina
        });

        $.ajax({
            type: "POST",
            url:  "obterDadosComponenteCurricularProposto_controle.php",
            data: {siglaCurso: siglaCurso, siglaDisciplina: siglaDisciplina, idMatriz: idMatrizVigente}
        }).success(function(response){
            var dados = JSON.parse(response);

            $('#siglaDisciplina').val(dados.ccp.siglaDisciplina);
            $('#nomeDisciplina').val(dados.ccp.nomeDisciplina);
            $('#cargaHoraria').val(dados.ccp.cargaHoraria);
            $('#creditos').val(dados.ccp.creditos);
            $('#periodo').val(dados.ccp.periodo);
            $('#tipoComponenteCurricular').val(dados.ccp.tipoComponenteCurricular);
            $('#preRequisitos').val(dados.ccp.preRequisitos);

            //Valores que devem ser mantidos para futuras comparacoes
            $('#oldSiglaDisciplina').val(dados.ccp.siglaDisciplina);
            $('#oldPeriodo').val(dados.ccp.periodo);
            $('#oldCreditos').val(dados.ccp.creditos);
            
            //global
            possiveisPreRequisitos = dados.possiveisPreRequisitos;    
            
            montaSelectPreRequisitos(dados.ccp.periodo);
            configurarSelectPreRequisitos(dados.preRequisitos);
            
            componenteCurricularPropostoDialog.dialog('open');
        }).fail(function(error){
            console.log("Ocorreu o seguinte erro no servidor: " + error.status + " - " + error.statusText);
        });
    }
    
    function excluirComponenteCurricularProposto(siblingElement) {
        obterPossiveisPreRequisitos();
        var siglaCurso = $('#siglaCursoMatrizProposta').val();
        var idMatriz = $('#idMatrizVigente').val();
        $('#action').val('excluir');
        var siglaDisciplina = $(siblingElement).siblings(0).html().toString();
        
        if(!confirm("Deseja realmente remover o componente Curricular Proposto " + siglaDisciplina + "?")){
            return false;
        }
        
        //Essa requisicao e necessaria pois nesse contexto nao existe dados do componente curricular proposto;
        $.ajax({
            type: "POST",
            url:  "obterDadosComponenteCurricularProposto_controle.php",
            data: {siglaCurso: siglaCurso, siglaDisciplina: siglaDisciplina, idMatriz: idMatrizVigente}
        }).success(function(response){
            var dados = JSON.parse(response);
            var periodo = dados.ccp.periodo;
            var creditos = dados.ccp.creditos;
            var oldCreditos = dados.ccp.creditos;
            alterarQuantidadeDeCreditosDoPeriodo(creditos, periodo, 'excluir', oldCreditos);
            clearDialogForm();
            
            $.ajax({
                type: "POST",
                url:  "gerenciarComponenteCurricularProposto_controle.php",
                data: {siglaCurso: siglaCurso, siglaDisciplina: siglaDisciplina, idMatrizEquivalente: idMatriz, action: 'excluir'}
             }).success(function(response){
                 if(response === 'semPermissao') {
                    window.location.assign("/coruja/baseCoruja/formularios/sem_permissao.php");
                    return false;
                 }
                 
                 removerLinhaNaMatriz(siglaDisciplina);;
                 
                 $(possiveisPreRequisitos).each(function(index, value){
                     if(value.siglaDisciplina === siglaDisciplina) {
                         delete possiveisPreRequisitos[index];
                     }
                 });
                 
                 montaTodosPreRequisitos();
                 reindexaComponentesDoPeriodo(periodo);
                 verificarEquivalencias();
             }).fail(function(error){
                 console.log("Ocorreu o seguinte erro no servidor: " + error.status + " - " + error.statusText);
             });
        });      
    }
    
    function montaSelectEquivalencia() {
        $.ajax({
           type: "POST",
           url: "obterComponentesCurricularesPorSiglaCurso_controle.php",
           data: {siglaCurso: siglaCurso, idMatrizVigente: idMatrizVigente}
        }). success(function(response){
            var dados = JSON.parse(response);
            //Evitando o bug de duplicacao dos valores.
            $('#equivalencias').empty();
    
            $(dados).each(function(index, object){
                $('#equivalencias').append($('<option>', {
                    value: object['siglaDisciplina'],
                    text: object['siglaDisciplina']
                }));
            });
        });
    }
    
    function configuraEquivalencias(siglaDisciplina) {
        $.ajax({
           type: "POST",
           url: "obterEquivalencias_controle.php",
           data: {siglaCurso: siglaCurso, idMatrizVigente: idMatrizVigente, siglaDisciplina: siglaDisciplina}
        }). success(function(response){
            var equivalencias = JSON.parse(response);
            $('#equivalencias').val(equivalencias);
        });
    }
    
    function configurarSelectPreRequisitos(preRequisitos) {
        $('#preRequisitos').val(preRequisitos);
    }
    
    function verificarEquivalencias() {
        $.ajax({
            type: "POST",
            url: "obterInformacoesEquivalencias_controle.php",
            data: {siglaCurso: siglaCurso, idMatrizAntiga: idMatrizVigente}
        }).success(function(response){
            var informacoesEquivalencias = JSON.parse(response);
            
            //Verificando novos componentes com equivalencia.
            for (idx in informacoesEquivalencias.comEquivalencia) {
                var componente = informacoesEquivalencias.comEquivalencia[idx];
                var elemento = $('#ccAnteriores').find('#cc' + componente.siglaDisciplina);
                
                if ( elemento.hasClass("vermelho")) {
                    elemento.removeClass("vermelho");
                    var corClasse = (componente.estadoEquivalencia === 'total') ? 'verde' : 'amarelo';
                    elemento.addClass(corClasse);

                    elemento.children().remove('.fa-list');
                    var botaoList = "<span id='listEquivalencias' class='fa fa-list' aria-hidden='true' onclick=\"listarEquivalencias('" + componente.siglaDisciplina + "')\"></span>";
                    elemento.append(botaoList);
                }
            } 
            
            //Verificando componentes que nao possuem mais equivalencia
            for (idx in informacoesEquivalencias.semEquivalencia) {
                var componente = informacoesEquivalencias.semEquivalencia[idx];
                var elemento = $('#ccAnteriores').find('#cc' + componente.siglaDisciplina);
                
                var corClasse = (elemento.hasClass('amarelo'))? 'amarelo' : 'verde';
                elemento.removeClass(corClasse);
                elemento.addClass('vermelho');

                elemento.children().remove('.fa-list');
            }
            
            //Verificando o estado dos componentes que ja possuiam equivalencia.
            for (idx in informacoesEquivalencias.comEquivalencia) {
                var componenteFromBackend = informacoesEquivalencias.comEquivalencia[idx];
                var componenteFromScreen = $('#ccAnteriores').find('#cc' + componenteFromBackend.siglaDisciplina);
                
                if ($(componenteFromScreen).hasClass('amarelo')){
                    if (componenteFromBackend.estadoEquivalencia === 'total') {
                        $(componenteFromScreen).removeClass('amarelo');    
                        $(componenteFromScreen).addClass('verde');
                    } else if (!componenteFromBackend.estadoEquivalencia){
                        $(componenteFromScreen).removeClass('amarelo');    
                        $(componenteFromScreen).addClass('vermelho');
                    } 
                } else if ($(componenteFromScreen).hasClass('verde')){
                    if (componenteFromBackend.estadoEquivalencia === 'parcial') {
                        $(componenteFromScreen).removeClass('verde');    
                        $(componenteFromScreen).addClass('amarelo');
                    } else if (!componenteFromBackend.estadoEquivalencia){
                        $(componenteFromScreen).removeClass('verde');    
                        $(componenteFromScreen).addClass('vermelho');
                    } 
                }
            }
        });
    }
    
    function listarEquivalencias(siglaDisciplina) {
        $.ajax({
           type: "POST",
           url: "obterInformacoesEquivalencias_controle.php",
           data: {siglaCurso: siglaCurso, idMatrizAntiga: idMatrizVigente, siglaDisciplina: siglaDisciplina}
        }). success(function(response){
            var infoEquivalencias = JSON.parse(response);
            
            var componente = infoEquivalencias.comEquivalencia[0];
            var linhasEquivalencias = '';
            
                for (idx in  infoEquivalencias.informacoesEquivalencias) {
                    for(idx in  infoEquivalencias.informacoesEquivalencias[siglaDisciplina]) {
                        var equivalencia = infoEquivalencias.informacoesEquivalencias[siglaDisciplina][idx];
                        
                        var siglaDiciplina = equivalencia.siglaDisciplina;
                        var cargaHoraria = equivalencia.cargaHoraria;
                        var creditos = equivalencia.creditos;
                         
                        linhasEquivalencias += "<tr>" +
                                                    "<td>" + siglaDiciplina + "</td>" +
                                                    "<td>" + cargaHoraria + "h </td>" +
                                                    "<td>" + creditos + "</td>" +
                                                "</tr>";
                    }
                }

            var table =  "<div>" +
                            "<table>" +
                                "<thead>" +
                                    "<tr>" +
                                        "<th>Sigla Disciplina</th>" +
                                        "<th>Carga Hor&aacute;ria</th>" +
                                        "<th>Cr&eacute;ditos</th>" +
                                    "</tr>" +
                                "<thead>" +
                                "<tbody>" + 
                                    "<tr>" +
                                        "<td>" + componente.siglaDisciplina + "</td>" +
                                        "<td>" + componente.cargaHoraria + "h </td>" + 
                                        "<td>" + componente.creditos + "</td>" +
                                    "</tr>" +
                                        "<tr id='tituloEquivalenciasDialogList'>" +
                                        "<td colspan='3'>Equival&ecirc;ncias</td>" 
                                        + linhasEquivalencias +
                                    "</tr>" +
                                "</tbody>" +
                            "</table>" +
                        "</div>";

            $( "#dialog-listagem-equivalencias" ).dialog({
                title: "Equival\u00eancias de " + siglaDisciplina
            });

            listagemEquivalencias.html(table);
            listagemEquivalencias.dialog('open');
        });
    }
    
    function montaSelectPreRequisitos(periodo) {
        var preRequisitosSelecioandos = $('#preRequisitos').val();
        $('#preRequisitos').empty();
        
        if(parseInt(periodo) === 1) {
            $('#divPreRequisitos').hide();
            return;
        }

        var temPreRequisito = false;
        for (idx in possiveisPreRequisitos) {
            var possivelPreRequisito = possiveisPreRequisitos[idx];
            if(parseInt(possivelPreRequisito.periodo) < parseInt(periodo)) {
                temPreRequisito = true;
                $('#preRequisitos').append($('<option>', {
                    value: possivelPreRequisito.siglaDisciplina,
                    text: possivelPreRequisito.siglaDisciplina
                }));
            }
        }
            
        if (temPreRequisito) {
            $('#divPreRequisitos').show();
            $('#preRequisitos').val(preRequisitosSelecioandos);
        } else {
            $('#divPreRequisitos').hide();
        }
    }
    
    function alteraMaiorPeriodo(newMaiorPeriodo) {
        $.ajax({
            type: "POST",
            url: "alteraMaiorPeriodo_controle.php",
            data: {siglaCurso: siglaCurso, idMatrizVigente: idMatrizVigente, newMaiorPeriodo: newMaiorPeriodo}
        });
    }
    
    function refazSelectDePeriodosParaUmNovoComponente(maiorPeriodo) {
        $('#periodo').empty();

        for (pos in periodosDaMatriz) {
            if(pos <= parseInt(maiorPeriodo)) {
                $('#periodo').append(
                    $('<option>', {
                        value: pos,
                        text: periodosDaMatriz[pos]
                    })
                );
            }
        }
    } 
    
    function aumentaMatriz(oldMaiorPeriodo, newMaiorPeriodo) { 
        var quantidadeASerAumentada = parseInt(newMaiorPeriodo) - parseInt(oldMaiorPeriodo);

        var cont = 0;
        while (cont < quantidadeASerAumentada) {
            var numeroPeriodo = ++oldMaiorPeriodo;
            var nomePeriodo = periodosDaMatriz[numeroPeriodo]; 

            var periodo = "<div id='periodo" + numeroPeriodo + "' class='periodo'>"
                            + "<div class='tituloPeriodo'>"
                              + "<div class='textoTituloPeriodo'>" + nomePeriodo + " Per&iacute;odo</div>"
                            + "</div>"
                            + "<div class='bodyPeriodo' id='bodyPeriodo" + numeroPeriodo + "'></div>"
                            + "<div class='creditos' id='creditos" + numeroPeriodo + "'> Cr&eacute;ditos: 0</div>";
                          + "</div>";

            $('#matrizProposta').append(periodo);
            cont++;
        }           
        $("#bodyPeriodo" + numeroPeriodo).sortable();
        $('#maiorPeriodo').val(newMaiorPeriodo);
        alteraMaiorPeriodo(newMaiorPeriodo);
    }
    
    function reduzMatriz(newMaiorPeriodo, oldMaiorPeriodo) {
        $.ajax({
            type: "POST",
            url: "verificaExistenciaDeComponentesCurriculares_controle.php",
            data: {siglaCurso: siglaCurso, idMatrizVigente: idMatrizVigente, newMaiorPeriodo: newMaiorPeriodo}
        }).success(function(response){
            if (response === "true") {
                if(!confirm("Os Componentes Curriculares existentes no(s) per\u00eddo(s) que ser\u00e1(\u00e3o) removido(s) ser\u00e3o perdidos. Realmente deseja continuar com a remo\u00e7\u00e3o de per\u00edodos?")){
                    $('#periodosDaMatriz').val(oldMaiorPeriodo);
                    return false;
                }
            }
            
            var quantidadeASerReduzidada = parseInt(oldMaiorPeriodo) - parseInt(newMaiorPeriodo);

            var cont = quantidadeASerReduzidada;
            while (cont > 0) {
                var numeroPeriodo = oldMaiorPeriodo--;

                $('#periodo' + numeroPeriodo).remove();
                cont--;
            }           
            $('#maiorPeriodo').val(newMaiorPeriodo);
            alteraMaiorPeriodo(newMaiorPeriodo); 
            
            $.ajax({
                type: "POST",
                url: "removeComponentesCurriculareDosPeriodosRemovidos_controle.php",
                data: {siglaCurso: siglaCurso, idMatrizVigente: idMatrizVigente, newMaiorPeriodo: newMaiorPeriodo}
            }).success(function(){
                verificarEquivalencias();
                excluirTodasConexoesPreRequisito();
                montaTodosPreRequisitos();
            });
        });
    }
    
    function redesenharPeriodosMatriz (element) {
        var newMaiorPeriodo = parseInt($(element).val());
        var oldMaiorPeriodo = parseInt($('#maiorPeriodo').val());

        if ( newMaiorPeriodo === oldMaiorPeriodo) {
            return;
        }
        
        if (newMaiorPeriodo > oldMaiorPeriodo) {
            aumentaMatriz(oldMaiorPeriodo, newMaiorPeriodo);
        } else {
            reduzMatriz(newMaiorPeriodo, oldMaiorPeriodo);
        }
        
        refazSelectDePeriodosParaUmNovoComponente(newMaiorPeriodo); 
    }
    
    function obterPossiveisPreRequisitos() {
        $.ajax({
            type: "POST",
            url: "obterPossiveisPreRequisitos_controle.php",
            data: {siglaCurso: siglaCurso, idMatrizVigente: idMatrizVigente}
        }).success(function(response){
            possiveisPreRequisitos = JSON.parse(response);
        });
    }
    
    function criarComponenteCurricular() {
        obterPossiveisPreRequisitos();
        montaSelectPreRequisitos(1);
        $('#action').val('criar');

        $("#dialog-form-componente-curricular").dialog({
            title: "Criar Componente Curricular Proposto"
        });
        componenteCurricularPropostoDialog.dialog('open');
    }
    
    function clearDialogForm() {
        $('#componenteCurricularPropostoForm').trigger("reset");
        resetFormularioComponenteCurricularProposto();
    }

    var componenteCurricularPropostoDialog = $("#dialog-form-componente-curricular").dialog({
        autoOpen: false,
        modal: true,
        height: 'auto',
        width: '540',
        draggable: false,
        resizable: false,
        close: function(){
            clearDialogForm();
        },
        buttons: [
            {
                text: "Salvar",
                click: function() {
                    salvarComponenteCurricularProposto();
                }
            },
            {
              text: "Cancelar",
              click: function() {
                $( this ).dialog( "close" );
              }
            }
        ]
    });
    
    var listagemEquivalencias = $("#dialog-listagem-equivalencias").dialog({
        autoOpen: false,
        modal: true,
        height: 'auto',
        width: '500',
        draggable: false,
        resizable: false,
        buttons: [
            {
              text: "Ok",
              click: function() {
                $( this ).dialog( "close" );
              }
            }
        ]
    });
    
    var validaMatrizModal = $("#dialog-valida-matriz").dialog({
        autoOpen: false,
        modal: true,
        height: 'auto',
        width: '550',
        draggable: false,
        resizable: false,
        close: function(){
            if ( $('#calendarDiv').html()) {
                closeCalendar();
            }
            resetFormularioValidacao();
        },
        buttons: [
            {
              text: "Validar",
              click: function() {
                if(validaFormValidacao()) {
                    efetivarValidacaoMatriz();
                }
              }
            },
            {
              text: "Cancelar",
              click: function() {
                if ( $('#calendarDiv').html()) {
                    closeCalendar();
                }
                resetFormularioValidacao();
                $( this ).dialog("close");
              }
            }
        ]
    });

    function reindexaComponentesDoPeriodo(numeroPeriodo) {
        var children = $('#bodyPeriodo' + numeroPeriodo).children();
        var positionInfos = [];
        $(children).each(function(index, child){
            var siglaDisciplina = $(child).children('div').children('span').text();
            //removendo a div que exibe os creditos
            if (siglaDisciplina.length > 0) {;
                positionInfos[index] = siglaDisciplina;
            }
        });

        $.ajax({
            type: "POST",
            url: "reindexarComponentesDeUmPeriodo_controle.php",
            data: {siglaCurso: siglaCurso, idMatriz: idMatrizVigente, periodo: numeroPeriodo, posicoes: positionInfos}
        }).success(function(response){
            
        });
    }
    
    $("#matrizProposta .bodyPeriodo").sortable({
        start: function(event, el) {
            console.log(event, el);
            var elementoArrastado = $(el.item).children()[0];
            var idElementoArrastado = $(elementoArrastado).attr('id'); 
            var siglaDisciplina = idElementoArrastado.replace('ccp', '');
            
            //se o elemento for um target
            if(!!conexoesPreRequisitos[siglaDisciplina]) {
                conexoesPreRequisitos[siglaDisciplina].hide(idElementoArrastado);
            }
            
            // e/ou se o elemento for um source
            for (idx in conexoesPreRequisitos) {
                if (conexoesPreRequisitos[idx].select({source:idElementoArrastado})) {
                    conexoesPreRequisitos[idx].select({source:idElementoArrastado}).setVisible(false);
                }
            }
        },
        update: function( event, el ) {
            var elementoArrastado = $(el.item).children()[0];
            var idElementoArrastado = $(elementoArrastado).attr('id'); 
            var siglaDisciplina = idElementoArrastado.replace('ccp', '');
            
            
            //se o elemento for um target
            if(!!conexoesPreRequisitos[siglaDisciplina]) {
                conexoesPreRequisitos[siglaDisciplina].repaintEverything();
                conexoesPreRequisitos[siglaDisciplina].show(idElementoArrastado);
            }
            
            // e/ou se o elemento for um source
            for (idx in conexoesPreRequisitos) {
                if (conexoesPreRequisitos[idx].select({source:idElementoArrastado})) {
                    conexoesPreRequisitos[idx].select({source:idElementoArrastado}).setVisible(true);
                    conexoesPreRequisitos[idx].repaintEverything();
                }
            }
            
            var numeroPeriodo = $(this).attr('id').replace('bodyPeriodo', '');
            reindexaComponentesDoPeriodo(numeroPeriodo);
        }, 
        stop: function(event, el) {
            var elementoArrastado = $(el.item).children()[0];
            var idElementoArrastado = $(elementoArrastado).attr('id'); 
            var siglaDisciplina = idElementoArrastado.replace('ccp', '');
            
            //se o elemento for um target
            if(!!conexoesPreRequisitos[siglaDisciplina]) {
                conexoesPreRequisitos[siglaDisciplina].show(idElementoArrastado);
            } 
            
            // e/ou se o elemento for um source
            for (idx in conexoesPreRequisitos) {
                conexoesPreRequisitos[idx].select({source:idElementoArrastado}).setVisible(true)
            }
        }  
    });

    $(document).ready(function(){
        var maiorPeriodo = <?php echo json_encode($totalPeriodos); ?>;
        $('#maiorPeriodo').val(maiorPeriodo);
        $('#periodosDaMatriz').val(maiorPeriodo);
        refazSelectDePeriodosParaUmNovoComponente(maiorPeriodo);
        montaSelectEquivalencia();
        
        jsPlumb.ready(function() {
            montaTodosPreRequisitos();
        });
    });

</script>