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


License
-------
<p xmlns:dct="http://purl.org/dc/terms/">
<a rel="license" href="http://creativecommons.org/publicdomain/mark/1.0/">
<img src="http://i.creativecommons.org/p/mark/1.0/88x31.png"
     style="border-style: none;" alt="Public Domain Mark" />
</a>
<br />
This work (<span property="dct:title">class.BitcoinHashUtils.php</span>, by <a href="http://pastebin.com/vmRQC7ha" rel="dct:creator"><span property="dct:title">unknown</span></a>), identified by <a href="https://github.com/ionux" rel="dct:publisher"><span property="dct:title">Rich Morgan</span></a>, is free of known copyright restrictions.
</p>
