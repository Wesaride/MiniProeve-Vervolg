<?php
include("check.php");
include("connect.php");

function GetCriteria() {
    global $conn;
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
        include("ModalAddCriterium.php");
        include("ModalEditCriterium.php");
        include("ModalDeleteCriterium.php");
        ?>
        <div class="row" style="margin-bottom: auto;">
            <div class="col s12 m4 l3" style="background-color: gray; height: 100%;">

            </div>
            <div class="col s12 m8 l9">
                <h4>Overzicht criterium
                    <a data-target="ModalAddCriterium" class="btn-floating btn-small waves-effect waves-light green btn modal-trigger">
                        <i class="material-icons" >add</i>
                    </a>
                </h4>
                <table>
                    <thead>
                        <tr>
                            <th>Criterium</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $get_werkproces_criterium_inhoud = "SELECT * FROM werkproces_criterium";
                        $result_get_werkproces_criterium_inhoud = $conn->query($get_werkproces_criterium_inhoud);
                        if ($result_get_werkproces_criterium_inhoud->num_rows > 0) {
                            while ($row_get_werkproces_criterium_inhoud = $result_get_werkproces_criterium_inhoud->fetch_assoc()) {
                                ?>
                                <tr>
                                    <td><?php echo $row_get_werkproces_criterium_inhoud['werkproces_criterium_naam']; ?></td>
                                    <td><button data-id="<?php echo $row_get_werkproces_criterium_inhoud['werkproces_criterium_id']; ?>" data-target="ModalEditCriterium" name="EditCriterium" class="btn-floating btn-large waves-effect waves-light yellow btn modal-trigger"><i class="material-icons" >edit</i></button></td>
                                    <td><button data-id="<?php echo $row_get_werkproces_criterium_inhoud['werkproces_criterium_id']; ?>" data-target="ModalDeleteCriterium" name="DeleteCriterium" class="btn-floating btn-large waves-effect waves-light red btn modal-trigger"><i class="material-icons">delete</i></button></td>
                                <tr>
                                    <?php
                                }
                            } else {
                                echo '0 results';
                            }
                            ?>
                    </tbody>
                </table>
            </div>
        </div>


        <!--EINDE CODE VOOR KLAS TOEVOEGEN BACKEND -->
        <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.5/js/materialize.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function () {
                // the "href" attribute of .modal-trigger must specify the modal ID that wants to be triggered
                $(".modal-trigger").leanModal();
                $("select").material_select();
                $(".button-collapse").sideNav();

                // Edit criteria
                $("button[name=EditCriterium]").on('click', function () {
                    // waarde van het geselecteerde id ophalen
                    id_criterium = $(this).data("id");
                    //alert(id_criterium);

                    // Velden leeg maken
                    document.getElementById("criterium_id").value = "";
                    document.getElementById("criterium_naam").value = "";

                    // ophalen van informatie, met ajax om naam/omschrijving kerntaak op te halen
                    $.ajax({
                        type: 'GET',
                        url: 'json_edit_criterium.php',
                        data: {id: id_criterium},
                        dataType: 'json',
                        success: function (data) {
                            //console.log(data);
                            $("#criterium_id").val(data.id);
                            $("#criterium_naam").val(data.name);
                            $("#criterium_naam").removeClass("hide");
                        },
                    });
                });

                // Delete criteria
                $("button[name=DeleteCriterium]").click(function (event) {
                    event.preventDefault();
                    // ophalen van het id
                    var werkproces_criterium_id = $(this).data("id");
                    
                    // link aanpassen
                    $("#delhref").attr("href", "delete_criterium.php?id=" + werkproces_criterium_id);
                });

                // Add criteria
                $("select[name=kerntaak_criterium_option]").on('change', function () {
                    // waarde van geslecteerde id ophalen
                    kt = this.value;
                    //alert(kt);

                    // Alles leeg maken:
                    $("select[name=werkproces_criterium_option]").empty().append($('<option>', {
                        value: 0,
                        text: "Kies een werkproces",
                    }));
                    // ophalen van informatie, met ajax
                    $.ajax({
                        type: 'GET',
                        url: 'json_add_criterium.php',
                        data: {id: kt},
                        dataType: 'json',
                        success: function (data) {
                            //alert(data);
                            $.each(data, function (index, element) {
                                //console.log(element.name);
                                $("select[name=werkproces_criterium_option]").append($('<option>', {
                                    value: element.id,
                                    text: element.name
                                }));
                            });
                            // toepassen css
                            // ** material only! **
                            $("select[name=werkproces_criterium_option]").material_select();
                            // als alles is opgehaald. Select weer laten zien.
                            //$("select[name=werkproces]").show();
                            $("select[name=werkproces_criterium_option]").closest('.select-wrapper').removeClass("hide");
                        }
                    });
                });
                $("select[name=werkproces_criterium_option]").on('change', function () {
                    $("input[name=criterium_oms]").removeClass("hide");
                    $("button[name=new_criterium_submit]").removeClass("hide");
                });
            });
        </script>
    </body>
</html>
