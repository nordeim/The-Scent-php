-- Get table creation statements
SELECT CONCAT(
    '\n-- Table structure for table `', 
    TABLE_NAME, 
    '`\n'
) as '' 
FROM information_schema.TABLES 
WHERE TABLE_SCHEMA = 'aromatherapy_store'
ORDER BY TABLE_NAME;

-- Show create statements for each table
SHOW TABLES;
SELECT CONCAT('SHOW CREATE TABLE `', TABLE_NAME, '`;') as ''
FROM information_schema.TABLES 
WHERE TABLE_SCHEMA = 'aromatherapy_store'
ORDER BY TABLE_NAME;

-- Get column information for each table
SELECT CONCAT(
    '\n-- Columns for table `',
    TABLE_NAME,
    '`\nDESCRIBE `',
    TABLE_NAME,
    '`;'
) as ''
FROM information_schema.TABLES 
WHERE TABLE_SCHEMA = 'aromatherapy_store'
ORDER BY TABLE_NAME;

-- Get index information
SELECT CONCAT(
    '\n-- Indexes for table `',
    TABLE_NAME,
    '`\nSHOW INDEX FROM `',
    TABLE_NAME,
    '`;'
) as ''
FROM information_schema.TABLES 
WHERE TABLE_SCHEMA = 'aromatherapy_store'
ORDER BY TABLE_NAME;

-- Get foreign key constraints
SELECT CONCAT(
    '\n-- Foreign keys for table `',
    TABLE_NAME,
    '`'
) as '',
CONCAT(
    'SELECT ',
    'CONSTRAINT_NAME, ',
    'COLUMN_NAME, ',
    'REFERENCED_TABLE_NAME, ',
    'REFERENCED_COLUMN_NAME ',
    'FROM information_schema.KEY_COLUMN_USAGE ',
    'WHERE TABLE_SCHEMA = ''aromatherapy_store'' ',
    'AND TABLE_NAME = ''', TABLE_NAME, ''' ',
    'AND REFERENCED_TABLE_NAME IS NOT NULL;'
) as ''
FROM information_schema.TABLES 
WHERE TABLE_SCHEMA = 'aromatherapy_store'
ORDER BY TABLE_NAME;

-- Show triggers
SELECT '\n-- Triggers';
SHOW TRIGGERS FROM aromatherapy_store;

-- Show views
SELECT '\n-- Views';
SELECT CONCAT('SHOW CREATE VIEW `', TABLE_NAME, '`;') as ''
FROM information_schema.VIEWS 
WHERE TABLE_SCHEMA = 'aromatherapy_store';

-- Show routines (stored procedures and functions)
SELECT '\n-- Routines (Stored Procedures and Functions)';
SHOW PROCEDURE STATUS WHERE Db = 'aromatherapy_store';
SHOW FUNCTION STATUS WHERE Db = 'aromatherapy_store';
