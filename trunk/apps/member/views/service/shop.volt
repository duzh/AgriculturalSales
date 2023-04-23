<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/css/pingdao.css" />
{{ partial('layouts/page_header') }}

<!-- 主体内容开始 -->
<div class="fabu_gy w960 mt20 mb20">
  
    {{ form("sell/create", "method":"post","id":"newsell") }}
    <h6>免费发布供应信息</h6>
    <div class="gy_box">
        <p class="wx_tip">温馨提示：填写规范的信息能够被更多采购商看到</p>
        <!-- 块 start -->
        <div class="gy_step active">
            <!-- 左侧 start -->
            <div class="step f-fl">
                <span>1</span>
                <font>产<br>品</font>
            </div>
            <!-- 左侧 end -->
            <!-- 右侧 start -->
            <ul class="f-fl">
                <li>
                    <span class="label"><font>*</font>要发布的产品：</span>
                    <em><input type="text" name="title" data-rule="required;" /></em>
                </li>
                <li>
                    <span class="label"><font>*</font>所属分类：</span>
                    <em>
                        <select class="selectCate">
                            <option value="0">选择大类</option>
                        </select>
                        <select class="selectCate" name="category" data-rule="select">
                            <option value="0">选择小类</option>
                        </select>
                    </em>
                </li>
            </ul>
            <!-- 右侧 end -->
        </div>
        <!-- 块 end -->
        <!-- 块 start -->
        <div class="gy_step">
            <!-- 左侧 start -->
            <div class="step f-fl">
                <span>2</span>
                <font>上<br>传<br>图<br>片</font>
            </div>
            <!-- 左侧 end -->
            <!-- 右侧 start -->
            <div class="load_img f-fl" id="show_img">
                <div class="load_btn">
                    <span style="float: left;">上传产品图片</span>
                    <input type="file" value="浏览" id="img_upload">
                </div>
                <p>图片大小不超过2M，支持jpg、png、gif格式（使用高质量图片，可提高成交的机会）</p>
            </div>
            <!-- 右侧 end -->
        </div>
        <!-- 块 end -->
        <!-- 块 start -->
        <div class="gy_step gy_step_input">
            <!-- 左侧 start -->
            <div class="step f-fl">
                <span>3</span>
                <font>其<br>他<br>信<br>息</font>
            </div>
            <!-- 左侧 end -->
            <!-- 右侧 start -->
            <ul class="f-fl">
                <li>
                    <span class="label"><font>*</font>价格：</span>
                    <em>
                        <input class="mr10 w1" type="text" id="min_price" name="min_price" data-rule="最小价格:required;nimei" data-target="#c_price">一<input class="mr10 ml10 w1" type="text" id='max_price' name="max_price"  data-rule="最大价格:required;nimei;match[gt, min_price]" data-target="#c_price">
                        <select class="s1" id="goods_unit" name="goods_unit">
                            {% for key, val in goods_unit %}
                            <option value="{{ key }}">元/{{ val }}</option>
                            {% endfor %}
                        </select>
                        <i  id="c_price"></i>
                    </em>
                </li>
                <li>
                    <span class="label"><font>*</font>供应量：</span>
                    <em>
                        <input class="mr10" type="text" name="quantity" data-rule="required;integer[+];length[1~8]" data-target="#c_quantity">
                         <span id="cur_unit" >{{ cur_unit }}</span> 
                          <i  id="c_quantity"></i>
                    </em>
                      
                </li>
                <li>
                    <span class="label"><font>*</font>供货地：</span>
                    <em>
                        <select class="selectAreas">
                            <option>省</option>
                        </select>
                        <select class="selectAreas">
                            <option>市</option>
                        </select>
                        <select class="selectAreas" name="areas" data-rule="select">
                            <option value="0">县/区</option>
                        </select>
                        <input class="input1 mt10" type="text" value="" name="address" data-rule="required" data-target="#c_address"/>
                        <i  id="c_address" class="f-fl"  style="margin-top:14px;" ></i>
                    </em>
                </li>
                <li>
                    <span class="label"><font>*</font>上市时间：</span>
                    <em>
                        <select name="stime" id="s_stime" data-rule="select" data-target="#c_time">
                            <option value='0'>请选择</option>
                            {% for key, val in time_type %}
                            <option value='{{ key }}'>{{ val }}</option>
                            {% endfor %}
                        </select>
                        至
                        <select class="ml10" name='etime' id="s_etime" data-rule="select"  onchange='savetime()' data-target="#c_time">
                            <option value='0'>请选择</option>
                            {% for key, val in time_type %}
                            <option value='{{ key }}'>{{ val }}</option>
                            {% endfor %}
                        </select><a href="javascript:selectTime();">全年供应</a>
                        <i  id="c_time"></i>
                    </em>
                </li>
                <li>
                    <span class="label"><font>*</font>品种：</span>
                    <em>
                        <input type="text" name='breed' data-rule="required;" />
                    </em>
                </li>
                <li>
                    <span class="label"><font>*</font>规格：</span>
                    <em>
                        <input type="text" name="spec" data-rule="required;">
                    </em>
                </li>
                <li>
                    <span class="label"><font>*</font>详细描述：</span>
                    <em>
                        <textarea name="content" data-rule="required;"></textarea>
                    </em>
                </li>
            </ul>
            <!-- 右侧 end -->
        </div>
        <!-- 块 end -->
        {{ submit_button("确认发布","class":"fabu_btn ") }}
    </div>
    </form>
</div>
<!-- 主体内容结束 -->

{{ partial('layouts/footer') }}

<script type="text/javascript" src="{{ constant('STATIC_URL') }}mdg/js/inputFocus.js"></script>
<script type="text/javascript" src="/uploadify/jquery.uploadify.min.js" ></script>
<link rel="stylesheet" type="text/css" href="/uploadify/uploadify.css">
<script>
jQuery(document).ready(function(){
    // var gyInput = $('.gy_step li input');
    // inputFb(gyInput);

    $(".gy_step").bind("click",function(){
        $(this).addClass("active").siblings().removeClass("active");
    })

    $('#goods_unit').change(function() {
        var cur_unit = $(this).find('option:selected').text();
        cur_unit = cur_unit.split('/');
        $('#cur_unit').html(cur_unit[1]);
    })

    $('#newsell').validator({
        rules: {
            select: function(element, param, field) {
                return element.value > 0 || '请选择';
            },
            nimei  : [/^([0-9])+(\.([0-9])+)?$/, '请输入数字'],
            xxxxx: function(element, param, field) {
                return $('#min_price').val() < $('#max_price').val() || '最大价格必须大于价格！';
            },
        }
    });
    setTimeout(function(){
          $('#img_upload').uploadify({
                'swf'      : '/uploadify/uploadify.swf',
                'uploader' : '/upload/tmpfile',
                'fileSizeLimit' : '2MB',
                'fileTypeExts' : '*.jpg;*.png;*.jpeg;*.bmp;*.png',
                'formData' : {
                    'sid' : '{{ sid }}',
                    'type' :'1',
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
          });
    },10);
});

var $ld5 = $(".selectCate");                     
$ld5.ld({ajaxOptions : 
  {"url" : "/ajax/getcate"},
    defaultParentId : 0,
    style : {"width" : 140}
})  

$ld5.eq(1).bind("change",onchange);
function onchange(e){
    var target = $(e.target);
    var selectval = target.val();
     jQuery.get('/upload/tmpcatefile', {selectval:selectval,sid:'{{sid}}'}, function(data) {
          
           if(data!=''){
               $("#categroyimg").remove();
                $('#show_img').append(data);
           }
     });
}
// 地区联动
$(".selectAreas").ld({ajaxOptions : {"url" : "/ajax/getareas"},
    defaultParentId : 0,
    style : {"width" : 140}
});

function selectUnit(val) {
    alert(val);
}

// 全年供应
function selectTime() {
    $('#s_stime').val(11);
    $('#s_etime').val(123);
}

// 删除图片
function closeImg(obj, id) {
    $.getJSON('/upload/deltmpfile', {id : id}, function(data) {
        alert(data.msg);
        if(data.state) {
            $(obj).parents('dl').slideUp();
        }
    });
}
function deleteImg(obj, id) {

    $.getJSON('/member/sell/delimg', {id : id}, function(data) {
        alert(data.msg);
        if(data.state) {
            $(obj).parents('dl').slideUp();
        }
    });
}
</script>

<style>
.upload_btn {width: 121px;height: 31px;line-height: 31px;text-align: center;background: url({{ constant('STATIC_URL') }}mdg/images/yz_btn.png) no-repeat;background-position: 0 0;top: 0;left: 88px;color: #7f7f7f; margin-left:75px;}
</style>

