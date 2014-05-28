// -----------------------------------------------------------
// Jquery Tracer 2.7 
// -----------------------------------------------------------
function trace(){
	var element = $('#tracer');
	if(!element.length){
		element = $('<div id="tracer"></div>');
		$('body').prepend(element);
		element.css({
			'position' : 'fixed',
			'background-color':'white',
			'border':'solid 1px black',
			'padding':'5px 25px 5px 5px',
			'z-index':'9999999',
			'max-width':'70%',
			'max-height':'98%',
			'top':'0',
			'left':'0',
			'display': 'none',
			'overflow':'auto',
			'text-align':'left',
			'color':'#000',
			'font-family':'Arial',
			'font-size':'12px',
			'white-space':'nowrap'
		});
		element.bind('dblclick touchend', function(){
			trace();
			trace_c();
		});
	}
	
	if(!arguments.length){
		element.css('display', 'none');
		element.html('');
	}else{
		element.css('display', 'block');
		element.html(element.html() + String(Array.prototype.slice.call(arguments).join(', ')) + '<br>');
	}
}

function trace_r(object, indent){
	indent = indent || 0;
	var type;
	for(var tracerObject in object){
		type = null;
		if(String(object[tracerObject]) == '[object Array]'){
			type = "Array";
		}else if(String(object[tracerObject]) == '[object Object]'){
			type = "Object";
		}
		
		if(type){
			trace('<span style="padding: ' + (indent * 30) + 'px;"><b>+ [' + tracerObject + ']</b> <span style="color:#999;">(' + type + ')</span>');
			trace_r(object[tracerObject], indent + 1);
		}else{
			trace('<span style="padding: ' + (indent * 30) + 'px;"><b>- ' + tracerObject + ':</b> ' + object[tracerObject] + '</span>');
		}
	}
}

function trace_mss(limit){
	window.trace_ms_limit = limit;
	window.trace_ms_time = new Date().getTime();
	window.trace_ms_index = 0;
}
function trace_ms(name){
	if(!window.trace_ms_time){
		trace('<b>Error:</b> Use <b>trace_mss();</b> before running trace_ms();');
	}else{
		var ms = (new Date().getTime() - window.trace_ms_time);
		window.trace_ms_index++;
		var str = '['+(name||window.trace_ms_index)+'] <b>' + ms + '</b> ms';
		if(window.trace_ms_limit && ms > window.trace_ms_limit) str = '<span style="color:red;font-weight:bold;">'+str+'</span>';
		trace(str);
	}
}

function trace_msf(){
	var i, func, funcs, limit;

	funcs = Array.prototype.slice.call(arguments);
	if(funcs.length && !isNaN(funcs[0])) limit = funcs.shift();
	if(!funcs.length){
		funcs = [];
		for(func in window){
			if(func.indexOf('trace') == -1 && typeof window[func] == 'function') funcs.push(window[func]);
		}
	}

	for(i=0;i<funcs.length;i++){
		func = funcs[i];
		window[func.name + '_orig'] = func;
		window[func.name] = function(){
			for(var func in window){
				if(window[func] == arguments.callee){
					var ms = new Date().getTime();
					var result = window[func + '_orig'].apply(this, arguments);
					ms = (new Date().getTime() - ms);
					var str = '[' + func + '] <b>' + ms + '</b> ms';
					if(limit && ms > limit) str = '<span style="color:red;font-weight:bold;">'+str+'</span>';
					trace(str);
					return result;
				}
			}
		}
	}
}

function trace_c(){
	if(arguments.length){
		if(!window.trace_c_objects) window.trace_c_objects = [];
		if(!window.trace_c_props) window.trace_c_props = [];

		var obj, prop;
		for(obj in arguments){
			obj = arguments[obj];
			window.trace_c_objects.push(obj.type || obj.currentTarget || obj);

			for(prop in obj){
				if(!window.trace_c_props[prop]) window.trace_c_props[prop] = [];
				window.trace_c_props[prop][window.trace_c_objects.length-1] = obj[prop];
			}
		}
	}else{
		window.trace_c_objects = [];
		window.trace_c_props = {};
	}
	if(window.trace_c_objects.length <= 1) return false;

	var i, err, html, str, props, border = ' style="border: 1px solid #ccc;"';

	str = '';
	for(i=0;i<window.trace_c_objects.length;i++){
		str += '<td'+border+'><b>' + i + ': ' +window.trace_c_objects[i] + '</b></td>';
	}

	html = '<table><tr><td'+border+'>&nbsp;</td>' + str + '</tr>';
	for(obj in window.trace_c_props){
		props = window.trace_c_props[obj];
		err = props.filter(function(prop, pos) {
		    return props.indexOf(prop) == pos;
		}).length > 1;

		str = '';
		for(i=0;i<props.length;i++){
			str += '<td'+border+'>' + (props[i] || '<span style="color:#ccc;"><i>null</i></span>') + '</td>';
		}

		html += '<tr' + (err?' style="background-color:#c00;color:white;"':'')+'><td'+border+'><b>'+obj+'</b></td>' + str + '</tr>';
	}
	html += '</table>';

	trace();
	trace(html);
}