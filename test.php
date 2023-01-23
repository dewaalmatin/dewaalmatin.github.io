<?php
require 'functions.php';
$earbuds = query("SELECT * FROM test_data");

function combine($n, $arr, $prev, &$result) {
    if ($n == count($arr)) {
        array_push($result, $arr);
    } else {
        for ($i = $prev; $i < 10; $i++) {
            $arr[$n] = $i;
            combine($n+1, $arr, $i, $result);
        }
    }
};

$arr = [0, 0, 0, 0, 0, 0];
$result = [];
combine(0, $arr, 1, $result);
  
$accCount = 0;

foreach($result as $userInput){
    $pairwise = pairwise($userInput);
    $colSum = columnSum($pairwise);
    $lambdaMax = lambdaMax($colSum, $weight);
    $consIndex = consIndex($lambdaMax);
    $consRatio = consRatio($consIndex);
    $isAccurate = isAccurate($consRatio);

    if($isAccurate){
        $accCount++;
    }
}

echo 'number of unique comparison = ';
echo count($result);
echo '<br>';
echo 'number of consistent comparison = ';
echo $accCount;
echo '<br>';
echo '<br>';

?>

<body style="text-align: center;">

<h1>AHP Test Result</h1>
<h3>Pairwise Comparison Matrix</h3>
<?php

$testInput = array(9, 8, 8, 4, 2, 1);
$pairwise = pairwise($testInput);
echo '<table border = 1 style="margin: auto">';

echo '<tr>';
echo '<td style="width: 16%;text-align: center; font-size: large; padding: 5px">' . 'Design' . '</td>';
echo '<td style="width: 16%;text-align: center; font-size: large; padding: 5px">' . 'Sound' . '</td>';
echo '<td style="width: 16%;text-align: center; font-size: large; padding: 5px">' . 'Power' . '</td>';
echo '<td style="width: 16%;text-align: center; font-size: large; padding: 5px">' . 'Con.' . '</td>';
echo '<td style="width: 16%;text-align: center; font-size: large; padding: 5px">' . 'Mic' . '</td>';
echo '<td style="width: 16%;text-align: center; font-size: large; padding: 5px">' . 'Price' . '</td>';
echo '</tr>';

// Output the table rows
foreach ($pairwise as $row) {
    echo '<tr>';
    foreach ($row as $cell) {
        echo '<td style="width: 16%;text-align: center; font-size: large; padding: 5px">' . round($cell,3) . '</td>';
    }
    echo '</tr>';
}

// End the table
echo '</table>';

echo '<br>';
echo '<br>';
?>

<h3>Normalized Pairwise Comparison Matrix</h3>

<?php
$normalizedPairwise = normalizedPairwise($pairwise);
echo '<table border = 1 style="margin: auto">';

echo '<tr>';
echo '<td style="width: 16%;text-align: center; font-size: large; padding: 5px">' . 'Design' . '</td>';
echo '<td style="width: 16%;text-align: center; font-size: large; padding: 5px">' . 'Sound' . '</td>';
echo '<td style="width: 16%;text-align: center; font-size: large; padding: 5px">' . 'Power' . '</td>';
echo '<td style="width: 16%;text-align: center; font-size: large; padding: 5px">' . 'Con.' . '</td>';
echo '<td style="width: 16%;text-align: center; font-size: large; padding: 5px">' . 'Mic' . '</td>';
echo '<td style="width: 16%;text-align: center; font-size: large; padding: 5px">' . 'Price' . '</td>';
echo '</tr>';

// Output the table rows
foreach ($normalizedPairwise as $row) {
    echo '<tr>';
    foreach ($row as $cell) {
        echo '<td style="width: 16%;text-align: center; font-size: large; padding: 5px">' . round($cell,3) . '</td>';
    }
    echo '</tr>';
}

// End the table
echo '</table>';

echo '<br>';
echo '<br>';
?>

<h3>Weights</h3>

<?php
$weight = weight($normalizedPairwise);
echo '<table border = 1 style="margin: auto">';

echo '<tr>';
echo '<td style="width: 16%;text-align: center; font-size: large; padding: 5px">' . 'Design' . '</td>';
echo '<td style="width: 16%;text-align: center; font-size: large; padding: 5px">' . 'Sound' . '</td>';
echo '<td style="width: 16%;text-align: center; font-size: large; padding: 5px">' . 'Power' . '</td>';
echo '<td style="width: 16%;text-align: center; font-size: large; padding: 5px">' . 'Con.' . '</td>';
echo '<td style="width: 16%;text-align: center; font-size: large; padding: 5px">' . 'Mic' . '</td>';
echo '<td style="width: 16%;text-align: center; font-size: large; padding: 5px">' . 'Price' . '</td>';
echo '</tr>';

// Output the table rows
//foreach ($normalizedPairwise as $row) {
    echo '<tr>';
    foreach ($weight as $cell) {
        echo '<td style="width: 16%;text-align: center; font-size: large; padding: 5px">' . round($cell,3) . '</td>';
    }
    echo '</tr>';
//}

// End the table
echo '</table>';

echo '<br>';
echo '<br>';
?>

<h3>Consistency</h3>

<?php
$lambdaMax = lambdaMax($pairwise, $weight);
$consIndex = consIndex($lambdaMax);
$consRatio = consRatio($consIndex);
echo '<table border = 1 style="margin: auto">';

echo '<tr>';
echo '<td style="width: 33%;text-align: center; font-size: large; padding: 5px">' . 'Lambda Max' . '</td>';
echo '<td style="width: 33%;text-align: center; font-size: large; padding: 5px">' . 'CI' . '</td>';
echo '<td style="width: 33%;text-align: center; font-size: large; padding: 5px">' . 'CR' . '</td>';
echo '</tr>';

// Output the table rows
//foreach ($normalizedPairwise as $row) {
    echo '<tr>';
    //foreach ($weight as $cell) {
        echo '<td style="width: 16%;text-align: center; font-size: large; padding: 5px">' . round($lambdaMax,3) . '</td>';
        echo '<td style="width: 16%;text-align: center; font-size: large; padding: 5px">' . round($consIndex,3) . '</td>';
        echo '<td style="width: 16%;text-align: center; font-size: large; padding: 5px">' . round($consRatio,3) . '</td>';
    //}
    echo '</tr>';
//}

// End the table
echo '</table>';

echo '<br>';
echo '<br>';
?>

<h1>TOPSIS Test Result</h1>
<h3>Desicion Matrix</h3>

<?php
$desicionMatrix = desicionMatrix($earbuds);
echo '<table border = 1 style="margin: auto">';

// Output the table rows
foreach ($desicionMatrix as $row) {
    echo '<tr>';
    foreach ($row as $cell) {
        echo '<td style="width: 8%;text-align: center; font-size: large; padding: 5px">' . $cell . '</td>';
    }
    echo '</tr>';
}

// End the table
echo '</table>';

echo '<br>';
echo '<br>';
?>

<h3>Normalized Desicion Matrix</h3>

<?php
$distributedWeight = distributeWeight($weight);
$normalizedDesicionMatrix = normalizedDesicionMatrix($desicionMatrix);
echo '<table border = 1 style="margin: auto">';

// Output the table rows
foreach ($normalizedDesicionMatrix as $row) {
    echo '<tr>';
    foreach ($row as $cell) {
        echo '<td style="width: 8%;text-align: center; font-size: large; padding: 5px">' . round($cell,3) . '</td>';
    }
    echo '</tr>';
}

// End the table
echo '</table>';

echo '<br>';
echo '<br>';
?>

<h3>Weighted Desicion Matrix</h3>

<?php
$weightedMatrix = weightedMatrix($normalizedDesicionMatrix, $distributedWeight);
echo '<table border = 1 style="margin: auto">';

// Output the table rows
foreach ($weightedMatrix as $row) {
    echo '<tr>';
    foreach ($row as $cell) {
        echo '<td style="width: 8%;text-align: center; font-size: large; padding: 5px">' . round($cell,3) . '</td>';
    }
    echo '</tr>';
}

// End the table
echo '</table>';

echo '<br>';
echo '<br>';
?>

<h3>Ideal Solution</h3>

<?php
    $idealSolution = idealSolution($weightedMatrix);
    echo '<table border = 1 style="margin: auto">';

// Output the table rows
foreach ($idealSolution as $row) {
    echo '<tr>';
    foreach ($row as $cell) {
        echo '<td style="width: 8%;text-align: center; font-size: large; padding: 5px">' . round($cell,3) . '</td>';
    }
    echo '</tr>';
}

// End the table
echo '</table>';

echo '<br>';
echo '<br>';
?>

<h3>Alternative Distance</h3>

<?php
    $distance = distance($weightedMatrix, $idealSolution);
    echo '<table border = 1 style="margin: auto">';

// Output the table rows
foreach ($distance as $row) {
    echo '<tr>';
    foreach ($row as $cell) {
        echo '<td style="width: 8%;text-align: center; font-size: large; padding: 5px">' . round($cell,3) . '</td>';
    }
    echo '</tr>';
}

// End the table
echo '</table>';

echo '<br>';
echo '<br>';
?>

<h3>Alternative Preference Value</h3>

<?php
    $preferenceValue = preferenceValue($distance);
    $rankedList = rankedList($earbuds, $preferenceValue);
    echo '<table border = 1 style="margin: auto; width: 50%">';

$row = 0;
foreach ($rankedList[0] as $cell) {
    echo '<tr>';
    echo '<td style="width: 2%;text-align: center; font-size: large; padding: 5px">' . $cell . '</td>';
    echo '<td style="width: 2%;text-align: center; font-size: large; padding: 5px">' . round($rankedList[1][$row],3) . '</td>';
    echo '</tr>';
    $row++;
}

// End the table
echo '</table>';

echo '<br>';
echo '<br>';
?>

</body>