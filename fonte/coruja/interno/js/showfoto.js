function preview(img,foto) {
	var extensao;
	var iLen = String(foto).length;
    extensao = String(foto).substring(iLen, iLen - 4);
    
	if ((extensao == '.jpg') || (extensao == '.JPG') || (extensao == 'jpeg') || (extensao == 'JPEG')) {
		var largura = 80; //Largura da Imagem de Preview!
		var altura = 100; //Altura da Imagem de Preview!
		img.src=foto;
		img.width=largura;
		img.height=altura;
	}
	else {
		alert('Formato de imagem inválido');
	}

}