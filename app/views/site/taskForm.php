<?php
function getErrorMessage($field, $errors)
{
    if (isset($errors[$field])) {
        return $errors[$field][0];
    }
}

function getValues($field, $values, $editedTask)
{
    if (isset($values[$field])) {
        return $values[$field];
    }
    if ($editedTask !== null) {
        return $editedTask->{$field};
    }
    return '';
}

//Отключаем некоторые поля в режиме редактирования таска админом
$disableField = ($task === null) ? '' : 'disabled';

?>

<form method="post">
    <div class="form-group">
        <label for="inputUserName">User name</label> <?= getErrorMessage('userName', $errors) ?>
        <input type="text" class="form-control" id="inputUserName" placeholder="User name" name="userName" value="<?= getValues('userName', $values, $task) ?>" <?= $disableField ?>>
    </div>

    <div class="form-group">
        <label for="inputEmail">Email address</label> <?= getErrorMessage('email', $errors) ?>
        <input type="text" class="form-control" id="inputEmail" placeholder="Email" name="email" value="<?= getValues('email', $values, $task) ?>" <?= $disableField ?>>
    </div>

    <div class="form-group">
        <label for="inputDescription">Description</label> <?= getErrorMessage('description', $errors) ?>
        <textarea rows="4" cols="50" name="description" id="inputDescription" class="form-control"><?= getValues('description', $values, $task) ?></textarea>
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-default">Send</button>
    </div>

</form>