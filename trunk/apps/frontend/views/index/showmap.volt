
<div  id="showmap">

</div>
<script>
function showmap(p) {
    $.get('/index/showmap', {p:p}, function(data) {
    	alert(data);
        $('#showmap').html(data);
    })
}
$(function(){
    showmap(1);
})
</script>