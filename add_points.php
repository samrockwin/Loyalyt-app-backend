<?php
require 'cors.php';
require 'db.php';

$data = json_decode(file_get_contents("php://input"));

if(isset($data->user_id) && isset($data->cost) && isset($data->type)) {

    $user_id = $data->user_id;
    $cost = $data->cost;
    $type = $data->type;
    $description = isset($data->description) ? $data->description : '';

    // 🔥 CALCULATE 2% POINTS HERE (IMPORTANT)
    $points = floor($cost * 0.02);

    $sql = "INSERT INTO loyalty_points (user_id, points, cost, description, type) 
            VALUES (:user_id, :points, :cost, :description, :type)";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':points', $points);
    $stmt->bindParam(':cost', $cost);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':type', $type);

    if($stmt->execute()) {
        echo json_encode([
            "message" => "Points added successfully",
            "points_earned" => $points
        ]);
    } else {
        http_response_code(500);
        echo json_encode(["message" => "Failed to add points"]);
    }

} else {
    http_response_code(400);
    echo json_encode(["message" => "Incomplete data"]);
}
?>