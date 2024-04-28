<?php

// Define two sample arrays
$array1 = array('API-Water0');

$array2 = array('Phenol', 'Asprin', 'Methonal');
$array3 = array('3Phenol', '3Asprin', '3Methonal');
$array4 = array('4Phenol', '4Asprin', '4Methonal');

// Define an empty array to hold the combinations
$combinations = array();
$combinations1 = array();
$combinations3 = array();
// Loop through each element in the first array
foreach ($array1 as $element1) {
    // Loop through each element in the second array
    foreach ($array2 as $element2) {
        // Concatenate the two elements and add to the combinations array
        $combinations[] = $element1 ."-->". $element2;
    }
}

// Loop through each element in the first array
foreach ($array1 as $element1) {
    // Loop through each element in the second array
    foreach ($array2 as $element2) {
        // Concatenate the two elements and add to the combinations array
        //$combinations[] = $element1 ."-->". $element2;
    

    foreach ($array3 as $element3) {
        // Concatenate the two elements and add to the combinations array
        $combinations1[] = $element1 ."-->". $element2."-->". $element3;
    }
}
}

// Loop through each element in the first array
foreach ($array1 as $element1) {
    // Loop through each element in the second array
    foreach ($array2 as $element2) {
        // Concatenate the two elements and add to the combinations array
        //$combinations[] = $element1 ."-->". $element2;
    

    foreach ($array3 as $element3) {
        // Concatenate the two elements and add to the combinations array
        //$combinations1[] = $element1 ."-->". $element2."-->". $element3;
        foreach ($array4 as $element4) {
            // Concatenate the two elements and add to the combinations array
            $combinations3[] = $element1 ."-->". $element2."-->". $element3."-->". $element4;
        }
    }
}
}

echo "Pure";
echo '<pre>',print_r($combinations),'</pre>';

echo "Binary";
echo '<pre>',print_r($combinations1),'</pre>';


echo "Ternary";
echo '<pre>',print_r($combinations3),'</pre>';
// Print the combinations array
//print_r($combinations);

?>