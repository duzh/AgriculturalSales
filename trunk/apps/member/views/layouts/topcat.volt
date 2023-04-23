<?php $arr= Mdg\Models\Category::gettopcate();  $cate=$arr["cate"];?>
<script>


function showtopcate1(id) {

    $.get('/index/showtopcate', {id : id}, function(data) {

        $('#showtop1').html(data);
    })
}
function showtopcate2(id) {

    $.get('/index/showtopcate', {id : id}, function(data) {

        $('#showtop2').html(data);
    })
}
function showtopcate3(id) {

    $.get('/index/showtopcate', {id : id}, function(data) {

        $('#showtop3').html(data);
    })
}
function showtopcate4(id) {

    $.get('/index/showtopcate', {id : id}, function(data) {

        $('#showtop4').html(data);
    })
}
function showtopcate5(id) {

    $.get('/index/showtopcate', {id : id}, function(data) {

        $('#showtop5').html(data);
    })
}
$(function(){

    showtopcate1('{{cate["7"]}}');
    showtopcate2('{{cate["1"]}}');
    showtopcate3('{{cate["2"]}}');
    showtopcate4('{{cate["1377"]}}');
    showtopcate5('{{cate["899"]}}');
})
</script>