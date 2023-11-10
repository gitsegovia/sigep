<?php


 class OnapreController extends AppController{


 	var $uses = array('cfpd01_grupo', 'cfpd01_partida', 'cfpd01_generica', 'cfpd01_especifica', 'cfpd01_sub_espec', 'cfpd01_auxiliar');
 	var $helpers = array('Html','Ajax','Javascript', 'Sisap');
    //var $layout =  "administradors";
    function index () {
         $grupo = $this->cfpd01_grupo->findAll(null, null, 'cod_grupo ASC', null,null, null);
		 $partida = $this->cfpd01_partida->findAll(null, null, 'cod_partida ASC', null, null, null);
		 $generica = $this->cfpd01_generica->findAll(null, null, 'cod_generica ASC', null, null, null);
		 $especifica = $this->cfpd01_especifica->findAll(null, null, 'cod_especifica ASC', null,null, null);
		 $subespecifica = $this->cfpd01_sub_espec->findAll(null, null, 'cod_sub_espec ASC', null, null, null);
		 $auxiliar = $this->cfpd01_auxiliar->findAll(null, null, 'cod_auxiliar ASC', 1,1, null);
		 print_r($grupo);
		  print_r($partida);
		   print_r($generica);
		    print_r($especifica);
		     print_r($subespecifica);
}
 }
?>
