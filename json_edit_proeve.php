<?php
include 'connect.php';
$return[] = array();
$edit_proeve_id = $_GET['id'];
//echo 'id = ' . $edit_kerntaak_id;
$get_selected_proeve = "SELECT proeve_id, proeve_naam FROM proeve WHERE proeve_id = $edit_proeve_id";
//echo $get_selected_kerntaak;
$result_selected_proeve = $conn->query($get_selected_proeve);
//echo $result_selected_kerntaak;
if ($result_selected_proeve->num_rows > 0) {
    while ($row_selected_proeve = $result_selected_proeve->fetch_assoc()) {
        $return_proeve["id"] = $row_selected_proeve["proeve_id"];
        $return_proeve["name"] = $row_selected_proeve["proeve_naam"];
    }
}

echo json_encode($return_proeve);
