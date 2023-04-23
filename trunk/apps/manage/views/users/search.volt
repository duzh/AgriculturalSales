

<table width="100%">
    <tr>
        <td align="left">
            {{ link_to("users/index", "Go Back") }}
        </td>
        <td align="right">
            {{ link_to("users/new", "Create ") }}
        </td>
    </tr>
</table>

<table class="browse" align="center">
    <thead>
        <tr>
            <th>Id</th>
            <th>Mobile</th>
            <th>Password</th>
            <th>Createtime</th>
            <th>Role</th>
            <th>Flag</th>
            <th>Lastlogintime</th>
            <th>Role</th>
         </tr>
    </thead>
    <tbody>
    {% if page.items is defined %}
    {% for user in page.items %}
        <tr>
            <td>{{ user.id }}</td>
            <td>{{ user.mobile }}</td>
            <td>{{ user.password }}</td>
            <td>{{ user.createtime }}</td>
            <td>{{ user.role_id }}</td>
            <td>{{ user.flag }}</td>
            <td>{{ user.lastlogintime }}</td>
            <td>{{ user.role }}</td>
            <td>{{ link_to("users/edit/"~user.id, "Edit") }}</td>
            <td>{{ link_to("users/delete/"~user.id, "Delete") }}</td>
        </tr>
    {% endfor %}
    {% endif %}
    </tbody>
    <tbody>
        <tr>
            <td colspan="2" align="right">
                <table align="center">
                    <tr>
                        <td>{{ link_to("users/search", "First") }}</td>
                        <td>{{ link_to("users/search?page="~page.before, "Previous") }}</td>
                        <td>{{ link_to("users/search?page="~page.next, "Next") }}</td>
                        <td>{{ link_to("users/search?page="~page.last, "Last") }}</td>
                        <td>{{ page.current~"/"~page.total_pages }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    <tbody>
</table>
