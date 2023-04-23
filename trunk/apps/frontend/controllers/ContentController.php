<?php

namespace Mdg\Frontend\Controllers;
use Mdg\Models\SellContent as Content;
use Mdg\Models\Village as Village;
use Mdg\Models\Townlet as Townlet;
use Mdg\Models\AreasFull as Full;
use Mdg\Models\Sell as Sell;



class ContentController extends ControllerBase

{

    /** ajax 商品分类 **/

	public function indexAction() {
		// for($i=23027; $i>=22235; $i--) {
		// 	$info = Content::findFirstBysid($i);
		// 	if(!$info) continue;
		// 	if(strpos($info->attr, "\\") === false) {
		// 		$info->attr = str_replace('u', '\u', $info->attr);
		// 		$info->save();
		// 	}
		// 	echo $info->sid,"<br />";
		// 	// print_r($info->toArray());exit;
		// }
		// echo 'finish';
		// exit;
	}

}