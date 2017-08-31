<?php

    $detalhesFaltas = array(
            array(
                    'disciplina' => 'AL1',
                    'data' => '10/11/2016',
                    'quantidade' => 3,
                    'periodo'=>2016.2
            ),
            array(
                'disciplina' => 'AL1',
                'data' => '17/10/2016',
                'quantidade' => 1,
                'periodo'=>2016.2
            ),
            array(
                'disciplina' => 'MAT',
                'data' => '17/09/2016',
                'quantidade' => 4,
                'periodo'=>2016.2
            )
        
            );

    $jsonDetalhesFaltas = json_encode($detalhesFaltas);

    echo $jsonDetalhesFaltas;
    //header('location:detalhamentoFaltas.php');
?>