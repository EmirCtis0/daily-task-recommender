<?php
require "tasksDB.php";

// Check if id parameter is set and not empty
if(isset($_GET["op"]) && $_GET["op"]=="del") {
    $id = $_GET["id"];
   
        // Prepare and execute the delete statement
        $stmt = $db->prepare("DELETE FROM todo WHERE id=?");
        $stmt->execute([$id]);
      
   
}
$typeError=false;

if(isset($_POST["addBtn"]))
{
    if(strlen(trim($_POST["addOne"]))!== 0)
    {
        $stmt=$db->prepare("INSERT INTO todo (action) VALUES (?)");
        $stmt->execute([ $_POST["addOne"]]);
    }
    else{
        $typeError=true;
    }
}

// Fetch the list of items from the database
try {
    $rs = $db->query("SELECT * FROM todo");
    if ($rs) {
        $list = $rs->fetchAll();
    } else {
        echo "Error fetching data.";
    }   
} catch (PDOException $ex) {
    echo "An error occurred: " . $ex->getMessage();
    // Handle error gracefully, redirect to an error page or log the error
    // header("Location: error.php");
    // exit;
}

$total=count($list);

if(isset($_GET["op"])&&$_GET["op"]=="pick")
{
    $random=rand(0, $total-1);
    $pick=$list[$random]["action"];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>TODO supply a title</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="style.css" rel="stylesheet" type="text/css"/>
</head>
<body>
<div>
    <div class="header">
        <div class="container">
            <h1 class="header__title">CTIS256 - Midterm #2</h1>
            <h2 class="header__subtitle">by Your Name Surname</h2>
        </div>
    </div>
    <div class="container">
        <div>
            <a href="?op=pick" class="big-button <?= $total!==0 ? "" : "disabled"?>" >What should I do?</a>
        </div>
        <div class="widget">
            <div>
                <div class="widget-header">
                    <h3 class="widget-header__title">Your Options</h3>
                    <h3 class="widget-header__title"><?=$total?></h3>
                </div>
                <div>
                    <?php if (empty($list)) : ?>
                        <p class="widget__message">List is empty</p>
                    <?php else : ?>
                        <?php foreach($list as $key=>$l) : ?>
                            <div class="option">
                                <p class="option__text"><?= ++$key .'.'.$l['action']?></p>
                                <a href="?op=del&id=<?=$l['id'] ?>" class="button button--link option__trash">&#x1f5d1;</a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
            <div>
                <?php
                    if($typeError)
                    {
                    echo " <p class='add-option-error'>Type some value</p>";
                    }
                ?>
               
                <form class="add-option" method="post" action="taskMain.php">
                    <input class="add-option__input" autocomplete="Off" type="text" name="addOne">
                    <button class="button" name="addBtn">Add One</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php if(isset($random)):?>
<div class="overlay">
         <div class="modal">
             <h1 class="modal__title">My Suggestion &#x1f609;</h1>
             <p class="modal__body"><?=$pick?></p>
             <a class="button" href="taskMain.php">OK</a>
         </div>
</div>
<?php endif?>
</body>
</html>
