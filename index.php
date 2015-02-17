<?php

function bcround($val, $precision = 0, $mode = PHP_ROUND_HALF_UP) {
    $val = (string) $val;
    $val0 = bcadd($val, 0, $precision);
    if (!in_array($mode, array(PHP_ROUND_HALF_UP, PHP_ROUND_HALF_DOWN))) {
        return $val0;
    }
    
    $maxPrecision = strlen(strrchr($val, '.'));
    $maxPrecision = $maxPrecision > 1 ? $maxPrecision-1 : 0;
    if ($maxPrecision === 0) {
        return $val0;
    }
    
    $sub = bcsub($val, $val0, $maxPrecision);
    $sub = preg_replace("/.*\..{{$precision}}/", '0.', $sub);
    /* zaokraglenie w gore */
    if ($sub > '0.5' || $mode == PHP_ROUND_HALF_UP && $sub == '0.5') {
        // obliczenie roznicy potrzebnej do zaokraglenia w gore
        $sub1 = bcsub($val, $val0, $precision+1);
        $sub1 = preg_replace('/\d$/', '5', $sub1);
        $sub1 = bcmul($sub1, 2, $precision);
        // zaokraglenie w gore
        $val0 = bcadd($val0, $sub1, $precision);
    }
    
    return $val0;
}

function bcfloor($val) {
    $val0 = bcadd($val, 0, 0);
    
    $maxPrecision = strlen(strrchr($val, '.'));
    $maxPrecision = $maxPrecision > 1 ? $maxPrecision-1 : 0;
    
    if (bccomp($val, $val0, $maxPrecision) === 0) {
        return $val0;
    } else {
        return ($val{0} === '-') ? bcadd($val0, -1) : $val0;
    }
}

function bcceil($val) {
    $val0 = bcadd($val, 0, 0);
    
    $maxPrecision = strlen(strrchr($val, '.'));
    $maxPrecision = $maxPrecision > 1 ? $maxPrecision-1 : 0;
    
    if (bccomp($val, $val0, $maxPrecision) === 0) {
        return $val0;
    } else {
        return ($val{0} === '-') ? $val0 : bcadd($val0, 1);
    }
}
