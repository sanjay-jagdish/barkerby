<?php include_once('redirect.php'); ?>
<div class="page dashboard-page">
	<div class="page-header">
    	<div class="page-header-left">
        	<!--<span>All Features Summary</span>-->
            <h2>
            	<?php
                	if(isset($_GET['page'])){
						if(isset($_GET['subpage'])){
							echo ucwords(removeDashTitle($_GET['subpage']));
						}
						else{
							echo ucwords(removeDashTitle($_GET['page']));
						}
					}
				?>
            </h2>
        </div>
        <div class="page-header-right">
        	<!--<input type="text" placeholder="To search type and hit enter..." class="searchbox">-->
        </div>
    </div>
    
     <div class="clear"></div>
    
    <div class="page-content">
    	<div class="left-dashboard">
        	<h2>Welcome to Garcon!</h2>
            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
        </div>
        <div class="right-dashboard"></div>
    </div>
    
</div>