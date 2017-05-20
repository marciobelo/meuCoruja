<?php
require_once("$BASE_DIR/classes/MatriculaAluno.php");
require_once("$BASE_DIR/classes/Curso.php");

class ExcedeuTempoMaximoCurso extends RegraBloqueioMatriculaAluno
{
    private $deveBloquear = false;
    private $textoCompleto;
    
    public function __construct(MatriculaAluno $matriculaAluno)
    {
        $siglaCurso = $matriculaAluno->getSiglaCurso();
        $curso = Curso::obterCurso( $siglaCurso);
        $tempoMax = $curso->getTempoMaximoIntegralizacaoEmMeses();
        $tempoCursado = $matriculaAluno->obterTempoCursoEmMeses();
        
        if( $tempoCursado >= $tempoMax)
        {
            $this->deveBloquear = true;
            $this->textoCompleto = "Aluno excedeu o tempo máximo permitido. "
                    . "O curso " .
                    $siglaCurso . " deve ser integralizado em no máximo " . 
                    $tempoMax . " meses, " .
                    "mas a matrícula já levou " . $tempoCursado . " meses.";
        }
    }
    
    public function getTextoResumidoMotivo() 
    {
        return "tempo excedido";
    }

    public function deveBloquear() 
    {
        return $this->deveBloquear;
    }

    public function getTextoCompleto() 
    {
        return $this->textoCompleto;
    }
}

/**
 * matrícula com ao menos duas reprovações no mesmo componente e ainda pendente
 */
class ReprovadoDuasVezes extends RegraBloqueioMatriculaAluno 
{
    private $deveBloquear = false;
    private $textoCompleto;
    private $disciplinas = array();
    
    public function __construct(MatriculaAluno $matriculaAluno)
    {
        $ccPendentes = $matriculaAluno->obterComponentesCurricularPendentes();
        foreach( $ccPendentes as $pendente )
        {
            if( $pendente->obterQtdeReprovacoes( $matriculaAluno) >= 2 )
            {
                $this->deveBloquear = true;
                $this->disciplinas[] = $pendente->getSiglaDisciplina();
            }
        }
        if( $this->deveBloquear )
        {
            $this->textoCompleto = "Reprovado ao menos duas vezes na(s) disciplina(s): " . implode(", ", $this->disciplinas);
        }
    }
    
    public function deveBloquear() 
    {
        return $this->deveBloquear;
    }

    public function getTextoCompleto() 
    {
        return $this->textoCompleto;
    }

    public function getTextoResumidoMotivo() 
    {
        return "duas reprovações";
    }
}

abstract class RegraBloqueioMatriculaAluno 
{
    public static function getInstancia( $idRegra, MatriculaAluno $matriculaAluno) 
    {
        
        if( $idRegra === "ExcedeuTempoMaximoCurso")
        {
            return new ExcedeuTempoMaximoCurso( $matriculaAluno);
        }
        else if( $idRegra === "ReprovadoDuasVezes")
        {
            return new ReprovadoDuasVezes( $matriculaAluno);
        }
        else
        {
            throw new Exception("Regra $idRegra não identificada.");
        }
    }
    
    public abstract function getTextoCompleto();
    
    public abstract function getTextoResumidoMotivo();
    
    /**
     * Indica se a matrícula deve ser bloqueada ou não
     * @return boolean Indica se violou a regra ou não
     */
    public abstract function deveBloquear();
}