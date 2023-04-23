<script type="text/javascript" charset="utf-8" src="/ueditor/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="/ueditor/ueditor.all.min.js"> </script>
<script type="text/javascript" charset="utf-8" src="/ueditor/lang/zh-cn/zh-cn.js"></script>

{{ form("/article/create", "method":"post") }}

<table width="100%">
    <tr>
        <td align="left">{{ link_to("/article", "Go Back") }}</td>
        <td align="right">{{ submit_button("Save") }}</td>
    </tr>
</table>
   
{{ content() }}

<div align="center">
    <h1>Create article</h1>
</div>

<table>
   
    <tr>
        <td align="right">
            <label for="title">文章标题：</label>
        </td>
        <td align="left">
                {{ text_field("title") }}
        </td>
    </tr>
     <tr>
        <td align="right">
            <label for="cid">文章分类；</label>
        </td>
        <td align="left">
            {{ hidden_field('cid', 'value' : 0) }}
            <div class="zTreeDemoBackground left">
                <ul id="treeDemo" class="ztree"></ul>
            </div>
        </td>
    </tr>
     <tr>
        <td align="right">
            <label for="is_show">是否显示：</label>
        </td>
        <td align="left">
            {% for index, state in is_show %}
            {{ radio_field('is_show','value' : index) }} {{ state }}
            {% endfor %}
        </td>
    </tr>
    <tr>
        <td align="right">
            <label for="keywords">关键字</label>
        </td>
        <td align="left">
            {{ text_field("keywords", "size" : 30) }}
        </td>
    </tr>
    <tr>
        <td align="right">
            <label for="tags">标签:</label>
        </td>
        <td align="left">
            {{ text_field("tags", "size" : 30) }}多个标签请用“空格”分隔
        </td>
    </tr>
    <tr>
        <td align="right">
            <label for="description">网页描述:</label>
        </td>
        <td align="left">
            {{ text_area("description", 'rows' : 5, 'cols' : 50) }}
        </td>
    </tr>
     <tr>
        <td align="right">
            <label for="content">文章简介:</label>
        </td>
        <td align="left">
             <script id="editor" type="text/plain" style="width:1024px;height:500px;" name="content" ></script>
        </td>
    </tr>

    <tr>
        <td></td>
        <td>{{ submit_button("Save") }}</td>
    </tr>
</table>

</form>

<link rel="stylesheet" href="http://js.static.ync365.com/zTree/css/zTreeStyle/zTreeStyle.css" type="text/css">
<script type="text/javascript" src="http://js.static.ync365.com/zTree/js/jquery.ztree.core-3.5.min.js"></script>
<script type="text/javascript" src="http://js.static.ync365.com/zTree/js/jquery.ztree.excheck-3.5.min.js"></script>

<SCRIPT type="text/javascript">
<!--
    var treeObj;
    var setting = {
        check: {
            enable: true,
            chkStyle: "radio",
            radioType: "all"
        },
        data: {
            simpleData: {
                enable: true
            }
        },
        callback: {
                onCheck: zTreeOnCheck,
                onClick: zTreeOnClick
            }
    };
    function zTreeOnCheck(event, treeId, treeNode) {
        $('#cid').val(treeNode.id);
    };

    function zTreeOnClick(event, treeId, treeNode) {
        treeObj.checkNode(treeNode, true, true);
        $('#cid').val(treeNode.id);
    };

    function setCheck() {
        $.getJSON('/manage/articlecategory/ajax', function(data) {
            treeObj = $.fn.zTree.init($("#treeDemo"), setting, data);
            treeObj.expandAll(true);
            treeObj.checkNode()
        });

    }
    $(document).ready(function(){
        setCheck();
    });
//-->
</SCRIPT>
<script type="text/javascript">
    var ue = UE.getEditor('editor');
</script>
