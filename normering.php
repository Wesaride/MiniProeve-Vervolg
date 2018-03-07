<?php
include("check.php");
include("connect.php");

if (isset($_POST['post_kerntaak'])){
    $_SESSION['session_kerntaak'] = $_POST['post_kerntaak'];
}
if (isset($_POST['post_werkproces'])){
    $_SESSION['session_werkproces'] = $_POST['post_werkproces'];
}
if (isset($_POST['post_criteria'])){
    $_SESSION['session_criteria'] = $_POST['post_criteria'];
}
//bereid session locals voor
$session_kerntaak = $session_werkproces = $session_criteria = "";
if (isset($_SESSION['session_kerntaak'])){
    $session_kerntaak = $_SESSION['session_kerntaak'];
}
if (isset($_SESSION['session_werkproces'])){
    $session_werkproces = $_SESSION['session_werkproces'];
}
if (isset($_SESSION['session_criteria'])){
    $session_criteria = $_SESSION['session_criteria'];
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
        include("ModalAddNormering.php");
        include("ModalEditNormering.php");
        include("ModalDeleteNormering.php");
        ?>
        <div class="row">
            <div class="col s12 m4 l3 sidebar">
                <br />
                <?php
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
                            $selectedvalue = "";
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
                <select name="selected_werkproces" class="hide">

                </select>
                <select name="selected_criteria" class="hide">

                </select><br />
            </div>
            <div style="overflow: scroll; height: 85%" class="col s12 m8 l9">
                <h4>Overzicht normeringen <a data-target="ModalAddNormering" class="btn-floating btn-small waves-effect waves-light green btn modal-trigger"><i class="material-icons" >add</i></a></h4>
                <?php
//                if (isset($_SESSION['session_kerntaak'])){
//                    echo $_SESSION['session_kerntaak'];
//                    
//                }
//                if (isset($_SESSION['session_werkproces'])){
//                    echo $_SESSION['session_werkproces'];
//                    
//                }
//                if (isset($_SESSION['session_criteria'])){
//                    echo $_SESSION['session_criteria'];
//                    
//                }
                ?>
                <table id="show_normering" class="hide">
                    <thead>
                        <tr>
                            <th>Normering</th>
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
        <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.5/js/materialize.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function () {
                // the "href" attribute of .modal-trigger must specify the modal ID that wants to be triggered
                $(".modal-trigger").leanModal();
                $("select").material_select();
                $(".button-collapse").sideNav();
                //alert(  );
                            
                //kerntaak is geselecteerd. Zoek werkprocessen.
                $("select[name=selected_kerntaak]").on('change', function () {
                    // waarde van geslecteerde id ophalen
                    kerntaak_id = this.value;
                    $.post('normering.php', {post_kerntaak: kerntaak_id});

                    // Werkproces leeg maken:
                    $("select[name=selected_werkproces]").empty().append($('<option>', {
                        value: 0,
                        text: "Kies een werkproces"
                    }));

                    // ophalen van werkprocessen, met ajax
                    $.ajax({
                        type: 'GET',
                        url: 'json_show_werkproces.php',
                        data: {id: kerntaak_id},
                        dataType: 'json',
                        success: function (data) {
                            //alert(data);
                            $.each(data, function (index, element) {
                                //console.log(element.name);
                                //alert(element.id);
                                //creer de content voor de dropdown menu voor werkprocessen
                                if ('<?php echo $session_werkproces?>' === element.werkproces_id){
                                    $("select[name=selected_werkproces]").append($('<option>', {
                                        value: element.werkproces_id,
                                        text: element.werkproces_name,
                                        selected: 1
                                    }));
                                }
                                else{
                                    $("select[name=selected_werkproces]").append($('<option>', {
                                        value: element.werkproces_id,
                                        text: element.werkproces_name
                                    }));
                                }
                            });
                            // toepassen css
                            // ** material only! **
                            $("select[name=selected_werkproces]").material_select();
                            $("select[name=selected_criteria]").material_select();
                            // als alles is opgehaald. Select weer laten zien.
                            $("select[name=selected_werkproces]").closest('.select-wrapper').removeClass("hide");
                            if (!$("table[id=geen_resultaten]").hasClass("hide")){
                                //als het "geen resultaten" scherm bestaat. Verstop het.
                                $("table[id=geen_resultaten]").addClass("hide");
                            }
                            //trigger het laten zien van de volgende lijst alleen als deze gevuld is. Wouter heeft hierbij geholpen.
                            <?php
                            if (isset($_SESSION['session_werkproces'])){
                            ?>
                                if (typeof(<?php echo $_SESSION['session_werkproces']?>) !== "undefined"){
                                    $("select[name=selected_werkproces]").trigger('change');
                                    //alert('jahoor');
                                }
                            <?php
                            }
                            ?>
                        },
                        error: function () {
                            //drop menu content is leeg
                            //alert('error');
                            //laat zien op de pagina dat er geen resultaten zijn
                            if (!$("table[id=show_normering]").hasClass("hide")){
                                //haalt overzicht normering weg
                                $("table[id=show_normering]").addClass("hide");
                            }
                            $("table[id=geen_resultaten]").removeClass("hide");
                            //hide de andere dropdown menus
                            if (!$("select[name=selected_werkproces]").closest('.select-wrapper').hasClass("hide")){
                                $("select[name=selected_werkproces]").closest('.select-wrapper').addClass("hide");
                            }
                            if (!$("select[name=selected_criteria]").closest('.select-wrapper').hasClass("hide")){
                                $("select[name=selected_criteria]").closest('.select-wrapper').addClass("hide");
                            }
                        }
                    });
                    if (!$("table[id=show_normering]").hasClass("hide")){
                        //haalt overzicht normering weg
                        $("table[id=show_normering]").addClass("hide");
                    }
                });
                //trigger het laten zien van de eerste lijst met de change function.
                <?php
                if (isset($_SESSION['session_kerntaak'])){
                ?>
                    if (typeof(<?php echo $_SESSION['session_kerntaak']?>) !== "undefined"){
                        $("select[name=selected_kerntaak]").trigger('change');
                    }
                <?php
                }
                ?>
                //werkproces is geselecteerd. Zoek criteria.
                $("select[name=selected_werkproces]").on("change", function () {
                    
                    // waarde van geslecteerde id ophalen
                    werkproces_id = this.value;
                    //alert(werkproces_id + "!");
                    $.post('normering.php', {post_werkproces: werkproces_id});
                    // Alles leeg maken:
                    $("select[name=selected_criteria]").empty().append($('<option>', {
                        value: 0,
                        text: "Kies een criteria"
                    }));
                    // Ophalen informatie met Ajax
                    $.ajax({
                        type: 'GET',
                        url: 'json_show_criterium.php',
                        data: {id: werkproces_id},
                        dataType: 'json',
                        success: function (data) {
                            //alert(data);
                            //creer de content voor de dropdown menu voor werkprocessen
                            $.each(data, function (index, element) {
                                if ('<?php echo $session_criteria?>' === element.criterium_id){
                                    $("select[name=selected_criteria]").append($('<option>', {
                                        value: element.criterium_id,
                                        text: element.criterium_naam,
                                        selected: 1
                                    }));
                                }
                                else{
                                    $("select[name=selected_criteria]").append($('<option>', {
                                        value: element.criterium_id,
                                        text: element.criterium_naam
                                    }));
                                }
                                //console.log(element.criterium_id, element.criterium_naam);
                            });
                            $("select[name=selected_criteria]").material_select();
                            //$("select[name=selected_criteria]").show();
                            $("select[name=selected_criteria]").closest('.select-wrapper').removeClass("hide");
                            if (!$("table[id=geen_resultaten]").hasClass("hide")){
                                //als het "geen resultaten" scherm bestaat. Verstop het.
                                $("table[id=geen_resultaten]").addClass("hide");
                            }
                            
                           //trigger het laten zien van de volgende lijst alleen als deze gevuld is. Wouter heeft hierbij geholpen.
                           <?php
                            if (isset($_SESSION['session_criteria'])){
                            ?>
                                if (typeof(<?php echo $_SESSION['session_criteria']?>) !== "undefined"){
                                    $("select[name=selected_criteria]").trigger('change');
                                }
                            <?php
                            }
                            ?>
               
                        },
                        error: function () {
                            //drop menu content is leeg
                            //alert('error');
                            //laat zien op de pagina dat er geen resultaten zijn
                            $("table[id=geen_resultaten]").removeClass("hide");
                            //hide de andere dropdown menus
                            if (!$("select[name=selected_criteria]").closest('.select-wrapper').hasClass("hide")){
                                $("select[name=selected_criteria]").closest('.select-wrapper').addClass("hide");
                            }
                        }
                    });
                    if (!$("table[id=show_normering]").hasClass("hide")){
                        //haalt overzicht normering weg
                        $("table[id=show_normering]").addClass("hide");
                    }
                });
                
             
                
                
                // Add normering
                $("select[name=kerntaak_option]").on('change', function () {
                    // waarde van geslecteerde id ophalen
                    kt = this.value;
                    //alert(kt);

                    // Alles leeg maken:
                    $("select[name=werkproces_option]").empty().append($('<option>', {
                        value: 0,
                        text: "Kies een werkproces"
                    }));
                    $("select[name=criterium_option]").empty().append($('<option>', {
                        value: 0,
                        text: "Kies een criterium"
                    }));

                    // ophalen van informatie, met ajax
                    $.ajax({
                        type: 'GET',
                        url: 'json_show_werkproces.php',
                        data: {id: kt},
                        dataType: 'json',
                        success: function (data) {
                            //alert(data);
                            $.each(data, function (index, element) {
                                //console.log(element.name);
                                $("select[name=werkproces_option]").append($('<option>', {
                                    value: element.id,
                                    text: element.name
                                }));
                            });
                            // toepassen css
                            // ** material only! **
                            $("select[name=werkproces_option]").material_select();
                            $("select[name=criterium_option]").material_select();
                            // als alles is opgehaald. Select weer laten zien.
                            //$("select[name=werkproces]").show();
                            $("select[name=werkproces_option]").closest('.select-wrapper').removeClass("hide");
//                            $("select[name=criterium_option]").closest('.select-wrapper').removeClass("hide");
                        }
                    });
                });
                
                
                $("select[name=werkproces_option]").on('change', function () {
                    // waarde van geslecteerde id ophalen
                    wp = this.value;
                    //alert(kt);

                    $("select[name=criterium_option]").empty().append($('<option>', {
                        value: 0,
                        text: "Kies een criterium"
                    }));

                    // ophalen van informatie, met ajax
                    $.ajax({
                        type: 'GET',
                        url: 'json_show_criterium.php',
                        data: {id: wp},
                        dataType: 'json',
                        success: function (data) {
                            //alert("data");
                            $.each(data, function (index, element) {
                                //console.log(element.name);
                                $("select[name=criterium_option]").append($('<option>', {
                                    value: element.criterium_id,
                                    text: element.criterium_naam
                                }));
                            });
                            // toepassen css
                            // ** material only! **
                            $("select[name=criterium_option]").material_select();
                            // als alles is opgehaald. Select weer laten zien.
                            //$("select[name=werkproces]").show();
                            //$("select[name=werkproces_option]").closest('.select-wrapper').removeClass("hide");
                            $("select[name=criterium_option]").closest('.select-wrapper').removeClass("hide");
                        }
                    });
                });
                
                $("select[name=criterium_option]").on('change', function () {
                    $("textarea[name=normering_naam]").removeClass("hide");
                    $("button[name=new_normering_submit]").removeClass("hide");
                });
               // alert('hier');
                   //$("select[name=selected_criteria]").material_select();
                 //$("select[name=selected_criteria]").show();
                    //$("select[name=selected_criteria]").closest('.select-wrapper').removeClass("hide");            
                
                
                $("select[name=selected_criteria]").on("change", function () {
                    
                    criteria_id = this.value;
                    $.post('normering.php', {post_criteria: criteria_id});
                    //alert(criteria_id);
                    // Table overzicht leegmaken voordat er nieuwe data ingeladen wordt.
                    $("tbody[name=tbody]").empty();
                    // Ophalen informatie met Ajax
                    $.ajax({
                        type: 'GET',
                        url: 'json_show_normering.php',
                        data: {id: criteria_id},
                        dataType: 'json',
                        success: function (data) {
                            $.each(data, function (index, element) {
                                //console.log(element.criterium_id, element.criterium_naam);
                                $("#show_normering").find('tbody')
                                    .append($('<tr>', {id: element.normering_id})
                                        .append($('<td>', {text: element.normering_name},))
                                        .append($('<td><button data-target="ModalEditNormering" name="EditNormering" class="EditNormering btn-floating btn-large waves-effect waves-light yellow btn modal-trigger2"><i class="material-icons" >edit</i></button>'))
                                        .append($('<td><button data-target="ModalDeleteNormering" name="DeleteNormering" class="DeleteNormering btn-floating btn-large waves-effect waves-light red btn modal-trigger2"><i class="material-icons">delete</i></button>'))
                                    );
                                //$("select[name=criteria]").material_select();
                                //$("select[name=selected_criteria]").show();
                                if(!$("table[id=geen_resultaten]").hasClass("hide")){
                                    $("table[id=geen_resultaten]").addClass("hide");
                                }
                                $("table[id=show_normering]").removeClass("hide");
                            });
                            $(".modal-trigger2").leanModal();
                            // Edit normering
                            $(".EditNormering").on('click', function () {
                                // waarde van het geselecteerde id ophalen
                                id_normering = $(this).parent().parent().attr('id');
                                //alert(id_normering);


                                // Velden leeg maken
                                document.getElementById("normering_id").value = "";
                                document.getElementById("modal_normering_naam").value = "";

                                // ophalen van informatie, met ajax om naam/omschrijving kerntaak op te halen
                                $.ajax({
                                    type: 'GET',
                                    url: 'json_edit_normering.php',
                                    data: {id: id_normering},
                                    dataType: 'json',
                                    success: function (data) {
                                        //console.log(data);
                                        $("#normering_id").val(data.id);
                                        $("#modal_normering_naam").val(data.name);
                                        $("#modal_normering_naam").removeClass("hide");
                                    }
                                });
                            });
                            
                            //DELETE normering
                            $(".DeleteNormering").on('click', function () {
                                // ophalen van het id
                                var criterium_normering_id = $(this).parent().parent().attr('id');
                                //console.log(werkproces_criterium_id);

                                // link aanpassen
                                $("#delhref").attr("href", "delete_normering.php?id=" + criterium_normering_id);
                            });
                        },
                        error: function () {
                            if (!$("table[id=show_normering]").hasClass("hide")){
                                //haalt overzicht normering weg
                                $("table[id=show_normering]").addClass("hide");
                            }
                            $("table[id=geen_resultaten]").removeClass("hide");
                        }
                    });
                });
            });
        </script>
    </body>
</html>
