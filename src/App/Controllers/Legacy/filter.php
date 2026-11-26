<?php
$category = (array) ($_GET['category'] ?? []);
$subcategory = (string) ($_GET['subCategory'] ?? '');
header('Location: menu.php?' . http_build_query(['Cate' => reset($category) ?: '', 'subc' => $subcategory === '' ? [] : [$subcategory]]));
