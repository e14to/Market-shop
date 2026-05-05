<?php
// 1. Supabase-ის მონაცემები (Settings > API-ში ნახავ)
$supabaseUrl = 'https://wiodymiqluwazbnexmyf.supabase.co'; // შენი URL
$supabaseKey = 'აქ_ჩაწერე_შენი_ANON_PUBLIC_KEY'; // შენი Key

// 2. ვიღებთ ID-ს ESP32-სგან
if (isset($_GET['id'])) {
    $card_id = $_GET['id'];

    // ვამზადებთ მონაცემს Supabase-სთვის (JSON ფორმატში)
    $data = json_encode([
        'card_uid' => $card_id
    ]);

    // 3. ვაგზავნით მონაცემს Supabase-ში CURL-ის საშუალებით
    $ch = curl_init($supabaseUrl . '/rest/v1/scans');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'apikey: ' . $supabaseKey,
        'Authorization: Bearer ' . $supabaseKey,
        'Content-Type: application/json',
        'Prefer: return=minimal'
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode == 201) {
        echo "წარმატებით ჩაიწერა Supabase-ში!";
    } else {
        echo "შეცდომა: " . $response;
    }
} else {
    echo "ID პარამეტრი აკლია";
}