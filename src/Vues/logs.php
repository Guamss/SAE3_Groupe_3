
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
    <a href='index.php?uc=logs&dir=user'>User</a><br>
    <a href='index.php?uc=logs&dir=ticket'>Ticket</a>
    <table><tbody>
    </div>";

    switch($actual_dir)
    {
        case "user" :
            $files = list_dir("log/user");
            foreach ($files as &$file)
            {
                if ($file != $today_user_log)
                {
                    echo "
                    <tr>
                        <td><button value=\"$file\" onclick=\"DownloadFile('$file', 'log/user/$file')\">$file</button></td>
                    </tr>";
                }
            }
            echo "</tbody></table>";
            break;
        case "ticket" :
            $files = list_dir("log/ticket");
            foreach ($files as &$file)
            {
                if ($file != $today_ticket_log)
                {
                    echo "
                    <tr>
                        <td><button value=\"$file\" onclick=\"DownloadFile('$file', 'log/ticket/$file')\">$file</button></td>
                    </tr>";
                }                
            }
            echo "</tbody></table>";
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
