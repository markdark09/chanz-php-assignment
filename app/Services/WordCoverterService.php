<?php
namespace App\Services;

use NumberFormatter;

class WordCoverterService 
{
    /**
     * Convert word to number
     * money.
     * 
     * @param string $text
     */
    public function stringToNumeric(string $text)
    {
        $toTextArray = explode(' ', $text);

        $result = '';
        foreach ($toTextArray as $key => $value) {
            $words = $this->digitsDecomposition();
            $ones = $words['ones'];
            $tens = $words['tens'];
            $hundreds = $words['hundreds'];

            $firstWord = $toTextArray[$key] ?? null;
            $secondWord = $toTextArray[$key] ?? null;
            $thirdWord = $toTextArray[$key] ?? null;

            switch ($value) {
                case count($toTextArray) <= 1:
                    $output = array_search($firstWord, $ones, true);
                    $result .= $output ? $output : array_search($firstWord, $tens, true) * 10;
                    break;
                
                case count($toTextArray) <= 2:
                    $firstWord ? $result .= array_search($firstWord, $tens, true) : false; 
                    $secondWord ? $result .= array_search($secondWord, $ones, true) : false;
                    break;
                default:
                    $firstWord ? $result .= array_search($firstWord, $ones, true) . ',' : false; 
                    $secondWord ? $result .= array_search($secondWord, $tens, true) : false;
                    $thirdWord ? $result .= array_search($thirdWord, $ones, true) : false;
                    break;
            }
        }

        return $result;
    }

    /**
     * Convert number to word
     * money.
     * 
     * @param int $number
     */
    public function numericToString(int $number)
    {
        $toCurrency = number_format($number, 0, '.',','); 

        /**
         * I've used these method in order 
         * to sort(Desc) the key of the array.
         */
        $toCurrencyArray = array_reverse(explode(',', $toCurrency));
        krsort($toCurrencyArray, 1);

        $result = ''; 
        foreach($toCurrencyArray as $key => $value){
            $words = $this->digitsDecomposition();
            $ones = $words['ones'];
            $tens = $words['tens'];
            $hundreds = $words['hundreds'];
            
            $firstDigit = substr($value, 0, 1);
            $secondDigit = substr($value, 1, 1);
            $thirdDigit = substr($value, 2, 1);

            switch ($value) {
                case $value <= 19:
                    $result = $ones[$value];
                    break;

                case $value <= 99:
                    $firstDigit ? $result .= $tens[$firstDigit] : false; 
                    $secondDigit ? $result .= ' ' . $ones[$secondDigit] : false;
                    break;
                
                default:
                    $firstDigit ? $result .= $ones[$firstDigit] . ' ' . $hundreds[0] : false;
                    $secondDigit ? $result .= " " . $tens[$secondDigit] : false; 
                    $thirdDigit ? $result .=  "-" . $ones[$thirdDigit] : false;
                    break;
            }

            if ($key > 0) { 
                $result .= ' '. $hundreds[$key] . ' '; 
            }
        }

        return $result;
    }

    /**
     * Array of number words
     * 
     * Info: The index of each one 
     * of list array are related to the 
     * values itself that's why its always 
     * started at zero
     */
    public function digitsDecomposition()
    {

        /**
         * key is equavalent to values
         */
        $ones = [
            'zero',
            'one',
            'two',
            'three',
            'four',
            'five',
            'six',
            'seven',
            'eighth',
            'nine',
            'ten',
            'eleven',
            'twelve',
            'thirteen',
            'fourteen',
            'fifteen',
            'sixteen',
            'seventeen',
            'eighteen',
            'nineteen'
        ];

        /**
         * key is equavalent to values * 10
         * 
         * Example: 4(key) * 10 = 40(Forty)
         */
        $tens = [
            'zero',
            'ten',
            'twenty',
            'thirty', 
            'forty', 
            'fifty', 
            'sixty', 
            'seventy', 
            'eighty', 
            'ninety' 
        ]; 

        /**
         * key is equavalent to the value
         * that has 3 digits
         * 
         * Example: 
         * 100 = 0(key) has (3 digits) = hundred
         * 2,000 = 1(key) has (3 digits) = thousand
         * 1,000,000 = 2(key) has (3 digits) = million
         */
        $hundredsToQuadtrillion = [
            'hundred', 
            'thousand',
            'million',
            'billion',
            'trillion', 
            'quadtrillion' 
        ];

        return [
            'ones' => $ones,
            'tens' => $tens,
            'hundreds' => $hundredsToQuadtrillion
        ];
    }
}
