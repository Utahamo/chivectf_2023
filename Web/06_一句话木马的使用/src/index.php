<?php
header("Content-Type: text/html;charset=utf-8");
//write a new page index.html which contains a form to upload file
echo "<form action=\"upload.php\" method=\"post\" enctype=\"multipart/form-data\">";
echo "Select file to upload: <input type=\"file\" name=\"file\" id=\"file\" />";
echo "<input type=\"submit\" name=\"submit\" value=\"Upload\" />";
echo "</form>";
//check if the file is uploaded successfully
if ($_FILES["file"]["error"] > 0)
{
    echo "Error: " . $_FILES["file"]["error"] . "<br />";
}
else
{
    //print the file information
    echo "Upload: " . $_FILES["file"]["name"] . "<br />";
    echo "Type: " . $_FILES["file"]["type"] . "<br />";
    echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
    //check if the file is already exist
    if (file_exists("upload/" . $_FILES["file"]["name"]))
    {
        echo $_FILES["file"]["name"] . " already exists. ";
    }
    else
    {
        //move the file to the upload folder
        move_uploaded_file($_FILES["file"]["tmp_name"], "upload/" . $_FILES["file"]["name"]);
        echo "Stored in: " . "upload/" . $_FILES["file"]["name"];
    }
}


