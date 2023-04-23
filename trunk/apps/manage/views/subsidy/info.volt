{{ form("sell/save", "method":"post","id":"mysell") }}
{{ content() }}
<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/manage/css/style.css" />
<div class="main">
    <div class="main_right">
        <div class="bt2">审核供应信息</div>
     
  {% if subsidy.status == 0 %}<input type="button" value="审核通过" class="btn" onclick="ShowDiv('MyDiv','fade')"/><input type="button" value="审核未通过" class="btn" onclick="ShowDiv1('MyDiv1','fade1')"/>{% endif %}
  

        <div class="cx">
            基本信息
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td class="cx_title">单号：</td>
                    <td class="cx_content">{{subsidy.subsidy_no}}</td>
                </tr>
                <tr>
                    <td class="cx_title">创建时间：</td>
                    <td class="cx_content">{{ date("Y-m-d",subsidy.add_time) }}</td>
                </tr>
                 <tr>
                    <td class="cx_title">补贴类型：</td>
                    <td class="cx_content">
                  <?php echo isset($_subsidy_type[$subsidy->subsidy_type]) ?  $_subsidy_type[$subsidy->subsidy_type] : ''?>
                    </td>
                </tr>
                 <tr>
                    <td class="cx_title">补贴金额：</td>
                    <td class="cx_content">{{ subsidy.subsidy_amount }}
                      {% if subsidy.status == 0 %}<input type="button" value="修改"  onclick="ShowDiv3('MyDiv3','fade3')"/>{% endif %}
                    </td>
                </tr>
                 <tr>
                    <td class="cx_title">状态：</td>
                    <td class="cx_content">
                      <?php echo isset($_status[$subsidy->status]) ?  $_status[$subsidy->status] : ''?>
                    </td>
                </tr>
                 <tr>
                    <td class="cx_title">订单号：</td>
                    <td class="cx_content">{{ subsidy.order_no }}</td>
                </tr>
                 <tr>
                    <td class="cx_title">用户ID：</td>
                    <td class="cx_content">{{ subsidy.user_id }}</td>
                </tr>
                 <tr>
                    <td class="cx_title">电话：</td>
                    <td class="cx_content">{{ subsidy.user_phone }}</td>
                </tr>
                 <tr>
                    <td class="cx_title">姓名：</td>
                    <td class="cx_content">{{ subsidy.user_name }}</td>
                </tr>
                 <tr>
                    <td class="cx_title">用户已获得的补贴金额：</td>
                    <td class="cx_content">{{ use_money }}</td>
                </tr>
            </table>
            {% if subsidy.subsidy_type !=3 %}
            交易信息
            <table width="100%" border="0" cellspacing="0" cellpadding="0" >
               
                <tr>
                    <td class="cx_title">可信农场认证：</td>
                    <td class="cx_content">
                        {{ isif ? "已认证" : "未认证" }}
                    </td>
                </tr>
                {% if subsidy.subsidy_type == 1 %}
                 <tr>
                    <td class="cx_title">土地面积 ：</td>
                    <td class="cx_content">
                       {{farm_areas}}亩
                    </td>
                </tr>
                {% else %}
                <tr>
                    <td class="cx_title">可追溯标签：</td>
                    <td class="cx_content">
                        {{source ?  "已认证" : "未认证" }}
                    </td>
                </tr>
                {% endif %}
                {% if subsidy.subsidy_type == 0 %}
                 <tr>
                    <td class="cx_title">交易商品所属：</td>
                    <td class="cx_content">
                         {{sell_type}}
                    </td>
                </tr>
                <tr>
                    <td class="cx_title">种植面积：</td>
                    <td class="cx_content">
                        {{plant_area}}亩
                    </td>
                </tr>
                <tr>
                    <td class="cx_title">成交量：</td>
                    <td class="cx_content">{{order_sell}}</td>
                </tr>
                <tr>
                    <td class="cx_title">成交金额：</td>
                    <td class="cx_content">
                        {{order_amount}}元
                    </td>
                </tr>
                <tr>
                    <td class="cx_title">该产品已补贴金额：</td>
                    <td class="cx_content">{{product_subsidy}}元</td>
                </tr>
                <tr>
                    <td class="cx_title" valign="top">补贴金额参考金额：</td>
                    <td >
                         {{consult_subsidy}}元
                    </td>
                </tr>
                {% endif %}
                {% if subsidy.subsidy_type == 1 %}
                    <tr>
                        <td class="cx_title">土地使用年限：</td>
                        <td class="cx_content">
                             {{use_period}}
                        </td>
                    </tr>
                    <tr>
                        <td class="cx_title">土地性质：</td>
                        <td class="cx_content">
                            {{source}}
                        </td>
                    </tr>
                {% endif %}
            </table>
            {% endif %}
            日志
             <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr align="center">
                  <td width='6%'  class="bj">时间</td>
                  <td width='10%' class="bj">操作人</td>
                  <td width='10%' class="bj">状态</td>
                  <td width='10%' class="bj">操作</td>
                  <td width='8%' class="bj">备注</td>
                </tr>
                {% for key ,val in subsidy_log %}
                <tr align="center">
                  <td>{{ val["operate_time"] ? date("Y-m-d H:i:s ",val["operate_time"]) : '' }}</td>
                  <td>{{ val["operate_user_name"] }}</td>
                  <td><?php echo isset($_status[$val['status']]) ?  $_status[$val['status']] : ''?></td>
                  <td>{{ val["operate_content"] }}</td>
                  <td>{{ val["demo"] }}</td>
                </tr>
                 {% endfor %}
                  <tr align="center">
                  <td>{{ date("Y-m-d H:i:s ",subsidy.add_time) }}</td>
                  <td>系统</td>
                  <td></td>
                  <td>创建</td>
                  <td></td>
                </tr>
               </table>
        </div>
       
    </div>
    <!-- main_right 结束  -->
</div>

</form>

  <!-- 审核通过弹框开始  -->
  <div id="fade" class="black_overlay"></div>
  
  <form action="/manage/subsidy/auditorpass"   method="post" id='via' >
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
            <input type="hidden" name='HTTP_REFERER' value='{{ HTTP_REFERER}}'>
            <input type="hidden" name='subsidy_id' value='{{ subsidy.subsidy_id}}'> <!-- # 隐藏ID -->
            <input type="hidden" name="status" value="1">
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
  <form action="/manage/subsidy/fall"   method="post" >
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
            <input type="hidden" name='HTTP_REFERER' value='{{ HTTP_REFERER}}'>
            <input type="hidden" name='subsidy_id' value='{{ subsidy.subsidy_id}}'> <!-- # 隐藏ID -->
            <input type="hidden" name="status" value="0">
            <input name="" type="button" value="取消" class="btn3" onclick="CloseDiv1('MyDiv1','fade1')"/>
          </div>
        </li>
      </ul>
    </div>
    </form>
  </div>
  <!-- 审核未通过弹框结束  -->
  <!-- 修改价格弹框开始  -->
  <div id="fade3" class="black_overlay"></div>
  <form action="/manage/subsidy/saveamount"   method="post" >
  <div id="MyDiv3" class="white_content2">
    <div class="gb">
      确定修改价格
    </div>
    <div class="shenh">
      <ul>
        <li>
          <lable style="display:inline-block; width:147px; text-align:left;">新补贴金额：</lable>
          <div>
            <input name="price" type="text"  data-rule="required;Number"  value='' />
          </div>
          <lable style="display:inline-block; width:147px; text-align:left; margin-top:10px;">备注：</lable>
          <div>
            <!-- <input name="content" type="text"  data-rule="required;"  value='' /> -->
            <textarea name="content" data-rule="required;" style="width:135px; height:40px; line-height:18px; resize:none; padding:5px;"></textarea>
          </div>
        </li>
        <li>
          <lable>&nbsp;</lable>
          <div>
            <input type="submit" value="确定" class="btn3"/>
            <input type="hidden" name='HTTP_REFERER' value='{{ HTTP_REFERER}}'>
            <input type="hidden" name='subsidy_id' value='{{ subsidy.subsidy_id}}'> <!-- # 隐藏ID -->
            <input type="hidden" name="status" value="0">
            <input name="" type="button" value="取消" class="btn3" onclick="CloseDiv3('MyDiv3','fade3')"/>
          </div>
        </li>
      </ul>
    </div>
    </form>
  </div>
  <!-- 修改价格弹框开始  -->
<link rel="stylesheet" type="text/css" href="{{ constant('JS_URL') }}validator/jquery.validator.css" />
<script type="text/javascript" src="{{ constant('JS_URL') }}validator/jquery.validator.js"></script>
<script type="text/javascript" src="{{ constant('JS_URL') }}validator/local/zh_CN.js"></script>
<script type="text/javascript" src="{{ constant('JS_URL') }}lhgdialog/lhgdialog.min.js?skin=igreen"></script> 

<script type="text/javascript">
//弹出隐藏层
function ShowDiv(show_div,bg_div){
  <?php 
  if($checkCanVerify){
  ?>
    document.getElementById(show_div).style.display='block';
    document.getElementById(bg_div).style.display='block' ;
    var bgdiv = document.getElementById(bg_div);
    bgdiv.style.width = document.body.scrollWidth;
    $("#"+bg_div).height($(document).height());
  <?php
  }else{
  ?>
    var r=confirm("超出补贴范围，是否仍要补贴？");
    if (r==true){
        document.getElementById(show_div).style.display='block';
        document.getElementById(bg_div).style.display='block' ;
        var bgdiv = document.getElementById(bg_div);
        bgdiv.style.width = document.body.scrollWidth;
        $("#"+bg_div).height($(document).height());
    }
  <?php
  }
  ?>

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
//弹出隐藏层
function ShowDiv3(show_div,bg_div){
document.getElementById(show_div).style.display='block';
document.getElementById(bg_div).style.display='block' ;
var bgdiv = document.getElementById(bg_div);
bgdiv.style.width = document.body.scrollWidth;
$("#"+bg_div).height($(document).height());
};
//关闭弹出层
function CloseDiv3(show_div,bg_div)
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