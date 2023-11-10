<?php


 class Cnmp06InstitutoEducativoController extends AppController{
	var $uses = array('cnmd06_instituto_educativo', 'ccfd04_cierre_mes','cnmd06_datos_educativos','cnmd06_datos_formacion_profesional','cugd05_restriccion_clave');
 	var $helpers = array('Html','Ajax','Javascript', 'Sisap');
 	var $name = "cnmp06_instituto_educativo";

//cnmp06_instituto_educativo

function checkSession(){$this->verifica_entrada('40');
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
}//fin before filter


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






function index($var=null, $var_cont=null){

$this->verifica_entrada('41');

	$this->layout = "ajax";

	  $cod_presi                =       $this->Session->read('SScodpresi');
	  $cod_entidad              =       $this->Session->read('SScodentidad');
	  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	  $cod_inst                 =       $this->Session->read('SScodinst');
	  $cod_dep                  =       $this->Session->read('SScoddep');
	  $condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." ";

     $var_cont = $this->cnmd06_instituto_educativo->findAll(null,null,"cod_institucion DESC",1,1,null);


        $PAGINAR="LIMIT 500 OFFSET 0 ";

     $Tfilas=$this->cnmd06_instituto_educativo->findCount();
        if($Tfilas!=0){
        	$pagina=1;
        	$Tfilas=(int)ceil($Tfilas/500);
        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
			$this->set('total_paginas',$Tfilas);
			$this->set('pagina_actual',$pagina);
			$this->set('ultimo',$Tfilas);
     	    //$datos_filas=$this->cnmd06_instituto_educativo->findAll(null,null,"upper(trim(quitar_acentos(denominacion))) ASC",500,1,null);
	        $datos_filas=$this->cnmd06_instituto_educativo->execute("SELECT * FROM cnmd06_instituto_educativo WHERE 1=1 ORDER BY upper(trim(quitar_acentos(denominacion))) ASC ".$PAGINAR);
	        $this->set("datosFILAS",$datos_filas);
	        $this->set('siguiente',$pagina+1);
			$this->set('anterior',$pagina-1);
			$this->bt_nav($Tfilas,$pagina);
        }else{
        	$this->set("datosFILAS",'');
        }
        $this->set('datos', $datos_filas);



     $var_cont2 = 0;
     foreach($var_cont as $ve1){$var_cont2 = $ve1['cnmd06_instituto_educativo']['cod_institucion'];}
     $var_cont2++;

     $this->set('codigo', $var_cont2);


}//fin index










function mostrar_datos($pagina=null){

    $this->layout = "ajax";


      if(isset($pagina)){
				$pagina=$pagina;
		}else{
				 $pagina=1;
		}//fin else

		if ($pagina > 1) {
			$offset = ($pagina - 1) * 500;
		}else{
			$offset = 0;
		}

        $PAGINAR="LIMIT 500 OFFSET ".$offset;

		$Tfilas=$this->cnmd06_instituto_educativo->findCount(null);
        if($Tfilas!=0){
        	$Tfilas=(int)ceil($Tfilas/500);
        	$this->set('total_paginas',$Tfilas);
			$this->set('pagina_actual',$pagina);
			$this->set('pag_cant',$pagina.'/'.$Tfilas);
			$this->set('ultimo',$Tfilas);
//     	    $datos_filas=$this->cnmd06_instituto_educativo->findAll(null,null,"upper(trim(quitar_acentos(denominacion))) ASC",500,$pagina,null);
     	    $datos_filas=$this->cnmd06_instituto_educativo->execute("SELECT * FROM cnmd06_instituto_educativo WHERE 1=1 ORDER BY upper(trim(quitar_acentos(denominacion))) ASC ".$PAGINAR);
	        $this->set("datosFILAS",$datos_filas);
	        $this->set('siguiente',$pagina+1);
			$this->set('anterior',$pagina-1);
			$this->bt_nav($Tfilas,$pagina);
        }else{
        	$this->set("datosFILAS",'');
        }


$this->set('datos', $datos_filas);


}//fin function









function funcion(){$this->layout = "ajax";}






function guardar($var_n=null){

	$this->layout = "ajax";

      $cod_presi                =       $this->Session->read('SScodpresi');
	  $cod_entidad              =       $this->Session->read('SScodentidad');
	  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	  $cod_inst                 =       $this->Session->read('SScodinst');
	  $cod_dep                  =       $this->Session->read('SScoddep');
	  $condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." ";



if($var_n==null){ $cod_institucion   =   $this->data['cnmp06_instituto_educativo']['codigo'.$var_n];}else{ $cod_institucion =  $var_n;}


if(!empty($this->data['cnmp06_instituto_educativo']['denominacion'.$var_n])){
   $denominacion            =       $this->data['cnmp06_instituto_educativo']['denominacion'.$var_n];
   $var_cont = $this->cnmd06_instituto_educativo->findCount("cod_institucion=".$cod_institucion);

			         if($var_cont==0){
                        $sw = $this->cnmd06_instituto_educativo->execute("BEGIN; INSERT INTO cnmd06_instituto_educativo (cod_institucion, denominacion) VALUES ('".$cod_institucion."', '".$denominacion."'); ");
			         }else{
                        $sw = $this->cnmd06_instituto_educativo->execute("BEGIN; UPDATE cnmd06_instituto_educativo SET denominacion='".$denominacion."' WHERE cod_institucion='".$cod_institucion."' ");
			         }//fin function
}else{$sw="a";}

          if($sw>1){

        $this->cnmd06_instituto_educativo->execute("COMMIT;");    $this->set('Message_existe', 'LOS DATOS FUERON GUARDADOS');

     }elseif($sw=="a"){

     	$this->cnmd06_instituto_educativo->execute("ROLLBACK;");  $this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS -- FALTAN DATOS');

     }else{

     	$this->cnmd06_instituto_educativo->execute("ROLLBACK;");  $this->set('errorMessage', 'LOS DATOS NO FUERON GUARDADOS');
     }//fin else




if($var_n==null){
	$this->set('autor_valido',true);
    $this->index();
	$this->render("index");
}else{

    $var_datos = $this->cnmd06_instituto_educativo->findAll("cod_institucion=".$cod_institucion);
   echo'<script>';
                   echo" document.getElementById('iconos_2_".$var_n."').style.display = 'none'; ";
                   echo" document.getElementById('iconos_1_".$var_n."').style.display = 'block'; ";
                   echo" document.getElementById('denominacion_".$var_n."').innerHTML ='".$var_datos[0]['cnmd06_instituto_educativo']['denominacion']."' ;";
     echo'</script>';
   $this->render("funcion");
}//fin else

}//fin function










function editar($var1=null, $var2=null){
	  $this->layout = "ajax";
      $cod_presi                =       $this->Session->read('SScodpresi');
	  $cod_entidad              =       $this->Session->read('SScodentidad');
	  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	  $cod_inst                 =       $this->Session->read('SScodinst');
	  $cod_dep                  =       $this->Session->read('SScoddep');
	  $condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." ";
         $var_datos = $this->cnmd06_instituto_educativo->findAll("cod_institucion=".$var1);
         $var2 = $var_datos[0]['cnmd06_instituto_educativo']['denominacion'];
      echo'<script>';
                   echo" document.getElementById('iconos_1_".$var1."').style.display = 'none'; ";
                   echo" document.getElementById('iconos_2_".$var1."').style.display = 'block'; ";
                   echo" document.getElementById('denominacion_".$var1."').innerHTML ='<input type=text name=data[cnmp06_instituto_educativo][denominacion".$var1."]    id=denominacion".$var1."  value=\"$var2\"   class=campoText  />' ;";
     echo'</script>';

$this->render("funcion");
}//fin function





function eliminar($var1=null, $var2=null){
	  $this->layout = "ajax";
      $cod_presi                =       $this->Session->read('SScodpresi');
	  $cod_entidad              =       $this->Session->read('SScodentidad');
	  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	  $cod_inst                 =       $this->Session->read('SScodinst');
	  $cod_dep                  =       $this->Session->read('SScoddep');
	  $condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." ";

	  $a=$this->cnmd06_datos_educativos->FindCount("cod_institucion='$var1'");
	  $b=$this->cnmd06_datos_formacion_profesional->FindCount("cod_institucion='$var1'");
	    if($a==0 && $b==0){
	    	 $sw = $this->cnmd06_instituto_educativo->execute("DELETE FROM cnmd06_instituto_educativo  WHERE cod_institucion='".$var1."'");
	    }else{
	    	$this->set('mensajeError','ESTE DATO NO PODRA SER ELIMINADO YA QUE ESTA SIENDO USADO POR OTRO PROGRAMA');
	    }
//		$this->render("funcion");
 	$datos    = $this->cnmd06_instituto_educativo->findAll(null, null, 'upper(denominacion) ASC');
     $this->set('datos', $datos);


//'cnmd06_datos_educativos','cnmd06_formacion_profesional'
}//fin function



function cancelar($var1=null, $var2=null){
	  $this->layout = "ajax";
      $cod_presi                =       $this->Session->read('SScodpresi');
	  $cod_entidad              =       $this->Session->read('SScodentidad');
	  $cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	  $cod_inst                 =       $this->Session->read('SScodinst');
	  $cod_dep                  =       $this->Session->read('SScoddep');
	  $condicion = "cod_presi=".$this->Session->read('SScodpresi')."  and  cod_entidad=".$this->Session->read('SScodentidad')." and cod_tipo_inst=".$this->Session->read('SScodtipoinst')." and  cod_inst=".$this->Session->read('SScodinst')." and cod_dep=".$this->Session->read('SScoddep')." ";
      $var_datos = $this->cnmd06_instituto_educativo->findAll("cod_institucion=".$var1);
      $var2 = $var_datos[0]['cnmd06_instituto_educativo']['denominacion'];
      echo'<script>';
                   echo" document.getElementById('iconos_2_".$var1."').style.display = 'none'; ";
                   echo" document.getElementById('iconos_1_".$var1."').style.display = 'block'; ";
                   echo" document.getElementById('denominacion_".$var1."').innerHTML ='".$var2."' ;";
     echo'</script>';
$this->render("funcion");
}//fin function



function entrar(){
	$this->layout="ajax";
	if(isset($this->data['cnmp06_instituto_educativo']['login']) && isset($this->data['cnmp06_instituto_educativo']['password'])){
		$l="PROYECTO";
		$c="JJJSAE";
		$user=addslashes($this->data['cnmp06_instituto_educativo']['login']);
		$paswd=addslashes($this->data['cnmp06_instituto_educativo']['password']);
		$cond=$this->SQLCA()." and username='".$user."' and cod_tipo=41 and clave='".$paswd."'";
		if(($user==$l && $paswd==$c)){
			$this->set('autor_valido',true);
			$this->index("autor_valido");
			$this->render("index");
		}elseif($this->cugd05_restriccion_clave->findCount($cond)!=0){
			$this->set('autor_valido',true);
			$this->index("autor_valido");
			$this->render("index");
		}else{
			$this->set('errorMessage',"Lo siento, se necesita autorizaci&oacute;n para utilizar este programa");
			$this->set('autor_valido',false);
			$this->index("autor_valido");
			$this->render("index");
		}
	}
}


function pista () {
   $this->layout="ajax";
   $pista = $this->data['cnmp06_instituto_educativo']['denominacion'];

   $c= strlen($pista);
   if($c>2){
   	   $datos_filas=$this->cnmd06_instituto_educativo->findAll("upper(trim(quitar_acentos(denominacion))) like '%".up($pista)."%'",null,"upper(trim(quitar_acentos(denominacion))) ASC",10,1,null);
       $this->set("data",$datos_filas);
   }
   $this->set('pista', $pista);
   //$this->render('repetidos');
}


function repetidos () {
   $this->layout="ajax";

   //$sql="SELECT upper(trim(denominacion)) as denominacion FROM cnmd06_instituto_educativo GROUP BY denominacion";
   //$sql="SELECT upper(trim(denominacion)) as denominacion FROM cnmd06_instituto_educativo";
   $sql="SELECT  x.denominacion,
(SELECT c.cod_institucion FROM cnmd06_instituto_educativo c
WHERE  upper(trim(quitar_acentos(c.denominacion)))=upper(trim(quitar_acentos(x.denominacion))) order by c.cod_institucion ASC limit 1) as codigos
FROM cnmd06_instituto_educativo x
GROUP BY  x.denominacion
ORDER BY  x.denominacion ASC
";

$sqlx="SELECT x.denominacion,
(SELECT c.cod_institucion FROM cnmd06_instituto_educativo c
WHERE 2=2 and c.denominacion=x.denominacion order by c.cod_institucion ASC limit 1) as codigos
FROM cnmd06_instituto_educativo x where 1=1
GROUP BY  x.denominacion
ORDER BY x.denominacion ASC
";

   $res=$this->cnmd06_instituto_educativo->execute($sqlx);
   $i=1;
   $xx=1;
   $updates ="";
   $delete="";
   foreach($res as $r){
   	   $sql2 = "SELECT c.cod_institucion FROM cnmd06_instituto_educativo c WHERE c.denominacion=(select x.denominacion from cnmd06_instituto_educativo x where x.cod_institucion=".$r[0]['codigos'].") and c.cod_institucion<>".$r[0]['codigos'];
   	   $sql_c = "SELECT count(*) as cantidad FROM cnmd06_instituto_educativo c WHERE c.denominacion=(select x.denominacion from cnmd06_instituto_educativo x where x.cod_institucion=".$r[0]['codigos'].") and c.cod_institucion<>".$r[0]['codigos'];
   	   $co = $this->cnmd06_instituto_educativo->execute($sql_c);
   	   if($co[0][0]['cantidad']!=0){
            $res2=$this->cnmd06_instituto_educativo->execute($sql2);
            $cod = array();
            foreach($res2 as $r2){
            	$cod[] = $r2[0]['cod_institucion'];
            }
            $updates .="UPDATE cnmd06_datos_educativos SET cod_institucion=".$r[0]['codigos']."   WHERE cod_institucion in (".implode(',',$cod).");";
            $updates .="UPDATE cnmd06_datos_formacion_profesional SET cod_institucion=".$r[0]['codigos']."   WHERE cod_institucion in (".implode(',',$cod).");";
            $delete .= "DELETE FROM cnmd06_instituto_educativo WHERE cod_institucion in (".implode(',',$cod).");";
   	        $xx++;
   	        echo "<br/>".$i." - ".$r[0]['denominacion']." =>".$r[0]['codigos'];$i++;
   	   }
   	   //echo "<br/>".$i." - ".$r[0]['denominacion']." =>".$r[0]['codigos'];$i++;
   }
   $this->cnmd06_instituto_educativo->execute($updates);
   $this->cnmd06_instituto_educativo->execute($delete);
   //echo "UPDATES:<hr>".$updates;
   //echo "<br/><br/>DELETES:<hr>".$delete;
}


}//fin class
?>
