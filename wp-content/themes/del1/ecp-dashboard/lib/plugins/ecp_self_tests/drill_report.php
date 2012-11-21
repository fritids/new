<?php
$level=0;
class Wp_Menu{
	private $menu_items;
	private $student_id;
	public function Wp_Menu($menu_items,$student_id){
		$this->menu_items=array();
		$this->student_id=$student_id;
		foreach($menu_items as $item){
			$menu_item=$this->menu_items[$item->ID]=new Wp_MenuItem($item,$item->menu_item_parent);
			if($item->menu_item_parent!=0){
				$this->menu_items[$item->menu_item_parent]->addChild($menu_item);
			}
		}
	}
	public function toString(){
		$out="<ul>";
		foreach($this->menu_items as $item){
			if($item->getParentID()==0){
				$out.=$item->toString($this->student_id);
			}
		}
		$out.="</ul>";
		return $out;
	}
}
class Wp_MenuItem{
	private $item;
	private $parent;
	private $submenu;
	private $hasth=false;
	public function Wp_MenuItem($item,$parent=0,$submenu=null){
		$this->item=$item;
		$this->parent=$parent;
		if($this->submenu==null){
			$this->submenu=array();
		}
	}
	public function addChild($item){
		$this->submenu[]=$item;
	}
	public function getParentID(){
		return $this->parent;
	}
	public function getChildCount(){
		return count($this->submenu);
	}
	public function hasChildren(){
		if($this->getDrillsNo()>0){
			return true;
		}else{
			return false;
		}
	}
	public function getDrillsNo(){
		$drillCount=0;
		if($this->submenu!=null){
			foreach($this->submenu as $menuItem){
				if($menuItem->item->object=="drill"){
					$drillCount++;
				}else{
					$drillCount+=$menuItem->getDrillsNo();
				}
			}
		}
		return $drillCount;
	}
	public function getType(){
		return $this->item->object;
	}
	public function toString($student_id=null){
		$sufix="";
		if($this->item->object!="drill" && count($this->submenu)==0){
			return;
		}
		

		if($this->item->object=="drill"){
			
			$out.="<li>";
			$sufix=" ".$this->item->title;
		}else{
			if(!$this->hasChildren()){
				return;
			}
			$out.="<li><a class='tableslider levelone collapsed'>".$this->item->title."(".$this->getDrillsNo().")</a>";
		}
		if(count($this->submenu)>0){
			$out.="<ul>";
			foreach($this->submenu as $item){
				if($item->getType()!="page"){
					$out.=$item->toString($student_id);
				}
			}
			$out.="</ul>";
		}
		
		
		
		
		if($this->item->object=="drill"){
			
		if(!$this->hasth){
			$out.='<table class="progress_table drill_header" width="100%" border="0" cellspacing="0" cellpadding="0"><tr>';
			$out.='<th class="text-blue center" width="20px">#</th>';
			$out.='<th width="170px">Date taken</th>';
			$out.='<th width="450px">Topic - '.$this->item->title.'</th>';
			$out.='<th width="110px">Total questions</th>';
			$out.='<th width="20px">Correct</th>';
			$out.='<th width="20px">Incorrect</th>';
			$out.='<th>Action</th>';
			$out.='</tr>';
			//$out.='</table>';
			$this->hasth=true;
		}
			
			if($student_id!=null){
				$meta=get_user_meta($student_id, "selftest_".$this->item->object_id,false);
			}else{
				global $current_user;
				$meta=get_user_meta($current_user->ID, "selftest_".$this->item->object_id,false);
			}
			//$out.='<table class="progress_table" width="100%" border="0" cellspacing="0" cellpadding="0">';

			$count=1;
			$drill=get_post($this->item->object_id);
			foreach($meta as $test){
				$cls="";
				if($count%2==0){
					$cls=" class='alt' ";
				}
				
				
				
				$out.='<tr'.$cls.'>';
				$out.='<td class="text-blue center" width="20px">'.$count.'</td>';
				$out.='<td width="170px">'.date("F j, Y,",$test["drill_end"]).'</td>';
				$out.='<td width="450px">'.$drill->post_title.$sufix.'</td>';
				$out.='<td width="110px">'.$test["question_count"].'</td>';
				$out.='<td width="20px">'.$test["correct_count"].'</td>';
				$out.='<td width="20px">'.($test["question_count"]-count($test["answers"])).'</td>';
				$out.='<td><a class="review" href="'.get_permalink($this->item->object_id).'?mode=review&trial='.($count-1).'">review</a></td>';
				$out.='</tr>';
				$count++;
			}
			$out.='</table>';
		}
		
		$out.="</li>";
		return $out;
	}
}