<?php
include("check.php");
include("connect.php");
if (isset($_POST['post_kerntaak'])){
    $_SESSION['session_kerntaak'] = $_POST['post_kerntaak'];
}
if (isset($_SESSION['session_kerntaak'])){
    $session_kerntaak = $_SESSION['session_kerntaak'];
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
        include("ModalAddWerkproces.php");
        include("ModalEditWerkproces.php");
        include("ModalDeleteWerkproces.php");
        ?>
        <div class="row" style="margin-bottom: auto;">
            <div class="col s12 m4 l3" style="background-color: gray; height: 100%;">
                <br>
                <?php
                $session_kerntaak = "";
                if (isset($_SESSION['session_kerntaak'])){
                    $session_kerntaak = $_SESSION['session_kerntaak'];
                }
                $get_kerntaak = "SELECT * FROM kerntaak";
                $result_kerntaak = $conn->query($get_kerntaak);
                if ($result_kerntaak->num_rows > 0) {
                    ?>
                    <select name="selected_kerntaak" required>
                        <?php
                        if (isset($_SESSION['session_kerntaak'])){
                            echo '<option disabled>Kies een kerntaak</option>';
                        }
                        else{
                            echo "<option selected='selected' disabled>Kies een kerntaak</option>";
                        }
                        while ($row_kerntaak = $result_kerntaak->fetch_assoc()) {
                            if (isset($_SESSION['session_kerntaak'])){
                                if ($_SESSION['session_kerntaak'] == $row_kerntaak['kerntaak_id']){
                                    $selectedvalue = "selected='selected'";
                                }
                                else{
                                    $selectedvalue = "";
                                }
                            }
                            echo "<option " . $selectedvalue . " value=" . $row_kerntaak['kerntaak_id'] . ">" . $row_kerntaak['kerntaak_naam'] . "</option>";
                        }
                        ?>
                    </select>
                    <?php
                }
                ?>
            </div>
            <div style="overflow: scroll; height: 85%" class="col s12 m8 l9">
                <h4>Overzicht werkprocessen <a data-target="ModalAddWerkproces" class="btn-floating btn-small waves-effect waves-light green btn modal-trigger"><i class="material-icons" >add</i></a></h4>
                <table id="show_werkproces" class="hide">
                    <thead>
                        <tr>
                            <th>Werkprocesnaam</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody name="tbody">

                    </tbody>
                </table>
                <table id="geen_resultaten" class="hide">
                    <thead>
                        <tr>
                            <th>Geen resultaten</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        <!--EINDE CODE VOOR KLAS TOEVOEGEN BACKEND -->
        <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
        <script src="http://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.5/js/materialize.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function () {
                // the "href" attribute of .modal-trigger must specify the modal ID that wants to be triggered
                $('.modal-trigger').leanModal();
                $('select').material_select();
                $(".button-collapse").sideNav();

                // Show werkproces
                $("select[name=selected_kerntaak]").on('change', function () {
                    kerntaak_id = this.value;
                    $.post('normering.php', {post_kerntaak: kerntaak_id});
                    //alert(kerntaak_id);

                    $("tbody[name=tbody]").empty();

                    // ophalen van informatie, met ajax
                    $.ajax({
                        type: 'GET',
                        url: 'json_show_werkproces.php',
                        data: {id: kerntaak_id},
                        dataType: 'json',
                        success: function (data) {
                            //console.log(data);
                            $.each(data, function (index, element) {
                                $("#show_werkproces").find('tbody')
                                    .append($('<tr>', {id: element.id})
                                        .append($('<td>', {text: element.name},))
                                        .append($('<td><button data-target="ModalEditWerkproces" class="EditWerkproces btn-floating btn-large waves-effect waves-light yellow btn modal-trigger2"><i class="material-icons" >edit</i></button>'))
                                        .append($('<td><button data-target="ModalDeleteWerkproces" class="DeleteWerkproces btn-floating btn-large waves-effect waves-light red btn modal-trigger2"><i class="material-icons">delete</i></button>'))
                                    );
                                //$('#show_klas').append($('<td>', {value: element.klas_id, text: element.name}, '</td>'));
                                $("#show_werkproces").removeClass("hide");
                                if (!$("table[id=geen_resultaten]").hasClass("hide")){
                                    //als het "geen resultaten" scherm bestaat. Verstop het.
                                    $("table[id=geen_resultaten]").addClass("hide");
                                }
                            });
                            $(".modal-trigger2").leanModal();

                            //Edit button Werkproces
                            $(".EditWerkproces").on('click', function () {
                                // waarde van het geselecteerde id ophalen
                                id_werkproces = $(this).parent().parent().attr('id');
                                //alert(id_werkproces);

                                // Velden leeg maken
                                document.getElementById("werkproces_id").value = "";
                                document.getElementById("werkproces_naam").value = "";

                                // ophalen van informatie, met ajax om naam/omschrijving werkproces op te halen
                                $.ajax({
                                    type: 'GET',
                                    url: 'json_edit_werkproces.php',
                                    data: {id: id_werkproces},
                                    dataType: 'json',
                                    success: function (data) {
                                        $("#werkproces_id").val(data.id);
                                        $("#werkproces_naam").val(data.name);
                                        $("#werkproces_naam").removeClass("hide");
                                    }
                                });
                            });
                            
                            // Delete button Werkproces
                            $(".DeleteWerkproces").on('click', function () {
                                
                                // ophalen van het id
                                var werkproces_id = $(this).parent().parent().attr('id');
                                //console.log(werkproces_id);
                                // link aanpassen
                                $("#delhref").attr("href", "delete_werkproces.php?id=" + werkproces_id);
                            });
                        },
                        error: function () {
                            if (!$("table[id=show_werkproces]").hasClass("hide")){
                                //haalt overzicht werkproces weg
                                $("table[id=show_werkproces]").addClass("hide");
                            }
                            //laat "geen resultaten" tab zien
                            $("table[id=geen_resultaten]").removeClass("hide");
                        }
                    });
                });
                <?php
                if (isset($_SESSION['session_kerntaak'])){
                ?>
                    if (typeof(<?php echo $_SESSION['session_kerntaak']?>) !== "undefined"){
                        $("select[name=selected_kerntaak]").trigger('change');
                    }
                <?php
                }
                ?>
            });
        </script>
    </body>
</html>
