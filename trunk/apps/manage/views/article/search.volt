
{{ content() }}

<table width="100%">
    <tr>
        <td align="left">
            {{ link_to("/article/index", "Go Back") }}
        </td>
        <td align="right">
            {{ link_to("/article/new", "Create ") }}
        </td>
    </tr>
</table>

<table class="browse" align="center">
    <thead>
        <tr>
            <th>Id</th>
            <th>Cid</th>
            <th>Title</th>
            <th>Keywords</th>
            <th>Tags</th>
            <th>Description</th>
            <th>Content</th>
            <th>Addtime</th>
            <th>Updatetime</th>
         </tr>
    </thead>
    <tbody>
    {% if page.items is defined %}
    {% for article in page.items %}
        <tr>
            <td>{{ article.id }}</td>
            <td>{{ article.cid }}</td>
            <td>{{ article.title }}</td>
            <td>{{ article.keywords }}</td>
            <td>{{ article.tags }}</td>
            <td>{{ article.description }}</td>
            <td>{{ article.content }}</td>
            <td>{{ date('Y-m-d H:i:s', article.addtime) }}</td>
            <td>{{ date('Y-m-d H:i:s', article.updatetime) }}</td>
            <td>{{ link_to("/article/edit/"~article.id, "Edit") }}</td>
            <td>{{ link_to("/article/delete/"~article.id, "Delete") }}</td>
        </tr>
    {% endfor %}
    {% endif %}
    </tbody>
    <tbody>
        <tr>
            <td colspan="2" align="right">
                <table align="center">
                    <tr>
                        <td>{{ link_to("/article/search", "First") }}</td>
                        <td>{{ link_to("/article/search?page="~page.before, "Previous") }}</td>
                        <td>{{ link_to("/article/search?page="~page.next, "Next") }}</td>
                        <td>{{ link_to("/article/search?page="~page.last, "Last") }}</td>
                        <td>{{ page.current~"/"~page.total_pages }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    <tbody>
</table>
