<?php
require_once "$BASE_DIR/classes/Curso.php";
require_once "$BASE_DIR/classes/MatriculaAluno.php";
require_once "$BASE_DIR/classes/PeriodoLetivo.php";
require_once "$BASE_DIR/classes/FormaIngresso.php";
require_once "$BASE_DIR/classes/MatrizCurricular.php";
require_once "$BASE_DIR/classes/Util.php";

class ManterMatriculaForm {
    
    // Campos de edição
    public $idPessoa;
    public $matriculaAlunoAntiga="";
    public $matriculaAlunoNova;
    public $siglaCurso="";
    public $dataMatriculaD;
    public $dataMatriculaM;
    public $dataMatriculaA;
    public $dataConclusaoD;
    public $dataConclusaoM;
    public $dataConclusaoA;
    public $turnoIngresso;
    public $idPeriodoLetivo;
    public $idMatriz;
    public $concursoPontos;
    public $concursoClassificacao;
    public $idFormaIngresso;
    public $concluido;

    public $modo; // 'edicao' ou 'novo'

    // Listas de apoio
    public $cursos;
    public $periodosLetivo;
    public $formasIngresso;
    public $matrizes;
    
    public function getConcursoPontos() {
       return str_replace(",", ".", $this->concursoPontos);
    }

   /*
    * Construtor da Classe
    */
    function __construct($modo) {

        // Preenche lista auxiliar de cursos disponíveis
        $this->cursos = Curso::obterCursosOrdemPorSigla();

        // Preenche lista auxiliar de formas de ingresso
        $this->formasIngresso = FormaIngresso::obterFormasIngresso();

        $this->modo=$modo;
    }

    public function atualizarDadosMatricula( $idPessoa, $matriculaAluno) {
        $this->idPessoa = $idPessoa;
        $ma = MatriculaAluno::obterMatriculaAluno($matriculaAluno);
        $this->matriculaAlunoAntiga = $ma->getMatriculaAluno();
        $this->matriculaAlunoNova = $ma->getMatriculaAluno();
        $this->siglaCurso = $ma->getSiglaCurso();
        $this->dataMatriculaD=date("d",strtotime($ma->getDataMatricula()));
        $this->dataMatriculaM=date("m",strtotime($ma->getDataMatricula()));
        $this->dataMatriculaA=date("Y",strtotime($ma->getDataMatricula()));
        if($ma->getDataConclusao()==null) {
            $this->dataConclusaoD = "";
            $this->dataConclusaoM = "";
            $this->dataConclusaoA = "";
        } else {
            $this->dataConclusaoD=date("d",strtotime($ma->getDataConclusao()));
            $this->dataConclusaoM=date("m",strtotime($ma->getDataConclusao()));
            $this->dataConclusaoA=date("Y",strtotime($ma->getDataConclusao()));
        }
        $this->turnoIngresso=$ma->getTurnoIngresso();
        $this->idPeriodoLetivo=$ma->getIdPeriodoLetivo();
        $this->idMatriz=$ma->getIdMatriz();
        $this->concursoPontos=number_format($ma->getConcursoPontos(),2,",",".");
        $this->concursoClassificacao=$ma->getConcursoClassificacao();
        $this->idFormaIngresso=$ma->getIdFormaIngresso();

        if($ma->getSituacaoMatricula()=="CONCLUÍDO") {
            $this->concluido = true;
        } else {
            $this->concluido = false;
        }

        // Preenche lista auxiliar de períodos letivos
        $this->periodosLetivo = PeriodoLetivo::obterPeriodosLetivoPorSiglaCurso($this->siglaCurso);

        // Preenche lista auxiliar de matrizes do curso
        $this->matrizes = MatrizCurricular::obterListaMatrizCurricularPorSiglaCurso($this->siglaCurso);
    }

    public function atualizarDadosForm() {
        $this->modo = $_POST["modo"];
        $this->idPessoa = $_POST["idPessoa"];
        $this->matriculaAlunoAntiga = $_POST["matriculaAlunoAntiga"];
        $this->matriculaAlunoNova = $_POST["matriculaAlunoNova"];
        $this->siglaCurso = $_POST["siglaCurso"];
        $this->idMatriz = $_POST["idMatriz"];
        $this->dataMatriculaD = $_POST["dataMatriculaD"];
        $this->dataMatriculaM = $_POST["dataMatriculaM"];
        $this->dataMatriculaA = $_POST["dataMatriculaA"];
        $this->dataConclusaoD = $_POST["dataConclusaoD"];
        $this->dataConclusaoM = $_POST["dataConclusaoM"];
        $this->dataConclusaoA = $_POST["dataConclusaoA"];
        $this->turnoIngresso = $_POST["turnoIngresso"];
        $this->idPeriodoLetivo = $_POST["idPeriodoLetivo"];
        $this->concursoPontos = $_POST["concursoPontos"];
        $this->concursoClassificacao = $_POST["concursoClassificacao"];
        $this->idFormaIngresso = $_POST["idFormaIngresso"];
        
        $this->concluido = false;
        if($this->modo=="edicao") {
            $ma = MatriculaAluno::obterMatriculaAluno($this->matriculaAlunoAntiga);
            if($ma->getSituacaoMatricula()=="CONCLUÍDO") {
                $this->concluido = true;
            }
        }

        // Preenche lista auxiliar de cursos disponíveis
        $this->cursos = Curso::obterCursosOrdemPorSigla();

        // Preenche lista auxiliar de períodos letivos
        $this->periodosLetivo = PeriodoLetivo::obterPeriodosLetivoPorSiglaCurso($this->siglaCurso);

        // Preenche lista auxiliar de formas de ingresso
        $this->formasIngresso = FormaIngresso::obterFormasIngresso();

        // Preenche lista auxiliar de matrizes do curso
        $this->matrizes = MatrizCurricular::obterListaMatrizCurricularPorSiglaCurso($this->siglaCurso);

    }

    public function getDataMatricula() {
        return $this->dataMatriculaA . "-" . $this->dataMatriculaM . "-" . $this->dataMatriculaD;
    }

    public function getDataConclusao() {
        if(empty($this->dataConclusaoA) && empty($this->dataConclusaoM) && empty($this->dataConclusaoD) ) {
            return "";
        } else  {
            return $this->dataConclusaoA . "-" . $this->dataConclusaoM . "-" . $this->dataConclusaoD;
        }
    }

    public function validarDados() {
        $msgsErro = array();

        // Valida campo matrícula
        if(empty($this->matriculaAlunoNova) || trim($this->matriculaAlunoNova)=="") {
            array_push($msgsErro, "A matrícula não pode ser vazia.");
        }

        // Valida campo data da matrícula
        if(!checkdate($this->dataMatriculaM, $this->dataMatriculaD, $this->dataMatriculaA)) {
            array_push($msgsErro, "Data de matrícula incorreta.");
        }

        // Valida campo data da matrícula
        if($this->concluido && !checkdate($this->dataConclusaoM,
                    $this->dataConclusaoD, $this->dataConclusaoA)) {
            array_push($msgsErro, "Data de conclusão deve estar preenchida.");
        }

        // Se estiver preenchido, valida o campo data conclusão
        if($this->dataConclusaoD != "" ||
            $this->dataConclusaoM != "" ||
            $this->dataConclusaoA != "") {
            if(!checkdate($this->dataConclusaoM,
                    $this->dataConclusaoD, $this->dataConclusaoA)) {
                array_push($msgsErro, "Data de conclusão incorreta.");
            }
        }

        // Valida se a sigla do curso está preenchida
        if(empty($this->siglaCurso)) {
            array_push($msgsErro, "O curso deve ser indicado.");
        }

        // Valida se o campo concurso pontos é ok
        if( (!is_numeric($this->getConcursoPontos())) && (!empty($this->concursoPontos)) ) {
            array_push($msgsErro, "O campo Concurso Pontos está incorreto.");
        }

        // Valida se o campo concurso classificação é ok
        if( (!is_numeric($this->concursoClassificacao)) && (!empty($this->concursoClassificacao)) ) {
            array_push($msgsErro, "O campo Concurso Classificação está incorreto.");
        }

        return $msgsErro;
    }
}
?>
