<?php
require_once "$BASE_DIR/includes/topo.php";
require_once "$BASE_DIR/includes/menu_horizontal.php";

?>
<script type="text/javascript" src="/coruja/baseCoruja/javascripts/carrega_div.js"></script>
<script type="text/javascript" src="/coruja/interno/js/jscolor.js"></script>

<!-- seção Mensagens de erro, se houver -->
<?php
if(count($msgsErro)>0) {
?>
<ul class="erro">
<?php
    foreach($msgsErro as $msgErro) {
?>
    <li>
        <?php echo htmlspecialchars($msgErro, ENT_QUOTES, "iso-8859-1"); ?>
    </li>
<?php
    }
?>
</ul>
<?php
}
?>
<!-- fim mensagens de erro -->

<form id="cadastro" method="post" name="cadastro" action="/coruja/interno/manter_professor/manterProfessor_controle.php" >

    <fieldset id="fieldsetGeral">
        <input type="hidden" name="acao" value="exibirResultado" />
        <input type="hidden" name="siglaCurso" value="<?php echo $siglaCurso; ?>" />
        <input type="hidden" name="controleDestino" value="<?php echo $controleDestino; ?>" />
        <input type="hidden" name="acaoControleDestino" value="<?php echo $acaoControleDestino; ?>" />
        <input type="hidden" name="controleDestinoTitulo" value="<?php echo $controleDestinoTitulo; ?>" />

        <legend><?php echo $controleDestinoTitulo; ?><br/>
           CADASTRO DE NOVO PROFESSOR   </legend>
<br />

    <div class="row" id="didfv2" >
        <font size="-1" color="#FF0000">Os campos marcados com (*) são obrigatórios!</font>
           <table width="927">
                <tr>
                    <td width="68">Nome:(*)</td>
                  <td width="312"><input id="nome" type="text" onchange="this.value=this.value.toUpperCase();" style="text-transform: uppercase;" maxlength="80" size="32" value="<?php echo $_POST['nome'];?>" name="nome"></td>
                    <td>Sexo:(*)
                       <input type="radio" checked="checked" value="M" name="sexo">
Masculino
<input type="radio" value="F" name="sexo">
Feminino                  </td>
                  <td>Data Nasc  :(*)
                    <input id="dataNascimentoD" class="" type="text" onchange="" maxlength="2" size="2" value="<?php echo $_POST['dataNascimentoD'];?>" name="dataNascimentoD">
/
<input id="dataNascimentoM" class="" type="text" onchange="" maxlength="2" size="2" value="<?php echo $_POST['dataNascimentoM'];?>" name="dataNascimentoM">
/
<input id="dataNascimentoA" class="" type="text" onchange="" maxlength="4" size="4" value="<?php echo $_POST['dataNascimentoA'];?>" name="dataNascimentoA"></td>
                </tr>
      </table>

             <table width="937">
              <tr>
                <td>Nacionalidade :(*)
                  <input id="nacionalidade" type="text" onchange="this.value=this.value.toUpperCase();" style="text-transform: uppercase;" maxlength="45" size="37" value="<?php echo $_POST['nacionalidade'];?>" name="nacionalidade"></td>
                <td>Naturalidade :(*)
                  <input id="naturalidade" type="text" onchange="this.value=this.value.toUpperCase();" style="text-transform: uppercase;" maxlength="45" size="37" value="<?php echo $_POST['naturalidade'];?>" name="naturalidade"></td>
               </tr>
      </table>
             <table width="804">
               <tr>
                 <td width="170">CEP :
                 <input id="enderecoCEP" class="" type="text" onchange="" maxlength="9" size="8" value="<?php echo $_POST['enderecoCEP'];?>" name="enderecoCEP" /></td>
                 <td width="622">Endere&ccedil;o :
                 <input id="enderecoLogradouro" type="text" onchange="this.value=this.value.toUpperCase();" style="text-transform: uppercase;" maxlength="80" size="50" value="<?php echo $_POST['enderecoLogradouro'];?>" name="enderecoLogradouro" /></td>
               </tr>
      </table>
             <table width="920">
               <tr>
                 <td>Bairro :
                 <input id="enderecoBairro" type="text" onchange="this.value=this.value.toUpperCase();" style="text-transform: uppercase;" maxlength="60" size="30" value="<?php echo $_POST['enderecoBairro'];?>" name="enderecoBairro"></td>
                 <td>Munic&iacute;pio :
                 <input id="enderecoMunicipio" type="text" onchange="this.value=this.value.toUpperCase();" style="text-transform: uppercase;" maxlength="60" size="30" value="<?php echo $_POST['enderecoMunicipio'];?>" name="enderecoMunicipio"></td>
                 <td>Estado :    <select id="enderecoEstado" name="enderecoEstado">
  <option value="AC">AC</option>
  <option value="AL">AL</option>
  <option value="AM">AM</option>
  <option value="AP">AP</option>
  <option value="BA">BA</option>
  <option value="CE">CE</option>
  <option value="DF">DF</option>
  <option value="ES">ES</option>
  <option value="GO">GO</option>
  <option value="MA">MA</option>
  <option value="MG">MG</option>
  <option value="MS">MS</option>
  <option value="MT">MT</option>
  <option value="PA">PA</option>
  <option value="PB">PB</option>
  <option value="PE">PE</option>
  <option value="PI">PI</option>
  <option value="PR">PR</option>
  <option selected="selected" value="RJ">RJ</option>
  <option value="RN">RN</option>
  <option value="RO">RO</option>
  <option value="RR">RR</option>
  <option value="RS">RS</option>
  <option value="SC">SC</option>
  <option value="SE">SE</option>
  <option value="SP">SP</option>
  <option value="TO">TO</option>
                   </select></td>
               </tr>
      </table>

             <table width="920">
              <tr>
                <td width="317">Tel. Residencial :
                <input id="telefoneResidencial" class="" type="text" onchange="" maxlength="15" size="15" value="<?php echo $_POST['telefoneResidencial'];?>" name="telefoneResidencial"></td>
                <td width="308">Tel. Comercial :
                <input id="telefoneComercial" class="" type="text" onchange="" maxlength="15" size="15" value="<?php echo $_POST['telefoneComercial'];?>" name="telefoneComercial"></td>
                <td width="279">Tel. Celular :
                <input id="telefoneCelular" class="" type="text" onchange="" maxlength="15" size="15" value="<?php echo $_POST['telefoneCelular'];?>" name="telefoneCelular"></td>
               </tr>
      </table>
            <table width="916">
              <tr>
                <td>E-mail :
                <input id="email" class="" type="text" onchange="" maxlength="80" size="25" value="<?php echo $_POST['email'];?>" name="email" /></td>
                <td>Titula&ccedil;&atilde;o :
                 <select id="titulacaoAcademica" name="titulacaoAcademica">
                            <option value="DOUTOR">DOUTOR</option>
                            <option value="MESTRE">MESTRE</option>
                            <option value="ESPECIALISTA">ESPECIALISTA</option>
                        </select> <td>Lattes :
                <input id="cvLattes" type="text" onchange="this.value=this.value.toUpperCase();" style="text-transform: uppercase;" maxlength="80" size="37" value="<?php echo $_POST['cvLattes'];?>" name="cvLattes" /></td>
              </tr>
              <tr>
                <td>Nom de Guerra :
                <input id="nomeGuerra" class="" type="text" onchange="this.value=this.value.toUpperCase();"" maxlength="80" size="25" value="<?php echo $_POST['nomeGuerra'];?>" name="nomeGuerra" /></td>
                <td>Cor:
                    <input id="corFundo" type="text" onchange="this.value=this.value.toUpperCase();" style="text-transform: uppercase;" maxlength="80" size="15" value="<?php echo $_POST['corFundo'];?>" name="corFundo" class="color" /></td>
           
              </tr>
      </table>

    </div>
    </fieldset>


        <fieldset id="fieldsetGeral">
        <input type="hidden" name="acao" value="salvarProfessor" />
        <input type="hidden" name="controleDestino" value="<?php echo $controleDestino; ?>" />
        <input type="hidden" name="acaoControleDestino" value="<?php echo $acaoControleDestino; ?>" />
        <input type="hidden" name="controleDestinoTitulo" value="<?php echo $controleDestinoTitulo; ?>" />

        <legend><br/>
        MATRICULA</legend>
<br />

        <div class="row" id="didfv1" >
    <table width="1008">
              <tr>
                <td width="160">Matricula :
                <input id="novaMatriculaProfessor" type="text" onchange="this.value=this.value.toUpperCase();" style="text-transform: uppercase;" maxlength="80" size="10" value="<?php echo $_POST['novaMatriculaProfessor'];?>" name="novaMatriculaProfessor" /></td>
                <td width="218">Carga Horária :
                <input id="cargaHoraria" type="text" onchange="this.value=this.value.toUpperCase();" style="text-transform: uppercase;" maxlength="80" size="10" value="<?php echo $_POST['cargaHoraria'];?>" name="cargaHoraria" /></td>
                <td width="251">Data de inicio :
                  <input id="dataInicioD" class="" type="text" onchange="" maxlength="2" size="2" value="<?php echo $_POST['dataInicioD'];?>" name="dataInicioD" />
/
  <input id="dataInicioM" class="" type="text" onchange="" maxlength="2" size="2" value="<?php echo $_POST['dataInicioM'];?>" name="dataInicioM" />
/
<input id="dataInicioA" class="" type="text" onchange="" maxlength="4" size="4" value="<?php echo $_POST['dataInicioA'];?>" name="dataInicioA" /></td>
                <td width="284">Data de termino :
                  <input id="dataEncerramentoD" class="" type="text" onchange="" maxlength="2" size="2" value="<?php echo $_POST['dataEncerramentoD'];?>" name=dataEncerramentoD" />
/
  <input id="dataEncerramentoM" class="" type="text" onchange="" maxlength="2" size="2" value="<?php echo $_POST['dataEncerramentoM'];?>" name="dataEncerramentoM" />
/
<input id="dataEncerramentoA" class="" type="text" onchange="" maxlength="4" size="4" value="<?php echo $_POST['dataEncerramentoA'];?>" name="dataEncerramentoA" /></td>
      </tr>
            </table>

            </div>



    </fieldset>
    <div align="center" class="row">
            <input type="submit" id="button1" value="Salvar" />
            </div>
    <br />
    <br />
</form>

<?php
require_once "$BASE_DIR/includes/rodape.php";
?>