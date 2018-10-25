<template>
    <div>
        <el-row :gutter="20">
            <el-col :span="24">
                <el-card shadow="hover" style="min-height: 400px;">
                    <div slot="header" class="clearfix">
                        <span>主机探针</span>
                    </div>
                    <div>
                        <table id="viewtable">
							<tr>
								<td class="viewtable_title">服务器（IP）</td>
								<td v-text="server_info.server_name"></td>
							</tr>
							<tr>
								<td class="viewtable_title">操作系统</td>
								<td v-text="server_info.os"></td>
							</tr>
							<tr>
								<td class="viewtable_title">服务器软件</td>
								<td v-text="server_info.server_software"></td>
							</tr>
							<tr>
								<td class="viewtable_title">PHP版本</td>
								<td v-text="server_info.php_version"></td>
							</tr>
							<tr>
								<td class="viewtable_title">ThinkPHP版本</td>
								<td v-text="server_info.thinkphp_ver"></td>
							</tr>
							<tr>
								<td class="viewtable_title">上传文件大小</td>
								<td v-text="server_info.upload_file_status"></td>
							</tr>
							<tr>
								<td class="viewtable_title">脚本超时</td>
								<td v-text="server_info.max_execution_time"></td>
							</tr>
							<tr>
								<td class="viewtable_title">网站路径</td>
								<td v-text="server_info.document_root"></td>
							</tr>
							<tr>
								<td class="viewtable_title">服务器时间</td>
								<td v-text="server_info.now"></td>
							</tr>
						</table>
                    </div>
                </el-card>
            </el-col>
        </el-row>
    </div>
</template>

<script>
    import bus from '../common/bus';
    import { cfg } from '../common/common.js';

    export default {
        name: 'dashboard',
        data() {
            return {
                server_info: {
                    server_name: '',
			        os: '',
			        server_software: '',
			        php_version: '',
			        upload_file_status: '',
			        max_execution_time: '',
			        document_root: '',
			        now: '',
			        thinkphp_ver: '',
                }
            }
        },
        methods: {

        },
        created() {
            document.title = cfg.web_title;

            var that = this;
            that.$axios.get(cfg.web_server_root + "other/serverInfo").then(function (response) {
                that.server_info = response.data.data;
            }).catch(function (error) {
                that.$alert(error, '提示', {
                    confirmButtonText: '确定',
                    type: 'error',
                });
            });
        }
    }

</script>


<style scoped>
#viewtable {
    width: 75%;
    color: #999;
}
#viewtable .viewtable_title {
	width: 30%;
	height: 30px;
	text-align: right;
	padding-right: 40px;
	color: #333;
}
</style>
