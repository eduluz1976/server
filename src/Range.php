<?php

namespace eduluz1976\server;

use eduluz1976\server\exception\RangeException;

class Range
{
    protected $value;
    protected $numberPossibilities = 0;
    protected $blocks = [];

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     * @return Range
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return int
     */
    public function getNumberPossibilities(): int
    {
        return $this->numberPossibilities;
    }

    /**
     * @param int $numberPossibilities
     * @return Range
     */
    public function setNumberPossibilities(int $numberPossibilities): Range
    {
        $this->numberPossibilities = $numberPossibilities;
        return $this;
    }

    /**
     * @param bool $expand
     * @return array
     */
    public function getBlocks($expand = false)
    {
        $blocks = $this->blocks;
        $response = [];

        if ($expand) {
            $ks = array_keys($blocks);

            for ($i = 0; $i < count($blocks); $i++) {
                $k = $ks[$i];
                $blk = $blocks[$k];

                if (!isset($response[$k])) {
                    $response[$k] = [];
                }

                $list = [];
                for ($j = 0; $j < count($blk); $j++) {
                    if (strpos($blk[$j], '-') !== false) {
                        $items = explode('-', $blk[$j]);

                        if ((count($items) != 2) || (empty($items[0]) || empty($items[1]))) {
                            throw new RangeException('Invalid ports interval ' . $blk[$j], RangeException::EXCEPTION_INVALID_PORT_INTERVAL);
                        } elseif (!is_numeric($items[0]) || !is_numeric($items[1])) {
                            throw new RangeException('Invalid characters on ports interval ' . $blk[$j], RangeException::EXCEPTION_INVALID_PORT_CHARACTER);
                        }

                        $min = min($items[0], $items[1]);
                        $max = max($items[0], $items[1]);

                        if (empty($min) || empty($max)) {
                            throw new RangeException('Invalid ports interval ' . $blk[$j], RangeException::EXCEPTION_INVALID_PORT_INTERVAL);
                        }

                        $length = ($max - $min) + 1;

                        for ($l = $min;$l <= $max;$l++) {
                            $list[$l] = $l;
                        }
                    } else {
                        $l = $blk[$j];

                        if (!is_numeric($l)) {
                            throw new RangeException('Invalid characters on ports interval ' . $l, RangeException::EXCEPTION_INVALID_PORT_CHARACTER);
                        }

                        $list[$l] = $l;
                    }
                    $response[$k] = $list;
                }
                sort($response[$k]);
            }
        } else {
            $response = $blocks;
        }

        // if expand...
        return $response;
    }

    public function __construct($value = false)
    {
        if ($value) {
            $this->setValue($value);
        }
        $this->evaluate();
    }

    /**
     *
     */
    protected function evaluate()
    {
        $list = [];
        $blocks = $this->breakValue();
        foreach ($blocks as $block) {
            $items = [];

            if (empty($block)) {
                throw new RangeException('Empty range ', RangeException::EXCEPTION_EMPTY_RANGE);
            } elseif (strpos($block, ':') === false) {
                throw new RangeException("Port is missing on addr $block ", RangeException::EXCEPTION_PORT_IS_MISSING);
            }

            list($addr, $ports) = explode(':', $block);

            if (empty($addr)) {
                throw new RangeException("Address is missing on addr $block ", RangeException::EXCEPTION_ADDR_IS_MISSING);
            }

            if (!isset($list[$addr])) {
                $list[$addr] = [];
            }

            if (strpos($ports, ',')) {
                $items = explode(',', $ports);
            } else {
                $items = [$ports];
            }

            $list[$addr] = $items;
        }
        $this->blocks = $list;
        $this->setNumberPossibilities($this->calculatePossibilities());
    }

    /**
     * @return int
     */
    public function calculatePossibilities($expand = true)
    {
        $count = 0;

        $blocks = $this->getBlocks($expand);

        if (!is_array($blocks) || count($blocks) == 0) {
            return 0;
        }

        $ks = array_keys($blocks);

        for ($i = 0; $i < count($blocks); $i++) {
            $k = $ks[$i];
            $blk = $blocks[$k];
            for ($j = 0; $j < count($blk); $j++) {
                $count++;
            }
        }

        return $count;
    }

    /**
     * @return array
     */
    protected function breakValue()
    {
        if (strpos($this->getValue(), ';') !== false) {
            $lsBlocks = explode(';', $this->getValue());
        } else {
            $lsBlocks = [$this->getValue()];
        }
        return $lsBlocks;
    }

    public function __toString()
    {
        $response = '';
        $blocks = $this->getBlocks(true);

        foreach ($blocks as $addr => $ports) {
            if (!empty($response)) {
                $response .= ';';
            }
            $response .= $addr . ':';
            $first = true;
            $countBlocks = 0;
            for ($i = 0;$i < count($ports);$i++) {
                $currentPort = $ports[$i];
                if ($first) {
                    $first = false;
                    $initialPort = $currentPort;
                    $previousPort = $currentPort;
                } elseif ($currentPort == ($previousPort + 1)) {
                    $previousPort = $currentPort;
                } else {
                    if ($countBlocks > 0) {
                        $response .= ',';
                    }

                    if ($initialPort == $previousPort) {
                        $response .= $initialPort;
                    } else {
                        $response .= $initialPort . '-' . $previousPort;
                    }

                    $initialPort = $currentPort;
                    $previousPort = $currentPort;
                    $countBlocks++;
                }
            }

            if ($countBlocks > 0) {
                $response .= ',';
            }

            if ($initialPort == $previousPort) {
                $response .= $currentPort;
            } else {
                $response .= $initialPort . '-' . $currentPort;
            }
        }

        return $response;
    }
}
