<?php
/*
 * Created on 19/07/2007
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

 class ReformulacionReactualizacionController extends AppController{
	  var $name = 'reformulacion_reactualizacion';
     var $uses = array('cepd02_contratoservicio_partidas','cepd02_contratoservicio_cuerpo','cobd01_contratoobras_partidas','cfpd20','cfpd10_reformulacion_texto','cfpd10_reformulacion_partidas','cfpd05','cugd04','cobd01_contratoobras_cuerpo');
     var $helpers = array('Html','Ajax','Javascript', 'Sisap');
    // var $paginate = array('limit' => 3, 'page' => 1);
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
    	/**
    	 * Funcion que permite leer las varibles de session. la cual permite capturar los codigos del usuario
    	 * para ser insertados en todas las tablas.
    	 * */
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
         $sql_re .= "cod_inst=".$this->verifica_SS(4)."  and  ";
         if($ano!=null){
         	$sql_re .= "cod_dep=".$this->verifica_SS(5)."  and  ";
            $sql_re .= "ano=".$ano."  ";
         }else{
         	$sql_re .= "cod_dep=".$this->verifica_SS(5)." ";
         }
         return $sql_re;
    }//fin funcion SQLCA

        function SQLCX($ano=null){//sql para busqueda de codigos de arranque con y sin año
         $sql_re = "cod_presi=".$this->verifica_SS(1)."  and    ";
         $sql_re .= "cod_entidad=".$this->verifica_SS(2)."  and  ";
         $sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)."  and ";
         $sql_re .= "cod_inst=".$this->verifica_SS(4)." ";
		 return $sql_re;


    }//fin funcion SQLX

function concatena4($vector1=null, $nomVar=null, $extra=null){
	$cod = array();
	if($vector1 != null){
		foreach($vector1 as $x => $y){
			if($extra!=null){
				if($x<10){
					$cod[$x] = $extra.'.0'.$x.' - '.$y;
				}else if($x>=10 && $x<=99){
					$cod[$x] = $extra.'.'.$x.' - '.$y;
				}
			}else{

				if($x<10){
					$cod[$x] = '0'.$x.' - '.$y;
				}else if($x>=10 && $x<=9999){
					$cod[$x] = $x.' - '.$y;
				}
			}
		}
		//print_r($cod);
	}

	$this->set($nomVar, $cod);
}//fin function


    function AddCeroR($n,$extra=null){
   	  if($n!=null){
   	  	  if($extra==null){
        	if($n<10){
        	   $Var="0".$n;
        	}else{
	           $Var=$n;
        	}
   	  }else{
        	if($n<10){
        	   $Var=$extra.".0".$n;
        	}else{
	           $Var=$extra.".".$n;
        	}
   	  }

   	  $Var = substr($Var, - 2);

   	return $Var;
   	  }else{
   	  	  //return $Var;
   	  }



   }//fin AddCero


        function AddCero($nomVar,$vector=object,$extra=null){
   	  if($vector!=null){
   	  	  if($extra==null){
   	  	foreach($vector as $x){
        	if($x<10){
        	   $Var[$x]="0".$x;
        	}else{
	           $Var[$x]=$x;
        	}
	    }//fin each
   	  }else{
          foreach($vector as $x){
        	if($x<10){
        	   $Var[$x]=$extra.".0".$x;
        	}else{
	           $Var[$x]=$extra.".".$x;
        	}
	    }//fin each
   	  }
   	  $this->set($nomVar,$Var);
   	  }else{
   	  	  $this->set($nomVar,'');
   	  }



   }//fin AddCero


   function Formato1($monto) {
    $monto = preg_replace("/[^0-9\.]/", "", str_replace(',','.',$monto));
    if (substr($monto,-3,1)=='.') {
        $sents = '.'.substr($monto,-2);
        $monto = substr($monto,0,strlen($monto)-3);
    } elseif (substr($monto,-2,1)=='.') {
        $sents = '.'.substr($monto,-1);
        $monto = substr($monto,0,strlen($monto)-2);
    } else {
        $sents = '.00';
    }
    $monto = preg_replace("/[^0-9]/", "", $monto);
    return number_format($monto.$sents,2,'.','');
    }

function Formato2($monto){
    	return number_format($monto,2,",",".");
    }



    function index(){
    $this->layout = "ajax";
    }


function eliminar_creditos(){
	$this->layout = "ajax";
	$eli='update cfpd05 set credito_adicional_anual=0, credito_adicional_ene=0, credito_adicional_feb=0, credito_adicional_mar=0, credito_adicional_abr=0, credito_adicional_may=0, credito_adicional_jun=0, credito_adicional_jul=0, credito_adicional_ago=0, credito_adicional_sep=0, credito_adicional_oct=0, credito_adicional_nov=0, credito_adicional_dic=0 where ano=2008';
	$this->cfpd05->execute($eli);
	$this->set('Message_existe', 'Creditos adicionales actualizados correctamente');
			$this->index();
			$this->render("index");
}

function eliminar_rebajas(){
	$this->layout = "ajax";
	$eli='update cfpd05 set rebaja_anual=0, rebaja_ene=0, rebaja_feb=0, rebaja_mar=0, rebaja_abr=0, rebaja_may=0, rebaja_jun=0, rebaja_jul=0, rebaja_ago=0, rebaja_sep=0, rebaja_oct=0, rebaja_nov=0, rebaja_dic=0 where ano=2008';
	$this->cfpd05->execute($eli);
	$this->set('Message_existe', 'rebajas actualizadas correctamente');
			$this->index();
			$this->render("index");
}

function eliminar_aumentos(){
	$this->layout = "ajax";
	$eli='update cfpd05 set aumento_traslado_anual=0, aumento_traslado_ene=0, aumento_traslado_feb=0, aumento_traslado_mar=0, aumento_traslado_abr=0, aumento_traslado_may=0, aumento_traslado_jun=0, aumento_traslado_jul=0, aumento_traslado_ago=0, aumento_traslado_sep=0, aumento_traslado_oct=0, aumento_traslado_nov=0, aumento_traslado_dic=0 where ano=2008';
	$this->cfpd05->execute($eli);
	$this->set('Message_existe', 'Aumentos actualizados correctamente');
			$this->index();
			$this->render("index");
}

function eliminar_disminucion(){
	$this->layout = "ajax";
	$eli='update cfpd05 set disminucion_traslado_anual=0, disminucion_traslado_ene=0, disminucion_traslado_feb=0, disminucion_traslado_mar=0, disminucion_traslado_abr=0, disminucion_traslado_may=0, disminucion_traslado_jun=0, disminucion_traslado_jul=0, disminucion_traslado_ago=0, disminucion_traslado_sep=0, disminucion_traslado_oct=0, disminucion_traslado_nov=0, disminucion_traslado_dic=0 where ano=2008';
	$this->cfpd05->execute($eli);
	$this->set('Message_existe', 'disminucion actualizadas correctamente');
			$this->index();
			$this->render("index");
}

function eliminar_cfpd20(){
	$this->layout = "ajax";
	$eli='delete from cfpd20 where ano=2008';
	$this->cfpd20->execute($eli);
	$this->set('Message_existe', 'Partidas Eliminadas Correctamente');
			$this->index();
			$this->render("index");
}


///////////////////////////////////////////////////////rebaja

function actualizar_cfpd05_rebaja(){
	$temporal = $this->cfpd10_reformulacion_partidas->findAll();
	$monto_disminuir_todo=0;
	$sql_update_cfpd05 = '';
$c=0;
$m=0;
	$texto = $this->cfpd10_reformulacion_texto->findAll('cod_tipo=3');

	foreach($texto as $txt){
	$codigo = $txt['cfpd10_reformulacion_texto']['cod_tipo'];
	$numero_oficio = $txt['cfpd10_reformulacion_texto']['numero_oficio'];
	$numero_decreto = $txt['cfpd10_reformulacion_texto']['numero_decreto'];
	$fecha_decreto = $txt['cfpd10_reformulacion_texto']['fecha_decreto'];

	if($codigo==3){
		$ofi="numero_oficio='".$numero_oficio."'";
		$texto2 = $this->cfpd10_reformulacion_partidas->findAll($ofi);

		foreach($texto2 as $row2){
	$codigo=3;
	$var[1] = $row2['cfpd10_reformulacion_partidas']['cod_presi'];
	$var[2] = $row2['cfpd10_reformulacion_partidas']['cod_entidad'];
	$var[3] = $row2['cfpd10_reformulacion_partidas']['cod_tipo_inst'];
	$var[4] = $row2['cfpd10_reformulacion_partidas']['cod_inst'];
	$var[5] = $row2['cfpd10_reformulacion_partidas']['cod_dep'];
	$var[6] = $row2['cfpd10_reformulacion_partidas']['ano_reformulacion'];
	$var[7] = $row2['cfpd10_reformulacion_partidas']['numero_oficio'];
	$var[8] = $row2['cfpd10_reformulacion_partidas']['codi_dep'];
	$var[9] = $row2['cfpd10_reformulacion_partidas']['ano'];
	$var[10] = $row2['cfpd10_reformulacion_partidas']['cod_sector'];
	$var[11] = $row2['cfpd10_reformulacion_partidas']['cod_programa'];
	$var[12] = $row2['cfpd10_reformulacion_partidas']['cod_sub_prog'];
	$var[13] = $row2['cfpd10_reformulacion_partidas']['cod_proyecto'];
	$var[14] = $row2['cfpd10_reformulacion_partidas']['cod_activ_obra'];
	$var[15] = $row2['cfpd10_reformulacion_partidas']['cod_partida'];
	$var[16] = $row2['cfpd10_reformulacion_partidas']['cod_generica'];
	$var[17] = $row2['cfpd10_reformulacion_partidas']['cod_especifica'];
	$var[18] = $row2['cfpd10_reformulacion_partidas']['cod_sub_espec'];
	$var[19] = $row2['cfpd10_reformulacion_partidas']['cod_auxiliar'];
	$var[20] = $row2['cfpd10_reformulacion_partidas']['monto_disminucion'];
	$var[21] = $row2['cfpd10_reformulacion_partidas']['monto_aumento'];
	$monto_disminuir= $var[20];
	$c++;

if($codigo==3 && $var[20]!=0){
	$m++;
	echo $m;
	//echo "entro rebaja";
	$cp = $this->crear_partida($var[9], $var[10], $var[11], $var[12], $var[13], $var[14], $var[15],$var[16], $var[17], $var[18],$var[19]);
						   $to = 1;
						   $td = 1;
						   $ta = 4;
						   $mt = $this->Formato1($var[20]);
						   $ccp = '';
						   $dnco = $this->motor_presupuestario($cp, $to ,$td, $ta, "$fecha_decreto", $mt, $ccp,$var[6],$numero_decreto,null, null, null,null, null, null,null, null, null, null, null,$var[8]);
							if($dnco != false){
								$numero_control_compromiso=$dnco;
							}else{
								$numero_control_compromiso=null;
								break;
							}

}

	}

	}

	}
///////////////fin motor
 			$this->set('Message_existe', 'Partidas Actualizadas correctamente');
			$this->index();
			$this->render("index");

}
//////////////////////////////////////////////////////traslado
function actualizar_cfpd05_traslado(){
	$temporal = $this->cfpd10_reformulacion_partidas->findAll();
	$monto_disminuir_todo=0;
	$sql_update_cfpd05 = '';
$c=0;
	$fe='1900-01-01';
	$texto = $this->cfpd10_reformulacion_texto->findAll("cod_tipo=1 and fecha_decreto!='".$fe."'");

	foreach($texto as $txt){
	$codigo = $txt['cfpd10_reformulacion_texto']['cod_tipo'];
	$numero_oficio = $txt['cfpd10_reformulacion_texto']['numero_oficio'];
	$numero_decreto = $txt['cfpd10_reformulacion_texto']['numero_decreto'];
	$fecha_decreto = $txt['cfpd10_reformulacion_texto']['fecha_decreto'];
	//echo $fecha_decreto;
	if($codigo==1){
		$ofi="numero_oficio='".$numero_oficio."'";
		$texto2 = $this->cfpd10_reformulacion_partidas->findAll($ofi);

		foreach($texto2 as $row2){
	$codigo=1;
	$var[1] = $row2['cfpd10_reformulacion_partidas']['cod_presi'];
	$var[2] = $row2['cfpd10_reformulacion_partidas']['cod_entidad'];
	$var[3] = $row2['cfpd10_reformulacion_partidas']['cod_tipo_inst'];
	$var[4] = $row2['cfpd10_reformulacion_partidas']['cod_inst'];
	$var[5] = $row2['cfpd10_reformulacion_partidas']['cod_dep'];
	$var[6] = $row2['cfpd10_reformulacion_partidas']['ano_reformulacion'];
	$var[7] = $row2['cfpd10_reformulacion_partidas']['numero_oficio'];
	$var[8] = $row2['cfpd10_reformulacion_partidas']['codi_dep'];
	$var[9] = $row2['cfpd10_reformulacion_partidas']['ano'];
	$var[10] = $row2['cfpd10_reformulacion_partidas']['cod_sector'];
	$var[11] = $row2['cfpd10_reformulacion_partidas']['cod_programa'];
	$var[12] = $row2['cfpd10_reformulacion_partidas']['cod_sub_prog'];
	$var[13] = $row2['cfpd10_reformulacion_partidas']['cod_proyecto'];
	$var[14] = $row2['cfpd10_reformulacion_partidas']['cod_activ_obra'];
	$var[15] = $row2['cfpd10_reformulacion_partidas']['cod_partida'];
	$var[16] = $row2['cfpd10_reformulacion_partidas']['cod_generica'];
	$var[17] = $row2['cfpd10_reformulacion_partidas']['cod_especifica'];
	$var[18] = $row2['cfpd10_reformulacion_partidas']['cod_sub_espec'];
	$var[19] = $row2['cfpd10_reformulacion_partidas']['cod_auxiliar'];
	$var[20] = $row2['cfpd10_reformulacion_partidas']['monto_disminucion'];
	$var[21] = $row2['cfpd10_reformulacion_partidas']['monto_aumento'];
	$monto_disminuir= $var[20];
	$c++;

if($codigo==1 && $var[20]!=0){
//echo "entro traslado disminucion";
	$cp = $this->crear_partida($var[9], $var[10], $var[11], $var[12], $var[13], $var[14], $var[15],$var[16], $var[17], $var[18],$var[19]);
						   $to = 1;
						   $td = 1;
						   $ta = 2;
						   $mt = $this->Formato1($var[20]);
						   $ccp = '';
						   $dnco = $this->motor_presupuestario($cp, $to ,$td, $ta, "$fecha_decreto", $mt, $ccp,$var[6],$numero_decreto,null, null, null,null, null, null,null, null, null, null, null,$var[8]);
							if($dnco != false){
								$numero_control_compromiso=$dnco;
							}else{
								$numero_control_compromiso=null;
								break;
							}
}
if($codigo==1 && $var[21]!=0){
//echo "entro traslado aumentto";
	$cp = $this->crear_partida($var[9], $var[10], $var[11], $var[12], $var[13], $var[14], $var[15],$var[16], $var[17], $var[18],$var[19]);
						   $to = 1;
						   $td = 1;
						   $ta = 1;
						   $mt = $this->Formato1($var[21]);
						   $ccp = '';
						   $dnco = $this->motor_presupuestario($cp, $to ,$td, $ta, "$fecha_decreto", $mt, $ccp,$var[6],$numero_decreto,null, null, null,null, null, null,null, null, null, null, null,$var[8]);
							if($dnco != false){
								$numero_control_compromiso=$dnco;
							}else{
								$numero_control_compromiso=null;
								break;
							}

}

	}

	}

	}
///////////////fin motor
 			$this->set('Message_existe', 'Partidas Actualizadas correctamente');
			$this->index();
			$this->render("index");

}

//////////////////////////////////////////////////creditos

function actualizar_cfpd05_creditos(){
	$temporal = $this->cfpd10_reformulacion_partidas->findAll();
	$monto_disminuir_todo=0;
	$sql_update_cfpd05 = '';
$c=0;
$m=0;
$mto=0;
	$texto = $this->cfpd10_reformulacion_texto->findAll('cod_tipo=2');

	foreach($texto as $txt){
	$codigo = $txt['cfpd10_reformulacion_texto']['cod_tipo'];
	$numero_oficio = $txt['cfpd10_reformulacion_texto']['numero_oficio'];
	$numero_decreto = $txt['cfpd10_reformulacion_texto']['numero_decreto'];
	$fecha_decreto = $txt['cfpd10_reformulacion_texto']['fecha_decreto'];

	if($codigo==2){
		$ofi="numero_oficio='".$numero_oficio."'";
		$texto2 = $this->cfpd10_reformulacion_partidas->findAll($ofi);

		foreach($texto2 as $row2){
	$codigo=2;
	$var[1] = $row2['cfpd10_reformulacion_partidas']['cod_presi'];
	$var[2] = $row2['cfpd10_reformulacion_partidas']['cod_entidad'];
	$var[3] = $row2['cfpd10_reformulacion_partidas']['cod_tipo_inst'];
	$var[4] = $row2['cfpd10_reformulacion_partidas']['cod_inst'];
	$var[5] = $row2['cfpd10_reformulacion_partidas']['cod_dep'];
	$var[6] = $row2['cfpd10_reformulacion_partidas']['ano_reformulacion'];
	$var[7] = $row2['cfpd10_reformulacion_partidas']['numero_oficio'];
	$var[8] = $row2['cfpd10_reformulacion_partidas']['codi_dep'];
	$var[9] = $row2['cfpd10_reformulacion_partidas']['ano'];
	$var[10] = $row2['cfpd10_reformulacion_partidas']['cod_sector'];
	$var[11] = $row2['cfpd10_reformulacion_partidas']['cod_programa'];
	$var[12] = $row2['cfpd10_reformulacion_partidas']['cod_sub_prog'];
	$var[13] = $row2['cfpd10_reformulacion_partidas']['cod_proyecto'];
	$var[14] = $row2['cfpd10_reformulacion_partidas']['cod_activ_obra'];
	$var[15] = $row2['cfpd10_reformulacion_partidas']['cod_partida'];
	$var[16] = $row2['cfpd10_reformulacion_partidas']['cod_generica'];
	$var[17] = $row2['cfpd10_reformulacion_partidas']['cod_especifica'];
	$var[18] = $row2['cfpd10_reformulacion_partidas']['cod_sub_espec'];
	$var[19] = $row2['cfpd10_reformulacion_partidas']['cod_auxiliar'];
	$var[20] = $row2['cfpd10_reformulacion_partidas']['monto_disminucion'];
	$var[21] = $row2['cfpd10_reformulacion_partidas']['monto_aumento'];
	$monto_disminuir= $var[20];
	$c++;

if($codigo==2 && $var[21]!=0){
	//echo "entro credito Adicional";
	//$m++;
	//echo $m;
	$cp = $this->crear_partida($var[9], $var[10], $var[11], $var[12], $var[13], $var[14], $var[15],$var[16], $var[17], $var[18],$var[19]);
						   $to = 1;
						   $td = 1;
						   $ta = 3;
						   $mt = $this->Formato1($var[21]);
						   $mto=$mto+$mt;
						   $ccp = '';
						   $dnco = $this->motor_presupuestario($cp, $to ,$td, $ta, "$fecha_decreto", $mt, $ccp,$var[6],$numero_decreto,null, null, null,null, null, null,null, null, null, null, null,$var[8]);
							if($dnco != false){
								$numero_control_compromiso=$dnco;
							}else{
								$numero_control_compromiso=null;
								break;
							}

}

	}

	}

	}echo $mto;
///////////////fin motor
 			$this->set('Message_existe', 'Partidas Actualizadas correctamente');
			$this->index();
			$this->render("index");

}


function reactualizar_obras(){
	$this->layout = "ajax";
	/*$fecha='2008-01-01';
	$cond="select numero_contrato_obra from cobd01_contratoobras_cuerpo where cod_inst=11 and (fecha_contrato_obra BETWEEN '01/01/2000' AND '31/12/2007')";

	//fecha_contrato_obra between '2005-01-01' and '2007-12-31'";

	$numeros_contratos=$this->cobd01_contratoobras_cuerpo->execute($cond);
	foreach($numeros_contratos as $nc){
		$numero=$nc[0]['numero_contrato_obra'];
		$obras="select numero_contrato_obra, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar from cobd01_contratoobras_partidas where numero_contrato_obra='".$numero."'";
		$partidas=$this->cobd01_contratoobras_partidas->execute($obras);


		pr($partidas);
	}
//$partidas=$this->cobd01_contratoobras_partidas->findAll("numero_contrato_obra='".$numero."'");
*/
	$sql="select a.*,c.monto,c.ano_contrato_obra,c.numero_contrato_obra ,b.fecha_decreto,d.fecha_contrato_obra
                    from cfpd10_reformulacion_partidas a, cfpd10_reformulacion_texto b,cobd01_contratoobras_partidas c,cobd01_contratoobras_cuerpo d
                    where a.cod_presi=b.cod_presi and
                    a.cod_entidad=b.cod_entidad and
                    a.cod_tipo_inst=b.cod_tipo_inst and
                    a.cod_inst=b.cod_inst and
                    a.cod_dep=b.cod_dep and
                    a.numero_oficio=b.numero_oficio and
                    a.ano=c.ano and
                    a.cod_sector=c.cod_sector and
                    a.cod_programa=c.cod_programa and
                    a.cod_sub_prog=c.cod_sub_prog and
                    a.cod_proyecto=c.cod_proyecto and
                    a.cod_activ_obra=c.cod_activ_obra and
                    a.cod_partida=c.cod_partida and
                    a.cod_generica=c.cod_generica and
                    a.cod_especifica=c.cod_especifica and
                    a.cod_sub_espec=c.cod_sub_espec and
                    a.cod_auxiliar=c.cod_auxiliar and c.numero_contrato_obra=d.numro_contrato_obra and d.fecha_contrato_obra<'2008-01-01' order by d.fecha_contrato_obra ASC";

	$todo=$this->cobd01_contratoobras_cuerpo->execute($sql);

	foreach($todo as $todos){
		$numero_contrato=$todos[0]['numero_contrato_obra'];
		$fecha_contrato=$todos[0]['fecha_contrato_obra'];
		$fecha_decreto=$todos[0]['fecha_decreto'];
		$cod_sector=$todos[0]['cod_sector'];
		$cod_programa=$todos[0]['cod_programa'];
		$cod_sub_prog=$todos[0]['cod_sub_prog'];
		$cod_activ_obra=$todos[0]['cod_activ_obra'];
		$cod_partida=$todos[0]['cod_partida'];
		$cod_generica=$todos[0]['cod_generica'];
		$cod_especifica=$todos[0]['cod_especifica'];
		$cod_sub_espec=$todos[0]['cod_sub_espec'];
		$cod_auxiliar=$todos[0]['cod_auxiliar'];

		$actualizar="update cobd01_contratoobras_cuerpo set fecha_contrato_obra='".$fecha_decreto."' where numero_contrato_obra='".$numero_contrato."'";
		$this->cobd01_contratoobras_cuerpo->execute($actualizar);
	}

	//pr($todo);


	$this->index();
	$this->render("index");


}


function reactualizar_servicios(){
	$this->layout = "ajax";
	/*$fecha='2008-01-01';
	$cond="select numero_contrato_obra from cobd01_contratoobras_cuerpo where cod_inst=11 and (fecha_contrato_obra BETWEEN '01/01/2000' AND '31/12/2007')";

	//fecha_contrato_obra between '2005-01-01' and '2007-12-31'";

	$numeros_contratos=$this->cobd01_contratoobras_cuerpo->execute($cond);
	foreach($numeros_contratos as $nc){
		$numero=$nc[0]['numero_contrato_obra'];
		$obras="select numero_contrato_obra, cod_sector, cod_programa, cod_sub_prog, cod_proyecto, cod_activ_obra, cod_partida, cod_generica, cod_especifica, cod_sub_espec, cod_auxiliar from cobd01_contratoobras_partidas where numero_contrato_obra='".$numero."'";
		$partidas=$this->cobd01_contratoobras_partidas->execute($obras);


		pr($partidas);
	}
//$partidas=$this->cobd01_contratoobras_partidas->findAll("numero_contrato_obra='".$numero."'");
*/
	$sql="select a.*,c.monto,c.ano_contrato_servicio,c.numero_contrato_servicio ,b.fecha_decreto,d.fecha_contrato_servicio
                    from cfpd10_reformulacion_partidas a, cfpd10_reformulacion_texto b,cepd02_contratoservicio_partidas c,cepd02_contratoservicio_cuerpo d
                    where a.cod_presi=b.cod_presi and
                    a.cod_entidad=b.cod_entidad and
                    a.cod_tipo_inst=b.cod_tipo_inst and
                    a.cod_inst=b.cod_inst and
                    a.cod_dep=b.cod_dep and
                    a.numero_oficio=b.numero_oficio and
                    a.ano=c.ano and
                    a.cod_sector=c.cod_sector and
                    a.cod_programa=c.cod_programa and
                    a.cod_sub_prog=c.cod_sub_prog and
                    a.cod_proyecto=c.cod_proyecto and
                    a.cod_activ_obra=c.cod_activ_obra and
                    a.cod_partida=c.cod_partida and
                    a.cod_generica=c.cod_generica and
                    a.cod_especifica=c.cod_especifica and
                    a.cod_sub_espec=c.cod_sub_espec and
                    a.cod_auxiliar=c.cod_auxiliar and c.numero_contrato_servicio=d.numero_contrato_servicio and d.fecha_contrato_servicio<'2008-01-01' order by d.fecha_contrato_servicio ASC";

	$todo=$this->cepd02_contratoservicio_cuerpo->execute($sql);

	foreach($todo as $todos){
		$numero_contrato=$todos[0]['numero_contrato_servicio'];
		$fecha_contrato=$todos[0]['fecha_contrato_servicio'];
		$fecha_decreto=$todos[0]['fecha_decreto'];
		$cod_sector=$todos[0]['cod_sector'];
		$cod_programa=$todos[0]['cod_programa'];
		$cod_sub_prog=$todos[0]['cod_sub_prog'];
		$cod_activ_obra=$todos[0]['cod_activ_obra'];
		$cod_partida=$todos[0]['cod_partida'];
		$cod_generica=$todos[0]['cod_generica'];
		$cod_especifica=$todos[0]['cod_especifica'];
		$cod_sub_espec=$todos[0]['cod_sub_espec'];
		$cod_auxiliar=$todos[0]['cod_auxiliar'];

		$actualizar="update cepd02_contratoservicio_cuerpo set fecha_contrato_servicio='".$fecha_decreto."' where numero_contrato_servicio='".$numero_contrato."'";
		$this->cepd02_contratoservicio_cuerpo->execute($actualizar);
	}

	//pr($todo);


	$this->index();
	$this->render("index");


}




}//fin clase cfpp09Controller