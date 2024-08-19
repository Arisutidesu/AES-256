<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>AES-256</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<?php

include 'method.php';

?>
    <header>CRYPTOGRAPHY WITH THE AES METHOD</header>
    <div class="container">
        <main>
            <form method="POST">
                <label for="inputtext"></label>
                <input type="text" name="inputtext" id="inputtext" placeholder="Fill in the text" required>
                <label for="encryption"></label>
                <input type="text" name="encryption" id="encryption" placeholder="Fill in the key" required>
                <input type="submit" value="Run">
            </form>
        </main>
    </div>
    <div class="container">
        <aside class="left-sidebar">Initial Text</aside>
        <main>
            <?php 
                echo "<b>" . $inputtext . "</b>";
            ?>
        </main>
    </div>
    <div class="container">
        <aside class="left-sidebar">Key</aside>
        <main>
            <?php 
                echo "<b>" . $encryptionkey . "</b>";
            ?>
        </main>
    </div>
    <div class="container">
        <aside class="left-sidebar">Encryption Text</aside>
        <main>
            <?php 
                echo "<b>" . $ciphertext . "</b>";
            ?>
        </main>
    </div>
    <div class="container">
        <aside class="left-sidebar">Encryption Time</aside>
        <main>
            <?php
                echo "<b>" . $encryptTime . " seconds </b>";
            ?>
        </main>
    </div>
    <div class="container">
        <aside class="left-sidebar">Decryption Text</aside>
        <main>
            <?php
                $decryptedtext = decryptAES($ciphertext, $encryptionkey);
                
                if (isset($decryptedtext['error'])){
                    echo "Error: " . $decryptedtext['error'];
                } else {
                    echo "<b>" . $decryptedtext['plaintext'] . "</b>";
                }
                
            ?>
        </main>
    </div>
    <div class="container">
        <aside class="left-sidebar">Decryption Time</aside>
        <main>
            <?php
                echo "<b>" . $decryptTime . " seconds </b>";
            ?>
        </main>
    </div>
    <div class="container">
        <aside class="left-sidebar">BER</aside>
        <main>
            <?php
                $inputtext = $_POST["inputtext"];;
                $decryptedtext = "";
                
                $ber = calculateBER($inputtext, $decryptedtext);
                
                if (is_array($ber)) {
                    echo "Error: " . $ber['error'];
                } else {
                    echo "<b>" . $ber . "</b>";
                }
                ?> 
        </main>
    </div>
    <div class="container">
        <aside class="left-sidebar">CER</aside>
        <main>
            <?php
                $cer= calculateCER($inputtext, $decryptedtext);
                
                if (is_array($cer)) {
                    echo "Error: " . $cer['error'];
                } else {
                    echo "<b>" . $cer . "</b>";
                }
            ?>
        </main>
    </div>
    <div class="container">
        <aside class="left-sidebar">Entropy</aside>
        <main>
            <?php
                $inputCiphertext = $ciphertext;

                if (is_string($inputCiphertext)) {
                    $entropyResult = calculateEntropy($inputCiphertext);
                    echo "<b>" . $entropyResult . "</b>";
                } else {
                    echo "Error: Invalid input type";
                }
            ?>
        </main>
    </div>
    <div class="container">
        <aside class="left-sidebar">Avalanche Effect</aside>
        <main>
            <?php
                    echo "<b>" . $avalancheeffect . " % </b>";
            ?>
        </main>
    </div>
    <footer><p></p></footer>
    <video autoplay muted loop class="back-video">
        <source src="mmv.mp4" type="video/mp4">
    </video>
</body>
</html>
