<?php
/**
 * Created by PhpStorm.
 * User: jeyfost
 * Date: 10.11.2017
 * Time: 11:51
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
$list = $mysqli->real_escape_string($_POST['serviceList']);
$list = explode(',', $list);

$nameCheckResult = $mysqli->query("SELECT COUNT(id) FROM prices_subcategories WHERE name = '".$name."'");
$nameCheck = $nameCheckResult->fetch_array(MYSQLI_NUM);

if($nameCheck[0] == 0) {
    $sefLinkCheckResult = $mysqli->query("SELECT COUNT(id) FROM prices_subcategories WHERE sef_link = '".$sef_link."'");
    $sefLinkCheck = $sefLinkCheckResult->fetch_array(MYSQLI_NUM);

    if($sefLinkCheck[0] == 0) {
        if(!empty($_FILES['servicePhoto']['tmp_name'])) {
            if($_FILES['servicePhoto']['error'] == 0 and substr($_FILES['servicePhoto']['type'], 0, 5) == "image") {
                $photoTmpName = $_FILES['servicePhoto']['tmp_name'];
                $photoName = randomName($photoTmpName);
                $photoDBName = $photoName.".".substr($_FILES['servicePhoto']['name'], count($_FILES['servicePhoto']['name']) - 4, 4);
                $photoUploadDir = "../../../img/services/";
                $photoUpload = $photoUploadDir.$photoDBName;

                $idResult = $mysqli->query("SELECT MAX(id) FROM prices_subcategories");
                $id = $idResult->fetch_array(MYSQLI_NUM);

                $newID = $id[0] + 1;

                $maxPriorityResult = $mysqli->query("SELECT MAX(priority) FROM prices_subcategories");
                $maxPriority = $maxPriorityResult->fetch_array(MYSQLI_NUM);

                if($priority <= $maxPriority[0]) {
                    //Изменить приоритет последующих услуг
                    $serviceResult = $mysqli->query("SELECT * FROM prices_subcategories WHERE priority >= $maxPriority[0]");
                    while ($service = $serviceResult->fetch_assoc()) {
                        $newPriority = $service['priority'] + 1;

                        $mysqli->query("UPDATE prices_subcategories SET priority = '".$newPriority."' WHERE id = '".$service['id']."'");
                    }
                }

                if($mysqli->query("INSERT INTO prices_subcategories (id, name, sef_link, priority, title, keywords, description, photo, text) VALUES ('".$newID."', '".$name."', '".$sef_link."', '".$priority."', '".$name."', '".$keywords."', '".$description."', '".$photoDBName."', '".$text."')")) {
                    resize($photoTmpName, 500);
                    move_uploaded_file($photoTmpName, $photoUpload);

                    foreach ($list as $point) {
                        if(substr($point, 0, 1) == ' ') {
                            $point = substr($point, 1);
                        }

                        if(!empty($point)) {
                            $mysqli->query("INSERT INTO services_list (service_id, text) VALUES ('".$newID."', '".$point."')");
                        }
                    }

                    echo "ok";
                } else {
                    echo "failed";
                }
            } else {
                echo "photo";
            }
        } else {
            echo "photo_empty";
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