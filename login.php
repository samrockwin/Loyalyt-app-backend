<?php
require 'cors.php';
require 'db.php';

$data = json_decode(file_get_contents("php://input"));

if(isset($data->identifier) && isset($data->password)) {
    $identifier = $data->identifier; // Email or Mobile
    $password = $data->password;

    $sql = "SELECT * FROM users WHERE email = :identifier OR mobile = :identifier";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':identifier', $identifier);
    $stmt->execute();
    
    if($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if(password_verify($password, $user['password'])) {
            unset($user['password']);
            echo json_encode(["message" => "Login successful", "user" => $user]);
        } else {
            http_response_code(401);
            echo json_encode(["message" => "Invalid credentials"]);
        }
    } else {
        http_response_code(401);
        echo json_encode(["message" => "User not found"]);
    }
} else {
    http_response_code(400);
    echo json_encode(["message" => "Incomplete data"]);
}
?>