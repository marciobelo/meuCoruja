<?php
require_once("../../includes/comum.php");
require_once("$BASE_DIR/includes/topo.php");
require_once("$BASE_DIR/includes/menu_horizontal.php");
$filename=$_REQUEST['mat'].".xml";
$path = Config::DIR_AREA_DADOS . "/" . $filename;
   if(file_exists($path)){
    $xml = simplexml_load_file($path);
   } 
?>
<link rel="stylesheet" href="/coruja/interno/css/DESIGN.CSS" media="screen" type="text/css" />
<script src="/coruja/interno/js/Validacaoform.js"></script>
<script src="/coruja/interno/js/formulario.js" type=text/javascript></script>
<IFRAME id=gToday:normal:agenda.js:ctyPopCalendario
style="Z-INDEX: 999; LEFT: -800px; VISIBILITY: visible; POSITION: absolute; TOP: 0px"
name=gToday:normal:agenda.js:ctyPopCalendario
src="/coruja/interno/view/pendencia/mostra.htm" frameBorder=0 width=174 scrolling=no
height=189></IFRAME>
<body>
<form id="form" name="form" method="post" action="/coruja/interno/emitir_hist_concl_controle/HistoricoConcluintesPDF.php" onsubmit="return validarRel(document.form);" >
    <input type="hidden" name="acao" value="exibirPDF" />
    <input type="hidden" name="mat" value="<?php echo $_GET['mat'];?>" />
<br />
<br />
  <table width="791" border="0" align="center">
    <tr>
      <td>DATA DE INÍCIO  : <span class="textos_red">
        <input name="dtini" type="text" class="form_obrigatorio" id="dtini"
		  onkeypress="return Mascaras_Format(document.form,'dtini','99/99/9999',event);" value="<?php echo $xml->historico->dtini; ?>" size="12" maxlength="10" title="Campo data de início &eacute; obrigat&oacute;rio" onBlur="return valida_data1(document.form.dtini,5);"/>
        <input name="button222" type="button" class="botaoCalendario"  title="Calend&aacute;rio" onClick="selectDate('form','dtini')"/>
      </span></td>
      <td>DATA DE TÉRMINO  : <span class="textos_red">
      <input name="dtfim" type="text" class="form_obrigatorio" id="dtfim"
		  onkeypress="return Mascaras_Format(document.form,'dtfim','99/99/9999',event);" value="<?php echo $xml->historico->dtfim; ?>" size="12" maxlength="10" title="Campo data de término &eacute; obrigat&oacute;rio" onBlur="return valida_data1(document.form.dtfim,5);"/>
      <input name="button2222" type="button" class="botaoCalendario"  title="Calend&aacute;rio" onClick="selectDate('form','dtfim')"/>
      </span></td>
    </tr>
    <tr>
        <td colspan="2">ESTABELECIMENTO: <span class="textos_red">
        <input name="estabelecimentoVestibular" type="text" class="form_obrigatorio" id="estabelecimentoVestibular"
		  value="<?php echo $xml->historico->estabelecimentoVestibular; ?>" size="20" maxlength="20" />
      </span></td>
    </tr>
    <tr>
      <td width="383">CARGA HOR&Aacute;RIA TOTAL DAS DISCIPLINAS EM AULAS : </td>
      <td width="398">
        <input name="chtda" type="text" class="form_obrigatorio" id="chtda" size="15" title="O campo C.H. total das disciplinas é obrigatório" value="<?php echo $xml->historico->chtda; ?>"/>      </td>
    </tr>
    <tr>
      <td>CARGA HOR&Aacute;RIA DE EST&Aacute;GIO SUPERVISIONADO : </td>
      <td><input name="ches" type="text" class="form_obrigatorio" id="ches" size="15" title="O campo C.H. de estágio supervisionado é obrigatório" value="<?php echo $xml->historico->ches; ?>"/></td>
    </tr>
    <tr>
      <td>CARGA HOR&Aacute;RIA DE ATIVIDADES DE EXTENS&Atilde;O CULTURAL : </td>
      <td><input name="chaec" type="text" class="form_obrigatorio" id="chaec" size="15" title="O campo C.H. de atividades de extensão cultural é obrigatório" value="<?php echo $xml->historico->chaec; ?>"/></td>
    </tr>
    <tr>
      <td>TOTAL DE C.H. DO CURSO : </td>
      <td><input name="tchc" type="text" class="form_obrigatorio" id="tchc" size="15" title="O campo total de carga horária do curso é obrigatório" value="<?php echo $xml->historico->tchc; ?>"/></td>
    </tr>
    <tr>
      <td colspan="2"><div align="center"><strong>TRABALHO DE CONCLUS&Atilde;O DE CURSO </strong></div></td>
    </tr>
    <tr>
      <td>T&Iacute;TULO :</td>
      <td><input name="titulo" type="text" id="titulo" size="50" class="form_obrigatorio" title="O campo título é obrigatório" value="<?php echo utf8_decode($xml->historico->titulo); ?>"/></td>
    </tr>
    <tr>
      <td>DATA DA DEFESA :</td>
      <td><span class="textos_red">
        <input name="dtdefesa" type="text" class="form_obrigatorio" id="dtdefesa"
		  onkeypress="return Mascaras_Format(document.form,'dtdefesa','99/99/9999',event);" value="<?php echo $xml->historico->dtdefesa; ?>" size="12" maxlength="10" title="Campo data da defesa &eacute; obrigat&oacute;rio" onBlur="return valida_data1(document.form.dtdefesa,5);"/>
        <input name="button22" type="button" class="botaoCalendario"  title="Calend&aacute;rio" onClick="selectDate('form','dtdefesa')"/>
      </span></td>
    </tr>
    <tr>
      <td>NOTA DO TRABALHO DE CONCLUS&Atilde;O DE CURSO :</td>
      <td><input name="ntcc" type="text" id="ntcc" size="15" class="form_obrigatorio" title="O campo nota do TCC é obrigatório" value="<?php echo $xml->historico->ntcc; ?>"/></td>
    </tr>
    <tr>
      <td>ENADE :</td>
      <td><input name="enade" type="text" class="form_obrigatorio" id="enade" size="50" title="O campo enade é obrigatório" value="<?php echo utf8_decode($xml->historico->enade); ?>"/></td>
    </tr>
    <tr>
      <td>DATA DA COLA&Ccedil;&Atilde;O DE GRAU :</td>
      <td><span class="textos_red">
        <input name="dtcolacao" type="text" class="form_obrigatorio" id="dtcolacao"
		  onkeypress="return Mascaras_Format(document.form,'dtcolacao','99/99/9999',event);" value="<?php echo $xml->historico->dtcolacao; ?>" size="12" maxlength="10" title="Campo data da colação de grau &eacute; obrigat&oacute;rio" onBlur="return valida_data1(document.form.dtcolacao,5);"/>
        <input name="button22" type="button" class="botaoCalendario"  title="Calend&aacute;rio" onClick="selectDate('form','dtcolacao')"/>
      </span></td>
    </tr>
    <tr>
      <td>DATA DA EXPEDI&Ccedil;&Atilde;O DIPLOMA :</td>
      <td><span class="textos_red">
        <input name="dtExpedicaoDiploma" type="text" class="form_obrigatorio" id="dtExpedicaoDiploma"
		  onkeypress="return Mascaras_Format(document.form,'dtcolacao','99/99/9999',event);" value="<?php echo $xml->historico->dtExpedicaoDiploma; ?>" size="12" maxlength="10" />
        <input name="button22" type="button" class="botaoCalendario"  title="Calend&aacute;rio" onClick="selectDate('form','dtExpedicaoDiploma')"/>
      </span></td>
    </tr>
     <tr>
      <td>DATA DE EMISSÃO :</td>
      <td><span class="textos_red">
        <input name="dtemissao" type="text" class="form_obrigatorio" id="dtemissao"
		  onkeypress="return Mascaras_Format(document.form,'dtemissao','99/99/9999',event);" value="<?php echo $xml->historico->dtemissao; ?>" size="12" maxlength="10" title="Campo data de emissão &eacute; obrigat&oacute;rio" onBlur="return valida_data1(document.form.dtemissao,5);"/>
        <input name="button22" type="button" class="botaoCalendario"  title="Calend&aacute;rio" onClick="selectDate('form','dtemissao')"/>
      </span></td>
    </tr>
    <tr>
      <td>OBSERVA&Ccedil;&Otilde;ES :</td>
      <td><textarea name="observacao" cols="40" rows="4" class="form_obrigatorio" id="observacao" ><?php echo utf8_decode($xml->historico->observacao); ?></textarea></td>
    </tr>
    <tr>
      <td>EXIBE CR?</td>
      <td><input type="checkbox" name="exibeCR" checked="checked" value="S"></td>
    </tr>
  </table>
  <table width="221" border="0" align="center">
    <tr class="tabela_form2">
      <td width="118"><input type="submit" name="salvar" class="botaoConfirmar" value="" />
        <input name="ok" type="hidden" id="ok" value=""/>
        <input name="mat" type="hidden" id="mat" value="<?php echo $_GET['mat'];?>"/></td>
      <td width="93"><input type="button" name="Limpar" onClick="document.getElementById('form').ok.value='cancelar'; submit();" class="botaoCancel" /></td>
    </tr>
  </table>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
</form>
<script>
document.form.dtini.value='<?php echo $_GET['dataInsc']; ?>';
document.form.dtemissao.value='<?php echo date('d/m/Y'); ?>';
</script>
<?php
require_once("$BASE_DIR/includes/rodape.php");
?>
</body>
</html>
