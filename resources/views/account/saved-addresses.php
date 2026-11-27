<section class="mt-5" id="saved-addresses" aria-labelledby="saved-addresses-title">
    <h3 id="saved-addresses-title">Saved addresses</h3>
    <?php if (!$addresses) { ?>
        <p>You have no saved addresses.</p>
    <?php } ?>
    <div class="row">
        <?php foreach ($addresses as $savedAddress) { ?>
            <div class="col-md-6 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <?php if (!empty($savedAddress['RecipientName'])) { ?>
                            <strong class="d-block"><?= htmlspecialchars($savedAddress['RecipientName']) ?></strong>
                        <?php } ?>
                        <?php if (!empty($savedAddress['RecipientPhone'])) { ?>
                            <span><?= htmlspecialchars($savedAddress['RecipientPhone']) ?></span>
                        <?php } ?>
                        <p class="text-break"><?= nl2br(htmlspecialchars($savedAddress['AddressText'])) ?></p>
                        <form method="post" action="profile_1.php#saved-addresses" onsubmit="return confirm('Delete this saved address?')">
                            <input type="hidden" name="profile_token" value="<?= htmlspecialchars($token) ?>">
                            <button class="btn btn-outline-danger btn-sm" name="delete_address" value="<?= htmlspecialchars((string) $savedAddress['Id']) ?>"
                                    aria-label="Delete saved address: <?= htmlspecialchars($savedAddress['AddressText']) ?>">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
    <details<?= $addAddress ? ' open' : '' ?>>
        <summary>Add address</summary>
        <form method="post" action="profile_1.php#saved-addresses" class="mt-3">
            <input type="hidden" name="profile_token" value="<?= htmlspecialchars($token) ?>">
            <div class="form-group">
                <label for="saved-recipient">Recipient name</label>
                <input class="form-control" id="saved-recipient" name="recipient" autocomplete="shipping name" maxlength="255" required
                       value="<?= htmlspecialchars($recipient) ?>">
            </div>
            <div class="form-group">
                <label for="saved-phone">Phone number</label>
                <input class="form-control" id="saved-phone" name="phone" type="tel" autocomplete="shipping tel" maxlength="255" required
                       value="<?= htmlspecialchars($addressPhone) ?>">
            </div>
            <div class="form-group">
                <label for="saved-street">Full shipping address</label>
                <textarea class="form-control" id="saved-street" name="address" autocomplete="shipping street-address" maxlength="255" rows="3"
                          required><?= htmlspecialchars($address) ?></textarea>
            </div>
            <button class="btn btn-primary" name="add_address" value="1">Save address</button>
        </form>
    </details>
</section>
