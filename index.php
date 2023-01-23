<?php
require 'functions.php';
$earbuds = query("SELECT * FROM earbuds_data");
?>

<style>
    <?php include 'styles.css'; ?>
</style>

<!DOCTYPE html>
<html>
<head>
    <title>DATTBuds</title>
</head>
<body>

    <header>  
        <h1>DATTBuds</h1>
        <h2>DSS with AHP & TOPSIS for TWS earBuds</h2>
    </header>

    <div class="main">
    <h1>What are you looking for?</h1>

    <form method="get" action="#result">
        <div class="slider">
            <label>Design</label>
            <input type="range" min="1" max="9" value="1" name="design"  
            oninput="designValue.innerText = this.value">
            <p id="designValue">1</p>
        </div>
        <div class="slider">
            <label>Sound</label>
            <input type="range" min="1" max="9" value="1" name="sound"  
            oninput="soundValue.innerText = this.value">
            <p id="soundValue">1</p>
        </div>
        <div class="slider">
            <label>Power</label>
            <input type="range" min="1" max="9" value="1" name="power"  
            oninput="powerValue.innerText = this.value">
            <p id="powerValue">1</p>
        </div>
        <div class="slider">
            <label>Connectivity</label>
            <input type="range" min="1" max="9" value="1" name="connectivity"  
            oninput="connValue.innerText = this.value">
            <p id="connValue">1</p>
        </div>
        <div class="slider">
            <label>Microphone</label>
            <input type="range" min="1" max="9" value="1" name="microphone"  
            oninput="micValue.innerText = this.value">
            <p id="micValue">1</p>
        </div>
        <div class="slider">
            <label>Price</label>
            <input type="range" min="1" max="9" value="1" name="price"  
            oninput="priceValue.innerText = this.value">
            <p id="priceValue">1</p>
        </div>

        <input href="#result" type="submit" value="See Result">
    </form>
    </div>

    <?php
        $userInput[0] = $_GET["design"];
        $userInput[1] = $_GET["sound"];
        $userInput[2] = $_GET["power"];
        $userInput[3] = $_GET["connectivity"];
        $userInput[4] = $_GET["microphone"];
        $userInput[5] = $_GET["price"];
        $rankedList = result($userInput, $earbuds);
    ?>

    <?php if($isAccurate == true): ?>

    <h1 id="result">Top Results</h1>

    <ol>
        <?php
        $row = 1;
        foreach($rankedList[0] as $result):
        ?>
        <li>
            <?php
            echo $result;
            $row++;
            if($row == 11){
                echo $isAccurate;
                break;
            }

            var_dump($rankedList);
            ?>
        </li>
         <?php endforeach; ?>
    </ol>

    <?php endif; ?>

    <?php if($isAccurate == false): ?>

    <h1 id="result">Please input another value</h1>

    <?php endif; ?>

</body>
</html>

