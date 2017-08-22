<?php
// required header
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// include database and object files
include_once '../config/database.php';
include_once '../objects/store.php';
 
// instantiate database and store object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$store = new Store($db);
 
// query Stores
if (!empty($_GET['id'])) {
  // Do something.
    $stmt = $store->read($_GET['id']);
}else{
    
    $stmt = $store->read(0);
}

$num = $stmt->rowCount();

// check if more than 0 record found
if($num>0){
 
    // products array
    $store_arr=array();
    $store_arr["records"]=array();
 
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
 
        $store_item=array(
            "id" => $id,
            "name" => $name,
            "description" => html_entity_decode($description)
        );
 
        array_push($store_arr["records"], $store_item);
    }
 
    echo json_encode($store_arr);
}
 
else{
    echo json_encode(
        array("message" => "No products found.")
    );
}
?>