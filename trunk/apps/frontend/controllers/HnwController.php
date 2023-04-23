<?php
namespace Mdg\Frontend\Controllers;
use Phalcon\Mvc\Controller;
use Lib\Crypt as crypt;
use Mdg\Models\Users as Users;
use Mdg\Models\UsersExt as UsersExt;
class HnwController extends Controller

{

    public function catAction() {

        $src = $this->request->get('url');

        $content = file_get_contents($src);



        preg_match('/id="pageCount" value="(.+?)"/i', $content, $m);

        $num = isset($m[1]) && $m[1] > 0 ? intval($m[1]) : 1;

        $url = array();

        for($i=2; $i<=$num; $i++) {

            $url[] = '<a href="http://search.cnhnb.com/product/huangdou?page.orderAsc=false&page.pageNumber='.$i.'"></a>';

        } 

        $content .= '<div id="caiji_page">'.implode('<br />', $url).'</div>';

      

    }

    /**
     * 设置cookie
     * @param  string $value 值
     * @return [type]        [description]
     */
    public function cookiesetAction  ($value = '') {
         
        $value = isset($_GET['val']) ? $_GET['val'] : '';
        $url = isset($_GET['url']) ? $_GET['url'] : '';
        if($value) {
             $cookies = base64_decode($value);
             setcookie("ync_auth",$cookies,time()+3600, '/',".ync365.com");
             setcookie("ync_auth",$cookies,time()+3600, '/',".5fengshou.com");
        }
     
        if($url){
             echo "<script>location.href='$url';</script>";
        }else{
             echo "<script>location.href='/purchase/index/';</script>";
        }
        $this->view->title = '丰收汇-可靠农产品交易服务商';
        $this->view->is_ajax=0;
    }


}

