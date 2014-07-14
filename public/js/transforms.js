// -----------------------------------------------------------
// TRANSFORMS 
// -----------------------------------------------------------
var elem = document.createElement('div');
elem.style.cssText = '-moz-transform:translate3d(0px, 0px, 0px); -ms-transform:translate3d(0px, 0px, 0px); -o-transform:translate3d(0px, 0px, 0px); -webkit-transform:translate3d(0px, 0px, 0px); transform:translate3d(0px, 0px, 0px)';
var value = elem.style.cssText.match(/translate3d\(0px, 0px, 0px\)/g);
var TRANSFORM3D = (value !== null && value.length == 1);

$.fn.transform = function(val){
	if(TRANSFORM3D){
		if(!val) val = '';
		var item = $(this), vendors = ['-webkit-','-moz-','-o-',''];
		for(var i=0;i<vendors.length;i++){
			item.css(vendors[i]+'transform', val);
		}
	}
	return item;
}

$.fn.transformed = function() {
	var matrix = $(this).first().css('transform');
	if (!matrix || !matrix.has('matrix')) {
		return {
			'x': 0,
			'y': 0,
			'z': 0
		};
	}

	matrix = matrix.split('(')[1].split(')')[0].split(',');
	if (matrix.length == 16) { // 3D
		return {
			'x': Number(matrix[12]),
			'y': Number(matrix[13]),
			'z': Number(matrix[14])
		};

	} else { // 2D
		return {
			'x': Number(matrix[4]),
			'y': Number(matrix[5]),
			'z': 0
		};
	}
}

$.fn.translate = function(x,y,z){
	var item = $(this);
	if(item.length && TRANSFORM3D){
		var val;
		if(x != null || y != null || z != null){
			x = String(x || 0);
			y = String(y || 0);
			z = String(z || 0);
			if(x.indexOf('px') == -1 && x.indexOf('%') == -1) x += 'px';
			if(y.indexOf('px') == -1 && y.indexOf('%') == -1) y += 'px';
			if(z.indexOf('px') == -1 && z.indexOf('%') == -1) z += 'px';
			val = 'translate3d('+x+','+y+','+z+')';
		}else{
			val = '';
		}
		
		var vendors = ['-webkit-','-moz-','-ms-','-o-',''];
		for(var i=0;i<vendors.length;i++){
			item.css(vendors[i]+'transform', val);
		}
	}
	return item;
}