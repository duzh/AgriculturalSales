{{ content() }}
<link rel="stylesheet" type="text/css" href="{{ constant('STATIC_URL') }}mdg/manage/css/style.css" />
<div class="main">
    <div class="main_right">
        <div class="bt2">资讯列表</div>
        {{ form("/advisory/index", "method":"get", "autocomplete" : "off") }}
        <div class="chaxun">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td  height="35" align="right">文章分类：</td>
                    <td  height="35" align="left">
                        <select name="pid" id="cat_id" >
                            <option value="0">请选择分类</option>
                            <?php foreach ($cat_list as $cat) { ?>
                            <option value="<?php echo $cat['id']; ?>
                                " {% if pid==cat['id'] %} selected {% endif %} >
                                <?php echo $cat['catname']; ?></option>
                            <?php if(!empty($cat['child'])) { ?>
                            <?php foreach ($cat['child'] as $child) { ?>
                            <option value="<?php echo $child['id']; ?>
                                " {% if pid==child['id'] %} selected {% endif %}  >&nbsp&nbsp&nbsp
                                <?php echo $child['catname']; ?></option>
                            <?php } ?>
                            <?php } ?>
                            <?php } ?></select>
                    </td>
                    <td  height="35" align="right">文章标题：</td>
                    <td height="35" align="left">{{ text_field("title","value":title) }}</td>
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
                <!--<td width='8%' class="bj">推荐位</td>-->

                <td width='8%' class="bj">操作</td>
            </tr>
            <?php $i=($data->
            current-1)*10+1 ?>
            {% if data.items is defined %}
            {% for article in data.items %}
            <tr  align="center">
                <td>
                    <?php echo $i++ ;?></td>
                <td>{{ article.title }}</td>
                <td>
                    <?php echo Mdg\Models\Advisory::returncategory($article->cid)?></td>
                <td>{{ isshow[article.is_show] }}</td>
                <td>{{ date('Y-m-d H:i:s', article.addtime) }}</td>
                <td>{{ article.count }}</td>
                 <!--<td>
				{% if article.is_hot == 1 %}
					相关推荐
				{% elseif article.is_hot == 2 %}
					副轮播
				{% elseif article.is_hot == 3 %}
					带图标题
				{% elseif article.is_hot == 4 %}
					公告版
				{% else %}
					主轮播
				{% endif %}
				</td>-->
                <td>
                    <a href="/advisory/adinfo?id={{ article.id}}" target="_blank" >查看</a>
                    <!-- {{ link_to("http://mdg.ync365.com/?p="~article.id, "查看",'' : '_blank') }} -->
                    {{ link_to("advisory/edit/"~article.id, "修改") }}
                    <a href='javascript:remove_agreement({{ article.id }})' >删除</a>
                </tr>
                {% endfor %}
            {% endif %}
            </table>
        </div>
        {{ form("advisory/index", "method":"get") }}
        <div class="fenye">
            <div class="fenye1">
                <span>{{ pages }}</span> <em>跳转到第
                    <input type="text" class='input' name='p' <?php if(isset($_GET['p'])&&$_GET['p']!=''){ echo "value='".$_GET['p']."'" ;}else{ echo "value='1'"; } ?>/>页</em>
                    <?php foreach ($_GET as $key => $val) {

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
<script type="text/javascript">
 function remove_agreement(id) 
 {  
    if(confirm("您确定要删除吗?"))
    location.href='/manage/advisory/delete/'+id;
 }
</script>
</html>