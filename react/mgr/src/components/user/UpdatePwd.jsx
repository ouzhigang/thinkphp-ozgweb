
import React from 'react';
import { Row, Col, Card, Timeline, Icon } from 'antd';
import BreadcrumbCustom from '../BreadcrumbCustom';
import { cfg, func } from '../../common';

export default class UserUpdatePwd extends React.Component {
	constructor(props) {
		super(props);
    	
    	document.title = cfg.web_title;
	}

    render() {
        return (
            <div>
                <BreadcrumbCustom first="用户管理" firsturl="/app/user/show" second="修改密码" />
                <Row gutter={10}>
                    <Col className="gutter-row" md={24}>
                        <div className="gutter-box">
                            <Card bordered={false}>
                                UserUpdatePwd
                            </Card>
                        </div>
                    </Col>
                </Row>                
            </div>
        )
    }
}
