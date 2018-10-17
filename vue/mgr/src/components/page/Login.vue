<template>
    <div class="login-wrap">
        <div class="ms-login">
            <div class="ms-title">后台管理系统</div>
            <el-form :model="ruleForm" :rules="rules" ref="ruleForm" label-width="0px" class="ms-content">
                <el-form-item prop="name">
                    <el-input v-model="ruleForm.name" placeholder="请输入用户名">
                        <el-button slot="prepend" icon="el-icon-lx-people"></el-button>
                    </el-input>
                </el-form-item>
                <el-form-item prop="pwd">
                    <el-input type="password" placeholder="请输入密码" v-model="ruleForm.pwd" @keyup.enter.native="submitForm('ruleForm')">
                        <el-button slot="prepend" icon="el-icon-lx-lock"></el-button>
                    </el-input>
                </el-form-item>
                <el-form-item prop="vcode" v-show="is_show_vcode">
                    <el-input placeholder="请输入验证码" v-model="ruleForm.vcode" maxlength="10" @keyup.enter.native="submitForm('ruleForm')">
                        <el-button slot="prepend" icon="el-icon-lx-info"></el-button>
                    </el-input>
                    <img v-if="is_show_vcode" class="vcode-img" :src="vcode_img" @click="vcodeUpdate($event)" />
                </el-form-item>
                <div class="login-btn">
                    <el-button type="primary" @click="submitForm('ruleForm')">登录</el-button>
                </div>

            </el-form>
        </div>
    </div>
</template>

<script>
    import { cfg } from '../common/common.js';

    export default {
        data: function(){
            return {
                ruleForm: {
                    name: '',
                    pwd: '',
                    vcode: ''
                },
                rules: {
                    name: [
                        { required: true, message: '请输入用户名', trigger: 'blur' }
                    ],
                    pwd: [
                        { required: true, message: '请输入密码', trigger: 'blur' }
                    ]
                },
                is_show_vcode: false,
                vcode_img: cfg.web_server_root + "index/getvcode"
            }
        },
        methods: {
            submitForm(formName) {
                var that = this;

                this.$refs[formName].validate((valid) => {
                    if (valid) {
                        that.$axios.post(cfg.web_server_root + "index/login", {
                            name: that.ruleForm.name,
                            pwd: that.ruleForm.pwd,
                            vcode: that.ruleForm.vcode
                        }).then(function (response) {
                            if(response.data.code == 0) {
                                localStorage.setItem('user_name', response.data.data.name);
                                localStorage.setItem('user_id', response.data.data.id);
                                that.$router.push('/');
                                //console.log(response.data.data);
                            }
                            else if(response.data.code == 2) {
                                //需要输入验证码
                                that.ruleForm.vcode = "";
                                that.is_show_vcode = true;
                            }
                            else {
                                that.is_show_vcode = false;
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
            },
            vcodeUpdate(event) {
                var el = event.currentTarget
                el.src = this.vcode_img + "?dt=" + Math.random()
            }
        }
    }
</script>

<style scoped>
    .login-wrap{
        position: relative;
        width:100%;
        height:100%;
        background-image: url(../../assets/login-bg.jpg);
        background-size: 100%;
    }
    .ms-title{
        width:100%;
        line-height: 50px;
        text-align: center;
        font-size:20px;
        color: #666;
        border-bottom: 1px solid #ddd;
    }
    .ms-login{
        position: absolute;
        left:50%;
        top:50%;
        width:350px;
        margin:-190px 0 0 -175px;
        border-radius: 5px;
        background: rgba(255,255,255, 0.3);
        overflow: hidden;
    }
    .ms-content{
        padding: 30px 30px;
    }
    .login-btn{
        text-align: center;
    }
    .login-btn button{
        width:100%;
        height:36px;
        margin-bottom: 10px;
    }
    .vcode-img {
        display: block;
        position: absolute;
        width: 118px;
        top: 3px;
        left: 171px;
        cursor: pointer;
    }
</style>
