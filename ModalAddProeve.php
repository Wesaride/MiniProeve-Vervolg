<div id="ModalAddProeve" class="modal">
    <div class="modal-header" style="padding-left: 24px;">
        <h5>Proeve toevoegen</h5>
    </div>
    <div class="modal-content">
        <form method="POST">
            <label>Om een proeve toe te voegen selecteert u eerst de bijbehorende cohort en vult u daarna het werkproces in:</label><br><br>
            <i class="material-icons prefix tiny">mode_edit</i><label>Proeve naam:</label>
            <input type="text" class="form-control" style="border-radius: 0;" name="proeve_naam" placeholder="Proeve">
            <input type="submit" name="new_proeve_submit" class="btn btn-success" value="Versturen" style="border-radius: 0;">
            <a href="#!" class="modal-action modal-close waves-effect waves-green btn btn-success ">Sluiten</a>
        </form>
        <?php
        if (isset($_POST['new_proeve_submit'])) {
            if (!empty($_POST['proeve_naam'])) {
                $proeve_name = $_POST['proeve_naam'];
                $add_proeve_sql = "INSERT INTO proeve(proeve_naam) VALUES ('" . $proeve_name . "')";
                echo "<meta http-equiv='refresh' content='0'>";
                if ($conn->query($add_proeve_sql) === TRUE) {
                    //echo "Proeve is toegevoegd";
                } else {
                    //echo "FOUTMELDING! Probeer opnieuw";
                }
            }
        }
        ?>
    </div>
</div>
