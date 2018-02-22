<div id="ModalEditProeve" class="modal">
    <div class="modal-header" style="padding-left: 24px;">
        <h5> Bewerken</h5>
    </div>
    <div class="modal-content">
        <form method="POST">
            <label>Om een proef aan te passen voert u hier de wijziging in:</label>
            <input type="hidden" class="form-control" style="border-radius: 0;" name="proeve_id" id="proeve_id">
            <input type="text" class="form-control hide" style="border-radius: 0;" name="proeve_naam" id="proeve_naam" placeholder="Proeve naam">
            <button type="submit" name="edit_proeve_submit" class="btn btn-success" value="Opslaan">Opslaan</button>
            <a href="#!" class="modal-action modal-close waves-effect waves-green btn btn-success ">Sluiten</a>
        </form>
    </div>
</div>
<?php
if (isset($_POST["edit_proeve_submit"])) {
    if (isset($_POST["proeve_naam"])) {
        $edit_proeve_id = $_POST["proeve_id"];
        $edit_proeve_submit = $_POST["proeve_naam"];
        $edit_proeve = "UPDATE proeve SET proeve_naam='$edit_proeve_naam' WHERE proeve_id = $edit_proeve_id";
        if ($conn->query($edit_proeve) === TRUE) {
            //echo "Record updated successfully";
        } else {
            //echo "Error updating record: " . $conn->error;
        }
    }
}
?>
