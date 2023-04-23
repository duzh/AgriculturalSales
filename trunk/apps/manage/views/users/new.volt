<link rel="stylesheet" href="http://yncstatic.b0.upaiyun.com/mdg/manage/css/manage-2.4.css">
<script type="text/javascript" src="http://yncstatic.b0.upaiyun.com/js/lhgdialog/lhgdialog.min.js?skin=igreen"></script>
<script type="text/javascript" src="http://yncstatic.b0.upaiyun.com//mdg/js/dialog_call.js?skin=igreen"></script>
<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/manage/css/style.css" />
<form action="/manage/users/create" id="usersform" method="post">
<div class="main">
  <div class="main_right">
    <div class="bt2">新增会员</div>
    <div class="cx">
         <table width="100%" border="0" cellspacing="0" cellpadding="0" style=" border:none;">
                <tr>
                    <td class="cx_title">手机号：</td>
                    <td class="cx_content">
                        {{ text_field("mobile", "size" : 30,'class':'txt') }}
                     </td>
                </tr>

                <tr>
                    <td class="cx_title">性别：</td>
                    <td class="cx_content">

                        <select name="sex">
                            <option value="">请选择</option>
                            <option value="0">男</option>
                            <option value="1">女</option>
                        </select>
                     </td>
                </tr>

                <tr>
                    <td class="cx_title">主营业务：</td>
                    <td class="cx_content">
                      <select name="main_category" >
                      <option value="">请选择</option>
                      {% for key,val in cate %}
                      {% if val["parent_id"] == 0 %}
                      <option value="{{val['id']}}">{{val["title"]}}</option>
                      {% endif %}
                      {% endfor %}
                    </select>
                     </td>
                </tr>

                <tr>
                    <td class="cx_title">头像：</td>
                    <td class="cx_content">
                        <a onclick="javascript:newWindows('change', '个人信息-头像', '/manage/users/uploadfile?operating=new');"  >
                                <?php if(isset($picture_path)){ $path=$picture_path;}else{$path='';} ?>
                                <img  id="myimg" src="{% if path %}{{ constant('IMG_URL') }}{{path}}{% else %}{{ constant('STATIC_URL') }}mdg/images/trust-farm-img.jpg
                                {% endif %}">
                            </a>
                     </td>
                </tr>

                <tr>
                    <td class="cx_title" valign="top" id="myusers" >我关注的采购：</td>
                    <td class="cx_content">
                        <div class="msg-box">
                            <b style="font-weight:normal;" id="addbox1"></b>
                            <a href="javascript:;" onclick = "editForm(0)">增加</a>
                            
                            <!-- <p class="help-block" id="purmsg" ></p> -->
                        </div>
                    </td>
                </tr>

                <tr>
                    <td class="cx_title" valign="top" id="myusers" >我关注的供应：</td>
                    <td class="cx_content">
                        <div class="msg-box">
                            <b style="font-weight:normal;" id="addbox2" ></b>
                            <a href="javascript:;" onclick = "editForm(1)">增加</a>
                            <!-- <p class="help-block" id="sellmsg" ></p> -->
                        </div>
                    </td>
                </tr>
                <tr>
                     <td class="cx_title">发布平台：</td>
                    <td class="cx_content">
                       {% for key,val in plat %}
                            {% if key == 0 or key == 3 %}
                            <input type="radio" name="plat" value="{{ key }}" {% if key == 0 %} checked {% endif %}/>{{ val }} 
                            {% endif %}
                        {% endfor %}

                    </td>
                </tr>
                <tr>
                    <td class="cx_title">密码：</td>
                    <td class="cx_content">
                         {{ password_field("password", "size" : 30,'class':'txt') }}
                     </td>
                </tr>
                <tr>
                    <td class="cx_title">确认密码：</td>
                    <td class="cx_content">
                        {{ password_field("confirmpassword", "size" : 30,'class':'txt') }} 
                      </td>
                </tr>
                <tr>
                     <td class="cx_title">身份类型：</td>
                    <td class="cx_content">
                       {{ radio_field("usertype", "value" : "1","onclick" : "selects('卖')") }}种植户/养殖户 (请种植户，养殖户，以及农业合作社经营者选此项)</br>
                       {{ radio_field("usertype", "value" : "2","onclick" : "selects('买')") }}采购商 (请采购商选此项)

                    </td>
                </tr>
                <tr>
                    <td class="cx_title"  valign="top">所在地区：</td>
                    <td > 
                       <div class="cx_content1"> 
                           <select name="province" class="selectAreas" id="province">
                                <option value="0" selected>省</option>
                            </select>
                            <select name="city" class="selectAreas" id="city">
                                <option value="0">市</option>
                            </select>
                            <select name="town" class="selectAreas" id="town">
                                <option value="">区/县</option>
                            </select>
                             <select name="zhen" class="selectAreas" id="zhen">
                                <option value="">请选择</option>
                            </select>
                             <select name="areas_name" class="selectAreas" id="areas_name">
                                <option value="">请选择</option>
                            </select>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="cx_title">真实姓名：</td>
                    <td class="cx_content">
                            {{ text_field("name", "type" : "numeric",'class':'txt') }}
                    </td>
                </tr>
                <tr>
                    <td class="cx_title" valign="top" id="myusers" >我要卖：</td>
                    <td class="cx_content"> <div>{{ text_field("goods", "size" : 30,'class':'txt') }}填写产品名称，多个产品以空格分开</div>
                        <span class="msg-box" style="position:static;" for="goods"></span></td>
                </tr>
         </table>
         
    </div> 
    <div align="center" style="margin-top:20px;">
        <input type="hidden" id="purcateid" name="purcateid" data-target="#purmsg"   >
        <input type="hidden" id="val" >
        <input type="hidden" id="sellcateid" name="sellcateid" data-target="#sellmsg"      >
         <input type="submit" value="确认添加" class="sub"/>
         </div>
  </div>

    <!-- 采购修改 弹层 -->
    <div class="vip-layer"></div>
    <div class="vip-box">
        <a class="close-btn" href="javascript:;"></a>
        <div class="form-attest info-box">
            <div class="message clearfix">
                <font>采购类别</font>
                <div class="select-box lang-select clearfix categrey-option">
                    <div class="choose-box f-fl">
                        <select  id="chage" onclick="chagea()">
                            {% for key,val in cate %}
                            {% if val["parent_id"] == 0 %}
                            <option value="{{val['id']}}" >{{val["title"]}}</option>
                            {% endif %}
                            {% endfor %}
                        </select>
                        <div class="erji-box">
                             {% for key,val in cate %}
                                 {% if key==1 %}
                                 {% for k,v in val["child"] %}
                                 <a href="javascript:;" id="{{v['id']}}" >{{v["title"]}}</a>
                                 {% endfor %}
                                 {% endif %}
                             {% endfor %}
                           
                        </div>
                    </div>
                    <div class="btn-box f-fl">
                        <a class="btn1" href="javascript:;">添加</a>
                        <a class="btn2" href="javascript:;">删除</a>
                    </div>
                    <div class="result-box f-fl" id="result">

                    </div>
                </div>

            </div>
        </div>
        <div style="height:20px;"></div>
        <input style="  width: 121px;cursor: pointer;height: 31px;line-height: 31px;font-size: 14px;font-weight: bold;color: #8AAF29;font-family: '微软雅黑';text-align: center;display:block;margin-top:10px; line-height:32px; margin:0 auto;" type="submit" value="确定" onclick="addpur()" />

    </div>
  <!-- main_right 结束  --> 
  
</div>
<div class="footer"> Copyright © 2013-2014 ync365.com All rights reserved. </div>
</body>
</html>
<!-- <script type="text/javascript" src="{{ constant('JS_URL') }}jquery/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="{{ constant('JS_URL') }}jquery/ld-select.js"></script> -->
<script>
$(".selectAreas").ld({ajaxOptions : {"url" : "/ajax/getareasfull"},
    defaultParentId : 0,
    style : {"width" : 140}
});
function selects(obj){
    $("#myusers").text("我要"+obj+"：");
}
</script>
<!-- <link rel="stylesheet" type="text/css" href="{{ constant('JS_URL') }}validator/jquery.validator.css" />
<script type="text/javascript" src="{{ constant('JS_URL') }}validator/jquery.validator.js"></script>
<script type="text/javascript" src="{{ constant('JS_URL') }}validator/local/zh_CN.js"></script> -->
<script type="text/javascript">
$("#usersform").validator({
     fields:  {
         mobile:"required;mobile;remote[/member/register/check]",
         password:"required;password",
         confirmpassword:"required;match(password)",
         areas_name:"required;",
         description:"required;",
         name:"required;chinese",
         goods:"required;",
         address:"required;"
     },
    
});
</script>

<script>
// 表单分类添加删除
(function(){
   selectchage();
})();
function editForm(val){
    val = val; //用参数val来判断修改的是哪个信息
    $("#val").val(val);
    $("#result").html("");
    $('.vip-layer').show();
    $('.vip-box').show();   
};
function  selectchage(){

        $('.categrey-option .erji-box a').click(function(){
            $(this).parents('.categrey-option').find('.result-box em').removeClass('active');

            $(this).parent().find('a').removeClass('active');
            $(this).addClass('active');
        });

        $('.categrey-option .result-box em').click(function(){
            $(this).parents('.categrey-option').find('.erji-box a').removeClass('active');

            $(this).parent().find('em').removeClass('active');
            $(this).addClass('active');
        });
}
//添加
var add_arr = [];
var flag=0;
$('.categrey-option .btn-box .btn1').click(function(){  
    $(this).parents('.categrey-option').find('.erji-box a').each(function(){
        if($(this).hasClass('active')){
            var txt = $(this).text();
            if($("#val").val()==0){
            var inputid=$("#purcateid").val();
            }else{
            var inputid=$("#sellcateid").val();    
            
            }
            var id =$(this).attr("id");

            if(inputid==''){
                if($("#val").val()==0){
                  $("#purcateid").val(id);
                }else{
                  $("#sellcateid").val(id);
                }
                add_arr.push(txt);
                add_arr.push(id);

            }else{
                if($('#val').val()==0){
                    $("#purcateid").val(id+","+inputid);
                    if($("#purcateid").val().indexOf(","+id)>0||$("#purcateid").val().indexOf(id)>0){
                        alert("分类信息重复");
                        flag=1;
                        $("#purcateid").val(inputid);
                    }else{
                         add_arr.push(txt);
                         add_arr.push(id);
                    }
                }else{
                    $("#sellcateid").val(id+","+inputid);
                    if($("#sellcateid").val().indexOf(","+id)>0||$("#sellcateid").val().indexOf(id)>0){
                        alert("分类信息重复");
                        flag=1;
                        $("#sellcateid").val(inputid);
                    }else{
                         add_arr.push(txt);
                         add_arr.push(id);
                    }      
                }
                
            }
            
        }else{
            return ;
        };
    });

    if(add_arr.length > 0){
        var str = "<em id="+add_arr[1]+">"+ add_arr[0] +"</em>";

        $(str).appendTo('.categrey-option .result-box');
        $(this).parents('.categrey-option').find('.result-box em').click(function(){
            $(this).parents('.categrey-option').find('.erji-box a').removeClass('active');
            $(this).parent().find('em').removeClass('active');
            $(this).addClass('active');
        });

        add_arr = [];

    }else{
        if(flag==0){
            alert("请选择分类！");
        }
        
    }
});

//删除
var delete_arr = [];
$('.categrey-option .btn-box .btn2').click(function(){          
    $(this).parents('.categrey-option').find('.result-box em').each(function(){
        if($(this).hasClass('active')){                 
            var txt = $(this).text();

            if($("#val").val()==0){
            var inputid=$("#purcateid").val();
            }else{
            var inputid=$("#sellcateid").val();    
            }
            var id =$(this).attr("id");
            if(inputid!=''&&id!=''){
                flag=1;
                if($('#val').val()==0){
                    if($("#purcateid").val()==id){
                        $("#purcateid").val("");
                        $(this).remove();
                    }
                    strs=inputid.split(","); //字符分割 
                    newstr=[];
                    for(i=0;i<strs.length;i++) 
                    {   
                        if(strs[i]!=id){
                          newstr.push(strs[i]); 
                        }

                    } 
                    $("#purcateid").val(newstr);
                    $(this).remove(); 
                }else{

                    if($("#sellcateid").val()==id){
                        $("#sellcateid").val("");
                        $(this).remove();
                    }
                    strs=inputid.split(","); //字符分割 
                    newstr=[];
                    for(i=0;i<strs.length;i++) 
                    {   if(strs[i]!=id){
                          newstr.push(strs[i]); 
                        }

                    } 
                    $("#sellcateid").val(newstr);
                    $(this).remove();     
                } 
            }           
            
        }else{
            return ;
        };
    });
    if(delete_arr.length > 0){
        return ;
    }else{
        if(flag==0){
        alert("请选择分类！");
        }
    };
});

//关闭层
$('.vip-box .close-btn').click(function(){
    $(this).parent().hide();
    $('.vip-layer').hide();
});
function chagea(){

  var selectid=$("#chage").val();

  $.getJSON('/ajax/getcatename', {pid:selectid}, function(data){
        content=utf8to16(base64decode(data));
        $(".erji-box a ").remove();
        $(".erji-box").append(content);
        add_arr=[];
        selectchage();
  });
}
function addpur(){
     var result=$("#result").html();
     //alert(result);
     //alert($('#addbox1').html());
     
     //$('#addbox1').html(result);
    if($("#val").val()==0){
        $('#addbox1').html(result);
    }else{
        $('#addbox2').html(result);
    }
    $('.vip-layer').hide();
    $('.vip-box').hide();   
}
var base64DecodeChars = new Array(
　　-1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1,
　　-1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1,
　　-1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, 62, -1, -1, -1, 63,
　　52, 53, 54, 55, 56, 57, 58, 59, 60, 61, -1, -1, -1, -1, -1, -1,
　　-1,　0,　1,　2,　3,  4,　5,　6,　7,　8,　9, 10, 11, 12, 13, 14,
　　15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, -1, -1, -1, -1, -1,
　　-1, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40,
　　41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, -1, -1, -1, -1, -1);
function base64encode(str) {
　　var out, i, len;
　　var c1, c2, c3;
　　len = str.length;
　　i = 0;
　　out = "";
　　while(i < len) {
c1 = str.charCodeAt(i++) & 0xff;
if(i == len)
{
　　 out += base64EncodeChars.charAt(c1 >> 2);
　　 out += base64EncodeChars.charAt((c1 & 0x3) << 4);
　　 out += "==";
　　 break;
}
c2 = str.charCodeAt(i++);
if(i == len)
{
　　 out += base64EncodeChars.charAt(c1 >> 2);
　　 out += base64EncodeChars.charAt(((c1 & 0x3)<< 4) | ((c2 & 0xF0) >> 4));
　　 out += base64EncodeChars.charAt((c2 & 0xF) << 2);
　　 out += "=";
　　 break;
}
c3 = str.charCodeAt(i++);
out += base64EncodeChars.charAt(c1 >> 2);
out += base64EncodeChars.charAt(((c1 & 0x3)<< 4) | ((c2 & 0xF0) >> 4));
out += base64EncodeChars.charAt(((c2 & 0xF) << 2) | ((c3 & 0xC0) >>6));
out += base64EncodeChars.charAt(c3 & 0x3F);
　　}
　　return out;
}
function base64decode(str) {
　　var c1, c2, c3, c4;
　　var i, len, out;
　　len = str.length;
　　i = 0;
　　out = "";
　　while(i < len) {
/* c1 */
do {
　　 c1 = base64DecodeChars[str.charCodeAt(i++) & 0xff];
} while(i < len && c1 == -1);
if(c1 == -1)
　　 break;
/* c2 */
do {
　　 c2 = base64DecodeChars[str.charCodeAt(i++) & 0xff];
} while(i < len && c2 == -1);
if(c2 == -1)
　　 break;
out += String.fromCharCode((c1 << 2) | ((c2 & 0x30) >> 4));
/* c3 */
do {
　　 c3 = str.charCodeAt(i++) & 0xff;
　　 if(c3 == 61)
　return out;
　　 c3 = base64DecodeChars[c3];
} while(i < len && c3 == -1);
if(c3 == -1)
　　 break;
out += String.fromCharCode(((c2 & 0XF) << 4) | ((c3 & 0x3C) >> 2));
/* c4 */
do {
　　 c4 = str.charCodeAt(i++) & 0xff;
　　 if(c4 == 61)
　return out;
　　 c4 = base64DecodeChars[c4];
} while(i < len && c4 == -1);
if(c4 == -1)
　　 break;
out += String.fromCharCode(((c3 & 0x03) << 6) | c4);
　　}
　　return out;
}
function utf16to8(str) {
　　var out, i, len, c;
　　out = "";
　　len = str.length;
　　for(i = 0; i < len; i++) {
c = str.charCodeAt(i);
if ((c >= 0x0001) && (c <= 0x007F)) {
　　 out += str.charAt(i);
} else if (c > 0x07FF) {
　　 out += String.fromCharCode(0xE0 | ((c >> 12) & 0x0F));
　　 out += String.fromCharCode(0x80 | ((c >>　6) & 0x3F));
　　 out += String.fromCharCode(0x80 | ((c >>　0) & 0x3F));
} else {
　　 out += String.fromCharCode(0xC0 | ((c >>　6) & 0x1F));
　　 out += String.fromCharCode(0x80 | ((c >>　0) & 0x3F));
}
　　}
　　return out;
}
function utf8to16(str) {
　　var out, i, len, c;
　　var char2, char3;
　　out = "";
　　len = str.length;
　　i = 0;
　　while(i < len) {
c = str.charCodeAt(i++);
switch(c >> 4)
{
　 case 0: case 1: case 2: case 3: case 4: case 5: case 6: case 7:
　　 // 0xxxxxxx
　　 out += str.charAt(i-1);
　　 break;
　 case 12: case 13:
　　 // 110x xxxx　 10xx xxxx
　　 char2 = str.charCodeAt(i++);
　　 out += String.fromCharCode(((c & 0x1F) << 6) | (char2 & 0x3F));
　　 break;
　 case 14:
　　 // 1110 xxxx　10xx xxxx　10xx xxxx
　　 char2 = str.charCodeAt(i++);
　　 char3 = str.charCodeAt(i++);
　　 out += String.fromCharCode(((c & 0x0F) << 12) |
　　　　((char2 & 0x3F) << 6) |
　　　　((char3 & 0x3F) << 0));
　　 break;
}
　　}
　　return out;
}
</script>
<script>
$('#perfect').validator({
        rules: {
            select: function(element, param, field) {
                return element.value > 0 || '请选择地区';
            },
            nimei  : [/^([0-9])+(\.([0-9])+)?$/, '请输入数字'],
            name: [/^[\u4E00-\u9FA5]+$/, '姓名格式错误'],
            zz_num:function(element, param, field) {
                return /^[0-9]+(.[0-9]{1,2})?$/.test(element.value) || '小数点后请保留2位';
            }
        },
        fields: {
            'name': '真实姓名:required;chinese;length[2~5]',
            // 'purcateid':'我关注的采购:required;',
            // 'sellcateid':'我关注的供应:required;'
        }
});

</script>

