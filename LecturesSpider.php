<?php
	require "simple_html_dom.php";
	require "PageParser.php";
	require "Item.php";
	date_default_timezone_set('prc');
	spider();
	function spider(){
		$mainURL = "http://news.cqu.edu.cn/newsv2/info-24.html";//校园网新闻讲座首页
		$items = getAllItemsURL($mainURL);
		crawlerAllItemPages($items);
		// testFunc();
	}
	function testFunc(){//测试代码
		echo time();
	}
	function testFunc2($test){
		$test--;
		$test = null;
		echo "func2:$test\n";
	}
	function getAllItemsURL($mainURL){
		$html = new simple_html_dom();
		$html->load_file($mainURL);
		$itemsURL = array();
		do{
			$itemsURL = array_merge($itemsURL,getPageItemsURL($html));
		}while(nextPage($html));
		$html->clear();
		return $itemsURL;
	}
	function nextPage($html){
		$nextPageE = $html->find('a.a1',2);
		if (isset($nextPageE->prev_sibling()->href)) {
			//还可以继续下一页
			$html->clear();
			$html->load_file("http://news.cqu.edu.cn/newsv2/".$nextPageE->href);
			return true;
		}else{
			return false;
		}	}
	function getPageItemsURL($html){
		static $pageNum = 0;
		$pageNum++;
		echo "\n\n=====================================\n";
		$titleElements = $html->find('div.item div.title');
		$pageItems = array();
		foreach ($titleElements as $key => $title) {
			if(getPostDate($title->next_sibling())<=time()){
				echo"\n\033[31m过期讲座：放弃！！\033[0m  ";
				echo $title->last_child()->href."  ".getCategory($title);
				continue;
			}
			$item = new Item($title->last_child()->href,getCategory($title));
			$pageItems[$key] = $item;
			echo $pageItems[$key];
			echo "\n";
		}
		echo "\n";
		echo "第";
		echo $pageNum;
		echo "页";
		echo "页面item数量:";
		echo count($pageItems);
		echo "\n=====================================\n\n";
		return $pageItems;
	}
	function getCategory($title){
		$title->first_child()->href;
		if (stristr($title->first_child()->href,"?tid")) {
			return $title->first_child()->plaintext;
		}else{
			return "其它";
		}
	}
	function getPostDate($minfo){
		$text  = $minfo->children(1)->plaintext;
		$text  = strip_tags($text);//去掉HTML&&PHP代码
		$arr   = explode(" ",$text);
		$text  = str_replace(array("讲座时间:","年","月","日"), array("","-","-",""), $arr[0]);
		$text .= str_replace(array("时","分"), array(":",":00"), $arr[1]);
		return strtotime($text);
	}
	function crawlerAllItemPages($items){
		foreach ($items as $key => $value) {
			_crawlerItemPage($value->getURL(),$value->getCategory());
		}

	}
	function _crawlerItemPage($url,$category){
		$page = new PageParser($url,$category);
		echo "\n";
		echo $page;
	}
?>