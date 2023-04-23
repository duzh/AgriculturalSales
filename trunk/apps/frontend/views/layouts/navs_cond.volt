<div class="hall-filter" style="height:auto;clear:both">
    <div class="product-filter" id="showCate">
		<span class="keywords myset">
			<font>{% if _nav=="farmlist" %}作物：{% else %}产品：{% endif %}</font>
			<a class=" {% if (url['f']) or (url['c']) %} {% else %}active{% endif %}" href="/{{controller}}/index">全部</a>
		</span>
    </div>
    <div class="product-filter area-filter" id="showAreas"> </div>
</div>

<script>
var query = window.location.search;
function showCate(id) {
    $.get('/ajax/showcate', {id : id,a : '{{url["a"]}}',f : '{{url["f"]}}',p : '{{url["p"]}}',c : '{{url["c"]}}', mc : '{{url["mc"]}}', keyword : '{{ keyword }}', cont : '{{ controller }}'}, function(data) {
        // console.log(data);

        //$('.more-btn').html(data);
        $(data).insertAfter('.myset');
    })
}

function showAreas(id) {
    $.get('/ajax/showareas', {id : id,a : '{{url["a"]}}',f : '{{url["f"]}}',p : '{{url["p"]}}',c : '{{url["c"]}}', mc : '{{url["mc"]}}' , keyword : '{{ keyword }}', cont : '{{ controller }}'}, function(data) {
        $('#showAreas').html(data);
    })
}
// function showsupply(id) {
//     $.get('/ajax/showsupply', {id : id, query : query, cont : '{{ controller }}'}, function(data) {
//         $('#showsupply').html(data);
//     })
// }


$(function(){
    showCate({{ cate }});
    showAreas({{ area }});
    // showsupply({{ area }});
})
</script>