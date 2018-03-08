<?php
include 'connect.php';
$get_proeve_id = $_GET['id'];
//echo $get_proeve_id;
$get_kerntaak = "SELECT kerntaak_id, kerntaak_naam FROM kerntaak WHERE proeve_id = " . $get_proeve_id;
//echo $get_kerntaak;
$result_kerntaak = $conn->query($get_kerntaak);
if ($result_kerntaak->num_rows > 0) {
    $i = 0;
    while ($row_kerntaak = $result_kerntaak->fetch_assoc()) {
        $return_kerntaak[$i]["kerntaak_name"] = $row_kerntaak["kerntaak_naam"];
        $return_kerntaak[$i]["kerntaak_id"] = $row_kerntaak["kerntaak_id"];
        $i++;
    }
}
echo json_encode($return_kerntaak);
?>
