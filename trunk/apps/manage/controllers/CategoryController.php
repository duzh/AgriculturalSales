<?php
namespace Mdg\Manage\Controllers;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Mdg\Models as M;
use Mdg\Models\Category as Category;
use Mdg\Models\Purchase as Purchase;
use Mdg\Models\Sell as Sell;
use Mdg\Models\Image as Image;
use Mdg\Models\TmpFile as TmpFile;
use Lib\File as File;
use Lib\Pages as Pages;
use Lib\Func as Func;

class CategoryController extends ControllerMember
{

     /**
     * 根据父类获取子级信息
     * @param  integer $pid 父级id
     * @return array
     */
    public function selectByparent_idtoChild($pid=0, $istop=0) {
        
        $data = array('items' => array());
        $data['items'] = M\Category::find(" parent_id = '{$pid}'")->toArray();
        if($istop) {

        }
        return $data;
    }
    /**
     *  获取子类
     * @return [type] [description]
     */
    public function getchildAction () {
        $pid = $this->request->get('pid', 'int', 0 );
        $deep = $this->request->get('deep', 'int', 0 );
        $data = array();
        if(!$pid) { exit(json_encode($data));}
        $data = $this->selectByparent_idtoChild($pid);
        print_R($data);
        exit;    
    }
    /**
     * 分类列表
     */
    public function indexAction()
    { 
        
        $title = $this->request->get('title', 'string', '');
    
        $where = array();
         $cid=array();
        if(!empty($title)) {
            $categoryarray=Category::find("title like '{$title}' ");
            
            if(empty($categoryarray->toArray())){
               $where[]=" id=0";
            }else{
                foreach ($categoryarray as $key => $value) {
                    if($value->parent_id==0){
                        $cid[]=$value->id;
                    }else{
                        $cid[]=$value->id;
                        $cid[]=$value->parent_id;
                    }
                }
                $cids=implode(",",$cid);
                $where[]="id in ({$cids})";
            }            
        }
        
        $where = array(implode(' and ', $where));
       
        $where['order'] = ' deeps desc';
      
        $category=Category::find($where)->toArray();

        $data = Func::toTree($category, 'id', 'parent_id');
      
        $this->view->data = $data;
        $this->view->title = $title;
        $this->view->child_id   = $cid;
    }
    /**
     * 新增分类
     */
    public function newAction()
    {
        // $cat_list = Func::totree(Category::find('parent_id=0')->toArray(), 'id', 'parent_id', 'child');
        $sid      = md5(session_id());
        $tfile = TmpFile::clearOld($sid);
        $cate= Category::find();
       
        $i = 1;
        foreach ($cate as $key => $value) {
            if($value!=''){
                $data[$i] = array('id'=>intval($value->id),'pId'=>intval($value->parent_id),'name'=>$value->title);
                if($value->parent_id == 0 ){
                  // $data[$i]['open'] ="true";
                  // $data[$i]['check'] = "true";    
                }
                $i++;
            }
        }
       
        $str='';
        foreach ($data as $key => $value) {
             $str.=json_encode($value).',';
        }
       
        $this->view->cate         = rtrim($str,',');
        $this->view->sid = $sid;
        $this->view->_crop_type = M\Category::$_crop_type;
        $this->tag->setDefault("is_groom","1");
        $this->tag->setDefault("is_show","1");
       
    }

    /**
     * 编辑分类
     *
     * @param string $id
     */
    public function editAction($id)
    {
        
        if (!$this->request->isPost()) {

            $category = Category::findFirstByid($id);

            if (!$category) {
                parent::msg('此分类不存在!','/manage/category/index');
                // echo "<script>alert('此分类不存在！');location.href='/manage/category/index'</script>";
            }
            $Image=Image::findFirst("gid=".$id." and type=1");
            $minImage=Image::findFirst("gid=".$id." and type=0");
            $moblieImage=Image::findFirst("gid=".$id." and type=13");
            $cat_list = Func::toTree(Category::find("parent_id=0")->toArray(), 'id', 'parent_id', 'child');

            $sid      = md5(session_id());
            $tfile = TmpFile::clearOld($sid);


            $cate= Category::find();
           
            $i = 1;
            foreach ($cate as $key => $value) {
                if($value!=''){
                    $data[$i] = array('id'=>intval($value->id),'pId'=>intval($value->parent_id),'name'=>$value->title);
                    if($value->parent_id == 0 ){
                      // $data[$i]['open'] ="true";
                      // $data[$i]['check'] = "true";    
                    }
                    $i++;
                }
            }
           

            $str='';

            foreach ($data as $key => $value) {
                 $str.=json_encode($value).',';
            }
             //获取分类名称
            if($category->parent_id==0){
                $categroyname='顶级分类';
            }else{
                $categroy=Category::getFamily($id);
                if(isset($categroy[2]["title"])){
                    $categroytitle=$categroy[2]["title"];
                }else{
                    $categroytitle='';
                }
                $categroyname=$categroy[0]["title"].'-'.$categroy[1]["title"].'-'.$categroytitle;
            }

            $this->view->cate         = rtrim($str,',');
            $this->view->sid = $sid;
            $this->view->id = $category->id;
            $this->view->parent_id = $category->parent_id;
            $this->view->title = $category->title;
            $this->view->cat_list=$cat_list;
            $this->view->img=$Image?$Image->agreement_image:"";
            $this->view->minimg=$minImage?$minImage->agreement_image:"";
            $this->view->moblieimg=$moblieImage?$moblieImage->agreement_image:"";
            $this->view->categroyname= $categroyname;
            $this->view->category = $category;
            $this->tag->setDefault("id", $category->id);
            $this->tag->setDefault("title", $category->title);
            $this->tag->setDefault("is_groom", $category->is_groom);
            $this->tag->setDefault("keyword", $category->keyword);
            $this->tag->setDefault("is_show", $category->is_show);
            $this->tag->setDefault("deeps", $category->deeps);
            $this->tag->setDefault("depict", $category->depict);
            $this->tag->setDefault("abbreviation", $category->abbreviation);
            $this->view->_crop_type = M\Category::$_crop_type;
        }
    }

    /**
     * 保存新增
     */
    public function createAction()
    {
        
        $title        = $this->request->getPost('title', 'string', '');
        $parent_id    = $this->request->getPost('categroy', 'string', 0);
        if($parent_id==''){
        $parent_id=0;
        }
        $deeps        = $this->request->getPost('deeps', 'int', 0);
        if($deeps==''){
            $deeps=0;
        }
        $is_groom     = $this->request->getPost('is_groom', 'int', 1);
        $is_show      = $this->request->getPost('is_show', 'int', 1);
        $keyword      = $this->request->getPost('keyword', 'string', '');
        $depict       = $this->request->getPost('depict', 'string', '');
        $sid          = $this->request->getPost('sid', 'string', '');
        $abbreviation = $this->request->getPost('abbreviation', 'string', '');
        $crop_type = $this->request->getPost('crop_type', 'int', 0);
        
        $checkcate=Category::find("title like '{$title}' and parent_id={$parent_id} ")->toArray();
        if(!empty($checkcate)){
            parent::msg('分类已经存在!','/manage/category/new');
            // echo "<script>alert('分类已经存在');location.href='/manage/category/new'</script>";die;
        }
        if(!$parent_id && !$crop_type &&$parent_id!=0 &&$crop_type!=0) {
            parent::msg('分类已经存在!','/manage/category/new');
            // echo "<script>alert('分类已经存在');location.href='/manage/category/new'</script>";
            // exit;
        }
 
        $category = new Category();
        $category->title = $title;
        $category->parent_id = $parent_id;
        $category->keyword = $keyword;
        $category->depict = $depict;
        $category->deeps = intval($deeps);

        $parent = $parent_id ? Category::findFirstByid($parent_id) : false;
        $category->is_groom  = $parent && !$parent->is_groom ? 0 : $is_groom;
        $category->is_show = $parent && !$parent->is_show ? 0 : $is_show;
        $category->abbreviation=$abbreviation;
        $category->crop_type=intval($crop_type);
        //print_r($category->toArray());die;
        if (!$category->save()) {
            parent::msg('新增分类失败！','/manage/category/new');
            // $this->flash->error('新增分类失败！');
            // $this->response->redirect('/category/new')->sendHeaders();
        }

        // error_reporting(E_ALL);
        // ini_set("display_errors","On");
        //拷贝图片
        TmpFile::copyImages($category->id,$sid);
       //M\Ifversion::addversion();
        Func::adminlog("新增商品分类：{$category->title}",$this->session->adminuser['id']);
        parent::msg('添加成功','/manage/category/index');
        // echo "<script>alert('添加成功');location.href='/manage/category/index'</script>";die;
       
      
    }

    /**
     * 保存分类修改
     * @return [type] [description]
     */
    public function saveAction()
    {
        

        $id = $this->request->getPost("id", 'int', 0);
        $parent_id = $this->request->getPost("categroy", 'int', 0);
        $oldcategroy = $this->request->getPost("oldcategroy", 'int', 0);
        $deeps = $this->request->getPost("deeps", 'int', 0);
        $is_groom = $this->request->getPost("is_groom", 'int', 0);
        $is_show = $this->request->getPost("is_show",'int',0);;
        $title = $this->request->getPost('title', 'string', '');
        $keyword = $this->request->getPost('keyword', 'string', '');
        $depict = $this->request->getPost('depict', 'string', '');
        $sid    = $this->request->get('sid', 'string', '');
        $abbreviation = $this->request->get('abbreviation', 'string', '');
        $crop_type = $this->request->getPost('crop_type', 'int', 0);
        $img_path = '';
        $minimg_path='';
        if($parent_id==''){
        $parent_id=0;
        }
        $category = Category::findFirstByid($id);
        if (!$category) 
        parent::msg('此分类不存在！','/manage/category/index');
        if($parent_id == $id)
        parent::msg('不能属于自身分类',"/manage/category/edit/{$id}");    
        
        $category->title     = $title;
        $category->parent_id = $parent_id;
        $category->keyword   = $keyword;
        $category->depict    = $depict;
        $category->deeps     = $deeps;
        $category->abbreviation=$abbreviation;
        $parent = $parent_id ? Category::findFirstByparent_id($parent_id) : false;
        $category->is_groom  = $parent && !$parent->is_groom ? 0 : $is_groom;
        $category->is_show = $parent && !$parent->is_show ? 0 : $is_show;
        $category->crop_type=$crop_type;
        
        if (!$category->save()) {
            parent::msg('修改失败！',"/manage/category/edit/{$id}");    
        }
        //如果分类进行更改  进行更新 采购以及供应分类信息
        //TmpFile::copyImages($category->id,$sid);
        
        // if($parent_id!=$oldcategroy){
           
        //      $sell=new Sell();
        //      $data =$sell->SellDynamicSynCategory($oldcategroy,$parent_id);
        // }
    
        //修改状态
        //M\Ifversion::addversion();
        //Category::changeState($id, 'is_groom',3, $is_groom);
        //Category::changeState($id, 'is_show', 3,$is_show);
        Func::adminlog("修改商品分类：{$category->title}",$this->session->adminuser['id']);
        parent::msg('分类修改成功！',"/manage/category/edit/{$id}");
       
        

    }

    /**
     * 删除分类
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        $category = Category::findFirstByid($id);
        if (!$category) {
            parent::msg('该分类不存在！','/manage/category/index');
        }
        if($category->parent_id==0){
             if(Category::findFirstByparent_id($id)) {
                parent::msg('请先删除子分类！','/manage/category/index'); 
             }
        }
        if($category->sell_num!='0'||$category->pur_num!='0'){
            parent::msg('该分类有关联信息，请先转移至其他分类','/manage/category/index'); 
        }
      
        $Image=Image::findFirst("gid=".$id." and type=1");

        if($Image){
           $Image->delete();
        }
       
        if (!$category->delete()) {
            parent::msg('删除分类失败','/manage/category/index'); 
        }

         Func::adminlog("删除商品分类：{$category->title}",$this->session->adminuser['id']);
         parent::msg('删除分类成功','/manage/category/index');
    }
    /**
     *  转移分类
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function chanageAction($id){
        
        if(empty($id)){
            parent::msg('转移分类不存在','/manage/category/index');

        }
        $categorys = M\Category::findFirstByid($id);
        if (!$categorys) {
            parent::msg('转移分类不存在！','/manage/category/index');
        }
        $cat_list = Func::toTree(Category::find()->toArray(), 'id', 'parent_id', 'child');
        //print_r($cat_list);die;
        $this->view->id = $id;
        $this->view->cat_list=$cat_list;
       
    } 
    /**
     * 检测分类
     * @return [type] [description]
     */
    public function checkAction(){

        $title=M\Category::findFirstBytitle($_POST["title"]);
        $msg = array();
        if(!empty($title)){
           $msg['error'] = '分类名称已经存在!';
        }else{
           $msg['ok'] = '';    
        }
       echo json_encode($msg);die; 
    }
    /**
     * 转移分类保存
     * @return [type] [description]
     */
    public function chanageproAction(){
        
        
        $catid = $this->request->getPost('catid', 'int', 0);
        $dst_id = $this->request->getPost('dst_id', 'int', 0);
       
        if(!Category::findFirstByid($dst_id)) {
            echo "<script>alert('目标分类不存在！');window.parent.changeUrl('/manage/category/index');</script>";die;
        }

        $ids = Category::getChild($catid);

        if(in_array($dst_id, $ids)) {
            echo "<script>alert('不能转移到下属分类！');window.parent.changeUrl('/manage/category/index');</script>";die;
        }

        Sell::changeCategory($ids, $dst_id);
        Purchase::changeCategory($ids, $dst_id);

        Func::adminlog("转移分类：{$catid}",$this->session->adminuser['id']);

        echo "<script>alert('分类转移成功');window.parent.changeUrl('/manage/category/index');</script>";die;
     
    }
    /**
     * 分类切换
     * @return [type] [description]
     */
    public function toggleAction(){
        $id = $this->request->get('id','int', 0);
        $field = $this->request->get('type','string', 'is_show');
        $deep=$this->request->get('deep','string', 1);
        $state=$this->request->get('state','int', 0);
        $_field = array('is_groom', 'is_show');
  
        if(!in_array($field, $_field)){
            return false;
        }
        $cat=Category::findFirstByid($id);  
        
        if(!$cat){
            return false;
        }

        $cat->$field=$state;
       
        if($cat->save()){
            if($field=='is_show'){
            Func::adminlog("商品分类导航否显示：{$cat->title}",$this->session->adminuser['id']);
            }
            if($field=='is_groom'){
            Func::adminlog("商品分类首页推荐：{$cat->title}",$this->session->adminuser['id']);
            }
            if($deep<3){
            Category::changeState($id,$field,$deep,$state);
            }
        }
    }
    /**
     *  根据分类名称匹配数据
     * @return [type] [description]
     */
    public function getabbreviationAction(){
       $title = $this->request->get('title', 'string', '');
         $msg['str'] = '';
       if($title){
         $msg['str']=$this->getFirstCharter($title);
       }
       echo json_encode($msg);die;
    }
    public function getFirstCharter($str){
     
        if(empty($str)){return '';}

        $fchar=ord($str{0});

        if($fchar>=ord('A')&&$fchar<=ord('z')) return strtoupper($str{0});

        $s1=iconv('UTF-8','gb2312',$str);

        $s2=iconv('gb2312','UTF-8',$s1);

        $s=$s2==$str?$s1:$str;

        $asc=ord($s{0})*256+ord($s{1})-65536;

        if($asc>=-20319&&$asc<=-20284) return 'A';

        if($asc>=-20283&&$asc<=-19776) return 'B';

        if($asc>=-19775&&$asc<=-19219) return 'C';

        if($asc>=-19218&&$asc<=-18711) return 'D';

        if($asc>=-18710&&$asc<=-18527) return 'E';

        if($asc>=-18526&&$asc<=-18240) return 'F';

        if($asc>=-18239&&$asc<=-17923) return 'G';

        if($asc>=-17922&&$asc<=-17418) return 'H';

        if($asc>=-17417&&$asc<=-16475) return 'J';

        if($asc>=-16474&&$asc<=-16213) return 'K';

        if($asc>=-16212&&$asc<=-15641) return 'L';

        if($asc>=-15640&&$asc<=-15166) return 'M';

        if($asc>=-15165&&$asc<=-14923) return 'N';

        if($asc>=-14922&&$asc<=-14915) return 'O';

        if($asc>=-14914&&$asc<=-14631) return 'P';

        if($asc>=-14630&&$asc<=-14150) return 'Q';

        if($asc>=-14149&&$asc<=-14091) return 'R';

        if($asc>=-14090&&$asc<=-13319) return 'S';

        if($asc>=-13318&&$asc<=-12839) return 'T';

        if($asc>=-12838&&$asc<=-12557) return 'W';

        if($asc>=-12556&&$asc<=-11848) return 'X';

        if($asc>=-11847&&$asc<=-11056) return 'Y';

        if($asc>=-11055&&$asc<=-10247) return 'Z';

        return '';
    }
    public function countAction(){
       
       //$sql="select id from category where parent_id in (select id from category where parent_id=1377) ";
       // $sql="select id from category where parent_id in (select id from category where parent_id=0) ";
       // $db = $GLOBALS['di']['db'];
       // $cate=$db->query($sql)->fetchAll();
       
       // foreach ($cate as $key => $value) {
       
       //    $id=$value["id"];
       //    //$sell=M\Purchase::count(" category=$id ");
       //    $sell=M\Category::find(" parent_id=$id ")->toArray();
          
       //    $arr=0;
       //    foreach ($sell as $key => $value) {
       //        $arr+=$value["pur_num"];
       //    }
       //    //echo $arr;die;
       //    // echo $sell;die;
         
       //    $sql="update category set pur_num=$arr where  id=$id;";
       //    echo $sql;
           
       // }
       // 
       $sql="select title,id from category ";
       $db = $GLOBALS['di']['db'];
       $cate=$db->query($sql)->fetchAll();
     
       $sql1.='';
       foreach ($cate as $key => $value) {
         $abbreviation= $this->getFirstCharter($value["title"]);
         $sql1.="update category set abbreviation='{$abbreviation}' where id=".$value["id"].';';
       }
    echo $sql1;
    }

}