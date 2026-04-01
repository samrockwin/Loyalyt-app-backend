<?php
require 'cors.php';
require 'db.php';

$data = json_decode(file_get_contents("php://input"));

if(isset($data->name) && (isset($data->email) || isset($data->mobile)) && isset($data->password)) {
    $name = $data->name;
    $email = $data->email;
    $mobile = $data->mobile;
    $password = password_hash($data->password, PASSWORD_BCRYPT);

    try {
        $sql = "INSERT INTO users (name, email, mobile, password) VALUES (:name, :email, :mobile, :password)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':mobile', $mobile);
        $stmt->bindParam(':password', $password);
        
        if($stmt->execute()) {
            echo json_encode(["message" => "User registered successfully"]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Registration failed"]);
        }
    } catch(PDOException $e) {
        http_response_code(500);
        echo json_encode(["message" => "Error: " . $e->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(["message" => "Incomplete data"]);
}
?>