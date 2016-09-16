<?php 

$arr = array("Reprogramado_Correl.N°227"
,"Reprogramdo_Correl. N°233"
,"Reprogramado_Correl.N°228"
,"Reprogramado_Correl.N°229"
);
foreach ($arr as $line) {
	
$line = str_replace("°", "", $line);
$line = str_replace("º", "", $line);
				
$findme   = 'N';
$pos = strpos($line , $findme);

if ($pos === false) {
    
} else {
    $var = explode($findme, $line);
	echo $var[1];
}
	
	echo "<br>";
	}

 ?>