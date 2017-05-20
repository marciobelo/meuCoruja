<?php
require_once("./includes/comum.php");
require_once("$BASE_DIR/classes/Curso.php");
require_once("$BASE_DIR/classes/MatriculaAluno.php");
require_once("$BASE_DIR/classes/Aluno.php");

$curso = Curso::obterCurso("TASI");
$ranking = Curso::obterRankingCRDoCurso($curso);

$pos=1;
echo "<html><body><table border='1'><thead><td>Pos.</td><td>Matricula</td><td>Nome</td><td>CR</td><tbody>";
foreach ($ranking as $matricula => $cr) {
    if($matricula !=  0) {
        echo "<tr>";
        echo "<td>" . $pos++ . "</td>";
        echo "<td>" . $matricula . "</td>";
        $objMatricula = MatriculaAluno::obterMatriculaAluno($matricula);
        $nome = strtoupper($objMatricula->getAluno()->getNome());
        echo "<td>" . $nome . "</td>";
        echo "<td>" . $cr . "</td>";
        echo "</tr>";
    }




}
echo "</tbody></table></body></html>";
