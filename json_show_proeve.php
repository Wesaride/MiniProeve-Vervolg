<?php
include 'connect.php';
$get_cohort_id = $_GET['id'];
//echo $get_cohort_id;
$get_proeve = "SELECT proeve_id, proeve_naam FROM proeve WHERE cohort_id = $get_cohort_id";
//echo $get_klas;
$result_proeve = $conn->query($get_proeve);
if ($result_proeve->num_rows > 0) {
    $i = 0;
    while ($row_proeve = $result_proeve->fetch_assoc()) {
        $return_proeve[$i]["proeve_name"] = $row_proeve["proeve_naam"];
        $return_proeve[$i]["proeve_id"] = $row_proeve["proeve_id"];
        $i++;
    }
}
echo json_encode($return_proeve);
