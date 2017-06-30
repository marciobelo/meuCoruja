<?php
/*{
	boletim:
	[
		{"nomeDisciplina":"AL1", "av1":7, "av2":5, "media":8, "avf":6, "mediaFinal":8, "professor":"Leonardo", "descricaoDisciplina":"Algoritmo e Linguagem de Programação", "faltas":10, "faltasMax":30}
	]
}
*/


$boletim = array(
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
		'av2' => 4,
		'media' =>8.5,
		'avf' =>7,
		'mediaFinal'=>8.5,
		'professor'=>'Miguel',
		'descricaoDisciplina'=>'Algoritmo e Linguagem de Programação 2',
		'faltas'=>15,
		'faltasMax'=>30
	),
                array(
                'nome' => 'RD1',
		'av1' => 6,
		'av2' => 5.5,
		'media' =>7,
		'avf' =>6.75,
		'mediaFinal'=>8.0,
		'professor'=>'Ferlin',
		'descricaoDisciplina'=>'Redes 1',
		'faltas'=>5,
		'faltasMax'=>30
                )
	);
	
$jsonBoletim = json_encode($boletim);

echo $jsonBoletim;
?>