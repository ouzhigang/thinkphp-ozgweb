<template>
    <div>
        <el-row :gutter="20">
            <el-col :span="24">
                <el-card shadow="hover" style="min-height: 400px;">
                    <div slot="header" class="clearfix">
                        <span>后台统计</span>
                    </div>
                    <div>
                        
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

            }
        },
        methods: {

        },
        created() {
			document.title = cfg.web_title;
			
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
