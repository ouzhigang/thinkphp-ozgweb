<template>
    <div>
        <div class="crumbs">
            <el-breadcrumb separator="/">
                <el-breadcrumb-item><i class="el-icon-lx-cascades"></i> 产品分类</el-breadcrumb-item>
                <el-breadcrumb-item>分类列表</el-breadcrumb-item>
            </el-breadcrumb>
            <div class="btn-right">
                <el-button icon="el-icon-plus" circle @click="handleAdd"></el-button>
            </div>
        </div>
        <div class="container">
            <el-tree :data="maindata" :props="defaultProps" node-key="id" :expand-on-click-node="false" default-expand-all>
                <span class="custom-tree-node" slot-scope="{ node, data }">
                    <span>{{ node.label }}</span>
                    <span>
                        <el-button type="text" size="mini" @click="() => append(data)">添加子节点</el-button>
                        <el-button type="text" size="mini" @click="() => edit(node, data)">编辑</el-button>
                        <el-button type="text" size="mini" @click="() => remove(node, data)">删除</el-button>
                    </span>
                </span>
            </el-tree>
        </div>

        <!-- 编辑弹出框 -->
        <el-dialog :title="editTitle" :visible.sync="editVisible" width="50%">
            <el-form :model="mainform" :rules="validform" ref="mainform" label-width="90px">
                <el-form-item label="名称" prop="name">
                    <el-input v-model="mainform.name"></el-input>
                </el-form-item>
                <el-form-item label="排序">
                    <el-input-number v-model="mainform.sort"></el-input-number>
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
import { cfg, func } from '../../common/common.js';

export default {
    data() {
        return {
            id: 1000,
            maindata: [],
            editTitle: "",
            editVisible: false,
            delVisible: false,
            defaultProps: {
                children: 'children',
                label: 'name'
            },
            to_delete_id: 0,
            mainform: {
                id: 0,
                name: '',
                parent_id: 0,
                sort: 0,
                type: 0
            },
            validform: {
                name: [
                    { required: true, message: '请输入名称', trigger: 'blur' }
                ]
            }
        }
    },
    methods: {
        loadData() {
            this.maindata.length = 0;

            var that = this;
            that.mainform.type = func.get_rest_param("type");
            that.$axios.get(cfg.web_server_root + "data_class/show?type=" + that.mainform.type).then(function (response) {
                if(response.data.code == 0) {
                    that.maindata = response.data.data;
                }
                else {
                    that.$alert(error, '提示', {
                        confirmButtonText: '确定',
                        type: 'error',
                    });
                }
            }).catch(function (error) {
                that.$alert(error, '提示', {
                    confirmButtonText: '确定',
                    type: 'error',
                });
            });
        },
        handleAdd() {
            var that = this;

            that.editTitle = "添加顶级分类";
            that.mainform.id = 0;
            that.mainform.name = "";
            that.mainform.sort = 0;
            that.mainform.parent_id = 0;
            that.editVisible = true;

        },
        append(data) {
            var that = this;
            that.editTitle = data.name + " - 添加子分类";
            that.mainform.id = 0;
            that.mainform.name = "";
            that.mainform.sort = 0;
            that.mainform.parent_id = data.id;
            that.editVisible = true;

        },
        edit(node, data) {
            var that = this;
            that.editTitle = "编辑分类";
            that.mainform.id = data.id;
            that.mainform.name = data.name;
            that.mainform.sort = data.sort;
            that.mainform.parent_id = data.parent_id;
            that.editVisible = true;
        },
        remove(node, data) {
            this.to_delete_id = data.id;
            this.delVisible = true;
        },
        saveEdit() {
            this.editVisible = false;

            var that = this;

            that.$refs['mainform'].validate((valid) => {
                if (valid) {
                    var formdata = {
                        name: that.mainform.name,
                        sort: that.mainform.sort,
                        parent_id: that.mainform.parent_id,
                        type: that.mainform.type,
                    };
                    if(that.mainform.id != 0) {
                        formdata.id = that.mainform.id;
                    }
                    that.$axios.post(cfg.web_server_root + "data_class/add", formdata).then(function (response) {
                        if(response.data.code == 0) {
                            that.mainform.id = 0;
                            that.mainform.name = "";
                            that.mainform.sort = 0;
                            that.mainform.parent_id = 0;
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
                    }).catch(function (error) {
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
        deleteRow() {
            var that = this;

            var url = cfg.web_server_root + "data_class/del?id=" + that.to_delete_id;
            that.$axios.get(url).then(function (response) {
                that.delVisible = false;
                if(response.data.code == 0) {
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
            }).catch(function (error) {
                that.$alert(error, '提示', {
                    confirmButtonText: '确定',
                    type: 'error',
                });
            });

        }

    },
    created() {
        document.title = cfg.web_title;
    },
    mounted() {
        this.loadData();
    },
    watch: {
        '$route' (to, from) {
            this.loadData()
        },
    }
}
</script>

<style scoped>
    .btn-right {
        float: right;
        position: relative;
        top: -25px;
    }
    .custom-tree-node {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 14px;
        padding-right: 8px;
    }
</style>
