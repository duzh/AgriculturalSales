
{{ content() }}

<table width="100%">
    <tr>
        <td align="left">
            {{ link_to("articlecategory/index", "返回") }}
        </td>
        <td align="right">
            {{ link_to("articlecategory/new", "新建 ") }}
        </td>
    </tr>
</table>

<table class="easyui-datagrid browse" align="center" width="100%" title="文章分类管理">
    <thead>
        <tr>
            <th data-options="field:'id',width:80">分类ID</th>
            <th data-options="field:'pid',width:80">所属分类</th>
            <th data-options="field:'catname',width:80">分类名称</th>
            <th data-options="field:'sortrank',width:80">排序</th>
            <th data-options="field:'is_show',width:80">是否显示</th>
            <th data-options="field:'addtime',width:150">添加时间</th>
            <th data-options="field:'title',width:80">操作</th>
         </tr>
    </thead>
    <tbody>
    {% if page.items is defined %}
    {% for articlecategory in page.items %}
        <tr>
            <td>{{ articlecategory.id }}</td>
            <td>{{ articlecategory.pid }}</td>
            <td>{{ articlecategory.catname }}</td>
            <td>{{ articlecategory.sortrank }}</td>
            <td>{{ is_show[articlecategory.is_show] }}</td>
            <td>{{ date( 'Y-m-d H:i:s', articlecategory.addtime) }}</td>
            <td>
                {{ link_to("articlecategory/edit/"~articlecategory.id, "Edit") }}
                {{ link_to("articlecategory/delete/"~articlecategory.id, "Delete") }}
            </td>
        </tr>
    {% endfor %}
    {% endif %}
    </tbody>
</table>
<table>
    
    <tbody>
        <tr>
            <td colspan="2" align="right">
                <table align="center">
                    <tr>
                        <td>{{ link_to("articlecategory/search", "首页") }}</td>
                        <td>{{ link_to("articlecategory/search?page="~page.before, "上一页") }}</td>
                        <td>{{ link_to("articlecategory/search?page="~page.next, "下一页") }}</td>
                        <td>{{ link_to("articlecategory/search?page="~page.last, "尾页") }}</td>
                        <td>{{ page.current~"/"~page.total_pages }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    <tbody>
</table>
