# 山海书盟读者端改版交付说明

2026-11-21：按本机保存的联合阅读首页 HTML、分类页与作品详情 PDF 调整读者界面；完成后台登录验证码移除、首页静态生成修复和 Git 初始化。渠道配置、授权、内容接口及收入计算未修改，未执行数据库结构变更。

## 当前动态模式

根据后续要求，整个 PC/WAP 站点现在固定动态渲染。core/init.php 将站点请求的 AUTO_STATIC 固定为 0；位于 api/ 下的外部内容 API 继续使用原配置，避免改变渠道接口行为。index.php 和 wap/index.php 直接进入 CMS，不再读取 pc.html 或 wap/wap.html。前台自动生成调用、AJAX 页面生成入口和 Make 静态页面任务均已停用。

后台 /make 只保留站点地图和采集维护功能，不再提供首页、栏目、书籍、章节页面生成或模式切换。修改模板后清理 runtime/complile 模板编译缓存并刷新页面即可；无需 Make。旧生成文件保留在磁盘，但首页入口不再读取它们。未执行数据库变更。

## 本轮改动

| 文件 | 结果 |
|---|---|
| `template/tpl/html/index.html` | 首页大幅轮播、榜单和书库快捷入口、热门分类、更新与推荐列表，继续使用现有书籍数据 |
| `template/tpl/html/category-book.html`、`book.html` | 分类页面布局与响应式调整；作品详情补齐字数和章节数展示 |
| `template/tpl/html/header_reader.html`、`footer_reader.html` | 公共导航和资源版本更新 |
| `template/tpl/css/reader-home.css`、`reader-pc.css` | 首页、分类、详情的桌面及窄屏样式 |
| `template/tpl/js/reader.js`、`template/tpl/image/home-banner-01.jpg`、`home-banner-02.jpg` | 本地横幅与手动/自动切换，焦点、悬停和减少动态效果偏好控制；横幅链接进入现有书库 |
| `apps/admin/controller/IndexController.php`、`apps/admin/view/default/index.html`、`system/config.html`、`js/mylayui.js`、`common/foot.html` | 删除后台登录验证码及后台验证码开关；保留密码校验、登录失败锁定、Session 与权限机制 |
| `core/init.php`、`index.php`、`wap/index.php` | 站点请求固定动态模式，PC/WAP 首页不读取生成文件；外部 API 保留原模式 |
| `ajax.php`、`wap/ajax.php`、`make/include/make.fun.php`、`make/index.php`、`make/js/ajax.js` | 停止自动与手动静态页面生成，Make 页面仅保留地图和维护工具；拒绝切换到静态模式 |
| `tests/dynamic-mode.php` | 验证只允许动态模式、旧模式切换被拒绝、旧页面生成任务停用 |
| `.gitignore`、`开发前置要求.md`、`CHANGELOG.md` | 源码版本管理、渠道保护清单和交付记录；私有配置、口令、上传、备份、依赖与生成产物排除在提交之外 |

之前已存在的书城筛选、榜单、搜索、阅读设置、书架与作者页面保留在首次源码快照中。本次 Git 初始化不代表重新实现或重新验证了全部历史功能。

## 验证结果

- 本地实际服务：`http://127.0.0.1:8080`，容器 Apache / PHP 7.4.33；主机 CLI 为 PHP 8.3。
- 普通 `/` 与 `/wap/` 响应均由 PHP/CMS 动态路由返回，即使 pc.html 与 wap/wap.html 存在也不读取；首页新版横幅无需 `?upcache=1`。
- `/make` 模式列表只提供动态，后台页面生成单选项不存在；站点地图选项默认选中。动态切换请求成功，其他模式被拒绝，旧 PC/WAP 页面生成接口返回 HTTP 410。
- `php tests/dynamic-mode.php` 检查 Make 静态任务拒绝逻辑；模板更改不再执行页面生成。
- 旧生成文件保留在磁盘，PC/WAP 入口已改为直接执行动态路由，不要求 Web 进程写入页面文件。
- Playwright 在 1366、768、390px 检查 `/`、`/qihuan.html`、`/book001/`：新布局存在、无横向溢出；轮播按钮和图片加载通过；无页面 JavaScript 异常。
- 后台登录表单无验证码，正确密码可登录；浏览器确认 /make 只显示动态模式与地图维护选项。
- 11 个修改的 PHP/混合模板文件通过 `php -l`，两个修改的 JavaScript 文件通过 `node --check`。
- `php tests/dynamic-mode.php` 在主机 PHP 8.3 和容器 PHP 7.4 均通过。
- 本次续作开始与结束比较的 22 个渠道保护文件哈希一致；渠道配置、接口和收入口径没有修改，未调用外部渠道接口、提交授权或稿费表单。完整保护范围见前置要求第 2 节。
- 项目没有 `prettier` / `linc` 脚本或 Yarn 命令，采用上述针对性检查，没有引入项目依赖。

本次没有进行支付交易、全量 WAP 测试、作者发布写入和渠道提交回归，也没有真实服务器上一版本代码，因此不声称完成服务器基线补丁验证。首次提交从现有源码状态开始记录历史。

## 更新与回滚

1. 在现有服务器上备份待覆盖源码及私有配置，记录当前 Git 提交（已有历史时）。保留 `.gitignore` 中排除的数据库、站点、支付及渠道配置、上传和数据；单独克隆源码不能还原这些运行资料。
2. 发布源模板、脚本、样式及对应后端代码；不执行 SQL、不修改渠道配置。
3. 清理模板编译缓存并刷新首页、分类、书籍和章节页面；不运行 Make 页面生成，也不要求 Web 用户写入静态页面文件。
4. 检查普通首页及分类、详情、后台登录。当前读者 CSS 版本 `20261121a`、读者 JS 版本 `20261121b`、后台 JS 版本 `20261121a`。
5. 后续回滚使用 `git revert <待回退提交>` 并清理模板编译缓存；没有数据库回滚步骤。首次建立 Git 前的改动没有历史提交，恢复到更早版本需用服务器备份，不能假设 `HEAD~1` 存在。

后续提交使用清楚的标题和说明，记录“改了什么、为什么、验证结果、是否涉及渠道/数据库”。本地 Git 尚未配置远程仓库，也未推送。
