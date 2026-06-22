<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET");
header("Access-Control-Allow-Headers: Content-Type");

// Vercel-ისთვის დროებით საქაღალდეში შენახვა, რათა ერორი არ დაიზავოს
$file_path = '/tmp/orders.json';

// თუ მოთხოვნა არის GET - ვაბრუნებთ ყველა შეკვეთას
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (file_exists($file_path)) {
        echo file_get_contents($file_path);
    } else {
        // თუ ფაილი არ არსებობს, 404-ის ნაცვლად ვაბრუნებთ ცარიელ მასივს
        echo json_encode([]);
    }
    exit;
}

// თუ მოთხოვნა არის POST - ვინახავთ ახალ შეკვეთას
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = file_get_contents("php://input");
    $data = json_decode($input, true);

    if (!$data || empty($data['items']) || empty($data['total'])) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "არასწორი მონაცემები"]);
        exit;
    }

    $orders = [];
    if (file_exists($file_path)) {
        $orders = json_decode(file_get_contents($file_path), true) ?? [];
    }

    $new_order = [
        "id" => "#" . strtoupper(substr(md5(uniqid()), 0, 5)),
        "date" => date("d/m/Y H:i"),
        "items" => $data['items'],
        "total" => $data['total']
    ];

    array_unshift($orders, $new_order);
    file_put_contents($file_path, json_encode($orders, JSON_PRETTY_PRINT));

    echo json_encode(["status" => "success", "order" => $new_order]);
    exit;
}