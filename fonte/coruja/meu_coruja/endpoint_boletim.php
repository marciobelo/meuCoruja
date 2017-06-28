<?php
/*{
	boletim:
	[
		{"nomeDisciplina":"AL1", "av1":7, "av2":5, "media":8, "avf":6, "mediaFinal":8, "professor":"Leonardo", "descricaoDisciplina":"Algoritmo e Linguagem de Programação", "faltas":10, "faltasMax":30}
	]
}
*/


$boletim = array('disciplinas' => array(
	array(
		'nome' => 'AL1',
		'av1' => 7.0,
		'av2' => 5.0,
		'media' =>8.0,
		'avf' =>6.0,
		'mediaFinal'=>8.0,
		'professor'=>'Leonardo',
		'descricaoDisciplina'=>'Algoritmo e Linguagem de Programação',
		'faltas'=>10,
		'faltasMax'=>30
	),
		array(
		'nome' => 'AL2',
		'av1' => 7.0,
		'av2' => 5.0,
		'media' =>8.0,
		'avf' =>6.0,
		'mediaFinal'=>8.0,
		'professor'=>'Leonardo',
		'descricaoDisciplina'=>'Algoritmo e Linguagem de Programação',
		'faltas'=>10,
		'faltasMax'=>30
	)
	));
	
$jsonBoletim = json_encode($boletim);

echo $jsonBoletim;
?>