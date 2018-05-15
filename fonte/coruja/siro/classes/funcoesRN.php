<?php
require_once "$BASE_DIR/classes/Pessoa.php";
require_once "$BASE_DIR/classes/Curso.php";
require_once "$BASE_DIR/classes/Turma.php";
require_once "$BASE_DIR/classes/PeriodoLetivo.php";
require_once "$BASE_DIR/classes/Inscricao.php";
require_once "$BASE_DIR/classes/MatriculaAluno.php";
require_once "$BASE_DIR/classes/Aluno.php";
require_once "$BASE_DIR/classes/ComponenteCurricular.php";

class funcoesRN {

    private $inscricao;

    public function __construct() {
        $this->inscricao = new Inscricao();
    }

    public function RN08($manterInscricaoTurma, $matriculaAluno) {
        $colideRN08=$this->inscricao->verificaConflito($manterInscricaoTurma, $matriculaAluno);
        return $colideRN08;
    }

    //OBTEM A TURMA QUE COLIDE - RN08
    public function obterTurmaRN08($colideRN08,$idTurma,$situacaoInscricao,$matriculaAluno,$idPeriodoLetivo) {
        if($colideRN08) {
            $turmaColide=array(array(),array());
            $turmaColide=$this->inscricao->obterTurmaComConflitoHorario($idTurma, $matriculaAluno, $situacaoInscricao,$idPeriodoLetivo);
            //OBTENDO OS TEMPOS QUE COLIDE PARA A RN08
            $tempoColide=array();
            $tColide = array();
            for($i=0;$i<count($turmaColide);$i++) {
                $tempoColide[]=$turmaColide[$i]["tempo"];
                $tColide[]=$turmaColide[$i]["turma"];
            }

            $RN08= array();
            $RN08['turma']=$tColide;
            $RN08['tempo']=$tempoColide;

            return  $RN08;
        }
        //FIM DA VERIFICACAO DA RN08
        return NULL;
    }

    public function mensagemRN08() {
        $mensagem=htmlspecialchars("Um aluno s� pode ser inscrito em duas ou mais turmas colidindo um ou ".
                "mais tempos semanais de aulas, no mesmo per�odo letivo, do mesmo ".
                "curso, com a expressa anu�ncia do Coordenador Acad�mico, que deve ".
                "justificar sua autoriza��o.", ENT_QUOTES, "iso-8859-1");
        return $mensagem;
    }

    public function mensagemRN08Curta() {
        $mensagem=htmlspecialchars("Colis�o de hor�rio:", ENT_QUOTES, "iso-8859-1");
        return $mensagem;
    }

    public function RN09($idTurma, $matriculaAluno) {
        //VARIAVEIS
        $quitacaoTO; // UTILIZADA PARA OBTER A INFORMA��O SE UM COMPONENTE CURRICULAR FOI CUMPRIDO
        $listaComponente = array(); // UTILIZADO PARA GUARDAR AS INFORMACOES DOS CCs NAO CUMPRIDOS

        $ma = MatriculaAluno::obterMatriculaAluno($matriculaAluno);
        $turma=Turma::getTurmaById($idTurma);
        $componenteRN= new ComponenteCurricular($turma->getSiglaCurso(), $turma->getIdMatriz(), $turma->getSiglaDisciplina());
        //  $cumpreRequisitosRN09=true;
        $turmaPreRequisitos=$componenteRN->obterPreRequisitos();
        //VERIFICA SE O ALUNO QUITOU OS PR�-REQUISITOS
        foreach ($turmaPreRequisitos as $turmaPre) {
            // PARA CADA TURMA PR� , OBTEM O COMPONENTE CURRICULAR ASSOCIADO
            $cc = ComponenteCurricular::obterComponenteCurricular($turmaPre->getSiglaCurso(), $turmaPre->getIdMatriz(), $turmaPre->getSiglaDisciplina());
            // VERIFICA SE O ALUNO CUMPRIU O PR�-REQUISITO;
            $quitacaoTO = $cc->obterQuitacao($ma);
            if(empty ($quitacaoTO)) {
                $listaComponente[]=$cc;
            }
        }
        return $listaComponente;

    }

    /*@param = Lista de componentes curriculares para serem exibidos ao final da mensagem
     * 
    */
    public function mensagemRN09($listaComponente) {
        $mensagem=htmlspecialchars("Um aluno s� pode ser inscrito em uma turma para qual ".
                "n�o possua pr�-requisitos, no mesmo per�odo letivo, do mesmo curso, ".
                "com a expressa anu�ncia do Coordenador Acad�mico, que deve ".
                "justificar sua autoriza��o. ", ENT_QUOTES, "iso-8859-1");
        $mensagem.=htmlspecialchars("No caso da turma solicitada falta cumprir : ", ENT_QUOTES, "iso-8859-1");
        foreach($listaComponente as $cc) {
            $mensagem.=htmlspecialchars($cc->getSiglaDisciplina()."; ", ENT_QUOTES, "iso-8859-1");
        }

        return $mensagem;
    }

    public function mensagemRN09Curta($listaComponente) {
        $mensagem=htmlspecialchars("Um aluno n�o pode ser inscrito em uma turma que ".
                "n�o possua pr�-requisitos. No caso da turma solicitada falta cumprir : ", ENT_QUOTES, "iso-8859-1");
        foreach($listaComponente as $cc) {
            $mensagem.=htmlspecialchars($cc->getSiglaDisciplina()."; ", ENT_QUOTES, "iso-8859-1");
        }
        return $mensagem;
    }

    public function RN10($matriculaAluno,$idTurma) 
    {
        $turma=Turma::getTurmaById($idTurma);
        $alunoRF_RN10=$this->inscricao->obterTurmasQueAlunoReprovou($matriculaAluno, $turma->getSiglaDisciplina(),"'RF'", $turma->getIdPeriodoLetivo()-1);
        return $alunoRF_RN10;
    }

    public function mensagemRN10() {
        $mensagem=htmlspecialchars("Um Aluno s� pode ser inscrito em uma Turma ".
                "de uma disciplina na qual ele foi reprovado por falta ".
                "no per�odo letivo imediatamente anterior, do mesmo curso, ".
                "com a expressa anu�ncia do Coordenador Acad�mico, ".
                "que deve justificar sua autoriza��o. ", ENT_QUOTES, "iso-8859-1");
        return $mensagem;
    }

    public function mensagemRN10Curta() {
        $mensagem=htmlspecialchars("Aluno reprovado por falta no per�odo anterior ao atual.", ENT_QUOTES, "iso-8859-1");
        return $mensagem;
    }

    public function RN11($matriculaAluno,$idTurma) {

        $turma=Turma::getTurmaById($idTurma);
        $contaRN11=0;
        $alunoRN11=false;
        for($i=1;$i<$turma->getIdPeriodoLetivo();$i++) {
            $alunoRN11=$this->inscricao->obterTurmasQueAlunoReprovou($matriculaAluno, $turma->getSiglaDisciplina(),"'RF'".","."'RM'", $i);
            if($alunoRN11) {
                $contaRN11++;
            }
        }

        return $contaRN11;

    }

    public function mensagemRN11() {
        $mensagem=htmlspecialchars("Um Aluno s� pode ser inscrito em um Turma ".
                "de uma disciplina na qual ele foi reprovado 3 vezes ou mais ".
                "(seja por falta ou por m�dia), do mesmo curso, ".
                "com a expressa anu�ncia do Coordenador Acad�mico, ".
                "que deve justificar sua autoriza��o.", ENT_QUOTES, "iso-8859-1");
        return $mensagem;
    }

    public function mensagemRN11Curta() {
        $mensagem=htmlspecialchars("Aluno excedeu limite de reprova��es, ".
                "procurar Coordenador Acad�mico.", ENT_QUOTES, "iso-8859-1");
        return $mensagem;
    }

    public function RN12($matr) 
    {
        $matriculaAluno = MatriculaAluno::obterMatriculaAluno( $matr);
        $alunoRN12 = $matriculaAluno->verificaMatriculaAlunoExcedeTempo();
        return $alunoRN12;
    }

    public function mensagemRN12() {
        $mensagem=htmlspecialchars("Um Aluno cuja matr�cula exceda 5 (cinco) anos ".
                "s� poder� ter sua solicita��o de inscri��o em turma ".
                "aceita com a expressa anu�ncia do Coordenador Acad�mico.", ENT_QUOTES, "iso-8859-1");
        return $mensagem;
    }

    public function mensagemRN12Curta() {
        $mensagem=htmlspecialchars("Tempo de matr�cula excedido, procurar secretaria.", ENT_QUOTES, "iso-8859-1");
        return $mensagem;
    }

    /*
     * Casos de Uso: UC02.06; UC02.01;
     * @return: false -> O aluno e do mesmo turno da turma solicitada;
     * @return: true -> O aluno nao eh do mesmo turno da turma solicitada;
    */
    public function RN22($matr,$idTurma) 
    {
        $matriculaAluno = MatriculaAluno::obterMatriculaAluno( $matr);
        $turma=Turma::getTurmaById($idTurma);

        if($matriculaAluno->getTurnoIngresso()!=$turma->getTurno())
        {
            $tIngressoRN22 = true;
        }
        else 
        {
            $tIngressoRN22 = false;
        }
        return $tIngressoRN22;
    }

    public function mensagemRN22() {
        $mensagem=htmlspecialchars("Um Aluno s� pode ser inscrito em uma Turma ".
                "ofertada em turno diferente do de ingresso (vinculado � matr�cula) ".
                "com a expressa anu�ncia do Coordenador Acad�mico, que deve justificar sua autoriza��o. ".
                "Obs.: O aluno somente obter� deferimento se houver vaga na turma solicitada, ".
                "ap�s ser dada prefer�ncia a todos os alunos que s�o do turno da turma.", ENT_QUOTES, "iso-8859-1");

        return $mensagem;
    }

    public function mensagemRN22Curta() {
        $mensagem=htmlspecialchars("A turma solicitada n�o pertence ao turno de ingresso do aluno.", ENT_QUOTES, "iso-8859-1");
        return $mensagem;
    }

}