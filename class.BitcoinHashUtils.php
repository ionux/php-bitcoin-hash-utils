<?php

/**
 *  Bitcoin-related hash functions in a class format.
 *  Original code found at http://pastebin.com/vmRQC7ha
 *
 *  Versions:
 *    2/12/2014, rich@bitpay.com:
 *      Converted to class format, cleaned up a bit and some
 *      error checking code.  Also added extension checks for
 *      BC Math or GMP and uses either method for calculations.
 *
 *  This code is considered public-domain. Original author
 *  did not place any copyright on his/her work and did not
 *  include any attribution or contact information. All
 *  modifications are also placed in the public domain.
 *
 *  THE SOFTWARE IS PROVIDED 'AS IS', WITHOUT WARRANTY OF
 *  ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED
 *  TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A
 *  PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL
 *  THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM,
 *  DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT,
 *  TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 *  WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 */


if (function_exists('bcadd'))
  define('MATH_TYPE', 'BC');
elseif (function_exists('gmp_add'))
  define('MATH_TYPE', 'GMP');
else
  die('Fatal error: This class requires either the BC Math or GMP math extensions for PHP to be installed.');

define('ADDRESSVERSION', '00');   // This is a hex byte

public final class BitcoinHashUtils {

  /**
  * Takes a base-16 (hexadecimal) string and decodes to base-10 (decimal).
  *
  * @param string $hex
  * @return string $return
  * @throws Exception $e
  *
  **/
  public static function decodeHex($hex) {
    try {
      // Hex input must be in uppercase, with no leading 0x
      $hex=strtoupper(remove0x($hex));
      $chars='0123456789ABCDEF';
      $return='0';

      for($i=0;$i<strlen($hex);$i++) {
        $current=(string)strpos($chars,$hex[$i]);
        switch MATH_TYPE {
          case 'BC':
            $return=(string)bcmul($return,'16',0);
            $return=(string)bcadd($return,$current,0);
            break;
          case 'GMP':
            $return=gmp_strval(gmp_mul($return,'16'));
            $return=gmp_strval(gmp_add($return,$current));
            break;
         }
      }

      return $return;
    } catch (Exception $e) {
      return 'Error: ' . $e->getMessage();
    }
  }

  /**
  * Takes a base-10 (decimal) string and encodes it to base-16 (hexadecimal).
  *
  * @param string $dec
  * @return string $return
  * @throws Exception $e
  *
  **/
  function encodeHex($dec) {
    try {
      $chars='0123456789ABCDEF';
      $return='';
      switch MATH_TYPE {
        case 'BC':
          while (bccomp($dec,0)==1) {
            $dv=(string)bcdiv($dec,'16',0);
            $rem=(integer)bcmod($dec,'16');
            $dec=$dv;
            $return=$return.$chars[$rem];
          }
          break;
        case 'GMP':
          while (gmp_cmp($dec,0)==1) {
            $dv=gmp_strval(gmp_div_q($dec,'16'));
            $rem=(integer)gmp_strval(gmp_div_r($dec,'16'));
            $dec=$dv;
            $return=$return.$chars[$rem];
          }
          break;
      }
      return strrev($return);
    } catch (Exception $e) {
      return 'Error: ' . $e->getMessage();
    }
  }

  /**
  * Takes a WIF (base-58) string and decodes to base-16 (hexadecimal).
  * Wallet Input Format is a special encoding format used by Bitcoin
  * to encode a ECDSA private key. More steps are needed to take a 
  * base-58 encoded string to the pure private key, however. For more
  * information on this process, see:
  * https://en.bitcoin.it/wiki/Wallet_import_format
  *
  * @param string $base58
  * @return string $return
  * @throws Exception $e
  *
  **/
  function decodeBase58($base58) {
    try {
      $origbase58=$base58;

      $chars='123456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz';
      $return='0';

      for($i=0;$i<strlen($base58);$i++) {
        $current=(string)strpos($chars,$base58[$i]);
        switch MATH_TYPE {
          case 'BC':
            $return=(string)bcmul($return,'58',0);
            $return=(string)bcadd($return,$current,0);
            break;
          case 'GMP':
            $return=gmp_strval(gmp_mul($return,'58'));
            $return=gmp_strval(gmp_add($return,$current));
            break;
         }
      }

      $return=encodeHex($return);

      // Leading zeros
      for($i=0;$i<strlen($origbase58)&&$origbase58[$i]=='1';$i++)
        $return='00'.$return;

      if(strlen($return)%2!=0)
        $return='0'.$return;

      return $return;
    } catch (Exception $e) {
      return 'Error: ' . $e->getMessage();
    }
  }

  /**
  * Takes a base-58 (WIF) string and decodes it to base-16 (hexadecimal).
  * Wallet Input Format is a special encoding format used by Bitcoin
  * to encode a ECDSA private key. More steps are needed to take a 
  * base-58 encoded string to the pure private key, however. For more
  * information on this process, see:
  * https://en.bitcoin.it/wiki/Wallet_import_format
  *
  * @param string $hex
  * @return string $return
  * @throws Exception $e
  *
  **/
  function encodeBase58($hex) {
    try {
      if(strlen($hex)%2!=0) {
        $return = 'Error: encodeBase58 -> uneven number of hex characters');
      } else {
        $orighex=$hex;
        $chars='123456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz';
        $hex=decodeHex($hex);
        $return='';

        switch MATH_TYPE {
          case 'BC':
            while (bccomp($hex,0)==1) {
              $dv=(string)bcdiv($hex,'58',0);
              $rem=(integer)bcmod($hex,'58');
              $hex=$dv;
              $return=$return.$chars[$rem];
            }
            break;
          case 'GMP':
            while (gmp_cmp($hex,0)==1) {
              $dv=gmp_strval(gmp_div_q($hex,'58'));
              $rem=(integer)gmp_strval(gmp_div_r($hex,'58'));
              $hex=$dv;
              $return=$return.$chars[$rem];
            }
            break;
        }

        $return=strrev($return);
  
        // Leading zeros
        for($i=0;$i<strlen($orighex)&&substr($orighex,$i,2)=='00';$i+=2)
          $return='1'.$return;
      }
  
      return $return;
    } catch (Exception $e) {
      return 'Error: ' . $e->getMessage();
    }
  }

  /**
  * Takes a RIPEMD-160 hash string and decodes to base-58.
  *
  * @param string $hash160
  * @return string $return
  * @throws Exception $e
  *
  **/
  function hash160ToAddress($hash160,$addressversion=ADDRESSVERSION) {
    try {
      $hash160=$addressversion.$hash160;
      $check=pack('H*' , $hash160);
      $check=hash('sha256',hash('sha256',$check,true));
      $check=substr($check,0,8);
      $hash160=strtoupper($hash160.$check);
      return encodeBase58($hash160);
    } catch (Exception $e) {
      return 'Error: ' . $e->getMessage();
    }
}

  /**
  * Takes a base-58 string and encodes to RIPEMD-160 hash.
  *
  * @param string $addr
  * @return string $addr
  * @throws Exception $e
  *
  **/
  function addressToHash160($addr) {
    try {
      $addr=decodeBase58($addr);
      $addr=substr($addr,2,strlen($addr)-10);
      return $addr;
    } catch (Exception $e) {
      return 'Error: ' . $e->getMessage();
    }
  }

  /**
  * Checks the format validity of an address string.
  *
  * @param string $addr
  * @return bool $check
  * @throws Exception $e
  *
  **/
  function checkAddress($addr,$addressversion=ADDRESSVERSION) {
    try {
      $addr=decodeBase58($addr);

      if(strlen($addr)!=50)
        return false;

      $version=substr($addr,0,2);

      if(hexdec($version)>hexdec($addressversion))
        return false;

      $check=substr($addr,0,strlen($addr)-8);
      $check=pack('H*' , $check);
      $check=strtoupper(hash('sha256',hash('sha256',$check,true)));
      $check=substr($check,0,8);

      return $check==substr($addr,strlen($addr)-8);
    } catch (Exception $e) {
      return 'Error: ' . $e->getMessage();
    }
  }

  /**
  * Returns a RIPEMD-160 hash of an input string.
  *
  * @param string $data
  * @return string $return
  * @throws Exception $e
  *
  **/
  function hash160($data) {
    try {
      $data=pack('H*' , $data);
      return strtoupper(hash('ripemd160',hash('sha256',$data,true)));
    } catch (Exception $e) {
      return 'Error: ' . $e->getMessage();
    }
  }

  /**
  * Returns an address from a RIPEMD-160 hash.
  *
  * @param string $pubkey
  * @return string $return
  * @throws Exception $e
  *
  **/
  function pubKeyToAddress($pubkey) {
    try {
      return hash160ToAddress(hash160($pubkey));
    } catch (Exception $e) {
      return 'Error: ' . $e->getMessage();
    }
  }

  /**
  * Removes a leading '0x' from a string.
  *
  * @param string $string
  * @return string $return
  * @throws Exception $e
  *
  **/
  function remove0x($string) {
    if(strtolower(substr($string,0,2))=='0x')
      $string=substr($string,2);

    return $string;
  }

  /* end BitcoinHashUtils class */
}
