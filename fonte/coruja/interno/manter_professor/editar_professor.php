<?php
require_once "$BASE_DIR/includes/topo.php";
require_once "$BASE_DIR/includes/menu_horizontal.php";
?>
<script type="text/javascript" src="/coruja/baseCoruja/javascripts/carrega_div.js"></script>
<script type="text/javascript" src="/coruja/interno/js/jscolor.js"></script>

<?php
if (count($msgsErro) > 0) {
?>
    <ul class="erro">
<?php
    foreach ($msgsErro as $msgErro) {
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

<form id="cadastro" method="post" name="cadastro" action="/coruja/interno/manter_professor/manterProfessor_controle.php" >

    <fieldset id="fieldsetGeral">
        <input type="hidden" name="acao" value="salvarProfessorEditado" />
        <input type="hidden" name="idPessoa" value="<?php echo $_REQUEST['idPessoa']; ?>" />


        <legend><?php echo $controleDestinoTitulo; ?><br/>
            ATUALIZAÇÃO DOS DADOS DO PROFESSOR</legend>
        <br />

        <div class="row" id="didfv2" >
            <font size="-1" color="#FF0000">Os campos marcados com (*) são obrigatórios!</font>
            <table width="927">
                <tr>
                    <td width="68">Nome:(*)</td>
                    <td width="312"><input id="nome" type="text" onchange="this.value=this.value.toUpperCase();" style="text-transform: uppercase;" maxlength="80" size="32" value="<?php echo htmlspecialchars($formProfessor->nome, ENT_QUOTES, "iso-8859-1"); ?>" name="nome"></td>
                    <td>Sexo:(*)
                        <input type="radio" checked="checked" value="M" name="sexo" <?php if($formProfessor->sexo=="M") echo "checked"; ?> />Masculino
                        <input type="radio" value="F" name="sexo" <?php if($formProfessor->sexo=="F") echo "checked"; ?> />Feminino
                    </td>
                    <td>Data Nasc  :(*)
                        <input id="dataNascimentoD" class="" type="text" onchange="" maxlength="2" size="2" value="<?php echo $formProfessor->dataNascimentoD; ?>" name="dataNascimentoD">
                        /
                        <input id="dataNascimentoM" class="" type="text" onchange="" maxlength="2" size="2" value="<?php echo $formProfessor->dataNascimentoM; ?>" name="dataNascimentoM">
                        /
                        <input id="dataNascimentoA" class="" type="text" onchange="" maxlength="4" size="4" value="<?php echo $formProfessor->dataNascimentoA; ?>" name="dataNascimentoA"></td>
                </tr>
            </table>

            <table width="937">
                <tr>
                    <td>Nacionalidade :(*)
                        <input id="nacionalidade" type="text" onchange="this.value=this.value.toUpperCase();" style="text-transform: uppercase;" maxlength="45" size="37" value="<?php echo $formProfessor->nacionalidade; ?>" name="nacionalidade"></td>
                    <td>Naturalidade :(*)
                        <input id="naturalidade" type="text" onchange="this.value=this.value.toUpperCase();" style="text-transform: uppercase;" maxlength="45" size="37" value="<?php echo $formProfessor->naturalidade; ?>" name="naturalidade"></td>
                </tr>
            </table>
            <table width="804">
                <tr>
                    <td width="170">CEP :
                        <input id="enderecoCEP" class="" type="text" onchange="" maxlength="9" size="8" value="<?php echo $formProfessor->enderecoCEP; ?>" name="enderecoCEP" /></td>
                    <td width="622">Endere&ccedil;o :
                        <input id="enderecoLogradouro" type="text" onchange="this.value=this.value.toUpperCase();" style="text-transform: uppercase;" maxlength="80" size="50" value="<?php echo $formProfessor->enderecoLogradouro; ?>" name="enderecoLogradouro" /></td>
                </tr>
            </table>
            <table width="920">
                <tr>
                    <td>Bairro :
                        <input id="enderecoBairro" type="text" onchange="this.value=this.value.toUpperCase();" style="text-transform: uppercase;" maxlength="60" size="30" value="<?php echo $formProfessor->enderecoBairro; ?>" name="enderecoBairro"></td>
                    <td>Munic&iacute;pio :
                        <input id="enderecoMunicipio" type="text" onchange="this.value=this.value.toUpperCase();" style="text-transform: uppercase;" maxlength="60" size="30" value="<?php echo $formProfessor->enderecoMunicipio; ?>" name="enderecoMunicipio"></td>
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
                        <input id="telefoneResidencial" class="" type="text" onchange="" maxlength="15" size="15" value="<?php echo $formProfessor->telefoneResidencial; ?>" name="telefoneResidencial"></td>
                    <td width="308">Tel. Comercial :
                        <input id="telefoneComercial" class="" type="text" onchange="" maxlength="15" size="15" value="<?php echo $formProfessor->telefoneComercial; ?>" name="telefoneComercial"></td>
                    <td width="279">Tel. Celular :
                        <input id="telefoneCelular" class="" type="text" onchange="" maxlength="15" size="15" value="<?php echo $formProfessor->telefoneCelular; ?>" name="telefoneCelular"></td>
                </tr>
            </table>
            <table width="916">
                <tr>
                    <td>E-mail :
                        <input id="email" class="" type="text" onchange="" maxlength="80" size="25" value="<?php echo $formProfessor->email; ?>" name="email" /></td>
                    <td>Titula&ccedil;&atilde;o :
                        <select id="titulacaoAcademica" name="titulacaoAcademica">
                            <option value="DOUTOR">DOUTOR</option>
                            <option value="MESTRE">MESTRE</option>
                            <option value="ESPECIALISTA">ESPECIALISTA</option>
                        </select>
                    <td>Lattes :
                        <input id="cvLattes" type="text" onchange="this.value=this.value.toUpperCase();" style="text-transform: uppercase;" maxlength="80" size="37" value="<?php echo $formProfessor->cvLattes; ?>" name="cvLattes" /></td>
                </tr>
                <tr>
                    <td>Nom de Guerra :
                        <input id="nomeGuerra" class="" type="text" onchange="this.value=this.value.toUpperCase();" maxlength="80" size="20" value="<?php echo $formProfessor->nomeGuerra; ?>" name="nomeGuerra" /></td>
                    <td>Cor:
                        <input id="corFundo" type="text" onchange="this.value=this.value.toUpperCase();" style="text-transform: uppercase;" maxlength="80" size="10" value="<?php echo $formProfessor->corFundo; ?>" name="corFundo" class="color" /></td>

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
<script>document.cadastro.titulacaoAcademica.value='<?php echo $formProfessor->titulacaoAcademica; ?>';</script>
<?php
require_once "$BASE_DIR/includes/rodape.php";
?>