<?php
	require "DocumentFrame.php";
	class PageParser{
		//提取页面的内容，并填到DocumentFrame里面去
		var $docFrame;
		var $html;
		var $category;
		function __construct($url,$category){
			$this->html = new simple_html_dom();
			$this->html->load_file($url);
			$this->category = $category;
			$docFrame = new DocumentFrame();
			$docFrame->setTitle($this->getTitle());
			$docFrame->setDTitle($this->getDTitle());
			$docFrame->setDate($this->getDate());
			$docFrame->setTime($this->getTime());
			$docFrame->setAddress($this->getAddress());
			$docFrame->setCampus($this->getCampus());
			$docFrame->setSpeaker($this->getSpeaker());
			$docFrame->setSpeakerProfile($this->getSpeakerProfile());
			$docFrame->setSpeakerImgSrc($this->getSpeakerImgSrc());
			$docFrame->setOrganizer($this->getOrganizer());
			$docFrame->setCoOrganizer($this->getCoOrganizer());
			$docFrame->setAuthor($this->getAuthor());
			$docFrame->setEidtorInCharge($this->getEditorInCharge());
			$docFrame->setSpeachContent($this->getSpeachContent());
			$docFrame->setCategory($this->category);
			$this->docFrame = $docFrame;
		}
		function __destruct(){
			echo "析构Item分析对象\n";
		}
		public function getDoc(){
			return $this->docFrame->getDoc();
		}
		public function __tostring(){
			return $this->docFrame->getDoc();
		}
		public function saveToFile($basePath){
			$this->docFrame->saveToFile($basePath);
		}
		private function getTitle(){
			$title  = trim($this->html->find("div.acontent h3",0)->innertext);
			$dtitle = trim($this->html->find("h1.dtitle",0)->innertext);
			if ($title==""||!$title) {
				if ($dtitle==""||!$dtitle) {
					return "无题";
				}else{
					return $dtitle;
				}
			}
			return $title;
		}
		private function getDTitle(){
			$dtitle = trim($this->html->find("h1.dtitle",0)->innertext);
			$title  = trim($this->html->find("div.acontent h3",0)->innertext);
			if ($dtitle==""||!$dtitle) {
				if ($title==""||!$title) {
					return "无题";
				}else{
					return $title;
				}
			}
			return $dtitle;
		}
		private function getDate(){
			// $strTime = $this->html->find("div.dinfo p",0)->plaintext;
			// $strTime = strip_tags($strTime);//去除HTML&&PHP代码
			// $arrInfo = explode(" ",$strTime);
			$formatDate =  trim($this->findInfo("div.dinfo p",0,2));
			$formatDate = str_replace(array("讲座时间",":","年","月","日"), array("","","-","-",""), $formatDate);
			return $formatDate;
		}
		private function getTime(){
			// $strTime = $this->html->find("div.dinfo p",0)->plaintext;
			// $strTime = strip_tags($strTime);//去除HTML&&PHP代码
			// $arrInfo = explode(" ",$strTime);
			return trim($this->findInfo("div.dinfo p",0,3));
		}
		private function getAddress(){
			return trim($this->findInfo("div.dinfo p",1,2));
		}
		private function getCampus(){
			$address = $this->getAddress();
			$campus = "undefined";
			if ($this->checkStrInStr($address,"A")) {
				$campus = "A";
			}
			if ($this->checkStrInStr($address,"B")) {
				$campus = "B";
			}
			if ($this->checkStrInStr($address,"C")) {
				$campus = "C";
			}
			if ($this->checkStrInStr($address,"D")) {
				$campus = "D";
			}
			if ($this->checkStrInStr($address,"虎溪")) {
				$campus = "D";
			}
			return $campus;
		}
		private function getSpeaker(){
			$arr = $this->findInfoArr("div.acontent p",0);
			$this->array_remove($arr,0);
			$this->array_remove($arr,0);
			return trim(join(" ",$arr));
		}
		private function getSpeakerProfile(){
			$arrP =  $this->html->find("div.acontent p");
			foreach ($arrP as $key => $value) {
				if ($this->checkStrInStr($value->plaintext,"主讲人简介")) {
					//找到主讲人简介标签
					if ($value->next_sibling()&&$value->next_sibling()->tag=="p") {
						return trim(strip_tags($value->next_sibling()->plaintext));
					}else{
						echo "erro!!$this->getTitle():找不到主讲人简介\n";
						return false;
					}
				}
			}
		}
		private function getSpeakerImgSrc(){
			$eImg = $this->html->find("div.acontent img",0);
			if ($eImg) {
				return $eImg->src;
			}else{
				return "http://externie.com/nophoto.png";
			}
		}

		private function getOrganizer(){
			$edinfo = $this->html->find("div.dinfo",0);
			$arrP = $edinfo->find("p");
			if (count($arrP)<=2) {
				return "";
			}else{
				$text = strip_tags($arrP[2]->plaintext);//去掉HTML&&PHP代码
				$arr  = explode(" ",$text);
				$this->array_remove($arr,0);
				$this->array_remove($arr,0);
				return trim(join(" ",$arr));
			}
		}
		private function getCoOrganizer(){
			$edinfo = $this->html->find("div.dinfo",0);
			$arrP = $edinfo->find("p");
			if (count($arrP)<=3) {
				return "";
			}else{
				$text = strip_tags($arrP[3]->plaintext);//去掉HTML&&PHP代码
				$arr  = explode(" ",$text);
				$this->array_remove($arr,0);
				$this->array_remove($arr,0);
				return trim(join(" ",$arr));
			}
		}
		private function getSpeachContent(){
			$allContent = $this->html->find("div.acontent",0);
			$strContent = "";
			foreach ($allContent->children as $key => $value) {
			 	if ($key >= 2) {
			 		if ($value->tag == "hr") {
			 			break;
			 		}
			 		$strContent .= $value->outertext;
			 	}
			}
			$strContent = strip_tags($strContent,"<p>");
			$strContent = str_replace("</p>","\n",$strContent);
			$strContent = str_replace("<p>","  ",$strContent);
			$strContent = str_replace("&nbsp;"," ",$strContent);
			return trim($strContent);

			// $text = strip_tags($)
		}

		private function getAuthor(){
			return trim($this->findInfo_ex("div.dinfoa p",0));
		}
		private function getEditorInCharge(){
			return trim($this->findInfo_ex("div.dinfoa p",1));
		}

		private function findInfo($str,$index1,$index2){
			$text =  $this->html->find($str,$index1)->plaintext;
			$text = strip_tags($text);//去掉HTML&&PHP代码
			$arr  = explode(" ",$text);
			return $arr[$index2];
		}
		private function findInfoArr($str,$index1){
			$text =  $this->html->find($str,$index1)->plaintext;
			$text = strip_tags($text);//去掉HTML&&PHP代码
			$arr  = explode(" ",$text);
			return $arr;
		}
		private function findInfo_ex($str,$index1){
			$arr = $this->findInfoArr($str,$index1);
			$this->array_remove($arr,0);
			$this->array_remove($arr,0);
			return join(" ",$arr);
		}
		private function checkStrInStr($string,$str){
			$in = stristr($string,$str);
			if ($in) {
				return true;
			}else{
				return false;
			}
		}
		private function array_remove(&$arr, $offset) 
		{ 
			array_splice($arr, $offset, 1); 
		} 
	}
?>