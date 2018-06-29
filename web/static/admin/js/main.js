/**
 * 配置requireJs
 */
define('jquery',function(){
    return jQuery;
});

requirejs.config({
    urlArgs: "r=" + (new Date()).getTime(),
    //baseUrl: '/static/admin/js/',
    paths: {
        'mods': '/static/admin/js/mods',
        'modules': '/static/admin/js/modules',
        'libs': '/static/libs',

        //插件
        'uploader': '/static/libs/uploader/jquery.dm-uploader'
    }
});



