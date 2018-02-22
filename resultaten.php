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
        ?>
        <div class="row">
            <div class="col s12 m4 l3 sidebar">
                <br />
                <?php //haal klassen records op
                $sql_klassen = "SELECT * FROM klas";
                $result_klassen = $conn->query($sql_klassen);
                if ($result_klassen->num_rows > 0) {
                    //start select element en eerste selected option
                    echo "<select name='selected_klas' required style='padding:0; margin:0;'>";
                    echo     "<option selected='selected' disabled>Kies een klas</option>";
                    //genereer voor alle records in de results een option
                    while ($row_klassen = $result_klassen->fetch_assoc()) {
                        $klas_id = $row_klassen["klas_id"];
                        $klas_naam = $row_klassen["klas_naam"];
                        echo "<option value='$klas_id'>Klas: $klas_naam</option>";
                    }
                    //einde select element
                    echo "</select>";
                } ?>
                
                <select name="selected_student" size="7" multiple class="browser-default" 
                        style="height:auto; margin:0; padding:0; border: none;">
                    <option value="1" style="height:35px; margin:0; padding:0; border:none;">
                        dit is een optie</option>
                    <option value="2">dit is een andere optie</option>
                    <option value="3">dit is nog een optie</option>
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

            </div>
            <div class="col s12 m8 l9" margin="0 auto">
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
                    klas_id = this.value;
                    alert(klas_id);
                });
                
                //onchange search
                //haal studenten op en geef weer in sidebar
                $("select[name=search]").on('change', function () {
                    search_term = this.value;
                    alert(search_term);
                });
                
                //Selecteer student
                $("select[name=selected_student]").on('change', function () {
                    student_id = this.value;
                    alert(student_id);
                });
            });
        </script>
    </body>
</html>
