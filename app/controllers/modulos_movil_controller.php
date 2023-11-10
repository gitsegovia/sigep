<?php

class ModulosMovilController extends AppController

{
    //var $uses = array('Usuario', 'cugd04_entrada_modulo' , 'reactualizar_solicitud');
    var $uses = array('Usuario', 'cugd04_entrada_modulo','modulos','modulos_sistema' );
    var $helpers = array('Html', 'Javascript', 'Ajax','Sisap');

    //var $layout =  "index_modulos";

function checkSession(){
				if (!$this->Session->check('Usuario')){
						if (!$this->Session->check('concejo_comunal')){
								$this->redirect('/salir');
								exit();
						}
				}else{
					$this->requestAction('/usuarios/actualizar_user');
				}
}//fin checksession
function beforeFilter(){
					$this->checkSession();

}

function index($opcion=null){
    $this->layout =  "index_modulos_movil";
    if(isset($opcion) && $opcion=='entrada_exitosa'){
          $this->set('EntradaForm',true);
    }
    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$username = $this->Session->read('nom_usuario');
	//$this->entrada();
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep." and username='$username'";
	$modulo=$this->Session->read('Modulo');
	$modulo=$this->Usuario->field('modulo', $condicion, null);
	//echo "este es el modulo: ".$modulo."<br>";
	if($modulo != '0') $modulo=str_split($modulo, 6);
	//print_r($modulo);
	//$mod=array('CUGP00','CFP000','CSIP00','CSCP00','COBP00','CCSP00','CEP000', 'CSTP00','CNP000','CIPP00','CFPP00','CGPP00','CATP00','SHPP00','CAP000','CMCP00', 'CSRP00');
	if((isset($_SESSION["concejo_comunal"]) || ($cod_presi==1 && $cod_entidad==1 && $cod_tipo_inst==1 && $cod_inst==1 && $cod_dep==1)) && $_SESSION["nom_usuario"]!="ADMIN"  ){
        $c_c_c=$this->modulos->findCount("cod_tipo_inst=30 and cod_inst=11 and cod_dep=1",null,'orden_ubicacion ASC');
        if($c_c_c!=0){
        	$modulo_modelo = 'modulos';
        	$data_modulos=$this->$modulo_modelo->findAll("cod_tipo_inst=30 and cod_inst=11 and cod_dep=1",null,'orden_ubicacion ASC');
        }else{
        	$modulo_modelo = 'modulos_sistema';
            $data_modulos=$this->$modulo_modelo->findAll(null,null,'orden_ubicacion ASC');
        }

	}else{
		$modulo_modelo = 'modulos';
		$data_modulos=$this->modulos->findAll("cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep,null,'orden_ubicacion ASC');
	}

    $this->set('data_modulos',$data_modulos);
    foreach($data_modulos as $datamod){
      if((isset($_SESSION["concejo_comunal"])  || ($cod_presi==1 && $cod_entidad==1 && $cod_tipo_inst==1 && $cod_inst==1 && $cod_dep==1))  && $_SESSION["nom_usuario"]!="ADMIN"){
       	 $mod[$datamod[$modulo_modelo]['orden_ubicacion']]="negar_menu";
      }else{
      	if(isset($datamod['modulos']['status']) && $datamod['modulos']['status']==1){
       	 $mod[$datamod[$modulo_modelo]['orden_ubicacion']]=strtoupper($datamod[$modulo_modelo]['cod_modulo']);
       }else{
       	 $mod[$datamod[$modulo_modelo]['orden_ubicacion']]="negar_menu";
       }
      }

    }
//$mod=array('CUGP00','CFP000','CSIP00','CSCP00','COBP00','CEP000','CEPP00','CSTP00','CNP000','CIPP00','CFPP00','CGPP00','CATP00','SHPP00','CAP000','CMCP00', 'CSRP00', 'CATSP0');
//$mod=array('CUGP00','CFP000','CSIP00','negar_menu','negar_menu','negar_menu','negar_menu','negar_menu','CNP000','CGPP00','CFPP00','CGPP00','CATP00','SHPP00','CAP000','CMCP00', 'CSRP00', 'CATSP0');
   for($i=0;$i<count($mod);$i++){
   		if($modulo=='0'){
   			for($j=0;$j<count($mod);$j++){

   			    if($j==0){
   			    	   if((isset($_SESSION["concejo_comunal"])  || ($cod_presi==1 && $cod_entidad==1 && $cod_tipo_inst==1 && $cod_inst==1 && $cod_dep==1))  && $_SESSION["nom_usuario"]!="ADMIN" ){
   			    	   	 $vmodulos[$i]='modulos/#';
   			    	   }else{
   			    	     $vmodulos[0]='administradors/uso_general';
   			    	   }
   				   }else{
   					 $vmodulos[$j]='administradors/standard/'.strtolower($mod[$j]);
   					 //$vmodulos[$i]='modulos/#';
   				   }//fin else

   			}//fin for

   		}else{
   			for($j=0;$j<count($modulo);$j++){


		       if($modulo[$j]==$mod[$i]){
					//echo "entre aqui ".$i." vez";
					//echo "modulo(".$modulo[$j].") == mod(".$mod[$i].")<br>";
					$vmodulos[$i]='administradors/standard/'.strtolower($mod[$i]);
					break;
		       }else{
		       		//echo "-1entre aqui ".$i." vez<br>";
		       		//echo "modulo(".$modulo[$j].") == mod(".$mod[$i].")<br>";
		       		$vmodulos[$i]='modulos/#';
		       }//fin else

	   		}
   		}

   }
   $this->set('modulo_modelo',$modulo_modelo);
   	//echo $vmodulos[0];
    $this->set('MODULO',$vmodulos);
    $this->render('index');
 }//fin index


function vacio(){
	$this->layout="ajax";
	$_SESSION['cod_dep_reporte_consolidado'] = isset($_SESSION['SScoddeporig'])?$_SESSION['SScoddeporig']:$_SESSION['SScoddep'];
		 echo"<script>menu_activo();</script>";
}//fin vacio



function vacio_salir(){
	$this->layout="ajax";
}//fin vacio


function solicitudes(){
	$datos = $this->reactualizar_solicitud->findAll($conditions = null, $fields = null, $order = null, $limit = null, $page = null, $recursive = null);
	$sql_update_solicitud = "";
	foreach($datos as $row){
		$concepto = $row['reactualizar_solicitud']['concepto'];
		$cod_presi = $row['reactualizar_solicitud']['cod_presi'];
		$cod_entidad = $row['reactualizar_solicitud']['cod_entidad'];
		$cod_tipo_inst = $row['reactualizar_solicitud']['cod_tipo_inst'];
		$cod_inst = $row['reactualizar_solicitud']['cod_inst'];
		$cod_dep = $row['reactualizar_solicitud']['cod_dep'];
		$ano_solicitud = $row['reactualizar_solicitud']['ano_solicitud'];
		$numero_solicitud = $row['reactualizar_solicitud']['numero_solicitud'];
		$sql_update_solicitud .= "UPDATE cscd02_solicitud_encabezado SET uso_destino='$concepto' WHERE cod_presi='$cod_presi' and cod_entidad='$cod_entidad' and cod_tipo_inst='$cod_tipo_inst' and cod_inst='$cod_inst' and cod_dep='$cod_dep' and ano_solicitud='$ano_solicitud' and numero_solicitud='$numero_solicitud';";
	}
	$sw = $this->reactualizar_solicitud->execute($sql_update_solicitud);
	if ($sw > 1) echo "actualizacion correcta";



}

function control_modulos () {
	  $this->layout="ajax";
	  $cod_tipo_inst = $this->Session->read('SScodtipoinst');
	  $cod_dep = $this->Session->read('SScoddep');
	  if($cod_dep==1 && $_SESSION["Modulo"]==0){
	  	   $this->set('Mostrar',true);
           $this->set('ListaModulos',$this->modulos->findAll(null,null,'cod_tipo_inst,cod_inst,cod_dep,orden_ubicacion ASC'));
	  }
}

function cambiar_status_modulo ($cod_dep,$cod_modulo,$status,$i) {
	$this->layout="ajax";
	$cond_mod='cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$cod_dep;
	$this->modulos->execute("UPDATE modulos SET status=".$status." WHERE cod_modulo='$cod_modulo' and ".$cond_mod);
	$this->set('cod_dep',$cod_dep);
	$this->set('Status',$status);
	$this->set('Cmodulo',$cod_modulo);
	$this->set('i',$i);
}


}//fin classs
?>
