<?php
# Requires libsodium extension, included by default in PHP 7.2+ 

# *** Functions
# Write to file, with optional base64 encoding
function write_file($data, $filename, $base64 = false) {
  $data = $base64? base64_encode($data): $data;
  $file = fopen($filename, "w");
  fwrite($file, $data);
  fclose($file);
}
# Read from file, with option base64 encoding
function read_file($filename, $base64 = false) {
  $file = fopen($filename, "r");
  $data = fread($file, filesize($filename));
  fclose($file);  
  $data = $base64? base64_decode($data): $data;
  return ($data);
}
if (array_key_exists("action", $_GET)) {
  switch ($_GET["action"]) {
    case "g":
      # Generate keypair
      $keypair = sodium_crypto_box_keypair();
      # Save to files 
      write_file(sodium_crypto_box_secretkey($keypair), "secret_key_base64.txt", true);
      write_file(sodium_crypto_box_publickey($keypair), "public_key_base64.txt", true);
      echo "Saved public to key to public_key_base64.txt and secret key to secret_key_base64.txt<br />";
      break;
    case "e":
      # Read decrypted & public key
      $decrypted = read_file("decrypted.txt", false);
      $public_key = read_file("public_key_base64.txt", true);
      # Encrypt
      $encrypted = sodium_crypto_box_seal($decrypted, $public_key);
      # Save encryted
      write_file($encrypted, "encrypted.txt", true);
      echo "Encrypted decrypted.txt using public key public_key_base64.txt and saved output to encrypted.txt<br />";
      break;
    case "d":
      # Read encrypted & secret key
      $encrypted = read_file("encrypted.txt", true);
      $secret_key = read_file("secret_key_base64.txt", true);
      $public_key = sodium_crypto_box_publickey_from_secretkey($secret_key);
      $keypair = sodium_crypto_box_keypair_from_secretkey_and_publickey($secret_key, $public_key);
      # Decrypt
      $decrypted = sodium_crypto_box_seal_open($encrypted, $keypair);
      echo "Decrypted encrypted.txt using secret key secret_key_base64.txt. The output is: " . $decrypted . "<br />";
      break;
  }
}
?>
Please pick an action...
<li><a href="?action=g">Generate a secret and public key</a>
<li><a href="?action=e">Encrypt a file with a public key</a>
<li><a href="?action=d">Decrypt a file with a secret key</a>