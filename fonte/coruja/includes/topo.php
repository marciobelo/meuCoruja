<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
    <title>Coruja</title>
    <link href="/coruja/estilos/estilo.css" rel="stylesheet" type="text/css" media="all" />
    <link href="/coruja/estilos/estilo_menu_horizontal.css" rel="stylesheet" type="text/css" media="screen" />
    <link href="/coruja/estilos/estilo_form.css" rel="stylesheet" type="text/css" media="all" />
    <link href="/coruja/estilos/calendar.css" rel="stylesheet" type="text/css" media="screen" />
    <link href="/coruja/estilos/jquery-ui/jquery-ui.min.css" rel="stylesheet" type="text/css" media="screen" />

    <link href="/coruja/baseCoruja/estilos/estilo_form_turma.css" rel="stylesheet" type="text/css" media="screen" />
    <link href="/coruja/baseCoruja/estilos/estilo_aba_formulario.css" rel="stylesheet" type="text/css" media="screen" />

    <link rel="shortcut icon" href="/coruja/imagens/favicon.ico" />

    <script type='text/javascript' src='/coruja/siro/javascripts/funcoes.js'></script>
    <script type='text/javascript' src='/coruja/siro/javascripts/calendar.js'></script>
    <script type="text/javascript" src="/coruja/javascript/jquery-1.7.1.min.js"></script>
    <script type="text/javascript" src="/coruja/javascript/jquery-ui.min.js"></script>
    <script src='/coruja/interno/js/Validacaoform.js'></script>
    <script type="text/javascript">
    $(function()
    {
        $("#dialog_filtro_curso").dialog({
            autoOpen: false,
            height: 400,
            width: 400,
            modal: true,
            buttons: {
                "Ok": function()
                {
                    var siglaCursoSelecionado = $("input[name=filtroCurso]:checked", "#body_lista_cursos").val();
                    $.ajax({
                        url: "/coruja/interno/manter_curso/manterCurso_controle.php",
                        type: "POST",
                        data: 
                        {
                            acao: "selecionarFiltroCursoAJAX",
                            siglaCursoFiltro: siglaCursoSelecionado
                        },
                        success: function (data, textStatus, jqXHR) 
                        {
                            $("#filtroCursoAtual").val( siglaCursoSelecionado);
                        },
                        error: function (jqXHR, textStatus, errorThrown) 
                        {
                            console.log( jqXHR);
                            console.log( textStatus);
                            console.log( errorThrown);
                            alert( "Erro ao selecionar o curso");
                        }
                    });
                    $(this).dialog("close");
                }
            }
        });
        $("#menu_curso_pre_selecao").on("click", function()
        {
            $.ajax({
                url: "/coruja/interno/manter_curso/manterCurso_controle.php",
                type: 'POST',
                data: 
                {
                    acao: "obterCursosAJAX"
                },
                success: function (data, textStatus, jqXHR) 
                {
                    siglasCursos = JSON.parse( data);
                    var filtroCursoAtual = $("#filtroCursoAtual").val();
                    var listaCursos = $("#body_lista_cursos");
                    listaCursos.empty();
                    var todosChecked = "";
                    if( filtroCursoAtual === "")
                    {
                        todosChecked="checked";
                    }
                    listaCursos.append( $("<tr><td>(TODOS)</td><td><input type='radio' name='filtroCurso' value='' " + todosChecked + "/></td></tr>"));
                    siglasCursos.forEach(function(value, index, arr) 
                    {
                        var cursoChecked = "";
                        if( filtroCursoAtual === value)
                        {
                            cursoChecked = "checked";
                        }
                        listaCursos.append( $("<tr><td>" + value + "</td><td><input type='radio' name='filtroCurso' value='" + value + "' " +
                                cursoChecked + "/></td></tr>"));
                    });
                    $("#dialog_filtro_curso").dialog("open");
                },
                error: function (jqXHR, textStatus, errorThrown) 
                {
                    console.log( jqXHR);
                    console.log( textStatus);
                    console.log( errorThrown);
                    alert( "Erro ao selecionar o curso");
                }
            });
        });
    });
    </script>
</head>

<body>
    <div id="topo">
        <?php
        // PEGA O NOME DE USUÁRIO E IDPESSOA DO USUÁRIO LOGADO
        $login = $_SESSION["login"];
        $nomeAcesso = $login->getNomeAcesso();
        $loginEmail = $login->getEmail();
        $avisosNaoLidos = $login->obterQtdeAvisosNaoLidos();
        
        $cursoFiltro = "";
        if( isset($_SESSION["siglaCursoFiltro"]))
        {
            $cursoFiltro = $_SESSION["siglaCursoFiltro"];
        }
        ?>
        <div id="topo_instituicao" style="float: left; padding-left: 100px; padding-top: 20px;">
            <img src="/coruja/imagens/logorj.jpg" />
        </div>
        <div id="nome_instituicao" style="text-align: left; padding-top: 50px;">
            <span>&nbsp;&nbsp;<?php echo Config::INSTITUICAO_NOME_COMPLETO; ?></span>
        </div>

        <?php
        if( $login->getPerfil() == Login::ADMINISTRADOR ) {
            
        ?>
        <div id="curso_pre_selecao">
            <span>Filtro Curso</span>
            <br/>
            <input id="filtroCursoAtual" type="text" readonly="true" value="<?php echo $cursoFiltro; ?>" size="6" />
            <button id="menu_curso_pre_selecao">&nbsp;</button>
        </div>
        <?php
        }
        ?>
        <div id="aviso_coruja">
            <a href="/coruja/interno/quadroAvisos/index_controle.php?acao=exibir"><img src="/coruja/imagens/mail_icon.png" /></a>
            <?php if( $avisosNaoLidos > 0 ) { ?>
            <a href="/coruja/interno/quadroAvisos/index_controle.php?acao=exibir">
                <img src="<?php echo "/coruja/imagens/number_" . ( $avisosNaoLidos > 9 ? "many" : $avisosNaoLidos ) . "_icon.png" ?>" id="qtde_aviso_coruja" />
            </a>
            <?php } ?>
        </div>
       
        <?php
        
        // EXIBE A FOTO DO USUÁRIO
        echo "<div class='usuariologado_foto'>";
        echo "<img src='/coruja/baseCoruja/controle/obterFotoLogado_controle.php' width='100' height='90' border=0 alt='$nomeAcesso' />";
        echo "</div>";
        ?>
        <?php
        echo "<div class='usuariologado_info'>";
        echo "$nomeAcesso ($loginEmail) ";
        echo "<br />";
        echo "( <a href='/coruja/interno/manter_login/manterLogin_controle.php?acao=exibirTrocaSenha'>Alterar Senha</a> | <a href='/coruja/autenticar/login_controle.php?acao=sair'>Sair</a> )";
        echo "</div>";
        ?>                    
    </div>
    
    <div id="dialog_filtro_curso" title="Selecione um Filtro">
        <table>
            <tbody id="body_lista_cursos"></tbody>
        </table>
    </div>