<?php
$supabaseUrl = 'https://wiodymiqluwazbnexmyf.supabase.co';
$supabaseKey = 'sb_publishable_pXdp7DM6Ard-Za2-2T0pcg_zNkz8-Qr'; // გამოიყენე შენი გასაღები

if (isset($_GET['id'])) {
    $card_id = strtoupper(trim($_GET['id']));
    $price = 5.00; // ერთი სკანირების ფასი

    // 1. მომხმარებლის შემოწმება
    $ch = curl_init($supabaseUrl . "/rest/v1/users?card_uid=eq." . $card_id . "&select=user_name,balance");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['apikey: '.$supabaseKey, 'Authorization: Bearer '.$supabaseKey]);
    $user_data = json_decode(curl_exec($ch), true);
    curl_close($ch);

    if (!empty($user_data)) {
        $balance = $user_data[0]['balance'];
        if ($balance >= $price) {
            $new_balance = $balance - $price;

            // 2. ბალანსის განახლება
            $ch = curl_init($supabaseUrl . "/rest/v1/users?card_uid=eq." . $card_id);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['balance' => $new_balance]));
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['apikey: '.$supabaseKey, 'Authorization: Bearer '.$supabaseKey, 'Content-Type: application/json']);
            curl_exec($ch);
            curl_close($ch);

            // 3. სკანირების ისტორიაში ჩაწერა (რომ საიტმა დაინახოს)
            $ch = curl_init($supabaseUrl . "/rest/v1/scans");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['card_uid' => $card_id]));
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['apikey: '.$supabaseKey, 'Authorization: Bearer '.$supabaseKey, 'Content-Type: application/json']);
            curl_exec($ch);
            curl_close($ch);

            echo "Warmatebit gadaixade! Nashti: $new_balance GEL";
        } else {
            echo "Tanxa ar geyofat!";
        }
    } else {
        echo "Barati ar aris registratoryli!";
    }
}