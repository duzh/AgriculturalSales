function loadRegion(sel, type_id, selName, url) {
  var selectvalue1 = $("#" + sel + "").val();
  var tempArr = selectvalue1.split(",");
  var selectvalue=tempArr[0];

  $("#" + selName + " option").each(function() {
    $(this).remove();
  });
  $("<option value=0>请选择</option>").appendTo($("#" + selName));
  if ($("#" + sel).val() == 0) {
    return;
  }
  $.ajax({
    type: "post",
    url: "" + url + "",
    data: {
      type: type_id,
      pid: selectvalue
    },
    dataType: "json",
    success: function(data) {
      if (data) {
        $.each(data, function(idx, item) {
          jQuery("<option value="+item.area_id+","+item.area_name+ ">" + item.area_name + "</option>").appendTo(jQuery("#" + selName));
        });
      } else {
        jQuery("<option value='0'>请选择</option>").appendTo(jQuery("#" + selName));
      }

    }
  });
}