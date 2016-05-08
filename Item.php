<?php
	class Item{
		var $url;
		var $category;
		function __construct($url,$category){
			$this->url 		= $url;
			$this->category = $category;
		}
		function getURL(){
			return $this->url;
		}
		function getCategory(){
			return $this->category;
		}
		function __tostring(){
			return "URL:".$this->url." 类别:".$this->category;
		}
	}
?>