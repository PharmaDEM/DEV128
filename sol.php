<?php
// Define the solvents

$solvents = array('h2o_c0', 'propanone_c0', 'n-pentylacetate_c0', '1-pentanol_c0', 'tert_Amyl_alcohol_c0', 'benzylalcohol_c0', '1-butanol_c0', '2-butanol_c0', 'n-butylacetate_c0', 'n-butyl_pyrrolidinone_c0', 'co2_c0', 'Dihydroeyoglucosenone_c0', 'Dimethylisosorbide_c0', 'ethanol_c0', 'n-heptane_c0', 'isopentanol_c0', '2-propanol_c0', 'propanol_c0', 'Propylene_carbonate_fine_c0', 'glycerol_c0', '1-methoxy-2-propanol_c0', 'aceticacid_c0', 'acetonitrile_c0', 'anisole_c0', 'tert-butanol_c0', '2-chlorotoluene_fine_c0', '1-chlorobutane_c0', 'isopropylbenzene_c0', 'cyclohexane_c0', 'cyclohexanone_c0', 'Cyclopentylmethylether_CPME_c0', 'p-cymene_isopropyl_toluene', 'Diethylene_glycol_diethyl_ether_fine_c0', 'dimethylsulfoxide_c0', '1,3-Dimethyl-2-imidazolidinone_c0', 'ethylacetate_c0', 'glycol_c0', 'formicacid_c0', '4-Formylmorpholine_fine_c0', 'methanol_c0', 'butanone_c0', '4-methyl-2-pentanone_c0', 'methylacetate_c0', 'methylcyclohexane_c0', 'isopropylacetate_c0', 'propionicacid_c0', '2MeTHF_c0', '4-methyl_tetrahydro_pyran', 'thf_c0', 'Tetramethylurea_fine_c0', 'toluene_c0', '1,2-dimethylbenzene_c0', 'chlorobenzene_c0', 'dioxane_c0', '1,2-Dimethoxyethane_c0', 'hexane_c0', '2-methoxyethanol_c0', 'Methyl_tert_butyl_ether_MTBE_c0', '1,2-dichlorobenzene_c0', 'pyridine_c0', 'Sulfolane_c0', 'triethylamine_c0', 'trifluoroaceticacid_c0', 'chcl3_c0', 'n,n-dimethylacetamide_c0', 'dimethylformamide_c0', 'N-methylpyrrolidinone_NMP_c0', 'ch2cl2_c0');


// Define the number of levels
$levels = $_GET['cc'];

// Generate the combinations
$combinations = generateCombinations($solvents, $levels);

// Print the combinations in a table
echo "<table>\n";
foreach ($combinations as $key => $combination) {
    // Add 1 to the key to start numbering at 1 instead of 0
    $number = $key + 1;
    echo "<tr><td>" . $number . "</td><td>" . implode("</td><td>", $combination) . "</td></tr>\n";
}
echo "</table>\n";

// Function to generate the combinations
function generateCombinations($elements, $length) {
    $combinations = array();
    if ($length == 1) {
        foreach ($elements as $element) {
            $combinations[] = array($element);
        }
    } else {
        foreach ($elements as $key => $element) {
            $subElements = array_slice($elements, $key + 1);
            // recursively generate combinations of sub-elements
            $subCombinations = generateCombinations($subElements, $length - 1);
            foreach ($subCombinations as $subCombination) {
                array_unshift($subCombination, $element);
                $combinations[] = $subCombination;
            }
        }
    }
    return $combinations;
}