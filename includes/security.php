<?php
	if(empty($_SESSION['id'])){
		header('Location: ?page=error');
	}