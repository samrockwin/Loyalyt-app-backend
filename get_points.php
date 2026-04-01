<?php
require 'cors.php';
require 'db.php';

if(isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    $sql = "SELECT SUM(points) as total_points FROM loyalty_points WHERE user_id = :user_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $total_points = $result['total_points'] ? $result['total_points'] : 0;

    // Also fetch history
    $sql_history = "SELECT * FROM loyalty_points WHERE user_id = :user_id ORDER BY created_at DESC";
    $stmt_history = $conn->prepare($sql_history);
    $stmt_history->bindParam(':user_id', $user_id);
    $stmt_history->execute();
    $history = $stmt_history->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(["total_points" => $total_points, "history" => $history]);
} else {
    http_response_code(400);
    echo json_encode(["message" => "User ID required"]);
}
?>