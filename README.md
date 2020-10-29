# Lumen Framework Template
 
#### 项目介绍
lumen-template 是一个简洁的lumen 开发模板，使用 lumen7.0 编写而成。

#### 环境要求
- PHP 7.2.5+
- Mysql 5.7+
- Redis 3.0+

#### 功能模块
- 用户认证 —— 注册、登录、退出；

#### 安装流程
克隆源代码
```bash
> git clone git@github.com:gzydong/lumen-template.git
```

安装 Composer 依赖包
```bash
> cd lumen-template
> composer install
```

赋予storage目录权限
```bash
> chmod -R 755 storage
```

拷贝 .env 文件
```bash
> cp .env.example .env
```

数据库迁移
```bash
> php artisan migrate 
```

添加数据
```bash
> php artisan db:seed
```
