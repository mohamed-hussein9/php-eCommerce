<?php
ob_start();
session_start();
include('init.php'); ?>
<div class="container">
    <?php
    $page = isset($_GET['page']) ? $_GET['page'] : 'NoPage';
    $search = isset($_GET['search']) ? 'AND Name like "%' . str_replace('+', ' ', $_GET['search']) . '%"' : '';

    if (isset($_GET['page'])) {
        echo '<h1>' . str_replace('-', ' ', $page) . '</h1>'; ?>
        <div class="items">
            <?php

            //////////
            if (isset($_GET['paginate'])) {
                $paginate = $_GET['paginate'];
            } else {
                $paginate = 0;
            }
            $num_show = 12;
            $getitems = GetAllFrom('*', 'items', 'WHERE Cat_ID= ' . $_GET['cat_id'] . ' AND Approve=1', 'ItemID', 'DESC LIMIT ' . $paginate . ',' . $num_show . '');
            if (!empty($getitems)) {
                foreach ($getitems as $item) {
                    echo '<div onclick="location.href =\' items.php?itemid=' . $item['ItemID'] . '\' " > ';
                    if (!empty($item['Image'])) {
                        $item_image_name = $item['Image'];
                    } else {
                        $item_image_name = 'default-store.jpg';
                    }
                    echo '<img src="admin/upload/item_images/' . $item_image_name . '" alt="">';
                    echo '<h3>' . $item['Name'] . '</h3>';
                    echo '<h5>' . $item['Description'] . '</h5>';
                    echo '<span class="price">' . $item['Price'] . '</span>';
                    echo '</div>';
                }
            } else {
                echo 'There Is No Items In This Category';
            } ?>
        </div>
        <?php
        // start paginate
        $itemCount = getCount('ItemID', 'items', 'WHERE Cat_ID= ' . $_GET['cat_id'] . ' AND Approve=1');
        $pages = ceil($itemCount / $num_show);
        if ($itemCount > $num_show) {

            echo '<div class="pagination_links">';
            if ($paginate > 0) {
                echo '<a href="index.php?page=' . str_replace('-', ' ', $page) . '&cat_id=' . $_GET['cat_id'] . '&paginate=' . ($paginate - $num_show) . '">prev</a>';
            }

            for ($i = 0; $i < $pages; $i++) {
                if (($paginate / $num_show) + 1 == ($i + 1)) {
                    $active = 'active';
                } else {
                    $active = '';
                }
                echo '<a class="' . $active . '" href="index.php?page=' . str_replace('-', ' ', $page) . '&cat_id=' . $_GET['cat_id'] . '&paginate=' . ($i * $num_show) . '">' . ($i + 1) . '</a>';
            }
            if ($paginate < $pages) {
                echo '<a href="index.php?page=' . str_replace('-', ' ', $page) . '&cat_id=' . $_GET['cat_id'] . '&paginate=' . ($paginate + $num_show) . '">next</a>';
            }

            echo '</div>';
        }
    } else { ?>
        <?php if (isset($_GET['search'])) {
            echo '<h1>  Result Search Of  "' . (str_replace('+', ' ', $_GET['search'])) . '" </h1>';
        } else {
            echo '<h1>ALL ITEMS</h1>';
        } ?>

        <div class="items">

            <?php
            if (isset($_GET['paginate'])) {
                $paginate = $_GET['paginate'];
            } else {
                $paginate = 0;
            }
            $num_show = 12;
            $getitems = GetAllFrom('*', 'items', 'WHERE Approve=1 ' . $search, 'ItemID', 'DESC LIMIT ' . $paginate . ',' . $num_show . '');
            foreach ($getitems as $item) {
                echo '<div onclick="location.href =\' items.php?itemid=' . $item['ItemID'] . '\' " >  ';
                if (!empty($item['Image'])) {
                    $item_image_name = $item['Image'];
                } else {
                    $item_image_name = 'default-store.jpg';
                }
                echo '<img src="admin/upload/item_images/' . $item_image_name . '" alt="">';
                echo '<h3>' . $item['Name'] . '</h3>';
                echo '<h5>' . $item['Description'] . '</h5>';
                echo '<span class="price">' . $item['Price'] . '</span>';
                echo '</div>';
            }


            ?>
        </div>

    <?php

        // start paginate
        $itemCount = getCount('ItemID', 'items', 'WHERE Approve=1');
        $pages = ceil($itemCount / $num_show);
        if ($itemCount > $num_show) {

            echo '<div class="pagination_links">';
            if ($paginate > 0) {
                echo '<a href="index.php?paginate=' . ($paginate - $num_show) . '">prev</a>';
            }

            for ($i = 0; $i < $pages; $i++) {
                if (($paginate / $num_show) + 1 == ($i + 1)) {
                    $active = 'active';
                } else {
                    $active = '';
                }
                echo '<a class="' . $active . '" href="index.php?paginate=' . ($i * $num_show) . '">' . ($i + 1) . '</a>';
            }
            if ($paginate < $pages) {
                echo '<a href="index.php?paginate=' . ($paginate + $num_show) . '">next</a>';
            }

            echo '</div>';
        }
    } ?>
</div>
<?php include($templates . "footer.php");  ?>