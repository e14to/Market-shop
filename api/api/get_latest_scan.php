<?php
header('Content-Type: application/json');
$supabaseUrl = 'შენი_URL';
$supabaseKey = 'შენი_KEY';

// ვიღებთ ბოლო ჩანაწერს scans ცხრილიდან
$ch = curl_init($supabaseUrl . "/rest/v1/scans?select=id,card_uid&order=id.desc&limit=1");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'apikey: ' . $supabaseKey,
    'Authorization: Bearer ' . $supabaseKey
]);
$scan_data = json_decode(curl_exec($ch), true);
curl_close($ch);

if (!empty($scan_data)) {
    $uid = $scan_data[0]['card_uid'];
    
    // ვიღებთ მომხმარებლის სახელს და ბალანსს
    $ch = curl_init($supabaseUrl . "/rest/v1/users?card_uid=eq." . $uid . "&select=user_name,balance");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'apikey: ' . $supabaseKey,
        'Authorization: Bearer ' . $supabaseKey
    ]);
    $user_data = json_decode(curl_exec($ch), true);
    curl_close($ch);

    echo json_encode([
        'id' => $scan_data[0]['id'],
        'user_name' => $user_data[0]['user_name'] ?? 'უცნობი',
        'balance' => $user_data[0]['balance'] ?? '0'
    ]);
}