{% if maintag.status == 0 %}
<input type="button" value="审核通过" class="btn" onclick="ShowDiv('MyDiv','fade')"/>
<input type="button" value="审核未通过" class="btn" onclick="ShowDiv1('MyDiv1','fade1')"/>
{% endif %}

<div class="main">
  <div class="main_right">
    <div class="bt2">追溯信息</div>
    <div align="left" style="margin-top:20px;">
      <div>
       

          <div class="cx">

            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td  colspan="2" class="cx_title1">基本信息</td>
              </tr>
              <tr>
                <td class="cx_title">生产日期：</td>
                <td class="cx_content">{{ maintag ? maintag.product_date  : '' }}</td>
              </tr>

              <tr>
                <td class="cx_title">产品保质期：</td>
                <td class="cx_content">{{ maintag ? maintag.expiration_date  : 0 }}天</td>
              </tr>

            <tr>
              <td class="cx_title">产地：</td>
              <td class="cx_content">{{ maintag ? maintag.full_address : '' }}</td>
            </tr>
            <tr>
              <td class="cx_title">种植面积：</td>
              <td class="cx_content">{{ maintag ? maintag.plant_area : '0.00' }}亩</td>
            </tr>
             <tr>
                <td class="cx_title">商品名称：</td>
                <td class="cx_content">{{ sell ? sell["title"] : '' }}</td>
              </tr>
              <tr>
                <td class="cx_title">商品编号：</td>
                <td class="cx_content">{{ sell ? sell["sell_sn"] : '' }}</td>
              </tr>
          </table>

          <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td  colspan="2" class="cx_title1">产地信息</td>
            </tr>
            <tr>
              <td class="cx_title">纬度：</td>
              <td class="cx_content">
                N{{ maintag ? maintag.longitude : '' }} S{{ maintag ? maintag.latitude : '' }}
              </td>
            </tr>
            <tr>
              <td class="cx_title">雨水等级：</td>
              <td class="cx_content">{{ maintag ? rainwater_type[maintag.rainwater] : '' }}</td>
            </tr>
          </table>

             <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                  <td  colspan="2" class="cx_title1">种植使用表</td>
              </tr>
               <tr align="center">
                  <td width='6%'  class="bj">品种</td>
                  <td width='10%' class="bj">净度</td>
                  <td width='18%' class="bj">纯度</td>
                  <td width='10%' class="bj">发芽率</td>
                  <td width='10%' class="bj">水分</td>
              </tr>

              {% for key, val in tag_seed %}
                    <tr align="center" >
                      <td>{{ val['breed'] }}</td>
                      <td>{{ val['neatness'] }}%</td>
                      <td>{{ val['fineness'] }}%</td>
                      <td>{{ val['sprout'] }}%</td>
                      <td>{{ val['water'] }}%</td>
                    </tr>
              {% endfor %}
             </table>

              <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                  <td  colspan="2" class="cx_title1">肥料使用表</td>
              </tr>
               <tr align="center">
                  <td width='6%'  class="bj">时间</td>
                  <td width='10%' class="bj">名称</td>
                  <td width='10%' class="bj">用量(千克/亩)</td>
              </tr>

              {% for key, val in tagmanure %}
                    <tr align="center" >
                      <td>{{ val['use_period'] }}</td>
                      <td>{{ val['manure_name'] }}</td>
                      <td>{{ val['manure_amount'] }}</td>
                    </tr>
              {% endfor %}
             </table>


               <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                  <td  colspan="2" class="cx_title1">农药使用表</td>
              </tr>
               <tr align="center">
                  <td width='6%'  class="bj">时间</td>
                  <td width='10%' class="bj">名称</td>
                  <td width='10%' class="bj">用量(千克/亩)</td>
              </tr>

              {% for key, val in tagpicture %}
                    <tr align="center" >
                      <td>{{  val['use_period'] }}</td>
                      <td>{{ val['pesticide_name'] }}</td>
                      <td>{{ val['pesticide_amount'] }}</td>
                    </tr>
              {% endfor %}
             </table>
    
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                  <td  colspan="2" class="cx_title1">生产环境全程监控</td>
              </tr>
               <tr align="center">
                  <td width='6%'  class="bj">作业类型</td>
                  <td width='10%' class="bj">开始时间</td>
                  <td width='10%' class="bj">结束时间</td>
              </tr>

              {% for key, val in tagplant %}
                    <tr align="center" >
                      <td>{{ val["operate_type"] ?  operate_type[val["operate_type"]] : ''}}</td>
                      <td>{{ date('Y-m-d' , val['begin_date']) }}</td>
                      <td>{{ date('Y-m-d' , val['end_date']) }}</td>
                    </tr>
              {% endfor %}
             </table>
             
      



  <!-- 审核通过弹框开始  -->
  <div id="fade" class="black_overlay"></div>
  
  <form action="/manage/tag/tagaudit"   method="post" id='via' >
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
            
           通过以后供应商将有权使用带有丰收汇LOGO的标签

            <br>
            <span class="msg-box" for="inputID" id='inputIDdns' style='display:none;'></span>
            
          </div>
        </li>
        <li>
          <lable>&nbsp;</lable>
          <div>
            <input name="" type="submit" value="确定" class="btn3"/>
            <input type="hidden" name='id' value='{{ maintag.tag_id}}'> <!-- # 隐藏ID -->
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
  <form action="/manage/tag/tagunauditun"   method="post"  >
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
            <input name="reject" type="text"  value=''  data-rule="拒绝理由:required;" data-target="#rejectinputID"   />
            <span class="msg-box" for="inputID" id='rejectinputID' style=''></span>
          </div>
        </li>
        <li>
          <lable>&nbsp;</lable>
          <div>
            <input type="submit" value="确定" class="btn3"/>
            <input type="hidden" name='id' value='{{ maintag.tag_id}}'> <!-- # 隐藏ID -->
            <input name="" type="button" value="取消" class="btn3" onclick="CloseDiv1('MyDiv1','fade1')"/>
          </div>
        </li>
      </ul>
    </div>
    </form>
  </div>

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
.upload_btn {width: 121px;height: 31px;line-height: 42px;text-align: center;background: url({{ constant('STATIC_URL') }}mdg/images/yz_btn.png) no-repeat;background-position: 0 0;top: 0;left: 88px;color: #7f7f7f;}
</style>
    <div class="footer">Copyright © 2013-2014 ync365.com All rights reserved.</div>
</body>
  </html>
</body>
</html>

