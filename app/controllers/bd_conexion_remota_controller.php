<?php


class bdConexionRemotaController extends AppController {

   var $name    = "bd_conexion_remota";
   var $uses    = array('cpcd02');
   var $helpers = array('Html','Ajax','Javascript','Sisap');
   var $layout  = "script_correciones";



function index(){

    $datos_mysql=$this->cpcd02->execute("SELECT * FROM dbi_link.remote_select(1, 'SELECT cedula, nombres, apellidos, (1) as tabla FROM datos_personales') AS ( cedula  integer, nombres varchar(60), apellidos varchar(60), tabla integer);");
	$this->set('datos_mysql',$datos_mysql);

	$datos_postgres=$this->cpcd02->execute("SELECT * FROM dbi_link.remote_select(2, 'SELECT cedula, nombres, apellidos, (2) as tabla FROM datos_personales') AS ( cedula  integer, nombres varchar(60), apellidos varchar(60), tabla integer);");
	$this->set('datos_postgres',$datos_postgres);

	$datos_union_bd=$this->cpcd02->execute("SELECT * FROM primera_vista_dbi_link;");
	$this->set('datos_union_bd',$datos_union_bd);



}//fin function




}//fin class


?>