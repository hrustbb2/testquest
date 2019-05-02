<?php
function getErrorMessage($field, $errors)
{
    if (isset($errors[$field])) {
        return $errors[$field][0];
    }
}
?>

<form method="post">
    <div class="form-group">
        <label for="inputLogin">login</label> <?= getErrorMessage('login', $errors) ?>
        <input type="text" class="form-control" id="inputLogin" placeholder="login" name="login" value="<?= $vars['login'] ?? '' ?>">
    </div>

    <div class="form-group">
        <label for="inputPassword">password</label> <?= getErrorMessage('password', $errors) ?>
        <input type="password" class="form-control" id="inputPassword" placeholder="password" name="password">
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-default">Send</button>
    </div>
</form>
