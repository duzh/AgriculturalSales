<style>
    .state{
        cursor: pointer;
    }

</style>

{{ form("/sell/index", "method":"get") }}
<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/manage/css/style.css" />
<div class="main">
    <div class="main_right">
        <div class="bt2">供应列表</div>
        {{ form("/sell/index", "method":"get", "autocomplete" : "off") }}
        <div class="chaxun">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td  height="35" align="right">商品分类：</td>
                    <td  height="35" align="left">
                        <select name="maxcategory" class="selectCate">
                            <option value="">选择分类</option>
                        </select>
                        <select name="category" class="selectCate">
                            <option value="">选择分类</option>
                        </select>
                    </td>
                    <td  height="35" align="right">是否有图：</td>
                    <td height="35" align="left">
                        <select name="is_img">
                            <option value="">全部</option>
                            <option value="1" {% if is_img =='1' %}selected="selected"{% endif %}>有</option>
                            <option value="2" {% if is_img =='2' %}selected="selected"{% endif %}>无</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td  width="15%" height="35" align="right">会员信息：</td>
                    <td  width="35%" height="35" align="left">{{ text_field("username",'value':username) }}</td>
                    <td  height="35" align="right">添加时间：</td>
                    <td height="35" align="left">
                      <input readonly="readonly"  type="text" class="Wdate" name="addstime" id="d4331" 
                      onfocus="WdatePicker({maxDate:'#F{$dp.$D(\'d4332\',{M:0,d:0})}',dateFmt:'yyyy-MM-dd HH:mm:ss'})" value="{{stime}}">-
                      <input readonly="readonly" type="text" class="Wdate" name="addetime"  id="d4332" onfocus="WdatePicker({minDate:'#F{$dp.$D(\'d4331\',{M:0,d:1});}',maxDate:'2020-4-3',dateFmt:'yyyy-MM-dd HH:mm:ss'})"
                       value="{{etime}}">
                    </td>
                </tr>
                <tr>
                    <td  width="15%" height="35" align="right">商品名称：</td>
                    <td  width="35%" height="35" align="left">{{ text_field("sellname",'value':sellname) }}</td>
                    <td  width="15%" height="35" align="right">商品编号：</td>
                    <td  width="35%" height="35" align="left">{{ text_field("sellsn",'value':sellsn) }}</td>
                </tr>
                <tr>
                    <td  width="15%" height="35" align="right">审核状态：</td>
                    <td  width="35%" height="35" align="left">

                        <select name="state">
                             {% for key,val in sellstate %}
                            <option value="{{key}}" {% if sellsatues == key %} selected="selected" {% endif %}>{{val}}</option>
                            {% endfor %}
                        </select>
                    </td>
                    <td  height="35" align="right">随机时间：</td>
                    <td height="35" align="left">
                      <input readonly="readonly"  type="text" class="Wdate" name="stime" id="d4334" onfocus="WdatePicker({maxDate:'#F{$dp.$D(\'d4335\',{M:0,d:0})}',dateFmt:'yyyy-MM-dd HH:mm:ss'})" >-
                      <input readonly="readonly" type="text" class="Wdate" name="etime"  id="d4335" onfocus="WdatePicker({minDate:'#F{$dp.$D(\'d4334\',{M:0,d:1});}',maxDate:'2020-4-3',dateFmt:'yyyy-MM-dd HH:mm:ss'})"
                      >
                    </td>

                </tr>

                <tr>
                    <td  width="15%" height="35" align="right">是否申请标签：</td>
                    <td  width="35%" height="35" align="left">
                        <select name="is_source">
                            <option value="">全部</option>
                            <option value="1" {% if is_source =='1' %}selected="selected"{% endif %}>已申请</option>
                            <option value="2" {% if is_source =='2' %}selected="selected"{% endif %}>未申请</option>
                        </select>
                    </td>

                    <td  height="35" align="right">推荐：</td>
                    <td height="35" align="left">
                      <input type="checkbox" name="location_home" value="1" <?php if(isset($_GET['location_home']) && $_GET['location_home'] == 1){ echo "checked='checked'";}?>>首页&nbsp;&nbsp;
                      <input type="checkbox" name="location_category" value="1" <?php if(isset($_GET['location_category']) && $_GET['location_category'] == 1){ echo "checked='checked'";}?>>分类列表&nbsp;&nbsp;
                      <input type="checkbox" name="location_hot" value="1" <?php if(isset($_GET['location_hot']) && $_GET['location_hot'] == 1){ echo "checked='checked'";}?>>热门&nbsp;&nbsp;
                    </td>

                </tr>
                <tr>
                  <td height="35" align="right" style="width:80px;">发布平台：</td>
                    <td  height="35" align="left">
                      <select name="plat" style="width:80px;">
                        {% for key,val in plat %}
                          {% if key !=3 %}
                          <option value="{{ key }}" {% if key == plat_param %} selected {% endif %}>{{ val }}</option>
                          {% endif %}
                        {% endfor %}
                      </select>
                  </td>
                   <td  width="15%" height="35" align="right">供应属性：</td>
                    <td  width="35%" height="35" align="left">
                      <select name="orderattribute" class="selectCate">
                        <option value="0" >全部供应</option>
                        <option value="1" {% if orderattribute == '1' %} selected {% endif %}>经纪人供应</option>
                        <option value="2" {% if orderattribute == '2' %} selected {% endif %}>普通会员供应</option>
                      </select>

                    </td>
                </tr>
         
            </table>
            <div class="btn">{{ submit_button("确定","class":'sub') }}</div>
        </div>
    </form>
    <div class="neirong" id="tb">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr align="center">
                <td width='6%'  class="bj"><input type='checkbox' id="checkAll"  >序号</td>
                <td width='10%' class="bj">供应编号</td>
                <td width='10%' class="bj">商品名称</td>
                <td width='10%' class="bj">会员信息</td>
                <td width='14%' class="bj">添加时间</td>
                <td width='8%' class="bj">是否有图</td>
                <td width='5%' class="bj">标签</td>
                <td width='5%' class="bj">可信农场</td>
                <!-- <td width='8%' class="bj">热门推荐</td> -->
                <td width='5%' class="bj">点击量</td>
                <td width='8%' class="bj">状态</td>
                <td width='8%' class="bj">推荐</td>
                <td width='8%' class="bj">发布平台</td>
                <td width='20%' class="bj">操作</td>
            </tr>
            <?php $i=($current-1)*10+1 ?>

            {% if data %}
                    {% for sell in data %}
            <tr align="center">
                <td><input type="checkbox" name="subBox[]" value='{{sell['id']}}'><?php echo $i++ ;?></td>
                <td>{% if sell['is_broker']==1 %}【直】{% endif %}{{ sell['sell_sn'] }}</td>
                <td>{{ sell['title'] }}</td>
                <td>{{ sell['uname'] }}</td>
                <td>{{ date("Y-m-d H:i:s",sell['createtime']) }}</td>
                <td>
                <?php if($sell['thumb']){echo "有";}else{echo "无";}?></td>
            <td>
             <?php $tag=Mdg\Models\Tag::checkSell($sell['id']); ?>
             {% if tag %}<a href="/manage/tag/info/<?php echo  $tag->tag_id ?>">查看标签</a>{% else %}暂无{% endif %}
            </td>
            <td>
            <?php if($sell['uid']){ $iskexin=Mdg\Models\UserInfo::selectBycredit_type($sell['uid'],8); echo $iskexin>0 ? "是":"否"; } ?>
            </td>
            <td>{{ sell['count']}}</td>
            <td id="deletesell{{sell['id']}}">{% if sell['is_del']==0  %}{% if (sell['state']== 1 ) %}已发布{% endif %}
              {% if (sell['state']== 2 )%} 审核失败 {% endif %}{% if (sell['state']== 0 ) %}待审核{% endif %}{% else %}已删除{% endif %}</td>

            <td>
              {% if sell['state']==1 %}
              <?php $recommand = Mdg\Models\RecommandSell::recommandtype($sell['id']); 
                    $parend_id = Mdg\Models\Category::getFamily($sell['category']); 
                    if($parend_id && ($parend_id[0]['id']==1 || $parend_id[0]['id']==2 || $parend_id[0]['id']==7)){ 
                      if($recommand['location_home']==1)
                      {$sellid=$sell['id'];echo "<input type='checkbox' name='recommand_type' checked='checked' onchange='recommand($sellid,1)'>首页".'<br>';}
                      else{$sellid=$sell['id'];echo "<input type='checkbox' name='recommand_type' onchange='recommand($sellid,1)'>首页".'<br>';}
                    } 
              ?>

              <input type="checkbox" name="recommand_type" <?php $recommand = Mdg\Models\RecommandSell::recommandtype($sell['id']); if($recommand['location_category']==1){ echo "checked='checked'";} ?> onchange="recommand({{sell['id']}},2)">分类列表<br>
              <input type="checkbox" name="recommand_type" <?php $recommand = Mdg\Models\RecommandSell::recommandtype($sell['id']); if($recommand['location_hot']==1){ echo "checked='checked'";} ?> onchange="recommand({{sell['id']}},3)">热门
              {% endif %}
            </td>
            <td>
                <?php if($sell['publish_place'] == 0){echo '全部';} elseif($sell['publish_place'] == 1) { echo 'pc';} elseif(
                $sell['publish_place'] == 2) { echo 'app';}?>
            </td>

            <td>               
              <a href="/manage/sell/edit/{{sell['id']}}/{{current}}" >修改</a>
                <a href="javascript:reload({{sell['id']}})"  id="reload{{sell['id']}}">刷新</a>
                <a href="javascript:deletesell({{sell['id']}})"  id="delete{{sell['id']}}">{% if sell['is_del'] %}取消删除{% else %}删除{% endif %}</a>
                
                {% if sell['state'] == 0 %}
                <a href="/manage/sell/shenhe/{{sell['id']}}/{{current}}">{{state1[sell['state']]}}</a>
                {% else %}
                <a href="javascript:upstate({{sell['id']}})" id="state{{sell['id']}}">{{state1[sell['state']]}}</a>
                {% endif %}
                              

            </td>
        </tr>
        {% endfor %}
        {% endif %}
        <tr style="display:none" id="piliang">
             <td colspan="9">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
              <a  href="javascript:batch_operation('1')">批量审核</a>&nbsp;&nbsp;
              <a  href="javascript:batch_operation('2')">批量刷新</a>&nbsp;&nbsp;
              <a  href="javascript:batch_operation('3')">取消审核</a>&nbsp;&nbsp;
              <a  href="javascript:batch_operation('4')" >批量删除</a>
             </td>
        </tr>

                
    </table>
</div>
{{ form("/sell/index", "method":"get") }}
<div class="fenye">
    <div class="fenye1">
        <span>{{ pages }}</span>
        <em>跳转到第<input type="text" class='input' id='page' name='p' value="<?php if(isset($_GET['p'])) { echo $_GET['p'];}?>" />页</em>
        <?php unset($_GET['p']);
              foreach ($_GET as $key => $val) {

          echo "<input type='hidden' name='{$key}' value='{$val}'>";
        }?>

         <input class="sure_btn"  type='submit' value='确定' >
    </div>
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
  $.getJSON("/manage/sell/recommand/", {'id':id , 'type':type} ,function(data){
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

//审核
function upstate(id){
   var query = window.location.search;
  if(window.confirm('您确定要取消审核吗?')){
       $.getJSON("/manage/sell/upstate/", {'id':id} ,function(data){
           alert(data.msg);
           if(data.state){
             var current={{current}};
              location.href="/manage/sell/index"+query;
              $('#state'+id+'').text(data.statestr);
              $('#deletesell'+id+'').text(data.nostate);
           }
       });
  }
}
//删除     
function deletesell(id){

  if(window.confirm('您确定要删除吗?')){
       $.getJSON("/manage/sell/delete/", {'id':id} ,function(data){
           alert(data.msg);
           if(data.state){
              var current={{current}};
              location.href='/manage/sell/index?p='+current;
              // $('#delete'+id+'').text("取消删除");
              // $('#deletesell'+id+'').text("已删除");
           }
       });
  }
}
//刷新
function reload(id){

  if(window.confirm('您确定要刷新吗?')){
       $.getJSON("/manage/sell/reload/", {'id':id} ,function(data){
           alert(data.msg);

       });
  }
}
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

$('#checkAll').on('click', function(event) {
    var check=$(this).prop("checked");
    if(check==true){
        $("input[name='subBox[]']").each(function() { 
        $(this).prop("checked", true);
        $("#piliang").show();
        }); 
    }else if(check==false){
        $("input[name='subBox[]']").each(function() { 
        $(this).prop("checked", false); 
        $("#piliang").hide();
        }); 
    }
    
});
function  batch_operation(type){
  var id="";
  var values="";
  var order_id=$("input[name='subBox[]']");
  $("#piliang").show();
  for(i=0;i<order_id.length;i++){
    if(order_id[i].checked){
      values=order_id[i].value;
      id = id +values + ","; 
    }
  }
  ida=id.substring(3);
  if(ida==""){
    alert("请选择");
    return false;
  }
  if(type==2){
    var stime=$("#d4334").val();
    var etime=$("#d4335").val();
    if(stime=='' && etime==''){
      alert('请选择随机时间');
      return false;
    }
  }
  var current={{current}};
  $.getJSON("/manage/sell/review/", {'id':id,'type':type,stime:stime,etime:etime} ,function(data){
       alert(data.msg);
       location.href='/manage/sell/index?p='+current;
  });
}
</script>