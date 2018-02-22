<?php
include 'connect.php';
global $conn;
$proeve_id = $_GET['id'];
$sql_delete_proeve = "DELETE FROM proeve WHERE proeve_id = $proeve_id";
if ($conn->query($sql_delete_proeve) === TRUE) {

} else {

}
?>
<script type="text/javascript">location.href = 'proeve.php';</script>
