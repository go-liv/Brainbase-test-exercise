<?php

function variancePercentage($x, $y) {
    $result = (($y - $x) / (($x + $y) / 2)) * 100;
    
    return round($result, 3);
}