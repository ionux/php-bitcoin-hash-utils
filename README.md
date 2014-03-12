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


Version
--------
 - 2/12/2014, rich@bitpay.com:
    - Converted to class format, cleaned up a bit and some
    - error checking code.  Also added extension checks for
    - BC Math or GMP and uses either method for calculations.

License
-------
<p xmlns:dct="http://purl.org/dc/terms/">
<a rel="license" href="http://creativecommons.org/publicdomain/mark/1.0/">
<img src="http://i.creativecommons.org/p/mark/1.0/88x31.png"
     style="border-style: none;" alt="Public Domain Mark" />
</a>
<br />
This work, based upon code by <a href="http://pastebin.com/vmRQC7ha" rel="dct:creator"><span property="dct:title">unknown</span></a> and enhanced by <a href="https://github.com/ionux" rel="dct:publisher"><span property="dct:title">Rich Morgan</span></a>, is free of known copyright restrictions.
</p>

THE SOFTWARE IS PROVIDED 'AS IS', WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
