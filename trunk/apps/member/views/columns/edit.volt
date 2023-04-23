{{ partial('layouts/shop_header') }}
<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/css/pingdao.css" />
<script type="text/javascript" charset="utf-8" src="/ueditor1/ueditor.config.sample.js"></script>
<script type="text/javascript" charset="utf-8" src="/ueditor1/ueditor.all.js"></script>
<script type="text/javascript" charset="utf-8" src="/ueditor1/lang/zh-cn/zh-cn.js"></script> 
<!-- 主体内容开始 -->
<div class="ur_here w960">
    <span>{{ partial('layouts/ur_here') }}编辑栏目</span>
</div>


<div class="shop_decora w960 clearfix" >
    {{ partial('layouts/shop_left') }}

    <div class="decora_right f-fr">

        {{ form("columns/save", "method":"post","id":"newshop") }}
        
        <div class="add_section">
            <h6 class="clearfix">
                <span class="f-fs14">编辑栏目</span>
                <a class="f-fr" href="/member/columns/index">返回栏目列表</a>
            </h6>
            <div class="sectionForm">

                <div class="sectionBox clearfix"> <font>栏目名称：</font>
                    <div class="inputTxt">
                        <input type="text" name="columns_name" value='{{ data.col_name }}' />
                    </div>
                </div>
            
                <div class="sectionBox clearfix"> <font>上级栏目：</font>
                    <div class="selectBox">
                        <select  name="columns_pid"  id='columns_pid' >
                                    {% if total < 5 %}
                                    <option value='0'>请选择上级栏目</option>
                                    {% endif %}

                                    {% for key,val in shopcolumns %}
                                    <option value="{{val.id}}" {% if val.id == data.col_pid %} selected{% endif %}>{{val.col_name}}</option>
                                    {% endfor %}
                        </select>
                    </div>
                    <span id='columns_piderror' role='alert'></span>

                </div>
                <i id="msg_holder"></i>
                <input type="hidden" name='cid' value='{{ data.id }}'>
                <input type="hidden" name='pid' value='{{ data.col_pid }}'>
                <input type="hidden" name='total' id='total' value="{{ total }}">
                {{ submit_button("提交","class":"btn ") }}{{content()}}

            </div>
        </div>
        </form>
    </div>
</div>

<!-- 主体内容结束 -->

{{ partial('layouts/footer') }}
<script type="text/javascript" src="/uploadify/jquery.uploadify.min.js?var=<?= rand(1, 9999999) ?>" ></script>
<link rel="stylesheet" type="text/css" href="/uploadify/uploadify.css">
<script>
// 子级  则不需要验证 父级 则验证数量

$('#newshop').validator({
        stopOnError:true,
         groups: [{
            fields: 'columns_pid',
            callback: function(o){
                var columns_pid = $('#columns_pid').val();
                var total = $('#total').val();
                if(columns_pid <=0 ) {
                    //验证父类数量
                    if(total>=5) {

                            $('#newshop').validator({
                            theme: "columns_piderror",
                            showOk: ""
                        });
                    } 
                }
            },
        }],

        fields:  {
            columns_name:"栏目名称:required;remote[/member/columns/checkcolumnsname?type=edit&pid={{data.col_pid}} , cid, columns_pid]",
            
        },
});


</script>


