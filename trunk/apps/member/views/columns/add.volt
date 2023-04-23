{{ partial('layouts/shop_header') }}
<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/css/pingdao.css" />
<script type="text/javascript" charset="utf-8" src="/ueditor1/ueditor.config.sample.js"></script>
<script type="text/javascript" charset="utf-8" src="/ueditor1/ueditor.all.js"></script>
<script type="text/javascript" charset="utf-8" src="/ueditor1/lang/zh-cn/zh-cn.js"></script>
<!-- 主体内容开始 -->
<div class="ur_here w960">
    <span>{{ partial('layouts/ur_here') }}编辑内容</span>
</div>

<div class="shop_decora w960 clearfix">
    {{ partial('layouts/shop_left') }}

    {{ form("columns/savecolcomment", "method":"post","id":"newshop") }}
    <div class="decora_right f-fr">
        <div class="edit_content">
            <h6 class="clearfix">
                <span class="f-fs14">
                    编辑内容&nbsp;|&nbsp; <em>{{data.col_name}}</em>
                </span>
                <a class="f-fr" href="/member/columns/index">返回栏目列表</a>
            </h6>
            <div class="m_editer">
                <script id="editor" type="text/plain"  style="width:1200px;height:500px;" name="col_comment" >
                                <?php if(isset($data->col_comment)){?>
                                {{ data.col_comment}}
                                <?php } ?></script>
            </div>

            <input type="hidden" name='cid' value='{{data.id}}'>{{ submit_button("发布","class":"btn ") }}{{content()}}</div>
    </form>

</div>
</div>
{{ partial('layouts/footer') }}
<script type="text/javascript" src="/uploadify/jquery.uploadify.min.js?var=<?= rand(1, 9999999) ?>" ></script>
<link rel="stylesheet" type="text/css" href="/uploadify/uploadify.css">
<script>
    var ue = UE.getEditor('editor');

//验证
$('#newshop').validator({
        
        fields:  {
            columns_name:"栏目名称:required;remote[/member/columns/checkcolumnsname]",
            

         
        },
});

</script>