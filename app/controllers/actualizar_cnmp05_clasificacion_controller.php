<?php


class Actualizarcnmp05clasificacionController extends AppController{

    var $uses = array('cnmd05_clasificacion', 'cnmd05', 'cnmd04_ocupacion', 'cnmd04_tipo', 'cfpd01_formulacion');
    var $name = 'actualizar_cnmp05_clasificacion';
    var $helpers = array('Html','Ajax','Javascript', 'Sisap','Fpdf');


function checkSession(){
        // If the session info hasn't been set...
        if (!$this->Session->check('Usuario'))
        {
            // Force the user to login
            $this->redirect('/salir/');
            exit();
        }
    }


function beforeFilter(){
     $this->checkSession();

    }



function index(){
	$this->layout="ajax";
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;
	$year = $this->cfpd01_formulacion->findAll($condicion, null, 'ano_formular ASC', null);
	$dato = null;
	foreach($year as $year){$dato = $year['cfpd01_formulacion']['ano_formular'];}
    if(!empty($dato)){$this->set('year', $dato);}else{$this->set('year', '');}
    $this->set('entidad_federal', $this->Session->read('entidad_federal'));
	$this->set('dependencia', $this->Session->read('SScoddep'));
}//fin function



function forma_2006($year=null){

	$sql='';

    $this->layout = "ajax";
    $this->limpia_menu();
    $this->set('ir', 'no');

	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;


    $year = $this->cfpd01_formulacion->findAll($condicion, null, 'ano_formular ASC', null);
	$dato = null;
	foreach($year as $year){
		$dato = $year['cfpd01_formulacion']['ano_formular'];
	}

	if(!empty($dato)){

		$this->set('year', $dato);

	}else{
		$this->set('year', '');
	}


	$i = 0;
	$xi = 0;
	$j = 0;
	$k = 0;
	$l = 0;
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$year = $dato;
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and ano=".$year;


    $cnmd04_ocupacion = $this->cnmd04_ocupacion->findAll();
    $cnmd04_tipo = $this->cnmd04_tipo->findAll();
    $cnmd05 = $this->cnmd05->findAll($condicion, null, 'cod_sector, cod_programa ASC');
    $cnmd05_clasificacion_general = $this->cnmd05_clasificacion_general->findAll($condicion, null, null);
    $cnmd01 = $this->cnmd01->findAll("cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst);

    foreach($cnmd04_ocupacion as $aux_a){ $i++;
		$var_cnmd04_ocupacion[$i]['cod_nivel_i'] = $aux_a['cnmd04_ocupacion']['cod_nivel_i'];
		$var_cnmd04_ocupacion[$i]['cod_nivel_ii'] = $aux_a['cnmd04_ocupacion']['cod_nivel_ii'];
		$var_cnmd04_ocupacion[$i]['denominacion'] = $aux_a['cnmd04_ocupacion']['denominacion'];
    }//fin foreach

   foreach($cnmd01 as $aux_cnmd01){ $xi++;
		$var_cnmd01[$xi]['cod_presi']          		  =    $aux_cnmd01['Cnmd01']['cod_presi'];
		$var_cnmd01[$xi]['cod_entidad']        		  =    $aux_cnmd01['Cnmd01']['cod_entidad'];
		$var_cnmd01[$xi]['cod_tipo_inst']      		  =    $aux_cnmd01['Cnmd01']['cod_tipo_inst'];
		$var_cnmd01[$xi]['cod_inst']           		  =    $aux_cnmd01['Cnmd01']['cod_inst'];
		$var_cnmd01[$xi]['cod_dep']            		  =    $aux_cnmd01['Cnmd01']['cod_dep'];
		$var_cnmd01[$xi]['frecuencia_cobro']   		  =    $aux_cnmd01['Cnmd01']['frecuencia_cobro'];
		$var_cnmd01[$xi]['cod_tipo_nomina']   		  =    $aux_cnmd01['Cnmd01']['cod_tipo_nomina'];
		$var_cnmd01[$xi]['clasificacion_personal']    =    $aux_cnmd01['Cnmd01']['clasificacion_personal'];

    }//fin foreach

    foreach($cnmd04_tipo as $aux_b){ $j++;
		$var_cnmd04_tipo[$j]['cod_nivel_i']  = $aux_b['cnmd04_tipo']['cod_nivel_i'];
		$var_cnmd04_tipo[$j]['denominacion'] = $aux_b['cnmd04_tipo']['denominacion'];
    }//fin foreach

     foreach($cnmd05 as $aux_c){ $k++;
		$var_cnmd05[$k]['cod_tipo_nomina']          =     $aux_c['cnmd05']['cod_tipo_nomina'];
		$var_cnmd05[$k]['cod_sector']               =     $aux_c['cnmd05']['cod_sector'];
		$var_cnmd05[$k]['cod_programa']             =     $aux_c['cnmd05']['cod_programa'];
		$var_cnmd05[$k]['cod_nivel_i']              =     $aux_c['cnmd05']['cod_nivel_i'];
		$var_cnmd05[$k]['cod_nivel_ii']             =     $aux_c['cnmd05']['cod_nivel_ii'];
	  //$var_cnmd05[$k]['numero_cargo_anterior']    =     $aux_c['cnmd05']['numero_cargo_anterior'];
		$var_cnmd05[$k]['sueldo_basico']            =     $aux_c['cnmd05']['sueldo_basico'];
		$var_cnmd05[$k]['compensaciones']           =     $aux_c['cnmd05']['compensaciones'];
		$var_cnmd05[$k]['primas']                   =     $aux_c['cnmd05']['primas'];
		$var_cnmd05[$k]['cod_dep']                  =     $aux_c['cnmd05']['cod_dep'];

    }//fin foreach


     foreach($cnmd05_clasificacion_general as $aux_d){ $l++;
		$var_cnmd05_clasificacion_general[$l]['cod_nivel_i']              =     $aux_d['cnmd05_clasificacion_general']['cod_nivel_i'];
		$var_cnmd05_clasificacion_general[$l]['cod_nivel_ii']             =     $aux_d['cnmd05_clasificacion_general']['cod_nivel_ii'];
		$var_cnmd05_clasificacion_general[$l]['numero_cargo_anterior']    =     $aux_d['cnmd05_clasificacion_general']['numero_cargo_anterior'];
		$var_cnmd05_clasificacion_general[$l]['sueldo_anterior']          =     $aux_d['cnmd05_clasificacion_general']['sueldo_anterior'];
		$var_cnmd05_clasificacion_general[$l]['compensaciones_anterior']  =     $aux_d['cnmd05_clasificacion_general']['compensaciones_anterior'];
		$var_cnmd05_clasificacion_general[$l]['primas_anterior']          =     $aux_d['cnmd05_clasificacion_general']['primas_anterior'];
		$var_cnmd05_clasificacion_general[$l]['numero_cargo_actual']      =     $aux_d['cnmd05_clasificacion_general']['numero_cargo_actual'];
		$var_cnmd05_clasificacion_general[$l]['sueldo_actual']            =     $aux_d['cnmd05_clasificacion_general']['sueldo_actual'];
		$var_cnmd05_clasificacion_general[$l]['compensaciones_actual']    =     $aux_d['cnmd05_clasificacion_general']['compensaciones_actual'];
		$var_cnmd05_clasificacion_general[$l]['primas_actual']            =     $aux_d['cnmd05_clasificacion_general']['primas_actual'];
		$var_cnmd05_clasificacion_general[$l]['cod_dep']                  =     $aux_d['cnmd05_clasificacion_general']['cod_dep'];
    }//fin foreach



for($ii=1; $ii<=$j; $ii++){
for($jj=1; $jj<=$k; $jj++){
if($this->cnmd05_clasificacion_general->findCount("cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=0 and ano=".$year." and  cod_nivel_i=".$var_cnmd04_tipo[$ii]['cod_nivel_i']." and cod_nivel_ii=0") == 0){
 		    $sql="INSERT INTO cnmd05_clasificacion_general";
			$sql.="(cod_presi, cod_entidad, cod_tipo_inst, cod_inst , cod_dep, ano, cod_nivel_i, cod_nivel_ii, numero_cargo_anterior, sueldo_anterior, compensaciones_anterior, primas_anterior, numero_cargo_actual, sueldo_actual, compensaciones_actual, primas_actual)";
    		$sql.=" VALUES ";
    		$sql.="('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '0', '$year', '".$var_cnmd04_tipo[$ii]['cod_nivel_i']."', '0', '0', '0', '0', '0', '0', '0', '0', '0')";
        	$this->cnmd05_clasificacion_general->execute($sql);

  	}//fin if
  }//fin for
}//fin for



for($ii=1; $ii<=$i; $ii++){
for($jj=1; $jj<=$k; $jj++){
if($this->cnmd05_clasificacion_general->findCount("cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=0 and ano=".$year."  and  cod_nivel_i=".$var_cnmd04_ocupacion[$ii]['cod_nivel_i']." and cod_nivel_ii=".$var_cnmd04_ocupacion[$ii]['cod_nivel_ii']."") == 0){
 		    $sql="INSERT INTO cnmd05_clasificacion_general";
			$sql.="(cod_presi, cod_entidad, cod_tipo_inst, cod_inst , cod_dep, ano, cod_nivel_i, cod_nivel_ii, numero_cargo_anterior, sueldo_anterior, compensaciones_anterior, primas_anterior, numero_cargo_actual, sueldo_actual, compensaciones_actual, primas_actual)";
    		$sql.=" VALUES ";
    		$sql.="('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '0', '$year', '".$var_cnmd04_ocupacion[$ii]['cod_nivel_i']."', '".$var_cnmd04_ocupacion[$ii]['cod_nivel_ii']."', '0', '0', '0', '0', '0', '0', '0', '0')";
        	$this->cnmd05_clasificacion_general->execute($sql);

  	}//fin if
  }//fin for
}//fin for




for($ii=1; $ii<=$j; $ii++){



	    $cod_sector = 0;
		$cod_programa = 0;
		$cod_nivel_i = 0;
		$cod_nivel_ii = 0;
		$numero_cargo_anterior = 0;
		$sueldo_anterior = 0;
		$compensaciones_anterior = 0;
		$primas_anterior = 0;
		$numero_cargo_actual = 0;
		$sueldo_actual = 0;
		$compensaciones_actual = 0;
		$primas_actual = 0;

  for($jj=1; $jj<=$k; $jj++){
  	if($var_cnmd05[$jj]['cod_nivel_i']==$var_cnmd04_tipo[$ii]['cod_nivel_i']){


        $cod_sector = 0;
		$cod_programa = 0;
		$cod_nivel_i = 0;
		$cod_nivel_ii = 0;
		$numero_cargo_anterior = 0;
		$sueldo_anterior = 0;
		$compensaciones_anterior = 0;
		$primas_anterior = 0;
		$numero_cargo_actual = 0;
		$sueldo_actual = 0;
		$compensaciones_actual = 0;
		$primas_actual = 0;

	   for($ll=1; $ll<=$k; $ll++){
  		 if($var_cnmd05[$ll]['cod_nivel_i']==$var_cnmd04_tipo[$ii]['cod_nivel_i']){

          $cod_nivel_i = $var_cnmd04_tipo[$ii]['cod_nivel_i'];
           $numero_cargo_actual++;

           $frecuencia = '';
           $clasificacion_personal='';
           for($aux=1; $aux<=$xi; $aux++){
           	  if($var_cnmd01[$aux]['cod_presi']==$cod_presi && $var_cnmd01[$aux]['cod_entidad']==$cod_entidad &&  $var_cnmd01[$aux]['cod_tipo_inst']==$cod_tipo_inst && $var_cnmd01[$aux]['cod_inst']==$cod_inst &&  $var_cnmd01[$aux]['cod_dep']==$var_cnmd05[$ll]['cod_dep'] &&  $var_cnmd01[$aux]['cod_tipo_nomina']==$var_cnmd05[$ll]['cod_tipo_nomina']){
                       $frecuencia = $var_cnmd01[$aux]['frecuencia_cobro'];
                       $clasificacion_personal = $var_cnmd01[$aux]['clasificacion_personal'];
           	  }//fin if
           }//fin for

           switch($frecuencia){
             case '1':{if($clasificacion_personal=='2'){$aux_sueldo = $var_cnmd05[$ll]['sueldo_basico'] * 365;}else{$aux_sueldo = $var_cnmd05[$ll]['sueldo_basico'] * 360;}}break;
             case '2':{$aux_sueldo = $var_cnmd05[$ll]['sueldo_basico'] * 52;}break;
             case '3':{$aux_sueldo = $var_cnmd05[$ll]['sueldo_basico'] * 24;}break;
             case '4':{$aux_sueldo = $var_cnmd05[$ll]['sueldo_basico'] * 12;}break;
             case '5':{$aux_sueldo = $var_cnmd05[$ll]['sueldo_basico'] * 6;}break;
             case '6':{$aux_sueldo = $var_cnmd05[$ll]['sueldo_basico'] * 4;}break;
           }//fin

		   $sueldo_actual += $aux_sueldo;
		   $compensaciones_actual += $var_cnmd05[$ll]['compensaciones'];
		   $primas_actual += $var_cnmd05[$ll]['primas'];

  		 }//fin if
  		}//fin for


  		$sql="UPDATE cnmd05_clasificacion_general SET";
        $sql.=" numero_cargo_actual=".$numero_cargo_actual.", sueldo_actual=".$sueldo_actual.", compensaciones_actual=".$compensaciones_actual.", primas_actual=".$primas_actual."";
        $sql.=" where cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=0 and ano=".$year."  and cod_nivel_i=".$cod_nivel_i." and cod_nivel_ii=".$cod_nivel_ii." ";
        $this->cnmd05_clasificacion_general->execute($sql);



  	}//fin if
  }//fin for j
}//fin for i



for($ii=1; $ii<=$i; $ii++){

	    $cod_sector = 0;
		$cod_programa = 0;
		$cod_nivel_i = 0;
		$cod_nivel_ii = 0;
		$numero_cargo_anterior = 0;
		$sueldo_anterior = 0;
		$compensaciones_anterior = 0;
		$primas_anterior = 0;
		$numero_cargo_actual = 0;
		$sueldo_actual = 0;
		$compensaciones_actual = 0;
		$primas_actual = 0;


  for($jj=1; $jj<=$k; $jj++){
  	if($var_cnmd05[$jj]['cod_nivel_i']==$var_cnmd04_ocupacion[$ii]['cod_nivel_i'] && $var_cnmd05[$jj]['cod_nivel_ii']==$var_cnmd04_ocupacion[$ii]['cod_nivel_ii']){



	    $cod_sector = 0;
		$cod_programa = 0;
		$cod_nivel_i = 0;
		$cod_nivel_ii = 0;
		$numero_cargo_anterior = 0;
		$sueldo_anterior = 0;
		$compensaciones_anterior = 0;
		$primas_anterior = 0;
		$numero_cargo_actual = 0;
		$sueldo_actual = 0;
		$compensaciones_actual = 0;
		$primas_actual = 0;

	   for($ll=1; $ll<=$k; $ll++){
  		 if($var_cnmd05[$ll]['cod_nivel_i']==$var_cnmd04_ocupacion[$ii]['cod_nivel_i']  && $var_cnmd05[$ll]['cod_nivel_ii']==$var_cnmd04_ocupacion[$ii]['cod_nivel_ii']){

           $cod_nivel_i = $var_cnmd04_ocupacion[$ii]['cod_nivel_i'];
		   $cod_nivel_ii = $var_cnmd04_ocupacion[$ii]['cod_nivel_ii'];
           $numero_cargo_actual++;

		  $frecuencia = '';
           $clasificacion_personal='';
           for($aux=1; $aux<=$xi; $aux++){
           	  if($var_cnmd01[$aux]['cod_presi']==$cod_presi && $var_cnmd01[$aux]['cod_entidad']==$cod_entidad &&  $var_cnmd01[$aux]['cod_tipo_inst']==$cod_tipo_inst && $var_cnmd01[$aux]['cod_inst']==$cod_inst &&  $var_cnmd01[$aux]['cod_dep']==$var_cnmd05[$ll]['cod_dep'] &&  $var_cnmd01[$aux]['cod_tipo_nomina']==$var_cnmd05[$ll]['cod_tipo_nomina']){
                       $frecuencia = $var_cnmd01[$aux]['frecuencia_cobro'];
                       $clasificacion_personal = $var_cnmd01[$aux]['clasificacion_personal'];
           	  }//fin if
           }//fin for

           switch($frecuencia){
             case '1':{if($clasificacion_personal=='2'){$aux_sueldo = $var_cnmd05[$ll]['sueldo_basico'] * 365;}else{$aux_sueldo = $var_cnmd05[$ll]['sueldo_basico'] * 360;}}break;
             case '2':{$aux_sueldo = $var_cnmd05[$ll]['sueldo_basico'] * 52;}break;
             case '3':{$aux_sueldo = $var_cnmd05[$ll]['sueldo_basico'] * 24;}break;
             case '4':{$aux_sueldo = $var_cnmd05[$ll]['sueldo_basico'] * 12;}break;
             case '5':{$aux_sueldo = $var_cnmd05[$ll]['sueldo_basico'] * 6;}break;
             case '6':{$aux_sueldo = $var_cnmd05[$ll]['sueldo_basico'] * 4;}break;
           }//fin

		   $sueldo_actual += $aux_sueldo;
		   $compensaciones_actual += $var_cnmd05[$ll]['compensaciones'];
		   $primas_actual += $var_cnmd05[$ll]['primas'];

  		 }//fin if
  		}//fin for


        $sql="UPDATE cnmd05_clasificacion_general SET";
        $sql.=" numero_cargo_actual=".$numero_cargo_actual.", sueldo_actual=".$sueldo_actual.", compensaciones_actual=".$compensaciones_actual.", primas_actual=".$primas_actual."";
        $sql.=" where cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=0 and ano=".$year." and cod_nivel_i=".$cod_nivel_i." and cod_nivel_ii=".$cod_nivel_ii." ";
        $this->cnmd05_clasificacion_general->execute($sql);


  	}//fin if
  }//fin for j
}//fin for i


}//fin function












function forma_2014($year=null){

	$sql='';

    $this->layout = "ajax";
    $this->limpia_menu();
    $this->set('ir', 'no');

	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst;


    $year = $this->cfpd01_formulacion->findAll($condicion, null, 'ano_formular ASC', null);
	$dato = null;
	foreach($year as $year){
		$dato = $year['cfpd01_formulacion']['ano_formular'];
	}

	if(!empty($dato)){

		$this->set('year', $dato);

	}else{
		$this->set('year', '');
	}


	$i = 0;
	$xi = 0;
	$j = 0;
	$k = 0;
	$l = 0;
	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$year = $dato;
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and ano=".$year;


    $cnmd04_ocupacion = $this->cnmd04_ocupacion->findAll();
    $cnmd04_tipo = $this->cnmd04_tipo->findAll();
    $cnmd05 = $this->cnmd05->findAll($condicion, null, 'cod_sector, cod_programa ASC');
    $cnmd05_clasificacion = $this->cnmd05_clasificacion->findAll($condicion, null, 'cod_sector, cod_programa ASC');
    $cnmd01 = $this->cnmd01->findAll("cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst);

    foreach($cnmd04_ocupacion as $aux_a){ $i++;
		$var_cnmd04_ocupacion[$i]['cod_nivel_i'] = $aux_a['cnmd04_ocupacion']['cod_nivel_i'];
		$var_cnmd04_ocupacion[$i]['cod_nivel_ii'] = $aux_a['cnmd04_ocupacion']['cod_nivel_ii'];
		$var_cnmd04_ocupacion[$i]['denominacion'] = $aux_a['cnmd04_ocupacion']['denominacion'];
    }//fin foreach

   foreach($cnmd01 as $aux_cnmd01){ $xi++;
		$var_cnmd01[$xi]['cod_presi']          		  =    $aux_cnmd01['Cnmd01']['cod_presi'];
		$var_cnmd01[$xi]['cod_entidad']        		  =    $aux_cnmd01['Cnmd01']['cod_entidad'];
		$var_cnmd01[$xi]['cod_tipo_inst']      		  =    $aux_cnmd01['Cnmd01']['cod_tipo_inst'];
		$var_cnmd01[$xi]['cod_inst']           		  =    $aux_cnmd01['Cnmd01']['cod_inst'];
		$var_cnmd01[$xi]['cod_dep']            		  =    $aux_cnmd01['Cnmd01']['cod_dep'];
		$var_cnmd01[$xi]['frecuencia_cobro']   		  =    $aux_cnmd01['Cnmd01']['frecuencia_cobro'];
		$var_cnmd01[$xi]['cod_tipo_nomina']   		  =    $aux_cnmd01['Cnmd01']['cod_tipo_nomina'];
		$var_cnmd01[$xi]['clasificacion_personal']    =    $aux_cnmd01['Cnmd01']['clasificacion_personal'];

    }//fin foreach

    foreach($cnmd04_tipo as $aux_b){ $j++;
		$var_cnmd04_tipo[$j]['cod_nivel_i']  = $aux_b['cnmd04_tipo']['cod_nivel_i'];
		$var_cnmd04_tipo[$j]['denominacion'] = $aux_b['cnmd04_tipo']['denominacion'];
    }//fin foreach

     foreach($cnmd05 as $aux_c){ $k++;
		$var_cnmd05[$k]['cod_tipo_nomina']          =     $aux_c['cnmd05']['cod_tipo_nomina'];
		$var_cnmd05[$k]['cod_sector']               =     $aux_c['cnmd05']['cod_sector'];
		$var_cnmd05[$k]['cod_programa']             =     $aux_c['cnmd05']['cod_programa'];
		$var_cnmd05[$k]['cod_nivel_i']              =     $aux_c['cnmd05']['cod_nivel_i'];
		$var_cnmd05[$k]['cod_nivel_ii']             =     $aux_c['cnmd05']['cod_nivel_ii'];
	  //$var_cnmd05[$k]['numero_cargo_anterior']    =     $aux_c['cnmd05']['numero_cargo_anterior'];
		$var_cnmd05[$k]['sueldo_basico']            =     $aux_c['cnmd05']['sueldo_basico'];
		$var_cnmd05[$k]['compensaciones']           =     $aux_c['cnmd05']['compensaciones'];
		$var_cnmd05[$k]['primas']                   =     $aux_c['cnmd05']['primas'];
		$var_cnmd05[$k]['cod_dep']                  =     $aux_c['cnmd05']['cod_dep'];

    }//fin foreach


     foreach($cnmd05_clasificacion as $aux_d){ $l++;
		$var_cnmd05_clasificacion[$l]['cod_sector']               =     $aux_d['cnmd05_clasificacion']['cod_sector'];
		$var_cnmd05_clasificacion[$l]['cod_programa']             =     $aux_d['cnmd05_clasificacion']['cod_programa'];
		$var_cnmd05_clasificacion[$l]['cod_nivel_i']              =     $aux_d['cnmd05_clasificacion']['cod_nivel_i'];
		$var_cnmd05_clasificacion[$l]['cod_nivel_ii']             =     $aux_d['cnmd05_clasificacion']['cod_nivel_ii'];
		$var_cnmd05_clasificacion[$l]['numero_cargo_anterior']    =     $aux_d['cnmd05_clasificacion']['numero_cargo_anterior'];
		$var_cnmd05_clasificacion[$l]['sueldo_anterior']          =     $aux_d['cnmd05_clasificacion']['sueldo_anterior'];
		$var_cnmd05_clasificacion[$l]['compensaciones_anterior']  =     $aux_d['cnmd05_clasificacion']['compensaciones_anterior'];
		$var_cnmd05_clasificacion[$l]['primas_anterior']          =     $aux_d['cnmd05_clasificacion']['primas_anterior'];
		$var_cnmd05_clasificacion[$l]['numero_cargo_actual']      =     $aux_d['cnmd05_clasificacion']['numero_cargo_actual'];
		$var_cnmd05_clasificacion[$l]['sueldo_actual']            =     $aux_d['cnmd05_clasificacion']['sueldo_actual'];
		$var_cnmd05_clasificacion[$l]['compensaciones_actual']    =     $aux_d['cnmd05_clasificacion']['compensaciones_actual'];
		$var_cnmd05_clasificacion[$l]['primas_actual']            =     $aux_d['cnmd05_clasificacion']['primas_actual'];
		$var_cnmd05_clasificacion[$l]['cod_dep']                  =     $aux_d['cnmd05_clasificacion']['cod_dep'];
    }//fin foreach



for($ii=1; $ii<=$j; $ii++){
for($jj=1; $jj<=$k; $jj++){
	$z = 1;
   $var_aux[$z]['cod_sector']='';
   $var_aux[$z]['cod_programa']='';
  	if( $var_cnmd05[$jj]['cod_sector']!=$var_aux[$z]['cod_sector'] || $var_cnmd05[$jj]['cod_programa']!=$var_aux[$z]['cod_programa']){
        $var_aux[$z]['cod_sector']=$var_cnmd05[$jj]['cod_sector'];
	    $var_aux[$z]['cod_programa']=$var_cnmd05[$jj]['cod_programa'];
  if($this->cnmd05_clasificacion->findCount("cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=0 and ano=".$year." and cod_sector=".$var_aux[$z]['cod_sector']." and cod_programa=".$var_aux[$z]['cod_programa']." and  cod_nivel_i=".$var_cnmd04_tipo[$ii]['cod_nivel_i']." and cod_nivel_ii=0") == 0){
 		    $sql="INSERT INTO cnmd05_clasificacion";
			$sql.="(cod_presi, cod_entidad, cod_tipo_inst, cod_inst , cod_dep, ano, cod_sector, cod_programa, cod_nivel_i, cod_nivel_ii, numero_cargo_anterior, sueldo_anterior, compensaciones_anterior, primas_anterior, numero_cargo_actual, sueldo_actual, compensaciones_actual, primas_actual)";
    		$sql.=" VALUES ";
    		$sql.="('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '0', '$year', '".$var_aux[$z]['cod_sector']."', '".$var_aux[$z]['cod_programa']."', '".$var_cnmd04_tipo[$ii]['cod_nivel_i']."', '0', '0', '0', '0', '0', '0', '0', '0', '0')";
        	$this->cnmd05_clasificacion->execute($sql);
      }//fin
  	}//fin if
  }//fin for
}//fin for



for($ii=1; $ii<=$i; $ii++){
for($jj=1; $jj<=$k; $jj++){
	$z = 1;
   $var_aux[$z]['cod_sector']='';
   $var_aux[$z]['cod_programa']='';
  	if( $var_cnmd05[$jj]['cod_sector']!=$var_aux[$z]['cod_sector'] || $var_cnmd05[$jj]['cod_programa']!=$var_aux[$z]['cod_programa']){
        $var_aux[$z]['cod_sector']   = $var_cnmd05[$jj]['cod_sector'];
	    $var_aux[$z]['cod_programa'] = $var_cnmd05[$jj]['cod_programa'];

    if($this->cnmd05_clasificacion->findCount("cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=0 and ano=".$year." and cod_sector=".$var_aux[$z]['cod_sector']." and cod_programa=".$var_aux[$z]['cod_programa']." and  cod_nivel_i=".$var_cnmd04_ocupacion[$ii]['cod_nivel_i']." and cod_nivel_ii=".$var_cnmd04_ocupacion[$ii]['cod_nivel_ii']."") == 0){
 		    $sql="INSERT INTO cnmd05_clasificacion";
			$sql.="(cod_presi, cod_entidad, cod_tipo_inst, cod_inst , cod_dep, ano, cod_sector, cod_programa, cod_nivel_i, cod_nivel_ii, numero_cargo_anterior, sueldo_anterior, compensaciones_anterior, primas_anterior, numero_cargo_actual, sueldo_actual, compensaciones_actual, primas_actual)";
    		$sql.=" VALUES ";
    		$sql.="('$cod_presi', '$cod_entidad', '$cod_tipo_inst', '$cod_inst', '0', '$year', '".$var_aux[$z]['cod_sector']."', '".$var_aux[$z]['cod_programa']."', '".$var_cnmd04_ocupacion[$ii]['cod_nivel_i']."', '".$var_cnmd04_ocupacion[$ii]['cod_nivel_ii']."', '0', '0', '0', '0', '0', '0', '0', '0')";
        	$this->cnmd05_clasificacion->execute($sql);
      }//fin
  	}//fin if
  }//fin for
}//fin for




for($ii=1; $ii<=$j; $ii++){



	    $cod_sector = 0;
		$cod_programa = 0;
		$cod_nivel_i = 0;
		$cod_nivel_ii = 0;
		$numero_cargo_anterior = 0;
		$sueldo_anterior = 0;
		$compensaciones_anterior = 0;
		$primas_anterior = 0;
		$numero_cargo_actual = 0;
		$sueldo_actual = 0;
		$compensaciones_actual = 0;
		$primas_actual = 0;
   $z = 1;
   $var_aux[$z]['cod_sector']='';
   $var_aux[$z]['cod_programa']='';

  for($jj=1; $jj<=$k; $jj++){
  	if($var_cnmd05[$jj]['cod_nivel_i']==$var_cnmd04_tipo[$ii]['cod_nivel_i'] && ( $var_cnmd05[$jj]['cod_sector']!=$var_aux[$z]['cod_sector'] || $var_cnmd05[$jj]['cod_programa']!=$var_aux[$z]['cod_programa'])){
        $var_aux[$z]['cod_sector']=$var_cnmd05[$jj]['cod_sector'];
	    $var_aux[$z]['cod_programa']=$var_cnmd05[$jj]['cod_programa'];

        $cod_sector = 0;
		$cod_programa = 0;
		$cod_nivel_i = 0;
		$cod_nivel_ii = 0;
		$numero_cargo_anterior = 0;
		$sueldo_anterior = 0;
		$compensaciones_anterior = 0;
		$primas_anterior = 0;
		$numero_cargo_actual = 0;
		$sueldo_actual = 0;
		$compensaciones_actual = 0;
		$primas_actual = 0;

	   for($ll=1; $ll<=$k; $ll++){
  		 if($var_cnmd05[$ll]['cod_nivel_i']==$var_cnmd04_tipo[$ii]['cod_nivel_i'] && $var_cnmd05[$ll]['cod_sector']==$var_aux[$z]['cod_sector'] && $var_cnmd05[$ll]['cod_programa']==$var_aux[$z]['cod_programa']){

           $cod_sector = $var_aux[$z]['cod_sector'];
		   $cod_programa = $var_aux[$z]['cod_programa'];
		   $cod_nivel_i = $var_cnmd04_tipo[$ii]['cod_nivel_i'];
           $numero_cargo_actual++;

           $frecuencia = '';
           $clasificacion_personal='';
           for($aux=1; $aux<=$xi; $aux++){
           	  if($var_cnmd01[$aux]['cod_presi']==$cod_presi && $var_cnmd01[$aux]['cod_entidad']==$cod_entidad &&  $var_cnmd01[$aux]['cod_tipo_inst']==$cod_tipo_inst && $var_cnmd01[$aux]['cod_inst']==$cod_inst &&  $var_cnmd01[$aux]['cod_dep']==$var_cnmd05[$ll]['cod_dep'] &&  $var_cnmd01[$aux]['cod_tipo_nomina']==$var_cnmd05[$ll]['cod_tipo_nomina']){
                       $frecuencia = $var_cnmd01[$aux]['frecuencia_cobro'];
                       $clasificacion_personal = $var_cnmd01[$aux]['clasificacion_personal'];
           	  }//fin if
           }//fin for

           switch($frecuencia){
             case '1':{if($clasificacion_personal=='2'){$aux_sueldo = $var_cnmd05[$ll]['sueldo_basico'] * 365;}else{$aux_sueldo = $var_cnmd05[$ll]['sueldo_basico'] * 360;}}break;
             case '2':{$aux_sueldo = $var_cnmd05[$ll]['sueldo_basico'] * 52;}break;
             case '3':{$aux_sueldo = $var_cnmd05[$ll]['sueldo_basico'] * 24;}break;
             case '4':{$aux_sueldo = $var_cnmd05[$ll]['sueldo_basico'] * 12;}break;
             case '5':{$aux_sueldo = $var_cnmd05[$ll]['sueldo_basico'] * 6;}break;
             case '6':{$aux_sueldo = $var_cnmd05[$ll]['sueldo_basico'] * 4;}break;
           }//fin

		   $sueldo_actual += $aux_sueldo;
		   $compensaciones_actual += $var_cnmd05[$ll]['compensaciones'];
		   $primas_actual += $var_cnmd05[$ll]['primas'];

  		 }//fin if
  		}//fin for


  		$sql="UPDATE cnmd05_clasificacion SET";
        $sql.=" numero_cargo_actual=".$numero_cargo_actual.", sueldo_actual=".$sueldo_actual.", compensaciones_actual=".$compensaciones_actual.", primas_actual=".$primas_actual."";
        $sql.=" where cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=0 and ano=".$year." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_nivel_i=".$cod_nivel_i." and cod_nivel_ii=".$cod_nivel_ii." ";
        $this->cnmd05_clasificacion->execute($sql);



  	}//fin if
  }//fin for j
}//fin for i



for($ii=1; $ii<=$i; $ii++){

	    $cod_sector = 0;
		$cod_programa = 0;
		$cod_nivel_i = 0;
		$cod_nivel_ii = 0;
		$numero_cargo_anterior = 0;
		$sueldo_anterior = 0;
		$compensaciones_anterior = 0;
		$primas_anterior = 0;
		$numero_cargo_actual = 0;
		$sueldo_actual = 0;
		$compensaciones_actual = 0;
		$primas_actual = 0;
   $z = 1;
   $var_aux[$z]['cod_sector']='';
   $var_aux[$z]['cod_programa']='';

  for($jj=1; $jj<=$k; $jj++){
  	if($var_cnmd05[$jj]['cod_nivel_i']==$var_cnmd04_ocupacion[$ii]['cod_nivel_i'] && $var_cnmd05[$jj]['cod_nivel_ii']==$var_cnmd04_ocupacion[$ii]['cod_nivel_ii'] && ($var_cnmd05[$jj]['cod_sector']!=$var_aux[$z]['cod_sector'] || $var_cnmd05[$jj]['cod_programa']!=$var_aux[$z]['cod_programa'])){
        $var_aux[$z]['cod_sector']=$var_cnmd05[$jj]['cod_sector'];
	    $var_aux[$z]['cod_programa']=$var_cnmd05[$jj]['cod_programa'];


	    $cod_sector = 0;
		$cod_programa = 0;
		$cod_nivel_i = 0;
		$cod_nivel_ii = 0;
		$numero_cargo_anterior = 0;
		$sueldo_anterior = 0;
		$compensaciones_anterior = 0;
		$primas_anterior = 0;
		$numero_cargo_actual = 0;
		$sueldo_actual = 0;
		$compensaciones_actual = 0;
		$primas_actual = 0;

	   for($ll=1; $ll<=$k; $ll++){
  		 if($var_cnmd05[$ll]['cod_nivel_i']==$var_cnmd04_ocupacion[$ii]['cod_nivel_i']  && $var_cnmd05[$ll]['cod_nivel_ii']==$var_cnmd04_ocupacion[$ii]['cod_nivel_ii'] && $var_cnmd05[$ll]['cod_sector']==$var_aux[$z]['cod_sector'] && $var_cnmd05[$ll]['cod_programa']==$var_aux[$z]['cod_programa']){

           $cod_sector = $var_aux[$z]['cod_sector'];
		   $cod_programa = $var_aux[$z]['cod_programa'];
		   $cod_nivel_i = $var_cnmd04_ocupacion[$ii]['cod_nivel_i'];
		   $cod_nivel_ii = $var_cnmd04_ocupacion[$ii]['cod_nivel_ii'];
           $numero_cargo_actual++;

		  $frecuencia = '';
           $clasificacion_personal='';
           for($aux=1; $aux<=$xi; $aux++){
           	  if($var_cnmd01[$aux]['cod_presi']==$cod_presi && $var_cnmd01[$aux]['cod_entidad']==$cod_entidad &&  $var_cnmd01[$aux]['cod_tipo_inst']==$cod_tipo_inst && $var_cnmd01[$aux]['cod_inst']==$cod_inst &&  $var_cnmd01[$aux]['cod_dep']==$var_cnmd05[$ll]['cod_dep'] &&  $var_cnmd01[$aux]['cod_tipo_nomina']==$var_cnmd05[$ll]['cod_tipo_nomina']){
                       $frecuencia = $var_cnmd01[$aux]['frecuencia_cobro'];
                       $clasificacion_personal = $var_cnmd01[$aux]['clasificacion_personal'];
           	  }//fin if
           }//fin for

           switch($frecuencia){
             case '1':{if($clasificacion_personal=='2'){$aux_sueldo = $var_cnmd05[$ll]['sueldo_basico'] * 365;}else{$aux_sueldo = $var_cnmd05[$ll]['sueldo_basico'] * 360;}}break;
             case '2':{$aux_sueldo = $var_cnmd05[$ll]['sueldo_basico'] * 52;}break;
             case '3':{$aux_sueldo = $var_cnmd05[$ll]['sueldo_basico'] * 24;}break;
             case '4':{$aux_sueldo = $var_cnmd05[$ll]['sueldo_basico'] * 12;}break;
             case '5':{$aux_sueldo = $var_cnmd05[$ll]['sueldo_basico'] * 6;}break;
             case '6':{$aux_sueldo = $var_cnmd05[$ll]['sueldo_basico'] * 4;}break;
           }//fin

		   $sueldo_actual += $aux_sueldo;
		   $compensaciones_actual += $var_cnmd05[$ll]['compensaciones'];
		   $primas_actual += $var_cnmd05[$ll]['primas'];

  		 }//fin if
  		}//fin for


        $sql="UPDATE cnmd05_clasificacion SET";
        $sql.=" numero_cargo_actual=".$numero_cargo_actual.", sueldo_actual=".$sueldo_actual.", compensaciones_actual=".$compensaciones_actual.", primas_actual=".$primas_actual."";
        $sql.=" where cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=0 and ano=".$year." and cod_sector=".$cod_sector." and cod_programa=".$cod_programa." and cod_nivel_i=".$cod_nivel_i." and cod_nivel_ii=".$cod_nivel_ii." ";
        $this->cnmd05_clasificacion->execute($sql);


  	}//fin if
  }//fin for j
}//fin for i


$this->set('entidad_federal', $this->Session->read('entidad_federal'));

}//fin function







function ir(){

	$this->layout="ajax";


}//fin function








}//fin class


?>