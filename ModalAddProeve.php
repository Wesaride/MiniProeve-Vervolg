<div id="ModalAddProeve" class="modal">
    <div class="modal-header" style="padding-left: 24px;">
        <h5>Proeve toevoegen</h5>
        <!-- TODO : dropdown menu zodat je kan selecteren in welk cohort je de proeve wilt toevoegen
                  : Zorg ervoor dat de meegegeven varibale kan opslaan in de database.-->
    </div>
    <div class="modal-content">
        <form method="POST">
            <label>Voer de naam van de proeve hieronder in:</label><br><br>
            <!-- <i class="material-icons prefix tiny">mode_edit</i><label>Cohort</label> -->
            <?php
            /////////////////////////////////
            // Dit is voor het selecteren van een cohort
            /////////////////////////////////
            // $error = '';
            // $get_cohort = "SELECT * FROM cohort";
            // $result_cohort = $conn->query($get_cohort);
            // if ($result_cohort->num_rows > 0) {
                ?>
                <!-- <select name="selected_cohort" required> -->
                    <!-- <option selected="selected" disabled>Kies een Cohort</option> -->
                    <?php
                    // while ($row_cohort = $result_cohort->fetch_assoc()) {
                        ?>
                        <!-- <option value="
                        <?php
                        // echo $row_cohort["cohort_id"]
                        ?>">
                        <?php
                        // echo $row_cohort["cohort_jaar"]
                        ?>
                        </option> -->
                        <?php
                    // }
                    ?>
                <!-- </select> -->
                <?php
            // }
            ?>
            <i class="material-icons prefix tiny">mode_edit</i><label>Proeve naam:</label>
            <input type="text" class="form-control" style="border-radius: 0;" name="proeve_naam" placeholder="Proeve">
            <input type="submit" name="new_proeve_submit" class="btn btn-success" value="Versturen" style="border-radius: 0;">
            <a href="#!" class="modal-action modal-close waves-effect waves-green btn btn-success ">Sluiten</a>
        </form>


        <?php
        if (isset($_POST['new_proeve_submit'])){
            if (isset($_POST['selected_cohort'])){
                if (isset($_POST['proeve_naam'])){
                    $cohort_id = $_POST['selected_cohort'];
                    $proeve_naam = $_POST['proeve_naam'];
                    $add_proeve_sql = "INSERT INTO proeve(proeve_naam, cohort_id) VALUES ('" . $proeve_naam . "', '" . $cohort_id . "')";
                    echo "<meta http-equiv='refresh' content='0'>";
                    if ($conn->query($add_proeve_sql) === TRUE) {
                        echo "Werkproces is toegevoegd";
                    } else {
                        echo "FOUTMELDING! Probeer opnieuw";
                    }
                }
            }  else {
            $error = 'Foutmelding, selecteer een kerntaak';
            }
        }

        if (!empty($error)){
            echo $error;
        }
        ?>
    </div>
</div>
