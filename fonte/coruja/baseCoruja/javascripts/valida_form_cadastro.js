function validaForm(){
    var form = document.getElementById("cadastro");

    if (form.nome.value == "") {
        alert("O campo nome deve ser preenchido!");
        form.nome.focus();
        return false;
    }

    if (form.dataNascimentoD.value == ""){
        alert("O dia do nascimento deve ser preenchido!");
        form.dataNascimentoD.focus();
        return false;
    }

    if (form.dataNascimentoM.value == ""){
        alert("O campo m�s de nascimento deve ser preenchido!");
        form.dataNascimentoM.focus();
        return false;
    }

    if (form.dataNascimentoA.value == ""){
        alert("O ano de nascimento deve ser preenchido!");
        form.dataNascimentoA.focus();
        return false;
    }

    if (form.nacionalidade.value == ""){
        alert("O campo nacionalidade deve ser preenchido!");
        form.nacionalidade.focus();
        return false;
    }

    if (form.naturalidade.value == ""){
        alert("O campo naturalidade deve ser preenchido!");
        form.naturalidade.focus();
        return false;
    }
	
    if (form.enderecoCEP.value == ""){
        alert("CEP deve ser preenchido!");
        form.enderecoCEP.focus();
        return false;
    }

    if (form.enderecoLogradouro.value == ""){
        alert("O endere�o deve ser preenchido!!");
        form.enderecoLogradouro.focus();
        return false;
    }

    if (form.enderecoNumero.value == ""){
        alert("O n�mero do endere�o deve ser preenchido!!");
        form.enderecoNumero.focus();
        return false;
    }

    if (form.enderecoBairro.value == ""){
        alert("O bairro deve ser preenchido!!");
        form.enderecoBairro.focus();
        return false;
    }
    if (form.enderecoMunicipio.value == ""){
        alert("O munic�pio deve ser preenchido!!");
        form.enderecoMunicipio.focus();
        return false;
    }
    if (form.enderecoEstado.value == ""){
        alert("O estado deve ser preenchido!!");
        form.enderecoEstado.focus();
        return false;
    }
    if (form.telefoneResidencial.value == ""){
        alert("O telefone residencial deve ser preenchido!!");
        form.telefoneResidencial.focus();
        return false;
    }
    
    if (form.email.value == ""){
        alert("O email deve ser preenchido!!");
        form.email.focus();
        return false;
    }

    if (form.estadoCivil.value == ""){
        alert("O estado civil deve ser preenchido!!");
        form.estadoCivil.focus();
        return false;
    }
    
    if (form.corRaca.value == ""){
        alert("Cor/ra�a deve ser preenchido!");
        form.corRaca.focus();
        return false;
    }
    
    if (form.rgNumero.value == ""){
        alert("O RG deve ser preenchido!!");
        form.rgNumero.focus();
        return false;
    }

    //validar rg
    if (form.rgOrgaoEmissor.value == ""){
        alert("O �rg�o emissor do RG deve ser preenchido!!");
        form.rgOrgaoEmissor.focus();
        return false;
    }

    if (form.rgDataEmissaoD.value == ""){
        alert("A data de emiss�o do RG deve ser preenchida!");
        form.rgDataEmissaoD.focus();
        return false;
    }

    if (form.rgDataEmissaoM.value == ""){
        alert("A data de emiss�o do RG deve ser preenchida!!");
        form.rgDataEmissaoM.focus();
        return false;
    }

    if (form.rgDataEmissaoA.value == ""){
        alert("A data de emiss�o do RG deve ser preenchida!!");
        form.rgDataEmissaoA.focus();
        return false;
    }

    if (form.cpfNumero.value == "" ){
        alert("O CPF deve ser preenchido!!");
        form.cpf.focus();
        return false;
    }
    
    if (form.nomeMae.value == ""){
        alert("O nome da m�e deve ser preenchido!!");
        form.nomeMae.focus();
        return false;
    }
    
    if (form.estabCursoOrigem.value == ""){
        alert("O nome do estabelecimento de origem deve ser preenchido!!");
        form.estabCursoOrigem.focus();
        return false;
    }
    if (form.estabCursoOrigemUF.value == ""){
        alert("A UF do estabelecimento de origem deve ser preenchida!!");
        form.estabCursoOrigemUF.focus();
        return false;
    }

    if(form.modalidadeCursoOrigem.value == ""){
        alert("A modalidade do curso de origem deve ser preenchida!!");
        form.modalidadeCursoOrigem.focus();
        return false;
    }

    if(form.cursoOrigemAnoConclusao.value == ""){
        alert("O ano de conclus�o do curso de origem deve ser preenchido!!");
        form.cursoOrigemAnoConclusao.focus();
        return false;
    }

    if( form.novaMatriculaAluno.value=="" || isNaN(form.novaMatriculaAluno.value) ) {
        alert("Matr�cula do aluno � inv�lida!");
        form.novaMatriculaAluno.focus();
        return false;
    }

    if( form.dataNovaMatriculaD.value=="" || 
        form.dataNovaMatriculaM.value=="" ||
        form.dataNovaMatriculaA.value=="") {
        alert("A data da matr�cula do aluno � inv�lida!");
        form.dataNovaMatriculaD.focus();
        return false;
    }

    if( form.siglaCursoNovaMatricula.value=="") {
        alert("O curso da matr�cula do aluno deve ser preenchido!");
        form.siglaCursoNovaMatricula.focus();
        return false;
    }

    if(form.turnoIngressoNovaMatricula.value=="") {
        alert("O turno da matr�cula do aluno deve ser preenchido!");
        form.turnoIngressoNovaMatricula.focus();
        return false;
    }

    if(form.idFormaIngressoNovaMatricula.value=="") {
        alert("A forma de ingresso da matr�cula do aluno deve ser preenchido!");
        form.idFormaIngressoNovaMatricula.focus();
        return false;
    }

    if(form.concursoPontosNovaMatricula.value!="" && isNaN(form.concursoPontosNovaMatricula.value)) {
        alert("A pontua��o no concurso da matr�cula do aluno est� incorreto!");
        form.concursoPontosNovaMatricula.focus();
        return false;
    }

    if(form.concursoClassificacaoNovaMatricula.value!="" && isNaN(form.concursoClassificacaoNovaMatricula.value)) {
        alert("A classica��o no concurso da matr�cula do aluno est� incorreta!");
        form.concursoClassificacaoNovaMatricula.focus();
        return false;
    }

    return true;
}

    
    
/*    
//validar MATRICULA
    if (d.idPeriodoLetivo.value == ""){
        alert("Ainda existem campos obrigatórios não preenchidos!");
        return false;
    }
//validar 
    if (d.siglaCurso.value == ""){
        alert("Ainda existem campos obrigatórios não preenchidos!");
        return false;
    }
//validar 
    if (d.idMatriz.value == ""){
        alert("Ainda existem campos obrigatórios não preenchidos!");
        return false;
    }
//validar 
    if (d.idFormaIngresso.value == ""){
        alert("Ainda existem campos obrigatórios não preenchidos!");
        return false;
    }
//validar 
    if (d.matriculaAluno.value == ""){
        alert("Ainda existem campos obrigatórios não preenchidos!");
        return false;
    }
*/    
    
    
    //validar sexo
    //if (!d.sexo[0].checked && !d.sexo[1].checked) {
    //    alert("Escolha o sexo!");
    //    return false;
    //}
    //    return true;
    //}