<?php
/**
 * The MIT License (MIT)
 *
 * Copyright (c) 2018 Anton Samuelsson
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
?>
<?php
/**
 * Two-Factor Authentication.
 *
 * @author Anton Samuelsson <samuelsson.anton@gmail.com>
 */
abstract class Freestream_TwoFactorAuth_Model_Factory_Abstract
{
    /**
     * Get array with all 32 characters for decoding from/encoding to base32.
     *
     * @var array
     */
    protected $_lut = [
        'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H',
        'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P',
        'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X',
        'Y', 'Z', '2', '3', '4', '5', '6', '7',
    ];

    /**
     * Generate a digit secret key in base32 format.
     *
     * @return string
     */
    public function generateSecretKey()
    {
        $base   = $this->_lut;
        $secret = '';

        for ($i = 0; $i < 32; $i++) {
            $secret .= $base[array_rand($base)];
        }

        return $secret;
    }

    /**
     * Authenticate one-time password for username.
     *
     * @param  string $username
     * @param  string $otp
     *
     * @throws Mage_Core_Exception
     *
     * @return boolean
     */
    abstract public function authenticateOtp($username, $otp);

    /**
     * Verify one-time password against user secret.
     *
     * @param  string $secret
     * @param  string $code
     *
     * @return boolean
     */
    public function verifyKey($secret, $code)
    {
        $timestamp = $this->getTimestamp();

        for ($i = -1; $i <= 1; $i++) {
            if ($this->getCode($secret, $timestamp + $i) === $code) {
                return true;
            }
        }

        return false;
    }

    /**
     * Calculate the code, with given secret and point in time.
     *
     * @param  string  $secret
     * @param  integer $timestamp
     *
     * @return string
     */
    public function getCode($secret, $timestamp)
    {
        $secretkey          = $this->base32Decode($secret);
        $binaryTimestamp    = pack('N*', 0) . pack('N*', $timestamp);
        $hash               = hash_hmac ('sha1', $binaryTimestamp, $secretkey, true);
        $offset             = ord(substr($hash, -1)) & 0x0F;
        $value              = unpack('N', substr($hash, $offset, 4));
        $value              = $value[1] & 0x7FFFFFFF;
        $modulo             = pow(10, 6);

        return str_pad($value % $modulo, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Decode a base64 string.
     *
     * @param  string  $secret
     *
     * @return string
     */
    public function base32Decode($string)
    {
        $lut    = array_flip($this->_lut);
        $string = strtoupper($string);
        $length = strlen($string);
        $n      = 0;
        $j      = 0;
        $binary = "";

        for ($i = 0; $i < $length; $i++) {
            $n = $n << 5;
            $n = $n + $lut[$string[$i]];
            $j = $j + 5;

            if ($j >= 8) {
                $j = $j - 8;
                $binary .= chr(($n & (0xFF << $j)) >> $j);
            }
        }

        return $binary;
    }

    /**
     * Returns the current Unix Timestamp devided by the interval between key
     * regeneration period.
     *
     * @return integer
     **/
    public function getTimestamp()
    {
        return floor(microtime(true) / 30);
    }
}

