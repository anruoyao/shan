-- 山海书盟作者端优化：字段增量回滚
-- 警告：回滚会删除作者简介、身份证号、作者称号、荣誉称号、作者有话说字段，请先备份。

SET @dbname = DATABASE();

SET @sql = (SELECT IF(
  (SELECT COUNT(*) FROM information_schema.columns WHERE table_schema=@dbname AND table_name='ay_member' AND column_name='author_bio') > 0,
  "ALTER TABLE `ay_member` DROP COLUMN `author_bio`",
  'SELECT 1'
));
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql = (SELECT IF(
  (SELECT COUNT(*) FROM information_schema.columns WHERE table_schema=@dbname AND table_name='ay_member' AND column_name='idcard') > 0,
  "ALTER TABLE `ay_member` DROP COLUMN `idcard`",
  'SELECT 1'
));
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql = (SELECT IF(
  (SELECT COUNT(*) FROM information_schema.columns WHERE table_schema=@dbname AND table_name='ay_member' AND column_name='author_title') > 0,
  "ALTER TABLE `ay_member` DROP COLUMN `author_title`",
  'SELECT 1'
));
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql = (SELECT IF(
  (SELECT COUNT(*) FROM information_schema.columns WHERE table_schema=@dbname AND table_name='ay_member' AND column_name='author_honor') > 0,
  "ALTER TABLE `ay_member` DROP COLUMN `author_honor`",
  'SELECT 1'
));
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql = (SELECT IF(
  (SELECT COUNT(*) FROM information_schema.columns WHERE table_schema=@dbname AND table_name='ay_article' AND column_name='author_say') > 0,
  "ALTER TABLE `ay_article` DROP COLUMN `author_say`",
  'SELECT 1'
));
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;
