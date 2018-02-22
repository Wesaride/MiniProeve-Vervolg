<?php
include("check.php");
include("connect.php");
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
        include("ModalAddProeve.php");
        include("ModalEditProeve.php");
        include("ModalDeleteProeve.php");
        ?>
        <div class="row" style="margin-bottom: auto;">
            <div class="col s12 m4 l3" style="background-color: gray; height: 100%;"></div>
            <div class="col s12 m8 l9" margin="0 auto">
                <h4>Overzicht Proeve<a data-target="ModalAddProeve" class="btn-floating btn-small waves-effect waves-light green btn modal-trigger"><i class="material-icons" >add</i></a></h4>
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
                            $get_proeve_inhoud = "SELECT * FROM proeve";
                            $result_get_proeve_inhoud = $conn->query($get_proeve_inhoud);
                            if ($result_get_proeve_inhoud->num_rows > 0) {
                                while ($row_get_proeve_inhoud = $result_get_proeve_inhoud->fetch_assoc()) {
                                    ?>
                                    <tr>
                                        <td><?php echo $row_get_proeve_inhoud['proeve_naam']; ?></td>
                                        <td><button data-id="<?php echo $row_get_proeve_inhoud['proeve_id']; ?>" data-target="ModalEditProeve" name="EditProeve" class="btn-floating btn-large waves-effect waves-light yellow btn modal-trigger"><i class="material-icons" >edit</i></button></td>
                                        <td><button data-id="<?php echo $row_get_proeve_inhoud['proeve_id']; ?>" data-target="ModalDeleteProeve" name="DeleteProeve"  class="btn-floating btn-large waves-effect waves-light red btn modal-trigger"><i class="material-icons">delete</i></button></td>
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
                $("button[name=EditProeve]").on('click', function () {
                    // waarde van het geselecteerde id ophalen
                    id_proeve = $(this).data("id");

                    // Velden leeg maken
                    document.getElementById("proeve_id").value = "";
                    document.getElementById("proeve_naam").value = "";

                    // ophalen van informatie, met ajax om naam/omschrijving kerntaak op te halen
                    $.ajax({
                        type: 'GET',
                        url: 'json_edit_proeve.php',
                        data: {id: id_proeve},
                        dataType: 'json',
                        success: function (data) {
                            $("#proeve_id").val(data.id);
                            $("#proeve_naam").val(data.name);
                            $("#proeve_naam").removeClass("hide");
                        }
                    });
                });

                // DELETE BUTTON
                $("button[name=DeleteProeve]").click(function (event) {
                    event.preventDefault();
                    // ophalen van het id
                    var proeve_id = $(this).data("id");
                    //alert(student_id);
                    // link aanpassen
                    $("#delhref").attr("href", "delete_proeve.php?id=" + proeve_id);
                });

            });
        </script>
    </body>
</html>
