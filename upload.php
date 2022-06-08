<?php

$conn  = new mysqli("localhost","root","","autotrader");

$url=$_POST["url"];
$description=$conn -> real_escape_string($_POST["description"]);
$make=$_POST["make"];
$year=$_POST["year"];
$model=$conn -> real_escape_string($_POST["model"]);
$price=$_POST["price"];
$trim=$conn -> real_escape_string($_POST["trim"]);
$title=$conn -> real_escape_string($_POST["title"]);
$mileage=$_POST["mileage"];
$location=$conn -> real_escape_string($_POST["location"]);
$mainCategory=$_POST["mainCategory"];
$googleRating=$_POST["googleRating"];
$imagesArray=$_POST["imagesArray"];

$sql = "INSERT INTO `wp_cars_data`(`make`,`description`, `model`, `year`, `price`, `trim`, `title`, `mileage`, `location`,
                           `mainCategory`, `googleRating`, `images`, `url`) 
VALUES ('".$make."','".$description."','".$model."',".$year.",
".$price.",'".$trim."','".$title."','
".$mileage."','".$location."','".$mainCategory."','".$googleRating."','".$imagesArray."','".$url."')";
//$conn->query($sql);
if ($conn->query($sql) === TRUE) {
//    echo "New record created successfully";
} else {
    echo  $conn->error;
}
$conn  -> close();
?>
