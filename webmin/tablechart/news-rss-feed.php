<?php

$url = "http://feeds.icington.se/?feed=rss2&cat=2";
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

		echo '<div class="post-item-container '.$counter.'">
                <div class="post-description">
                    <b style="font-size: 14px;"><a target="_blank" href="' . $link . '">' .$title. '</a></b>
                    <i style="margin-bottom: 10px;display: block;">' . $published_on . '</i>
                    <p>' .$description . '</p>
                </div>
                
                <div class="post-featured-image-wrapper" data-id="'.$counter.'"><a target="_blank" href="' . $link . '">'
                    . $content['text'] .
                '</a></div>
            </div>';
 		
    }
}


function xmlObjToArr($obj) {
        $namespace = $obj->getDocNamespaces(true);
        $namespace[NULL] = NULL;

        $children = array();
        $attributes = array();
        $name = strtolower((string)$obj->getName());

        $text = trim((string)$obj);
        if( strlen($text) <= 0 ) {
            $text = NULL;
        }

        // get info for all namespaces
        if(is_object($obj)) {
            foreach( $namespace as $ns=>$nsUrl ) {
                // atributes
                $objAttributes = $obj->attributes($ns, true);
                foreach( $objAttributes as $attributeName => $attributeValue ) {
                    $attribName = strtolower(trim((string)$attributeName));
                    $attribVal = trim((string)$attributeValue);
                    if (!empty($ns)) {
                        $attribName = $ns . ':' . $attribName;
                    }
                    $attributes[$attribName] = $attribVal;
                }

                // children
                $objChildren = $obj->children($ns, true);
                foreach( $objChildren as $childName=>$child ) {
                    $childName = strtolower((string)$childName);
                    if( !empty($ns) ) {
                        $childName = $ns.':'.$childName;
                    }
                    $children[$childName][] = xmlObjToArr($child);
                }
            }
        }

        return array(
            'name'=>$name,
            'attributes'=>$attributes,
			'text'=>$text,
            'children'=>$children
        );
    }


?>

<script type="text/javascript">
	jQuery(function(){
		jQuery('.post-featured-image-wrapper').each(function(i){
			var check=jQuery(this).find('img').length;
			
			if(check==0){
				var id = jQuery(this).attr('data-id');
				jQuery('.'+id+' .post-description').css('width','100%');
				jQuery(this).remove();
			}
			
		});
	});
</script>