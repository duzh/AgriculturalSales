{{ partial('layouts/shop_header') }}


<div class="ur_here w960">
     <span>{{ partial('layouts/ur_here') }}栏目列表</span>
</div>

<div class="shop_decora w960 clearfix">

    {{ partial('layouts/shop_left') }}
    <div class="decora_right f-fr">
        <div class="topic_list">
            <div class="add_list">
                <a href="/member/columns/new/">新增栏目</a>
            </div>
            <div class="title">栏目列表</div>
            <table cellpadding="0" cellspacing="0" style="background:#fff;" width="100%" class="f-fs12">
                <tr height="30" style="border-bottom:solid 2px #ccc;">
                    <th width="8%">序号</th>
                    <th width="15%">名称</th>
                    <th width="15%">链接</th>
                    <th width="15%">上级导航</th>
                    <th width="20%">添加时间</th>
                    <th width="27%">操作</th>
                </tr>
                {% for key,val in data['items'] %}
                <tr height="40" style="border-bottom:dashed 1px #ccc;" >
                    <td>{{ key + 1 }}</td>
                    <td>{{val['col_name']}}</td>
                    <td>store/shoplook/columns/{{val['id']}}</td>
                    <td><?php echo Mdg\Models\ShopColumns::getColumnName($val['col_pid']);?></td>
                    <td>{{date("Y-m-d H:i:s",val['add_time'])}}</td>
                    <td>

                        <a class="link" href="/member/columns/edit/{{ val['id'] }}">编辑</a>
                        <a class="link" href="javascript:;" onclick='removeCol({{val['id']}})'>删除</a>
                        <a class="link" href="/member/columns/add/{{val['id']}}">内容添加</a>
                    </td>
                </tr>
                {% endfor %}
            </table>
            <!-- 分页 start -->
            <div class="page">{{ data['pages'] }}</div>
            <!-- 分页 end --> </div>
    </div>
</div>

<script type="text/javascript">
    function  removeCol (id) {
        var returnVal = window.confirm("确定要删除吗?") ;
        if(returnVal) { window.location.href = "/member/columns/remove/"+id; }
    }
</script>

<!-- 底部 start -->
{{ partial('layouts/footer') }}
<!-- 底部 end -->