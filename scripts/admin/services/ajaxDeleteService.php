<?php
/**
 * Created by PhpStorm.
 * User: jeyfost
 * Date: 09.11.2017
 * Time: 14:35
 */

include("../../connect.php");

$req = false;
ob_start();

$id = $mysqli->real_escape_string($_POST['service']);

$serviceResult = $mysqli->query("SELECT * FROM prices_subcategories WHERE id = '".$id."'");
$service = $serviceResult->fetch_assoc();

if($mysqli->query("DELETE FROM prices_subcategories WHERE id = '".$id."'")) {
	$mysqli->query("DELETE FROM services_list WHERE service_id = '".$id."'");
	unlink("../../../img/services/".$service['photo']);

	$servicesResult = $mysqli->query("SELECT * FROM prices_subcategories WHERE priority >= '".$service['priority']."'");
	while ($services = $servicesResult->fetch_assoc()) {
	    $newPriority = $services['priority'] - 1;

	    $mysqli->query("UPDATE prices_subcategories SET priority = '".$newPriority."' WHERE id = '".$services['id']."'");
    }

    $mysqli->query("DELETE FROM prices_items WHERE prices_category_id = '".$service['id']."'");

	echo "ok";
} else {
	echo "failed";
}

$req = ob_get_contents();
ob_end_clean();
echo json_encode($req);

exit;