<?php

  class Gpio
  {

    const WIRING_PI = "/usr/local/bin/gpio -g";
    const I2C_GET   = "i2cget -y 0";
    const I2C_SET   = "i2cset -y 0";
    const INPUT     = 0;
    const OUTPUT    = 1;
    const LOW       = 0;
    const HIGH      = 1;

    protected static

      $_instance;

    public function instance()
    {
      if (!(self::$_instance instanceof self)) {
        self::$_instance = new self();
      }
      return self::$_instance;
    } // instance

    public function pinMode($pin, $mode)
    {
      if (($mode !== self::INPUT) && ($mode !== self::OUTPUT)) {
        throw new Exception(__METHOD__ . ' > Invalid mode.');
      }
      $mode = ($mode == self::INPUT) ? "in" : "out";
      $command = self::WIRING_PI . " mode " . $pin . " " . $mode;
      return shell_exec($command);
    } // pinMode

    public function digitalWrite($pin, $level)
    {
      if (($level !== self::HIGH) && ($level !== self::LOW)) {
        throw new Exception(__METHOD__ . ' > Invalid level.');
      }
      $command = self::WIRING_PI . " write " . $pin . " " . $level;
      return shell_exec($command); 
    } // digitalWrite

    public function digitalRead($pin)
    {
      $command = self::WIRING_PI . " read " . $pin;
      return shell_exec($command); 
    } // digitalRead

    public function i2cWrite($address, $register, $value)
    {
      if (stripos((string) $address, '0x') !== 0) {
        $address = "0x" . dechex($address);
      }
      if (stripos((string) $register, '0x') !== 0) {
        $register = "0x" . dechex($register);
      }
      if (stripos((string) $value, '0x') !== 0) {
        $value = "0x" . dechex($value);
      }
      $command = self::I2C_SET . $address . " " . $register . " " . $value;
      return shell_exec($command);
    } // i2cWrite

    public function i2cRead($address, $register)
    {
      if (stripos((string) $address, '0x') !== 0) {
        $address = "0x" . dechex($address);
      }
      if (stripos((string) $register, '0x') !== 0) {
        $register = "0x" . dechex($register);
      }
      $command = self::I2C_GET . $address . " " . $register;
      $result  = shell_exec($command);
      if (stripos($result, '0x') === 0) {
        $result = hexdec(preg_replace('/[^0-9A-Fa-f]/', '', $result));
      }
      return (int) result;
    } // i2cRead

    protected function __construct()
    {
    } // __construct

  } // Gpio

?>
