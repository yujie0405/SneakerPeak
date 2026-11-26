# SneakerPeak Revamp

This is the proposal for the second redesign of SneakerPeak, my diploma final-year project. Keep PHP and XAMPP, along with the existing storefront and admin content, page layouts, product images, and core workflows. The revamp focuses on a consistent color palette, centralized connection settings, and a clearer project structure.

## 1. Unify the color palette

- Use the current homepage and brand assets to choose a primary and accent color. Apply a shared palette for backgrounds, text, borders, and status indicators across the storefront and admin area. Confirm the final color values before implementation.
- Create a shared `public/assets/css/theme.css` and define custom properties under `:root`, including `--color-primary`, `--color-accent`, `--color-bg`, `--color-surface`, `--color-text`, `--color-border`, `--color-success`, `--color-warning`, and `--color-danger`. Load the file through the shared storefront and admin layouts.
- Replace repeated color values in project CSS and inline styles with `var(--color-*)`. Start with navigation, buttons, cards, forms, and order statuses. Apply Bootstrap and third-party component color overrides through project styles.
- Compare the homepage, product pages, cart, and admin pages against screenshots from before the revamp. Check desktop and mobile layouts and the contrast of buttons, form fields, and feedback messages.

## 2. Move connection settings to `.env`

- Review `config/Database.php` and the connection scripts in `database/` to identify their callers. The proposed setup reads settings through `src/Config/database.php` and opens connections through `src/Core/Database.php`. Migrate existing callers in batches.
- Store the database IP address or hostname, port, database name, username, and password in the project-root `.env` using `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, and `DB_PASSWORD`. If email or payment integrations are active, move their endpoints and credentials into environment configuration as well.
- Commit an `.env.example` with sample or empty values and ignore the real `.env` in `.gitignore`. Rotate any real credentials found in Git history.
- Point the XAMPP Apache virtual host at `public/` and verify that configuration and SQL files are also inaccessible through the old localhost path. Check that missing settings produce a generic error and that logs omit passwords and keys.

## 3. Proposed folder structure

Use the project-root `Controller/` for route handlers and follow the Filetr.ee layout for `src/App`, `src/Core`, `src/Config`, `resources`, and `public`. Group SneakerPeak templates by storefront, account, and admin area. Serve existing CSS, JavaScript, images, and fonts from `public/assets/`. If the project continues to maintain SCSS, place its source files in `resources/assets/scss/`.

```text
SneakerPeak/
├── Controller/
│   ├── Admin/
│   └── Legacy/
├── src/
│   ├── App/
│   │   ├── Models/
│   │   ├── Services/
│   │   └── Middleware/
│   ├── Core/
│   │   ├── Application.php
│   │   ├── Database.php
│   │   └── Router.php
│   └── Config/
│       ├── app.php
│       └── database.php
├── resources/
│   └── views/
│       ├── storefront/
│       ├── account/
│       ├── admin/
│       ├── layouts/
│       └── partials/
├── public/
│   ├── index.php
│   └── assets/
│       ├── css/
│       │   └── theme.css
│       ├── js/
│       ├── images/
│       └── fonts/
├── database/
│   └── sneakerpeak.sql
├── .env                  # Local configuration, ignored by Git
├── .env.example
├── .gitignore
├── composer.json
└── README.md
```

| Existing files | Proposed migration |
| --- | --- |
| `Controller/ProductController.php`, `Controller/UserController.php` | Keep in the project-root `Controller/` directory and check callers. |
| `index.php`, `cart.php`, `OrderHistory.php`, `admin-Orderlist.php` | Move rendering into the corresponding templates and keep form handling, queries, and permission checks in the application logic. Once the homepage logic moves, use `public/index.php` as the shared entry point. |
| `header.php`, `footer.php`, `admin_sidebar.php` | Move reusable templates to `resources/views/partials/` and load them through the relevant layouts. |
| `css/`, `js/`, `fonts/`, and image directories | Check references before moving these assets into `public/assets/`. Check product image paths stored in the database and identify the directories used for uploads. |
| `sneakerpeak.sql` | Move the existing database import to `database/` as the baseline. Add migrations when the schema changes. |

[Filetr.ee PHP Project Structure](https://filetr.ee/php)

## 4. Layer responsibilities

| Layer | Responsibility | SneakerPeak example |
| --- | --- | --- |
| Controllers | Read route and request inputs, apply request-level validation, call the relevant service or model, and return a view, redirect, or JSON response. | `CartController::updateQuantity()` reads the requested quantity and returns the response expected by the cart page. |
| Models | Own database queries and map rows to the data the application needs. Use prepared statements for user-supplied parameters. | `InventoryModel` reads and updates size-level stock in the existing `sizeandqty` table. |
| Services | Apply business rules and coordinate actions across models or external services. Own transaction boundaries for operations that update several tables. | `CheckoutService` validates the selected cart items, calculates totals on the server, and coordinates order creation with the appropriate payment state. |
| Middleware | Check shared request requirements such as customer or admin login and CSRF protection before dispatching to a controller. | Admin routes check the admin session. Services and queries enforce ownership of specific carts, orders, and addresses. |
| Views | Render controller-provided data, form markup, and partials. Escape rendered values and keep SQL and state changes out of templates. | The header receives a cart count and the cart view receives line items and totals. |
| Core and Config | Bootstrap routing and sessions, load environment settings, and supply the database connection. | Centralize connection creation in `src/Core/Database.php`. |

Read-only pages may call a model directly from a controller when there is no business rule to share. Name models after application concepts and preserve the existing SQL table names during migration, including `oder`, `oder_address`, `oder_details`, `sizeandqty`, and `promote_code`.

## 5. Source-to-target inventory

Controller, model, and service names below are proposed. Page entries also move their HTML into the matching template under `resources/views/`. Track the original URL, HTTP method, parameters, response shape, session requirements, and all callers before converting a legacy endpoint.

| Area | Existing files | Proposed destination and split |
| --- | --- | --- |
| Homepage and static pages | `index.php`, `about.php`, `contact.php`, `Controller/ProductController.php` | `HomeController` renders the home page and reads featured items from `ProductModel`. Move the current `GetLimitItems` query there. Use `PageController` and storefront templates for About and Contact. |
| Catalog and search | `menu.php`, `prod.php`, `get_products.php`, `filter.php`, `filterlist.php`, `Search.php`, `newone.php`, `sizecar.php` | `CatalogController` serves listing, details, and verified filter or search endpoints. `ProductModel`, `BrandModel`, and `InventoryModel` own catalog queries. Move the add-to-cart action from `prod.php` to `CartController`. Confirm the live callers and response formats for the standalone scripts before assigning routes. |
| Authentication and verification | `Login.php`, `Register.php`, `Logout.php`, `Forgot_pwd.php`, `otp_user.php`, `otp_reset.php`, `chg_pwd.php`, `user_changed_password.php`, `controllerUser.php`, `Controller/UserController.php` | Split request handling into `AuthController` actions. `AuthService` owns registration and sign-in, `PasswordResetService` owns reset and OTP rules, and `EmailService` sends messages. `CustomerModel` and `AdminModel` use the current schema. Reconcile the old `registerUser` class with the live registration flow before adopting any of its logic. |
| Customer account and wishlist | `profile_1.php`, `Account_setting.php`, `Password_setting.php`, `wishlist.php`, `delete_wish.php` | `AccountController` and `WishlistController` serve their pages and actions. `CustomerModel` and `WishlistModel` own persistence. Shared authentication logic handles password changes. Limit wishlist and profile writes to the signed-in customer. |
| Cart and promotions | `cart.php`, `call_pic.php`, `plush.php`, `minus.php` | `CartController` owns cart requests, quantity updates, and removal. `CartService` validates items and quantities, `PromotionService` applies eligibility and discount rules, and `CartModel` and `PromotionModel` own queries. Verify the legacy removal link and whether `call_pic.php` still has callers. |
| Address, checkout, and payment | `selectaddress.php`, `fetch_address.php`, `Payment.php`, `card.php`, `QR.php` | `CheckoutController` handles address selection and payment requests. `CheckoutService` coordinates cart, order, and inventory changes, with payment and email actions delegated to the appropriate services. Use `CustomerModel` for the saved address and `OrderModel` for the order address snapshot. Move payment markup into checkout templates and verify how each method actually confirms payment. |
| Customer orders | `OrderHistory.php`, `invoice.php`, `recei.php`, `updateask.php` | `OrderController` serves history, invoice, receipt, and customer status requests. `OrderModel` reads orders and line items. `OrderService` checks ownership and allowed status changes. |
| Admin catalog | `admin_productlist.php`, `admin_productdetails.php`, `admin_addproduct.php`, `admin_addbrand.php`, `Available_item.php`, `delete_item.php`, `update_status.php`, `set_session.php` | `Admin/ProductController` and `Admin/BrandController` handle catalog requests. `CatalogService` coordinates writes across products, brands, and inventory. Preserve visibility and stock rules. Check how the product selection session is used before replacing it with a route parameter. |
| Admin orders and reporting | `admin-Orderlist.php`, `admin_Orderdetails.php`, `changestatus.php`, `telluser.php`, `DashBoad.php`, `admin-OrderDashboard.php`, `admin-ProductDashboard.php`, `linechar.php` | `Admin/OrderController` handles order management through `OrderService` and `EmailService`. `Admin/DashboardController` renders dashboards and returns chart data from `ReportingModel`. |
| Admin accounts, customers, and promotions | `add_newAdmin.php`, `admin_profile.php`, `admin_account_setting.php`, `admin_password_setting.php`, `viewCustomers.php`, `admin_promote.php` | Use `Admin/AccountController`, `Admin/CustomerController`, and `Admin/PromotionController` with `AdminModel`, `CustomerModel`, and `PromotionModel`. Reuse authentication and promotion rules where applicable. Verify account creation permissions separately from profile editing. |
| Shared templates | `header.php`, `footer.php`, `side_bar.php`, `admin_sidebar.php`, `admin_profile_sidebar.php` | Move markup into view partials. Populate cart counts and profile data before rendering, and apply role checks before serving the page. |
| Database bootstrap | `config/Database.php`, `database/Database_Connection.php`, `database/connect.php`, `database/config.php`, `database/conten.php` | Migrate confirmed connection callers to Core and Config. Review `conten.php` separately to establish whether it contains a live integration or an experiment. |
| Needs verification | `Controller/UtilitieController.php`, `date.php`, `popup.php`, `t.php` | Check references in PHP, inline JavaScript, form actions, and direct routes. Assign a destination if active. Leave retirement decisions until usage is confirmed. |

Treat the bundled `stripe-php/` library as a dependency. Confirm whether it is used at runtime before changing how it is installed. Review `prod/` and `prod.zip` for live assets and archive contents before moving them.

## 6. Worked examples

### Checkout from cart to order

- `CartController` renders the cart and passes checkout submissions to `CheckoutService`. The service calculates totals from current product prices and promotion rules, and checks that the selected items belong to the signed-in customer.
- `CheckoutController` handles address selection and payment input. `OrderModel` stores the order, line items, and address snapshot. `InventoryModel` updates stock at the appropriate point in the existing payment flow.
- Trace the current intermediate order records created across `cart.php`, `selectaddress.php`, and `Payment.php` before moving them. Document when stock, cart items, and order status change. Verify duplicate submissions and failures so stock is changed only once, and avoid holding a database transaction open while waiting for an external payment response.
- The cart and checkout views render totals, validation messages, and the relevant forms. Review card data handling and whether card and QR flows are real integrations before deciding the test approach.

### Sign-in, registration, and password reset

- `AuthController` maps each form in `controllerUser.php` to a specific request action and manages the resulting redirect or session update.
- `AuthService` and `PasswordResetService` own credential verification, account verification, and OTP rules. Models execute the customer and admin queries, and `EmailService` handles delivery.
- Plan a compatible migration for existing accounts before changing credential storage. The current file includes direct password comparisons and a remember-password cookie path. Check these paths before the auth cutover, and validate existing customer and admin accounts, reset flows, and remember-me behavior.

### Migration checks for each batch

- Record the old route and the replacement, the templates and queries moved, and any callers that still use compatibility routes.
- Verify rendering, form submissions, redirects or JSON, and permitted and denied access using a local database copy. Check legacy filename casing, especially links to `payment.php` and `admin_OrderDetails.php`.
- Review old scripts for direct reachability after switching the web root. Remove a compatibility route only once its callers are migrated and the replacement is verified.
 
## 7. Implementation order

1. Inventory the current pages, legacy URLs, shared templates, and connection settings. Run through the main workflows with a local database copy and capture screenshots for comparison.
2. Centralize database settings and verify the main pages can connect. Add the shared theme and replace color values page by page.
3. Set up the target folders and entry point. Migrate the storefront, accounts, cart and orders, and admin area in batches. Check legacy links, form submissions, AJAX requests, asset paths, and access controls after each batch. Keep routes for legacy URLs still in use.
4. Verify product browsing and search, registration and login, wishlists, the cart, checkout, order history, and admin management in XAMPP. Verify payments in a test environment.

## Decisions to review

- Choose which existing page should guide the color palette. The homepage and brand assets are the proposed starting point.
- Keep legacy `.php` URLs working during migration. Decide whether to switch to clean URLs once all pages have moved.
