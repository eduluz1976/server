<?php
namespace eduluz1976\server;


use eduluz1976\action\Action;
use eduluz1976\server\exception\RangeException;

class RangeTest extends \PHPUnit\Framework\TestCase
{


    public function testSimpleRange() {

        $range = new Range("0.0.0.0:1234");

        $this->assertEquals(1,$range->getNumberPossibilities());

    }


    public function testComplexRange() {


        $range1 = new Range("0.0.0.0:1234-1240;127.0.0.1:2300,2305-2310");

       // print_r($range1->getBlocks(true));

        $this->assertEquals(14,$range1->getNumberPossibilities());

        $range2 = new Range("0.0.0.0:1-10,5");

        //print_r($range2->getBlocks(true));

        $this->assertEquals(10,$range2->getNumberPossibilities());

    }



    public function testInconsistentRange() {

        $invalidRanges = [
            RangeException::EXCEPTION_EMPTY_RANGE => '', // empty range
            RangeException::EXCEPTION_ADDR_IS_MISSING => ':300', // without addr
            RangeException::EXCEPTION_PORT_IS_MISSING => '127.0.0.1', // without port
            RangeException::EXCEPTION_INVALID_PORT_CHARACTER =>  '0.0.0.0:aaa', // invalid characteres in port
            RangeException::EXCEPTION_INVALID_PORT_INTERVAL => '0.0.0.0:250-' // invalid interval
        ];

        foreach ($invalidRanges as $code => $strRange) {

            try {
                $range = new Range($strRange);
            } catch (RangeException $e) {
                if ($code != $e->getCode()) {
                    echo $e->getTraceAsString();
                }
                $this->assertEquals($code, $e->getCode());
            }

        }



    }


    public function testRebuildStringRepresentation() {

        $range = new Range("0.0.0.0:1234-1240;127.0.0.1:2300,2305-2310,2307,2320-2330");

        $this->assertEquals(25, $range->calculatePossibilities(true));

        $s = (string) $range;

        $this->assertTrue((strlen($s)>0));

        $range2 = new Range($s);
        $this->assertEquals(25, $range2->calculatePossibilities(true));

        $s2 = (string) $range2;

        $this->assertEquals($s, $s2);


    }




}
