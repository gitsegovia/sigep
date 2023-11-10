<?php
 class catd01_ano_ordenanza extends AppModel{
 	var $name='catd01_ano_ordenanza';
 	var $useTable='catd01_ano_ordenanza';

 	public function ano_actual ($condicion) {
	   $this->layout="ajax";
       $c = parent::findCount($condicion);
       $data = parent::findAll($condicion);
       if($c>0){
            return  $data[0]['catd01_ano_ordenanza']['ano_actual'];
    	}else{
    		echo "<script>
    			   $('msj_texto_hacienda').innerHTML='<br/><h3>Por favor, registre el año actual de la ordenanza</h3>';
                   Control.Modal.open(document.getElementById('contenido_tabla_NOTA_HACIENDA').innerHTML);
                   menu_activo();
                 </script>";
            exit();
    	}
	}//fin funcion ano_actual

 }
?>
