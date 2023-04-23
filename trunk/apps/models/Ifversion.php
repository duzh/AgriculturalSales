<?php
namespace Mdg\Models;
class Ifversion extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id=0;

    /**
     *
     * @var string
     */
    public $version='';

    /**
     *
     * @var integer
     */
    public $type=0;

    /**
     *
     * @var string
     */
    public $addtime='';
   
    public function getSource()
    {
        return "if_version";
    }
    static public function getversion(){
    	$sql="select max(version) as version ,type as versiontype from if_version group by versiontype  ";
    	$db = & $GLOBALS['di']['db'];
        $rs = $db->query($sql)->FetchAll(); 
        foreach ($rs as $key => $value) {
             if($value["versiontype"]==1){
                 $arr["cate"]=$value["version"];
             }
             if($value["versiontype"]==2){
                $arr["area"]=$value["version"];
             }
          }
          return $arr;
    }
    static public function addversion(){
        $version=self::getversion();
        $if=new self();
        $if->type=1;
        $if->version=$version["cate"]+1;
        $if->addtime=time();
        $if->save();
    }
}
