<?php

 class Cscd02SeleccionProveedorController extends AppController {
   var $name="cscd02_seleccion_proveedor";
   var $uses = array('cscd02_solicitud_cuerpo','cscd02_solicitud_encabezado','v_cscd02_solicitud_encabezado','ccfd04_cierre_mes','Usuario');
   var $helpers = array('Html','Ajax','Javascript', 'Sisap');
   var $estatus = array ('1'=>'Abierta','2'=>'Cerrada');

function checkSession(){
		if (!$this->Session->check('Usuario')){
			$this->redirect('/salir/');
			exit();
		}else{
			//$this->set('userTable', $this->requestAction('/cnmp03partidas/', array('return')));
			//echo "H".$this->requestAction('/usuarios/actualizar_user',array('return'));
			$this->requestAction('/usuarios/actualizar_user');
		}
}//fin checksession

 function beforeFilter(){
    $this->checkSession();

 }

function verifica_SS($i){
  	switch ($i){
   		case 1:return $this->Session->read('SScodpresi');break;
   		case 2:return $this->Session->read('SScodentidad');break;
   		case 3:return $this->Session->read('SScodtipoinst');break;
   		case 4:return $this->Session->read('SScodinst');break;
   		case 5:return $this->Session->read('SScoddep');break;
   		case 6:return $this->Session->read('entidad_federal');break;
   		default:
   		return "NULO";
   	}//fin switch
}//fin verifica_SS

function SQLCA($ano=null){//sql para busqueda de codigos de arranque con y sin año
        $sql_re = "cod_presi=".$this->verifica_SS(1)."  and    ";
        $sql_re .= "cod_entidad=".$this->verifica_SS(2)."  and  ";
        $sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)."  and ";
        $sql_re .= "cod_inst=".$this->verifica_SS(4)."  and ";
        $sql_re .= "cod_dep=".$this->verifica_SS(5);
        return $sql_re;
}//fin funcion SQLCA


function datos(){
        $ano=$this->ano_ejecucion();
        $condi =$this->SQLCA($ano);
        $solicitud_encabezado=$this->cscd02_solicitud_encabezado->findAll($condi."and status >0",null, "numero_solicitud",null,null, null);
        //                                                 findAll($conditions = null, $fields = null, $order = null, $limit = null, $page = 1, $recursive = null)
        $this->set('datos',$solicitud_encabezado);
 
}

function solicitudes_pendientes(){
 $ano=$this->ano_ejecucion();
 $condi =$this->SQLCA($ano);
 $solicitudes_pendientes=$this->v_cscd02_solicitud_encabezado->findAll($condi."and numero_oferentes = 0 ",null, "numero_solicitud",null,null, null);
        //                                                   findAll($conditions = null, $fields = null, $order = null, $limit = null, $page = 1, $recursive = null)
  $this->set('solicitudes_pendientes',$solicitudes_pendientes);
}


function index () {
 	$this->layout = "ajax";
	 $this->solicitudes_pendientes();
 }


 function detalles($ano, $numero_solicitud=null){
     $this->layout="ajax";
     $condi =$this->SQLCA($ano);
     
     // SELECT  * FROM  cscd02_solicitud_cuerpo,   cscd01_unidad_medida WHERE cscd02_solicitud_cuerpo.cod_medida = cscd01_unidad_medida.cod_medida;    
     
     $sql="SELECT  * FROM v_cscd02_solicitud_cuerpo WHERE $condi and numero_solicitud=$numero_solicitud ";
    $detalle_solicitud=$this->cscd02_solicitud_encabezado->execute($sql);
    
    $this->set('numero_solicitud',$numero_solicitud);
    $this->set('detalle_solicitud',$detalle_solicitud);
}
 

function CalculaEdad( $fecha ) {
    list($Y,$m,$d) = explode("-",$fecha);
    return( date("md") < $m.$d ? date("Y")-$Y-1 : date("Y")-$Y );
}


function seleccionar($numero_solicitud=null,$opcion=null){
     $this->layout="ajax";
     $ano=$this->ano_ejecucion();
     $condi =$this->SQLCA($ano);
      
     //unidad Tributaria:
    $sql2="SELECT unidad_tributaria FROM cscd04_ordencompra_parametros where $condi";
    $unidad_tributaria=$this->cscd02_solicitud_encabezado->execute($sql2);
    $unidad_tributaria=$unidad_tributaria[0][0]['unidad_tributaria'];
    
    
    //Bienes
    if ($opcion==1){
        $sql="select *,
            devolver_edad(current_date,fecha_vencimiento_ocei,'ano') as ano_antiguedad,
            devolver_edad(current_date,fecha_inscrip_inicial_snc,'ano') as ano_snc, 
            devolver_edad(current_date,fecha_inscrip_inicial_snc,'mes') as mes_snc,
            (capacidad_financiera/107) as capacidad_financiera_ut 

            from cpcd02 WHERE categoria_suministro= 1 or categoria_suministro= 3 or categoria_suministro= 5 ";
        
        
    }else if($opcion==2){ //Servicios
        $sql="select *,
            devolver_edad(current_date,fecha_vencimiento_ocei,'ano') as ano_antiguedad,
            devolver_edad(current_date,fecha_inscrip_inicial_snc,'ano') as ano_snc, 
            devolver_edad(current_date,fecha_inscrip_inicial_snc,'mes') as mes_snc,
            (capacidad_financiera/107) as capacidad_financiera_ut 
            from cpcd02 WHERE categoria_suministro= 2 or categoria_suministro= 3 or categoria_suministro= 5 ";     
        
        
    }else if($opcion==3){ //Bienes y Servicios
        $sql="select *,
            devolver_edad(current_date,fecha_vencimiento_ocei,'ano') as ano_antiguedad,
            devolver_edad(current_date,fecha_inscrip_inicial_snc,'ano') as ano_snc, 
            devolver_edad(current_date,fecha_inscrip_inicial_snc,'mes') as mes_snc,
            (capacidad_financiera/107) as capacidad_financiera_ut 
            from cpcd02 WHERE categoria_suministro= 1 or categoria_suministro= 3 or categoria_suministro= 5 ";        
    
    }else if($opcion==4){ //Obras
        $sql="select *,
            devolver_edad(current_date,fecha_vencimiento_ocei,'ano') as ano_antiguedad,
            devolver_edad(current_date,fecha_inscrip_inicial_snc,'ano') as ano_snc, 
            devolver_edad(current_date,fecha_inscrip_inicial_snc,'mes') as mes_snc,
            (capacidad_financiera/107) as capacidad_financiera_ut 
            from cpcd02 WHERE  categoria_suministro= 4 or categoria_suministro= 5 ";        
    
    }else if($opcion==5){ //Todos
        $sql="select *,
            devolver_edad(current_date,fecha_vencimiento_ocei,'ano') as ano_antiguedad,
            devolver_edad(current_date,fecha_inscrip_inicial_snc,'ano') as ano_snc, 
            devolver_edad(current_date,fecha_inscrip_inicial_snc,'mes') as mes_snc,
            (capacidad_financiera/107) as capacidad_financiera_ut 
            from cpcd02";        
    }
    
    //lista de Proveedores
    $proveedores=$this->cscd02_solicitud_encabezado->execute($sql);
    
    $cantidad=count($proveedores);
 
    //elimina a los vencimiento diferente de 0
    for ($i=0;$i<$cantidad;$i++){
        if ($proveedores[$i][0]['ano_antiguedad']!=0){unset($proveedores[$i]);
        
        }else { //si no esta vencido
           
            //Calcula Puntos de Antiguedad
            if ($proveedores[$i][0]['ano_antiguedad']==0 && $proveedores[$i][0]['mes_antiguedad']=!0 ||  $proveedores[$i][0]['ano_antiguedad']==1 &&  $proveedores[$i][0]['mes_antiguedad']==0){ $proveedores[$i][0]['puntos_antiguedad']=1;};
            if ($proveedores[$i][0]['ano_antiguedad']==1 && $proveedores[$i][0]['mes_antiguedad']>0 &&  $proveedores[$i][0]['ano_antiguedad']<=5){ $proveedores[$i][0]['puntos_antiguedad']=3;};
            if ($proveedores[$i][0]['ano_antiguedad']==5 && $proveedores[$i][0]['mes_antiguedad']>0 &&  $proveedores[$i][0]['ano_antiguedad']<=10){ $proveedores[$i][0]['puntos_antiguedad']=6;};
            if ($proveedores[$i][0]['ano_antiguedad']>10 || $proveedores[$i][0]['ano_antiguedad']==10 && $proveedores[$i][0]['mes_antiguedad']>0){ $proveedores[$i][0]['puntos_antiguedad']=9;};

            
            //Capacidad Financiera
            if ($proveedores[$i][0]['capacidad_financiera_ut'] ==0 && $proveedores[$i][0]['capacidad_financiera_ut'] ==1000.00){$proveedores[$i][0]['puntos_capacidad']  = 1;}
            if ($proveedores[$i][0]['capacidad_financiera_ut'] >=0 && $proveedores[$i][0]['capacidad_financiera_ut'] <=1000.00){$proveedores[$i][0]['puntos_capacidad']  = 1;}
            if ($proveedores[$i][0]['capacidad_financiera_ut'] >=1000.01 && $proveedores[$i][0]['capacidad_financiera_ut'] <=2500.00){$proveedores[$i][0]['puntos_capacidad']  = 3;}
            if ($proveedores[$i][0]['capacidad_financiera_ut'] >=2500.01 && $proveedores[$i][0]['capacidad_financiera_ut'] <=5000.00){$proveedores[$i][0]['puntos_capacidad']  = 6;}
            if ($proveedores[$i][0]['capacidad_financiera_ut'] >=5000.01){$proveedores[$i][0]['puntos_capacidad']  = 9;}
            
            
            //Instituciones Similares
            if ($proveedores[$i][0]['suministro_cliente_similar']==1){
                $proveedores[$i][0]['puntos_instituciones_similares']=1;
            }else{
                $proveedores[$i][0]['puntos_instituciones_similares']=0;
            }
           
            //Totaliza los renglones
                $proveedores[$i][0]['total_puntos']=(
                    $proveedores[$i][0]['puntos_antiguedad']+
                    $proveedores[$i][0]['puntos_capacidad']+
                    $proveedores[$i][0]['puntos_instituciones_similares']
                    );
           
            //Inserto en la Base de Datos, la informacion
    $sql="INSERT INTO cscd02_solicitud_seleccion values (".
            $this->verifica_SS(1).
            ",".$this->verifica_SS(2).
            ",".$this->verifica_SS(3).
            ",".$this->verifica_SS(4).
            ",".$this->verifica_SS(5).
            ",".$ano.
            ",".$numero_solicitud.
            ",'".$proveedores[$i][0]['rif'].
            "',".$proveedores[$i][0]['puntos_antiguedad'].
            ",".$proveedores[$i][0]['puntos_instituciones_similares'].
            ",".$proveedores[$i][0]['puntos_capacidad'].
            ",".$proveedores[$i][0]['total_puntos'].");";
    
     $proveedores=$this->cscd02_solicitud_encabezado->execute($sql);
        }
    }

     
//Recupero la Informacion para 
    $sql1="SELECT *
FROM 
  public.cscd02_solicitud_seleccion, 
  public.cpcd02
WHERE $condi AND cscd02_solicitud_seleccion.rif = cpcd02.rif and numero_solicitud=".$numero_solicitud;
   $datos=$this->cscd02_solicitud_encabezado->execute($sql1);
        
 $this->set('proveedores',$datos);
 //$this->set('proveedores',$proveedores);
 $this->set('sql',$sql);
    
    
}

function envia_correos($numero_solicitud=null){
    
//Plantilla de la gobernacion    
$mess="A traves de Este Mensaje lo invitamos a participar en la oferta de productos contentivos en la Solicitud de Cotización Nro $numero_solicitud";
$subject="Invitacion a Cotizar";

foreach ($proveedores as $destinos){
//mail($to, $subject, $mess, $header);
mail($destinos[0]['correo_electronico_empresa'], $subject, $mess, $header);
}

$this->vacio();
$this->render('vacio');

    
}

function vacio(){
   $this->layout="ajax";  
}
}//fin class
?>
