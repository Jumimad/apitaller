<?php
header('Content-Type: application/json; charset=utf-8');

include '../../bootstrap.php';
# When installed via composer
require_once '/var/www/html/api/web/api/faker/vendor/fzaninotto/faker/src/autoload.php';
$faker = Faker\Factory::create();
// use the factory to create a Faker\Generator instance

$Total = isset($_REQUEST['total'])?$_REQUEST['total']:50;

$Data = $DataArr = array();

if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'create'){
   
    for ($i = 0; $i < $Total; $i++) {
        $Data['FirstName'] = $faker->firstName;
        $Data['LastName'] = $faker->lastName;
        $Data['Phone'] = $faker->phoneNumber;
        $Data['Address'] = $faker->streetAddress;
        $Data['City'] = $faker->city;
        $Data['State'] = $faker->state;
        $Data['Zip'] = $faker->postcode;
        $Data['Country'] = $faker->country;
        $Data['Email'] = $faker->firstName;
        $Data['Company'] = $faker->company;
        $DataArr[$i] = $Data;
       // var_dump($Data);
        $Conn->insertSanitized('Customers', $Data);
    }
    
    die(json_encode(array('message'=>'Se insertaron 100 registros FAKA', 'status'=> true, 'data'=>$DataArr)));
                    
    
}else if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'clear'){
    $db->query('TRUNCATE Customers');
    die(json_encode(array('message'=>'Se han eliminado todos los registros FAKA', 'status'=> true )));
}else{
    die(json_encode(array('message'=>'Por favor especifique que accion desea ejecutar create/clear', 'status'=> false )));
}
