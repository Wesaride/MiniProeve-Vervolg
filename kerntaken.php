<?php
include("check.php");
include("connect.php");


if (isset($_POST['post_cohort'])){
    $_SESSION['session_cohort'] = $_POST['post_cohort'];
}
if (isset($_POST['post_proeve'])){
    $_SESSION['session_proeve'] = $_POST['post_proeve'];
}
$session_cohort = $session_proeve = "";
if (isset($_SESSION['session_cohort'])){
    $session_cohort = $_SESSION['session_cohort'];
}
if (isset($_SESSION['session_proeve'])){
    $session_proeve = $_SESSION['session_proeve'];
}

?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>MiniProeve</title>
        <link type="text/css" rel="stylesheet" href="stylesheet.css">
        <link type="text/css" rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <link type="text/css" rel="stylesheet" media="screen,projection" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.5/css/materialize.min.css" />
    </head>
    <body>
        <?php
        include("navbar.php");
        include("ModalAddKerntaak.php");
        include("ModalEditKerntaak.php");
        include("ModalDeleteKerntaak.php");
        ?>
        <div class="row" style="margin-bottom: auto;">
            
            <div class="col s12 m4 l3" style="background-color: gray; height: 100%;">
                <?php
                $session_cohort = "";
                if (isset($_SESSION['session_cohort'])){
                    $session_cohort = $_SESSION['session_cohort'];
                }
                $get_cohort = "SELECT * FROM cohort";
                $result_cohort = $conn->query($get_cohort);
                if ($result_cohort->num_rows > 0) {
                    ?>
                    <select name="selected_cohort" required>
                        <?php
                        if (isset($_SESSION['session_cohort'])){
                            echo '<option disabled>Kies een cohort</option>';
                        }
                        else{
                            echo "<option selected='selected' disabled>Kies een cohort</option>";
                        }
                        while ($row_cohort = $result_cohort->fetch_assoc()) {
                            if (isset($_SESSION['session_cohort'])){
                                if ($_SESSION['session_cohort'] == $row_cohort['cohort_id']){
                                    $selectedvalue = "selected='selected'";
                                }
                                else{
                                    $selectedvalue = "";
                                }
                            }
                            echo "<option " . $selectedvalue . " value=" . $row_cohort['cohort_id'] . ">" . $row_cohort['cohort_jaar'] . "</option>";
                        }
                        ?>
                    </select>
                    <?php
                }
                ?>
            </div>
            <div style="overflow: scroll; height: 85%" class="col s12 m8 l9" margin="0 auto">
                <h4>Overzicht kerntaken <a data-target="ModalAddKerntaak" class="btn-floating btn-small waves-effect waves-light green btn modal-trigger"><i class="material-icons" >add</i></a></h4>
                    <table>
                        <thead>
                            <tr>
                                <th>Naam</th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $get_kerntaak_inhoud = "SELECT * FROM kerntaak";
                            $result_get_kerntaak_inhoud = $conn->query($get_kerntaak_inhoud);
                            if ($result_get_kerntaak_inhoud->num_rows > 0) {
                                while ($row_get_kerntaak_inhoud = $result_get_kerntaak_inhoud->fetch_assoc()) {
                                    ?>
                                    <tr>
                                        <td><?php echo $row_get_kerntaak_inhoud['kerntaak_naam']; ?></td>
                                        <td><button data-id="<?php echo $row_get_kerntaak_inhoud['kerntaak_id']; ?>" data-target="ModalEditKerntaak" name="EditKerntaak" class="btn-floating btn-large waves-effect waves-light yellow btn modal-trigger"><i class="material-icons" >edit</i></button></td>
                                        <td><button data-id="<?php echo $row_get_kerntaak_inhoud['kerntaak_id']; ?>" data-target="ModalDeleteKerntaak" name="DeleteKerntaak"  class="btn-floating btn-large waves-effect waves-light red btn modal-trigger"><i class="material-icons">delete</i></button></td>
                                    <tr>
                                        <?php
                                    }
                                }
                                ?>
                        </tbody>
                    </table>
            </div>
        </div>

        <!--EINDE CODE VOOR KLAS TOEVOEGEN BACKEND -->
        <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.5/js/materialize.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function () {
                // the "href" attribute of .modal-trigger must specify the modal ID that wants to be triggered
                $('.modal-trigger').leanModal();
                $('select').material_select();
                $(".button-collapse").sideNav();
                
                

                // Edit button
                $("button[name=EditKerntaak]").on('click', function () {
                    // waarde van het geselecteerde id ophalen
                    id_kerntaak = $(this).data("id");

                    // Velden leeg maken
                    document.getElementById("kerntaak_id").value = "";
                    document.getElementById("kerntaak_naam").value = "";

                    // ophalen van informatie, met ajax om naam/omschrijving kerntaak op te halen
                    $.ajax({
                        type: 'GET',
                        url: 'json_edit_kerntaak.php',
                        data: {id: id_kerntaak},
                        dataType: 'json',
                        success: function (data) {
                            $("#kerntaak_id").val(data.id);
                            $("#kerntaak_naam").val(data.name);
                            $("#kerntaak_naam").removeClass("hide");
                        }
                    });
                });

                // DELETE BUTTON
                $("button[name=DeleteKerntaak]").click(function (event) {
                    event.preventDefault();
                    // ophalen van het id
                    var kerntaak_id = $(this).data("id");
                    //alert(student_id);
                    // link aanpassen
                    $("#delhref").attr("href", "delete_kerntaak.php?id=" + kerntaak_id);
                });

            });
        </script>
    </body>
</html>
