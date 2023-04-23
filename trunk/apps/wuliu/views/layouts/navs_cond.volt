	
    <!-- 块 start -->
	<div class="choose_list mt20 mb10">
    	<!-- list1 start -->
        <div class="list" id="showCate">

        </div>
        <!-- list1 end -->
    	<!-- list1 start -->
        <div class="list border_none" id="showAreas">
        </div>
        <!-- list1 end -->
    </div>
    <!-- 块 end -->

<script>

var query = window.location.search;

function showCate(id,type) {

    $.get('/ajax/showcate', {id : id, query : query, cont : '{{ controller }}',type:type}, function(data) {
        $('#showCate').html(data);
    })
}

function showAreas(id) {
    $.get('/ajax/showareas', {id : id, query : query, cont : '{{ controller }}'}, function(data) {
        $('#showAreas').html(data);
    })
}

$(function(){

     {% if onec %}
     showCate({{ onec }},1);
     {% else %}
      showCate(0,1);
     {% endif %}
     {% if twoc %}
     showCate({{ twoc }},2);
     {% endif %}
     {% if threec %}
     showCate({{ threec }},3);
     {% endif %}
    showAreas({{ area }});
})
</script>