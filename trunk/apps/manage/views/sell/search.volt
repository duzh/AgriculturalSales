
{{ content() }}

<table width="100%">
    <tr>
        <td align="left">
            {{ link_to("sell/index", "Go Back") }}
        </td>
        <td align="right">
            {{ link_to("sell/new", "Create ") }}
        </td>
    </tr>
</table>

<table class="browse" align="center">
    <thead>
        <tr>
            <th>Id</th>
            <th>Title</th>
            <th>Category</th>
            <th>Thumb</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Areas</th>
            <th>Address</th>
            <th>Stime</th>
            <th>Etime</th>
            <th>Breed</th>
            <th>Spec</th>
            <th>State</th>
            <th>Createtime</th>
            <th>Updatetime</th>
         </tr>
    </thead>
    <tbody>
    {% if page.items is defined %}
    {% for sell in page.items %}
        <tr>
            <td>{{ sell.id }}</td>
            <td>{{ sell.title }}</td>
            <td>{{ sell.category }}</td>
            <td>{{ sell.thumb }}</td>
            <td>{{ sell.price }}</td>
            <td>{{ sell.quantity }}</td>
            <td>{{ sell.areas }}</td>
            <td>{{ sell.address }}</td>
            <td>{{ sell.stime }}</td>
            <td>{{ sell.etime }}</td>
            <td>{{ sell.breed }}</td>
            <td>{{ sell.spec }}</td>
            <td>{{ sell.state }}</td>
            <td>{{ sell.createtime }}</td>
            <td>{{ sell.updatetime }}</td>
            <td>{{ link_to("sell/edit/"~sell.id, "Edit") }}</td>
            <td>{{ link_to("sell/delete/"~sell.id, "Delete") }}</td>
        </tr>
    {% endfor %}
    {% endif %}
    </tbody>
    <tbody>
        <tr>
            <td colspan="2" align="right">
                <table align="center">
                    <tr>
                        <td>{{ link_to("sell/search", "First") }}</td>
                        <td>{{ link_to("sell/search?page="~page.before, "Previous") }}</td>
                        <td>{{ link_to("sell/search?page="~page.next, "Next") }}</td>
                        <td>{{ link_to("sell/search?page="~page.last, "Last") }}</td>
                        <td>{{ page.current~"/"~page.total_pages }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    <tbody>
</table>
