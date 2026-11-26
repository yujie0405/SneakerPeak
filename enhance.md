# SneakerPeak Customer-Facing Enhancements

Improve the customer-facing color palette, navbar dropdowns, size chart, cart, and product listing while preserving the existing storefront design and shopping flow. This specification covers the storefront and customer account pages.

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

Use the image referenced by `src/App/Controllers/Legacy/sizecar.php` as the source for the existing `sizecar.php` page. Transcribe its entries into HTML, keeping the original labels, values, units, and meaningful groupings. Flag any unreadable entry for review before publishing.

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
