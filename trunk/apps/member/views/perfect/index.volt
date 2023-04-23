<!--头部-->
{{ partial('layouts/member_header') }}
<link rel="stylesheet" href="http://yncstatic.b0.upaiyun.com/mdg/version2.5/css/verfiy.css">
<div class="wrapper">
    <div class="w1190 mtauto f-oh">
        <div class="bread-crumbs w1185 mtauto">
            <span>{{ partial('layouts/ur_here') }}完善资料</span>
        </div>
        <!-- 左侧 -->
        {{ partial('layouts/navs_left') }}
        <!-- 右侧 -->
        <div class="center-right f-fr">

            <div class="perfect-info f-oh pb30">
                <div class="title f-oh">
                    <span>完善资料</span>
                </div>
                <form action="/member/Perfect/save" method="post"  id="perInfo-form">
                    <div class="message clearfix">
                        <font>会员ID：</font>
                        <div class="content">{{users.id}}</div>
                    </div>
                    <div class="message clearfix">
                        <font>手机号码：</font>
                        <div class="content">{{users.username}}</div>
                    </div>
                    <div class="message clearfix">
                        <font>姓名：</font>
                        <div class="inputBox f-pr">
                            <input name="name" type="text"  value="{% if (users.ext) %}{{ users.ext.name }}{% endif %}">
                        </div>
                    </div>
                    <div class="message clearfix">
                        <font>性别：</font>
                        <div class="radioBox f-pr f-oh">
                            <label class="f-db f-fl">
                                <input type="radio" {% if users.ext and  users.ext.sex == 0 %} checked {% endif %}   name="sex" value="0"/>先生
                            </label>
                            <label class="f-db f-fl">
                                <input type="radio"  name="sex" value="1" {% if users.ext and users.ext.sex == 1 %} checked {% endif %} />女士
                            </label>
                        </div>
                    </div>
                    <div class="message clearfix">
                        <font>主营业务：</font>
                        <div class="selectBox f-pr">
                                <select name="mgoodscate">
                                {% for key,val in cate %}
                                {% if val["parent_id"] == 0 %}
                                <option value="{{val['id']}}" {% if users.ext and users.ext.main_category == val['id'] %} selected='selected' {% endif %} >{{val["title"]}}</option>
                                {% endif %}
                                {% endfor %}
                            </select>
                        </div>
                    </div>
                    <div class="message clearfix">
                        <font>我关注的采购：</font>
                        <div class="content clearfix">
                            <i id="addbox1">                                
                                {% for key,val in purchasecate %}
                                {{val['category_name']}}
                                {% endfor %}
                            </i>
                            {% if purchasecate %}
                            <a href="javascript:;" onclick = "editForm(0,'{{purchasetxt}}')">修改</a>
                            {% else %}
                             <a href="javascript:;" onclick = "editForm(0,'{{purchasetxt}}')">增加</a>
                            {% endif %}
                            <i class="help-block" id="purmsg" ></i>
                        </div>
                    </div>
                    <div class="message clearfix">
                        <font>我关注的供应：</font>
                        <div class="content clearfix">
                            <i id="addbox2">
                                {% for key,val in sellcate %}
                                {{val['category_name']}}
                                {% endfor %}
                            </i>
                            {% if sellcate %}
                            <a href="javascript:;" onclick = "editForm(1,'{{selltxt}}')">修改</a>
                            {% else %}
                             <a href="javascript:;" onclick = "editForm(1,'{{selltxt}}')">增加</a>
                            {% endif %}
                            <i class="help-block" id="sellmsg" ></i>
                        </div>
                        
                    </div>
                    
                    <div class="message clearfix">
                        <font>所在区域：</font>
                        <div class="selectBox f-pr">
                            <select class="selectAreas" name="province_id">
                                <option value="">请选择</option>
                            </select>
                            <select class="selectAreas" name="city_id">
                                <option value="">请选择</option>
                            </select>
                            <select class="selectAreas" name="district_id">
                                <option value="">请选择</option>
                            </select>
                            <select class="selectAreas" name="town_id">
                                <option value="">请选择</option>
                            </select>
                            <select name="areas" class="selectAreas"  >
                                <option value="">请选择</option>
                            </select>
                        </div>
                    </div>
                    
                    
                    <input class="confirm-btn" type="submit" value="保  存">
                    <input type="hidden" id="purcateid" name="purcateid" value="{{purchasecateid}}" data-target="#purmsg" >
                    <input type="hidden" id="val" >
                    <input type="hidden" id="sellcateid" name="sellcateid"  value="{{sellcateid}}" data-target="#sellmsg" >
                </form>
            </div>

        </div>      

    </div>
<!-- 采购修改 弹层 -->
    <div class="vip-layer"></div>
    <div class="vip-box">
        <a class="close-btn" href="javascript:;"></a>
        <div class="form-attest info-box">
            <div class="message clearfix">
                <font>选择类别</font>
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

    
    
    
    
</div>

<!--底部-->
{{ partial('layouts/footer') }}

<script>
// 表单分类添加删除
(function(){
   selectchage();
})();
var sell = new Array();
var pur  = new Array();

{% if purchasecate %}
{% for key , val in purchasecate %}
    pur[{{val['category_id']}}] = "{{ val['category_name']}}";
    {% endfor %}
{% endif %}


{% if sellcate %}
{% for key , val in sellcate %}
    sell[{{val['category_id']}}] = "{{ val['category_name']}}";
    {% endfor %}
{% endif %}

function showSel(val) {
    var msg = '';
    if(val==0){
        for (i = 1; i < pur.length; i++)
        {
            if(pur[i] != "" && typeof(pur[i]) != "undefined")
            {
              msg+= '<em id="'+ i +'">'+ pur[i] +'</em>';
            }
        }
    }else{
        for (i = 1; i < sell.length; i++)
        {
            if(sell[i] != "" && typeof(sell[i]) != "undefined")
            {
              msg+= '<em id="'+ i +'">'+ sell[i] +'</em>';
            }
        }
    }
    $("#result").html(msg);
}

var t_pur,t_sell;
/**
 * 编辑订单
 * @param  {[type]} val 数据
 * @return {[type]}     [description]
 */
function editForm(val){
    t_pur = pur;
    t_sell = sell;
    $("#val").val(val);
    showSel(val);
    $('.vip-layer').show();
    $('.vip-box').show();
    // $("#result").html(msg); 
    selectchage();
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
    var val = $("#val").val();
    var $sel = $('.erji-box').find("a[class='active']");
    // console.log($sel.attr('id'));
    var arr = 0 == val ? pur : sell;
    console.log(typeof arr[$sel.attr('id')]);
    if('undefined' != typeof arr[$sel.attr('id')]) {
        alert('此类别已添加');
        return;
    }
    if(0 == val) {
        pur[$sel.attr('id')] = $sel.html();
    } else {
        sell[$sel.attr('id')] = $sel.html();
    }
    showSel(val);
    selectchage();
});

//删除
$('.categrey-option .btn-box .btn2').click(function(){ 

    var val = $("#val").val();
    var $sel = $('.result-box').find("em[class='active']");
    var arr = 0 == val ? pur : sell;
    if('undefined' == typeof arr[$sel.attr('id')]) {
        return;
    }
    if(0 == val) {
        pur.splice($sel.attr('id'), 1);
    } else {
        sell.splice($sel.attr('id'), 1);
    }
    showSel(val);
    selectchage();
});


//关闭层
$('.vip-box .close-btn').click(function(){
    pur = t_pur;
    sell = t_sell;
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

    var v_pur = new Array();
    var k_pur = new Array();
    var v_sell = new Array();
    var k_sell = new Array();
    for (i = 1; i < pur.length; i++)
    {
        if(pur[i] != "" && typeof(pur[i]) != "undefined")
        {
            v_pur.push(pur[i]);
            k_pur.push(i);
        }
    }
    for (i = 1; i < sell.length; i++)
    {
        if(sell[i] != "" && typeof(sell[i]) != "undefined")
        {
            v_sell.push(sell[i]);
            k_sell.push(i);
        }
    }
    $('#addbox1').html(v_pur.join(','));
    $('#addbox2').html(v_sell.join(','));
    $('#purcateid').val(k_pur.join(','));
    $('#sellcateid').val(k_sell.join(','));
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
$(function(){
    // 验证
    $('#perInfo-form').validator({
        fields: {
            'name': '姓名:required;fuck;length[2~10]',
            'main-business': '主营业务:required;',
            'areas': '所在区域:required;'
        }
    });
});
$(".selectAreas").ld({ajaxOptions:{"url":"/ajax/getareasfull"},
    defaultParentId : 0,
    {% if (curAreas) %}
    texts : [{{ curAreas }}],
    {% endif %}
    style : {"width" : 140}
});
</script>
