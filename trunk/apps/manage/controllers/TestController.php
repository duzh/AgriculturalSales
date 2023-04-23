<?php

namespace Mdg\Manage\Controllers;
use Lib\Time as times;
use Lib\Caiji as Caiji;
use Mdg\Models\Areas as Areas;
use Mdg\Models\Village as Village;
use Mdg\Models\Townlet as Townlet;
use Mdg\Models\AreasFull as Full;
use Mdg\Models\Sell as Sell;
use Mdg\Models  as M;



class TestController extends ControllerMember

{

	public function testAction() {
		$username = $this->getUsername();
		$userid   = $this->getUserID();
		var_dump($username);
		var_dump($userid);
		die;
		

	}


}