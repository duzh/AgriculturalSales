<link rel="stylesheet" type="text/css" href="http://yncstatic.b0.upaiyun.com/mdg/css/base.css" />
<link rel="stylesheet" type="text/css" href="/mdg/css/base_index.css" />

<link rel="stylesheet" type="text/css" href="{{ constant("STATIC_URL")}}/mdg/css/pingdao.css" />
<link rel="stylesheet" type="text/css" href="{{ constant("STATIC_URL")}}/mdg/version2.3/css/mix-bq-gy.css" />


<!--时间插件-->
<script type="text/javascript">

 var plantkey = {{plantCount}};
 /* 农药 */
 var Pesticide = {{ pesticideCount }} ;
 /* 肥料*/
 var Manure = {{manureCount}} ;

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
        var str = '<div class="gy_step gy_step_input f-pr"><a class="step_closeBnt pa" href="javascript:;" onclick="$(this).parent().remove();">关闭</a><div class="step f-fl" style="height:176px;"></div><ul class="f-fl"><li><span class="label">使用时期：</span><em><input style="width:174px;" type="text" name="manure_use_period['+Manure+']"/></em></li><li><span class="label">名称：</span><em><input style="width:174px;" type="text" name="manure_name['+Manure+']" /></em></li><li><span class="label">种类：</span><em><select name="manure_type['+Manure+']"><option value="">请选择</option> <option value="1">有机肥</option> <option value="2">化肥</option></select></em></li><li><span class="label">用量(千克/亩)：</span><em><input style="width:174px;" type="text" name="manure_amount['+Manure+']" /></em></li><li><span class="label">品牌：</span><em><input style="width:174px;" type="text"  name="manure_brand['+Manure+']"/></em></li><li><span class="label">供应商：</span><em><input style="width:174px;" type="text" name="manure_suppliers['+Manure+']"/></em></li></ul></div>';
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
/**
 * 添加种植信息
 * @return {[type]} [description]
 */
function addpluat() {
        plantkey = $('#plantCount').val();
      
        var str = '<div class="gy_step gy_step_input f-pr"><a class="step_closeBnt pa" href="javascript:;" onclick="javascript:deletePlant(this);">关闭</a><div class="step f-fl" style="height:176px;"></div><ul class="f-fl"><li><span class="label">作业类型：</span><em><select name="operate_type['+plantkey+']"><option value="">请选择</option>{% for key,val in _operate_type %}<option value="{{key}}">{{val}}</option>{% endfor %}</select></em></li><li><span class="label">时间：</span><em><input readonly="readonly" type="text" class="Wdate" name="begin_date['+plantkey+']" id="begin_date['+plantkey+']" onfocus="fun('+plantkey+')"/><font>—</font><input readonly="readonly" type="text" class="Wdate" name="end_date['+plantkey+']" id="end_date['+plantkey+']"  onfocus="fun1('+plantkey+')" /></em></li><li><span class="label">天气状态：</span><em><input style="width:174px;" type="text" name="weather['+plantkey+']" /></em></li><li><span class="label">作业内容：</span><em><textarea name="comment['+plantkey+']" onkeyup="checkLen(this)"></textarea></em></li><li><span class="label">作业照片：</span><div class="load_img f-fl" style="margin-top:0;"><div class="load_btn"> <input type="file" id="plant_'+plantkey+'" style="left:0;" value="浏览" /></div><p>图片大小不超过2M，支持jpg、png、gif格式（使用高质量图片，可提高成交的机会）</p><p id="plant_show_'+plantkey+'"></p></div></li></ul></div>';
        $(str).appendTo($('.bq_sq_box'));
        bankImg($("#plant_"+plantkey), '26', $("#plant_show_"+plantkey), $("#plant_show_"+plantkey),'append', plantkey  );
        
        $('#plantCount').val(plantkey++);

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

  
    for (var i = 0; i <{{plantCount}}; i++) {
        setTimeout(bankImg($('#plant_'+i),26,$('#plant_show_'+i),$('#plant_show_'+1),'append', i ) ,100);    
    };
    //权威机构安全鉴
    var certifying_file = setTimeout(bankImg($('#certifying_file'),24,$('#certifying_fileshow'),$('#certifying_fileshow'),'html', '') ,100);
    var product = setTimeout(bankImg($('#product'),25,$('#product_show'),$('#product_show'),'append', '') ,100);
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

            whd: [/^[\u4E00-\u9FA5]|\w+$/, '只能输入中文或字母'],
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
            'village' : 'checked;'
        }
    });

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
    <div class="fabu_gy  mt20 mb20 f-oh">
        <form action="/manage/tag/save" method="post" id='createTag'>

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
                            </span> <em><i>{{ data.goods_name }}</i></em> 
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
                                <input style="width:314px;" type="text" name='address'  value='{{data.address}}' placeholder='街道地址/门牌号'/>(已选择的请勿重复填写)
                            </em>
                        </li>

                        <li>
                            <span class="label">
                                <font>*</font>
                                生产商：
                            </span>
                            <em>
                                <input style="width:314px;" type="text" value='{{data.productor}}' name='productor' />
                            </em>
                        </li>

                        <li>
                            <span class="label">
                                <font>*</font>
                                生产日期：
                            </span>
                            <em>
                                <input readonly="readonly" type="text" class="Wdate" name="product_date" id="product_date" onfocus="WdatePicker({maxDate:'#F{$dp.$D(\'product_date\',{M:0,d:0})}'})" value='{{data.product_date}}' ></em>
                        </li>
                        <li>
                            <span class="label">
                                <font>*</font>
                                保质期：
                            </span>
                            <em>
                                <input style="width:174px; margin-right:6px;" data-target="#bzq_tip"  value='{{data.expiration_date}}'  type="text" name='expiration_date' />
                                天
                                <span id="bzq_tip"></span>
                            </em>
                        </li>

                        <li>
                            <span class="label">加工地：</span>
                            <em>
                                <input style="width:174px;" type="text"  value='{{data.process_place}}'  name='process_place'  value=''/>
                            </em>
                        </li>

                        <li>
                            <span class="label">加工商：</span>
                            <em>
                                <input style="width:174px;" type="text" name='process_merchant' value='{{data.process_merchant}}' />
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
                                <input style="width:314px;" type="text"  value='{{TagQuality.quality_level}}' name='quality_level' />
                                <b style="display:block; color:#000; margin:0; margin-top:10px; background:#fff; ">一级、二级、优、良</b>

                            </em>
                        </li>
                        <li>
                            <span class="label">农残检测：</span>
                            <em>
                                <input style="width:314px;" type="text" value='{{TagQuality.pesticide_residue}}' name='pesticide_residue' />
                                <b style="display:block; color:#000; margin:0; margin-top:10px; background:#fff; ">未检测、有机磷：无、氯氰菊酯：0.2mg/kg</b>
                            </em>
                        </li>
                        <li>
                            <span class="label">重金属含量：</span>
                            <em>
                                <input style="width:314px;" type="text" value='{{TagQuality.heavy_metal}}' name='heavy_metal' />
                                <b style="display:block; color:#000; margin:0; margin-top:10px; background:#fff; ">未检测、铅：0.3mg/kg、镉：0.01mg/kg</b>
                            </em>
                        </li>
                        <li>
                            <span class="label">
                                <!-- <font>*</font> -->
                                是否转基因：
                            </span>
                            <em style="f-oh">
                                <label class="radioBtn f-db f-fl">
                                    <input class="f-fl" type="radio" value='1' name='is_gene' <?php if($TagQuality->
                                    is_gene == 1){ echo 'checked';}?>/>
                                    <font class="f-fl">是</font>
                                </label>
                                <label class="radioBtn f-db f-fl">
                                    <input class="f-fl" type="radio" value='0' name='is_gene' <?php if($TagQuality->
                                    is_gene == 0){ echo 'checked';}?> />
                                    <font class="f-fl">否</font>
                                </label>
                            </em>
                        </li>
                        <!-- <li>
                            <span class="label">检验员：</span>
                            <em>
                                <input style="width:314px;" type="text" value='{{TagQuality.inspector}}' name='inspector'/>
                            </em>
                        </li>
                        <li>
                            <span class="label">
                                 <font>*</font>
                                检验时间：
                            </span>
                            <em>
                                <input readonly="readonly" type="text" class="Wdate" name="inspect_time" id="inspect_time" onfocus="WdatePicker({maxDate:'#F{$dp.$D(\'inspect_time\',{M:0,d:0})}'})"   value='{{ TagQuality.inspect_time ? date('Y-m-d ' , TagQuality.inspect_time) : '' }}' ></em>
                        </li>
                        <li>
                            <span class="label">安全检机构名称：</span>
                            <em>
                                <input style="width:174px;" type="text"   value='{{TagQuality.certifying_agency}}' name='certifying_agency' />
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
                                <input type="hidden" name='certifying_filei' id='certifying_filei' vlaue=''> <i></i>
                                <p id='certifying_fileshow'>
                                    {% if TagCertifying %}

                                    {% for key, item in TagCertifying %}
                                    <dl id='certifying_1_{{item['id']}}'>
                                        <dt>
                                            <img src="{{item['path']}}" width='201px' height='201px'/>
                                            <a href="javascript:removerealImg('{{item['id']}}', '1', 'certifying', this);">关闭</a>
                                        </dt>
                                    </dl>
                                    {% endfor %}

                                    {% endif %}
                                </p>

                            </div>
                        </li>
                    </ul>
                    <!-- 右侧 end --> </div>
                <!-- 块 end -->

            <!--
        **  我是分割线 以下代码拿走
        -->
        {% if manureCount > 0 %}
        {% for key , item in TagManureList %}
        <!-- 块 start -->
        <div class="gy_step gy_step_input{% if key > 0 %} f-pr{% endif %}">
            <!-- 左侧 start -->
            
            <div class="step f-fl">
                {% if key == 0 %}
                <font>肥<br />料<br />使<br />用<br />信<br />息</font>
                {% else %}
                <font></font>
                {% endif %}

            </div>
            <a class="step_closeBnt pa" href="javascript:;" onclick="$(this).parent().remove();">关闭</a>
            <!-- 左侧 end -->
            <!-- 右侧 start -->
            <ul class="f-fl">
                <li>
                    <span class="label">使用时期：</span>
                    <em>
                        <input style="width:174px;" type="text" name='manure_use_period[{{ key }}]' value="{{ item.use_period }}" />
                    </em>
                </li>
                <li>
                    <span class="label">名称：</span>
                    <em>
                        <input style="width:174px;" type="text" name='manure_name[{{ key }}]' value="{{ item.manure_name }}" />
                    </em>
                </li>
                <li>
                    <span class="label">种类：</span>
                    <em>
                        <select name='manure_type[{{ key }}]'>
                            <option value=''>请选择</option>
                            <option value='1' {% if item.manure_type == 1 %}selected {% endif %}>有机肥</option>
                            <option value='2' {% if item.manure_type == 2 %}selected {% endif %} >化肥</option>
                        </select>
                    </em>
                </li>
                <li>
                    <span class="label">用量(千克/亩)：</span>
                    <em>
                        <input style="width:174px;" type="text" name='manure_amount[{{ key }}]' value="{{ item.manure_amount }}"/>
                    </em>
                </li>
                <li>
                    <span class="label">品牌：</span>
                    <em>
                        <input style="width:174px;" type="text" name='manure_brand[{{ key }}]' value="{{ item.manure_brand }}"/>
                    </em>
                </li>
                <li>
                    <span class="label">供应商：</span>
                    <em>
                        <input style="width:174px;" type="text" name='manure_suppliers[{{ key }}]' value="{{ item.manure_suppliers }}"/>
                    </em>
                </li>
            </ul>
            <!-- 右侧 end -->
        </div>
        {% endfor %}
        {% else %}

        <!-- 块 start -->
        <div class="gy_step gy_step_input">
            <!-- 左侧 start -->
            
            <div class="step f-fl">
                <font>肥<br />料<br />使<br />用<br />信<br />息</font>
            </div>
            <a class="step_closeBnt pa" href="javascript:;" onclick="$(this).parent().remove();">关闭</a>
            <!-- 左侧 end -->
            <!-- 右侧 start -->
            <ul class="f-fl">
                <li>
                    <span class="label">使用时期：</span>
                    <em>
                        <input style="width:174px;" type="text" name='manure_use_period[0]'  />
                    </em>
                </li>
                <li>
                    <span class="label">名称：</span>
                    <em>
                        <input style="width:174px;" type="text" name='manure_name[0]'  />
                    </em>
                </li>
                <li>
                    <span class="label">种类：</span>
                    <em>
                        <select name='manure_type[0]'>
                            <option value=''>请选择</option>
                            <option value='1' >有机肥</option>
                            <option value='2'  >化肥</option>
                        </select>
                    </em>
                </li>
                <li>
                    <span class="label">用量(千克/亩)：</span>
                    <em>
                        <input style="width:174px;" type="text" name='manure_amount[0]' />
                    </em>
                </li>
                <li>
                    <span class="label">品牌：</span>
                    <em>
                        <input style="width:174px;" type="text" name='manure_brand[0]' />
                    </em>
                </li>
                <li>
                    <span class="label">供应商：</span>
                    <em>
                        <input style="width:174px;" type="text" name='manure_suppliers[0]' />
                    </em>
                </li>
            </ul>
            <!-- 右侧 end -->
        </div>

        {% endif %}
        <!-- 块 end -->
        <div class="unit_add f-oh" style="padding:0 38px;" id="unit_add2">
            <input class="fabu_btn f-fr" style="margin:0; margin-bottom:30px; width:100px;" type="button" value="继续添加"  onclick="addManure()" />
        </div>

        {% if pesticideCount >  0 %}
            {% for key , item in TagPesticide %}

            <!-- 块 start -->
            <!-- 块 start -->
            <div class="gy_step gy_step_input{% if key > 0 %} f-pr{% endif %}">
                <!-- 左侧 start -->
                
                <div class="step f-fl">
                    {% if key == 0 %}
                    <font>农<br />药<br />使<br />用<br />信<br />息</font>
                    {% else %}
                    <font></font>
                    {% endif %}

                </div>
                <a class="step_closeBnt pa" href="javascript:;" onclick="$(this).parent().remove();">关闭</a>
                <!-- 左侧 end -->
                <!-- 右侧 start -->
                <ul class="f-fl">
                    <li>
                        <span class="label">使用时期：</span>
                        <em>
                            <input style="width:174px;" type="text" name='pesticide_use_period[{{ key }}]' value="{{ item.use_period }}"/>
                        </em>
                    </li>
                    <li>
                        <span class="label">名称：</span>
                        <em>
                            <input style="width:174px;" type="text" name='pesticide_name[{{ key }}]' value="{{ item.pesticide_name }}" />
                        </em>
                    </li>
                    <li>
                        <span class="label">用量(千克/亩)：</span>
                        <em>
                            <input style="width:174px;" type="text" name='pesticide_amount[{{ key }}]' value="{{ item.pesticide_amount }}" />
                        </em>
                    </li>
                    <li>
                        <span class="label">品牌：</span>
                        <em>
                            <input style="width:174px;" type="text" name='pesticide_brand[{{ key }}]' value="{{ item.pesticide_brand }}" />
                        </em>
                    </li>
                    <li>
                        <span class="label">供应商：</span>
                        <em>
                            <input style="width:174px;" type="text" name='pesticide_suppliers[{{ key }}]' value="{{ item.pesticide_suppliers }}" />
                        </em>
                    </li>
                </ul>
                <!-- 右侧 end -->
            </div>
            {% endfor %}

            {% else %}
                <!-- 块 start -->
            <!-- 块 start -->
            <div class="gy_step gy_step_input">
                <!-- 左侧 start -->
                
                <div class="step f-fl">
                   <font>农<br />药<br />使<br />用<br />信<br />息</font>
                </div>
                <a class="step_closeBnt pa" href="javascript:;" onclick="$(this).parent().remove();">关闭</a>
                <!-- 左侧 end -->
                <!-- 右侧 start -->
                <ul class="f-fl">
                    <li>
                        <span class="label">使用时期：</span>
                        <em>
                            <input style="width:174px;" type="text" name='pesticide_use_period[0]' />
                        </em>
                    </li>
                    <li>
                        <span class="label">名称：</span>
                        <em>
                            <input style="width:174px;" type="text" name='pesticide_name[0]'  />
                        </em>
                    </li>
                    <li>
                        <span class="label">用量(千克/亩)：</span>
                        <em>
                            <input style="width:174px;" type="text" name='pesticide_amount[0]'  />
                        </em>
                    </li>
                    <li>
                        <span class="label">品牌：</span>
                        <em>
                            <input style="width:174px;" type="text" name='pesticide_brand[0]'  />
                        </em>
                    </li>
                    <li>
                        <span class="label">供应商：</span>
                        <em>
                            <input style="width:174px;" type="text" name='pesticide_suppliers[0]'   />
                        </em>
                    </li>
                </ul>
                <!-- 右侧 end -->
            </div>

            {% endif %}
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
                                <input style="width:174px;" type="text" value='{{TagProduct.product_place}}' name='product_place' />

                               
                            </em>
                        </li>
                        <li>
                            <span class="label">土地肥力：</span>
                            <em>
                                <input style="width:174px;" type="text" value='{{TagProduct.manure}}' name='manure'/>
                                 <b style="display:block; color:#000; margin:0; margin-top:10px; background:#fff; ">优、良</b>
                            </em>
                        </li>
                        <li>
                            <span class="label">土地污染：</span>
                            <em>
                                <input style="width:174px;" type="text" value='{{TagProduct.pollute}}' name='pollute'/>
                                <b style="display:block; color:#000; margin:0; margin-top:10px; background:#fff; ">无</b>
                            </em>
                        </li>
                        <li>
                            <span class="label">品种名：</span>
                            <em>
                                <input style="width:174px;" type="text" value='{{TagProduct.breed}}' name='breed'/>
                            </em>
                        </li>
                        <li>
                            <span class="label">种子质量指标：</span>
                            <em>
                                <input style="width:174px;" type="text" value='{{TagProduct.seed_quality}}' name='seed_quality'/>
                                <b style="display:block; color:#000; margin:0; margin-top:10px; background:#fff; ">净度99.0%；发芽率：98%；纯度：99.0%；水分：11.0%</b>
                            </em>
                        </li>
                        <!-- <li>
                            <span class="label">肥料种类：</span>
                            <em>
                                <input style="width:174px;" type="text" value='{{TagProduct.manure_type}}' name='manure_type'/>
                            </em>
                        </li>
                        <li>
                            <span class="label">肥料用量：</span>
                            <em>
                                <input style="width:174px;" type="text" value='{{TagProduct.manure_amount}}' name='manure_amount'/>
                            </em>
                        </li>
                        <li>
                            <span class="label">农药种类：</span>
                            <em>
                                <input style="width:174px;" type="text" value='{{TagProduct.pesticides_type}}' name='pesticides_type'/>
                            </em>
                        </li>
                        <li>
                            <span class="label">农药用量：</span>
                            <em>
                                <input style="width:174px;" type="text" value='{{TagProduct.pesticides_amount}}' name='pesticides_amount'/>
                            </em>
                        </li> -->
                        <li>
                            <span class="label">加工方式：</span>
                            <em>
                                <input style="width:174px;" type="text" value='{{TagProduct.process_type}}' name='process_type'/>
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
                                    {% for key , val in TagProductImgList %}
                                    <dl id='product_2_{{val['id']}}'>
                                        <dt>
                                            <img src="{{val['path']}}" width='201px' height='201px'/>
                                            <a href="javascript:removerealImg('{{val['id']}}', '2', 'product', this);">关闭</a>
                                        </dt>
                                    </dl>
                                    {% endfor %}
                                </p>

                            </div>
                        </li>
                    </ul>
                    <!-- 右侧 end --> </div>
                <!-- 块 end -->
                {% for tagkey , row in tagplant %}
                <!-- 块 start -->
                <div class="gy_step gy_step_input {% if tagkey >0 %}f-pr{% endif %}">
                    {% if tagkey >0 %}
                    <a class="step_closeBnt pa" href="javascript:;" onclick="deletePlant(this,'{{row['id']}}')">关闭</a>
                    {% endif %}

                    <!-- 左侧 start -->
                    <div class="step f-fl">
                        {% if tagkey == 0 %}
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
                        {% else %}
                        <font></font>
                        {% endif %}
                    </div>
                    <!-- 左侧 end -->
                    <!-- 右侧 start -->
                    <ul class="f-fl">
                        <li>
                            <span class="label">作业类型：</span>
                            <em>
                                <select name='operate_type[{{tagkey}}]'>
                                    <option value=''>请选择</option>
                                    {% for key,val in _operate_type %}
                                    <option value='{{ key }}' <?php if(isset($row['operate_type']) && $row['operate_type'] == "$key" ){ echo 'selected';}?>>{{ val }}</option>
                                    {% endfor %}
                                </select>
                            </em>
                        </li>
                        <li>
                            <span class="label">时间：</span>
                            <em>
                                <input readonly="readonly" type="text" class="Wdate" name="begin_date[{{tagkey}}]" id="begin_date[{{tagkey}}]" onfocus="WdatePicker({maxDate:'#F{$dp.$D(\'begin_date\[{{tagkey}}\]\',{M:0,d:0})}'})" value='{{ row['begin_date']}}' >

                                <font>—</font>
                                <input readonly="readonly" type="text" class="Wdate" name="end_date[{{tagkey}}]" id="end_date[{{tagkey}}]" onfocus="WdatePicker({maxDate:'#F{$dp.$D(\'end_date\[{{tagkey}}\]\',{M:0,d:0})}'})"  value='{{ row['end_date']}}' ></em>
                        </li>
                        <li>
                            <span class="label">天气状态：</span>
                            <em>
                                <input style="width:174px;" type="text" name='weather[{{tagkey}}]' value='{{ row['weather']}}' />
                            </em>
                        </li>
                        <li>
                            <span class="label">作业内容：</span>
                            <em>
                                <textarea name='comment[{{tagkey}}]' onkeyup="checkLen(this)">{{ row['comment']}}</textarea>
                            </em>
                        </li>
                        <li>
                            <span class="label">作业照片：</span>
                            <div class="load_img f-fl" style="margin-top:0;">
                                <div class="load_btn">
                                    <input type="hidden" name='plantid[{{tagkey}}]' value='{{row['id']}}'>
                                    <input type="file" id='plant_{{ tagkey }}' style="left:0;" value="浏览" />
                                </div>
                                <p>图片大小不超过2M，支持jpg、png、gif格式（使用高质量图片，可提高成交的机会）</p>
                                <p id='plant_show_{{ tagkey }}'>
                                    {% for img in row['imgList'] %}
                                    <dl id='picture_3_{{img['id']}}' >
                                        <dt>
                                            <img src="{{img['path']}}" width='201px' height='201px'/>
                                            <a href="javascript:removerealImg('{{img['id']}}', '3', 'picture', this);">关闭</a>
                                        </dt>
                                    </dl>
                                    {% endfor %}
                                </p>

                            </div>
                        </li>
                    </ul>
                    <!-- 右侧 end --> </div>
                {% endfor %}
                <!-- 块 end --> </div>

            <div class="unit_add f-oh" style="padding:0 38px;">
                <input class="fabu_btn f-fr" style="margin:0; margin:10px 0; width:100px;" onclick='addpluat()' type="button" value="继续添加" />
            </div>
            <input type="hidden" name='plantcount' id='plantCount' value='{{plantCount}}'>    
            <input type="hidden" name='sellid' value='{{data.tag_id}}'>
            <input class="fabu_btn" type="submit" value="确定提交" />
        </div>
    </form>

</div>
<!-- 主体内容结束 -->


<script>
/**
 * 删除照片
 * @param  {[type]} id   id
 * @param  {[type]} type 类型
 * @param  {[type]} tab  表
 * @return {[type]}      [description]
 */
function removerealImg(id, type, tab,o) {
    $.getJSON('/manage/tag/remove', {id: id, type:type,tab:tab }, function(json, textStatus) {
            alert(json.msg);
            if(json.status) {
                $('#'+tab+"_"+type+'_'+id).slideUp();
            }
    });
}
function deletePlant(o,id){
    
    $('#plantCount').val(plantkey--);
   
    if(id) {
        $.getJSON('/manage/tag/removeplant', {id: id}, function(json, textStatus) {
            alert(json.msg);
            if(json.status) {
                $(o).parent().remove();
            }
        });
        return false;
    }
    $(o).parent().remove();
    

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
  background: url(http://yncstatic.b0.upaiyun.com/mdg/images/yz_btn.png) no-repeat;
  background-position: 0 0;
  cursor: pointer;
  margin: 10px auto 0;
  position: relative;}
.edui-default .edui-editor{ margin:10px auto;}
</style>

</body>
</html>