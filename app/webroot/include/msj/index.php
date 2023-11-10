<?php

if(isset($_GET['tipo'])){
   $link = pg_connect("host=localhost user=sisap password=sisap dbname=bd_sisap_falcon_0107") or die(pg_last_error($link));
   $tipo=explode("=",$_GET['tipo']);


   if(strtolower($tipo[0])=="verifica_msj"){
          verifica_msj ($tipo[1],$tipo[2],$link);
   }else if(strtolower($tipo[0])=="cerrar_msj"){
          cerrar_msj ($tipo[1],$tipo[2],$link);
   }
}else{
	HOLA();
}

function HOLA(){
	echo "error - 403";
}
function verifica_msj ($username,$id,$link) {
	$username = strtoupper($username);
	/*echo "<br>".$username;
	echo "<br>".$id;
	echo "<br>".$link;*/
	if(isset($id) && strtoupper($id)=="NO"){

	     $rs=pg_query($link, "SELECT a.username_origen,a.id_mensaje FROM mensajes a where a.username='$username' and a.estado=1 ORDER BY a.id_mensaje ASC") or die(pg_last_error($link));
	     $c=pg_query($link, "SELECT count(*) as t FROM mensajes where username='$username' and estado=1") or die(pg_last_error($link));
	     //print_r(pg_fetch_array($rs));
	     $cc=pg_fetch_array($c);
	     $CANTIDAD_MSJ=$cc[0]["t"];
?>

<script language="JavaScript" type="text/javascript">
  document.getElementById('cantidad_msj').innerHTML='<a href="#Listar_mensajes" onClick="c_msj();" id="f_a"><img src="/img/evolution-1.4.png"  title="Listar Mensajes" border="0" width="24" height="24"/></a>(<b><?=$CANTIDAD_MSJ;?></b>)';
  fondoCampo('f_a',2);
  document.getElementById('c_usuarios').innerHTML=document.getElementById('LOS_U').innerHTML;
</script>

<div id="LOS_U"  style="display:none;">
<table border="0" width="100%">

<?
while($CM = pg_fetch_array($rs)){
?>
<tr><td><a href="#msj_<?=strtolower($CM["username_origen"]);?>"  class="a_msj" onClick="ver_documento('/include/msj/?tipo=verifica_msj<?="=".$username."=".$CM["id_mensaje"];?>','lugar_msj');document.getElementById('c_usuarios').style.display='none';"><?=$CM["username_origen"];?></a></td></tr>
<?
}
?>
</table>
</div>
<?
	     pg_free_result($rs);
	     pg_free_result($c);
	     pg_close($link);

	}else if(!empty($id) && strtoupper($id)!="NO"){
		$id_msj=$id;
        $rs=pg_query($link, "SELECT a.*,(SELECT b.denominacion FROM arrd05 b WHERE  b.cod_presi=a.cod_presi and b.cod_entidad=a.cod_entidad and b.cod_tipo_inst=a.cod_tipo_inst and b.cod_dep=a.cod_dep_origen) as dependencia FROM mensajes a where a.username='$username' and a.estado=1 and id_mensaje=".$id_msj." ORDER BY a.id_mensaje ASC limit 1") or die(pg_last_error($link));
	    $c=pg_query($link, "SELECT count(*) as t FROM mensajes where username='$username' and estado=1") or die(pg_last_error($link));
	    $CC=pg_fetch_array($c);
	    $D=pg_fetch_array($rs);
	    if($CC[0]["t"]>0){
	      //$contenido_msj=$D[0]["contenido_msj"];
	      $DATA=$D;
	      //echo "<pre>";
	      //print_r($DATA);
	      //echo "</pre>";
	      $Name_DEP=$DATA["dependencia"]==''?'':$DATA["dependencia"];
	      ?>
<div id="contenido_tabla_msj" style="display:none;">
 <table width="550" border="0" class="tabla_msj" cellpadding="0" cellspacing="0">
       <tr><td colspan="3" class="th_msj"><?=strtoupper($Name_DEP)?></td></tr>
       <tr><td rowspan="2" width="128"><img src="/img/email.png"/></td><td valign="top" class="td_msj" colspan="2"><u>Informa</u>:<br><?=strtoupper($DATA["contenido_msj"]);?></td></tr>
       <tr><td class="td_fecha"><?= enviado($DATA["fecha_emitido"])?></td><td align="right"><a href="#Cerrar_msj" onClick="ver_documento('/include/msj/?tipo=cerrar_msj<?="=".$DATA["username"]."=".$DATA["id_mensaje"];?>','lugar_msj');Control.Modal.current.close(true);"><img src="/img/cancela_fila.png" title="Cerrar Mensaje" border="0" width="24" height="24"/></a></td></tr>
       </table>
</div>
<script>
       Control.Modal.open(document.getElementById('contenido_tabla_msj').innerHTML);
</script>
	      <?
	      pg_free_result($rs);
	     pg_free_result($c);
	     pg_close($link);
	  }
	}
}//fin verifica_msj

function cerrar_msj ($username,$id,$link) {
	$username = strtoupper($username);
	if(isset($id) && !empty($id)){
		$id_msj=$id;
         $upp=pg_query($link, "UPDATE mensajes SET estado=2 where username='$username' and id_mensaje=".$id_msj."") or die(pg_last_error($link));
	     $rs=pg_query($link, "SELECT a.username_origen,a.id_mensaje FROM mensajes a where a.username='$username' and a.estado=1 ORDER BY a.id_mensaje ASC") or die(pg_last_error($link));
	     $c=pg_query($link, "SELECT count(*) as t FROM mensajes where username='$username' and estado=1") or die(pg_last_error($link));
	     //print_r(pg_fetch_array($rs));
	     $cc=pg_fetch_array($c);
	     $CANTIDAD_MSJ=$cc[0]["t"];
?>

<script language="JavaScript" type="text/javascript">
  document.getElementById('cantidad_msj').innerHTML='<a href="#Listar_mensajes" onClick="c_msj();" id="f_a"><img src="/img/evolution-1.4.png"  title="Listar Mensajes" border="0" width="24" height="24"/></a>(<b><?=$CANTIDAD_MSJ;?></b>)';
  fondoCampo('f_a',2);
  document.getElementById('c_usuarios').innerHTML=document.getElementById('LOS_U').innerHTML;
</script>

<div id="LOS_U"  style="display:none;">
<table border="0" width="100%">

<?
while($CM = pg_fetch_array($rs)){
?>
<tr><td><a href="#msj_<?=strtolower($CM["username_origen"]);?>"  class="a_msj" onClick="ver_documento('/include/msj/?tipo=verifica_msj<?="=".$username."=".$CM["id_mensaje"];?>','lugar_msj');document.getElementById('c_usuarios').style.display='none';"><?=$CM["username_origen"];?></a></td></tr>
<?
}
?>
</table>
</div>
<?
         pg_free_result($upp);
	     pg_free_result($rs);
	     pg_free_result($c);
	     pg_close($link);
	}
}//fin cerrar_msj

 function enviado ($v) {
 	  $fecha=$v[5].$v[6]."/".$v[8].$v[9]."/".$v[0].$v[1].$v[2].$v[3];
 	  $hora=$v[11].$v[12].":".$v[14].$v[15].":".$v[17].$v[18];
 	  return "Enviado: ".$fecha." - ".$hora;

}
?>
ffk
