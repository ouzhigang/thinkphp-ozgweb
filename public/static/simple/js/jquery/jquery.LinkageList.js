
//无限级联动下拉框
//暂时不支持ajax
//update date: 20150619

/*
相关属性

selectedValue 默认选定的value
objId 根对象的id
inputObjId input对象的id（name值也使用这个）
css select用到的css
style select用到的style
*/

/*
data变量的格式

var data = new Array();
data.push({id: '2', parent_id: '0', name: 'bbb'});
data.push({id: '1', parent_id: '0', name: 'aaa'});
data.push({id: '6', parent_id: '1', name: 'ccc2'});
data.push({id: '3', parent_id: '1', name: 'ccc'});
data.push({id: '4', parent_id: '3', name: 'ddd'});
data.push({id: '5', parent_id: '4', name: 'eee'});

*/

/*
demo

//$("#parent") 是一个div
$("#parent").LinkageList(data, {
	objId: "parent",
	inputObjId: "parent_id",
	css: "form-control",
	style: "width: 20%; margin-right: 10px;",
	selectedValue: 5
});

*/

(function($) {
	
	var settings = null;
	
	var methods = {
		
		nextRemove: function(curr_select_obj) {
			while(curr_select_obj.next().size() > 0)
				curr_select_obj.next().remove();
		},
		
		change: function(parent_obj, curr_select_obj, data, id) {
			//id的参数为0的话就是最顶级
			
			if(id == 0) {
				parent_obj.empty();
				
				parent_obj.append("<input type='hidden' id='" + settings.inputObjId + "' name='" + settings.inputObjId + "' value='0' />");
			}
			
			if(curr_select_obj != null)
				methods.nextRemove(curr_select_obj);			
			
			var child = "<select" + (settings.css ? " class='" + settings.css + "'" : "") + (settings.style ? " style='" + settings.style + "'" : "") + ">";
			child += "<option value='0'>请选择...</option>";
			for(var i = 0; i < data.length; i++) {
				if(data[i].parent_id == id) {
					child += "<option value='" + data[i].id + "'>" + data[i].name + "</option>";
				}
			}
			child += "</select>";
			parent_obj.append(child);
			
			if($("#" + settings.objId + " > select:last > option").size() == 1)
				$("#" + settings.objId + " > select:last").remove();
			
			$("#" + settings.objId + " > select").unbind("change");
			$("#" + settings.objId + " > select").change(function() {
				
				//设置对应表单值
				if($(this).val() == "" || $(this).val() == "0") {
					
					if($(this).prev().get(0).tagName.toLowerCase() == "input")
						$("#" + settings.inputObjId).val("0");
					else {
						var val = $(this).prev().val();
						$("#" + settings.inputObjId).val(val);
					}
					
				}
				else
					$("#" + settings.inputObjId).val($(this).val());
				
				if(parseInt($(this).val()) != 0)
					methods.change($("#" + settings.objId), $(this), data, $(this).val());
				else
					methods.nextRemove($(this));
			});
		}
	};
	
	$.fn.LinkageList = function(data, options) {
		
		//合并默认设置
		settings = $.extend({
			selectedValue: null
			
			//objId: "",
			//inputObjId: "",
			//css: null,
			//style: null
		}, options);
		
		return this.each(function() {
			methods.change($("#" + settings.objId), null, data, 0);
			
			if(settings.selectedValue) {
				
				var selectedList = new Array(); //这个变量用来保存已选定的对象
				var currId = settings.selectedValue; //当前已选定的id
				do {
					for(var i = 0; i < data.length; i++) {
						if(data[i].id == currId) {
							currId = data[i].parent_id;
							selectedList.push(data[i]);
							break;
						}
					}
				}
				while(currId > 0);
				selectedList.reverse();
				
				//循环选定对应项
				for(var i = 0; i < selectedList.length; i++) {
					var currSelect = $($("#" + settings.objId + " > select").get(i)); //获取对应的select
					currSelect.val(selectedList[i].id); //设置当前的选定项
					methods.change($("#" + settings.objId), currSelect, data, selectedList[i].id); //触发当前选定项的change事件
				}
				
				$("#" + settings.inputObjId).val(settings.selectedValue);
			}
			
        });
	};
	
})(jQuery);
