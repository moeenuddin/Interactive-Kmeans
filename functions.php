<?php

/*

functions for experiments

*/


function GenerateData(){
	
	$data = array();

	for($i=0; $i<10000; $i++){

		$data[$i] = array(rand(0,6),rand(0,4),rand(0,4),rand(0,6),rand(0,3));
  
	}

return $data;	
	
}


function GenerateDataNPoints($iSize=1000){
	
	$data = array();

	for($i=0; $i<$iSize; $i++){

		$data[$i] = array(rand(0,6),rand(0,4),rand(0,4),rand(0,6),rand(0,3));
  
	}

return $data;	
	
}


function GenerateDataNPointsWithNTupleSize($iSize=500,$iTupleSize=3){
	
	
	$data = array();

	for($i=0; $i<$iSize; $i++){

		for($j=0; $j< $iTupleSize; $j++){
			
			$data[$i][]= rand(0,6);
		}
		//$data[$i] = array(rand(0,6),rand(0,4),rand(0,4),rand(0,6),rand(0,3));
  
	}

return $data;	
	
}


function GenerateDataNPointsWithNTupleSizeAndRandomRangeSize($iSize=500,$iTupleSize=3,$iMinRange=0,$iMaxRange=10){
	
	
	$data = array();

	for($i=0; $i<$iSize; $i++){

		for($j=0; $j< $iTupleSize; $j++){
			
			$data[$i][]= rand($iMinRange,$iMaxRange);
		}
		//$data[$i] = array(rand(0,6),rand(0,4),rand(0,4),rand(0,6),rand(0,3));
  
	}

return $data;	
	
	
	
}


function RandomKSeedSelection($data,$k=1){
	
	$total_rows = count($data);

	$random_selection = array();

	// select initial k random seed!!
	while($k != count($random_selection)) {

		$r = rand(0,$total_rows-1);
	
		if(!in_array($r,$random_selection)){
			$random_selection[] = $r;
		}
	
	}
	return $random_selection;
}



function clusterAssignment($random,$data,$criteria){
	 
	 	$Clusters = array();

		foreach($random  as $i => $row){
		
		
		$centroid = $row; //$data[$row];
		$Clusters[$i] = array();

			foreach($data as $a => $b)
			{
			
				if($row == $a) continue;
			
			//print_r($b);
			$d = distance($centroid,$b);
			//echo $d." is the distance: \n";
				if($d <= $criteria)
				{
				if(count($Clusters) == 0)
					$Clusters[$i][] = $centroid;
				
				$Clusters[$i][] = $b;
				}		
			}

		//print_r($centroid);

		}
		
		return $Clusters;
	 
	 }
	 
	 
	 function updateCentroid($Clusters){
	 	
	 	$random_selection = array();
	 	
	 	foreach($Clusters as $i => $arr)
		{
	
		//print_r(CalculateNewCentroid($arr));
		//die("First new centroid");
		
			//echo $i." has  count ".count($arr)." \n";
		
			$random_selection[$i] = CalculateNewCentroid($arr);
	
		}
		
	 	return $random_selection;
	 	
	 }
	 
	 function CalculateNewCentroid($arr)
{
	$avg= array();
	$newCentroid = array();
	$pointsInCluster = 0;
	
	foreach($arr as $i => $v){
		//$avg[] =0
		foreach($v as $j => $js){
		
			@$avg[$j]+= $js ;
		
		}
	 $pointsInCluster+=1;
	
	}
	
	foreach($avg as $v){
	
		//echo ($v/$pointsInCluster)+", \n";
	    $newCentroid[]=$v/$pointsInCluster;
	}
	
	//print_r($avg);
	//die("... Die");
 
 return $newCentroid;

}




function distance($A, $B){
 
 $value =0;
 
 for($i =0 ; $i<count($A);$i++){
 	
 	$value+=abs($A[$i]-$B[$i]);
 }
 
 return sqrt($value);
 // return sqrt(abs($A[0]-$B[0]) + abs($A[1]-$B[1]) + abs($A[2]-$B[2]));
    
  
}



function convergenceTest($lastCentroids,$currentCentroids){

	$minus = 0;

	foreach($lastCentroids as $i => $v){
		
		foreach($currentCentroids[$i] as $j => $f){
		
			$minus+= abs($v[$j]-$f);
		
		}
		
	}

	return $minus;

}


function InitialClusters($seeds,$data,$criteria){
	
	$Clusters = array();

	foreach($seeds  as $i => $row){
		
		
		$centroid = $data[$row];
		$Clusters[$i] = array();

		foreach($data as $a => $b)
		{
			
			if($row == $a) continue;
			
			//print_r($b);
			$d = distance($centroid,$b);
			//echo $d+" is the distance: \n";
			if($d <= $criteria)
			{
				if(count($Clusters) == 0)
					$Clusters[$i][] = $centroid;
				
				$Clusters[$i][] = $b;
			}		
		}

		//print_r($centroid);

	}
	
	//print_r($Clusters); 

	
	return $Clusters;
	
}
/*

PerformNIterationBeforeConvergence 
- max interger maximum number of possible iteration
- n  a strict criteria before thee convergenceTest is applied likeafter 10 iterations, we want to check the convergence.
- randomData  is random selected data or any data like iris etc

*/

function PerformNIterationBeforeConvergence($max=200,$n=10,$randomData,$centriods,$criteria){
	
	for($j=0; $j<=$max;$j++){
	
		echo "iteration ....".$j." \n";
		
		$lastCentroids = $centriods;
		
		$c = clusterAssignment( $centriods, $randomData,$criteria);
		$centriods = updateCentroid($c);
		echo "New Random \n";
		print_r($centriods);
		
		if($j > $n){
			$selections = convergenceTest($lastCentroids,$centriods);
			
			echo "Selection minus: ".$selections." \n";
			if($selections  < 5){
				print_r($c); // output
				die("Converged!!!...");
			
				
			}
			
		}
	
	}
	
}
/*

LoadIrisData - Loads the iris data

*/

function LoadIrisData($path){
	
	$lines = file($path);
	$data = array();
	foreach($lines as $i => $vm){
		if($i > 0)
			$data[]=array_slice(explode(",",$vm),0,4);
	}

return $data;
	
}

	 


?>