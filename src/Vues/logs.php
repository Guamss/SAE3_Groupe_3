
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.0.0/jquery.min.js"></script>
<script type="text/javascript">
            function DownloadFile(fileName, url) {
            $.ajax({
                url: url,
                cache: false,
                xhr: function () {
                    var xhr = new XMLHttpRequest();
                    xhr.onreadystatechange = function () {
                        if (xhr.readyState == 2) {
                            if (xhr.status == 200) {
                                xhr.responseType = "blob";
                            } else {
                                xhr.responseType = "text";
                            }
                        }
                    };
                    return xhr;
                },
                success: function (data)
                {
                    //Convert the Byte Data to BLOB object.
                    var blob = new Blob([data], { type: "application/octetstream" });
 
                    //Check the Browser type and download the File.
                    var isIE = false || !!document.documentMode;
                    if (isIE) {
                        window.navigator.msSaveBlob(blob, fileName);
                    } else {
                        var url = window.URL || window.webkitURL;
                        link = url.createObjectURL(blob);
                        var a = $("<a />");
                        a.attr("download", fileName);
                        a.attr("href", link);
                        $("body").append(a);
                        a[0].click();
                        $("body").remove(a);
                    }
                }
            });
        };
</script>



<?php

$today_date = date("d-m-Y");
$today_user_log = "historyUser_".$today_date.".csv";
$today_ticket_log = "historyTicket_".$today_date.".csv"; 
$actual_dir = empty($_GET['dir']) ?  "" : $_GET['dir'];

if (file_exists("log/") && (file_exists("log/user") && file_exists("log/ticket")))
{
    echo "<div class='messages'>
    <h2>Journal d'activité</h2>
    <p>Ici vous pouvez voir les journaux d'activité relatifs aux utilisateurs et aux tickets</p>
    <br>
    <h4>Veuillez choisir l'option que vous voulez ci-dessous :</h4>
    <div class='displayChoice'>
        <a href='index.php?uc=logs&dir=user'>User</a>
        <a href='index.php?uc=logs&dir=ticket'>Ticket</a>
    </div>
    <div class='wrap_tab'>
    <table class='table_files'>
        <h3 class='dir'>Répertoire $actual_dir</h3>
        <tbody>";

    switch($actual_dir)
    {
        case "user" :
            $cpt = 0;
            $files = list_dir("log/user");
            foreach ($files as $file) {
                if ($file != $today_user_log) {
                    if ($cpt % 4 == 0) {
                        echo "<tr>";
                    }
                    if (file_exists("log/user/$file")) {
                        $date_modification = date("d/m/Y", filemtime("log/user/$file"));
                    } else {
                        $date_modification = "";
                    }
                    echo "<td class='td_tab'>
                                <button class='fichier' value=\"$file\" onclick=\"DownloadFile('$file', 'log/user/$file')\">$date_modification</button>
                            </td>";
                    $cpt++;
                    if ($cpt % 4 == 0) {
                        echo "</tr>";
                    }
                }
            }
            echo "</tbody></table>";
            break;


        case "ticket" :
            $cpt = 0;
            $files = list_dir("log/ticket");
            foreach ($files as &$file){
                if ($file != $today_ticket_log) {
                    if ($cpt % 4 == 0) {
                        echo "<tr>";
                    }
                    if (file_exists("log/ticket/$file")) {
                        $date_modification = date("d/m/Y", filemtime("log/ticket/$file"));
                    } else {
                        $date_modification = "";
                    }
                    echo "<td class='td_tab'>
                                <button class='fichier' value=\"$file\" onclick=\"DownloadFile('$file', 'log/ticket/$file')\">$date_modification</button>
                            </td>";
                    $cpt++;
                    if ($cpt % 4 == 0) {
                        echo "</tr>";
                    }
                }
            }
            echo "</tbody></table></div>";
            break;
    }
}
else
{
    echo "<div class='messages'>
    <h2>Rien ne s'affiche?</h2>
    <p>Aucun journal d'activité n'a été trouvé</p>
    </div>";
}
?>
