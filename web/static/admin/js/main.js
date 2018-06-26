/**
 * 配置requireJs
 */
define('jquery',function(){
    return jQuery;
});

requirejs.config({
    baseUrl: '/static/admin/js',
    paths: {
        'mods':'mods',
        'modules':'modules',
        // 'libs':'libs'
    }
});



