<?php
require_once 'insert.php';
$insert = new Insert();

if(isset($_POST['text'])){
    $insert->insertMessage($_POST['text']);
}
?>


<html>
<head>
<body>

<form name="form" method="post" action="index.php" >
<input type="text" name="text" id="text">
<button type="submit">Submit</button>
</form>


</body>
</head>
</html>

