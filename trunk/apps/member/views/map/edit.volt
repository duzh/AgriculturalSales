{{ partial('layouts/shop_header') }}
<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/css/pingdao.css" />
<script type="text/javascript" charset="utf-8" src="/ueditor1/ueditor.config.sample.js"></script>
<script type="text/javascript" charset="utf-8" src="/ueditor1/ueditor.all.js"></script>
<script type="text/javascript" charset="utf-8" src="/ueditor1/lang/zh-cn/zh-cn.js"></script> 
<!-- 主体内容开始 -->
<div class="ur_here w960">
    <span>{{ partial('layouts/ur_here') }}新增栏目</span>
</div>
<div class="personal_center w960 mb20">

    {{ partial('layouts/shop_left') }}

    <!-- 右侧 start -->
    <div class="center_right f-fr">
   
        <div class="fabu_gy w960 mt20 mb20">
  
        {{ form("columns/create", "method":"post","id":"newshop") }}
            <h6>编辑栏目</h6>
            <div class="gy_box">
                
                <!-- 块 start -->
                <div class="gy_step active">
                
                    <!-- 右侧 start -->
                    <ul class="f-fl">
                        <li>
                            <span class="label"><font>*</font>栏目名称：</span>
                            <em><input type="text" name="columns_name" value='{{ data.col_name }}' /></em>
                        </li>
                         <li>
                            <span class="label"><font>*</font>上级栏目</span>
                            <em>
                                <select class="selectCate" name="columns_pid" data-rule="select">
                                    <option  value='0'>请选择</option>
                                    {% for key,val in shopcolumns %}
                                    <option value="{{val.col_id}}" {% if val.col_id == data.col_id %} selected{% endif %}>{{val.col_name}}</option>
                                    {% endfor %}
                                </select>
                            </em>
                        </li>
                    </ul>
                    <!-- 右侧 end -->
                </div>
                {{ submit_button("确认开通","class":"fabu_btn ") }}{{content()}}
            </div>
        </form>
</div>
       
    </div>
    <!-- 右侧 end -->
</div>
<!-- 主体内容结束 -->

{{ partial('layouts/footer') }}
<script type="text/javascript" src="/uploadify/jquery.uploadify.min.js?var=<?= rand(1, 9999999) ?>" ></script>
<link rel="stylesheet" type="text/css" href="/uploadify/uploadify.css">
<script>
//验证
$('#newshop').validator({
        
        fields:  {
            columns_name:"栏目名称:required;remote[/member/columns/checkcolumnsname]",
            

         
        },
});

</script>


