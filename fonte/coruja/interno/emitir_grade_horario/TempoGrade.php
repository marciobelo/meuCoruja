<?php
require_once "$BASE_DIR/classes/Turma.php";
require_once "$BASE_DIR/classes/Espaco.php";

class TempoGrade {
    private $turma;
    private $espaco;
    private $horaInicio;
    private $horaFim;
    
    public function __construct(Turma $turma, Espaco $espaco, $horaInicio, $horaFim ) {
        $this->espaco = $espaco;
        $this->turma = $turma;
        $this->horaInicio = $horaInicio;
        $this->horaFim = $horaFim;
    }
    
    public function getTurma() {
        return $this->turma;
    }

    public function getEspaco() {
        return $this->espaco;
    }
    
    public function getHoraInicio() {
        return $this->horaInicio;
    }

    public function getHoraFim() {
        return $this->horaFim;
    }
}