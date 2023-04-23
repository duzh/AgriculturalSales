{{ partial('layouts/page_header') }}
        <div class="bread-crumbs w1185 mtauto">
           {{ partial('layouts/ur_here') }}我的供应
        </div>
        <div class="w1185 mtauto clearfix">
            <!-- 左侧 -->
            {{ partial('layouts/navs_left') }}
            <!-- 右侧 -->
            <div class="center-right f-fr">
                <form action="/member/Perfect/save" method="post" id="perfect"> 
                <div class="my-information">
                
                    <div class="title f-oh">个人信息</div>
                    <div class="message clearfix">
                        <font class="name">会员ID</font>
                        <div class="msg-box">{{users.id}}</div>
                    </div>
                    <div class="message clearfix">
                        <font class="name">手机号码</font>
                        <div class="msg-box">{{users.username}}</div>
                    </div>
                    <div class="message clearfix">
                        <font class="name">姓名</font>
                        <div class="msg-box">
                         {% if (users.ext) %}{{ users.ext.name }}{% endif %}
                        </div>
                    </div>
                    <div class="message clearfix">
                        <font class="name">性别</font>
                        <div class="radio-box">
                            <label>
                                 {% if users.ext and  users.ext.sex == 0 %} <font>先生</font> {% endif %}
                            </label>
                            <label>
                                {% if users.ext and  users.ext.sex == 1 %} <font>女士</font> {% endif %}
                            </label>
                        </div>
                    </div>
                    <div class="message clearfix">
                        <font class="name">主营业务</font>
                        <div class="msg-box">
                            {{mgoods}}
                        </div>
                    </div>
                    <div class="message clearfix">
                        <font class="name">关注的采购</font>

                        <div class="msg-box" id="purtxt">
                            <b style="font-weight:normal;" id="addbox1">  
                                {% if purchasecate %}
                                {% for key,val in purchasecate %}
                                {{val['category_name']}}
                                {% endfor %}
                            </b>
                           
                            {% else %}
                              暂无
                            {% endif %}
                            
                            <p class="help-block" id="purmsg" ></p>
                        </div>
                    </div>
                    <div class="message clearfix" id="selltxt">
                        <font class="name">关注的供应</font>
                        <div class="msg-box">
                            <b style="font-weight:normal;" id="addbox2" >
                                {% if sellcate %}
                                {% for key,val in sellcate %}
                                {{val['category_name']}}
                                {% endfor %}
                            </b>
                
                            {% else %}
                           暂无
                            {% endif %}
                            <p class="help-block" id="sellmsg" ></p>
                        </div>
                    </div>
                    <div class="message clearfix">
                        <font class="name">所在区域</font>
                        <div class="msg-box">
                         {{curAreas}}
                        </div>
                    </div>
                </div>
                </form>
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
                        <select  id="chage" onclick="chage()">
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
        <input class="btn" type="submit" value="确定" onclick="addpur()" />
    </div>
    <!-- 底部 -->
{{ partial('layouts/footer') }}

<script>
// 表单分类添加删除
(function(){
   selectchage();
})();
function editForm(val,content){
    content=utf8to16(base64decode(content));
    if(content!=''){
        $("#result").html(content);
        selectchage();
    }else{
        $("#result").html("");    
    }
    val = val; //用参数val来判断修改的是哪个信息
    $("#val").val(val);
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
function chage(){
  var selectid=$("#chage").val();
  $.getJSON('/member/ajax/getcatename', {pid:selectid}, function(data){
        content=utf8to16(base64decode(data));
        $(".erji-box a ").remove();
        $(".erji-box").append(content);
        add_arr=[];
        selectchage();
  });
}
function addpur(){
    var result=$("#result").html();
    if($("#val").val()==0){
        $('#addbox1').html('');
        $('#addbox1').html(result);
    }else{
        $('#addbox2').html();
        $('#addbox2').html(result);
    }
    $('.vip-layer').hide();
    $('.vip-box').hide();   
    $("#result").html('');
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
            'name': '真实姓名:required;chinese;length[2~5]'
            // 'purcateid':'我关注的采购:required;',
            // 'sellcateid':'我关注的供应:required;'
        }
});
$(".selectAreas").ld({ajaxOptions : {"url" : "/ajax/getareasfull"},
    defaultParentId : 0,
    {% if ( curAreas ) %}
    texts : [{{curAreas}}],
    {% endif %}
    style : {"width" : 120}
   
});
setTimeout(function(){
          $('#img_upload').uploadify({
                'swf'      : '/uploadify/uploadify.swf',
                'uploader' : '/upload/tmpfile',
                'fileSizeLimit' : '10MB',
                'fileTypeExts' : '*.jpg;*.png;*.jpeg;*.bmp;*.png',
                'formData' : {
                    'sid' : '{{ sid }}',
                    'type' : '27'
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
                        $('#show_img').attr("src",data.path);
                    }
                }
          })
},10);
</script>
  <style>
.upload_btn {width: 121px;height: 31px;line-height: 31px;text-align: center;background: url({{ constant('STATIC_URL') }}mdg/images/yz_btn.png) no-repeat;background-position: 0 0;top: 0;left: 88px;color: #7f7f7f; margin-left:75px;}
</style>

