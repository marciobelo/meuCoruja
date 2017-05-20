<?php
/* 
* $gateway    : armazena endereço do gateway do AMFPHP.
* $parametros : armazena os parâmetros que serão enviados ao Flash, onde este enviará junto com a 
                foto para a função saveDataToFile no AMFPHP.
* flashvars   : armazena os parâmetros que o flash utiliza internamente 
* movie       : armazena o caminho do WebCamBiblio.swf
*/

$gateway    = "/coruja/interno/manter_login/webcambiblio/amf/gateway.php";
$idPessoa   = $_GET['idPessoa'];
$parametros = $idPessoa . ";";
  
//echo $idPessoa.' - '.$nomeAcesso;
  
?>	
<!-- WebCamBiblio >> -->
	<script type="text/javascript" src="WebCamBiblio.js"></script>
	<script language="JavaScript" type="text/javascript">
		AC_FL_RunContent(
			'codebase', 'http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=10,0,0,0',
			'width', '480',
			'height', '260',
			'src', 'WebCamBiblio',
			'quality', 'high',
			'pluginspage', 'http://www.adobe.com/go/getflashplayer',
			'align', 'middle',
			'play', 'true',
			'loop', 'true',
			'scale', 'showall',
			'wmode', 'window',
			'devicefont', 'false',
			'id', 'WebCamBiblio',
			'bgcolor', '#ffffff',
			'name', 'WebCamBiblio',
			'menu', 'true',
			'allowFullScreen', 'false',
			'allowScriptAccess','sameDomain',
			// UrlAmfGateway: variável com o caminho de onde está o gateway para a comunicação com o AMFPHP
			'flashvars','UrlAmfGateway=<?php echo $gateway; ?>&parametros=<?php echo $parametros ?>',
			'movie', 'WebCamBiblio', 
			'salign', ''
		); //end AC code
		
		function finaliza() {
		  window.opener.location.reload();
		  window.close();
		}
	</script>
	<noscript>
		<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=10,0,0,0" width="480" height="240" id="WebCamBiblio" align="middle">
		<param name="allowScriptAccess" value="sameDomain" />
		<param name="allowFullScreen" value="false" />
		<param name="movie" value="WebCamBiblio.swf" /><param name="quality" value="high" /><param name="bgcolor" value="#ffffff" />	<embed src="WebCamBiblio.swf" quality="high" bgcolor="#ffffff" width="480" height="240" name="WebCamBiblio" align="middle" allowScriptAccess="sameDomain" allowFullScreen="false" type="application/x-shockwave-flash" pluginspage="http://www.adobe.com/go/getflashplayer" />
		</object>
	</noscript>
<!-- WebCamBiblio << -->

