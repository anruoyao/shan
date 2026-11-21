-- 山海书盟作者端优化：字段增量升级（幂等）
-- 说明：仅新增作者资料、作者等级/荣誉、章节作者说字段，不修改任何渠道相关字段。

SET @dbname = DATABASE();
-- 兼容历史库中的 0000-00-00 日期，避免 ALTER TABLE 拷贝行时报错
SET SESSION sql_mode = 'NO_ENGINE_SUBSTITUTION';

-- ay_member: 作者简介
SET @sql = (SELECT IF(
  (SELECT COUNT(*) FROM information_schema.columns WHERE table_schema=@dbname AND table_name='ay_member' AND column_name='author_bio') > 0,
  'SELECT 1',
  "ALTER TABLE `ay_member` ADD COLUMN `author_bio` varchar(500) NOT NULL DEFAULT '' COMMENT '作者简介'"
));
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- ay_member: 身份证号
SET @sql = (SELECT IF(
  (SELECT COUNT(*) FROM information_schema.columns WHERE table_schema=@dbname AND table_name='ay_member' AND column_name='idcard') > 0,
  'SELECT 1',
  "ALTER TABLE `ay_member` ADD COLUMN `idcard` varchar(18) NOT NULL DEFAULT '' COMMENT '身份证号'"
));
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- ay_member: 后台手动设置的作者称号（帝君作家/至尊作家）
SET @sql = (SELECT IF(
  (SELECT COUNT(*) FROM information_schema.columns WHERE table_schema=@dbname AND table_name='ay_member' AND column_name='author_title') > 0,
  'SELECT 1',
  "ALTER TABLE `ay_member` ADD COLUMN `author_title` varchar(50) NOT NULL DEFAULT '' COMMENT '作者称号，编辑后台设置'"
));
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- ay_member: 后台手动设置的荣誉称号，多个用英文逗号分隔
SET @sql = (SELECT IF(
  (SELECT COUNT(*) FROM information_schema.columns WHERE table_schema=@dbname AND table_name='ay_member' AND column_name='author_honor') > 0,
  'SELECT 1',
  "ALTER TABLE `ay_member` ADD COLUMN `author_honor` varchar(255) NOT NULL DEFAULT '' COMMENT '荣誉称号，编辑后台设置'"
));
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- ay_article: 作者有话说
SET @sql = (SELECT IF(
  (SELECT COUNT(*) FROM information_schema.columns WHERE table_schema=@dbname AND table_name='ay_article' AND column_name='author_say') > 0,
  'SELECT 1',
  "ALTER TABLE `ay_article` ADD COLUMN `author_say` varchar(500) NOT NULL DEFAULT '' COMMENT '作者有话说'"
));
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;
