<?php
namespace Mdg\Manage\Controllers;
use Mdg\Models as M;
use Mdg\Models\AdminRoles as AdminRoles;
class PrivController extends ControllerBase
{
	public function indexAction(){

		$path = __DIR__.'/';
		$list = glob($path.'*.php');
		$priv = array();

		foreach ($list as $files) {
			$classname = 'Mdg\Manage\Controllers\\'.str_replace(array($path,'.php'),'',$files);
		
			$class = new \ReflectionClass($classname);
			foreach($class->getMethods(\ReflectionMethod::IS_PUBLIC) as $method){

				if($method->class != $classname) continue;
				$priv[$classname]['action'][] = $method->name; 
				$priv[$classname]['doc'][] = $this->replace($method); 
			}
		}

       
		$str='';
	    foreach ($priv as $key => $value) {

         	    $key1=explode("\\",$key);
         	    $key2=strtolower(str_replace('Controller', '', $key1[3])); 
                 
         	foreach ($value["action"] as $k => $v) {

         		$action=strtolower(str_replace('Action','', $v));  
         		if($value["doc"][$k]!=''){
	            $str.='manage/'.$key2.'/'.$action.'-'.$value["doc"][$k]."\n";
	            }
	         
	         
              //file_put_contents(PUBLIC_PATH.'/member.txt',$str."\n", FILE_APPEND);
	         	// $adminPermission->save();
         	}
        }
        echo $str;die;
		  
	}


    private function replace($method)
    {
		$doc = trim(str_replace(array('//','*','/'),'',$method->getdoccomment()));
		$doc = preg_replace('/@.*/i', '', $doc);
		return trim($doc);
	}
}