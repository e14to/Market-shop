<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$supabaseUrl = 'https://wiodymiqluwazbnexmyf.supabase.co';
$supabaseKey = 'sb_publishable_pXdp7DM6Ard-Za2-2T0pcg_zNkz8-Qr';

// 1. ვიღებთ ბოლო სკანირებას 'scans' ცხრილიდან
$ch = curl_init($supabaseUrl . "/rest/v1/scans?select=id,card_uid&order=id.desc&limit=1");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'apikey: ' . $supabaseKey,
    'Authorization: Bearer ' . $supabaseKey
]);
$scan_res = json_decode(curl_exec($ch), true);
curl_close($ch);

if (!empty($scan_res)) {
    $uid = $scan_res[0]['card_uid'];

    // 2. ვიღებთ ამ მომხმარებლის სახელს და ბალანსს 'users' ცხრილიდან
    $ch = curl_init($supabaseUrl . "/rest/v1/users?card_uid=eq." . $uid . "&select=user_name,balance");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'apikey: ' . $supabaseKey,
        'Authorization: Bearer ' . $supabaseKey
    ]);
    $user_res = json_decode(curl_exec($ch), true);
    curl_close($ch);

    echo json_encode([
        'id' => $scan_res[0]['id'],
        'user_name' => $user_res[0]['user_name'] ?? 'Unknown',
        'balance' => $user_res[0]['balance'] ?? '0'
    ]);
} else {
    echo json_encode(['error' => 'No scans found']);
}