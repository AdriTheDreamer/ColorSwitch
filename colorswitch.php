<?php

class ColorSwitch
{
    // RGB color space in 0<=X<=1
    var $sourceRGB;
    var $result;

    public function inputRGB($input)
    {
        $this->sourceRGB = array_map( function($val) { return $val / 255; }, $input);
    }

    public function inputHSL($input)
    {
        $C = (1 - abs(2 * ($input[2] / 100) - 1)) * ($input[1] / 100);
        $hue = $input[0] / 60;
        $X = $C * (1 - abs(fmod($hue, 2) - 1));

        if ($hue <= 1) {
            $this->sourceRGB = array($C, $X, 0);
        } elseif ($hue <= 2) {
            $this->sourceRGB = array($X, $C, 0);
        } elseif ($hue <= 3) {
            $this->sourceRGB = array(0, $C, $X);
        } elseif ($hue <= 4) {
            $this->sourceRGB = array(0, $X, $C);
        } elseif ($hue <= 5) {
            $this->sourceRGB = array($X, 0, $C);
        } elseif ($hue <= 6) {
            $this->sourceRGB = array($C, 0, $X);
        }

        $m = ($input[2] / 100) - ($C / 2);
        $this->sourceRGB = array_map( 
            function ($val) use ($m)
            { 
                return $val + $m; 
            },
            $this->sourceRGB
        );
    }

    public function inputHSV($input)
    {
        $C = $input[2] / 100 * $input[1] / 100;
        $hue = $input[0] / 60;
        $X = $C * (1 - abs(fmod($hue, 2) - 1));

        if ($hue <= 1) {
            $this->sourceRGB = array($C, $X, 0);
        } elseif ($hue <= 2) {
            $this->sourceRGB = array($X, $C, 0);
        } elseif ($hue <= 3) {
            $this->sourceRGB = array(0, $C, $X);
        } elseif ($hue <= 4) {
            $this->sourceRGB = array(0, $X, $C);
        } elseif ($hue <= 5) {
            $this->sourceRGB = array($X, 0, $C);
        } elseif ($hue <= 6) {
            $this->sourceRGB = array($C, 0, $X);
        }

        $m = $input[2] / 100 - $C;
        $this->sourceRGB = array_map( 
            function ($val) use ($m)
            { 
                return $val + $m; 
            },
            $this->sourceRGB
        );
    }

    public function inputCMYK($input)
    {
        $K = $input[3] / 100;

        // make CMYK into CMY of actual value (from percents)
        $CMY = $input;
        unset($CMY[3]); // remove the excess K from CMYK

        $this->sourceRGB = array_map( 
            function($val) use ($K)
            { 
                return (1 - $val / 100) * (1 - $K);
            }, 
            $CMY
        );
    }

    public function outputHSV()
    {
        $max = max($this->sourceRGB);
        $min = min($this->sourceRGB);
        $chroma = $max - $min;

        switch ($max) {
            case 0:
                $hue = 0;
                break;
            case $this->sourceRGB[0]:
                $hue = ($this->sourceRGB[1] - $this->sourceRGB[2]) / $chroma;
                break;
            case $this->sourceRGB[1]:
                $hue = ($this->sourceRGB[2] - $this->sourceRGB[0]) / $chroma + 2;
                break;
            case $this->sourceRGB[2]:
                $hue = ($this->sourceRGB[0] - $this->sourceRGB[1]) / $chroma + 4;
                break;
            default:
                $hue = 0;
        }

        $hue = fmod(($hue * 60 + 360) , 360);  // ensures it is positive
        $saturation = ($max == 0) ? 0 : ($chroma / $max * 100);
        $this->result = array($hue, $saturation, $max * 100);
    }

    public function outputHSL()
    {
        $max = max($this->sourceRGB);
        $min = min($this->sourceRGB);
        $chroma = $max - $min;

        switch ($max) {
            case 0:
                $hue = 0;
                break;
            case $this->sourceRGB[0]:
                $hue = ($this->sourceRGB[1] - $this->sourceRGB[2]) / $chroma;
                break;
            case $this->sourceRGB[1]:
                $hue = ($this->sourceRGB[2] - $this->sourceRGB[0]) / $chroma + 2;
                break;
            case $this->sourceRGB[2]:
                $hue = ($this->sourceRGB[0] - $this->sourceRGB[1]) / $chroma + 4;
                break;
            default:
                $hue = 0;
        }

        $hue = fmod(($hue * 60 + 360) , 360);  // ensures it is positive
        $lightness = ($max + $min) * 50;
        $saturation = ($chroma === 0) ? 0 : ($chroma / (1 - abs($lightness * 0.02 - 1)) * 100);
        $this->result = array($hue, $saturation, $lightness);
    }

    public function outputCMYK()
    {
        $max = max($this->sourceRGB);
        $K = 1 - $max;
        if ($K < 1) {
            $C = (1 - $this->sourceRGB[0] - $K) / (1 - $K);
            $M = (1 - $this->sourceRGB[1] - $K) / (1 - $K);
            $Y = (1 - $this->sourceRGB[2] - $K) / (1 - $K);
        } else {
            $C = $M = $Y = 0;
        }
        $this->result = array($C * 100, $M * 100, $Y * 100, $K * 100);
    }

    public function outputRGB()
    {
        $this->result = array_map( function($val) { return $val * 255; }, $this->sourceRGB);
    }

}

?>
