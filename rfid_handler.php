<?php
if (isset($_GET['id'])) {
    $uid = $_GET['id'];
    $time = date('Y-m-d H:i:s');
    
    // ვამზადებთ ტექსტს: დრო + ID
    $data = "დრო: " . $time . " | ბარათის ID: " . $uid . PHP_EOL;
    
    // ვწერთ ფაილში სახელით log.txt (FILE_APPEND ნიშნავს, რომ ძველს არ წაშლის)
    file_put_contents('log.txt', $data, FILE_APPEND);
    
    echo "ჩაიწერა!";
} else {
    echo "ID ვერ მოიძებნა";
}
?>