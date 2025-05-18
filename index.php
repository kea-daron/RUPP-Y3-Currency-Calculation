<?php
// មុខងារបំលែងលេខទៅជាពាក្យភាសាអង់គ្លេស
function numberToEnglishWords($num) {
    $ones = array("", "one", "two", "three", "four", "five", "six", "seven", "eight", "nine");
    $teens = array("ten", "eleven", "twelve", "thirteen", "fourteen", "fifteen", "sixteen", "seventeen", "eighteen", "nineteen");
    $tens = array("", "ten", "twenty", "thirty", "forty", "fifty", "sixty", "seventy", "eighty", "ninety");
    
    if ($num == 0) return "zero";
    
    $result = "";
    
    // រាប់លេខជាម៉ឺន
    if ($num >= 100000) {
        $result .= numberToEnglishWords(floor($num / 100000)) . " hundred ";
        $num %= 100000;
    }
    
    if ($num >= 1000) {
        $result .= numberToEnglishWords(floor($num / 1000)) . " thousand ";
        $num %= 1000;
    }
    
    if ($num >= 100) {
        $result .= $ones[floor($num / 100)] . " hundred ";
        $num %= 100;
    }
    
    if ($num >= 20) {
        $result .= $tens[floor($num / 10)] . " ";
        $num %= 10;
    } elseif ($num >= 10) {
        $result .= $teens[$num - 10] . " ";
        $num = 0;
    }
    
    if ($num > 0) {
        $result .= $ones[$num] . " ";
    }
    
    return trim($result) . " riel";
}

// មុខងារបំលែងលេខទៅជាពាក្យភាសាខ្មែរ
function numberToKhmerWords($num) {
    $khmer = array("", "មួយ", "ពីរ", "បី", "បួន", "ប្រាំ", "ប្រាំមួយ", "ប្រាំពីរ", "ប្រាំបី", "ប្រាំបួន");
    $tens = array("", "ដប់", "ម្ភៃ", "សាមសិប", "សែសិប", "ហាសិប", "ហុកសិប", "ចិតសិប", "ប៉ែតសិប", "កៅសិប");
    
    if ($num == 0) return "សូន្យរៀល";
    
    $result = "";
    
    // រាប់លេខជាម៉ឺន
    if ($num >= 10000) {
        $result .= $khmer[floor($num / 10000)] . "ម៉ឺន";
        $num %= 10000;
    }
    
    if ($num >= 1000) {
        $result .= $khmer[floor($num / 1000)] . "ពាន់";
        $num %= 1000;
    }
    
    if ($num >= 100) {
        $result .= $khmer[floor($num / 100)] . "រយ";
        $num %= 100;
    }
    
    if ($num >= 10) {
        $result .= $tens[floor($num / 10)];
        $num %= 10;
    }
    
    if ($num > 0) {
        $result .= $khmer[$num];
    }
    
    return $result . "រៀល";
}

// រក្សាទុកទិន្នន័យក្នុងឯកសារ
function saveToFile($data) {
    $file = 'transactions.txt';
    file_put_contents($file, $data.PHP_EOL, FILE_APPEND);
}

// ដំណើរការទិន្នន័យ
$error = '';
$result = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $riel = $_POST['riel'] ?? '';
    
    if (!is_numeric($riel)) {
        $error = 'សូមបញ្ចូលលេខដែលត្រឹមត្រូវ!';
    } else {
        $riel = (float)$riel;
        $usd = number_format($riel / 4000, 2);
        $english = numberToEnglishWords($riel);
        $khmer = numberToKhmerWords($riel);
        
        // រក្សាទុកទិន្នន័យ
        $log = date('Y-m-d H:i:s')." | Riel: $riel | USD: $usd";
        saveToFile($log);
        
        $result = "
        <div class='result'>
            <p>a. $english</p>
            <p>b. $khmer</p>
            <p>c. USD: $usd</p>
        </div>";
    }
}
?>


<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>គណនារូបិយប័ណ្ណ</title>
    <style>
        body {
            font-family: 'Khmer OS System', Arial, sans-serif;
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        input[type="text"], button {
            padding: 10px;
            margin: 5px 0;
            width: 100%;
            box-sizing: border-box;
        }
        .error {
            color: red;
            margin: 10px 0;
        }
        .result {
            margin-top: 20px;
            padding: 15px;
            background: #e8f5e9;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>កម្មវិធីបម្លែងរូបិយប័ណ្ណ</h2>
        <form method="POST">
            <input type="text" name="riel" placeholder="សូមបញ្ចូលចំនួនរៀល..." required>
            <button type="submit">គណនា</button>
        </form>
        
        <?php if($error): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>
        
        <?php if($result): ?>
            <h3>លទ្ធផល៖</h3>
            <?= $result ?>
        <?php endif; ?>
    </div>
</body>
</html>
