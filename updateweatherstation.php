<?php

#include influxdb driver
require __DIR__ . '/vendor/autoload.php';

use InfluxDB2\Client;
use InfluxDB2\Model\WritePrecision;
use InfluxDB2\Point;
use InfluxDB2\WriteApi;
use InfluxDB2\WriteType;

#set user & password, bucket, database etc. 
$username = 'username';
$password = 'password';
$bucket = "garni";
$database = 'garni';
$retentionPolicy = 'autogen';

$bucket = "$database/$retentionPolicy";

#initialize influxdb connection
$client = new InfluxDB2\Client(["url" => "http://localhost:8086",
    "token" => "$username:$password",
    "bucket" => $bucket,
    "org" => "-",
    "precision" => InfluxDB2\Model\WritePrecision::S
]);

$writeApi = $client->createWriteApi();

#go through GET array and work with them
$fields_arr = array(); 
foreach ($_GET as $key => $value) {
#filter out some useless data
    if ( $key != "ID" && $key != "PASSWORD" && $key != "dateutc" && $key != "softwaretype" && $key != "action" && $key != "realtime" && $key != "rtfreq") { 
#convert F -> C
      if ( $key == "indoortempf" || $key == "tempf" || $key == "dewptf" || $key == "windchillf" ) {
        $value=floatval(round(($value-32)*5/9, 1)); 
      }
#convert mph -> mps
      if ( $key == "windspeedmph" || $key == "windgustmph" ) {
        $value=floatval(round($value*0.44704, 1));
      }
#convert in -> mm
      if ( $key == "rainin" || $key == "dailyrainin" || $key == "weeklyrainin" || $key == "monthlyrainin") {
        $value=floatval(round($value*25.4, 1));
      }
#convert inch of mercury -> hPa
      if ( $key == "absbaromin" || $key == "baromin" ) {
        $value=floatval(round($value*33.86389, 1));
      }
      if ( $key == "winddir" ) {
        $value=intval($value);
      }

        $fields_arr[$key]= $value; 
    }

}

#insert some tags 
$tag=array('device' => 'garni', 'location' => 'Your_Location');

#write data to influxdb
$array = array('name' => 'weather',
    'tags' => $tag,
    'fields' => $fields_arr,
    'time' => microtime(true));

$writeApi->write($array);
$writeApi->close();

?>

