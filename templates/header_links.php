<?php 
	
	//check if css for current page exists if so include it
    $exploded=array_reverse(explode("/", $path));
    $exploded_page = explode('.', $exploded[0]);
    $file = $exploded_page[0];

    if(file_exists($root.'/hot/css/'.$file.'.css')) {
    	$include_css = $file . ".css";
    }

    if(file_exists($root.'/hot/js/'.$file.'.js')) {
    	$include_js = $file . ".js";
    }

?>

<link href="/hot/css/style.css" rel="stylesheet" type="text/css"  media="all" />
<link href="/hot/css/main.css" rel="stylesheet" type="text/css"  media="all" />
<script src="/hot/js/jquery-2.0.1.min.js" type="text/javascript"></script>
<?php if(!empty($include_css)):?>
	<link href="/hot/css/<?php echo $include_css;?>" rel="stylesheet" type="text/css" medial="all" />
<?php endif;?>

<?php if(!empty($include_js)):?>
	<script src="/hot/js/<?php echo $include_js?>" type="text/javascript"></script>
<?php endif;?>