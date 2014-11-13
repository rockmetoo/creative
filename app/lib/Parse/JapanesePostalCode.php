<?php
/*
 * PostalCode Parser for 日本郵政
 */
namespace Parse;

use Parse\BasicInterface;
use Parse\JapanesePostalCode\Row;

class JapanesePostalCode implements BasicInterface
{
    const VERSION = '0.0.1';

    private $fh;

    public function __construct($file = null)
    {
        if (!$this->fh && isset($file)) {

        }
    }

    public function fetch_obj()
    {

    }

    public function get_line()
    {

    }

    private function __get_line()
    {

    }
}
