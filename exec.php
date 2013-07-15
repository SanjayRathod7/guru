<?php
error_reporting(E_ALL);
$code= $_GET['code'];
if ( strpos($code,"<?php") !==FALSE ){

            $code=str_replace("<?php"," ",$code);
	//	print $line;
        }

if ( strpos($code,"<?") !==FALSE ){
	//print $line;
            $codeline=str_replace("<?"," ",$code);
	//	print $line;
        }
if ( strpos($code,"?>") !==FALSE ){
	//print $line;
            $code=str_replace("?>"," ",$code);
	//	print $line;
        }
if ( strpos($code,"\\\"") !==FALSE ){
	//print $line;
            $code=str_replace("\\\"","\"",$code);
	//	print $line;
        }

if ( strpos($code,"include") !==FALSE ){
	//print $line;
            $code=str_replace("include","  ",$code);
	//	print $line;
        }
        
if ( strpos($code,"include_once") !==FALSE ){
	//print $line;
            $code=str_replace("include_once","  ",$code);
	//	print $line;
        }
        
if ( strpos($code,"require") !==FALSE ){
	//print $line;
            $code=str_replace("require","  ",$code);
	//	print $line;
        }
        
if ( strpos($code,"require_once") !==FALSE ){
	//print $line;
            $code=str_replace("require_once","  ",$code);
	//	print $line;
        }

if ( strpos($code,"eval") !==FALSE ){
	//print $line;
            $code=str_replace("eval","  ",$code);
	//	print $line;
        }
        
if ( strpos($code,"file_put_contents") !==FALSE ){
	//print $line;
            $code=str_replace("file_put_contents","  ",$code);
	//	print $line;
        }
        
   gc_collect_cycles();     
//echo $code;
 ob_start();//for start save code in buffer
  

  set_time_limit(10);
  try {
  eval($code);//evaluate string as php code
  } catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}
 
$script_output = ob_get_contents();//get the content from buffer
ob_end_clean();//for clean buffer

if (preg_match("/Parse error/",$script_output)) {
$script_output =substr($script_output,0,strpos($script_output,"in ")); 

}
//Change By Krishna Rungta
//Code to remove sensitive information when warning is issued
if (preg_match("/Warning/",$script_output)) {
$script_output =substr($script_output,0,strpos($script_output,"in ")); 

}


//date_default_timezone_set('UTC');the date is now set in php.ini This line of code is not required
$d=date("j m Y H i s");
$myfile=$d.".txt";
file_put_contents($myfile,"code: ",FILE_APPEND);
file_put_contents($myfile,$code."\n",FILE_APPEND);
file_put_contents($myfile,"output ",FILE_APPEND);
file_put_contents($myfile,$script_output,FILE_APPEND);
header('Content-Type: application/jsonp');
echo $_GET['callback']."(".json_encode($script_output).");";
?>