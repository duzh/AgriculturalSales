<link rel="stylesheet" type="t_GET/css" href="{{ constant('STATIC_URL') }}mdg/manage/css/style.css" />
<div class="main">
  <div class="main_right">
    <div class="bt2">货源列表</div>

    <div class="chaxun">
      {{ form("cargo/index", "method":"get", "autocomplete" : "off") }}
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td  width="15%" height="35" align="right">出发地：</td>
          <td  width="35%" height="35" align="left">
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
          <td  height="35" align="right">目的地：</td>
          <td height="35" align="left">
            <select name="end_pid" class='endselectAreas' id="">
              <option value="">请选择</option>
            </select>
            <select name="end_cid" class='endselectAreas' id="">
              <option value="">请选择</option>
            </select>
            <select name="end_did" class='endselectAreas' id="">
              <option value="">请选择</option>
            </select>

          </td>
        </tr>
        <tr>
          <td  width="15%" height="35" align="right">货物种类：</td>
          <td  width="35%" height="35" align="left">
            <select name="goods_type" id="goods_type" class="sel w sel-province">
              <option value="all">请选择</option>
              {% for key , item in _BOX_TYPE %}
              <option value="{{ key }}"  <?php echo isset($_GET['goods_type'])&&$_GET['goods_type'] == "$key" ? 'selected' : '';?>>{{ item }}</option>
              {% endfor %}
            </select>
          </td>
        </tr>

      </table>
      <div class="btn">{{ submit_button("确定",'class':'sub') }}</div>
    </div>
  </form>

  <div class="neirong" id="tb">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr align="center">
        <td width='3%'  class="bj"><input type="checkbox" id='check_all'>序号</td>
        <td width='3%' class="bj">编号</td>
        <td width='10%' class="bj">出发地</td>
        <td width='10%' class="bj">目的地</td>
        <td width='8%' class="bj">货物</td>
        <td width='14%' class="bj">重量</td>
        <td width='8%' class="bj">体积</td>
        <td width='8%' class="bj">联系人</td>
        <td width='8%' class="bj">手机号码</td>
        <td width='8%' class="bj">添加时间</td>
        <td width='8%' class="bj">操作</td>
      </tr>

      <form action="/manage/cargo/removeAll" method="post" name='remove' >
  <input type="button" value='批量删除' id='removeBtn' >

      {% for key, item in data['items'] %}
      <tr align='center'>
        <td ><input type="checkbox" name='remove[]' value='{{ item['goods_id']}}'> 
          {{ data['start'] + key + 1}}</td>
        <td >{{ item['goods_no']}}</td>
        <td >{{ item['start_pname'] }}{{ item['start_cname'] }}{{ item['start_dname'] }}</td>
        <td >{{ item['end_pname'] }}{{ item['end_cname'] }}{{ item['end_dname'] }}</td>
        <td >{{ item['goods_name']}}</td>
        <td >{{ item['goods_weight']}}</td>
        <td >{{ item['goods_size']}}</td>
        <td >{{ item['contact_man'] }}</td>
        <td >{{ item['contact_phone']}}</td>
        <td >{{ date('Y-m-d H:i:s', item['add_time'] ) }}</td>
        <td >
          <a href="/manage/cargo/get/{{ item['goods_id']}}">查看详细</a>
          <a href="/manage/cargo/edit/{{ item['goods_id']}}">修改</a>
          <a href="javascript:;" onclick="del({{item['goods_id']}})">删除</a>
        </td>
      </tr>
      {% endfor %}
      </form>

    </table>
  </div>
  {{ form("cargo/index", "method":"get") }}
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

</div>
{{ partial('layouts/bottom') }}
</body>
</html>
<SCRIPT language=javascript>
$('#check_all').click(function () {

  if($(this).prop('checked')) {
    $('input[name="remove[]"]').prop('checked' , true);
  }else{
    $('input[name="remove[]"]').prop('checked' , false);
  }

})

        function del(id) {  
             if(window.confirm('你确定要删除该记录！')){
                 window.location="/manage/cargo/delete/"+id+"";
                 return true;
              }else{
                 //alert("取消");
                 return false;
             }
          }//del end
</SCRIPT>
<script>
$(function(){

$(".selectAreas").ld({ajaxOptions : {"url" : "/ajax/getareasfull"},
    defaultParentId : 0,
    {% if start_areas %}
    texts:['{{ start_areas}}'],
    {% endif %}
    style : {"width" : 140}
});
//  目的地
$(".endselectAreas").ld({ajaxOptions : {"url" : "/ajax/getareasfull"},
    defaultParentId : 0,
   {% if end_areas %}
    texts:['{{ end_areas}}'],
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