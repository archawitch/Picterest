<?php
include_once '../config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

function decryptData($data) {
    // Store the cipher method
    $ciphering = "AES-128-CTR";
    
    // Use OpenSSl Encryption method
    $options = 0;
    
    // Non-NULL Initialization Vector for encryption
    $encryption_iv = "1234567891011121";
    
    // Store the encryption key
    $encryption_key = "2tHOLGkppCnAx6XVIcRRGwxKtjVj4PXL";
    
    $decrypted = openssl_decrypt($data, $ciphering, $encryption_key, $options, $encryption_iv);
    return $decrypted;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"));
    $username = $data->username;
    $password = $data->password;

    $selectUserDataQuery = "SELECT username, password, user_type, email, first_name, last_name, bio, 
                                        profile_image_path FROM user 
                                        WHERE username = ?";

    $stmt = $conn->prepare($selectUserDataQuery);

    if($stmt){
        // Bind username
        $stmt->bind_param("s", $username);

        // Execute the statement
        $stmt->execute();

        // Bind the result variables
        $stmt->bind_result($username, $hashed_password, $user_type, $email, $first_name, $last_name, $bio, $profile_image_path);

        // User found
        if($stmt->fetch()){

            // Verify password
            if(password_verify($password, $hashed_password)){

                $decryptedEmail = decryptData($email);

                http_response_code(200);
                echo json_encode(array(
                    "success" => true,
                    "message" => "Logged in successfully",
                    "userData" => array(
                        "username" => $username,
                        "userType" => $user_type,
                        "email" => $decryptedEmail,
                        "firstName" => $first_name,
                        "lastName" => $last_name,
                        "bio" => $bio,
                        "profileImage" => $profile_image_path
                    )
                ));
            } else {
                echo json_encode(array("success" => false ,"message" => "Logging in failed"));
            }

        } else {
            echo json_encode(array("success" => false ,"message" => "User not found"));
        }
    } else {
        http_response_code(500);
        echo json_encode(array("error" => "Error in prepared statement: " . $conn->error));
    }
}

// Close the database connection
$conn->close();
?>