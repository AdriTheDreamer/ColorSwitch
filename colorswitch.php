<?php

class ColorSwitch
{
    var $result;
    var $red; 
    var $green;
    var $blue;

    public function inputRGB($input)
    {
        $this->red = $input[0] / 255;
        $this->green = $input[1] / 255;
        $this->blue = $input[2] / 255;
    }

    public function inputHSL($input)
    {
        $C = (1 - abs(2 * ($input[2] / 100) - 1)) * ($input[1] / 100);
        $hue = $input[0] / 60;
        $X = $C * (1 - abs(fmod($hue, 2) - 1));

        if ($hue <= 1) {
            $this->red = $C;
            $this->green = $X;
            $this->blue = 0;
        } elseif ($hue <= 2) {
            $this->red = $X;
            $this->green = $C;
            $this->blue = 0;
        } elseif ($hue <= 3) {
            $this->red = 0;
            $this->green = $C;
            $this->blue = $X;
        } elseif ($hue <= 4) {
            $this->red = 0;
            $this->green = $X;
            $this->blue = $C;
        } elseif ($hue <= 5) {
            $this->red = $X;
            $this->green = 0;
            $this->blue = $C;
        } elseif ($hue <= 6) {
            $this->red = $C;
            $this->green = 0;
            $this->blue = $X;
        }

        $m = ($input[2] / 100) - ($C / 2);
        $this->red = $this->red + $m;
        $this->green = $this->green + $m;
        $this->blue = $this->blue + $m;
    }

    public function inputHSV($input)
    {
        $C = $input[2] / 100 * $input[1] / 100;
        $hue = $input[0] / 60;
        $X = $C * (1 - abs(fmod($hue, 2) - 1));

        if ($hue <= 1) {
            $this->red = $C;
            $this->green = $X;
            $this->blue = 0;
        } elseif ($hue <= 2) {
            $this->red = $X;
            $this->green = $C;
            $this->blue = 0;
        } elseif ($hue <= 3) {
            $this->red = 0;
            $this->green = $C;
            $this->blue = $X;
        } elseif ($hue <= 4) {
            $this->red = 0;
            $this->green = $X;
            $this->blue = $C;
        } elseif ($hue <= 5) {
            $this->red = $X;
            $this->green = 0;
            $this->blue = $C;
        } elseif ($hue <= 6) {
            $this->red = $C;
            $this->green = 0;
            $this->blue = $X;
        }

        $m = $input[2] / 100 - $C;
        $this->red = $this->red + $m;
        $this->green = $this->green + $m;
        $this->blue = $this->blue + $m;
    }

    public function inputCMYK($input)
    {
        $this->red = (100 - $input[0]) * (100 - $input[3]) / 10000;
        $this->green = (100 - $input[1]) * (100 - $input[3]) / 10000;
        $this->blue = (100 - $input[2]) * (100 - $input[3]) / 10000;
    }

    public function outputHSV()
    {
        $red = $this->red;
        $green = $this->green;
        $blue = $this->blue;

        $max = max($red, $green, $blue);
        $min = min($red, $green, $blue);
        $chroma = $max - $min;

        switch ($max) {
            case 0:
                $hue = 0;
                break;
            case $red:
                $hue = ($green - $blue) / $chroma;
                break;
            case $green:
                $hue = ($blue - $red) / $chroma + 2;
                break;
            case $blue:
                $hue = ($red - $green) / $chroma + 4;
                break;
            default:
                $hue = 0;
        }

        $hue = ($hue * 60 + 360) % 360;  // ensures it is positive
        $saturation = ($max == 0) ? 0 : ($chroma / $max * 100);
        $variance = $max * 100;
        $this->result = array($hue, $saturation, $variance);
    }

    public function outputHSL()
    {
        $red = $this->red;
        $green = $this->green;
        $blue = $this->blue;

        $max = max($red, $green, $blue);
        $min = min($red, $green, $blue);
        $chroma = $max - $min;

        switch ($max) {
            case 0:
                $hue = 0;
                break;
            case $red:
                $hue = ($green - $blue) / $chroma;
                break;
            case $green:
                $hue = ($blue - $red) / $chroma + 2;
                break;
            case $blue:
                $hue = ($red - $green) / $chroma + 4;
                break;
            default:
                $hue = 0;
        }

        $hue = ($hue * 60 + 360) % 360;  // ensures it is positive
        $lightness = ($max + $min) * 50;
        $saturation = ($chroma === 0) ? 0 : ($chroma / (1 - abs($lightness * 0.02 - 1)) * 100);
        $this->result = array($hue, $saturation, $lightness);
    }

    public function outputCMYK()
    {
        $red = $this->red;
        $green = $this->green;
        $blue = $this->blue;

        $max = max($red, $green, $blue);
        $K = 1 - $max;
        if ($K < 1) {
            $C = (1 - $red - $K) / (1 - $K);
            $M = (1 - $green - $K) / (1 - $K);
            $Y = (1 - $blue - $K) / (1 - $K);
        } else {
            $C = $M = $Y = 0;
        }
        $this->result = array($C * 100, $M * 100, $Y * 100, $K * 100);
    }

    public function outputRGB()
    {
        $this->result = array($this->red * 255, $this->green * 255, $this->blue * 255);
    }

}

?>
