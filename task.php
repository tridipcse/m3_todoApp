<?php 
define('TASK_FILE','tasks.json');
function loadTasks(){
   if(!file_exists(TASK_FILE)){
    return [];
   }
   $data = file_get_contents(TASK_FILE);
   return $data ? json_decode($data,true): [];
}
$tasks = loadTasks();
function saveTask(array $task) {
    file_put_contents(TASK_FILE,json_encode($task,JSON_PRETTY_PRINT));
} 
if($_SERVER['REQUEST_METHOD']=== "POST"){
if(isset($_POST['task']) && !empty(trim($_POST['task']))){
   $tasks[] = [
    'task' => htmlspecialchars($_POST["task"]),
    'done'=> false
    ];
    saveTask($tasks);
    header('Location'.$_SERVER['PHP_SELF']);
    //exit;
}elseif(isset($_POST['delete'])){
    unset($tasks[$_POST['delete']]);
    $tasks = array_values($tasks);
    saveTask($tasks);
    header('Location'.$_SERVER['PHP_SELF']);
}elseif(isset($_POST["toggle"])){
    $tasks[$_POST["toggle"]]["done"] = !$tasks[$_POST["toggle"]]["done"];
    saveTask($tasks);
    header('Location'.$_SERVER['PHP_SELF']);
}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TO DO APP</title>
    <style type="text/css">
        *,html{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }
        .vgap20{
            height: 20px;
            clear: both;
        }
        .wrapper{
            overflow:hidden;
            background:#dedede;
            min-height:800px;
        }
        .container{
            width: 90%;
            margin: 0 auto;
        }
        .app-title h3{
            text-align:center;
        }
        .tskList-title{
            color: #43617b;
            margin-bottom: 20px;
        }
        .todo-app{
            width: 50%;
            margin: 0 auto;
        }
        .task-input-inner{
            display: flex;
            justify-content: center;
        }
        .task-input input{
            height: 35px;
            border-radius: 5px;
            border: 1px solid #938282;
            cursor: text;
            outline: #8ca2b5;
            padding: 5px;
            width:43%;
        }
        tbody:before {
        content: "-";
        display: block;
        line-height: 1em;
        color: transparent;
        }
        th,td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: center;
        }
        tr:nth-child(even) {
            background-color: #c8e6c9;
        }
        tr:nth-child(odd) {
            background-color: #e8f5e9;
        }
        .no-task{
            color: #a85050;
        }
        .task-input .addTask-btn{
            padding: 5px 7px;
            border-radius: 5px;
            border: 1px solid #938282;
            background: #86c786;
            color: #ffffff;
            cursor: pointer;
        }
        .task-inner{
            margin: 0 auto;
        }
        .task-inner tr,.task-list{
            text-align:center;
        }
        .task-inner tr td:nth-child(1){
            width: 75%;
        }
        .task-inner tr td:nth-child(2){
            width: 25%;
        }
        .task-inner thead tr th{
            color: #43617b;
        }
        .task-input .task-btn{
            background: none; 
            border: none; 
            color: #302b2b; 
            width: 100%;
            cursor: pointer;
        }
        .task-input .taskDelete-btn{
            padding: 5px 7px;
            border-radius: 5px;
            border: 1px solid #938282;
            background: #860000;
            color: #ffffff;
            cursor: pointer;
        }
        .task-done{
            text-decoration: line-through;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <div class="todo-app">
                <div class="app-title">
                    <br>
                    <h3>TO DO APP</h3>
                </div>
                    <br>
                <div class="task-input">
                    <form method="POST">
                        <div class="task-input-inner">
                            <input type="text" name="task">
                            <button type="submit" class="addTask-btn">Add Task</button>
                            </div>
                    </form>
                    <div class="task-list">
                        <table class="task-inner">
                        <br>
                        <br>
                        <h3 class="tskList-title">Task List</h3>
                        <thead><tr><th>Name of task</th><th>Action</th></tr></thead>
                                <?php if(!empty($tasks)):?>
                                <?php foreach($tasks as $index => $task):?>
                                <tr class="list"><td><form method="POST"><input type="hidden" name="toggle" value="<?= $index ?>"><button type="submit" class="task-btn"><span class="task <?= $task['done'] ? 'task-done' :''?>"><?= $task['task'] ?></span></form></td><td><form method="POST"><input type="hidden" name="delete" value="<?= $index ?>"><button type="submit" class="taskDelete-btn">Delete</button></form></td></tr>
                                <tr><td><hr></td><td><hr></td></tr>
                                <?php endforeach;?>
                                <?php else: ?>
                                <tr><td class="no-task">No task available yet! Try to make!</td><td></td></tr>
                                <?php endif;?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>