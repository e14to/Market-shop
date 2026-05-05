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
    <script>
    let lastScanId = null;

    async function checkNewPayment() {
        try {
            // აქ მიუთითე შენი Vercel-ის API ლინკი, რომელიც ბოლო სკანირებას აბრუნებს
            const response = await fetch('https://market-shop-green.vercel.app/api/get_latest_scan.php');
            const data = await response.json();

            if (data && data.id !== lastScanId) {
                if (lastScanId !== null) {
                    showPaymentAlert(data.user_name, data.balance);
                }
                lastScanId = data.id;
            }
        } catch (error) {
            console.error('Error checking payment:', error);
        }
    }

    function showPaymentAlert(name, balance) {
        const alertBox = document.getElementById('payment-alert');
        alertBox.innerHTML = `✅ წარმატებით გადაიხადე!<br>მომხმარებელი: ${name}<br>ნაშთი: ${balance} GEL`;
        alertBox.style.display = 'block';
        
        setTimeout(() => {
            alertBox.style.display = 'none';
        }, 5000); // 5 წამში გაქრება
    }

    setInterval(checkNewPayment, 2000); // 2 წამში ერთხელ შემოწმება
</script>
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