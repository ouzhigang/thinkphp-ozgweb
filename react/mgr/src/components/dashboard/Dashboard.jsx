
import React from 'react';
import { Row, Col, Card, Timeline, Icon, Table, message } from 'antd';
import BreadcrumbCustom from '../BreadcrumbCustom';
import { cfg, func } from '../../common';

import axios from 'axios';

import '../../style/css/dashboard/Dashboard.css';

export default class Dashboard extends React.Component {
	constructor(props) {
		super(props);
    	
    	this.state = {
    		server_info: {
    			server_name: "",
    			os: "",
    			server_software: "",
    			php_version: "",
    			thinkphp_ver: "",
    			upload_file_status: "",
    			max_execution_time: "",
    			document_root: "",
    			now: ""
    		}
    	};
    	
    	document.title = cfg.web_title;
	}
	
	componentDidMount() {
		var that = this;
        axios.get(cfg.web_server_root + "other/serverInfo").then(function (response) {
        	if(response.data.code === 0) {
            	that.setState({
            		server_info: response.data.data
            	});
            }
            else {
            	message.error(response.data.msg);
            	that.props.history.push('/login');
            }
        }).catch(function (error) {
            message.error(error);
        });
	}

    render() {
    
        return (
            <div>
                <BreadcrumbCustom first="系统首页" />
                <Row gutter={10}>
                    <Col className="gutter-row" md={24}>
                        <div className="gutter-box">
                            <Card bordered={false}>
                                <h3 id="dashboard_title_1">主机探针</h3>
                                <table id="dashboard_viewtable">
									<tr>
										<td class="viewtable_title">服务器（IP）</td>
										<td>{this.state.server_info.server_name}</td>
									</tr>
									<tr>
										<td class="viewtable_title">操作系统</td>
										<td>{this.state.server_info.os}</td>
									</tr>
									<tr>
										<td class="viewtable_title">服务器软件</td>
										<td>{this.state.server_info.server_software}</td>
									</tr>
									<tr>
										<td class="viewtable_title">PHP版本</td>
										<td>{this.state.server_info.php_version}</td>
									</tr>
									<tr>
										<td class="viewtable_title">ThinkPHP版本</td>
										<td>{this.state.server_info.thinkphp_ver}</td>
									</tr>
									<tr>
										<td class="viewtable_title">上传文件大小</td>
										<td>{this.state.server_info.upload_file_status}</td>
									</tr>
									<tr>
										<td class="viewtable_title">脚本超时</td>
										<td>{this.state.server_info.max_execution_time}</td>
									</tr>
									<tr>
										<td class="viewtable_title">网站路径</td>
										<td>{this.state.server_info.document_root}</td>
									</tr>
									<tr>
										<td class="viewtable_title">服务器时间</td>
										<td>{this.state.server_info.now}</td>
									</tr>
								</table>
						
                            </Card>
                        </div>
                    </Col>
                </Row>                
            </div>
        )
    }
}
