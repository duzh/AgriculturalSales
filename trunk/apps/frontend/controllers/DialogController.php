<?php

namespace Mdg\Frontend\Controllers;
use Mdg\Models as M;
class DialogController extends ControllerDialog
{
  	public function alertsellAction($id) {
          $this->view->ajax = 1;
          $sell=M\Sell::findFirstByid($id);
          $this->view->sell=$sell;
  	}
  	public function alertpurAction($id) {

         $this->view->ajax = 1;
         $Purchase=M\Purchase::findFirstByid($id);
         $this->view->Purchase=$Purchase;
  	}
}