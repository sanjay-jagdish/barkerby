<?php session_start(); 

	if(isset($_GET['page'])){
		
		if($_GET['page']=='controlpanel'){
			if($_SESSION['login']['type']==1)
				include 'tablechart/index.php';
			else{
				if(isset($_GET['overview']) || $nav_overview==1)
					include 'tablechart/index.php';
				else	
					include $_GET['page'].'.php';
			}
		}
		else if($_GET['page']=='dashboard'){
				include 'tablechart/dashboard.php';
		}
		else if($_GET['page']=='dashboardold'){
				include 'tablechart/dashboardold.php';
		}
		else if($_GET['page']=='scheduler-chart'){
			include 'tablechart/scheduler-chart.php';
		}
		else{
			if(isset($_GET['subpage'])){
				//getting the child page
				include $_GET['subpage'].'.php';
			}
			else{
				//getting the parent page
				include $_GET['page'].'.php';
			}
		}
		
	}
	else{
		include 'dashboard.php';
	}
?>