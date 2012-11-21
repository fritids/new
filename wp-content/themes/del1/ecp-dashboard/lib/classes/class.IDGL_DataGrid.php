<?php
class IDGL_DataGrid{
	private $pageNum=20;
	private $query;
	private $filter;
	private $dg_params;
	public function IDGL_DataGrid($dg_params){
		$this->dg_params=$dg_params;
	}
	public function setQuery($q){
		$this->query=$q;
	}
	public function addFilter($filter){
		$this->filter=$filter;
	}
	public function render(){
		$outStr="";
		$outStr.=$this->getPager();
		$outStr.=$this->getFilter();
		$outStr.='<div class="clear"></div>
    			  <table cellspacing="0" class="widefat page fixed">';
		$outStr.=$this->getBody();
		$outStr.='</table>';
		$outStr.=$this->getPager();
		return $outStr;
	}
	private function getHeader($object){
		$outStr='<thead><tr>';
	    foreach($object as $rowName=>$niceName){
			$outStr.='<th style="" class="manage-column" scope="col">'.$rowName.'</th>';
	    }
	    if(is_array($this->dg_params)){
	    	$outStr.='<th style="" class="manage-column" scope="col">actions</th>';
	    }
	    $outStr.='</tr></thead>';
	    return $outStr;
	}
	private function getBody(){
		$outStr="";
		global $wpdb;
		
		$q=$this->query;
		
		if($this->filter!=""){
			$q.=" WHERE ".$this->filter;
		}
		
		$pg=new Paginator($q,$_GET["pageid"],$this->pageNum);
		$objects = $wpdb->get_results($q." ".$pg->getLimit(), ARRAY_A);
		echo $q." ".$pg->getLimit();
		$outStr.=$this->getHeader($objects[0]);
		foreach($objects as $object){
			$outStr.="<tr>";
			foreach($object as $rowName=>$niceName){
				 $outStr.="<td>".$object[$rowName]."</td>";	
			}
			
			if(is_array($this->dg_params)){
				$outStr.="<td style='padding:10px;'>";
				foreach($this->dg_params as $action=>$param){
					$param=str_replace("{ID}",$object["ID"],$param);
					$outStr.="<a href='$param' class='button-secondary action'>$action</a> ";
				}
				$outStr.="</td>";
			}
			
			$outStr.="</tr>";
		}
		return $outStr;
	}
	private function getFooter(){
		
	}
	private function getPager(){
		$pg=new Paginator($this->query,$_GET["pageid"],$this->pageNum);
		//$url= preg_replace('/&pageid=+/', "&", Util::curPageURL());
		$url=array();
		foreach($_GET as $key=>$val){
			if($key!="pageid"){
				$url[]=$key."=".$val;
			}
		}
		$url=implode($url,"&");;
		$url_part=explode("?",Util::curPageURL());
		$url=$url_part[0]."?".$url;
	 	$paging=$pg->getPages($url."&pageid={i}");
		$outStr='
			<div class="tablenav">
        		<div class="tablenav-pages">
            		<span class="displaying-num">Displaying '.$pg->getStartRecords().' - '.$pg->getEndRecords().' step of '.$pg->getRecordsCount().'</span>
            	'.$paging.'
            		</div>
        		<br class="clear"/>
    		</div>
		';
		return $outStr;
	}
	
	private function getFilter(){
		$outStr='
			<ul class="subsubsub">
		        <li>
		            <a class="current" href="">All <span class="count">(x)</span></a>
		            |
		        </li>
		        <li>
		            <a href="admin.php?page=AzEventCalendar/AzEventCalendar.php&filter=pending">Online Students <span class="count">()</span></a>
					|
		        </li>
				 <li>
		            <a href="admin.php?page=AzEventCalendar/AzEventCalendar.php&filter=approved">Live Students <span class="count">()</span></a>
		        	|
		        </li>
		        <li>
		            <a href="admin.php?page=AzEventCalendar/AzEventCalendar.php&filter=upcoming">School Students <span class="count">()</span></a>
		        </li>
		    </ul>
		';
		return $outStr;
	}
}


class Paginator{
	private $query;
	private $where;
	private $order;
	private $recordsPerPage;
	private $currentPage;
	private $record_count;
	public function Paginator($query,$currentPage=0,$recordsPerPage=3,$where="",$order=""){
		$this->query=$query;
		$this->currentPage=$currentPage;
		/*if($where!=""){
			$this->where=" WHERE ".$where;
		}
		if($order!=""){
			$this->order=" ORDER BY ".$order;
		}*/
		$this->recordsPerPage=$recordsPerPage;
	}
	
	public function getPages($url){
		global $wpdb;
		$q=$this->query;
		
		$q= preg_replace('/SELECT [*] FROM/', "SELECT COUNT(*) FROM", $q);
		
		$this->record_count=$record_count = $wpdb->get_var($wpdb->prepare($q));
		//echo $q."<br/>";

		$outStr="<div class='pageNav'>";
		$iterNo=ceil($record_count/$this->recordsPerPage);
		if($this->currentPage!=0){
			
			$first=str_replace("{i}",0,$url);
			$prev=max($this->currentPage-1,0);
			$prev=str_replace("{i}",$prev,$url);
			
			$outStr.= "<a href='$first'>&laquo; First</a>";
			$outStr.= "<a href='$prev'>&laquo;</a>";
		}
		$bottom=max($this->currentPage-4,0);
		$top=min($bottom+8,$iterNo);
		for($i=$bottom;$i<$top;$i++){
			if($this->currentPage==$i){
				$outStr.= "<span class='page-numbers current'>".($i+1)."</span>";
			}else{
				$newurl=str_replace("{i}",$i,$url);
				$outStr.= "<a href='$newurl'>".($i+1)."</a>";
			}
		}
		if($this->currentPage!=$iterNo-1){
			$last=str_replace("{i}",$iterNo-1,$url);
			$next=min($this->currentPage+1,$iterNo);
			$next=str_replace("{i}",$next,$url);
			//$outStr.= "<span class='extend'>&nbsp;...&nbsp;</span>";
			$outStr.= "<a href='$next'>&raquo;</a>";
			$outStr.= "<a href='$last'>Last &raquo;</a>";
		}
		$outStr.= "</div>";
		return $outStr;
	}
	
	public function getLimit(){
		return " LIMIT ".$this->currentPage*$this->recordsPerPage.",".$this->recordsPerPage;
	}
	public function getCurrentPageNum(){
		return $this->currentPage+1;
	}
	public function getRecordsperPage(){
		return $this->recordsPerPage;
	}
	public function getStartRecords(){
		return $this->getCurrentPageNum()*$this->recordsPerPage;
	}
	public function getEndRecords(){
		return $this->getCurrentPageNum()*($this->recordsPerPage)+$this->recordsPerPage;
	}
	public function getRecordsCount(){
		return $this->record_count;
	}
}
