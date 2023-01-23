<?php
// connection to database

$db = mysqli_connect("localhost", "root", "", "dattbuds");

function query($query){
    global $db;
    $result = mysqli_query($db, $query);
    $rows = [];
    while($row = mysqli_fetch_assoc($result)){
        $rows[] = $row;
    }
    return $rows;
};

//AHP FUNCTIONS

function pairwise($userInput){
    $matrix = array(
        array(0,0,0,0,0,0),
        array(0,0,0,0,0,0),
        array(0,0,0,0,0,0),
        array(0,0,0,0,0,0),
        array(0,0,0,0,0,0),
        array(0,0,0,0,0,0)
    );
    $row = 0;

    foreach($userInput as $rowVal){
        $col = 0;
        foreach($userInput as $colVal){
            if($rowVal < $colVal){
                $x = $colVal - $rowVal;
                $x++;
                $matrix[$row][$col] = 1 / $x;
            }
            else{
                $x = $rowVal - $colVal;
                $x++;
                $matrix[$row][$col] = $x;
            };
            $col++;
        };
        $row++;
    };

    return $matrix;
};

function columnSum($pairwise){
    $colSum = array();

    foreach($pairwise as $rowArr){
        $col = 0;
        foreach($rowArr as $colVal){
            $colSum[$col] += $colVal;
            $col++;
        };
    };

    return $colSum;
}

function normalizedPairwise($pairwise){

    $colSum = columnSum($pairwise);

    $row = 0;

    foreach($pairwise as $rowArr){
        $col = 0;
        foreach($rowArr as $colVal){
            $pairwise[$row][$col] = $colVal / $colSum[$col];
            $col++;
        };
        $row++;
    };

    return $pairwise;
};

function weight($pairwise){
    $weight = array(0, 0, 0, 0, 0, 0);
    $col = 0;

    foreach($pairwise as $row){
        $x = array_sum($row);
        $weight[$col] = $x / 6;
        $col++;
    };

    return $weight;
};

function lambdaMax($pairwise, $weight){
    $total = array(0, 0, 0, 0, 0, 0);
    $row = 0;

    $colSum = columnSum($pairwise);

    foreach($colSum as $val){
        $total[$row] = $val * $weight[$row];
        $row++;
    };

    $lambdaMax = array_sum($total);

    return $lambdaMax;
};

function consIndex($lambdaMax){
    $x = $lambdaMax - 6;
    $consIndex = $x / 5;

    return $consIndex;
};

function consRatio($consIndex){
    $consRatio = $consIndex / 1.24;

    return $consRatio;
};

function isAccurate($consRatio){
    if($consRatio >= 0.1){
        return false;
    }
    else {
        return true;
    };
};

//TOPSIS FUNCTIONS

function desicionMatrix($earbuds){
    $row = 0;
    foreach($earbuds as $earbud){
        $desicionMatrix[$row] = array_values($earbud);
        array_shift($desicionMatrix[$row]);
        $row++;
    };

    return $desicionMatrix;
};

function sqrd($matrix){
    $row = 0;
    foreach($matrix as $arr){
        $col = 0;
        foreach($arr as $val){
            $sqrd[$row][$col] = $val ** 2;
            $col++;
        };
    $row++;
    };

    return $sqrd;
};

function normalizedDesicionMatrix($desicionMatrix){
    $sqrd = sqrd($desicionMatrix);

    $colSum = columnSum($sqrd);
    $row = 0;

    foreach($desicionMatrix as $arr){
        $col = 0;
        foreach($arr as $val){
            $normalizedDesicionMatrix[$row][$col] = $val / sqrt($colSum[$col]);
            $col++;
        };
        $row++;
    };

    return $normalizedDesicionMatrix;
};

function distributeWeight($weight){
    $distributedWeight[0] = $weight[0];
    $distributedWeight[1] = $weight[0];
    $distributedWeight[2] = $weight[1];
    $distributedWeight[3] = $weight[1];
    $distributedWeight[4] = $weight[1];
    $distributedWeight[5] = $weight[1];
    $distributedWeight[6] = $weight[2];
    $distributedWeight[7] = $weight[2];
    $distributedWeight[8] = $weight[3];
    $distributedWeight[9] = $weight[4];
    $distributedWeight[10] = $weight[4];
    $distributedWeight[11] = $weight[5];
    
    return $distributedWeight;
};

function weightedMatrix($normalizedDesicionMatrix, $weight){
    $row = 0;
    foreach($normalizedDesicionMatrix as $arr){
        $col = 0;
        foreach($arr as $val){
            $weigthedMatrix[$row][$col] = $val * $weight[$col];
            $col++;
        };
        $row++;
    };

    return $weigthedMatrix;
};

function transpose($array) {
    array_unshift($array, null);
    return call_user_func_array('array_map', $array);
}

function idealSolution($weigthedMatrix){
    $isBenefit = array(1, 0, 1, 1, 0, 1, 1, 1, 1, 1, 1, 0);
    $idealSolution = array(array(), array());

    $transposed = transpose($weigthedMatrix);

    $row = 0;
    foreach($transposed as $crit){
        $max = max($crit);
        $min = min($crit);
        if($isBenefit[$row] == 1){
            $idealSolution[0][$row] = $max;
            $idealSolution[1][$row] = $min;
        }
        else{
            $idealSolution[0][$row] = $min;
            $idealSolution[1][$row] = $max;
        }
        $row++;
    };
    return $idealSolution;
};

function sumMatrix($matrix){
    $row = 0;
    foreach($matrix as $arr){
        $sum[$row] = array_sum($arr);
        $row++;
    };

    return $sum;
};

function distance($weigthedMatrix, $idealSolution){
    $posDistance = $weigthedMatrix;
    $negDistance = $weigthedMatrix;

    $row = 0;
    foreach($weigthedMatrix as $arr){
        $col = 0;
        foreach($arr as $val){
            $posDistance[$row][$col] = $idealSolution[0][$col] - $val;
            $negDistance[$row][$col] = $idealSolution[1][$col] - $val;
            $col++;
        };
    $row++;
    };

    $sqrdPos = sqrd($posDistance);
    $sqrdNeg = sqrd($negDistance);

    $sumPos = sumMatrix($sqrdPos);
    $sumNeg = sumMatrix($sqrdNeg);

    $row = 0;
    foreach($sumPos as $val){
        $distance[0][$row] = sqrt($val);
        $distance[1][$row] = sqrt($sumNeg[$row]);
        $row++;
    };

    return $distance;
};

function preferenceValue($distance){
    $row = 0;

    foreach($distance[1] as $negDis){ 
        $x = $distance[0][$row] + $negDis;
        $preferenceValue[$row] = $negDis / $x;
        $row++;
    };

    return $preferenceValue;
};

function rankedList($earbuds, $preferenceValue){
    $row = 0;
    $list = array(array(), array());

    foreach($preferenceValue as $val){
        $list[0][$row] = $earbuds[$row]["Name"];
        $list[1][$row] = $val;

        $row++;
    };

    array_multisort($list[1], SORT_DESC, $list[0]);

    return $list;
};

$isAccurate = false;

function result($userInput, $earbuds){
    //AHP
    $pairwise = pairwise($userInput);
    $normalizedPairwise = normalizedPairwise($pairwise);
    $weight = weight($normalizedPairwise);
    $lambdaMax = lambdaMax($pairwise, $weight);
    $consIndex = consIndex($lambdaMax);
    $consRatio = consRatio($consIndex);

    global $isAccurate;
    $isAccurate = isAccurate($consRatio);

    //TOPSIS
    $desicionMatrix = desicionMatrix($earbuds);
    $distributedWeight = distributeWeight($weight);
    $normalizedDesicionMatrix = normalizedDesicionMatrix($desicionMatrix);
    $weightedMatrix = weightedMatrix($normalizedDesicionMatrix, $distributedWeight);
    $idealSolution = idealSolution($weightedMatrix);
    $distance = distance($weightedMatrix, $idealSolution);
    $preferenceValue = preferenceValue($distance);
    $rankedList = rankedList($earbuds, $preferenceValue);

    return $rankedList;
}

//$testInput = array(9, 8, 8, 4, 2, 1);
//$testEarbuds = query("SELECT * FROM test_data");
?>