-- Run on the migrated database before deploying the new catalog code.
-- Audience remains available for historical reconciliation.
CREATE TABLE IF NOT EXISTS E_Category (
    Id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(255) NOT NULL UNIQUE,
    CreatedDate DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
    AdjustedDate DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE T_Product
    ADD COLUMN IF NOT EXISTS CategoryId BIGINT UNSIGNED NULL AFTER SubCategoryId;

INSERT INTO E_Category (Name)
SELECT DISTINCT TRIM(Audience) FROM T_Product WHERE CategoryId IS NULL
ON DUPLICATE KEY UPDATE Id = E_Category.Id;

UPDATE T_Product p
JOIN E_Category c ON c.Name = TRIM(p.Audience)
SET p.CategoryId = c.Id, p.AdjustedDate = p.AdjustedDate
WHERE p.CategoryId IS NULL;

ALTER TABLE T_Product
    MODIFY CategoryId BIGINT UNSIGNED NOT NULL,
    ALTER COLUMN Audience SET DEFAULT '',
    ADD UNIQUE KEY IF NOT EXISTS uq_product_name (Name),
    ADD CONSTRAINT fk_product_category FOREIGN KEY IF NOT EXISTS (CategoryId)
        REFERENCES E_Category (Id) ON DELETE RESTRICT;

INSERT INTO E_Size (SizingSystem, SizeLabel, SortOrder)
VALUES ('UNSPECIFIED', '13', 26), ('UNSPECIFIED', '14', 28)
ON DUPLICATE KEY UPDATE Id = Id;
