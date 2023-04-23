
<script>


function showCate899(id) {

    $.get('/index/showcate', {id : id}, function(data) {

        $('#showCate899').html(data);
    })
}
function showCate7(id) {

    $.get('/index/showcate', {id : id}, function(data) {

        $('#showCate7').html(data);
    })
}
function showCate1(id) {

    $.get('/index/showcate', {id : id}, function(data) {

        $('#showCate1').html(data);
    })
}
function showCate2(id) {

    $.get('/index/showcate', {id : id}, function(data) {

        $('#showCate2').html(data);
    })
}
function showCate1377(id) {

    $.get('/index/showcate', {id : id}, function(data) {

        $('#showCate1377').html(data);
    })
}
$(function(){
    showCate899({{cate["899"]}});
    showCate7({{cate["7"]}});
    showCate1({{cate["1"]}});
    showCate2({{cate["2"]}});
    showCate1377({{cate["1377"]}});
})
</script>