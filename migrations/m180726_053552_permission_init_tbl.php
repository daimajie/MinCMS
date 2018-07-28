<?php

use yii\db\Migration;

/**
 * Class m180726_053552_permission_init_tbl
 */
class m180726_053552_permission_init_tbl extends Migration
{
    const TBL_NAME = '{{%auth_item}}';
    const TBL_CHILD = '{{%auth_item_child}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addPermissions();
        $this->addRoles();
        $this->allotPermission();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete(self::TBL_NAME);
        $this->delete(self::TBL_CHILD);
    }

    /**
     * 添加后台所有权限
     */
    private function addPermissions(){
        $permission = [
            ['admin', '管理', 2],
            ['author', '作者', 2],
            ['admin/default/frame', '后台框架', 2],
            ['admin/default/index', '后台首页', 2],
            ['admin/default/error', '后台错误页', 2],

            ['comment/comment/*', '评论所有权限', 2],
            ['comment/comment/index', '评论列表', 2],
            ['comment/comment/update', '评论更新', 2],
            ['comment/comment/delete', '评论删除', 2],

            ['content/article/*', '文章所有权限', 2],
            ['content/article/upload', '文章图片上传', 2],
            ['content/article/index', '文章列表', 2],
            ['content/article/create', '文章创建', 2],
            ['content/article/update', '文章更新', 2],
            ['content/article/view', '文章详情', 2],
            ['content/article/delete', '文章删除', 2],
            ['content/article/put-recycle', '文章放置回收站', 2],
            ['content/article/draft', '草稿箱', 2],
            ['content/article/recycle', '回收站', 2],
            ['content/article/re-store', '回收站恢复文章', 2],
            ['content/article/check', '审核文章', 2],

            ['content/category/*', '分类所有权限', 2],
            ['content/category/upload', '分类图片上传', 2],
            ['content/category/search', '分类搜索', 2],
            ['content/category/index', '分类列表', 2],
            ['content/category/create', '分类创建', 2],
            ['content/category/view', '分类详情', 2],
            ['content/category/update', '分类更新', 2],
            ['content/category/delete', '分类删除', 2],
            ['content/category/batch-del', '分类批量删除', 2],

            ['content/tag/*', '标签所有权限', 2],
            ['content/tag/index', '标签列表', 2],
            ['content/tag/create', '标签创建', 2],
            ['content/tag/view', '标签详情', 2],
            ['content/tag/update', '标签更新', 2],
            ['content/tag/delete', '标签删除', 2],
            ['content/tag/ajax-delete', '在话题页删除标签', 2],
            ['content/tag/ajax-update', '在话题页编辑标签', 2],
            ['content/tag/batch-del', '标签批量删除', 2],
            ['content/tag/get-tags', '获取话题下所有标签', 2],

            ['content/topic/*', '话题所有权限', 2],
            ['content/topic/upload', '话题图片上传', 2],
            ['content/topic/search', '话题搜索', 2],
            ['content/topic/index', '话题列表', 2],
            ['content/topic/create', '话题创建', 2],
            ['content/topic/view', '话题详情', 2],
            ['content/topic/update', '话题更新', 2],
            ['content/topic/delete', '话题删除', 2],
            ['content/topic/batch-del', '话题批量删除', 2],

            ['data/log/*', '日志所有权限', 2],
            ['data/log/index', '日志列表', 2],
            ['data/log/view', '日志详情', 2],
            ['data/log/delete-all', '日志删除', 2],

            ['member/assign/*', '指派所有权限（为用户指派角色）', 2],
            ['member/assign/index', '指派列表（为用户指派角色）', 2],
            ['member/assign/create', '新建指派（为用户指派角色）', 2],
            ['member/assign/delete', '删除指派（为用户指派角色）', 2],
            ['member/assign/assign', '编辑指派（为用户指派角色）', 2],
            ['member/assign/get-roles', '获取所有角色（为用户指派角色）', 2],

            ['member/user/*', '用户所有权限', 2],
            ['member/user/search', '搜索用户', 2],
            ['member/user/index', '用户列表', 2],
            ['member/user/create', '用户创建', 2],
            ['member/user/view', '用户详情', 2],
            ['member/user/update', '用户更新', 2],
            ['member/user/delete', '用户删除', 2],

            ['rbac/item/*','权限角色所有权限', 2],
            ['rbac/item/search', '权限角色搜索', 2],
            ['rbac/item/index', '权限角色列表', 2],
            ['rbac/item/create', '权限角色创建', 2],
            ['rbac/item/view', '权限角色详情', 2],
            ['rbac/item/update', '权限角色更新', 2],
            ['rbac/item/delete', '权限角色删除', 2],
            ['rbac/item/assign', '为用户分配角色', 2],
            ['rbac/item/remove', '移除角色分配的角色', 2],

            ['rbac/rule/*', '规则所有权限', 2],
            ['rbac/rule/index', '规则列表', 2],
            ['rbac/rule/create', '规则创建', 2],
            ['rbac/rule/update', '规则更新', 2],
            ['rbac/rule/view', '规则详情', 2],
            ['rbac/rule/delete', '规则删除', 2],

            ['rbac/allot/*', '分配所有权限', 2],
            ['rbac/allot/index', '分配列表', 2],
            ['rbac/allot/create', '分配创建', 2],
            ['rbac/allot/update', '分配更新', 2],
            ['rbac/allot/delete', '分配删除', 2],
            ['rbac/allot/get-role-and-permission', '获取可分配角色和权限', 2],

            ['setting/friend/*', '友链所有权限', 2],
            ['setting/friend/index', '友链列表', 2],
            ['setting/friend/create', '友链创建', 2],
            ['setting/friend/update', '友链更新', 2],
            ['setting/friend/view', '友链查看', 2],
            ['setting/friend/delete', '友链删除', 2],
            ['setting/friend/batch-del', '友链批量删除', 2],

            ['setting/metadata/*', '元数据所有权限', 2],
            ['setting/metadata/setup', '设置元信息', 2],


        ];
        $this->batchInsert(self::TBL_NAME,['name','description','type'], $permission);
    }

    /**
     * 添加角色
     */
    private function addRoles(){
        $roles = [
            ['管理员', '管理员', 1],
            ['作者', '作者', 1],
        ];

        $this->batchInsert(self::TBL_NAME,['name','description','type'], $roles);

    }

    /**
     * 为角色分配权限
     */
    private function allotPermission(){
        $adminChild = [
            ['管理员','admin'],
            ['管理员','admin/default/frame'],
            ['管理员','admin/default/index'],
            ['管理员','admin/default/error'],

            ['管理员','comment/comment/*'],
            ['管理员','content/article/*'],
            ['管理员','content/category/*'],
            ['管理员','content/topic/*'],
            ['管理员','content/tag/*'],
            ['管理员','member/assign/*'],
            ['管理员','member/user/*'],
            ['管理员','rbac/item/*'],
            ['管理员','rbac/rule/*'],
            ['管理员','rbac/allot/*'],
            ['管理员','setting/friend/*'],
            ['管理员','setting/metadata/*'],
            ['管理员','data/log/*'],
        ];

        $authorChild = [
            ['作者','author'],
            ['作者','admin/default/frame'],
            ['作者','admin/default/index'],
            ['作者','admin/default/error'],

            //标签所有权限
            ['作者','content/tag/ajax-delete'],
            ['作者','content/tag/ajax-update'],
            ['作者','content/tag/get-tags'],

            //文章创建  查看（修改 删除）
            ['作者','content/article/upload'],
            ['作者','content/article/index'],
            ['作者','content/article/create'],
            //['作者','content/article/view'],
            ['作者','content/article/draft'],
            ['作者','content/article/recycle'],

            //话题创建  查看（修改 删除）
            ['作者','content/category/search'],
            ['作者','content/topic/search'],
            ['作者','content/topic/upload'],
            ['作者','content/topic/index'],
            ['作者','content/topic/create'],
            //['作者','content/topic/view'],

        ];
        $this->batchInsert(self::TBL_CHILD,['parent','child'], $adminChild);
        $this->batchInsert(self::TBL_CHILD,['parent','child'], $authorChild);



    }
}
