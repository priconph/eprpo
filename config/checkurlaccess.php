 <?php  
 
 	
 	$url = @$_SERVER['HTTP_REFERER'];
 	$main_dir = str_replace(' ','%20',str_replace($_SERVER['DOCUMENT_ROOT'].'/', '', $_SERVER['SCRIPT_FILENAME']));
 	$main_dir = substr($main_dir, 0,strpos($main_dir, '/')); 
 	$url_allowed = 'http://'.$_SERVER['HTTP_HOST'].'/'.$main_dir.'/Application/main.php';

 	$exist = strpos(strtoupper($url), strtoupper($url_allowed));
    if($exist<0){
       header("location:../index.php");
    }
	

?>
