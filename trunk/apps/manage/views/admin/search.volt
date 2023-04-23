
{{ content() }}

<table width="100%">
    <tr>
        <td align="left">
            {{ link_to("admin/index", "Go Back") }}
        </td>
        <td align="right">
            {{ link_to("admin/new", "Create ") }}
        </td>
    </tr>
</table>

<table class="browse" align="center">
    <thead>
        <tr>
            <th>Id</th>
            <th>Username</th>
            <th>Password</th>
            <th>Salt</th>
            <th>Lastlogintime</th>
            <th>Lastloginip</th>
            <th>Logincount</th>
            <th>Createtime</th>
            <th>Role</th>
         </tr>
    </thead>
    <tbody>
    {% if page.items is defined %}
    {% for admin in page.items %}
        <tr>
            <td>{{ admin.id }}</td>
            <td>{{ admin.username }}</td>
            <td>{{ admin.password }}</td>
            <td>{{ admin.salt }}</td>
            <td>{{ admin.lastlogintime }}</td>
            <td>{{ admin.lastloginip }}</td>
            <td>{{ admin.logincount }}</td>
            <td>{{ admin.createtime }}</td>
            <td>{{ admin.role_id }}</td>
            <td>{{ link_to("admin/edit/"~admin.id, "Edit") }}</td>
            <td>{{ link_to("admin/delete/"~admin.id, "Delete") }}</td>
        </tr>
    {% endfor %}
    {% endif %}
    </tbody>
    <tbody>
        <tr>
            <td colspan="2" align="right">
                <table align="center">
                    <tr>
                        <td>{{ link_to("admin/search", "First") }}</td>
                        <td>{{ link_to("admin/search?page="~page.before, "Previous") }}</td>
                        <td>{{ link_to("admin/search?page="~page.next, "Next") }}</td>
                        <td>{{ link_to("admin/search?page="~page.last, "Last") }}</td>
                        <td>{{ page.current~"/"~page.total_pages }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    <tbody>
</table>
