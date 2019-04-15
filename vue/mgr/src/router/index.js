import Vue from 'vue';
import Router from 'vue-router';

Vue.use(Router);

export default new Router({
    routes: [
        {
            path: '/',
            redirect: '/dashboard'
        },
        {
            path: '/',
            component: resolve => require(['../components/common/Home.vue'], resolve),
            meta: { title: '自述文件' },
            children:[
                {
                    path: '/dashboard',
                    component: resolve => require(['../components/page/Dashboard.vue'], resolve),
                    meta: { title: '系统首页' }
                },
                {
                    path: '/data_cat/show',
                    component: resolve => require(['../components/page/data_cat/Show.vue'], resolve),
                    children: [
                        { path: "type/1", meta: { title: '分类列表' } },
                    ]
                },
                {
                    path: '/data/show',
                    component: resolve => require(['../components/page/data/Show.vue'], resolve),
                    children: [
                        { path: "type/1", meta: { title: '产品列表' } },
                        { path: "type/2", meta: { title: '新闻列表' } },
                    ]
                },
                {
                    path: '/art_single/get',
                    component: resolve => require(['../components/page/art_single/Get.vue'], resolve),
                    children: [
                        { path: "1", meta: { title: '关于我们' } },
                        { path: "2", meta: { title: '公司简介' } },
                        { path: "3", meta: { title: '人才招聘' } },
                        { path: "4", meta: { title: '解决方案' } },
                        { path: "5", meta: { title: '联系我们' } },
                        { path: "6", meta: { title: '联系我们(首页)' } },
                        { path: "7", meta: { title: '联系我们(内页)' } },
                        { path: "8", meta: { title: '页脚部分' } },
                    ]
                },
                {
                    path: '/user/show',
                    component: resolve => require(['../components/page/user/Show.vue'], resolve),
                    meta: { title: '用户列表' }
                },
                {
                    path: '/user/updatepwd',
                    component: resolve => require(['../components/page/user/UpdatePwd.vue'], resolve),
                    meta: { title: '修改密码' }
                },
                {
                    path: '/404',
                    component: resolve => require(['../components/page/404.vue'], resolve),
                    meta: { title: '404' }
                },
                {
                    path: '/403',
                    component: resolve => require(['../components/page/403.vue'], resolve),
                    meta: { title: '403' }
                }
            ]
        },
        {
            path: '/login',
            component: resolve => require(['../components/page/Login.vue'], resolve)
        },
        {
            path: '*',
            redirect: '/404'
        }
    ]
})
