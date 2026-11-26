# SneakerPeak Customer-Facing Enhancements

Improve the customer-facing color palette, navbar dropdowns, size chart, cart, and product listing while preserving the existing storefront design and shopping flow. This specification also covers the database cutover and a responsive revamp of the admin interface.

## 1. Match the navbar color across customer pages

Use the navbar's charcoal black for existing dark navy UI elements. Reuse the shared `:root` token `var(--color-primary)` in `public/assets/css/theme.css`, currently `#212529`.

- Audit storefront, authentication, customer account, cart, checkout, and order pages for dark navy in custom styles and inline markup. Apply the shared token to the affected backgrounds, buttons, borders, and other UI elements.
- Keep product imagery, the gold accent, and semantic status colors. Verify text contrast and visible hover and focus states after the change.
- Verify that the intended elements match the navbar on both desktop and mobile.

## 2. Keep navbar dropdowns open during pointer movement

Review the shared markup in `resources/views/partials/header.php` and the existing dropdown handlers in `public/assets/js/main.js`. Check the gap between each trigger and its menu and whether hover and click handlers compete for the open state.

- Keep the dropdown open while the pointer moves from the trigger to the menu, including diagonal movement. Make the trigger and menu a continuous hover area and cancel any pending close when the pointer re-enters either one.
- After the pointer leaves both areas, close the dropdown with a brief delay. Keep it open while keyboard focus is inside. Support Escape and click or tap operation, and keep `aria-expanded` in sync using a unique ID for each trigger.
- Verify navigation through Catalog and both signed-in and signed-out account menus. Test pointer movement, keyboard focus, Escape, and touch.

## 3. Turn the size-chart image into a readable listing

Use the image referenced by `Controller/Legacy/sizecar.php` as the source for the existing `sizecar.php` page. Transcribe its entries into HTML, keeping the original labels, values, units, and meaningful groupings. Flag any unreadable entry for review before publishing.

- Use titled sections for any groups shown in the source and semantic tables for size conversions. Give each table clear column headers and style the rows for easy scanning.
- Keep the chart readable on narrow screens. If a table is wider than the screen, let the table scroll within its own container.
- Make the chart text selectable and accessible to screen readers. Verify the entries against the original image and keep the existing Catalog → Size Chart link working.

## 4. Redesign the product-listing filters

Update the left sidebar in `resources/views/storefront/menu.php`. Show product search, brand, category, and subcategory in clearly labeled groups with consistent spacing, visible checkbox states, and a prominent **Apply filters** action. Provide a **Clear filters** action and show the applied selections after the results reload.

- Use a bordered panel and shared theme colors. Replace fixed inline widths with responsive layout styles. Keep the filters beside the products on desktop, and put them in a collapsible **Filters** panel above the results on mobile.
- Preserve the existing search and filtering behavior. Check individual and combined filters, clearing filters, and the empty-results state. Confirm that all controls remain usable by keyboard and touch.

## 5. Match the cart to the navbar

Review `resources/views/storefront/cart.php` and `public/assets/css/cart.css`. Replace remaining dark navy accents and surfaces on the cart page with the navbar's charcoal black through `var(--color-primary)`.

- Check cart headers, quantity controls, totals, promotion controls, and checkout buttons. Where the charcoal color is used as a background, use readable text and keep hover, focus, and disabled states distinct.
- Verify the cart with and without items on desktop and mobile. Check quantity changes, item removal, promo codes, and checkout after the styling update.

## 6. Display uniform product cards in a responsive grid

Update the product results in `resources/views/storefront/menu.php` to use a responsive grid inside the space available beside or below the filter panel.

- Fit multiple cards per row when the results area has room. Use fewer columns as the available width shrinks, down to one column where needed. Wrap remaining cards onto the next row without horizontal overflow.
- Give cards a consistent width and height within each layout. Use a shared image aspect ratio without stretching product photos. Keep titles, prices, and actions aligned when titles have different lengths.
- Check narrow phones, tablets, desktops, and partially filled rows. Confirm that product links and any existing card actions still work.

## 7. Limit the product listing to 20 items per page

Paginate the `menu.php` results on the server after applying search and filters. Return up to 20 products per page and use a stable sort with a unique tie-breaker so products appear in a predictable order across pages.

- Provide page numbers and Previous and Next controls when the result set spans multiple pages. Mark the current page and make navigation work with keyboard and touch.
- Preserve the search query and selected filters when moving between pages. Return to page 1 when the search or filters change or are cleared. Handle invalid and out-of-range page numbers without breaking the listing.
- Verify 0, 20, 21, and more than 40 matching products. With 21 results, show 20 on the first page and one on the second. Verify that the same pagination rules apply to filtered results and that products appear once across pages.

## 8. Switch PHP queries to the migrated database schema

Fix the homepage failure in `src/App/Models/ProductModel.php`. `ProductModel::latest()`, called by `HomeController`, currently runs `SELECT * FROM product ORDER BY Prod_ID DESC LIMIT 8`. The active `sneakerpeak` database now uses `T_Product` with primary key `Id`. Update the query and its view data together so the homepage renders the latest products with their existing images, prices, and links.

Use `database/proposed-schema.sql` and read-only checks against the active XAMPP database to verify exact columns, keys, and lookup codes. Audit every reachable PHP query across models, services, controllers, legacy endpoints, and views, including writes and AJAX handlers. Use the following table mapping.

| Legacy tables | New tables and data |
| --- | --- |
| `customer`, `admin` | `T_User`, `T_Admin`. Use `T_User.Id` for identity and synchronize authorization checks with the migrated admin role. |
| `brand`, `sub_category`, `product`, `sizeandqty` | `E_Brand`, `E_SubCategory`, `T_Product`, `T_ProductSizeAndCategory`, `E_Size`, and `E_ProductStatus`. Resolve lookups through IDs and map product image paths and status codes explicitly. |
| `cart`, `wishlist`, `promote_code` | `T_Cart`, `T_Wishlist`, `T_Promotecode`. Scope reads and writes to the correct user or role. Read wishlist names and images from `T_Product`. |
| `oder`, `oder_details`, `oder_address` | `T_Order` and `T_OrderLineItem`. Read historical shipping information from the order snapshot. Read and edit saved customer addresses through `T_CustomerAddress`. |

- Update both SQL and PHP consumers of legacy column names. Keep active routes and form or AJAX contracts working. Preserve the catalog filters and 20-item pagination.
- Check customer and admin login, registration, account verification, password reset, and sessions. The new user table has no legacy OTP column. Identify what storage or schema changes these flows require and document any blocked flows. Treat migrated accounts with a null password hash as requiring password recovery.
- Preserve the already imported data in `sneakerpeak`. Keep `sneakerpeak_legacy` and `database/sneakerpeak.sql` available for unresolved records. The one-time import script has been removed. Run write-path tests on a database copy and avoid triggering live email or payment calls.
- Verify the homepage, catalog and product details, customer accounts, wishlist, cart, promotions, checkout, order history and invoices, and admin product and order management. Report which workflows passed and any remaining data or schema gaps.

## 9. Revamp the admin dashboard and management pages

Make the entire admin interface more compact and easier to scan at normal browser zoom. Start with the main, product, and order dashboards in `resources/views/admin/`, then apply the same layout and sizing to product and brand management, order lists and details, customers, promotions, admin account creation, profile, and password settings.

- Review `resources/views/partials/admin_sidebar.php`, `admin_profile_sidebar.php`, and the existing admin CSS. Standardize the sidebar, page headers, typography, spacing, and controls through shared admin styles. Replace page-specific fixed dimensions where they cause oversized elements or overflow.
- Give the sidebar a compact desktop width and make it collapsible on narrow screens. Keep the current page clearly highlighted and ensure the sidebar leaves enough room for the content.
- Reduce excess height and padding in summary cards and charts. Arrange related cards in a responsive grid and let them stack at smaller widths. Keep chart labels and legends readable and resize charts with their containers.
- Tighten table rows, badges, and action buttons so more records are visible on desktop. Let wide tables scroll within their own containers on smaller screens. Keep actions reachable and retain visible focus states.
- Use a consistent form layout with readable labels and field-level validation. Group related fields in columns where space permits and stack them on smaller screens. Keep save, cancel, and destructive actions easy to distinguish.
- Preserve existing metrics, data, routes, permissions, and management workflows. Verify representative dashboard, list, detail, and form pages at normal zoom on laptop, desktop, and narrow mobile widths. Confirm that charts and tables fit their containers and that navigation, forms, and management actions still work.

## 10. Enhance admin product management

### Product list and filters

- In `resources/views/admin/admin_productlist.php`, show at most 20 matching products per page. Apply search and filters before counting and paginating on the server. Use a stable sort and show page numbers, Previous, and Next when applicable.
- Support product search, brand, product status, minimum price, maximum price, category, and subcategory. Apply each filter only when it has a value and combine active filters with AND. A zero price is a valid value. Support either price bound on its own and validate invalid or reversed ranges.
- Place **Min price** and **Max price** side by side on the same row as one price-range group. Give both fields their own labels and keep the pair together when the filter layout wraps.
- Label the price column **Price (RM)**. Display only the formatted amount in each product row, such as `129.00`, without repeating the `RM` prefix or suffix.
- Keep search and filter selections when paging. Return to page 1 when criteria change or are cleared. Verify unfiltered, individually filtered, combined, empty, and multi-page results.
- Place an **Add product** button immediately to the right of the search controls on desktop. Keep it visible when the controls wrap on narrow screens.

### Encrypted product detail links

- Replace the product list's `View` link parameter `prodName` with `id` containing an encrypted token for `T_Product.Id`. Use a shared `Manager/EncryptionManager.php` to encrypt and decrypt it.
- Use authenticated encryption with a fresh nonce for each token and a server-side key loaded from `.env`. Encode the resulting token with URL-safe Base64 for the query string. Store only a placeholder for the key in `.env.example`.
- In `Controller/Admin/ProductDetailsController.php`, enforce existing admin access, then decode, authenticate, and decrypt the token. Validate the resulting positive product ID and fetch the row by `T_Product.Id` using a prepared statement. Return an appropriate error for missing, invalid, tampered, or unknown IDs.
- Update detail links, edit and size actions, and redirects that still pass `prodName` so they use the same ID token. Keep links working after a product rename. Verify valid tokens, tampered tokens, missing products, and access without an admin session.

### Category schema and lookup management

- Use the existing `E_Category` lookup for categories such as Male, Female, and Kid. Products reference it through `T_Product.CategoryId`. The legacy `Audience` column has already been removed from the active database.
- Use the category lookup in product forms and filters. Preserve existing product-to-subcategory assignments. Confirm the relationship between categories and subcategories from current data before requiring a parent category.
- Improve the brand, category, and subcategory management screens with consistent lists, clear labels, and visible actions. Add an **Add category** action and support editing and deleting all three lookup types. Before deletion, check product references and explain how to reassign affected products if the lookup is in use.
- Add three tabs at the top of the **Brand and Category** management page: **Brand**, **Category**, and **Subcategory**. Open **Brand** by default and show the selected list and its add, edit, and delete actions in the active panel. Support keyboard navigation and keep the relevant tab selected after form submissions or validation errors.

### Product details and size-level inventory

- In `resources/views/admin/admin_productdetails.php`, open a modal when an admin clicks **Update status** for a size row. Show the product, size, current status, and supported size-level status options. Save only the chosen size row after confirmation. Use the size inventory status rules independently of the overall product status.
- Provide a separate action to adjust or restore the quantity for one size. Prefill the current quantity, accept a nonnegative whole number, and save only that size row. Keep the status unchanged unless the admin also submits a status change. Show errors and the updated row clearly.

### Add-product form and draft state

- Organize `resources/views/admin/admin_addproduct.php` into product details, images, and size inventory. Keep one shared draft for all sections so product fields, size rows, and chosen images stay associated while the admin moves between sections or fixes validation errors. Follow the shared-state concept of Redux within the existing PHP and JavaScript stack.
- Use a sequential form with three steps in this order: **1. Product details → 2. Images → 3. Size inventory**. Show one step at a time with a progress indicator. Provide **Next** and **Back** controls, validate the current step before moving forward, and preserve the draft when going back. Reserve tabs for **Brand and Categories**.
- Show **Save product** on the final step and submit the complete draft together. If submission fails validation, return to the step containing the first error and focus the invalid field.
- Collect size rows in the same draft, without a product-name selector. On Save, validate the complete draft, insert the product, and use its generated `Id` to insert each size row. Commit database changes together and clean up newly moved files if saving fails. Prevent duplicate products on repeat submission.

### Central image manager

- Build a shared, configurable image manager using the existing `Manager/CatalogImageUpload.php` where appropriate. Load the image base directory from environment configuration and route each supported image type through a fixed mapping. Use `Product/` for product images, with dedicated `UserProfile/` and `Logo/` folders for profile images and logos. Confirm the configured base path on the target installation.
- Validate file type and size, generate unique filenames, and store relative image paths in the database. Resolve display URLs consistently for new and existing images. Prevent a caller from specifying arbitrary filesystem destinations.
- Verify image upload and display for each type, and test rollback after a failed product save. Confirm permissions for product and lookup edits and test referenced lookup deletion.

## 11. Improve admin orders and promotions

### Order list and details

- Add **Search order code**, **Date from**, **Date to**, **Payment type**, **Payment status**, and **Order status** to the admin order list. Apply each filter only when it has a value and combine active filters using AND. Support either date bound independently and include both endpoints when both are set. Validate reversed ranges and populate status options from the values supported by the application.
- Keep applied filters visible. Provide a clear action and preserve the active criteria across any pagination.
- In `resources/views/admin/admin_Orderdetails.php`, organize the order items into a compact table with clear columns for product, size, quantity, unit price, and line amount. Align numeric values consistently and place existing order totals below the item list. Keep order status, payment, shipping details, and existing actions easy to find. Show a clear empty state when historical line items are unavailable.

### Promotions

- Add a promotion-code search and an expiry-date range to the admin promotions list. Apply each criterion only when supplied, combine active criteria using AND, and support either date bound independently. Keep selected values visible and provide a clear action.
- Use `E_PromoteType` as the lookup for promotion types. The active XAMPP database already has `FIXED`, `PERCENTAGE`, and `FREE_SHIPPING`, and all 12 existing `T_Promotecode.TypeId` values have been backfilled. Check the live schema before applying further changes. For other installations, make the migration safe for databases at different stages. Compare legacy codes using a common collation or a binary comparison to avoid error `#1267`.
- In **Add Promote** and any promotion edit form, load the available types from `E_PromoteType`, display `Name`, and submit the selected `Id`. Validate the ID on the server and save it in `T_Promotecode.TypeId`. While the legacy `PromotionType` column is required, write its matching `Code` in the same operation.
- In **Promotion Listing**, join `E_PromoteType.Id` to `T_Promotecode.TypeId` and display the lookup name. In checkout, use the lookup code to apply fixed discounts, percentage discounts, and free shipping.
- After verifying add, edit, listing, and checkout flows, confirm that every promotion has a valid type. Make `TypeId` required and add a foreign key to `E_PromoteType.Id`. Remove the old `PromotionType` column after all PHP readers and writers have switched. Update `database/proposed-schema.sql` for fresh installations.

Use this query to populate the **Add Promote** type dropdown. Use `Id` as the option value and `Name` as the label.

```sql
SELECT Id, Name
FROM E_PromoteType
ORDER BY Id;
```

Use the lookup ID for the **Promotion Listing** join. Apply the existing code and expiry-date filters to this query.

```sql
SELECT p.*, pt.Name AS PromotionTypeName
FROM T_Promotecode AS p
JOIN E_PromoteType AS pt ON pt.Id = p.TypeId
ORDER BY p.Id DESC;
```

For installations that still have empty `TypeId` values and the legacy `PromotionType` column, backfill using the query below. The binary comparison matches the known type codes exactly and avoids the collation error.

```sql
UPDATE T_Promotecode AS p
JOIN E_PromoteType AS pt ON BINARY pt.Code = BINARY p.PromotionType
SET p.TypeId = pt.Id
WHERE p.TypeId IS NULL;
```

## 12. Improve customer and administrator management

### Customer list

- Add a **Name** filter using partial matching. Apply it before counting and paginating results. Show up to 20 customers per page, use a stable sort, and preserve the name filter during navigation. Reset to page 1 when the filter changes.
- Add a **Terminate** action for active customers. Ask for confirmation, then use an authorized, CSRF-protected POST to set `T_User.IsActive` to `false` for the selected customer. Keep the account and order history available to administrators. Show the inactive state and ensure terminated customers cannot continue using an existing authenticated session or sign in again.

### Add New Admin form

- Redesign `resources/views/admin/add_newAdmin.php` using the shared admin form styles. Group the existing account and credential fields logically, use compact spacing and clear labels, and stack fields on narrow screens. Provide field-level errors and preserve valid non-password input after a failed submission.
- Keep the create action and cancel action clear. Preserve the existing authorization rules and verify successful creation, duplicate-email errors, and password validation. Ensure creation keeps `T_User.IsAdmin` and the corresponding `T_Admin` row consistent.
