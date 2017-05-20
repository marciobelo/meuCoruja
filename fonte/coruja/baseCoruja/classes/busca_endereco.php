<?php
// BUSCA O ENDEREÇO PELO CEP DIGITADO
	echo file_get_contents('http://cep.republicavirtual.com.br/web_cep.php?cep='.$_GET['cep'].'&formato=javascript');
?>