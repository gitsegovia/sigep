<?php

  class Cfpd01_formulacion extends AppModel{
	var $name = 'cfpd01_formulacion';
	var $useTable = 'cfpd01_formulacion';



	public function ano_formulacion ($condicion) {
         $c = parent::findCount($condicion);
         if($c!=0){
         	$d = parent::findAll($condicion,'ano_formular');
         	return $d[0]['cfpd01_formulacion']['ano_formular'];
         }else{
            echo "<script>
    			   $('msj_texto_hacienda').innerHTML='<br/><h3>Por favor, registre el año de formulación</h3>';
                   Control.Modal.open(document.getElementById('contenido_tabla_NOTA_HACIENDA').innerHTML);
                   menu_activo();
                 </script>";
            exit();
         }
	}//fin funcion ano_formulacion

}
?>
