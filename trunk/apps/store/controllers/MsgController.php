<?php

namespace Mdg\Store\Controllers;

class MsgController extends ControllerBase
{
	public function showmsgAction() {
		// $this->view->url = $url;
		// echo $url;exit;
		$this->view->is_ajax = true;
	}
}