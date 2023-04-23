{{ content() }}

<div align="right">

</div>
<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/manage/css/style.css" />
<div class="main">
    <div class="main_right">
        <div class="bt2">直营轮播广告管理</div>
        <!-- {{ form("ad/index", "method":"post", "autocomplete" : "off") }}

        <div align="center">
            <h1>供应信息</h1>
        </div>

        <table>
           
           

            <tr>
                <td align="right">
                    <label for="createtime">添加时间：</label>
                </td>
                <td align="left">
                     {{ text_field("stime") }}
                    {{ text_field("etime") }}
                </td>
            </tr>
             <tr>
                <td align="right">
                    <label for="title">广告位标题</label>
                </td>
                <td align="left">
                        {{ text_field("title") }}
                </td>
            </tr>
            <tr>
                <td></td>
                <td>{{ submit_button("确定") }}</td>
            </tr>
        </table>

        </form> -->
            <div class="neirong" id="tb">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr align="center">
                  <td width='6%'  class="bj">广告位编号</td>
                  <td width='10%' class="bj">广告位说明</td>
                  <td width='18%' class="bj">广告位标题</td>
                  <td width='10%' class="bj">广告位链接</td>
                  <td width='10%' class="bj">是否显示</td>
                  <td width='8%' class="bj">添加时间</td>
                  <td width='8%' class="bj">操作</td>
            </tr>
            {% if page.items is defined %}
            {% for ad in page.items %}
                <tr align="center" >
                    <td>{{ ad.id }}</td>
                    <td>{{ ad.addesc }}</td>
                    <td>{{ ad.adtitle }}</td>
                    <td>{{ ad.adsrc }}</td> 
                    <td>{{ ad.is_show ? "是" : "否" }} </td>
                    <td>{{ date('Y-m-d H:i:s', ad.addtime) }}</td>
                    <td>{{ link_to("ad/edit/"~ad.id, "修改") }}</td>
                </tr>
            {% endfor %}
            {% endif %}
     </table>
</div>
<div class="fenye">
    <div class="fenye1">
        <span>{{ pages }}</span>
    </div>
</div>
</div>
<!-- main_right 结束  -->

</div>
<div class="footer">Copyright © 2013-2014 ync365.com All rights reserved.</div>
</body>
</html>
<script type="text/javascript" src="{{ constant('JS_URL') }}jquery/ld-select.js"></script>
<script type="text/javascript" src="{{ constant('JS_URL') }}jquery/jquery-ui.min.js"></script>
<script type="text/javascript" src="{{ constant('JS_URL') }}jquery/timepicker/jquery-ui-timepicker-addon.min.js"></script>
<script type="text/javascript" src="{{ constant('JS_URL') }}jquery/timepicker/i18n/jquery-ui-timepicker-zh-CN.js"></script>
<link rel="stylesheet" type="text/css" href="{{ constant('JS_URL') }}jquery/jquery-ui.css" />
<link rel="stylesheet" type="text/css" href="{{ constant('JS_URL') }}jquery/timepicker/jquery-ui-timepicker-addon.min.css" />
<script>
$(function(){
   $("#stime").datetimepicker();
   $("#etime").datetimepicker();
   $(".selectCate").ld({ajaxOptions : {"url" : "/ajax/getcate"},
    defaultParentId : 0,
    style : {"width" : 140}
   });
});
 $('.state').on('click', function(event) {
      event.preventDefault();
      obj = $(this);
      url = ($(this).attr('data-url'));
      $.get(url, function(data) {
       
       console.log(obj); 
        obj.text(data);
        location.reload(); 
      });
  });
$(document).ready(function () {        
    //按钮样式切换
    $(".neirong tr").mouseover(function(){    
    //如果鼠标移到class为stripe的表格的tr上时，执行函数    
    $(this).addClass("over");}).mouseout(function(){    
    //给这行添加class值为over，并且当鼠标一出该行时执行函数    
    $(this).removeClass("over");}) //移除该行的class    
    $(".neirong tr:even").addClass("alt");    
    //给class为stripe的表格的偶数行添加class值为alt 
});

$(document).ready(function () {        
    //按钮样式切换
    $(".btn>input").hover(
    function () {
    $(this).removeClass("sub").addClass("sub1");
    },
    function () {
    $(this).removeClass("sub1").addClass("sub"); 
    }
    );
});
</script>