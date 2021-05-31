$csv  = array();
$file = "../../item_db/10000.csv";	
$fp   = fopen($file, "r");

     _||_
    \    /
     \  /
      \/
$csv  = array();
$position = array();
$file_position = "../../item_db/position_db.csv";
$fp_position = fopen($file_position, 'r');
while (($data = fgetcsv($fp_position, 0, ",")) !== FALSE) {
if($data[0] == '10000') $position = explode(";", $data[1]);
}
array_pop($position);
fclose($fp_position);
$file = "../../item_db/products_db.csv";
$buffer=explode("\n",file_get_contents($file));
foreach($position as $index) {
$csv[] = explode(",", $buffer[intval($index)]);
}





while (($line = fgetcsv($fp)) !== FALSE) {    ==>   foreach($csv as $line) {



if (($fp = fopen($file, 'r')) !== FALSE){	=> if (true) {


fclose($fp);


unset($manufacturelist[0]);


while (($data = fgetcsv($fp, 0, ",")) !== FALSE) {
$csv[] = $data;
}


$row = 0;


if (($fp = fopen($file, 'r')) !== FALSE){
$row = 0;
}