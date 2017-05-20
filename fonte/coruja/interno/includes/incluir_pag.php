<?php

	 if ($_GET["pg"]=="") {
	 
		include "view/home/home.php";
							
		 }else{
								
							
		include $_GET["pg"];
							
	 }	


?>