<body>
<div class="bt2">
  店铺查看
  {% if data.shop_status == 0 %}
  <input type="button" value="审核通过" class="btn" onclick="ShowDiv('MyDiv','fade')"/>
  <input type="button" value="审核未通过" class="btn" onclick="ShowDiv1('MyDiv1','fade1')"/>
  {% endif %}
</div>

<link rel="stylesheet" type="text/css" href="http://yncstatic.b0.upaiyun.com/mdg/manage/css/style.css">
<div class="main">
  <div class="main_right">
    <h6>申请开通店铺</h6>
    <div class="cx">
      <table width="100%" border="0" cellspacing="0" cellpadding="0" style=" border:none;">
        <tbody>
          <tr>
            <td class="cx_title">店铺名称：</td>
            <td class="cx_content">{{ data.shop_name}}</td>
          </tr>
          <tr>
            <td class="cx_title">您的身份：</td>
            <td class="cx_content">
              <!-- {{ _business_type[data.business_type] }}/{{ _user_type[data.business_type] }} -->{{ data.shop_type }}</td>
          </tr>
          <tr>
            <td class="cx_title">主营产品：</td>
            <td class="cx_content">{{ main_product  }}</td>
          </tr>
          <tr>
            <td class="cx_title">所在地区：</td>
            <td class="cx_content">{{ address }}</td>
          </tr>
          <tr>
            <td class="cx_title">联系人：</td>
            <td class="cx_content">{{ data.contact_man}}</td>
          </tr>
          <tr>
            <td class="cx_title">电话：</td>
            <td class="cx_content">{{ data.contact_phone}}</td>
          </tr>
          <tr>
            <td class="cx_title">身份证号：</td>
            <td class="cx_content">{{ data.credit.identity_no}}</td>
          </tr>
          <tr>
            <td class="cx_title">银行卡开户行：</td>
            <td class="cx_content">{{ data.credit.bank_name}}</td>
          </tr>
          <tr>
            <td class="cx_title">开户名：</td>
            <td class="cx_content">{{ data.credit.account_name}}</td>
          </tr>

          <tr>
            <td class="cx_title">卡号：</td>
            <td class="cx_content">{{ data.credit.card_no}}</td>
          </tr>
          <!-- 企业 -->
          {% if data.user_type == 3 %}
          <tr>
            <td class="cx_title">银行开户许可证：</td>
            <td class="cx_content">
              {% if data.credit.bank_card_picture %}
              <img src="{{ data.credit.bank_card_picture }} " alt="银行卡正面照"  width='150px' height='100px'>
              {% else %}
                  无
                {% endif %}
            </td>
          </tr>

          <tr>
            <td class="cx_title">企业营业执照副本照：</td>
            <td class="cx_content">
              {% if data.credit.identity_picture_lic %}
              <img src="{{ data.credit.identity_picture_lic }} " alt="银行卡正面照"  width='150px' height='100px'>
              {% else %}
                  无
                {% endif %}
            </td>
          </tr>
          <tr>
            <td class="cx_title">企业税务登记证照：</td>
            <td class="cx_content">
              {% if data.credit.tax_registration %}
              <img src="{{ data.credit.tax_registration }} " alt="银行卡正面照"  width='150px' height='100px'>
              {% else %}
                  无
                {% endif %}
            </td>
          </tr>
          <tr>
            <td class="cx_title">组织机构代码证：</td>
            <td class="cx_content">
              {% if data.credit.organization_code %}
              <img src="{{ data.credit.organization_code }} " alt="银行卡正面照"  width='150px' height='100px'>
              {% else %}
                  无
                {% endif %}
            </td>
          </tr>

          <tr>
            <td class="cx_title">法定代表人身份证照:</td>
            <td class="cx_content"></td>
          </tr>

          <tr>
            <td class="cx_title">身份证正面照：</td>
            <td class="cx_content">
              {% if data.credit.identity_card_front %}
              <img src="{{ data.credit.identity_card_front }} " alt="银行卡正面照"  width='150px' height='100px'>
              {% else %}
                  无
                {% endif %}
            </td>
          </tr>
          <tr>
            <td class="cx_title">身份证背面照：</td>
            <td class="cx_content">
              {% if data.credit.identity_card_back %}
              <img src="{{ data.credit.identity_card_back }} " alt="银行卡正面照"  width='150px' height='100px'>
              {% else %}
                  无
                {% endif %}
            </td>
          </tr>
          {% endif %}
          <!-- 个体户 -->
          {% if data.user_type == 2 %}
          <tr>
            <td class="cx_title">银行卡正面照：</td>
            <td class="cx_content">
              {% if data.credit.bank_card_picture %}
              <img src="{{ data.credit.bank_card_picture }} " alt="银行卡正面照"  width='150px' height='100px'>
              {% else %}
                  无
                {% endif %}
              <!-- {{ data.credit.bank_card_picture}}  --> </td>
          </tr>

          <tr>
            <td class="cx_title">个体工商户营业执照：</td>
            <td class="cx_content">
              {% if data.credit.identity_picture_lic %}
              <img src="{{ data.credit.identity_picture_lic }} " alt="银行卡正面照"  width='150px' height='100px'>
              {% else %}
                  无
                {% endif %}
            </td>
          </tr>

          <tr>
            <td class="cx_title">身份证正面照：</td>
            <td class="cx_content">
              {% if data.credit.bank_card_picture %}
              <img src="{{ data.credit.identity_picture_lic}}" alt="银行卡正面照"  width='150px' height='100px'>
              {% else %}
                  无
                {% endif %}
            </td>
          </tr>

          <tr>
            <td class="cx_title">身份证背面照：</td>
            <td class="cx_content">
              {% if data.credit.bank_card_picture %}
              <img src="{{ data.credit.identity_card_front }}" alt="银行卡正面照"  width='150px' height='100px'>
              {% else %}
                  无
                {% endif %}
            </td>
          </tr>
          {% endif %}
          <!-- 个人 -->
          {% if data.user_type == 1 %}
          <tr>
            <td class="cx_title">银行卡正面照：</td>
            <td class="cx_content">
              {% if data.credit.bank_card_picture %}
              <img src="{{ data.credit.bank_card_picture }} " alt="银行卡正面照"  width='150px' height='100px'>
              {% else %}
              无
              {% endif %}
            </td>
          </tr>

          <tr>
            <td class="cx_title">手持身份证正面头部照：</td>
            <td class="cx_content">
              {% if data.credit.identity_card_front %}
              <img src="{{ data.credit.identity_picture_lic }} " alt="银行卡正面照"  width='150px' height='100px'>
              {% else %}
              无
              {% endif %}
            </tr>
            <tr>
              <td class="cx_title">身份证正面照：</td>
              <td class="cx_content">
                {% if data.credit.identity_card_front %}
                <img src="{{ data.credit.identity_card_front }} " alt="银行卡正面照"  width='150px' height='100px'>
                {% else %}
              无
              {% endif %}
              </td>
            </tr>
            {% endif %}
            <tr>
              <td class="cx_title">网店介绍：</td>
              <td class="cx_content">{{ data.credit.shop_desc }}</td>
            </tr>

          </tbody>
        </table>
      </div>
      <div align="center" style="margin-top:20px;"></div>
    </div>
    <!-- main_right 结束  --> </div>
  <div class="footer">Copyright © 2013-2014 ync365.com All rights reserved.</div>
  <script type="text/javascript" src="http://yncstatic.b0.upaiyun.com/js/jquery/jquery-1.11.1.min.js"></script>
  <script type="text/javascript" src="http://yncstatic.b0.upaiyun.com/js/jquery/ld-select.js"></script>
  <link rel="stylesheet" type="text/css" href="http://yncstatic.b0.upaiyun.com/js/validator/jquery.validator.css">
  <script type="text/javascript" src="http://yncstatic.b0.upaiyun.com/js/validator/jquery.validator.js"></script>
  <script type="text/javascript" src="http://yncstatic.b0.upaiyun.com/js/validator/local/zh_CN.js"></script>
  <script></script>

  <!-- 审核通过弹框开始  -->
  <div id="fade" class="black_overlay"></div>

  <form action="/manage/shop/shopaudit"   method="post" id='via' >
    <div id="MyDiv" class="white_content2">
      <div class="gb">
        确定审核通过
        <a href="#" onclick="CloseDiv('MyDiv','fade')"></a>
      </div>
      <div class="shenh">
        <ul>
          <li>
            <lable>请输入网店地址：</lable>
            <div>
              <input name="url_dns" type="text" id='url_dns'   value="{{ link }}" data-target="#inputIDdns" />
              .5fengshou.com
              <br>
              <span class="msg-box" for="inputID" id='inputIDdns' style='display:none;'></span>

            </div>
          </li>
          <li>
            <lable>&nbsp;</lable>
            <div>
              <input name="" type="submit" value="确定" class="btn3"/>
              <input type="hidden" name='id' value='{{ data.shop_id}}'>
              <!-- # 隐藏ID -->
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
  <form action="/manage/shop/shopunauditun"   method="post"  >
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
              <input type="hidden" name='id' value='{{ data.shop_id}}'>
              <!-- # 隐藏ID -->
              <input name="" type="button" value="取消" class="btn3" onclick="CloseDiv1('MyDiv1','fade1')"/>
            </div>
          </li>
        </ul>
      </div>
    </form>
  </div>
  <!-- 审核未通过弹框结束  -->

  <script type="text/javascript">
  
$('#via').validator({
    rules: {
        wwwDns: [/^\w{5,}$/, '只能输入数字字母,5位以上']
    },

    fields: {
        'url_dns': '网店地址:required;wwwDns;remote[/manage/shop/checkdns, id ]',
    }
});


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
</body>