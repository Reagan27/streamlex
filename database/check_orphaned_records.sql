-- Check orphaned records for common foreign keys

-- 1. user_documents.user_id not in users.id
SELECT * FROM user_documents WHERE user_id NOT IN (SELECT id FROM users);

-- 2. user_bank_details.user_id not in users.id
SELECT * FROM user_bank_details WHERE user_id NOT IN (SELECT id FROM users);

-- 3. user_bank_details.bank_id not in banks.id
SELECT * FROM user_bank_details WHERE bank_id NOT IN (SELECT id FROM banks);

-- 4. admin_contracts.role_id not in roles.id
SELECT * FROM admin_contracts WHERE role_id NOT IN (SELECT id FROM roles);

-- 5. user_contract_signatures.user_id not in users.id
SELECT * FROM user_contract_signatures WHERE user_id NOT IN (SELECT id FROM users);

-- 6. user_contract_signatures.contract_id not in admin_contracts.id
SELECT * FROM user_contract_signatures WHERE contract_id NOT IN (SELECT id FROM admin_contracts);

-- 7. regional_coordinator_counties.user_id not in users.id
SELECT * FROM regional_coordinator_counties WHERE user_id NOT IN (SELECT id FROM users);

-- 8. regional_coordinator_counties.county_id not in counties.id
SELECT * FROM regional_coordinator_counties WHERE county_id NOT IN (SELECT id FROM counties);

-- 9. subcounties.county_id not in counties.id
SELECT * FROM subcounties WHERE county_id NOT IN (SELECT id FROM counties);

-- 10. wards.subcounty_id not in subcounties.id
SELECT * FROM wards WHERE subcounty_id NOT IN (SELECT id FROM subcounties);

-- Add more checks as needed for other tables referencing foreign keys.

-- Run these queries in your DB to find orphaned records before adding constraints.