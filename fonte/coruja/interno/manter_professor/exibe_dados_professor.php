<?php
require_once "../../includes/comum.php";
require_once "$BASE_DIR/includes/topo.php";
require_once "$BASE_DIR/includes/menu_horizontal.php";
?>
<script type="text/javascript" src="/coruja/baseCoruja/javascripts/carrega_div.js"></script>

<!-- seção Mensagens de erro, se houver -->
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
<!-- fim mensagens de erro -->

<form id="cadastro" method="post" name="cadastro" action="/coruja/interno/manter_professor/manterProfessor_controle.php" >

    <fieldset id="fieldsetGeral">
        <input type="hidden" name="acao" value="preparaEdicaoProfessor" />
        <input type="hidden" name="idPessoa" value="<?php echo $professor->getIdPessoa(); ?>" />
        <input type="hidden" name="pessoa" value="<?php echo $professor->getNome(); ?>" />
        <input type="hidden" name="matriculaProfessor" value="<?php echo $_REQUEST['matriculaProfessor']; ?>" />


        <legend><?php echo $controleDestinoTitulo; ?>
            DADOS PESSOAIS DO PROFESSOR <br /><?php echo strtoupper($professor->getNome()); ?></legend>
        <br />

        <div class="row" id="didfv2" >
            <table width="871">
                <tr>
                    <td width="46">Nome:</td>
                    <td width="361"><b><?php echo $professor->getNome(); ?></b></td>
                    <td width="58">Sexo:</td>
                    <td width="179"><b><?php echo $professor->getSexo(); ?></b></td>
                    <td width="75">Data Nasc. </td>
                    <td width="124"><b><?php echo Util::dataSQLParaBr($professor->getDataNascimento()); ?></b></td>
                </tr>
            </table>

            <table width="873">
                <tr>
                    <td width="121">Nacionalidade : </td>
                    <td width="314"><b><?php echo $professor->getNacionalidade(); ?></b></td>
                    <td width="100">Naturalidade :</td>
                    <td width="318"><b><?php echo $professor->getNaturalidade(); ?></b></td>
                </tr>
            </table>
            <table width="871">
                <tr>
                    <td width="39">CEP :</td>
                    <td width="107"><b><?php echo $professor->getEnderecoCEP(); ?></b></td>
                    <td width="71">Endere&ccedil;o :</td>
                    <td width="300"><b><?php echo $professor->getEnderecoLogradouro(); ?></b></td>
                    <td width="56">Bairro :</td>
                    <td width="270"><b><?php echo $professor->getEnderecoBairro(); ?></b></td>
                </tr>
            </table>
            <table width="895">
                <tr>
                    <td width="76">Munic&iacute;pio :</td>
                    <td width="385"><b><?php echo $professor->getEnderecoMunicipio(); ?></b></td>
                    <td width="55"><label for="estado">Estado :</label></td>
                    <td width="359"><b><?php echo $professor->getEnderecoEstado(); ?></b></td>
                </tr>
            </table>
            <table width="1000">
                <tr>
                    <td width="114">Tel. Residencial :</td>
                    <td width="121"><b><?php echo $professor->getTelefoneResidencial(); ?></b></td>
                    <td width="105">Tel. Comercial : </td>
                    <td width="128"><b><?php echo $professor->getTelefoneComercial(); ?></b></td>
                    <td width="101">Tel. Celular : </td>
                    <td width="135"><b><?php echo $professor->getTelefoneCelular(); ?></b></td>
                    <td width="64">E-mail :</td>
                    <td width="196"><b><?php echo $professor->getEmail(); ?></b></td>
                </tr>
            </table>
            <table width="645">
                <tr>
                    <td width="112">Titula&ccedil;&atilde;o : </td>
                    <td width="107"><b><?php echo $professor->getTitulacaoAcademica(); ?></b></td>
                    <td width="98">Lattes :</td>
                    <td width="120"><b><?php echo $professor->getCvLattes(); ?></b></td>
                </tr>
                <tr>
                    <td width="112">Nome de Guerra : </td>
                    <td width="107"><b><?php echo $professor->getNomeGuerra(); ?></b></td>
                    <td width="98">Cor :</td>
                    <td width="120" bgcolor="#<?php echo $professor->getCorFundo(); ?>"><b>#<?php echo $professor->getCorFundo(); ?></b></td>
                </tr>
            </table>

        </div>

    </fieldset>

    <div align="center" class="row">
        <input type="submit" id="button1" value="Editar Dados Professor" />
    </div>
    <br/>

    <fieldset id="fieldsetGeral">
        <input type="hidden" name="acaoMatricula" value="exibirResultado" />
        <legend><?php echo $controleDestinoTitulo; ?><br/>
            MATRÍCULAS DO PROFESSOR</legend>
        <div class="row" id="didfv1" >
            <table width="1000">
                <?php foreach ($professor->getMatriculasProfessor() as $matriculaProf) {
 ?>
                    <tr>
                        <td width="90">Matr&iacute;cula: </td>
                        <td width="70"><b><?php echo $matriculaProf->getMatriculaProfessor(); ?></b></td>
                        <td width="98">Carga Hor&aacute;ria:</td>
                        <td width="40"><b><?php echo $matriculaProf->getCargaHoraria(); ?></b></td>
                        <td width="94">Data de In&iacute;cio:</td>
                        <td width="85"><b><?php echo Util::dataSQLParaBr($matriculaProf->getDataInicio()); ?></b></td>
                        <td width="120">Data Encerramento:</td>
                        <td width="70"><b><?php echo Util::dataSQLParaBr($matriculaProf->getDataEncerramento()); ?></b></td>
                        <td width="152"><input type="submit" id="button1" value="Editar Matricula" onclick="document.cadastro.acao.value='preparaEdicaoMatricula';document.cadastro.matriculaProfessor.value='<?php echo $matriculaProf->getMatriculaProfessor(); ?>';" /></td>

                    </tr>
<?php } ?>
            </table>

            <p align="left"><input type="submit" id="button1" value="Nova Matricula" onclick="document.cadastro.acao.value='preparaNovaMatricula'" /></p>
        </div>
    </fieldset>
    <br />
</form>

<?php
                require_once "$BASE_DIR/includes/rodape.php";
?>