<link rel="stylesheet" type="t_GET/css" href="{{ constant('STATIC_URL') }}mdg/manage/css/style.css" />
<div class="main">
  <div class="main_right">
    <div class="bt2">价格行情列表</div>

    <div class="chaxun" style="width:100%;">
      {{ form("quotation/index", "method":"get", "autocomplete" : "off") }}
      <table border="0" cellspacing="0" cellpadding="0" width="100%">
        <tr height="80">
          <td align="right">出发地：</td>
          <td align="left">
            <select name="start_pid" class='selectAreas' id="">
              <option value="">请选择</option>
            </select>
            <select name="start_cid" class='selectAreas' id="">
              <option value="">请选择</option>
            </select>

            <select name="start_did" class='selectAreas' id="">
              <option value="">请选择</option>
            </select>
          </td>

          <td align="left" width="30%">
             <div>发布日期：<input type="text" id="expire_time" name="expire_time" value="<?php echo isset($_GET['expire_time']) && $_GET['expire_time'] ? $_GET['expire_time'] : '';?>"></div>
             <div style="margin-top:8px;">结束日期：<input type="text" id="expire_etime" name="expire_etime" value="<?php echo isset($_GET['expire_etime']) && $_GET['expire_etime'] ? $_GET['expire_etime'] : '';?>"></div>
          </td>
          </td>
        </tr>

        <tr height="50">
          <td align="right">产品分类:</td>
          <td >
                <select class="selectCate" name="category_one">
                        <option value="">选择分类</option>
                </select>
                <select name="category" class="selectCate">
                        <option value="">选择分类</option>
                </select>
          </td>
          <td >
              是否采集:
                <select name="is_long">
                        <option value="All" <?php echo isset($_GET['is_long']) && $_GET['is_long']=="All" ? 'selected' : '';?>>请选择</option>
                        <option value="1" <?php echo isset($_GET['is_long']) && $_GET['is_long']==1 ? 'selected' : '';?>>是</option>
                        <option value="0" <?php echo isset($_GET['is_long']) && $_GET['is_long']=='0' ? 'selected' : '';?>>否</option>
                </select>
          </td>

        </tr>

      </table>
      <div class="btn">{{ submit_button("确定",'class':'sub') }}</div>
    </div>
  </form>
<!--   <a href="/manage/cargo/new">新增货源</a> -->
  <div class="neirong" id="tb">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr align="center">
        <td width='3%'  class="bj">序号</td>
        <td width='3%' class="bj">产品名</td>
        <td width='10%' class="bj">地区/市场</td>
        <td width='10%' class="bj">报价/单位</td>
        <td width='8%' class="bj">发布时间</td>
        <td width='14%' class="bj">操作</td>

      </tr>
      {% for key, item in data['items'] %}
      <tr align='center'>
        <td >{{ data['start'] + key + 1}}</td>
        <td >{{ item['goods_name']}}</td>
        <td >{{ item['province_name']}} {{ item['city_name'] }} {{ item['market_name'] }}</td>
        <td >{{ item['price']}}/{{ item['unit'] ? item['unit'] : '斤'}}</td>
        <td >{{ date('Y-m-d H:i:s', item['publish_time'] ) }}</td>
        <td >
          <a href="/manage/quotation/get/{{ item['id']}}">详细</a>
<!--           <a href="/manage/quotation/update/{{ item['id']}}">修改</a> -->
<!--           <a href="javascript:;" onclick="del({{item['goods_id']}})">删除</a> -->
        </td>
      </tr>
      {% endfor %}
    </table>
  </div>
  {{ form("quotation/index", "method":"get") }}
  <div class="fenye">
    <div class="fenye1">
      <span>{{ data['pages'] }}</span> <em>跳转到第
        <input type="t_GET" class='input' name='p' <?php if(isset($_GET['p'])&&$_GET['p']!=''){ echo "value='".$_GET['p']."'" ;}else{ echo "value='1'"; } ?>/>页</em>
      <?php unset($_GET['p']);
              foreach ($_GET as $key =>
      $val) {
          echo "<input type='hidden' name='{$key}' value='{$val}'>";
        }?>
      <input class="sure_btn"  type='submit' value='确定'></div>
  </div>
</form>
</div>
<!-- main_right 结束  -->
<script type="text/javascript" src="{{ constant('JS_URL') }}jquery/ld-select.js"></script>
<script type="text/javascript" src="{{ constant('JS_URL') }}jquery/jquery-ui.min.js"></script>
<script type="text/javascript" src="{{ constant('JS_URL') }}jquery/timepicker/jquery-ui-timepicker-addon.min.js"></script>
<script type="text/javascript" src="{{ constant('JS_URL') }}jquery/timepicker/i18n/jquery-ui-timepicker-zh-CN.js"></script>
<link rel="stylesheet" type="text/css" href="{{ constant('JS_URL') }}jquery/jquery-ui.css" />
<link rel="stylesheet" type="text/css" href="{{ constant('JS_URL') }}jquery/timepicker/jquery-ui-timepicker-addon.min.css" />
<link rel="stylesheet" type="text/css" href="http://static.ync365.com/mdg/css/uibase.css" />
<link rel="stylesheet" type="text/css" href="{{ constant('JS_URL') }}validator/jquery.validator.css" />
<script type="text/javascript" src="{{ constant('JS_URL') }}validator/jquery.validator.js"></script>
<script type="text/javascript" src="{{ constant('JS_URL') }}validator/local/zh_CN.js"></script>
<script type="text/javascript" src="{{ constant('JS_URL') }}lhgdialog/lhgdialog.min.js?skin=igreen"></script>

</div>
{{ partial('layouts/bottom') }}
</body>
</html>
<SCRIPT language=javascript>
        function del(id) {  
             if(window.confirm('你确定要删除该记录！')){
                 window.location="/manage/quotation/delete/"+id+"";
                 return true;
              }else{
                 //alert("取消");
                 return false;
             }
          }//del end
</SCRIPT>
<script>
$(function(){

$('#expire_time').datepicker();
$('#expire_etime').datepicker();

$(".selectCate").ld({ajaxOptions : {"url" : "/ajax/getcate"},
    defaultParentId : 0,
    {% if catname %}
    texts:[{{ catname }}],
    {% endif %}
    style : {"width" : 140}
});

$(".selectAreas").ld({ajaxOptions : {"url" : "/ajax/getareasfull"},
    defaultParentId : 0,
    {% if start_areas %}
    texts:['{{ start_areas}}'],
    {% endif %}
    style : {"width" : 140}
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