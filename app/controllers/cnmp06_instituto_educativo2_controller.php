<?php
/*
 * Creado el 09/11/2007
 *
 * Miguelangel Cabrera
 * miguel4ngel@gmail.com
 *
 * 12:26:51 PM
 */
 class Cnmp06institutoeducativoController extends AppController {
   var $name = 'cnmp06_instituto_educativo';
   var $uses = array('cnmd06_instituto_educativo','cugd01_republica','cugd01_estados','cugd01_municipios','cugd01_parroquias','cugd01_centropoblados');
   var $helpers = array('Html','Ajax','Javascript', 'Sisap');

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
 	 echo'				<script>
							 document.getElementById("valida_codigo").innerHTML = "";
							 document.getElementById("valida_codigo").style.display = "none";
							 if(document.getElementById("registro")){document.getElementById("registro").style.visibility = "hidden";}
                             if(document.getElementById("nomina")){document.getElementById("nomina").style.visibility = "hidden";}
                             if(document.getElementById("dependencia")){document.getElementById("dependencia").style.visibility = "hidden";}
                             if(document.getElementById("sistema_general")){document.getElementById("sistema_general").style.visibility = "hidden";}
                         </script>';

 }

 function s($i){
 	switch ($i){
    		case 'rep':return $this->Session->read('s_rep');break;
    		case 'est':return $this->Session->read('s_est');break;
    		case 'mun':return $this->Session->read('s_mun');break;
    		case 'par':return $this->Session->read('s_par');break;
    		case 'cen':return $this->Session->read('s_cen');break;
    		case 'ins':return $this->Session->read('s_ins');break;
    		default:
    		   return "";
    	}//fin switch
 }

  function dep($i){
 	switch ($i){
    		case 'rep':return 'cod_republica=';break;
    		case 'est':return 'cod_republica='.$this->s('rep').' and cod_estado=';break;
    		case 'mun':return 'cod_republica='.$this->s('rep').' and cod_estado='.$this->s('est').' and cod_municipio=';break;
    		case 'par':return 'cod_republica='.$this->s('rep').' and cod_estado='.$this->s('est').' and cod_municipio='.$this->s('mun').' and cod_parroquia=';break;
    		case 'cen':return 'cod_republica='.$this->s('rep').' and cod_estado='.$this->s('est').' and cod_municipio='.$this->s('mun').' and cod_parroquia='.$this->s('par').' and cod_centro=';break;
    		default:
    		   return "";
    	}//fin switch
 }

   function indep($i){
 	switch ($i){
    		case 'rep':return 'cod_republica='.$this->s('rep');break;
    		case 'est':return 'cod_republica='.$this->s('rep').' and cod_estado='.$this->s('est');break;
    		case 'mun':return 'cod_republica='.$this->s('rep').' and cod_estado='.$this->s('est').' and cod_municipio='.$this->s('mun');break;
    		case 'par':return 'cod_republica='.$this->s('rep').' and cod_estado='.$this->s('est').' and cod_municipio='.$this->s('mun').' and cod_parroquia='.$this->s('par');break;
    		case 'cen':return 'cod_republica='.$this->s('rep').' and cod_estado='.$this->s('est').' and cod_municipio='.$this->s('mun').' and cod_parroquia='.$this->s('par').' and cod_centro='.$this->s('cen');break;
    		default:
    		   return "";
    	}//fin switch
 }


 function index(){
 	$this->layout ="ajax";
	$this->set('accion',$this->cnmd06_instituto_educativo->findAll(null,null, 'cod_institucion ASC'));

}

function select_republica($id=null){
  	$this->layout ="ajax";
  	if($id !='otros'){
	$this->concatena($this->cugd01_republica->generateList($this->dep('rep').$id,' cod_republica ASC', null, '{n}.cugd01_republica.cod_republica', '{n}.cugd01_republica.denominacion'), 'tipo_rep');
	$this->concatena($this->cugd01_estados->generateList($this->dep('rep').$id,' denominacion ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion'), 'tipo_est');
	//$this->concatena($this->cugd01_republica->generateList($this->dep('rep').$id,' cod_republica ASC', null, '{n}.cugd01_republica.cod_republica', '{n}.cugd01_republica.denominacion'), 'tipo_rep');
	//$this->concatena($this->cugd01_estados->generateList($this->dep('rep').$id,' denominacion ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion'), 'tipo_est');
  	$this->set('datos_rep', $this->cugd01_republica->findAll($this->dep('rep').$id));
	$this->Session->write('s_rep',$id);
	$this->set('s_rep',$id);
	$this->set('cod_institucion','');
  	}

 }


 function select_estado($id=null){
  	$this->layout ="ajax";
  	if($id !='otros'){
  	//$this->filtro('rep',$id);
  	//$this->filtro('est',$this->s('rep'));
	$this->concatena($this->cugd01_republica->generateList('',' cod_republica ASC', null, '{n}.cugd01_republica.cod_republica', '{n}.cugd01_republica.denominacion'), 'tipo_rep');
	$this->concatena($this->cugd01_estados->generateList($this->indep('rep'),' cod_estado ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion'), 'tipo_est');
  	$this->concatena($this->cugd01_municipios->generateList($this->dep('est').$id,' cod_municipio ASC', null, '{n}.cugd01_municipios.cod_municipio', '{n}.cugd01_municipios.denominacion'), 'tipo_mun');
  	$this->set('datos_rep', $this->cugd01_republica->findAll($this->indep('rep')));
  	$this->set('datos_est', $this->cugd01_estados->findAll($this->dep('est').$id));
  	$this->Session->write('s_est',$id);
  	$this->set('s_rep',$this->s('rep'));
  	$this->set('s_est',$id);
  	$this->set('cod_institucion','');
  		}
 }


  function select_municipio($id){
  	$this->layout ="ajax";
  	if($id !='otros'){
	$this->concatena($this->cugd01_republica->generateList('',' cod_republica ASC', null, '{n}.cugd01_republica.cod_republica', '{n}.cugd01_republica.denominacion'), 'tipo_rep');
	$this->concatena($this->cugd01_estados->generateList($this->indep('rep'),' cod_estado ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion'), 'tipo_est');
  	$this->concatena($this->cugd01_municipios->generateList($this->indep('est'),' cod_municipio ASC', null, '{n}.cugd01_municipios.cod_municipio', '{n}.cugd01_municipios.denominacion'), 'tipo_mun');
  	//$this->concatena($this->cugd01_parroquias->generateList($this->dep('mun').$id,' cod_parroquia ASC', null, '{n}.cugd01_parroquias.cod_parroquia', '{n}.cugd01_parroquias.denominacion'), 'tipo_par');



  	$this->set('datos_rep', $this->cugd01_republica->findAll($this->indep('rep')));
  	$this->set('datos_est', $this->cugd01_estados->findAll($this->indep('est')));
  	$this->set('datos_mun', $this->cugd01_municipios->findAll($this->dep('mun').$id));
  	$this->Session->write('s_mun',$id);
  	$this->set('s_rep',$this->s('rep'));
  	$this->set('s_est',$this->s('est'));
  	$this->set('s_mun',$id);
	$this->set('cod_institucion','');
	$this->concatena($this->cnmd06_instituto_educativo->generateList($this->indep('mun'),' cod_institucion ASC', null, '{n}.cnmd06_instituto_educativo.cod_institucion', '{n}.cnmd06_instituto_educativo.denominacion'), 'tipo_par');
  	}

 }

   function select_parroquia($id){
  	$this->layout ="ajax";
  	if($id !='otros'){
	$this->concatena($this->cugd01_republica->generateList('',' cod_republica ASC', null, '{n}.cugd01_republica.cod_republica', '{n}.cugd01_republica.denominacion'), 'tipo_rep');
	$this->concatena($this->cugd01_estados->generateList($this->indep('rep'),' cod_estado ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion'), 'tipo_est');
  	$this->concatena($this->cugd01_municipios->generateList($this->indep('est'),' cod_municipio ASC', null, '{n}.cugd01_municipios.cod_municipio', '{n}.cugd01_municipios.denominacion'), 'tipo_mun');
  	$this->concatena($this->cugd01_parroquias->generateList($this->indep('mun'),' cod_parroquia ASC', null, '{n}.cugd01_parroquias.cod_parroquia', '{n}.cugd01_parroquias.denominacion'), 'tipo_par');
  	$this->concatena($this->cugd01_centropoblados->generateList($this->dep('par').$id,' cod_centro ASC', null, '{n}.cugd01_centropoblados.cod_centro', '{n}.cugd01_centropoblados.denominacion'), 'tipo_cen');
  	$this->set('datos_rep', $this->cugd01_republica->findAll($this->indep('rep')));
  	$this->set('datos_est', $this->cugd01_estados->findAll($this->indep('est')));
  	$this->set('datos_mun', $this->cugd01_municipios->findAll($this->indep('mun')));
  	$this->set('datos_par', $this->cugd01_parroquias->findAll($this->dep('par').$id));
  	$this->Session->write('s_par',$id);
  	$this->set('s_rep',$this->s('rep'));
  	$this->set('s_est',$this->s('est'));
  	$this->set('s_mun',$this->s('mun'));
  	$this->set('s_par',$id);
	$this->set('cod_institucion','');
  	}
 }




 function select_centropoblado($id=null){
  	$this->layout ="ajax";
  	//echo 'centro <br>';
  	if($id !='otros'){
	$this->concatena($this->cugd01_republica->generateList('',' cod_republica ASC', null, '{n}.cugd01_republica.cod_republica', '{n}.cugd01_republica.denominacion'), 'tipo_rep');
	$this->concatena($this->cugd01_estados->generateList($this->indep('rep'),' cod_estado ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion'), 'tipo_est');
  	$this->concatena($this->cugd01_municipios->generateList($this->indep('est'),' cod_municipio ASC', null, '{n}.cugd01_municipios.cod_municipio', '{n}.cugd01_municipios.denominacion'), 'tipo_mun');
  	$this->concatena($this->cugd01_parroquias->generateList($this->indep('mun'),' cod_parroquia ASC', null, '{n}.cugd01_parroquias.cod_parroquia', '{n}.cugd01_parroquias.denominacion'), 'tipo_par');
  	$this->concatena($this->cugd01_centropoblados->generateList($this->indep('par'),' cod_centro ASC', null, '{n}.cugd01_centropoblados.cod_centro', '{n}.cugd01_centropoblados.denominacion'), 'tipo_cen');
   	$this->concatena($this->cnmd06_instituto_educativo->generateList($this->indep('mun'),' cod_institucion ASC', null, '{n}.cnmd06_instituto_educativo.cod_institucion', '{n}.cnmd06_instituto_educativo.denominacion'), 'tipo_ins');
	$this->set('datos_rep', $this->cugd01_republica->findAll($this->indep('rep')));
  	$this->set('datos_est', $this->cugd01_estados->findAll($this->indep('est')));
  	$this->set('datos_mun', $this->cugd01_municipios->findAll($this->indep('mun')));
  	$this->set('datos_par', $this->cugd01_parroquias->findAll($this->indep('par')));
  	$this->set('datos_cen', $this->cugd01_centropoblados->findAll($this->dep('cen').$id));
  	$this->Session->write('s_cen',$id);
  	$this->set('s_rep',$this->Session->read('s_rep'));
  	$this->set('s_est',$this->Session->read('s_est'));
  	$this->set('s_mun',$this->Session->read('s_mun'));
  	$this->set('s_par',$this->Session->read('s_par'));
  	$this->set('s_cen',$id);
	$this->set('cod_institucion','');
  	//echo $id;
  	}
 }

  function select_institucion($id=null){
  	$this->layout ="ajax";
  	//echo 'ins';
  	if($id !='otros'){
  		//echo 'asd';
	$this->concatena($this->cugd01_republica->generateList('',' cod_republica ASC', null, '{n}.cugd01_republica.cod_republica', '{n}.cugd01_republica.denominacion'), 'tipo_rep');
	$this->concatena($this->cugd01_estados->generateList($this->indep('rep'),' cod_estado ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion'), 'tipo_est');
  	$this->concatena($this->cugd01_municipios->generateList($this->indep('est'),' cod_municipio ASC', null, '{n}.cugd01_municipios.cod_municipio', '{n}.cugd01_municipios.denominacion'), 'tipo_mun');
  	//$this->concatena($this->cugd01_parroquias->generateList($this->indep('mun'),' cod_parroquia ASC', null, '{n}.cugd01_parroquias.cod_parroquia', '{n}.cugd01_parroquias.denominacion'), 'tipo_par');
  	//$this->concatena($this->cugd01_centropoblados->generateList($this->indep('par'),' cod_centro ASC', null, '{n}.cugd01_centropoblados.cod_centro', '{n}.cugd01_centropoblados.denominacion'), 'tipo_cen');
   	$this->concatena($this->cnmd06_instituto_educativo->generateList($this->indep('mun'),' cod_institucion ASC', null, '{n}.cnmd06_instituto_educativo.cod_institucion', '{n}.cnmd06_instituto_educativo.denominacion'), 'tipo_ins');
	$this->set('datos_rep', $this->cugd01_republica->findAll($this->indep('rep')));
  	$this->set('datos_est', $this->cugd01_estados->findAll($this->indep('est')));
  	$this->set('datos_mun', $this->cugd01_municipios->findAll($this->indep('mun')));
  	//$this->set('datos_par', $this->cugd01_parroquias->findAll($this->indep('par')));
  	//$this->set('datos_cen', $this->cugd01_centropoblados->findAll($this->indep('cen')));
  	$this->set('datos_ins', $this->cnmd06_instituto_educativo->findAll($this->indep('mun').' and cod_institucion='.$id));
  	//echo $this->indep('cen').' and cod_institucion='.$id;
  	$this->set('s_rep',$this->s('rep'));
  	$this->set('s_est',$this->s('est'));
  	$this->set('s_mun',$this->s('mun'));
  	//$this->set('s_par',$this->s('par'));
  	//$this->set('s_cen',$this->s('cen'));
  	$this->set('s_ins',$id);
  	$this->set('disabled','disabled');
  	$this->set('disabled2','enabled');
  	$this->set('read','readonly');



  	}else{// Para habilitar el boton de guardar y preparar para guardar!
  	$this->concatena($this->cugd01_republica->generateList('',' cod_republica ASC', null, '{n}.cugd01_republica.cod_republica', '{n}.cugd01_republica.denominacion'), 'tipo_rep');
	$this->concatena($this->cugd01_estados->generateList($this->indep('rep'),' cod_estado ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion'), 'tipo_est');
  	$this->concatena($this->cugd01_municipios->generateList($this->indep('est'),' cod_municipio ASC', null, '{n}.cugd01_municipios.cod_municipio', '{n}.cugd01_municipios.denominacion'), 'tipo_mun');
  	//$this->concatena($this->cugd01_parroquias->generateList($this->indep('mun'),' cod_parroquia ASC', null, '{n}.cugd01_parroquias.cod_parroquia', '{n}.cugd01_parroquias.denominacion'), 'tipo_par');
  	//$this->concatena($this->cugd01_centropoblados->generateList($this->indep('par'),' cod_centro ASC', null, '{n}.cugd01_centropoblados.cod_centro', '{n}.cugd01_centropoblados.denominacion'), 'tipo_cen');
   	$this->concatena($this->cnmd06_instituto_educativo->generateList($this->indep('mun'),' cod_institucion ASC', null, '{n}.cnmd06_instituto_educativo.cod_institucion', '{n}.cnmd06_instituto_educativo.denominacion'), 'tipo_ins');
	$this->set('datos_rep', $this->cugd01_republica->findAll($this->indep('rep')));
  	$this->set('datos_est', $this->cugd01_estados->findAll($this->indep('est')));
  	$this->set('datos_mun', $this->cugd01_municipios->findAll($this->indep('mun')));
  	//$this->set('datos_par', $this->cugd01_parroquias->findAll($this->indep('par')));
  	//$this->set('datos_cen', $this->cugd01_centropoblados->findAll($this->indep('cen')));
  	//$this->set('datos_ins', array(''=>''));
  	$this->set('s_rep',$this->s('rep'));
  	$this->set('s_est',$this->s('est'));
  	$this->set('s_mun',$this->s('mun'));
  	//$this->set('s_par',$this->s('par'));
  	//$this->set('s_cen',$this->s('cen'));
  	$this->set('s_ins',$id);
  	$this->set('disabled','enabled');
  	$this->set('disabled2','disabled');
  	$this->set('read','');

  	}
 }

function guardar($id=null){
  	$this->layout ="ajax";
	$this->set('disabled','disabled');
  	$this->set('disabled2','enabled');
  	$this->set('read','readonly');

	//$cod_institucion = $this->data['cnmp06_instituto_educativo']['codigo'];
	//$cod_republica = $this->data['cnmp06_instituto_educativo']['cod_republica'];
	//$cod_estado = $this->data['cnmp06_instituto_educativo']['cod_estado'];
	//$cod_municipio = $this->data['cnmp06_instituto_educativo']['cod_municipio'];

	//$cod_parroquia = $this->data['cnmp06_instituto_educativo']['cod_parroquia'];
	//$cod_centro = $this->data['cnmp06_instituto_educativo']['cod_centro_poblado'];
	$denominacion = $this->data['cnmp06_instituto_educativo']['denominacion'];
	//$consulta="select *from cnmd06_instituto_educativo where cod_institucion='$cod_institucion' ";
	$sql="insert into cnmd06_instituto_educativo (denominacion)values('$denominacion')";
if($denominacion!=""){

		if($this->cnmd06_instituto_educativo->execute($sql)>1){
		$this->set('Message_existe','Los datos fueron guardados exitosamente');

	}else{
		$this->set('errorMessage','Los datos no fueron guardados');

     	}//fin else insersion

     	  echo "<script>";
              echo" document.getElementById('monto_deduccion').value = '';";
			echo "</script>";
}else{
	$this->set('errorMessage','Inserte la denominación');

}//fin else
    $this->set('accion',$this->cnmd06_instituto_educativo->findAll(null,null, 'cod_institucion ASC'));
 }//fin funcion


function modificar($id=null){
  	$this->layout ="ajax";
  	$this->set('disabled','disabled');
  	$this->set('disabled2','disabled');
  	$this->set('read','');
	$this->concatena($this->cugd01_republica->generateList('',' cod_republica ASC', null, '{n}.cugd01_republica.cod_republica', '{n}.cugd01_republica.denominacion'), 'tipo_rep');
	$this->concatena($this->cugd01_estados->generateList($this->indep('rep'),' cod_estado ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion'), 'tipo_est');
  	$this->concatena($this->cugd01_municipios->generateList($this->indep('est'),' cod_municipio ASC', null, '{n}.cugd01_municipios.cod_municipio', '{n}.cugd01_municipios.denominacion'), 'tipo_mun');
  	//$this->concatena($this->cugd01_parroquias->generateList($this->indep('mun'),' cod_parroquia ASC', null, '{n}.cugd01_parroquias.cod_parroquia', '{n}.cugd01_parroquias.denominacion'), 'tipo_par');
  	//$this->concatena($this->cugd01_centropoblados->generateList($this->indep('par'),' cod_centro ASC', null, '{n}.cugd01_centropoblados.cod_centro', '{n}.cugd01_centropoblados.denominacion'), 'tipo_cen');
   	$this->concatena($this->cnmd06_instituto_educativo->generateList($this->indep('mun'),' cod_institucion ASC', null, '{n}.cnmd06_instituto_educativo.cod_institucion', '{n}.cnmd06_instituto_educativo.denominacion'), 'tipo_ins');
	$this->set('datos_rep', $this->cugd01_republica->findAll($this->indep('rep')));
  	$this->set('datos_est', $this->cugd01_estados->findAll($this->indep('est')));
  	$this->set('datos_mun', $this->cugd01_municipios->findAll($this->indep('mun')));
  	//$this->set('datos_par', $this->cugd01_parroquias->findAll($this->indep('par')));
  	//$this->set('datos_cen', $this->cugd01_centropoblados->findAll($this->indep('cen')));
  	$this->set('datos_ins', $this->cnmd06_instituto_educativo->findAll($this->indep('mun').' and cod_institucion='.$id));
  	$this->set('s_rep',$this->s('rep'));
  	$this->set('s_est',$this->s('est'));
  	$this->set('s_mun',$this->s('mun'));
  	//$this->set('s_par',$this->s('par'));
  	//$this->set('s_cen',$this->s('cen'));
  	$this->set('s_ins',$id);

 }

 	  function guardar_modificar($id=null){
  	$this->layout ="ajax";
	$this->set('disabled','disabled');
  	$this->set('disabled2','enabled');
  	$this->set('read','readonly');

	$cod_institucion = $this->data['cnmp06_instituto_educativo']['cod_institucion'];
	$cod_republica = $this->data['cnmp06_instituto_educativo']['cod_republica'];
	$cod_estado = $this->data['cnmp06_instituto_educativo']['cod_estado'];
	$cod_municipio = $this->data['cnmp06_instituto_educativo']['cod_municipio'];
	$denominacion = $this->data['cnmp06_instituto_educativo']['denominacion_institucion'];
	$sql="update cnmd06_instituto_educativo set denominacion='$denominacion' where cod_institucion='$cod_institucion' ";
	if($cod_institucion==NULL){
		$this->set('mensajeError','El C&oacute;digo de instituci&oacute;n No pude estar vac&iacute;o');
		$this->set('disabled','enabled');
  		$this->set('disabled2','disabled');
  		$this->set('read','');
	}else if($denominacion==null){
		$this->set('mensajeError','La donominaci&oacute;n de la instituci&oacute;n No pude estar vac&iacute;a');
		$this->set('disabled','enabled');
  		$this->set('disabled2','disabled');
  		$this->set('read','');
	}else{
		if($this->cnmd06_instituto_educativo->execute($sql)>1){
		$this->set('mensaje','Los datos fueron modificados exitosamente');
	}else{
		$this->set('mensajeError','Los datos no fueron modificados');
	}//fin else insersion
}//fin de la verificacion de vacios

	$this->concatena($this->cugd01_republica->generateList('',' cod_republica ASC', null, '{n}.cugd01_republica.cod_republica', '{n}.cugd01_republica.denominacion'), 'tipo_rep');
	$this->concatena($this->cugd01_estados->generateList($this->indep('rep'),' cod_estado ASC', null, '{n}.cugd01_estados.cod_estado', '{n}.cugd01_estados.denominacion'), 'tipo_est');
  	$this->concatena($this->cugd01_municipios->generateList($this->indep('est'),' cod_municipio ASC', null, '{n}.cugd01_municipios.cod_municipio', '{n}.cugd01_municipios.denominacion'), 'tipo_mun');
  	$this->concatena($this->cugd01_parroquias->generateList($this->indep('mun'),' cod_parroquia ASC', null, '{n}.cugd01_parroquias.cod_parroquia', '{n}.cugd01_parroquias.denominacion'), 'tipo_par');
  	$this->concatena($this->cugd01_centropoblados->generateList($this->indep('par'),' cod_centro ASC', null, '{n}.cugd01_centropoblados.cod_centro', '{n}.cugd01_centropoblados.denominacion'), 'tipo_cen');
   	$this->concatena($this->cnmd06_instituto_educativo->generateList($this->indep('mun'),' cod_institucion ASC', null, '{n}.cnmd06_instituto_educativo.cod_institucion', '{n}.cnmd06_instituto_educativo.denominacion'), 'tipo_ins');
	$this->set('datos_rep', $this->cugd01_republica->findAll($this->indep('rep')));
  	$this->set('datos_est', $this->cugd01_estados->findAll($this->indep('est')));
  	$this->set('datos_mun', $this->cugd01_municipios->findAll($this->indep('mun')));
  	$this->set('datos_par', $this->cugd01_parroquias->findAll($this->indep('par')));
  	$this->set('datos_cen', $this->cugd01_centropoblados->findAll($this->indep('cen')));
  	//$this->set('datos_ins', $this->cnmd06_instituto_educativo->findAll($this->indep('mun').' and cod_institucion='.$id));
  	$this->set('s_rep',$this->s('rep'));
  	$this->set('s_est',$this->s('est'));
  	$this->set('s_mun',$this->s('mun'));
  	$this->set('s_par',$this->s('par'));
  	$this->set('s_cen',$this->s('cen'));
  	$this->set('s_ins','');

 }
 function eliminar($id=null){
  	$this->layout ="ajax";
	$this->set('disabled','disabled');
  	$this->set('disabled2','enabled');
  	$this->set('read','readonly');

	$cod_institucion = $this->data['cnmp06_instituto_educativo']['cod_institucion'];
	$cod_republica = $this->data['cnmp06_instituto_educativo']['cod_republica'];
	$cod_estado = $this->data['cnmp06_instituto_educativo']['cod_estado'];
	$cod_municipio = $this->data['cnmp06_instituto_educativo']['cod_municipio'];
	$denominacion = $this->data['cnmp06_instituto_educativo']['denominacion_institucion'];
	$sql="delete from cnmd06_instituto_educativo where cod_institucion='$id' ";

	if($this->cnmd06_instituto_educativo->execute($sql)>1){
		$this->set('mensaje','Los datos fueron eliminados exitosamente');
	}else{
		$this->set('mensajeError','Los datos no fueron eliminados');
	}//fin else insersion

 }

function consulta ($pagina=null) {
	$this->layout="ajax";
	if(isset($pagina)){
		$Tfilas=$this->cnmd06_instituto_educativo->findCount();
        if($Tfilas!=0){
        	$data=$this->cnmd06_instituto_educativo->findAll(null,null,"cod_republica, cod_estado, cod_municipio ASC",1,$pagina,null);

            $this->set('DATA',$data);
          	$this->set('siguiente',$pagina+1);
          	$this->set('anterior',$pagina-1);
            $this->bt_nav($Tfilas,$pagina);
        }else{
	 	       $this->set('errorMessage', 'No se encontrar&oacute;n datos');
	 	       $this->index();
	 	       $this->render("index");
        }

	}else{
		$pagina=1;
		$Tfilas=$this->cnmd06_instituto_educativo->findCount();
        if($Tfilas!=0){
        	$data=$this->cnmd06_instituto_educativo->findAll(null,null,"cod_republica, cod_estado, cod_municipio ASC",1,$pagina,null);
			$this->set('DATA',$data);
          	$this->set('siguiente',$pagina+1);
          	$this->set('anterior',$pagina-1);
          	$this->bt_nav($Tfilas,$pagina);
        }else{
	 	       $this->set('errorMessage', 'No se encontrar&oacute;n datos');
	 	       $this->index();
	 	       $this->render("index");
        }
	}

		foreach($data as $datos){
	    $cod_republica=  $datos['cnmd06_instituto_educativo']['cod_republica'];
	    $this->set('cod_republica',$cod_republica);
		$cod_estado=  $datos['cnmd06_instituto_educativo']['cod_estado'];
		$this->set('cod_estado',$cod_estado);
		$cod_municipio=  $datos['cnmd06_instituto_educativo']['cod_municipio'];
		$this->set('cod_municipio',$cod_municipio);
		$cod_institucion=  $datos['cnmd06_instituto_educativo']['cod_institucion'];
		$this->set('cod_institucion',$cod_institucion);
		$denominacion_institucion= $datos['cnmd06_instituto_educativo']['denominacion'];
		$this->set('denominacion_institucion',$denominacion_institucion);

	}
	$dataR=$this->cugd01_republica->findAll('cod_republica='.$cod_republica);
	foreach($dataR as $datosR){
		$denominacion_republica=  $datosR['cugd01_republica']['denominacion'];
	    $this->set('denominacion_republica',$denominacion_republica);
	}

	$dataE=$this->cugd01_estados->findAll('cod_republica='.$cod_republica.' and cod_estado='.$cod_estado);
	foreach($dataE as $datosE){
		$denominacion_estado=  $datosE['cugd01_estados']['denominacion'];
	    $this->set('denominacion_estado',$denominacion_estado);
	}

	$dataM=$this->cugd01_municipios->findAll('cod_republica='.$cod_republica.' and cod_estado='.$cod_estado.' and cod_municipio='.$cod_municipio);
	foreach($dataM as $datosM){
		$denominacion_municipio=  $datosM['cugd01_municipios']['denominacion'];
	    $this->set('denominacion_municipio',$denominacion_municipio);
	}
}

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


function modificar_consulta($rep=null,$est=null,$mun=null,$ins=null,$den=null){
 	$this->layout ="ajax";
 	$this->set('disabled','disabled');
  	$this->set('disabled2','enabled');
  	$this->set('read','');
	//echo $rep,$est,$mun,$ins,$den;
	$this->set('cod_republica',$rep);
	$this->set('cod_estado',$est);
	$this->set('cod_municipio',$mun);
	$this->set('cod_institucion',$ins);
	$this->set('denominacion_institucion',$den);
	$this->set('datos_rep', $this->cugd01_republica->findAll('cod_republica='.$rep));
  	$this->set('datos_est', $this->cugd01_estados->findAll('cod_republica='.$rep.' and cod_estado='.$est));
  	$this->set('datos_mun', $this->cugd01_municipios->findAll('cod_republica='.$rep.' and cod_estado='.$est.' and cod_municipio='.$mun));
  	$this->Session->write('den',$den);
	$this->Session->write('s_est',$est);
	$this->Session->write('s_mun',$mun);
	$this->Session->write('s_ins',$ins);
}

function guardar_modificar_consulta($rep=null,$est=null,$mun=null,$ins=null,$den=null){
	$this->layout ="ajax";
	$this->set('disabled','disabled');
  	$this->set('disabled2','enabled');
  	$this->set('read','readonly');
  	$den = $this->data['cnmp06_instituto_educativo']['denominacion_institucion'];
  	//echo $rep,$est,$mun,$ins,$den.'<br>';
	$sql="update cnmd06_instituto_educativo set denominacion='$den' where cod_institucion='$ins' ";
	if($den==null){
		$this->set('mensajeError','La donominaci&oacute;n de la instituci&oacute;n No pude estar vac&iacute;a');
		$this->set('disabled','enabled');
  		$this->set('disabled2','disabled');
  		$this->set('read','');
	}else{
		if($this->cnmd06_instituto_educativo->execute($sql)>1){
		$this->set('mensaje','Los datos fueron modificados exitosamente');
	}else{
		$this->set('mensajeError','Los datos no fueron modificados');
	}//fin else insersion
}//fin de la verificacion de vacios
$this->consulta();


 }

  function eliminar_consulta($rep=null,$est=null,$mun=null,$ins=null,$den=null){
 	$this->layout ="ajax";
	$this->set('disabled','disabled');
  	$this->set('disabled2','enabled');
  	$this->set('read','readonly');

	$sql="delete from cnmd06_instituto_educativo where cod_institucion='$ins' ";

	if($this->cnmd06_instituto_educativo->execute($sql)>1){
		$this->set('mensaje','Los datos fueron eliminados exitosamente');
	}else{
		$this->set('mensajeError','Los datos no fueron eliminados');
	}//fin else insersion
$this->consulta();

}


 function salir(){
 	$this->layout ="ajax";
 	$this->concatena($this->cugd01_republica->generateList('',' cod_republica ASC', null, '{n}.cugd01_republica.cod_republica', '{n}.cugd01_republica.denominacion'), 'tipo_rep');
}

}
?>
