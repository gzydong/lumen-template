# lumen-template

#### 项目介绍
lumen-template 是一个简洁的lumen 开发模板，使用 lumen7.0 编写而成。项目没有过度的进行业务功能开发，保留了 lumen 框架的简洁。

#### 软件架构
软件架构说明

#### 运行环境要求
- PHP 7.2+
- Mysql 5.7+
- Redis 3.0+

#### 基础功能模块
- 用户认证 —— 注册、登录、退出；
- 管理员权限管理

#### 安装教程

1. 克隆源代码
```bash
> git clone git@github.com:gzydong/lumen-template.git
```

2. 安装 Composer 依赖包
```bash
> cd lumen-template
> composer install
```

3. 赋予storage目录权限
```bash
> chmod -R 755 storage
```

4. 配置 .env 环境变量

5. 数据库迁移
```bash
> php artisan migrate 
```

6. 添加数据
```bash
> php artisan db:seed
```

#### 使用说明

1. xxxx
2. xxxx
3. xxxx

#### 参与贡献

1. Fork 本项目
2. 新建 Feat_xxx 分支
3. 提交代码
4. 新建 Pull Request
