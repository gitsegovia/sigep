function valida_cpop01_proyectos(){

  var proyecto = document.getElementById('proyecto').value;
  proyecto = proyecto.replace(/\./g, '');
  proyecto = proyecto.trim();

  var responsable = document.getElementById('responsable').value;
  responsable = responsable.replace(/\./g, '');
  responsable = responsable.trim();

	if(proyecto=="" || proyecto < 2){

			fun_msj('Ingrese la Descripción del Proyecto');
			document.getElementById('proyecto').focus();
			return false;

	}else if(responsable=="" || responsable < 2){
      fun_msj('Ingrese el Profesional Responsable');
      document.getElementById('responsable').focus();
      return false;
  }else{
		return true;
	}

}