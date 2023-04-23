{{ content() }}
{{ form("category/save", "method":"post") }}

<table width="100%">
    <tr>
        <td align="left">{{ link_to("category", "Go Back") }}</td>
        <td align="right">{{ submit_button("Save") }}</td>
    </tr>
</table>

<div align="center">
    <h1>Edit category</h1>
</div>

<table>
    <tr>
        <td align="right">
            <label for="title">Title</label>
        </td>
        <td align="left">
            {{ text_field("title", "size" : 30) }}
        </td>
    </tr>
    <tr>
        <td align="right">
            <label for="parent_id">Parent</label>
        </td>
        <td align="left">
            {{ text_field("parent_id", "type" : "numeric") }}
        </td>
    </tr>
    <tr>
        <td align="right">
            <label for="arr_child">Arr Of Child</label>
        </td>
        <td align="left">
                {{ text_field("arr_child", "type" : "date") }}
        </td>
    </tr>
    <tr>
        <td align="right">
            <label for="arr_parent">Arr Of Parent</label>
        </td>
        <td align="left">
                {{ text_field("arr_parent", "type" : "date") }}
        </td>
    </tr>
    <tr>
        <td align="right">
            <label for="deeps">Deeps</label>
        </td>
        <td align="left">
            {{ text_field("deeps", "type" : "numeric") }}
        </td>
    </tr>

    <tr>
        <td>{{ hidden_field("id") }}</td>
        <td>{{ submit_button("Save") }}</td>
    </tr>
</table>

</form>
