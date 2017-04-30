<?php

if(isset($_POST['Search'])) {
    $Search = $_POST['ProductSearch'];
    header ('Location: Product.php?Search=' . $Search);
}

?>