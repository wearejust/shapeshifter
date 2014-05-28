// -----------------------------------------------------------
// VARIABLES
// -----------------------------------------------------------
var $document=$(document), $html=$('html'), $window=$(window), $body=$('body'), $bodyHtml=$('body, html');
window.TOUCH=(function(){try{document.createEvent('TouchEvent');return true;}catch(e){return false;}})();
window.DEVELOPMENT=(function(){return ((window.location.href.indexOf('.dev/')!=-1)||(window.location.href.indexOf('10.0.22.')!=-1));})();


// -----------------------------------------------------------
// HASH
// -----------------------------------------------------------
function hashReplace(h){if(h.substr(0,1)!='#')h='#'+h;(typeof window.location.replace=='function')?window.location.replace(window.location.pathname+h):window.location.hash=h;}


// -----------------------------------------------------------
// POLYFILL
// -----------------------------------------------------------
if(!Function.prototype.bind){Function.prototype.bind=function(e){if(typeof this!=="function"){throw new TypeError("Function.prototype.bind - what is trying to be bound is not callable")}var t=Array.prototype.slice.call(arguments,1),n=this,r=function(){},i=function(){return n.apply(this instanceof r?this:e||window,t.concat(Array.prototype.slice.call(arguments)))};r.prototype=this.prototype;i.prototype=new r;return i}}
if(!String.prototype.has){String.prototype.has=function(e){return (this.indexOf(e) != -1);}};
if(!Array.prototype.map){Array.prototype.map=function(e,t){var n,r,i;if(this==null){throw new TypeError(" this is null or not defined")}var s=Object(this);var o=s.length>>>0;if(typeof e!=="function"){throw new TypeError(e+" is not a function")}if(t){n=t}r=new Array(o);i=0;while(i<o){var u,a;if(i in s){u=s[i];a=e.call(n,u,i,s);r[i]=a}i++}return r}}
if(!Array.prototype.forEach){Array.prototype.forEach=function(e,t){for(var n=0,r=this.length;n<r;++n){e.call(t,this[n],n,this)}}}
if(!Array.prototype.indexOf){Array.prototype.indexOf=function(a){var b=this.length>>>0;var c=Number(arguments[1])||0;c=c<0?Math.ceil(c):Math.floor(c);if(c<0)c+=b;for(;c<b;c++){if(c in this&&this[c]===a)return c}return-1}};
if(!Array.prototype.has){Array.prototype.has=function(e){return (this.indexOf(e) != -1);}};
if(!Array.prototype.random){Array.prototype.random=function(){return this[Math.floor(Math.random()*(this.length-0.000001))];}};
if(!Array.prototype.random_r){Array.prototype.random_r=function(){return this.splice(Math.floor(Math.random()*(this.length-0.000001)),1)[0];}};
if(!Array.prototype.remove){Array.prototype.remove=function(items){if(items==null)return this;if(!(items instanceof Array))items=[items];var i=0;while(i<this.length){if(items.indexOf(this[i])!=-1){this.splice(i,1);}else{i++;}}return this;}};
if(!Array.prototype.sortOn){Array.prototype.sortOn=function(){var p=arguments;return this.sort(function(a,b){var pi=0,ap=a[p[pi]],bp=b[p[pi]];while(ap==bp&&pi<p.length){pi++;ap=a[p[pi]];bp=b[p[pi]];}if(ap<bp)return -1;if(ap>bp)return 1;return 0;});}};


// -----------------------------------------------------------
// URL
// -----------------------------------------------------------
function urlArray(u){u=u||window.location.pathname;if(u.substr(0,1)=='/')u=u.substr(1);if(u.substr(u.length-1,1)=='/')u=u.substr(0,u.length-1);return u.split('/');}
function urlGet(u){u=u||window.location.search;if(!u)return {};var i,s,o={},a=window.location.search.replace('?','').split('&');for(i=0;i<a.length;i++){s=a[i].split('=');o[s[0]]=s[1];}return o;}
function urlPage(u){var p=urlArray(u||window.location.pathname).pop();if(isNaN(p))p=1;return p;}


// -----------------------------------------------------------
// POPUPS
// -----------------------------------------------------------
// function popups(){$('.popup').unbind('click touchend').bind('click touchend',function(e){e.preventDefault();window.open(this.href,'','menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');});}$(popups);


// -----------------------------------------------------------
// CHECK IF DEVICE IS PERFORMANT
// -----------------------------------------------------------
function performant(){var i,n=new Date().getTime();for(i=0;i<100000;i++)Math.abs(-1);n=new Date().getTime()-n;window.PERFORMANT=(n<10);};


// -----------------------------------------------------------
// CHECK IF CSS TRANSITION IS SUPPORTED
// -----------------------------------------------------------
function supportsTransitions(){var e=document.body||document.documentElement;var t=e.style;var n="transition";if(typeof t[n]=="string"){return true}v=["Moz","Webkit","Khtml","O","ms"],n=n.charAt(0).toUpperCase()+n.substr(1);for(var r=0;r<v.length;r++){if(typeof t[v[r]+n]=="string"){return true}}return false};


// -----------------------------------------------------------
// jQuery EXECUTE
// -----------------------------------------------------------
$.fn.execute=function(){
    var delay = 1, func = arguments[arguments.length-1];
    if(isNaN(arguments[0])){
        func = func.bind(arguments[0]);
        if(arguments.length > 2) delay = arguments[1];
    }else{
        delay = arguments[0];
        if(arguments.length > 2) func = func.bind(arguments[1]);
    }
    return $(this).stop(true).delay(delay).queue(func);
}


// -----------------------------------------------------------
// WINDOW RESIZE
// -----------------------------------------------------------
var SCREENSIZE = 0,
    WIDESCREEN = false;

function windowResize() {
    if (window.getComputedStyle != null) {
        SCREENSIZE = window.getComputedStyle(document.body, ':after').getPropertyValue('content');
        SCREENSIZE = parseInt(SCREENSIZE.replace(/["']{1}/gi, ""));
        if (isNaN(SCREENSIZE)) SCREENSIZE = 0;
    }
    WIDESCREEN = (SCREENSIZE > 30);
};
//windowResize();
