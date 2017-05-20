$(document).ready(function() {
	$("#conteudo_abas > div").hide();
	$("#conteudo_abas > div:eq(0)").show();
	$("#abas > a:eq(0)").css("background", "url(imagens/aba_selecionada.jpg) top left no-repeat");
});
 
function abreAba(num) {
	$("#conteudo_abas > div").hide();
	$("#conteudo_abas > div:eq(" + (num-1) + ")").fadeIn();
	$("#abas > a").css("background", "url(imagens/aba.jpg) top left no-repeat");
	$("#abas > a:eq(" + (num-1) + ")").css("background", "url(imagens/aba_selecionada.jpg) top left no-repeat");	
}