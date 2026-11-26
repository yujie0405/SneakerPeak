<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Menu Page</title>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width,initial-scale=1" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="css/headerandfooter.css">

        <link rel="stylesheet" href="css/style.css">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <link rel="stylesheet" href="css/flaticon.css">
        <link rel="stylesheet" href="css/icomoon.css">
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/ionicons.min.css">

        <link rel="stylesheet" href="css/menu.css">
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">



    <?php require SNEAKERPEAK_ROOT . '/resources/views/layouts/theme.php'; ?>
</head>


    <body>
    <?php
       include SNEAKERPEAK_ROOT . '/resources/views/partials/header.php';
       ?>
        <br>
        <br>
        <br>
        <div class="menuborder">
        <?php   
         if ($wishlistMessage !== null) {
            ?>
            <script>Swal.fire({ title: <?= json_encode($wishlistMessage) ?> });</script>
            <?php
         }
         ?>
        <aside class="menuselect" aria-label="Product filters">
                <details class="catalog-filters" open>
                    <summary>Filters<?php if ($filterCount > 0) { ?> <span>(<?= $filterCount ?> applied)</span><?php } ?></summary>
                    <form method="get" action="menu.php">
                        <div class="filter-field">
                            <label for="catalog-search">Search products</label>
                            <input type="search" id="catalog-search" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search by product name">
                        </div>
                        <fieldset>
                            <legend>Brand</legend>
                            <?php foreach ($brandOptions as $brand) { ?>
                                <label class="filter-option">
                                    <input type="checkbox" name="brands[]" value="<?= htmlspecialchars($brand) ?>" <?= in_array($brand, $brands, true) ? 'checked' : '' ?>>
                                    <span><?= htmlspecialchars($brand) ?></span>
                                </label>
                            <?php } ?>
                        </fieldset>
                        <div class="filter-field">
                            <label for="catalog-category">Category</label>
                            <select name="Cate" id="catalog-category">
                                <option value="">All categories</option>
                                <?php foreach ($categories as $option) { ?>
                                    <option value="<?= htmlspecialchars($option) ?>" <?= $category === $option ? 'selected' : '' ?>><?= htmlspecialchars($option) ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <fieldset>
                            <legend>Subcategory</legend>
                            <?php foreach ($subcategoryOptions as $subcategory) { ?>
                                <label class="filter-option">
                                    <input type="checkbox" name="subc[]" value="<?= htmlspecialchars($subcategory) ?>" <?= in_array($subcategory, $subcategories, true) ? 'checked' : '' ?>>
                                    <span><?= htmlspecialchars($subcategory) ?></span>
                                </label>
                            <?php } ?>
                        </fieldset>
                        <div class="filter-actions">
                            <button type="submit" class="btn btn-primary">Apply filters</button>
                            <a href="menu.php">Clear filters</a>
                        </div>
                    </form>
                </details>
            </aside>
            <section class="menu-list" aria-labelledby="catalog-results">
                <h1 id="catalog-results">Products</h1>
                <p class="catalog-result-count"><?= $total ?> <?= $total === 1 ? 'product' : 'products' ?> found</p>
                <?php if ($products === []) { ?>
                    <p>No products match your filters. <a href="menu.php">Clear filters</a> to see all products.</p>
                <?php } else { ?>
                    <div class="catalog-grid">
                        <?php foreach ($products as $product) { ?>
                            <article class="catalog-card">
                                <a class="catalog-wishlist" aria-label="Add <?= htmlspecialchars($product['Prod_Name']) ?> to wishlist"
                                   href="<?= htmlspecialchars($pageUrl($page) . '&' . http_build_query(['add' => $product['Prod_Name']])) ?>">
                                    <i class="fa-solid fa-heart" aria-hidden="true"></i>
                                </a>
                                <img class="catalog-card-image" src="prod/<?= rawurlencode($product['Prod_img1']) ?>" alt="" loading="lazy">
                                <h2><?= htmlspecialchars($product['Prod_Name']) ?></h2>
                                <p class="catalog-description"><?= htmlspecialchars($product['Prod_Details']) ?></p>
                                <p class="catalog-price">RM <?= htmlspecialchars($product['Prod_Price']) ?></p>
                                <a class="btn btn-primary" href="prod.php?Prod_Name=<?= rawurlencode($product['Prod_Name']) ?>">View Product</a>
                            </article>
                        <?php } ?>
                    </div>
                <?php } ?>
                <?php if ($pageCount > 1) { ?>
                    <nav class="catalog-pagination" aria-label="Product results pages">
                        <?php if ($page > 1) { ?>
                            <a rel="prev" href="<?= htmlspecialchars($pageUrl($page - 1)) ?>">Previous</a>
                        <?php } ?>
                        <?php for ($number = 1; $number <= $pageCount; $number++) { ?>
                            <?php if ($number === $page) { ?>
                                <span aria-current="page" aria-label="Page <?= $number ?>"><?= $number ?></span>
                            <?php } else { ?>
                                <a aria-label="Page <?= $number ?>" href="<?= htmlspecialchars($pageUrl($number)) ?>"><?= $number ?></a>
                            <?php } ?>
                        <?php } ?>
                        <?php if ($page < $pageCount) { ?>
                            <a rel="next" href="<?= htmlspecialchars($pageUrl($page + 1)) ?>">Next</a>
                        <?php } ?>
                    </nav>
                <?php } ?>
            </section>
        </div>


            <br>
            <br>



    <footer>
       <?php
       include SNEAKERPEAK_ROOT . '/resources/views/partials/footer.php';

       ?>
    </footer>
    <script src="js/jquery.min.js"></script>
  <script src="js/jquery-migrate-3.0.1.min.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/jquery.easing.1.3.js"></script>
  <script src="js/jquery.waypoints.min.js"></script>
  <script src="js/jquery.stellar.min.js"></script>
  <script src="js/owl.carousel.min.js"></script>
  <script src="js/jquery.magnific-popup.min.js"></script>
  <script src="js/aos.js"></script>
  <script src="js/jquery.animateNumber.min.js"></script>
  <script src="js/bootstrap-datepicker.js"></script>
  <script src="js/scrollax.min.js"></script>
  <?php if ($mapsKey = sneakerpeakSetting('MAPS_BROWSER_KEY')) { ?><script src="https://maps.googleapis.com/maps/api/js?key=<?= rawurlencode($mapsKey) ?>&sensor=false"></script><?php } ?>
  <?php if (!empty($mapsKey)) { ?><script src="js/google-map.js"></script><?php } ?>
  <script src="js/main.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const filterPanel = document.querySelector('.catalog-filters');
    const mobileFilters = window.matchMedia('(max-width: 767px)');
    const updateFilterPanel = () => { filterPanel.open = !mobileFilters.matches || <?= $filterCount ?> > 0; };
    mobileFilters.addEventListener('change', updateFilterPanel);
    updateFilterPanel();
</script>
</body>
</html>