<?php
namespace Mdg\Manage\Controllers;
use Phalcon\Mvc\Controller;
use Mdg\Models\UsersNav as Nav;
use Mdg\Models\Article as Article;
use Mdg\Models\ArticleCategory as ArticleCategory;
use Mdg\Models\Category as Category;
use Lib\Func as Func;
use Mdg\Models as M;
use Lib\Arrays as Arrays;
class ControllerMember extends Controller
{
	public function initialize(){
    		$adminuser = $this->session->adminuser;
    		if(!$adminuser['id']){
           echo "<script>parent.location.href='/manage/login/index'</script>";die;
    		}
    		$action=strtolower($this->dispatcher->getActionName());
    		$con=$this->dispatcher->getControllerName();
    		foreach ($adminuser["permission"] as $k => $v) {
            	$per[]=strtolower($v["controller"].$v["action"]);
        }
        $rolecon=strtolower($con.$action);
        $public=M\AdminRoles::$publicrole;
        $per[] = "adminconlist";
        $per[] = "categorycount";	
        $per[] = 'sellgetcate';
        $per[] = 'categorygetabbreviation';
       	$per[] = "sellfall";
       	$per[] = "purchasefall";
        $per[] = "sellauditorpass";
       	$per[] = "purchaseauditorpass";
       	$per[] = 'sellshenhe'; 
        $per[] = 'purchaseauditorpasspro';
       	$per[] = 'categorygetchild';
        $per[] = 'ordersshowmsg';
        $per[] = 'advisorygetcats';
        $per[] = 'advisorygetarticle';
        $per[] = 'advisorydelrecom';        
        $per[] = 'orderschangeestime';
        $per[] = 'orderschangeeetime';
        $per[] = 'orderschangestimepro';
        if(in_array($rolecon,$per)||$adminuser["username"]=="admin"|| $adminuser["username"]=="cesz" || $adminuser["username"]=="zhanggp" ||$adminuser["username"]=="yangk"||in_array($action,$public)){
             
        }else{
          // echo "<script>alert('您没有此权限');history.go(-1);</script>";die;
        }
        //$this->view->row=$row;

	}

	public function getUserID() {
		$adminuser = $this->session->adminuser;

		return $adminuser['id'];
	}

	public function getUsername() {
		$adminuser = $this->session->adminuser;

		return $adminuser['username'];
	}

	public function getRoleID() {
		$adminuser = $this->session->adminuser;

		return $adminuser['role_id'];
	}

  public function showMessage ($url='') {

      $this->session->sessionREFERER = '';
      echo "<script>location.href='{$url}'</script>";exit;
  }
  public function msg($content='',$url='',$parents = 0){
      $parent = '';
      switch($parents){
            case 1:
                $parent = 'parent.';
                break;
            case 2:
                $parent = 'parent.parent.';
                break;
            default:
                $parent = '';
                break;
        }
        if($url){
        echo "<script>alert('{$content}');{$parent}location.href='{$url}'</script>";die;
        }else{
        echo "<script>alert({$content});</script>";die;
        }
  } 
  
}
