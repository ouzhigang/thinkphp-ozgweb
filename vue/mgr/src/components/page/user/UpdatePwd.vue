<template>
    <div>
        <div class="crumbs">
            <el-breadcrumb separator="/">
                <el-breadcrumb-item><i class="el-icon-lx-calendar"></i> 修改密码</el-breadcrumb-item>
            </el-breadcrumb>
        </div>
        <div class="container">
            <div class="form-box">
                <el-form :model="mainform" :rules="validform" ref="mainform" label-width="80px">
                    <el-form-item label="旧密码" prop="old_pwd">
                        <el-input type="password" v-model="mainform.old_pwd"></el-input>
                    </el-form-item>
                    <el-form-item label="新密码" prop="pwd">
                        <el-input type="password" v-model="mainform.pwd"></el-input>
                    </el-form-item>
                    <el-form-item label="确认密码" prop="pwd2">
                        <el-input type="password" v-model="mainform.pwd2"></el-input>
                    </el-form-item>
                    <el-form-item>
                        <el-button type="primary" @click="submitForm('mainform')">更新</el-button>
                    </el-form-item>
                </el-form>
            </div>
        </div>
    </div>
</template>

<script>
import { cfg } from '../../common/common.js';

export default {
    name: 'user_updatepwd',
    data() {
        var validPwd2 = (rule, value, callback) => {
            if (value === '') {
                callback(new Error('请再次输入密码'));
            }
            else if (value !== this.mainform.pwd) {
                callback(new Error('两次输入密码不一致!'));
            }
            else {
                callback();
            }
        };

        return {
            mainform: {
                old_pwd: "",
                pwd: "",
                pwd2: ""
            },
            validform: {
                old_pwd: [
                    { required: true, message: '请输入旧密码', trigger: 'blur' }
                ],
                pwd: [
                    { required: true, message: '请输入密码', trigger: 'blur' }
                ],
                pwd2: [
                    { validator: validPwd2, trigger: 'blur' }
                ]

            },
        }
    },
    methods: {
        submitForm(formName) {
            var that = this;

            that.$refs[formName].validate((valid) => {
                if (valid) {
                    that.$axios.post(cfg.web_server_root + "user/updatepwd", {
                        old_pwd: that.mainform.old_pwd,
                        pwd: that.mainform.pwd,
                        pwd2: that.mainform.pwd2
                    }).then(function (response) {
                        if(response.data.code == 0) {
                            that.$alert(response.data.msg, '提示', {
                                confirmButtonText: '确定',
                                type: 'success',
                                callback: action => {
                                    that.mainform.old_pwd = "";
                                    that.mainform.pwd = "";
                                    that.mainform.pwd2 = "";
                                }
                            });
                        }
                        else {
                            that.$alert(response.data.msg, '提示', {
                                confirmButtonText: '确定',
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
        }
    },
    created() {
        document.title = cfg.web_title;
    }
}
</script>

<style scoped>

</style>
