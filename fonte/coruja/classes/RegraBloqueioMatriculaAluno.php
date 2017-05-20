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
            $this->textoCompleto = "Aluno excedeu o tempo m�ximo permitido. "
                    . "O curso " .
                    $siglaCurso . " deve ser integralizado em no m�ximo " . 
                    $tempoMax . " meses, " .
                    "mas a matr�cula j� levou " . $tempoCursado . " meses.";
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
 * matr�cula com ao menos duas reprova��es no mesmo componente e ainda pendente
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
        return "duas reprova��es";
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
            throw new Exception("Regra $idRegra n�o identificada.");
        }
    }
    
    public abstract function getTextoCompleto();
    
    public abstract function getTextoResumidoMotivo();
    
    /**
     * Indica se a matr�cula deve ser bloqueada ou n�o
     * @return boolean Indica se violou a regra ou n�o
     */
    public abstract function deveBloquear();
}