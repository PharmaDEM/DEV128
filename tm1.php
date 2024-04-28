<?php
function pure() {
    // Define a sample array of values
    //$values = array('phenol','1-Octanol','methylcyclohexane','n,n-dimethylacetamide','methane1' );

$values = array('h2o_c0', 'propanone_c0', 'n-pentylacetate_c0', '1-pentanol_c0', 'tert_Amyl_alcohol_c0', 'benzylalcohol_c0', '1-butanol_c0', '2-butanol_c0', 'n-butylacetate_c0', 'n-butyl_pyrrolidinone_c0', 'co2_c0', 'Dihydroeyoglucosenone_c0', 'Dimethylisosorbide_c0', 'ethanol_c0', 'n-heptane_c0', 'isopentanol_c0', '2-propanol_c0', 'propanol_c0', 'Propylene_carbonate_fine_c0', 'glycerol_c0', '1-methoxy-2-propanol_c0', 'aceticacid_c0', 'acetonitrile_c0', 'anisole_c0', 'tert-butanol_c0', '2-chlorotoluene_fine_c0', '1-chlorobutane_c0', 'isopropylbenzene_c0', 'cyclohexane_c0', 'cyclohexanone_c0', 'Cyclopentylmethylether_CPME_c0', 'p-cymene_isopropyl_toluene', 'Diethylene_glycol_diethyl_ether_fine_c0', 'dimethylsulfoxide_c0', '1,3-Dimethyl-2-imidazolidinone_c0', 'ethylacetate_c0', 'glycol_c0', 'formicacid_c0', '4-Formylmorpholine_fine_c0', 'methanol_c0', 'butanone_c0', '4-methyl-2-pentanone_c0', 'methylacetate_c0', 'methylcyclohexane_c0', 'isopropylacetate_c0', 'propionicacid_c0', '2MeTHF_c0', '4-methyl_tetrahydro_pyran', 'thf_c0', 'Tetramethylurea_fine_c0', 'toluene_c0', '1,2-dimethylbenzene_c0', 'chlorobenzene_c0', 'dioxane_c0', '1,2-Dimethoxyethane_c0', 'hexane_c0', '2-methoxyethanol_c0', 'Methyl_tert_butyl_ether_MTBE_c0', '1,2-dichlorobenzene_c0', 'pyridine_c0', 'Sulfolane_c0', 'triethylamine_c0', 'trifluoroaceticacid_c0', 'chcl3_c0', 'n,n-dimethylacetamide_c0', 'dimethylformamide_c0', 'N-methylpyrrolidinone_NMP_c0', 'ch2cl2_c0');

   //print_r($values);
    
    
    // Define the number of rows and columns in the matrix
    $rows = count($values);
    $cols = count($values);
    
    // Define an empty matrix array
    $matrix = array();
    
    // Loop through each row in the matrix
    for ($i = 0; $i < $rows; $i++) {
        // Define an empty row array
        $row = array();
        // Loop through each column in the matrix
        for ($j = 0; $j < $cols; $j++) {
            // Concatenate the corresponding values and add to the row array
            if ($i<>$j) {
            $row[] = "XYZ ".$values[$i]."<br>";
            }
        }
        // Add the row array to the matrix array
        $matrix[] = $row;
    }
    
    // Print the matrix
    foreach ($matrix as $row) {
        echo implode(' ', $row) . "<br><br>";
    }
    
    }

function binary() {
// Define a sample array of values
//$values = array("h2o_c0", "propanone_c0", "n-pentylacetate_c0", "1-pentanol_c0", "tert_Amyl_alcohol_c0", "benzylalcohol_c0", "1-butanol_c0", "2-butanol_c0", "n-butylacetate_c0", "n-butyl_pyrrolidinone_c0", "co2_c0", "Dihydroeyoglucosenone_c0", "Dimethylisosorbide_c0", "ethanol_c0", "n-heptane_c0", "isopentanol_c0", "2-propanol_c0", "propanol_c0", "Propylene_carbonate_fine_c0", "glycerol_c0", "1-methoxy-2-propanol_c0", "aceticacid_c0", "acetonitrile_c0", "anisole_c0", "tert-butanol_c0", "2-chlorotoluene_fine_c0", "1-chlorobutane_c0", "isopropylbenzene_c0", "cyclohexane_c0", "cyclohexanone_c0", "Cyclopentylmethylether_CPME_c0", "p-cymene_isopropyl_toluene", "Diethylene_glycol_diethyl_ether_fine_c0", "dimethylsulfoxide_c0", "1,3-Dimethyl-2-imidazolidinone_c0", "ethylacetate_c0", "glycol_c0", "formicacid_c0", "4-Formylmorpholine_fine_c0", "methanol_c0", "butanone_c0", "4-methyl-2-pentanone_c0", "methylacetate_c0", "methylcyclohexane_c0", "isopropylacetate_c0", "propionicacid_c0", "2MeTHF_c0", "4-methyl_tetrahydro_pyran", "thf_c0", "Tetramethylurea_fine_c0", "toluene_c0", "1,2-dimethylbenzene_c0", "chlorobenzene_c0", "dioxane_c0", "1,2-Dimethoxyethane_c0", "hexane_c0", "2-methoxyethanol_c0", "Methyl_tert_butyl_ether_MTBE_c0", "1,2-dichlorobenzene_c0", "pyridine_c0", "Sulfolane_c0", "triethylamine_c0", "trifluoroaceticacid_c0", "chcl3_c0", "n,n-dimethylacetamide_c0", "dimethylformamide_c0", "N-methylpyrrolidinone_NMP_c0", "ch2cl2_c0");
$values= array("Water", "Acetone", "Amyl Acetate", "n-Amyl Alcohol", "tAmOH", "Benzyl Alcohol", "1-Butanol", "2-Butanol", "nBuOAC", "1-Butyl-2-Pyrolidinone", "CO2", "Dihydroeyoglucosenone", "Dimethylisosorbide", "EtOH", "n-Heptane", "Isoamyl Alcohol", "IPA", "n-Propanol", "Propylene Carbonate", "Propylene Glycol", "1-methoxy-2-propanol", "Acetic Acid", "Acetonitrile", "Anisole", "t-Butanol", "2-Chlorotoluene", "1-Chlorobutane", "Cumene", "Cyclohexane", "Cyclohexanone", "CPME", "p-Cymene", "Ethyl Diglyme", "DMSO", "DMI", "EtOAc", "Ethylene Glycol", "Formic Acid", "4-Formylmorpholine", "MeOH", "MEK", "MIBK", "MeOAc", "Methyl Cyclohexane", "iPrOAc", "Propionic Acid", "2-MeTHF", "4-MeTHP", "THF", "TMU", "Toluene", "Xylenes", "Chlorobenzene", "Dioxane", "Glyme", "Hexane", "2-Methoxyethanol", "MTBE", "o-Dichlorobenzene", "Pyridine", "Sulfolane", "Et3N", "TFA", "Chloroform", "DMAC", "DMF", "NMP", "DCM");


//print_r($values);


// Define the number of rows and columns in the matrix
$rows = count($values);
$cols = count($values);

// Define an empty matrix array
$matrix = array();

// Loop through each row in the matrix
for ($i = 0; $i < $rows; $i++) {
    // Define an empty row array
    $row = array();
    $rowe= array();
    // Loop through each column in the matrix
    for ($j = 0; $j < $cols; $j++) {
        // Concatenate the corresponding values and add to the row array
       if ($values[$i]!=$values[$j]) {
        $row[] = $values[$i] .'->'. $values[$j]."<br>";
       // echo  "crs.add_molecule(['".$values[$i]."/COSMO_TZVPD/".$values[$i]."_c000.orcacosmo'])<br>";
       // echo  "crs.add_molecule(['".$values[$j]."/COSMO_TZVPD/".$values[$j]."_c000.orcacosmo'])<br>";
        echo $values[$i] . " -> " . $values[$j] . " <br>";
        echo "<hr>";
        $rowe[]=$values[$i].'#'.$values[$j];
        
        }
    }

}


}

function tinary (){

   // Define an array
//$array = array('phenol', '1-Octanol', 'methylcyclohexane', 'n,n-dimethylacetamide', 'methane1');

//$array = array('h2o_c0', 'propanone_c0', 'n-pentylacetate_c0', '1-pentanol_c0', 'tert_Amyl_alcohol_c0', 'benzylalcohol_c0', '1-butanol_c0', '2-butanol_c0', 'n-butylacetate_c0', 'n-butyl_pyrrolidinone_c0', 'co2_c0', 'Dihydroeyoglucosenone_c0', 'Dimethylisosorbide_c0', 'ethanol_c0', 'n-heptane_c0', 'isopentanol_c0', '2-propanol_c0', 'propanol_c0', 'Propylene_carbonate_fine_c0', 'glycerol_c0', '1-methoxy-2-propanol_c0', 'aceticacid_c0', 'acetonitrile_c0', 'anisole_c0', 'tert-butanol_c0', '2-chlorotoluene_fine_c0', '1-chlorobutane_c0', 'isopropylbenzene_c0', 'cyclohexane_c0', 'cyclohexanone_c0', 'Cyclopentylmethylether_CPME_c0', 'p-cymene_isopropyl_toluene', 'Diethylene_glycol_diethyl_ether_fine_c0', 'dimethylsulfoxide_c0', '1,3-Dimethyl-2-imidazolidinone_c0', 'ethylacetate_c0', 'glycol_c0', 'formicacid_c0', '4-Formylmorpholine_fine_c0', 'methanol_c0', 'butanone_c0');
//$array = array('h2o_c0', 'propanone_c0', 'n-pentylacetate_c0', '1-pentanol_c0', 'tert_Amyl_alcohol_c0', 'benzylalcohol_c0', '1-butanol_c0', '2-butanol_c0', 'n-butylacetate_c0', 'n-butyl_pyrrolidinone_c0', 'co2_c0', 'Dihydroeyoglucosenone_c0', 'Dimethylisosorbide_c0', 'ethanol_c0', 'n-heptane_c0', 'isopentanol_c0', '2-propanol_c0', 'propanol_c0', 'Propylene_carbonate_fine_c0', 'glycerol_c0', '1-methoxy-2-propanol_c0', 'aceticacid_c0', 'acetonitrile_c0', 'anisole_c0', 'tert-butanol_c0', '2-chlorotoluene_fine_c0', '1-chlorobutane_c0', 'isopropylbenzene_c0', 'cyclohexane_c0', 'cyclohexanone_c0', 'Cyclopentylmethylether_CPME_c0', 'p-cymene_isopropyl_toluene', 'Diethylene_glycol_diethyl_ether_fine_c0', 'dimethylsulfoxide_c0', '1,3-Dimethyl-2-imidazolidinone_c0', 'ethylacetate_c0', 'glycol_c0', 'formicacid_c0', '4-Formylmorpholine_fine_c0', 'methanol_c0', 'butanone_c0');
//LATEST $array = array('h2o_c0', 'propanone_c0', 'n-pentylacetate_c0', '1-pentanol_c0', 'tert_Amyl_alcohol_c0', 'benzylalcohol_c0', '1-butanol_c0', '2-butanol_c0', 'n-butylacetate_c0', 'n-butyl_pyrrolidinone_c0', 'co2_c0', 'Dihydroeyoglucosenone_c0', 'Dimethylisosorbide_c0', 'ethanol_c0', 'n-heptane_c0', 'isopentanol_c0', '2-propanol_c0', 'propanol_c0', 'Propylene_carbonate_fine_c0', 'glycerol_c0', '1-methoxy-2-propanol_c0', 'aceticacid_c0', 'acetonitrile_c0', 'anisole_c0', 'tert-butanol_c0', '2-chlorotoluene_fine_c0', '1-chlorobutane_c0', 'isopropylbenzene_c0', 'cyclohexane_c0', 'cyclohexanone_c0', 'Cyclopentylmethylether_CPME_c0', 'p-cymene_isopropyl_toluene', 'Diethylene_glycol_diethyl_ether_fine_c0', 'dimethylsulfoxide_c0', '1,3-Dimethyl-2-imidazolidinone_c0', 'ethylacetate_c0', 'glycol_c0', 'formicacid_c0', '4-Formylmorpholine_fine_c0', 'methanol_c0', 'butanone_c0');
////$array = array('h2o_c0', 'propanone_c0', 'n-pentylacetate_c0', '1-pentanol_c0', 'tert_Amyl_alcohol_c0', 'benzylalcohol_c0', '1-butanol_c0', '2-butanol_c0', 'n-butylacetate_c0', 'n-butyl_pyrrolidinone_c0', 'co2_c0', 'Dihydroeyoglucosenone_c0', 'Dimethylisosorbide_c0', 'ethanol_c0', 'n-heptane_c0', 'isopentanol_c0', '2-propanol_c0', 'propanol_c0', 'Propylene_carbonate_fine_c0', 'glycerol_c0', '1-methoxy-2-propanol_c0', 'aceticacid_c0', 'acetonitrile_c0', 'anisole_c0', 'tert-butanol_c0', '2-chlorotoluene_fine_c0', '1-chlorobutane_c0', 'isopropylbenzene_c0', 'cyclohexane_c0', 'cyclohexanone_c0', 'Cyclopentylmethylether_CPME_c0', 'p-cymene_isopropyl_toluene', 'Diethylene_glycol_diethyl_ether_fine_c0', 'dimethylsulfoxide_c0', '1,3-Dimethyl-2-imidazolidinone_c0', 'ethylacetate_c0', 'glycol_c0', 'formicacid_c0', '4-Formylmorpholine_fine_c0', 'methanol_c0', 'butanone_c0', '4-methyl-2-pentanone_c0', 'methylacetate_c0', 'methylcyclohexane_c0', 'isopropylacetate_c0', 'propionicacid_c0', '2MeTHF_c0', '4-methyl_tetrahydro_pyran', 'thf_c0', 'Tetramethylurea_fine_c0', 'toluene_c0', '1,2-dimethylbenzene_c0', 'chlorobenzene_c0', 'dioxane_c0', '1,2-Dimethoxyethane_c0', 'hexane_c0', '2-methoxyethanol_c0', 'Methyl_tert_butyl_ether_MTBE_c0', '1,2-dichlorobenzene_c0', 'pyridine_c0', 'Sulfolane_c0', 'triethylamine_c0', 'trifluoroaceticacid_c0', 'chcl3_c0', 'n,n-dimethylacetamide_c0', 'dimethylformamide_c0', 'N-methylpyrrolidinone_NMP_c0', 'ch2cl2_c0');
//
//echo count($array);

$array = array("Water", "Acetone", "Amyl Acetate", "n-Amyl Alcohol", "tAmOH", "Benzyl Alcohol", "1-Butanol", "2-Butanol", "nBuOAC", "1-Butyl-2-Pyrolidinone", "CO2", "Dihydroeyoglucosenone", "Dimethylisosorbide", "EtOH", "n-Heptane", "Isoamyl Alcohol", "IPA", "n-Propanol", "Propylene Carbonate", "Propylene Glycol", "1-methoxy-2-propanol","Acetic Acid");


// Initialize an empty matrix
$matrix = array();

// Create all possible combinations of array elements
$combinations = array();
foreach ($array as $element1) {
    foreach ($array as $element2) {
        foreach ($array as $element3) {
            $combinations[] = array($element1, $element2, $element3);
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
        if(($element[0]<>$element[1]) && ($element[0]<>$element[2]) && ($element[1]<>$element[2]))  {
        echo "XYZ ".$element[0] . " -> " . $element[1] . " -> " . $element[2] . " <br>";
       // echo  "crs.add_molecule(['".$element[0]."/COSMO_TZVPD/".$element[0]."_c000.orcacosmo'])<br>";
       // echo  "crs.add_molecule(['".$element[1]."/COSMO_TZVPD/".$element[1]."_c000.orcacosmo'])<br>";
        //echo  "crs.add_molecule(['".$element[2]."/COSMO_TZVPD/".$element[2]."_c000.orcacosmo'])<br>";
        echo "<hr>";
    
        }
        
    }
    
    //echo "\n";
}

//echo count($matrix);


}

function tinary1 (){

    // Define an array
 //$array = array('phenol', '1-Octanol', 'methylcyclohexane', 'n,n-dimethylacetamide', 'methane1');
 
 //$array = array('h2o_c0', 'propanone_c0', 'n-pentylacetate_c0', '1-pentanol_c0', 'tert_Amyl_alcohol_c0', 'benzylalcohol_c0', '1-butanol_c0', '2-butanol_c0', 'n-butylacetate_c0', 'n-butyl_pyrrolidinone_c0', 'co2_c0', 'Dihydroeyoglucosenone_c0', 'Dimethylisosorbide_c0', 'ethanol_c0', 'n-heptane_c0', 'isopentanol_c0', '2-propanol_c0', 'propanol_c0', 'Propylene_carbonate_fine_c0', 'glycerol_c0', '1-methoxy-2-propanol_c0', 'aceticacid_c0', 'acetonitrile_c0', 'anisole_c0', 'tert-butanol_c0', '2-chlorotoluene_fine_c0', '1-chlorobutane_c0', 'isopropylbenzene_c0', 'cyclohexane_c0', 'cyclohexanone_c0', 'Cyclopentylmethylether_CPME_c0', 'p-cymene_isopropyl_toluene', 'Diethylene_glycol_diethyl_ether_fine_c0', 'dimethylsulfoxide_c0', '1,3-Dimethyl-2-imidazolidinone_c0', 'ethylacetate_c0', 'glycol_c0', 'formicacid_c0', '4-Formylmorpholine_fine_c0', 'methanol_c0', 'butanone_c0');
//$array = array('h2o_c0', 'propanone_c0', 'n-pentylacetate_c0', '1-pentanol_c0', 'tert_Amyl_alcohol_c0', 'benzylalcohol_c0', '1-butanol_c0', '2-butanol_c0', 'n-butylacetate_c0', 'n-butyl_pyrrolidinone_c0', 'co2_c0', 'Dihydroeyoglucosenone_c0', 'Dimethylisosorbide_c0', 'ethanol_c0', 'n-heptane_c0', 'isopentanol_c0', '2-propanol_c0', 'propanol_c0', 'Propylene_carbonate_fine_c0', 'glycerol_c0', '1-methoxy-2-propanol_c0', 'aceticacid_c0', 'acetonitrile_c0', 'anisole_c0', 'tert-butanol_c0', '2-chlorotoluene_fine_c0', '1-chlorobutane_c0', 'isopropylbenzene_c0', 'cyclohexane_c0', 'cyclohexanone_c0', 'Cyclopentylmethylether_CPME_c0', 'p-cymene_isopropyl_toluene', 'Diethylene_glycol_diethyl_ether_fine_c0', 'dimethylsulfoxide_c0', '1,3-Dimethyl-2-imidazolidinone_c0', 'ethylacetate_c0', 'glycol_c0', 'formicacid_c0', '4-Formylmorpholine_fine_c0', 'methanol_c0', 'butanone_c0');
 //LATEST $array = array('4-methyl-2-pentanone_c0', 'methylacetate_c0', 'methylcyclohexane_c0', 'isopropylacetate_c0', 'propionicacid_c0', '2MeTHF_c0', '4-methyl_tetrahydro_pyran', 'thf_c0', 'Tetramethylurea_fine_c0', 'toluene_c0', '1,2-dimethylbenzene_c0', 'chlorobenzene_c0', 'dioxane_c0', '1,2-Dimethoxyethane_c0', 'hexane_c0', '2-methoxyethanol_c0', 'Methyl_tert_butyl_ether_MTBE_c0', '1,2-dichlorobenzene_c0', 'pyridine_c0', 'Sulfolane_c0', 'triethylamine_c0', 'trifluoroaceticacid_c0', 'chcl3_c0', 'n,n-dimethylacetamide_c0', 'dimethylformamide_c0', 'N-methylpyrrolidinone_NMP_c0', 'ch2cl2_c0');
 
 $array = array ("Acetonitrile", "Anisole", "t-Butanol", "2-Chlorotoluene", "1-Chlorobutane", "Cumene", "Cyclohexane", "Cyclohexanone", "CPME", "p-Cymene", "Ethyl Diglyme", "DMSO", "DMI", "EtOAc", "Ethylene Glycol", "Formic Acid", "4-Formylmorpholine", "MeOH", "MEK", "MIBK", "MeOAc", "Methyl Cyclohexane", "iPrOAc", "Propionic Acid", "2-MeTHF", "4-MeTHP", "THF", "TMU", "Toluene", "Xylenes", "Chlorobenzene", "Dioxane", "Glyme", "Hexane", "2-Methoxyethanol", "MTBE", "o-Dichlorobenzene", "Pyridine", "Sulfolane", "Et3N", "TFA", "Chloroform", "DMAC", "DMF", "NMP", "DCM");
 //echo count($array);
 
 // Initialize an empty matrix
 $matrix = array();
 
 // Create all possible combinations of array elements
 $combinations = array();
 foreach ($array as $element1) {
     foreach ($array as $element2) {
         foreach ($array as $element3) {
             $combinations[] = array($element1, $element2, $element3);
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
         if(($element[0]<>$element[1]) && ($element[0]<>$element[2]) && ($element[1]<>$element[2]))  {
         echo "XYZ ".$element[0] . " -> " . $element[1] . " -> " . $element[2] . " <br>";
         //echo  "crs.add_molecule(['".$element[0]."/COSMO_TZVPD/".$element[0]."_c000.orcacosmo'])<br>";
         //echo  "crs.add_molecule(['".$element[1]."/COSMO_TZVPD/".$element[1]."_c000.orcacosmo'])<br>";
        // echo  "crs.add_molecule(['".$element[2]."/COSMO_TZVPD/".$element[2]."_c000.orcacosmo'])<br>";
         echo "<hr>";
     
         }
         
     }
     
     //echo "\n";
 }
 
 //echo count($matrix);
 
 }


//echo "Solvents<br>XYZ is API<br>";

//echo "phenol, 1-Octanol, methylcyclohexane, n,n-dimethylacetamide, methane1<br><br>";


//echo "Pure<br><hr>";
//pure();



//echo "Binary<br><hr>";
//binary();

echo "Ternary<br><hr>";
tinary();
echo "<hr>";
//echo "<hr>44T</hr>";
tinary1();



$data = "[2.14294941 -1.83529021 -0.02952849 0.66917119]
[2.67004867 -1.00441422 -0.28758577 0.69732091]
[2.26550368 -0.07335871 -1.3693529 0.90243595]
[1.10785356 0.04954193 -2.23270534 1.34261255]
[0.96131401 0.01999967 -2.18468921 1.48742242]";

// Remove square brackets and split the data by whitespace
$data = preg_replace('/[ \[\]]+/', ' ', $data);
$data = trim($data);
$data = explode(' ', $data);

// Convert data to array
$result = array();
$row = array();
foreach ($data as $value) {
    $row[] = floatval($value);
    if (count($row) == 4) {
        $result[] = $row;
        $row = array();
    }
}

$json = json_encode($result);

//echo $json;


?>
