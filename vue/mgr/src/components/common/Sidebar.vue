<template>
    <div class="sidebar">
        <el-menu class="sidebar-el-menu" :default-active="onRoutes" :collapse="collapse" background-color="#324157"
            text-color="#bfcbd9" active-text-color="#20a0ff" unique-opened router>
            <template v-for="item in items">
                <template v-if="item.subs">
                    <el-submenu :index="item.index" :key="item.index">
                        <template slot="title">
                            <i :class="item.icon"></i><span slot="title">{{ item.title }}</span>
                        </template>
                        <template v-for="subItem in item.subs">
                            <el-submenu v-if="subItem.subs" :index="subItem.index" :key="subItem.index">
                                <template slot="title">{{ subItem.title }}</template>
                                <el-menu-item v-for="(threeItem,i) in subItem.subs" :key="i" :index="threeItem.index">
                                    {{ threeItem.title }}
                                </el-menu-item>
                            </el-submenu>
                            <el-menu-item v-else :index="subItem.index" :key="subItem.index">
                                {{ subItem.title }}
                            </el-menu-item>
                        </template>
                    </el-submenu>
                </template>
                <template v-else>
                    <el-menu-item :index="item.index" :key="item.index">
                        <i :class="item.icon"></i><span slot="title">{{ item.title }}</span>
                    </el-menu-item>
                </template>
            </template>
        </el-menu>
    </div>
</template>

<script>
    import bus from '../common/bus';
    export default {
        data() {
            return {
                collapse: false,
                items: [
                    {
                        icon: 'el-icon-lx-home',
                        index: '/dashboard',
                        title: '系统首页'
                    },
                    {
                        icon: 'el-icon-lx-cascades',
                        index: '1',
                        title: '产品分类',
                        subs: [
                            {
                                index: '/data_cat/show/type/1',
                                title: '分类列表'
                            }
                        ]
                    },
                    {
                        icon: 'el-icon-lx-copy',
                        index: '2',
                        title: '产品管理',
                        subs: [
                            {
                                index: '/data/show/type/1',
                                title: '产品列表'
                            }
                        ]
                    },
                    {
                        icon: 'el-icon-lx-copy',
                        index: '3',
                        title: '新闻管理',
                        subs: [
                            {
                                index: '/data/show/type/2',
                                title: '新闻列表'
                            }
                        ]
                    },
                    {
                        icon: 'el-icon-lx-newsfill',
                        index: '4',
                        title: '区域管理',
                        subs: [
                            {
                                index: '/art_single/get/1',
                                title: '关于我们'
                            },
                            {
                                index: '/art_single/get/2',
                                title: '公司简介'
                            },
                            {
                                index: '/art_single/get/3',
                                title: '人才招聘'
                            },
                            {
                                index: '/art_single/get/4',
                                title: '解决方案'
                            },
                            {
                                index: '/art_single/get/5',
                                title: '联系我们'
                            },
                            {
                                index: '/art_single/get/6',
                                title: '联系我们(首页)'
                            },
                            {
                                index: '/art_single/get/7',
                                title: '联系我们(内页)'
                            },
                            {
                                index: '/art_single/get/8',
                                title: '页脚部分'
                            }
                        ]
                    },
                    {
                        icon: 'el-icon-lx-friend',
                        index: '5',
                        title: '用户管理',
                        subs: [
                            {
                                index: '/user/show',
                                title: '用户列表'
                            }
                        ]
                    }
                ]
            }
        },
        computed:{
            onRoutes(){
                return this.$route.path.replace('/','');
            }
        },
        created(){
            // 通过 Event Bus 进行组件间通信，来折叠侧边栏
            bus.$on('collapse', msg => {
                this.collapse = msg;
            })
        }
    }
</script>

<style scoped>
    .sidebar{
        display: block;
        position: absolute;
        left: 0;
        top: 70px;
        bottom:0;
        overflow-y: scroll;
    }
    .sidebar::-webkit-scrollbar{
        width: 0;
    }
    .sidebar-el-menu:not(.el-menu--collapse){
        width: 250px;
    }
    .sidebar > ul {
        height:100%;
    }
</style>
