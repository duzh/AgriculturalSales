<!--头部-->
{{ partial('layouts/member_header') }}
<link rel="stylesheet" href="{{ constant('STATIC_URL') }}mdg/version2.5/css/verfiy.css">
<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/version2.5/css/jquery.powertip.css" />
<script type="text/javascript" src="{{ constant('STATIC_URL') }}mdg/version2.5/js/jquery.powertip.min.js"></script>
<style>
#powerTip{ padding: 10px; max-width:242px; color:#999; font-family:'宋体'; line-height:24px; background:#fff2d8; white-space:normal; word-wrap:break-word;}
#powerTip.sw-alt:before, #powerTip.se-alt:before, #powerTip.s:before{ border-bottom:10px solid #fff2d8;}
#powerTip.nw-alt:before, #powerTip.ne-alt:before, #powerTip.n:before{ border-top:10px solid #fff2d8;}
</style>
<div class="wrapper">
    <div class="w1190 mtauto f-oh">
        <!-- 左侧 -->
        {{ partial('layouts/navs_left') }}
        <!-- 右侧 -->
            <div class="center-right f-fr">
                <form action="/member/lwtt/save" method="post" id='rolesForm'>
                <input type="hidden" name="cid" id="cid" value="{{ credit_id }}" >
                <input type="hidden" name="tp" id="tp" value="{{ users.type }}" >
                <div class="roles-apply f-oh">
                    <div class="title f-oh">
                        <span>产地服务站申请</span>
                    </div>
                    <div class="m-title">基本信息</div>

                        <div class="tabChange" style="display:block;">
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>姓名：
                                </font>
                                <div class="inputBox inputBox1 f-pr">
                                    <input name="user_name" class="input1" value="{{users ? users.name : '' }}" type="text">
                                </div>
                            </div>
                             <div class="message clearfix">
                                <font>
                                    <i>*</i>用户类型：
                                </font>
                                <div class="radioBox f-oh tabBtn">
                                    <label class="f-db f-fl f-oh">
                                     <input type="radio" name='member_type' {% if users and users.type == 0  %}checked='checked'{% endif %}value='0' >
                                        <i>个体户</i>
                                    </label>
                                    <label class="f-db f-fl f-oh">
                                        <input type="radio" {% if users and users.type == 1 %}checked='checked'{% endif %} name='member_type' value='1'>
                                        <i>企业</i>
                                    </label>
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>手机号：
                                </font>
                                <div class="inputBox inputBox1 f-pr">
                                    <input name="user_mobile"  value="{{users ? users.phone : '' }}" class="input1" type="text">
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>身份证号：
                                </font>
                                <div class="inputBox inputBox1 f-pr">
                                    <input name="user_credit_no" value="{{users ? users.certificate_no : '' }}"  class="input1" type="text">
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>相关工程师手机号：
                                </font>
                                <div class="inputBox inputBox1 f-pr">
                                    <input name='semobile' class="input1" type="text" value="{{engineerinfo ? engineerinfo.engineer_phone : '' }}">
                                    <div class="ms-personalTips">
                                        <a class="south-west-alt" title="工程师是丰收汇在各地区安排的相关业务服务人员。" href="javascript:;">什么是工程师</a>
                                        <em>|</em>
                                        <a class="south-west-alt" title="丰收汇在全国各地正在开展活动，如果您不知道所在地区的工程师联系信息，请拨打24小时客服电话400-8811-365进行咨询。" href="javascript:;">没有工程师手机号怎么办</a>
                                    </div>
                                </div>
                            </div>
                            <div class="line"></div>
                            <div class="m-title">认证信息</div>
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>身份证照：
                                </font>
                                <div class="loadBox f-pr">
                                    <div class="loadBtn">
                                        <input class="btn1" type="button" value="+上传图片">
                                        <input class="btn2" type="file" id='user_idcard_picture'>
                                    </div>
                                    <a class="link" href="{{ constant('STATIC_URL')}}/mdg/images/lodeImg_img3.jpg" target="_blank">查看样照</a>
                                    <input class="hideTxt" name="photo" id="user_idcard_picture_hide" type="text" {% if userbank and userbank.idcard_picture %} 
                                     value="1" {% else %} value="" {% endif %}   data-target="#user_idcard_picture_tip">
                                    <i id='user_idcard_picture_tip' style="left:176px; top:-6px;"></i>
                                    <div class="tips">图片大小不超过2M，支持jpg、png、gif格式</div>
                                </div>
                            </div>
                            <!-- 上传成功后的图片位置 -->
                            <div class="imgBox f-oh">
                                <div class="imgs f-fl f-pr" id='user_show_idcard_picture'>
                                    {% if userbank and userbank.idcard_picture %}
                                    <div class="imgs f-fl f-pr">
                                        <img src="{{ constant('IMG_URL')}}{{ userbank.idcard_picture }}" height="120" width="120" alt="">
                                    </div>
                                    {% endif %}
                                </div>
                            </div>
                            <div class="message clearfix">
                                <font>
                                    <i>*</i>身份证背面照：
                                </font>
                                <div class="loadBox f-pr">
                                    <div class="loadBtn">
                                        <input class="btn1" type="button" value="+上传图片">
                                        <input class="btn2" type="file" id='user_idcard_picture_back'>
                                    </div>
                                    <a class="link" href="{{ constant('STATIC_URL')}}/mdg/images/lodeImg_img3.jpg" target="_blank">查看样照</a>
                                    <input class="hideTxt" name="back-photo" id="user_idcard_picture_back_hide" style="width:1px;opacity:0;filter:alpha(opacity:0);" type="text" {% if userbank and userbank.idcard_picture_back %} 
                                     value="1" {% else %} value="" {% endif %}  data-target="#user_idcard_picture_back_tip">
                                    <i id='user_idcard_picture_back_tip' style="left:176px; top:-6px;"></i>
                                    <div class="tips">图片大小不超过2M，支持jpg、png、gif格式</div>
                                </div>
                            </div>
                            <!-- 上传成功后的图片位置 -->
                            <div class="imgBox f-oh">
                                <div class="imgs f-fl f-pr" id='user_show_idcard_picture_back'>
                                    {% if userbank and userbank.idcard_picture_back %}
                                    <div class="imgs f-fl f-pr">
                                        <img src="{{ constant('IMG_URL')}}{{ userbank.idcard_picture_back }}" height="120" width="120" alt="">
                                    </div>
                                    {% endif %}
                                </div>
                            </div>
                                <div class="m-title">经营类别</div>
                                <div class="message clearfix">
                                <font>
                                    <i>*</i>选择类别：
                                </font>
                                <div class="select-box lang-select clearfix categrey-option f-pr" style="width:518px;">
                                <div class="choose-box f-fl" id='result-box_1' >
                                        <select name='category_name' onchange="selectBycate(this.value, '0' )" >
                                            {% for key , item in cateList %}
                                            <option value="{{ item.id }}">{{ item.title}}</option>
                                            {% endfor %}
                                        </select>
                                        <div class="erji-box" id='show_cate_chid_0'>
                                        </div>
                                    </div>
                                    <div class="btn-box f-fl">
                                        <a class="btn1" href="javascript:;">添加</a>
                                        <a class="btn2" href="javascript:;">删除</a>
                                    </div>
                                    <div class="result-box f-fl" id='result-box_0' >
                                        {% if crops %}
                                        {% for key , item in crops %}
                                        <em data-id="{{ item['category_id'] }}">{{ item['category_name'] }}</em>
                                        {% endfor %}
                                        {% endif %}
                                    </div>
                                    <input id="category_name_text_0"  {% if crops %} value="1" {% else %} value="" {% endif %} name='category_name_text_0' style="width:1px;border:none;opacity:0;filter:alpha(opacity:0);" type="text" value=""  data-rule="经营类别:required;" />
                                </div>
                            </div>
                        </div>
                       <div class="btns f-oh">
                            <input class="apply-btn f-fl" type="submit" value="提交申请">
                            <a href="{{url}}"> <input class="back-btn f-fl" type="button" value="取消并返回"></a>
                        </div>
                        
                    </form>
                </div>

            </div>
        <!-- 右侧end-->
    </div>
</div>
<!--底部-->
{{ partial('layouts/footer') }}}

<script type="text/javascript">
$(function(){
    // 验证
    $('#rolesForm').validator({
        ignore: ':hidden',
        fields: {
            'user_name': '姓名:required;fuck;length[2~10]',
            'user_credit_no': '身份证号:required;ID_card',
            'user_mobile': '手机号:required;mobile',
            'photo': '身份证照:required;',
            'back-photo': '身份证背面照:required;',
            'semobile': '工程师账号:required;mobile;remote[/member/lwtt/checkEngineer]'
        }
    });
    selectBycate(1, 0);
    $('.south-west-alt').powerTip({
        placement: 's',
        smartPlacement: true
    })
    var sid = '';
    var upload_total = {};
    /*  获取所有选中分类结果 */
    function getAllCateResult(type) {
        /* 获取result */
        var txt = '';
        $('#result-box_' + type).find('em').each(function() {
            txt += $(this).attr('data-id') + ',';
        });

        $('#category_name_text_' + type).val(txt);
        if (txt) {
            $('#category_name_text_' + type).next('i').html('<span class="msg-wrap n-ok" role="alert"><span class="n-icon"></span><span class="n-msg"></span></span>');
        } else {
            $('#category_name_text_' + type).next('i').html('<span class="msg-wrap n-error" role="alert"><span class="n-icon"></span><span class="n-msg">不能为空</span></span>');
        }


    }
    //添加
    var add_arr = [];
    var add_cid = [];

    function selectBycate(cid, type) {

        $.get('/ajax/getcate', {
            'parent_id': cid
        }, function(json) {
            var str = '';
            data = eval(json);
            for (var o in data) {
                str += '<a href="javascript:;" data-id="' + data[o].region_id + '">' + data[o].region_name + '</a>';
            }

            $(".categrey-option .erji-box a").unbind("click");
            $(".categrey-option .btn-box .btn1").unbind("click");
            $(".categrey-option .btn-box .btn2").unbind("click");
            $(".categrey-option .result-box em").unbind("click");
            $('#show_cate_chid_' + type).html(str);

            $('.categrey-option .erji-box a').click(function() {
                $(this).parents('.categrey-option').find('.result-box em').removeClass('active');
                $(this).parent().find('a').removeClass('active');
                $(this).addClass('active');
            });

            $('.categrey-option .result-box em').click(function() {
                $(this).parents('.categrey-option').find('.erji-box a').removeClass('active');
                $(this).parent().find('em').removeClass('active');
                $(this).addClass('active');
            });

            //添加
            var add_arr = [];
            var add_cid = [];
            var flag = 0;

            $('.categrey-option .btn-box .btn1').click(function() {
                $(this).parents('.categrey-option').find('.erji-box a').each(function() {
                    if ($(this).hasClass('active')) {
                        var txt = $(this).text();
                        var cid = $(this).attr('data-id');
                        add_arr.push(txt);
                        add_cid.push(cid);

                    } else {
                        return;
                    };
                });


                if (add_arr.length > 0) {
                    /* 检测是否有重复数据 */
                    flag = 0;
                    $('#result-box_' + type).find('em').each(function(index, el) {
                        if ($.trim($(el).text()) == $.trim(add_arr[0])) {
                            flag = 1;
                        }
                    });

                    if (flag) {
                        alert('分类重复');
                        add_arr = [];
                        add_cid = [];
                        return false;
                    }

                    var str = "<em data-id='" + add_cid[0] + "'>" + add_arr[0] + "</em>";
                    $(str).appendTo('#result-box_' + type);
                    getAllCateResult(type);
                    $(this).parents('.categrey-option').find('.result-box em').click(function() {
                        $(this).parents('.categrey-option').find('.erji-box a').removeClass('active');

                        $(this).parent().find('em').removeClass('active');
                        $(this).addClass('active');
                    });

                    add_arr = [];
                    add_cid = [];
                    flag = 0;

                } else {
                    alert("请选择分类！");
                }
            });


            //删除
            var delete_arr = [];
            $('.categrey-option .btn-box .btn2').click(function() {
                $(this).parents('.categrey-option').find('.result-box em').each(function() {
                    if ($(this).hasClass('active')) {
                        var txt = $(this).text();
                        delete_arr.push(txt);
                        $(this).remove();
                    } else {
                        return;
                    };
                });
                  
                if (delete_arr.length > 0) {
                    getAllCateResult(type);
                    return;
                } else {
                    alert("请选择分类！");
                };
            });

        });
    }
    var jsFileName = "user_farm.js";
    var rName = new RegExp(jsFileName + "(\\?(.*))?$")
    var jss = document.getElementsByTagName('script');
    for (var i = 0; i < jss.length; i++) {
        var j = jss[i];
        if (j.src && j.src.match(rName)) {
            var oo = j.src.match(rName)[2];
            if (oo && (t = oo.match(/([^&=]+)=([^=&]+)/g))) {
                for (var l = 0; l < t.length; l++) {
                    r = t[l];
                    var tt = r.match(/([^&=]+)=([^=&]+)/);
                    if (tt && tt[1] == 'sid') {
                        sid = tt[2];
                    }
                }
            }
        }
    }
    });
</script>
