{{ form("sell/save", "method":"post","id":"mysell") }}
{{ content() }}
<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/manage/css/style.css" />
<div class="main">
    <div class="main_right">
        <div class="bt2">审核供应信息</div>
      {% if sell.state == 0 %}
<input type="button" value="审核通过" class="btn" onclick="ShowDiv('MyDiv','fade')"/>
  <input type="button" value="审核未通过" class="btn" onclick="ShowDiv1('MyDiv1','fade1')"/>
    {% endif %}
        <div class="cx">

            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td class="cx_title">发布平台：</td>
                       <td class="cx_content"><?php if($sell->publish_place == 0){echo '全部';} elseif($sell->publish_place == 1){echo 'pc';}elseif($sell->publish_place == 2){echo 'app';}?></td>
                </tr>
                <tr>
                    <td class="cx_title">要发布的产品：</td>
                    <td class="cx_content">{{sell.title}}</td>
                </tr>
                <tr>
                    <td class="cx_title">所属分类：</td>
                    <td class="cx_content">{{ curCat }}</td>
              
                </tr>
            </table>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td class="cx_title"></td>
                    <td class="cx_content">
                        <div class="cx_content1" id="show_img">
                            {% if imgfile %}
                            {% for key, img in imgfile %}
                              <dl><dt><img src="{{ constant('IMG_URL') }}{{ img['path'] }}" width="200" height="200"><dt><dd><a href="javascript:;" onclick="deleteImg(this,{{ img['id'] }});"></a></dd></dl>
                            {% endfor %}
                            {% else %}
                            暂无图片
                            {% endif %}
                        </div>
                    </td>
                </tr>
            </table>
            <table width="100%" border="0" cellspacing="0" cellpadding="0" >
               
                <tr>
                    <td class="cx_title">价格：</td>
                    <td class="cx_content">
                        {% if sell.price_type == 1%}
                            <table>
                                <tr>
                                    <th>起购量（{{ goods_unit[sell.goods_unit] }}）</th>
                                    <th>价格</th>
                                </tr>
                                <?php foreach ($sell_step_price as $key =>$step_price) {
                                    echo "<tr>";
                                    if ( isset($sell_step_price[$key+1]) ) {
                                        $step_p = $sell_step_price[$key+1]['quantity']-1;
                                        echo "<td>".$step_price['quantity']."-".$step_p."</td>";
                                    }
                                    else {
                                        echo "<td>≥{$step_price['quantity']}</td>";
                                    }
                                    echo "<td>{$step_price['price']}元/".Mdg\Models\Purchase::$_goods_unit[$sell->goods_unit]."</td>";
                                    echo "</tr>";
                                }?>
                            </table>
                        {% else %}
                        {{sell.min_price}}-{{sell.max_price}}

                        元/{{ goods_unit[sell.goods_unit] }}
                        {% endif %}
                        <span class="msg-box" style="position:static;" for="max_price"></span>
                    </td>
                   
                </tr>
                <tr>
                    <td class="cx_title">供应量：</td>
                    <td class="cx_content">
                      {% if sell.quantity == 0 %}
                          不限
                        {% else %}
                       {{  sell.quantity }}  {{ goods_unit[sell.goods_unit] }}
                       {% endif %}
                      
                    </td>
                </tr>
                 <tr>
                    <td class="cx_title">起购量：</td>
                    <td class="cx_content">
                      {% if sell.min_number == 0 %}
                          不限
                        {% else %}
                       {{  sell.min_number }} {{ goods_unit[sell.goods_unit] }}
                       {% endif %}
                      
                    </td>
                </tr>
                <tr>
                    <td class="cx_title">上市时间：</td>
                    <td class="cx_content">
                    <select id="u69_input" data-label="上市时间1" name="stime">
                            <option value="">请选择</option>
                            <?php foreach (Mdg\Models\Sell::$type as $key =>$value) {
                                if($key ==  $stime ){
                                    echo "<option value='$key'  selected >$value</option>";
                                }else{
                                    echo "<option value='$key' >$value</option>";
                                } 
                            }?>
                   </select>
                   <select id="u72_input" data-label="上市时间2" name="etime">
                            <option value="">请选择</option>
                            <?php foreach (Mdg\Models\Sell::$type as $key =>$value) {
                              if($key ==  $etime ){
                                  echo "<option value='$key'  selected >$value</option>";
                              }else{
                                  echo "<option value='$key' >$value</option>";
                              } 
                            }?>
                    </select>
    
                    </td>
                </tr>
                <tr>
                    <td class="cx_title">品种：</td>
                    <td class="cx_content">{{sell.breed}}</td>
                </tr>
                <tr style="display:none">
                    <td class="cx_title">是否热卖：</td>
                    <td class="cx_content">
                        <label for=""><input type="radio" name="is_hot" {% if sell.is_hot %}checked='checked'{% endif %} value='1'>是</label>&nbsp;&nbsp;&nbsp;&nbsp;
                        <label for=""><input type="radio" name="is_hot" {% if !sell.is_hot %}checked='checked'{% endif %} value='0'>否</label>
                    </td>
                </tr>
                <tr>
                    <td class="cx_title">规格：</td>
                    <td class="cx_content">{{sell.spec}}</td>
                </tr>
                <tr>
                    <td class="cx_title" valign="top">详细描述：</td>
                    <td >
                      <div class="cx_content1" >{{  text_area("content",'class':'txt',"value":contents.content, 'id' : 'editor') }}</div>
                        
                    </td>
                </tr>
            </table>
            <table width="100%" border="0" cellspacing="0" cellpadding="0" style=" border:none;">
                
                <tr>
                    <td class="cx_title">供应商姓名：</td>
                    <td class="cx_content">{{sell.uname}}</td>
                </tr>
                <tr>
                    <td class="cx_title">供应商电话：</td>
                    <td class="cx_content">
                      {% if sell.mobile %}{{sell.mobile}}{% else %}{% if sell.mobileurl %}<img src='http://yncmdg.b0.upaiyun.com{{sell.mobileurl}}' width="100px" height="30px" >{% else %}无{% endif %}{% endif %}
                      
                    </td>
                </tr>

                <tr>
                    <td class="cx_title">供货商地址：</td>
                     <td class="cx_content">{{curAreas}}</td>  
                </tr>
            </table>
        </div>
       
    </div>
    <!-- main_right 结束  -->
</div>

</form>

  <!-- 审核通过弹框开始  -->
  <div id="fade" class="black_overlay"></div>
  
  <form action="/manage/sell/auditorpass"   method="post" id='via' >
  <div id="MyDiv" class="white_content2">
    <div class="gb">
      确定审核通过
      <a href="#" onclick="CloseDiv('MyDiv','fade')"></a>
    </div>
    <div class="shenh">
      <ul>
        <li>
          <lable></lable>
          <div>
            <!-- <input name="url_dns" type="text" id='url_dns'  data-target="#dns" />
            .mdg.ync365.com  -->
            <span id='dns'></span>
          </div>
        </li>
        <li>
          <lable>&nbsp;</lable>
          <div>
            <input name="" type="submit" value="确定" class="btn3"/>
            <input type="hidden" name="pages" value="{{pages}}">
            <input type="hidden" name='id' value='{{ sell.id}}'> <!-- # 隐藏ID -->
            <input type="hidden" name="referer" value="{{referer}}">
            <input name="" type="button" value="取消" class="btn3" onclick="CloseDiv('MyDiv','fade')" />
          </div>
        </li>
      </ul>
    </div>
    </form>

  </div>
  <!-- 审核通过弹框结束  -->
  <!-- 审核未通过弹框开始  -->
  <div id="fade1" class="black_overlay"></div>
  <form action="/manage/sell/fall"   method="post" >
  <div id="MyDiv1" class="white_content2">
    <div class="gb">
      确定审核未通过
      <a href="#" onclick="CloseDiv1('MyDiv1','fade1')"></a>
    </div>
    <div class="shenh">
      <ul>
        <li>
          <lable>请输入拒绝理由：</lable>
          <div>
            <input name="reject" type="text"  data-rule="required;"  value='' />
          </div>
        </li>
        <li>
          <lable>&nbsp;</lable>
          <div>
            <input type="submit" value="确定" class="btn3"/>
            <input type="hidden" name="pages" value="{{pages}}">
            <input type="hidden" name='id' value='{{ sell.id}}'> <!-- # 隐藏ID -->
            <input type="hidden" name="referer" value="{{referer}}">
            <input name="" type="button" value="取消" class="btn3" onclick="CloseDiv1('MyDiv1','fade1')"/>
          </div>
        </li>
      </ul>
    </div>
    </form>
  </div>
  <!-- 审核未通过弹框结束  -->

<script type="text/javascript" src="{{ constant('JS_URL') }}jquery/ld-select.js"></script>
<script type="text/javascript" src="{{ constant('STATIC_URL') }}mdg/js/inputFocus.js"></script>
<script type="text/javascript" src="/uploadify/jquery.uploadify.min.js?ver=<?= rand(0, 9999) ?>" ></script>
<link rel="stylesheet" type="text/css" href="/uploadify/uploadify.css">
<link rel="stylesheet" type="text/css" href="{{ constant('JS_URL') }}validator/jquery.validator.css" />
<script type="text/javascript" src="{{ constant('JS_URL') }}validator/jquery.validator.js"></script>
<script type="text/javascript" src="{{ constant('JS_URL') }}validator/local/zh_CN.js"></script>
<script type="text/javascript" src="{{ constant('JS_URL') }}lhgdialog/lhgdialog.min.js?skin=igreen"></script>


<script type="text/javascript" charset="utf-8" src="/ueditor1/ueditor.config.sample.js"></script>
<script type="text/javascript" charset="utf-8" src="/ueditor1/ueditor.all.js"></script>
<script type="text/javascript" charset="utf-8" src="/ueditor1/lang/zh-cn/zh-cn.js"></script> 
<script type="text/javascript">
    var ue = UE.getEditor('editor');
</script>

<script>


$("#mysell").validator({
     rules: {
          max_price:[/^\d{1,10}\.*\d{0,2}$/, '请输入正确的价格区间'],
     },
     fields:  {
         title:"required;",
         categorys:"required;",
         max_price : "required;max_price",
         quantity  : "required;",
         etime : "required;",
         town  : "required;",
         breed : "required;",
         search  :"required",
         spec  :"required",
         content  :"required;",
         search  :"required",
         user: "required",

     },
    
});
var dialog = null;
function newWindows(id,title,url) {
    var username=$("#user").val();

    if(username==""){
       alert("请输入");
    }else{
        dialog = $.dialog({
        id    : id,
        title : title,
        min   : false,
        max   : false,
        lock  : true,
        width : 600,
        content: 'url:'+url+"/"+username
       });
    }
    

    
}
function closeDialog(){
   // $('#userid').val(id);
    dialog.close();
}

</script>
<script type="text/javascript">
//弹出隐藏层
function ShowDiv(show_div,bg_div){
document.getElementById(show_div).style.display='block';
document.getElementById(bg_div).style.display='block' ;
var bgdiv = document.getElementById(bg_div);
bgdiv.style.width = document.body.scrollWidth;
$("#"+bg_div).height($(document).height());
};
//关闭弹出层
function CloseDiv(show_div,bg_div)
{
document.getElementById(show_div).style.display='none';
document.getElementById(bg_div).style.display='none';
};
//弹出隐藏层
function ShowDiv1(show_div,bg_div){
document.getElementById(show_div).style.display='block';
document.getElementById(bg_div).style.display='block' ;
var bgdiv = document.getElementById(bg_div);
bgdiv.style.width = document.body.scrollWidth;
$("#"+bg_div).height($(document).height());
};
//关闭弹出层
function CloseDiv1(show_div,bg_div)
{
document.getElementById(show_div).style.display='none';
document.getElementById(bg_div).style.display='none';
};
</script>

<style>
.upload_btn {width: 121px;height: 31px;line-height: 31px;text-align: center;background: url({{ constant('STATIC_URL') }}mdg/images/yz_btn.png) no-repeat;background-position: 0 0;top: 0;left: 88px;color: #7f7f7f;}
</style>
<div class="footer">Copyright © 2013-2014 ync365.com All rights reserved.</div>

</body>
</html>