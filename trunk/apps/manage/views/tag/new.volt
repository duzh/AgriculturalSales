<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}/mdg/css/pingdao.css" />
<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}/mdg/version2.3/css/mix-bq-gy.css" />
<script type="text/javascript" src="{{ constant('JS_URL') }}lhgdialog/lhgdialog.min.js?skin=igreen"></script>
<link rel="stylesheet" type="text/css" href="{{ constant('STATIC') }}/mdg/css/channel_pindao.css" />



<div class="ur_here w960">
    <span>{{ partial('layouts/ur_here') }}申请标签</span>
</div>
<script type="text/javascript">
 /* 种植信息 */
 var plantkey = 1;
 /* 农药 */
 var Pesticide = 1 ;
 /* 肥料*/
 var Manure = 1 ;

// 删除图片
function closeImg(obj, id) {

    $.getJSON('/upload/deltmpfile', {id : id}, function(data) {
        alert(data.msg);
        if(data.state) {
            $('#dl_'+id).slideUp();
        }
    });
}


//添加 肥料使用信息
function addManure() {
        var str = '<div class="gy_step gy_step_input f-pr"><a class="step_closeBnt pa" href="javascript:;" onclick="$(this).parent().remove();">关闭</a><div class="step f-fl" style="height:176px;"></div><ul class="f-fl"><li><span class="label">使用时期：</span><em><input style="width:174px;" type="text" name="manure_use_period['+Manure+']"/></em></li><li><span class="label">名称：</span><em><input style="width:174px;" type="text" name="manure_name['+Manure+']" /></em></li><li><span class="label">种类：</span><em><select name="manure_type['+Manure+']"><option value="">请选择</option> <option value="0">有机肥</option> <option value="1">化肥</option></select></em></li><li><span class="label">用量(千克/亩)：</span><em><input style="width:174px;" type="text" name="manure_amount['+Manure+']" /></em></li><li><span class="label">品牌：</span><em><input style="width:174px;" type="text"  name="manure_brand['+Manure+']"/></em></li><li><span class="label">供应商：</span><em><input style="width:174px;" type="text" name="manure_suppliers['+Manure+']"/></em></li></ul></div>';
        $(str).insertBefore($('#unit_add2'));
}
/**
 * 添加农药
 */
function addPesticide() {
    var str = '<div class="gy_step gy_step_input f-pr"><a class="step_closeBnt pa" href="javascript:;" onclick="$(this).parent().remove();">关闭</a><div class="step f-fl" style="height:176px;"></div><ul class="f-fl"><li><span class="label">使用时期：</span><em><input style="width:174px;" type="text" name="pesticide_use_period['+Pesticide+']"/></em></li><li><span class="label">名称：</span><em><input style="width:174px;" type="text" name="pesticide_name['+Pesticide+']"/></em></li><li><span class="label">用量(千克/亩)：</span><em><input style="width:174px;" type="text" name="pesticide_amount['+Pesticide+']"/></em></li><li><span class="label">品牌：</span><em><input style="width:174px;" type="text" name="pesticide_brand['+Pesticide+']" /></em></li><li><span class="label">供应商：</span><em><input style="width:174px;" type="text" name="pesticide_suppliers['+Pesticide+']"/></em></li></ul></div>';
        
        Pesticide++;
        $(str).insertBefore($('#unit_add3'));
}


function addpluat() {
        var str = '<div class="gy_step gy_step_input f-pr"><a class="step_closeBnt pa" href="javascript:;" onclick="$(this).parent().remove();">关闭</a><div class="step f-fl" style="height:176px;"></div><ul class="f-fl"><li><span class="label">作业类型：</span><em><select name="operate_type['+plantkey+']"><option value="">请选择</option>{% for key,val in _operate_type %}<option value="{{key}}">{{val}}</option>{% endfor %}</select></em></li><li><span class="label">时间：</span><em><input readonly="readonly" type="text" class="Wdate" name="begin_date['+plantkey+']" id="begin_date['+plantkey+']" onfocus="fun('+plantkey+')"/><font>—</font><input readonly="readonly" type="text" class="Wdate" name="end_date['+plantkey+']" id="end_date['+plantkey+']"  onfocus="fun1('+plantkey+')" /></em></li><li><span class="label">天气状态：</span><em><input style="width:174px;" type="text" name="weather['+plantkey+']" /></em></li><li><span class="label">作业内容：</span><em><textarea name="comment['+plantkey+']" onkeyup="checkLen(this)"></textarea></em></li><li><span class="label">作业照片：</span><div class="load_img f-fl" style="margin-top:0;"><div class="load_btn"> <input type="file" id="plant_'+plantkey+'" style="left:0;" value="浏览" /></div><p>图片大小不超过2M，支持jpg、png、gif格式（使用高质量图片，可提高成交的机会）</p><p id="plant_show_'+plantkey+'"></p></div></li></ul></div>';

        $(str).appendTo($('.bq_sq_box'));
        bankImg($("#plant_"+plantkey), '26', $("#plant_show_"+plantkey), $("#plant_show_"+plantkey),'append', plantkey  );
        plantkey++;
};

function bankImg(id,type,show_img,tip_id, limit,plant){
            //银行正面照
            id.uploadify({
                'width'    : '121',
                'height'   : '31',
                'swf'      : '/uploadify/uploadify.swf',
                'uploader' : '/upload/tmpfile',
                'fileSizeLimit' : '2MB',
                'fileTypeExts' : '*.jpg;*.png;*.jpeg;*.bmp;*.png',
                'formData' : {
                    'sid' : '{{ sid }}',
                    'type' :type,
                    'tag' : 1,
                    'plant' : plant
                    
                },
                'buttonClass' : 'upload_btn',
                'buttonText'  : '浏览',
                'multi'       : false,
                onDialogOpen : function() {
                    $('.gy_step').eq(1).addClass("active").siblings().removeClass("active");
                },
                onUploadSuccess  : function(file, data, response) {
                    data = $.parseJSON(data);
                    alert(data.msg);
                    if(data.status) {
                        // show_img.attr("src":data.path);
                        if(limit == 'append') {
                            show_img.append(data.html);
                        }else if(limit =='html'){
                            show_img.html(data.html);
                        }
                    //show_img.attr('src', data.path);
                    //show_img.parent().attr('href', data.path);
                    
                        tip_id.val(data.path);
                        tip_id.next('i').html('<span class="msg-wrap n-ok" role="alert"><span class="n-icon"></span><span class="n-msg"></span></span>');
                    }
                }
        });
    }


$(function(){
    
    
    // 地区联动
    $(".selectAreas").ld({ajaxOptions : {"url" : "/ajax/getareasfull"},
        defaultParentId : 0,
        <?php if(isset($address) && $address) {
            echo " texts : [{$address}],";
        }?>
        style : {"width" : 102}
    });

    $('#createTag').validator({
        rules: {
            //whd: [/^[\u4E00-\u9FA5]|\w+$/, '只能输入中文或字母或数字'],  //[a-zA-Z0-9\u4e00-\u9fa5]
			whd: [/^[a-zA-Z0-9\u4e00-\u9fa5]+$/, '只能输入中文或字母或数字'],	
            par: [/^-?\d+\.?\d{0,2}$/, '保留两位小数'],
        },
        fields: {
            'productor': 'required;whd;',
            'product_date' : 'required;',
            'expiration_date' : 'required;digits',
            'process_place': 'whd;',
            'process_merchant': 'whd;',
            'quality_level' : 'whd;',
            'pesticide_residue' : 'par;', 
            'heavy_metal' : 'par;',
            'inspector' : 'whd;',
            'product_place' : 'whd;',
            'manure' : 'whd;',
            'pollute' : 'whd;',
            'breed' : 'whd;',
            'seed_quality' : 'whd;',
            'manure_type' : 'whd;',
            'manure_amount' : 'par;',
            'pesticides_type' : 'whd;',
            'pesticides_amount' : 'par;',
            'process_type' : 'whd;',
            'village' : 'required;checked;'
        }
        // valid: function(form){
        //    // var tagservice = null;
        //    // tagservice =$.dialog({
        //    //      min   : false,
        //    //      max   : false,
        //    //      fixed : true,
        //    //      // autoSize:true,
        //    //      left  : 706, 
        //    //      top   : 246,
        //    //      lock  : true,
        //    //      title :"是否添加标签",
        //    //      content: "<strone>审核通过之后二维码和资料不可修改，请谨慎选择！</strone>",
        //    //      ok: function(){
        //    //          $.ajax({
        //    //              url: "/member/tag/create/",
        //    //              type:"Post",
        //    //              data: $(form).serialize(),
        //    //              success: function(d){
        //    //                  location.href="/member/sell/index/";
        //    //              }
        //    //         });
        //    //      },
        //    //      cancelVal: '取消',
        //    //      cancel: function(){
        //    //          tagservice.close();
        //    //      }
        //    //  });
        //    //  tagservice.size(328,138);
        // }
    });
    //权威机构安全鉴
    var certifying_file = $('#certifying_file');
    var certifying_fileshow = $('#certifying_fileshow');
    setTimeout(bankImg(certifying_file ,24,certifying_fileshow ,certifying_fileshow,'append', '') ,100);

    var product = $('#product');
    var product_show = $('#product_show');
    setTimeout(bankImg( product ,25,product_show ,product_show ,'append', '') ,100);

    var plant_0 = $('#plant_0');
    var plant_show_0 = $('#plant_show_0');
    setTimeout(bankImg(plant_0 ,26,plant_show_0, plant_show_0 ,'append', 0 ) ,100);
    

    
});

function checkLen(obj) {  
    var maxChars = 200;//最多字符数  
    if (obj.value.length > maxChars)  obj.value = obj.value.substring(0,maxChars);  
    var curr = maxChars - obj.value.length;  
    document.getElementByIdx_x("count").innerHTML = curr.toString(); 
} 


</script>

<!-- 主体内容开始 -->
<div class="wrapper f-oh" style="background:#f2f2f2;">
    <div class="fabu_gy w960 mt20 mb20 f-oh">
        <form action="/member/tag/create" method="post" id='createTag'>

            <h6>生成商品标签</h6>
            <div class="gy_box bq_sq_box">
                <p class="wx_tip">温馨提示：填写规范的信息能够被更多采购商看到</p>
                <!-- 块 start -->
                <div class="gy_step active">
                    <!-- 左侧 start -->
                    <div class="step f-fl">
                        <span>1</span> <font>基
                            <br />
                            本
                            <br />
                            信
                            <br />
                            息</font> 
                    </div>
                    <!-- 左侧 end -->
                    <!-- 右侧 start -->
                    <ul class="f-fl">
                        <li>
                            <span class="label"> <font>*</font>
                                品名：
                            </span> <em><i>{{ data.title }}</i></em> 
                        </li>
                        <li>
                            <span class="label">
                                <font>*</font>
                                所属分类：
                            </span> <em>
                            {% if maxcategory %}
                            <b>{{ maxcategory }}</b> 
                            {% endif %}
                            {% if twocategory %}
                            <b>{{ twocategory }}</b> 
                            {% endif %}
                            {% if category %}
                            <b>{{ category }}</b> 
                            {% endif %}

                        </em> 
                        </li>

                        <li>
                            <span class="label">
                                <font>*</font>
                                产地：
                            </span>
                            <em>
                                <select name="province" class="selectAreas" id="province">
                                    <option value="" selected>省</option>
                                </select>

                                <select name="city" class="selectAreas" id="city">
                                    <option value="">市</option>
                                </select>

                                <select name="county" class="selectAreas" id="town"   >
                                    <option value="">县</option>
                                </select>
                                <select name="townlet" class="selectAreas" id="town"   >
                                    <option value="">镇</option>
                                </select>
                                <select name="village" class="selectAreas" id="town"   >
                                    <option value="">村</option>
                                </select>

                            </em>
                        </li>
                        <li>
                            <span class="label"></span>
                            <em>
                                <input style="width:314px;" type="text" name='address'  placeholder='详细地址'/>
                            </em>
                        </li>

                        <li>
                            <span class="label">
                                <font>*</font>
                                生产商：
                            </span>
                            <em>
                                <input style="width:314px;" type="text" name='productor' />
                            </em>
                        </li>

                        <li>
                            <span class="label">
                                <font>*</font>
                                生产日期：
                            </span>
                            <em>
                                <input readonly="readonly" type="text" class="Wdate" name="product_date" id="product_date" onfocus="WdatePicker({maxDate:'#F{$dp.$D(\'product_date\',{M:0,d:0})}'})" ></em>
                        </li>
                        <li>
                            <span class="label">
                                <font>*</font>
                                保质期：
                            </span>
                            <em>
                                <input style="width:174px; margin-right:6px;" data-target="#bzq_tip" type="text" name='expiration_date' />
                                天
                                <span id="bzq_tip"></span>
                            </em>
                        </li>

                        <li>
                            <span class="label">加工地：</span>
                            <em>
                                <input style="width:174px;" type="text"  name='process_place'  value=''/>
                            </em>
                        </li>

                        <li>
                            <span class="label">加工商：</span>
                            <em>
                                <input style="width:174px;" type="text" name='process_merchant' value='' />
                            </em>
                        </li>
                    </ul>
                    <!-- 右侧 end --> </div>
                <!-- 块 end -->
                <!-- 块 start -->
                <div class="gy_step">
                    <!-- 左侧 start -->
                    <div class="step f-fl">
                        <span>2</span>
                        <font>质
                            <br />
                            量
                            <br />
                            评
                            <br />
                            估</font> 
                    </div>
                    <!-- 左侧 end -->
                    <!-- 右侧 start -->
                    <ul class="f-fl">
                        <li>
                            <span class="label">质量等级：</span>
                            <em>
                                <input style="width:314px;" type="text"  name='quality_level' />
                            </em>
                        </li>
                        <li>
                            <span class="label">农残检测：</span>
                            <em>
                                <input style="width:314px;" type="text" name='pesticide_residue' />
                            </em>
                        </li>
                        <li>
                            <span class="label">重金属含量：</span>
                            <em>
                                <input style="width:314px;" type="text" name='heavy_metal' />
                            </em>
                        </li>
                        <li>
                            <span class="label">
                                <!--<font>*</font>-->
                                是否转基因：
                            </span>
                            <em style="f-oh">
                                <label class="radioBtn f-db f-fl">
                                    <input class="f-fl" type="radio" value='1' name='is_gene'/>
                                    <font class="f-fl">是</font>
                                </label>
                                <label class="radioBtn f-db f-fl">
                                    <input class="f-fl" type="radio" value='0' name='is_gene' checked />
                                    <font class="f-fl">否</font>
                                </label>
                            </em>
                        </li>
                        <!-- <li>
                            <span class="label">检验员：</span>
                            <em>
                                <input style="width:314px;" type="text" name='inspector'/>
                            </em>
                        </li>
                        <li>
                            <span class="label">
                               
                                检验时间：
                            </span>
                            <em>
                                <input readonly="readonly" type="text" class="Wdate" name="inspect_time" id="inspect_time" onfocus="WdatePicker({maxDate:'#F{$dp.$D(\'inspect_time\',{M:0,d:0})}'})" ></em>
                        </li>
                        <li>
                            <span class="label">安全检机构名称：</span>
                            <em>
                                <input style="width:174px;" type="text" name='certifying_agency' />
                            </em>
                        </li> -->
                        <li>
                            <span class="label">权威机构安全鉴定文件：</span>
                            <div class="load_img f-fl" style="margin-top:0;">
                                    <div class="load_btn">
                                        <input type="file" id='certifying_file' name='certifying_file' style="left:0;" value="浏览" />
                                    </div>
                                <p >图片大小不超过2M，支持jpg、png、gif格式（使用高质量图片，可提高成交的机会）</p>
                                 <input type="hidden" id='certifying_filehiden' name='certifying_filehiden' style="left:0;" value="" />
                                        <input type="hidden" name='certifying_filei' id='certifying_filei' vlaue=''>
                                        <i></i>
                                <p id='certifying_fileshow'></p>
                               
                           

                            </div>
                        </li>
                    </ul>
                    <!-- 右侧 end --> </div>
                <!-- 块 end -->


        <!--
        **  我是分割线 以下代码拿走
        -->
        
        <!-- 块 start -->
        <div class="gy_step gy_step_input">
            <!-- 左侧 start -->
            <div class="step f-fl">
                <font>肥<br />料<br />使<br />用<br />信<br />息</font>
            </div>
            <!-- 左侧 end -->
            <!-- 右侧 start -->
            <ul class="f-fl">
                <li>
                    <span class="label">使用时期：</span>
                    <em>
                        <input style="width:174px;" type="text" name='manure_use_period[0]' />
                    </em>
                </li>
                <li>
                    <span class="label">名称：</span>
                    <em>
                        <input style="width:174px;" type="text" name='manure_name[0]'/>
                    </em>
                </li>
                <li>
                    <span class="label">种类：</span>
                    <em>
                        <select name='manure_type[0]'>
                            <option value=''>请选择</option>
                            <option value='1'>有机肥</option>
                            <option value='2'>化肥</option>
                        </select>
                    </em>
                </li>
                <li>
                    <span class="label">用量(千克/亩)：</span>
                    <em>
                        <input style="width:174px;" type="text" name='manure_amount[0]'/>
                    </em>
                </li>
                <li>
                    <span class="label">品牌：</span>
                    <em>
                        <input style="width:174px;" type="text" name='manure_brand[0]'/>
                    </em>
                </li>
                <li>
                    <span class="label">供应商：</span>
                    <em>
                        <input style="width:174px;" type="text" name='manure_suppliers[0]'/>
                    </em>
                </li>
            </ul>
            <!-- 右侧 end -->
        </div>
        <!-- 块 end -->
        <div class="unit_add f-oh" style="padding:0 38px;" id="unit_add2">
            <input class="fabu_btn f-fr" style="margin:0; margin-bottom:30px; width:100px;" type="button" value="继续添加"  onclick="addManure()" />
        </div>
        
        <!-- 块 start -->
        <div class="gy_step gy_step_input">
            <!-- 左侧 start -->
            <div class="step f-fl">
                <font>农<br />药<br />使<br />用<br />信<br />息</font>
            </div>
            <!-- 左侧 end -->
            <!-- 右侧 start -->
            <ul class="f-fl">
                <li>
                    <span class="label">使用时期：</span>
                    <em>
                        <input style="width:174px;" type="text" name='pesticide_use_period[0]'/>
                    </em>
                </li>
                <li>
                    <span class="label">名称：</span>
                    <em>
                        <input style="width:174px;" type="text" name='pesticide_name[0]' />
                    </em>
                </li>
                <li>
                    <span class="label">用量(千克/亩)：</span>
                    <em>
                        <input style="width:174px;" type="text" name='pesticide_amount[0]' />
                    </em>
                </li>
                <li>
                    <span class="label">品牌：</span>
                    <em>
                        <input style="width:174px;" type="text" name='pesticide_brand[0]' />
                    </em>
                </li>
                <li>
                    <span class="label">供应商：</span>
                    <em>
                        <input style="width:174px;" type="text" name='pesticide_suppliers[0]' />
                    </em>
                </li>
            </ul>
            <!-- 右侧 end -->
        </div>
        <!-- 块 end -->
        <div class="unit_add f-oh" style="padding:0 38px;" id="unit_add3">
            <input class="fabu_btn f-fr" style="margin:0; margin-bottom:30px; width:100px;" type="button" value="继续添加" onclick="addPesticide()" />
        </div>
        
        <!--
        **  我是分割线 代码拿到这结束
        -->
        


                <!-- 块 start -->
                <div class="gy_step gy_step_input">
                    <!-- 左侧 start -->
                    <div class="step f-fl">
                        <span>3</span>
                        <font>
                            生
                            <br />
                            产
                            <br />
                            信
                            <br />
                            息
                        </font>
                    </div>
                    <!-- 左侧 end -->
                    <!-- 右侧 start -->
                    <ul class="f-fl">
                        <li>
                            <span class="label">生产基地位置：</span>
                            <em>
                                <input style="width:174px;" type="text" name='product_place' />
                            </em>
                        </li>
                        <li>
                            <span class="label">土地肥力：</span>
                            <em>
                                <input style="width:174px;" type="text" name='manure'/>
                            </em>
                        </li>
                        <li>
                            <span class="label">土地污染：</span>
                            <em>
                                <input style="width:174px;" type="text" name='pollute'/>
                            </em>
                        </li>
                        <li>
                            <span class="label">品种名：</span>
                            <em>
                                <input style="width:174px;" type="text" name='breed'/>
                            </em>
                        </li>
                        <li>
                            <span class="label">种子质量指标：</span>
                            <em>
                                <input style="width:174px;" type="text" name='seed_quality'/>
                            </em>
                        </li>
                        <!-- <li>
                            <span class="label">肥料种类：</span>
                            <em>
                                <input style="width:174px;" type="text" name='manure_type'/>
                            </em>
                        </li>
                        <li>
                            <span class="label">肥料用量：</span>
                            <em>
                                <input style="width:174px;" type="text" name='manure_amount'/>
                            </em>
                        </li>
                        <li>
                            <span class="label">农药种类：</span>
                            <em>
                                <input style="width:174px;" type="text" name='pesticides_type'/>
                            </em>
                        </li>
                        <li>
                            <span class="label">农药用量：</span>
                            <em>
                                <input style="width:174px;" type="text" name='pesticides_amount'/>
                            </em>
                        </li> -->
                        <li>
                            <span class="label">加工方式：</span>
                            <em>
                                <input style="width:174px;" type="text" name='process_type'/>
                            </em>
                        </li>
                        <li>
                            <span class="label">产地照片：</span>
                            <div class="load_img f-fl" style="margin-top:0;">
                                <div class="load_btn">
                                    
                                    <input type="file" id='product' style="left:0;" value="浏览" />
                                </div>
                                <p>图片大小不超过2M，支持jpg、png、gif格式（使用高质量图片，可提高成交的机会）</p>
                                <p id='product_show'>

                                </p>
                           
                        </div>
                    </li>
                </ul>
                <!-- 右侧 end --> </div>
            <!-- 块 end -->


            <!-- 块 start -->
            <div class="gy_step gy_step_input">
                <!-- 左侧 start -->
                <div class="step f-fl">
                    <span>4</span>
                    <font>
                        种
                        <br />
                        植
                        <br />
                        作
                        <br />
                        业
                    </font>
                </div>
                <!-- 左侧 end -->
                <!-- 右侧 start -->
                <ul class="f-fl">
                    <li>
                        <span class="label">作业类型：</span>
                        <em>
                            <select name='operate_type[0]'>
                                <option value=''>请选择</option>
                                {% for key,val in _operate_type %}
                                <option value='{{ key }}'>{{ val }}</option>
                                {% endfor %}
                            </select>
                        </em>
                    </li>
                    <li>
                        <span class="label">时间：</span>
                        <em>
                            <input readonly="readonly" type="text" class="Wdate" name="begin_date[0]" id="begin_date[0]" onfocus="WdatePicker({maxDate:'#F{$dp.$D(\'begin_date\[0\]\',{M:0,d:0})}'})" >

                            <font>—</font>
                            <input readonly="readonly" type="text" class="Wdate" name="end_date[0]" id="end_date[0]" onfocus="WdatePicker({maxDate:'#F{$dp.$D(\'end_date\[0\]\',{M:0,d:0})}'})" ></em>
                    </li>
                    <li>
                        <span class="label">天气状态：</span>
                        <em>
                            <input style="width:174px;" type="text" name='weather[0]' />
                        </em>
                    </li>
                    <li>
                        <span class="label">作业内容：</span>
                        <em>
                            <textarea name='comment[0]' onkeyup="checkLen(this)" ></textarea>
                        </em>
                    </li>
                    <li>
                        <span class="label">作业照片：</span>
                        <div class="load_img f-fl" style="margin-top:0;">
                            <div class="load_btn">
                                
                                <input type="file" id='plant_0' style="left:0;" value="浏览" />
                            </div>
                            <p>图片大小不超过2M，支持jpg、png、gif格式（使用高质量图片，可提高成交的机会）</p>
                             <p id='plant_show_0'>
                             </p>
                            
                    </div>
                </li>
            </ul>
            <!-- 右侧 end --> </div>


        <!-- 块 end --> </div>

    <div class="unit_add f-oh" style="padding:0 38px;">
        <input class="fabu_btn f-fr" style="margin:0; margin:10px 0; width:100px;" onclick='addpluat()' type="button" value="继续添加" />
    </div>
    <input type="hidden" name='sellid' value='{{data.id}}'>
    <input class="fabu_btn" type="submit" value="确定提交" />
</div>
</form>

</div>
<!-- 主体内容结束 -->
<script type="text/javascript" src="/uploadify/jquery.uploadify.min.js" ></script>
<link rel="stylesheet" type="text/css" href="/uploadify/uploadify.css">
<script type="text/javascript" src="{{ constant('JS_URL') }}lhgdialog/lhgdialog.min.js?selfskin=igreen"></script>
<script>
var tagservice = null;
function newtag(id,title,url){
   
    tagservice = $.dialog({
        id    : id,
        title : title,
        min   : false,
        max   : false,
        fixed : true,
        left  : 706, 
        top   : 246,
        lock  : true,
        content: 'url:'+url
    });
}
function closeDialog(){
    tagservice.close();
    window.location.reload();
}
function fun(str){
    var beginstr ="begin_date["+str+"]";
 WdatePicker({maxDate:'#F{$dp.$D(\''+beginstr+'\')||\'2020-10-01\'}'})
}
function fun1(str){
    var endstr ="end_date["+str+"]";
WdatePicker({minDate:'#F{$dp.$D(\''+endstr+'\')}',maxDate:'2020-10-01'})
}

</script>

<style>
.upload_btn {width: 121px;
  height: 31px;
  text-align: center;
  line-height: 31px;
  color: #808080;
  font-family: '微软雅黑';
  font-size: 14px;
  background: url({{ constant('STATIC_URL') }}/mdg/images/yz_btn.png) no-repeat;
  background-position: 0 0;
  cursor: pointer;
  margin: 10px auto 0;
  position: relative;}
.edui-default .edui-editor{ margin:10px auto;}
</style>

</body>
</html>