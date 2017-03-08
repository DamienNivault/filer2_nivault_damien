<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Home page</title>
    <script type="text/javascript" src="assets/script/jquery-3.1.1.min.js"></script>
    <script src="assets/script/script.js"></script>
    <link href="assets/css/profile.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">

</head>
<body>
<header>
    <div id="welcome">
        <?php echo  " <div id='welcomeSession'>Welcome " .$username."</div>"?>
        <div id="title"> LibertyFile</div>
        <div id="titleLogout"><a href="?action=logout" id="logout">Logout</a></div>
    </div>
</header>
<div id="blockError">
    <?php if (!empty($error)): ?>
        <p>Error : <?php echo $error ?></p>
    <?php endif; ?>
</div>
<div id="form">
    <form action="?action=profile" method="POST" enctype="multipart/form-data" class="sendFile">
        <fieldset class="fieldsetSend">
            <legend> Drop you're file</legend>

            <input type="file" name="file" id="file"><br><br>
            <label for="edit_name">Name ur File, or not</label><br><br>
            <input type="text" name="edit_name" id="edit_name"><br>
            <input type="submit" name="upload" value="submit">
        </fieldset>

    </form>

    <form action="?action=profile" method="POST" enctype="multipart/form-data" class="sendFile">
        <fieldset class="fieldsetSend">
            <legend> Replace a file</legend>

            <input type="file" name="file_to_replace" id="file_to_replace"><br><br>
            <label for="edit_name">Name the file to replace</label><br><br>
            <input type="text" name="name_file_to_replace" id="name_file_to_replace"><br>
            <input type="submit" name="replace" value="submit">
        </fieldset>

    </form>

</div>

<?php


foreach ($files as $key) {


    echo "<div class='img_file'>";
    if ($key['types'] === 'image') {
        echo "<img class='img' src=" . $key['file_url'] . " alt=" . $key['file_name'] . ">";
    } else if ($key['types'] === 'text') {
        echo "<img class='img classText' src='assets/images/text.png' alt='File Text'><br><br>";
      echo   "
            <button id='myBtn'>Modify</button>
            <div class=\"modal-content\">
             
        <div id='myModal' class='modal'>";
        echo "<form action=\"?action=profile\" method=\"POST\">
                    <textarea name='txt_content'>" . file_get_contents($key['file_url']) . "</textarea>
            <input class='none' type='text' name='url_txt' value='" . $key['file_url'] . "'>   
            <input type='submit' name='modify' value='Save'>
            </form>
              </div>
               </div>";
    } else if ($key['types'] === 'application') {
        echo "<embed class='img' src=" . $key['file_url'] . ">";
    } else if ($key['types'] === 'audio') {
        echo "<audio controls='controls' class='img' src=" . $key['file_url'] . "></audio>";
    } else if ($key['types'] === 'video') {
        echo "<video controls='controls' class='img' src=" . $key['file_url'] ."></video>";
    }

    echo "<p class='bouton'>" . $key['file_name'] . "</p><br><div class='alignButton'><a  href='" . $key['file_url'] . "'   download='" . $key['file_name'] . "'><img   title='Téléchargement' alt='téléchargement'  src='assets/images/bouton-dl.png'></a></div>";
    echo "<form action=\"?action=profile\" method=\"POST\" class=\"sendFile\">
        <input class='buttonNone'  type=\"text\" name=\"file_to_delete\"  value='" . $key['file_url'] . "'>
        <div class='alignButton'><input  type=\"submit\" name=\"submit_delete\" value=\"delete\"> </div>
    </form>";

    echo "<form action=\"?action=profile\" method=\"POST\" class=\"sendFile\">               
                  <input class = 'none' type='text' name='current_file_name' value='" . $key['file_name'] . "'>
                                        <input class = 'none' type='text' name='file_to_rename' value='" . $key['file_url'] . "'>
                                          <input type=\"text\" name=\"new_name\" placeholder='rename ur file'><br>
                                          <input type=\"submit\" name=\"submit_rename\" value=\"rename\">
                          </form>";
    echo "</div>";
}
?>



</body>
</html>