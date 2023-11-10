<?php
/*
 * Fecha: 19/07/2007
 *
 * Por Erisk G. Aragol H.
 *
 * Herramienta utilizada: easyEclipse
 * sisap
 */
class Cnmp09RegistroLunesAnoVigenteController extends AppController {
   var $name = 'cnmp09_registro_lunes_ano_vigente';
   var $uses = array('Cnmd01',  'cnmd03_transacciones', 'cnmd09_lunes_ejercicio','v_cnmd09_lunes_ejercicio','ccfd04_cierre_mes');
   var $helpers = array('Html','Ajax','Javascript', 'Sisap');


function checkSession()
    {
        // If the session info hasn't been set...
        if (!$this->Session->check('Usuario'))
        {
            // Force the user to login
            $this->redirect('/salir');
            exit();
        }
    }



 function beforeFilter(){
 	$this->checkSession();

 }


function index($cod=null){
	$this->layout="ajax";

	$ano=$this->ano_ejecucion();
	$this->set('ano',$ano);

	$ver=$this->cnmd09_lunes_ejercicio->execute('select * from cnmd09_lunes_ejercicio where ano='.$ano);
	if($ver!=null){
		$this->ver($ano);
		$this->render('ver');
	}


}



function guardar(){
	$this->layout="ajax";
	$ano=$this->data['cnmp09']['ano'];
if(!$this->cnmd09_lunes_ejercicio->FindAll("ano=".$ano)){
	for($i=1;$i<13;$i++){
		$lunes=$this->data['cnmp09']['lunes'.$i];
		$sql_insert = "INSERT INTO cnmd09_lunes_ejercicio VALUES(".$ano.",".$i.",".$lunes.")";
		$sw1 = $this->cnmd09_lunes_ejercicio->execute($sql_insert);
	}
	if($sw1>1){
		$this->set('Message_existe','registro exitoso');
	}
}else{
	$this->set('errorMessage','ya existe un registro para este año');
}

	$this->index();
	$this->render('index');
}//fin guardar




function eliminar($ano=null,$pagina=null){
 		$this->layout="ajax";
if($this->cnmd09_lunes_ejercicio->FindAll("ano=".$ano)){
		$sql = "DELETE FROM cnmd09_lunes_ejercicio WHERE ano=".$ano;
		if($this->cnmd09_lunes_ejercicio->execute($sql)>1){
			$this->set('Message_existe', 'EL REGISTRO FUE ELIMINADO EXITOSAMENTE');
			$Tfilas=$this->cnmd09_lunes_ejercicio->findCount();
				if($Tfilas!=0){
					$this->consultar($pagina);
					$this->render("consultar");
				}else{
					$this->index();
					$this->render("index");
				}
		}else{
			$this->set('errorMessage', 'EL REGISTRO NO PUDO SER ELIMINADO');
			if(isset($pagina)){
				$this->consultar($pagina);
				$this->render("consultar");
			}else{
				$this->ver($ano);
				$this->render("ver");
			}

		}
}else{
	$this->set('errorMessage', 'no existe registro para este año');
	if(isset($pagina)){
		$this->consultar($pagina);
		$this->render("consultar");
	}else{
		$this->ver($ano);
		$this->render("ver");
	}

}

 }//eliminar




function consultar($pagina=null) {
	$this->layout="ajax";

	if(isset($pagina)){
		$Tfilas=$this->v_cnmd09_lunes_ejercicio->findCount();
        if($Tfilas!=0){
        	$x=$this->v_cnmd09_lunes_ejercicio->findAll(null,null,"ano ASC",1,$pagina,null);

            $this->set('DATA',$x);
            $this->set('pagina',$pagina);
          	$this->set('siguiente',$pagina+1);
          	$this->set('anterior',$pagina-1);
            $this->bt_nav($Tfilas,$pagina);
            $this->set('numT',$Tfilas);
			$this->set('numP',$pagina);
        }else{
	 	       $this->set('errorMessage', 'No se encontrar&oacute;n datos');
	 	       $this->set('noExiste',true);
	 	       $this->index();
			   $this->render("index");
			   return;
        }

	}else{
		$pagina=1;
		$Tfilas=$this->v_cnmd09_lunes_ejercicio->findCount();

        if($Tfilas!=0){
        	$x=$this->v_cnmd09_lunes_ejercicio->findAll(null,null,"ano ASC",1,$pagina,null);
			$this->set('DATA',$x);
			$this->set('pagina',$pagina);
          	$this->set('siguiente',$pagina+1);
          	$this->set('anterior',$pagina-1);
          	$this->bt_nav($Tfilas,$pagina);
          	$this->set('numT',$Tfilas);
			$this->set('numP',$pagina);

        }else{
	 	       $this->set('errorMessage', 'No se encontrar&oacute;n datos');
	 	       $this->set('noExiste',true);
	 	       $this->index();
			   $this->render("index");
			   return;
        }
	}

	$ano= $x[0]["v_cnmd09_lunes_ejercicio"]["ano"];
	$sql="SELECT * from cnmd09_lunes_ejercicio where ano='$ano' order by mes asc";
	$result=$this->cnmd09_lunes_ejercicio->execute($sql);
	$this->set('datos',$result);
	$this->set('ano',$ano);

 }//consultar



 function bt_nav($Tfilas,$pagina){
    if($Tfilas==1){
                $this->set('mostrarS',false);
                $this->set('mostrarA',false);
          	}else if($Tfilas==2){
          		if($pagina==2){
                   $this->set('mostrarS',false);
                   $this->set('mostrarA',true);
          		}else{
          		   $this->set('mostrarS',true);
                   $this->set('mostrarA',false);
          		}
          	}else if($Tfilas>=3){
          		if($pagina==$Tfilas){
                     $this->set('mostrarS',false);
                     $this->set('mostrarA',true);
          		}else if($pagina==1){
          			 $this->set('mostrarS',true);
                     $this->set('mostrarA',false);
          		}else{
          			 $this->set('mostrarS',true);
                     $this->set('mostrarA',true);
          		}
          	}
 }//fin navegacion



function ver($ano){
	$this->layout="ajax";
	$sql="SELECT * from cnmd09_lunes_ejercicio where ano='$ano' order by mes asc";
	$result=$this->cnmd09_lunes_ejercicio->execute($sql);
	$this->set('datos',$result);
	$this->set('ano',$ano);

}



function verifica($ano){
	$this->layout="ajax";
	$sql="SELECT * from cnmd09_lunes_ejercicio where ano='$ano' order by mes asc";
	$result=$this->cnmd09_lunes_ejercicio->execute($sql);
	if($result!=null){
		$this->ver($ano);
		$this->render('ver');
	}else{
		$this->set('ano',$ano);
	}

}





 function modificar($ano=null,$nombre=null,$numero=null,$lunes=null,$pagina=null,$id=null){
 	$this->layout="ajax";


	$this->set('ano', $ano);
	$this->set('nombre', $nombre);
	$this->set('numero', $numero);
	$this->set('id', $id);
	$this->set('lunes', $lunes);
	$this->set('pagina', $pagina);

 }//fin modificar




function guardar_modificar($ano=null,$num=null,$pagina=null,$id=null){
	$this->layout="ajax";
	//pr($this->data);
	    $numero=$this->data['cnmp09']['lunes'.$id];
			$v=$this->cnmd09_lunes_ejercicio->execute("update cnmd09_lunes_ejercicio set numero_lunes='$numero' where ano=".$ano." and mes=".$num);
				$this->set('Message_existe','EL REGISTRO SE MODIFICO EXITOSAMENTE');
				$this->consultar($pagina);
				$this->render('consultar');


 }//guardar modificar


 function cancelar($ano){
	$this->layout="ajax";
	$sql="SELECT * from cnmd09_lunes_ejercicio where ano='$ano'";
	$result=$this->cnmd09_lunes_ejercicio->execute($sql);
	$this->set('datos',$result);

 }



}//FIN CONTROLADOR
?>