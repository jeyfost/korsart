<?php
/**
 * Created by PhpStorm.
 * User: jeyfost
 * Date: 24.03.2019
 * Time: 15:49
 */

include("../../connect.php");

$id = $mysqli->real_escape_string($_POST['id']);

if($mysqli->query("DELETE FROM prices_items WHERE id = '".$id."'")) {
    echo "ok";
} else {
    echo "failed";
}