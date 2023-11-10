<?php

class shp002CobranzaPendienteController extends AppController{

    var $name    = "shp002_cobranza_pendiente";
    var $uses    = array('v_relacion_coradores', 'shd002_cobradores', 'shd002_cobranza_pendiente', "v_shd002_cobranza_pendiente");


    var $helpers = array('Html', 'Javascript', 'Ajax', 'Sisap');


function checkSession(){
				if (!$this->Session->check('Usuario')){
						$this->redirect('/salir/');
						exit();
				}else{
					$this->requestAction('/usuarios/actualizar_user');
				}
}//fin checksession



function beforeFilter(){$this->checkSession();}




function ventana_cobradores_1($var1=null){

 $this->layout="ajax";


$url                  =  "/shp002_cobranza_pendiente/ventana_cobradores_2/1";
$width_aux            =  "750px";
$height_aux           =  "400px";
$title_aux            =  "Buscar";
$resizable_aux        =  false;
$maximizable_aux      =  false;
$minimizable_aux      =  false;
$closable_aux         =  false;

    if($var1==2){
	         echo"<script>";
	           echo  "codigo_ventana('".$url."', '".$width_aux."', '".$height_aux."', '".$title_aux."', '".$resizable_aux."', '".$maximizable_aux."', '".$minimizable_aux."', '".$closable_aux."')";
	         echo"</script>";
    	}else{
              echo"<script>";
               echo  " Windows.close(document.getElementById('capa_ventana').value)";
              echo"</script>";

	}//fin else


}//fin function








function ventana_cobradores_2($var1=null, $pagina=null, $pista=null){
$this->layout="ajax";
$cod_presi                =       $this->Session->read('SScodpresi');
$cod_entidad              =       $this->Session->read('SScodentidad');
$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
$cod_inst                 =       $this->Session->read('SScodinst');
$cod_dep                  =       $this->Session->read('SScoddep');
       if($var1==1){
        $this->set("datos",'');
 }else if($var1==2){
    	            if(isset($pagina)){ $pagina=$pagina; }else{ $pagina=1; }
					if($pista!=null){
						  $this->Session->write('pista_buscar_cobrador_hacienda', $pista);
					}else{
					      $pista = $this->Session->read('pista_buscar_cobrador_hacienda');
					}//fin else
					$condicion = $this->condicion()." and (".$this->busca_separado(array("rif_ci","nombre_razon"), $pista).")  ";
		            $Tfilas=$this->shd002_cobradores->findCount($condicion);
				        if($Tfilas!=0){
				        	$Tfilas=(int)ceil($Tfilas/50);
				        	$this->set('total_paginas',$Tfilas);
							$this->set('pagina_actual',$pagina);
							$this->set('pag_cant',$pagina.'/'.$Tfilas);
							$this->set('ultimo',$Tfilas);
				     	    $datos_filas=$this->shd002_cobradores->findAll($condicion,null,"rif_ci, nombre_razon ASC",50,$pagina,null);
					        $this->set("datos",$datos_filas);
					        $this->set('siguiente',$pagina+1);
							$this->set('anterior',$pagina-1);
							$this->bt_nav($Tfilas,$pagina);
				        }else{
				        	$this->set("datos",'');
				        }
					$this->set("pista",$pista);
 }else  if($var1==3){
                    if(isset($pagina)){ $pagina=$pagina; }else{ $pagina=1; }
                    $year = 0;
					$condicion       = $this->condicion()." and rif_ci='".$pista."' and ano='".$year."' ";
					$condicion2      = $this->condicion()." and rif_ci='".$pista."'";
                    $datos_filas_aux = $this->shd002_cobradores->findAll($condicion2,null,"rif_ci, nombre_razon ASC");
		            $Tfilas=$this->v_shd002_cobranza_pendiente->findCount($condicion);
				        if($Tfilas!=0){
				        	$Tfilas=(int)ceil($Tfilas/1);
				        	$this->set('total_paginas',$Tfilas);
							$this->set('pagina_actual',$pagina);
							$this->set('pag_cant',$pagina.'/'.$Tfilas);
							$this->set('ultimo',$Tfilas);
				     	    $datos_filas=$this->v_shd002_cobranza_pendiente->findAll($condicion,null,"rif_ci ASC",1,$pagina,null);
					        $this->set("datos",$datos_filas);
					        $this->set('siguiente',$pagina+1);
							$this->set('anterior',$pagina-1);
							$this->bt_nav($Tfilas,$pagina);
				        }else{
				        	$this->set("datos",'');
				        }
		              if($Tfilas!=0){
			           $this->render("consulta");
			          }else{
			           $this->set("nombre_razon", $datos_filas_aux[0]["shd002_cobradores"]["nombre_razon"]);
			           $this->set("rif_ci",       $datos_filas_aux[0]["shd002_cobradores"]["rif_ci"]);
			           $this->set("year",         "");
			           $this->render("index_2");
			          }//fin else
 }//fin else
$this->set("opcion",$var1);
}//fin function






function ventana_cobradores_3($var1=null, $pagina=null, $pista=null){
$this->layout="ajax";
$cod_presi                =       $this->Session->read('SScodpresi');
$cod_entidad              =       $this->Session->read('SScodentidad');
$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
$cod_inst                 =       $this->Session->read('SScodinst');
$cod_dep                  =       $this->Session->read('SScoddep');
       if($var1==1){
        $this->set("datos",'');
 }else if($var1==2){
    	            if(isset($pagina)){$pagina=$pagina;}else{$pagina=1;}
					if($pista!=null){
						  $this->Session->write('pista_buscar_cobranza_pendiente_hacienda', $pista);
					}else{
					      $pista = $this->Session->read('pista_buscar_cobranza_pendiente_hacienda');
					}//fin else
					$condicion = $this->condicion()." and (".$this->busca_separado(array("rif_ci","nombre_razon"), $pista).")  ";
		            $Tfilas=$this->v_shd002_cobranza_pendiente->findCount($condicion);
				        if($Tfilas!=0){
				        	$Tfilas=(int)ceil($Tfilas/50);
				        	$this->set('total_paginas',$Tfilas);
							$this->set('pagina_actual',$pagina);
							$this->set('pag_cant',$pagina.'/'.$Tfilas);
							$this->set('ultimo',$Tfilas);
				     	    $datos_filas=$this->v_shd002_cobranza_pendiente->findAll($condicion,null,"rif_ci, nombre_razon ASC",50,$pagina,null);
					        $this->set("datos",$datos_filas);
					        $this->set('siguiente',$pagina+1);
							$this->set('anterior',$pagina-1);
							$this->bt_nav($Tfilas,$pagina);
				        }else{
				        	$this->set("datos",'');
				        }
					$this->set("pista",$pista);
 }//fin else
$this->set("opcion",$var1);
}//fin function



function buscar_year($rif_ci=null, $year=null){
$this->layout="ajax";
        if(isset($pagina)){ $pagina=$pagina; }else{ $pagina=1; }
		$condicion       = $this->condicion()." and rif_ci='".$rif_ci."' and ano='".$year."' ";
		$condicion_2     = $this->condicion()." and rif_ci='".$rif_ci."' ";
        $datos_filas_aux = $this->shd002_cobradores->findAll($condicion_2,null,"rif_ci, nombre_razon ASC");
        $Tfilas=$this->v_shd002_cobranza_pendiente->findCount($condicion);
	        if($Tfilas!=0){
	        	$Tfilas=(int)ceil($Tfilas/1);
	        	$this->set('total_paginas',$Tfilas);
				$this->set('pagina_actual',$pagina);
				$this->set('pag_cant',$pagina.'/'.$Tfilas);
				$this->set('ultimo',$Tfilas);
	     	    $datos_filas=$this->v_shd002_cobranza_pendiente->findAll($condicion,null,"rif_ci ASC",1,1,null);
		        $this->set("datos",$datos_filas);
		        $this->set('siguiente',$pagina+1);
				$this->set('anterior',$pagina-1);
				$this->bt_nav($Tfilas,$pagina);
	        }else{
	        	$this->set("datos",'');
	        }
          if($Tfilas!=0){
           $this->render("consulta");
          }else{
           $this->set("nombre_razon", $datos_filas_aux[0]["shd002_cobradores"]["nombre_razon"]);
           $this->set("rif_ci",       $datos_filas_aux[0]["shd002_cobradores"]["rif_ci"]);
           $this->set("year",         $year);
           $this->render("index_2");
          }//fin else
}//fin function



function guardar(){
$this->layout="ajax";
$cod_presi                =       $this->Session->read('SScodpresi');
$cod_entidad              =       $this->Session->read('SScodentidad');
$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
$cod_inst                 =       $this->Session->read('SScodinst');
$cod_dep                  =       $this->Session->read('SScoddep');



$Tfilas=$this->shd002_cobranza_pendiente->findCount($this->condicion()." and rif_ci='".$this->data["shp002_cobranza_pendiente"]["rif_cedula"]."'  and ano='".$this->data["shp002_cobranza_pendiente"]["ano"]."'   ");

if($Tfilas==0){
$campos = "   cod_presi,
			  cod_entidad,
			  cod_tipo_inst,
			  cod_inst,
			  cod_dep,
			  rif_ci,
			  ano,
			  cobranza_pendiente_acumulada,
			  enero,
			  febrero,
			  marzo,
			  abril,
			  mayo,
			  junio,
			  julio,
			  agosto,
			  septiembre,
			  octubre,
			  noviembre,
			  diciembre ";

$VALUES = "   '".$cod_presi."',
			  '".$cod_entidad."',
			  '".$cod_tipo_inst."',
			  '".$cod_inst."',
			  '".$cod_dep."',
			  '".$this->data["shp002_cobranza_pendiente"]["rif_cedula"]."',
			  '".$this->data["shp002_cobranza_pendiente"]["ano"]."',
			  '".$this->Formato1($this->data["shp002_cobranza_pendiente"]["cobranza_pendiente_acumulada"])."',
			  '".$this->Formato1($this->data["shp002_cobranza_pendiente"]["enero"])."',
			  '".$this->Formato1($this->data["shp002_cobranza_pendiente"]["febrero"])."',
			  '".$this->Formato1($this->data["shp002_cobranza_pendiente"]["marzo"])."',
			  '".$this->Formato1($this->data["shp002_cobranza_pendiente"]["abril"])."',
			  '".$this->Formato1($this->data["shp002_cobranza_pendiente"]["mayo"])."',
			  '".$this->Formato1($this->data["shp002_cobranza_pendiente"]["junio"])."',
			  '".$this->Formato1($this->data["shp002_cobranza_pendiente"]["julio"])."',
			  '".$this->Formato1($this->data["shp002_cobranza_pendiente"]["agosto"])."',
			  '".$this->Formato1($this->data["shp002_cobranza_pendiente"]["septiembre"])."',
			  '".$this->Formato1($this->data["shp002_cobranza_pendiente"]["octubre"])."',
			  '".$this->Formato1($this->data["shp002_cobranza_pendiente"]["noviembre"])."',
			  '".$this->Formato1($this->data["shp002_cobranza_pendiente"]["diciembre"])."' ";
$sw = $this->shd002_cobranza_pendiente->execute("BEGIN; INSERT INTO shd002_cobranza_pendiente (".$campos.") VALUES (".$VALUES.");");
}else{
$VALUES = "   cobranza_pendiente_acumulada = '".$this->Formato1($this->data["shp002_cobranza_pendiente"]["cobranza_pendiente_acumulada"])."',
			  enero              = '".$this->Formato1($this->data["shp002_cobranza_pendiente"]["enero"])."',
			  febrero            = '".$this->Formato1($this->data["shp002_cobranza_pendiente"]["febrero"])."',
			  marzo              = '".$this->Formato1($this->data["shp002_cobranza_pendiente"]["marzo"])."',
			  abril              = '".$this->Formato1($this->data["shp002_cobranza_pendiente"]["abril"])."',
			  mayo               = '".$this->Formato1($this->data["shp002_cobranza_pendiente"]["mayo"])."',
			  junio              = '".$this->Formato1($this->data["shp002_cobranza_pendiente"]["junio"])."',
			  julio              = '".$this->Formato1($this->data["shp002_cobranza_pendiente"]["julio"])."',
			  agosto             = '".$this->Formato1($this->data["shp002_cobranza_pendiente"]["agosto"])."',
			  septiembre         = '".$this->Formato1($this->data["shp002_cobranza_pendiente"]["septiembre"])."',
			  octubre            = '".$this->Formato1($this->data["shp002_cobranza_pendiente"]["octubre"])."',
			  noviembre          = '".$this->Formato1($this->data["shp002_cobranza_pendiente"]["noviembre"])."',
			  diciembre          = '".$this->Formato1($this->data["shp002_cobranza_pendiente"]["diciembre"])."' ";
$sw = $this->shd002_cobranza_pendiente->execute("BEGIN; update shd002_cobranza_pendiente set ".$VALUES." WHERE ".$this->condicion()." and rif_ci='".$this->data["shp002_cobranza_pendiente"]["rif_cedula"]."' and ano='".$this->data["shp002_cobranza_pendiente"]["ano"]."'; ");
}//fin else

if($sw>1){
  $this->shd002_cobranza_pendiente->execute("COMMIT;");
  $this->data=null;
  $this->set('Message_existe', 'EL Registro fue guardado con exito');
}else{
  $this->shd002_cobranza_pendiente->execute("ROLLBACK;");
  $this->set('errorMessage', 'Disculpe, El Registro no fue guardado');
}//fin else

$this->index();
$this->render("index");

}//fin function




function consulta($pagina=null, $rif_cedula=null, $year=null){
    $this->layout="ajax";
    if(isset($pagina)){ $pagina=$pagina; }else{ $pagina=1; }
	$condicion       = $this->condicion();
	if($rif_cedula!=null){$condicion .=" and rif_ci='".$rif_cedula."' and ano='".$year."' ";}
	$datos_filas_aux = $this->v_shd002_cobranza_pendiente->findAll($condicion,null,"rif_ci, nombre_razon ASC");
	$Tfilas=$this->v_shd002_cobranza_pendiente->findCount($condicion);
	    if($Tfilas!=0){
	    	$Tfilas=(int)ceil($Tfilas/1);
	    	$this->set('total_paginas',$Tfilas);
			$this->set('pagina_actual',$pagina);
			$this->set('pag_cant',$pagina.'/'.$Tfilas);
			$this->set('ultimo',$Tfilas);
	 	    $datos_filas=$this->v_shd002_cobranza_pendiente->findAll($condicion,null,"rif_ci ASC",1,$pagina,null);
	        $this->set("datos",$datos_filas);
	        $this->set('siguiente',$pagina+1);
			$this->set('anterior',$pagina-1);
			$this->bt_nav($Tfilas,$pagina);
	    }else{
	    	$this->set("datos",'');
	    }
	  //$this->set("nombre_razon", $datos_filas_aux[0]["shd002_cobradores"]["nombre_razon"]);
	  //$this->set("rif_ci",       $datos_filas_aux[0]["shd002_cobradores"]["rif_ci"]);
}//fin function



function funcion(){
  $this->layout="ajax";
}//fin function


function modificar(){
  $this->layout="ajax";
  $this->render("funcion");
}//fin function

function cancelar(){
  $this->layout="ajax";
  $this->render("funcion");
}//fin function

function eliminar(){
   $this->layout="ajax";
   $sw = $this->shd002_cobranza_pendiente->execute("BEGIN; DELETE FROM shd002_cobranza_pendiente WHERE ".$this->condicion()." and rif_ci='".$this->data["shp002_cobranza_pendiente"]["rif_cedula"]."' and ano='".$this->data["shp002_cobranza_pendiente"]["ano"]."'; ");
if($sw>1){
  $this->shd002_cobranza_pendiente->execute("COMMIT;");
  $this->set('Message_existe', 'EL Registro fue eliminado con exito');
}else{
  $this->shd002_cobranza_pendiente->execute("ROLLBACK;");
  $this->set('errorMessage', 'Disculpe, El Registro no pudo ser eliminado');
}//fin else

$this->render("index");

}//fin function


function index(){
$this->layout="ajax";
$this->Session->delete('pista_buscar_cobrador_hacienda');
$this->Session->delete('pista_buscar_cobranza_pendiente_hacienda');
}//fin function





}//fin class

?>