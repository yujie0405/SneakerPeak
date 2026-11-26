# SneakerPeak Database Schema Redesign

Redesign the SneakerPeak MySQL schema. Use the existing `database/sneakerpeak.sql` as the starting point and compare it with the live schema if the configured database is reachable. For this phase, produce a proposed schema and migration plan for review before applying any database changes.

## 1. Users and administrators

- Merge `customer` and `admin` into `T_User`. Use `Id` as the primary key and add `IsAdmin` as a boolean with a default of `false`.
- Create `T_Admin` for administrator-specific information. Use `Id` as the primary key. Reference `T_User.Id` through `UserId` and enforce at most one admin record per user. Review the existing `admin` columns to determine which fields belong in `T_Admin` and which belong in `T_User`.
- Define how admin access is granted and revoked so `T_User.IsAdmin` and `T_Admin` stay consistent. Identify overlapping IDs and duplicate emails across the legacy tables. Document how old IDs and references will map to `UserId`, and flag ambiguous accounts for review before merging them.

## 2. Products and inventory

- Replace `product` with `T_Product`, `brand` with `E_Brand`, and `sub_category` with `E_SubCategory`.
- Create `E_Size` as the size lookup table. Review the current size values and determine whether a size also needs a sizing system such as UK, US, or EU.
- Create `E_ProductStatus` with the initial codes `AVAILABLE`, `OUT_OF_STOCK`, and `DEACTIVATED`. Reference it from `T_Product.ProductStatusId`. Map legacy status values by meaning and report unmapped values for review.
- Store product image paths as `VARCHAR(255)` in `T_Product.Image1Path`, `Image2Path`, and `Image3Path`. Check source path lengths before migration and report any paths that exceed the limit.
- Replace `sizeandqty` with `T_ProductSizeAndCategory`. Use `ProductId`, `SizeId`, and `Qty`, with foreign keys to `T_Product` and `E_Size`. Enforce one row per product and size if the current data confirms that stock is tracked at that level.
- Propose where category belongs. The suggested design is to keep the product's subcategory on `T_Product` and use `T_ProductSizeAndCategory` for size-level stock. Confirm the table name and whether category must also vary by size.

## 3. Orders and customer addresses

- Replace `oder` and `oder_details` with `T_Order` and `T_OrderLineItem`. Link each line item to its order and preserve historical quantities and purchase prices.
- Create `T_CustomerAddress` for saved addresses, linked to `T_User.Id` through `UserId`.
- Store a shipping address snapshot on `T_Order` so editing or deleting a saved address leaves past orders intact. Review `oder_address` and migrate historical shipping addresses into the matching orders. Create saved address records only where the source data supports that mapping.
- Migrate legacy `cart` to `T_Cart` and map its user and stock references.
- Migrate the legacy `promote_code` table to `T_Promotecode` and map existing order promotion references to the corresponding `T_Promotecode.Id` through `T_Order.PromotecodeId`.

### Wishlist

- Rename `wishlist` to `T_Wishlist`. Use `Id` as the primary key and use `UserId` and `ProductId` as foreign keys to `T_User` and `T_Product`. Include `CreatedDate` and `AdjustedDate`.
- Remove the legacy `prod_name` and `Pimg1` columns from the target schema. Read the current product name and image from `T_Product` when displaying the wishlist. Update the PHP add, list, and remove flows to use product IDs.
- During migration, map each legacy `UID` to the new user ID and resolve `prod_name` to a product ID before importing the row. Use the legacy image path as supporting evidence where helpful. Report unmatched or ambiguous products for review before importing those rows.

## 4. Audit columns and relationships

- Add `CreatedDate` and `AdjustedDate` to every table in the final application schema, including lookup tables and retained tables. Use `CreatedDate` for row creation and `AdjustedDate` for the most recent update. Specify the default values, update behavior, and backfill approach for rows without historical timestamps.
- Use uppercase `T_` and `E_` table prefixes. Every table has its own primary key named `Id`. Foreign key columns keep the referenced entity name, such as `T_Admin.UserId` referencing `T_User.Id`. Keep business uniqueness constraints, including one admin per user and one stock row per product and size.
- Document primary keys, foreign keys, unique constraints, indexes, and delete behavior. Preserve historical orders and line items when products or accounts are deactivated.

## 5. Deliverables for review

- An entity-relationship diagram and a table-by-table definition showing columns, types, keys, and constraints.
- An old-to-new mapping for all 12 existing tables, including how to handle duplicate accounts, orphan records, and conflicting stock entries.
- A migration sequence and verification plan for a local database copy. Compare record counts, order totals, inventory by size, and customer versus admin access.
- A list of PHP queries and workflows affected by the schema change, plus any design decisions that need approval before implementation.

## 6. Prepare and migrate existing data

After the target schema is approved and created, clean, transform, and import the existing records. Rehearse the full migration on a local copy of the current database, then apply the verified migration to the target database.

1. Back up the source database and inventory all 12 legacy tables. Check for duplicate emails, conflicting sizes or stock records, missing references, and values that require conversion. Report ambiguous records for review and retain their original IDs for reconciliation.
2. Import users and lookup tables first. Maintain separate old-to-new ID mappings for legacy customers and admins. Use the mappings to create admin records and migrate products, inventory, addresses, orders, order line items, carts, wishlists, and promotions in dependency order. Preserve historical order totals, prices, quantities, and shipping addresses.
3. Preserve available legacy timestamps and apply the agreed backfill rule for missing `CreatedDate` and `AdjustedDate` values. Make the import safe to retry after a failed or partial run without duplicating records or changing stock twice.
4. Reconcile source and target row counts, accounting for approved merges and exceptions. Verify foreign keys, customer and admin access, stock per product and size, order totals, and historical shipping addresses. Record the migration results and any unresolved exceptions before switching the application to the new schema.
