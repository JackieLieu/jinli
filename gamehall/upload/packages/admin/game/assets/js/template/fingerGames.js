function prizeAllocation (id,imgId){
	var html =['<div js="layer" id="'+id+'" style="display:none">',
			'<div title="配置道具" class="prize-allocation">',
			'<table>',
			'<tr>',
				'<td>类型</td>',
				'<td >',
					'<select name="type">',
 	'                   <option value="1">增加积分</option>',
  	'              		<option value="0">游戏结束</option>',
	'				 </select>',
	'				<font style="color: red">&nbsp;&nbsp;游戏结束的道具在游戏中，必定出现1次以上</font>',
	'			</td>',
	'		</tr>',
	'		<tr>',
	'			<td>名称</td>',
	'			<td >',
	'				<input name="propName" type="text" /><font style="color: red">&nbsp;&nbsp;长度不要超过6个中文汉字</font>',
	'			</td>',
	'		</tr>',
	'		<tr>',
	'			<td>图片</td>',
	'			<td>',
	'				<ul class="uploadImg">',
	'					<li id="AdImg'+imgId+'">',
	'						<img class="js_form" src="http://assets.gionee.com/apps/admin/img/content/nopic.jpg">',
	'						<input type="hidden" name="propImg" value="">',
	'					</li>',
	'				</ul>',
	'				<p style="clear:both;">',
	'					<iframe name="upload" src="/Admin/Festival_Touchgame/upload/?imgId=AdImg'+imgId+'" style="height:80px;width:100%;" frameborder="0" scrolling="no"></iframe>',
	'				</p>',
	'			</td>',
	'			</tr>',
	'			<t>',
	'				<td>概率</td>',
	'				<td >',
	'					<input type="text" name="probability" /><font style="color: red">&nbsp;&nbsp;概率范围0-100</font>',
	
	'				</td>',
	'			</tr>',
	'			<tr data-name="score">',
	'				<td>分数</td>',
	'				<td >',
	'					<input type="text" name="score" />',
	'				</td>',
	'			</tr>',
	'			<tr data-name="point">',
	'				<td>积分</td>',
	'				<td >',
	'					<input type="text" name="point" />',
	'				</td>',
	'			</tr>',
	'		</table>',
	'		<input name="ok" type="button" value="完成" class="but-yellow ok" />',
	'	</div>',
	'</div>'].join(' ');
	return html;
}
function prizeConfigIntr(name,length){
	var html = [
		'<table class="remove-border" name="',name,'" style="width:28%;float:left">',
		'	<tr>',
		'		<td rowspan="5" style="width:156px">',
		'			<img name="propImg" src="http://assets.gionee.com/apps/admin/img/content/nopic.jpg" />',
		'		</td>',
		'	</tr>',
		'	<tr>',
		'		<td>',
		'			名称：<span name="propName"></span>',
		'		</td>',
		'	</tr>',
		'	<tr>',
		'		<td>',
		'			概率：<span name="probability"></span>',
		'		</td>',
		'	</tr>',
		'	<tr>',
		'		<td>',
		'			分数：<span name="score"></span>',
		'		</td>',
		'	</tr>',
		'	<tr>',
		'		<td>',
		'			积分：<span name="point"></span>',
		'		</td>',
		'	</tr>',
		'	<tr>',
		'		<td>',
		'			<input name="update" type="button" class="but-yellow" length="',length,'" value="编辑" />',
		'		</td>',
		'		<td>',
		'			<input name="del" type="button" class="but-yellow" length="',length,'" value="删除" />',
		'		</td>',
		'	</tr>',
		'</table>'].join('')
	return html;
}