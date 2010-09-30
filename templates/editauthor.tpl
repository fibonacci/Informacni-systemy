<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" href="styly.css" media="all" />
        <script src="jquery.js"></script>
        <script src="javascript.js"></script>
        <title>{title}</title>
    </head>
    <body>
        <div class="page">
            <h1>{title}</h1>
            {menu}
            <h2>Edituj autora</h2>
            <div class="warning">{warning}</div>
            <form method="post">
                <fieldset>
                    <label>Jméno autora:<br /><input type="text" name="nameValue" value="{nameValue}" /></label><br/>
                    <label>Email autora:<br /><input type="text" name="emailValue" value="{emailValue}" /></label><br />
                    <div class="submit"><input type="submit" name="editAuthorSend" value="upravit" /></div>
                </fieldset>
            </form>
        </div>
    </body>
</html>