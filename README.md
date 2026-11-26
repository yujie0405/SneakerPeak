# SneakerPeak

SneakerPeak is a PHP 8.2 storefront and admin application for XAMPP. It uses MariaDB through the PHP MySQLi extension.

## Local setup

1. Create a database and import `database/sneakerpeak.sql` for a fresh installation. Keep the existing database when upgrading a local installation.
2. Copy `.env.example` to `.env` and set the five `DB_*` values. A blank local database password is supported. Configure the sender and SMTP relay for verification and password-reset mail.
3. In XAMPP, enable `mod_rewrite` and load `deploy/apache/sneakerpeak.conf` from `httpd-vhosts.conf`. Adjust the two Windows paths if XAMPP is installed elsewhere. Restart Apache and open [SneakerPeak](http://sneakerpeak.localhost/).
4. Enable `CHECKOUT_DEMO_MODE=true` only on a local test database to exercise checkout through invoice generation. The demo changes order, cart, and stock data in the connected database. Live payment requires a provider integration.

## Routes and assets

The public entry point is `public/index.php`. The legacy filenames are registered in `src/Config/routes.php`, including case-insensitive aliases such as `payment.php` and `admin_OrderDetails.php`. Apache preserves the old `/SneakerPeak/` entry point and legacy image, script, stylesheet, and font URLs. Product image filenames stored in the database remain valid. Uploads go to `public/assets/prod`, `public/assets/image/logo`, and `public/assets/img`.

The homepage, catalog, sign-in, customer profiles, wishlists, cart, checkout, order history, admin dashboards, and admin product and order management use controllers under `src/App/Controllers`. Legacy scripts dispatch through the same entry point. Catalog creation and promotion views still contain some of their original queries and request handling. The shared palette is in `public/assets/css/theme.css`.

Cart checkout takes prices and shipping from the server and snapshots the order and lines. Address selection saves the address snapshot and payment method. Demo completion locks the owned order, updates inventory, removes the matching cart lines, and marks the payment complete in one transaction. A repeated completion leaves inventory unchanged.

The bundled Stripe PHP library and `prod.zip` are outside the document root. The archive contains product image copies. The previous Stripe experiment had no application caller. Replace or revoke historical Stripe and Google Maps keys through their respective provider accounts. Supply a restricted browser key through `MAPS_BROWSER_KEY` if the embedded map is needed.

## Migrated form and AJAX contracts

| Legacy URL | Method and input | Caller and response |
| --- | --- | --- |
| `Login.php`, `Register.php`, `Forgot_pwd.php`, `otp_user.php`, `otp_reset.php`, `chg_pwd.php` | Existing form POST fields | Account forms render validation errors or redirect to the next step. Remember my email stores the email only. A successful sign-in upgrades eligible legacy passwords to hashes. |
| `prod.php` | GET `Prod_Name` or POST item size, quantity, and cart token | Product links render details. The add-to-cart form displays the result and the server uses the current product price. |
| `plush.php`, `minus.php` | POST product name, size, and cart token | The cart reloads on success. Quantity stays within the signed-in customer's cart and available stock. |
| `cart.php`, `selectaddress.php`, `Payment.php` | GET views or POST forms with cart token | Checkout proceeds to the shipping address, payment method, and invoice. |
| `fetch_address.php` | GET `addressOption` | Checkout receives plain text from the signed-in customer's saved address. |
| `admin_productdetails.php` | GET `prodName`; POST edits with catalog token | Admin product list links directly to the selected product. The controller also accepts the legacy session selection. |
| `delete_item.php`, `Available_item.php`, `update_status.php` | POST product ID or product and size, with catalog token | Admin catalog pages receive plain-text status feedback. |
| `changestatus.php`, `telluser.php` | POST order code and order token | Admin order details receives plain-text status feedback and the controller sends the configured notification. |

## Migrated database

The PHP application now expects the live migrated schema. The SQL in database/proposed-schema.sql is a historical design draft. The PHP queries follow the actual schema with Id primary keys, E_ProductStatus, T_Cart, and T_Promotecode. Keep sneakerpeak_legacy and database/sneakerpeak.sql available for reconciliation. The application does not fall back to the legacy database.

Users with a null PasswordHash need Forgot Password before signing in. This also applies to administrators. Recovery requires an active account and access to its email address. Set up working mail delivery before onboarding imported users. Registration and recovery use hashed, expiring codes bound to the current PHP session, with five verification attempts. Complete the code flow in the same browser session. Shared session storage is required if multiple PHP workers run on different servers. The existing user table needs no OTP column for this setup. Pre-migration sign-in sessions are invalidated, and admin access requires both the user flag and a matching active admin extension.

The live read-only check found nine users with null password hashes, including four admins. Six imported orders have no shipping snapshot, and one order line has no current product reference. Shipment data remains blank when the migration could not establish a snapshot. The order line still displays its saved description, size, price and quantity. Imported size systems are UNSPECIFIED. The current size-label forms remain unambiguous because the active schema has no product with repeated size labels across systems.

On the migrated database copy, verify customer and admin login, denied customer access to admin pages, registration, verification, recovery, saved addresses, catalog and product details, wishlist, cart, promotions, demo checkout, repeat payment submission, order history, invoices, receipts, and admin management. Route tests ran with a local mail capture and demo payments enabled. SMTP delivery to a real mailbox and real payment processing require separate verification. The legacy Search and filter routes lead to the catalog, and the old call_pic and t demo routes lead to the scoped cart.
