<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/manage/css/style.css" />
<div class="main">
  <div class="main_right">
    <div class="bt2">采购列表</div>

    <div class="chaxun">
      {{ form("purchase/index", "method":"get", "autocomplete" : "off") }}
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td  height="35" align="right">状态：</td>
          <td  height="35" align="left">
            <select name="state">
              <option value="0">全部</option>
              {% for key,stat in  state2 %}
              <option value="{{key+1}}" {% if status == key+1 %}selected='selected'{% endif %}>{{stat}}</option>
              {% endfor %}
              <option value="99" {% if status == 99 %}selected='selected'{% endif %} >删除</option>
            </select>
          </td>
          <td  height="35" align="right">所属分类：</td>
          <td height="35" align="left">
            <select name="maxcategory" class="selectCate">
              <option value="0">选择分类</option>
            </select>
            <select name="category" class="selectCate">
              <option value="0">选择分类</option>
            </select>
          </td>
        </tr>
        <tr>
          <td  width="15%" height="35" align="right">采购编号：</td>
          <td  width="35%" height="35" align="left">
            {{ text_field("pur_sn", 'class':'t1',"type" : "numeric","value":pur_sn) }}
          </td>
          <td  height="35" align="right">添加时间：</td>
          <td height="35" align="left">
            <input readonly="readonly"  type="text" class="Wdate" name="stime" id="d4331" onfocus="WdatePicker({maxDate:'#F{$dp.$D(\'d4332\',{M:0,d:0})}'})" value="{{stime}}">
            -
            <input readonly="readonly" type="text" class="Wdate" name="etime"  id="d4332" onfocus="WdatePicker({minDate:'#F{$dp.$D(\'d4331\',{M:0,d:1});}',maxDate:'2020-4-3'})"
                       value="{{etime}}"></td>
        </tr>
        <tr>
          <td  width="15%" height="35" align="right">采购商：</td>
          <td  width="35%" height="35" align="left">{{ text_field("title",'class':'t1',"value":title) }}</td>
          <td  height="35" align="right">推荐：</td>
          <td height="35" align="left">
            <input type="checkbox" name="location_home" value="1" <?php if(isset($_GET['location_home']) && $_GET['location_home'] == 1){ echo "checked='checked'";}?>>首页&nbsp;&nbsp;
                      <input type="checkbox" name="location_category" value="1" <?php if(isset($_GET['location_category']) && $_GET['location_category'] == 1){ echo "checked='checked'";}?>>分类列表
          </td>
        </tr>
      </table>
      <div class="btn">{{ submit_button("确定",'class':'sub') }}</div>
    </div>
  </form>
  <div class="neirong" id="tb">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr align="center">
        <td width='10%'  class="bj">序号</td>
        <td width='10%' class="bj">采购编号</td>
        <td width='24%' class="bj">商品名称</td>
        <td width='10%' class="bj">所属分类</td>
        <td width='8%' class="bj">采购商</td>
        <td width='8%' class="bj">添加时间</td>
        <td width='6%' class="bj">状态</td>
        <td width='8%' class="bj">点击量</td>
        <td width='6%' class="bj">推荐</td>
        <td width='8%' class="bj">操作</td>
      </tr>
      <?php $i=($current-1)*10+1 ?>
      {% if data is defined %}
            {% for purchase in data %}
      <tr align="center">
        <td>
          <?php echo $i++ ;?></td>
        <td>{{ purchase.pur_sn }}</td>
        <td>{{ purchase.title }}</td>
        <td>
          <?php if($purchase->
          category){echo Mdg\Models\PurchaseContent::getcategory($purchase->category); }else{echo "-";} ?>
        </td>
        <td>{{ purchase.username ? purchase.username : '-' }}</td>
        <td>{{ date('Y-m-d H:i:s', purchase.createtime) }}</td>
        <td>
          {% if purchase.is_del %}用户已删除{% else %}{{ state2[purchase.state] }}{% endif %}
        </td>
        <td>{{ purchase.clicknum}}</td>
        <td>
              {% if purchase.state==1 and purchase.is_del==0 %}
              <?php $recommand = Mdg\Models\RecommandPurchase::recommandtype($purchase->id); 
              
                    //$parend_id = Mdg\Models\Category::getFamily($purchase->category); 
                    //if($parend_id && ($parend_id[0]['id']==1 || $parend_id[0]['id']==2 || $parend_id[0]['id']==7)){ 
                    if($purchase->maxcategory==1 || $purchase->maxcategory==2 || $purchase->maxcategory==7){
                      if($recommand && $recommand['location_home']==1)
                      {echo "<input type='checkbox' name='recommand_type' checked='checked' onchange='recommand($purchase->id,1)'>首页".'<br>';}
                      else{echo "<input type='checkbox' name='recommand_type' onchange='recommand($purchase->id,1)'>首页".'<br>';}
                    } 
              ?>

              <input type="checkbox" name="recommand_type" <?php $recommand = Mdg\Models\RecommandPurchase::recommandtype($purchase->id); if($recommand['location_category']==1){ echo "checked='checked'";} ?> onchange="recommand({{purchase.id}},2)">分类列表
              {% endif %}
            </td>
        <td>

          {% if purchase.state == 0 and purchase.is_del == 0  %}
          <a href="/manage/purchase/auditorpass/{{purchase.id}}/{{current}}" >审核</a>

          {{ link_to("purchase/delete/"~purchase.id, "删除") }}
          {% else %}
              {% if  purchase.state == 1 %}
            <a href='/manage/purchase/upstate/{{purchase.id}}'  class="state">取消审核</a>
            {% endif %}
          {% endif %}
          <a href="/manage/purchase/edit/{{purchase.id}}/{{current}}" >修改</a>
        </td>
      </tr>
      {% endfor %}
            {% endif %}
    </table>
  </div>
  {{ form("purchase/index", "method":"get") }}
  <div class="fenye">
    <div class="fenye1">
      <span>{{ pages }}</span> <em>跳转到第
        <input type="text" class='input' name='p' <?php if(isset($_GET['p'])&&$_GET['p']!=''){ echo "value='".$_GET['p']."'" ;}else{ echo "value='1'"; } ?>/>页</em>
          <?php unset($_GET['p']);
              foreach ($_GET as $key => $val) {

          echo "<input type='hidden' name='{$key}' value='{$val}'>";
        }?>
      <input class="sure_btn"  type='submit' value='确定'></div>
  </div>
</form>
</div>
<!-- main_right 结束  -->

</div>
<div class="footer">Copyright © 2013-2014 ync365.com All rights reserved.</div>
</body>
</html>

<script>
//推荐
function recommand(id,type){

  if(window.confirm('您确定要进行此操作吗?')){
  $.getJSON("/manage/purchase/recommand/", {'id':id , 'type':type} ,function(data){
      alert(data);

       });
  }
}

$(function(){

   $(".selectCate").ld({ajaxOptions : {"url" : "/ajax/getcate"},
    defaultParentId : 0,
    {% if cat_name %}
    texts : [{{ cat_name }}],
    {% endif %}
    style : {"width" : 140}
   });
});
 // $('.state').on('click', function(event) {
 //      event.preventDefault();
 //      obj = $(this);
 //      url = ($(this).attr('data-url'));
 //      $.get(url, function(data) {
 //        var current={{current}};
 //        location.href="/manage/purchase/index?p="+current; 
 //      });
 //  });
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