# WAP 分类与书城改版（2026-11-28）

点击首页「分类」进入频道与分类网格，点击分类进入该分类书城。使用站内原有分类名称、书籍封面与实际数量；宽度不足时网格从三列调整为两列。

## 后台设置

后台 → 栏目管理 → 对应分类 → **WAP推荐位**。

- 每个分类分别设置「热门推荐」「连载推荐」「编辑推荐」「新书入库」。支持按书名、作者或 ID 搜索，点击加入，调整顺序或移除，再保存。
- 每个推荐位最多 20 本。分类书城先展示热门推荐前 4 本、其他区前 3 本；「更多」展示该区全部已配置作品。
- 只允许选择本分类及其子分类中的已上架作品；连载推荐额外排除完本。
- 初次未设置时，热门、连载、编辑区分别兼容旧后台的 `isrecommend`、`hrlz`、`bjlj` 人工推荐标记。「新书入库」需手动选书，未设置时显示空状态。
- 保存空列表会清空该推荐位，不自动补书；同一作品可以由编辑分别安排在不同推荐位。
- 可设置分类显示于男生或女生频道，默认依据分类名称识别。

## 改动文件

| 文件 | 用途 |
|---|---|
| `wap/include/catalog.fun.php` | 分类、人工推荐、作品列表及输入校验，使用现有数据表 |
| `wap/include/shuku/index.php` | 分类入口与全部作品列表分流，兼容旧分页入口 |
| `wap/include/category/index.php` | 分类书城、更多推荐、全部作品入口，兼容分类别名和分页 |
| `wap/include/paihang/index.php` | 从分类书城进入排行时按分类筛选 |
| `wap/template/shuku.html` | 频道、分类封面网格、数量 |
| `wap/template/category.html` | 分类书城和四个推荐区 |
| `wap/template/catalog-book.html` | 封面、标题、简介、作者和字数 |
| `wap/template/catalog-list.html` | 推荐位更多、全部作品、状态筛选、排序和分页 |
| `wap/template/header_inner.html` | 分类页头及返回频道、排行榜分类导航 |
| `wap/template/paihang.html` | 切换榜单和周期时保留当前分类 |
| `wap/style/css/catalog-ui.css` | 分类和书城样式，仅作用于相应 WAP 页面 |
| `apps/admin/controller/content/ContentSortController.php` | 在既有栏目编辑入口增加 WAP 推荐读取和保存 |
| `apps/admin/view/default/content/contentsort.html` | WAP 推荐设置入口 |
| `apps/admin/view/default/content/waprecommend.html` | 四个推荐位及分类频道设置表单 |
| `apps/admin/view/default/css/waprecommend.css` | 推荐设置页面样式 |
| `apps/admin/view/default/js/waprecommend.js` | 选书搜索、顺序调整、移除和分页 |
| `tests/wap-catalog.php` | CLI 隔离验证；使用同一连接的临时表，不修改站点数据 |

未涉及渠道逻辑。PC 读者页面未修改，栏目管理新增 WAP 设置入口。没有数据库结构变更，无需执行 SQL；只有管理员保存推荐设置时，才会在已有 `ay_config` 表写入 `wap_cat_{分类编码}_{推荐位}` 配置记录。

## 本地验证

- 分类：`http://localhost:8080/wap/?pbcode/shuku/`
- 女生：`http://localhost:8080/wap/?pbcode/shuku/&channel=girl`
- 玄幻分类书城：`http://localhost:8080/wap/?22/`
- 分类全部作品：`http://localhost:8080/wap/?22/&view=all`
- 玄幻分类推荐设置：`http://localhost:8080/admin.php?p=/ContentSort/mod/scode/22/action/waprecommend`（需要已有栏目编辑权限）

已验证 9 个分类、频道切换、四个推荐区、更多、分页、完本筛选、分类排行以及旧分页和别名入口。浏览器检查宽度为 320、390、553、768 像素，无页面横向溢出或脚本错误；同时回归了首页、作品页和章节入口。

后台选书的搜索、添加、重复检测、移动、移除、分页在独立页面样本中验证；推荐读取与保存、错误提交校验、人工顺序、空推荐位、子分类范围在临时表中通过 `php tests/wap-catalog.php` 验证。没有登录真实后台并保存站点推荐数据。

修改的 PHP 和模板通过 PHP 7.4、PHP 8.3 语法检查；后台模板通过项目模板编译器检查，JS 通过 `node --check`。74 个受保护源码文件哈希与改动前一致。

## 更新与恢复

1. 更新前备份上表中现有文件；如已设置推荐位，同时备份 `ay_config` 中的 `wap_cat_` 配置记录。
2. 按原目录覆盖上表文件并上传新增文件。无需回退整个项目，也无需重新导入 WAP 归档。
3. 清理 `runtime/complile` 中的模板编译缓存；CSS、JS 引用已带版本号。刷新分类和后台栏目管理页面。
4. 在后台为各分类选择推荐书籍并保存；无需运行页面静态生成。

恢复时仅还原这次备份的文件，移除本次新增文件并清理模板编译缓存；旧代码会忽略新增的 `wap_cat_` 配置，可保留。没有数据库结构回滚步骤。

尚未在服务器部署，未执行服务器旧版本补丁验证。没有为缺少封面或推荐配置的旧数据虚构内容，使用现有封面处理和空状态。
