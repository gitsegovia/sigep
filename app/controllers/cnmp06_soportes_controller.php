<?php

 class Cnmp06SoportesController extends AppController{

	var $name = 'cnmp06_soportes';
	var $uses = array('cnmd06_soportes', 'cnmd06_datos_personales');
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

	$cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$modulo = $this->Session->read('Modulo');

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



  function zero($x=null){
	if($x != null){
		if($x<10){
			$x="0".$x;
		}else if($x>=10 && $x<=99){
			$x=$x;
		}
	}
	return $x;
}//fin zero



function concatena($vector1=null, $nomVar=null){
	if($vector1 != null){
		foreach($vector1 as $x => $y){$cod[$x] = $this->zero($x).' - '.$y;}
		$this->set($nomVar, $cod);
	}//fin if
}//fin concatena







 function index($var=null){

  $this->layout = "ajax";


    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;

    if($this->Session->read('cedula_pestana_expediente')==""){
         	$id=0;
    }else{
    	    $id=$this->Session->read('cedula_pestana_expediente');
    	 }//fin else

    	$this->set('cedula', "");
    	$Tfilas=$this->cnmd06_datos_personales->findCount("cedula_identidad=".$id);
       if($Tfilas!=0){

           $Tfilas=$this->cnmd06_soportes->findCount("cedula=".$id);
            if($Tfilas!=0){
            	$this->consulta($this->Session->read('cedula_pestana_expediente'));
            	$this->render("consulta");
            }else{
            	$cond ="cedula_identidad=".$id;
				$a = $this->cnmd06_datos_personales->findAll($cond);
			    $pa=$a[0]['cnmd06_datos_personales']['primer_apellido'];
			    $sa=$a[0]['cnmd06_datos_personales']['segundo_apellido'];
			    $pn=$a[0]['cnmd06_datos_personales']['primer_nombre'];
			    $sn=$a[0]['cnmd06_datos_personales']['segundo_nombre'];
			    $this->set('ci',$id);
			    $this->set('pa',$pa);
			    $this->set('sa',$sa);
			    $this->set('pn',$pn);
			    $this->set('sn',$sn);
            }//fin else



       }else{
       	    $this->set('ci',"");
		    $this->set('pa',"");
		    $this->set('sa',"");
		    $this->set('pn',"");
		    $this->set('sn',"");
      }//fin else

}///fin function





function consulta($var=null){

  $this->layout = "ajax";


    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
    $cond ="cedula_identidad=".$var;
			$a = $this->cnmd06_datos_personales->findAll($cond);
		    $pa=$a[0]['cnmd06_datos_personales']['primer_apellido'];
		    $sa=$a[0]['cnmd06_datos_personales']['segundo_apellido'];
		    $pn=$a[0]['cnmd06_datos_personales']['primer_nombre'];
		    $sn=$a[0]['cnmd06_datos_personales']['segundo_nombre'];
		    $this->set('ci',$var);
		    $this->set('pa',$pa);
		    $this->set('sa',$sa);
		    $this->set('pn',$pn);
		    $this->set('sn',$sn);


if($this->Session->read('cedula_pestana_expediente')==""){
     	$id=0;
}else{
	    $id=$this->Session->read('cedula_pestana_expediente');
}//fin else
      $Tfilas=$this->cnmd06_datos_personales->findCount("cedula_identidad=".$id);
   if($Tfilas!=0){
       $cond2="cedula=".$id;
   }else{
   	    $cond2="";
   }//fin else


$cont_aux = $this->cnmd06_soportes->findAll($cond2);



$this->set('datos', $cont_aux);


}///fin function






 function guardar($var=null){

  $this->layout = "ajax";


    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;

      $cont = 0;

      $cedula = $this->data['cnmp06_soportes']['cedula'];

	   if(isset($this->data['cnmp06_soportes']['a'])){$a = $this->data['cnmp06_soportes']['a'];}else{$a=0;}
	   if(isset($this->data['cnmp06_soportes']['b'])){$b = $this->data['cnmp06_soportes']['b'];}else{$b=0;}
	   if(isset($this->data['cnmp06_soportes']['c'])){$c = $this->data['cnmp06_soportes']['c'];}else{$c=0;}
	   if(isset($this->data['cnmp06_soportes']['d'])){$d = $this->data['cnmp06_soportes']['d'];}else{$d=0;}
	   if(isset($this->data['cnmp06_soportes']['e'])){$e = $this->data['cnmp06_soportes']['e'];}else{$e=0;}
	   if(isset($this->data['cnmp06_soportes']['f'])){$f = $this->data['cnmp06_soportes']['f'];}else{$f=0;}
	   if(isset($this->data['cnmp06_soportes']['g'])){$g = $this->data['cnmp06_soportes']['g'];}else{$g=0;}
	   if(isset($this->data['cnmp06_soportes']['h'])){$h = $this->data['cnmp06_soportes']['h'];}else{$h=0;}
	   if(isset($this->data['cnmp06_soportes']['i'])){$i = $this->data['cnmp06_soportes']['i'];}else{$i=0;}
	   if(isset($this->data['cnmp06_soportes']['j'])){$j = $this->data['cnmp06_soportes']['j'];}else{$j=0;}
	   if(isset($this->data['cnmp06_soportes']['k'])){$k = $this->data['cnmp06_soportes']['k'];}else{$k=0;}
	   if(isset($this->data['cnmp06_soportes']['l'])){$l = $this->data['cnmp06_soportes']['l'];}else{$l=0;}
	   if(isset($this->data['cnmp06_soportes']['ll'])){$ll = $this->data['cnmp06_soportes']['ll'];}else{$ll=0;}
	   if(isset($this->data['cnmp06_soportes']['m'])){$m = $this->data['cnmp06_soportes']['m'];}else{$m=0;}
	   if(isset($this->data['cnmp06_soportes']['n'])){$n = $this->data['cnmp06_soportes']['n'];}else{$n=0;}
	   if(isset($this->data['cnmp06_soportes']['o'])){$o = $this->data['cnmp06_soportes']['o'];}else{$o=0;}
	   if(isset($this->data['cnmp06_soportes']['p'])){$p = $this->data['cnmp06_soportes']['p'];}else{$p=0;}
	   if(isset($this->data['cnmp06_soportes']['q'])){$q = $this->data['cnmp06_soportes']['q'];}else{$q=0;}


 $cont  = $this->cnmd06_soportes->findCount('cedula='.$cedula);
 $sw2   = $this->cnmd06_soportes->execute('BEGIN; ');

 if($cont==0){

      if($a!=0){
		$sql  = "INSERT INTO cnmd06_soportes (cedula, cod_soporte) VALUES ('".$cedula."', '".$a."'); ";
        $sw2  = $this->cnmd06_soportes->execute($sql);
      }//fin

       if($b!=0){
		$sql  = "INSERT INTO cnmd06_soportes (cedula, cod_soporte) VALUES ('".$cedula."', '".$b."'); ";
        $sw2  = $this->cnmd06_soportes->execute($sql);
      }//fin

       if($c!=0){
		$sql  = "INSERT INTO cnmd06_soportes (cedula, cod_soporte) VALUES ('".$cedula."', '".$c."'); ";
        $sw2  = $this->cnmd06_soportes->execute($sql);
      }//fin

       if($d!=0){
		$sql  = "INSERT INTO cnmd06_soportes (cedula, cod_soporte) VALUES ('".$cedula."', '".$d."'); ";
        $sw2  = $this->cnmd06_soportes->execute($sql);
      }//fin

       if($e!=0){
		$sql  = "INSERT INTO cnmd06_soportes (cedula, cod_soporte) VALUES ('".$cedula."', '".$e."'); ";
        $sw2  = $this->cnmd06_soportes->execute($sql);
      }//fin

       if($f!=0){
		$sql  = "INSERT INTO cnmd06_soportes (cedula, cod_soporte) VALUES ('".$cedula."', '".$f."'); ";
        $sw2  = $this->cnmd06_soportes->execute($sql);
      }//fin

       if($g!=0){
		$sql  = "INSERT INTO cnmd06_soportes (cedula, cod_soporte) VALUES ('".$cedula."', '".$g."'); ";
        $sw2  = $this->cnmd06_soportes->execute($sql);
      }//fin

       if($h!=0){
		$sql  = "INSERT INTO cnmd06_soportes (cedula, cod_soporte) VALUES ('".$cedula."', '".$h."'); ";
        $sw2  = $this->cnmd06_soportes->execute($sql);
      }//fin

       if($i!=0){
		$sql  = "INSERT INTO cnmd06_soportes (cedula, cod_soporte) VALUES ('".$cedula."', '".$i."'); ";
        $sw2  = $this->cnmd06_soportes->execute($sql);
      }//fin

       if($j!=0){
		$sql  = "INSERT INTO cnmd06_soportes (cedula, cod_soporte) VALUES ('".$cedula."', '".$j."'); ";
        $sw2  = $this->cnmd06_soportes->execute($sql);
      }//fin

       if($k!=0){
		$sql  = "INSERT INTO cnmd06_soportes (cedula, cod_soporte) VALUES ('".$cedula."', '".$k."'); ";
        $sw2  = $this->cnmd06_soportes->execute($sql);
      }//fin

       if($l!=0){
		$sql  = "INSERT INTO cnmd06_soportes (cedula, cod_soporte) VALUES ('".$cedula."', '".$l."'); ";
        $sw2  = $this->cnmd06_soportes->execute($sql);
      }//fin

       if($ll!=0){
		$sql  = "INSERT INTO cnmd06_soportes (cedula, cod_soporte) VALUES ('".$cedula."', '".$ll."'); ";
        $sw2  = $this->cnmd06_soportes->execute($sql);
      }//fin

       if($m!=0){
		$sql  = "INSERT INTO cnmd06_soportes (cedula, cod_soporte) VALUES ('".$cedula."', '".$m."'); ";
        $sw2  = $this->cnmd06_soportes->execute($sql);
      }//fin

       if($n!=0){
		$sql  = "INSERT INTO cnmd06_soportes (cedula, cod_soporte) VALUES ('".$cedula."', '".$n."'); ";
        $sw2  = $this->cnmd06_soportes->execute($sql);
      }//fin

       if($o!=0){
		$sql  = "INSERT INTO cnmd06_soportes (cedula, cod_soporte) VALUES ('".$cedula."', '".$o."'); ";
        $sw2  = $this->cnmd06_soportes->execute($sql);
      }//fin

       if($p!=0){
		$sql  = "INSERT INTO cnmd06_soportes (cedula, cod_soporte) VALUES ('".$cedula."', '".$p."'); ";
        $sw2  = $this->cnmd06_soportes->execute($sql);
      }//fin

       if($q!=0){
		$sql  = "INSERT INTO cnmd06_soportes (cedula, cod_soporte) VALUES ('".$cedula."', '".$q."'); ";
        $sw2  = $this->cnmd06_soportes->execute($sql);
      }//fin




 }else{



      if($a!=0){
		$sql  = "INSERT INTO cnmd06_soportes (cedula, cod_soporte) VALUES ('".$cedula."', '".$a."'); ";
        $cont = $this->cnmd06_soportes->findCount('cedula='.$cedula.' and cod_soporte='.$a);
        if($cont==0){$sw2  = $this->cnmd06_soportes->execute($sql);}
      }else{
        $sql  = "DELETE FROM cnmd06_soportes  WHERE cedula = '".$cedula."' and cod_soporte  = '1'; ";
        $sw2  = $this->cnmd06_soportes->execute($sql);
      }//fin

		       if($b!=0){
				$sql  = "INSERT INTO cnmd06_soportes (cedula, cod_soporte) VALUES ('".$cedula."', '".$b."'); ";
		         $cont = $this->cnmd06_soportes->findCount('cedula='.$cedula.' and cod_soporte='.$b);
        if($cont==0){$sw2  = $this->cnmd06_soportes->execute($sql);}
		      }else{
		        $sql  = "DELETE FROM cnmd06_soportes  WHERE cedula = '".$cedula."' and cod_soporte  = '2'; ";
		        $sw2  = $this->cnmd06_soportes->execute($sql);
		      }//fin

       if($c!=0){
		$sql  = "INSERT INTO cnmd06_soportes (cedula, cod_soporte) VALUES ('".$cedula."', '".$c."'); ";
         $cont = $this->cnmd06_soportes->findCount('cedula='.$cedula.' and cod_soporte='.$c);
        if($cont==0){$sw2  = $this->cnmd06_soportes->execute($sql);}
      }else{
        $sql  = "DELETE FROM cnmd06_soportes  WHERE cedula = '".$cedula."' and cod_soporte  = '3'; ";
        $sw2  = $this->cnmd06_soportes->execute($sql);
      }//fin

		       if($d!=0){
				$sql  = "INSERT INTO cnmd06_soportes (cedula, cod_soporte) VALUES ('".$cedula."', '".$d."'); ";
		         $cont = $this->cnmd06_soportes->findCount('cedula='.$cedula.' and cod_soporte='.$d);
        if($cont==0){$sw2  = $this->cnmd06_soportes->execute($sql);}
		      }else{
		        $sql  = "DELETE FROM cnmd06_soportes  WHERE cedula = '".$cedula."' and cod_soporte  = '4'; ";
		        $sw2  = $this->cnmd06_soportes->execute($sql);
		      }//fin

       if($e!=0){
		$sql  = "INSERT INTO cnmd06_soportes (cedula, cod_soporte) VALUES ('".$cedula."', '".$e."'); ";
         $cont = $this->cnmd06_soportes->findCount('cedula='.$cedula.' and cod_soporte='.$e);
        if($cont==0){$sw2  = $this->cnmd06_soportes->execute($sql);}
      }else{
        $sql  = "DELETE FROM cnmd06_soportes  WHERE cedula = '".$cedula."' and cod_soporte  = '5'; ";
        $sw2  = $this->cnmd06_soportes->execute($sql);
      }//fin

		       if($f!=0){
				$sql  = "INSERT INTO cnmd06_soportes (cedula, cod_soporte) VALUES ('".$cedula."', '".$f."'); ";
		         $cont = $this->cnmd06_soportes->findCount('cedula='.$cedula.' and cod_soporte='.$f);
        if($cont==0){$sw2  = $this->cnmd06_soportes->execute($sql);}
		      }else{
		        $sql  = "DELETE FROM cnmd06_soportes  WHERE cedula = '".$cedula."' and cod_soporte  = '6'; ";
		        $sw2  = $this->cnmd06_soportes->execute($sql);
		      }//fin

       if($g!=0){
		$sql  = "INSERT INTO cnmd06_soportes (cedula, cod_soporte) VALUES ('".$cedula."', '".$g."'); ";
         $cont = $this->cnmd06_soportes->findCount('cedula='.$cedula.' and cod_soporte='.$g);
        if($cont==0){$sw2  = $this->cnmd06_soportes->execute($sql);}
      }else{
        $sql  = "DELETE FROM cnmd06_soportes  WHERE cedula = '".$cedula."' and cod_soporte  = '7'; ";
        $sw2  = $this->cnmd06_soportes->execute($sql);
      }//fin

		       if($h!=0){
				$sql  = "INSERT INTO cnmd06_soportes (cedula, cod_soporte) VALUES ('".$cedula."', '".$h."'); ";
		         $cont = $this->cnmd06_soportes->findCount('cedula='.$cedula.' and cod_soporte='.$h);
        if($cont==0){$sw2  = $this->cnmd06_soportes->execute($sql);}
		      }else{
		        $sql  = "DELETE FROM cnmd06_soportes  WHERE cedula = '".$cedula."' and cod_soporte  = '8'; ";
		        $sw2  = $this->cnmd06_soportes->execute($sql);
		      }//fin

       if($i!=0){
		$sql  = "INSERT INTO cnmd06_soportes (cedula, cod_soporte) VALUES ('".$cedula."', '".$i."'); ";
         $cont = $this->cnmd06_soportes->findCount('cedula='.$cedula.' and cod_soporte='.$i);
        if($cont==0){$sw2  = $this->cnmd06_soportes->execute($sql);}
      }else{
        $sql  = "DELETE FROM cnmd06_soportes  WHERE cedula = '".$cedula."' and cod_soporte  = '9'; ";
        $sw2  = $this->cnmd06_soportes->execute($sql);
      }//fin

		       if($j!=0){
				$sql  = "INSERT INTO cnmd06_soportes (cedula, cod_soporte) VALUES ('".$cedula."', '".$j."'); ";
		         $cont = $this->cnmd06_soportes->findCount('cedula='.$cedula.' and cod_soporte='.$j);
        if($cont==0){$sw2  = $this->cnmd06_soportes->execute($sql);}
		      }else{
		        $sql  = "DELETE FROM cnmd06_soportes  WHERE cedula = '".$cedula."' and cod_soporte  = '10'; ";
		        $sw2  = $this->cnmd06_soportes->execute($sql);
		      }//fin

       if($k!=0){
		$sql  = "INSERT INTO cnmd06_soportes (cedula, cod_soporte) VALUES ('".$cedula."', '".$k."'); ";
         $cont = $this->cnmd06_soportes->findCount('cedula='.$cedula.' and cod_soporte='.$k);
        if($cont==0){$sw2  = $this->cnmd06_soportes->execute($sql);}
      }else{
        $sql  = "DELETE FROM cnmd06_soportes  WHERE cedula = '".$cedula."' and cod_soporte  = '11'; ";
        $sw2  = $this->cnmd06_soportes->execute($sql);
      }//fin

		       if($l!=0){
				$sql  = "INSERT INTO cnmd06_soportes (cedula, cod_soporte) VALUES ('".$cedula."', '".$l."'); ";
		         $cont = $this->cnmd06_soportes->findCount('cedula='.$cedula.' and cod_soporte='.$l);
        if($cont==0){$sw2  = $this->cnmd06_soportes->execute($sql);}
		      }else{
		        $sql  = "DELETE FROM cnmd06_soportes  WHERE cedula = '".$cedula."' and cod_soporte  = '12'; ";
		        $sw2  = $this->cnmd06_soportes->execute($sql);
		      }//fin

       if($ll!=0){
		$sql  = "INSERT INTO cnmd06_soportes (cedula, cod_soporte) VALUES ('".$cedula."', '".$ll."'); ";
         $cont = $this->cnmd06_soportes->findCount('cedula='.$cedula.' and cod_soporte='.$ll);
        if($cont==0){$sw2  = $this->cnmd06_soportes->execute($sql);}
      }else{
        $sql  = "DELETE FROM cnmd06_soportes  WHERE cedula = '".$cedula."' and cod_soporte  = '13'; ";
        $sw2  = $this->cnmd06_soportes->execute($sql);
      }//fin

		       if($m!=0){
				$sql  = "INSERT INTO cnmd06_soportes (cedula, cod_soporte) VALUES ('".$cedula."', '".$m."'); ";
		         $cont = $this->cnmd06_soportes->findCount('cedula='.$cedula.' and cod_soporte='.$m);
        if($cont==0){$sw2  = $this->cnmd06_soportes->execute($sql);}
		      }else{
		        $sql  = "DELETE FROM cnmd06_soportes  WHERE cedula = '".$cedula."' and cod_soporte  = '14'; ";
		        $sw2  = $this->cnmd06_soportes->execute($sql);
		      }//fin

       if($n!=0){
		$sql  = "INSERT INTO cnmd06_soportes (cedula, cod_soporte) VALUES ('".$cedula."', '".$n."'); ";
         $cont = $this->cnmd06_soportes->findCount('cedula='.$cedula.' and cod_soporte='.$n);
        if($cont==0){$sw2  = $this->cnmd06_soportes->execute($sql);}
      }else{
        $sql  = "DELETE FROM cnmd06_soportes  WHERE cedula = '".$cedula."' and cod_soporte  = '15'; ";
        $sw2  = $this->cnmd06_soportes->execute($sql);
      }//fin

		       if($o!=0){
				$sql  = "INSERT INTO cnmd06_soportes (cedula, cod_soporte) VALUES ('".$cedula."', '".$o."'); ";
		         $cont = $this->cnmd06_soportes->findCount('cedula='.$cedula.' and cod_soporte='.$o);
        if($cont==0){$sw2  = $this->cnmd06_soportes->execute($sql);}
		      }else{
		        $sql  = "DELETE FROM cnmd06_soportes  WHERE cedula = '".$cedula."' and cod_soporte  = '16'; ";
		        $sw2  = $this->cnmd06_soportes->execute($sql);
		      }//fin

       if($p!=0){
		$sql  = "INSERT INTO cnmd06_soportes (cedula, cod_soporte) VALUES ('".$cedula."', '".$p."'); ";
         $cont = $this->cnmd06_soportes->findCount('cedula='.$cedula.' and cod_soporte='.$p);
        if($cont==0){$sw2  = $this->cnmd06_soportes->execute($sql);}
      }else{
        $sql  = "DELETE FROM cnmd06_soportes  WHERE cedula = '".$cedula."' and cod_soporte  = '17'; ";
        $sw2  = $this->cnmd06_soportes->execute($sql);
      }//fin

		       if($q!=0){
				$sql  = "INSERT INTO cnmd06_soportes (cedula, cod_soporte) VALUES ('".$cedula."', '".$q."'); ";
		         $cont = $this->cnmd06_soportes->findCount('cedula='.$cedula.' and cod_soporte='.$q);
        if($cont==0){$sw2  = $this->cnmd06_soportes->execute($sql);}
		      }else{
		        $sql  = "DELETE FROM cnmd06_soportes  WHERE cedula = '".$cedula."' and cod_soporte  = '18'; ";
		        $sw2  = $this->cnmd06_soportes->execute($sql);
		      }//fin




 }//fin else






if($sw2>1){
	  $this->cnmd06_soportes->execute("COMMIT;");
	  $cont_aux = $this->cnmd06_soportes->findAll("cedula = '".$cedula."'");
	  $this->set('Message_existe', "LOS DATOS FUERON GUARDADOS");
}else{
	  $this->cnmd06_soportes->execute("ROLLBACK;");
	 $cont_aux = "";
	 $this->set('errorMessage', "NO ESTA REGISTRADO EN PERSONAL"); }


	$this->index();
	//$this->render("index");

$this->set('datos', $cont_aux);
$this->set('cedula', $cedula);
}///fin function
















function eliminar(){

	  $this->layout = "ajax";



    $cod_presi = $this->Session->read('SScodpresi');
	$cod_entidad = $this->Session->read('SScodentidad');
	$cod_tipo_inst = $this->Session->read('SScodtipoinst');
	$cod_inst = $this->Session->read('SScodinst');
	$cod_dep = $this->Session->read('SScoddep');
	$condicion = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
    $cont = 0;
    $cedula = $this->data['cnmp06_soportes']['cedula'];

	 $this->cnmd06_soportes->execute("DELETE FROM cnmd06_soportes  WHERE cedula = '".$cedula."'");
     $this->set('Message_existe', "LOS DATOS FUERON ELIMINADOS");

	  $this->index();
      $this->render('index');

}//fin function








 function modificar($var=null){

  $this->layout = "ajax";
  $this->render('funcion');

echo"
<script>
    document.getElementById('guarda').style.display='block';
    document.getElementById('modifica').style.display='none';
    document.getElementById('a').disabled=false;
    document.getElementById('b').disabled=false;
    document.getElementById('c').disabled=false;
    document.getElementById('d').disabled=false;
    document.getElementById('e').disabled=false;
    document.getElementById('f').disabled=false;
    document.getElementById('g').disabled=false;
    document.getElementById('h').disabled=false;
    document.getElementById('i').disabled=false;
    document.getElementById('j').disabled=false;
    document.getElementById('k').disabled=false;
    document.getElementById('l').disabled=false;
    document.getElementById('ll').disabled=false;
    document.getElementById('m').disabled=false;
    document.getElementById('n').disabled=false;
    document.getElementById('o').disabled=false;
    document.getElementById('p').disabled=false;
    document.getElementById('q').disabled=false;
</script>";



}///fin function










 function cancelar($var=null){

  $this->layout = "ajax";
  $this->render('funcion');


echo"
<script>
    document.getElementById('guarda').style.display='none';
    document.getElementById('modifica').style.display='block';
    document.getElementById('a').disabled=true;
    document.getElementById('b').disabled=true;
    document.getElementById('c').disabled=true;
    document.getElementById('d').disabled=true;
    document.getElementById('e').disabled=true;
    document.getElementById('f').disabled=true;
    document.getElementById('g').disabled=true;
    document.getElementById('h').disabled=true;
    document.getElementById('i').disabled=true;
    document.getElementById('j').disabled=true;
    document.getElementById('k').disabled=true;
    document.getElementById('l').disabled=true;
    document.getElementById('ll').disabled=true;
    document.getElementById('m').disabled=true;
    document.getElementById('n').disabled=true;
    document.getElementById('o').disabled=true;
    document.getElementById('p').disabled=true;
    document.getElementById('q').disabled=true;
</script>";

}///fin function








}//fin class


?>