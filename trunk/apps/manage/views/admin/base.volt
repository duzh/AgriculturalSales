
<!DOCTYPE html>
<html>
    <head>
        {% block head %}
            <link rel="stylesheet" href="style.css" />
        {% endblock %}
        {% block title %}<title>- My Webpage</title>{% endblock %} 
    </head>
    <body>
        <div id="content">{% block content %}{% endblock %}</div>
        <div id="footer">
            {% block footer %}&copy; Copyright 2012, All rights reserved.{% endblock %}
        </div>
    </body>
</html>