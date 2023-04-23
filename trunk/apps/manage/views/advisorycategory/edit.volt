<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/manage/css/style.css" />
{{ content() }}
{{ form("advisorycategory/save", "method":"post") }}

<div class="main">
  <div class="main_right">
    <div class="bt2">修改分类</div>
    <div class="cx">
         <table width="100%" border="0" cellspacing="0" cellpadding="0" style=" border:none;">
                <tr>
                    <td class="cx_title">分类名称：</td>
                    <td class="cx_content">
                       {{ text_field("catname") }}
                     </td>
                </tr>
                <tr>
                    <td class="cx_title">上级分类：</td>
                    <td class="cx_content">

                        {{ hidden_field('pid', 'value' : category.pid) }}
                        <div class="zTreeDemoBackground left">
                            <ul id="treeDemo" class="ztree"></ul>
                        </div>
                      
                     </td>
                </tr>
                <tr>
                    <td class="cx_title">排序：</td>
                    <td class="cx_content">
                        {{ text_field("sortrank") }}
                    </td>
                </tr>
                <tr>
                     <td class="cx_title">是否显示：</td>
                    <td class="cx_content">
                        {% for index, state in is_show %}
                        {{ radio_field('is_show','value' : index) }} {{ state }}
                        {% endfor %}
                    </td>
                </tr>
                <tr>
                    <td class="cx_title"  valign="top">关键字：</td>
                    <td class="cx_content">
                        {{ text_field("keywords") }}
                    </td>
                </tr>
                <tr>
                    <td class="cx_title">分类描述：</td>
                    <td class="cx_content">
                  <div class="cx_content1"> 
                        {{ text_area("description", 'rows' : 5, 'cols' : 50) }}
                  </div>
                    </td>
                </tr>
                
         </table>
         
    </div> 
    <div align="center" style="margin-top:20px;">
        {{ hidden_field("id") }}
         <input type="submit" value="确认修改" class="sub"/>
         </div>
  </div>
  <!-- main_right 结束  --> 
 
</form> 
</div>
<div class="footer"> Copyright © 2013-2014 ync365.com All rights reserved. </div>
</body>
</html>
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
        $('#pid').val(treeNode.id);
    };

    function zTreeOnClick(event, treeId, treeNode) {
        treeObj.checkNode(treeNode, true, true);
        $('#pid').val(treeNode.id);
    };

    function setCheck() {
        $.getJSON('/manage/advisorycategory/ajax', {pid : {{ category.pid }}}, function(data) {
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
