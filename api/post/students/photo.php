<?php 

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo "Please submit the form to view data";
    return;
}

$photoURL = $_POST['photoUrl'];
$URLstored = '';

if ($photoURL != '' && filter_var($photoURL, FILTER_VALIDATE_URL)) {
    $headers = @get_headers($photoURL);
    
    if ($headers && strpos($headers[0], '200') !== false) {

        $imageContent = @file_get_contents($photoURL);
        $fileExtension = pathinfo($photoURL, PATHINFO_EXTENSION);
        $newFileName = uniqid('url_', true) . '.' . $fileExtension;
        $uploadFile = '../../../uploads/' . $newFileName;

        if (file_put_contents($uploadFile, $imageContent) !== false) {
            $URLstored = $newFileName; 
        } else {
            echo "Error saving image from URL.";
        }
    } else {
        echo "The URL is not accessible or invalid.";
    }
} else {
    echo "The URL provided is not valid.";
}

if ($URLstored == '' && isset($_FILES['upload']) && $_FILES['upload']['error'] === UPLOAD_ERR_OK) {
    if (!file_exists('../../../uploads')) {
        mkdir('../../../uploads', 0777, true); 
    }
    $originalFileName = $_FILES['upload']['name'];

    $fileExtension = pathinfo($originalFileName, PATHINFO_EXTENSION);

    $newFileName = uniqid('pp_', true) . '.' . $fileExtension;

    $uploadFile = '../../../uploads/' . $newFileName;
    if (move_uploaded_file($_FILES['upload']['tmp_name'], $uploadFile)) {
        $URLstored = $newFileName;
    }
}



include '../../../src/modules/database.php';

$conn = (new Database())->getConnection();

$account_number = $_POST['account_number'];

$sql = "SELECT photos FROM Students WHERE account_number = ?";
$result = $conn->execute_query($sql, [$account_number]);
$row = $result->fetch_assoc();
$photos = json_decode($row['photos'], true);

if (!is_array($photos)) {
    $photos = [];
}

if ($URLstored) {
    $photos[] = $URLstored;
}

$updated_photos = json_encode($photos);

$update_sql = "UPDATE Students SET photos = ? WHERE account_number = ?";
$conn->execute_query($update_sql, [$updated_photos, $account_number]);

echo 'Photo saved <a href="http://localhost/views/students/profile/index.php?account_number='.$account_number.'">Go back</a>';

$conn->close();