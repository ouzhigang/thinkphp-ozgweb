<template>
    <div class="table">
        <div class="crumbs">
            <el-breadcrumb separator="/">
                <el-breadcrumb-item><i class="el-icon-lx-cascades"></i> {{ $route.meta.title.replace('列表', '') }}管理</el-breadcrumb-item>
                <el-breadcrumb-item>{{ $route.meta.title }}</el-breadcrumb-item>
            </el-breadcrumb>
            <div class="btn-right">
                <el-button icon="el-icon-search" circle @click="handleSearch"></el-button>
                <el-button icon="el-icon-plus" circle @click="handleAdd"></el-button>
            </div>
        </div>
        <div class="container">
            <el-table :data="maindata" border class="table" ref="multipleTable" @selection-change="handleSelectionChange">
                <el-table-column type="selection" width="55" align="center"></el-table-column>
                <el-table-column prop="name" label="名称" width="200"></el-table-column>
                <el-table-column prop="dc_name" label="分类" width="100"></el-table-column>
                <el-table-column prop="hits" label="点击" width="100"></el-table-column>
                <el-table-column prop="add_time_s" label="时间" width="200"></el-table-column>
                <el-table-column label="操作" align="center">
                    <template slot-scope="scope">
                        <el-button type="text" icon="el-icon-edit" @click="handleEdit(scope.$index, scope.row)">编辑</el-button>
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
        <el-dialog :title="editTitle" :visible.sync="editVisible" width="90%">
            <el-form :model="mainform" :rules="validform" ref="mainform" label-width="90px">
                <el-form-item label="名称" prop="name">
                    <el-input v-model="mainform.name"></el-input>
                </el-form-item>
                <el-form-item label="分类" v-if="mainform.type == 1">
                    <el-button type="primary" @click="dataClassSelect('add')" plain>选择</el-button>
                    <span v-if="mainform.data_class_id != 0" v-text="data_class_name"></span>
                </el-form-item>
                <el-form-item label="排序">
                    <el-input-number v-model="mainform.sort"></el-input-number>
                </el-form-item>
                <el-form-item label="图片" v-if="mainform.type == 1">
                    <el-upload
                        :action="upload_url"
                        :on-preview="uploadPreview"
                        :on-remove="uploadRemove"
                        :before-remove="uploadBeforeRemove"
                        :limit="upload_limit"
                        :on-exceed="uploadExceed"
                        :on-success="uploadSuccess"
                        :file-list="mainform.picture"
                        drag>
                        <i class="el-icon-upload"></i>
                        <div class="el-upload__text">将文件拖到此处，或<em>点击上传</em></div>
                        <div slot="tip" class="el-upload__tip">只能上传jpg/png文件，且不超过10M</div>
                    </el-upload>
                </el-form-item>
                <el-form-item label="显示">
                    <div>
                        <el-checkbox label="首页显示" v-model="mainform.is_index_show"></el-checkbox>
                        <el-checkbox label="顶部显示" v-model="mainform.is_index_top" v-if="mainform.type == 2"></el-checkbox>
                        <el-checkbox label="推荐" v-model="mainform.recommend"></el-checkbox>
                    </div>
                </el-form-item>
                <el-form-item label="内容">
                    <quill-editor ref="myTextEditor" v-model="mainform.content" :options="editorOption"></quill-editor>
                </el-form-item>
            </el-form>
            <span slot="footer" class="dialog-footer">
                <el-button @click="editVisible = false">取 消</el-button>
                <el-button type="primary" @click="saveEdit">确 定</el-button>
            </span>
        </el-dialog>

        <el-dialog title="选择分类" :visible.sync="dataClassVisible" width="60%">
            <el-tree :data="data_class_data" :props="defaultProps" node-key="id" :expand-on-click-node="false" default-expand-all @node-click="handleNodeClick">
            </el-tree>
            <span slot="footer" class="dialog-footer">
                <el-button @click="dataClassVisible = false">取 消</el-button>
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

        <!-- 搜索弹出框 -->
        <el-dialog title="搜索条件" :visible.sync="searchVisible" width="50%">
            <el-form label-width="90px">
                <el-form-item label="搜索名称">
                    <el-input v-model="k_name"></el-input>
                </el-form-item>
                <el-form-item label="分类" v-if="mainform.type == 1">
                    <el-button type="primary" @click="dataClassSelect('search')" plain>选择</el-button>
                    <span v-if="k_data_class_id != 0" v-text="k_data_class_name"></span>
                </el-form-item>
            </el-form>
            <span slot="footer" class="dialog-footer">
                <el-button type="primary" plain @click="searchClear">清 空</el-button>
                <el-button @click="searchCancel">取 消</el-button>
                <el-button type="primary" @click="search">确 定</el-button>
            </span>
        </el-dialog>
    </div>
</template>

<script>
    import 'quill/dist/quill.core.css';
    import 'quill/dist/quill.snow.css';
    import 'quill/dist/quill.bubble.css';
    import { quillEditor } from 'vue-quill-editor';

    import { cfg, func } from '../../common/common.js';

    export default {
        name: 'data_show',
        data() {
            return {
                maindata: [],
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
                dataClassVisible: false,
                searchVisible: false,
                data_class_select_type: "",
                k_name: "",
                k_data_class_id: 0,
                k_data_class_name: "",
                mainform: {
                    id: 0,
                    name: '',
                    data_class_id: 0,
                    sort: 0,
                    is_index_show: false,
                    is_index_top: false,
                    recommend: false,
                    picture: [],
                    content: "",
                    type: 0
                },
                validform: {
                    name: [
                        { required: true, message: '请输入名称', trigger: 'blur' }
                    ]
                },
                data_class_data: [],
                defaultProps: {
                    children: 'children',
                    label: 'name'
                },
                data_class_name: "",
                upload_url: cfg.web_server_root + "data/upload",
                upload_limit: 300,
                editorOption: {
                    placeholder: '请输入内容',
                    modules: {
                        toolbar: [
                            [ 'background', 'bold', 'color', 'font', 'code', 'italic', 'link', 'size', 'strike', 'script', 'underline', 'blockquote', 'header', 'indent', 'list', 'align', 'direction', 'code-block', 'formula', 'video', 'clean' ]
                        ]
                    }
                },
                idx: -1
            }
        },
        watch: {
            '$route' (to, from) {
                this.page = 1;
                this.loadData();
            },
        },
        mounted() {
            this.loadData();

        },
        components: {
            quillEditor
        },
        methods: {
            //quillEditor默认的
            onEditorChange({ editor, html, text }) {
                this.mainform.content = html;
            },
            // 分页导航
            handleCurrentChange(val) {
                this.page = val;

                this.loadData();
            },
            loadData() {
                this.mainform.type = func.get_rest_param("type");

                var that = this;
                var url = cfg.web_server_root + "data/show?type=" + this.mainform.type + "&page=" + that.page;

                if(that.k_name != "") {
                    url += "&k_name=" + encodeURI(that.k_name);
                }
                if(that.k_data_class_id != 0) {
                    url += "&k_data_class_id=" + that.k_data_class_id;
                }

                that.$axios.get(url).then(function (response) {
                    that.maindata = response.data.data.list;
                    that.page = response.data.data.page;
                    that.page_count = response.data.data.page_count;
                    that.page_size = response.data.data.page_size;
                    that.total = response.data.data.total;
                }).catch(function (error) {
                    that.$alert(error, '提示', {
                        confirmButtonText: '确定',
                        type: 'error',
                    });
                });

            },
            handleSearch() {
                this.searchVisible = true;
            },
            search() {
                this.searchVisible = false;
                this.page = 1;
                this.loadData();
            },
            searchClear() {
                this.k_name = "";
                this.k_data_class_id = 0;
                this.k_data_class_name = "";
            },
            searchCancel() {
                this.searchVisible = false;
            },
            handleAdd() {
                this.editTitle = "添加产品";
                this.mainform.id = 0;
                this.mainform.name = "";
                this.mainform.data_class_id = 0;
                this.mainform.sort = 0;
                this.mainform.is_index_show = false;
                this.mainform.is_index_top = false;
                this.mainform.recommend = false;
                this.mainform.content = "";
                this.mainform.picture = [],
                this.editVisible = true;

            },
            handleEdit(index, row) {
                this.idx = index;
                const data = this.maindata[index];

                var that = this;
                that.editTitle = "编辑" + that.$route.meta.title.replace('列表', '');
                that.mainform.id = data.id;
                that.mainform.name = data.name;
                that.mainform.data_class_id = data.data_class_id;
                that.mainform.sort = data.sort;
                that.mainform.is_index_show = data.is_index_show == "1" ? true : false;
                that.mainform.is_index_top = data.is_index_top == "1" ? true : false;
                that.mainform.recommend = data.recommend == "1" ? true : false;
                that.mainform.content = data.content;

                for(var i in data.picture) {
                    this.mainform.picture.push({
                        name: data.picture[i],
                        url: cfg.web_root + "../static/upload/" + data.picture[i]
                    });
                }

                that.editVisible = true;

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
                        var formdata = {
                            name: that.mainform.name,
                            data_class_id: that.mainform.data_class_id,
                            sort: that.mainform.sort,
                            is_index_show: that.mainform.is_index_show,
                            is_index_top: that.mainform.is_index_top,
                            recommend: that.mainform.recommend,
                            content: that.mainform.content,
                            type: that.mainform.type,
                        };

                        if(that.mainform.picture.length > 0) {
                            formdata.picture = [];
                            for(var i in that.mainform.picture) {
                                formdata.picture.push(that.mainform.picture[i].name);
                            }
                        }

                        if(that.mainform.id != 0) {
                            formdata.id = that.mainform.id;
                        }
                        that.$axios.post(cfg.web_server_root + "data/add", formdata).then(function (response) {
                            if(response.data.code == 0) {
                                that.mainform.id = 0;
                                that.mainform.name = "";
                                that.mainform.data_class_id = 0;
                                that.mainform.sort = 0;
                                that.mainform.is_index_show = false;
                                that.mainform.is_index_top = false;
                                that.mainform.recommend = false;
                                that.mainform.content = "";
                                that.mainform.picture = [];

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
            // 确定删除
            deleteRow() {
                var that = this;

                var url = null;

                if(that.doMany) {
                    //删除多条数据
                    if(that.selected_objs.length == 0) {
                        that.$message({
                            message: "请选择要删除的数据",
                            type: 'error'
                        });
                        this.delVisible = false;
                        return false;
                    }
                    else {
                        url = cfg.web_server_root + "data/del?ids=";
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
                    url = cfg.web_server_root + "data/del?ids=" + that.maindata[that.idx].id;
                }
                that.$axios.get(url).then(function (response) {
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
                }).catch(function (error) {
                    that.$alert(error, '提示', {
                        confirmButtonText: '确定',
                        type: 'error',
                    });
                });
            },
            handleDeleteMany() {
                this.doMany = true;
                this.delVisible = true;
            },
            loadDataClassData() {
                this.data_class_data.length = 0;

                var that = this;
                that.$axios.get(cfg.web_server_root + "data_class/show?type=" + that.mainform.type).then(function (response) {
                    if(response.data.code == 0) {
                        that.data_class_data = response.data.data;
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
            dataClassSelect(type) {

                var that = this;
                that.dataClassVisible = true;

                that.data_class_select_type = type;
                this.loadDataClassData();
            },
            handleNodeClick(data) {
                var that = this;
                if(that.data_class_select_type == "search") {
                    that.k_data_class_name = data.name;
                    that.k_data_class_id = data.id;
                }
                else {
                    that.data_class_name = data.name;
                    that.mainform.data_class_id = data.id;
                }

                that.dataClassVisible = false;
            },
            uploadPreview(file) {

            },
            uploadRemove(file, fileList) {
                this.mainform.picture = fileList;
            },
            uploadBeforeRemove(file, fileList) {
                return this.$confirm('确定移除 ' + file.name + '？');
            },
            uploadExceed(files, fileList) {
                this.$message.warning('当前限制选择 ' + this.upload_limit + ' 个文件，本次选择了 ' + files.length + ' 个文件，共选择了 ' + (files.length + fileList.length) + ' 个文件');
            },
            uploadSuccess(response, file, fileList) {
                if(response.code == 0) {
                    this.mainform.picture.push({
                        name: response.data.filepath,
                        url: cfg.web_root + "../static/upload/" + response.data.filepath
                    });
                }
                //test info
                /*for(var i in this.mainform.picture) {
                    console.log(this.mainform.picture[i].name);
                }*/
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
        text-align: center;
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
    .el-upload-dragger .el-upload__text {
        color: #606266;
        font-size: 14px;
        text-align: center;
    }
    .el-upload__tip {
        font-size: 12px;
        color: #606266;
        margin-top: 7px;
    }
</style>
