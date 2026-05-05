<!DOCTYPE html>
<html lang="ka">
<head>
    <meta charset="UTF-8">
    <title>გადახდების სისტემა</title>
    <style>
        body { font-family: sans-serif; text-align: center; background: #f4f4f9; padding-top: 50px; }
        .card { background: white; padding: 20px; border-radius: 10px; display: inline-block; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        .status { font-size: 24px; color: #2ecc71; margin-top: 10px; }
    </style>
    <!-- გვერდი ავტომატურად განახლდება ყოველ 3 წამში -->
    <meta http-equiv="refresh" content="3">
</head>
<body>
    <div class="card">
        <h1>ბოლო ტრანზაქცია</h1>
        <div id="display">
            <?php
            // აქ შეგიძლია წამოიღო ბოლო ჩანაწერი 'scans' ცხრილიდან, 
            // რომ დაინახო ვინ დაასკანერა ბოლოს.
            echo "მიადეთ ბარათი გადასახდელად...";
            ?>
        </div>
    </div>
</body>
</html>