<?php
include("check.php");
include("connect.php");

if (isset($_POST['post_klas'])){
    $_SESSION['session_klas'] = $_POST['post_klas'];
}
if (isset($_POST['post_student'])){
    $_SESSION['session_student'] = $_POST['post_student'];
}
//bereid session locals voor
$session_klas = $session_student = "";
if (isset($_SESSION['session_klas'])){
    $session_klas = $_SESSION['session_klas'];
}
if (isset($_SESSION['session_student'])){
    $session_student = $_SESSION['session_student'];
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
        ?>
        <div class="row">
            <div class="col s12 m4 l3 sidebar">
                <br />
                <?php //haal klassen records op
                //$session_klas = "";
                /*if (isset($_SESSION['session_klas'])){
                    $session_klas = $_SESSION['session_klas'];
                }*/
                $sql_klas = "SELECT * FROM klas";
                $result_klas = $conn->query($sql_klas);
                if ($result_klas->num_rows > 0) {
                    ?>
                    <select name="selected_klas" required>
                        <?php
                        if (isset($_SESSION['session_klas'])){
                            echo '<option disabled>Kies een klas</option>';
                        }
                        else{
                            echo "<option selected='selected' disabled>Kies een klas</option>";
                        }
                        while ($row_klas = $result_klas->fetch_assoc()) {
                            if (isset($_SESSION['session_klas'])){
                                if ($_SESSION['session_klas'] == $row_klas['klas_id']){
                                    $selectedvalue = "selected='selected'";
                                }
                                else{
                                    $selectedvalue = "";
                                }
                            }
                            echo "<option " . $selectedvalue . " value=" . $row_klas['klas_id'] . ">" . $row_klas['klas_naam'] . "</option>";
                        }
                        ?>
                    </select>
                    <?php
                }
                ?>
                
                <select name="selected_student" class="">
                </select>
                
                <br />
                <div>Zoek op student</div>
                <div style="bottom: 0;">
                    <div style="float:left; width:20px;">
                        <i class="material-icons">search</i>
                    </div>
                    <div style="margin-left: 30px;">
                        <input name="search" style="background-color: white; width:100%; height: 25px;">
                    </div>
                </div>
                
                <select name="selected_search_student" class="">
                </select>
                
            </div>
            <div style="overflow: scroll; height: 85%" class="col s12 m8 l9" margin="0 auto">
                <h4>Resultaten en Beoordelen</h4>
                <table>
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Cohort</th>
                        <th>Klas</th>
                        <th>OV-Nummer</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
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
                
                //onchange selected_klas
                //haal studenten op en geef weer in sidebar
                $("select[name=selected_klas]").on('change', function () {
                    //selecteerde id posten naar deze pagina, om straks in de session te gooien
                    klas_id = this.value;
                    $.post('resultaten.php', {post_klas: klas_id});
                    // Student dropdown leeg maken:
                    $("select[name=selected_student]").empty().append($('<option>', {
                        value: 0,
                        text: "Kies een student"
                    }));
                    // ophalen van studenten met ajax
                    $.ajax({
                        type: 'GET',
                        url: 'json_show_student.php',
                        data: {id: klas_id},
                        dataType: 'json',
                        success: function (data) {
                            //alert(data);
                            $.each(data, function (index, element) {
                                //creer de content voor de dropdown menu voor studenten
                                if ('<?php echo $session_student?>' === element.student_id){
                                    $("select[name=selected_student]").append($('<option>', {
                                        value: element.student_id,
                                        text: element.student_name,
                                        selected: 1
                                    }));
                                }
                                else{
                                    $("select[name=selected_student]").append($('<option>', {
                                        value: element.student_id,
                                        text: element.student_name
                                    }));
                                }
                            });
                            // toepassen css
                            // ** material only! **
                            $("select[name=selected_student]").material_select();
                            // als alles is opgehaald. Select weer laten zien.
                            $("select[name=selected_student]").closest('.select-wrapper').removeClass("hide");
                            
                            <?php //trigger het laten zien van de volgende lijst alleen als deze gevuld is. Wouter heeft hierbij geholpen.
                            if (isset($_SESSION['session_student'])){
                            ?>
                                if (typeof(<?php echo $_SESSION['session_student']?>) !== "undefined"){
                                    $("select[name=selected_student]").trigger('change');
                                    //alert('jahoor');
                                }
                            <?php
                            }
                            ?>
                        },
                        error: function () {
                            //drop menu content is leeg
                        }
                    });
                });
                //trigger het laten zien van de gekozen klas als de session_klas bestaat
                <?php
                if (isset($_SESSION['session_klas'])){
                ?>
                    if (typeof(<?php echo $_SESSION['session_klas']?>) !== "undefined"){
                        $("select[name=selected_klas]").trigger('change');
                    }
                <?php
                }
                ?>
                
                //onchange search
                //haal studenten op en geef weer in sidebar
                $("select[name=search]").on('change', function () {
                    search_term = this.value;
                    //alert(search_term);
                    //hide door klas gekozen studentenlijst
                    //un-hide de search studentenlijst
                    //een knoppie met onclick JS functie die de query start? of iedere keypress een nieuwe query?
                    //hoedanook die search SQL moet er komen
                    //ook een .ajax blokje voor het interpreteren van de records
                    
                    $.post('resultaten.php', {post_search: search});
                    // Student dropdown leeg maken:
                    $("select[name=selected_search_student]").empty().append($('<option>', {
                        value: 0,
                        text: "Kies een student"
                    }));
                    // ophalen van studenten met ajax
                    $.ajax({
                        type: 'GET',
                        url: 'json_show_student.php',
                        data: {id: klas_id},
                        dataType: 'json',
                        success: function (data) {
                            //alert(data);
                            $.each(data, function (index, element) {
                                //creer de content voor de dropdown menu voor studenten
                                if ('<?php echo $session_student?>' === element.student_id){
                                    $("select[name=selected_student]").append($('<option>', {
                                        value: element.student_id,
                                        text: element.student_name,
                                        selected: 1
                                    }));
                                }
                                else{
                                    $("select[name=selected_student]").append($('<option>', {
                                        value: element.student_id,
                                        text: element.student_name
                                    }));
                                }
                            });
                            // toepassen css
                            // ** material only! **
                            $("select[name=selected_student]").material_select();
                            // als alles is opgehaald. Select weer laten zien.
                            $("select[name=selected_student]").closest('.select-wrapper').removeClass("hide");
                            
                            <?php //trigger het laten zien van de volgende lijst alleen als deze gevuld is. Wouter heeft hierbij geholpen.
                            if (isset($_SESSION['session_student'])){
                            ?>
                                if (typeof(<?php echo $_SESSION['session_student']?>) !== "undefined"){
                                    $("select[name=selected_student]").trigger('change');
                                    //alert('jahoor');
                                }
                            <?php
                            }
                            ?>
                        },
                        error: function () {
                            //drop menu content is leeg
                        }
                    });
                });
                
                
                //Selecteer student
                $("select[name=selected_student]").on('change', function () {
                    student_id = this.value;
                    //alert(student_id);
                    //selected_student heeft 2 versies.
                    //(1)0klas  (2)search
                    //hoe ga je deze schijden? met een extra select element?
                    //hoedanook die dropdown heb je al.
                    //het belangrijke is dat je de student kan selecteren, dat doe je hier.
                    //nu nog een contentvlak recht van de sidebar waarje al de data v.d. student in moet proppen.
                });
            });
        </script>
    </body>
</html>
