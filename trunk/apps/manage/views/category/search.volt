
{{ content() }}

<table width="100%">
    <tr>
        <td align="left">
            {{ link_to("category/index", "Go Back") }}
        </td>
        <td align="right">
            {{ link_to("category/new", "Create ") }}
        </td>
    </tr>
</table>

<table class="browse" align="center">
    <thead>
        <tr>
            <th>Id</th>
            <th>Title</th>
            <th>Parent</th>
            <th>Arr Of Child</th>
            <th>Arr Of Parent</th>
            <th>Deeps</th>
         </tr>
    </thead>
    <tbody>
    {% if page.items is defined %}
    {% for category in page.items %}
        <tr>
            <td>{{ category.id }}</td>
            <td>{{ category.title }}</td>
            <td>{{ category.parent_id }}</td>
            <td>{{ category.arr_child }}</td>
            <td>{{ category.arr_parent }}</td>
            <td>{{ category.deeps }}</td>
            <td>{{ link_to("category/edit/"~category.id, "Edit") }}</td>
            <td>{{ link_to("category/delete/"~category.id, "Delete") }}</td>
        </tr>
    {% endfor %}
    {% endif %}
    </tbody>
    <tbody>
        <tr>
            <td colspan="2" align="right">
                <table align="center">
                    <tr>
                        <td>{{ link_to("category/search", "First") }}</td>
                        <td>{{ link_to("category/search?page="~page.before, "Previous") }}</td>
                        <td>{{ link_to("category/search?page="~page.next, "Next") }}</td>
                        <td>{{ link_to("category/search?page="~page.last, "Last") }}</td>
                        <td>{{ page.current~"/"~page.total_pages }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    <tbody>
</table>
