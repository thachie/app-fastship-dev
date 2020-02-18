<?php 

for($i = 3120000;$i<=3120999;$i++){
    //86423597
    $c1 = substr($i,0,1) * 6;
    $c2 = substr($i,1,1) * 4;
    $c3 = substr($i,2,1) * 2;
    $c4 = substr($i,3,1) * 3;
    $c5 = substr($i,4,1) * 5;
    $c6 = substr($i,5,1) * 9;
    $c7 = substr($i,6,1) * 7;
    $mod = ($c1+$c2+$c3+$c4+$c5+$c6+$c7) % 11;

    if($mod == 0) $check = 5;
    else if($mod == 1) $check = 0;
    else $check = (11 - $mod);
    
    echo "EY0" . $i . $check . "TH";
    echo "<br />";
}
?>