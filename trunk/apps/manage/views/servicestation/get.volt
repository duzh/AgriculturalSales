<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/manage/css/style.css" />
<link rel="stylesheet" type="text/css" href="http://yncstatic.b0.upaiyun.com/mdg/manage/css/style.css">

<body>
<div class="bt2">
  服务站查看
  {% if data.shop_status == 0 %}
  <input type="button" value="审核通过" class="btn" onclick="ShowDiv('MyDiv','fade')"/>
  <input type="button" value="审核未通过" class="btn" onclick="ShowDiv1('MyDiv1','fade1')"/>
  {% endif %}
</div>

<div class="main">

  <div class="main_right">


    <div class="cx">
      <form method="post" action="/manage/servicestation/savepic" name="myform" enctype="multipart/form-data">
        <table>
          <tbody>

            <tr>
              <td class="cx_title">上传实地查看照片：</td>
              <td class="cx_content">
                   <input type="file" value="浏览" id="img_upload" >
                    <div id="show_img" >
                        {% for key,val in pic %}
                              <b id='img_{{ val['id']}}'>
                              <img src="{{ constant('ITEM_IMG') }}/{{val['pic_path']}}" alt=""  width='100px' height='100px'>
                              <a href="javascript:removePicImg('{{val['id']}}')">删除</a>
                              </b>
                        {% endfor %}
                    </div>
              </td>
            </tr>

            <tr>
              <td class="cx_title">服务站介绍：</td>
              <td class="cx_content">
                <textarea name="service_demo" id="" cols="30" rows="10">
                  <?php echo isset($data->credit->service_desc) ? $data->credit->service_desc : '';?></textarea>
              </td>
            </tr>

            <tr>
              <td class="cx_title"></td>
              <td class="cx_content">
                <input type="hidden" name='shopid' value='{{ data.shop_id}}'>
                <input type="submit" name='' value='保存'></td>
            </tr>
          </tbody>
        </table>
      </form>
      <table width="100%" border="0" cellspacing="0" cellpadding="0" style=" border:none;">
        <tbody>

          <tr>
            <td class="cx_title">您的身份：</td>
            <td class="cx_content">{{ _user_type[data.user_type] }}</td>
          </tr>
          <tr>
            <td class="cx_title">服务站编号：</td>
            <td class="cx_content">{{ data.shop_no  }}</td>
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
            <td class="cx_title">负责区域：</td>
            <td class="cx_content">
              {% for key,val in Viewareas%}
                {{val}}
              <br />
              {% endfor %}
            </td>
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
            <td class="cx_title">企业名称：</td>
            <td class="cx_content">
              {% if data.shop_name %}
                {{data.shop_name}}
                {% else %}
                  无
                {% endif %}
            </td>
          </tr>

          <tr>
            <td class="cx_title">企业logo：</td>
            <td class="cx_content">
              {% if data.credit.bank_card_picture %}
              <img src="{{ data.credit.bank_card_picture }} " alt="银行卡正面照"  width='150px' height='100px'>
              {% else %}
                  无
                {% endif %}
            </td>
          </tr>

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
                <!-- {{ data.credit.bank_card_picture }}  --> </td>
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

  <script type="text/javascript" src="/uploadify/jquery.uploadify.min.js" ></script>
  <link rel="stylesheet" type="text/css" href="/uploadify/uploadify.css">
  <script type="text/javascript">
  
// $('#via').validator({
//     rules: {
//         wwwDns: [/^\w{5,}$/, '只能输入数字字母,5位以上']
//     },

//     fields: {
//         'url_dns': '网店地址:required;wwwDns;remote[/manage/shop/checkdns, id ]',
//     }
// });

jQuery(document).ready(function(){
  
 setTimeout(function(){
          $('#img_upload').uploadify({
                'swf'      : '/uploadify/uploadify.swf',
                'uploader' : '/upload/tmpfile',
                'fileSizeLimit' : '2MB',
                'fileTypeExts' : '*.jpg;*.png;*.jpeg;*.bmp;*.png',
                'uploadLimit' : 5,
                'formData' : {
                    'sid' : '{{ sid }}',
                    'type' : '22',
                    'isclos' : '1',
                },
                'buttonClass' : 'upload_btn',
                'buttonText'  : '浏览',
                'multi'       : false,
                onDialogOpen : function() {
                    $('.gy_step').eq(1).addClass("active").siblings().removeClass("active");
                },
                onUploadSuccess  : function(file, data, response) {
                    data = $.parseJSON(data);
                    if(data.status) {
                        $('#show_img').append(data.html);
                    }
                }
          })
    },10);
});


</script>

  <!-- 审核通过弹框开始  -->
  <div id="fade" class="black_overlay"></div>

  <form action="/manage/servicestation/shopaudit"   method="post" id='via' >
    <div id="MyDiv" class="white_content2">
      <div class="gb">
        确定审核通过
        <a href="#" onclick="CloseDiv('MyDiv','fade')"></a>
      </div>
      <div class="shenh">
        <ul>
          <li>
            <!-- <lable>请输入网店地址：</lable> -->
            <div>
              <!-- <input name="url_dns" type="text" id='url_dns'   value="{{ link }}" data-target="#inputIDdns" />
              .5fengshou.com
              <br>
              <span class="msg-box" for="inputID" id='inputIDdns' style='display:none;'></span> -->

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
  <form action="/manage/servicestation/shopunauditun"   method="post"  >
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

function removePicImg(id) {
  $.getJSON('/manage/servicestation/removepicimg', {id: id}, function(data, textStatus) {
      alert(data.msg);
      if(data.status) {
        $("#img_"+id).remove();
      }
  });
}
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
.upload_btn {width: 121px;height: 31px;line-height: 31px;text-align: center;background: url({{ constant('STATIC_URL') }}mdg/images/yz_btn.png) no-repeat;background-position: 0 0;top: 0;left: 88px;color: #7f7f7f; /*margin-left:75px;*/}
</style>

</body>