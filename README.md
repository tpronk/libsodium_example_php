# Example of libsodium in R 
The scripts in this repo show how to:
* generate a public and secret key
* encrypt a sealed box via a public key
* decrypt a sealed box via a secret key

For portability, all keys and encrypted data are stored in base64.

# Required extensions
* [Sodium](http://php.net/manual/en/book.sodium.php) (inluded in PHP7.2+ by default)
