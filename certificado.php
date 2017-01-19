<html>
<head>
<meta charset="UTF-8">
<script language="javascript">
function add_error(msg) {
    var obj = document.getElementById('error_msg');
    if (obj != null) {
        var html = obj.innerHTML
        if (html == null)
            html = ""
        html = html + '<p style="color:#d00">'+msg+'</p>'
        obj.innerHTML = html
    }
}

function validate_form(form)
{
    var ok = true
    var namevalue = form.name.value
    var codevalue = form.code.value
    if ((namevalue == null && codevalue == null) ||
       (namevalue == "" && codevalue == ""))
    {
        add_error("Preencha o nome ou o c&oacute;digo.")
        ok = false
    }
    return ok
}
</script>
<style>
label {
    display: inline-block;
    min-width: 10em;
    text-align: right;
}

#submit-button-div {
    width: 30em;
    text-align: center;
    margin-top: 10px;
}
.dbentry {
    display: inline-block;
    width: 32%;
}
</style>
</head>
<body>
<p>Digite seu nome ou o c&oacute;digo do certificado a ser validado.</p>
<form action="certificates/generate.php" method="POST" onsubmit="return validate_form(this)">
<label>Nome Completo</label>
<input type="text" name="name" size="50" />
<br />
<label>C&oacute;digo do Certificado</label>
<input type="text" name="code" size="50" />
<br />
<div id="submit-button-div">
<input id="submit-button" type="submit" value="Requisitar Certificado." />
</div>
<div id="error_msg"></div>
</form>
<hr />
<div>
<?php
    if (include('certificates/db.php'))
        list_table('participantes', array("nome"), "nome");
?>
</div>
</body>
</html>
