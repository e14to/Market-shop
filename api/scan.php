<?php
$supabaseUrl = 'შენი_URL';
$supabaseKey = 'შენი_KEY';

if (isset($_GET['id'])) {
    $card_id = $_GET['id'];
    $price = 5.00; // გადასახდელი თანხა

    // 1. ვამოწმებთ მომხმარებლის ბალანსს
    $ch = curl_init($supabaseUrl . "/rest/v1/users?card_uid=eq." . $card_id . "&select=balance,user_name");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'apikey: ' . $supabaseKey,
        'Authorization: Bearer ' . $supabaseKey
    ]);
    $user_data = json_decode(curl_exec($ch), true);
    curl_close($ch);

    if (!empty($user_data)) {
        $current_balance = $user_data[0]['balance'];
        $user_name = $user_data[0]['user_name'];

        if ($current_balance >= $price) {
            $new_balance = $current_balance - $price;

            // 2. ვაახლებთ ბალანსს (Update)
            $ch = curl_init($supabaseUrl . "/rest/v1/users?card_uid=eq." . $card_id);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['balance' => $new_balance]));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'apikey: ' . $supabaseKey,
                'Authorization: Bearer ' . $supabaseKey,
                'Content-Type: application/json'
            ]);
            curl_exec($ch);
            curl_close($ch);

            echo "Warmatebit gadaixade! NaSTi: " . $new_balance . " GEL";
        } else {
            echo "Tanxa ar geyofat!";
        }
    } else {
        echo "Barati ar aris registratoryli!";
    }
}