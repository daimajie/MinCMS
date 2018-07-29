# MinCMS

包含功能：
###前台

注册，登录，找回密码，个人中心，用户信息修改（修改头像，修改密码，修改邮箱），
作者中心，喜欢，收藏，评论，回复，作者申请

###后台

分类管理，话题管理，标签管理（每个话题下最多n个标签），文章管理(关联话题和标签)，
用户管理，指派角色，rbac权限管理，友链管理，元数据，评论回复管理，日志管理。
(备份和缓存管理未完成)

###安装步骤

1.下载
>git clone https://github.com/daimajie/MinCMS.git cms.com

2.安装依赖
>composer install

3.配置虚拟主机（这里我使用的是lnmp包 操作如下）
>根据提示自定义配置虚拟主机， 指向项目根目录

>lnmp vhost add

4.配置nginx web目录为 /项目目录/web
>/home/wwwroot/cms.com/web

5.解除web目录访问项目目录的权限 要么会提示访问错误(使用lnmp 自带的工具)
>lnmp安装包/tools/remove_open_basedir_restriction.sh

6.配置hosts系统文件,添加如下ip指向
> 127.0.0.1  www.domain.com domain.com

7.配置

>7.1.创建数据库 配置项目配置文件db.php,

>7.2.可选择配置 params.php

>7.3.配置web.php 邮箱账户密码

~~~
return [
     'class' => 'yii\db\Connection',
     'dsn' => 'mysql:host=localhost;dbname=DBname',
     'username' => 'root',
     'password' => 'root',
     'charset' => 'utf8mb4',
];
~~~

8.执行迁移脚本 初始化数据表及权限数据
>./yii migrate

9.添加权限规则
>./yii author-rule/add-rule

10.添加后台用户 管理员和作者(可创建任意多个)
>./yii init-member/add-admin  根据提示添加管理员
>./yii init-member/add-author 根据提示添加作者

##至此安装程序完成 
使用刚才创建的用户登录站点进行测试。
