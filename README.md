php-bitcoin-hash-utils
======================

A PHP class consisting of Bitcoin-related hash &amp; encoding functions.

Functions include:<br />
 - BASE-58 encoding and decoding
 - Standard hex encoding and decoding
 - Converting a RIPEMD-160 hash to address and address to hash
 - Converting a public key to an address
 
Usage
-----
The class consists of static functions so creating a new object from this class is not needed.  Simply include() the class file in the head of your PHP script and call the needed function.
 
For example:
<pre>
include_once('class.BitcoinHashUtils.php');
$WIF_address = BitcoinHashUtils::encodeBase58($hex_string);
</pre>
