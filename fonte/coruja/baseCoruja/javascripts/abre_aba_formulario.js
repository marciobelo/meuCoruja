function abreAba(num) {
	$("#conteudo_abas > div").hide();
	$("#conteudo_abas > div:eq(" + (num-1) + ")").fadeIn();
	$("#abas > a").css("background", "url(/coruja/baseCoruja/imagens/aba.jpg) top left no-repeat");
	$("#abas > a:eq(" + (num-1) + ")").css("background", "url(/coruja/baseCoruja/imagens/aba_selecionada.jpg) top left no-repeat");
}