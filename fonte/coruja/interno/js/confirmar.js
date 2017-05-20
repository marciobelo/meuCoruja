function Confirma(msn) {
var confirma = confirm(msn)
if (confirma){
document.form.ok.value='excluir';
document.form.submit();
return true
} else {
return false
} 
}

function ConfirmaCart(msn) {
var confirma = confirm(msn)
if (confirma){
document.form.ok.value='true';
document.form.submit();
return true
} else {
return false
} 
}
