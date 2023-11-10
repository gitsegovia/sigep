function cpop01_filosofia_gestion(){

  var mision = document.getElementById('mision').value;
  mision = mision.replace(/\./g, '');
  mision = mision.trim();

  var vision = document.getElementById('vision').value;
  vision = vision.replace(/\./g, '');
  vision = vision.trim();

  if(mision == "" || mision < 2){

			fun_msj('Ingrese la Misión');
			document.getElementById('mision').focus();
			return false;

  }else if(vision == "" || vision < 2){

			fun_msj('Ingrese la Visión');
			document.getElementById('vision').focus();
			return false;

}else{

			fun_msj2('Fue Almacenado el Registro');

		}

}


function mensajes_cpop01_filosofica_gestion_eliminar(){

	fun_msj('Fue Eliminado el Registro');

}