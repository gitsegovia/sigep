<?php

 class shd000_arranque extends AppModel{
	var $name = 'shd000_arranque';
	var $useTable = 'shd000_arranque';


    public function ano($condicion){
    	$data = parent::execute("SELECT ano_arranque FROM shd000_arranque WHERE ".$condicion);
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

    public function mes($condicion){
    	$data = parent::execute("SELECT mes_arranque FROM shd000_arranque WHERE ".$condicion);
   		return  $data[0][0]['mes_arranque'];
    }

    public function actualizacion($condicion,$cod_ingreso){
    	$data = parent::execute("SELECT ano_arranque,mes_arranque FROM shd000_arranque WHERE ".$condicion);
    	$data2 = parent::execute("SELECT denominacion FROM shd003_codigo_ingresos WHERE cod_ingreso=$cod_ingreso");
    	$meses=array(1=>'enero',2=>'febrero',3=>'marzo',4=>'abril',5=>'mayo',6=>'junio',7=>'julio',8=>'agosto',9=>'septiembre',10=>'octubre',11=>'noviembre',12=>'diciembre');
        if(count($data)>0){
        	$r[0]=$data[0][0]['ano_arranque'];
        	$r[1]=$data[0][0]['mes_arranque'];
        	$r[2]=$meses[$data[0][0]['mes_arranque']];
        	if(count($data2)>0){
        		$r[3]=$data2[0][0]['denominacion'];
        	}else{
        		 echo "<script>
    			   $('msj_texto_hacienda').innerHTML='<br/><h2>TIPO DE INGRESO NO REGISTRADO</h2>';
                   Control.Modal.open(document.getElementById('contenido_tabla_NOTA_HACIENDA').innerHTML);
                   menu_activo();
                 </script>";
                exit();
        	}

        	/**
        	 if($data[0][0]['mes_arranque']!=date('m')){
                echo "<script>
    			   $('msj_texto_hacienda').innerHTML='<br/><h2>MES NO CORRESPONDE AL PROCESO</h2>';
                   Control.Modal.open(document.getElementById('contenido_tabla_NOTA_HACIENDA').innerHTML);
                   menu_activo();
                 </script>";
                exit();
        	}
        	/**/

            return  $r;
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