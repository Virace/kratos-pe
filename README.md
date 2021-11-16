<p align="center">
<img src="https://cdn.jsdelivr.net/gh/virace/kratos-pe@main/assets/img/options/about.png">
</p>

<p align="center">
<img src="https://img.shields.io/badge/php-%3E%3D7.0-777BB4?style=flat-square&logo=php&logoColor=#777BB4">
<img src="https://img.shields.io/badge/wordpress-v5.9%20tested-21759B?style=flat-square&logo=wordpress">
<a href="https://www.jsdelivr.com/package/gh/virace/kratos-pe" target="_blank"><img src="https://data.jsdelivr.com/v1/package/gh/virace/kratos-pe/badge"></a>
<img src="https://img.shields.io/github/license/virace/kratos-pe?color=%234c1&style=flat-square">
</p>

# Kratos-PE

基于 [kratos](https://github.com/vtrois/kratos) 二次开发, 适当精简并且加入一些新功能. 个人使用版本.

- [介绍](#介绍)
- [安装](#安装)
- [维护者](#维护者)
- [感谢](#感谢)
- [许可证](#许可证)

### 介绍

- 新增功能
    - 体验增强——**更多动画**, 代码来源开源库[animate.css](https://github.com/animate-css/animate.css)
    - 体验增强——**PJAX**( pushState + ajax ),
      代码借鉴 [xb2016/kratos-pjax](https://github.com/xb2016/kratos-pjax/blob/master/static/js/pjax.js)
    - 文章增强——**代码高亮**, 基于[highlight.js](https://highlightjs.org/)
    - 文章增强——**图片灯箱**, 基于[lightgallery.js](https://sachinchoolur.github.io/lightgallery.js/)
    - 文章增强——**文章目录**, 部分代码借鉴[WPJAM BASIC](https://wordpress.org/plugins/wpjam-basic/)
    - 后台文章增强——**文章快速复制**, 代码来源于插件[Duplicate Page](https://wordpress.org/plugins/duplicate-page/)
    - 后台媒体库增强——**从URL插入**,
      代码来源于插件[External Media without Import](https://github.com/zzxiang/external-media-without-import)
    - 古腾堡增强——**新增块**: 提醒、分组、手风琴、Bilibili嵌入
    - 主题设置——**更多的颜色设置**
    - 移动端重写
    - 等等

- 优化内容
    - 精简Bootstrap, 基于Chromium内核浏览器提供的"Coverage"覆盖范围检查功能精简其css文件
    - 精简Bootstrap, 基于4.5.3版本重新编译, 仅保留scrollspy功能
    - 去除layer, 重写弹出层
    - 整合js、css文件, 减少请求
    - 等等

- 待解决的问题
    - 图片资源文件未整理
    - 古腾堡块, 目前不推荐使用(才接触块开发, 仅仅是能用的状态, 如果目前使用后续更新可能会无法解析原文章块, 但不会像短代码一样失效)
    - 图片灯箱与图片延迟加载插件冲突, 比如Autoptimize中的图片延迟加载功能
    - ~~评论表情部分替换为高清svg, 但因资源加载过多此处待优化~~

### 安装

**要求:**

- Releases下载最新版本, 在后台直接添加主题即可
- 或在主题目录直接克隆本库

### 维护者

**Virace**

- blog: [孤独的未知数](https://x-item.com)

### 感谢

- [@vtrois](https://www.vtrois.com), **kratos**主题作者
- [@小白-白](https://github.com/xb2016), **kratos-pjax**主题作者
- [@mndpsingh287](https://profiles.wordpress.org/mndpsingh287/), Duplicate Page插件作者
- [@Zhixiang Zhu](https://github.com/zzxiang), **External Media without Import**插件作者
- [@我爱水煮鱼](https://blog.wpjam.com/project/wpjam-basic/), **WPJAM-Basic**插件作者
- 其他开源库的支持, [animate.css](https://github.com/animate-css/animate.css)
  、[highlight.js](https://github.com/highlightjs/highlight.js)
  、[lightgallery.js](https://github.com/sachinchoolur/lightgallery.js) 、[bootstrap](https://github.com/twbs/bootstrap)
- 以及**JetBrains**提供开发环境支持

  <a href="https://www.jetbrains.com/?from=kratos-pe" target="_blank"><img src="https://cdn.jsdelivr.net/gh/virace/kratos-pe@main/jetbrains.svg"></a>

### 许可证

[GPL-3.0](LICENSE)