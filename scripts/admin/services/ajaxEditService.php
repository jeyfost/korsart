<?php
/**
 * Created by PhpStorm.
 * User: jeyfost
 * Date: 09.11.2017
 * Time: 11:37
 */

include("../../connect.php");
include("../../image.php");

$req = false;
ob_start();

$name = $mysqli->real_escape_string($_POST['serviceName']);
$sef_link = $mysqli->real_escape_string($_POST['serviceSefLink']);
$priority = $mysqli->real_escape_string($_POST['servicePriority']);
$keywords = $mysqli->real_escape_string($_POST['serviceKeywords']);
$description = $mysqli->real_escape_string($_POST['serviceDescription']);
$text = $mysqli->real_escape_string(nl2br($_POST['serviceText']));
$id = $mysqli->real_escape_string($_POST['service']);
$list = $mysqli->real_escape_string($_POST['serviceList']);
$list = explode(',', $list);

$nameCheckResult = $mysqli->query("SELECT COUNT(id) FROM prices_subcategories WHERE name = '".$name."' AND id <> '".$id."'");
$nameCheck = $nameCheckResult->fetch_array(MYSQLI_NUM);

$serviceResult = $mysqli->query("SELECT * FROM prices_subcategories WHERE id = '".$id."'");
$service = $serviceResult->fetch_assoc();

if($nameCheck[0] == 0) {
    $sefLinkCheckResult = $mysqli->query("SELECT COUNT(id) FROM prices_subcategories WHERE sef_link = '".$sef_link."' AND id <> '".$id."'");
    $sefLinkCheck = $sefLinkCheckResult->fetch_array(MYSQLI_NUM);

    if($sefLinkCheck[0] == 0) {
        $oldPriority = $service['priority'];
        $newPriority = $priority;

        if($newPriority < $oldPriority) {
            $servicesResult = $mysqli->query("SELECT * FROM prices_subcategories WHERE priority >= '".$newPriority."' AND priority < '".$oldPriority."'");
            while ($services = $servicesResult->fetch_assoc()) {
                $p = $services['priority'] + 1;

                $mysqli->query("UPDATE prices_subcategories SET priority = '".$p."' WHERE id = '".$services['id']."'");
            }
        }

        if($oldPriority < $newPriority) {
            $servicesResult = $mysqli->query("SELECT * FROM prices_subcategories WHERE priority >= '".$oldPriority."' AND priority < '".$newPriority."'");
            while ($services = $servicesResult->fetch_assoc()) {
                $p = $services['priority'] + 1;

                $mysqli->query("UPDATE prices_subcategories SET priority = '".$p."' WHERE id = '".$services['id']."'");
            }
        }

        if($mysqli->query("UPDATE prices_subcategories SET name = '".$name."', sef_link = '".$sef_link."', priority = '".$priority."', text = '".$text."', keywords = '".$keywords."', description = '".$description."' WHERE id = '".$id."'")) {
            $mysqli->query("DELETE FROM services_list WHERE service_id = '".$id."'");

            foreach ($list as $point) {
                if(substr($point, 0, 1) == ' ') {
                    $point = substr($point, 1);
                }

                if(!empty($point)) {
                    $mysqli->query("INSERT INTO services_list (service_id, text) VALUES ('".$id."', '".$point."')");
                }
            }

            if(!empty($_FILES['servicePhoto']['tmp_name'])) {
                if($_FILES['servicePhoto']['error'] == 0 and substr($_FILES['servicePhoto']['type'], 0, 5) == "image") {
                    $photoTmpName = $_FILES['servicePhoto']['tmp_name'];
                    $photoName = randomName($photoTmpName);
                    $photoDBName = $photoName.".".substr($_FILES['servicePhoto']['name'], count($_FILES['servicePhoto']['name']) - 4, 4);
                    $photoUploadDir = "../../../img/services/";
                    $photoUpload = $photoUploadDir.$photoDBName;

                    if($mysqli->query("UPDATE services SET photo = '".$photoDBName."' WHERE id = '".$id."'")) {
                        unlink("../../../img/services/".$service['photo']);
                        resize($photoTmpName, 500);
                        move_uploaded_file($photoTmpName, $photoUpload);

                        echo "ok";
                    } else {
                        echo "photo_failed";
                    }
                } else {
                    echo "photo";
                }
            } else {
                echo "ok";
            }
        } else {
            echo "failed";
        }
    } else {
        echo "sef_link";
    }
} else {
	echo "duplicate";
}

$req = ob_get_contents();
ob_end_clean();
echo json_encode($req);

exit;