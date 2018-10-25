
var cfg = {
    web_server_root: '/thinkphp-ozgweb/server/mgr/',
    web_root: '/thinkphp-ozgweb/mgr/',
    web_title: 'thinkphp-ozgweb后台管理系统'
}

var func = {
    get_rest_param(name) {
        var url = location.href.split("#");
        url = url[1];

        var params = url.split("/");
        for(var i = 0; i < params.length; i++) {
            if(params[i] == name) {
                var k = i + 1;
                if(params[k] != undefined) {
                    return params[k];
                }
                else {
                    return null;
                }
            }
        }

        return null;
    }
}

export {
    cfg,
    func
}
