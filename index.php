<?php

include "configure.php";
include "functions.php";


$k= $config["NumberOfClusters"];
$criteria = $config["Distance_metric"];

$randomData = GenerateDataNPointsWithNTupleSizeAndRandomRangeSize(200,3,0,3);


//var_dump($randomData);

/*

Random select the k initial seeds or clusters centers

*/

$seeds = RandomKSeedSelection($randomData,$k);


var_dump($seeds);

// Assignment Step: calculate the distance

	$clusters = InitialClusters($seeds,$randomData,$criteria);

var_dump($clusters);

//update the centeriods

$centriods = updateCentroid($clusters);

var_dump($centriods);


PerformNIterationBeforeConvergence($max=200,$n=10,$randomData,$centriods,$criteria);
	

// N iteration


?>