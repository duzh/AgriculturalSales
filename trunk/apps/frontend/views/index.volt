{% if is_ajax  %}
<script type="text/javascript" src="{{ constant('JS_URL') }}lhgdialog/lhgdialog.min.js?skin=igreen">
</script>
<script type="text/javascript" src="{{ constant('STATIC_URL') }}/mdg/js/dialog_call.js?skin=igreen"></script>
<?php echo $this->getContent(); ?>
{% else %}
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	{% if keywords != '' and descript != '' %}
	{% if _nav != 'advisory' %}
	<meta name="Keywords" content="{{ keywords }}" />
	<meta name="Description" content="{{ descript }}" />
	{% endif %}
	{% endif %}
	<title>{{ title }}</title>

	<link rel="shortcut icon" href="{{ constant('STATIC_URL') }}mdg/ico/5fengshou.ico" />
	<?php $dev=DEV_MODE?>
	{% if dev =='master'%}
	<link rel="stylesheet" href="{{ constant('STATIC_URL') }}mdg/version2.5/css/base.css">
	<link rel="stylesheet" href="{{ constant('STATIC_URL') }}mdg/version2.5/css/index.css">
	<link rel="stylesheet" href="{{ constant('STATIC_URL') }}mdg/version2.5/css/page.css">
	<script src="{{ constant('STATIC_URL') }}mdg/version2.5/js/jquery-1.11.1.min.js"></script>
	<script src="{{ constant('STATIC_URL') }}mdg/version2.5/js/index.js"></script>
	<script src="{{ constant('STATIC_URL') }}mdg/version2.5/js/page.js"></script>
	<script type="text/javascript" src="{{ constant('STATIC_URL') }}mdg/lhgdialog/lhgdialog.min.js?skin=igreen"></script>
	<script type="text/javascript" src="{{ constant('STATIC_URL') }}/mdg/js/dialog_call.js?skin=igreen"></script>
	<script type="text/javascript" src="{{ constant('JS_URL') }}jquery/ld-select.js"></script>
    {% else %}
    <link rel="stylesheet" href="/mdgtest/version2.5/css/base.css">
	<link rel="stylesheet" href="/mdgtest/version2.5/css/index.css">
	<link rel="stylesheet" href="/mdgtest/version2.5/css/page.css">
	<script src="/mdgtest/version2.5/js/jquery-1.11.1.min.js"></script>
	<script src="/mdgtest/version2.5/js/index.js"></script>
	<script src="/mdgtest/version2.5/js/page.js"></script>
	<script type="text/javascript" src="/mdgtest/lhgdialog/lhgdialog.min.js?skin=igreen"></script>
	<script type="text/javascript" src="/mdgtest/js/dialog_call.js?skin=igreen"></script>
	<script type="text/javascript" src="{{ constant('JS_URL') }}jquery/ld-select.js"></script>
    {% endif %}
</head>
<body>
	<?php echo $this->getContent(); ?>
</body>
</html>
{% endif %}

