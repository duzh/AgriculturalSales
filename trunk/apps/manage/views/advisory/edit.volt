
{{ content() }}
{{ form("advisory/save", "method":"post",'id':'myarticle') }}
<script type="text/javascript" charset="utf-8" src="/ueditor1/ueditor.config.sample.js"></script>
<script type="text/javascript" charset="utf-8" src="/ueditor1/ueditor.all.js"></script>
<script type="text/javascript" charset="utf-8" src="/ueditor1/lang/zh-cn/zh-cn.js"></script> 
<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/manage/css/style.css" />
<script type="text/javascript" src="/uploadify/jquery.uploadify.min.js?var=<?= rand(1, 9999) ?>" ></script>
<link rel="stylesheet" type="text/css" href="/uploadify/uploadify.css">
<div class="main_right">
    <div class="bt2">编辑咨询文章</div>
    <div class="tab2">
<ul id="test2_li_now_">
<li class="now">通用信息</li>
<li>文章内容</li>
</ul>
</div>
   <div id="test2_1" class="tablist block">
<div class="cx">
         <table width="100%" border="0" cellspacing="0" cellpadding="0" style=" border:none;">
                <tr>
                    <td class="cx_title">文章标题：</td>
                    <td class="cx_content">{{ text_field("title") }}</td>
                </tr>
				<!--<tr>
                    <td class="cx_title">推荐到：</td>
                    <td class="cx_content">
                         {% for index, state in is_recom %}
						 {% if is_hot == index %}
                        {{ radio_field('is_recom','value' : index,'checked':'checked') }} {{ state }}
						{% else %}
                        {{ radio_field('is_recom','value' : index) }} {{ state }}
						 {% endif %}
                        {% endfor %}
                    </td>
                </tr>-->
                <tr>
                    <td class="cx_title">文章分类：</td>
                    <td class="cx_content">
                        {{ hidden_field('cid', 'value' : art.cid) }}
                        <div class="zTreeDemoBackground left">
                            <ul id="treeDemo" class="ztree"></ul>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="cx_title">关键字：</td>
                    <td class="cx_content">{{ text_field("keywords","placeholder":"多个关键字请用逗号或空格分割", "size" : 30) }}</td>
                </tr>
                <tr>
                    <td class="cx_title">标签：</td>
                    <td class="cx_content">{{ text_field("tags","placeholder":"多个标签请用逗号或空格分割", "size" : 30) }}</td>
                </tr>
                <tr>
                     <td class="cx_title">是否显示：</td>
                    <td class="cx_content">
                        {% for index, state in is_show %}
                        {{ radio_field('is_show','value' : index) }} {{ state }}
                        {% endfor %}
                    </td>
                </tr>
                <tr>
                    <td class="cx_title"  valign="top">网页描述：</td>
                    <td >
                        <div class="cx_content1">{{ text_area("description", 'rows' : 5, 'cols' : 50) }}</div>
                    </td>
                </tr>
				<tr colspan="2">
                     <td class="cx_title">上传产品图片：</td>
					 <td class="cx_content">  
                         <div class="gy_step">
                                <div class="load_img f-fl">
                                    <div class="load_btn">
                                        <input type="file" value="浏览" id="img_upload"></div>
                                    <div>
                                    <img src="{{ constant('IMG_URL') }}{{ img }}" id="img_show" width="100" height="100"></div>
                                </div>
                        </div>
                     </td>
                </tr>
         </table>
         
    </div>
</div>
    <div id="test2_2" class="tablist">
<div class="cx">
 <table width="100%" border="0" cellspacing="0" cellpadding="0" style=" border:none;">
        
    <tr>
       <td>
            <script id="editor" type="text/plain"  style="width:1024px;height:500px;" name="content" >{{content}}</script>
      </td>
    </tr>
 </table>
 </div>
</div>
    <div align="center" style="margin-top:20px;">
        <td>{{ hidden_field("id") }}</td>
         <input type="submit" value="确认修改" class="sub"/>
         </div>
  </div>
  <!-- main_right 结束  --> 
  
</div>
</form>
<script>
jQuery(document).ready(function(){
    var gyInput = $('.gy_step li input');
    inputFb(gyInput);
     setTimeout(function(){
          $('#img_upload').uploadify({
              'swf'      : '/uploadify/uploadify.swf',
              'uploader' : '/upload/tmpfile',
              'multi '   : false,
              'formData' : {
                  'sid' : '{{ sid }}',
                  'type' : "28",
              },
              'buttonClass' : 'sub',
              'buttonText'  : '浏览',
              'multi'       : false,

              onUploadSuccess  : function(file, data, response) {
                  data = $.parseJSON(data);
                  if(data.status) {
                      $('#img_show').attr('src', data.path);
                  }
              }
          })
      },10)
});
</script>
<script type="text/javascript">
function tab(o, s, cb, ev){ //tab切换类
var $ = function(o){return document.getElementById(o)};
var css = o.split((s||'_'));
if(css.length!=4)return;
this.event = ev || 'onclick';
o = $(o);
if(o){
this.ITEM = [];
o.id = css[0];
var item = o.getElementsByTagName(css[1]);
var j=1;
for(var i=0;i<item.length;i++){
if(item[i].className.indexOf(css[2])>=0 || item[i].className.indexOf(css[3])>=0){
if(item[i].className == css[2])o['cur'] = item[i];
item[i].callBack = cb||function(){};
item[i]['css'] = css;
item[i]['link'] = o;
this.ITEM[j] = item[i];
item[i]['Index'] = j++;
item[i][this.event] = this.ACTIVE;
}
}
return o;
}
}
tab.prototype = {
ACTIVE:function(){
var $ = function(o){return document.getElementById(o)};
this['link']['cur'].className = this['css'][3];
this.className = this['css'][2];
try{
$(this['link']['id']+'_'+this['link']['cur']['Index']).style.display = 'none';
$(this['link']['id']+'_'+this['Index']).style.display = 'block';
}catch(e){}
this.callBack.call(this);
this['link']['cur'] = this;
}
}
</script>
<script type="text/javascript">
window.onload = function(){
new tab('test2_li_now_');
}
</script>
<link rel="stylesheet" href="http://js.static.ync365.com/zTree/css/zTreeStyle/zTreeStyle.css" type="text/css">
<script type="text/javascript" src="http://js.static.ync365.com/zTree/js/jquery.ztree.core-3.5.min.js"></script>
<script type="text/javascript" src="http://js.static.ync365.com/zTree/js/jquery.ztree.excheck-3.5.min.js"></script>

<SCRIPT type="text/javascript">
<!--
    var treeObj;
    var setting = {
        check: {
            enable: true,
            chkStyle: "radio",
            radioType: "all"
        },
        data: {
            simpleData: {
                enable: true
            }
        },
        callback: {
                onCheck: zTreeOnCheck,
                onClick: zTreeOnClick
            }
    };
    function zTreeOnCheck(event, treeId, treeNode) {
        $('#cid').val(treeNode.id);
    };

    function zTreeOnClick(event, treeId, treeNode) {
        treeObj.checkNode(treeNode, true, true);
        $('#cid').val(treeNode.id);
    };

    function setCheck() {
        $.getJSON('/manage/advisorycategory/ajax', {pid : {{ art.cid }}}, function(data) {
            treeObj = $.fn.zTree.init($("#treeDemo"), setting, data);
            treeObj.expandAll(true);
            treeObj.checkNode()
        });

    }
    $(document).ready(function(){
        setCheck();
    });
//-->
</SCRIPT>
<script type="text/javascript">var ue = UE.getEditor('editor');</script>
<link rel="stylesheet" type="text/css" href="{{ constant('JS_URL') }}validator/jquery.validator.css" />
<script type="text/javascript" src="{{ constant('JS_URL') }}validator/jquery.validator.js"></script>
<script type="text/javascript" src="{{ constant('JS_URL') }}validator/local/zh_CN.js"></script>
<script type="text/javascript">
$("#myarticle").validator({
     fields:  {
         title:"required;length[5~30]",
         keywords:"required;",
         description:"required;",
         tags:"required;"
     },
    
});
</script>
<style>
.upload_btn {width: 121px;height: 31px;line-height: 42px;text-align: center;background: url({{ constant('STATIC_URL') }}mdg/images/yz_btn.png) no-repeat;background-position: 0 0;top: 0;left: 88px;color: #7f7f7f;}
</style>
<div class="footer"> Copyright © 2013-2014 ync365.com All rights reserved. </div>
</body>
</html>

