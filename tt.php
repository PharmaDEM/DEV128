<?php

$array = array("Water", "Acetone", "Amyl Acetate", "n-Amyl Alcohol", "tAmOH", "Benzyl Alcohol", "1-Butanol", "2-Butanol", "nBuOAC", "1-Butyl-2-Pyrolidinone", "CO2", "Dihydroeyoglucosenone", "Dimethylisosorbide", "EtOH", "n-Heptane", "Isoamyl Alcohol", "IPA", "n-Propanol", "Propylene Carbonate", "Propylene Glycol", "1-methoxy-2-propanol","Acetic Acid");

// Initialize an empty matrix
$matrix = array();

// Create all possible combinations of array elements
$combinations = array();
foreach (array_unique($array) as $element1) {
    foreach (array_unique($array) as $element2) {
        foreach (array_unique($array) as $element3) {
            if (($element1 !== $element2) && ($element1 !== $element3) && ($element2 !== $element3)) {
                $combination = array($element1, $element2, $element3);
                $is_valid = true;
                foreach ($matrix as $row) {
                    if (($row[0] === $combination[0]) || ($row[2] === $combination[2])) {
                        $is_valid = false;
                        break;
                    }
                }
                if ($is_valid) {
                    $combinations[] = $combination;
                }
            }
        }
    }
}

// Split the combinations into chunks of 3 and add them to the matrix
foreach (array_chunk($combinations, 3) as $chunk) {
    $matrix[] = $chunk;
}

// Print the matrix
foreach ($matrix as $row) {
    foreach ($row as $element) {
        echo "XYZ " . $element[0] . " -> " . $element[1] . " -> " . $element[2] . " <br>";
    }
}


 

 
?>