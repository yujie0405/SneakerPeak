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
