<?php
/**
 * Created by PhpStorm.
 * User: jeyfost
 * Date: 24.03.2019
 * Time: 15:59
 */

include("../../connect.php");

$id = $mysqli->real_escape_string($_POST['id']);
$name = $mysqli->real_escape_string($_POST['name']);
$description = $mysqli->real_escape_string($_POST['description']);
$description = str_replace("\n", "<br />", $description);

if($mysqli->query("INSERT INTO prices_items (prices_category_id, name, description) VALUES ('".$id."', '".$name."', '".$description."')")) {
    echo "ok";
} else {
    echo "failed";
}