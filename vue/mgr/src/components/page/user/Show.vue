<template>
    <div class="table">
        <div class="crumbs">
            <el-breadcrumb separator="/">
                <el-breadcrumb-item><i class="el-icon-lx-cascades"></i> 用户管理</el-breadcrumb-item>
                <el-breadcrumb-item>用户列表</el-breadcrumb-item>
            </el-breadcrumb>
            <div class="btn-right">
                <el-button icon="el-icon-plus" circle @click="handleAdd"></el-button>
            </div>
        </div>
        <div class="container">
            <el-table :data="maindata" border class="table" ref="multipleTable" @selection-change="handleSelectionChange">
                <el-table-column type="selection" width="55" align="center"></el-table-column>
                <el-table-column prop="name" label="用户名" width="200"></el-table-column>
                <el-table-column prop="add_time_s" label="添加时间" width="200"></el-table-column>
                <el-table-column prop="err_login" label="错误登录次数" width="150"></el-table-column>
                <el-table-column label="操作" align="center">
                    <template slot-scope="scope">
                        <!--<el-button type="text" icon="el-icon-edit" @click="handleEdit(scope.$index, scope.row)">编辑</el-button>-->
                        <el-button type="text" icon="el-icon-delete" class="red" @click="handleDelete(scope.$index, scope.row)">删除</el-button>
                    </template>
                </el-table-column>
            </el-table>
            <div class="pagination">
                <el-pagination background @current-change="handleCurrentChange" layout="prev, pager, next" :page-count="page_count">
                </el-pagination>
            </div>
            <div class="many">
                <el-button type="danger" plain @click="handleDeleteMany">删除</el-button>
            </div>
        </div>

        <!-- 编辑弹出框 -->
        <el-dialog :title="editTitle" :visible.sync="editVisible" width="50%">
            <el-form :model="mainform" :rules="validform" ref="mainform" label-width="90px">
                <el-form-item label="用户名" prop="name">
                    <el-input v-model="mainform.name"></el-input>
                </el-form-item>
                <el-form-item label="密码" prop="pwd">
                    <el-input v-model="mainform.pwd"></el-input>
                </el-form-item>
            </el-form>
            <span slot="footer" class="dialog-footer">
                <el-button @click="editVisible = false">取 消</el-button>
                <el-button type="primary" @click="saveEdit">确 定</el-button>
            </span>
        </el-dialog>

        <!-- 删除提示框 -->
        <el-dialog title="提示" :visible.sync="delVisible" width="300px" center>
            <div class="del-dialog-cnt">删除不可恢复，是否确定删除？</div>
            <span slot="footer" class="dialog-footer">
                <el-button @click="delVisible = false">取 消</el-button>
                <el-button type="primary" @click="deleteRow">确 定</el-button>
            </span>
        </el-dialog>

    </div>
</template>

<script>
    import { cfg } from '../../common/common.js';

    export default {
        name: 'user_show',
        data() {
            return {
                maindata: [],
                page: 1,
                page_count: 1,
                page_size: 1,
                total: 1,
                selected_objs: [],
                editTitle: "",
                editVisible: false,
                delVisible: false,
                doMany: false,
                mainform: {
                    name: '',
                    pwd: ''
                },
                validform: {
                    name: [
                        { required: true, message: '请输入用户名', trigger: 'blur' }
                    ],
                    pwd: [
                        { required: true, message: '请输入密码', trigger: 'blur' }
                    ]
                },
                idx: -1
            }
        },
        created() {
            document.title = cfg.web_title;
        },
        mounted() {
            this.loadData();
        },
        methods: {
            // 分页导航
            handleCurrentChange(val) {
                this.page = val;

                this.loadData();
            },
            loadData() {
                this.maindata.length = 0;

                var that = this;
                that.$axios.get(cfg.web_server_root + "user/show?page=" + that.page).then((response) => {
                    if(response.data.code == 0) {
                        that.maindata = response.data.data.list;
                        that.page = response.data.data.page;
                        that.page_count = response.data.data.page_count;
                        that.page_size = response.data.data.page_size;
                        that.total = response.data.data.total;
                    }
                    else {
                        that.$router.push('/login');
                    }
                }).catch((error) => {
                    that.$alert(error, '提示', {
                        confirmButtonText: '确定',
                        type: 'error',
                    });
                });

            },
            handleAdd() {
                this.editTitle = "添加用户";
                this.editVisible = true;
            },
            handleEdit(index, row) {
                this.idx = index;
                const item = this.maindata[index];

                this.editTitle = "编辑用户";
                this.doMany = false;
                this.editVisible = true;
            },
            handleDelete(index, row) {
                this.idx = index;
                this.doMany = false;
                this.delVisible = true;
            },
            handleSelectionChange(val) {
                //val为已经选定的多个maindata下的对象
                this.selected_objs = val;
            },
            // 保存编辑
            saveEdit() {
                this.editVisible = false;

                var that = this;

                that.$refs['mainform'].validate((valid) => {
                    if (valid) {
                        that.$axios.post(cfg.web_server_root + "user/add", {
                            name: that.mainform.name,
                            pwd: that.mainform.pwd
                        }).then((response) => {
                            if(response.data.code == 0) {
                                that.mainform.name = "";
                                that.mainform.pwd = "";
                                that.page = 1;
                                that.loadData();
                                that.$message({
                                    message: response.data.msg,
                                    type: 'success'
                                });
                            }
                            else {
                                that.$message({
                                    message: response.data.msg,
                                    type: 'error'
                                });
                            }
                        }).catch((error) => {
                            that.$alert(error, '提示', {
                                confirmButtonText: '确定',
                                type: 'error'
                            });
                        });
                    }
                    else {
                        that.$alert('请正确输入完整信息', '提示', {
                            confirmButtonText: '确定',
                            type: 'error'
                        });
                        return false;
                    }
                });

            },
            // 确定删除
            deleteRow() {
                var that = this;

                var url = null;
                if(that.doMany) {
                    //删除多条数据
                    if(that.selected_objs.length == 0) {
                        that.$message({
                            message: "请选择要删除的用户",
                            type: 'error'
                        });
                        this.delVisible = false;
                        return false;
                    }
                    else {
                        url = cfg.web_server_root + "user/del?ids=";
                        for(var i = 0; i < that.selected_objs.length; i++) {
                            url += that.selected_objs[i].id;
                            if(i + 1 < that.selected_objs.length) {
                                url += ",";
                            }
                        }
                    }
                }
                else {
                    //删除单条数据
                    url = cfg.web_server_root + "user/del?ids=" + that.maindata[that.idx].id;
                }
                that.$axios.get(url).then((response) => {
                    that.delVisible = false;
                    if(response.data.code == 0) {
                        if(that.doMany) {
                            for(var i = 0; i < that.selected_objs.length; i++) {
                                for(var j = 0; j < that.maindata.length; j++) {
                                    if(that.selected_objs[i].id == that.maindata[j].id) {
                                        that.maindata.splice(j, 1);
                                    }
                                }
                            }
                            that.selected_objs.length = 0;
                        }
                        else {
                            that.maindata.splice(that.idx, 1);
                        }
                        that.$message({
                            message: response.data.msg,
                            type: 'success'
                        });
                    }
                    else {
                        that.$message({
                            message: response.data.msg,
                            type: 'error'
                        });
                    }
                }).catch((error) => {
                    that.$alert(error, '提示', {
                        confirmButtonText: '确定',
                        type: 'error',
                    });
                });

            },
            handleDeleteMany() {
                this.doMany = true;
                this.delVisible = true;
            }
        }
    }
</script>

<style scoped>
    .btn-right {
        float: right;
        position: relative;
        top: -25px;
    }
    .handle-box {
        margin-bottom: 20px;
    }
    .handle-select {
        width: 120px;
    }
    .handle-input {
        width: 300px;
        display: inline-block;
    }
    .del-dialog-cnt {
        font-size: 16px;
        text-align: center
    }
    .table {
        width: 100%;
        font-size: 14px;
    }
    .red {
        color: #ff0000;
    }
    .many {
        margin-top: -50px;
    }
</style>
