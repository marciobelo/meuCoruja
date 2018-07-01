<?php
$BASE_DIR = ".."; // indica o caminho dos fontes relativo ao diretório onde estão estes testes

require_once "$BASE_DIR/config.php";
require_once "$BASE_DIR/classes/RegraBloqueioMatriculaAluno.php";

class RegraBloqueioMatriculaAlunoTest extends PHPUnit_Framework_TestCase 
{
    
    public function testExcedeuTempoMaximoCurso() 
    {
        $matriculaAluno = MatriculaAluno::obterMatriculaAluno("1328");
        $matriculaAluno->setDataMatricula( "2011-01-01");
        $regra = RegraBloqueioMatriculaAluno::getInstancia("ExcedeuTempoMaximoCurso", $matriculaAluno);
        
        $this->assertTrue( $regra->deveBloquear() );
        $this->assertTrue( strpos( $regra->getTextoCompleto(), 
                "Aluno excedeu o tempo máximo permitido.") !== false);
    }
    
    public function testReprovadoDuasVezesPositivo()
    {
        $matriculaAluno = MatriculaAluno::obterMatriculaAluno("1329"); // Nelson
        $regra = RegraBloqueioMatriculaAluno::getInstancia("ReprovadoDuasVezes", $matriculaAluno);
        $this->assertEquals("Reprovado ao menos duas vezes na(s) disciplina(s): AL2, INT", $regra->getTextoCompleto());
    }
}
