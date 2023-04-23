
{{ content() }}

<table width="100%">
    <tr>
        <td align="left">
            {{ link_to("purchase/index", "Go Back") }}
        </td>
        <td align="right">
            {{ link_to("purchase/new", "Create ") }}
        </td>
    </tr>
</table>

<table class="browse" align="center">
    <thead>
        <tr>
            <th>Id</th>
            <th>Uid</th>
            <th>Title</th>
            <th>Category</th>
            <th>Quantity</th>
            <th>Goods Of Unit</th>
            <th>State</th>
            <th>Areas</th>
            <th>Address</th>
            <th>Username</th>
            <th>Createtime</th>
            <th>Updatetime</th>
         </tr>
    </thead>
    <tbody>
    {% if page.items is defined %}
    {% for purchase in page.items %}
        <tr>
            <td>{{ purchase.id }}</td>
            <td>{{ purchase.uid }}</td>
            <td>{{ purchase.title }}</td>
            <td>{{ purchase.category }}</td>
            <td>{{ purchase.quantity }}</td>
            <td>{{ goods_unit[purchase.goods_unit] }}</td>
            <td>{{ state[purchase.state] }}</td>
            <td>{{ purchase.areas_name }}</td>
            <td>{{ purchase.address }}</td>
            <td>{{ purchase.username }}</td>
            <td>{{ date('Y-m-d H:i:s', purchase.createtime) }}</td>
            <td>{{ date('Y-m-d H:i:s', purchase.updatetime) }}</td>
            <td>{{ link_to("purchase/edit/"~purchase.id, "Edit") }}</td>
            <td>{{ link_to("purchase/delete/"~purchase.id, "Delete") }}</td>
        </tr>
    {% endfor %}
    {% endif %}
    </tbody>
    <tbody>
        <tr>
            <td colspan="2" align="right">
                <table align="center">
                    <tr>
                        <td>{{ link_to("purchase/search", "First") }}</td>
                        <td>{{ link_to("purchase/search?page="~page.before, "Previous") }}</td>
                        <td>{{ link_to("purchase/search?page="~page.next, "Next") }}</td>
                        <td>{{ link_to("purchase/search?page="~page.last, "Last") }}</td>
                        <td>{{ page.current~"/"~page.total_pages }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    <tbody>
</table>
