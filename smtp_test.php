<?php
$host = 'mail.abadgroup.tech';
$port = 587; // o 465
$user = 'prueba@kenya.com.pe';
$pass = 'nY5g5nDhoqhha3Ah';

function read_smtp($fp) {
    $res = '';
    while ($str = fgets($fp, 515)) {
        $res .= $str;
        if (substr($str, 3, 1) == ' ') break;
    }
    return $res;
}

function test_smtp($host, $port, $crypto = false) {
    echo "Testing $host:$port (Crypto: " . ($crypto ? 'yes' : 'no') . ")\n";
    $fp = fsockopen($host, $port, $errno, $errstr, 10);
    if (!$fp) { echo "Failed to connect: $errstr\n\n"; return; }
    
    echo "S: " . read_smtp($fp);
    
    fwrite($fp, "EHLO test\r\n");
    echo "C: EHLO test\n";
    echo "S: " . read_smtp($fp);
    
    if ($crypto) {
        fwrite($fp, "STARTTLS\r\n");
        echo "C: STARTTLS\n";
        echo "S: " . read_smtp($fp);
        
        stream_context_set_option($fp, 'ssl', 'allow_self_signed', true);
        stream_context_set_option($fp, 'ssl', 'verify_peer', false);
        stream_context_set_option($fp, 'ssl', 'verify_peer_name', false);
        stream_socket_enable_crypto($fp, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
        
        fwrite($fp, "EHLO test\r\n");
        echo "C: EHLO test (post-tls)\n";
        echo "S: " . read_smtp($fp);
    }

    fwrite($fp, "AUTH LOGIN\r\n");
    echo "C: AUTH LOGIN\n";
    $auth = read_smtp($fp);
    echo "S: " . $auth;
    
    if (strpos($auth, '334') === 0) {
        global $user, $pass;
        fwrite($fp, base64_encode($user) . "\r\n");
        echo "S: " . read_smtp($fp);
        fwrite($fp, base64_encode($pass) . "\r\n");
        echo "S: " . read_smtp($fp);
    }
    
    fwrite($fp, "QUIT\r\n");
    echo "S: " . read_smtp($fp);
    fclose($fp);
    echo "\n";
}

test_smtp($host, 587, true);
test_smtp('ssl://'.$host, 465, false);
