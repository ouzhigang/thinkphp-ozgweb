
var curr_page = 1;
var page_count = 1;

function show_data(page) {
	
	var data = {
		get_data: 1,
		type: getUrlParam("type"),
		page: page
	};	
	$.ajax({
		url: cfg.web_root + "mgr/data/show",
		type: "get",
		dataType: "json",
		data: data,
		beforeSend: function() {
			
		},
		success: function(res, status) {
			if(res.code == 0) {
				
				$("#datalist > tbody").empty();
				for(var i = 0; i < res.data.list.length; i++) {				
					var row = "<tr>";
					row += "<td>" + res.data.list[i].id + "</td>"
					row += "<td>" + res.data.list[i].name + "</td>";
					row += "<td>" + res.data.list[i].dc_name + "</td>";
					row += "<td>" + res.data.list[i].hits + "</td>";
					row += "<td>" + res.data.list[i].add_time + "</td>";
					row += "<td>";
					row += "<button class=\"btn btn-outline btn-link\" type=\"button\" id=\"btn_edit_" + res.data.list[i].id + "\" data-id=\"" + res.data.list[i].id + "\">编辑</button>";
					row += "<button class=\"btn btn-outline btn-link\" type=\"button\" id=\"btn_del_" + res.data.list[i].id + "\" data-id=\"" + res.data.list[i].id + "\">删除</button>";
					row += "</td>";
					row += "</tr>";
					
					$("#datalist > tbody").append(row);
				}
				
				page = res.data.page;
				page_count = res.data.page_count;
				$("#page").html(page);
				$("#page_count").html(page_count);
				
				$("button[id ^= 'btn_edit_']").click(function() {
					var data = {
						id: $(this).attr("data-id")
					};
					$.get(cfg.web_root + "mgr/data/get", data, function(res) {
						if(res.code == 0) {
							$("#name").val(res.data.name);
							$("#sort").val(res.data.sort);
							$("#dataclass_selected").val(res.data.data_class_id);
							$("#dataclass").empty();
							$("#content").val(res.data.content);
							$("#id").val(res.data.id);
							
							if(res.data.is_index_show == 1)
								$("#is_index_show").attr("checked", "checked");
							else
								$("#is_index_show").removeAttr("checked");
							
							if(res.data.is_index_top == 1)
								$("#is_index_top").attr("checked", "checked");
							else
								$("#is_index_top").removeAttr("checked");
							
							if(res.data.recommend == 1)
								$("#recommend").attr("checked", "checked");
							else
								$("#recommend").removeAttr("checked");
							
							req_dataclass();
							
							//图片
							$("#picture_list").empty();
							if(res.data.picture.length > 0) {
								for(var i in res.data.picture) {
									var html = '<div class="picture_div">';
									html += '<input class="form-control" placeholder="图片路径" id="picture_' + i + '" name="picture_' + i + '" value="' + res.data.picture[i] + '" />';
									html += '<input type="button" id="btn_upload_' + i + '" class="btn btn-outline btn-primary" value="上传" />';
									html += '<input id="file_upload_' + i + '" name="file_upload_' + i + '" class="file-upload" type="file" />';
									html += '<button type="button" class="btn btn-default add">+</button>';
									html += '<button type="button" class="btn btn-default dec">-</button>';
									html += '<input type="hidden" value="' + i + '" />';
									html += '</div>';
								
									$("#picture_list").append(html);
								}
							}
							else {
								var html = '<div class="picture_div">';
								html += '<input class="form-control" placeholder="图片路径" id="picture_0" name="picture_0" />';
								html += '<input type="button" id="btn_upload_0" class="btn btn-outline btn-primary" value="上传" />';
								html += '<input id="file_upload_0" name="file_upload_0" class="file-upload" type="file" />';
								html += '<button type="button" class="btn btn-default add">+</button>';
								html += '<button type="button" class="btn btn-default dec">-</button>';
								html += '<input type="hidden" value="0" />';
								html += '</div>';		
								$("#picture_list").append(html);
							}
							
							upload_btn();
							
							$("#btn_submit").val("更新");
							
							$("#add_table").show();
							$("#getlist_table").hide();							
						}
					});
				});
				
				//点击删除
				$("button[id ^= 'btn_del_']").click(function() {
					var curr_obj = $(this);			
					var data = {
						id: curr_obj.attr("data-id")
					};
					$("#dialog_confirm").find(".modal-body").html("确定删除吗？");
					$("#dialog_confirm").modal('show');
					$("#dialog_confirm_btn").unbind().click(function() {
						
						$.ajax({
							url: cfg.web_root + "mgr/data/del",
							type: "get",
							dataType: "json",
							data: data,
							beforeSend: function() {
								curr_obj.attr("disabled", true);
							},
							success: function(res, status) {
								$("#dialog_confirm").modal("hide");						
								if(res.code == 0) {
									
									show_data(curr_page);
								}
								else {
									$("#dialog_alert").find(".modal-body").html(res.msg);
									$("#dialog_alert").modal("show");		
								}
							},
							complete: function() {
								curr_obj.attr("disabled", false);
							}
						});
						
					});					
				});
			}
		},
		complete: function() {
			
		}
	});
	
}

//无限级下拉框
function req_dataclass() {
	
	var data = {
		type: $("#type").val()
		
	};
	$.ajax({
		url: cfg.web_root + "mgr/data_class/gettree",
		type: "get",
		dataType: "json",
		data: data,
		beforeSend: function() {
			
		},
		success: function(res, status) {
	
			$("#dataclass").LinkageList(res.data, {
				objId: "dataclass",
				inputObjId: "data_class_id",
				css: "form-control",
				style: "width: 20%; margin-right: 10px;",
				selectedValue: $("#dataclass_selected").val() == "0" ? null : $("#dataclass_selected").val()
			});
			
		},
		complete: function() {
			
		}
	});
}

function upload_btn() {
	//上传
	var btn_upload = null;
	$("input[id ^= 'btn_upload_']").click(function() {
		btn_upload = $(this);
		var id = $(this).attr("id").split("_");
		id = id[id.length - 1];
		$("#file_upload_" + id).click();
	});
	$("input[id ^= 'file_upload_']").fileupload({
		url: cfg.web_root + "mgr/data/upload",
		dataType: 'json',
		done: function(e, data) {
			var id = btn_upload.attr("id").split("_");
			id = id[id.length - 1];
			
			if(data.result.code == 0) {
				$("#picture_" + id).val(data.result.data.filepath);
			}
			else {
				$("#dialog_alert").find(".modal-body").html(data.result.msg);
				$("#dialog_alert").modal("show");
			}				
		},
		progressall: function (e, data) {
			//var progress = parseInt(data.loaded / data.total * 100, 10);
			
		}
	});	
	
	$("button.add").hide();
	$("button.add:last").show();
	if($("button.dec").length == 1)
		$("button.dec").hide();
	
	$("button.add").click(function() {
		var id = "0";
		do {
			id = parseInt(Math.random() * 1000000);
		}
		while($("#picture_" + id).length > 0);
		
		$(this).parent().after($(this).parent().clone(true));
		$(".picture_div").last().find("input[type = 'hidden']").val(id);
		$(".picture_div").last().find("input[id ^= 'picture_']").val("");
		$(".picture_div").last().find(".dec").show();
		
		$(".picture_div").last().find("input[id ^= 'picture_']").each(function() {
			var ids = $(this).attr("id").split("_");
			var tmp = "";
			for(var i = 0; i < ids.length - 1; i++) {
				tmp += ids[i] + "_";
			}
			tmp += id;
			$(this).attr("id", tmp);
			$(this).attr("name", tmp);
		});
		$(".picture_div").last().find("input[id ^= 'btn_upload_']").each(function() {
			var ids = $(this).attr("id").split("_");
			var tmp = "";
			for(var i = 0; i < ids.length - 1; i++) {
				tmp += ids[i] + "_";
			}
			tmp += id;
			$(this).attr("id", tmp);
		});
		$(".picture_div").last().find("input[id ^= 'file_upload_']").each(function() {
			var ids = $(this).attr("id").split("_");
			var tmp = "";
			for(var i = 0; i < ids.length - 1; i++) {
				tmp += ids[i] + "_";
			}
			tmp += id;
			$(this).attr("id", tmp);
			$(this).attr("name", tmp);
		});
		
		$("button.add").hide();
		$("button.add:last").show();
		$("button.dec").show();
	});
	$("button.dec").click(function() {
		$(this).parent().remove();
		
		$("button.add").hide();
		$("button.add:last").show();
		
		if($("button.dec").length == 1)
			$("button.dec").hide();
	});
	//上传 end
}

$(function() {
	
	$('#content').ckeditor({ 
		height: '450px' 
	});
	
	show_data(curr_page);		
	$("#page_first").click(function() {
		curr_page = 1;		
		show_data(curr_page);	
	});
	$("#page_prev").click(function() {
		curr_page--;
		if(curr_page < 1)
			curr_page = 1;
		show_data(curr_page);	
	});
	$("#page_next").click(function() {
		curr_page++;
		if(curr_page > page_count)
			curr_page = page_count;
		show_data(curr_page);
	});
	$("#page_last").click(function() {
		curr_page = page_count;		
		show_data(page_count);	
	});
	
	req_dataclass();
	
	$("#btn_add").click(function() {
		//初始化表单		
		$("#name").val("");
		$("#sort").val("0");
		$("#dataclass_selected").val("0");
		$("#dataclass").empty();
		$("#content").val("");
		$("#id").val("0");
		
		$("#is_index_show").removeAttr("checked");
		$("#is_index_top").removeAttr("checked");
		$("#recommend").removeAttr("checked");
		
		req_dataclass();
		
		//图片
		$(".picture_div").remove();
		var html = '<div class="picture_div">';
		html += '<input class="form-control" placeholder="图片路径" id="picture_0" name="picture_0" />';
		html += '<input type="button" id="btn_upload_0" class="btn btn-outline btn-primary" value="上传" />';
		html += '<input id="file_upload_0" name="file_upload_0" class="file-upload" type="file" />';
		html += '<button type="button" class="btn btn-default add">+</button>';
		html += '<button type="button" class="btn btn-default dec">-</button>';
		html += '<input type="hidden" value="0" />';
		html += '</div>';		
		$("#picture_list").append(html);
		upload_btn();
		
		$("#btn_submit").val("添加");
		
		$("#add_table").show();
		$("#getlist_table").hide();
	});
	$("#btn_getlist").click(function() {
		$("#add_table").hide();
		$("#getlist_table").show();
		
		show_data(curr_page);
	});
	$("#add_table").hide();
	
	$("#main_form").validate({
		rules: {
			name: {
				required: true
			},
			sort: {
				required: true,
				digits: true
			}
		},
		messages: {
			name: {
				required: "没有填写名称"
			},
			sort: {
				required: "没有填写排序",
				number: "请输入整数"
			}				
		},
		submitHandler: function(form) {
			
			if($("#type").val() == "1") {
				if($("#data_class_id").val() == "0" || $("#data_class_id").val() == "") {
					$("#dialog_alert").find(".modal-body").html("没有选择分类");
					$("#dialog_alert").modal("show");	
					return false;
				}
			}
			
			var data = {
				id: $("#id").val(),
				name: $("#name").val(),
				sort: $("#sort").val(),
				data_class_id: $("#data_class_id").val(),
				content: $("#content").val(),
				type: $("#type").val(),
				is_index_show: $("#is_index_show").is(':checked') ? 1 : 0,
				is_index_top: $("#is_index_top").is(':checked') ? 1 : 0,
				recommend: $("#recommend").is(':checked') ? 1 : 0,
				picture: []
			};
			
			$("input[id ^= 'picture_']").each(function() {
				if($(this).val() && $(this).val() != "") {
					data.picture.push($(this).val());
				}
			});
			
			$.ajax({
				url: cfg.web_root + "mgr/data/add",
				type: "post",
				dataType: "json",
				data: data,
				beforeSend: function() {
					$("#btn_submit").attr("disabled", true);
				},
				success: function(res, status) {
					if(res.code == 0) {
						$("#btn_getlist").get(0).click();
					}
					else {
						$("#dialog_alert").find(".modal-body").html(res.msg);
						$("#dialog_alert").modal("show");	
					}
				},
				complete: function() {
					$("#btn_submit").attr("disabled", false);
				}
			});
			
			return false;
		}
	});
});
