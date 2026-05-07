<?php
header('Content-Type: application/json');
$supabaseUrl = 'https://wiodymiqluwazbnexmyf.supabase.co';
$supabaseKey = 'sb_publishable_pXdp7DM6Ard-Za2-2T0pcg_zNkz8-Qr';

// ვიღებთ ბოლო სკანირებას
$ch = curl_init($supabaseUrl . "/rest/v1/scans?select=id,card_uid&order=id.desc&limit=1");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['apikey: '.$supabaseKey, 'Authorization: Bearer '.$supabaseKey]);
$scan = json_decode(curl_exec($ch), true);
curl_close($ch);

if (!empty($scan)) {
    $uid = $scan[0]['card_uid'];
    // ვიღებთ მომხმარებლის სახელს
    $ch = curl_init($supabaseUrl . "/rest/v1/users?card_uid=eq." . $uid . "&select=user_name,balance");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['apikey: '.$supabaseKey, 'Authorization: Bearer '.$supabaseKey]);
    $user = json_decode(curl_exec($ch), true);
    curl_close($ch);

    echo json_encode([
        'id' => $scan[0]['id'],
        'user_name' => $user[0]['user_name'] ?? 'Unknown',
        'balance' => $user[0]['balance'] ?? '0'
    ]);
}