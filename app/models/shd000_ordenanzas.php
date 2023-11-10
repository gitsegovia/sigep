<?php

 class shd000_ordenanzas extends AppModel{
	var $name = 'shd000_ordenanzas';
	var $useTable = 'shd000_ordenanzas';


    public function ano($condicion){
    	$data = parent::execute("SELECT ano_arranque FROM shd000_ordenanzas WHERE ".$condicion);
    	if(count($data)>0){
            return  $data[0][0]['ano_arranque'];
    	}else{
    		echo "<script>
    			   $('msj_texto_hacienda').innerHTML='<br/><h3>Por favor, registre el año de arranque para el Módulo de Hacienda</h3>';
                   Control.Modal.open(document.getElementById('contenido_tabla_NOTA_HACIENDA').innerHTML);
                   menu_activo();
                 </script>";
            exit();
    	}

    }


}
?>