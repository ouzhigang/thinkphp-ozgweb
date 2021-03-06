import React from 'react';
import { Menu, Icon } from 'antd';
import { Link } from 'react-router-dom';

const renderMenuItem = item => ( // item.route 菜单单独跳转的路由
    <Menu.Item
        key={item.key}
    >
        <Link to={item.route || item.key}>   
            {item.icon && <Icon type={item.icon} />}
            <span className="nav-text">{item.title}</span>
        </Link>
    </Menu.Item>
);

const renderSubMenu = item => ( 
    <Menu.SubMenu
        key={item.key}
        title={
            <span>
                {item.icon && <Icon type={item.icon} />}
                <span className="nav-text">{item.title}</span>
            </span>
        }
    >
        {item.subs.map(item => renderMenuItem(item))}
    </Menu.SubMenu>
);

export default ({ menus, ...props }) => (
    <Menu {...props}>
        {menus && menus.map(item => 
            item.subs ? renderSubMenu(item) : renderMenuItem(item)
        )}
    </Menu>
);

/*
权限demo，没有权限就不显示（左边菜单），纯前端的话只做是否显示的判断就已经适应大部分场景了
export default ({ menus, ...props }) => (
    <Menu {...props}>
        {menus && menus.map(item => {
        	if(item.key == '/app/user' && localStorage["role_id"] == 1) {
        		return false;
        	}
        	else {
        		return item.subs ? renderSubMenu(item) : renderMenuItem(item);
        	}
        }
            
        )}
    </Menu>
);
*/
