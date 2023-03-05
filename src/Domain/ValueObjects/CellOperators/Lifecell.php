<?php

namespace Rater\Domain\ValueObjects\CellOperators;

class Lifecell
{
    const ID = 3;
    const LABEL = 'Lifecell';
    const CODES =  ['63', '73'];
    const CREDIT_RATE = 0.94;

    public function getRate(): float
    {
        return self::CREDIT_RATE;
    }
}


/*
+380 32 2xx-xx-xx  (international call to 6-digit numbers in Lviv)
+380 50 xxx-xx-xx  (international call to Vodafone)
+380 66 xxx-xx-xx  (international call to Vodafone)
+380 95 xxx-xx-xx  (international call to Vodafone)
+380 99 xxx-xx-xx  (international call to Vodafone)
+380 63 xxx-xx-xx  (international call to lifecell)
+380 73 xxx-xx-xx  (international call to lifecell)
+380 93 xxx-xx-xx  (international call to lifecell)
+380 67 xxx-xx-xx  (international call to Kyivstar)
+380 68 xxx-xx-xx  (international call to Kyivstar)
+380 96 xxx-xx-xx  (international call to Kyivstar)
+380 97 xxx-xx-xx  (international call to Kyivstar)
+380 98 xxx-xx-xx  (international call to Kyivstar)

*/