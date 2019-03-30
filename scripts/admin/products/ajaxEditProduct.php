<?php
/**
 * Created by PhpStorm.
 * User: jeyfost
 * Date: 24.03.2019
 * Time: 15:34
 */

include("../../connect.php");

$id = $mysqli->real_escape_string($_POST['id']);
$name = $mysqli->real_escape_string($_POST['name']);
$description = $mysqli->real_escape_string($_POST['description']);
$description = str_replace("\n", "<br />", $description);

if($mysqli->query("UPDATE prices_items SET name = '".$name."', description = '".$description."' WHERE id = '".$id."'")) {
    echo "ok";
} else {
    echo "failed";
}