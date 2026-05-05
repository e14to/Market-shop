<?php
$supabaseUrl = 'https://wiodymiqluwazbnexmyf.supabase.co'; // შენი პროექტის URL
$supabaseKey = 'sb_publishable_pXdp7DM6Ard-Za2-2T0pcg_zNkz8-Qr'; // სურათზე რაც ვნახეთ

if (isset($_GET['id'])) {
    $card_id = strtoupper(trim($_GET['id'])); // ყველაფერს დიდ ასოებად ვაქცევთ
    $price = 5.00;

    // 1. მომხმარებლის ძებნა
    $url = $supabaseUrl . "/rest/v1/users?card_uid=eq." . $card_id . "&select=user_name,balance";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'apikey: ' . $supabaseKey,
        'Authorization: Bearer ' . $supabaseKey
    ]);
    $response = curl_exec($ch);
    $user_data = json_decode($response, true);
    curl_close($ch);

    if (!empty($user_data)) {
        $current_balance = $user_data[0]['balance'];
        $user_name = $user_data[0]['user_name'];

        if ($current_balance >= $price) {
            $new_balance = $current_balance - $price;

            // 2. ბალანსის განახლება
            $updateUrl = $supabaseUrl . "/rest/v1/users?card_uid=eq." . $card_id;
            $ch = curl_init($updateUrl);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['balance' => $new_balance]));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'apikey: ' . $supabaseKey,
                'Authorization: Bearer ' . $supabaseKey,
                'Content-Type: application/json'
            ]);
            curl_exec($ch);
            curl_close($ch);

            // 3. ისტორიის ჩაწერა 'scans' ცხრილში (საიტისთვის)
            $historyUrl = $supabaseUrl . "/rest/v1/scans";
            $ch = curl_init($historyUrl);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['card_uid' => $card_id]));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'apikey: ' . $supabaseKey,
                'Authorization: Bearer ' . $supabaseKey,
                'Content-Type: application/json'
            ]);
            curl_exec($ch);
            curl_close($ch);

            echo "Warmatebit gadaixade! Momkhmarebeli: " . $user_name . " | Nashti: " . $new_balance . " GEL";
        } else {
            echo "Tanxa ar geyofat! Nashti: " . $current_balance . " GEL";
        }
    } else {
        echo "Barati ar aris registratoryli! (ID: " . $card_id . ")";
    }
}