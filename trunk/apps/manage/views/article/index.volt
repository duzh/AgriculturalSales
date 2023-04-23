{{ content() }}

<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/manage/css/style.css" />
<div class="main">
    <div class="main_right">
        <div class="bt2">文章列表</div>
        {{ form("/article/index", "method":"get", "autocomplete" : "off") }}
        <div class="chaxun">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td  height="35" align="right">文章分类：</td>
                    <td  height="35" align="left">
                       <select name="pid" id="cat_id" >
                            <option value="0">请选择分类</option>
                            <?php foreach ($cat_list as $cat) { ?>
                                <option value="<?php echo $cat['id']; ?>" {% if pid==cat['id'] %} selected {% endif %} >
                                <?php echo $cat['catname']; ?></option>
                                <?php if(!empty($cat['child'])) { ?>
                                    <?php foreach ($cat['child'] as $child) { ?>
                                        <option value="<?php echo $child['id']; ?>" {% if pid==child['id'] %} selected {% endif %}  >&nbsp&nbsp&nbsp
                                    <?php echo $child['catname']; ?></option>
                                <?php } ?>
                            <?php } ?>
                            <?php } ?>
                        </select>
                    </td>
                    <td  height="35" align="right">文章标题：</td>
                    <td height="35" align="left">
                        {{ text_field("title","value":title) }}
                    </td>
                </tr>
            </table>
            <div class="btn">{{ submit_button("确定","class":'sub') }}</div>
        </div>
    </form>
    <div class="neirong" id="tb">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr align="center">

                <td width='6%'  class="bj">编号</td>
                <td width='10%' class="bj">文章标题</td>
                <td width='20%' class="bj">文章分类</td>
                <td width='10%' class="bj">是否显示</td>
                <td width='14%' class="bj">添加时间</td>
                <td width='8%' class="bj">点击量</td>
              
                <td width='8%' class="bj">操作</td>
            </tr>
            <?php $i=($data->current-1)*10+1 ?>
            {% if data.items is defined %}
            {% for article in data.items %}
                <tr  align="center">
                    <td><?php echo $i++ ;?></td>
                    <td>{{ article.title }}</td>
                    <td><?php echo Mdg\Models\Article::returncategory($article->cid)?></td>
                    <td>{{ isshow[article.is_show] }}</td>
                    <td>{{ date('Y-m-d H:i:s', article.addtime) }}</td>
                    <td>{{ article.count }}</td>
                    <td><a href="/article/index?p={{article.id}}" target='_blank'>查看</a>
                        {{ link_to("article/edit/"~article.id, "修改") }}
                        <a href='javascript:remove_agreement({{ article.id }})' > 删除</a>
                </tr>
            {% endfor %}
            {% endif %}
    </table>
</div>
{{ form("article/index", "method":"get") }}
<div class="fenye">
    <div class="fenye1">
        <span>{{ pages }}</span>
        <em>跳转到第<input type="text" class='input' name='p' <?php if(isset($_GET['p'])&&$_GET['p']!=''){ echo "value='".$_GET['p']."'" ;}else{ echo "value='1'"; } ?> />页</em>
            <?php unset($_GET['p']);
              foreach ($_GET as $key => $val) {

            echo "<input type='hidden' name='{$key}' value='{$val}'>";
            }?>

         <input class="sure_btn"  type='submit' value='确定'>
    </div>
</div>
</form>
</div>
<!-- main_right 结束  -->

</div>
<div class="footer">Copyright © 2013-2014 ync365.com All rights reserved.</div>
</body>
</html>
<script type="text/javascript" src="{{ constant('JS_URL') }}jquery/ld-select.js"></script>
<script type="text/javascript" src="{{ constant('JS_URL') }}jquery/jquery-ui.min.js"></script>
<script type="text/javascript" src="{{ constant('JS_URL') }}jquery/timepicker/jquery-ui-timepicker-addon.min.js"></script>
<script type="text/javascript" src="{{ constant('JS_URL') }}jquery/timepicker/i18n/jquery-ui-timepicker-zh-CN.js"></script>
<link rel="stylesheet" type="text/css" href="{{ constant('JS_URL') }}jquery/jquery-ui.css" />
<link rel="stylesheet" type="text/css" href="{{ constant('JS_URL') }}jquery/timepicker/jquery-ui-timepicker-addon.min.css" />
<link rel="stylesheet" href="http://js.static.ync365.com/zTree/css/zTreeStyle/zTreeStyle.css" type="text/css">
<script type="text/javascript" src="http://js.static.ync365.com/zTree/js/jquery.ztree.core-3.5.min.js"></script>
<script type="text/javascript" src="http://js.static.ync365.com/zTree/js/jquery.ztree.excheck-3.5.min.js"></script>
<script>
$(function(){
   $("#stime").datetimepicker();
   $("#etime").datetimepicker();
   $(".selectCate").ld({ajaxOptions : {"url" : "/ajax/getcate"},
    defaultParentId : 0,
    style : {"width" : 140}
   });
});
 function remove_agreement(id) 
 {  

   if(confirm("您确定要删除吗?"))
   location.href='/manage/article/delete/'+id;

 }
$(document).ready(function () {        
    //按钮样式切换
    $(".neirong tr").mouseover(function(){    
    //如果鼠标移到class为stripe的表格的tr上时，执行函数    
    $(this).addClass("over");}).mouseout(function(){    
    //给这行添加class值为over，并且当鼠标一出该行时执行函数    
    $(this).removeClass("over");}) //移除该行的class    
    $(".neirong tr:even").addClass("alt");    
    //给class为stripe的表格的偶数行添加class值为alt 
});

$(document).ready(function () {        
    //按钮样式切换
    $(".btn>input").hover(
    function () {
    $(this).removeClass("sub").addClass("sub1");
    },
    function () {
    $(this).removeClass("sub1").addClass("sub"); 
    }
    );
});
</script>
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