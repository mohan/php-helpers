<form action='/?<?= http_build_query(['post_action'=>'login', 'p'=>$_GET['p']]); ?>' method='post'>
    <p>Enter Password</p>
    <input type='password' name='password' autocomplete='off' />
    <input type='submit' />
    <p>Password is "123"</p>
</form>
