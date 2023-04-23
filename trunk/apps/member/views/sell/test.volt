<!doctype html>
<html lang="us">
<head>
	<meta charset="utf-8">
	<title>jQuery UI Example Page</title>
	
	<style>
	body{
		font: 62.5% "Trebuchet MS", sans-serif;
		margin: 50px;
	}
	.demoHeaders {
		margin-top: 2em;
	}
	#dialog-link {
		padding: .4em 1em .4em 20px;
		text-decoration: none;
		position: relative;
	}
	#dialog-link span.ui-icon {
		margin: 0 5px 0 0;
		position: absolute;
		left: .2em;
		top: 50%;
		margin-top: -8px;
	}
	#icons {
		margin: 0;
		padding: 0;
	}
	#icons li {
		margin: 2px;
		position: relative;
		padding: 4px 0;
		cursor: pointer;
		float: left;
		list-style: none;
	}
	#icons span.ui-icon {
		float: left;
		margin: 0 4px;
	}
	.fakewindowcontain .ui-widget-overlay {
		position: absolute;
	}
	select {
		width: 200px;
	}
	</style>
</head>
<body>



<!-- Autocomplete -->
<h2 class="demoHeaders">Autocomplete</h2>
<div>
	<input id="autocomplete" title="type &quot;a&quot;">
</div>
<input type="radio" onclick="setCheck(7)" >


<link href="{{ constant('JS_URL1') }}/jquery-ui/jquery-ui.css" rel="stylesheet">
<script src="{{ constant('JS_URL1') }}/jquery-ui/external/jquery/jquery.js"></script>
<script src="{{ constant('JS_URL1') }}/jquery-ui/jquery-ui.js"></script>

<script>
var availableTags = [
	"ActionScript",
	"AppleScript",
	"Asp",
	"BASIC",
	"C",
	"C++",
	"Clojure",
	"COBOL",
	"ColdFusion",
	"Erlang",
	"Fortran",
	"Groovy",
	"Haskell",
	"Java",
	"JavaScript",
	"Lisp",
	"Perl",
	"PHP",
	"Python",
	"Ruby",
	"Scala",
	"Scheme",
	"ActionScript",
	"AppleScript",
	"Asp",
	"BASIC",
	"C",
	"C++",
	"Clojure",
	"COBOL",
	"ColdFusion",
	"Erlang",
	"Fortran",
	"Groovy",
	"Haskell",
	"Java",
	"JavaScript",
	"Lisp",
	"Perl",
	"PHP",
	"Python",
	"Ruby",
	"Scala",
	"Scheme",
	'张贵平'
];

// 本地json数组
var availableTagsJSON = [
    { label: "C# Language", value: "C#",id :"2"},
    { label: "C++ Language", value: "C++" ,id:"2"},
    { label: "Java Language", value: "Java" ,id:"2"},
    { label: "JavaScript Language", value: "JavaScript" ,id:"2"},
    { label: "ASP.NET", value: "ASP.NET",id:"2" },
    { label: "JSP", value: "JSP",id:"2" },
    { label: "PHP", value: "PHP" ,id:"2"},
    { label: "Python", value: "Python" ,id:"2"},
    { label: "Ruby", value: "Ruby",id:"2" }
];
// function availableTagsJSONtest(availableTagsJSON){
// 	$( "#autocomplete" ).autocomplete({
// 		source: availableTagsJSON,select: function(e, ui){
// 	      alert(ui.item.id) 

// 	    }
//     });
// }
$( "#autocomplete" ).autocomplete({
		source: availableTagsJSON,select: function(e, ui){
	      alert(ui.item.id) 

	    }
});

// function setCheck(pid) {

//     $.getJSON('/member/ajax/getcate',{'pid':pid},function(data) {
//     	 $("#autocomplete").unbind();
//          availableTagsJSONtest(data);
//     });
// }
</script>
</body>
</html>
