{% if arr %}
{% for key,val in arr %}
<div class="wms-box f-oh">
    <label class="f-db f-fl">
        <input type="checkbox" name="fromfarm[]" checked='checked' value="{{val['credit_id']}}">
        <em>{{val['farm_name']}}农场</em>
    </label>
    <div class="wms-icon f-db icon1 f-fl">可信农场</div>
</div>
{% endfor %}
{% else %}
无
{% endif %}