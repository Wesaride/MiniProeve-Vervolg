<?php

//TODO : Bij alle json bestanden kijken of alle query's werken.

include 'connect.php';
$get_kerntaak_id = $_GET['id'];
//echo $get_kerntaak_id;
$get_werkproces = "SELECT werkproces_id, werkproces_naam FROM werkproces WHERE kerntaak_id = " . $get_kerntaak_id ;
//echo $get_werkproces;
$result_werkproces = $conn->query($get_werkproces);
if ($result_werkproces->num_rows > 0) {
    $i = 0;
    while ($row_werkproces = $result_werkproces->fetch_assoc()) {
        $return_werkproces[$i]["werkproces_name"] = $row_werkproces["werkproces_naam"];
        $return_werkproces[$i]["werkproces_id"] = $row_werkproces["werkproces_id"];
        $i++;
    }
}
echo json_encode($return_werkproces);
?>
