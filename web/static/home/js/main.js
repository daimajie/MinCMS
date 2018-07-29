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

        //插件映射
        'uploader': '/static/libs/uploader/jquery.dm-uploader',
        'jSmart': '/static/libs/jSmart/jSmart',
        'simplemde': '/static/libs/simplemde/simplemde',
        'simplemdeCss': '/static/libs/simplemde/simplemde.min',
        'css': '/static/admin/js/mods/css',
    },
    map: {
        '*': {
            'css': 'css'
        }
    },

    shim: {
        /*'amazeui':{
            deps: ['jquery','css!libs/css/amazeui.min','css!style/common','css!style/footer']
        },*/
        'simplemde':{
            deps:['css!simplemdeCss']
        },
    }
});



