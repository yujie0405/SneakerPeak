# SneakerPeak

SneakerPeak is a sneaker e-commerce web application I originally built for my university Final Year Project (FYP). I wanted to build a complete online shop where customers could browse sneakers, choose a size, and place an order, while administrators could manage products, stock, promotions, and fulfilment from one dashboard.

The project has since been revamped. The current version separates request handling, database queries, and page templates, uses a relational database schema, and supports a responsive storefront and admin interface.

## Technology stack

| Layer | Technology |
| --- | --- |
| Backend | PHP 8.2+ with MySQLi for database access and OpenSSL for encrypted admin product links |
| Frontend | Server-rendered HTML, CSS, JavaScript, and Bootstrap |
| Database | MariaDB and SQL with InnoDB tables and foreign keys |
| Local development | XAMPP on Windows, Apache with `mod_rewrite`, and phpMyAdmin |

## Storefront and account pages

| Page | Route | What it does |
| --- | --- | --- |
| Home | `/`, `index.php` | Introduces the shop and shows recently added products. |
| Shop | `menu.php` | Shows a paginated product grid with search and brand, category, and subcategory filters. |
| Product details | `prod.php` | Shows product photos, details, price, available sizes and stock. Customers can choose a size and add the item to their cart. |
| Size chart | `sizecar.php` | Shows shoe-size conversion tables. |
| About and contact | `about.php`, `contact.php` | Introduce the store and show its contact information. |
| Sign in and register | `Login.php`, `Register.php` | Sign in or create a customer account. |
| Verify registration | `otp_user.php` | Confirms a new account using an emailed verification code. |
| Recover a password | `Forgot_pwd.php`, `otp_reset.php`, `chg_pwd.php`, `user_changed_password.php` | Request a reset code, verify it, set a new password, and see confirmation. |
| Wishlist | `wishlist.php` | Shows saved products and lets customers remove them. |
| Cart | `cart.php` | Adjusts quantities, removes items, applies a promotion, and starts checkout. |
| Address selection | `selectaddress.php` | Selects or enters a shipping address and chooses a payment method. |
| Payment | `Payment.php` | Presents the selected payment method. Local demo mode can complete checkout for testing. |
| Order history | `OrderHistory.php` | Lists the customer's orders and their progress. |
| Invoice and receipt | `invoice.php`, `recei.php` | Show printable order and payment summaries. |
| Profile, account and password | `profile_1.php`, `Account_setting.php`, `Password_setting.php` | Manage profile information, saved addresses, profile photo, and password. |
| Sign out | `Logout.php` | Ends the current session. |

## Admin pages

| Page | Route | What it does |
| --- | --- | --- |
| Dashboard | `DashBoad.php` | Gives administrators an overview of store activity and recent records. |
| Product dashboard | `admin-ProductDashboard.php` | Shows product reporting and stock summaries. |
| Order dashboard | `admin-OrderDashboard.php` | Summarizes orders and their status. |
| Product list | `admin_productlist.php` | Searches and filters the catalog, shows up to 20 products per page, and links to product details and creation. |
| Product details | `admin_productdetails.php` | Edits product information and updates availability or quantity for individual sizes. |
| Add product | `admin_addproduct.php` | Creates a product with its images and size inventory in one submission. |
| Brand and Categories | `admin_addbrand.php` | Manages brands, categories, subcategories, and brand logos. |
| Order list | `admin-Orderlist.php` | Finds orders by code, date range, payment type, payment status, and order status. |
| Order details | `admin_Orderdetails.php` | Shows the order lines, totals, payment and shipping details, and fulfilment actions. |
| Promotions | `admin_promote.php` | Creates and edits discount codes and filters them by code or expiry date. |
| Customers | `viewCustomers.php` | Searches customers by name, paginates results, and deactivates accounts while preserving their order history. |
| Add administrator | `add_newAdmin.php` | Creates an administrator account through an authorized admin session. |
| Admin profile, account and password | `admin_profile.php`, `admin_account_setting.php`, `admin_password_setting.php` | Manage the signed-in administrator's profile and credentials. |

The route map in [src/Config/routes.php](src/Config/routes.php) also lists form and AJAX endpoints for actions such as cart quantity changes, saved-address retrieval, inventory updates, image delivery, and order notifications.

## Database tables

The current schema is defined in [database/proposed-schema.sql](database/proposed-schema.sql). Each table has its own `Id` primary key and `CreatedDate` and `AdjustedDate` audit columns. Foreign keys use descriptive names, such as `T_Admin.UserId` referencing `T_User.Id`.

| Table | Stores |
| --- | --- |
| `T_User` | Customer and administrator identities, password hashes, profile information, and account state. |
| `T_Admin` | Administrator-specific records linked to `T_User`. |
| `E_Brand` | Brand names and logo paths. |
| `E_Category` | Main product categories such as Male, Female, and Kid. |
| `E_SubCategory` | Product subcategory names. |
| `E_Size` | Shoe-size labels and sizing systems. |
| `E_ProductStatus` | Product status codes such as available, out of stock, and deactivated. |
| `T_Product` | Product details, price, image paths, and references to its brand, category, subcategory, and status. |
| `T_ProductSizeAndCategory` | Quantity and availability for each product and size combination. |
| `T_CustomerAddress` | Saved customer addresses. |
| `T_Cart` | The customer's selected products, sizes, and quantities before checkout. |
| `T_Wishlist` | References linking customers to saved products. |
| `E_PromoteType` | Discount types for fixed amounts, percentages, and free shipping. |
| `T_Promotecode` | Promotion codes, type IDs, values, expiry dates, and active states. |
| `T_Order` | Order totals, payment and fulfilment status, and the shipping address saved at checkout. |
| `T_OrderLineItem` | Purchased products, sizes, quantities, and prices as recorded when the order was created. |

## Database column reference

These definitions follow `database/proposed-schema.sql`. `VARCHAR(n)` holds up to `n` characters, `TEXT` holds longer text, `UNSIGNED` limits integers to nonnegative values, and `BOOLEAN` stores true or false flags. `DECIMAL(13,2)` stores exact amounts with two decimal places. `DATE` stores a calendar date and `DATETIME(6)` includes time with microsecond precision.

### Shared audit columns

| Column | SQL type | Rules and purpose |
| --- | --- | --- |
| `CreatedDate` | `DATETIME(6)` | Required. Defaults to the insertion time. |
| `AdjustedDate` | `DATETIME(6)` | Required. Defaults to the insertion time and updates automatically when a row changes. |

### `T_User`

| Column | SQL type | Rules |
| --- | --- | --- |
| `Id` | `BIGINT UNSIGNED` | Primary key, auto-increment |
| `DisplayName` | `VARCHAR(255)` | Required |
| `Email` | `VARCHAR(255)` | Required |
| `EmailKey` | `VARCHAR(255)` | Nullable, unique |
| `PasswordHash` | `VARCHAR(255)` | Nullable |
| `Phone` | `VARCHAR(255)` | Nullable |
| `ProfileImage` | `VARCHAR(255)` | Nullable |
| `IsAdmin` | `BOOLEAN` | Required, default `FALSE` |
| `IsActive` | `BOOLEAN` | Required, default `TRUE` |
| `EmailVerified` | `BOOLEAN` | Required, default `FALSE` |

Unique combination: `Id` + `IsAdmin`.

### `T_Admin`

| Column | SQL type | Rules |
| --- | --- | --- |
| `Id` | `BIGINT UNSIGNED` | Primary key, auto-increment |
| `UserId` | `BIGINT UNSIGNED` | Required, unique, foreign key to `T_User(Id, IsAdmin)` |
| `AdminFlag` | `BOOLEAN` | Required, default `TRUE`, foreign key to `T_User(Id, IsAdmin)`, `AdminFlag = 1` |
| `GrantedDate` | `DATETIME(6)` | Nullable |
| `GrantedByUserId` | `BIGINT UNSIGNED` | Nullable, foreign key to `T_User(Id)` |

### `E_Brand`

| Column | SQL type | Rules |
| --- | --- | --- |
| `Id` | `BIGINT UNSIGNED` | Primary key, auto-increment |
| `Name` | `VARCHAR(255)` | Required, unique |
| `LogoPath` | `VARCHAR(255)` | Nullable |

### `E_SubCategory`

| Column | SQL type | Rules |
| --- | --- | --- |
| `Id` | `BIGINT UNSIGNED` | Primary key, auto-increment |
| `Name` | `VARCHAR(255)` | Required, unique |

### `E_Category`

| Column | SQL type | Rules |
| --- | --- | --- |
| `Id` | `BIGINT UNSIGNED` | Primary key, auto-increment |
| `Name` | `VARCHAR(255)` | Required, unique |

### `E_Size`

| Column | SQL type | Rules |
| --- | --- | --- |
| `Id` | `BIGINT UNSIGNED` | Primary key, auto-increment |
| `SizingSystem` | `VARCHAR(24)` | Required |
| `SizeLabel` | `VARCHAR(32)` | Required |
| `SortOrder` | `SMALLINT UNSIGNED` | Required, default `0` |

Unique combination: `SizingSystem` + `SizeLabel`.

### `E_ProductStatus`

| Column | SQL type | Rules |
| --- | --- | --- |
| `Id` | `TINYINT UNSIGNED` | Primary key |
| `Code` | `VARCHAR(24)` | Required, unique |

### `T_Product`

| Column | SQL type | Rules |
| --- | --- | --- |
| `Id` | `BIGINT UNSIGNED` | Primary key, auto-increment |
| `BrandId` | `BIGINT UNSIGNED` | Required, foreign key to `E_Brand(Id)` |
| `SubCategoryId` | `BIGINT UNSIGNED` | Required, foreign key to `E_SubCategory(Id)` |
| `CategoryId` | `BIGINT UNSIGNED` | Required, foreign key to `E_Category(Id)` |
| `Name` | `VARCHAR(255)` | Required, unique |
| `Price` | `DECIMAL(13,2)` | Required, `Price >= 0` |
| `Colour` | `VARCHAR(255)` | Nullable |
| `Details` | `TEXT` | Required |
| `Image1Path` | `VARCHAR(255)` | Nullable |
| `Image2Path` | `VARCHAR(255)` | Nullable |
| `Image3Path` | `VARCHAR(255)` | Nullable |
| `ProductStatusId` | `TINYINT UNSIGNED` | Required, foreign key to `E_ProductStatus(Id)` |

### `T_ProductSizeAndCategory`

| Column | SQL type | Rules |
| --- | --- | --- |
| `Id` | `BIGINT UNSIGNED` | Primary key, auto-increment |
| `ProductId` | `BIGINT UNSIGNED` | Required, foreign key to `T_Product(Id)` |
| `SizeId` | `BIGINT UNSIGNED` | Required, foreign key to `E_Size(Id)` |
| `Qty` | `INT UNSIGNED` | Required |
| `IsAvailable` | `BOOLEAN` | Required |

Unique combination: `ProductId` + `SizeId`.

### `T_CustomerAddress`

| Column | SQL type | Rules |
| --- | --- | --- |
| `Id` | `BIGINT UNSIGNED` | Primary key, auto-increment |
| `UserId` | `BIGINT UNSIGNED` | Required, foreign key to `T_User(Id)` |
| `AddressSlot` | `TINYINT UNSIGNED` | Required, `AddressSlot > 0` |
| `AddressText` | `VARCHAR(255)` | Required |
| `RecipientName` | `VARCHAR(255)` | Nullable |
| `RecipientPhone` | `VARCHAR(255)` | Nullable |

Unique combination: `UserId` + `AddressSlot`.

### `E_PromoteType`

| Column | SQL type | Rules |
| --- | --- | --- |
| `Id` | `BIGINT UNSIGNED` | Primary key, auto-increment |
| `Code` | `VARCHAR(24)` | Required, unique |
| `Name` | `VARCHAR(100)` | Required |

### `T_Promotecode`

| Column | SQL type | Rules |
| --- | --- | --- |
| `Id` | `BIGINT UNSIGNED` | Primary key, auto-increment |
| `Code` | `VARCHAR(255)` | Required, unique |
| `TypeId` | `BIGINT UNSIGNED` | Required, foreign key to `E_PromoteType(Id)` |
| `PromotionValue` | `DECIMAL(13,2)` | Required, `PromotionValue >= 0` |
| `ExpiresOn` | `DATE` | Nullable |
| `Details` | `VARCHAR(255)` | Required |
| `IsActive` | `BOOLEAN` | Required |

### `T_Order`

| Column | SQL type | Rules |
| --- | --- | --- |
| `Id` | `BIGINT UNSIGNED` | Primary key, auto-increment |
| `UserId` | `BIGINT UNSIGNED` | Required, foreign key to `T_User(Id)` |
| `OrderCode` | `VARCHAR(255)` | Required, unique |
| `OrderedOn` | `DATE` | Nullable |
| `NetItemsAmount` | `DECIMAL(13,2)` | Required |
| `ShippingFee` | `DECIMAL(13,2)` | Required |
| `CurrencyCode` | `CHAR(3)` | Required, default `'MYR'` |
| `PaymentType` | `VARCHAR(32)` | Nullable |
| `PaymentStatus` | `VARCHAR(24)` | Required |
| `OrderStatus` | `VARCHAR(24)` | Required |
| `ConfirmationRequested` | `BOOLEAN` | Required, default `FALSE` |
| `PromotecodeId` | `BIGINT UNSIGNED` | Nullable, foreign key to `T_Promotecode(Id)` |
| `PromotionCodeSnapshot` | `VARCHAR(255)` | Nullable |
| `ShippingAddressSnapshot` | `VARCHAR(255)` | Nullable |
| `ShippingRecipientSnapshot` | `VARCHAR(255)` | Nullable |
| `ShippingPhoneSnapshot` | `VARCHAR(255)` | Nullable |

### `T_OrderLineItem`

| Column | SQL type | Rules |
| --- | --- | --- |
| `Id` | `BIGINT UNSIGNED` | Primary key, auto-increment |
| `OrderId` | `BIGINT UNSIGNED` | Required, foreign key to `T_Order(Id)` |
| `ProductId` | `BIGINT UNSIGNED` | Nullable, foreign key to `T_Product(Id)` |
| `SizeId` | `BIGINT UNSIGNED` | Nullable, foreign key to `E_Size(Id)` |
| `ProductNameSnapshot` | `VARCHAR(255)` | Required |
| `SizeLabelSnapshot` | `VARCHAR(255)` | Required |
| `UnitPrice` | `DECIMAL(13,2)` | Required |
| `Qty` | `INT UNSIGNED` | Required, `Qty > 0` |
| `RecordedOn` | `DATE` | Nullable |

### `T_Cart`

| Column | SQL type | Rules |
| --- | --- | --- |
| `Id` | `BIGINT UNSIGNED` | Primary key, auto-increment |
| `UserId` | `BIGINT UNSIGNED` | Required, foreign key to `T_User(Id)` |
| `ProductId` | `BIGINT UNSIGNED` | Required, foreign key to `T_ProductSizeAndCategory(ProductId, SizeId)` |
| `SizeId` | `BIGINT UNSIGNED` | Required, foreign key to `T_ProductSizeAndCategory(ProductId, SizeId)` |
| `Qty` | `INT UNSIGNED` | Required, `Qty > 0` |

Unique combination: `UserId` + `ProductId` + `SizeId`.

### `T_Wishlist`

| Column | SQL type | Rules |
| --- | --- | --- |
| `Id` | `BIGINT UNSIGNED` | Primary key, auto-increment |
| `UserId` | `BIGINT UNSIGNED` | Required, foreign key to `T_User(Id)` |
| `ProductId` | `BIGINT UNSIGNED` | Required, foreign key to `T_Product(Id)` |

Unique combination: `UserId` + `ProductId`.
## Project folder structure

```text
SneakerPeak/
├── Controller/
│   ├── Admin/                 # Dashboard and management actions
│   └── Legacy/                # Older route handlers, including active actions
├── Manager/                   # Image storage and encrypted product links
├── src/
│   ├── App/
│   │   ├── Middleware/        # Session and access checks
│   │   ├── Models/            # Database queries
│   │   └── Services/          # Account, checkout, email, and validation logic
│   ├── Config/                # Routes and environment configuration
│   └── Core/                  # Bootstrap, router, and shared database connection
├── resources/views/
│   ├── account/               # Authentication, profile, wishlist, and orders
│   ├── admin/                 # Dashboard and management templates
│   ├── layouts/               # Shared page layouts
│   ├── partials/              # Navigation, footer, and sidebars
│   └── storefront/            # Home, catalog, cart, and checkout
├── public/
│   ├── index.php              # Web entry point
│   └── assets/                # CSS, JavaScript, fonts, and original images
├── database/
│   ├── migrations/            # Upgrades for existing migrated databases
│   ├── proposed-schema.sql    # Current table definitions for a fresh database
│   └── sneakerpeak.sql        # Original FYP schema and data snapshot
├── deploy/apache/             # XAMPP virtual host configuration
├── storage/catalog-images/    # Default local storage for new uploads
├── .env.example               # Environment configuration template
└── composer.json              # PHP requirements and autoload configuration
```

## Run locally with XAMPP

1. Start Apache and MySQL in the XAMPP Control Panel. For a fresh installation, create a database in phpMyAdmin and import [database/proposed-schema.sql](database/proposed-schema.sql). This creates the current schema and initial lookup codes. Products, customers, and orders need to be created or migrated separately.
2. Copy `.env.example` to `.env`. Set the five `DB_*` values and configure the sender and SMTP relay for registration and password recovery. Accounts with a missing password hash can recover access through the password reset flow.
3. Set `PRODUCT_LINK_KEY` to a Base64-encoded 32-byte key. Generate it locally with `php -r "echo base64_encode(random_bytes(32)), PHP_EOL;"`. Changing it invalidates existing encrypted admin product links.
4. Enable Apache's `mod_rewrite` and load [deploy/apache/sneakerpeak.conf](deploy/apache/sneakerpeak.conf) from `httpd-vhosts.conf`. Adjust the Windows paths if needed. Restart Apache and open [SneakerPeak](http://sneakerpeak.localhost/).
5. To test checkout through invoice generation, set `CHECKOUT_DEMO_MODE=true` against a local test database. Demo checkout changes order, cart, and stock data. Live payment requires a provider integration.

### Existing databases and images

Keep the original `sneakerpeak.sql` and any `sneakerpeak_legacy` staging database for reconciliation. Use the migration scripts in order where applicable to the installed schema. Migration `001` is for databases that still need categories backfilled from the legacy `Audience` column. Migration `002` normalizes legacy product image paths. For older promotion schemas, run `php database/migrations/003-promotion-types.php --prepare`, verify promotion management and checkout, then run it with `--finalize`.

Set `IMAGE_BASE_DIR` to a path PHP can write to. The default is `storage/catalog-images`, with separate `Product/`, `UserProfile/`, and `Logo/` folders. The original product photos ship with the repository in `storage/catalog-images/Product/Imported/`. The image manager resolves existing `prod/` paths and bare product filenames to that directory, including when new uploads use a different base directory. Back up both the database and the configured upload directory.
