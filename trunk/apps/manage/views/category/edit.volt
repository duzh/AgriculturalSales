{{ form("category/save", "method":"post", 'enctype':"multipart/form-data") }}
{{ content() }}
<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/manage/css/style.css" />
<link rel="stylesheet" href="{{ constant('JS_URL') }}zTree_v3/css/demo.css" type="text/css">
<link rel="stylesheet" href="{{ constant('JS_URL') }}zTree_v3/css/zTreeStyle/zTreeStyle.css" type="text/css">
<script type="text/javascript" src="{{ constant('JS_URL') }}zTree_v3/js/jquery.ztree.core-3.5.js"></script>
<script type="text/javascript" src="{{ constant('JS_URL') }}zTree_v3/js/jquery.ztree.excheck-3.5.js"></script>
<script type="text/javascript" src="{{ constant('JS_URL') }}zTree_v3/js/jquery.ztree.exedit-3.5.js"></script>
<div class="main">
  <div class="main_right">
    <div class="bt2">编辑分类</div>
    <div class="cx">
         <table width="100%" border="0" cellspacing="0" cellpadding="0" style=" border:none;">
                <tr>
                    <td class="cx_title">分类名称：</td>
                    <td class="cx_content">
                        {{ text_field("title", "size" : 30) }} 
                    </td>
                </tr>
                <tr>
                    <td class="cx_title"><label>上级分类：</label></td>
                    <td class="cx_content">
                      <input id="citySel" type="text"  readonly value="{{categroyname}}" class="dfinput" data-target="#categroyTip"  style="width:120px;"
                      onclick="showMenu();" />
                      <input type="hidden" name='categroy'  id="cates" value="{{parent_id}}">
                      <input type="hidden" name='oldcategroy'  value="{{id}}">
                        <a id="menuBtn" href="#" onclick="showMenu();">请选择分类</a>
                      <div id="menuContent" class="menuContent" style="display:none; position: absolute; z-index:10;">
                         <ul id="treeDemo" class="ztree" style="margin-top:0; width:180px; height: 300px;"></ul>
                      </div>
                      <span id="categroyTip"><span>
                    </td>
                </tr>
                <tr>
                    <td class="cx_title">排序：</td>
                    <td class="cx_content">
                        {{ text_field("deeps", "type" : "date") }}
                    </td>
                </tr>
                <tr>
                     <td class="cx_title">导航显示：</td>
                    <td class="cx_content">
                       {{ radio_field("is_show","value":"1") }}是
                       {{ radio_field("is_show","value":"0") }}否
                    </td>
                </tr>
                 <tr>
                     <td class="cx_title">首页推荐：</td>
                    <td class="cx_content">
                      {{ radio_field("is_groom","value":"1") }}是
                      {{ radio_field("is_groom","value":"0") }}否
                    </td>
                </tr>
                {% if category.parent_id != 0 %}
                 <tr>
                     <td class="cx_title">作物类型：</td>
                    <td class="cx_content">
                      <select name="crop_type" id="">
                        <option value="0">请选择</option>
                        {% for key, val in _crop_type %}
                        <option value="{{ key }}" {% if category.crop_type == key %}selected{% endif %}>{{ val }}</option>
                        {% endfor %}
                      </select>
                    </td>
                </tr>
                {% endif %}
                <tr>
                    <td class="cx_title"  valign="top">关键字：</td>
                    <td class="cx_content">
                        {{ text_field("keyword", "type" : "numeric") }}  
                    </td>
                </tr>
                
                <tr>
                    <td class="cx_title"  valign="top">分类大图片：</td>
                    <td class="cx_content">  
                         <div class="gy_step">
                                <div class="load_img f-fl">
                                    <div class="load_btn">
                                        <input type="file" value="浏览" id="img_upload"></div>
                                        <font color="red">图片尺寸 宽: 190px;高: 230px;(首页显示)</font>
                                    <div>
                                    <div id="imgbig_show">
                                        
                                          {% if img  %}
                                           <dl>
                                              <dt>
                                              <img src="{{ constant('IMG_URL') }}{{img}}" width="190" height="230">
                                              <a href="javascript:;" onclick="deleteImg(this,{{id}},1);">删除</a>
                                              </dt>
                                           </dl>
                                           {% endif %}

                                    </div>
                                 
                               
                                </div>
                        </div>
                     </td>
                </tr>
                <tr>
                    <td class="cx_title"  valign="top">分类小图片：</td>
                    <td class="cx_content">  
                         <div class="gy_step">
                                <div class="load_img f-fl">
                                    <div class="load_btn">
                                        <input type="file" value="浏览" id="small_upload"></div>
                                         <font color="red">图片尺寸 宽: 28px;高: 29px;(导航显示)</font>
                                    <div>
                                     <div id="imgsmall_show">
                                          {% if minimg  %}
                                           <dl>
                                              <dt>
                                              <img src="{{ constant('IMG_URL') }}{{minimg}}" width="28" height="29">
                                              <a href="javascript:;" onclick="deleteImg(this,{{id}},0);">删除</a>
                                              </dt>
                                           </dl>
                                           {% endif %}
                                    </div>
                                </div>
                        </div>
                     </td>
                </tr>
                 <tr>
                    <td class="cx_title"  valign="top">手机端分类图片：</td>
                    <td class="cx_content">  
                         <div class="gy_step">
                                <div class="load_img f-fl">
                                    <div class="load_btn">
                                        <input type="file" value="浏览" id="moblie_upload"></div>
                                         <font color="red">图片尺寸 宽: 28px;高: 29px;(导航显示)</font>
                                    <div>
                                     <div id="imgmoblie_show">
                                          {% if moblieimg  %}
                                           <dl>
                                              <dt>
                                              <img src="{{ constant('IMG_URL') }}{{moblieimg}}" width="28" height="29">
                                              <a href="javascript:;" onclick="deleteImg(this,{{id}},13);">删除</a>
                                              </dt>
                                           </dl>
                                           {% endif %}
                                    </div>
                                </div>
                        </div>
                     </td>
                </tr>
                <tr>
                    <td class="cx_title">分类描述：</td>
                    <td class="cx_content">
                    <div class="cx_content1">      
                        {{ text_field("depict", "type" : "numeric") }}
                    </div>
                    </td>
                </tr>
                <tr>
                    <td class="cx_title"  valign="top">分类简称：</td>
                    <td class="cx_content">
                        {{ text_field("abbreviation","type" : "date") }}
                    </td>
                </tr>
                
         </table>
         
    </div> 
    <div align="center" style="margin-top:20px;">
        <input type='hidden' name='sid' value="{{sid}}" >
        {{ hidden_field("id") }}
         <input type="submit" value="确认修改" class="sub"/>
    </div>
  </div>
  <!-- main_right 结束  --> 
  
</div>
<link rel="stylesheet" type="text/css" href="{{ constant('JS_URL') }}validator/jquery.validator.css" />
<script type="text/javascript" src="{{ constant('JS_URL') }}validator/jquery.validator.js"></script>
<script type="text/javascript" src="{{ constant('JS_URL') }}validator/local/zh_CN.js"></script>
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
                  'type' : "2",
              },
              'buttonClass' : 'sub',
              'buttonText'  : '浏览',
              'multi'       : false,

              onUploadSuccess  : function(file, data, response) {
                  data = $.parseJSON(data);
                  if(data.status) {
                       $('#imgbig_show').append(data.html);
                  }
              }
          })
          //分类小图片
          $('#small_upload').uploadify({
              'swf'      : '/uploadify/uploadify.swf',
              'uploader' : '/upload/tmpfile',
              'multi '   : false,
              'formData' : {
                  'sid' : '{{ sid }}',
                  'type' : "3",
              },
              'buttonClass' : 'sub',
              'buttonText'  : '浏览',
              'multi'       : false,

              onUploadSuccess  : function(file, data, response) {
                  data = $.parseJSON(data);
                  if(data.status) {
                      $('#imgsmall_show').append(data.html);
                  }
              }
          })
           //分类小图片
          $('#moblie_upload').uploadify({
              'swf'      : '/uploadify/uploadify.swf',
              'uploader' : '/upload/tmpfile',
              'multi '   : false,
              'formData' : {
                  'sid' : '{{ sid }}',
                  'type' : "13",
              },
              'buttonClass' : 'sub',
              'buttonText'  : '浏览',
              'multi'       : false,

              onUploadSuccess  : function(file, data, response) {
                  data = $.parseJSON(data);
                  if(data.status) {
                      $('#imgmoblie_show').append(data.html);
                  }
              }
          })

      },10)
});
//删除图片
function deleteImg(obj,id,type) {
  $.getJSON('/upload/deleteImg', {id:id,type:type}, function(data) {
      alert(data.msg);
      if(data.state) {
        $(obj).parents('dl').slideUp();
      }
  });
}
// 删除临时图片
function closeImg(obj, id) {
  $.getJSON('/upload/deltmpfile', {id : id}, function(data) {
      alert(data.msg);
      if(data.state) {
          $(obj).parents('dl').slideUp();
      }
  });
}
</script>

<SCRIPT type="text/javascript">
        <!--
        var setting = {
            check: {
                enable: true,
                chkStyle: "radio",
                radioType: "all"
            },
            view: {
                dblClickExpand: false
            },
            data: {
                simpleData: {
                    enable: true
                }
            },
            callback: {
                onClick: onClick,
                onCheck: onCheck
            }

        };
        var zNodes =[
          {{cate}}
         ];
        function onClick(e, treeId, treeNode) {
            var zTree = $.fn.zTree.getZTreeObj("treeDemo");
            zTree.checkNode(treeNode, !treeNode.checked, null, true);
            return false;
        }

        function onCheck(e, treeId, treeNode) {
            var zTree = $.fn.zTree.getZTreeObj("treeDemo"),
            nodes = zTree.getCheckedNodes(true),
            v = "";
            k ='';
            for (var i=0, l=nodes.length; i<l; i++) {
                v += nodes[i].name + ",";
                k += nodes[i].id + ",";
            }
            if (v.length > 0 ) v = v.substring(0, v.length-1);
            if (k.length > 0 ) k = k.substring(0, k.length-1);
            var cityObj = $("#citySel");
            cityObj.attr("value", v);

            var cateObj = $("#cates");
            cateObj.attr("value", k);

            $("#categroyTip").html('<span class="msg-wrap n-ok" role="alert"><span class="n-icon"></span><span class="n-msg"></span></span>');
        }
        function showMenu() {
            var cityObj = $("#citySel");
            var cityOffset = $("#citySel").offset();
            $("#menuContent").css({left:cityOffset.left + "px", top:cityOffset.top + cityObj.outerHeight() + "px"}).slideDown("fast");

            $("body").bind("mousedown", onBodyDown);
        }
        function hideMenu() {
            $("#menuContent").fadeOut("fast");
            $("body").unbind("mousedown", onBodyDown);
        }
        function onBodyDown(event) {
            if (!(event.target.id == "menuBtn" || event.target.id == "citySel" || event.target.id == "menuContent" || $(event.target).parents("#menuContent").length>0)) {
                hideMenu();
            }
        }
        $(document).ready(function(){
           $.fn.zTree.init($("#treeDemo"), setting,zNodes);
        });
    </SCRIPT>

<style>
    .uploadify-button{ background:url(http://erp.ync365.com/images/tj2-12.png) no-repeat; border-radius: 0; border: none; color:#333;}
    .uploadify:hover .uploadify-button{ background:url(http://erp.ync365.com/images/tj2-12.png) no-repeat;}
    .forminfo li label{ width:120px;}
</style>

<style>
.upload_btn {width: 150px;height: 31px;line-height: 31px;text-align: center;background: url({{ constant('STATIC_URL') }}mdg/images/yz_btn.png) no-repeat;background-position: 0 0;top: 0;left: 88px;color: #7f7f7f; margin-left:75px;}
</style>
<div class="footer"> Copyright © 2013-2014 ync365.com All rights reserved. </div>
</body>
</html>

