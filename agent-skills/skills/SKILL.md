---
name: skills-index
description: Skills 技能索引 - 提供项目技能管理和创建指导
---

# Skills 技能索引

本目录包含项目所需的各类技能，遵循 Claude Code Agent Skills 规范。

## 可用技能

### skill-creator

Claude 官方 Skill 创建工具，用于创建新的自定义技能。

**功能：**
- 初始化新 skill 目录结构
- 验证 skill 格式是否符合规范
- 打包 skill 为可分发的 .skill 文件

**脚本位置：** `.agent/skills/skill-creator/scripts/`

**使用方法：**

```bash
# 创建新 skill
python .agent/skills/skill-creator/scripts/init_skill.py <skill-name> --path .agent/skills/

# 验证 skill
python .agent/skills/skill-creator/scripts/quick_validate.py .agent/skills/<skill-name>

# 打包 skill
python .agent/skills/skill-creator/scripts/package_skill.py .agent/skills/<skill-name>
```

### laravel-dev

Laravel 12 开发环境配置，记录本项目的特定环境配置、依赖版本和开发命令。

**内容包括：**
- 项目信息（位置、版本）
- 环境要求（PHP、Composer、Node.js）
- 已安装依赖及版本
- 数据库配置
- 常用开发命令
- phpstudy PRO 配置要点

## 添加新技能

如需添加新技能，请参考 skill-creator 工具的 SKILL.md 文档。
