<?php

$url = "http://feeds.icington.se/?feed=rss2&cat=3";
$rss = simplexml_load_file($url);

if($rss){

    $items = $rss->channel->item;
	$counter=0;
    foreach($items as $item){

		$counter++;

        $title = $item->title;
        $image = $item->image;
        $link = $item->link;
        $published_on = $item->pubDate;
        $description = $item->description;

        // bringing in to array <content:encoded> items from SimpleXMLElement Object()
        $content = xmlObjToArr($item->children('content', true)->encoded);

		echo'<div class="post-item-container '.$counter.'">
				<div class="offers-featured-image-wrapper" data-id="'.$counter.'">
					<a target="_blank" href="' . $link . '">'. $content['text'] .'</a>
				</div>
				
				<div class="offers-info">
					<h4><a target="_blank" href="' . $link . '">' .$title. '</a></h4>
					<p>' .$description . '</p>
					<span>Price: </span><b style="font-size: 14px;">$$$</b><a href="#" class="btn-purchase">Purchase</a>
				</div>
			 </div>';
 		
    }
}

?>

<script type="text/javascript">
	jQuery(function(){
		jQuery('.offers-featured-image-wrapper').each(function(i){
			var check=jQuery(this).find('img').length;
			
			if(check==0){
				var id = jQuery(this).attr('data-id');
				jQuery('.'+id+' .offers-featured-image-wrapper a').html('<img class= "def-img" src="./images/newdashboard/pc-dummy.png" style="width:102px; height: 102px;"/>');
			}
			
		});
	});
</script>