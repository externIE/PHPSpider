<?php
	class DocumentFrame{
		var $mTitle;//标题
		var $mDTitle;//详细标题

		var $mDate;//日期
		var $mTime;//时间
		var $mAddress;//地点
		var $mCampus;//校区

		var $mSpeaker;//主讲人
		var $mSpeakerProfile;//主讲人简介
		var $mSpeakerImgSrc;//主讲人照片地址

		var $mOrganizer;//主办方
		var $mCoOrganizer;//协办方

		var $mCategory;//讲座类别

		var $mAuthor;//文章作者
		var $mEidtorInCharge;//责任编辑

		var $mSpeachContent;//演讲内容

		// var $doc;//全文

		function __construct(){

		}
		public function setTitle($title){
			$this->mTitle = $title;
		}
		public function setDTitle($dtitle){
			$this->mDTitle = $dtitle;
		}

		public function setDate($date){
			$this->mDate = $date;
		}
		public function setTime($time){
			$this->mTime = $time;
		}
		public function setAddress($address){
			$this->mAddress = $address;
		}
		public function setCampus($campus){
			$this->mCampus = $campus;
		}

		public function setSpeaker($speaker){
			$this->mSpeaker = $speaker;
		}
		public function setSpeakerProfile($speakerProfile){
			$this->mSpeakerProfile = $speakerProfile;
		}
		public function setSpeakerImgSrc($src){
			$this->mSpeakerImgSrc = $src;
		}

		public function setOrganizer($organizer){
			$this->mOrganizer = $organizer;
		}
		public function setCoOrganizer($coorganizer){
			$this->mCoOrganizer = $coorganizer;
		}

		public function setAuthor($author){
			$this->mAuthor = $author;
		}
		public function setEidtorInCharge($edic){
			$this->mEidtorInCharge = $edic;
		}

		public function setCategory($category){
			$this->mCategory = $category;
		}

		public function setSpeachContent($content){
			$this->mSpeachContent = $content;
		}

		private function buildDocument(){
			$doc  = "";
			$doc .= "---\n";
			$doc .= "layout: 			post\n";
			$doc .= "title:       	  \"$this->mTitle\"\n";
			$doc .= "dtitle:      	  \"$this->mDTitle\"\n";
			$doc .= "time: 		  	  \"$this->mTime\"\n";
			$doc .= "address:	  	  \"$this->mAddress\"\n";
			$doc .= "campus:	  	  \"$this->mCampus\"\n";
			$doc .= "speaker:	   	  \"$this->mSpeaker\"\n";
			$doc .= "speaker-profile: \"$this->mSpeakerProfile\"\n";
			$doc .= "speaker-img:	  \"$this->mSpeakerImgSrc\"\n";
			$doc .= "organizer:		  \"$this->mOrganizer\"\n";
			$doc .= "co-organizer:	  \"$this->mCoOrganizer\"\n";
			$doc .= "category:		  \"$this->mCategory\"\n";
			$doc .= "author:		  \"$this->mAuthor\"\n";
			$doc .= "editorInCharge:  \"$this->mEidtorInCharge\"\n";
			$doc .= "---\n";
			$doc .= "$this->mSpeachContent\n";
			return $doc;
		}

		public function getDoc(){
			return $this->buildDocument();
		}

		public function saveToFile($basePath){
			$doc   = $this->buildDocument();
			$path  = $basePath;
			$path .= "/".$this->mDate."-".$this->mTitle.".markdown";
			$file  = fopen($path,"w");
			if($file){
				fwrite($file,$doc);
				fclose($file);
				echo "保存成功\n";
			}else{
				echo "保存失败\n";
			}
			
		}
	}
?>