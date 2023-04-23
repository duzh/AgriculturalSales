
{{ content() }}

<table width="100%">
    <tr>
        <td align="left">
            {{ link_to("admin_roles/index", "Go Back") }}
        </td>
        <td align="right">
            {{ link_to("admin_roles/new", "Create ") }}
        </td>
    </tr>
</table>

<table class="browse" align="center">
    <thead>
        <tr>
            <th>Id</th>
            <th>Parent</th>
            <th>Rolename</th>
            <th>Description</th>
         </tr>
    </thead>
    <tbody>
    {% if page.items is defined %}
    {% for admin_role in page.items %}
        <tr>
            <td>{{ admin_role.id }}</td>
            <td>{{ admin_role.parent_id }}</td>
            <td>{{ admin_role.rolename }}</td>
            <td>{{ admin_role.description }}</td>
            <td>{{ link_to("admin_roles/edit/"~admin_role.id, "Edit") }}</td>
            <td>{{ link_to("admin_roles/delete/"~admin_role.id, "Delete") }}</td>
        </tr>
    {% endfor %}
    {% endif %}
    </tbody>
    <tbody>
        <tr>
            <td colspan="2" align="right">
                <table align="center">
                    <tr>
                        <td>{{ link_to("admin_roles/search", "First") }}</td>
                        <td>{{ link_to("admin_roles/search?page="~page.before, "Previous") }}</td>
                        <td>{{ link_to("admin_roles/search?page="~page.next, "Next") }}</td>
                        <td>{{ link_to("admin_roles/search?page="~page.last, "Last") }}</td>
                        <td>{{ page.current~"/"~page.total_pages }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    <tbody>
</table>
