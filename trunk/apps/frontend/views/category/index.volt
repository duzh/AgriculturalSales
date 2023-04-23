
{{ content() }}

<div align="right">
    {{ link_to("category/new", "Create category") }}
</div>

{{ form("category/search", "method":"post", "autocomplete" : "off") }}

<div align="center">
    <h1>Search category</h1>
</div>

<table>
    <tr>
        <td align="right">
            <label for="id">Id</label>
        </td>
        <td align="left">
            {{ text_field("id", "type" : "numeric") }}
        </td>
    </tr>
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
        <td></td>
        <td>{{ submit_button("Search") }}</td>
    </tr>
</table>

</form>
