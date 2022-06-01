<?php
    if(isset($_POST['code']) && $_POST['code']){
        echo $_POST['code'];
        exit;
    }
?>

<html>
<head>
<title>HTML Playground</title>
<style type="text/css">
body{
    padding: 0;
    margin: 0;
    background: #eee;
}
#editor {
    padding: 10px;
    border-top: 1px solid #FFCC00;
    border-bottom: 1px solid #FFCC00;
}
#editor textarea{
    width: 100%;
    height: 85%;
    font-size: 14px;
    font-family: monaco, consolas, "lucida console", "courier new", courier, monospace;
    line-height: 28px;
    background: #eee;
    border: none;
    padding: 0 20px;
    outline: none;
    margin-bottom: 10px;
}
#editor input{
    font-size: 14px;
    font-family: monaco, consolas, "lucida console", "courier new", courier, monospace;
    background: #eee;
    padding: 4px 20px;
    border: 1px solid #999;
}
iframe{
    border: 0;
    width: 100%;
    height: 99%;
    background: #fff;
}
</style>
</head>
<body>

<div id='editor'>
<form method='post' target='output'>
<textarea name='code' autofocus='true' autocomplete='off' autocorrect='off' spellcheck='false' wrap='off'><?php
    if(isset($_POST['code']) && $_POST['code']):
        echo htmlentities($_POST['code']);
    else:
?>&lt;html&gt;
&lt;head&gt;
 &lt;title&gt;Example Page&lt;/title&gt;
 &lt;style type="text/css"&gt;
 body{
  background: #FFCC00;
  color: #000000;
  text-align: center;
 }
 h1{
  margin-top: 50px;
 }
 p{
  color: #555555;
 }
&lt;/style&gt;
&lt;/head&gt;
&lt;body&gt;
 &lt;h1&gt;Hello from HTML Playground!&lt;/h1&gt;
 &lt;p&gt;Type you HTML and CSS here and click run to view output below.&lt;/p&gt;
&lt;/body&gt;
&lt;/html&gt;
<?php endif; ?>
</textarea>
<input type='submit' value='Run' />
</form>
</div>
<iframe name='output' src=''></iframe>

</body>
</html>
