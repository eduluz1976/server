<?php
include_once __DIR__.'/bootstrap.php';



function sampleFunction1($parm='') {
    return '((('.$parm.')))';
}


function sampleFunction2($parm='') {
    return ')))'.$parm.'(((';
}
