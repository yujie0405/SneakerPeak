<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <link rel="stylesheet" href="css/style.css">
     
    <link rel="stylesheet" href="css/flaticon.css">
    <link rel="stylesheet" href="css/icomoon.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/ionicons.min.css">
    <title>Size Chart</title>
    <link rel="stylesheet" href="assets/css/size-chart.css">
<?php require SNEAKERPEAK_ROOT . '/resources/views/layouts/theme.php'; ?>
</head>
<body>
    <?php
    include SNEAKERPEAK_ROOT . '/resources/views/partials/header.php';

    ?>
    <main class="size-chart container">
        <h1>Size Chart</h1>
        <?php foreach ($sizeGroups as $group => $tables) { ?>
            <section class="size-chart-group">
                <h2><?= htmlspecialchars($group) ?></h2>
                <?php foreach ($tables as $caption => $entries) {
                    $systems = array_keys($entries);
                    $columns = array_map(static fn($values) => explode('|', $values), array_values($entries));
                    $captionId = 'size-chart-' . strtolower(str_replace(' ', '-', $caption));
                    $captionId .= '-' . strtolower(strtok($group, ', '));
                    ?>
                    <div class="size-chart-scroll" tabindex="0" role="region" aria-labelledby="<?= htmlspecialchars($captionId) ?>">
                        <table>
                            <caption id="<?= htmlspecialchars($captionId) ?>"><?= htmlspecialchars($caption) ?></caption>
                            <thead>
                                <tr>
                                    <?php foreach ($systems as $system) { ?>
                                        <th scope="col"><?= htmlspecialchars($system) ?></th>
                                    <?php } ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($columns[0] as $index => $size) { ?>
                                    <tr>
                                        <th scope="row"><?= htmlspecialchars($size) ?></th>
                                        <?php foreach (array_slice($columns, 1) as $column) { ?>
                                            <td><?= htmlspecialchars($column[$index]) ?></td>
                                        <?php } ?>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                <?php } ?>
            </section>
        <?php } ?>
    </main>
<?php
    include SNEAKERPEAK_ROOT . '/resources/views/partials/footer.php';

    ?>
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
</body>
</html>