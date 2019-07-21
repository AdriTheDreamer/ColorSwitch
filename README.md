# ColorSwitch
A strangely simple PHP tool to convert between colors.

It can be used to convert colors from and to RGB, HSV, HSL and CMYK.

## Tutorial

### Values
Not necessarily be integers. All real numbers are accepted ^((though there should have no negative values)).

#### RGB
RGB values are decimal values (0<=X<=255).
example: maroon as `128,0,0`

#### HSV, HSL
Both with hue between 0 to 360 as degrees. Saturation, variance or lightness values are percents.
example: maroon as `(HSV)0,100,25` `(HSL)0,100,50`

### CMYK
CMYK values are all percents.
ecample: maroon as `0,100,100,50`

### Functions
#### Initialization
`$newColor = new ColorSwitch;`<br>
This creates a new _ColorSwitch_ object.

#### inputRGB, inputHSV, inputHSL, inputCMYK
These are input functions that lets you to put your original value of color to the object. Only accept an **array with the order as the name of the function** with the format mentioned above.

#### outputRGB, outputHSV, outputHSL, outputCMYK
These are **process** functions that processes to convert values. It do what the name talks about.

#### Result
The result would be stored in an array `$result` inside the class. You could call by `$newColor->result` as the variable of the color values.

### Example

#### Case 1
```php
$newColor = new ColorSwitch;
$newColor->inputRGB(array(120,43,24));
$newColor->outputCMYK();
echo print_r($newColor->result, TRUE);
```
The result would be `[0,64.1667,80,52.9412]`

#### Case 2
````php
$newColor = new ColorSwitch;
$newColor->inputCMYK(array(20,24,0,12));
$newColor->outputHSL();
echo print_r($newColor->result, TRUE);
````
The result would be `[250,46.8085,77.44]`