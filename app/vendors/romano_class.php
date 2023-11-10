<?php
/*
 * Script Name: Numeros a Romanos Class
 * Script URI: http://www.mis-algoritmos.com/2007/10/10/convertir-numeros-a-romanos/
 * Description: Permite la conversión entre números Romanos y Arábigos.
 * Script Version: 1
 * Author: Victor De la Rocha
 * Author URI: http://www.mis-algoritmos.com
 * Script Version: 2
 * Description: Agregada la compatibilidad para PHP 5 y niveles de visibilidad
 * funciones estáticas y la posibilidad de saber si un número es Romano o no mediante
 * expresiones regulares, en el constructor se obtienen ya las dos posibilidades
 * Author: Reynier De la Rosa
 * Author URI: http://scriptinside.blogspot.com/
 */
class CRomano{
	
	protected $arabigo;
	protected $romano;
	
	/*
	 * Constructor: A partir del valor, lo convierte a su valor contrario.
	 * Romano a Arabigo
	 * Arabigo a Romano
	 */
	public function __construct($valor){
		if(is_numeric($valor)){
			$this->arabigo = intval($valor);
			$this->romano = CRomano::arabigo2romano($this->arabigo);
		}
		else{
			$this->arabigo = CRomano::romano2arabigo($valor);
			$this->romano = CRomano::arabigo2romano($this->arabigo);
		}
	}
	
	public static function esRomano($valor){
		$patron = "/^M{0,4}(CM|CD|D?C{0,3})(XC|XL|L?X{0,3})(IX|IV|V?I{0,3})$/";
		return (preg_match($patron, $valor))?true:false;
	}
			
	/*
	 * Convierte de Romano a Arabigo, retorna un entero.
	 */
	public static function romano2arabigo($valor){
		$valromano=array('I'=>1, 'V'=>5,'X'=>10,'L'=>50,'C'=>100,'D'=>500,'M'=>1000);
		$resultado=0;
		if(!empty($valor)){
			for($i=0; $i<strlen($valor)-1; $i++){
				$letra1=substr($valor,$i,1);
				$letra2=substr($valor,$i+1,1);
				if($valromano[$letra1] >= $valromano[$letra2] )
					$resultado+=$valromano[$letra1];
				else
					$resultado-=$valromano[$letra1];
			}
			$letra1=substr($valor,strlen($valor)-1,1);
			$resultado+=$valromano[$letra1];
		}
		return $resultado;
	}
			
	/*
	 * Convierte de Arabigo a Romano, retorna una cadena.
	 */
	public static function arabigo2romano($valor){
		$romanos = array('M','CM','D','CD','C','XC','L','XL','X','IX','V','IV','I');
		$valores = array(1000,900,500,400,100,90,50,40,10,9,5,4,1);
		$resultado='';
		for($i=0;$i<count($romanos);$i++){
			while($valor>=$valores[$i]){
				$resultado.=$romanos[$i];
				$valor-=$valores[$i];
			}
		}
		return $resultado;
	}
			
	/*
	 * Retorna el valor decimal.
	 */
	public function getArabigo(){
		return $this->arabigo;
	}
	
	/*
	 * Retorna el valor en Romano.
	 */
	public function getRomano(){
		return $this->romano;
	}
}
?>