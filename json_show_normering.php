<?php

//TODO : Bij alle json bestanden kijken of alle query's werken.

include 'connect.php';
$get_normering_id = $_GET['id'];
//echo $get_cohort_id;
$get_normering = "SELECT normering_id, normering_text FROM normering WHERE criterium_id = '" . $get_normering_id . "'";
//echo $get_klas;
$result_normering = $conn->query($get_normering);
if ($result_normering->num_rows > 0) {
    $i = 0;
    while ($row_normering = $result_normering->fetch_assoc()) {
        $return_normering[$i]["normering_name"] = $row_normering["normering_text"];
        $return_normering[$i]["normering_id"] = $row_normering["normering_id"];
        $i++;
    }
}
echo json_encode($return_normering);
