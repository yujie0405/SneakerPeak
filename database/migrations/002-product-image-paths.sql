-- Store the public asset path for imported product images.
-- New uploads already use Product/ paths relative to IMAGE_BASE_DIR.
UPDATE T_Product
SET Image1Path = CASE
        WHEN CHAR_LENGTH(Image1Path) BETWEEN 1 AND 250 AND Image1Path NOT IN ('.', '..')
            AND INSTR(Image1Path, '/') = 0 AND INSTR(Image1Path, CHAR(92)) = 0
        THEN CONCAT('prod/', Image1Path)
        ELSE Image1Path
    END,
    Image2Path = CASE
        WHEN CHAR_LENGTH(Image2Path) BETWEEN 1 AND 250 AND Image2Path NOT IN ('.', '..')
            AND INSTR(Image2Path, '/') = 0 AND INSTR(Image2Path, CHAR(92)) = 0
        THEN CONCAT('prod/', Image2Path)
        ELSE Image2Path
    END,
    Image3Path = CASE
        WHEN CHAR_LENGTH(Image3Path) BETWEEN 1 AND 250 AND Image3Path NOT IN ('.', '..')
            AND INSTR(Image3Path, '/') = 0 AND INSTR(Image3Path, CHAR(92)) = 0
        THEN CONCAT('prod/', Image3Path)
        ELSE Image3Path
    END,
    AdjustedDate = AdjustedDate;
