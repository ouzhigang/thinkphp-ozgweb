<template>
    <div>
        <div class="crumbs">
            <el-breadcrumb separator="/">
                <el-breadcrumb-item><i class="el-icon-lx-calendar"></i> 区域管理</el-breadcrumb-item>
                <el-breadcrumb-item>{{ $route.meta.title }}</el-breadcrumb-item>
            </el-breadcrumb>
        </div>
        <div class="container">
            <input type="hidden" v-model="id" />
            <quill-editor ref="myTextEditor" v-model="content" :options="editorOption"></quill-editor>
            <el-button class="editor-btn" type="primary" @click="submitForm">提交</el-button>
        </div>
    </div>
</template>

<script>
import 'quill/dist/quill.core.css';
import 'quill/dist/quill.snow.css';
import 'quill/dist/quill.bubble.css';
import { quillEditor } from 'vue-quill-editor';

import { cfg, func } from '../../common/common.js';

export default {
    name: 'art_single_get',
    data() {
        return {
            content: '',
            id: 0,
            editorOption: {
                placeholder: '请输入内容',
                modules: {
                    toolbar: [
                        [ 'background', 'bold', 'color', 'font', 'code', 'italic', 'link', 'size', 'strike', 'script', 'underline', 'blockquote', 'header', 'indent', 'list', 'align', 'direction', 'code-block', 'formula', 'video', 'clean' ]
                    ]
                }
            }
        }
    },
    components: {
        quillEditor
    },
    methods: {
        //quillEditor默认的
        onEditorChange({ editor, html, text }) {
            this.content = html;
        },
        submitForm() {
            var that = this;

            that.$axios.post(cfg.web_server_root + "art_single/update", {
                id: that.id,
                content: that.content
            }).then(function (response) {
                if(response.data.code == 0) {
                    that.$message({
                        message: response.data.msg,
                        type: 'success'
                    });
                    that.loadData();
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

        },
        loadData() {
            var that = this;

            var id = func.get_rest_param("get");
            that.id = id;

            that.$axios.get(cfg.web_server_root + "art_single/get?id=" + id).then(function (response) {
                if(response.data.code == 0) {
                    that.content = response.data.data.content;
                }
                else {
                    that.$alert(response.data.msg, '提示', {
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
        }
    },
    watch: {
        '$route' (to, from) {
            this.loadData();
        },
    },
    mounted() {
        this.loadData();
    }
}
</script>

<style scoped>
    .editor-btn{
        margin-top: 20px;
    }
</style>
