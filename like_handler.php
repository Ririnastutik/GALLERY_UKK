<?php

$pdo = new PDO('mysql:host=localhost;dbname=galerifoto', 'root', '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $image_id = $_POST['image_id'];
    $admin_id = $_POST['admin_id'];
    $action = $_POST['action'];


    $countQuery = $pdo->prepare("SELECT * FROM likes WHERE image_id = :image_id AND admin_id = :admin_id");
    $countQuery->execute(['image_id' => $image_id, 'admin_id' => $admin_id]);
    $likeRecord = $countQuery->fetch(PDO::FETCH_OBJ);

    if ($action === 'like') {
        if (!$likeRecord) {
        
            $insertQuery = $pdo->prepare("INSERT INTO likes (likeTotal, admin_id, image_id) VALUES (1, :admin_id, :image_id)");
            $insertQuery->execute(['admin_id' => $admin_id, 'image_id' => $image_id]);
            $liked = true;
        } else {
    
            $liked = true; 
        }
    } elseif ($action === 'dislike') {
        if ($likeRecord) {
    
            $deleteQuery = $pdo->prepare("DELETE FROM likes WHERE image_id = :image_id AND admin_id = :admin_id");
            $deleteQuery->execute(['image_id' => $image_id, 'admin_id' => $admin_id]);
            $liked = false;
        } else {
            $liked = false;
        }
    }

    $totalQuery = $pdo->prepare("SELECT SUM(likeTotal) as totalLikes FROM likes WHERE image_id = :image_id");
    $totalQuery->execute(['image_id' => $image_id]);
    $totalLikes = $totalQuery->fetch(PDO::FETCH_OBJ)->totalLikes;

    echo json_encode([
        'success' => true,
        'liked' => $liked,
        'totalLikes' => $totalLikes
    ]);
    exit;
}

echo json_encode(['success' => false]);
?>
