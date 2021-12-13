<?php
session_start();

include('init.php'); ?>
<div class="container">
    <?php
    $tag = isset($_GET['tagname']) ? $_GET['tagname'] : 'NoPage';
    if (isset($_GET['tagname'])) {
        echo '<h1>' . str_replace('-', ' ', $tag) . '</h1>'; ?>
        <div class="items">

            <?php
            $getitems = GetAllFrom('*', 'items', 'WHERE tags like "%' . $tag . '%" AND Approve=1', 'ItemID', 'DESC');
            if (!empty($getitems)) {
                foreach ($getitems as $item) {
                    $img_name =  $item['Image'] !== '' ? $item['Image'] : "default-store.jpg";

                    echo '<div onclick="location.href =\' items.php?itemid=' . $item['ItemID'] . '\' " > ';
                    echo '<img src="admin/upload/item_images/' . $img_name . '" alt="">';
                    echo '<h3>' . $item['Name'] . '</h3>';
                    echo '<h5>' . $item['Description'] . '</h5>';
                    echo '<span class="price">' . $item['Price'] . '</span>';
                    echo '</div>';
                }
            } else {
                echo 'There Is No Items with this tag';
            } ?>

        </div>
    <?php } else {
        header('location:index.php');
    } ?>


</div>


<?php include($templates . "footer.php");  ?>