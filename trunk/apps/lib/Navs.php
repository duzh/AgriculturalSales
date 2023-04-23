<?php
namespace Lib;
use Lib\Arrays as Arrays;
use Mdg\Models as Model;
class Navs{
	static function getTopNav(){
		$navs = Model\AdminNav::find('is_show=1 and pid=0')->toArray();
		return $navs;
	}

	static function getNav($id){

		$navs = Model\AdminNav::find('is_show=1')->toArray();
		$navs = Arrays::toTree($navs,'id','pid');

		if(isset($navs[$id])){
			return $navs[$id];
		}else{
			return array();
		}
	}

	static function getLeftNav($id){

		$sub = self::getNav($id);

		$deep = max(Arrays::getCols(Arrays::treeToArray($sub),'deep'));

		
		if($deep ==1) return;
		if($deep ==2) {
			$menu = $sub['children'];
		}elseif($deep == 3){
			$menu = $sub['children'];
		}else{
			$menu = array($sub);
		}

		return self::getLeftNavHtml($menu);

	}
	static function getLeftNavHtml($arrays = array()){

		$html = '';
		$title = '';
		$menu_tpl = '<li class="%s" attr-id="%s">%s</li>';
		$href = '<a href="/%s" target="rightMain">%s</a>';

		foreach($arrays as $array){
			$sub = self::getLeftNavHtml($array['children']);
			$url = implode('/',array($array['controller'],$array['action'],$array['params']));
			$urls = sprintf($href,'url');
			if($sub){
				$css = 'nav_title nav_title_bc';
				$id = $array['id'];
				$html .= sprintf($menu_tpl,$css,$id,$array['name']).'<ul class="left_nav_list" id="subnav'.$id.'">'.$sub.'</ul>';
			}else{
				$urls = sprintf($href,$url,$array['name']);
				$css = '';
				$id = $array['id'];
				$html .= sprintf($menu_tpl,$css,$id,$urls);
			}

		}
		if($html){
			$html = '<ul class="hover_left_nav_list">'.$html.'</ul>';
		}

		return $html;
	}
}