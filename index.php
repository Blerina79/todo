
<?php
    const ERROR_REQUIRED = 'veillez renseigner une todo';
    const  ERROR_TOO_SHORT = 'veillez entrer au moins 5 caracteres';

    $filename = __DIR__ . "/data/todos.json";
    $error = '';
    $todos = [];

    if(file_exists($filename)) {
        $data = file_get_contents($filename);
        $todos = json_decode($data, true) ?? [];
    }

   
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
       $_POST = filter_input(INPUT_POST,FILTER_SANITIZE_FULL_SPECIAL_CHARS);
       $todo = $_POST['todo'] ?? '';

       if (!$todo){
        $error = 'ERROR_REQUIRED'; 
       }  else if (mb_strlen($todo)< 5) {
        $error = ERROR_TOO_SHORT;
       }

       if(!$error) {
          $todos = [...$todos,[
            'name' => $todo,
            'done' => false,
            'id'   => time()
          ]];
          file_put_contents($filename, json_encode($todos, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));

       }
    }

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <?php require_once 'includes/head.php'?>
    <title>projet-todo</title>
</head>
<body>
   
   <div class="container">
       <?php require_once 'includes/header.php' ?>
        <div class="content">
            <div class="todo-container">
                <h1>Ma todo</h1>
               <form class="todo-form" action="/" method="POST">
                    <input value="<?=$todo ?>"  name="todo"  type="text">
                    <button class="btn btn-primary">envoyer</button>
               </form> 
               <?php if($error): ?>
                <P class="text-danger"><?=$error ?></P>
                <?php endif; ?>
                <ul class="todo-list">
                         <?php foreach($todos as $t) :?>
                           <li class="todo-item <?=$t['done'] ? 'low-opacity' : ''?>">
                              <span class="todo-name"><?= $t['name'] ?></span>
                              <a href="/edit-todo.php?id=<?= $t['id'] ?>">
                               <button class="btn btn-primary btn-small"><?= $t['done'] ? 'annuler':'valider' ?></button>
                              </a>
                              <a href="/remove-todo.php?id=<?= $t['id'] ?>">
                               <button class="btn btn-danger btn-small">supprimer</button>
                              </a>
                           </li>
                        <?php endforeach ;?>
                </ul>
            </div>
        </div>
        
        <?php require_once 'includes/footer.php'  ?>
   </div>
    
</body>
</html>