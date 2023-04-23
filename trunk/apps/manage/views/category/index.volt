<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/manage/css/style.css" />
<script type="text/javascript" src='{{ constant('STATIC_URL') }}mdg/manage/js/utils.js'></script>
{{ content() }}
<div class="main">
  <div class="main_right">
    <div class="bt2">分类列表</div>
    {{ form("category/index", "method":"get", "autocomplete" : "off") }}
    <div class="chaxun">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td  height="35" align="right">分类名称：</td>
          <td  height="35" align="left">
            <input type="text" name='title' id='title' value='<?php if(isset($_GET['title'])){ echo $_GET['title'];}?>'></td>
        </tr>
      </table>
      <div class="btn">{{ submit_button("搜索",'class':'sub') }}</div>
    </div>
  </form>
  <form method="post" action="" name="listForm">
    <div class="neirong" id="listDiv">
      <table width="100%" border="0" cellspacing="0" cellpadding="0" id="list-table">
        <tr align="center">
          <td width='10%' class="bj">分类名称</td>
          <td width='14%' class="bj">采购信息数量</td>
          <td width='14%' class="bj">供应信息数量</td>
          <td width='8%' class="bj" >导航显示</td>
          <td width='8%' class="bj" >首页推荐</td>
          <td width='14%' class="bj">排序</td>
          <td width='8%' class="bj">操作</td>
        </tr>
      </table>
      {% for key,val in data %}
        <!-- 一级分类循环整个table -->

        <table cellpadding="0" cellspacing="0" width="100%" class="yiji">
          <tr height="30">
                <td width="10%"><span class="has_erji">{{ val['title'] }}</span></td>
                <td width="14%">{{ val['pur_num'] }}</td>
                <td width="14%">{{ val['sell_num'] }}</td>
                <td width="8%">
                    {% if val['is_show']=="1" %}
                    <img  src="{{ constant('STATIC_URL') }}mdg/manage/images/yes.gif" onclick="toggle(this,'{{val['id']}}','is_show','1')" >
                    {% else %}
                    <img src="{{ constant('STATIC_URL') }}mdg/manage/images/no.gif" onclick="toggle(this,'{{val['id']}}','is_show','1')">
                    {% endif %}
                </td>
                <td width="8%">
                  {% if val['is_groom']=="1" %}
                  <img src="{{ constant('STATIC_URL') }}mdg/manage/images/yes.gif" onclick="toggle(this,'{{val['id']}}','is_groom','1')">
                  {% else %}
                  <img src="{{ constant('STATIC_URL') }}mdg/manage/images/no.gif" onclick="toggle(this,'{{val['id']}}','is_groom','1')">
                  {% endif %}
                </td>
                <td width="14%">{{ val['deeps'] }}</td>
                <td width="8%">
                  {{ link_to("category/edit/"~val['id'], "编辑") }}
                  <a href='javascript:remove_agreement({{ val['id'] }})' >删除</a>
                  <a href="#" onclick="javascript:newWindows('{{val['id']}}', '转移商品', '/manage/category/chanage/{{val['id']}}')">转移商品</a>
                </td>
            </tr>
            <tr style="display:none;">
              <td colspan="7">
                  <table cellpadding="0" cellspacing="0" width="100%" class="erji">
                      <!-- 二级分类循环下面这部分 -->
                      {% if val['child'] %}
                      {% for key2,val2 in val["child"] %}
                      <tr>
                              <td width="10%">&nbsp;&nbsp;&nbsp;<span class="has_sanji">{{val2["title"]}}</span></td>
                              <td width="14%">{{ val2['pur_num'] }}</td>
                              <td width="14%">{{ val2['sell_num'] }}</td>
                              <td width="8%">
                                  {% if val2['is_show']=="1" %}
                                  <img  src="{{ constant('STATIC_URL') }}mdg/manage/images/yes.gif" onclick="toggle(this,'{{val2['id']}}','is_show','2')" >
                                  {% else %}
                                  <img src="{{ constant('STATIC_URL') }}mdg/manage/images/no.gif" onclick="toggle(this,'{{val2['id']}}','is_show','2')">
                                  {% endif %}
                              </td>
                              <td width="8%">
                                {% if val2['is_groom']=="1" %}
                                <img src="{{ constant('STATIC_URL') }}mdg/manage/images/yes.gif" onclick="toggle(this,'{{val2['id']}}','is_groom','2')">
                                {% else %}
                                <img src="{{ constant('STATIC_URL') }}mdg/manage/images/no.gif" onclick="toggle(this,'{{val2['id']}}','is_groom','2')">
                                {% endif %}
                              </td>
                              <td width="14%">{{ val2['deeps'] }}</td>
                              <td width="8%">
                                {{ link_to("category/edit/"~val2['id'], "编辑") }}
                                <a href='javascript:remove_agreement({{ val2['id'] }})' >删除</a>
                                <a href="#" onclick="javascript:newWindows('{{val2['id']}}', '转移商品', '/manage/category/chanage/{{val2['id']}}')">转移商品</a>
                              </td>
                        </tr>
                        <tr style="display:none;">
                          <td colspan="7">
                              <table cellpadding="0" cellspacing="0" width="100%" class="sanji">
                                  <!-- 三级分类(循环下面这个tr) -->
                                   {% if val2['child'] %}
                                     {% for key3,val3 in val2["child"] %}
                                      <tr>
                                          <td width="10%" colspan="7">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{val3["title"]}}</td>
                                            <td width="14%">{{ val3['pur_num'] }}</td>
                                            <td width="14%">{{ val3['sell_num'] }}</td>
                                            <td width="8%">
                                                {% if val3['is_show']=="1" %}
                                                <img  src="{{ constant('STATIC_URL') }}mdg/manage/images/yes.gif" onclick="toggle(this,'{{val3['id']}}','is_show','3')" >
                                                {% else %}
                                                <img src="{{ constant('STATIC_URL') }}mdg/manage/images/no.gif" onclick="toggle(this,'{{val3['id']}}','is_show','3')">
                                                {% endif %}
                                            </td>
                                            <td width="8%">
                                              {% if val3['is_groom']=="1" %}
                                              <img src="{{ constant('STATIC_URL') }}mdg/manage/images/yes.gif" onclick="toggle(this,'{{val3['id']}}','is_groom','3')">
                                              {% else %}
                                              <img src="{{ constant('STATIC_URL') }}mdg/manage/images/no.gif" onclick="toggle(this,'{{
                                              val3['id']}}','is_groom','3')">
                                              {% endif %}
                                            </td>
                                            <td width="14%">{{ val3['deeps'] }}</td>
                                            <td width="8%">
                                              {{ link_to("category/edit/"~val3['id'], "编辑") }}
                                              <a href='javascript:remove_agreement({{ val3['id'] }})' >删除</a>
                                              <a href="#" onclick="javascript:newWindows('{{val3['id']}}', '转移商品', '/manage/category/chanage/{{val3['id']}}')">转移商品</a>
                                            </td>
                                        </tr>
                                      {% endfor %}
                                    <!-- 三级分类(循环上面这个tr)-->
                                   {% endif %}
                                </table>
                            </td>
                        </tr>
                        <!-- 二级分类循环上面这部分 -->
                        {% endfor %}
                        {% endif %}
                    </table>
                </td>
            </tr>
        </table>
        <!-- 一级分类循环整个table -->
        {% endfor %}
    </div>
  </form>
</div>
<!-- main_right 结束  -->

</div>
<script type="text/javascript" src="{{ constant('JS_URL') }}lhgdialog/lhgdialog.min.js?skin=igreen"></script>
<!--转移商品的js-->
<style>
td{ border:solid 1px #ccc;}

/* 有用的样式 注释里的样式可以拿走 其他的不用拿 */
.has_erji{ background:url(http://yncstatic.b0.upaiyun.com/mdg/manage/images/menu_plus.gif) no-repeat; padding-left:20px; cursor:pointer;}
.has_sanji{ background:url(http://yncstatic.b0.upaiyun.com/mdg/manage/images/menu_plus.gif) no-repeat; padding-left:20px; cursor:pointer;}
.show_ji{ background:url(http://static.ync365.com/mdg/manage/images/menu_minus.gif) no-repeat;}
.erji, .sanji{ display:none;}
</style>
<script>
$(function(){
  $('.has_erji').click(function(){
    if($(this).hasClass('show_ji')){
      $(this).removeClass('show_ji');
      $(this).parents('tr').next('tr').hide();
      $(this).parents('tr').next('tr').find('.erji').hide();
    }else{
      $(this).addClass('show_ji');
      $(this).parents('tr').next('tr').show();
      $(this).parents('tr').next('tr').find('.erji').css('display', 'table');
    };
  });
  $('.has_sanji').click(function(){
    if($(this).hasClass('show_ji')){
      $(this).removeClass('show_ji');
      $(this).parents('tr').next('tr').hide();
      $(this).parents('tr').next('tr').find('.sanji').hide();
    }else{
      $(this).addClass('show_ji');
      $(this).parents('tr').next('tr').show();
      $(this).parents('tr').next('tr').find('.sanji').css('display', 'table');
    };
  });
});
</script>

<script>
var dialog = null;
function newWindows(id,title,url) {
            dialog = $.dialog({
            id    : id,
            title : title,
            min   : false,
            max   : false,
            lock  : true,
            width : 600,
            content: 'url:'+url,
       });
   
}
function toggle(obj,id,type,deep){
   var state = (obj.src.match(/yes.gif/i)) ? 0 : 1;
  $.get('/manage/category/toggle', {id:id,type:type,deep:deep,state:state}, function(data) {
     location.reload();
  });
}
function closeDialog(){

    dialog.close();
}
 function remove_agreement(id) 
 {  
    if(confirm("您确定要删除吗?"))
    location.href='/manage/category/delete/'+id;
 }
</script>
<div class="footer">Copyright © 2013-2014 ync365.com All rights reserved.</div>
</body>
</html>