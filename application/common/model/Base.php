<?php
namespace app\common\model;

use traits\model\SoftDelete;

class Base extends \think\Model {
    
	use SoftDelete;
	protected static $deleteTime = 'delete_time';
	
}
