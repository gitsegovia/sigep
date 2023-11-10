function valida_cpop03_organigrama(){

  var fundamento_legal = document.getElementById('fundamento_legal').value;
  fundamento_legal = fundamento_legal.replace(/\./g, '');
  fundamento_legal = fundamento_legal.trim();

  if(fundamento_legal == "" || fundamento_legal < 2){
      fun_msj('Debe Ingresar el Fundamento Legal de Aprobación del Organigrama');
      document.getElementById('fundamento_legal').focus();
      return false;
  }

  return true;
}