<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Home page</title>

</head>
<body>
<div id="form">
<form action="?action=home" method="POST" enctype="multipart/form-data" class="sendFile">
    <fieldset class="fieldsetSend">
        <legend> Drop you're file</legend>

        <input type="file" name="file" id="file"><br><br>
        <label for="edit_name">Name ur File, or not</label><br><br>
        <input type="text" name="edit_name" id="edit_name"><br>
        <input type="submit" name="upload" value="submit">
    </fieldset>

</form>

</div>

<?php


foreach ($files as $key) {



        echo "<div class='img_file'>";
        if($key['type']==='image') {
            echo "<img class='img' src=" . $key['file_url'] . " alt=" . $key['file_name'] . ">";
        }
        else if($key['type']==='text') {
            echo "<img class='img' src='assets/images/text.png' alt='File Text'>";
        }
        else if($key['type']==='pdf'){
              echo "<img class='img' src='assets/images/pdf.png' alt='File Text'>";
        }

        echo "<p class='bouton'>" . $key['file_name'] . "</p><br><a  href='" . $key['file_url'] . "'   download='" . $key['file_name'] . "'><img title='Téléchargement' alt='téléchargement'  src='assets/images/bouton-dl.png'></a>";
        echo "<form action=\"?action=home\" method=\"POST\" class=\"sendFile\">
        <input class='buttonNone'  type=\"text\" name=\"file_to_delete\"  value='" . $key['file_url'] . "'><br>
        <input class='alignButton' type=\"submit\" name=\"submit_delete\" value=\"delete\">
    </form>";
        echo "</div>";
        echo "<form action=\"?action=home\" method=\"POST\" class=\"sendFile\">               
                  <input class = 'none' type='text' name='current_file_name' value='" . $key['file_name'] . "'>
                                        <input class = 'none' type='text' name='file_to_rename' value='" . $key['file_url'] . "'>
                                          <input type=\"text\" name=\"new_name\" id=\"new_name\"><br>
                                          <input type=\"submit\" name=\"submit_rename\" value=\"submit\">
                          </form>";
        echo "</div>";




}
    ?>

<a href="?action=logout">Déconnecté</a>

</body>
</html>