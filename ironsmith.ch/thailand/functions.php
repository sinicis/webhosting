<?php
######################
#
# GALLERY BUILDER
#
######################

# Experiment # 1
# 23.02.2016
# Simu
#
# Thema: Gallery builden
# Substract:    here we build a gallery builder. there will be several pre-defined schemes from 
#               which once will be taken randomly. number of pictures in one scheme are variable
#               
#               

function galBuilder($images) {

echo count($images);
    echo "in function: galBuilder";

    $cntScheme  = 1;
    $randomNr   = rand(1,$cntScheme);
    galScheme($randomNr,$cntScheme,$images);

} // eof galBuilder

function galScheme($nr,$cntScheme,$images) {
    
    echo "in function: galScheme";

    // call scheme function
    for ( $i=1;$i<=$cntScheme;$i++ ){
        if ( $nr == $i ) { schemeOne($images); }
    }


} // eof galScheme

function schemeOne($images) {

echo "in function schemeOne";
echo "<pre>";
print_r($images);
echo "</pre>";

    echo "<table class='scheme' id='schemeOne' width='900px' border='1px solid red'>";
        echo "<tr class='scheme' id='schemeOneFirst'>";
            $img    = array_shift($images);
            echo "<td width='530px' rowspan='3'><img src='img/" . $img . "' max-width='530px' height='auto' /></td>";
            $img    = array_shift($images); echo "<td width='370px'><img src='img/" . $img . "' width='370px' /></td>";
        echo "</tr><tr>";
            $img    = array_shift($images); echo "<td width='370px'><img src='img/" . $img . "' width='370px' /></td>";
        echo "</tr><tr>";
            $img    = array_shift($images); echo "<td width='370px'><img src='img/" . $img . "' width='370px' /></td>";
        echo "</tr>";
    echo "</table>";

} // eof schemeOne
