<?php
// Set the upload folder path
$target_path = "uploads/";
// Set the new path with the file name
$target_path = $target_path . basename( $_FILES['myfile']['name']); 

// Move the file to the upload folder
if(move_uploaded_file($_FILES['myfile']['tmp_name'], $target_path)) {
	// print the new image path in the page, and this will recevie the javascripts 'response' variable
    echo $target_path;
} else{
	// Set default the image path if any error in upload.
    echo "default.jpg";
}

?>