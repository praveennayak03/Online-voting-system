<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("connect.php");

// Check if form data is received
if ($_SERVER['REQUEST_METHOD'] !== "post") {
    http_response_code(405);
    echo json_encode(["error" => "Method not allowed"]);
    
    $name = $_POST['name'];
    $mobile = $_POST['mobile'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    $address = $_POST['address'];
    $role = $_POST['role'];
    
    // File upload handling
    $fileUpload = $_FILES['fileUpload']['name']; 
    $tmp_name = $_FILES['fileUpload']['tmp_name'];

    // Validate password match
    if ($password === $confirmPassword) {
        // Ensure the uploads directory exists
        $uploadDir = "../uploads/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Move uploaded file
        if (move_uploaded_file($tmp_name, $uploadDir . $fileUpload)) {
            // Insert data into the database
            $insert = mysqli_query($connect, "INSERT INTO user (name, mobile, address, password, photo, role, status, votes) 
                                              VALUES ('$name', '$mobile', '$address', '$password', '$fileUpload', '$role', 0, 0)");

            if ($insert) {
                echo '<script>alert("Registration successful"); window.location = "../";</script>';
            } else {
                echo '<script>alert("Error: ' . mysqli_error($connect) . '"); window.location = "../routes/register.html";</script>';
            }
        } else {
            echo '<script>alert("File upload failed"); window.location = "../routes/register.html";</script>';
        }
    } else {
        echo '<script>alert("Passwords do not match"); window.location = "../routes/register.html";</script>';
    }
} else {
    echo '<script>alert("Invalid request"); window.location = "../routes/register.html";</script>';
}
?>