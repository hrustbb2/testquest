<?php
use app\App;

$urlGenerator = App::getInstance()->getRouterContainer()->getGenerator();

function getSortedQueryString($field, $sortedBy, $sortedDirect)
{
    $newDirect = $sortedDirect;
    if ($field == $sortedBy) {
        $newDirect = ($sortedDirect == 'asc') ? 'desc' : 'asc';
    }
    $newDirectStr = ($newDirect === null) ? 'desc' : $newDirect;
    return 'sortedBy='.$field.'&direct='.$newDirectStr;
}

?>

<div class="btn-group" role="group">
    <a href="<?= $urlGenerator->generate('addTask') ?>" class="btn btn-default">Add</a>
    <?php
        if($isAdmin){
            echo '<a href="'.$urlGenerator->generate('adminLogout').'" class="btn btn-default">Logout</a>';
        }else{
            echo '<a href="'.$urlGenerator->generate('adminLogin').'" class="btn btn-default">Login</a>';
        }
    ?>

</div>

<table class="table table-striped">
    <?php
        $sortedUrl = $urlGenerator->generate('index.page', ['page' => $currentPage]);
        echo '<tr>';
        echo '<th><a href="'.$sortedUrl.'?'.getSortedQueryString('name', $sortedBy, $sortedDirect).'" >User name</a></th>';
        echo '<th><a href="'.$sortedUrl.'?'.getSortedQueryString('email', $sortedBy, $sortedDirect).'" >Email</a></th>';
        echo '<th>Description</th>';
        echo '<th><a href="'.$sortedUrl.'?'.getSortedQueryString('status', $sortedBy, $sortedDirect).'" >Status</a></th>';
        if ($isAdmin) {
            echo '<th></th>';
        }
        echo '</tr>';
        foreach ($tasks as $task) {
            echo '<tr>';
            echo '<td>'.$task->displayUserName().'</td>';
            echo '<td>'.$task->displayEmail().'</td>';
            echo '<td>'.$task->displayDescription().'</td>';
            if ($isAdmin) {
                echo '<td>';
                $url = $urlGenerator->generate('setStatus', ['taskId' => $task->id]);
                echo '<a href="'.$url.'">'.$task->displayStatus().'</a>';
                echo '</td>';
                echo '<td>';
                $url = $urlGenerator->generate('editTask', ['taskId' => $task->id]);
                echo '<a href="'.$url.'">edit</a>';
                echo '</td>';
            } else {
                echo '<td>'.$task->displayStatus().'</td>';
            }
            echo '</tr>';
        }
    ?>
</table>
<div class="btn-group" role="group">
<?php
    $pageCount = ceil($listCount / $perPage);
    for ($i = 1; $i<=$pageCount; $i++) {
        $class = ($i == $currentPage) ? 'btn-primary' : 'btn-default';
        $url = $urlGenerator->generate('index.page', ['page' => $i]);
        echo '<a href="'.$url.'" class="btn '.$class.'">'.$i.'</a>';
    }
?>
</div>