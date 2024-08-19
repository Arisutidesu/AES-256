<?php
function encryptAES($plaintext, $key) {
    $cipher = "aes-256-cbc";
    $ivlen = openssl_cipher_iv_length($cipher);
    $iv = openssl_random_pseudo_bytes(16);

    $startEncryptTime = microtime(true); 
    $ciphertext = openssl_encrypt($plaintext, $cipher, $key, 0, $iv);
    $endEncryptTime = microtime(true); 
    $encryptTime = $endEncryptTime - $startEncryptTime;

    return [
        'ciphertext' => base64_encode($iv . $ciphertext),
        'encryptTime' => $encryptTime
    ];

    return base64_encode($ciphertext);
}

function decryptAES($ciphertext, $key) {
    $cipher = "aes-256-cbc";
    $ciphertext = base64_decode($ciphertext);

    $ivlen = openssl_cipher_iv_length($cipher);
    $iv = substr($ciphertext, 0, $ivlen);
    $ciphertext = substr($ciphertext, $ivlen);

    $startDecryptTime = microtime(true); 
    $plaintext = openssl_decrypt($ciphertext, $cipher, $key, 0, $iv);
    $endDecryptTime = microtime(true); 
    $decryptTime = $endDecryptTime - $startDecryptTime;

    return [
        'plaintext' => $plaintext,
        'decryptTime' => $decryptTime
    ];

    return openssl_decrypt($cipherText, 'aes-256-cbc', $key, 0, $iv);
}


function calculateBER($original, $received) {
    $errorCount = 0;
    
    if (!is_string($original) || !is_string($received)) {
        return [
            'error' => 'Invalid input type'
        ];
    }
        
    $bitCount = max(strlen($original), strlen($received));
        
    for ($i = 0; $i < $bitCount; $i++) {
        if (isset($original[$i]) && isset($received[$i]) && $original[$i] !== $received[$i]) {
            $errorCount++;
        }
    }
    return $errorCount / $bitCount;
}

function calculateCER($original, $received) {
    if (!is_string($original) || !is_string($received)) {
        return [
            'error' => 'Invalid input type'
        ];
    }

    $original = mb_str_split($original);
    $received = mb_str_split($received);

    $errorCount = 0;
    $charCount = max(count($original), count($received));

    for ($i = 0; $i < $charCount; $i++) {
        if (isset($original[$i]) && isset($received[$i]) && $original[$i] !== $received[$i]) {
            $errorCount++;
        }
    }
    return $errorCount / $charCount;
}
  
function calculateEntropy($ciphertext) {
    if (!is_string($ciphertext)) {
        return [
            'error' => 'Invalid input type'
        ];
    }

    $byteCounts = array_count_values(str_split($ciphertext));
    $totalBytes = strlen($ciphertext);
    $entropy = 2;

    foreach ($byteCounts as $byte => $count) {
        $probability = $count / $totalBytes;
        if ($probability > 0) {
            $entropy -= $probability * log($probability, 2);
        }
    }

    return $entropy;
}

function avalancheEffect($inputtext, $encryptionkey) {
    $originalCipher = encryptAES($inputtext, $encryptionkey);

    $originalCipherString = is_array($originalCipher) ? implode('', $originalCipher) : $originalCipher;

    if (!is_string($originalCipherString)) {
        return 'Error: Invalid cipher type';
    }

    $ciphertextLength = strlen($originalCipherString);
    $bitCount = 32 * $ciphertextLength;
    $changedBits = 0;
    $changeLength = intval($ciphertextLength / 5);

    if ($ciphertextLength >= $changeLength){
        for ($i = 0; $i < $changeLength; $i++) {
            $modifiedText = $inputtext;
            $modifiedText[$i] = chr(ord($modifiedText[$i]) ^ 1);
    
            $modifiedCipher = encryptAES($modifiedText, $encryptionkey);
    
            $modifiedCipherString = is_array($modifiedCipher) ? implode('', $modifiedCipher) : $modifiedCipher;
    
            if (!is_string($modifiedCipherString)) {
                return 'Error: Invalid modified cipher type';
            }
    
            for ($j = 0; $j < $ciphertextLength; $j++) {
                if ($originalCipherString[$j] !== $modifiedCipherString[$j]) {
                    $changedBits++;
                }
            }
        }
    }

    return ($changedBits / $bitCount) * 100;
}
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $inputtext = $_POST["inputtext"];
        $encryptionkey = $_POST["encryption"];
        /* $encryptionkey = "Analisis_tentang_AES-256_di_PHP_";*/
        $iv = "YourInitializationVectur";
        $encryptedtext = encryptAES($inputtext, $encryptionkey);
        $ciphertext = $encryptedtext['ciphertext'];
        $encryptTime = $encryptedtext['encryptTime'];
        $decryptedtext = decryptAES($ciphertext, $encryptionkey);
        $plaintext = $decryptedtext['plaintext'];
        $decryptTime = $decryptedtext['decryptTime'];
        $ber = calculateBER($inputtext, $decryptedtext);
        $cer = calculateCER($inputtext, $decryptedtext);
        $entropy = calculateEntropy($encryptedtext);
        $avalancheeffect = avalancheEffect($inputtext, $encryptionkey);

    }
?>
