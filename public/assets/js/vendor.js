function strlen(e){var t=e+"",n=0,i=0;if(!this.php_js||!this.php_js.ini||!this.php_js.ini["unicode.semantics"]||"on"!==this.php_js.ini["unicode.semantics"].local_value.toLowerCase())return e.length;var r=function(e,t){var n=e.charCodeAt(t),i="",r="";if(55296<=n&&n<=56319){if(e.length<=t+1)throw"High surrogate without following low surrogate";if(56320>(i=e.charCodeAt(t+1))||i>57343)throw"High surrogate without following low surrogate";return e.charAt(t)+e.charAt(t+1)}if(56320<=n&&n<=57343){if(0===t)throw"Low surrogate without preceding high surrogate";if(55296>(r=e.charCodeAt(t-1))||r>56319)throw"Low surrogate without preceding high surrogate";return!1}return e.charAt(t)};for(n=0,i=0;n<t.length;n++)!1!==r(t,n)&&i++;return i}function array_diff(e){var t={},n=arguments.length,i="",r=1,o="",a={};e:for(i in e)for(r=1;r<n;r++){for(o in a=arguments[r])if(a[o]===e[i])continue e;t[i]=e[i]}return t}function strtotime(e,t){var n,i,r,o,a,s,l,u,c,d,f=!1;if(!e)return f;if((i=(e=e.replace(/^\s+|\s+$/g,"").replace(/\s{2,}/g," ").replace(/[\t\r\n]/g,"").toLowerCase()).match(/^(\d{1,4})([\-\.\/\:])(\d{1,2})([\-\.\/\:])(\d{1,4})(?:\s(\d{1,2}):(\d{2})?:?(\d{2})?)?(?:\s([A-Z]+)?)?$/))&&i[2]===i[4])if(i[1]>1901)switch(i[2]){case"-":return i[3]>12||i[5]>31?f:new Date(i[1],parseInt(i[3],10)-1,i[5],i[6]||0,i[7]||0,i[8]||0,i[9]||0)/1e3;case".":return f;case"/":return i[3]>12||i[5]>31?f:new Date(i[1],parseInt(i[3],10)-1,i[5],i[6]||0,i[7]||0,i[8]||0,i[9]||0)/1e3}else if(i[5]>1901)switch(i[2]){case"-":case".":return i[3]>12||i[1]>31?f:new Date(i[5],parseInt(i[3],10)-1,i[1],i[6]||0,i[7]||0,i[8]||0,i[9]||0)/1e3;case"/":return i[1]>12||i[3]>31?f:new Date(i[5],parseInt(i[1],10)-1,i[3],i[6]||0,i[7]||0,i[8]||0,i[9]||0)/1e3}else switch(i[2]){case"-":return i[3]>12||i[5]>31||i[1]<70&&i[1]>38?f:(o=i[1]>=0&&i[1]<=38?+i[1]+2e3:i[1],new Date(o,parseInt(i[3],10)-1,i[5],i[6]||0,i[7]||0,i[8]||0,i[9]||0)/1e3);case".":return i[5]>=70?i[3]>12||i[1]>31?f:new Date(i[5],parseInt(i[3],10)-1,i[1],i[6]||0,i[7]||0,i[8]||0,i[9]||0)/1e3:i[5]<60&&!i[6]?i[1]>23||i[3]>59?f:(r=new Date,new Date(r.getFullYear(),r.getMonth(),r.getDate(),i[1]||0,i[3]||0,i[5]||0,i[9]||0)/1e3):f;case"/":return i[1]>12||i[3]>31||i[5]<70&&i[5]>38?f:(o=i[5]>=0&&i[5]<=38?+i[5]+2e3:i[5],new Date(o,parseInt(i[1],10)-1,i[3],i[6]||0,i[7]||0,i[8]||0,i[9]||0)/1e3);case":":return i[1]>23||i[3]>59||i[5]>59?f:(r=new Date,new Date(r.getFullYear(),r.getMonth(),r.getDate(),i[1]||0,i[3]||0,i[5]||0)/1e3)}if("now"===e)return null===t||isNaN(t)?(new Date).getTime()/1e3|0:0|t;if(!isNaN(n=Date.parse(e)))return n/1e3|0;if((i=e.match(/^([0-9]{4}-[0-9]{2}-[0-9]{2})[ t]([0-9]{2}:[0-9]{2}:[0-9]{2}(\.[0-9]+)?)([\+-][0-9]{2}(:[0-9]{2})?|z)/))&&("z"==i[4]?i[4]="Z":i[4].match(/^([\+-][0-9]{2})$/)&&(i[4]=i[4]+":00"),!isNaN(n=Date.parse(i[1]+"T"+i[2]+i[4]))))return n/1e3|0;function h(e){var t=e.split(" "),n=t[0],i=t[1].substring(0,3),r=/\d+/.test(n),o=("last"===n?-1:1)*("ago"===t[2]?-1:1);if(r&&(o*=parseInt(n,10)),l.hasOwnProperty(i)&&!t[1].match(/^mon(day|\.)?$/i))return a["set"+l[i]](a["get"+l[i]]()+o);if("wee"===i)return a.setDate(a.getDate()+7*o);if("next"===n||"last"===n)!function(e,t,n){var i,r=s[t];void 0!==r&&(0==(i=r-a.getDay())?i=7*n:i>0&&"last"===e?i-=7:i<0&&"next"===e&&(i+=7),a.setDate(a.getDate()+i))}(n,i,o);else if(!r)return!1;return!0}if(a=t?new Date(1e3*t):new Date,s={sun:0,mon:1,tue:2,wed:3,thu:4,fri:5,sat:6},l={yea:"FullYear",mon:"Month",day:"Date",hou:"Hours",min:"Minutes",sec:"Seconds"},"([+-]?\\d+\\s"+(c="(years?|months?|weeks?|days?|hours?|minutes?|min|seconds?|sec|sunday|sun\\.?|monday|mon\\.?|tuesday|tue\\.?|wednesday|wed\\.?|thursday|thu\\.?|friday|fri\\.?|saturday|sat\\.?)")+"|(last|next)\\s"+c+")(\\sago)?",!(i=e.match(new RegExp("([+-]?\\d+\\s(years?|months?|weeks?|days?|hours?|minutes?|min|seconds?|sec|sunday|sun\\.?|monday|mon\\.?|tuesday|tue\\.?|wednesday|wed\\.?|thursday|thu\\.?|friday|fri\\.?|saturday|sat\\.?)|(last|next)\\s(years?|months?|weeks?|days?|hours?|minutes?|min|seconds?|sec|sunday|sun\\.?|monday|mon\\.?|tuesday|tue\\.?|wednesday|wed\\.?|thursday|thu\\.?|friday|fri\\.?|saturday|sat\\.?))(\\sago)?","gi"))))return f;for(d=0,u=i.length;d<u;d++)if(!h(i[d]))return f;return a.getTime()/1e3}function is_numeric(e){return("number"==typeof e||"string"==typeof e&&-1===" \n\r\t\f\v            ​\u2028\u2029　".indexOf(e.slice(-1)))&&""!==e&&!isNaN(e)}var DateFormatter,laravelValidation;!function(e,t){"use strict";"object"==typeof module&&"object"==typeof module.exports?module.exports=e.document?t(e,!0):function(e){if(!e.document)throw new Error("jQuery requires a window with a document");return t(e)}:t(e)}("undefined"!=typeof window?window:this,(function(e,t){"use strict";var n=[],i=e.document,r=Object.getPrototypeOf,o=n.slice,a=n.concat,s=n.push,l=n.indexOf,u={},c=u.toString,d=u.hasOwnProperty,f=d.toString,h=f.call(Object),p={},m=function(e){return"function"==typeof e&&"number"!=typeof e.nodeType},g=function(e){return null!=e&&e===e.window},v={type:!0,src:!0,noModule:!0};function y(e,t,n){var r,o=(t=t||i).createElement("script");if(o.text=e,n)for(r in v)n[r]&&(o[r]=n[r]);t.head.appendChild(o).parentNode.removeChild(o)}function b(e){return null==e?e+"":"object"==typeof e||"function"==typeof e?u[c.call(e)]||"object":typeof e}var _=function(e,t){return new _.fn.init(e,t)},w=/^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g;function x(e){var t=!!e&&"length"in e&&e.length,n=b(e);return!m(e)&&!g(e)&&("array"===n||0===t||"number"==typeof t&&t>0&&t-1 in e)}_.fn=_.prototype={jquery:"3.3.1",constructor:_,length:0,toArray:function(){return o.call(this)},get:function(e){return null==e?o.call(this):e<0?this[e+this.length]:this[e]},pushStack:function(e){var t=_.merge(this.constructor(),e);return t.prevObject=this,t},each:function(e){return _.each(this,e)},map:function(e){return this.pushStack(_.map(this,(function(t,n){return e.call(t,n,t)})))},slice:function(){return this.pushStack(o.apply(this,arguments))},first:function(){return this.eq(0)},last:function(){return this.eq(-1)},eq:function(e){var t=this.length,n=+e+(e<0?t:0);return this.pushStack(n>=0&&n<t?[this[n]]:[])},end:function(){return this.prevObject||this.constructor()},push:s,sort:n.sort,splice:n.splice},_.extend=_.fn.extend=function(){var e,t,n,i,r,o,a=arguments[0]||{},s=1,l=arguments.length,u=!1;for("boolean"==typeof a&&(u=a,a=arguments[s]||{},s++),"object"==typeof a||m(a)||(a={}),s===l&&(a=this,s--);s<l;s++)if(null!=(e=arguments[s]))for(t in e)n=a[t],a!==(i=e[t])&&(u&&i&&(_.isPlainObject(i)||(r=Array.isArray(i)))?(r?(r=!1,o=n&&Array.isArray(n)?n:[]):o=n&&_.isPlainObject(n)?n:{},a[t]=_.extend(u,o,i)):void 0!==i&&(a[t]=i));return a},_.extend({expando:"jQuery"+("3.3.1"+Math.random()).replace(/\D/g,""),isReady:!0,error:function(e){throw new Error(e)},noop:function(){},isPlainObject:function(e){var t,n;return!(!e||"[object Object]"!==c.call(e)||(t=r(e))&&("function"!=typeof(n=d.call(t,"constructor")&&t.constructor)||f.call(n)!==h))},isEmptyObject:function(e){var t;for(t in e)return!1;return!0},globalEval:function(e){y(e)},each:function(e,t){var n,i=0;if(x(e))for(n=e.length;i<n&&!1!==t.call(e[i],i,e[i]);i++);else for(i in e)if(!1===t.call(e[i],i,e[i]))break;return e},trim:function(e){return null==e?"":(e+"").replace(w,"")},makeArray:function(e,t){var n=t||[];return null!=e&&(x(Object(e))?_.merge(n,"string"==typeof e?[e]:e):s.call(n,e)),n},inArray:function(e,t,n){return null==t?-1:l.call(t,e,n)},merge:function(e,t){for(var n=+t.length,i=0,r=e.length;i<n;i++)e[r++]=t[i];return e.length=r,e},grep:function(e,t,n){for(var i=[],r=0,o=e.length,a=!n;r<o;r++)!t(e[r],r)!==a&&i.push(e[r]);return i},map:function(e,t,n){var i,r,o=0,s=[];if(x(e))for(i=e.length;o<i;o++)null!=(r=t(e[o],o,n))&&s.push(r);else for(o in e)null!=(r=t(e[o],o,n))&&s.push(r);return a.apply([],s)},guid:1,support:p}),"function"==typeof Symbol&&(_.fn[Symbol.iterator]=n[Symbol.iterator]),_.each("Boolean Number String Function Array Date RegExp Object Error Symbol".split(" "),(function(e,t){u["[object "+t+"]"]=t.toLowerCase()}));var D=function(e){var t,n,i,r,o,a,s,l,u,c,d,f,h,p,m,g,v,y,b,_="sizzle"+1*new Date,w=e.document,x=0,D=0,T=ae(),C=ae(),k=ae(),E=function(e,t){return e===t&&(d=!0),0},S={}.hasOwnProperty,A=[],O=A.pop,M=A.push,N=A.push,I=A.slice,L=function(e,t){for(var n=0,i=e.length;n<i;n++)if(e[n]===t)return n;return-1},j="checked|selected|async|autofocus|autoplay|controls|defer|disabled|hidden|ismap|loop|multiple|open|readonly|required|scoped",F="[\\x20\\t\\r\\n\\f]",P="(?:\\\\.|[\\w-]|[^\0-\\xa0])+",R="\\["+F+"*("+P+")(?:"+F+"*([*^$|!~]?=)"+F+"*(?:'((?:\\\\.|[^\\\\'])*)'|\"((?:\\\\.|[^\\\\\"])*)\"|("+P+"))|)"+F+"*\\]",U=":("+P+")(?:\\((('((?:\\\\.|[^\\\\'])*)'|\"((?:\\\\.|[^\\\\\"])*)\")|((?:\\\\.|[^\\\\()[\\]]|"+R+")*)|.*)\\)|)",Y=new RegExp(F+"+","g"),H=new RegExp("^"+F+"+|((?:^|[^\\\\])(?:\\\\.)*)"+F+"+$","g"),W=new RegExp("^"+F+"*,"+F+"*"),q=new RegExp("^"+F+"*([>+~]|"+F+")"+F+"*"),V=new RegExp("="+F+"*([^\\]'\"]*?)"+F+"*\\]","g"),B=new RegExp(U),z=new RegExp("^"+P+"$"),$={ID:new RegExp("^#("+P+")"),CLASS:new RegExp("^\\.("+P+")"),TAG:new RegExp("^("+P+"|[*])"),ATTR:new RegExp("^"+R),PSEUDO:new RegExp("^"+U),CHILD:new RegExp("^:(only|first|last|nth|nth-last)-(child|of-type)(?:\\("+F+"*(even|odd|(([+-]|)(\\d*)n|)"+F+"*(?:([+-]|)"+F+"*(\\d+)|))"+F+"*\\)|)","i"),bool:new RegExp("^(?:"+j+")$","i"),needsContext:new RegExp("^"+F+"*[>+~]|:(even|odd|eq|gt|lt|nth|first|last)(?:\\("+F+"*((?:-\\d)?\\d*)"+F+"*\\)|)(?=[^-]|$)","i")},G=/^(?:input|select|textarea|button)$/i,K=/^h\d$/i,X=/^[^{]+\{\s*\[native \w/,Q=/^(?:#([\w-]+)|(\w+)|\.([\w-]+))$/,Z=/[+~]/,J=new RegExp("\\\\([\\da-f]{1,6}"+F+"?|("+F+")|.)","ig"),ee=function(e,t,n){var i="0x"+t-65536;return i!=i||n?t:i<0?String.fromCharCode(i+65536):String.fromCharCode(i>>10|55296,1023&i|56320)},te=/([\0-\x1f\x7f]|^-?\d)|^-$|[^\0-\x1f\x7f-\uFFFF\w-]/g,ne=function(e,t){return t?"\0"===e?"�":e.slice(0,-1)+"\\"+e.charCodeAt(e.length-1).toString(16)+" ":"\\"+e},ie=function(){f()},re=ye((function(e){return!0===e.disabled&&("form"in e||"label"in e)}),{dir:"parentNode",next:"legend"});try{N.apply(A=I.call(w.childNodes),w.childNodes),A[w.childNodes.length].nodeType}catch(e){N={apply:A.length?function(e,t){M.apply(e,I.call(t))}:function(e,t){for(var n=e.length,i=0;e[n++]=t[i++];);e.length=n-1}}}function oe(e,t,i,r){var o,s,u,c,d,p,v,y=t&&t.ownerDocument,x=t?t.nodeType:9;if(i=i||[],"string"!=typeof e||!e||1!==x&&9!==x&&11!==x)return i;if(!r&&((t?t.ownerDocument||t:w)!==h&&f(t),t=t||h,m)){if(11!==x&&(d=Q.exec(e)))if(o=d[1]){if(9===x){if(!(u=t.getElementById(o)))return i;if(u.id===o)return i.push(u),i}else if(y&&(u=y.getElementById(o))&&b(t,u)&&u.id===o)return i.push(u),i}else{if(d[2])return N.apply(i,t.getElementsByTagName(e)),i;if((o=d[3])&&n.getElementsByClassName&&t.getElementsByClassName)return N.apply(i,t.getElementsByClassName(o)),i}if(n.qsa&&!k[e+" "]&&(!g||!g.test(e))){if(1!==x)y=t,v=e;else if("object"!==t.nodeName.toLowerCase()){for((c=t.getAttribute("id"))?c=c.replace(te,ne):t.setAttribute("id",c=_),s=(p=a(e)).length;s--;)p[s]="#"+c+" "+ve(p[s]);v=p.join(","),y=Z.test(e)&&me(t.parentNode)||t}if(v)try{return N.apply(i,y.querySelectorAll(v)),i}catch(e){}finally{c===_&&t.removeAttribute("id")}}}return l(e.replace(H,"$1"),t,i,r)}function ae(){var e=[];return function t(n,r){return e.push(n+" ")>i.cacheLength&&delete t[e.shift()],t[n+" "]=r}}function se(e){return e[_]=!0,e}function le(e){var t=h.createElement("fieldset");try{return!!e(t)}catch(e){return!1}finally{t.parentNode&&t.parentNode.removeChild(t),t=null}}function ue(e,t){for(var n=e.split("|"),r=n.length;r--;)i.attrHandle[n[r]]=t}function ce(e,t){var n=t&&e,i=n&&1===e.nodeType&&1===t.nodeType&&e.sourceIndex-t.sourceIndex;if(i)return i;if(n)for(;n=n.nextSibling;)if(n===t)return-1;return e?1:-1}function de(e){return function(t){return"input"===t.nodeName.toLowerCase()&&t.type===e}}function fe(e){return function(t){var n=t.nodeName.toLowerCase();return("input"===n||"button"===n)&&t.type===e}}function he(e){return function(t){return"form"in t?t.parentNode&&!1===t.disabled?"label"in t?"label"in t.parentNode?t.parentNode.disabled===e:t.disabled===e:t.isDisabled===e||t.isDisabled!==!e&&re(t)===e:t.disabled===e:"label"in t&&t.disabled===e}}function pe(e){return se((function(t){return t=+t,se((function(n,i){for(var r,o=e([],n.length,t),a=o.length;a--;)n[r=o[a]]&&(n[r]=!(i[r]=n[r]))}))}))}function me(e){return e&&void 0!==e.getElementsByTagName&&e}for(t in n=oe.support={},o=oe.isXML=function(e){var t=e&&(e.ownerDocument||e).documentElement;return!!t&&"HTML"!==t.nodeName},f=oe.setDocument=function(e){var t,r,a=e?e.ownerDocument||e:w;return a!==h&&9===a.nodeType&&a.documentElement?(p=(h=a).documentElement,m=!o(h),w!==h&&(r=h.defaultView)&&r.top!==r&&(r.addEventListener?r.addEventListener("unload",ie,!1):r.attachEvent&&r.attachEvent("onunload",ie)),n.attributes=le((function(e){return e.className="i",!e.getAttribute("className")})),n.getElementsByTagName=le((function(e){return e.appendChild(h.createComment("")),!e.getElementsByTagName("*").length})),n.getElementsByClassName=X.test(h.getElementsByClassName),n.getById=le((function(e){return p.appendChild(e).id=_,!h.getElementsByName||!h.getElementsByName(_).length})),n.getById?(i.filter.ID=function(e){var t=e.replace(J,ee);return function(e){return e.getAttribute("id")===t}},i.find.ID=function(e,t){if(void 0!==t.getElementById&&m){var n=t.getElementById(e);return n?[n]:[]}}):(i.filter.ID=function(e){var t=e.replace(J,ee);return function(e){var n=void 0!==e.getAttributeNode&&e.getAttributeNode("id");return n&&n.value===t}},i.find.ID=function(e,t){if(void 0!==t.getElementById&&m){var n,i,r,o=t.getElementById(e);if(o){if((n=o.getAttributeNode("id"))&&n.value===e)return[o];for(r=t.getElementsByName(e),i=0;o=r[i++];)if((n=o.getAttributeNode("id"))&&n.value===e)return[o]}return[]}}),i.find.TAG=n.getElementsByTagName?function(e,t){return void 0!==t.getElementsByTagName?t.getElementsByTagName(e):n.qsa?t.querySelectorAll(e):void 0}:function(e,t){var n,i=[],r=0,o=t.getElementsByTagName(e);if("*"===e){for(;n=o[r++];)1===n.nodeType&&i.push(n);return i}return o},i.find.CLASS=n.getElementsByClassName&&function(e,t){if(void 0!==t.getElementsByClassName&&m)return t.getElementsByClassName(e)},v=[],g=[],(n.qsa=X.test(h.querySelectorAll))&&(le((function(e){p.appendChild(e).innerHTML="<a id='"+_+"'></a><select id='"+_+"-\r\\' msallowcapture=''><option selected=''></option></select>",e.querySelectorAll("[msallowcapture^='']").length&&g.push("[*^$]="+F+"*(?:''|\"\")"),e.querySelectorAll("[selected]").length||g.push("\\["+F+"*(?:value|"+j+")"),e.querySelectorAll("[id~="+_+"-]").length||g.push("~="),e.querySelectorAll(":checked").length||g.push(":checked"),e.querySelectorAll("a#"+_+"+*").length||g.push(".#.+[+~]")})),le((function(e){e.innerHTML="<a href='' disabled='disabled'></a><select disabled='disabled'><option/></select>";var t=h.createElement("input");t.setAttribute("type","hidden"),e.appendChild(t).setAttribute("name","D"),e.querySelectorAll("[name=d]").length&&g.push("name"+F+"*[*^$|!~]?="),2!==e.querySelectorAll(":enabled").length&&g.push(":enabled",":disabled"),p.appendChild(e).disabled=!0,2!==e.querySelectorAll(":disabled").length&&g.push(":enabled",":disabled"),e.querySelectorAll("*,:x"),g.push(",.*:")}))),(n.matchesSelector=X.test(y=p.matches||p.webkitMatchesSelector||p.mozMatchesSelector||p.oMatchesSelector||p.msMatchesSelector))&&le((function(e){n.disconnectedMatch=y.call(e,"*"),y.call(e,"[s!='']:x"),v.push("!=",U)})),g=g.length&&new RegExp(g.join("|")),v=v.length&&new RegExp(v.join("|")),t=X.test(p.compareDocumentPosition),b=t||X.test(p.contains)?function(e,t){var n=9===e.nodeType?e.documentElement:e,i=t&&t.parentNode;return e===i||!(!i||1!==i.nodeType||!(n.contains?n.contains(i):e.compareDocumentPosition&&16&e.compareDocumentPosition(i)))}:function(e,t){if(t)for(;t=t.parentNode;)if(t===e)return!0;return!1},E=t?function(e,t){if(e===t)return d=!0,0;var i=!e.compareDocumentPosition-!t.compareDocumentPosition;return i||(1&(i=(e.ownerDocument||e)===(t.ownerDocument||t)?e.compareDocumentPosition(t):1)||!n.sortDetached&&t.compareDocumentPosition(e)===i?e===h||e.ownerDocument===w&&b(w,e)?-1:t===h||t.ownerDocument===w&&b(w,t)?1:c?L(c,e)-L(c,t):0:4&i?-1:1)}:function(e,t){if(e===t)return d=!0,0;var n,i=0,r=e.parentNode,o=t.parentNode,a=[e],s=[t];if(!r||!o)return e===h?-1:t===h?1:r?-1:o?1:c?L(c,e)-L(c,t):0;if(r===o)return ce(e,t);for(n=e;n=n.parentNode;)a.unshift(n);for(n=t;n=n.parentNode;)s.unshift(n);for(;a[i]===s[i];)i++;return i?ce(a[i],s[i]):a[i]===w?-1:s[i]===w?1:0},h):h},oe.matches=function(e,t){return oe(e,null,null,t)},oe.matchesSelector=function(e,t){if((e.ownerDocument||e)!==h&&f(e),t=t.replace(V,"='$1']"),n.matchesSelector&&m&&!k[t+" "]&&(!v||!v.test(t))&&(!g||!g.test(t)))try{var i=y.call(e,t);if(i||n.disconnectedMatch||e.document&&11!==e.document.nodeType)return i}catch(e){}return oe(t,h,null,[e]).length>0},oe.contains=function(e,t){return(e.ownerDocument||e)!==h&&f(e),b(e,t)},oe.attr=function(e,t){(e.ownerDocument||e)!==h&&f(e);var r=i.attrHandle[t.toLowerCase()],o=r&&S.call(i.attrHandle,t.toLowerCase())?r(e,t,!m):void 0;return void 0!==o?o:n.attributes||!m?e.getAttribute(t):(o=e.getAttributeNode(t))&&o.specified?o.value:null},oe.escape=function(e){return(e+"").replace(te,ne)},oe.error=function(e){throw new Error("Syntax error, unrecognized expression: "+e)},oe.uniqueSort=function(e){var t,i=[],r=0,o=0;if(d=!n.detectDuplicates,c=!n.sortStable&&e.slice(0),e.sort(E),d){for(;t=e[o++];)t===e[o]&&(r=i.push(o));for(;r--;)e.splice(i[r],1)}return c=null,e},r=oe.getText=function(e){var t,n="",i=0,o=e.nodeType;if(o){if(1===o||9===o||11===o){if("string"==typeof e.textContent)return e.textContent;for(e=e.firstChild;e;e=e.nextSibling)n+=r(e)}else if(3===o||4===o)return e.nodeValue}else for(;t=e[i++];)n+=r(t);return n},(i=oe.selectors={cacheLength:50,createPseudo:se,match:$,attrHandle:{},find:{},relative:{">":{dir:"parentNode",first:!0}," ":{dir:"parentNode"},"+":{dir:"previousSibling",first:!0},"~":{dir:"previousSibling"}},preFilter:{ATTR:function(e){return e[1]=e[1].replace(J,ee),e[3]=(e[3]||e[4]||e[5]||"").replace(J,ee),"~="===e[2]&&(e[3]=" "+e[3]+" "),e.slice(0,4)},CHILD:function(e){return e[1]=e[1].toLowerCase(),"nth"===e[1].slice(0,3)?(e[3]||oe.error(e[0]),e[4]=+(e[4]?e[5]+(e[6]||1):2*("even"===e[3]||"odd"===e[3])),e[5]=+(e[7]+e[8]||"odd"===e[3])):e[3]&&oe.error(e[0]),e},PSEUDO:function(e){var t,n=!e[6]&&e[2];return $.CHILD.test(e[0])?null:(e[3]?e[2]=e[4]||e[5]||"":n&&B.test(n)&&(t=a(n,!0))&&(t=n.indexOf(")",n.length-t)-n.length)&&(e[0]=e[0].slice(0,t),e[2]=n.slice(0,t)),e.slice(0,3))}},filter:{TAG:function(e){var t=e.replace(J,ee).toLowerCase();return"*"===e?function(){return!0}:function(e){return e.nodeName&&e.nodeName.toLowerCase()===t}},CLASS:function(e){var t=T[e+" "];return t||(t=new RegExp("(^|"+F+")"+e+"("+F+"|$)"))&&T(e,(function(e){return t.test("string"==typeof e.className&&e.className||void 0!==e.getAttribute&&e.getAttribute("class")||"")}))},ATTR:function(e,t,n){return function(i){var r=oe.attr(i,e);return null==r?"!="===t:!t||(r+="","="===t?r===n:"!="===t?r!==n:"^="===t?n&&0===r.indexOf(n):"*="===t?n&&r.indexOf(n)>-1:"$="===t?n&&r.slice(-n.length)===n:"~="===t?(" "+r.replace(Y," ")+" ").indexOf(n)>-1:"|="===t&&(r===n||r.slice(0,n.length+1)===n+"-"))}},CHILD:function(e,t,n,i,r){var o="nth"!==e.slice(0,3),a="last"!==e.slice(-4),s="of-type"===t;return 1===i&&0===r?function(e){return!!e.parentNode}:function(t,n,l){var u,c,d,f,h,p,m=o!==a?"nextSibling":"previousSibling",g=t.parentNode,v=s&&t.nodeName.toLowerCase(),y=!l&&!s,b=!1;if(g){if(o){for(;m;){for(f=t;f=f[m];)if(s?f.nodeName.toLowerCase()===v:1===f.nodeType)return!1;p=m="only"===e&&!p&&"nextSibling"}return!0}if(p=[a?g.firstChild:g.lastChild],a&&y){for(b=(h=(u=(c=(d=(f=g)[_]||(f[_]={}))[f.uniqueID]||(d[f.uniqueID]={}))[e]||[])[0]===x&&u[1])&&u[2],f=h&&g.childNodes[h];f=++h&&f&&f[m]||(b=h=0)||p.pop();)if(1===f.nodeType&&++b&&f===t){c[e]=[x,h,b];break}}else if(y&&(b=h=(u=(c=(d=(f=t)[_]||(f[_]={}))[f.uniqueID]||(d[f.uniqueID]={}))[e]||[])[0]===x&&u[1]),!1===b)for(;(f=++h&&f&&f[m]||(b=h=0)||p.pop())&&((s?f.nodeName.toLowerCase()!==v:1!==f.nodeType)||!++b||(y&&((c=(d=f[_]||(f[_]={}))[f.uniqueID]||(d[f.uniqueID]={}))[e]=[x,b]),f!==t)););return(b-=r)===i||b%i==0&&b/i>=0}}},PSEUDO:function(e,t){var n,r=i.pseudos[e]||i.setFilters[e.toLowerCase()]||oe.error("unsupported pseudo: "+e);return r[_]?r(t):r.length>1?(n=[e,e,"",t],i.setFilters.hasOwnProperty(e.toLowerCase())?se((function(e,n){for(var i,o=r(e,t),a=o.length;a--;)e[i=L(e,o[a])]=!(n[i]=o[a])})):function(e){return r(e,0,n)}):r}},pseudos:{not:se((function(e){var t=[],n=[],i=s(e.replace(H,"$1"));return i[_]?se((function(e,t,n,r){for(var o,a=i(e,null,r,[]),s=e.length;s--;)(o=a[s])&&(e[s]=!(t[s]=o))})):function(e,r,o){return t[0]=e,i(t,null,o,n),t[0]=null,!n.pop()}})),has:se((function(e){return function(t){return oe(e,t).length>0}})),contains:se((function(e){return e=e.replace(J,ee),function(t){return(t.textContent||t.innerText||r(t)).indexOf(e)>-1}})),lang:se((function(e){return z.test(e||"")||oe.error("unsupported lang: "+e),e=e.replace(J,ee).toLowerCase(),function(t){var n;do{if(n=m?t.lang:t.getAttribute("xml:lang")||t.getAttribute("lang"))return(n=n.toLowerCase())===e||0===n.indexOf(e+"-")}while((t=t.parentNode)&&1===t.nodeType);return!1}})),target:function(t){var n=e.location&&e.location.hash;return n&&n.slice(1)===t.id},root:function(e){return e===p},focus:function(e){return e===h.activeElement&&(!h.hasFocus||h.hasFocus())&&!!(e.type||e.href||~e.tabIndex)},enabled:he(!1),disabled:he(!0),checked:function(e){var t=e.nodeName.toLowerCase();return"input"===t&&!!e.checked||"option"===t&&!!e.selected},selected:function(e){return e.parentNode&&e.parentNode.selectedIndex,!0===e.selected},empty:function(e){for(e=e.firstChild;e;e=e.nextSibling)if(e.nodeType<6)return!1;return!0},parent:function(e){return!i.pseudos.empty(e)},header:function(e){return K.test(e.nodeName)},input:function(e){return G.test(e.nodeName)},button:function(e){var t=e.nodeName.toLowerCase();return"input"===t&&"button"===e.type||"button"===t},text:function(e){var t;return"input"===e.nodeName.toLowerCase()&&"text"===e.type&&(null==(t=e.getAttribute("type"))||"text"===t.toLowerCase())},first:pe((function(){return[0]})),last:pe((function(e,t){return[t-1]})),eq:pe((function(e,t,n){return[n<0?n+t:n]})),even:pe((function(e,t){for(var n=0;n<t;n+=2)e.push(n);return e})),odd:pe((function(e,t){for(var n=1;n<t;n+=2)e.push(n);return e})),lt:pe((function(e,t,n){for(var i=n<0?n+t:n;--i>=0;)e.push(i);return e})),gt:pe((function(e,t,n){for(var i=n<0?n+t:n;++i<t;)e.push(i);return e}))}}).pseudos.nth=i.pseudos.eq,{radio:!0,checkbox:!0,file:!0,password:!0,image:!0})i.pseudos[t]=de(t);for(t in{submit:!0,reset:!0})i.pseudos[t]=fe(t);function ge(){}function ve(e){for(var t=0,n=e.length,i="";t<n;t++)i+=e[t].value;return i}function ye(e,t,n){var i=t.dir,r=t.next,o=r||i,a=n&&"parentNode"===o,s=D++;return t.first?function(t,n,r){for(;t=t[i];)if(1===t.nodeType||a)return e(t,n,r);return!1}:function(t,n,l){var u,c,d,f=[x,s];if(l){for(;t=t[i];)if((1===t.nodeType||a)&&e(t,n,l))return!0}else for(;t=t[i];)if(1===t.nodeType||a)if(c=(d=t[_]||(t[_]={}))[t.uniqueID]||(d[t.uniqueID]={}),r&&r===t.nodeName.toLowerCase())t=t[i]||t;else{if((u=c[o])&&u[0]===x&&u[1]===s)return f[2]=u[2];if(c[o]=f,f[2]=e(t,n,l))return!0}return!1}}function be(e){return e.length>1?function(t,n,i){for(var r=e.length;r--;)if(!e[r](t,n,i))return!1;return!0}:e[0]}function _e(e,t,n,i,r){for(var o,a=[],s=0,l=e.length,u=null!=t;s<l;s++)(o=e[s])&&(n&&!n(o,i,r)||(a.push(o),u&&t.push(s)));return a}function we(e,t,n,i,r,o){return i&&!i[_]&&(i=we(i)),r&&!r[_]&&(r=we(r,o)),se((function(o,a,s,l){var u,c,d,f=[],h=[],p=a.length,m=o||function(e,t,n){for(var i=0,r=t.length;i<r;i++)oe(e,t[i],n);return n}(t||"*",s.nodeType?[s]:s,[]),g=!e||!o&&t?m:_e(m,f,e,s,l),v=n?r||(o?e:p||i)?[]:a:g;if(n&&n(g,v,s,l),i)for(u=_e(v,h),i(u,[],s,l),c=u.length;c--;)(d=u[c])&&(v[h[c]]=!(g[h[c]]=d));if(o){if(r||e){if(r){for(u=[],c=v.length;c--;)(d=v[c])&&u.push(g[c]=d);r(null,v=[],u,l)}for(c=v.length;c--;)(d=v[c])&&(u=r?L(o,d):f[c])>-1&&(o[u]=!(a[u]=d))}}else v=_e(v===a?v.splice(p,v.length):v),r?r(null,a,v,l):N.apply(a,v)}))}function xe(e){for(var t,n,r,o=e.length,a=i.relative[e[0].type],s=a||i.relative[" "],l=a?1:0,c=ye((function(e){return e===t}),s,!0),d=ye((function(e){return L(t,e)>-1}),s,!0),f=[function(e,n,i){var r=!a&&(i||n!==u)||((t=n).nodeType?c(e,n,i):d(e,n,i));return t=null,r}];l<o;l++)if(n=i.relative[e[l].type])f=[ye(be(f),n)];else{if((n=i.filter[e[l].type].apply(null,e[l].matches))[_]){for(r=++l;r<o&&!i.relative[e[r].type];r++);return we(l>1&&be(f),l>1&&ve(e.slice(0,l-1).concat({value:" "===e[l-2].type?"*":""})).replace(H,"$1"),n,l<r&&xe(e.slice(l,r)),r<o&&xe(e=e.slice(r)),r<o&&ve(e))}f.push(n)}return be(f)}function De(e,t){var n=t.length>0,r=e.length>0,o=function(o,a,s,l,c){var d,p,g,v=0,y="0",b=o&&[],_=[],w=u,D=o||r&&i.find.TAG("*",c),T=x+=null==w?1:Math.random()||.1,C=D.length;for(c&&(u=a===h||a||c);y!==C&&null!=(d=D[y]);y++){if(r&&d){for(p=0,a||d.ownerDocument===h||(f(d),s=!m);g=e[p++];)if(g(d,a||h,s)){l.push(d);break}c&&(x=T)}n&&((d=!g&&d)&&v--,o&&b.push(d))}if(v+=y,n&&y!==v){for(p=0;g=t[p++];)g(b,_,a,s);if(o){if(v>0)for(;y--;)b[y]||_[y]||(_[y]=O.call(l));_=_e(_)}N.apply(l,_),c&&!o&&_.length>0&&v+t.length>1&&oe.uniqueSort(l)}return c&&(x=T,u=w),b};return n?se(o):o}return ge.prototype=i.filters=i.pseudos,i.setFilters=new ge,a=oe.tokenize=function(e,t){var n,r,o,a,s,l,u,c=C[e+" "];if(c)return t?0:c.slice(0);for(s=e,l=[],u=i.preFilter;s;){for(a in n&&!(r=W.exec(s))||(r&&(s=s.slice(r[0].length)||s),l.push(o=[])),n=!1,(r=q.exec(s))&&(n=r.shift(),o.push({value:n,type:r[0].replace(H," ")}),s=s.slice(n.length)),i.filter)!(r=$[a].exec(s))||u[a]&&!(r=u[a](r))||(n=r.shift(),o.push({value:n,type:a,matches:r}),s=s.slice(n.length));if(!n)break}return t?s.length:s?oe.error(e):C(e,l).slice(0)},s=oe.compile=function(e,t){var n,i=[],r=[],o=k[e+" "];if(!o){for(t||(t=a(e)),n=t.length;n--;)(o=xe(t[n]))[_]?i.push(o):r.push(o);(o=k(e,De(r,i))).selector=e}return o},l=oe.select=function(e,t,n,r){var o,l,u,c,d,f="function"==typeof e&&e,h=!r&&a(e=f.selector||e);if(n=n||[],1===h.length){if((l=h[0]=h[0].slice(0)).length>2&&"ID"===(u=l[0]).type&&9===t.nodeType&&m&&i.relative[l[1].type]){if(!(t=(i.find.ID(u.matches[0].replace(J,ee),t)||[])[0]))return n;f&&(t=t.parentNode),e=e.slice(l.shift().value.length)}for(o=$.needsContext.test(e)?0:l.length;o--&&(u=l[o],!i.relative[c=u.type]);)if((d=i.find[c])&&(r=d(u.matches[0].replace(J,ee),Z.test(l[0].type)&&me(t.parentNode)||t))){if(l.splice(o,1),!(e=r.length&&ve(l)))return N.apply(n,r),n;break}}return(f||s(e,h))(r,t,!m,n,!t||Z.test(e)&&me(t.parentNode)||t),n},n.sortStable=_.split("").sort(E).join("")===_,n.detectDuplicates=!!d,f(),n.sortDetached=le((function(e){return 1&e.compareDocumentPosition(h.createElement("fieldset"))})),le((function(e){return e.innerHTML="<a href='#'></a>","#"===e.firstChild.getAttribute("href")}))||ue("type|href|height|width",(function(e,t,n){if(!n)return e.getAttribute(t,"type"===t.toLowerCase()?1:2)})),n.attributes&&le((function(e){return e.innerHTML="<input/>",e.firstChild.setAttribute("value",""),""===e.firstChild.getAttribute("value")}))||ue("value",(function(e,t,n){if(!n&&"input"===e.nodeName.toLowerCase())return e.defaultValue})),le((function(e){return null==e.getAttribute("disabled")}))||ue(j,(function(e,t,n){var i;if(!n)return!0===e[t]?t.toLowerCase():(i=e.getAttributeNode(t))&&i.specified?i.value:null})),oe}(e);_.find=D,_.expr=D.selectors,_.expr[":"]=_.expr.pseudos,_.uniqueSort=_.unique=D.uniqueSort,_.text=D.getText,_.isXMLDoc=D.isXML,_.contains=D.contains,_.escapeSelector=D.escape;var T=function(e,t,n){for(var i=[],r=void 0!==n;(e=e[t])&&9!==e.nodeType;)if(1===e.nodeType){if(r&&_(e).is(n))break;i.push(e)}return i},C=function(e,t){for(var n=[];e;e=e.nextSibling)1===e.nodeType&&e!==t&&n.push(e);return n},k=_.expr.match.needsContext;function E(e,t){return e.nodeName&&e.nodeName.toLowerCase()===t.toLowerCase()}var S=/^<([a-z][^\/\0>:\x20\t\r\n\f]*)[\x20\t\r\n\f]*\/?>(?:<\/\1>|)$/i;function A(e,t,n){return m(t)?_.grep(e,(function(e,i){return!!t.call(e,i,e)!==n})):t.nodeType?_.grep(e,(function(e){return e===t!==n})):"string"!=typeof t?_.grep(e,(function(e){return l.call(t,e)>-1!==n})):_.filter(t,e,n)}_.filter=function(e,t,n){var i=t[0];return n&&(e=":not("+e+")"),1===t.length&&1===i.nodeType?_.find.matchesSelector(i,e)?[i]:[]:_.find.matches(e,_.grep(t,(function(e){return 1===e.nodeType})))},_.fn.extend({find:function(e){var t,n,i=this.length,r=this;if("string"!=typeof e)return this.pushStack(_(e).filter((function(){for(t=0;t<i;t++)if(_.contains(r[t],this))return!0})));for(n=this.pushStack([]),t=0;t<i;t++)_.find(e,r[t],n);return i>1?_.uniqueSort(n):n},filter:function(e){return this.pushStack(A(this,e||[],!1))},not:function(e){return this.pushStack(A(this,e||[],!0))},is:function(e){return!!A(this,"string"==typeof e&&k.test(e)?_(e):e||[],!1).length}});var O,M=/^(?:\s*(<[\w\W]+>)[^>]*|#([\w-]+))$/;(_.fn.init=function(e,t,n){var r,o;if(!e)return this;if(n=n||O,"string"==typeof e){if(!(r="<"===e[0]&&">"===e[e.length-1]&&e.length>=3?[null,e,null]:M.exec(e))||!r[1]&&t)return!t||t.jquery?(t||n).find(e):this.constructor(t).find(e);if(r[1]){if(t=t instanceof _?t[0]:t,_.merge(this,_.parseHTML(r[1],t&&t.nodeType?t.ownerDocument||t:i,!0)),S.test(r[1])&&_.isPlainObject(t))for(r in t)m(this[r])?this[r](t[r]):this.attr(r,t[r]);return this}return(o=i.getElementById(r[2]))&&(this[0]=o,this.length=1),this}return e.nodeType?(this[0]=e,this.length=1,this):m(e)?void 0!==n.ready?n.ready(e):e(_):_.makeArray(e,this)}).prototype=_.fn,O=_(i);var N=/^(?:parents|prev(?:Until|All))/,I={children:!0,contents:!0,next:!0,prev:!0};function L(e,t){for(;(e=e[t])&&1!==e.nodeType;);return e}_.fn.extend({has:function(e){var t=_(e,this),n=t.length;return this.filter((function(){for(var e=0;e<n;e++)if(_.contains(this,t[e]))return!0}))},closest:function(e,t){var n,i=0,r=this.length,o=[],a="string"!=typeof e&&_(e);if(!k.test(e))for(;i<r;i++)for(n=this[i];n&&n!==t;n=n.parentNode)if(n.nodeType<11&&(a?a.index(n)>-1:1===n.nodeType&&_.find.matchesSelector(n,e))){o.push(n);break}return this.pushStack(o.length>1?_.uniqueSort(o):o)},index:function(e){return e?"string"==typeof e?l.call(_(e),this[0]):l.call(this,e.jquery?e[0]:e):this[0]&&this[0].parentNode?this.first().prevAll().length:-1},add:function(e,t){return this.pushStack(_.uniqueSort(_.merge(this.get(),_(e,t))))},addBack:function(e){return this.add(null==e?this.prevObject:this.prevObject.filter(e))}}),_.each({parent:function(e){var t=e.parentNode;return t&&11!==t.nodeType?t:null},parents:function(e){return T(e,"parentNode")},parentsUntil:function(e,t,n){return T(e,"parentNode",n)},next:function(e){return L(e,"nextSibling")},prev:function(e){return L(e,"previousSibling")},nextAll:function(e){return T(e,"nextSibling")},prevAll:function(e){return T(e,"previousSibling")},nextUntil:function(e,t,n){return T(e,"nextSibling",n)},prevUntil:function(e,t,n){return T(e,"previousSibling",n)},siblings:function(e){return C((e.parentNode||{}).firstChild,e)},children:function(e){return C(e.firstChild)},contents:function(e){return E(e,"iframe")?e.contentDocument:(E(e,"template")&&(e=e.content||e),_.merge([],e.childNodes))}},(function(e,t){_.fn[e]=function(n,i){var r=_.map(this,t,n);return"Until"!==e.slice(-5)&&(i=n),i&&"string"==typeof i&&(r=_.filter(i,r)),this.length>1&&(I[e]||_.uniqueSort(r),N.test(e)&&r.reverse()),this.pushStack(r)}}));var j=/[^\x20\t\r\n\f]+/g;function F(e){return e}function P(e){throw e}function R(e,t,n,i){var r;try{e&&m(r=e.promise)?r.call(e).done(t).fail(n):e&&m(r=e.then)?r.call(e,t,n):t.apply(void 0,[e].slice(i))}catch(e){n.apply(void 0,[e])}}_.Callbacks=function(e){e="string"==typeof e?function(e){var t={};return _.each(e.match(j)||[],(function(e,n){t[n]=!0})),t}(e):_.extend({},e);var t,n,i,r,o=[],a=[],s=-1,l=function(){for(r=r||e.once,i=t=!0;a.length;s=-1)for(n=a.shift();++s<o.length;)!1===o[s].apply(n[0],n[1])&&e.stopOnFalse&&(s=o.length,n=!1);e.memory||(n=!1),t=!1,r&&(o=n?[]:"")},u={add:function(){return o&&(n&&!t&&(s=o.length-1,a.push(n)),function t(n){_.each(n,(function(n,i){m(i)?e.unique&&u.has(i)||o.push(i):i&&i.length&&"string"!==b(i)&&t(i)}))}(arguments),n&&!t&&l()),this},remove:function(){return _.each(arguments,(function(e,t){for(var n;(n=_.inArray(t,o,n))>-1;)o.splice(n,1),n<=s&&s--})),this},has:function(e){return e?_.inArray(e,o)>-1:o.length>0},empty:function(){return o&&(o=[]),this},disable:function(){return r=a=[],o=n="",this},disabled:function(){return!o},lock:function(){return r=a=[],n||t||(o=n=""),this},locked:function(){return!!r},fireWith:function(e,n){return r||(n=[e,(n=n||[]).slice?n.slice():n],a.push(n),t||l()),this},fire:function(){return u.fireWith(this,arguments),this},fired:function(){return!!i}};return u},_.extend({Deferred:function(t){var n=[["notify","progress",_.Callbacks("memory"),_.Callbacks("memory"),2],["resolve","done",_.Callbacks("once memory"),_.Callbacks("once memory"),0,"resolved"],["reject","fail",_.Callbacks("once memory"),_.Callbacks("once memory"),1,"rejected"]],i="pending",r={state:function(){return i},always:function(){return o.done(arguments).fail(arguments),this},catch:function(e){return r.then(null,e)},pipe:function(){var e=arguments;return _.Deferred((function(t){_.each(n,(function(n,i){var r=m(e[i[4]])&&e[i[4]];o[i[1]]((function(){var e=r&&r.apply(this,arguments);e&&m(e.promise)?e.promise().progress(t.notify).done(t.resolve).fail(t.reject):t[i[0]+"With"](this,r?[e]:arguments)}))})),e=null})).promise()},then:function(t,i,r){var o=0;function a(t,n,i,r){return function(){var s=this,l=arguments,u=function(){var e,u;if(!(t<o)){if((e=i.apply(s,l))===n.promise())throw new TypeError("Thenable self-resolution");u=e&&("object"==typeof e||"function"==typeof e)&&e.then,m(u)?r?u.call(e,a(o,n,F,r),a(o,n,P,r)):(o++,u.call(e,a(o,n,F,r),a(o,n,P,r),a(o,n,F,n.notifyWith))):(i!==F&&(s=void 0,l=[e]),(r||n.resolveWith)(s,l))}},c=r?u:function(){try{u()}catch(e){_.Deferred.exceptionHook&&_.Deferred.exceptionHook(e,c.stackTrace),t+1>=o&&(i!==P&&(s=void 0,l=[e]),n.rejectWith(s,l))}};t?c():(_.Deferred.getStackHook&&(c.stackTrace=_.Deferred.getStackHook()),e.setTimeout(c))}}return _.Deferred((function(e){n[0][3].add(a(0,e,m(r)?r:F,e.notifyWith)),n[1][3].add(a(0,e,m(t)?t:F)),n[2][3].add(a(0,e,m(i)?i:P))})).promise()},promise:function(e){return null!=e?_.extend(e,r):r}},o={};return _.each(n,(function(e,t){var a=t[2],s=t[5];r[t[1]]=a.add,s&&a.add((function(){i=s}),n[3-e][2].disable,n[3-e][3].disable,n[0][2].lock,n[0][3].lock),a.add(t[3].fire),o[t[0]]=function(){return o[t[0]+"With"](this===o?void 0:this,arguments),this},o[t[0]+"With"]=a.fireWith})),r.promise(o),t&&t.call(o,o),o},when:function(e){var t=arguments.length,n=t,i=Array(n),r=o.call(arguments),a=_.Deferred(),s=function(e){return function(n){i[e]=this,r[e]=arguments.length>1?o.call(arguments):n,--t||a.resolveWith(i,r)}};if(t<=1&&(R(e,a.done(s(n)).resolve,a.reject,!t),"pending"===a.state()||m(r[n]&&r[n].then)))return a.then();for(;n--;)R(r[n],s(n),a.reject);return a.promise()}});var U=/^(Eval|Internal|Range|Reference|Syntax|Type|URI)Error$/;_.Deferred.exceptionHook=function(t,n){e.console&&e.console.warn&&t&&U.test(t.name)&&e.console.warn("jQuery.Deferred exception: "+t.message,t.stack,n)},_.readyException=function(t){e.setTimeout((function(){throw t}))};var Y=_.Deferred();function H(){i.removeEventListener("DOMContentLoaded",H),e.removeEventListener("load",H),_.ready()}_.fn.ready=function(e){return Y.then(e).catch((function(e){_.readyException(e)})),this},_.extend({isReady:!1,readyWait:1,ready:function(e){(!0===e?--_.readyWait:_.isReady)||(_.isReady=!0,!0!==e&&--_.readyWait>0||Y.resolveWith(i,[_]))}}),_.ready.then=Y.then,"complete"===i.readyState||"loading"!==i.readyState&&!i.documentElement.doScroll?e.setTimeout(_.ready):(i.addEventListener("DOMContentLoaded",H),e.addEventListener("load",H));var W=function(e,t,n,i,r,o,a){var s=0,l=e.length,u=null==n;if("object"===b(n))for(s in r=!0,n)W(e,t,s,n[s],!0,o,a);else if(void 0!==i&&(r=!0,m(i)||(a=!0),u&&(a?(t.call(e,i),t=null):(u=t,t=function(e,t,n){return u.call(_(e),n)})),t))for(;s<l;s++)t(e[s],n,a?i:i.call(e[s],s,t(e[s],n)));return r?e:u?t.call(e):l?t(e[0],n):o},q=/^-ms-/,V=/-([a-z])/g;function B(e,t){return t.toUpperCase()}function z(e){return e.replace(q,"ms-").replace(V,B)}var $=function(e){return 1===e.nodeType||9===e.nodeType||!+e.nodeType};function G(){this.expando=_.expando+G.uid++}G.uid=1,G.prototype={cache:function(e){var t=e[this.expando];return t||(t={},$(e)&&(e.nodeType?e[this.expando]=t:Object.defineProperty(e,this.expando,{value:t,configurable:!0}))),t},set:function(e,t,n){var i,r=this.cache(e);if("string"==typeof t)r[z(t)]=n;else for(i in t)r[z(i)]=t[i];return r},get:function(e,t){return void 0===t?this.cache(e):e[this.expando]&&e[this.expando][z(t)]},access:function(e,t,n){return void 0===t||t&&"string"==typeof t&&void 0===n?this.get(e,t):(this.set(e,t,n),void 0!==n?n:t)},remove:function(e,t){var n,i=e[this.expando];if(void 0!==i){if(void 0!==t){n=(t=Array.isArray(t)?t.map(z):(t=z(t))in i?[t]:t.match(j)||[]).length;for(;n--;)delete i[t[n]]}(void 0===t||_.isEmptyObject(i))&&(e.nodeType?e[this.expando]=void 0:delete e[this.expando])}},hasData:function(e){var t=e[this.expando];return void 0!==t&&!_.isEmptyObject(t)}};var K=new G,X=new G,Q=/^(?:\{[\w\W]*\}|\[[\w\W]*\])$/,Z=/[A-Z]/g;function J(e,t,n){var i;if(void 0===n&&1===e.nodeType)if(i="data-"+t.replace(Z,"-$&").toLowerCase(),"string"==typeof(n=e.getAttribute(i))){try{n=function(e){return"true"===e||"false"!==e&&("null"===e?null:e===+e+""?+e:Q.test(e)?JSON.parse(e):e)}(n)}catch(e){}X.set(e,t,n)}else n=void 0;return n}_.extend({hasData:function(e){return X.hasData(e)||K.hasData(e)},data:function(e,t,n){return X.access(e,t,n)},removeData:function(e,t){X.remove(e,t)},_data:function(e,t,n){return K.access(e,t,n)},_removeData:function(e,t){K.remove(e,t)}}),_.fn.extend({data:function(e,t){var n,i,r,o=this[0],a=o&&o.attributes;if(void 0===e){if(this.length&&(r=X.get(o),1===o.nodeType&&!K.get(o,"hasDataAttrs"))){for(n=a.length;n--;)a[n]&&0===(i=a[n].name).indexOf("data-")&&(i=z(i.slice(5)),J(o,i,r[i]));K.set(o,"hasDataAttrs",!0)}return r}return"object"==typeof e?this.each((function(){X.set(this,e)})):W(this,(function(t){var n;if(o&&void 0===t){if(void 0!==(n=X.get(o,e)))return n;if(void 0!==(n=J(o,e)))return n}else this.each((function(){X.set(this,e,t)}))}),null,t,arguments.length>1,null,!0)},removeData:function(e){return this.each((function(){X.remove(this,e)}))}}),_.extend({queue:function(e,t,n){var i;if(e)return t=(t||"fx")+"queue",i=K.get(e,t),n&&(!i||Array.isArray(n)?i=K.access(e,t,_.makeArray(n)):i.push(n)),i||[]},dequeue:function(e,t){t=t||"fx";var n=_.queue(e,t),i=n.length,r=n.shift(),o=_._queueHooks(e,t);"inprogress"===r&&(r=n.shift(),i--),r&&("fx"===t&&n.unshift("inprogress"),delete o.stop,r.call(e,(function(){_.dequeue(e,t)}),o)),!i&&o&&o.empty.fire()},_queueHooks:function(e,t){var n=t+"queueHooks";return K.get(e,n)||K.access(e,n,{empty:_.Callbacks("once memory").add((function(){K.remove(e,[t+"queue",n])}))})}}),_.fn.extend({queue:function(e,t){var n=2;return"string"!=typeof e&&(t=e,e="fx",n--),arguments.length<n?_.queue(this[0],e):void 0===t?this:this.each((function(){var n=_.queue(this,e,t);_._queueHooks(this,e),"fx"===e&&"inprogress"!==n[0]&&_.dequeue(this,e)}))},dequeue:function(e){return this.each((function(){_.dequeue(this,e)}))},clearQueue:function(e){return this.queue(e||"fx",[])},promise:function(e,t){var n,i=1,r=_.Deferred(),o=this,a=this.length,s=function(){--i||r.resolveWith(o,[o])};for("string"!=typeof e&&(t=e,e=void 0),e=e||"fx";a--;)(n=K.get(o[a],e+"queueHooks"))&&n.empty&&(i++,n.empty.add(s));return s(),r.promise(t)}});var ee=/[+-]?(?:\d*\.|)\d+(?:[eE][+-]?\d+|)/.source,te=new RegExp("^(?:([+-])=|)("+ee+")([a-z%]*)$","i"),ne=["Top","Right","Bottom","Left"],ie=function(e,t){return"none"===(e=t||e).style.display||""===e.style.display&&_.contains(e.ownerDocument,e)&&"none"===_.css(e,"display")},re=function(e,t,n,i){var r,o,a={};for(o in t)a[o]=e.style[o],e.style[o]=t[o];for(o in r=n.apply(e,i||[]),t)e.style[o]=a[o];return r};function oe(e,t,n,i){var r,o,a=20,s=i?function(){return i.cur()}:function(){return _.css(e,t,"")},l=s(),u=n&&n[3]||(_.cssNumber[t]?"":"px"),c=(_.cssNumber[t]||"px"!==u&&+l)&&te.exec(_.css(e,t));if(c&&c[3]!==u){for(l/=2,u=u||c[3],c=+l||1;a--;)_.style(e,t,c+u),(1-o)*(1-(o=s()/l||.5))<=0&&(a=0),c/=o;c*=2,_.style(e,t,c+u),n=n||[]}return n&&(c=+c||+l||0,r=n[1]?c+(n[1]+1)*n[2]:+n[2],i&&(i.unit=u,i.start=c,i.end=r)),r}var ae={};function se(e){var t,n=e.ownerDocument,i=e.nodeName,r=ae[i];return r||(t=n.body.appendChild(n.createElement(i)),r=_.css(t,"display"),t.parentNode.removeChild(t),"none"===r&&(r="block"),ae[i]=r,r)}function le(e,t){for(var n,i,r=[],o=0,a=e.length;o<a;o++)(i=e[o]).style&&(n=i.style.display,t?("none"===n&&(r[o]=K.get(i,"display")||null,r[o]||(i.style.display="")),""===i.style.display&&ie(i)&&(r[o]=se(i))):"none"!==n&&(r[o]="none",K.set(i,"display",n)));for(o=0;o<a;o++)null!=r[o]&&(e[o].style.display=r[o]);return e}_.fn.extend({show:function(){return le(this,!0)},hide:function(){return le(this)},toggle:function(e){return"boolean"==typeof e?e?this.show():this.hide():this.each((function(){ie(this)?_(this).show():_(this).hide()}))}});var ue=/^(?:checkbox|radio)$/i,ce=/<([a-z][^\/\0>\x20\t\r\n\f]+)/i,de=/^$|^module$|\/(?:java|ecma)script/i,fe={option:[1,"<select multiple='multiple'>","</select>"],thead:[1,"<table>","</table>"],col:[2,"<table><colgroup>","</colgroup></table>"],tr:[2,"<table><tbody>","</tbody></table>"],td:[3,"<table><tbody><tr>","</tr></tbody></table>"],_default:[0,"",""]};function he(e,t){var n;return n=void 0!==e.getElementsByTagName?e.getElementsByTagName(t||"*"):void 0!==e.querySelectorAll?e.querySelectorAll(t||"*"):[],void 0===t||t&&E(e,t)?_.merge([e],n):n}function pe(e,t){for(var n=0,i=e.length;n<i;n++)K.set(e[n],"globalEval",!t||K.get(t[n],"globalEval"))}fe.optgroup=fe.option,fe.tbody=fe.tfoot=fe.colgroup=fe.caption=fe.thead,fe.th=fe.td;var me=/<|&#?\w+;/;function ge(e,t,n,i,r){for(var o,a,s,l,u,c,d=t.createDocumentFragment(),f=[],h=0,p=e.length;h<p;h++)if((o=e[h])||0===o)if("object"===b(o))_.merge(f,o.nodeType?[o]:o);else if(me.test(o)){for(a=a||d.appendChild(t.createElement("div")),s=(ce.exec(o)||["",""])[1].toLowerCase(),l=fe[s]||fe._default,a.innerHTML=l[1]+_.htmlPrefilter(o)+l[2],c=l[0];c--;)a=a.lastChild;_.merge(f,a.childNodes),(a=d.firstChild).textContent=""}else f.push(t.createTextNode(o));for(d.textContent="",h=0;o=f[h++];)if(i&&_.inArray(o,i)>-1)r&&r.push(o);else if(u=_.contains(o.ownerDocument,o),a=he(d.appendChild(o),"script"),u&&pe(a),n)for(c=0;o=a[c++];)de.test(o.type||"")&&n.push(o);return d}!function(){var e=i.createDocumentFragment().appendChild(i.createElement("div")),t=i.createElement("input");t.setAttribute("type","radio"),t.setAttribute("checked","checked"),t.setAttribute("name","t"),e.appendChild(t),p.checkClone=e.cloneNode(!0).cloneNode(!0).lastChild.checked,e.innerHTML="<textarea>x</textarea>",p.noCloneChecked=!!e.cloneNode(!0).lastChild.defaultValue}();var ve=i.documentElement,ye=/^key/,be=/^(?:mouse|pointer|contextmenu|drag|drop)|click/,_e=/^([^.]*)(?:\.(.+)|)/;function we(){return!0}function xe(){return!1}function De(){try{return i.activeElement}catch(e){}}function Te(e,t,n,i,r,o){var a,s;if("object"==typeof t){for(s in"string"!=typeof n&&(i=i||n,n=void 0),t)Te(e,s,n,i,t[s],o);return e}if(null==i&&null==r?(r=n,i=n=void 0):null==r&&("string"==typeof n?(r=i,i=void 0):(r=i,i=n,n=void 0)),!1===r)r=xe;else if(!r)return e;return 1===o&&(a=r,(r=function(e){return _().off(e),a.apply(this,arguments)}).guid=a.guid||(a.guid=_.guid++)),e.each((function(){_.event.add(this,t,r,i,n)}))}_.event={global:{},add:function(e,t,n,i,r){var o,a,s,l,u,c,d,f,h,p,m,g=K.get(e);if(g)for(n.handler&&(n=(o=n).handler,r=o.selector),r&&_.find.matchesSelector(ve,r),n.guid||(n.guid=_.guid++),(l=g.events)||(l=g.events={}),(a=g.handle)||(a=g.handle=function(t){return void 0!==_&&_.event.triggered!==t.type?_.event.dispatch.apply(e,arguments):void 0}),u=(t=(t||"").match(j)||[""]).length;u--;)h=m=(s=_e.exec(t[u])||[])[1],p=(s[2]||"").split(".").sort(),h&&(d=_.event.special[h]||{},h=(r?d.delegateType:d.bindType)||h,d=_.event.special[h]||{},c=_.extend({type:h,origType:m,data:i,handler:n,guid:n.guid,selector:r,needsContext:r&&_.expr.match.needsContext.test(r),namespace:p.join(".")},o),(f=l[h])||((f=l[h]=[]).delegateCount=0,d.setup&&!1!==d.setup.call(e,i,p,a)||e.addEventListener&&e.addEventListener(h,a)),d.add&&(d.add.call(e,c),c.handler.guid||(c.handler.guid=n.guid)),r?f.splice(f.delegateCount++,0,c):f.push(c),_.event.global[h]=!0)},remove:function(e,t,n,i,r){var o,a,s,l,u,c,d,f,h,p,m,g=K.hasData(e)&&K.get(e);if(g&&(l=g.events)){for(u=(t=(t||"").match(j)||[""]).length;u--;)if(h=m=(s=_e.exec(t[u])||[])[1],p=(s[2]||"").split(".").sort(),h){for(d=_.event.special[h]||{},f=l[h=(i?d.delegateType:d.bindType)||h]||[],s=s[2]&&new RegExp("(^|\\.)"+p.join("\\.(?:.*\\.|)")+"(\\.|$)"),a=o=f.length;o--;)c=f[o],!r&&m!==c.origType||n&&n.guid!==c.guid||s&&!s.test(c.namespace)||i&&i!==c.selector&&("**"!==i||!c.selector)||(f.splice(o,1),c.selector&&f.delegateCount--,d.remove&&d.remove.call(e,c));a&&!f.length&&(d.teardown&&!1!==d.teardown.call(e,p,g.handle)||_.removeEvent(e,h,g.handle),delete l[h])}else for(h in l)_.event.remove(e,h+t[u],n,i,!0);_.isEmptyObject(l)&&K.remove(e,"handle events")}},dispatch:function(e){var t,n,i,r,o,a,s=_.event.fix(e),l=new Array(arguments.length),u=(K.get(this,"events")||{})[s.type]||[],c=_.event.special[s.type]||{};for(l[0]=s,t=1;t<arguments.length;t++)l[t]=arguments[t];if(s.delegateTarget=this,!c.preDispatch||!1!==c.preDispatch.call(this,s)){for(a=_.event.handlers.call(this,s,u),t=0;(r=a[t++])&&!s.isPropagationStopped();)for(s.currentTarget=r.elem,n=0;(o=r.handlers[n++])&&!s.isImmediatePropagationStopped();)s.rnamespace&&!s.rnamespace.test(o.namespace)||(s.handleObj=o,s.data=o.data,void 0!==(i=((_.event.special[o.origType]||{}).handle||o.handler).apply(r.elem,l))&&!1===(s.result=i)&&(s.preventDefault(),s.stopPropagation()));return c.postDispatch&&c.postDispatch.call(this,s),s.result}},handlers:function(e,t){var n,i,r,o,a,s=[],l=t.delegateCount,u=e.target;if(l&&u.nodeType&&!("click"===e.type&&e.button>=1))for(;u!==this;u=u.parentNode||this)if(1===u.nodeType&&("click"!==e.type||!0!==u.disabled)){for(o=[],a={},n=0;n<l;n++)void 0===a[r=(i=t[n]).selector+" "]&&(a[r]=i.needsContext?_(r,this).index(u)>-1:_.find(r,this,null,[u]).length),a[r]&&o.push(i);o.length&&s.push({elem:u,handlers:o})}return u=this,l<t.length&&s.push({elem:u,handlers:t.slice(l)}),s},addProp:function(e,t){Object.defineProperty(_.Event.prototype,e,{enumerable:!0,configurable:!0,get:m(t)?function(){if(this.originalEvent)return t(this.originalEvent)}:function(){if(this.originalEvent)return this.originalEvent[e]},set:function(t){Object.defineProperty(this,e,{enumerable:!0,configurable:!0,writable:!0,value:t})}})},fix:function(e){return e[_.expando]?e:new _.Event(e)},special:{load:{noBubble:!0},focus:{trigger:function(){if(this!==De()&&this.focus)return this.focus(),!1},delegateType:"focusin"},blur:{trigger:function(){if(this===De()&&this.blur)return this.blur(),!1},delegateType:"focusout"},click:{trigger:function(){if("checkbox"===this.type&&this.click&&E(this,"input"))return this.click(),!1},_default:function(e){return E(e.target,"a")}},beforeunload:{postDispatch:function(e){void 0!==e.result&&e.originalEvent&&(e.originalEvent.returnValue=e.result)}}}},_.removeEvent=function(e,t,n){e.removeEventListener&&e.removeEventListener(t,n)},_.Event=function(e,t){if(!(this instanceof _.Event))return new _.Event(e,t);e&&e.type?(this.originalEvent=e,this.type=e.type,this.isDefaultPrevented=e.defaultPrevented||void 0===e.defaultPrevented&&!1===e.returnValue?we:xe,this.target=e.target&&3===e.target.nodeType?e.target.parentNode:e.target,this.currentTarget=e.currentTarget,this.relatedTarget=e.relatedTarget):this.type=e,t&&_.extend(this,t),this.timeStamp=e&&e.timeStamp||Date.now(),this[_.expando]=!0},_.Event.prototype={constructor:_.Event,isDefaultPrevented:xe,isPropagationStopped:xe,isImmediatePropagationStopped:xe,isSimulated:!1,preventDefault:function(){var e=this.originalEvent;this.isDefaultPrevented=we,e&&!this.isSimulated&&e.preventDefault()},stopPropagation:function(){var e=this.originalEvent;this.isPropagationStopped=we,e&&!this.isSimulated&&e.stopPropagation()},stopImmediatePropagation:function(){var e=this.originalEvent;this.isImmediatePropagationStopped=we,e&&!this.isSimulated&&e.stopImmediatePropagation(),this.stopPropagation()}},_.each({altKey:!0,bubbles:!0,cancelable:!0,changedTouches:!0,ctrlKey:!0,detail:!0,eventPhase:!0,metaKey:!0,pageX:!0,pageY:!0,shiftKey:!0,view:!0,char:!0,charCode:!0,key:!0,keyCode:!0,button:!0,buttons:!0,clientX:!0,clientY:!0,offsetX:!0,offsetY:!0,pointerId:!0,pointerType:!0,screenX:!0,screenY:!0,targetTouches:!0,toElement:!0,touches:!0,which:function(e){var t=e.button;return null==e.which&&ye.test(e.type)?null!=e.charCode?e.charCode:e.keyCode:!e.which&&void 0!==t&&be.test(e.type)?1&t?1:2&t?3:4&t?2:0:e.which}},_.event.addProp),_.each({mouseenter:"mouseover",mouseleave:"mouseout",pointerenter:"pointerover",pointerleave:"pointerout"},(function(e,t){_.event.special[e]={delegateType:t,bindType:t,handle:function(e){var n,i=this,r=e.relatedTarget,o=e.handleObj;return r&&(r===i||_.contains(i,r))||(e.type=o.origType,n=o.handler.apply(this,arguments),e.type=t),n}}})),_.fn.extend({on:function(e,t,n,i){return Te(this,e,t,n,i)},one:function(e,t,n,i){return Te(this,e,t,n,i,1)},off:function(e,t,n){var i,r;if(e&&e.preventDefault&&e.handleObj)return i=e.handleObj,_(e.delegateTarget).off(i.namespace?i.origType+"."+i.namespace:i.origType,i.selector,i.handler),this;if("object"==typeof e){for(r in e)this.off(r,t,e[r]);return this}return!1!==t&&"function"!=typeof t||(n=t,t=void 0),!1===n&&(n=xe),this.each((function(){_.event.remove(this,e,n,t)}))}});var Ce=/<(?!area|br|col|embed|hr|img|input|link|meta|param)(([a-z][^\/\0>\x20\t\r\n\f]*)[^>]*)\/>/gi,ke=/<script|<style|<link/i,Ee=/checked\s*(?:[^=]|=\s*.checked.)/i,Se=/^\s*<!(?:\[CDATA\[|--)|(?:\]\]|--)>\s*$/g;function Ae(e,t){return E(e,"table")&&E(11!==t.nodeType?t:t.firstChild,"tr")&&_(e).children("tbody")[0]||e}function Oe(e){return e.type=(null!==e.getAttribute("type"))+"/"+e.type,e}function Me(e){return"true/"===(e.type||"").slice(0,5)?e.type=e.type.slice(5):e.removeAttribute("type"),e}function Ne(e,t){var n,i,r,o,a,s,l,u;if(1===t.nodeType){if(K.hasData(e)&&(o=K.access(e),a=K.set(t,o),u=o.events))for(r in delete a.handle,a.events={},u)for(n=0,i=u[r].length;n<i;n++)_.event.add(t,r,u[r][n]);X.hasData(e)&&(s=X.access(e),l=_.extend({},s),X.set(t,l))}}function Ie(e,t){var n=t.nodeName.toLowerCase();"input"===n&&ue.test(e.type)?t.checked=e.checked:"input"!==n&&"textarea"!==n||(t.defaultValue=e.defaultValue)}function Le(e,t,n,i){t=a.apply([],t);var r,o,s,l,u,c,d=0,f=e.length,h=f-1,g=t[0],v=m(g);if(v||f>1&&"string"==typeof g&&!p.checkClone&&Ee.test(g))return e.each((function(r){var o=e.eq(r);v&&(t[0]=g.call(this,r,o.html())),Le(o,t,n,i)}));if(f&&(o=(r=ge(t,e[0].ownerDocument,!1,e,i)).firstChild,1===r.childNodes.length&&(r=o),o||i)){for(l=(s=_.map(he(r,"script"),Oe)).length;d<f;d++)u=r,d!==h&&(u=_.clone(u,!0,!0),l&&_.merge(s,he(u,"script"))),n.call(e[d],u,d);if(l)for(c=s[s.length-1].ownerDocument,_.map(s,Me),d=0;d<l;d++)u=s[d],de.test(u.type||"")&&!K.access(u,"globalEval")&&_.contains(c,u)&&(u.src&&"module"!==(u.type||"").toLowerCase()?_._evalUrl&&_._evalUrl(u.src):y(u.textContent.replace(Se,""),c,u))}return e}function je(e,t,n){for(var i,r=t?_.filter(t,e):e,o=0;null!=(i=r[o]);o++)n||1!==i.nodeType||_.cleanData(he(i)),i.parentNode&&(n&&_.contains(i.ownerDocument,i)&&pe(he(i,"script")),i.parentNode.removeChild(i));return e}_.extend({htmlPrefilter:function(e){return e.replace(Ce,"<$1></$2>")},clone:function(e,t,n){var i,r,o,a,s=e.cloneNode(!0),l=_.contains(e.ownerDocument,e);if(!(p.noCloneChecked||1!==e.nodeType&&11!==e.nodeType||_.isXMLDoc(e)))for(a=he(s),i=0,r=(o=he(e)).length;i<r;i++)Ie(o[i],a[i]);if(t)if(n)for(o=o||he(e),a=a||he(s),i=0,r=o.length;i<r;i++)Ne(o[i],a[i]);else Ne(e,s);return(a=he(s,"script")).length>0&&pe(a,!l&&he(e,"script")),s},cleanData:function(e){for(var t,n,i,r=_.event.special,o=0;void 0!==(n=e[o]);o++)if($(n)){if(t=n[K.expando]){if(t.events)for(i in t.events)r[i]?_.event.remove(n,i):_.removeEvent(n,i,t.handle);n[K.expando]=void 0}n[X.expando]&&(n[X.expando]=void 0)}}}),_.fn.extend({detach:function(e){return je(this,e,!0)},remove:function(e){return je(this,e)},text:function(e){return W(this,(function(e){return void 0===e?_.text(this):this.empty().each((function(){1!==this.nodeType&&11!==this.nodeType&&9!==this.nodeType||(this.textContent=e)}))}),null,e,arguments.length)},append:function(){return Le(this,arguments,(function(e){1!==this.nodeType&&11!==this.nodeType&&9!==this.nodeType||Ae(this,e).appendChild(e)}))},prepend:function(){return Le(this,arguments,(function(e){if(1===this.nodeType||11===this.nodeType||9===this.nodeType){var t=Ae(this,e);t.insertBefore(e,t.firstChild)}}))},before:function(){return Le(this,arguments,(function(e){this.parentNode&&this.parentNode.insertBefore(e,this)}))},after:function(){return Le(this,arguments,(function(e){this.parentNode&&this.parentNode.insertBefore(e,this.nextSibling)}))},empty:function(){for(var e,t=0;null!=(e=this[t]);t++)1===e.nodeType&&(_.cleanData(he(e,!1)),e.textContent="");return this},clone:function(e,t){return e=null!=e&&e,t=null==t?e:t,this.map((function(){return _.clone(this,e,t)}))},html:function(e){return W(this,(function(e){var t=this[0]||{},n=0,i=this.length;if(void 0===e&&1===t.nodeType)return t.innerHTML;if("string"==typeof e&&!ke.test(e)&&!fe[(ce.exec(e)||["",""])[1].toLowerCase()]){e=_.htmlPrefilter(e);try{for(;n<i;n++)1===(t=this[n]||{}).nodeType&&(_.cleanData(he(t,!1)),t.innerHTML=e);t=0}catch(e){}}t&&this.empty().append(e)}),null,e,arguments.length)},replaceWith:function(){var e=[];return Le(this,arguments,(function(t){var n=this.parentNode;_.inArray(this,e)<0&&(_.cleanData(he(this)),n&&n.replaceChild(t,this))}),e)}}),_.each({appendTo:"append",prependTo:"prepend",insertBefore:"before",insertAfter:"after",replaceAll:"replaceWith"},(function(e,t){_.fn[e]=function(e){for(var n,i=[],r=_(e),o=r.length-1,a=0;a<=o;a++)n=a===o?this:this.clone(!0),_(r[a])[t](n),s.apply(i,n.get());return this.pushStack(i)}}));var Fe=new RegExp("^("+ee+")(?!px)[a-z%]+$","i"),Pe=function(t){var n=t.ownerDocument.defaultView;return n&&n.opener||(n=e),n.getComputedStyle(t)},Re=new RegExp(ne.join("|"),"i");function Ue(e,t,n){var i,r,o,a,s=e.style;return(n=n||Pe(e))&&(""!==(a=n.getPropertyValue(t)||n[t])||_.contains(e.ownerDocument,e)||(a=_.style(e,t)),!p.pixelBoxStyles()&&Fe.test(a)&&Re.test(t)&&(i=s.width,r=s.minWidth,o=s.maxWidth,s.minWidth=s.maxWidth=s.width=a,a=n.width,s.width=i,s.minWidth=r,s.maxWidth=o)),void 0!==a?a+"":a}function Ye(e,t){return{get:function(){if(!e())return(this.get=t).apply(this,arguments);delete this.get}}}!function(){function t(){if(c){u.style.cssText="position:absolute;left:-11111px;width:60px;margin-top:1px;padding:0;border:0",c.style.cssText="position:relative;display:block;box-sizing:border-box;overflow:scroll;margin:auto;border:1px;padding:1px;width:60%;top:1%",ve.appendChild(u).appendChild(c);var t=e.getComputedStyle(c);r="1%"!==t.top,l=12===n(t.marginLeft),c.style.right="60%",s=36===n(t.right),o=36===n(t.width),c.style.position="absolute",a=36===c.offsetWidth||"absolute",ve.removeChild(u),c=null}}function n(e){return Math.round(parseFloat(e))}var r,o,a,s,l,u=i.createElement("div"),c=i.createElement("div");c.style&&(c.style.backgroundClip="content-box",c.cloneNode(!0).style.backgroundClip="",p.clearCloneStyle="content-box"===c.style.backgroundClip,_.extend(p,{boxSizingReliable:function(){return t(),o},pixelBoxStyles:function(){return t(),s},pixelPosition:function(){return t(),r},reliableMarginLeft:function(){return t(),l},scrollboxSize:function(){return t(),a}}))}();var He=/^(none|table(?!-c[ea]).+)/,We=/^--/,qe={position:"absolute",visibility:"hidden",display:"block"},Ve={letterSpacing:"0",fontWeight:"400"},Be=["Webkit","Moz","ms"],ze=i.createElement("div").style;function $e(e){var t=_.cssProps[e];return t||(t=_.cssProps[e]=function(e){if(e in ze)return e;for(var t=e[0].toUpperCase()+e.slice(1),n=Be.length;n--;)if((e=Be[n]+t)in ze)return e}(e)||e),t}function Ge(e,t,n){var i=te.exec(t);return i?Math.max(0,i[2]-(n||0))+(i[3]||"px"):t}function Ke(e,t,n,i,r,o){var a="width"===t?1:0,s=0,l=0;if(n===(i?"border":"content"))return 0;for(;a<4;a+=2)"margin"===n&&(l+=_.css(e,n+ne[a],!0,r)),i?("content"===n&&(l-=_.css(e,"padding"+ne[a],!0,r)),"margin"!==n&&(l-=_.css(e,"border"+ne[a]+"Width",!0,r))):(l+=_.css(e,"padding"+ne[a],!0,r),"padding"!==n?l+=_.css(e,"border"+ne[a]+"Width",!0,r):s+=_.css(e,"border"+ne[a]+"Width",!0,r));return!i&&o>=0&&(l+=Math.max(0,Math.ceil(e["offset"+t[0].toUpperCase()+t.slice(1)]-o-l-s-.5))),l}function Xe(e,t,n){var i=Pe(e),r=Ue(e,t,i),o="border-box"===_.css(e,"boxSizing",!1,i),a=o;if(Fe.test(r)){if(!n)return r;r="auto"}return a=a&&(p.boxSizingReliable()||r===e.style[t]),("auto"===r||!parseFloat(r)&&"inline"===_.css(e,"display",!1,i))&&(r=e["offset"+t[0].toUpperCase()+t.slice(1)],a=!0),(r=parseFloat(r)||0)+Ke(e,t,n||(o?"border":"content"),a,i,r)+"px"}function Qe(e,t,n,i,r){return new Qe.prototype.init(e,t,n,i,r)}_.extend({cssHooks:{opacity:{get:function(e,t){if(t){var n=Ue(e,"opacity");return""===n?"1":n}}}},cssNumber:{animationIterationCount:!0,columnCount:!0,fillOpacity:!0,flexGrow:!0,flexShrink:!0,fontWeight:!0,lineHeight:!0,opacity:!0,order:!0,orphans:!0,widows:!0,zIndex:!0,zoom:!0},cssProps:{},style:function(e,t,n,i){if(e&&3!==e.nodeType&&8!==e.nodeType&&e.style){var r,o,a,s=z(t),l=We.test(t),u=e.style;if(l||(t=$e(s)),a=_.cssHooks[t]||_.cssHooks[s],void 0===n)return a&&"get"in a&&void 0!==(r=a.get(e,!1,i))?r:u[t];"string"==(o=typeof n)&&(r=te.exec(n))&&r[1]&&(n=oe(e,t,r),o="number"),null!=n&&n==n&&("number"===o&&(n+=r&&r[3]||(_.cssNumber[s]?"":"px")),p.clearCloneStyle||""!==n||0!==t.indexOf("background")||(u[t]="inherit"),a&&"set"in a&&void 0===(n=a.set(e,n,i))||(l?u.setProperty(t,n):u[t]=n))}},css:function(e,t,n,i){var r,o,a,s=z(t);return We.test(t)||(t=$e(s)),(a=_.cssHooks[t]||_.cssHooks[s])&&"get"in a&&(r=a.get(e,!0,n)),void 0===r&&(r=Ue(e,t,i)),"normal"===r&&t in Ve&&(r=Ve[t]),""===n||n?(o=parseFloat(r),!0===n||isFinite(o)?o||0:r):r}}),_.each(["height","width"],(function(e,t){_.cssHooks[t]={get:function(e,n,i){if(n)return!He.test(_.css(e,"display"))||e.getClientRects().length&&e.getBoundingClientRect().width?Xe(e,t,i):re(e,qe,(function(){return Xe(e,t,i)}))},set:function(e,n,i){var r,o=Pe(e),a="border-box"===_.css(e,"boxSizing",!1,o),s=i&&Ke(e,t,i,a,o);return a&&p.scrollboxSize()===o.position&&(s-=Math.ceil(e["offset"+t[0].toUpperCase()+t.slice(1)]-parseFloat(o[t])-Ke(e,t,"border",!1,o)-.5)),s&&(r=te.exec(n))&&"px"!==(r[3]||"px")&&(e.style[t]=n,n=_.css(e,t)),Ge(0,n,s)}}})),_.cssHooks.marginLeft=Ye(p.reliableMarginLeft,(function(e,t){if(t)return(parseFloat(Ue(e,"marginLeft"))||e.getBoundingClientRect().left-re(e,{marginLeft:0},(function(){return e.getBoundingClientRect().left})))+"px"})),_.each({margin:"",padding:"",border:"Width"},(function(e,t){_.cssHooks[e+t]={expand:function(n){for(var i=0,r={},o="string"==typeof n?n.split(" "):[n];i<4;i++)r[e+ne[i]+t]=o[i]||o[i-2]||o[0];return r}},"margin"!==e&&(_.cssHooks[e+t].set=Ge)})),_.fn.extend({css:function(e,t){return W(this,(function(e,t,n){var i,r,o={},a=0;if(Array.isArray(t)){for(i=Pe(e),r=t.length;a<r;a++)o[t[a]]=_.css(e,t[a],!1,i);return o}return void 0!==n?_.style(e,t,n):_.css(e,t)}),e,t,arguments.length>1)}}),_.Tween=Qe,Qe.prototype={constructor:Qe,init:function(e,t,n,i,r,o){this.elem=e,this.prop=n,this.easing=r||_.easing._default,this.options=t,this.start=this.now=this.cur(),this.end=i,this.unit=o||(_.cssNumber[n]?"":"px")},cur:function(){var e=Qe.propHooks[this.prop];return e&&e.get?e.get(this):Qe.propHooks._default.get(this)},run:function(e){var t,n=Qe.propHooks[this.prop];return this.options.duration?this.pos=t=_.easing[this.easing](e,this.options.duration*e,0,1,this.options.duration):this.pos=t=e,this.now=(this.end-this.start)*t+this.start,this.options.step&&this.options.step.call(this.elem,this.now,this),n&&n.set?n.set(this):Qe.propHooks._default.set(this),this}},Qe.prototype.init.prototype=Qe.prototype,Qe.propHooks={_default:{get:function(e){var t;return 1!==e.elem.nodeType||null!=e.elem[e.prop]&&null==e.elem.style[e.prop]?e.elem[e.prop]:(t=_.css(e.elem,e.prop,""))&&"auto"!==t?t:0},set:function(e){_.fx.step[e.prop]?_.fx.step[e.prop](e):1!==e.elem.nodeType||null==e.elem.style[_.cssProps[e.prop]]&&!_.cssHooks[e.prop]?e.elem[e.prop]=e.now:_.style(e.elem,e.prop,e.now+e.unit)}}},Qe.propHooks.scrollTop=Qe.propHooks.scrollLeft={set:function(e){e.elem.nodeType&&e.elem.parentNode&&(e.elem[e.prop]=e.now)}},_.easing={linear:function(e){return e},swing:function(e){return.5-Math.cos(e*Math.PI)/2},_default:"swing"},_.fx=Qe.prototype.init,_.fx.step={};var Ze,Je,et=/^(?:toggle|show|hide)$/,tt=/queueHooks$/;function nt(){Je&&(!1===i.hidden&&e.requestAnimationFrame?e.requestAnimationFrame(nt):e.setTimeout(nt,_.fx.interval),_.fx.tick())}function it(){return e.setTimeout((function(){Ze=void 0})),Ze=Date.now()}function rt(e,t){var n,i=0,r={height:e};for(t=t?1:0;i<4;i+=2-t)r["margin"+(n=ne[i])]=r["padding"+n]=e;return t&&(r.opacity=r.width=e),r}function ot(e,t,n){for(var i,r=(at.tweeners[t]||[]).concat(at.tweeners["*"]),o=0,a=r.length;o<a;o++)if(i=r[o].call(n,t,e))return i}function at(e,t,n){var i,r,o=0,a=at.prefilters.length,s=_.Deferred().always((function(){delete l.elem})),l=function(){if(r)return!1;for(var t=Ze||it(),n=Math.max(0,u.startTime+u.duration-t),i=1-(n/u.duration||0),o=0,a=u.tweens.length;o<a;o++)u.tweens[o].run(i);return s.notifyWith(e,[u,i,n]),i<1&&a?n:(a||s.notifyWith(e,[u,1,0]),s.resolveWith(e,[u]),!1)},u=s.promise({elem:e,props:_.extend({},t),opts:_.extend(!0,{specialEasing:{},easing:_.easing._default},n),originalProperties:t,originalOptions:n,startTime:Ze||it(),duration:n.duration,tweens:[],createTween:function(t,n){var i=_.Tween(e,u.opts,t,n,u.opts.specialEasing[t]||u.opts.easing);return u.tweens.push(i),i},stop:function(t){var n=0,i=t?u.tweens.length:0;if(r)return this;for(r=!0;n<i;n++)u.tweens[n].run(1);return t?(s.notifyWith(e,[u,1,0]),s.resolveWith(e,[u,t])):s.rejectWith(e,[u,t]),this}}),c=u.props;for(function(e,t){var n,i,r,o,a;for(n in e)if(r=t[i=z(n)],o=e[n],Array.isArray(o)&&(r=o[1],o=e[n]=o[0]),n!==i&&(e[i]=o,delete e[n]),(a=_.cssHooks[i])&&"expand"in a)for(n in o=a.expand(o),delete e[i],o)n in e||(e[n]=o[n],t[n]=r);else t[i]=r}(c,u.opts.specialEasing);o<a;o++)if(i=at.prefilters[o].call(u,e,c,u.opts))return m(i.stop)&&(_._queueHooks(u.elem,u.opts.queue).stop=i.stop.bind(i)),i;return _.map(c,ot,u),m(u.opts.start)&&u.opts.start.call(e,u),u.progress(u.opts.progress).done(u.opts.done,u.opts.complete).fail(u.opts.fail).always(u.opts.always),_.fx.timer(_.extend(l,{elem:e,anim:u,queue:u.opts.queue})),u}_.Animation=_.extend(at,{tweeners:{"*":[function(e,t){var n=this.createTween(e,t);return oe(n.elem,e,te.exec(t),n),n}]},tweener:function(e,t){m(e)?(t=e,e=["*"]):e=e.match(j);for(var n,i=0,r=e.length;i<r;i++)n=e[i],at.tweeners[n]=at.tweeners[n]||[],at.tweeners[n].unshift(t)},prefilters:[function(e,t,n){var i,r,o,a,s,l,u,c,d="width"in t||"height"in t,f=this,h={},p=e.style,m=e.nodeType&&ie(e),g=K.get(e,"fxshow");for(i in n.queue||(null==(a=_._queueHooks(e,"fx")).unqueued&&(a.unqueued=0,s=a.empty.fire,a.empty.fire=function(){a.unqueued||s()}),a.unqueued++,f.always((function(){f.always((function(){a.unqueued--,_.queue(e,"fx").length||a.empty.fire()}))}))),t)if(r=t[i],et.test(r)){if(delete t[i],o=o||"toggle"===r,r===(m?"hide":"show")){if("show"!==r||!g||void 0===g[i])continue;m=!0}h[i]=g&&g[i]||_.style(e,i)}if((l=!_.isEmptyObject(t))||!_.isEmptyObject(h))for(i in d&&1===e.nodeType&&(n.overflow=[p.overflow,p.overflowX,p.overflowY],null==(u=g&&g.display)&&(u=K.get(e,"display")),"none"===(c=_.css(e,"display"))&&(u?c=u:(le([e],!0),u=e.style.display||u,c=_.css(e,"display"),le([e]))),("inline"===c||"inline-block"===c&&null!=u)&&"none"===_.css(e,"float")&&(l||(f.done((function(){p.display=u})),null==u&&(c=p.display,u="none"===c?"":c)),p.display="inline-block")),n.overflow&&(p.overflow="hidden",f.always((function(){p.overflow=n.overflow[0],p.overflowX=n.overflow[1],p.overflowY=n.overflow[2]}))),l=!1,h)l||(g?"hidden"in g&&(m=g.hidden):g=K.access(e,"fxshow",{display:u}),o&&(g.hidden=!m),m&&le([e],!0),f.done((function(){for(i in m||le([e]),K.remove(e,"fxshow"),h)_.style(e,i,h[i])}))),l=ot(m?g[i]:0,i,f),i in g||(g[i]=l.start,m&&(l.end=l.start,l.start=0))}],prefilter:function(e,t){t?at.prefilters.unshift(e):at.prefilters.push(e)}}),_.speed=function(e,t,n){var i=e&&"object"==typeof e?_.extend({},e):{complete:n||!n&&t||m(e)&&e,duration:e,easing:n&&t||t&&!m(t)&&t};return _.fx.off?i.duration=0:"number"!=typeof i.duration&&(i.duration in _.fx.speeds?i.duration=_.fx.speeds[i.duration]:i.duration=_.fx.speeds._default),null!=i.queue&&!0!==i.queue||(i.queue="fx"),i.old=i.complete,i.complete=function(){m(i.old)&&i.old.call(this),i.queue&&_.dequeue(this,i.queue)},i},_.fn.extend({fadeTo:function(e,t,n,i){return this.filter(ie).css("opacity",0).show().end().animate({opacity:t},e,n,i)},animate:function(e,t,n,i){var r=_.isEmptyObject(e),o=_.speed(t,n,i),a=function(){var t=at(this,_.extend({},e),o);(r||K.get(this,"finish"))&&t.stop(!0)};return a.finish=a,r||!1===o.queue?this.each(a):this.queue(o.queue,a)},stop:function(e,t,n){var i=function(e){var t=e.stop;delete e.stop,t(n)};return"string"!=typeof e&&(n=t,t=e,e=void 0),t&&!1!==e&&this.queue(e||"fx",[]),this.each((function(){var t=!0,r=null!=e&&e+"queueHooks",o=_.timers,a=K.get(this);if(r)a[r]&&a[r].stop&&i(a[r]);else for(r in a)a[r]&&a[r].stop&&tt.test(r)&&i(a[r]);for(r=o.length;r--;)o[r].elem!==this||null!=e&&o[r].queue!==e||(o[r].anim.stop(n),t=!1,o.splice(r,1));!t&&n||_.dequeue(this,e)}))},finish:function(e){return!1!==e&&(e=e||"fx"),this.each((function(){var t,n=K.get(this),i=n[e+"queue"],r=n[e+"queueHooks"],o=_.timers,a=i?i.length:0;for(n.finish=!0,_.queue(this,e,[]),r&&r.stop&&r.stop.call(this,!0),t=o.length;t--;)o[t].elem===this&&o[t].queue===e&&(o[t].anim.stop(!0),o.splice(t,1));for(t=0;t<a;t++)i[t]&&i[t].finish&&i[t].finish.call(this);delete n.finish}))}}),_.each(["toggle","show","hide"],(function(e,t){var n=_.fn[t];_.fn[t]=function(e,i,r){return null==e||"boolean"==typeof e?n.apply(this,arguments):this.animate(rt(t,!0),e,i,r)}})),_.each({slideDown:rt("show"),slideUp:rt("hide"),slideToggle:rt("toggle"),fadeIn:{opacity:"show"},fadeOut:{opacity:"hide"},fadeToggle:{opacity:"toggle"}},(function(e,t){_.fn[e]=function(e,n,i){return this.animate(t,e,n,i)}})),_.timers=[],_.fx.tick=function(){var e,t=0,n=_.timers;for(Ze=Date.now();t<n.length;t++)(e=n[t])()||n[t]!==e||n.splice(t--,1);n.length||_.fx.stop(),Ze=void 0},_.fx.timer=function(e){_.timers.push(e),_.fx.start()},_.fx.interval=13,_.fx.start=function(){Je||(Je=!0,nt())},_.fx.stop=function(){Je=null},_.fx.speeds={slow:600,fast:200,_default:400},_.fn.delay=function(t,n){return t=_.fx&&_.fx.speeds[t]||t,n=n||"fx",this.queue(n,(function(n,i){var r=e.setTimeout(n,t);i.stop=function(){e.clearTimeout(r)}}))},function(){var e=i.createElement("input"),t=i.createElement("select").appendChild(i.createElement("option"));e.type="checkbox",p.checkOn=""!==e.value,p.optSelected=t.selected,(e=i.createElement("input")).value="t",e.type="radio",p.radioValue="t"===e.value}();var st,lt=_.expr.attrHandle;_.fn.extend({attr:function(e,t){return W(this,_.attr,e,t,arguments.length>1)},removeAttr:function(e){return this.each((function(){_.removeAttr(this,e)}))}}),_.extend({attr:function(e,t,n){var i,r,o=e.nodeType;if(3!==o&&8!==o&&2!==o)return void 0===e.getAttribute?_.prop(e,t,n):(1===o&&_.isXMLDoc(e)||(r=_.attrHooks[t.toLowerCase()]||(_.expr.match.bool.test(t)?st:void 0)),void 0!==n?null===n?void _.removeAttr(e,t):r&&"set"in r&&void 0!==(i=r.set(e,n,t))?i:(e.setAttribute(t,n+""),n):r&&"get"in r&&null!==(i=r.get(e,t))?i:null==(i=_.find.attr(e,t))?void 0:i)},attrHooks:{type:{set:function(e,t){if(!p.radioValue&&"radio"===t&&E(e,"input")){var n=e.value;return e.setAttribute("type",t),n&&(e.value=n),t}}}},removeAttr:function(e,t){var n,i=0,r=t&&t.match(j);if(r&&1===e.nodeType)for(;n=r[i++];)e.removeAttribute(n)}}),st={set:function(e,t,n){return!1===t?_.removeAttr(e,n):e.setAttribute(n,n),n}},_.each(_.expr.match.bool.source.match(/\w+/g),(function(e,t){var n=lt[t]||_.find.attr;lt[t]=function(e,t,i){var r,o,a=t.toLowerCase();return i||(o=lt[a],lt[a]=r,r=null!=n(e,t,i)?a:null,lt[a]=o),r}}));var ut=/^(?:input|select|textarea|button)$/i,ct=/^(?:a|area)$/i;function dt(e){return(e.match(j)||[]).join(" ")}function ft(e){return e.getAttribute&&e.getAttribute("class")||""}function ht(e){return Array.isArray(e)?e:"string"==typeof e&&e.match(j)||[]}_.fn.extend({prop:function(e,t){return W(this,_.prop,e,t,arguments.length>1)},removeProp:function(e){return this.each((function(){delete this[_.propFix[e]||e]}))}}),_.extend({prop:function(e,t,n){var i,r,o=e.nodeType;if(3!==o&&8!==o&&2!==o)return 1===o&&_.isXMLDoc(e)||(t=_.propFix[t]||t,r=_.propHooks[t]),void 0!==n?r&&"set"in r&&void 0!==(i=r.set(e,n,t))?i:e[t]=n:r&&"get"in r&&null!==(i=r.get(e,t))?i:e[t]},propHooks:{tabIndex:{get:function(e){var t=_.find.attr(e,"tabindex");return t?parseInt(t,10):ut.test(e.nodeName)||ct.test(e.nodeName)&&e.href?0:-1}}},propFix:{for:"htmlFor",class:"className"}}),p.optSelected||(_.propHooks.selected={get:function(e){var t=e.parentNode;return t&&t.parentNode&&t.parentNode.selectedIndex,null},set:function(e){var t=e.parentNode;t&&(t.selectedIndex,t.parentNode&&t.parentNode.selectedIndex)}}),_.each(["tabIndex","readOnly","maxLength","cellSpacing","cellPadding","rowSpan","colSpan","useMap","frameBorder","contentEditable"],(function(){_.propFix[this.toLowerCase()]=this})),_.fn.extend({addClass:function(e){var t,n,i,r,o,a,s,l=0;if(m(e))return this.each((function(t){_(this).addClass(e.call(this,t,ft(this)))}));if((t=ht(e)).length)for(;n=this[l++];)if(r=ft(n),i=1===n.nodeType&&" "+dt(r)+" "){for(a=0;o=t[a++];)i.indexOf(" "+o+" ")<0&&(i+=o+" ");r!==(s=dt(i))&&n.setAttribute("class",s)}return this},removeClass:function(e){var t,n,i,r,o,a,s,l=0;if(m(e))return this.each((function(t){_(this).removeClass(e.call(this,t,ft(this)))}));if(!arguments.length)return this.attr("class","");if((t=ht(e)).length)for(;n=this[l++];)if(r=ft(n),i=1===n.nodeType&&" "+dt(r)+" "){for(a=0;o=t[a++];)for(;i.indexOf(" "+o+" ")>-1;)i=i.replace(" "+o+" "," ");r!==(s=dt(i))&&n.setAttribute("class",s)}return this},toggleClass:function(e,t){var n=typeof e,i="string"===n||Array.isArray(e);return"boolean"==typeof t&&i?t?this.addClass(e):this.removeClass(e):m(e)?this.each((function(n){_(this).toggleClass(e.call(this,n,ft(this),t),t)})):this.each((function(){var t,r,o,a;if(i)for(r=0,o=_(this),a=ht(e);t=a[r++];)o.hasClass(t)?o.removeClass(t):o.addClass(t);else void 0!==e&&"boolean"!==n||((t=ft(this))&&K.set(this,"__className__",t),this.setAttribute&&this.setAttribute("class",t||!1===e?"":K.get(this,"__className__")||""))}))},hasClass:function(e){var t,n,i=0;for(t=" "+e+" ";n=this[i++];)if(1===n.nodeType&&(" "+dt(ft(n))+" ").indexOf(t)>-1)return!0;return!1}});var pt=/\r/g;_.fn.extend({val:function(e){var t,n,i,r=this[0];return arguments.length?(i=m(e),this.each((function(n){var r;1===this.nodeType&&(null==(r=i?e.call(this,n,_(this).val()):e)?r="":"number"==typeof r?r+="":Array.isArray(r)&&(r=_.map(r,(function(e){return null==e?"":e+""}))),(t=_.valHooks[this.type]||_.valHooks[this.nodeName.toLowerCase()])&&"set"in t&&void 0!==t.set(this,r,"value")||(this.value=r))}))):r?(t=_.valHooks[r.type]||_.valHooks[r.nodeName.toLowerCase()])&&"get"in t&&void 0!==(n=t.get(r,"value"))?n:"string"==typeof(n=r.value)?n.replace(pt,""):null==n?"":n:void 0}}),_.extend({valHooks:{option:{get:function(e){var t=_.find.attr(e,"value");return null!=t?t:dt(_.text(e))}},select:{get:function(e){var t,n,i,r=e.options,o=e.selectedIndex,a="select-one"===e.type,s=a?null:[],l=a?o+1:r.length;for(i=o<0?l:a?o:0;i<l;i++)if(((n=r[i]).selected||i===o)&&!n.disabled&&(!n.parentNode.disabled||!E(n.parentNode,"optgroup"))){if(t=_(n).val(),a)return t;s.push(t)}return s},set:function(e,t){for(var n,i,r=e.options,o=_.makeArray(t),a=r.length;a--;)((i=r[a]).selected=_.inArray(_.valHooks.option.get(i),o)>-1)&&(n=!0);return n||(e.selectedIndex=-1),o}}}}),_.each(["radio","checkbox"],(function(){_.valHooks[this]={set:function(e,t){if(Array.isArray(t))return e.checked=_.inArray(_(e).val(),t)>-1}},p.checkOn||(_.valHooks[this].get=function(e){return null===e.getAttribute("value")?"on":e.value})})),p.focusin="onfocusin"in e;var mt=/^(?:focusinfocus|focusoutblur)$/,gt=function(e){e.stopPropagation()};_.extend(_.event,{trigger:function(t,n,r,o){var a,s,l,u,c,f,h,p,v=[r||i],y=d.call(t,"type")?t.type:t,b=d.call(t,"namespace")?t.namespace.split("."):[];if(s=p=l=r=r||i,3!==r.nodeType&&8!==r.nodeType&&!mt.test(y+_.event.triggered)&&(y.indexOf(".")>-1&&(y=(b=y.split(".")).shift(),b.sort()),c=y.indexOf(":")<0&&"on"+y,(t=t[_.expando]?t:new _.Event(y,"object"==typeof t&&t)).isTrigger=o?2:3,t.namespace=b.join("."),t.rnamespace=t.namespace?new RegExp("(^|\\.)"+b.join("\\.(?:.*\\.|)")+"(\\.|$)"):null,t.result=void 0,t.target||(t.target=r),n=null==n?[t]:_.makeArray(n,[t]),h=_.event.special[y]||{},o||!h.trigger||!1!==h.trigger.apply(r,n))){if(!o&&!h.noBubble&&!g(r)){for(u=h.delegateType||y,mt.test(u+y)||(s=s.parentNode);s;s=s.parentNode)v.push(s),l=s;l===(r.ownerDocument||i)&&v.push(l.defaultView||l.parentWindow||e)}for(a=0;(s=v[a++])&&!t.isPropagationStopped();)p=s,t.type=a>1?u:h.bindType||y,(f=(K.get(s,"events")||{})[t.type]&&K.get(s,"handle"))&&f.apply(s,n),(f=c&&s[c])&&f.apply&&$(s)&&(t.result=f.apply(s,n),!1===t.result&&t.preventDefault());return t.type=y,o||t.isDefaultPrevented()||h._default&&!1!==h._default.apply(v.pop(),n)||!$(r)||c&&m(r[y])&&!g(r)&&((l=r[c])&&(r[c]=null),_.event.triggered=y,t.isPropagationStopped()&&p.addEventListener(y,gt),r[y](),t.isPropagationStopped()&&p.removeEventListener(y,gt),_.event.triggered=void 0,l&&(r[c]=l)),t.result}},simulate:function(e,t,n){var i=_.extend(new _.Event,n,{type:e,isSimulated:!0});_.event.trigger(i,null,t)}}),_.fn.extend({trigger:function(e,t){return this.each((function(){_.event.trigger(e,t,this)}))},triggerHandler:function(e,t){var n=this[0];if(n)return _.event.trigger(e,t,n,!0)}}),p.focusin||_.each({focus:"focusin",blur:"focusout"},(function(e,t){var n=function(e){_.event.simulate(t,e.target,_.event.fix(e))};_.event.special[t]={setup:function(){var i=this.ownerDocument||this,r=K.access(i,t);r||i.addEventListener(e,n,!0),K.access(i,t,(r||0)+1)},teardown:function(){var i=this.ownerDocument||this,r=K.access(i,t)-1;r?K.access(i,t,r):(i.removeEventListener(e,n,!0),K.remove(i,t))}}}));var vt=e.location,yt=Date.now(),bt=/\?/;_.parseXML=function(t){var n;if(!t||"string"!=typeof t)return null;try{n=(new e.DOMParser).parseFromString(t,"text/xml")}catch(e){n=void 0}return n&&!n.getElementsByTagName("parsererror").length||_.error("Invalid XML: "+t),n};var _t=/\[\]$/,wt=/\r?\n/g,xt=/^(?:submit|button|image|reset|file)$/i,Dt=/^(?:input|select|textarea|keygen)/i;function Tt(e,t,n,i){var r;if(Array.isArray(t))_.each(t,(function(t,r){n||_t.test(e)?i(e,r):Tt(e+"["+("object"==typeof r&&null!=r?t:"")+"]",r,n,i)}));else if(n||"object"!==b(t))i(e,t);else for(r in t)Tt(e+"["+r+"]",t[r],n,i)}_.param=function(e,t){var n,i=[],r=function(e,t){var n=m(t)?t():t;i[i.length]=encodeURIComponent(e)+"="+encodeURIComponent(null==n?"":n)};if(Array.isArray(e)||e.jquery&&!_.isPlainObject(e))_.each(e,(function(){r(this.name,this.value)}));else for(n in e)Tt(n,e[n],t,r);return i.join("&")},_.fn.extend({serialize:function(){return _.param(this.serializeArray())},serializeArray:function(){return this.map((function(){var e=_.prop(this,"elements");return e?_.makeArray(e):this})).filter((function(){var e=this.type;return this.name&&!_(this).is(":disabled")&&Dt.test(this.nodeName)&&!xt.test(e)&&(this.checked||!ue.test(e))})).map((function(e,t){var n=_(this).val();return null==n?null:Array.isArray(n)?_.map(n,(function(e){return{name:t.name,value:e.replace(wt,"\r\n")}})):{name:t.name,value:n.replace(wt,"\r\n")}})).get()}});var Ct=/%20/g,kt=/#.*$/,Et=/([?&])_=[^&]*/,St=/^(.*?):[ \t]*([^\r\n]*)$/gm,At=/^(?:GET|HEAD)$/,Ot=/^\/\//,Mt={},Nt={},It="*/".concat("*"),Lt=i.createElement("a");function jt(e){return function(t,n){"string"!=typeof t&&(n=t,t="*");var i,r=0,o=t.toLowerCase().match(j)||[];if(m(n))for(;i=o[r++];)"+"===i[0]?(i=i.slice(1)||"*",(e[i]=e[i]||[]).unshift(n)):(e[i]=e[i]||[]).push(n)}}function Ft(e,t,n,i){var r={},o=e===Nt;function a(s){var l;return r[s]=!0,_.each(e[s]||[],(function(e,s){var u=s(t,n,i);return"string"!=typeof u||o||r[u]?o?!(l=u):void 0:(t.dataTypes.unshift(u),a(u),!1)})),l}return a(t.dataTypes[0])||!r["*"]&&a("*")}function Pt(e,t){var n,i,r=_.ajaxSettings.flatOptions||{};for(n in t)void 0!==t[n]&&((r[n]?e:i||(i={}))[n]=t[n]);return i&&_.extend(!0,e,i),e}Lt.href=vt.href,_.extend({active:0,lastModified:{},etag:{},ajaxSettings:{url:vt.href,type:"GET",isLocal:/^(?:about|app|app-storage|.+-extension|file|res|widget):$/.test(vt.protocol),global:!0,processData:!0,async:!0,contentType:"application/x-www-form-urlencoded; charset=UTF-8",accepts:{"*":It,text:"text/plain",html:"text/html",xml:"application/xml, text/xml",json:"application/json, text/javascript"},contents:{xml:/\bxml\b/,html:/\bhtml/,json:/\bjson\b/},responseFields:{xml:"responseXML",text:"responseText",json:"responseJSON"},converters:{"* text":String,"text html":!0,"text json":JSON.parse,"text xml":_.parseXML},flatOptions:{url:!0,context:!0}},ajaxSetup:function(e,t){return t?Pt(Pt(e,_.ajaxSettings),t):Pt(_.ajaxSettings,e)},ajaxPrefilter:jt(Mt),ajaxTransport:jt(Nt),ajax:function(t,n){"object"==typeof t&&(n=t,t=void 0),n=n||{};var r,o,a,s,l,u,c,d,f,h,p=_.ajaxSetup({},n),m=p.context||p,g=p.context&&(m.nodeType||m.jquery)?_(m):_.event,v=_.Deferred(),y=_.Callbacks("once memory"),b=p.statusCode||{},w={},x={},D="canceled",T={readyState:0,getResponseHeader:function(e){var t;if(c){if(!s)for(s={};t=St.exec(a);)s[t[1].toLowerCase()]=t[2];t=s[e.toLowerCase()]}return null==t?null:t},getAllResponseHeaders:function(){return c?a:null},setRequestHeader:function(e,t){return null==c&&(e=x[e.toLowerCase()]=x[e.toLowerCase()]||e,w[e]=t),this},overrideMimeType:function(e){return null==c&&(p.mimeType=e),this},statusCode:function(e){var t;if(e)if(c)T.always(e[T.status]);else for(t in e)b[t]=[b[t],e[t]];return this},abort:function(e){var t=e||D;return r&&r.abort(t),C(0,t),this}};if(v.promise(T),p.url=((t||p.url||vt.href)+"").replace(Ot,vt.protocol+"//"),p.type=n.method||n.type||p.method||p.type,p.dataTypes=(p.dataType||"*").toLowerCase().match(j)||[""],null==p.crossDomain){u=i.createElement("a");try{u.href=p.url,u.href=u.href,p.crossDomain=Lt.protocol+"//"+Lt.host!=u.protocol+"//"+u.host}catch(e){p.crossDomain=!0}}if(p.data&&p.processData&&"string"!=typeof p.data&&(p.data=_.param(p.data,p.traditional)),Ft(Mt,p,n,T),c)return T;for(f in(d=_.event&&p.global)&&0==_.active++&&_.event.trigger("ajaxStart"),p.type=p.type.toUpperCase(),p.hasContent=!At.test(p.type),o=p.url.replace(kt,""),p.hasContent?p.data&&p.processData&&0===(p.contentType||"").indexOf("application/x-www-form-urlencoded")&&(p.data=p.data.replace(Ct,"+")):(h=p.url.slice(o.length),p.data&&(p.processData||"string"==typeof p.data)&&(o+=(bt.test(o)?"&":"?")+p.data,delete p.data),!1===p.cache&&(o=o.replace(Et,"$1"),h=(bt.test(o)?"&":"?")+"_="+yt+++h),p.url=o+h),p.ifModified&&(_.lastModified[o]&&T.setRequestHeader("If-Modified-Since",_.lastModified[o]),_.etag[o]&&T.setRequestHeader("If-None-Match",_.etag[o])),(p.data&&p.hasContent&&!1!==p.contentType||n.contentType)&&T.setRequestHeader("Content-Type",p.contentType),T.setRequestHeader("Accept",p.dataTypes[0]&&p.accepts[p.dataTypes[0]]?p.accepts[p.dataTypes[0]]+("*"!==p.dataTypes[0]?", "+It+"; q=0.01":""):p.accepts["*"]),p.headers)T.setRequestHeader(f,p.headers[f]);if(p.beforeSend&&(!1===p.beforeSend.call(m,T,p)||c))return T.abort();if(D="abort",y.add(p.complete),T.done(p.success),T.fail(p.error),r=Ft(Nt,p,n,T)){if(T.readyState=1,d&&g.trigger("ajaxSend",[T,p]),c)return T;p.async&&p.timeout>0&&(l=e.setTimeout((function(){T.abort("timeout")}),p.timeout));try{c=!1,r.send(w,C)}catch(e){if(c)throw e;C(-1,e)}}else C(-1,"No Transport");function C(t,n,i,s){var u,f,h,w,x,D=n;c||(c=!0,l&&e.clearTimeout(l),r=void 0,a=s||"",T.readyState=t>0?4:0,u=t>=200&&t<300||304===t,i&&(w=function(e,t,n){for(var i,r,o,a,s=e.contents,l=e.dataTypes;"*"===l[0];)l.shift(),void 0===i&&(i=e.mimeType||t.getResponseHeader("Content-Type"));if(i)for(r in s)if(s[r]&&s[r].test(i)){l.unshift(r);break}if(l[0]in n)o=l[0];else{for(r in n){if(!l[0]||e.converters[r+" "+l[0]]){o=r;break}a||(a=r)}o=o||a}if(o)return o!==l[0]&&l.unshift(o),n[o]}(p,T,i)),w=function(e,t,n,i){var r,o,a,s,l,u={},c=e.dataTypes.slice();if(c[1])for(a in e.converters)u[a.toLowerCase()]=e.converters[a];for(o=c.shift();o;)if(e.responseFields[o]&&(n[e.responseFields[o]]=t),!l&&i&&e.dataFilter&&(t=e.dataFilter(t,e.dataType)),l=o,o=c.shift())if("*"===o)o=l;else if("*"!==l&&l!==o){if(!(a=u[l+" "+o]||u["* "+o]))for(r in u)if((s=r.split(" "))[1]===o&&(a=u[l+" "+s[0]]||u["* "+s[0]])){!0===a?a=u[r]:!0!==u[r]&&(o=s[0],c.unshift(s[1]));break}if(!0!==a)if(a&&e.throws)t=a(t);else try{t=a(t)}catch(e){return{state:"parsererror",error:a?e:"No conversion from "+l+" to "+o}}}return{state:"success",data:t}}(p,w,T,u),u?(p.ifModified&&((x=T.getResponseHeader("Last-Modified"))&&(_.lastModified[o]=x),(x=T.getResponseHeader("etag"))&&(_.etag[o]=x)),204===t||"HEAD"===p.type?D="nocontent":304===t?D="notmodified":(D=w.state,f=w.data,u=!(h=w.error))):(h=D,!t&&D||(D="error",t<0&&(t=0))),T.status=t,T.statusText=(n||D)+"",u?v.resolveWith(m,[f,D,T]):v.rejectWith(m,[T,D,h]),T.statusCode(b),b=void 0,d&&g.trigger(u?"ajaxSuccess":"ajaxError",[T,p,u?f:h]),y.fireWith(m,[T,D]),d&&(g.trigger("ajaxComplete",[T,p]),--_.active||_.event.trigger("ajaxStop")))}return T},getJSON:function(e,t,n){return _.get(e,t,n,"json")},getScript:function(e,t){return _.get(e,void 0,t,"script")}}),_.each(["get","post"],(function(e,t){_[t]=function(e,n,i,r){return m(n)&&(r=r||i,i=n,n=void 0),_.ajax(_.extend({url:e,type:t,dataType:r,data:n,success:i},_.isPlainObject(e)&&e))}})),_._evalUrl=function(e){return _.ajax({url:e,type:"GET",dataType:"script",cache:!0,async:!1,global:!1,throws:!0})},_.fn.extend({wrapAll:function(e){var t;return this[0]&&(m(e)&&(e=e.call(this[0])),t=_(e,this[0].ownerDocument).eq(0).clone(!0),this[0].parentNode&&t.insertBefore(this[0]),t.map((function(){for(var e=this;e.firstElementChild;)e=e.firstElementChild;return e})).append(this)),this},wrapInner:function(e){return m(e)?this.each((function(t){_(this).wrapInner(e.call(this,t))})):this.each((function(){var t=_(this),n=t.contents();n.length?n.wrapAll(e):t.append(e)}))},wrap:function(e){var t=m(e);return this.each((function(n){_(this).wrapAll(t?e.call(this,n):e)}))},unwrap:function(e){return this.parent(e).not("body").each((function(){_(this).replaceWith(this.childNodes)})),this}}),_.expr.pseudos.hidden=function(e){return!_.expr.pseudos.visible(e)},_.expr.pseudos.visible=function(e){return!!(e.offsetWidth||e.offsetHeight||e.getClientRects().length)},_.ajaxSettings.xhr=function(){try{return new e.XMLHttpRequest}catch(e){}};var Rt={0:200,1223:204},Ut=_.ajaxSettings.xhr();p.cors=!!Ut&&"withCredentials"in Ut,p.ajax=Ut=!!Ut,_.ajaxTransport((function(t){var n,i;if(p.cors||Ut&&!t.crossDomain)return{send:function(r,o){var a,s=t.xhr();if(s.open(t.type,t.url,t.async,t.username,t.password),t.xhrFields)for(a in t.xhrFields)s[a]=t.xhrFields[a];for(a in t.mimeType&&s.overrideMimeType&&s.overrideMimeType(t.mimeType),t.crossDomain||r["X-Requested-With"]||(r["X-Requested-With"]="XMLHttpRequest"),r)s.setRequestHeader(a,r[a]);n=function(e){return function(){n&&(n=i=s.onload=s.onerror=s.onabort=s.ontimeout=s.onreadystatechange=null,"abort"===e?s.abort():"error"===e?"number"!=typeof s.status?o(0,"error"):o(s.status,s.statusText):o(Rt[s.status]||s.status,s.statusText,"text"!==(s.responseType||"text")||"string"!=typeof s.responseText?{binary:s.response}:{text:s.responseText},s.getAllResponseHeaders()))}},s.onload=n(),i=s.onerror=s.ontimeout=n("error"),void 0!==s.onabort?s.onabort=i:s.onreadystatechange=function(){4===s.readyState&&e.setTimeout((function(){n&&i()}))},n=n("abort");try{s.send(t.hasContent&&t.data||null)}catch(e){if(n)throw e}},abort:function(){n&&n()}}})),_.ajaxPrefilter((function(e){e.crossDomain&&(e.contents.script=!1)})),_.ajaxSetup({accepts:{script:"text/javascript, application/javascript, application/ecmascript, application/x-ecmascript"},contents:{script:/\b(?:java|ecma)script\b/},converters:{"text script":function(e){return _.globalEval(e),e}}}),_.ajaxPrefilter("script",(function(e){void 0===e.cache&&(e.cache=!1),e.crossDomain&&(e.type="GET")})),_.ajaxTransport("script",(function(e){var t,n;if(e.crossDomain)return{send:function(r,o){t=_("<script>").prop({charset:e.scriptCharset,src:e.url}).on("load error",n=function(e){t.remove(),n=null,e&&o("error"===e.type?404:200,e.type)}),i.head.appendChild(t[0])},abort:function(){n&&n()}}}));var Yt=[],Ht=/(=)\?(?=&|$)|\?\?/;_.ajaxSetup({jsonp:"callback",jsonpCallback:function(){var e=Yt.pop()||_.expando+"_"+yt++;return this[e]=!0,e}}),_.ajaxPrefilter("json jsonp",(function(t,n,i){var r,o,a,s=!1!==t.jsonp&&(Ht.test(t.url)?"url":"string"==typeof t.data&&0===(t.contentType||"").indexOf("application/x-www-form-urlencoded")&&Ht.test(t.data)&&"data");if(s||"jsonp"===t.dataTypes[0])return r=t.jsonpCallback=m(t.jsonpCallback)?t.jsonpCallback():t.jsonpCallback,s?t[s]=t[s].replace(Ht,"$1"+r):!1!==t.jsonp&&(t.url+=(bt.test(t.url)?"&":"?")+t.jsonp+"="+r),t.converters["script json"]=function(){return a||_.error(r+" was not called"),a[0]},t.dataTypes[0]="json",o=e[r],e[r]=function(){a=arguments},i.always((function(){void 0===o?_(e).removeProp(r):e[r]=o,t[r]&&(t.jsonpCallback=n.jsonpCallback,Yt.push(r)),a&&m(o)&&o(a[0]),a=o=void 0})),"script"})),p.createHTMLDocument=function(){var e=i.implementation.createHTMLDocument("").body;return e.innerHTML="<form></form><form></form>",2===e.childNodes.length}(),_.parseHTML=function(e,t,n){return"string"!=typeof e?[]:("boolean"==typeof t&&(n=t,t=!1),t||(p.createHTMLDocument?((r=(t=i.implementation.createHTMLDocument("")).createElement("base")).href=i.location.href,t.head.appendChild(r)):t=i),a=!n&&[],(o=S.exec(e))?[t.createElement(o[1])]:(o=ge([e],t,a),a&&a.length&&_(a).remove(),_.merge([],o.childNodes)));var r,o,a},_.fn.load=function(e,t,n){var i,r,o,a=this,s=e.indexOf(" ");return s>-1&&(i=dt(e.slice(s)),e=e.slice(0,s)),m(t)?(n=t,t=void 0):t&&"object"==typeof t&&(r="POST"),a.length>0&&_.ajax({url:e,type:r||"GET",dataType:"html",data:t}).done((function(e){o=arguments,a.html(i?_("<div>").append(_.parseHTML(e)).find(i):e)})).always(n&&function(e,t){a.each((function(){n.apply(this,o||[e.responseText,t,e])}))}),this},_.each(["ajaxStart","ajaxStop","ajaxComplete","ajaxError","ajaxSuccess","ajaxSend"],(function(e,t){_.fn[t]=function(e){return this.on(t,e)}})),_.expr.pseudos.animated=function(e){return _.grep(_.timers,(function(t){return e===t.elem})).length},_.offset={setOffset:function(e,t,n){var i,r,o,a,s,l,u=_.css(e,"position"),c=_(e),d={};"static"===u&&(e.style.position="relative"),s=c.offset(),o=_.css(e,"top"),l=_.css(e,"left"),("absolute"===u||"fixed"===u)&&(o+l).indexOf("auto")>-1?(a=(i=c.position()).top,r=i.left):(a=parseFloat(o)||0,r=parseFloat(l)||0),m(t)&&(t=t.call(e,n,_.extend({},s))),null!=t.top&&(d.top=t.top-s.top+a),null!=t.left&&(d.left=t.left-s.left+r),"using"in t?t.using.call(e,d):c.css(d)}},_.fn.extend({offset:function(e){if(arguments.length)return void 0===e?this:this.each((function(t){_.offset.setOffset(this,e,t)}));var t,n,i=this[0];return i?i.getClientRects().length?(t=i.getBoundingClientRect(),n=i.ownerDocument.defaultView,{top:t.top+n.pageYOffset,left:t.left+n.pageXOffset}):{top:0,left:0}:void 0},position:function(){if(this[0]){var e,t,n,i=this[0],r={top:0,left:0};if("fixed"===_.css(i,"position"))t=i.getBoundingClientRect();else{for(t=this.offset(),n=i.ownerDocument,e=i.offsetParent||n.documentElement;e&&(e===n.body||e===n.documentElement)&&"static"===_.css(e,"position");)e=e.parentNode;e&&e!==i&&1===e.nodeType&&((r=_(e).offset()).top+=_.css(e,"borderTopWidth",!0),r.left+=_.css(e,"borderLeftWidth",!0))}return{top:t.top-r.top-_.css(i,"marginTop",!0),left:t.left-r.left-_.css(i,"marginLeft",!0)}}},offsetParent:function(){return this.map((function(){for(var e=this.offsetParent;e&&"static"===_.css(e,"position");)e=e.offsetParent;return e||ve}))}}),_.each({scrollLeft:"pageXOffset",scrollTop:"pageYOffset"},(function(e,t){var n="pageYOffset"===t;_.fn[e]=function(i){return W(this,(function(e,i,r){var o;if(g(e)?o=e:9===e.nodeType&&(o=e.defaultView),void 0===r)return o?o[t]:e[i];o?o.scrollTo(n?o.pageXOffset:r,n?r:o.pageYOffset):e[i]=r}),e,i,arguments.length)}})),_.each(["top","left"],(function(e,t){_.cssHooks[t]=Ye(p.pixelPosition,(function(e,n){if(n)return n=Ue(e,t),Fe.test(n)?_(e).position()[t]+"px":n}))})),_.each({Height:"height",Width:"width"},(function(e,t){_.each({padding:"inner"+e,content:t,"":"outer"+e},(function(n,i){_.fn[i]=function(r,o){var a=arguments.length&&(n||"boolean"!=typeof r),s=n||(!0===r||!0===o?"margin":"border");return W(this,(function(t,n,r){var o;return g(t)?0===i.indexOf("outer")?t["inner"+e]:t.document.documentElement["client"+e]:9===t.nodeType?(o=t.documentElement,Math.max(t.body["scroll"+e],o["scroll"+e],t.body["offset"+e],o["offset"+e],o["client"+e])):void 0===r?_.css(t,n,s):_.style(t,n,r,s)}),t,a?r:void 0,a)}}))})),_.each("blur focus focusin focusout resize scroll click dblclick mousedown mouseup mousemove mouseover mouseout mouseenter mouseleave change select submit keydown keypress keyup contextmenu".split(" "),(function(e,t){_.fn[t]=function(e,n){return arguments.length>0?this.on(t,null,e,n):this.trigger(t)}})),_.fn.extend({hover:function(e,t){return this.mouseenter(e).mouseleave(t||e)}}),_.fn.extend({bind:function(e,t,n){return this.on(e,null,t,n)},unbind:function(e,t){return this.off(e,null,t)},delegate:function(e,t,n,i){return this.on(t,e,n,i)},undelegate:function(e,t,n){return 1===arguments.length?this.off(e,"**"):this.off(t,e||"**",n)}}),_.proxy=function(e,t){var n,i,r;if("string"==typeof t&&(n=e[t],t=e,e=n),m(e))return i=o.call(arguments,2),(r=function(){return e.apply(t||this,i.concat(o.call(arguments)))}).guid=e.guid=e.guid||_.guid++,r},_.holdReady=function(e){e?_.readyWait++:_.ready(!0)},_.isArray=Array.isArray,_.parseJSON=JSON.parse,_.nodeName=E,_.isFunction=m,_.isWindow=g,_.camelCase=z,_.type=b,_.now=Date.now,_.isNumeric=function(e){var t=_.type(e);return("number"===t||"string"===t)&&!isNaN(e-parseFloat(e))},"function"==typeof define&&define.amd&&define("jquery",[],(function(){return _}));var Wt=e.jQuery,qt=e.$;return _.noConflict=function(t){return e.$===_&&(e.$=qt),t&&e.jQuery===_&&(e.jQuery=Wt),_},t||(e.jQuery=e.$=_),_})),function(e,t){"object"==typeof exports&&"undefined"!=typeof module?module.exports=t():"function"==typeof define&&define.amd?define(t):e.Popper=t()}(this,(function(){"use strict";function e(e){return e&&"[object Function]"==={}.toString.call(e)}function t(e,t){if(1!==e.nodeType)return[];var n=getComputedStyle(e,null);return t?n[t]:n}function n(e){return"HTML"===e.nodeName?e:e.parentNode||e.host}function i(e){if(!e)return document.body;switch(e.nodeName){case"HTML":case"BODY":return e.ownerDocument.body;case"#document":return e.body}var r=t(e),o=r.overflow,a=r.overflowX,s=r.overflowY;return/(auto|scroll|overlay)/.test(o+s+a)?e:i(n(e))}function r(e){return 11===e?Q:10===e?Z:Q||Z}function o(e){if(!e)return document.documentElement;for(var n=r(10)?document.body:null,i=e.offsetParent;i===n&&e.nextElementSibling;)i=(e=e.nextElementSibling).offsetParent;var a=i&&i.nodeName;return a&&"BODY"!==a&&"HTML"!==a?-1!==["TD","TABLE"].indexOf(i.nodeName)&&"static"===t(i,"position")?o(i):i:e?e.ownerDocument.documentElement:document.documentElement}function a(e){return null===e.parentNode?e:a(e.parentNode)}function s(e,t){if(!(e&&e.nodeType&&t&&t.nodeType))return document.documentElement;var n=e.compareDocumentPosition(t)&Node.DOCUMENT_POSITION_FOLLOWING,i=n?e:t,r=n?t:e,l=document.createRange();l.setStart(i,0),l.setEnd(r,0);var u=l.commonAncestorContainer;if(e!==u&&t!==u||i.contains(r))return function(e){var t=e.nodeName;return"BODY"!==t&&("HTML"===t||o(e.firstElementChild)===e)}(u)?u:o(u);var c=a(e);return c.host?s(c.host,t):s(e,a(t).host)}function l(e){var t=1<arguments.length&&void 0!==arguments[1]?arguments[1]:"top",n="top"===t?"scrollTop":"scrollLeft",i=e.nodeName;if("BODY"===i||"HTML"===i){var r=e.ownerDocument.documentElement,o=e.ownerDocument.scrollingElement||r;return o[n]}return e[n]}function u(e,t){var n=2<arguments.length&&void 0!==arguments[2]&&arguments[2],i=l(t,"top"),r=l(t,"left"),o=n?-1:1;return e.top+=i*o,e.bottom+=i*o,e.left+=r*o,e.right+=r*o,e}function c(e,t){var n="x"===t?"Left":"Top",i="Left"==n?"Right":"Bottom";return parseFloat(e["border"+n+"Width"],10)+parseFloat(e["border"+i+"Width"],10)}function d(e,t,n,i){return B(t["offset"+e],t["scroll"+e],n["client"+e],n["offset"+e],n["scroll"+e],r(10)?n["offset"+e]+i["margin"+("Height"===e?"Top":"Left")]+i["margin"+("Height"===e?"Bottom":"Right")]:0)}function f(){var e=document.body,t=document.documentElement,n=r(10)&&getComputedStyle(t);return{height:d("Height",e,t,n),width:d("Width",e,t,n)}}function h(e){return ne({},e,{right:e.left+e.width,bottom:e.top+e.height})}function p(e){var n={};try{if(r(10)){n=e.getBoundingClientRect();var i=l(e,"top"),o=l(e,"left");n.top+=i,n.left+=o,n.bottom+=i,n.right+=o}else n=e.getBoundingClientRect()}catch(e){}var a={left:n.left,top:n.top,width:n.right-n.left,height:n.bottom-n.top},s="HTML"===e.nodeName?f():{},u=s.width||e.clientWidth||a.right-a.left,d=s.height||e.clientHeight||a.bottom-a.top,p=e.offsetWidth-u,m=e.offsetHeight-d;if(p||m){var g=t(e);p-=c(g,"x"),m-=c(g,"y"),a.width-=p,a.height-=m}return h(a)}function m(e,n){var o=2<arguments.length&&void 0!==arguments[2]&&arguments[2],a=r(10),s="HTML"===n.nodeName,l=p(e),c=p(n),d=i(e),f=t(n),m=parseFloat(f.borderTopWidth,10),g=parseFloat(f.borderLeftWidth,10);o&&"HTML"===n.nodeName&&(c.top=B(c.top,0),c.left=B(c.left,0));var v=h({top:l.top-c.top-m,left:l.left-c.left-g,width:l.width,height:l.height});if(v.marginTop=0,v.marginLeft=0,!a&&s){var y=parseFloat(f.marginTop,10),b=parseFloat(f.marginLeft,10);v.top-=m-y,v.bottom-=m-y,v.left-=g-b,v.right-=g-b,v.marginTop=y,v.marginLeft=b}return(a&&!o?n.contains(d):n===d&&"BODY"!==d.nodeName)&&(v=u(v,n)),v}function g(e){var t=1<arguments.length&&void 0!==arguments[1]&&arguments[1],n=e.ownerDocument.documentElement,i=m(e,n),r=B(n.clientWidth,window.innerWidth||0),o=B(n.clientHeight,window.innerHeight||0),a=t?0:l(n),s=t?0:l(n,"left"),u={top:a-i.top+i.marginTop,left:s-i.left+i.marginLeft,width:r,height:o};return h(u)}function v(e){var i=e.nodeName;return"BODY"!==i&&"HTML"!==i&&("fixed"===t(e,"position")||v(n(e)))}function y(e){if(!e||!e.parentElement||r())return document.documentElement;for(var n=e.parentElement;n&&"none"===t(n,"transform");)n=n.parentElement;return n||document.documentElement}function b(e,t,r,o){var a=4<arguments.length&&void 0!==arguments[4]&&arguments[4],l={top:0,left:0},u=a?y(e):s(e,t);if("viewport"===o)l=g(u,a);else{var c;"scrollParent"===o?"BODY"===(c=i(n(t))).nodeName&&(c=e.ownerDocument.documentElement):c="window"===o?e.ownerDocument.documentElement:o;var d=m(c,u,a);if("HTML"!==c.nodeName||v(u))l=d;else{var h=f(),p=h.height,b=h.width;l.top+=d.top-d.marginTop,l.bottom=p+d.top,l.left+=d.left-d.marginLeft,l.right=b+d.left}}return l.left+=r,l.top+=r,l.right-=r,l.bottom-=r,l}function _(e){return e.width*e.height}function w(e,t,n,i,r){var o=5<arguments.length&&void 0!==arguments[5]?arguments[5]:0;if(-1===e.indexOf("auto"))return e;var a=b(n,i,o,r),s={top:{width:a.width,height:t.top-a.top},right:{width:a.right-t.right,height:a.height},bottom:{width:a.width,height:a.bottom-t.bottom},left:{width:t.left-a.left,height:a.height}},l=Object.keys(s).map((function(e){return ne({key:e},s[e],{area:_(s[e])})})).sort((function(e,t){return t.area-e.area})),u=l.filter((function(e){var t=e.width,i=e.height;return t>=n.clientWidth&&i>=n.clientHeight})),c=0<u.length?u[0].key:l[0].key,d=e.split("-")[1];return c+(d?"-"+d:"")}function x(e,t,n){var i=3<arguments.length&&void 0!==arguments[3]?arguments[3]:null,r=i?y(t):s(t,n);return m(n,r,i)}function D(e){var t=getComputedStyle(e),n=parseFloat(t.marginTop)+parseFloat(t.marginBottom),i=parseFloat(t.marginLeft)+parseFloat(t.marginRight);return{width:e.offsetWidth+i,height:e.offsetHeight+n}}function T(e){var t={left:"right",right:"left",bottom:"top",top:"bottom"};return e.replace(/left|right|bottom|top/g,(function(e){return t[e]}))}function C(e,t,n){n=n.split("-")[0];var i=D(e),r={width:i.width,height:i.height},o=-1!==["right","left"].indexOf(n),a=o?"top":"left",s=o?"left":"top",l=o?"height":"width",u=o?"width":"height";return r[a]=t[a]+t[l]/2-i[l]/2,r[s]=n===s?t[s]-i[u]:t[T(s)],r}function k(e,t){return Array.prototype.find?e.find(t):e.filter(t)[0]}function E(t,n,i){return(void 0===i?t:t.slice(0,function(e,t,n){if(Array.prototype.findIndex)return e.findIndex((function(e){return e[t]===n}));var i=k(e,(function(e){return e[t]===n}));return e.indexOf(i)}(t,"name",i))).forEach((function(t){t.function&&console.warn("`modifier.function` is deprecated, use `modifier.fn`!");var i=t.function||t.fn;t.enabled&&e(i)&&(n.offsets.popper=h(n.offsets.popper),n.offsets.reference=h(n.offsets.reference),n=i(n,t))})),n}function S(){if(!this.state.isDestroyed){var e={instance:this,styles:{},arrowStyles:{},attributes:{},flipped:!1,offsets:{}};e.offsets.reference=x(this.state,this.popper,this.reference,this.options.positionFixed),e.placement=w(this.options.placement,e.offsets.reference,this.popper,this.reference,this.options.modifiers.flip.boundariesElement,this.options.modifiers.flip.padding),e.originalPlacement=e.placement,e.positionFixed=this.options.positionFixed,e.offsets.popper=C(this.popper,e.offsets.reference,e.placement),e.offsets.popper.position=this.options.positionFixed?"fixed":"absolute",e=E(this.modifiers,e),this.state.isCreated?this.options.onUpdate(e):(this.state.isCreated=!0,this.options.onCreate(e))}}function A(e,t){return e.some((function(e){var n=e.name;return e.enabled&&n===t}))}function O(e){for(var t=[!1,"ms","Webkit","Moz","O"],n=e.charAt(0).toUpperCase()+e.slice(1),i=0;i<t.length;i++){var r=t[i],o=r?""+r+n:e;if(void 0!==document.body.style[o])return o}return null}function M(){return this.state.isDestroyed=!0,A(this.modifiers,"applyStyle")&&(this.popper.removeAttribute("x-placement"),this.popper.style.position="",this.popper.style.top="",this.popper.style.left="",this.popper.style.right="",this.popper.style.bottom="",this.popper.style.willChange="",this.popper.style[O("transform")]=""),this.disableEventListeners(),this.options.removeOnDestroy&&this.popper.parentNode.removeChild(this.popper),this}function N(e){var t=e.ownerDocument;return t?t.defaultView:window}function I(e,t,n,r){var o="BODY"===e.nodeName,a=o?e.ownerDocument.defaultView:e;a.addEventListener(t,n,{passive:!0}),o||I(i(a.parentNode),t,n,r),r.push(a)}function L(e,t,n,r){n.updateBound=r,N(e).addEventListener("resize",n.updateBound,{passive:!0});var o=i(e);return I(o,"scroll",n.updateBound,n.scrollParents),n.scrollElement=o,n.eventsEnabled=!0,n}function j(){this.state.eventsEnabled||(this.state=L(this.reference,this.options,this.state,this.scheduleUpdate))}function F(){this.state.eventsEnabled&&(cancelAnimationFrame(this.scheduleUpdate),this.state=function(e,t){return N(e).removeEventListener("resize",t.updateBound),t.scrollParents.forEach((function(e){e.removeEventListener("scroll",t.updateBound)})),t.updateBound=null,t.scrollParents=[],t.scrollElement=null,t.eventsEnabled=!1,t}(this.reference,this.state))}function P(e){return""!==e&&!isNaN(parseFloat(e))&&isFinite(e)}function R(e,t){Object.keys(t).forEach((function(n){var i="";-1!==["width","height","top","right","bottom","left"].indexOf(n)&&P(t[n])&&(i="px"),e.style[n]=t[n]+i}))}function U(e,t,n){var i=k(e,(function(e){return e.name===t})),r=!!i&&e.some((function(e){return e.name===n&&e.enabled&&e.order<i.order}));if(!r){var o="`"+t+"`";console.warn("`"+n+"` modifier is required by "+o+" modifier in order to work, be sure to include it before "+o+"!")}return r}function Y(e){var t=1<arguments.length&&void 0!==arguments[1]&&arguments[1],n=re.indexOf(e),i=re.slice(n+1).concat(re.slice(0,n));return t?i.reverse():i}function H(e,t,n,i){var r=[0,0],o=-1!==["right","left"].indexOf(i),a=e.split(/(\+|\-)/).map((function(e){return e.trim()})),s=a.indexOf(k(a,(function(e){return-1!==e.search(/,|\s/)})));a[s]&&-1===a[s].indexOf(",")&&console.warn("Offsets separated by white space(s) are deprecated, use a comma (,) instead.");var l=/\s*,\s*|\s+/,u=-1===s?[a]:[a.slice(0,s).concat([a[s].split(l)[0]]),[a[s].split(l)[1]].concat(a.slice(s+1))];return(u=u.map((function(e,i){var r=(1===i?!o:o)?"height":"width",a=!1;return e.reduce((function(e,t){return""===e[e.length-1]&&-1!==["+","-"].indexOf(t)?(e[e.length-1]=t,a=!0,e):a?(e[e.length-1]+=t,a=!1,e):e.concat(t)}),[]).map((function(e){return function(e,t,n,i){var r=e.match(/((?:\-|\+)?\d*\.?\d*)(.*)/),o=+r[1],a=r[2];if(!o)return e;if(0===a.indexOf("%")){var s;switch(a){case"%p":s=n;break;case"%":case"%r":default:s=i}return h(s)[t]/100*o}return"vh"===a||"vw"===a?("vh"===a?B(document.documentElement.clientHeight,window.innerHeight||0):B(document.documentElement.clientWidth,window.innerWidth||0))/100*o:o}(e,r,t,n)}))}))).forEach((function(e,t){e.forEach((function(n,i){P(n)&&(r[t]+=n*("-"===e[i-1]?-1:1))}))})),r}for(var W=Math.min,q=Math.round,V=Math.floor,B=Math.max,z="undefined"!=typeof window&&"undefined"!=typeof document,$=["Edge","Trident","Firefox"],G=0,K=0;K<$.length;K+=1)if(z&&0<=navigator.userAgent.indexOf($[K])){G=1;break}var X=z&&window.Promise?function(e){var t=!1;return function(){t||(t=!0,window.Promise.resolve().then((function(){t=!1,e()})))}}:function(e){var t=!1;return function(){t||(t=!0,setTimeout((function(){t=!1,e()}),G))}},Q=z&&!(!window.MSInputMethodContext||!document.documentMode),Z=z&&/MSIE 10/.test(navigator.userAgent),J=function(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")},ee=function(){function e(e,t){for(var n,i=0;i<t.length;i++)(n=t[i]).enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(e,n.key,n)}return function(t,n,i){return n&&e(t.prototype,n),i&&e(t,i),t}}(),te=function(e,t,n){return t in e?Object.defineProperty(e,t,{value:n,enumerable:!0,configurable:!0,writable:!0}):e[t]=n,e},ne=Object.assign||function(e){for(var t,n=1;n<arguments.length;n++)for(var i in t=arguments[n])Object.prototype.hasOwnProperty.call(t,i)&&(e[i]=t[i]);return e},ie=["auto-start","auto","auto-end","top-start","top","top-end","right-start","right","right-end","bottom-end","bottom","bottom-start","left-end","left","left-start"],re=ie.slice(3),oe="flip",ae="clockwise",se="counterclockwise",le=function(){function t(n,i){var r=this,o=2<arguments.length&&void 0!==arguments[2]?arguments[2]:{};J(this,t),this.scheduleUpdate=function(){return requestAnimationFrame(r.update)},this.update=X(this.update.bind(this)),this.options=ne({},t.Defaults,o),this.state={isDestroyed:!1,isCreated:!1,scrollParents:[]},this.reference=n&&n.jquery?n[0]:n,this.popper=i&&i.jquery?i[0]:i,this.options.modifiers={},Object.keys(ne({},t.Defaults.modifiers,o.modifiers)).forEach((function(e){r.options.modifiers[e]=ne({},t.Defaults.modifiers[e]||{},o.modifiers?o.modifiers[e]:{})})),this.modifiers=Object.keys(this.options.modifiers).map((function(e){return ne({name:e},r.options.modifiers[e])})).sort((function(e,t){return e.order-t.order})),this.modifiers.forEach((function(t){t.enabled&&e(t.onLoad)&&t.onLoad(r.reference,r.popper,r.options,t,r.state)})),this.update();var a=this.options.eventsEnabled;a&&this.enableEventListeners(),this.state.eventsEnabled=a}return ee(t,[{key:"update",value:function(){return S.call(this)}},{key:"destroy",value:function(){return M.call(this)}},{key:"enableEventListeners",value:function(){return j.call(this)}},{key:"disableEventListeners",value:function(){return F.call(this)}}]),t}();return le.Utils=("undefined"==typeof window?global:window).PopperUtils,le.placements=ie,le.Defaults={placement:"bottom",positionFixed:!1,eventsEnabled:!0,removeOnDestroy:!1,onCreate:function(){},onUpdate:function(){},modifiers:{shift:{order:100,enabled:!0,fn:function(e){var t=e.placement,n=t.split("-")[0],i=t.split("-")[1];if(i){var r=e.offsets,o=r.reference,a=r.popper,s=-1!==["bottom","top"].indexOf(n),l=s?"left":"top",u=s?"width":"height",c={start:te({},l,o[l]),end:te({},l,o[l]+o[u]-a[u])};e.offsets.popper=ne({},a,c[i])}return e}},offset:{order:200,enabled:!0,fn:function(e,t){var n,i=t.offset,r=e.placement,o=e.offsets,a=o.popper,s=o.reference,l=r.split("-")[0];return n=P(+i)?[+i,0]:H(i,a,s,l),"left"===l?(a.top+=n[0],a.left-=n[1]):"right"===l?(a.top+=n[0],a.left+=n[1]):"top"===l?(a.left+=n[0],a.top-=n[1]):"bottom"===l&&(a.left+=n[0],a.top+=n[1]),e.popper=a,e},offset:0},preventOverflow:{order:300,enabled:!0,fn:function(e,t){var n=t.boundariesElement||o(e.instance.popper);e.instance.reference===n&&(n=o(n));var i=O("transform"),r=e.instance.popper.style,a=r.top,s=r.left,l=r[i];r.top="",r.left="",r[i]="";var u=b(e.instance.popper,e.instance.reference,t.padding,n,e.positionFixed);r.top=a,r.left=s,r[i]=l,t.boundaries=u;var c=t.priority,d=e.offsets.popper,f={primary:function(e){var n=d[e];return d[e]<u[e]&&!t.escapeWithReference&&(n=B(d[e],u[e])),te({},e,n)},secondary:function(e){var n="right"===e?"left":"top",i=d[n];return d[e]>u[e]&&!t.escapeWithReference&&(i=W(d[n],u[e]-("right"===e?d.width:d.height))),te({},n,i)}};return c.forEach((function(e){var t=-1===["left","top"].indexOf(e)?"secondary":"primary";d=ne({},d,f[t](e))})),e.offsets.popper=d,e},priority:["left","right","top","bottom"],padding:5,boundariesElement:"scrollParent"},keepTogether:{order:400,enabled:!0,fn:function(e){var t=e.offsets,n=t.popper,i=t.reference,r=e.placement.split("-")[0],o=V,a=-1!==["top","bottom"].indexOf(r),s=a?"right":"bottom",l=a?"left":"top",u=a?"width":"height";return n[s]<o(i[l])&&(e.offsets.popper[l]=o(i[l])-n[u]),n[l]>o(i[s])&&(e.offsets.popper[l]=o(i[s])),e}},arrow:{order:500,enabled:!0,fn:function(e,n){var i;if(!U(e.instance.modifiers,"arrow","keepTogether"))return e;var r=n.element;if("string"==typeof r){if(!(r=e.instance.popper.querySelector(r)))return e}else if(!e.instance.popper.contains(r))return console.warn("WARNING: `arrow.element` must be child of its popper element!"),e;var o=e.placement.split("-")[0],a=e.offsets,s=a.popper,l=a.reference,u=-1!==["left","right"].indexOf(o),c=u?"height":"width",d=u?"Top":"Left",f=d.toLowerCase(),p=u?"left":"top",m=u?"bottom":"right",g=D(r)[c];l[m]-g<s[f]&&(e.offsets.popper[f]-=s[f]-(l[m]-g)),l[f]+g>s[m]&&(e.offsets.popper[f]+=l[f]+g-s[m]),e.offsets.popper=h(e.offsets.popper);var v=l[f]+l[c]/2-g/2,y=t(e.instance.popper),b=parseFloat(y["margin"+d],10),_=parseFloat(y["border"+d+"Width"],10),w=v-e.offsets.popper[f]-b-_;return w=B(W(s[c]-g,w),0),e.arrowElement=r,e.offsets.arrow=(te(i={},f,q(w)),te(i,p,""),i),e},element:"[x-arrow]"},flip:{order:600,enabled:!0,fn:function(e,t){if(A(e.instance.modifiers,"inner"))return e;if(e.flipped&&e.placement===e.originalPlacement)return e;var n=b(e.instance.popper,e.instance.reference,t.padding,t.boundariesElement,e.positionFixed),i=e.placement.split("-")[0],r=T(i),o=e.placement.split("-")[1]||"",a=[];switch(t.behavior){case oe:a=[i,r];break;case ae:a=Y(i);break;case se:a=Y(i,!0);break;default:a=t.behavior}return a.forEach((function(s,l){if(i!==s||a.length===l+1)return e;i=e.placement.split("-")[0],r=T(i);var u=e.offsets.popper,c=e.offsets.reference,d=V,f="left"===i&&d(u.right)>d(c.left)||"right"===i&&d(u.left)<d(c.right)||"top"===i&&d(u.bottom)>d(c.top)||"bottom"===i&&d(u.top)<d(c.bottom),h=d(u.left)<d(n.left),p=d(u.right)>d(n.right),m=d(u.top)<d(n.top),g=d(u.bottom)>d(n.bottom),v="left"===i&&h||"right"===i&&p||"top"===i&&m||"bottom"===i&&g,y=-1!==["top","bottom"].indexOf(i),b=!!t.flipVariations&&(y&&"start"===o&&h||y&&"end"===o&&p||!y&&"start"===o&&m||!y&&"end"===o&&g);(f||v||b)&&(e.flipped=!0,(f||v)&&(i=a[l+1]),b&&(o=function(e){return"end"===e?"start":"start"===e?"end":e}(o)),e.placement=i+(o?"-"+o:""),e.offsets.popper=ne({},e.offsets.popper,C(e.instance.popper,e.offsets.reference,e.placement)),e=E(e.instance.modifiers,e,"flip"))})),e},behavior:"flip",padding:5,boundariesElement:"viewport"},inner:{order:700,enabled:!1,fn:function(e){var t=e.placement,n=t.split("-")[0],i=e.offsets,r=i.popper,o=i.reference,a=-1!==["left","right"].indexOf(n),s=-1===["top","left"].indexOf(n);return r[a?"left":"top"]=o[n]-(s?r[a?"width":"height"]:0),e.placement=T(t),e.offsets.popper=h(r),e}},hide:{order:800,enabled:!0,fn:function(e){if(!U(e.instance.modifiers,"hide","preventOverflow"))return e;var t=e.offsets.reference,n=k(e.instance.modifiers,(function(e){return"preventOverflow"===e.name})).boundaries;if(t.bottom<n.top||t.left>n.right||t.top>n.bottom||t.right<n.left){if(!0===e.hide)return e;e.hide=!0,e.attributes["x-out-of-boundaries"]=""}else{if(!1===e.hide)return e;e.hide=!1,e.attributes["x-out-of-boundaries"]=!1}return e}},computeStyle:{order:850,enabled:!0,fn:function(e,t){var n=t.x,i=t.y,r=e.offsets.popper,a=k(e.instance.modifiers,(function(e){return"applyStyle"===e.name})).gpuAcceleration;void 0!==a&&console.warn("WARNING: `gpuAcceleration` option moved to `computeStyle` modifier and will not be supported in future versions of Popper.js!");var s,l,u=void 0===a?t.gpuAcceleration:a,c=p(o(e.instance.popper)),d={position:r.position},f={left:V(r.left),top:q(r.top),bottom:q(r.bottom),right:V(r.right)},h="bottom"===n?"top":"bottom",m="right"===i?"left":"right",g=O("transform");if(l="bottom"==h?-c.height+f.bottom:f.top,s="right"==m?-c.width+f.right:f.left,u&&g)d[g]="translate3d("+s+"px, "+l+"px, 0)",d[h]=0,d[m]=0,d.willChange="transform";else{var v="bottom"==h?-1:1,y="right"==m?-1:1;d[h]=l*v,d[m]=s*y,d.willChange=h+", "+m}var b={"x-placement":e.placement};return e.attributes=ne({},b,e.attributes),e.styles=ne({},d,e.styles),e.arrowStyles=ne({},e.offsets.arrow,e.arrowStyles),e},gpuAcceleration:!0,x:"bottom",y:"right"},applyStyle:{order:900,enabled:!0,fn:function(e){return R(e.instance.popper,e.styles),function(e,t){Object.keys(t).forEach((function(n){!1===t[n]?e.removeAttribute(n):e.setAttribute(n,t[n])}))}(e.instance.popper,e.attributes),e.arrowElement&&Object.keys(e.arrowStyles).length&&R(e.arrowElement,e.arrowStyles),e},onLoad:function(e,t,n,i,r){var o=x(r,t,e,n.positionFixed),a=w(n.placement,o,t,e,n.modifiers.flip.boundariesElement,n.modifiers.flip.padding);return t.setAttribute("x-placement",a),R(t,{position:n.positionFixed?"fixed":"absolute"}),n},gpuAcceleration:void 0}}},le})),function(e,t){"object"==typeof exports&&"undefined"!=typeof module?t(exports,require("jquery"),require("popper.js")):"function"==typeof define&&define.amd?define(["exports","jquery","popper.js"],t):t(e.bootstrap={},e.jQuery,e.Popper)}(this,(function(e,t,n){"use strict";function i(e,t){for(var n=0;n<t.length;n++){var i=t[n];i.enumerable=i.enumerable||!1,i.configurable=!0,"value"in i&&(i.writable=!0),Object.defineProperty(e,i.key,i)}}function r(e,t,n){return t&&i(e.prototype,t),n&&i(e,n),e}function o(e){for(var t=1;t<arguments.length;t++){var n=null!=arguments[t]?arguments[t]:{},i=Object.keys(n);"function"==typeof Object.getOwnPropertySymbols&&(i=i.concat(Object.getOwnPropertySymbols(n).filter((function(e){return Object.getOwnPropertyDescriptor(n,e).enumerable})))),i.forEach((function(t){var i,r,o;i=e,o=n[r=t],r in i?Object.defineProperty(i,r,{value:o,enumerable:!0,configurable:!0,writable:!0}):i[r]=o}))}return e}t=t&&t.hasOwnProperty("default")?t.default:t,n=n&&n.hasOwnProperty("default")?n.default:n;var a,s,l,u,c,d,f,h,p,m,g,v,y,b,_,w,x,D,T,C,k,E,S,A,O,M,N,I,L,j,F,P,R,U,Y,H,W,q,V,B,z,$,G,K,X,Q,Z,J,ee,te,ne,ie,re,oe,ae,se,le,ue,ce,de,fe,he,pe,me,ge,ve,ye,be,_e,we,xe,De,Te,Ce,ke,Ee,Se,Ae,Oe,Me,Ne,Ie,Le,je,Fe,Pe,Re,Ue,Ye,He,We,qe,Ve,Be,ze,$e,Ge,Ke,Xe,Qe,Ze,Je,et,tt,nt,it,rt,ot,at,st,lt,ut,ct,dt,ft,ht,pt,mt,gt,vt,yt,bt,_t,wt,xt,Dt,Tt,Ct=function(e){var t="transitionend";var n={TRANSITION_END:"bsTransitionEnd",getUID:function(e){for(;e+=~~(1e6*Math.random()),document.getElementById(e););return e},getSelectorFromElement:function(t){var n=t.getAttribute("data-target");n&&"#"!==n||(n=t.getAttribute("href")||"");try{return 0<e(document).find(n).length?n:null}catch(t){return null}},getTransitionDurationFromElement:function(t){if(!t)return 0;var n=e(t).css("transition-duration");return parseFloat(n)?(n=n.split(",")[0],1e3*parseFloat(n)):0},reflow:function(e){return e.offsetHeight},triggerTransitionEnd:function(n){e(n).trigger(t)},supportsTransitionEnd:function(){return Boolean(t)},isElement:function(e){return(e[0]||e).nodeType},typeCheckConfig:function(e,t,i){for(var r in i)if(Object.prototype.hasOwnProperty.call(i,r)){var o=i[r],a=t[r],s=a&&n.isElement(a)?"element":(l=a,{}.toString.call(l).match(/\s([a-z]+)/i)[1].toLowerCase());if(!new RegExp(o).test(s))throw new Error(e.toUpperCase()+': Option "'+r+'" provided type "'+s+'" but expected type "'+o+'".')}var l}};return e.fn.emulateTransitionEnd=function(t){var i=this,r=!1;return e(this).one(n.TRANSITION_END,(function(){r=!0})),setTimeout((function(){r||n.triggerTransitionEnd(i)}),t),this},e.event.special[n.TRANSITION_END]={bindType:t,delegateType:t,handle:function(t){if(e(t.target).is(this))return t.handleObj.handler.apply(this,arguments)}},n}(t),kt=(s="alert",u="."+(l="bs.alert"),c=(a=t).fn[s],d={CLOSE:"close"+u,CLOSED:"closed"+u,CLICK_DATA_API:"click"+u+".data-api"},"alert","fade","show",f=function(){function e(e){this._element=e}var t=e.prototype;return t.close=function(e){var t=this._element;e&&(t=this._getRootElement(e)),this._triggerCloseEvent(t).isDefaultPrevented()||this._removeElement(t)},t.dispose=function(){a.removeData(this._element,l),this._element=null},t._getRootElement=function(e){var t=Ct.getSelectorFromElement(e),n=!1;return t&&(n=a(t)[0]),n||(n=a(e).closest(".alert")[0]),n},t._triggerCloseEvent=function(e){var t=a.Event(d.CLOSE);return a(e).trigger(t),t},t._removeElement=function(e){var t=this;if(a(e).removeClass("show"),a(e).hasClass("fade")){var n=Ct.getTransitionDurationFromElement(e);a(e).one(Ct.TRANSITION_END,(function(n){return t._destroyElement(e,n)})).emulateTransitionEnd(n)}else this._destroyElement(e)},t._destroyElement=function(e){a(e).detach().trigger(d.CLOSED).remove()},e._jQueryInterface=function(t){return this.each((function(){var n=a(this),i=n.data(l);i||(i=new e(this),n.data(l,i)),"close"===t&&i[t](this)}))},e._handleDismiss=function(e){return function(t){t&&t.preventDefault(),e.close(this)}},r(e,null,[{key:"VERSION",get:function(){return"4.1.1"}}]),e}(),a(document).on(d.CLICK_DATA_API,'[data-dismiss="alert"]',f._handleDismiss(new f)),a.fn[s]=f._jQueryInterface,a.fn[s].Constructor=f,a.fn[s].noConflict=function(){return a.fn[s]=c,f._jQueryInterface},f),Et=(p="button",g="."+(m="bs.button"),v=".data-api",y=(h=t).fn[p],b="active","btn",_='[data-toggle^="button"]','[data-toggle="buttons"]',"input",".active",w=".btn",x={CLICK_DATA_API:"click"+g+v,FOCUS_BLUR_DATA_API:"focus"+g+v+" blur"+g+v},D=function(){function e(e){this._element=e}var t=e.prototype;return t.toggle=function(){var e=!0,t=!0,n=h(this._element).closest('[data-toggle="buttons"]')[0];if(n){var i=h(this._element).find("input")[0];if(i){if("radio"===i.type)if(i.checked&&h(this._element).hasClass(b))e=!1;else{var r=h(n).find(".active")[0];r&&h(r).removeClass(b)}if(e){if(i.hasAttribute("disabled")||n.hasAttribute("disabled")||i.classList.contains("disabled")||n.classList.contains("disabled"))return;i.checked=!h(this._element).hasClass(b),h(i).trigger("change")}i.focus(),t=!1}}t&&this._element.setAttribute("aria-pressed",!h(this._element).hasClass(b)),e&&h(this._element).toggleClass(b)},t.dispose=function(){h.removeData(this._element,m),this._element=null},e._jQueryInterface=function(t){return this.each((function(){var n=h(this).data(m);n||(n=new e(this),h(this).data(m,n)),"toggle"===t&&n[t]()}))},r(e,null,[{key:"VERSION",get:function(){return"4.1.1"}}]),e}(),h(document).on(x.CLICK_DATA_API,_,(function(e){e.preventDefault();var t=e.target;h(t).hasClass("btn")||(t=h(t).closest(w)),D._jQueryInterface.call(h(t),"toggle")})).on(x.FOCUS_BLUR_DATA_API,_,(function(e){var t=h(e.target).closest(w)[0];h(t).toggleClass("focus",/^focus(in)?$/.test(e.type))})),h.fn[p]=D._jQueryInterface,h.fn[p].Constructor=D,h.fn[p].noConflict=function(){return h.fn[p]=y,D._jQueryInterface},D),St=(C="carousel",E="."+(k="bs.carousel"),S=".data-api",A=(T=t).fn[C],O={interval:5e3,keyboard:!0,slide:!1,pause:"hover",wrap:!0},M={interval:"(number|boolean)",keyboard:"boolean",slide:"(boolean|string)",pause:"(string|boolean)",wrap:"boolean"},N="next",I="prev","left","right",L={SLIDE:"slide"+E,SLID:"slid"+E,KEYDOWN:"keydown"+E,MOUSEENTER:"mouseenter"+E,MOUSELEAVE:"mouseleave"+E,TOUCHEND:"touchend"+E,LOAD_DATA_API:"load"+E+S,CLICK_DATA_API:"click"+E+S},"carousel",j="active","slide","carousel-item-right","carousel-item-left","carousel-item-next","carousel-item-prev",F={ACTIVE:".active",ACTIVE_ITEM:".active.carousel-item",ITEM:".carousel-item",NEXT_PREV:".carousel-item-next, .carousel-item-prev",INDICATORS:".carousel-indicators",DATA_SLIDE:"[data-slide], [data-slide-to]",DATA_RIDE:'[data-ride="carousel"]'},P=function(){function e(e,t){this._items=null,this._interval=null,this._activeElement=null,this._isPaused=!1,this._isSliding=!1,this.touchTimeout=null,this._config=this._getConfig(t),this._element=T(e)[0],this._indicatorsElement=T(this._element).find(F.INDICATORS)[0],this._addEventListeners()}var t=e.prototype;return t.next=function(){this._isSliding||this._slide(N)},t.nextWhenVisible=function(){!document.hidden&&T(this._element).is(":visible")&&"hidden"!==T(this._element).css("visibility")&&this.next()},t.prev=function(){this._isSliding||this._slide(I)},t.pause=function(e){e||(this._isPaused=!0),T(this._element).find(F.NEXT_PREV)[0]&&(Ct.triggerTransitionEnd(this._element),this.cycle(!0)),clearInterval(this._interval),this._interval=null},t.cycle=function(e){e||(this._isPaused=!1),this._interval&&(clearInterval(this._interval),this._interval=null),this._config.interval&&!this._isPaused&&(this._interval=setInterval((document.visibilityState?this.nextWhenVisible:this.next).bind(this),this._config.interval))},t.to=function(e){var t=this;this._activeElement=T(this._element).find(F.ACTIVE_ITEM)[0];var n=this._getItemIndex(this._activeElement);if(!(e>this._items.length-1||e<0))if(this._isSliding)T(this._element).one(L.SLID,(function(){return t.to(e)}));else{if(n===e)return this.pause(),void this.cycle();var i=n<e?N:I;this._slide(i,this._items[e])}},t.dispose=function(){T(this._element).off(E),T.removeData(this._element,k),this._items=null,this._config=null,this._element=null,this._interval=null,this._isPaused=null,this._isSliding=null,this._activeElement=null,this._indicatorsElement=null},t._getConfig=function(e){return e=o({},O,e),Ct.typeCheckConfig(C,e,M),e},t._addEventListeners=function(){var e=this;this._config.keyboard&&T(this._element).on(L.KEYDOWN,(function(t){return e._keydown(t)})),"hover"===this._config.pause&&(T(this._element).on(L.MOUSEENTER,(function(t){return e.pause(t)})).on(L.MOUSELEAVE,(function(t){return e.cycle(t)})),"ontouchstart"in document.documentElement&&T(this._element).on(L.TOUCHEND,(function(){e.pause(),e.touchTimeout&&clearTimeout(e.touchTimeout),e.touchTimeout=setTimeout((function(t){return e.cycle(t)}),500+e._config.interval)})))},t._keydown=function(e){if(!/input|textarea/i.test(e.target.tagName))switch(e.which){case 37:e.preventDefault(),this.prev();break;case 39:e.preventDefault(),this.next()}},t._getItemIndex=function(e){return this._items=T.makeArray(T(e).parent().find(F.ITEM)),this._items.indexOf(e)},t._getItemByDirection=function(e,t){var n=e===N,i=e===I,r=this._getItemIndex(t),o=this._items.length-1;if((i&&0===r||n&&r===o)&&!this._config.wrap)return t;var a=(r+(e===I?-1:1))%this._items.length;return-1===a?this._items[this._items.length-1]:this._items[a]},t._triggerSlideEvent=function(e,t){var n=this._getItemIndex(e),i=this._getItemIndex(T(this._element).find(F.ACTIVE_ITEM)[0]),r=T.Event(L.SLIDE,{relatedTarget:e,direction:t,from:i,to:n});return T(this._element).trigger(r),r},t._setActiveIndicatorElement=function(e){if(this._indicatorsElement){T(this._indicatorsElement).find(F.ACTIVE).removeClass(j);var t=this._indicatorsElement.children[this._getItemIndex(e)];t&&T(t).addClass(j)}},t._slide=function(e,t){var n,i,r,o=this,a=T(this._element).find(F.ACTIVE_ITEM)[0],s=this._getItemIndex(a),l=t||a&&this._getItemByDirection(e,a),u=this._getItemIndex(l),c=Boolean(this._interval);if(e===N?(n="carousel-item-left",i="carousel-item-next",r="left"):(n="carousel-item-right",i="carousel-item-prev",r="right"),l&&T(l).hasClass(j))this._isSliding=!1;else if(!this._triggerSlideEvent(l,r).isDefaultPrevented()&&a&&l){this._isSliding=!0,c&&this.pause(),this._setActiveIndicatorElement(l);var d=T.Event(L.SLID,{relatedTarget:l,direction:r,from:s,to:u});if(T(this._element).hasClass("slide")){T(l).addClass(i),Ct.reflow(l),T(a).addClass(n),T(l).addClass(n);var f=Ct.getTransitionDurationFromElement(a);T(a).one(Ct.TRANSITION_END,(function(){T(l).removeClass(n+" "+i).addClass(j),T(a).removeClass(j+" "+i+" "+n),o._isSliding=!1,setTimeout((function(){return T(o._element).trigger(d)}),0)})).emulateTransitionEnd(f)}else T(a).removeClass(j),T(l).addClass(j),this._isSliding=!1,T(this._element).trigger(d);c&&this.cycle()}},e._jQueryInterface=function(t){return this.each((function(){var n=T(this).data(k),i=o({},O,T(this).data());"object"==typeof t&&(i=o({},i,t));var r="string"==typeof t?t:i.slide;if(n||(n=new e(this,i),T(this).data(k,n)),"number"==typeof t)n.to(t);else if("string"==typeof r){if(void 0===n[r])throw new TypeError('No method named "'+r+'"');n[r]()}else i.interval&&(n.pause(),n.cycle())}))},e._dataApiClickHandler=function(t){var n=Ct.getSelectorFromElement(this);if(n){var i=T(n)[0];if(i&&T(i).hasClass("carousel")){var r=o({},T(i).data(),T(this).data()),a=this.getAttribute("data-slide-to");a&&(r.interval=!1),e._jQueryInterface.call(T(i),r),a&&T(i).data(k).to(a),t.preventDefault()}}},r(e,null,[{key:"VERSION",get:function(){return"4.1.1"}},{key:"Default",get:function(){return O}}]),e}(),T(document).on(L.CLICK_DATA_API,F.DATA_SLIDE,P._dataApiClickHandler),T(window).on(L.LOAD_DATA_API,(function(){T(F.DATA_RIDE).each((function(){var e=T(this);P._jQueryInterface.call(e,e.data())}))})),T.fn[C]=P._jQueryInterface,T.fn[C].Constructor=P,T.fn[C].noConflict=function(){return T.fn[C]=A,P._jQueryInterface},P),At=(U="collapse",H="."+(Y="bs.collapse"),W=(R=t).fn[U],q={toggle:!0,parent:""},V={toggle:"boolean",parent:"(string|element)"},B={SHOW:"show"+H,SHOWN:"shown"+H,HIDE:"hide"+H,HIDDEN:"hidden"+H,CLICK_DATA_API:"click"+H+".data-api"},z="show",$="collapse",G="collapsing",K="collapsed",X="width","height",Q={ACTIVES:".show, .collapsing",DATA_TOGGLE:'[data-toggle="collapse"]'},Z=function(){function e(e,t){this._isTransitioning=!1,this._element=e,this._config=this._getConfig(t),this._triggerArray=R.makeArray(R('[data-toggle="collapse"][href="#'+e.id+'"],[data-toggle="collapse"][data-target="#'+e.id+'"]'));for(var n=R(Q.DATA_TOGGLE),i=0;i<n.length;i++){var r=n[i],o=Ct.getSelectorFromElement(r);null!==o&&0<R(o).filter(e).length&&(this._selector=o,this._triggerArray.push(r))}this._parent=this._config.parent?this._getParent():null,this._config.parent||this._addAriaAndCollapsedClass(this._element,this._triggerArray),this._config.toggle&&this.toggle()}var t=e.prototype;return t.toggle=function(){R(this._element).hasClass(z)?this.hide():this.show()},t.show=function(){var t,n,i=this;if(!(this._isTransitioning||R(this._element).hasClass(z)||(this._parent&&0===(t=R.makeArray(R(this._parent).find(Q.ACTIVES).filter('[data-parent="'+this._config.parent+'"]'))).length&&(t=null),t&&(n=R(t).not(this._selector).data(Y))&&n._isTransitioning))){var r=R.Event(B.SHOW);if(R(this._element).trigger(r),!r.isDefaultPrevented()){t&&(e._jQueryInterface.call(R(t).not(this._selector),"hide"),n||R(t).data(Y,null));var o=this._getDimension();R(this._element).removeClass($).addClass(G),(this._element.style[o]=0)<this._triggerArray.length&&R(this._triggerArray).removeClass(K).attr("aria-expanded",!0),this.setTransitioning(!0);var a="scroll"+(o[0].toUpperCase()+o.slice(1)),s=Ct.getTransitionDurationFromElement(this._element);R(this._element).one(Ct.TRANSITION_END,(function(){R(i._element).removeClass(G).addClass($).addClass(z),i._element.style[o]="",i.setTransitioning(!1),R(i._element).trigger(B.SHOWN)})).emulateTransitionEnd(s),this._element.style[o]=this._element[a]+"px"}}},t.hide=function(){var e=this;if(!this._isTransitioning&&R(this._element).hasClass(z)){var t=R.Event(B.HIDE);if(R(this._element).trigger(t),!t.isDefaultPrevented()){var n=this._getDimension();if(this._element.style[n]=this._element.getBoundingClientRect()[n]+"px",Ct.reflow(this._element),R(this._element).addClass(G).removeClass($).removeClass(z),0<this._triggerArray.length)for(var i=0;i<this._triggerArray.length;i++){var r=this._triggerArray[i],o=Ct.getSelectorFromElement(r);null!==o&&(R(o).hasClass(z)||R(r).addClass(K).attr("aria-expanded",!1))}this.setTransitioning(!0),this._element.style[n]="";var a=Ct.getTransitionDurationFromElement(this._element);R(this._element).one(Ct.TRANSITION_END,(function(){e.setTransitioning(!1),R(e._element).removeClass(G).addClass($).trigger(B.HIDDEN)})).emulateTransitionEnd(a)}}},t.setTransitioning=function(e){this._isTransitioning=e},t.dispose=function(){R.removeData(this._element,Y),this._config=null,this._parent=null,this._element=null,this._triggerArray=null,this._isTransitioning=null},t._getConfig=function(e){return(e=o({},q,e)).toggle=Boolean(e.toggle),Ct.typeCheckConfig(U,e,V),e},t._getDimension=function(){return R(this._element).hasClass(X)?X:"height"},t._getParent=function(){var t=this,n=null;Ct.isElement(this._config.parent)?(n=this._config.parent,void 0!==this._config.parent.jquery&&(n=this._config.parent[0])):n=R(this._config.parent)[0];var i='[data-toggle="collapse"][data-parent="'+this._config.parent+'"]';return R(n).find(i).each((function(n,i){t._addAriaAndCollapsedClass(e._getTargetFromElement(i),[i])})),n},t._addAriaAndCollapsedClass=function(e,t){if(e){var n=R(e).hasClass(z);0<t.length&&R(t).toggleClass(K,!n).attr("aria-expanded",n)}},e._getTargetFromElement=function(e){var t=Ct.getSelectorFromElement(e);return t?R(t)[0]:null},e._jQueryInterface=function(t){return this.each((function(){var n=R(this),i=n.data(Y),r=o({},q,n.data(),"object"==typeof t&&t?t:{});if(!i&&r.toggle&&/show|hide/.test(t)&&(r.toggle=!1),i||(i=new e(this,r),n.data(Y,i)),"string"==typeof t){if(void 0===i[t])throw new TypeError('No method named "'+t+'"');i[t]()}}))},r(e,null,[{key:"VERSION",get:function(){return"4.1.1"}},{key:"Default",get:function(){return q}}]),e}(),R(document).on(B.CLICK_DATA_API,Q.DATA_TOGGLE,(function(e){"A"===e.currentTarget.tagName&&e.preventDefault();var t=R(this),n=Ct.getSelectorFromElement(this);R(n).each((function(){var e=R(this),n=e.data(Y)?"toggle":t.data();Z._jQueryInterface.call(e,n)}))})),R.fn[U]=Z._jQueryInterface,R.fn[U].Constructor=Z,R.fn[U].noConflict=function(){return R.fn[U]=W,Z._jQueryInterface},Z),Ot=(ee="dropdown",ne="."+(te="bs.dropdown"),ie=".data-api",re=(J=t).fn[ee],oe=new RegExp("38|40|27"),ae={HIDE:"hide"+ne,HIDDEN:"hidden"+ne,SHOW:"show"+ne,SHOWN:"shown"+ne,CLICK:"click"+ne,CLICK_DATA_API:"click"+ne+ie,KEYDOWN_DATA_API:"keydown"+ne+ie,KEYUP_DATA_API:"keyup"+ne+ie},se="disabled",le="show","dropup","dropright","dropleft",ue="dropdown-menu-right","position-static",ce='[data-toggle="dropdown"]',".dropdown form",de=".dropdown-menu",".navbar-nav",".dropdown-menu .dropdown-item:not(.disabled):not(:disabled)","top-start","top-end","bottom-start","bottom-end","right-start","left-start",fe={offset:0,flip:!0,boundary:"scrollParent",reference:"toggle",display:"dynamic"},he={offset:"(number|string|function)",flip:"boolean",boundary:"(string|element)",reference:"(string|element)",display:"string"},pe=function(){function e(e,t){this._element=e,this._popper=null,this._config=this._getConfig(t),this._menu=this._getMenuElement(),this._inNavbar=this._detectNavbar(),this._addEventListeners()}var t=e.prototype;return t.toggle=function(){if(!this._element.disabled&&!J(this._element).hasClass(se)){var t=e._getParentFromElement(this._element),i=J(this._menu).hasClass(le);if(e._clearMenus(),!i){var r={relatedTarget:this._element},o=J.Event(ae.SHOW,r);if(J(t).trigger(o),!o.isDefaultPrevented()){if(!this._inNavbar){if(void 0===n)throw new TypeError("Bootstrap dropdown require Popper.js (https://popper.js.org)");var a=this._element;"parent"===this._config.reference?a=t:Ct.isElement(this._config.reference)&&(a=this._config.reference,void 0!==this._config.reference.jquery&&(a=this._config.reference[0])),"scrollParent"!==this._config.boundary&&J(t).addClass("position-static"),this._popper=new n(a,this._menu,this._getPopperConfig())}"ontouchstart"in document.documentElement&&0===J(t).closest(".navbar-nav").length&&J(document.body).children().on("mouseover",null,J.noop),this._element.focus(),this._element.setAttribute("aria-expanded",!0),J(this._menu).toggleClass(le),J(t).toggleClass(le).trigger(J.Event(ae.SHOWN,r))}}}},t.dispose=function(){J.removeData(this._element,te),J(this._element).off(ne),this._element=null,(this._menu=null)!==this._popper&&(this._popper.destroy(),this._popper=null)},t.update=function(){this._inNavbar=this._detectNavbar(),null!==this._popper&&this._popper.scheduleUpdate()},t._addEventListeners=function(){var e=this;J(this._element).on(ae.CLICK,(function(t){t.preventDefault(),t.stopPropagation(),e.toggle()}))},t._getConfig=function(e){return e=o({},this.constructor.Default,J(this._element).data(),e),Ct.typeCheckConfig(ee,e,this.constructor.DefaultType),e},t._getMenuElement=function(){if(!this._menu){var t=e._getParentFromElement(this._element);this._menu=J(t).find(de)[0]}return this._menu},t._getPlacement=function(){var e=J(this._element).parent(),t="bottom-start";return e.hasClass("dropup")?(t="top-start",J(this._menu).hasClass(ue)&&(t="top-end")):e.hasClass("dropright")?t="right-start":e.hasClass("dropleft")?t="left-start":J(this._menu).hasClass(ue)&&(t="bottom-end"),t},t._detectNavbar=function(){return 0<J(this._element).closest(".navbar").length},t._getPopperConfig=function(){var e=this,t={};"function"==typeof this._config.offset?t.fn=function(t){return t.offsets=o({},t.offsets,e._config.offset(t.offsets)||{}),t}:t.offset=this._config.offset;var n={placement:this._getPlacement(),modifiers:{offset:t,flip:{enabled:this._config.flip},preventOverflow:{boundariesElement:this._config.boundary}}};return"static"===this._config.display&&(n.modifiers.applyStyle={enabled:!1}),n},e._jQueryInterface=function(t){return this.each((function(){var n=J(this).data(te);if(n||(n=new e(this,"object"==typeof t?t:null),J(this).data(te,n)),"string"==typeof t){if(void 0===n[t])throw new TypeError('No method named "'+t+'"');n[t]()}}))},e._clearMenus=function(t){if(!t||3!==t.which&&("keyup"!==t.type||9===t.which))for(var n=J.makeArray(J(ce)),i=0;i<n.length;i++){var r=e._getParentFromElement(n[i]),o=J(n[i]).data(te),a={relatedTarget:n[i]};if(o){var s=o._menu;if(J(r).hasClass(le)&&!(t&&("click"===t.type&&/input|textarea/i.test(t.target.tagName)||"keyup"===t.type&&9===t.which)&&J.contains(r,t.target))){var l=J.Event(ae.HIDE,a);J(r).trigger(l),l.isDefaultPrevented()||("ontouchstart"in document.documentElement&&J(document.body).children().off("mouseover",null,J.noop),n[i].setAttribute("aria-expanded","false"),J(s).removeClass(le),J(r).removeClass(le).trigger(J.Event(ae.HIDDEN,a)))}}}},e._getParentFromElement=function(e){var t,n=Ct.getSelectorFromElement(e);return n&&(t=J(n)[0]),t||e.parentNode},e._dataApiKeydownHandler=function(t){if((/input|textarea/i.test(t.target.tagName)?!(32===t.which||27!==t.which&&(40!==t.which&&38!==t.which||J(t.target).closest(de).length)):oe.test(t.which))&&(t.preventDefault(),t.stopPropagation(),!this.disabled&&!J(this).hasClass(se))){var n=e._getParentFromElement(this),i=J(n).hasClass(le);if((i||27===t.which&&32===t.which)&&(!i||27!==t.which&&32!==t.which)){var r=J(n).find(".dropdown-menu .dropdown-item:not(.disabled):not(:disabled)").get();if(0!==r.length){var o=r.indexOf(t.target);38===t.which&&0<o&&o--,40===t.which&&o<r.length-1&&o++,o<0&&(o=0),r[o].focus()}}else{if(27===t.which){var a=J(n).find(ce)[0];J(a).trigger("focus")}J(this).trigger("click")}}},r(e,null,[{key:"VERSION",get:function(){return"4.1.1"}},{key:"Default",get:function(){return fe}},{key:"DefaultType",get:function(){return he}}]),e}(),J(document).on(ae.KEYDOWN_DATA_API,ce,pe._dataApiKeydownHandler).on(ae.KEYDOWN_DATA_API,de,pe._dataApiKeydownHandler).on(ae.CLICK_DATA_API+" "+ae.KEYUP_DATA_API,pe._clearMenus).on(ae.CLICK_DATA_API,ce,(function(e){e.preventDefault(),e.stopPropagation(),pe._jQueryInterface.call(J(this),"toggle")})).on(ae.CLICK_DATA_API,".dropdown form",(function(e){e.stopPropagation()})),J.fn[ee]=pe._jQueryInterface,J.fn[ee].Constructor=pe,J.fn[ee].noConflict=function(){return J.fn[ee]=re,pe._jQueryInterface},pe),Mt=(ge="modal",ye="."+(ve="bs.modal"),be=(me=t).fn[ge],_e={backdrop:!0,keyboard:!0,focus:!0,show:!0},we={backdrop:"(boolean|string)",keyboard:"boolean",focus:"boolean",show:"boolean"},xe={HIDE:"hide"+ye,HIDDEN:"hidden"+ye,SHOW:"show"+ye,SHOWN:"shown"+ye,FOCUSIN:"focusin"+ye,RESIZE:"resize"+ye,CLICK_DISMISS:"click.dismiss"+ye,KEYDOWN_DISMISS:"keydown.dismiss"+ye,MOUSEUP_DISMISS:"mouseup.dismiss"+ye,MOUSEDOWN_DISMISS:"mousedown.dismiss"+ye,CLICK_DATA_API:"click"+ye+".data-api"},"modal-scrollbar-measure","modal-backdrop",De="modal-open",Te="fade",Ce="show",ke={DIALOG:".modal-dialog",DATA_TOGGLE:'[data-toggle="modal"]',DATA_DISMISS:'[data-dismiss="modal"]',FIXED_CONTENT:".fixed-top, .fixed-bottom, .is-fixed, .sticky-top",STICKY_CONTENT:".sticky-top",NAVBAR_TOGGLER:".navbar-toggler"},Ee=function(){function e(e,t){this._config=this._getConfig(t),this._element=e,this._dialog=me(e).find(ke.DIALOG)[0],this._backdrop=null,this._isShown=!1,this._isBodyOverflowing=!1,this._ignoreBackdropClick=!1,this._scrollbarWidth=0}var t=e.prototype;return t.toggle=function(e){return this._isShown?this.hide():this.show(e)},t.show=function(e){var t=this;if(!this._isTransitioning&&!this._isShown){me(this._element).hasClass(Te)&&(this._isTransitioning=!0);var n=me.Event(xe.SHOW,{relatedTarget:e});me(this._element).trigger(n),this._isShown||n.isDefaultPrevented()||(this._isShown=!0,this._checkScrollbar(),this._setScrollbar(),this._adjustDialog(),me(document.body).addClass(De),this._setEscapeEvent(),this._setResizeEvent(),me(this._element).on(xe.CLICK_DISMISS,ke.DATA_DISMISS,(function(e){return t.hide(e)})),me(this._dialog).on(xe.MOUSEDOWN_DISMISS,(function(){me(t._element).one(xe.MOUSEUP_DISMISS,(function(e){me(e.target).is(t._element)&&(t._ignoreBackdropClick=!0)}))})),this._showBackdrop((function(){return t._showElement(e)})))}},t.hide=function(e){var t=this;if(e&&e.preventDefault(),!this._isTransitioning&&this._isShown){var n=me.Event(xe.HIDE);if(me(this._element).trigger(n),this._isShown&&!n.isDefaultPrevented()){this._isShown=!1;var i=me(this._element).hasClass(Te);if(i&&(this._isTransitioning=!0),this._setEscapeEvent(),this._setResizeEvent(),me(document).off(xe.FOCUSIN),me(this._element).removeClass(Ce),me(this._element).off(xe.CLICK_DISMISS),me(this._dialog).off(xe.MOUSEDOWN_DISMISS),i){var r=Ct.getTransitionDurationFromElement(this._element);me(this._element).one(Ct.TRANSITION_END,(function(e){return t._hideModal(e)})).emulateTransitionEnd(r)}else this._hideModal()}}},t.dispose=function(){me.removeData(this._element,ve),me(window,document,this._element,this._backdrop).off(ye),this._config=null,this._element=null,this._dialog=null,this._backdrop=null,this._isShown=null,this._isBodyOverflowing=null,this._ignoreBackdropClick=null,this._scrollbarWidth=null},t.handleUpdate=function(){this._adjustDialog()},t._getConfig=function(e){return e=o({},_e,e),Ct.typeCheckConfig(ge,e,we),e},t._showElement=function(e){var t=this,n=me(this._element).hasClass(Te);this._element.parentNode&&this._element.parentNode.nodeType===Node.ELEMENT_NODE||document.body.appendChild(this._element),this._element.style.display="block",this._element.removeAttribute("aria-hidden"),this._element.scrollTop=0,n&&Ct.reflow(this._element),me(this._element).addClass(Ce),this._config.focus&&this._enforceFocus();var i=me.Event(xe.SHOWN,{relatedTarget:e}),r=function(){t._config.focus&&t._element.focus(),t._isTransitioning=!1,me(t._element).trigger(i)};if(n){var o=Ct.getTransitionDurationFromElement(this._element);me(this._dialog).one(Ct.TRANSITION_END,r).emulateTransitionEnd(o)}else r()},t._enforceFocus=function(){var e=this;me(document).off(xe.FOCUSIN).on(xe.FOCUSIN,(function(t){document!==t.target&&e._element!==t.target&&0===me(e._element).has(t.target).length&&e._element.focus()}))},t._setEscapeEvent=function(){var e=this;this._isShown&&this._config.keyboard?me(this._element).on(xe.KEYDOWN_DISMISS,(function(t){27===t.which&&(t.preventDefault(),e.hide())})):this._isShown||me(this._element).off(xe.KEYDOWN_DISMISS)},t._setResizeEvent=function(){var e=this;this._isShown?me(window).on(xe.RESIZE,(function(t){return e.handleUpdate(t)})):me(window).off(xe.RESIZE)},t._hideModal=function(){var e=this;this._element.style.display="none",this._element.setAttribute("aria-hidden",!0),this._isTransitioning=!1,this._showBackdrop((function(){me(document.body).removeClass(De),e._resetAdjustments(),e._resetScrollbar(),me(e._element).trigger(xe.HIDDEN)}))},t._removeBackdrop=function(){this._backdrop&&(me(this._backdrop).remove(),this._backdrop=null)},t._showBackdrop=function(e){var t=this,n=me(this._element).hasClass(Te)?Te:"";if(this._isShown&&this._config.backdrop){if(this._backdrop=document.createElement("div"),this._backdrop.className="modal-backdrop",n&&me(this._backdrop).addClass(n),me(this._backdrop).appendTo(document.body),me(this._element).on(xe.CLICK_DISMISS,(function(e){t._ignoreBackdropClick?t._ignoreBackdropClick=!1:e.target===e.currentTarget&&("static"===t._config.backdrop?t._element.focus():t.hide())})),n&&Ct.reflow(this._backdrop),me(this._backdrop).addClass(Ce),!e)return;if(!n)return void e();var i=Ct.getTransitionDurationFromElement(this._backdrop);me(this._backdrop).one(Ct.TRANSITION_END,e).emulateTransitionEnd(i)}else if(!this._isShown&&this._backdrop){me(this._backdrop).removeClass(Ce);var r=function(){t._removeBackdrop(),e&&e()};if(me(this._element).hasClass(Te)){var o=Ct.getTransitionDurationFromElement(this._backdrop);me(this._backdrop).one(Ct.TRANSITION_END,r).emulateTransitionEnd(o)}else r()}else e&&e()},t._adjustDialog=function(){var e=this._element.scrollHeight>document.documentElement.clientHeight;!this._isBodyOverflowing&&e&&(this._element.style.paddingLeft=this._scrollbarWidth+"px"),this._isBodyOverflowing&&!e&&(this._element.style.paddingRight=this._scrollbarWidth+"px")},t._resetAdjustments=function(){this._element.style.paddingLeft="",this._element.style.paddingRight=""},t._checkScrollbar=function(){var e=document.body.getBoundingClientRect();this._isBodyOverflowing=e.left+e.right<window.innerWidth,this._scrollbarWidth=this._getScrollbarWidth()},t._setScrollbar=function(){var e=this;if(this._isBodyOverflowing){me(ke.FIXED_CONTENT).each((function(t,n){var i=me(n)[0].style.paddingRight,r=me(n).css("padding-right");me(n).data("padding-right",i).css("padding-right",parseFloat(r)+e._scrollbarWidth+"px")})),me(ke.STICKY_CONTENT).each((function(t,n){var i=me(n)[0].style.marginRight,r=me(n).css("margin-right");me(n).data("margin-right",i).css("margin-right",parseFloat(r)-e._scrollbarWidth+"px")})),me(ke.NAVBAR_TOGGLER).each((function(t,n){var i=me(n)[0].style.marginRight,r=me(n).css("margin-right");me(n).data("margin-right",i).css("margin-right",parseFloat(r)+e._scrollbarWidth+"px")}));var t=document.body.style.paddingRight,n=me(document.body).css("padding-right");me(document.body).data("padding-right",t).css("padding-right",parseFloat(n)+this._scrollbarWidth+"px")}},t._resetScrollbar=function(){me(ke.FIXED_CONTENT).each((function(e,t){var n=me(t).data("padding-right");void 0!==n&&me(t).css("padding-right",n).removeData("padding-right")})),me(ke.STICKY_CONTENT+", "+ke.NAVBAR_TOGGLER).each((function(e,t){var n=me(t).data("margin-right");void 0!==n&&me(t).css("margin-right",n).removeData("margin-right")}));var e=me(document.body).data("padding-right");void 0!==e&&me(document.body).css("padding-right",e).removeData("padding-right")},t._getScrollbarWidth=function(){var e=document.createElement("div");e.className="modal-scrollbar-measure",document.body.appendChild(e);var t=e.getBoundingClientRect().width-e.clientWidth;return document.body.removeChild(e),t},e._jQueryInterface=function(t,n){return this.each((function(){var i=me(this).data(ve),r=o({},_e,me(this).data(),"object"==typeof t&&t?t:{});if(i||(i=new e(this,r),me(this).data(ve,i)),"string"==typeof t){if(void 0===i[t])throw new TypeError('No method named "'+t+'"');i[t](n)}else r.show&&i.show(n)}))},r(e,null,[{key:"VERSION",get:function(){return"4.1.1"}},{key:"Default",get:function(){return _e}}]),e}(),me(document).on(xe.CLICK_DATA_API,ke.DATA_TOGGLE,(function(e){var t,n=this,i=Ct.getSelectorFromElement(this);i&&(t=me(i)[0]);var r=me(t).data(ve)?"toggle":o({},me(t).data(),me(this).data());"A"!==this.tagName&&"AREA"!==this.tagName||e.preventDefault();var a=me(t).one(xe.SHOW,(function(e){e.isDefaultPrevented()||a.one(xe.HIDDEN,(function(){me(n).is(":visible")&&n.focus()}))}));Ee._jQueryInterface.call(me(t),r,this)})),me.fn[ge]=Ee._jQueryInterface,me.fn[ge].Constructor=Ee,me.fn[ge].noConflict=function(){return me.fn[ge]=be,Ee._jQueryInterface},Ee),Nt=(Ae="tooltip",Me="."+(Oe="bs.tooltip"),Ne=(Se=t).fn[Ae],Ie="bs-tooltip",Le=new RegExp("(^|\\s)"+Ie+"\\S+","g"),Pe={animation:!0,template:'<div class="tooltip" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>',trigger:"hover focus",title:"",delay:0,html:!(Fe={AUTO:"auto",TOP:"top",RIGHT:"right",BOTTOM:"bottom",LEFT:"left"}),selector:!(je={animation:"boolean",template:"string",title:"(string|element|function)",trigger:"string",delay:"(number|object)",html:"boolean",selector:"(string|boolean)",placement:"(string|function)",offset:"(number|string)",container:"(string|element|boolean)",fallbackPlacement:"(string|array)",boundary:"(string|element)"}),placement:"top",offset:0,container:!1,fallbackPlacement:"flip",boundary:"scrollParent"},Ue="out",Ye={HIDE:"hide"+Me,HIDDEN:"hidden"+Me,SHOW:(Re="show")+Me,SHOWN:"shown"+Me,INSERTED:"inserted"+Me,CLICK:"click"+Me,FOCUSIN:"focusin"+Me,FOCUSOUT:"focusout"+Me,MOUSEENTER:"mouseenter"+Me,MOUSELEAVE:"mouseleave"+Me},He="fade",We="show",".tooltip-inner",".arrow",qe="hover",Ve="focus","click","manual",Be=function(){function e(e,t){if(void 0===n)throw new TypeError("Bootstrap tooltips require Popper.js (https://popper.js.org)");this._isEnabled=!0,this._timeout=0,this._hoverState="",this._activeTrigger={},this._popper=null,this.element=e,this.config=this._getConfig(t),this.tip=null,this._setListeners()}var t=e.prototype;return t.enable=function(){this._isEnabled=!0},t.disable=function(){this._isEnabled=!1},t.toggleEnabled=function(){this._isEnabled=!this._isEnabled},t.toggle=function(e){if(this._isEnabled)if(e){var t=this.constructor.DATA_KEY,n=Se(e.currentTarget).data(t);n||(n=new this.constructor(e.currentTarget,this._getDelegateConfig()),Se(e.currentTarget).data(t,n)),n._activeTrigger.click=!n._activeTrigger.click,n._isWithActiveTrigger()?n._enter(null,n):n._leave(null,n)}else{if(Se(this.getTipElement()).hasClass(We))return void this._leave(null,this);this._enter(null,this)}},t.dispose=function(){clearTimeout(this._timeout),Se.removeData(this.element,this.constructor.DATA_KEY),Se(this.element).off(this.constructor.EVENT_KEY),Se(this.element).closest(".modal").off("hide.bs.modal"),this.tip&&Se(this.tip).remove(),this._isEnabled=null,this._timeout=null,this._hoverState=null,(this._activeTrigger=null)!==this._popper&&this._popper.destroy(),this._popper=null,this.element=null,this.config=null,this.tip=null},t.show=function(){var e=this;if("none"===Se(this.element).css("display"))throw new Error("Please use show on visible elements");var t=Se.Event(this.constructor.Event.SHOW);if(this.isWithContent()&&this._isEnabled){Se(this.element).trigger(t);var i=Se.contains(this.element.ownerDocument.documentElement,this.element);if(t.isDefaultPrevented()||!i)return;var r=this.getTipElement(),o=Ct.getUID(this.constructor.NAME);r.setAttribute("id",o),this.element.setAttribute("aria-describedby",o),this.setContent(),this.config.animation&&Se(r).addClass(He);var a="function"==typeof this.config.placement?this.config.placement.call(this,r,this.element):this.config.placement,s=this._getAttachment(a);this.addAttachmentClass(s);var l=!1===this.config.container?document.body:Se(this.config.container);Se(r).data(this.constructor.DATA_KEY,this),Se.contains(this.element.ownerDocument.documentElement,this.tip)||Se(r).appendTo(l),Se(this.element).trigger(this.constructor.Event.INSERTED),this._popper=new n(this.element,r,{placement:s,modifiers:{offset:{offset:this.config.offset},flip:{behavior:this.config.fallbackPlacement},arrow:{element:".arrow"},preventOverflow:{boundariesElement:this.config.boundary}},onCreate:function(t){t.originalPlacement!==t.placement&&e._handlePopperPlacementChange(t)},onUpdate:function(t){e._handlePopperPlacementChange(t)}}),Se(r).addClass(We),"ontouchstart"in document.documentElement&&Se(document.body).children().on("mouseover",null,Se.noop);var u=function(){e.config.animation&&e._fixTransition();var t=e._hoverState;e._hoverState=null,Se(e.element).trigger(e.constructor.Event.SHOWN),t===Ue&&e._leave(null,e)};if(Se(this.tip).hasClass(He)){var c=Ct.getTransitionDurationFromElement(this.tip);Se(this.tip).one(Ct.TRANSITION_END,u).emulateTransitionEnd(c)}else u()}},t.hide=function(e){var t=this,n=this.getTipElement(),i=Se.Event(this.constructor.Event.HIDE),r=function(){t._hoverState!==Re&&n.parentNode&&n.parentNode.removeChild(n),t._cleanTipClass(),t.element.removeAttribute("aria-describedby"),Se(t.element).trigger(t.constructor.Event.HIDDEN),null!==t._popper&&t._popper.destroy(),e&&e()};if(Se(this.element).trigger(i),!i.isDefaultPrevented()){if(Se(n).removeClass(We),"ontouchstart"in document.documentElement&&Se(document.body).children().off("mouseover",null,Se.noop),this._activeTrigger.click=!1,this._activeTrigger[Ve]=!1,this._activeTrigger[qe]=!1,Se(this.tip).hasClass(He)){var o=Ct.getTransitionDurationFromElement(n);Se(n).one(Ct.TRANSITION_END,r).emulateTransitionEnd(o)}else r();this._hoverState=""}},t.update=function(){null!==this._popper&&this._popper.scheduleUpdate()},t.isWithContent=function(){return Boolean(this.getTitle())},t.addAttachmentClass=function(e){Se(this.getTipElement()).addClass(Ie+"-"+e)},t.getTipElement=function(){return this.tip=this.tip||Se(this.config.template)[0],this.tip},t.setContent=function(){var e=Se(this.getTipElement());this.setElementContent(e.find(".tooltip-inner"),this.getTitle()),e.removeClass(He+" "+We)},t.setElementContent=function(e,t){var n=this.config.html;"object"==typeof t&&(t.nodeType||t.jquery)?n?Se(t).parent().is(e)||e.empty().append(t):e.text(Se(t).text()):e[n?"html":"text"](t)},t.getTitle=function(){var e=this.element.getAttribute("data-original-title");return e||(e="function"==typeof this.config.title?this.config.title.call(this.element):this.config.title),e},t._getAttachment=function(e){return Fe[e.toUpperCase()]},t._setListeners=function(){var e=this;this.config.trigger.split(" ").forEach((function(t){if("click"===t)Se(e.element).on(e.constructor.Event.CLICK,e.config.selector,(function(t){return e.toggle(t)}));else if("manual"!==t){var n=t===qe?e.constructor.Event.MOUSEENTER:e.constructor.Event.FOCUSIN,i=t===qe?e.constructor.Event.MOUSELEAVE:e.constructor.Event.FOCUSOUT;Se(e.element).on(n,e.config.selector,(function(t){return e._enter(t)})).on(i,e.config.selector,(function(t){return e._leave(t)}))}Se(e.element).closest(".modal").on("hide.bs.modal",(function(){return e.hide()}))})),this.config.selector?this.config=o({},this.config,{trigger:"manual",selector:""}):this._fixTitle()},t._fixTitle=function(){var e=typeof this.element.getAttribute("data-original-title");(this.element.getAttribute("title")||"string"!==e)&&(this.element.setAttribute("data-original-title",this.element.getAttribute("title")||""),this.element.setAttribute("title",""))},t._enter=function(e,t){var n=this.constructor.DATA_KEY;(t=t||Se(e.currentTarget).data(n))||(t=new this.constructor(e.currentTarget,this._getDelegateConfig()),Se(e.currentTarget).data(n,t)),e&&(t._activeTrigger["focusin"===e.type?Ve:qe]=!0),Se(t.getTipElement()).hasClass(We)||t._hoverState===Re?t._hoverState=Re:(clearTimeout(t._timeout),t._hoverState=Re,t.config.delay&&t.config.delay.show?t._timeout=setTimeout((function(){t._hoverState===Re&&t.show()}),t.config.delay.show):t.show())},t._leave=function(e,t){var n=this.constructor.DATA_KEY;(t=t||Se(e.currentTarget).data(n))||(t=new this.constructor(e.currentTarget,this._getDelegateConfig()),Se(e.currentTarget).data(n,t)),e&&(t._activeTrigger["focusout"===e.type?Ve:qe]=!1),t._isWithActiveTrigger()||(clearTimeout(t._timeout),t._hoverState=Ue,t.config.delay&&t.config.delay.hide?t._timeout=setTimeout((function(){t._hoverState===Ue&&t.hide()}),t.config.delay.hide):t.hide())},t._isWithActiveTrigger=function(){for(var e in this._activeTrigger)if(this._activeTrigger[e])return!0;return!1},t._getConfig=function(e){return"number"==typeof(e=o({},this.constructor.Default,Se(this.element).data(),"object"==typeof e&&e?e:{})).delay&&(e.delay={show:e.delay,hide:e.delay}),"number"==typeof e.title&&(e.title=e.title.toString()),"number"==typeof e.content&&(e.content=e.content.toString()),Ct.typeCheckConfig(Ae,e,this.constructor.DefaultType),e},t._getDelegateConfig=function(){var e={};if(this.config)for(var t in this.config)this.constructor.Default[t]!==this.config[t]&&(e[t]=this.config[t]);return e},t._cleanTipClass=function(){var e=Se(this.getTipElement()),t=e.attr("class").match(Le);null!==t&&0<t.length&&e.removeClass(t.join(""))},t._handlePopperPlacementChange=function(e){this._cleanTipClass(),this.addAttachmentClass(this._getAttachment(e.placement))},t._fixTransition=function(){var e=this.getTipElement(),t=this.config.animation;null===e.getAttribute("x-placement")&&(Se(e).removeClass(He),this.config.animation=!1,this.hide(),this.show(),this.config.animation=t)},e._jQueryInterface=function(t){return this.each((function(){var n=Se(this).data(Oe),i="object"==typeof t&&t;if((n||!/dispose|hide/.test(t))&&(n||(n=new e(this,i),Se(this).data(Oe,n)),"string"==typeof t)){if(void 0===n[t])throw new TypeError('No method named "'+t+'"');n[t]()}}))},r(e,null,[{key:"VERSION",get:function(){return"4.1.1"}},{key:"Default",get:function(){return Pe}},{key:"NAME",get:function(){return Ae}},{key:"DATA_KEY",get:function(){return Oe}},{key:"Event",get:function(){return Ye}},{key:"EVENT_KEY",get:function(){return Me}},{key:"DefaultType",get:function(){return je}}]),e}(),Se.fn[Ae]=Be._jQueryInterface,Se.fn[Ae].Constructor=Be,Se.fn[Ae].noConflict=function(){return Se.fn[Ae]=Ne,Be._jQueryInterface},Be),It=($e="popover",Ke="."+(Ge="bs.popover"),Xe=(ze=t).fn[$e],Qe="bs-popover",Ze=new RegExp("(^|\\s)"+Qe+"\\S+","g"),Je=o({},Nt.Default,{placement:"right",trigger:"click",content:"",template:'<div class="popover" role="tooltip"><div class="arrow"></div><h3 class="popover-header"></h3><div class="popover-body"></div></div>'}),et=o({},Nt.DefaultType,{content:"(string|element|function)"}),"fade",".popover-header",".popover-body",tt={HIDE:"hide"+Ke,HIDDEN:"hidden"+Ke,SHOW:"show"+Ke,SHOWN:"shown"+Ke,INSERTED:"inserted"+Ke,CLICK:"click"+Ke,FOCUSIN:"focusin"+Ke,FOCUSOUT:"focusout"+Ke,MOUSEENTER:"mouseenter"+Ke,MOUSELEAVE:"mouseleave"+Ke},nt=function(e){var t,n;function i(){return e.apply(this,arguments)||this}n=e,(t=i).prototype=Object.create(n.prototype),(t.prototype.constructor=t).__proto__=n;var o=i.prototype;return o.isWithContent=function(){return this.getTitle()||this._getContent()},o.addAttachmentClass=function(e){ze(this.getTipElement()).addClass(Qe+"-"+e)},o.getTipElement=function(){return this.tip=this.tip||ze(this.config.template)[0],this.tip},o.setContent=function(){var e=ze(this.getTipElement());this.setElementContent(e.find(".popover-header"),this.getTitle());var t=this._getContent();"function"==typeof t&&(t=t.call(this.element)),this.setElementContent(e.find(".popover-body"),t),e.removeClass("fade show")},o._getContent=function(){return this.element.getAttribute("data-content")||this.config.content},o._cleanTipClass=function(){var e=ze(this.getTipElement()),t=e.attr("class").match(Ze);null!==t&&0<t.length&&e.removeClass(t.join(""))},i._jQueryInterface=function(e){return this.each((function(){var t=ze(this).data(Ge),n="object"==typeof e?e:null;if((t||!/destroy|hide/.test(e))&&(t||(t=new i(this,n),ze(this).data(Ge,t)),"string"==typeof e)){if(void 0===t[e])throw new TypeError('No method named "'+e+'"');t[e]()}}))},r(i,null,[{key:"VERSION",get:function(){return"4.1.1"}},{key:"Default",get:function(){return Je}},{key:"NAME",get:function(){return $e}},{key:"DATA_KEY",get:function(){return Ge}},{key:"Event",get:function(){return tt}},{key:"EVENT_KEY",get:function(){return Ke}},{key:"DefaultType",get:function(){return et}}]),i}(Nt),ze.fn[$e]=nt._jQueryInterface,ze.fn[$e].Constructor=nt,ze.fn[$e].noConflict=function(){return ze.fn[$e]=Xe,nt._jQueryInterface},nt),Lt=(rt="scrollspy",at="."+(ot="bs.scrollspy"),st=(it=t).fn[rt],lt={offset:10,method:"auto",target:""},ut={offset:"number",method:"string",target:"(string|element)"},ct={ACTIVATE:"activate"+at,SCROLL:"scroll"+at,LOAD_DATA_API:"load"+at+".data-api"},"dropdown-item",dt="active",ft={DATA_SPY:'[data-spy="scroll"]',ACTIVE:".active",NAV_LIST_GROUP:".nav, .list-group",NAV_LINKS:".nav-link",NAV_ITEMS:".nav-item",LIST_ITEMS:".list-group-item",DROPDOWN:".dropdown",DROPDOWN_ITEMS:".dropdown-item",DROPDOWN_TOGGLE:".dropdown-toggle"},"offset",ht="position",pt=function(){function e(e,t){var n=this;this._element=e,this._scrollElement="BODY"===e.tagName?window:e,this._config=this._getConfig(t),this._selector=this._config.target+" "+ft.NAV_LINKS+","+this._config.target+" "+ft.LIST_ITEMS+","+this._config.target+" "+ft.DROPDOWN_ITEMS,this._offsets=[],this._targets=[],this._activeTarget=null,this._scrollHeight=0,it(this._scrollElement).on(ct.SCROLL,(function(e){return n._process(e)})),this.refresh(),this._process()}var t=e.prototype;return t.refresh=function(){var e=this,t=this._scrollElement===this._scrollElement.window?"offset":ht,n="auto"===this._config.method?t:this._config.method,i=n===ht?this._getScrollTop():0;this._offsets=[],this._targets=[],this._scrollHeight=this._getScrollHeight(),it.makeArray(it(this._selector)).map((function(e){var t,r=Ct.getSelectorFromElement(e);if(r&&(t=it(r)[0]),t){var o=t.getBoundingClientRect();if(o.width||o.height)return[it(t)[n]().top+i,r]}return null})).filter((function(e){return e})).sort((function(e,t){return e[0]-t[0]})).forEach((function(t){e._offsets.push(t[0]),e._targets.push(t[1])}))},t.dispose=function(){it.removeData(this._element,ot),it(this._scrollElement).off(at),this._element=null,this._scrollElement=null,this._config=null,this._selector=null,this._offsets=null,this._targets=null,this._activeTarget=null,this._scrollHeight=null},t._getConfig=function(e){if("string"!=typeof(e=o({},lt,"object"==typeof e&&e?e:{})).target){var t=it(e.target).attr("id");t||(t=Ct.getUID(rt),it(e.target).attr("id",t)),e.target="#"+t}return Ct.typeCheckConfig(rt,e,ut),e},t._getScrollTop=function(){return this._scrollElement===window?this._scrollElement.pageYOffset:this._scrollElement.scrollTop},t._getScrollHeight=function(){return this._scrollElement.scrollHeight||Math.max(document.body.scrollHeight,document.documentElement.scrollHeight)},t._getOffsetHeight=function(){return this._scrollElement===window?window.innerHeight:this._scrollElement.getBoundingClientRect().height},t._process=function(){var e=this._getScrollTop()+this._config.offset,t=this._getScrollHeight(),n=this._config.offset+t-this._getOffsetHeight();if(this._scrollHeight!==t&&this.refresh(),n<=e){var i=this._targets[this._targets.length-1];this._activeTarget!==i&&this._activate(i)}else{if(this._activeTarget&&e<this._offsets[0]&&0<this._offsets[0])return this._activeTarget=null,void this._clear();for(var r=this._offsets.length;r--;)this._activeTarget!==this._targets[r]&&e>=this._offsets[r]&&(void 0===this._offsets[r+1]||e<this._offsets[r+1])&&this._activate(this._targets[r])}},t._activate=function(e){this._activeTarget=e,this._clear();var t=this._selector.split(",");t=t.map((function(t){return t+'[data-target="'+e+'"],'+t+'[href="'+e+'"]'}));var n=it(t.join(","));n.hasClass("dropdown-item")?(n.closest(ft.DROPDOWN).find(ft.DROPDOWN_TOGGLE).addClass(dt),n.addClass(dt)):(n.addClass(dt),n.parents(ft.NAV_LIST_GROUP).prev(ft.NAV_LINKS+", "+ft.LIST_ITEMS).addClass(dt),n.parents(ft.NAV_LIST_GROUP).prev(ft.NAV_ITEMS).children(ft.NAV_LINKS).addClass(dt)),it(this._scrollElement).trigger(ct.ACTIVATE,{relatedTarget:e})},t._clear=function(){it(this._selector).filter(ft.ACTIVE).removeClass(dt)},e._jQueryInterface=function(t){return this.each((function(){var n=it(this).data(ot);if(n||(n=new e(this,"object"==typeof t&&t),it(this).data(ot,n)),"string"==typeof t){if(void 0===n[t])throw new TypeError('No method named "'+t+'"');n[t]()}}))},r(e,null,[{key:"VERSION",get:function(){return"4.1.1"}},{key:"Default",get:function(){return lt}}]),e}(),it(window).on(ct.LOAD_DATA_API,(function(){for(var e=it.makeArray(it(ft.DATA_SPY)),t=e.length;t--;){var n=it(e[t]);pt._jQueryInterface.call(n,n.data())}})),it.fn[rt]=pt._jQueryInterface,it.fn[rt].Constructor=pt,it.fn[rt].noConflict=function(){return it.fn[rt]=st,pt._jQueryInterface},pt),jt=(vt="."+(gt="bs.tab"),yt=(mt=t).fn.tab,bt={HIDE:"hide"+vt,HIDDEN:"hidden"+vt,SHOW:"show"+vt,SHOWN:"shown"+vt,CLICK_DATA_API:"click"+vt+".data-api"},"dropdown-menu",_t="active","disabled","fade",wt="show",".dropdown",".nav, .list-group",xt=".active",Dt="> li > .active",'[data-toggle="tab"], [data-toggle="pill"], [data-toggle="list"]',".dropdown-toggle","> .dropdown-menu .active",Tt=function(){function e(e){this._element=e}var t=e.prototype;return t.show=function(){var e=this;if(!(this._element.parentNode&&this._element.parentNode.nodeType===Node.ELEMENT_NODE&&mt(this._element).hasClass(_t)||mt(this._element).hasClass("disabled"))){var t,n,i=mt(this._element).closest(".nav, .list-group")[0],r=Ct.getSelectorFromElement(this._element);if(i){var o="UL"===i.nodeName?Dt:xt;n=(n=mt.makeArray(mt(i).find(o)))[n.length-1]}var a=mt.Event(bt.HIDE,{relatedTarget:this._element}),s=mt.Event(bt.SHOW,{relatedTarget:n});if(n&&mt(n).trigger(a),mt(this._element).trigger(s),!s.isDefaultPrevented()&&!a.isDefaultPrevented()){r&&(t=mt(r)[0]),this._activate(this._element,i);var l=function(){var t=mt.Event(bt.HIDDEN,{relatedTarget:e._element}),i=mt.Event(bt.SHOWN,{relatedTarget:n});mt(n).trigger(t),mt(e._element).trigger(i)};t?this._activate(t,t.parentNode,l):l()}}},t.dispose=function(){mt.removeData(this._element,gt),this._element=null},t._activate=function(e,t,n){var i=this,r=("UL"===t.nodeName?mt(t).find(Dt):mt(t).children(xt))[0],o=n&&r&&mt(r).hasClass("fade"),a=function(){return i._transitionComplete(e,r,n)};if(r&&o){var s=Ct.getTransitionDurationFromElement(r);mt(r).one(Ct.TRANSITION_END,a).emulateTransitionEnd(s)}else a()},t._transitionComplete=function(e,t,n){if(t){mt(t).removeClass(wt+" "+_t);var i=mt(t.parentNode).find("> .dropdown-menu .active")[0];i&&mt(i).removeClass(_t),"tab"===t.getAttribute("role")&&t.setAttribute("aria-selected",!1)}if(mt(e).addClass(_t),"tab"===e.getAttribute("role")&&e.setAttribute("aria-selected",!0),Ct.reflow(e),mt(e).addClass(wt),e.parentNode&&mt(e.parentNode).hasClass("dropdown-menu")){var r=mt(e).closest(".dropdown")[0];r&&mt(r).find(".dropdown-toggle").addClass(_t),e.setAttribute("aria-expanded",!0)}n&&n()},e._jQueryInterface=function(t){return this.each((function(){var n=mt(this),i=n.data(gt);if(i||(i=new e(this),n.data(gt,i)),"string"==typeof t){if(void 0===i[t])throw new TypeError('No method named "'+t+'"');i[t]()}}))},r(e,null,[{key:"VERSION",get:function(){return"4.1.1"}}]),e}(),mt(document).on(bt.CLICK_DATA_API,'[data-toggle="tab"], [data-toggle="pill"], [data-toggle="list"]',(function(e){e.preventDefault(),Tt._jQueryInterface.call(mt(this),"show")})),mt.fn.tab=Tt._jQueryInterface,mt.fn.tab.Constructor=Tt,mt.fn.tab.noConflict=function(){return mt.fn.tab=yt,Tt._jQueryInterface},Tt);!function(e){if(void 0===e)throw new TypeError("Bootstrap's JavaScript requires jQuery. jQuery must be included before Bootstrap's JavaScript.");var t=e.fn.jquery.split(" ")[0].split(".");if(t[0]<2&&t[1]<9||1===t[0]&&9===t[1]&&t[2]<1||4<=t[0])throw new Error("Bootstrap's JavaScript requires at least jQuery v1.9.1 but less than v4.0.0")}(t),e.Util=Ct,e.Alert=kt,e.Button=Et,e.Carousel=St,e.Collapse=At,e.Dropdown=Ot,e.Modal=Mt,e.Popover=It,e.Scrollspy=Lt,e.Tab=jt,e.Tooltip=Nt,Object.defineProperty(e,"__esModule",{value:!0})})),function(e,t){"object"==typeof exports&&"undefined"!=typeof module?module.exports=t():"function"==typeof define&&define.amd?define(t):e.moment=t()}(this,(function(){"use strict";function e(){return je.apply(null,arguments)}function t(e){return"[object Array]"===Object.prototype.toString.call(e)}function n(e){return e instanceof Date||"[object Date]"===Object.prototype.toString.call(e)}function i(e,t){return Object.prototype.hasOwnProperty.call(e,t)}function r(e,t){for(var n in t)i(t,n)&&(e[n]=t[n]);return i(t,"toString")&&(e.toString=t.toString),i(t,"valueOf")&&(e.valueOf=t.valueOf),e}function o(e,t,n,i){return te(e,t,n,i,!0).utc()}function a(e){return null==e._pf&&(e._pf={empty:!1,unusedTokens:[],unusedInput:[],overflow:-2,charsLeftOver:0,nullInput:!1,invalidMonth:null,invalidFormat:!1,userInvalidated:!1,iso:!1}),e._pf}function s(e){if(null==e._isValid){var t=a(e);e._isValid=!(isNaN(e._d.getTime())||!(t.overflow<0)||t.empty||t.invalidMonth||t.invalidWeekday||t.nullInput||t.invalidFormat||t.userInvalidated),e._strict&&(e._isValid=e._isValid&&0===t.charsLeftOver&&0===t.unusedTokens.length&&void 0===t.bigHour)}return e._isValid}function l(e){var t=o(NaN);return null!=e?r(a(t),e):a(t).userInvalidated=!0,t}function u(e,t){var n,i,r;if(void 0!==t._isAMomentObject&&(e._isAMomentObject=t._isAMomentObject),void 0!==t._i&&(e._i=t._i),void 0!==t._f&&(e._f=t._f),void 0!==t._l&&(e._l=t._l),void 0!==t._strict&&(e._strict=t._strict),void 0!==t._tzm&&(e._tzm=t._tzm),void 0!==t._isUTC&&(e._isUTC=t._isUTC),void 0!==t._offset&&(e._offset=t._offset),void 0!==t._pf&&(e._pf=a(t)),void 0!==t._locale&&(e._locale=t._locale),Pe.length>0)for(n in Pe)void 0!==(r=t[i=Pe[n]])&&(e[i]=r);return e}function c(t){u(this,t),this._d=new Date(null!=t._d?t._d.getTime():NaN),!1===Re&&(Re=!0,e.updateOffset(this),Re=!1)}function d(e){return e instanceof c||null!=e&&null!=e._isAMomentObject}function f(e){return 0>e?Math.ceil(e):Math.floor(e)}function h(e){var t=+e,n=0;return 0!==t&&isFinite(t)&&(n=f(t)),n}function p(e,t,n){var i,r=Math.min(e.length,t.length),o=Math.abs(e.length-t.length),a=0;for(i=0;r>i;i++)(n&&e[i]!==t[i]||!n&&h(e[i])!==h(t[i]))&&a++;return a+o}function m(){}function g(e){return e?e.toLowerCase().replace("_","-"):e}function v(e){var t=null;if(!Ue[e]&&"undefined"!=typeof module&&module&&module.exports)try{t=Fe._abbr,require("./locale/"+e),y(t)}catch(e){}return Ue[e]}function y(e,t){var n;return e&&((n=void 0===t?_(e):b(e,t))&&(Fe=n)),Fe._abbr}function b(e,t){return null!==t?(t.abbr=e,Ue[e]=Ue[e]||new m,Ue[e].set(t),y(e),Ue[e]):(delete Ue[e],null)}function _(e){var n;if(e&&e._locale&&e._locale._abbr&&(e=e._locale._abbr),!e)return Fe;if(!t(e)){if(n=v(e))return n;e=[e]}return function(e){for(var t,n,i,r,o=0;o<e.length;){for(t=(r=g(e[o]).split("-")).length,n=(n=g(e[o+1]))?n.split("-"):null;t>0;){if(i=v(r.slice(0,t).join("-")))return i;if(n&&n.length>=t&&p(r,n,!0)>=t-1)break;t--}o++}return null}(e)}function w(e,t){var n=e.toLowerCase();Ye[n]=Ye[n+"s"]=Ye[t]=e}function x(e){return"string"==typeof e?Ye[e]||Ye[e.toLowerCase()]:void 0}function D(e){var t,n,r={};for(n in e)i(e,n)&&((t=x(n))&&(r[t]=e[n]));return r}function T(t,n){return function(i){return null!=i?(k(this,t,i),e.updateOffset(this,n),this):C(this,t)}}function C(e,t){return e._d["get"+(e._isUTC?"UTC":"")+t]()}function k(e,t,n){return e._d["set"+(e._isUTC?"UTC":"")+t](n)}function E(e,t){var n;if("object"==typeof e)for(n in e)this.set(n,e[n]);else if("function"==typeof this[e=x(e)])return this[e](t);return this}function S(e,t,n){var i=""+Math.abs(e),r=t-i.length;return(e>=0?n?"+":"":"-")+Math.pow(10,Math.max(0,r)).toString().substr(1)+i}function A(e,t,n,i){var r=i;"string"==typeof i&&(r=function(){return this[i]()}),e&&(Ve[e]=r),t&&(Ve[t[0]]=function(){return S(r.apply(this,arguments),t[1],t[2])}),n&&(Ve[n]=function(){return this.localeData().ordinal(r.apply(this,arguments),e)})}function O(e){return e.match(/\[[\s\S]/)?e.replace(/^\[|\]$/g,""):e.replace(/\\/g,"")}function M(e,t){return e.isValid()?(t=N(t,e.localeData()),qe[t]=qe[t]||function(e){var t,n,i=e.match(He);for(t=0,n=i.length;n>t;t++)Ve[i[t]]?i[t]=Ve[i[t]]:i[t]=O(i[t]);return function(r){var o="";for(t=0;n>t;t++)o+=i[t]instanceof Function?i[t].call(r,e):i[t];return o}}(t),qe[t](e)):e.localeData().invalidDate()}function N(e,t){function n(e){return t.longDateFormat(e)||e}var i=5;for(We.lastIndex=0;i>=0&&We.test(e);)e=e.replace(We,n),We.lastIndex=0,i-=1;return e}function I(e,t,n){rt[e]=function(e){return"function"==typeof e&&"[object Function]"===Object.prototype.toString.call(e)}(t)?t:function(e){return e&&n?n:t}}function L(e,t){return i(rt,e)?rt[e](t._strict,t._locale):new RegExp(function(e){return e.replace("\\","").replace(/\\(\[)|\\(\])|\[([^\]\[]*)\]|\\(.)/g,(function(e,t,n,i,r){return t||n||i||r})).replace(/[-\/\\^$*+?.()|[\]{}]/g,"\\$&")}(e))}function j(e,t){var n,i=t;for("string"==typeof e&&(e=[e]),"number"==typeof t&&(i=function(e,n){n[t]=h(e)}),n=0;n<e.length;n++)ot[e[n]]=i}function F(e,t){j(e,(function(e,n,i,r){i._w=i._w||{},t(e,i._w,i,r)}))}function P(e,t,n){null!=t&&i(ot,e)&&ot[e](t,n._a,n,e)}function R(e,t){return new Date(Date.UTC(e,t+1,0)).getUTCDate()}function U(e,t){var n;return"string"==typeof t&&"number"!=typeof(t=e.localeData().monthsParse(t))||(n=Math.min(e.date(),R(e.year(),t)),e._d["set"+(e._isUTC?"UTC":"")+"Month"](t,n)),e}function Y(t){return null!=t?(U(this,t),e.updateOffset(this,!0),this):C(this,"Month")}function H(e){var t,n=e._a;return n&&-2===a(e).overflow&&(t=n[st]<0||n[st]>11?st:n[lt]<1||n[lt]>R(n[at],n[st])?lt:n[ut]<0||n[ut]>24||24===n[ut]&&(0!==n[ct]||0!==n[dt]||0!==n[ft])?ut:n[ct]<0||n[ct]>59?ct:n[dt]<0||n[dt]>59?dt:n[ft]<0||n[ft]>999?ft:-1,a(e)._overflowDayOfYear&&(at>t||t>lt)&&(t=lt),a(e).overflow=t),e}function W(t){!1===e.suppressDeprecationWarnings&&"undefined"!=typeof console&&console.warn&&console.warn("Deprecation warning: "+t)}function q(e,t){var n=!0;return r((function(){return n&&(W(e+"\n"+(new Error).stack),n=!1),t.apply(this,arguments)}),t)}function V(e){var t,n,i=e._i,r=gt.exec(i);if(r){for(a(e).iso=!0,t=0,n=vt.length;n>t;t++)if(vt[t][1].exec(i)){e._f=vt[t][0];break}for(t=0,n=yt.length;n>t;t++)if(yt[t][1].exec(i)){e._f+=(r[6]||" ")+yt[t][0];break}i.match(nt)&&(e._f+="Z"),Z(e)}else e._isValid=!1}function B(e,t,n,i,r,o,a){var s=new Date(e,t,n,i,r,o,a);return 1970>e&&s.setFullYear(e),s}function z(e){var t=new Date(Date.UTC.apply(null,arguments));return 1970>e&&t.setUTCFullYear(e),t}function $(e){return G(e)?366:365}function G(e){return e%4==0&&e%100!=0||e%400==0}function K(e,t,n){var i,r=n-t,o=n-e.day();return o>r&&(o-=7),r-7>o&&(o+=7),i=ne(e).add(o,"d"),{week:Math.ceil(i.dayOfYear()/7),year:i.year()}}function X(e,t,n){return null!=e?e:null!=t?t:n}function Q(e){var t,n,i,r,o=[];if(!e._d){for(i=function(e){var t=new Date;return e._useUTC?[t.getUTCFullYear(),t.getUTCMonth(),t.getUTCDate()]:[t.getFullYear(),t.getMonth(),t.getDate()]}(e),e._w&&null==e._a[lt]&&null==e._a[st]&&function(e){var t,n,i,r,o,a,s;null!=(t=e._w).GG||null!=t.W||null!=t.E?(o=1,a=4,n=X(t.GG,e._a[at],K(ne(),1,4).year),i=X(t.W,1),r=X(t.E,1)):(o=e._locale._week.dow,a=e._locale._week.doy,n=X(t.gg,e._a[at],K(ne(),o,a).year),i=X(t.w,1),null!=t.d?(r=t.d,o>r&&++i):r=null!=t.e?t.e+o:o),s=function(e,t,n,i,r){var o,a=6+r-i,s=z(e,0,1+a).getUTCDay();return r>s&&(s+=7),{year:(o=1+a+7*(t-1)-s+(n=null!=n?1*n:r))>0?e:e-1,dayOfYear:o>0?o:$(e-1)+o}}(n,i,r,a,o),e._a[at]=s.year,e._dayOfYear=s.dayOfYear}(e),e._dayOfYear&&(r=X(e._a[at],i[at]),e._dayOfYear>$(r)&&(a(e)._overflowDayOfYear=!0),n=z(r,0,e._dayOfYear),e._a[st]=n.getUTCMonth(),e._a[lt]=n.getUTCDate()),t=0;3>t&&null==e._a[t];++t)e._a[t]=o[t]=i[t];for(;7>t;t++)e._a[t]=o[t]=null==e._a[t]?2===t?1:0:e._a[t];24===e._a[ut]&&0===e._a[ct]&&0===e._a[dt]&&0===e._a[ft]&&(e._nextDay=!0,e._a[ut]=0),e._d=(e._useUTC?z:B).apply(null,o),null!=e._tzm&&e._d.setUTCMinutes(e._d.getUTCMinutes()-e._tzm),e._nextDay&&(e._a[ut]=24)}}function Z(t){if(t._f!==e.ISO_8601){t._a=[],a(t).empty=!0;var n,i,r,o,s,l=""+t._i,u=l.length,c=0;for(r=N(t._f,t._locale).match(He)||[],n=0;n<r.length;n++)o=r[n],(i=(l.match(L(o,t))||[])[0])&&((s=l.substr(0,l.indexOf(i))).length>0&&a(t).unusedInput.push(s),l=l.slice(l.indexOf(i)+i.length),c+=i.length),Ve[o]?(i?a(t).empty=!1:a(t).unusedTokens.push(o),P(o,i,t)):t._strict&&!i&&a(t).unusedTokens.push(o);a(t).charsLeftOver=u-c,l.length>0&&a(t).unusedInput.push(l),!0===a(t).bigHour&&t._a[ut]<=12&&t._a[ut]>0&&(a(t).bigHour=void 0),t._a[ut]=function(e,t,n){var i;return null==n?t:null!=e.meridiemHour?e.meridiemHour(t,n):null!=e.isPM?((i=e.isPM(n))&&12>t&&(t+=12),i||12!==t||(t=0),t):t}(t._locale,t._a[ut],t._meridiem),Q(t),H(t)}else V(t)}function J(e){var i=e._i,o=e._f;return e._locale=e._locale||_(e._l),null===i||void 0===o&&""===i?l({nullInput:!0}):("string"==typeof i&&(e._i=i=e._locale.preparse(i)),d(i)?new c(H(i)):(t(o)?function(e){var t,n,i,o,l;if(0===e._f.length)return a(e).invalidFormat=!0,void(e._d=new Date(NaN));for(o=0;o<e._f.length;o++)l=0,t=u({},e),null!=e._useUTC&&(t._useUTC=e._useUTC),t._f=e._f[o],Z(t),s(t)&&(l+=a(t).charsLeftOver,l+=10*a(t).unusedTokens.length,a(t).score=l,(null==i||i>l)&&(i=l,n=t));r(e,n||t)}(e):o?Z(e):n(i)?e._d=i:ee(e),e))}function ee(i){var r=i._i;void 0===r?i._d=new Date:n(r)?i._d=new Date(+r):"string"==typeof r?function(t){var n=bt.exec(t._i);null!==n?t._d=new Date(+n[1]):(V(t),!1===t._isValid&&(delete t._isValid,e.createFromInputFallback(t)))}(i):t(r)?(i._a=function(e,t){var n,i=[];for(n=0;n<e.length;++n)i.push(t(e[n],n));return i}(r.slice(0),(function(e){return parseInt(e,10)})),Q(i)):"object"==typeof r?function(e){if(!e._d){var t=D(e._i);e._a=[t.year,t.month,t.day||t.date,t.hour,t.minute,t.second,t.millisecond],Q(e)}}(i):"number"==typeof r?i._d=new Date(r):e.createFromInputFallback(i)}function te(e,t,n,i,r){var o={};return"boolean"==typeof n&&(i=n,n=void 0),o._isAMomentObject=!0,o._useUTC=o._isUTC=r,o._l=n,o._i=e,o._f=t,o._strict=i,function(e){var t=new c(H(J(e)));return t._nextDay&&(t.add(1,"d"),t._nextDay=void 0),t}(o)}function ne(e,t,n,i){return te(e,t,n,i,!1)}function ie(e,n){var i,r;if(1===n.length&&t(n[0])&&(n=n[0]),!n.length)return ne();for(i=n[0],r=1;r<n.length;++r)(!n[r].isValid()||n[r][e](i))&&(i=n[r]);return i}function re(e){var t=D(e),n=t.year||0,i=t.quarter||0,r=t.month||0,o=t.week||0,a=t.day||0,s=t.hour||0,l=t.minute||0,u=t.second||0,c=t.millisecond||0;this._milliseconds=+c+1e3*u+6e4*l+36e5*s,this._days=+a+7*o,this._months=+r+3*i+12*n,this._data={},this._locale=_(),this._bubble()}function oe(e){return e instanceof re}function ae(e,t){A(e,0,0,(function(){var e=this.utcOffset(),n="+";return 0>e&&(e=-e,n="-"),n+S(~~(e/60),2)+t+S(~~e%60,2)}))}function se(e){var t=(e||"").match(nt)||[],n=((t[t.length-1]||[])+"").match(Dt)||["-",0,0],i=60*n[1]+h(n[2]);return"+"===n[0]?i:-i}function le(t,i){var r,o;return i._isUTC?(r=i.clone(),o=(d(t)||n(t)?+t:+ne(t))-+r,r._d.setTime(+r._d+o),e.updateOffset(r,!1),r):ne(t).local()}function ue(e){return 15*-Math.round(e._d.getTimezoneOffset()/15)}function ce(){return this._isUTC&&0===this._offset}function de(e,t){var n,r,o,a=e,s=null;return oe(e)?a={ms:e._milliseconds,d:e._days,M:e._months}:"number"==typeof e?(a={},t?a[t]=e:a.milliseconds=e):(s=Tt.exec(e))?(n="-"===s[1]?-1:1,a={y:0,d:h(s[lt])*n,h:h(s[ut])*n,m:h(s[ct])*n,s:h(s[dt])*n,ms:h(s[ft])*n}):(s=Ct.exec(e))?(n="-"===s[1]?-1:1,a={y:fe(s[2],n),M:fe(s[3],n),d:fe(s[4],n),h:fe(s[5],n),m:fe(s[6],n),s:fe(s[7],n),w:fe(s[8],n)}):null==a?a={}:"object"==typeof a&&("from"in a||"to"in a)&&(o=function(e,t){var n;return t=le(t,e),e.isBefore(t)?n=he(e,t):((n=he(t,e)).milliseconds=-n.milliseconds,n.months=-n.months),n}(ne(a.from),ne(a.to)),(a={}).ms=o.milliseconds,a.M=o.months),r=new re(a),oe(e)&&i(e,"_locale")&&(r._locale=e._locale),r}function fe(e,t){var n=e&&parseFloat(e.replace(",","."));return(isNaN(n)?0:n)*t}function he(e,t){var n={milliseconds:0,months:0};return n.months=t.month()-e.month()+12*(t.year()-e.year()),e.clone().add(n.months,"M").isAfter(t)&&--n.months,n.milliseconds=+t-+e.clone().add(n.months,"M"),n}function pe(e,t){return function(n,i){var r;return null===i||isNaN(+i)||(function(e,t){mt[e]||(W(t),mt[e]=!0)}(t,"moment()."+t+"(period, number) is deprecated. Please use moment()."+t+"(number, period)."),r=n,n=i,i=r),me(this,de(n="string"==typeof n?+n:n,i),e),this}}function me(t,n,i,r){var o=n._milliseconds,a=n._days,s=n._months;r=null==r||r,o&&t._d.setTime(+t._d+o*i),a&&k(t,"Date",C(t,"Date")+a*i),s&&U(t,C(t,"Month")+s*i),r&&e.updateOffset(t,a||s)}function ge(){var e=this.clone().utc();return 0<e.year()&&e.year()<=9999?"function"==typeof Date.prototype.toISOString?this.toDate().toISOString():M(e,"YYYY-MM-DD[T]HH:mm:ss.SSS[Z]"):M(e,"YYYYYY-MM-DD[T]HH:mm:ss.SSS[Z]")}function ve(e){var t;return void 0===e?this._locale._abbr:(null!=(t=_(e))&&(this._locale=t),this)}function ye(){return this._locale}function be(e,t){A(0,[e,e.length],0,t)}function _e(e,t,n){return K(ne([e,11,31+t-n]),t,n).week}function we(e,t){A(e,0,0,(function(){return this.localeData().meridiem(this.hours(),this.minutes(),t)}))}function xe(e,t){return t._meridiemParse}function De(e,t){t[ft]=h(1e3*("0."+e))}function Te(e){return e}function Ce(e,t,n,i){var r=_(),a=o().set(i,t);return r[n](a,e)}function ke(e,t,n,i,r){if("number"==typeof e&&(t=e,e=void 0),e=e||"",null!=t)return Ce(e,t,n,r);var o,a=[];for(o=0;i>o;o++)a[o]=Ce(e,o,n,r);return a}function Ee(e,t,n,i){var r=de(t,n);return e._milliseconds+=i*r._milliseconds,e._days+=i*r._days,e._months+=i*r._months,e._bubble()}function Se(e){return 0>e?Math.floor(e):Math.ceil(e)}function Ae(e){return 4800*e/146097}function Oe(e){return 146097*e/4800}function Me(e){return function(){return this.as(e)}}function Ne(e){return function(){return this._data[e]}}function Ie(e,t,n,i,r){return r.relativeTime(t||1,!!n,e,i)}function Le(){var e,t,n=an(this._milliseconds)/1e3,i=an(this._days),r=an(this._months);e=f(n/60),t=f(e/60),n%=60,e%=60;var o=f(r/12),a=r%=12,s=i,l=t,u=e,c=n,d=this.asSeconds();return d?(0>d?"-":"")+"P"+(o?o+"Y":"")+(a?a+"M":"")+(s?s+"D":"")+(l||u||c?"T":"")+(l?l+"H":"")+(u?u+"M":"")+(c?c+"S":""):"P0D"}var je,Fe,Pe=e.momentProperties=[],Re=!1,Ue={},Ye={},He=/(\[[^\[]*\])|(\\)?(Mo|MM?M?M?|Do|DDDo|DD?D?D?|ddd?d?|do?|w[o|w]?|W[o|W]?|Q|YYYYYY|YYYYY|YYYY|YY|gg(ggg?)?|GG(GGG?)?|e|E|a|A|hh?|HH?|mm?|ss?|S{1,9}|x|X|zz?|ZZ?|.)/g,We=/(\[[^\[]*\])|(\\)?(LTS|LT|LL?L?L?|l{1,4})/g,qe={},Ve={},Be=/\d/,ze=/\d\d/,$e=/\d{3}/,Ge=/\d{4}/,Ke=/[+-]?\d{6}/,Xe=/\d\d?/,Qe=/\d{1,3}/,Ze=/\d{1,4}/,Je=/[+-]?\d{1,6}/,et=/\d+/,tt=/[+-]?\d+/,nt=/Z|[+-]\d\d:?\d\d/gi,it=/[0-9]*['a-z\u00A0-\u05FF\u0700-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+|[\u0600-\u06FF\/]+(\s*?[\u0600-\u06FF]+){1,2}/i,rt={},ot={},at=0,st=1,lt=2,ut=3,ct=4,dt=5,ft=6;A("M",["MM",2],"Mo",(function(){return this.month()+1})),A("MMM",0,0,(function(e){return this.localeData().monthsShort(this,e)})),A("MMMM",0,0,(function(e){return this.localeData().months(this,e)})),w("month","M"),I("M",Xe),I("MM",Xe,ze),I("MMM",it),I("MMMM",it),j(["M","MM"],(function(e,t){t[st]=h(e)-1})),j(["MMM","MMMM"],(function(e,t,n,i){var r=n._locale.monthsParse(e,i,n._strict);null!=r?t[st]=r:a(n).invalidMonth=e}));var ht="January_February_March_April_May_June_July_August_September_October_November_December".split("_"),pt="Jan_Feb_Mar_Apr_May_Jun_Jul_Aug_Sep_Oct_Nov_Dec".split("_"),mt={};e.suppressDeprecationWarnings=!1;var gt=/^\s*(?:[+-]\d{6}|\d{4})-(?:(\d\d-\d\d)|(W\d\d$)|(W\d\d-\d)|(\d\d\d))((T| )(\d\d(:\d\d(:\d\d(\.\d+)?)?)?)?([\+\-]\d\d(?::?\d\d)?|\s*Z)?)?$/,vt=[["YYYYYY-MM-DD",/[+-]\d{6}-\d{2}-\d{2}/],["YYYY-MM-DD",/\d{4}-\d{2}-\d{2}/],["GGGG-[W]WW-E",/\d{4}-W\d{2}-\d/],["GGGG-[W]WW",/\d{4}-W\d{2}/],["YYYY-DDD",/\d{4}-\d{3}/]],yt=[["HH:mm:ss.SSSS",/(T| )\d\d:\d\d:\d\d\.\d+/],["HH:mm:ss",/(T| )\d\d:\d\d:\d\d/],["HH:mm",/(T| )\d\d:\d\d/],["HH",/(T| )\d\d/]],bt=/^\/?Date\((\-?\d+)/i;e.createFromInputFallback=q("moment construction falls back to js Date. This is discouraged and will be removed in upcoming major release. Please refer to https://github.com/moment/moment/issues/1407 for more info.",(function(e){e._d=new Date(e._i+(e._useUTC?" UTC":""))})),A(0,["YY",2],0,(function(){return this.year()%100})),A(0,["YYYY",4],0,"year"),A(0,["YYYYY",5],0,"year"),A(0,["YYYYYY",6,!0],0,"year"),w("year","y"),I("Y",tt),I("YY",Xe,ze),I("YYYY",Ze,Ge),I("YYYYY",Je,Ke),I("YYYYYY",Je,Ke),j(["YYYYY","YYYYYY"],at),j("YYYY",(function(t,n){n[at]=2===t.length?e.parseTwoDigitYear(t):h(t)})),j("YY",(function(t,n){n[at]=e.parseTwoDigitYear(t)})),e.parseTwoDigitYear=function(e){return h(e)+(h(e)>68?1900:2e3)};var _t=T("FullYear",!1);A("w",["ww",2],"wo","week"),A("W",["WW",2],"Wo","isoWeek"),w("week","w"),w("isoWeek","W"),I("w",Xe),I("ww",Xe,ze),I("W",Xe),I("WW",Xe,ze),F(["w","ww","W","WW"],(function(e,t,n,i){t[i.substr(0,1)]=h(e)}));A("DDD",["DDDD",3],"DDDo","dayOfYear"),w("dayOfYear","DDD"),I("DDD",Qe),I("DDDD",$e),j(["DDD","DDDD"],(function(e,t,n){n._dayOfYear=h(e)})),e.ISO_8601=function(){};var wt=q("moment().min is deprecated, use moment.min instead. https://github.com/moment/moment/issues/1548",(function(){var e=ne.apply(null,arguments);return this>e?this:e})),xt=q("moment().max is deprecated, use moment.max instead. https://github.com/moment/moment/issues/1548",(function(){var e=ne.apply(null,arguments);return e>this?this:e}));ae("Z",":"),ae("ZZ",""),I("Z",nt),I("ZZ",nt),j(["Z","ZZ"],(function(e,t,n){n._useUTC=!0,n._tzm=se(e)}));var Dt=/([\+\-]|\d\d)/gi;e.updateOffset=function(){};var Tt=/(\-)?(?:(\d*)\.)?(\d+)\:(\d+)(?:\:(\d+)\.?(\d{3})?)?/,Ct=/^(-)?P(?:(?:([0-9,.]*)Y)?(?:([0-9,.]*)M)?(?:([0-9,.]*)D)?(?:T(?:([0-9,.]*)H)?(?:([0-9,.]*)M)?(?:([0-9,.]*)S)?)?|([0-9,.]*)W)$/;de.fn=re.prototype;var kt=pe(1,"add"),Et=pe(-1,"subtract");e.defaultFormat="YYYY-MM-DDTHH:mm:ssZ";var St=q("moment().lang() is deprecated. Instead, use moment().localeData() to get the language configuration. Use moment().locale() to change languages.",(function(e){return void 0===e?this.localeData():this.locale(e)}));A(0,["gg",2],0,(function(){return this.weekYear()%100})),A(0,["GG",2],0,(function(){return this.isoWeekYear()%100})),be("gggg","weekYear"),be("ggggg","weekYear"),be("GGGG","isoWeekYear"),be("GGGGG","isoWeekYear"),w("weekYear","gg"),w("isoWeekYear","GG"),I("G",tt),I("g",tt),I("GG",Xe,ze),I("gg",Xe,ze),I("GGGG",Ze,Ge),I("gggg",Ze,Ge),I("GGGGG",Je,Ke),I("ggggg",Je,Ke),F(["gggg","ggggg","GGGG","GGGGG"],(function(e,t,n,i){t[i.substr(0,2)]=h(e)})),F(["gg","GG"],(function(t,n,i,r){n[r]=e.parseTwoDigitYear(t)})),A("Q",0,0,"quarter"),w("quarter","Q"),I("Q",Be),j("Q",(function(e,t){t[st]=3*(h(e)-1)})),A("D",["DD",2],"Do","date"),w("date","D"),I("D",Xe),I("DD",Xe,ze),I("Do",(function(e,t){return e?t._ordinalParse:t._ordinalParseLenient})),j(["D","DD"],lt),j("Do",(function(e,t){t[lt]=h(e.match(Xe)[0])}));var At=T("Date",!0);A("d",0,"do","day"),A("dd",0,0,(function(e){return this.localeData().weekdaysMin(this,e)})),A("ddd",0,0,(function(e){return this.localeData().weekdaysShort(this,e)})),A("dddd",0,0,(function(e){return this.localeData().weekdays(this,e)})),A("e",0,0,"weekday"),A("E",0,0,"isoWeekday"),w("day","d"),w("weekday","e"),w("isoWeekday","E"),I("d",Xe),I("e",Xe),I("E",Xe),I("dd",it),I("ddd",it),I("dddd",it),F(["dd","ddd","dddd"],(function(e,t,n){var i=n._locale.weekdaysParse(e);null!=i?t.d=i:a(n).invalidWeekday=e})),F(["d","e","E"],(function(e,t,n,i){t[i]=h(e)}));var Ot="Sunday_Monday_Tuesday_Wednesday_Thursday_Friday_Saturday".split("_"),Mt="Sun_Mon_Tue_Wed_Thu_Fri_Sat".split("_"),Nt="Su_Mo_Tu_We_Th_Fr_Sa".split("_");A("H",["HH",2],0,"hour"),A("h",["hh",2],0,(function(){return this.hours()%12||12})),we("a",!0),we("A",!1),w("hour","h"),I("a",xe),I("A",xe),I("H",Xe),I("h",Xe),I("HH",Xe,ze),I("hh",Xe,ze),j(["H","HH"],ut),j(["a","A"],(function(e,t,n){n._isPm=n._locale.isPM(e),n._meridiem=e})),j(["h","hh"],(function(e,t,n){t[ut]=h(e),a(n).bigHour=!0}));var It=T("Hours",!0);A("m",["mm",2],0,"minute"),w("minute","m"),I("m",Xe),I("mm",Xe,ze),j(["m","mm"],ct);var Lt=T("Minutes",!1);A("s",["ss",2],0,"second"),w("second","s"),I("s",Xe),I("ss",Xe,ze),j(["s","ss"],dt);var jt,Ft=T("Seconds",!1);for(A("S",0,0,(function(){return~~(this.millisecond()/100)})),A(0,["SS",2],0,(function(){return~~(this.millisecond()/10)})),A(0,["SSS",3],0,"millisecond"),A(0,["SSSS",4],0,(function(){return 10*this.millisecond()})),A(0,["SSSSS",5],0,(function(){return 100*this.millisecond()})),A(0,["SSSSSS",6],0,(function(){return 1e3*this.millisecond()})),A(0,["SSSSSSS",7],0,(function(){return 1e4*this.millisecond()})),A(0,["SSSSSSSS",8],0,(function(){return 1e5*this.millisecond()})),A(0,["SSSSSSSSS",9],0,(function(){return 1e6*this.millisecond()})),w("millisecond","ms"),I("S",Qe,Be),I("SS",Qe,ze),I("SSS",Qe,$e),jt="SSSS";jt.length<=9;jt+="S")I(jt,et);for(jt="S";jt.length<=9;jt+="S")j(jt,De);var Pt=T("Milliseconds",!1);A("z",0,0,"zoneAbbr"),A("zz",0,0,"zoneName");var Rt=c.prototype;Rt.add=kt,Rt.calendar=function(e,t){var n=e||ne(),i=le(n,this).startOf("day"),r=this.diff(i,"days",!0),o=-6>r?"sameElse":-1>r?"lastWeek":0>r?"lastDay":1>r?"sameDay":2>r?"nextDay":7>r?"nextWeek":"sameElse";return this.format(t&&t[o]||this.localeData().calendar(o,this,ne(n)))},Rt.clone=function(){return new c(this)},Rt.diff=function(e,t,n){var i,r,o=le(e,this),a=6e4*(o.utcOffset()-this.utcOffset());return"year"===(t=x(t))||"month"===t||"quarter"===t?(r=function(e,t){var n,i,r=12*(t.year()-e.year())+(t.month()-e.month()),o=e.clone().add(r,"months");return 0>t-o?(n=e.clone().add(r-1,"months"),i=(t-o)/(o-n)):(n=e.clone().add(r+1,"months"),i=(t-o)/(n-o)),-(r+i)}(this,o),"quarter"===t?r/=3:"year"===t&&(r/=12)):(i=this-o,r="second"===t?i/1e3:"minute"===t?i/6e4:"hour"===t?i/36e5:"day"===t?(i-a)/864e5:"week"===t?(i-a)/6048e5:i),n?r:f(r)},Rt.endOf=function(e){return void 0===(e=x(e))||"millisecond"===e?this:this.startOf(e).add(1,"isoWeek"===e?"week":e).subtract(1,"ms")},Rt.format=function(t){var n=M(this,t||e.defaultFormat);return this.localeData().postformat(n)},Rt.from=function(e,t){return this.isValid()?de({to:this,from:e}).locale(this.locale()).humanize(!t):this.localeData().invalidDate()},Rt.fromNow=function(e){return this.from(ne(),e)},Rt.to=function(e,t){return this.isValid()?de({from:this,to:e}).locale(this.locale()).humanize(!t):this.localeData().invalidDate()},Rt.toNow=function(e){return this.to(ne(),e)},Rt.get=E,Rt.invalidAt=function(){return a(this).overflow},Rt.isAfter=function(e,t){return"millisecond"===(t=x(void 0!==t?t:"millisecond"))?+this>+(e=d(e)?e:ne(e)):(d(e)?+e:+ne(e))<+this.clone().startOf(t)},Rt.isBefore=function(e,t){var n;return"millisecond"===(t=x(void 0!==t?t:"millisecond"))?+(e=d(e)?e:ne(e))>+this:(n=d(e)?+e:+ne(e),+this.clone().endOf(t)<n)},Rt.isBetween=function(e,t,n){return this.isAfter(e,n)&&this.isBefore(t,n)},Rt.isSame=function(e,t){var n;return"millisecond"===(t=x(t||"millisecond"))?+this==+(e=d(e)?e:ne(e)):(n=+ne(e),+this.clone().startOf(t)<=n&&n<=+this.clone().endOf(t))},Rt.isValid=function(){return s(this)},Rt.lang=St,Rt.locale=ve,Rt.localeData=ye,Rt.max=xt,Rt.min=wt,Rt.parsingFlags=function(){return r({},a(this))},Rt.set=E,Rt.startOf=function(e){switch(e=x(e)){case"year":this.month(0);case"quarter":case"month":this.date(1);case"week":case"isoWeek":case"day":this.hours(0);case"hour":this.minutes(0);case"minute":this.seconds(0);case"second":this.milliseconds(0)}return"week"===e&&this.weekday(0),"isoWeek"===e&&this.isoWeekday(1),"quarter"===e&&this.month(3*Math.floor(this.month()/3)),this},Rt.subtract=Et,Rt.toArray=function(){var e=this;return[e.year(),e.month(),e.date(),e.hour(),e.minute(),e.second(),e.millisecond()]},Rt.toObject=function(){var e=this;return{years:e.year(),months:e.month(),date:e.date(),hours:e.hours(),minutes:e.minutes(),seconds:e.seconds(),milliseconds:e.milliseconds()}},Rt.toDate=function(){return this._offset?new Date(+this):this._d},Rt.toISOString=ge,Rt.toJSON=ge,Rt.toString=function(){return this.clone().locale("en").format("ddd MMM DD YYYY HH:mm:ss [GMT]ZZ")},Rt.unix=function(){return Math.floor(+this/1e3)},Rt.valueOf=function(){return+this._d-6e4*(this._offset||0)},Rt.year=_t,Rt.isLeapYear=function(){return G(this.year())},Rt.weekYear=function(e){var t=K(this,this.localeData()._week.dow,this.localeData()._week.doy).year;return null==e?t:this.add(e-t,"y")},Rt.isoWeekYear=function(e){var t=K(this,1,4).year;return null==e?t:this.add(e-t,"y")},Rt.quarter=Rt.quarters=function(e){return null==e?Math.ceil((this.month()+1)/3):this.month(3*(e-1)+this.month()%3)},Rt.month=Y,Rt.daysInMonth=function(){return R(this.year(),this.month())},Rt.week=Rt.weeks=function(e){var t=this.localeData().week(this);return null==e?t:this.add(7*(e-t),"d")},Rt.isoWeek=Rt.isoWeeks=function(e){var t=K(this,1,4).week;return null==e?t:this.add(7*(e-t),"d")},Rt.weeksInYear=function(){var e=this.localeData()._week;return _e(this.year(),e.dow,e.doy)},Rt.isoWeeksInYear=function(){return _e(this.year(),1,4)},Rt.date=At,Rt.day=Rt.days=function(e){var t=this._isUTC?this._d.getUTCDay():this._d.getDay();return null!=e?(e=function(e,t){return"string"!=typeof e?e:isNaN(e)?"number"==typeof(e=t.weekdaysParse(e))?e:null:parseInt(e,10)}(e,this.localeData()),this.add(e-t,"d")):t},Rt.weekday=function(e){var t=(this.day()+7-this.localeData()._week.dow)%7;return null==e?t:this.add(e-t,"d")},Rt.isoWeekday=function(e){return null==e?this.day()||7:this.day(this.day()%7?e:e-7)},Rt.dayOfYear=function(e){var t=Math.round((this.clone().startOf("day")-this.clone().startOf("year"))/864e5)+1;return null==e?t:this.add(e-t,"d")},Rt.hour=Rt.hours=It,Rt.minute=Rt.minutes=Lt,Rt.second=Rt.seconds=Ft,Rt.millisecond=Rt.milliseconds=Pt,Rt.utcOffset=function(t,n){var i,r=this._offset||0;return null!=t?("string"==typeof t&&(t=se(t)),Math.abs(t)<16&&(t*=60),!this._isUTC&&n&&(i=ue(this)),this._offset=t,this._isUTC=!0,null!=i&&this.add(i,"m"),r!==t&&(!n||this._changeInProgress?me(this,de(t-r,"m"),1,!1):this._changeInProgress||(this._changeInProgress=!0,e.updateOffset(this,!0),this._changeInProgress=null)),this):this._isUTC?r:ue(this)},Rt.utc=function(e){return this.utcOffset(0,e)},Rt.local=function(e){return this._isUTC&&(this.utcOffset(0,e),this._isUTC=!1,e&&this.subtract(ue(this),"m")),this},Rt.parseZone=function(){return this._tzm?this.utcOffset(this._tzm):"string"==typeof this._i&&this.utcOffset(se(this._i)),this},Rt.hasAlignedHourOffset=function(e){return e=e?ne(e).utcOffset():0,(this.utcOffset()-e)%60==0},Rt.isDST=function(){return this.utcOffset()>this.clone().month(0).utcOffset()||this.utcOffset()>this.clone().month(5).utcOffset()},Rt.isDSTShifted=function(){if(void 0!==this._isDSTShifted)return this._isDSTShifted;var e={};if(u(e,this),(e=J(e))._a){var t=e._isUTC?o(e._a):ne(e._a);this._isDSTShifted=this.isValid()&&p(e._a,t.toArray())>0}else this._isDSTShifted=!1;return this._isDSTShifted},Rt.isLocal=function(){return!this._isUTC},Rt.isUtcOffset=function(){return this._isUTC},Rt.isUtc=ce,Rt.isUTC=ce,Rt.zoneAbbr=function(){return this._isUTC?"UTC":""},Rt.zoneName=function(){return this._isUTC?"Coordinated Universal Time":""},Rt.dates=q("dates accessor is deprecated. Use date instead.",At),Rt.months=q("months accessor is deprecated. Use month instead",Y),Rt.years=q("years accessor is deprecated. Use year instead",_t),Rt.zone=q("moment().zone is deprecated, use moment().utcOffset instead. https://github.com/moment/moment/issues/1779",(function(e,t){return null!=e?("string"!=typeof e&&(e=-e),this.utcOffset(e,t),this):-this.utcOffset()}));var Ut=Rt,Yt=m.prototype;Yt._calendar={sameDay:"[Today at] LT",nextDay:"[Tomorrow at] LT",nextWeek:"dddd [at] LT",lastDay:"[Yesterday at] LT",lastWeek:"[Last] dddd [at] LT",sameElse:"L"},Yt.calendar=function(e,t,n){var i=this._calendar[e];return"function"==typeof i?i.call(t,n):i},Yt._longDateFormat={LTS:"h:mm:ss A",LT:"h:mm A",L:"MM/DD/YYYY",LL:"MMMM D, YYYY",LLL:"MMMM D, YYYY h:mm A",LLLL:"dddd, MMMM D, YYYY h:mm A"},Yt.longDateFormat=function(e){var t=this._longDateFormat[e],n=this._longDateFormat[e.toUpperCase()];return t||!n?t:(this._longDateFormat[e]=n.replace(/MMMM|MM|DD|dddd/g,(function(e){return e.slice(1)})),this._longDateFormat[e])},Yt._invalidDate="Invalid date",Yt.invalidDate=function(){return this._invalidDate},Yt._ordinal="%d",Yt.ordinal=function(e){return this._ordinal.replace("%d",e)},Yt._ordinalParse=/\d{1,2}/,Yt.preparse=Te,Yt.postformat=Te,Yt._relativeTime={future:"in %s",past:"%s ago",s:"a few seconds",m:"a minute",mm:"%d minutes",h:"an hour",hh:"%d hours",d:"a day",dd:"%d days",M:"a month",MM:"%d months",y:"a year",yy:"%d years"},Yt.relativeTime=function(e,t,n,i){var r=this._relativeTime[n];return"function"==typeof r?r(e,t,n,i):r.replace(/%d/i,e)},Yt.pastFuture=function(e,t){var n=this._relativeTime[e>0?"future":"past"];return"function"==typeof n?n(t):n.replace(/%s/i,t)},Yt.set=function(e){var t,n;for(n in e)"function"==typeof(t=e[n])?this[n]=t:this["_"+n]=t;this._ordinalParseLenient=new RegExp(this._ordinalParse.source+"|"+/\d{1,2}/.source)},Yt.months=function(e){return this._months[e.month()]},Yt._months=ht,Yt.monthsShort=function(e){return this._monthsShort[e.month()]},Yt._monthsShort=pt,Yt.monthsParse=function(e,t,n){var i,r,a;for(this._monthsParse||(this._monthsParse=[],this._longMonthsParse=[],this._shortMonthsParse=[]),i=0;12>i;i++){if(r=o([2e3,i]),n&&!this._longMonthsParse[i]&&(this._longMonthsParse[i]=new RegExp("^"+this.months(r,"").replace(".","")+"$","i"),this._shortMonthsParse[i]=new RegExp("^"+this.monthsShort(r,"").replace(".","")+"$","i")),n||this._monthsParse[i]||(a="^"+this.months(r,"")+"|^"+this.monthsShort(r,""),this._monthsParse[i]=new RegExp(a.replace(".",""),"i")),n&&"MMMM"===t&&this._longMonthsParse[i].test(e))return i;if(n&&"MMM"===t&&this._shortMonthsParse[i].test(e))return i;if(!n&&this._monthsParse[i].test(e))return i}},Yt.week=function(e){return K(e,this._week.dow,this._week.doy).week},Yt._week={dow:0,doy:6},Yt.firstDayOfYear=function(){return this._week.doy},Yt.firstDayOfWeek=function(){return this._week.dow},Yt.weekdays=function(e){return this._weekdays[e.day()]},Yt._weekdays=Ot,Yt.weekdaysMin=function(e){return this._weekdaysMin[e.day()]},Yt._weekdaysMin=Nt,Yt.weekdaysShort=function(e){return this._weekdaysShort[e.day()]},Yt._weekdaysShort=Mt,Yt.weekdaysParse=function(e){var t,n,i;for(this._weekdaysParse=this._weekdaysParse||[],t=0;7>t;t++)if(this._weekdaysParse[t]||(n=ne([2e3,1]).day(t),i="^"+this.weekdays(n,"")+"|^"+this.weekdaysShort(n,"")+"|^"+this.weekdaysMin(n,""),this._weekdaysParse[t]=new RegExp(i.replace(".",""),"i")),this._weekdaysParse[t].test(e))return t},Yt.isPM=function(e){return"p"===(e+"").toLowerCase().charAt(0)},Yt._meridiemParse=/[ap]\.?m?\.?/i,Yt.meridiem=function(e,t,n){return e>11?n?"pm":"PM":n?"am":"AM"},y("en",{ordinalParse:/\d{1,2}(th|st|nd|rd)/,ordinal:function(e){var t=e%10;return e+(1===h(e%100/10)?"th":1===t?"st":2===t?"nd":3===t?"rd":"th")}}),e.lang=q("moment.lang is deprecated. Use moment.locale instead.",y),e.langData=q("moment.langData is deprecated. Use moment.localeData instead.",_);var Ht=Math.abs,Wt=Me("ms"),qt=Me("s"),Vt=Me("m"),Bt=Me("h"),zt=Me("d"),$t=Me("w"),Gt=Me("M"),Kt=Me("y"),Xt=Ne("milliseconds"),Qt=Ne("seconds"),Zt=Ne("minutes"),Jt=Ne("hours"),en=Ne("days"),tn=Ne("months"),nn=Ne("years"),rn=Math.round,on={s:45,m:45,h:22,d:26,M:11},an=Math.abs,sn=re.prototype;return sn.abs=function(){var e=this._data;return this._milliseconds=Ht(this._milliseconds),this._days=Ht(this._days),this._months=Ht(this._months),e.milliseconds=Ht(e.milliseconds),e.seconds=Ht(e.seconds),e.minutes=Ht(e.minutes),e.hours=Ht(e.hours),e.months=Ht(e.months),e.years=Ht(e.years),this},sn.add=function(e,t){return Ee(this,e,t,1)},sn.subtract=function(e,t){return Ee(this,e,t,-1)},sn.as=function(e){var t,n,i=this._milliseconds;if("month"===(e=x(e))||"year"===e)return t=this._days+i/864e5,n=this._months+Ae(t),"month"===e?n:n/12;switch(t=this._days+Math.round(Oe(this._months)),e){case"week":return t/7+i/6048e5;case"day":return t+i/864e5;case"hour":return 24*t+i/36e5;case"minute":return 1440*t+i/6e4;case"second":return 86400*t+i/1e3;case"millisecond":return Math.floor(864e5*t)+i;default:throw new Error("Unknown unit "+e)}},sn.asMilliseconds=Wt,sn.asSeconds=qt,sn.asMinutes=Vt,sn.asHours=Bt,sn.asDays=zt,sn.asWeeks=$t,sn.asMonths=Gt,sn.asYears=Kt,sn.valueOf=function(){return this._milliseconds+864e5*this._days+this._months%12*2592e6+31536e6*h(this._months/12)},sn._bubble=function(){var e,t,n,i,r,o=this._milliseconds,a=this._days,s=this._months,l=this._data;return o>=0&&a>=0&&s>=0||0>=o&&0>=a&&0>=s||(o+=864e5*Se(Oe(s)+a),a=0,s=0),l.milliseconds=o%1e3,e=f(o/1e3),l.seconds=e%60,t=f(e/60),l.minutes=t%60,n=f(t/60),l.hours=n%24,a+=f(n/24),s+=r=f(Ae(a)),a-=Se(Oe(r)),i=f(s/12),s%=12,l.days=a,l.months=s,l.years=i,this},sn.get=function(e){return this[(e=x(e))+"s"]()},sn.milliseconds=Xt,sn.seconds=Qt,sn.minutes=Zt,sn.hours=Jt,sn.days=en,sn.weeks=function(){return f(this.days()/7)},sn.months=tn,sn.years=nn,sn.humanize=function(e){var t=this.localeData(),n=function(e,t,n){var i=de(e).abs(),r=rn(i.as("s")),o=rn(i.as("m")),a=rn(i.as("h")),s=rn(i.as("d")),l=rn(i.as("M")),u=rn(i.as("y")),c=r<on.s&&["s",r]||1===o&&["m"]||o<on.m&&["mm",o]||1===a&&["h"]||a<on.h&&["hh",a]||1===s&&["d"]||s<on.d&&["dd",s]||1===l&&["M"]||l<on.M&&["MM",l]||1===u&&["y"]||["yy",u];return c[2]=t,c[3]=+e>0,c[4]=n,Ie.apply(null,c)}(this,!e,t);return e&&(n=t.pastFuture(+this,n)),t.postformat(n)},sn.toISOString=Le,sn.toString=Le,sn.toJSON=Le,sn.locale=ve,sn.localeData=ye,sn.toIsoString=q("toIsoString() is deprecated. Please use toISOString() instead (notice the capitals)",Le),sn.lang=St,A("X",0,0,"unix"),A("x",0,0,"valueOf"),I("x",tt),I("X",/[+-]?\d+(\.\d{1,3})?/),j("X",(function(e,t,n){n._d=new Date(1e3*parseFloat(e,10))})),j("x",(function(e,t,n){n._d=new Date(h(e))})),e.version="2.10.6",function(e){je=e}(ne),e.fn=Ut,e.min=function(){var e=[].slice.call(arguments,0);return ie("isBefore",e)},e.max=function(){var e=[].slice.call(arguments,0);return ie("isAfter",e)},e.utc=o,e.unix=function(e){return ne(1e3*e)},e.months=function(e,t){return ke(e,t,"months",12,"month")},e.isDate=n,e.locale=y,e.invalid=l,e.duration=de,e.isMoment=d,e.weekdays=function(e,t){return ke(e,t,"weekdays",7,"day")},e.parseZone=function(){return ne.apply(null,arguments).parseZone()},e.localeData=_,e.isDuration=oe,e.monthsShort=function(e,t){return ke(e,t,"monthsShort",12,"month")},e.weekdaysMin=function(e,t){return ke(e,t,"weekdaysMin",7,"day")},e.defineLocale=b,e.weekdaysShort=function(e,t){return ke(e,t,"weekdaysShort",7,"day")},e.normalizeUnits=x,e.relativeTimeThreshold=function(e,t){return void 0!==on[e]&&(void 0===t?on[e]:(on[e]=t,!0))},e})),function(e,t){"object"==typeof exports&&"object"==typeof module?module.exports=t():"function"==typeof define&&define.amd?define([],t):"object"==typeof exports?exports.swal=t():e.swal=t()}(this,(function(){return function(e){function t(i){if(n[i])return n[i].exports;var r=n[i]={i,l:!1,exports:{}};return e[i].call(r.exports,r,r.exports,t),r.l=!0,r.exports}var n={};return t.m=e,t.c=n,t.d=function(e,n,i){t.o(e,n)||Object.defineProperty(e,n,{configurable:!1,enumerable:!0,get:i})},t.n=function(e){var n=e&&e.__esModule?function(){return e.default}:function(){return e};return t.d(n,"a",n),n},t.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},t.p="",t(t.s=8)}([function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var i="swal-button";t.CLASS_NAMES={MODAL:"swal-modal",OVERLAY:"swal-overlay",SHOW_MODAL:"swal-overlay--show-modal",MODAL_TITLE:"swal-title",MODAL_TEXT:"swal-text",ICON:"swal-icon",ICON_CUSTOM:"swal-icon--custom",CONTENT:"swal-content",FOOTER:"swal-footer",BUTTON_CONTAINER:"swal-button-container",BUTTON:i,CONFIRM_BUTTON:i+"--confirm",CANCEL_BUTTON:i+"--cancel",DANGER_BUTTON:i+"--danger",BUTTON_LOADING:i+"--loading",BUTTON_LOADER:i+"__loader"},t.default=t.CLASS_NAMES},function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.getNode=function(e){var t="."+e;return document.querySelector(t)},t.stringToNode=function(e){var t=document.createElement("div");return t.innerHTML=e.trim(),t.firstChild},t.insertAfter=function(e,t){var n=t.nextSibling;t.parentNode.insertBefore(e,n)},t.removeNode=function(e){e.parentElement.removeChild(e)},t.throwErr=function(e){throw"SweetAlert: "+(e=e.replace(/ +(?= )/g,"")).trim()},t.isPlainObject=function(e){if("[object Object]"!==Object.prototype.toString.call(e))return!1;var t=Object.getPrototypeOf(e);return null===t||t===Object.prototype},t.ordinalSuffixOf=function(e){var t=e%10,n=e%100;return 1===t&&11!==n?e+"st":2===t&&12!==n?e+"nd":3===t&&13!==n?e+"rd":e+"th"}},function(e,t,n){"use strict";function i(e){for(var n in e)t.hasOwnProperty(n)||(t[n]=e[n])}Object.defineProperty(t,"__esModule",{value:!0}),i(n(25));var r=n(26);t.overlayMarkup=r.default,i(n(27)),i(n(28)),i(n(29));var o=n(0),a=o.default.MODAL_TITLE,s=o.default.MODAL_TEXT,l=o.default.ICON,u=o.default.FOOTER;t.iconMarkup='\n  <div class="'+l+'"></div>',t.titleMarkup='\n  <div class="'+a+'"></div>\n',t.textMarkup='\n  <div class="'+s+'"></div>',t.footerMarkup='\n  <div class="'+u+'"></div>\n'},function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var i=n(1);t.CONFIRM_KEY="confirm",t.CANCEL_KEY="cancel";var r={visible:!0,text:null,value:null,className:"",closeModal:!0},o=Object.assign({},r,{visible:!1,text:"Cancel",value:null}),a=Object.assign({},r,{text:"OK",value:!0});t.defaultButtonList={cancel:o,confirm:a};var s=function(e){switch(e){case t.CONFIRM_KEY:return a;case t.CANCEL_KEY:return o;default:var n=e.charAt(0).toUpperCase()+e.slice(1);return Object.assign({},r,{text:n,value:e})}},l=function(e,t){var n=s(e);return!0===t?Object.assign({},n,{visible:!0}):"string"==typeof t?Object.assign({},n,{visible:!0,text:t}):i.isPlainObject(t)?Object.assign({visible:!0},n,t):Object.assign({},n,{visible:!1})},u=function(e){var n={};switch(e.length){case 1:n[t.CANCEL_KEY]=Object.assign({},o,{visible:!1});break;case 2:n[t.CANCEL_KEY]=l(t.CANCEL_KEY,e[0]),n[t.CONFIRM_KEY]=l(t.CONFIRM_KEY,e[1]);break;default:i.throwErr("Invalid number of 'buttons' in array ("+e.length+").\n      If you want more than 2 buttons, you need to use an object!")}return n};t.getButtonListOpts=function(e){var n=t.defaultButtonList;return"string"==typeof e?n[t.CONFIRM_KEY]=l(t.CONFIRM_KEY,e):Array.isArray(e)?n=u(e):i.isPlainObject(e)?n=function(e){for(var t={},n=0,i=Object.keys(e);n<i.length;n++){var r=i[n],a=e[r],s=l(r,a);t[r]=s}return t.cancel||(t.cancel=o),t}(e):!0===e?n=u([!0,!0]):!1===e?n=u([!1,!1]):void 0===e&&(n=t.defaultButtonList),n}},function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var i=n(1),r=n(2),o=n(0),a=o.default.MODAL,s=o.default.OVERLAY,l=n(30),u=n(31),c=n(32),d=n(33);t.injectElIntoModal=function(e){var t=i.getNode(a),n=i.stringToNode(e);return t.appendChild(n),n};var f=function(e,t){!function(e){e.className=a,e.textContent=""}(e);var n=t.className;n&&e.classList.add(n)};t.initModalContent=function(e){var t=i.getNode(a);f(t,e),l.default(e.icon),u.initTitle(e.title),u.initText(e.text),d.default(e.content),c.default(e.buttons,e.dangerMode)};t.default=function(){var e=i.getNode(s),t=i.stringToNode(r.modalMarkup);e.appendChild(t)}},function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var i=n(3),r={isOpen:!1,promise:null,actions:{},timer:null},o=Object.assign({},r);t.resetState=function(){o=Object.assign({},r)},t.setActionValue=function(e){if("string"==typeof e)return a(i.CONFIRM_KEY,e);for(var t in e)a(t,e[t])};var a=function(e,t){o.actions[e]||(o.actions[e]={}),Object.assign(o.actions[e],{value:t})};t.setActionOptionsFor=function(e,t){var n=(void 0===t?{}:t).closeModal,i=void 0===n||n;Object.assign(o.actions[e],{closeModal:i})},t.default=o},function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var i=n(1),r=n(3),o=n(0),a=o.default.OVERLAY,s=o.default.SHOW_MODAL,l=o.default.BUTTON,u=o.default.BUTTON_LOADING,c=n(5);t.openModal=function(){i.getNode(a).classList.add(s),c.default.isOpen=!0};t.onAction=function(e){void 0===e&&(e=r.CANCEL_KEY);var t=c.default.actions[e],n=t.value;if(!1===t.closeModal){var o=l+"--"+e;i.getNode(o).classList.add(u)}else i.getNode(a).classList.remove(s),c.default.isOpen=!1;c.default.promise.resolve(n)},t.getState=function(){var e=Object.assign({},c.default);return delete e.promise,delete e.timer,e},t.stopLoading=function(){for(var e=document.querySelectorAll("."+l),t=0;t<e.length;t++)e[t].classList.remove(u)}},function(e,t){var n;n=function(){return this}();try{n=n||Function("return this")()||(0,eval)("this")}catch(e){"object"==typeof window&&(n=window)}e.exports=n},function(e,t,n){(function(t){e.exports=t.sweetAlert=n(9)}).call(t,n(7))},function(e,t,n){(function(t){e.exports=t.swal=n(10)}).call(t,n(7))},function(e,t,n){"undefined"!=typeof window&&n(11),n(16);var i=n(23).default;e.exports=i},function(e,t,n){var i=n(12);"string"==typeof i&&(i=[[e.i,i,""]]);var r={insertAt:"top",transform:void 0};n(14)(i,r),i.locals&&(e.exports=i.locals)},function(e,t,n){(e.exports=n(13)(void 0)).push([e.i,'.swal-icon--error{border-color:#f27474;-webkit-animation:animateErrorIcon .5s;animation:animateErrorIcon .5s}.swal-icon--error__x-mark{position:relative;display:block;-webkit-animation:animateXMark .5s;animation:animateXMark .5s}.swal-icon--error__line{position:absolute;height:5px;width:47px;background-color:#f27474;display:block;top:37px;border-radius:2px}.swal-icon--error__line--left{-webkit-transform:rotate(45deg);transform:rotate(45deg);left:17px}.swal-icon--error__line--right{-webkit-transform:rotate(-45deg);transform:rotate(-45deg);right:16px}@-webkit-keyframes animateErrorIcon{0%{-webkit-transform:rotateX(100deg);transform:rotateX(100deg);opacity:0}to{-webkit-transform:rotateX(0deg);transform:rotateX(0deg);opacity:1}}@keyframes animateErrorIcon{0%{-webkit-transform:rotateX(100deg);transform:rotateX(100deg);opacity:0}to{-webkit-transform:rotateX(0deg);transform:rotateX(0deg);opacity:1}}@-webkit-keyframes animateXMark{0%{-webkit-transform:scale(.4);transform:scale(.4);margin-top:26px;opacity:0}50%{-webkit-transform:scale(.4);transform:scale(.4);margin-top:26px;opacity:0}80%{-webkit-transform:scale(1.15);transform:scale(1.15);margin-top:-6px}to{-webkit-transform:scale(1);transform:scale(1);margin-top:0;opacity:1}}@keyframes animateXMark{0%{-webkit-transform:scale(.4);transform:scale(.4);margin-top:26px;opacity:0}50%{-webkit-transform:scale(.4);transform:scale(.4);margin-top:26px;opacity:0}80%{-webkit-transform:scale(1.15);transform:scale(1.15);margin-top:-6px}to{-webkit-transform:scale(1);transform:scale(1);margin-top:0;opacity:1}}.swal-icon--warning{border-color:#f8bb86;-webkit-animation:pulseWarning .75s infinite alternate;animation:pulseWarning .75s infinite alternate}.swal-icon--warning__body{width:5px;height:47px;top:10px;border-radius:2px;margin-left:-2px}.swal-icon--warning__body,.swal-icon--warning__dot{position:absolute;left:50%;background-color:#f8bb86}.swal-icon--warning__dot{width:7px;height:7px;border-radius:50%;margin-left:-4px;bottom:-11px}@-webkit-keyframes pulseWarning{0%{border-color:#f8d486}to{border-color:#f8bb86}}@keyframes pulseWarning{0%{border-color:#f8d486}to{border-color:#f8bb86}}.swal-icon--success{border-color:#a5dc86}.swal-icon--success:after,.swal-icon--success:before{content:"";border-radius:50%;position:absolute;width:60px;height:120px;background:#fff;-webkit-transform:rotate(45deg);transform:rotate(45deg)}.swal-icon--success:before{border-radius:120px 0 0 120px;top:-7px;left:-33px;-webkit-transform:rotate(-45deg);transform:rotate(-45deg);-webkit-transform-origin:60px 60px;transform-origin:60px 60px}.swal-icon--success:after{border-radius:0 120px 120px 0;top:-11px;left:30px;-webkit-transform:rotate(-45deg);transform:rotate(-45deg);-webkit-transform-origin:0 60px;transform-origin:0 60px;-webkit-animation:rotatePlaceholder 4.25s ease-in;animation:rotatePlaceholder 4.25s ease-in}.swal-icon--success__ring{width:80px;height:80px;border:4px solid hsla(98,55%,69%,.2);border-radius:50%;box-sizing:content-box;position:absolute;left:-4px;top:-4px;z-index:2}.swal-icon--success__hide-corners{width:5px;height:90px;background-color:#fff;padding:1px;position:absolute;left:28px;top:8px;z-index:1;-webkit-transform:rotate(-45deg);transform:rotate(-45deg)}.swal-icon--success__line{height:5px;background-color:#a5dc86;display:block;border-radius:2px;position:absolute;z-index:2}.swal-icon--success__line--tip{width:25px;left:14px;top:46px;-webkit-transform:rotate(45deg);transform:rotate(45deg);-webkit-animation:animateSuccessTip .75s;animation:animateSuccessTip .75s}.swal-icon--success__line--long{width:47px;right:8px;top:38px;-webkit-transform:rotate(-45deg);transform:rotate(-45deg);-webkit-animation:animateSuccessLong .75s;animation:animateSuccessLong .75s}@-webkit-keyframes rotatePlaceholder{0%{-webkit-transform:rotate(-45deg);transform:rotate(-45deg)}5%{-webkit-transform:rotate(-45deg);transform:rotate(-45deg)}12%{-webkit-transform:rotate(-405deg);transform:rotate(-405deg)}to{-webkit-transform:rotate(-405deg);transform:rotate(-405deg)}}@keyframes rotatePlaceholder{0%{-webkit-transform:rotate(-45deg);transform:rotate(-45deg)}5%{-webkit-transform:rotate(-45deg);transform:rotate(-45deg)}12%{-webkit-transform:rotate(-405deg);transform:rotate(-405deg)}to{-webkit-transform:rotate(-405deg);transform:rotate(-405deg)}}@-webkit-keyframes animateSuccessTip{0%{width:0;left:1px;top:19px}54%{width:0;left:1px;top:19px}70%{width:50px;left:-8px;top:37px}84%{width:17px;left:21px;top:48px}to{width:25px;left:14px;top:45px}}@keyframes animateSuccessTip{0%{width:0;left:1px;top:19px}54%{width:0;left:1px;top:19px}70%{width:50px;left:-8px;top:37px}84%{width:17px;left:21px;top:48px}to{width:25px;left:14px;top:45px}}@-webkit-keyframes animateSuccessLong{0%{width:0;right:46px;top:54px}65%{width:0;right:46px;top:54px}84%{width:55px;right:0;top:35px}to{width:47px;right:8px;top:38px}}@keyframes animateSuccessLong{0%{width:0;right:46px;top:54px}65%{width:0;right:46px;top:54px}84%{width:55px;right:0;top:35px}to{width:47px;right:8px;top:38px}}.swal-icon--info{border-color:#c9dae1}.swal-icon--info:before{width:5px;height:29px;bottom:17px;border-radius:2px;margin-left:-2px}.swal-icon--info:after,.swal-icon--info:before{content:"";position:absolute;left:50%;background-color:#c9dae1}.swal-icon--info:after{width:7px;height:7px;border-radius:50%;margin-left:-3px;top:19px}.swal-icon{width:80px;height:80px;border-width:4px;border-style:solid;border-radius:50%;padding:0;position:relative;box-sizing:content-box;margin:20px auto}.swal-icon:first-child{margin-top:32px}.swal-icon--custom{width:auto;height:auto;max-width:100%;border:none;border-radius:0}.swal-icon img{max-width:100%;max-height:100%}.swal-title{color:rgba(0,0,0,.65);font-weight:600;text-transform:none;position:relative;display:block;padding:13px 16px;font-size:27px;line-height:normal;text-align:center;margin-bottom:0}.swal-title:first-child{margin-top:26px}.swal-title:not(:first-child){padding-bottom:0}.swal-title:not(:last-child){margin-bottom:13px}.swal-text{font-size:16px;position:relative;float:none;line-height:normal;vertical-align:top;text-align:left;display:inline-block;margin:0;padding:0 10px;font-weight:400;color:rgba(0,0,0,.64);max-width:calc(100% - 20px);overflow-wrap:break-word;box-sizing:border-box}.swal-text:first-child{margin-top:45px}.swal-text:last-child{margin-bottom:45px}.swal-footer{text-align:right;padding-top:13px;margin-top:13px;padding:13px 16px;border-radius:inherit;border-top-left-radius:0;border-top-right-radius:0}.swal-button-container{margin:5px;display:inline-block;position:relative}.swal-button{background-color:#7cd1f9;color:#fff;border:none;box-shadow:none;border-radius:5px;font-weight:600;font-size:14px;padding:10px 24px;margin:0;cursor:pointer}.swal-button[not:disabled]:hover{background-color:#78cbf2}.swal-button:active{background-color:#70bce0}.swal-button:focus{outline:none;box-shadow:0 0 0 1px #fff,0 0 0 3px rgba(43,114,165,.29)}.swal-button[disabled]{opacity:.5;cursor:default}.swal-button::-moz-focus-inner{border:0}.swal-button--cancel{color:#555;background-color:#efefef}.swal-button--cancel[not:disabled]:hover{background-color:#e8e8e8}.swal-button--cancel:active{background-color:#d7d7d7}.swal-button--cancel:focus{box-shadow:0 0 0 1px #fff,0 0 0 3px rgba(116,136,150,.29)}.swal-button--danger{background-color:#e64942}.swal-button--danger[not:disabled]:hover{background-color:#df4740}.swal-button--danger:active{background-color:#cf423b}.swal-button--danger:focus{box-shadow:0 0 0 1px #fff,0 0 0 3px rgba(165,43,43,.29)}.swal-content{padding:0 20px;margin-top:20px;font-size:medium}.swal-content:last-child{margin-bottom:20px}.swal-content__input,.swal-content__textarea{-webkit-appearance:none;background-color:#fff;border:none;font-size:14px;display:block;box-sizing:border-box;width:100%;border:1px solid rgba(0,0,0,.14);padding:10px 13px;border-radius:2px;transition:border-color .2s}.swal-content__input:focus,.swal-content__textarea:focus{outline:none;border-color:#6db8ff}.swal-content__textarea{resize:vertical}.swal-button--loading{color:transparent}.swal-button--loading~.swal-button__loader{opacity:1}.swal-button__loader{position:absolute;height:auto;width:43px;z-index:2;left:50%;top:50%;-webkit-transform:translateX(-50%) translateY(-50%);transform:translateX(-50%) translateY(-50%);text-align:center;pointer-events:none;opacity:0}.swal-button__loader div{display:inline-block;float:none;vertical-align:baseline;width:9px;height:9px;padding:0;border:none;margin:2px;opacity:.4;border-radius:7px;background-color:hsla(0,0%,100%,.9);transition:background .2s;-webkit-animation:swal-loading-anim 1s infinite;animation:swal-loading-anim 1s infinite}.swal-button__loader div:nth-child(3n+2){-webkit-animation-delay:.15s;animation-delay:.15s}.swal-button__loader div:nth-child(3n+3){-webkit-animation-delay:.3s;animation-delay:.3s}@-webkit-keyframes swal-loading-anim{0%{opacity:.4}20%{opacity:.4}50%{opacity:1}to{opacity:.4}}@keyframes swal-loading-anim{0%{opacity:.4}20%{opacity:.4}50%{opacity:1}to{opacity:.4}}.swal-overlay{position:fixed;top:0;bottom:0;left:0;right:0;text-align:center;font-size:0;overflow-y:auto;background-color:rgba(0,0,0,.4);z-index:10000;pointer-events:none;opacity:0;transition:opacity .3s}.swal-overlay:before{content:" ";display:inline-block;vertical-align:middle;height:100%}.swal-overlay--show-modal{opacity:1;pointer-events:auto}.swal-overlay--show-modal .swal-modal{opacity:1;pointer-events:auto;box-sizing:border-box;-webkit-animation:showSweetAlert .3s;animation:showSweetAlert .3s;will-change:transform}.swal-modal{width:478px;opacity:0;pointer-events:none;background-color:#fff;text-align:center;border-radius:5px;position:static;margin:20px auto;display:inline-block;vertical-align:middle;-webkit-transform:scale(1);transform:scale(1);-webkit-transform-origin:50% 50%;transform-origin:50% 50%;z-index:10001;transition:opacity .2s,-webkit-transform .3s;transition:transform .3s,opacity .2s;transition:transform .3s,opacity .2s,-webkit-transform .3s}@media (max-width:500px){.swal-modal{width:calc(100% - 20px)}}@-webkit-keyframes showSweetAlert{0%{-webkit-transform:scale(1);transform:scale(1)}1%{-webkit-transform:scale(.5);transform:scale(.5)}45%{-webkit-transform:scale(1.05);transform:scale(1.05)}80%{-webkit-transform:scale(.95);transform:scale(.95)}to{-webkit-transform:scale(1);transform:scale(1)}}@keyframes showSweetAlert{0%{-webkit-transform:scale(1);transform:scale(1)}1%{-webkit-transform:scale(.5);transform:scale(.5)}45%{-webkit-transform:scale(1.05);transform:scale(1.05)}80%{-webkit-transform:scale(.95);transform:scale(.95)}to{-webkit-transform:scale(1);transform:scale(1)}}',""])},function(e,t){function n(e,t){var n=e[1]||"",i=e[3];if(!i)return n;if(t&&"function"==typeof btoa){var r=function(e){return"/*# sourceMappingURL=data:application/json;charset=utf-8;base64,"+btoa(unescape(encodeURIComponent(JSON.stringify(e))))+" */"}(i);return[n].concat(i.sources.map((function(e){return"/*# sourceURL="+i.sourceRoot+e+" */"}))).concat([r]).join("\n")}return[n].join("\n")}e.exports=function(e){var t=[];return t.toString=function(){return this.map((function(t){var i=n(t,e);return t[2]?"@media "+t[2]+"{"+i+"}":i})).join("")},t.i=function(e,n){"string"==typeof e&&(e=[[null,e,""]]);for(var i={},r=0;r<this.length;r++){var o=this[r][0];"number"==typeof o&&(i[o]=!0)}for(r=0;r<e.length;r++){var a=e[r];"number"==typeof a[0]&&i[a[0]]||(n&&!a[2]?a[2]=n:n&&(a[2]="("+a[2]+") and ("+n+")"),t.push(a))}},t}},function(e,t,n){function i(e,t){for(var n=0;n<e.length;n++){var i=e[n],r=p[i.id];if(r){r.refs++;for(var o=0;o<r.parts.length;o++)r.parts[o](i.parts[o]);for(;o<i.parts.length;o++)r.parts.push(c(i.parts[o],t))}else{var a=[];for(o=0;o<i.parts.length;o++)a.push(c(i.parts[o],t));p[i.id]={id:i.id,refs:1,parts:a}}}}function r(e,t){for(var n=[],i={},r=0;r<e.length;r++){var o=e[r],a=t.base?o[0]+t.base:o[0],s={css:o[1],media:o[2],sourceMap:o[3]};i[a]?i[a].parts.push(s):n.push(i[a]={id:a,parts:[s]})}return n}function o(e,t){var n=g(e.insertInto);if(!n)throw new Error("Couldn't find a style target. This probably means that the value for the 'insertInto' parameter is invalid.");var i=b[b.length-1];if("top"===e.insertAt)i?i.nextSibling?n.insertBefore(t,i.nextSibling):n.appendChild(t):n.insertBefore(t,n.firstChild),b.push(t);else{if("bottom"!==e.insertAt)throw new Error("Invalid value for parameter 'insertAt'. Must be 'top' or 'bottom'.");n.appendChild(t)}}function a(e){if(null===e.parentNode)return!1;e.parentNode.removeChild(e);var t=b.indexOf(e);t>=0&&b.splice(t,1)}function s(e){var t=document.createElement("style");return e.attrs.type="text/css",u(t,e.attrs),o(e,t),t}function l(e){var t=document.createElement("link");return e.attrs.type="text/css",e.attrs.rel="stylesheet",u(t,e.attrs),o(e,t),t}function u(e,t){Object.keys(t).forEach((function(n){e.setAttribute(n,t[n])}))}function c(e,t){var n,i,r,o;if(t.transform&&e.css){if(!(o=t.transform(e.css)))return function(){};e.css=o}if(t.singleton){var u=y++;n=v||(v=s(t)),i=d.bind(null,n,u,!1),r=d.bind(null,n,u,!0)}else e.sourceMap&&"function"==typeof URL&&"function"==typeof URL.createObjectURL&&"function"==typeof URL.revokeObjectURL&&"function"==typeof Blob&&"function"==typeof btoa?(n=l(t),i=h.bind(null,n,t),r=function(){a(n),n.href&&URL.revokeObjectURL(n.href)}):(n=s(t),i=f.bind(null,n),r=function(){a(n)});return i(e),function(t){if(t){if(t.css===e.css&&t.media===e.media&&t.sourceMap===e.sourceMap)return;i(e=t)}else r()}}function d(e,t,n,i){var r=n?"":i.css;if(e.styleSheet)e.styleSheet.cssText=w(t,r);else{var o=document.createTextNode(r),a=e.childNodes;a[t]&&e.removeChild(a[t]),a.length?e.insertBefore(o,a[t]):e.appendChild(o)}}function f(e,t){var n=t.css,i=t.media;if(i&&e.setAttribute("media",i),e.styleSheet)e.styleSheet.cssText=n;else{for(;e.firstChild;)e.removeChild(e.firstChild);e.appendChild(document.createTextNode(n))}}function h(e,t,n){var i=n.css,r=n.sourceMap,o=void 0===t.convertToAbsoluteUrls&&r;(t.convertToAbsoluteUrls||o)&&(i=_(i)),r&&(i+="\n/*# sourceMappingURL=data:application/json;base64,"+btoa(unescape(encodeURIComponent(JSON.stringify(r))))+" */");var a=new Blob([i],{type:"text/css"}),s=e.href;e.href=URL.createObjectURL(a),s&&URL.revokeObjectURL(s)}var p={},m=function(e){var t;return function(){return void 0===t&&(t=e.apply(this,arguments)),t}}((function(){return window&&document&&document.all&&!window.atob})),g=function(e){var t={};return function(n){return void 0===t[n]&&(t[n]=e.call(this,n)),t[n]}}((function(e){return document.querySelector(e)})),v=null,y=0,b=[],_=n(15);e.exports=function(e,t){if("undefined"!=typeof DEBUG&&DEBUG&&"object"!=typeof document)throw new Error("The style-loader cannot be used in a non-browser environment");(t=t||{}).attrs="object"==typeof t.attrs?t.attrs:{},t.singleton||(t.singleton=m()),t.insertInto||(t.insertInto="head"),t.insertAt||(t.insertAt="bottom");var n=r(e,t);return i(n,t),function(e){for(var o=[],a=0;a<n.length;a++){var s=n[a];(l=p[s.id]).refs--,o.push(l)}e&&i(r(e,t),t);for(a=0;a<o.length;a++){var l;if(0===(l=o[a]).refs){for(var u=0;u<l.parts.length;u++)l.parts[u]();delete p[l.id]}}}};var w=function(){var e=[];return function(t,n){return e[t]=n,e.filter(Boolean).join("\n")}}()},function(e,t){e.exports=function(e){var t="undefined"!=typeof window&&window.location;if(!t)throw new Error("fixUrls requires window.location");if(!e||"string"!=typeof e)return e;var n=t.protocol+"//"+t.host,i=n+t.pathname.replace(/\/[^\/]*$/,"/");return e.replace(/url\s*\(((?:[^)(]|\((?:[^)(]+|\([^)(]*\))*\))*)\)/gi,(function(e,t){var r,o=t.trim().replace(/^"(.*)"$/,(function(e,t){return t})).replace(/^'(.*)'$/,(function(e,t){return t}));return/^(#|data:|http:\/\/|https:\/\/|file:\/\/\/)/i.test(o)?e:(r=0===o.indexOf("//")?o:0===o.indexOf("/")?n+o:i+o.replace(/^\.\//,""),"url("+JSON.stringify(r)+")")}))}},function(e,t,n){var i=n(17);"undefined"==typeof window||window.Promise||(window.Promise=i),n(21),String.prototype.includes||(String.prototype.includes=function(e,t){"use strict";return"number"!=typeof t&&(t=0),!(t+e.length>this.length)&&-1!==this.indexOf(e,t)}),Array.prototype.includes||Object.defineProperty(Array.prototype,"includes",{value:function(e,t){if(null==this)throw new TypeError('"this" is null or not defined');var n=Object(this),i=n.length>>>0;if(0===i)return!1;for(var r=0|t,o=Math.max(r>=0?r:i-Math.abs(r),0);o<i;){if(function(e,t){return e===t||"number"==typeof e&&"number"==typeof t&&isNaN(e)&&isNaN(t)}(n[o],e))return!0;o++}return!1}}),"undefined"!=typeof window&&[Element.prototype,CharacterData.prototype,DocumentType.prototype].forEach((function(e){e.hasOwnProperty("remove")||Object.defineProperty(e,"remove",{configurable:!0,enumerable:!0,writable:!0,value:function(){this.parentNode.removeChild(this)}})}))},function(e,t,n){(function(t){!function(n){function i(){}function r(e){if("object"!=typeof this)throw new TypeError("Promises must be constructed via new");if("function"!=typeof e)throw new TypeError("not a function");this._state=0,this._handled=!1,this._value=void 0,this._deferreds=[],c(e,this)}function o(e,t){for(;3===e._state;)e=e._value;0!==e._state?(e._handled=!0,r._immediateFn((function(){var n=1===e._state?t.onFulfilled:t.onRejected;if(null!==n){var i;try{i=n(e._value)}catch(e){return void s(t.promise,e)}a(t.promise,i)}else(1===e._state?a:s)(t.promise,e._value)}))):e._deferreds.push(t)}function a(e,t){try{if(t===e)throw new TypeError("A promise cannot be resolved with itself.");if(t&&("object"==typeof t||"function"==typeof t)){var n=t.then;if(t instanceof r)return e._state=3,e._value=t,void l(e);if("function"==typeof n)return void c(function(e,t){return function(){e.apply(t,arguments)}}(n,t),e)}e._state=1,e._value=t,l(e)}catch(t){s(e,t)}}function s(e,t){e._state=2,e._value=t,l(e)}function l(e){2===e._state&&0===e._deferreds.length&&r._immediateFn((function(){e._handled||r._unhandledRejectionFn(e._value)}));for(var t=0,n=e._deferreds.length;t<n;t++)o(e,e._deferreds[t]);e._deferreds=null}function u(e,t,n){this.onFulfilled="function"==typeof e?e:null,this.onRejected="function"==typeof t?t:null,this.promise=n}function c(e,t){var n=!1;try{e((function(e){n||(n=!0,a(t,e))}),(function(e){n||(n=!0,s(t,e))}))}catch(e){if(n)return;n=!0,s(t,e)}}var d=setTimeout;r.prototype.catch=function(e){return this.then(null,e)},r.prototype.then=function(e,t){var n=new this.constructor(i);return o(this,new u(e,t,n)),n},r.all=function(e){var t=Array.prototype.slice.call(e);return new r((function(e,n){function i(o,a){try{if(a&&("object"==typeof a||"function"==typeof a)){var s=a.then;if("function"==typeof s)return void s.call(a,(function(e){i(o,e)}),n)}t[o]=a,0==--r&&e(t)}catch(e){n(e)}}if(0===t.length)return e([]);for(var r=t.length,o=0;o<t.length;o++)i(o,t[o])}))},r.resolve=function(e){return e&&"object"==typeof e&&e.constructor===r?e:new r((function(t){t(e)}))},r.reject=function(e){return new r((function(t,n){n(e)}))},r.race=function(e){return new r((function(t,n){for(var i=0,r=e.length;i<r;i++)e[i].then(t,n)}))},r._immediateFn="function"==typeof t&&function(e){t(e)}||function(e){d(e,0)},r._unhandledRejectionFn=function(e){"undefined"!=typeof console&&console&&console.warn("Possible Unhandled Promise Rejection:",e)},r._setImmediateFn=function(e){r._immediateFn=e},r._setUnhandledRejectionFn=function(e){r._unhandledRejectionFn=e},void 0!==e&&e.exports?e.exports=r:n.Promise||(n.Promise=r)}(this)}).call(t,n(18).setImmediate)},function(e,t,n){function i(e,t){this._id=e,this._clearFn=t}var r=Function.prototype.apply;t.setTimeout=function(){return new i(r.call(setTimeout,window,arguments),clearTimeout)},t.setInterval=function(){return new i(r.call(setInterval,window,arguments),clearInterval)},t.clearTimeout=t.clearInterval=function(e){e&&e.close()},i.prototype.unref=i.prototype.ref=function(){},i.prototype.close=function(){this._clearFn.call(window,this._id)},t.enroll=function(e,t){clearTimeout(e._idleTimeoutId),e._idleTimeout=t},t.unenroll=function(e){clearTimeout(e._idleTimeoutId),e._idleTimeout=-1},t._unrefActive=t.active=function(e){clearTimeout(e._idleTimeoutId);var t=e._idleTimeout;t>=0&&(e._idleTimeoutId=setTimeout((function(){e._onTimeout&&e._onTimeout()}),t))},n(19),t.setImmediate=setImmediate,t.clearImmediate=clearImmediate},function(e,t,n){(function(e,t){!function(e,n){"use strict";function i(e){delete s[e]}function r(e){if(l)setTimeout(r,0,e);else{var t=s[e];if(t){l=!0;try{!function(e){var t=e.callback,n=e.args;switch(n.length){case 0:t();break;case 1:t(n[0]);break;case 2:t(n[0],n[1]);break;case 3:t(n[0],n[1],n[2]);break;default:t.apply(void 0,n)}}(t)}finally{i(e),l=!1}}}}if(!e.setImmediate){var o,a=1,s={},l=!1,u=e.document,c=Object.getPrototypeOf&&Object.getPrototypeOf(e);c=c&&c.setTimeout?c:e,"[object process]"==={}.toString.call(e.process)?o=function(e){t.nextTick((function(){r(e)}))}:function(){if(e.postMessage&&!e.importScripts){var t=!0,n=e.onmessage;return e.onmessage=function(){t=!1},e.postMessage("","*"),e.onmessage=n,t}}()?function(){var t="setImmediate$"+Math.random()+"$",n=function(n){n.source===e&&"string"==typeof n.data&&0===n.data.indexOf(t)&&r(+n.data.slice(t.length))};e.addEventListener?e.addEventListener("message",n,!1):e.attachEvent("onmessage",n),o=function(n){e.postMessage(t+n,"*")}}():e.MessageChannel?function(){var e=new MessageChannel;e.port1.onmessage=function(e){r(e.data)},o=function(t){e.port2.postMessage(t)}}():u&&"onreadystatechange"in u.createElement("script")?function(){var e=u.documentElement;o=function(t){var n=u.createElement("script");n.onreadystatechange=function(){r(t),n.onreadystatechange=null,e.removeChild(n),n=null},e.appendChild(n)}}():o=function(e){setTimeout(r,0,e)},c.setImmediate=function(e){"function"!=typeof e&&(e=new Function(""+e));for(var t=new Array(arguments.length-1),n=0;n<t.length;n++)t[n]=arguments[n+1];var i={callback:e,args:t};return s[a]=i,o(a),a++},c.clearImmediate=i}}("undefined"==typeof self?void 0===e?this:e:self)}).call(t,n(7),n(20))},function(e,t){function n(){throw new Error("setTimeout has not been defined")}function i(){throw new Error("clearTimeout has not been defined")}function r(e){if(u===setTimeout)return setTimeout(e,0);if((u===n||!u)&&setTimeout)return u=setTimeout,setTimeout(e,0);try{return u(e,0)}catch(t){try{return u.call(null,e,0)}catch(t){return u.call(this,e,0)}}}function o(){p&&f&&(p=!1,f.length?h=f.concat(h):m=-1,h.length&&a())}function a(){if(!p){var e=r(o);p=!0;for(var t=h.length;t;){for(f=h,h=[];++m<t;)f&&f[m].run();m=-1,t=h.length}f=null,p=!1,function(e){if(c===clearTimeout)return clearTimeout(e);if((c===i||!c)&&clearTimeout)return c=clearTimeout,clearTimeout(e);try{c(e)}catch(t){try{return c.call(null,e)}catch(t){return c.call(this,e)}}}(e)}}function s(e,t){this.fun=e,this.array=t}function l(){}var u,c,d=e.exports={};!function(){try{u="function"==typeof setTimeout?setTimeout:n}catch(e){u=n}try{c="function"==typeof clearTimeout?clearTimeout:i}catch(e){c=i}}();var f,h=[],p=!1,m=-1;d.nextTick=function(e){var t=new Array(arguments.length-1);if(arguments.length>1)for(var n=1;n<arguments.length;n++)t[n-1]=arguments[n];h.push(new s(e,t)),1!==h.length||p||r(a)},s.prototype.run=function(){this.fun.apply(null,this.array)},d.title="browser",d.browser=!0,d.env={},d.argv=[],d.version="",d.versions={},d.on=l,d.addListener=l,d.once=l,d.off=l,d.removeListener=l,d.removeAllListeners=l,d.emit=l,d.prependListener=l,d.prependOnceListener=l,d.listeners=function(e){return[]},d.binding=function(e){throw new Error("process.binding is not supported")},d.cwd=function(){return"/"},d.chdir=function(e){throw new Error("process.chdir is not supported")},d.umask=function(){return 0}},function(e,t,n){"use strict";n(22).polyfill()},function(e,t,n){"use strict";function i(e,t){if(null==e)throw new TypeError("Cannot convert first argument to object");for(var n=Object(e),i=1;i<arguments.length;i++){var r=arguments[i];if(null!=r)for(var o=Object.keys(Object(r)),a=0,s=o.length;a<s;a++){var l=o[a],u=Object.getOwnPropertyDescriptor(r,l);void 0!==u&&u.enumerable&&(n[l]=r[l])}}return n}e.exports={assign:i,polyfill:function(){Object.assign||Object.defineProperty(Object,"assign",{enumerable:!1,configurable:!0,writable:!0,value:i})}}},function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var i=n(24),r=n(6),o=n(5),a=n(36),s=function(){for(var e=[],t=0;t<arguments.length;t++)e[t]=arguments[t];if("undefined"!=typeof window){var n=a.getOpts.apply(void 0,e);return new Promise((function(e,t){o.default.promise={resolve:e,reject:t},i.default(n),setTimeout((function(){r.openModal()}))}))}};s.close=r.onAction,s.getState=r.getState,s.setActionValue=o.setActionValue,s.stopLoading=r.stopLoading,s.setDefaults=a.setDefaults,t.default=s},function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var i=n(1),r=n(0).default.MODAL,o=n(4),a=n(34),s=n(35),l=n(1);t.init=function(e){i.getNode(r)||(document.body||l.throwErr("You can only use SweetAlert AFTER the DOM has loaded!"),a.default(),o.default()),o.initModalContent(e),s.default(e)},t.default=t.init},function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var i=n(0).default.MODAL;t.modalMarkup='\n  <div class="'+i+'" role="dialog" aria-modal="true"></div>',t.default=t.modalMarkup},function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var i='<div \n    class="'+n(0).default.OVERLAY+'"\n    tabIndex="-1">\n  </div>';t.default=i},function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var i=n(0).default.ICON;t.errorIconMarkup=function(){var e=i+"--error",t=e+"__line";return'\n    <div class="'+e+'__x-mark">\n      <span class="'+t+" "+t+'--left"></span>\n      <span class="'+t+" "+t+'--right"></span>\n    </div>\n  '},t.warningIconMarkup=function(){var e=i+"--warning";return'\n    <span class="'+e+'__body">\n      <span class="'+e+'__dot"></span>\n    </span>\n  '},t.successIconMarkup=function(){var e=i+"--success";return'\n    <span class="'+e+"__line "+e+'__line--long"></span>\n    <span class="'+e+"__line "+e+'__line--tip"></span>\n\n    <div class="'+e+'__ring"></div>\n    <div class="'+e+'__hide-corners"></div>\n  '}},function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var i=n(0).default.CONTENT;t.contentMarkup='\n  <div class="'+i+'">\n\n  </div>\n'},function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var i=n(0),r=i.default.BUTTON_CONTAINER,o=i.default.BUTTON,a=i.default.BUTTON_LOADER;t.buttonMarkup='\n  <div class="'+r+'">\n\n    <button\n      class="'+o+'"\n    ></button>\n\n    <div class="'+a+'">\n      <div></div>\n      <div></div>\n      <div></div>\n    </div>\n\n  </div>\n'},function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var i=n(4),r=n(2),o=n(0),a=o.default.ICON,s=o.default.ICON_CUSTOM,l=["error","warning","success","info"],u={error:r.errorIconMarkup(),warning:r.warningIconMarkup(),success:r.successIconMarkup()};t.default=function(e){if(e){var t=i.injectElIntoModal(r.iconMarkup);l.includes(e)?function(e,t){var n=a+"--"+e;t.classList.add(n);var i=u[e];i&&(t.innerHTML=i)}(e,t):function(e,t){t.classList.add(s);var n=document.createElement("img");n.src=e,t.appendChild(n)}(e,t)}}},function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var i=n(2),r=n(4),o=function(e){navigator.userAgent.includes("AppleWebKit")&&(e.style.display="none",e.offsetHeight,e.style.display="")};t.initTitle=function(e){if(e){var t=r.injectElIntoModal(i.titleMarkup);t.textContent=e,o(t)}},t.initText=function(e){if(e){var t=document.createDocumentFragment();e.split("\n").forEach((function(e,n,i){t.appendChild(document.createTextNode(e)),n<i.length-1&&t.appendChild(document.createElement("br"))}));var n=r.injectElIntoModal(i.textMarkup);n.appendChild(t),o(n)}}},function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var i=n(1),r=n(4),o=n(0),a=o.default.BUTTON,s=o.default.DANGER_BUTTON,l=n(3),u=n(2),c=n(6),d=n(5),f=function(e,t,n){var r=t.text,o=t.value,f=t.className,h=t.closeModal,p=i.stringToNode(u.buttonMarkup),m=p.querySelector("."+a),g=a+"--"+e;m.classList.add(g),f&&(Array.isArray(f)?f:f.split(" ")).filter((function(e){return e.length>0})).forEach((function(e){m.classList.add(e)})),n&&e===l.CONFIRM_KEY&&m.classList.add(s),m.textContent=r;var v={};return v[e]=o,d.setActionValue(v),d.setActionOptionsFor(e,{closeModal:h}),m.addEventListener("click",(function(){return c.onAction(e)})),p};t.default=function(e,t){var n=r.injectElIntoModal(u.footerMarkup);for(var i in e){var o=e[i],a=f(i,o,t);o.visible&&n.appendChild(a)}0===n.children.length&&n.remove()}},function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var i=n(3),r=n(4),o=n(2),a=n(5),s=n(6),l=n(0).default.CONTENT,u=function(e){e.addEventListener("input",(function(e){var t=e.target.value;a.setActionValue(t)})),e.addEventListener("keyup",(function(e){if("Enter"===e.key)return s.onAction(i.CONFIRM_KEY)})),setTimeout((function(){e.focus(),a.setActionValue("")}),0)};t.default=function(e){if(e){var t=r.injectElIntoModal(o.contentMarkup),n=e.element,i=e.attributes;"string"==typeof n?function(e,t,n){var i=document.createElement(t),r=l+"__"+t;for(var o in i.classList.add(r),n){var a=n[o];i[o]=a}"input"===t&&u(i),e.appendChild(i)}(t,n,i):t.appendChild(n)}}},function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var i=n(1),r=n(2);t.default=function(){var e=i.stringToNode(r.overlayMarkup);document.body.appendChild(e)}},function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var i=n(5),r=n(6),o=n(1),a=n(3),s=n(0),l=s.default.MODAL,u=s.default.BUTTON,c=s.default.OVERLAY,d=function(e){if(i.default.isOpen)switch(e.key){case"Escape":return r.onAction(a.CANCEL_KEY)}},f=function(e){if(i.default.isOpen)switch(e.key){case"Tab":return function(e){e.preventDefault(),p()}(e)}},h=function(e){if(i.default.isOpen)return"Tab"===e.key&&e.shiftKey?function(e){e.preventDefault(),m()}(e):void 0},p=function(){var e=o.getNode(u);e&&(e.tabIndex=0,e.focus())},m=function(){var e=o.getNode(l).querySelectorAll("."+u),t=e[e.length-1];t&&t.focus()},g=function(){var e=o.getNode(l).querySelectorAll("."+u);e.length&&(function(e){e[e.length-1].addEventListener("keydown",f)}(e),function(e){e[0].addEventListener("keydown",h)}(e))},v=function(e){if(o.getNode(c)===e.target)return r.onAction(a.CANCEL_KEY)};t.default=function(e){e.closeOnEsc?document.addEventListener("keyup",d):document.removeEventListener("keyup",d),e.dangerMode?p():m(),g(),function(e){var t=o.getNode(c);t.removeEventListener("click",v),e&&t.addEventListener("click",v)}(e.closeOnClickOutside),function(e){i.default.timer&&clearTimeout(i.default.timer),e&&(i.default.timer=window.setTimeout((function(){return r.onAction(a.CANCEL_KEY)}),e))}(e.timer)}},function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var i=n(1),r=n(3),o=n(37),a=n(38),s={title:null,text:null,icon:null,buttons:r.defaultButtonList,content:null,className:null,closeOnClickOutside:!0,closeOnEsc:!0,dangerMode:!1,timer:null},l=Object.assign({},s);t.setDefaults=function(e){l=Object.assign({},s,e)};var u=function(e){var t=e&&e.button,n=e&&e.buttons;return void 0!==t&&void 0!==n&&i.throwErr("Cannot set both 'button' and 'buttons' options!"),void 0!==t?{confirm:t}:n},c=function(e){return i.ordinalSuffixOf(e+1)},d=function(e,t){i.throwErr(c(t)+" argument ('"+e+"') is invalid")},f=function(e,t){var n=e+1,r=t[n];i.isPlainObject(r)||void 0===r||i.throwErr("Expected "+c(n)+" argument ('"+r+"') to be a plain object")},h=function(e,t,n,r){var o=t instanceof Element;if("string"===typeof t){if(0===n)return{text:t};if(1===n)return{text:t,title:r[0]};if(2===n)return f(n,r),{icon:t};d(t,n)}else{if(o&&0===n)return f(n,r),{content:t};if(i.isPlainObject(t))return function(e,t){var n=e+1,r=t[n];void 0!==r&&i.throwErr("Unexpected "+c(n)+" argument ("+r+")")}(n,r),t;d(t,n)}};t.getOpts=function(){for(var e=[],t=0;t<arguments.length;t++)e[t]=arguments[t];var n={};e.forEach((function(t,i){var r=h(0,t,i,e);Object.assign(n,r)}));var i=u(n);n.buttons=r.getButtonListOpts(i),delete n.button,n.content=o.getContentOpts(n.content);var c=Object.assign({},s,l,n);return Object.keys(c).forEach((function(e){a.DEPRECATED_OPTS[e]&&a.logDeprecation(e)})),c}},function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var i=n(1),r={element:"input",attributes:{placeholder:""}};t.getContentOpts=function(e){return i.isPlainObject(e)?Object.assign({},e):e instanceof Element?{element:e}:"input"===e?r:null}},function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.logDeprecation=function(e){var n=t.DEPRECATED_OPTS[e],i=n.onlyRename,r=n.replacement,o=n.subOption,a=n.link,s='SweetAlert warning: "'+e+'" option has been '+(i?"renamed":"deprecated")+".";r&&(s+=" Please use"+(o?' "'+o+'" in ':" ")+'"'+r+'" instead.');var l="https://sweetalert.js.org";s+=a?" More details: "+l+a:" More details: "+l+"/guides/#upgrading-from-1x",console.warn(s)},t.DEPRECATED_OPTS={type:{replacement:"icon",link:"/docs/#icon"},imageUrl:{replacement:"icon",link:"/docs/#icon"},customClass:{replacement:"className",onlyRename:!0,link:"/docs/#classname"},imageSize:{},showCancelButton:{replacement:"buttons",link:"/docs/#buttons"},showConfirmButton:{replacement:"button",link:"/docs/#button"},confirmButtonText:{replacement:"button",link:"/docs/#button"},confirmButtonColor:{},cancelButtonText:{replacement:"buttons",link:"/docs/#buttons"},closeOnConfirm:{replacement:"button",subOption:"closeModal",link:"/docs/#button"},closeOnCancel:{replacement:"buttons",subOption:"closeModal",link:"/docs/#buttons"},showLoaderOnConfirm:{replacement:"buttons"},animation:{},inputType:{replacement:"content",link:"/docs/#content"},inputValue:{replacement:"content",link:"/docs/#content"},inputPlaceholder:{replacement:"content",link:"/docs/#content"},html:{replacement:"content",link:"/docs/#content"},allowEscapeKey:{replacement:"closeOnEsc",onlyRename:!0,link:"/docs/#closeonesc"},allowClickOutside:{replacement:"closeOnClickOutside",onlyRename:!0,link:"/docs/#closeonclickoutside"}}}])})),function(){var e={initialize:function(){this.registerEvents()},registerEvents:function(){$("body").on("click","a[data-method]",this.handleMethod)},handleMethod:function(t){var n=$(this),i=n.data("method").toUpperCase();-1!==$.inArray(i,["PUT","DELETE"])&&(n.data("confirm-text")&&e.verifyConfirm(n,(function(t){if(!t)return!1;e.createForm(n).submit()})),t.preventDefault())},verifyConfirm:function(e,t){swal({title:e.data("confirm-title"),text:e.data("confirm-text"),type:"warning",button:e.data("confirm-delete")?e.data("confirm-delete"):"Yes, delete it!"}).then((function(e){t(e)}))},getCsrfToken:function(){return $('meta[name="csrf-token"]').attr("content")},createForm:function(t){var n=$("<form>",{method:"POST",action:t.attr("href")}),i=$("<input>",{name:"_token",type:"hidden",value:e.getCsrfToken()}),r=$("<input>",{name:"_method",type:"hidden",value:t.data("method")});return n.append(i,r).appendTo("body")}};e.initialize()}(),function(e){if("function"==typeof define&&define.amd)define(e);else if("object"==typeof exports)module.exports=e();else{var t=window.Cookies,n=window.Cookies=e();n.noConflict=function(){return window.Cookies=t,n}}}((function(){function e(){for(var e=0,t={};e<arguments.length;e++){var n=arguments[e];for(var i in n)t[i]=n[i]}return t}return function t(n){function i(t,r,o){var a;if(arguments.length>1){if("number"==typeof(o=e({path:"/"},i.defaults,o)).expires){var s=new Date;s.setMilliseconds(s.getMilliseconds()+864e5*o.expires),o.expires=s}try{a=JSON.stringify(r),/^[\{\[]/.test(a)&&(r=a)}catch(e){}return r=n.write?n.write(r,t):encodeURIComponent(String(r)).replace(/%(23|24|26|2B|3A|3C|3E|3D|2F|3F|40|5B|5D|5E|60|7B|7D|7C)/g,decodeURIComponent),t=(t=(t=encodeURIComponent(String(t))).replace(/%(23|24|26|2B|5E|60|7C)/g,decodeURIComponent)).replace(/[\(\)]/g,escape),document.cookie=[t,"=",r,o.expires&&"; expires="+o.expires.toUTCString(),o.path&&"; path="+o.path,o.domain&&"; domain="+o.domain,o.secure?"; secure":""].join("")}t||(a={});for(var l=document.cookie?document.cookie.split("; "):[],u=/(%[0-9A-Z]{2})+/g,c=0;c<l.length;c++){var d=l[c].split("="),f=d[0].replace(u,decodeURIComponent),h=d.slice(1).join("=");'"'===h.charAt(0)&&(h=h.slice(1,-1));try{if(h=n.read?n.read(h,f):n(h,f)||h.replace(u,decodeURIComponent),this.json)try{h=JSON.parse(h)}catch(e){}if(t===f){a=h;break}t||(a[f]=h)}catch(e){}}return a}return i.get=i.set=i,i.getJSON=function(){return i.apply({json:!0},[].slice.call(arguments))},i.defaults={},i.remove=function(t,n){i(t,"",e(n,{expires:-1}))},i.withConverter=t,i}((function(){}))})),function(e){"function"==typeof define&&define.amd?define(["jquery"],e):e(jQuery)}((function(e){e.extend(e.fn,{validate:function(t){if(this.length){var n=e.data(this[0],"validator");return n||(this.attr("novalidate","novalidate"),n=new e.validator(t,this[0]),e.data(this[0],"validator",n),n.settings.onsubmit&&(this.on("click.validate",":submit",(function(t){n.settings.submitHandler&&(n.submitButton=t.target),e(this).hasClass("cancel")&&(n.cancelSubmit=!0),void 0!==e(this).attr("formnovalidate")&&(n.cancelSubmit=!0)})),this.on("submit.validate",(function(t){function i(){var i,r;return!n.settings.submitHandler||(n.submitButton&&(i=e("<input type='hidden'/>").attr("name",n.submitButton.name).val(e(n.submitButton).val()).appendTo(n.currentForm)),r=n.settings.submitHandler.call(n,n.currentForm,t),n.submitButton&&i.remove(),void 0!==r&&r)}return n.settings.debug&&t.preventDefault(),n.cancelSubmit?(n.cancelSubmit=!1,i()):n.form()?n.pendingRequest?(n.formSubmitted=!0,!1):i():(n.focusInvalid(),!1)}))),n)}t&&t.debug&&window.console&&console.warn("Nothing selected, can't validate, returning nothing.")},valid:function(){var t,n,i;return e(this[0]).is("form")?t=this.validate().form():(i=[],t=!0,n=e(this[0].form).validate(),this.each((function(){t=n.element(this)&&t,i=i.concat(n.errorList)})),n.errorList=i),t},rules:function(t,n){var i,r,o,a,s,l,u=this[0];if(t)switch(r=(i=e.data(u.form,"validator").settings).rules,o=e.validator.staticRules(u),t){case"add":e.extend(o,e.validator.normalizeRule(n)),delete o.messages,r[u.name]=o,n.messages&&(i.messages[u.name]=e.extend(i.messages[u.name],n.messages));break;case"remove":return n?(l={},e.each(n.split(/\s/),(function(t,n){l[n]=o[n],delete o[n],"required"===n&&e(u).removeAttr("aria-required")})),l):(delete r[u.name],o)}return(a=e.validator.normalizeRules(e.extend({},e.validator.classRules(u),e.validator.attributeRules(u),e.validator.dataRules(u),e.validator.staticRules(u)),u)).required&&(s=a.required,delete a.required,a=e.extend({required:s},a),e(u).attr("aria-required","true")),a.remote&&(s=a.remote,delete a.remote,a=e.extend(a,{remote:s})),a}}),e.extend(e.expr[":"],{blank:function(t){return!e.trim(""+e(t).val())},filled:function(t){return!!e.trim(""+e(t).val())},unchecked:function(t){return!e(t).prop("checked")}}),e.validator=function(t,n){this.settings=e.extend(!0,{},e.validator.defaults,t),this.currentForm=n,this.init()},e.validator.format=function(t,n){return 1===arguments.length?function(){var n=e.makeArray(arguments);return n.unshift(t),e.validator.format.apply(this,n)}:(arguments.length>2&&n.constructor!==Array&&(n=e.makeArray(arguments).slice(1)),n.constructor!==Array&&(n=[n]),e.each(n,(function(e,n){t=t.replace(new RegExp("\\{"+e+"\\}","g"),(function(){return n}))})),t)},e.extend(e.validator,{defaults:{messages:{},groups:{},rules:{},errorClass:"error",validClass:"valid",errorElement:"label",focusCleanup:!1,focusInvalid:!0,errorContainer:e([]),errorLabelContainer:e([]),onsubmit:!0,ignore:":hidden",ignoreTitle:!1,onfocusin:function(e){this.lastActive=e,this.settings.focusCleanup&&(this.settings.unhighlight&&this.settings.unhighlight.call(this,e,this.settings.errorClass,this.settings.validClass),this.hideThese(this.errorsFor(e)))},onfocusout:function(e){this.checkable(e)||!(e.name in this.submitted)&&this.optional(e)||this.element(e)},onkeyup:function(t,n){9===n.which&&""===this.elementValue(t)||-1!==e.inArray(n.keyCode,[16,17,18,20,35,36,37,38,39,40,45,144,225])||(t.name in this.submitted||t===this.lastElement)&&this.element(t)},onclick:function(e){e.name in this.submitted?this.element(e):e.parentNode.name in this.submitted&&this.element(e.parentNode)},highlight:function(t,n,i){"radio"===t.type?this.findByName(t.name).addClass(n).removeClass(i):e(t).addClass(n).removeClass(i)},unhighlight:function(t,n,i){"radio"===t.type?this.findByName(t.name).removeClass(n).addClass(i):e(t).removeClass(n).addClass(i)}},setDefaults:function(t){e.extend(e.validator.defaults,t)},messages:{required:"This field is required.",remote:"Please fix this field.",email:"Please enter a valid email address.",url:"Please enter a valid URL.",date:"Please enter a valid date.",dateISO:"Please enter a valid date ( ISO ).",number:"Please enter a valid number.",digits:"Please enter only digits.",creditcard:"Please enter a valid credit card number.",equalTo:"Please enter the same value again.",maxlength:e.validator.format("Please enter no more than {0} characters."),minlength:e.validator.format("Please enter at least {0} characters."),rangelength:e.validator.format("Please enter a value between {0} and {1} characters long."),range:e.validator.format("Please enter a value between {0} and {1}."),max:e.validator.format("Please enter a value less than or equal to {0}."),min:e.validator.format("Please enter a value greater than or equal to {0}.")},autoCreateRanges:!1,prototype:{init:function(){this.labelContainer=e(this.settings.errorLabelContainer),this.errorContext=this.labelContainer.length&&this.labelContainer||e(this.currentForm),this.containers=e(this.settings.errorContainer).add(this.settings.errorLabelContainer),this.submitted={},this.valueCache={},this.pendingRequest=0,this.pending={},this.invalid={},this.reset();var t,n=this.groups={};function i(t){var n=e.data(this.form,"validator"),i="on"+t.type.replace(/^validate/,""),r=n.settings;r[i]&&!e(this).is(r.ignore)&&r[i].call(n,this,t)}e.each(this.settings.groups,(function(t,i){"string"==typeof i&&(i=i.split(/\s/)),e.each(i,(function(e,i){n[i]=t}))})),t=this.settings.rules,e.each(t,(function(n,i){t[n]=e.validator.normalizeRule(i)})),e(this.currentForm).on("focusin.validate focusout.validate keyup.validate",":text, [type='password'], [type='file'], select, textarea, [type='number'], [type='search'], [type='tel'], [type='url'], [type='email'], [type='datetime'], [type='date'], [type='month'], [type='week'], [type='time'], [type='datetime-local'], [type='range'], [type='color'], [type='radio'], [type='checkbox']",i).on("click.validate","select, option, [type='radio'], [type='checkbox']",i),this.settings.invalidHandler&&e(this.currentForm).on("invalid-form.validate",this.settings.invalidHandler),e(this.currentForm).find("[required], [data-rule-required], .required").attr("aria-required","true")},form:function(){return this.checkForm(),e.extend(this.submitted,this.errorMap),this.invalid=e.extend({},this.errorMap),this.valid()||e(this.currentForm).triggerHandler("invalid-form",[this]),this.showErrors(),this.valid()},checkForm:function(){this.prepareForm();for(var e=0,t=this.currentElements=this.elements();t[e];e++)this.check(t[e]);return this.valid()},element:function(t){var n=this.clean(t),i=this.validationTargetFor(n),r=!0;return this.lastElement=i,void 0===i?delete this.invalid[n.name]:(this.prepareElement(i),this.currentElements=e(i),(r=!1!==this.check(i))?delete this.invalid[i.name]:this.invalid[i.name]=!0),e(t).attr("aria-invalid",!r),this.numberOfInvalids()||(this.toHide=this.toHide.add(this.containers)),this.showErrors(),r},showErrors:function(t){if(t){for(var n in e.extend(this.errorMap,t),this.errorList=[],t)this.errorList.push({message:t[n],element:this.findByName(n)[0]});this.successList=e.grep(this.successList,(function(e){return!(e.name in t)}))}this.settings.showErrors?this.settings.showErrors.call(this,this.errorMap,this.errorList):this.defaultShowErrors()},resetForm:function(){e.fn.resetForm&&e(this.currentForm).resetForm(),this.submitted={},this.lastElement=null,this.prepareForm(),this.hideErrors();var t,n=this.elements().removeData("previousValue").removeAttr("aria-invalid");if(this.settings.unhighlight)for(t=0;n[t];t++)this.settings.unhighlight.call(this,n[t],this.settings.errorClass,"");else n.removeClass(this.settings.errorClass)},numberOfInvalids:function(){return this.objectLength(this.invalid)},objectLength:function(e){var t,n=0;for(t in e)n++;return n},hideErrors:function(){this.hideThese(this.toHide)},hideThese:function(e){e.not(this.containers).text(""),this.addWrapper(e).hide()},valid:function(){return 0===this.size()},size:function(){return this.errorList.length},focusInvalid:function(){if(this.settings.focusInvalid)try{e(this.findLastActive()||this.errorList.length&&this.errorList[0].element||[]).filter(":visible").focus().trigger("focusin")}catch(e){}},findLastActive:function(){var t=this.lastActive;return t&&1===e.grep(this.errorList,(function(e){return e.element.name===t.name})).length&&t},elements:function(){var t=this,n={};return e(this.currentForm).find("input, select, textarea").not(":submit, :reset, :image, :disabled").not(this.settings.ignore).filter((function(){return!this.name&&t.settings.debug&&window.console&&console.error("%o has no name assigned",this),!(this.name in n||!t.objectLength(e(this).rules()))&&(n[this.name]=!0,!0)}))},clean:function(t){return e(t)[0]},errors:function(){var t=this.settings.errorClass.split(" ").join(".");return e(this.settings.errorElement+"."+t,this.errorContext)},reset:function(){this.successList=[],this.errorList=[],this.errorMap={},this.toShow=e([]),this.toHide=e([]),this.currentElements=e([])},prepareForm:function(){this.reset(),this.toHide=this.errors().add(this.containers)},prepareElement:function(e){this.reset(),this.toHide=this.errorsFor(e)},elementValue:function(t){var n,i=e(t),r=t.type;return"radio"===r||"checkbox"===r?this.findByName(t.name).filter(":checked").val():"number"===r&&void 0!==t.validity?!t.validity.badInput&&i.val():"string"==typeof(n=i.val())?n.replace(/\r/g,""):n},check:function(t){t=this.validationTargetFor(this.clean(t));var n,i,r,o=e(t).rules(),a=e.map(o,(function(e,t){return t})).length,s=!1,l=this.elementValue(t);for(i in o){r={method:i,parameters:o[i]};try{if("dependency-mismatch"===(n=e.validator.methods[i].call(this,l,t,r.parameters))&&1===a){s=!0;continue}if(s=!1,"pending"===n)return void(this.toHide=this.toHide.not(this.errorsFor(t)));if(!n)return this.formatAndAdd(t,r),!1}catch(e){throw this.settings.debug&&window.console&&console.log("Exception occurred when checking element "+t.id+", check the '"+r.method+"' method.",e),e instanceof TypeError&&(e.message+=".  Exception occurred when checking element "+t.id+", check the '"+r.method+"' method."),e}}if(!s)return this.objectLength(o)&&this.successList.push(t),!0},customDataMessage:function(t,n){return e(t).data("msg"+n.charAt(0).toUpperCase()+n.substring(1).toLowerCase())||e(t).data("msg")},customMessage:function(e,t){var n=this.settings.messages[e];return n&&(n.constructor===String?n:n[t])},findDefined:function(){for(var e=0;e<arguments.length;e++)if(void 0!==arguments[e])return arguments[e]},defaultMessage:function(t,n){return this.findDefined(this.customMessage(t.name,n),this.customDataMessage(t,n),!this.settings.ignoreTitle&&t.title||void 0,e.validator.messages[n],"<strong>Warning: No message defined for "+t.name+"</strong>")},formatAndAdd:function(t,n){var i=this.defaultMessage(t,n.method),r=/\$?\{(\d+)\}/g;"function"==typeof i?i=i.call(this,n.parameters,t):r.test(i)&&(i=e.validator.format(i.replace(r,"{$1}"),n.parameters)),this.errorList.push({message:i,element:t,method:n.method}),this.errorMap[t.name]=i,this.submitted[t.name]=i},addWrapper:function(e){return this.settings.wrapper&&(e=e.add(e.parent(this.settings.wrapper))),e},defaultShowErrors:function(){var e,t,n;for(e=0;this.errorList[e];e++)n=this.errorList[e],this.settings.highlight&&this.settings.highlight.call(this,n.element,this.settings.errorClass,this.settings.validClass),this.showLabel(n.element,n.message);if(this.errorList.length&&(this.toShow=this.toShow.add(this.containers)),this.settings.success)for(e=0;this.successList[e];e++)this.showLabel(this.successList[e]);if(this.settings.unhighlight)for(e=0,t=this.validElements();t[e];e++)this.settings.unhighlight.call(this,t[e],this.settings.errorClass,this.settings.validClass);this.toHide=this.toHide.not(this.toShow),this.hideErrors(),this.addWrapper(this.toShow).show()},validElements:function(){return this.currentElements.not(this.invalidElements())},invalidElements:function(){return e(this.errorList).map((function(){return this.element}))},showLabel:function(t,n){var i,r,o,a=this.errorsFor(t),s=this.idOrName(t),l=e(t).attr("aria-describedby");a.length?(a.removeClass(this.settings.validClass).addClass(this.settings.errorClass),a.html(n)):(i=a=e("<"+this.settings.errorElement+">").attr("id",s+"-error").addClass(this.settings.errorClass).html(n||""),this.settings.wrapper&&(i=a.hide().show().wrap("<"+this.settings.wrapper+"/>").parent()),this.labelContainer.length?this.labelContainer.append(i):this.settings.errorPlacement?this.settings.errorPlacement(i,e(t)):i.insertAfter(t),a.is("label")?a.attr("for",s):0===a.parents("label[for='"+s+"']").length&&(o=a.attr("id").replace(/(:|\.|\[|\]|\$)/g,"\\$1"),l?l.match(new RegExp("\\b"+o+"\\b"))||(l+=" "+o):l=o,e(t).attr("aria-describedby",l),(r=this.groups[t.name])&&e.each(this.groups,(function(t,n){n===r&&e("[name='"+t+"']",this.currentForm).attr("aria-describedby",a.attr("id"))})))),!n&&this.settings.success&&(a.text(""),"string"==typeof this.settings.success?a.addClass(this.settings.success):this.settings.success(a,t)),this.toShow=this.toShow.add(a)},errorsFor:function(t){var n=this.idOrName(t),i=e(t).attr("aria-describedby"),r="label[for='"+n+"'], label[for='"+n+"'] *";return i&&(r=r+", #"+i.replace(/\s+/g,", #")),this.errors().filter(r)},idOrName:function(e){return this.groups[e.name]||(this.checkable(e)?e.name:e.id||e.name)},validationTargetFor:function(t){return this.checkable(t)&&(t=this.findByName(t.name)),e(t).not(this.settings.ignore)[0]},checkable:function(e){return/radio|checkbox/i.test(e.type)},findByName:function(t){return e(this.currentForm).find("[name='"+t+"']")},getLength:function(t,n){switch(n.nodeName.toLowerCase()){case"select":return e("option:selected",n).length;case"input":if(this.checkable(n))return this.findByName(n.name).filter(":checked").length}return t.length},depend:function(e,t){return!this.dependTypes[typeof e]||this.dependTypes[typeof e](e,t)},dependTypes:{boolean:function(e){return e},string:function(t,n){return!!e(t,n.form).length},function:function(e,t){return e(t)}},optional:function(t){var n=this.elementValue(t);return!e.validator.methods.required.call(this,n,t)&&"dependency-mismatch"},startRequest:function(e){this.pending[e.name]||(this.pendingRequest++,this.pending[e.name]=!0)},stopRequest:function(t,n){this.pendingRequest--,this.pendingRequest<0&&(this.pendingRequest=0),delete this.pending[t.name],n&&0===this.pendingRequest&&this.formSubmitted&&this.form()?(e(this.currentForm).submit(),this.formSubmitted=!1):!n&&0===this.pendingRequest&&this.formSubmitted&&(e(this.currentForm).triggerHandler("invalid-form",[this]),this.formSubmitted=!1)},previousValue:function(t){return e.data(t,"previousValue")||e.data(t,"previousValue",{old:null,valid:!0,message:this.defaultMessage(t,"remote")})},destroy:function(){this.resetForm(),e(this.currentForm).off(".validate").removeData("validator")}},classRuleSettings:{required:{required:!0},email:{email:!0},url:{url:!0},date:{date:!0},dateISO:{dateISO:!0},number:{number:!0},digits:{digits:!0},creditcard:{creditcard:!0}},addClassRules:function(t,n){t.constructor===String?this.classRuleSettings[t]=n:e.extend(this.classRuleSettings,t)},classRules:function(t){var n={},i=e(t).attr("class");return i&&e.each(i.split(" "),(function(){this in e.validator.classRuleSettings&&e.extend(n,e.validator.classRuleSettings[this])})),n},normalizeAttributeRule:function(e,t,n,i){/min|max/.test(n)&&(null===t||/number|range|text/.test(t))&&(i=Number(i),isNaN(i)&&(i=void 0)),i||0===i?e[n]=i:t===n&&"range"!==t&&(e[n]=!0)},attributeRules:function(t){var n,i,r={},o=e(t),a=t.getAttribute("type");for(n in e.validator.methods)"required"===n?(""===(i=t.getAttribute(n))&&(i=!0),i=!!i):i=o.attr(n),this.normalizeAttributeRule(r,a,n,i);return r.maxlength&&/-1|2147483647|524288/.test(r.maxlength)&&delete r.maxlength,r},dataRules:function(t){var n,i,r={},o=e(t),a=t.getAttribute("type");for(n in e.validator.methods)i=o.data("rule"+n.charAt(0).toUpperCase()+n.substring(1).toLowerCase()),this.normalizeAttributeRule(r,a,n,i);return r},staticRules:function(t){var n={},i=e.data(t.form,"validator");return i.settings.rules&&(n=e.validator.normalizeRule(i.settings.rules[t.name])||{}),n},normalizeRules:function(t,n){return e.each(t,(function(i,r){if(!1!==r){if(r.param||r.depends){var o=!0;switch(typeof r.depends){case"string":o=!!e(r.depends,n.form).length;break;case"function":o=r.depends.call(n,n)}o?t[i]=void 0===r.param||r.param:delete t[i]}}else delete t[i]})),e.each(t,(function(i,r){t[i]=e.isFunction(r)?r(n):r})),e.each(["minlength","maxlength"],(function(){t[this]&&(t[this]=Number(t[this]))})),e.each(["rangelength","range"],(function(){var n;t[this]&&(e.isArray(t[this])?t[this]=[Number(t[this][0]),Number(t[this][1])]:"string"==typeof t[this]&&(n=t[this].replace(/[\[\]]/g,"").split(/[\s,]+/),t[this]=[Number(n[0]),Number(n[1])]))})),e.validator.autoCreateRanges&&(null!=t.min&&null!=t.max&&(t.range=[t.min,t.max],delete t.min,delete t.max),null!=t.minlength&&null!=t.maxlength&&(t.rangelength=[t.minlength,t.maxlength],delete t.minlength,delete t.maxlength)),t},normalizeRule:function(t){if("string"==typeof t){var n={};e.each(t.split(/\s/),(function(){n[this]=!0})),t=n}return t},addMethod:function(t,n,i){e.validator.methods[t]=n,e.validator.messages[t]=void 0!==i?i:e.validator.messages[t],n.length<3&&e.validator.addClassRules(t,e.validator.normalizeRule(t))},methods:{required:function(t,n,i){if(!this.depend(i,n))return"dependency-mismatch";if("select"===n.nodeName.toLowerCase()){var r=e(n).val();return r&&r.length>0}return this.checkable(n)?this.getLength(t,n)>0:t.length>0},email:function(e,t){return this.optional(t)||/^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/.test(e)},url:function(e,t){return this.optional(t)||/^(?:(?:(?:https?|ftp):)?\/\/)(?:\S+(?::\S*)?@)?(?:(?!(?:10|127)(?:\.\d{1,3}){3})(?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})).?)(?::\d{2,5})?(?:[/?#]\S*)?$/i.test(e)},date:function(e,t){return this.optional(t)||!/Invalid|NaN/.test(new Date(e).toString())},dateISO:function(e,t){return this.optional(t)||/^\d{4}[\/\-](0?[1-9]|1[012])[\/\-](0?[1-9]|[12][0-9]|3[01])$/.test(e)},number:function(e,t){return this.optional(t)||/^(?:-?\d+|-?\d{1,3}(?:,\d{3})+)?(?:\.\d+)?$/.test(e)},digits:function(e,t){return this.optional(t)||/^\d+$/.test(e)},creditcard:function(e,t){if(this.optional(t))return"dependency-mismatch";if(/[^0-9 \-]+/.test(e))return!1;var n,i,r=0,o=0,a=!1;if((e=e.replace(/\D/g,"")).length<13||e.length>19)return!1;for(n=e.length-1;n>=0;n--)i=e.charAt(n),o=parseInt(i,10),a&&(o*=2)>9&&(o-=9),r+=o,a=!a;return r%10==0},minlength:function(t,n,i){var r=e.isArray(t)?t.length:this.getLength(t,n);return this.optional(n)||r>=i},maxlength:function(t,n,i){var r=e.isArray(t)?t.length:this.getLength(t,n);return this.optional(n)||r<=i},rangelength:function(t,n,i){var r=e.isArray(t)?t.length:this.getLength(t,n);return this.optional(n)||r>=i[0]&&r<=i[1]},min:function(e,t,n){return this.optional(t)||e>=n},max:function(e,t,n){return this.optional(t)||e<=n},range:function(e,t,n){return this.optional(t)||e>=n[0]&&e<=n[1]},equalTo:function(t,n,i){var r=e(i);return this.settings.onfocusout&&r.off(".validate-equalTo").on("blur.validate-equalTo",(function(){e(n).valid()})),t===r.val()},remote:function(t,n,i){if(this.optional(n))return"dependency-mismatch";var r,o,a=this.previousValue(n);return this.settings.messages[n.name]||(this.settings.messages[n.name]={}),a.originalMessage=this.settings.messages[n.name].remote,this.settings.messages[n.name].remote=a.message,i="string"==typeof i&&{url:i}||i,a.old===t?a.valid:(a.old=t,r=this,this.startRequest(n),(o={})[n.name]=t,e.ajax(e.extend(!0,{mode:"abort",port:"validate"+n.name,dataType:"json",data:o,context:r.currentForm,success:function(i){var o,s,l,u=!0===i||"true"===i;r.settings.messages[n.name].remote=a.originalMessage,u?(l=r.formSubmitted,r.prepareElement(n),r.formSubmitted=l,r.successList.push(n),delete r.invalid[n.name],r.showErrors()):(o={},s=i||r.defaultMessage(n,"remote"),o[n.name]=a.message=e.isFunction(s)?s(t):s,r.invalid[n.name]=!0,r.showErrors(o)),a.valid=u,r.stopRequest(n,u)}},i)),"pending")}}});var t,n={};e.ajaxPrefilter?e.ajaxPrefilter((function(e,t,i){var r=e.port;"abort"===e.mode&&(n[r]&&n[r].abort(),n[r]=i)})):(t=e.ajax,e.ajax=function(i){var r=("mode"in i?i:e.ajaxSettings).mode,o=("port"in i?i:e.ajaxSettings).port;return"abort"===r?(n[o]&&n[o].abort(),n[o]=t.apply(this,arguments),n[o]):t.apply(this,arguments)})})),function(){"use strict";var e,t,n,i,r,o;o=864e5,e=function(e,t){return"string"==typeof e&&"string"==typeof t&&e.toLowerCase()===t.toLowerCase()},t=function(e,n,i){var r=e.toString();return i=i||"0",r.length<n?t(i+r,n):r},n=function(e){var t,i;for(e=e||{},t=1;t<arguments.length;t++)if(i=arguments[t])for(var r in i)i.hasOwnProperty(r)&&("object"==typeof i[r]?n(e[r],i[r]):e[r]=i[r]);return e},i=function(e,t){for(var n=0;n<t.length;n++)if(t[n].toLowerCase()===e.toLowerCase())return n;return-1},r={dateSettings:{days:["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"],daysShort:["Sun","Mon","Tue","Wed","Thu","Fri","Sat"],months:["January","February","March","April","May","June","July","August","September","October","November","December"],monthsShort:["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],meridiem:["AM","PM"],ordinal:function(e){var t=e%10,n={1:"st",2:"nd",3:"rd"};return 1!==Math.floor(e%100/10)&&n[t]?n[t]:"th"}},separators:/[ \-+\/\.T:@]/g,validParts:/[dDjlNSwzWFmMntLoYyaABgGhHisueTIOPZcrU]/g,intParts:/[djwNzmnyYhHgGis]/g,tzParts:/\b(?:[PMCEA][SDP]T|(?:Pacific|Mountain|Central|Eastern|Atlantic) (?:Standard|Daylight|Prevailing) Time|(?:GMT|UTC)(?:[-+]\d{4})?)\b/g,tzClip:/[^-+\dA-Z]/g},(DateFormatter=function(e){var t=this,i=n(r,e);t.dateSettings=i.dateSettings,t.separators=i.separators,t.validParts=i.validParts,t.intParts=i.intParts,t.tzParts=i.tzParts,t.tzClip=i.tzClip}).prototype={constructor:DateFormatter,getMonth:function(e){var t;return 0===(t=i(e,this.dateSettings.monthsShort)+1)&&(t=i(e,this.dateSettings.months)+1),t},parseDate:function(t,n){var i,r,o,a,s,l,u,c,d,f,h=this,p=!1,m=!1,g=h.dateSettings,v={date:null,year:null,month:null,day:null,hour:0,min:0,sec:0};if(!t)return null;if(t instanceof Date)return t;if("U"===n)return(o=parseInt(t))?new Date(1e3*o):t;switch(typeof t){case"number":return new Date(t);case"string":break;default:return null}if(!(i=n.match(h.validParts))||0===i.length)throw new Error("Invalid date format definition.");for(r=t.replace(h.separators,"\0").split("\0"),o=0;o<r.length;o++)switch(a=r[o],s=parseInt(a),i[o]){case"y":case"Y":if(!s)return null;d=a.length,v.year=2===d?parseInt((s<70?"20":"19")+a):s,p=!0;break;case"m":case"n":case"M":case"F":if(isNaN(s)){if(!((l=h.getMonth(a))>0))return null;v.month=l}else{if(!(s>=1&&s<=12))return null;v.month=s}p=!0;break;case"d":case"j":if(!(s>=1&&s<=31))return null;v.day=s,p=!0;break;case"g":case"h":if(f=r[u=i.indexOf("a")>-1?i.indexOf("a"):i.indexOf("A")>-1?i.indexOf("A"):-1],-1!==u)c=e(f,g.meridiem[0])?0:e(f,g.meridiem[1])?12:-1,s>=1&&s<=12&&-1!==c?v.hour=s%12==0?c:s+c:s>=0&&s<=23&&(v.hour=s);else{if(!(s>=0&&s<=23))return null;v.hour=s}m=!0;break;case"G":case"H":if(!(s>=0&&s<=23))return null;v.hour=s,m=!0;break;case"i":if(!(s>=0&&s<=59))return null;v.min=s,m=!0;break;case"s":if(!(s>=0&&s<=59))return null;v.sec=s,m=!0}if(!0===p&&v.year&&v.month&&v.day)v.date=new Date(v.year,v.month-1,v.day,v.hour,v.min,v.sec,0);else{if(!0!==m)return null;v.date=new Date(0,0,0,v.hour,v.min,v.sec,0)}return v.date},guessDate:function(e,t){if("string"!=typeof e)return e;var n,i,r,o,a,s,l=e.replace(this.separators,"\0").split("\0"),u=t.match(this.validParts),c=new Date,d=0;if(!/^[djmn]/g.test(u[0]))return e;for(r=0;r<l.length;r++){if(d=2,a=l[r],s=parseInt(a.substr(0,2)),isNaN(s))return null;switch(r){case 0:"m"===u[0]||"n"===u[0]?c.setMonth(s-1):c.setDate(s);break;case 1:"m"===u[0]||"n"===u[0]?c.setDate(s):c.setMonth(s-1);break;case 2:if(i=c.getFullYear(),d=(n=a.length)<4?n:4,!(i=parseInt(n<4?i.toString().substr(0,4-n)+a:a.substr(0,4))))return null;c.setFullYear(i);break;case 3:c.setHours(s);break;case 4:c.setMinutes(s);break;case 5:c.setSeconds(s)}(o=a.substr(d)).length>0&&l.splice(r+1,0,o)}return c},parseFormat:function(e,n){var i,r=this,a=r.dateSettings,s=/\\?(.?)/gi,l=function(e,t){return i[e]?i[e]():t};return i={d:function(){return t(i.j(),2)},D:function(){return a.daysShort[i.w()]},j:function(){return n.getDate()},l:function(){return a.days[i.w()]},N:function(){return i.w()||7},w:function(){return n.getDay()},z:function(){var e=new Date(i.Y(),i.n()-1,i.j()),t=new Date(i.Y(),0,1);return Math.round((e-t)/o)},W:function(){var e=new Date(i.Y(),i.n()-1,i.j()-i.N()+3),n=new Date(e.getFullYear(),0,4);return t(1+Math.round((e-n)/o/7),2)},F:function(){return a.months[n.getMonth()]},m:function(){return t(i.n(),2)},M:function(){return a.monthsShort[n.getMonth()]},n:function(){return n.getMonth()+1},t:function(){return new Date(i.Y(),i.n(),0).getDate()},L:function(){var e=i.Y();return e%4==0&&e%100!=0||e%400==0?1:0},o:function(){var e=i.n(),t=i.W();return i.Y()+(12===e&&t<9?1:1===e&&t>9?-1:0)},Y:function(){return n.getFullYear()},y:function(){return i.Y().toString().slice(-2)},a:function(){return i.A().toLowerCase()},A:function(){var e=i.G()<12?0:1;return a.meridiem[e]},B:function(){var e=3600*n.getUTCHours(),i=60*n.getUTCMinutes(),r=n.getUTCSeconds();return t(Math.floor((e+i+r+3600)/86.4)%1e3,3)},g:function(){return i.G()%12||12},G:function(){return n.getHours()},h:function(){return t(i.g(),2)},H:function(){return t(i.G(),2)},i:function(){return t(n.getMinutes(),2)},s:function(){return t(n.getSeconds(),2)},u:function(){return t(1e3*n.getMilliseconds(),6)},e:function(){return/\((.*)\)/.exec(String(n))[1]||"Coordinated Universal Time"},I:function(){return new Date(i.Y(),0)-Date.UTC(i.Y(),0)!=new Date(i.Y(),6)-Date.UTC(i.Y(),6)?1:0},O:function(){var e=n.getTimezoneOffset(),i=Math.abs(e);return(e>0?"-":"+")+t(100*Math.floor(i/60)+i%60,4)},P:function(){var e=i.O();return e.substr(0,3)+":"+e.substr(3,2)},T:function(){return(String(n).match(r.tzParts)||[""]).pop().replace(r.tzClip,"")||"UTC"},Z:function(){return 60*-n.getTimezoneOffset()},c:function(){return"Y-m-d\\TH:i:sP".replace(s,l)},r:function(){return"D, d M Y H:i:s O".replace(s,l)},U:function(){return n.getTime()/1e3||0}},l(e,e)},formatDate:function(e,t){var n,i,r,o,a,s=this,l="";if("string"==typeof e&&!(e=s.parseDate(e,t)))return null;if(e instanceof Date){for(r=t.length,n=0;n<r;n++)"S"!==(a=t.charAt(n))&&"\\"!==a&&(n>0&&"\\"===t.charAt(n-1)?l+=a:(o=s.parseFormat(a,e),n!==r-1&&s.intParts.test(a)&&"S"===t.charAt(n+1)&&(i=parseInt(o)||0,o+=s.dateSettings.ordinal(i)),l+=o));return l}return""}}}(),laravelValidation={implicitRules:["Required","Confirmed"],init:function(){$.validator.classRuleSettings={},$.validator.attributeRules=function(){this.rules={}},$.validator.dataRules=this.arrayRules,$.validator.prototype.arrayRulesCache={},this.setupValidations()},arrayRules:function(e){var t={},n=$.data(e.form,"validator"),i=n.arrayRulesCache;return-1===e.name.indexOf("[")||(e.name in i||(i[e.name]={}),$.each(n.settings.rules,(function(n,r){if(n in i[e.name])$.extend(t,i[e.name][n]);else{i[e.name][n]={};var o=laravelValidation.helpers.regexFromWildcard(n);if(e.name.match(o)){var a=$.validator.normalizeRule(r)||{};i[e.name][n]=a,$.extend(t,a)}}}))),t},setupValidations:function(){$.validator.addMethod("laravelValidation",(function(e,t,n){var i=this,r=!0,o=this.previousValue(t),a=[];return $.each(n,(function(e,t){t[3]||-1!==laravelValidation.implicitRules.indexOf(t[0])?a.unshift(t):a.push(t)})),$.each(a,(function(n,a){var s=a[3]||-1!==laravelValidation.implicitRules.indexOf(a[0]),l=a[0],u=a[2];return!s&&i.optional(t)?(r="dependency-mismatch",!1):!0!==(r=void 0!==laravelValidation.methods[l]&&laravelValidation.methods[l].call(i,e,t,a[1],(function(n){if(i.settings.messages[t.name].laravelValidationRemote=o.originalMessage,n){var r=i.formSubmitted;i.prepareElement(t),i.formSubmitted=r,i.successList.push(t),delete i.invalid[t.name],i.showErrors()}else{var a={};a[t.name]=o.message=$.isFunction(u)?u(e):u,i.invalid[t.name]=!0,i.showErrors(a)}i.showErrors(i.errorMap),o.valid=n})))?(i.settings.messages[t.name]||(i.settings.messages[t.name]={}),i.settings.messages[t.name].laravelValidation=u,!1):void 0})),r}),""),$.validator.addMethod("laravelValidationRemote",(function(e,t,n){var i=!1,r=n[0][1],o=t.name,a=r[1],s=r[2];if($.each(n,(function(e,t){i=i||t[3]})),!i&&this.optional(t))return"dependency-mismatch";var l,u,c=this.previousValue(t);this.settings.messages[t.name]||(this.settings.messages[t.name]={}),c.originalMessage=this.settings.messages[t.name].laravelValidationRemote,this.settings.messages[t.name].laravelValidationRemote=c.message;var d="string"==typeof d&&{url:d}||d;if(c.old===e)return c.valid;c.old=e,l=this,this.startRequest(t),(u=$(l.currentForm).serializeArray()).push({name:"_jsvalidation",value:o}),u.push({name:"_jsvalidation_validate_all",value:s});var f=$(l.currentForm).attr("method");return $(l.currentForm).find('input[name="_method"]').length&&(f=$(l.currentForm).find('input[name="_method"]').val()),$.ajax($.extend(!0,{mode:"abort",port:"validate"+t.name,dataType:"json",data:u,context:l.currentForm,url:$(l.currentForm).attr("action"),type:f,beforeSend:function(e){if("get"!==$(l.currentForm).attr("method").toLowerCase()&&a)return e.setRequestHeader("X-XSRF-TOKEN",a)}},d)).always((function(n,i){var r,o,a,s;if("error"===i)s=!1,n=laravelValidation.helpers.parseErrorResponse(n);else{if("success"!==i)return;s=!0===n||"true"===n}l.settings.messages[t.name].laravelValidationRemote=c.originalMessage,s?(a=l.formSubmitted,l.prepareElement(t),l.formSubmitted=a,l.successList.push(t),delete l.invalid[t.name],l.showErrors()):(r={},o=n||l.defaultMessage(t,"remote"),r[t.name]=c.message=$.isFunction(o)?o(e):o[0],l.invalid[t.name]=!0,l.showErrors(r)),l.showErrors(l.errorMap),c.valid=s,l.stopRequest(t,s)})),"pending"}),"")}},$((function(){laravelValidation.init()})),$.extend(!0,laravelValidation,{helpers:{numericRules:["Integer","Numeric"],fileinfo:function(e,t){var n=e.value;return t=void 0!==t?t:0,null!==e.files&&void 0!==e.files[t]&&{file:n,extension:n.substr(n.lastIndexOf(".")+1),size:e.files[t].size/1024,type:e.files[t].type}},selector:function(e){var t=[];$.isArray(e)||(e=[e]);for(var n=0;n<e.length;n++)t.push("[name='"+e[n]+"']");return t.join()},hasNumericRules:function(e){return this.hasRules(e,this.numericRules)},hasRules:function(e,t){var n=!1;"string"==typeof t&&(t=[t]);var i=$.data(e.form,"validator"),r=[],o=i.arrayRulesCache;return e.name in o&&$.each(o[e.name],(function(e,t){r.push(t)})),e.name in i.settings.rules&&r.push(i.settings.rules[e.name]),$.each(r,(function(e,i){if("laravelValidation"in i)for(var r=i.laravelValidation,o=0;o<r.length;o++)if(-1!==$.inArray(r[o][0],t))return n=!0,!1})),n},strlen:function(e){return strlen(e)},getSize:function(e,t,n){return this.hasNumericRules(t)&&this.is_numeric(n)?parseFloat(n):$.isArray(n)?parseFloat(n.length):"file"===t.type?parseFloat(Math.floor(this.fileinfo(t).size)):parseFloat(this.strlen(n))},getLaravelValidation:function(e,t){var n=void 0;return $.each($.validator.staticRules(t),(function(t,i){"laravelValidation"===t&&$.each(i,(function(t,i){i[0]===e&&(n=i)}))})),n},parseTime:function(e,t){var n=!1,i=new DateFormatter;if("object"===$.type(t)){var r=this.getLaravelValidation("DateFormat",t);t=void 0!==r?r[1][0]:null}return null==t?n=this.strtotime(e):(n=i.parseDate(e,t))&&(n=Math.round(n.getTime()/1e3)),n},guessDate:function(e,t){return(new DateFormatter).guessDate(e,t)},strtotime:function(e,t){return strtotime(e,t)},is_numeric:function(e){return is_numeric(e)},arrayDiff:function(e,t){return array_diff(e,t)},dependentElement:function(e,t,n){var i=e.findByName(n);if(void 0!==i[0]&&e.settings.onfocusout){var r="blur";"SELECT"!==i[0].tagName&&"OPTION"!==i[0].tagName&&"checkbox"!==i[0].type&&"radio"!==i[0].type||(r="click");var o=".validate-laravelValidation";i.off(o).off(r+o+"-"+t.name).on(r+o+"-"+t.name,(function(){$(t).valid()}))}return i[0]},parseErrorResponse:function(e){var t=["Whoops, looks like something went wrong."];if("responseText"in e){var n=e.responseText.match(/<h1\s*>(.*)<\/h1\s*>/i);$.isArray(n)&&(t=[n[1]])}return t},escapeRegExp:function(e){return e.replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g,"\\$&")},regexFromWildcard:function(e){var t=e.split("[*]");1===t.length&&t.push("");var n=t.map((function(e,t){return t%2==0?e+="[":e="]"+e,laravelValidation.helpers.escapeRegExp(e)}));return new RegExp("^"+n.join(".*")+"$")}}}),$.extend(!0,laravelValidation,{helpers:{isTimezone:function(e){var t={africa:["abidjan","accra","addis_ababa","algiers","asmara","bamako","bangui","banjul","bissau","blantyre","brazzaville","bujumbura","cairo","casablanca","ceuta","conakry","dakar","dar_es_salaam","djibouti","douala","el_aaiun","freetown","gaborone","harare","johannesburg","juba","kampala","khartoum","kigali","kinshasa","lagos","libreville","lome","luanda","lubumbashi","lusaka","malabo","maputo","maseru","mbabane","mogadishu","monrovia","nairobi","ndjamena","niamey","nouakchott","ouagadougou","porto-novo","sao_tome","tripoli","tunis","windhoek"],america:["adak","anchorage","anguilla","antigua","araguaina","argentina/buenos_aires","argentina/catamarca","argentina/cordoba","argentina/jujuy","argentina/la_rioja","argentina/mendoza","argentina/rio_gallegos","argentina/salta","argentina/san_juan","argentina/san_luis","argentina/tucuman","argentina/ushuaia","aruba","asuncion","atikokan","bahia","bahia_banderas","barbados","belem","belize","blanc-sablon","boa_vista","bogota","boise","cambridge_bay","campo_grande","cancun","caracas","cayenne","cayman","chicago","chihuahua","costa_rica","creston","cuiaba","curacao","danmarkshavn","dawson","dawson_creek","denver","detroit","dominica","edmonton","eirunepe","el_salvador","fortaleza","glace_bay","godthab","goose_bay","grand_turk","grenada","guadeloupe","guatemala","guayaquil","guyana","halifax","havana","hermosillo","indiana/indianapolis","indiana/knox","indiana/marengo","indiana/petersburg","indiana/tell_city","indiana/vevay","indiana/vincennes","indiana/winamac","inuvik","iqaluit","jamaica","juneau","kentucky/louisville","kentucky/monticello","kralendijk","la_paz","lima","los_angeles","lower_princes","maceio","managua","manaus","marigot","martinique","matamoros","mazatlan","menominee","merida","metlakatla","mexico_city","miquelon","moncton","monterrey","montevideo","montreal","montserrat","nassau","new_york","nipigon","nome","noronha","north_dakota/beulah","north_dakota/center","north_dakota/new_salem","ojinaga","panama","pangnirtung","paramaribo","phoenix","port-au-prince","port_of_spain","porto_velho","puerto_rico","rainy_river","rankin_inlet","recife","regina","resolute","rio_branco","santa_isabel","santarem","santiago","santo_domingo","sao_paulo","scoresbysund","shiprock","sitka","st_barthelemy","st_johns","st_kitts","st_lucia","st_thomas","st_vincent","swift_current","tegucigalpa","thule","thunder_bay","tijuana","toronto","tortola","vancouver","whitehorse","winnipeg","yakutat","yellowknife"],antarctica:["casey","davis","dumontdurville","macquarie","mawson","mcmurdo","palmer","rothera","south_pole","syowa","vostok"],arctic:["longyearbyen"],asia:["aden","almaty","amman","anadyr","aqtau","aqtobe","ashgabat","baghdad","bahrain","baku","bangkok","beirut","bishkek","brunei","choibalsan","chongqing","colombo","damascus","dhaka","dili","dubai","dushanbe","gaza","harbin","hebron","ho_chi_minh","hong_kong","hovd","irkutsk","jakarta","jayapura","jerusalem","kabul","kamchatka","karachi","kashgar","kathmandu","khandyga","kolkata","krasnoyarsk","kuala_lumpur","kuching","kuwait","macau","magadan","makassar","manila","muscat","nicosia","novokuznetsk","novosibirsk","omsk","oral","phnom_penh","pontianak","pyongyang","qatar","qyzylorda","rangoon","riyadh","sakhalin","samarkand","seoul","shanghai","singapore","taipei","tashkent","tbilisi","tehran","thimphu","tokyo","ulaanbaatar","urumqi","ust-nera","vientiane","vladivostok","yakutsk","yekaterinburg","yerevan"],atlantic:["azores","bermuda","canary","cape_verde","faroe","madeira","reykjavik","south_georgia","st_helena","stanley"],australia:["adelaide","brisbane","broken_hill","currie","darwin","eucla","hobart","lindeman","lord_howe","melbourne","perth","sydney"],europe:["amsterdam","andorra","athens","belgrade","berlin","bratislava","brussels","bucharest","budapest","busingen","chisinau","copenhagen","dublin","gibraltar","guernsey","helsinki","isle_of_man","istanbul","jersey","kaliningrad","kiev","lisbon","ljubljana","london","luxembourg","madrid","malta","mariehamn","minsk","monaco","moscow","oslo","paris","podgorica","prague","riga","rome","samara","san_marino","sarajevo","simferopol","skopje","sofia","stockholm","tallinn","tirane","uzhgorod","vaduz","vatican","vienna","vilnius","volgograd","warsaw","zagreb","zaporozhye","zurich"],indian:["antananarivo","chagos","christmas","cocos","comoro","kerguelen","mahe","maldives","mauritius","mayotte","reunion"],pacific:["apia","auckland","chatham","chuuk","easter","efate","enderbury","fakaofo","fiji","funafuti","galapagos","gambier","guadalcanal","guam","honolulu","johnston","kiritimati","kosrae","kwajalein","majuro","marquesas","midway","nauru","niue","norfolk","noumea","pago_pago","palau","pitcairn","pohnpei","port_moresby","rarotonga","saipan","tahiti","tarawa","tongatapu","wake","wallis"],utc:[""]},n=e.split("/",2),i=n[0].toLowerCase(),r="";return n[1]&&(r=n[1].toLowerCase()),i in t&&(0===t[i].length||-1!==t[i].indexOf(r))}}}),$.extend(!0,laravelValidation,{methods:{helpers:laravelValidation.helpers,jsRemoteTimer:0,Sometimes:function(){return!0},Bail:function(){return!0},Nullable:function(){return!0},Filled:function(e,t){return $.validator.methods.required.call(this,e,t,!0)},Required:function(e,t){return $.validator.methods.required.call(this,e,t)},RequiredWith:function(e,t,n){var i=this,r=!1,o=this;return $.each(n,(function(e,n){var a=laravelValidation.helpers.dependentElement(o,t,n);r=r||void 0!==a&&$.validator.methods.required.call(i,o.elementValue(a),a,!0)})),!r||$.validator.methods.required.call(this,e,t,!0)},RequiredWithAll:function(e,t,n){var i=this,r=!0,o=this;return $.each(n,(function(e,n){var a=laravelValidation.helpers.dependentElement(o,t,n);r=r&&void 0!==a&&$.validator.methods.required.call(i,o.elementValue(a),a,!0)})),!r||$.validator.methods.required.call(this,e,t,!0)},RequiredWithout:function(e,t,n){var i=this,r=!1,o=this;return $.each(n,(function(e,n){var a=laravelValidation.helpers.dependentElement(o,t,n);r=r||void 0===a||!$.validator.methods.required.call(i,o.elementValue(a),a,!0)})),!r||$.validator.methods.required.call(this,e,t,!0)},RequiredWithoutAll:function(e,t,n){var i=this,r=!0,o=this;return $.each(n,(function(e,n){var a=laravelValidation.helpers.dependentElement(o,t,n);r=r&&(void 0===a||!$.validator.methods.required.call(i,o.elementValue(a),a,!0))})),!r||$.validator.methods.required.call(this,e,t,!0)},RequiredIf:function(e,t,n){var i=laravelValidation.helpers.dependentElement(this,t,n[0]);if(void 0!==i){var r=String(this.elementValue(i));if(void 0!==r){var o=n.slice(1);if(-1!==$.inArray(r,o))return $.validator.methods.required.call(this,e,t,!0)}}return!0},RequiredUnless:function(e,t,n){var i=laravelValidation.helpers.dependentElement(this,t,n[0]);if(void 0!==i){var r=String(this.elementValue(i));if(void 0!==r){var o=n.slice(1);if(-1!==$.inArray(r,o))return!0}}return $.validator.methods.required.call(this,e,t,!0)},Confirmed:function(e,t,n){return laravelValidation.methods.Same.call(this,e,t,n)},Same:function(e,t,n){var i=laravelValidation.helpers.dependentElement(this,t,n[0]);return void 0!==i&&String(e)===String(this.elementValue(i))},InArray:function(e,t,n){if(void 0===n[0])return!1;for(var i=this.elements(),r=!1,o=laravelValidation.helpers.regexFromWildcard(n[0]),a=0;a<i.length;a++){var s=i[a].name;if(s.match(o)){var l=laravelValidation.methods.Same.call(this,e,t,[s]);r=r||l}}return r},Distinct:function(e,t,n){if(void 0===n[0])return!1;for(var i=this.elements(),r=!1,o=laravelValidation.helpers.regexFromWildcard(n[0]),a=0;a<i.length;a++){var s=i[a].name;if(s!==t.name&&s.match(o)){var l=laravelValidation.methods.Same.call(this,e,t,[s]);r=r||l}}return!r},Different:function(e,t,n){return!laravelValidation.methods.Same.call(this,e,t,n)},Accepted:function(e){return new RegExp("^(?:(yes|on|1|true))$","i").test(e)},Array:function(e){return $.isArray(e)},Boolean:function(e){return new RegExp("^(?:(true|false|1|0))$","i").test(e)},Integer:function(e){return new RegExp("^(?:-?\\d+)$","i").test(e)},Numeric:function(e,t){return $.validator.methods.number.call(this,e,t,!0)},String:function(e){return"string"==typeof e},Digits:function(e,t,n){return $.validator.methods.number.call(this,e,t,!0)&&e.length===parseInt(n,10)},DigitsBetween:function(e,t,n){return $.validator.methods.number.call(this,e,t,!0)&&e.length>=parseFloat(n[0])&&e.length<=parseFloat(n[1])},Size:function(e,t,n){return laravelValidation.helpers.getSize(this,t,e)===parseFloat(n[0])},Between:function(e,t,n){return laravelValidation.helpers.getSize(this,t,e)>=parseFloat(n[0])&&laravelValidation.helpers.getSize(this,t,e)<=parseFloat(n[1])},Min:function(e,t,n){return laravelValidation.helpers.getSize(this,t,e)>=parseFloat(n[0])},Max:function(e,t,n){return laravelValidation.helpers.getSize(this,t,e)<=parseFloat(n[0])},In:function(e,t,n){if($.isArray(e)&&laravelValidation.helpers.hasRules(t,"Array")){var i=laravelValidation.helpers.arrayDiff(e,n);return 0===Object.keys(i).length}return-1!==n.indexOf(e.toString())},NotIn:function(e,t,n){return-1===n.indexOf(e.toString())},Ip:function(e){return/^(25[0-5]|2[0-4]\d|[01]?\d\d?)\.(25[0-5]|2[0-4]\d|[01]?\d\d?)\.(25[0-5]|2[0-4]\d|[01]?\d\d?)\.(25[0-5]|2[0-4]\d|[01]?\d\d?)$/i.test(e)||/^((([0-9A-Fa-f]{1,4}:){7}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){6}:[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){5}:([0-9A-Fa-f]{1,4}:)?[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){4}:([0-9A-Fa-f]{1,4}:){0,2}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){3}:([0-9A-Fa-f]{1,4}:){0,3}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){2}:([0-9A-Fa-f]{1,4}:){0,4}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){6}((\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b)\.){3}(\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b))|(([0-9A-Fa-f]{1,4}:){0,5}:((\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b)\.){3}(\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b))|(::([0-9A-Fa-f]{1,4}:){0,5}((\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b)\.){3}(\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b))|([0-9A-Fa-f]{1,4}::([0-9A-Fa-f]{1,4}:){0,5}[0-9A-Fa-f]{1,4})|(::([0-9A-Fa-f]{1,4}:){0,6}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){1,7}:))$/i.test(e)},Email:function(e,t){return $.validator.methods.email.call(this,e,t,!0)},Url:function(e,t){return $.validator.methods.url.call(this,e,t,!0)},File:function(e,t){return!(window.File&&window.FileReader&&window.FileList&&window.Blob)||"files"in t&&t.files.length>0},Mimes:function(e,t,n){if(!(window.File&&window.FileReader&&window.FileList&&window.Blob))return!0;var i=$.map(n,(function(e){return e.toLowerCase()})),r=laravelValidation.helpers.fileinfo(t);return!1!==r&&-1!==i.indexOf(r.extension.toLowerCase())},Mimetypes:function(e,t,n){if(!(window.File&&window.FileReader&&window.FileList&&window.Blob))return!0;var i=$.map(n,(function(e){return e.toLowerCase()})),r=laravelValidation.helpers.fileinfo(t);return!1!==r&&-1!==i.indexOf(r.type.toLowerCase())},Image:function(e,t){return laravelValidation.methods.Mimes.call(this,e,t,["jpg","png","gif","bmp","svg","jpeg"])},Dimensions:function(value,element,params,callback){if(!(window.File&&window.FileReader&&window.FileList&&window.Blob))return!0;if(null===element.files||void 0===element.files[0])return!1;var fr=new FileReader;return fr.onload=function(){var img=new Image;img.onload=function(){var height=parseFloat(img.naturalHeight),width=parseFloat(img.naturalWidth),ratio=width/height,notValid=params.width&&parseFloat(params.width!==width)||params.min_width&&parseFloat(params.min_width)>width||params.max_width&&parseFloat(params.max_width)<width||params.height&&parseFloat(params.height)!==height||params.min_height&&parseFloat(params.min_height)>height||params.max_height&&parseFloat(params.max_height)<height||params.ratio&&ratio!==parseFloat(eval(params.ratio));callback(!notValid)},img.onerror=function(){callback(!1)},img.src=fr.result},fr.readAsDataURL(element.files[0]),"pending"},Alpha:function(e){return"string"==typeof e&&new RegExp("^(?:^[a-zà-ü]+$)$","i").test(e)},AlphaNum:function(e){return"string"==typeof e&&new RegExp("^(?:^[a-z0-9à-ü]+$)$","i").test(e)},AlphaDash:function(e){return"string"==typeof e&&new RegExp("^(?:^[a-z0-9à-ü_-]+$)$","i").test(e)},Regex:function(e,t,n){var i=["x","s","u","X","U","A"],r=new RegExp("^(?:/)(.*\\/?[^/]*|[^/]*)(?:/)([gmixXsuUAJ]*)?$"),o=n[0].match(r);if(null===o)return!1;var a=[];if(void 0!==o[2]){a=o[2].split("");for(var s=0;s<a.length<s;s++)if(-1!==i.indexOf(a[s]))return!0}return new RegExp("^(?:"+o[1]+")$",a.join()).test(e)},Date:function(e){return!1!==laravelValidation.helpers.strtotime(e)},DateFormat:function(e,t,n){return!1!==laravelValidation.helpers.parseTime(e,n[0])},Before:function(e,t,n){var i=parseFloat(n);if(isNaN(i)){var r=laravelValidation.helpers.dependentElement(this,t,n);if(void 0===r)return!1;i=laravelValidation.helpers.parseTime(this.elementValue(r),r)}var o=laravelValidation.helpers.parseTime(e,t);return!1!==o&&o<i},After:function(e,t,n){var i=parseFloat(n);if(isNaN(i)){var r=laravelValidation.helpers.dependentElement(this,t,n);if(void 0===r)return!1;i=laravelValidation.helpers.parseTime(this.elementValue(r),r)}var o=laravelValidation.helpers.parseTime(e,t);return!1!==o&&o>i},Timezone:function(e){return laravelValidation.helpers.isTimezone(e)},Json:function(e){var t=!0;try{JSON.parse(e)}catch(e){t=!1}return t}}}),function(e){"function"==typeof define&&define.amd?define(["jquery"],e):e("object"==typeof exports?require("jquery"):jQuery)}((function(e,t){function n(){return new Date(Date.UTC.apply(Date,arguments))}function i(){var e=new Date;return n(e.getFullYear(),e.getMonth(),e.getDate())}function r(e){return function(){return this[e].apply(this,arguments)}}function o(t,n){function i(e,t){return t.toLowerCase()}var r=e(t).data(),o={},a=new RegExp("^"+n.toLowerCase()+"([A-Z])");for(var s in n=new RegExp("^"+n.toLowerCase()),r)n.test(s)&&(o[s.replace(a,i)]=r[s]);return o}function a(t){var n={};if(p[t]||(t=t.split("-")[0],p[t])){var i=p[t];return e.each(h,(function(e,t){t in i&&(n[t]=i[t])})),n}}var s=function(){var t={get:function(e){return this.slice(e)[0]},contains:function(e){for(var t=e&&e.valueOf(),n=0,i=this.length;i>n;n++)if(this[n].valueOf()===t)return n;return-1},remove:function(e){this.splice(e,1)},replace:function(t){t&&(e.isArray(t)||(t=[t]),this.clear(),this.push.apply(this,t))},clear:function(){this.length=0},copy:function(){var e=new s;return e.replace(this),e}};return function(){var n=[];return n.push.apply(n,arguments),e.extend(n,t),n}}(),l=function(t,n){e(t).data("datepicker",this),this._process_options(n),this.dates=new s,this.viewDate=this.o.defaultViewDate,this.focusDate=null,this.element=e(t),this.isInput=this.element.is("input"),this.inputField=this.isInput?this.element:this.element.find("input"),this.component=!!this.element.hasClass("date")&&this.element.find(".add-on, .input-group-addon, .btn"),this.hasInput=this.component&&this.inputField.length,this.component&&0===this.component.length&&(this.component=!1),this.isInline=!this.component&&this.element.is("div"),this.picker=e(m.template),this._check_template(this.o.templates.leftArrow)&&this.picker.find(".prev").html(this.o.templates.leftArrow),this._check_template(this.o.templates.rightArrow)&&this.picker.find(".next").html(this.o.templates.rightArrow),this._buildEvents(),this._attachEvents(),this.isInline?this.picker.addClass("datepicker-inline").appendTo(this.element):this.picker.addClass("datepicker-dropdown dropdown-menu"),this.o.rtl&&this.picker.addClass("datepicker-rtl"),this.viewMode=this.o.startView,this.o.calendarWeeks&&this.picker.find("thead .datepicker-title, tfoot .today, tfoot .clear").attr("colspan",(function(e,t){return parseInt(t)+1})),this._allow_update=!1,this.setStartDate(this._o.startDate),this.setEndDate(this._o.endDate),this.setDaysOfWeekDisabled(this.o.daysOfWeekDisabled),this.setDaysOfWeekHighlighted(this.o.daysOfWeekHighlighted),this.setDatesDisabled(this.o.datesDisabled),this.fillDow(),this.fillMonths(),this._allow_update=!0,this.update(),this.showMode(),this.isInline&&this.show()};l.prototype={constructor:l,_resolveViewName:function(e,n){return 0===e||"days"===e||"month"===e?0:1===e||"months"===e||"year"===e?1:2===e||"years"===e||"decade"===e?2:3===e||"decades"===e||"century"===e?3:4===e||"centuries"===e||"millennium"===e?4:n!==t&&n},_check_template:function(n){try{return n!==t&&""!==n&&((n.match(/[<>]/g)||[]).length<=0||e(n).length>0)}catch(e){return!1}},_process_options:function(t){this._o=e.extend({},this._o,t);var r=this.o=e.extend({},this._o),o=r.language;p[o]||(o=o.split("-")[0],p[o]||(o=f.language)),r.language=o,r.startView=this._resolveViewName(r.startView,0),r.minViewMode=this._resolveViewName(r.minViewMode,0),r.maxViewMode=this._resolveViewName(r.maxViewMode,4),r.startView=Math.min(r.startView,r.maxViewMode),r.startView=Math.max(r.startView,r.minViewMode),!0!==r.multidate&&(r.multidate=Number(r.multidate)||!1,!1!==r.multidate&&(r.multidate=Math.max(0,r.multidate))),r.multidateSeparator=String(r.multidateSeparator),r.weekStart%=7,r.weekEnd=(r.weekStart+6)%7;var a=m.parseFormat(r.format);r.startDate!==-1/0&&(r.startDate?r.startDate instanceof Date?r.startDate=this._local_to_utc(this._zero_time(r.startDate)):r.startDate=m.parseDate(r.startDate,a,r.language,r.assumeNearbyYear):r.startDate=-1/0),r.endDate!==1/0&&(r.endDate?r.endDate instanceof Date?r.endDate=this._local_to_utc(this._zero_time(r.endDate)):r.endDate=m.parseDate(r.endDate,a,r.language,r.assumeNearbyYear):r.endDate=1/0),r.daysOfWeekDisabled=r.daysOfWeekDisabled||[],e.isArray(r.daysOfWeekDisabled)||(r.daysOfWeekDisabled=r.daysOfWeekDisabled.split(/[,\s]*/)),r.daysOfWeekDisabled=e.map(r.daysOfWeekDisabled,(function(e){return parseInt(e,10)})),r.daysOfWeekHighlighted=r.daysOfWeekHighlighted||[],e.isArray(r.daysOfWeekHighlighted)||(r.daysOfWeekHighlighted=r.daysOfWeekHighlighted.split(/[,\s]*/)),r.daysOfWeekHighlighted=e.map(r.daysOfWeekHighlighted,(function(e){return parseInt(e,10)})),r.datesDisabled=r.datesDisabled||[],e.isArray(r.datesDisabled)||(r.datesDisabled=[r.datesDisabled]),r.datesDisabled=e.map(r.datesDisabled,(function(e){return m.parseDate(e,a,r.language,r.assumeNearbyYear)}));var s=String(r.orientation).toLowerCase().split(/\s+/g),l=r.orientation.toLowerCase();if(s=e.grep(s,(function(e){return/^auto|left|right|top|bottom$/.test(e)})),r.orientation={x:"auto",y:"auto"},l&&"auto"!==l)if(1===s.length)switch(s[0]){case"top":case"bottom":r.orientation.y=s[0];break;case"left":case"right":r.orientation.x=s[0]}else l=e.grep(s,(function(e){return/^left|right$/.test(e)})),r.orientation.x=l[0]||"auto",l=e.grep(s,(function(e){return/^top|bottom$/.test(e)})),r.orientation.y=l[0]||"auto";if(r.defaultViewDate){var u=r.defaultViewDate.year||(new Date).getFullYear(),c=r.defaultViewDate.month||0,d=r.defaultViewDate.day||1;r.defaultViewDate=n(u,c,d)}else r.defaultViewDate=i()},_events:[],_secondaryEvents:[],_applyEvents:function(e){for(var n,i,r,o=0;o<e.length;o++)n=e[o][0],2===e[o].length?(i=t,r=e[o][1]):3===e[o].length&&(i=e[o][1],r=e[o][2]),n.on(r,i)},_unapplyEvents:function(e){for(var n,i,r,o=0;o<e.length;o++)n=e[o][0],2===e[o].length?(r=t,i=e[o][1]):3===e[o].length&&(r=e[o][1],i=e[o][2]),n.off(i,r)},_buildEvents:function(){var t={keyup:e.proxy((function(t){-1===e.inArray(t.keyCode,[27,37,39,38,40,32,13,9])&&this.update()}),this),keydown:e.proxy(this.keydown,this),paste:e.proxy(this.paste,this)};!0===this.o.showOnFocus&&(t.focus=e.proxy(this.show,this)),this.isInput?this._events=[[this.element,t]]:this.component&&this.hasInput?this._events=[[this.inputField,t],[this.component,{click:e.proxy(this.show,this)}]]:this._events=[[this.element,{click:e.proxy(this.show,this),keydown:e.proxy(this.keydown,this)}]],this._events.push([this.element,"*",{blur:e.proxy((function(e){this._focused_from=e.target}),this)}],[this.element,{blur:e.proxy((function(e){this._focused_from=e.target}),this)}]),this.o.immediateUpdates&&this._events.push([this.element,{"changeYear changeMonth":e.proxy((function(e){this.update(e.date)}),this)}]),this._secondaryEvents=[[this.picker,{click:e.proxy(this.click,this)}],[e(window),{resize:e.proxy(this.place,this)}],[e(document),{mousedown:e.proxy((function(e){this.element.is(e.target)||this.element.find(e.target).length||this.picker.is(e.target)||this.picker.find(e.target).length||this.isInline||this.hide()}),this)}]]},_attachEvents:function(){this._detachEvents(),this._applyEvents(this._events)},_detachEvents:function(){this._unapplyEvents(this._events)},_attachSecondaryEvents:function(){this._detachSecondaryEvents(),this._applyEvents(this._secondaryEvents)},_detachSecondaryEvents:function(){this._unapplyEvents(this._secondaryEvents)},_trigger:function(t,n){var i=n||this.dates.get(-1),r=this._utc_to_local(i);this.element.trigger({type:t,date:r,dates:e.map(this.dates,this._utc_to_local),format:e.proxy((function(e,t){0===arguments.length?(e=this.dates.length-1,t=this.o.format):"string"==typeof e&&(t=e,e=this.dates.length-1),t=t||this.o.format;var n=this.dates.get(e);return m.formatDate(n,t,this.o.language)}),this)})},show:function(){return this.inputField.prop("disabled")||this.inputField.prop("readonly")&&!1===this.o.enableOnReadonly?void 0:(this.isInline||this.picker.appendTo(this.o.container),this.place(),this.picker.show(),this._attachSecondaryEvents(),this._trigger("show"),(window.navigator.msMaxTouchPoints||"ontouchstart"in document)&&this.o.disableTouchKeyboard&&e(this.element).blur(),this)},hide:function(){return this.isInline||!this.picker.is(":visible")||(this.focusDate=null,this.picker.hide().detach(),this._detachSecondaryEvents(),this.viewMode=this.o.startView,this.showMode(),this.o.forceParse&&this.inputField.val()&&this.setValue(),this._trigger("hide")),this},destroy:function(){return this.hide(),this._detachEvents(),this._detachSecondaryEvents(),this.picker.remove(),delete this.element.data().datepicker,this.isInput||delete this.element.data().date,this},paste:function(t){var n;if(t.originalEvent.clipboardData&&t.originalEvent.clipboardData.types&&-1!==e.inArray("text/plain",t.originalEvent.clipboardData.types))n=t.originalEvent.clipboardData.getData("text/plain");else{if(!window.clipboardData)return;n=window.clipboardData.getData("Text")}this.setDate(n),this.update(),t.preventDefault()},_utc_to_local:function(e){return e&&new Date(e.getTime()+6e4*e.getTimezoneOffset())},_local_to_utc:function(e){return e&&new Date(e.getTime()-6e4*e.getTimezoneOffset())},_zero_time:function(e){return e&&new Date(e.getFullYear(),e.getMonth(),e.getDate())},_zero_utc_time:function(e){return e&&new Date(Date.UTC(e.getUTCFullYear(),e.getUTCMonth(),e.getUTCDate()))},getDates:function(){return e.map(this.dates,this._utc_to_local)},getUTCDates:function(){return e.map(this.dates,(function(e){return new Date(e)}))},getDate:function(){return this._utc_to_local(this.getUTCDate())},getUTCDate:function(){var e=this.dates.get(-1);return void 0!==e?new Date(e):null},clearDates:function(){this.inputField&&this.inputField.val(""),this.update(),this._trigger("changeDate"),this.o.autoclose&&this.hide()},setDates:function(){var t=e.isArray(arguments[0])?arguments[0]:arguments;return this.update.apply(this,t),this._trigger("changeDate"),this.setValue(),this},setUTCDates:function(){var t=e.isArray(arguments[0])?arguments[0]:arguments;return this.update.apply(this,e.map(t,this._utc_to_local)),this._trigger("changeDate"),this.setValue(),this},setDate:r("setDates"),setUTCDate:r("setUTCDates"),remove:r("destroy"),setValue:function(){var e=this.getFormattedDate();return this.inputField.val(e),this},getFormattedDate:function(n){n===t&&(n=this.o.format);var i=this.o.language;return e.map(this.dates,(function(e){return m.formatDate(e,n,i)})).join(this.o.multidateSeparator)},getStartDate:function(){return this.o.startDate},setStartDate:function(e){return this._process_options({startDate:e}),this.update(),this.updateNavArrows(),this},getEndDate:function(){return this.o.endDate},setEndDate:function(e){return this._process_options({endDate:e}),this.update(),this.updateNavArrows(),this},setDaysOfWeekDisabled:function(e){return this._process_options({daysOfWeekDisabled:e}),this.update(),this.updateNavArrows(),this},setDaysOfWeekHighlighted:function(e){return this._process_options({daysOfWeekHighlighted:e}),this.update(),this},setDatesDisabled:function(e){this._process_options({datesDisabled:e}),this.update(),this.updateNavArrows()},place:function(){if(this.isInline)return this;var t=this.picker.outerWidth(),n=this.picker.outerHeight(),i=e(this.o.container),r=i.width(),o="body"===this.o.container?e(document).scrollTop():i.scrollTop(),a=i.offset(),s=[];this.element.parents().each((function(){var t=e(this).css("z-index");"auto"!==t&&0!==t&&s.push(parseInt(t))}));var l=Math.max.apply(Math,s)+this.o.zIndexOffset,u=this.component?this.component.parent().offset():this.element.offset(),c=this.component?this.component.outerHeight(!0):this.element.outerHeight(!1),d=this.component?this.component.outerWidth(!0):this.element.outerWidth(!1),f=u.left-a.left,h=u.top-a.top;"body"!==this.o.container&&(h+=o),this.picker.removeClass("datepicker-orient-top datepicker-orient-bottom datepicker-orient-right datepicker-orient-left"),"auto"!==this.o.orientation.x?(this.picker.addClass("datepicker-orient-"+this.o.orientation.x),"right"===this.o.orientation.x&&(f-=t-d)):u.left<0?(this.picker.addClass("datepicker-orient-left"),f-=u.left-10):f+t>r?(this.picker.addClass("datepicker-orient-right"),f+=d-t):this.picker.addClass("datepicker-orient-left");var p=this.o.orientation.y;if("auto"===p&&(p=0>-o+h-n?"bottom":"top"),this.picker.addClass("datepicker-orient-"+p),"top"===p?h-=n+parseInt(this.picker.css("padding-top")):h+=c,this.o.rtl){var m=r-(f+d);this.picker.css({top:h,right:m,zIndex:l})}else this.picker.css({top:h,left:f,zIndex:l});return this},_allow_update:!0,update:function(){if(!this._allow_update)return this;var t=this.dates.copy(),n=[],i=!1;return arguments.length?(e.each(arguments,e.proxy((function(e,t){t instanceof Date&&(t=this._local_to_utc(t)),n.push(t)}),this)),i=!0):(n=(n=this.isInput?this.element.val():this.element.data("date")||this.inputField.val())&&this.o.multidate?n.split(this.o.multidateSeparator):[n],delete this.element.data().date),n=e.map(n,e.proxy((function(e){return m.parseDate(e,this.o.format,this.o.language,this.o.assumeNearbyYear)}),this)),n=e.grep(n,e.proxy((function(e){return!this.dateWithinRange(e)||!e}),this),!0),this.dates.replace(n),this.dates.length?this.viewDate=new Date(this.dates.get(-1)):this.viewDate<this.o.startDate?this.viewDate=new Date(this.o.startDate):this.viewDate>this.o.endDate?this.viewDate=new Date(this.o.endDate):this.viewDate=this.o.defaultViewDate,i?this.setValue():n.length&&String(t)!==String(this.dates)&&this._trigger("changeDate"),!this.dates.length&&t.length&&this._trigger("clearDate"),this.fill(),this.element.change(),this},fillDow:function(){var t=this.o.weekStart,n="<tr>";for(this.o.calendarWeeks&&(this.picker.find(".datepicker-days .datepicker-switch").attr("colspan",(function(e,t){return parseInt(t)+1})),n+='<th class="cw">&#160;</th>');t<this.o.weekStart+7;)n+='<th class="dow',e.inArray(t,this.o.daysOfWeekDisabled)>-1&&(n+=" disabled"),n+='">'+p[this.o.language].daysMin[t++%7]+"</th>";n+="</tr>",this.picker.find(".datepicker-days thead").append(n)},fillMonths:function(){for(var e=this._utc_to_local(this.viewDate),t="",n=0;12>n;){t+='<span class="month'+(e&&e.getMonth()===n?" focused":"")+'">'+p[this.o.language].monthsShort[n++]+"</span>"}this.picker.find(".datepicker-months td").html(t)},setRange:function(t){t&&t.length?this.range=e.map(t,(function(e){return e.valueOf()})):delete this.range,this.fill()},getClassNames:function(t){var n=[],i=this.viewDate.getUTCFullYear(),r=this.viewDate.getUTCMonth(),o=new Date;return t.getUTCFullYear()<i||t.getUTCFullYear()===i&&t.getUTCMonth()<r?n.push("old"):(t.getUTCFullYear()>i||t.getUTCFullYear()===i&&t.getUTCMonth()>r)&&n.push("new"),this.focusDate&&t.valueOf()===this.focusDate.valueOf()&&n.push("focused"),this.o.todayHighlight&&t.getUTCFullYear()===o.getFullYear()&&t.getUTCMonth()===o.getMonth()&&t.getUTCDate()===o.getDate()&&n.push("today"),-1!==this.dates.contains(t)&&n.push("active"),this.dateWithinRange(t)||n.push("disabled"),this.dateIsDisabled(t)&&n.push("disabled","disabled-date"),-1!==e.inArray(t.getUTCDay(),this.o.daysOfWeekHighlighted)&&n.push("highlighted"),this.range&&(t>this.range[0]&&t<this.range[this.range.length-1]&&n.push("range"),-1!==e.inArray(t.valueOf(),this.range)&&n.push("selected"),t.valueOf()===this.range[0]&&n.push("range-start"),t.valueOf()===this.range[this.range.length-1]&&n.push("range-end")),n},_fill_yearsView:function(n,i,r,o,a,s,l,u){var c,d,f,h,p,m,g,v,y,b,_;for(c="",d=this.picker.find(n),f=parseInt(a/r,10)*r,p=parseInt(s/o,10)*o,m=parseInt(l/o,10)*o,h=e.map(this.dates,(function(e){return parseInt(e.getUTCFullYear()/o,10)*o})),d.find(".datepicker-switch").text(f+"-"+(f+9*o)),g=f-o,v=-1;11>v;v+=1)y=[i],b=null,-1===v?y.push("old"):10===v&&y.push("new"),-1!==e.inArray(g,h)&&y.push("active"),(p>g||g>m)&&y.push("disabled"),g===this.viewDate.getFullYear()&&y.push("focused"),u!==e.noop&&((_=u(new Date(g,0,1)))===t?_={}:"boolean"==typeof _?_={enabled:_}:"string"==typeof _&&(_={classes:_}),!1===_.enabled&&y.push("disabled"),_.classes&&(y=y.concat(_.classes.split(/\s+/))),_.tooltip&&(b=_.tooltip)),c+='<span class="'+y.join(" ")+'"'+(b?' title="'+b+'"':"")+">"+g+"</span>",g+=o;d.find("td").html(c)},fill:function(){var i,r,o=new Date(this.viewDate),a=o.getUTCFullYear(),s=o.getUTCMonth(),l=this.o.startDate!==-1/0?this.o.startDate.getUTCFullYear():-1/0,u=this.o.startDate!==-1/0?this.o.startDate.getUTCMonth():-1/0,c=this.o.endDate!==1/0?this.o.endDate.getUTCFullYear():1/0,d=this.o.endDate!==1/0?this.o.endDate.getUTCMonth():1/0,f=p[this.o.language].today||p.en.today||"",h=p[this.o.language].clear||p.en.clear||"",g=p[this.o.language].titleFormat||p.en.titleFormat;if(!isNaN(a)&&!isNaN(s)){this.picker.find(".datepicker-days .datepicker-switch").text(m.formatDate(o,g,this.o.language)),this.picker.find("tfoot .today").text(f).toggle(!1!==this.o.todayBtn),this.picker.find("tfoot .clear").text(h).toggle(!1!==this.o.clearBtn),this.picker.find("thead .datepicker-title").text(this.o.title).toggle(""!==this.o.title),this.updateNavArrows(),this.fillMonths();var v=n(a,s-1,28),y=m.getDaysInMonth(v.getUTCFullYear(),v.getUTCMonth());v.setUTCDate(y),v.setUTCDate(y-(v.getUTCDay()-this.o.weekStart+7)%7);var b=new Date(v);v.getUTCFullYear()<100&&b.setUTCFullYear(v.getUTCFullYear()),b.setUTCDate(b.getUTCDate()+42),b=b.valueOf();for(var _,w=[];v.valueOf()<b;){if(v.getUTCDay()===this.o.weekStart&&(w.push("<tr>"),this.o.calendarWeeks)){var x=new Date(+v+(this.o.weekStart-v.getUTCDay()-7)%7*864e5),D=new Date(Number(x)+(11-x.getUTCDay())%7*864e5),T=new Date(Number(T=n(D.getUTCFullYear(),0,1))+(11-T.getUTCDay())%7*864e5),C=(D-T)/864e5/7+1;w.push('<td class="cw">'+C+"</td>")}(_=this.getClassNames(v)).push("day"),this.o.beforeShowDay!==e.noop&&((r=this.o.beforeShowDay(this._utc_to_local(v)))===t?r={}:"boolean"==typeof r?r={enabled:r}:"string"==typeof r&&(r={classes:r}),!1===r.enabled&&_.push("disabled"),r.classes&&(_=_.concat(r.classes.split(/\s+/))),r.tooltip&&(i=r.tooltip)),_=e.isFunction(e.uniqueSort)?e.uniqueSort(_):e.unique(_),w.push('<td class="'+_.join(" ")+'"'+(i?' title="'+i+'"':"")+">"+v.getUTCDate()+"</td>"),i=null,v.getUTCDay()===this.o.weekEnd&&w.push("</tr>"),v.setUTCDate(v.getUTCDate()+1)}this.picker.find(".datepicker-days tbody").empty().append(w.join(""));var k=p[this.o.language].monthsTitle||p.en.monthsTitle||"Months",E=this.picker.find(".datepicker-months").find(".datepicker-switch").text(this.o.maxViewMode<2?k:a).end().find("span").removeClass("active");if(e.each(this.dates,(function(e,t){t.getUTCFullYear()===a&&E.eq(t.getUTCMonth()).addClass("active")})),(l>a||a>c)&&E.addClass("disabled"),a===l&&E.slice(0,u).addClass("disabled"),a===c&&E.slice(d+1).addClass("disabled"),this.o.beforeShowMonth!==e.noop){var S=this;e.each(E,(function(n,i){var r=new Date(a,n,1),o=S.o.beforeShowMonth(r);o===t?o={}:"boolean"==typeof o?o={enabled:o}:"string"==typeof o&&(o={classes:o}),!1!==o.enabled||e(i).hasClass("disabled")||e(i).addClass("disabled"),o.classes&&e(i).addClass(o.classes),o.tooltip&&e(i).prop("title",o.tooltip)}))}this._fill_yearsView(".datepicker-years","year",10,1,a,l,c,this.o.beforeShowYear),this._fill_yearsView(".datepicker-decades","decade",100,10,a,l,c,this.o.beforeShowDecade),this._fill_yearsView(".datepicker-centuries","century",1e3,100,a,l,c,this.o.beforeShowCentury)}},updateNavArrows:function(){if(this._allow_update){var e=new Date(this.viewDate),t=e.getUTCFullYear(),n=e.getUTCMonth();switch(this.viewMode){case 0:this.o.startDate!==-1/0&&t<=this.o.startDate.getUTCFullYear()&&n<=this.o.startDate.getUTCMonth()?this.picker.find(".prev").css({visibility:"hidden"}):this.picker.find(".prev").css({visibility:"visible"}),this.o.endDate!==1/0&&t>=this.o.endDate.getUTCFullYear()&&n>=this.o.endDate.getUTCMonth()?this.picker.find(".next").css({visibility:"hidden"}):this.picker.find(".next").css({visibility:"visible"});break;case 1:case 2:case 3:case 4:this.o.startDate!==-1/0&&t<=this.o.startDate.getUTCFullYear()||this.o.maxViewMode<2?this.picker.find(".prev").css({visibility:"hidden"}):this.picker.find(".prev").css({visibility:"visible"}),this.o.endDate!==1/0&&t>=this.o.endDate.getUTCFullYear()||this.o.maxViewMode<2?this.picker.find(".next").css({visibility:"hidden"}):this.picker.find(".next").css({visibility:"visible"})}}},click:function(t){var r,o,a,s,l,u,c;t.preventDefault(),t.stopPropagation(),(r=e(t.target)).hasClass("datepicker-switch")&&this.showMode(1);var d=r.closest(".prev, .next");d.length>0&&(o=m.modes[this.viewMode].navStep*(d.hasClass("prev")?-1:1),0===this.viewMode?(this.viewDate=this.moveMonth(this.viewDate,o),this._trigger("changeMonth",this.viewDate)):(this.viewDate=this.moveYear(this.viewDate,o),1===this.viewMode&&this._trigger("changeYear",this.viewDate)),this.fill()),r.hasClass("today")&&!r.hasClass("day")&&(this.showMode(-2),this._setDate(i(),"linked"===this.o.todayBtn?null:"view")),r.hasClass("clear")&&this.clearDates(),r.hasClass("disabled")||(r.hasClass("day")&&(a=parseInt(r.text(),10)||1,s=this.viewDate.getUTCFullYear(),l=this.viewDate.getUTCMonth(),r.hasClass("old")&&(0===l?(l=11,s-=1,u=!0,c=!0):(l-=1,u=!0)),r.hasClass("new")&&(11===l?(l=0,s+=1,u=!0,c=!0):(l+=1,u=!0)),this._setDate(n(s,l,a)),c&&this._trigger("changeYear",this.viewDate),u&&this._trigger("changeMonth",this.viewDate)),r.hasClass("month")&&(this.viewDate.setUTCDate(1),a=1,l=r.parent().find("span").index(r),s=this.viewDate.getUTCFullYear(),this.viewDate.setUTCMonth(l),this._trigger("changeMonth",this.viewDate),1===this.o.minViewMode?(this._setDate(n(s,l,a)),this.showMode()):this.showMode(-1),this.fill()),(r.hasClass("year")||r.hasClass("decade")||r.hasClass("century"))&&(this.viewDate.setUTCDate(1),a=1,l=0,s=parseInt(r.text(),10)||0,this.viewDate.setUTCFullYear(s),r.hasClass("year")&&(this._trigger("changeYear",this.viewDate),2===this.o.minViewMode&&this._setDate(n(s,l,a))),r.hasClass("decade")&&(this._trigger("changeDecade",this.viewDate),3===this.o.minViewMode&&this._setDate(n(s,l,a))),r.hasClass("century")&&(this._trigger("changeCentury",this.viewDate),4===this.o.minViewMode&&this._setDate(n(s,l,a))),this.showMode(-1),this.fill())),this.picker.is(":visible")&&this._focused_from&&e(this._focused_from).focus(),delete this._focused_from},_toggle_multidate:function(e){var t=this.dates.contains(e);if(e||this.dates.clear(),-1!==t?(!0===this.o.multidate||this.o.multidate>1||this.o.toggleActive)&&this.dates.remove(t):!1===this.o.multidate?(this.dates.clear(),this.dates.push(e)):this.dates.push(e),"number"==typeof this.o.multidate)for(;this.dates.length>this.o.multidate;)this.dates.remove(0)},_setDate:function(e,t){t&&"date"!==t||this._toggle_multidate(e&&new Date(e)),t&&"view"!==t||(this.viewDate=e&&new Date(e)),this.fill(),this.setValue(),t&&"view"===t||this._trigger("changeDate"),this.inputField&&this.inputField.change(),!this.o.autoclose||t&&"date"!==t||this.hide()},moveDay:function(e,t){var n=new Date(e);return n.setUTCDate(e.getUTCDate()+t),n},moveWeek:function(e,t){return this.moveDay(e,7*t)},moveMonth:function(e,t){if(!function(e){return e&&!isNaN(e.getTime())}(e))return this.o.defaultViewDate;if(!t)return e;var n,i,r=new Date(e.valueOf()),o=r.getUTCDate(),a=r.getUTCMonth(),s=Math.abs(t);if(t=t>0?1:-1,1===s)i=-1===t?function(){return r.getUTCMonth()===a}:function(){return r.getUTCMonth()!==n},n=a+t,r.setUTCMonth(n),(0>n||n>11)&&(n=(n+12)%12);else{for(var l=0;s>l;l++)r=this.moveMonth(r,t);n=r.getUTCMonth(),r.setUTCDate(o),i=function(){return n!==r.getUTCMonth()}}for(;i();)r.setUTCDate(--o),r.setUTCMonth(n);return r},moveYear:function(e,t){return this.moveMonth(e,12*t)},moveAvailableDate:function(e,t,n){do{if(e=this[n](e,t),!this.dateWithinRange(e))return!1;n="moveDay"}while(this.dateIsDisabled(e));return e},weekOfDateIsDisabled:function(t){return-1!==e.inArray(t.getUTCDay(),this.o.daysOfWeekDisabled)},dateIsDisabled:function(t){return this.weekOfDateIsDisabled(t)||e.grep(this.o.datesDisabled,(function(e){return function(e,t){return e.getUTCFullYear()===t.getUTCFullYear()&&e.getUTCMonth()===t.getUTCMonth()&&e.getUTCDate()===t.getUTCDate()}(t,e)})).length>0},dateWithinRange:function(e){return e>=this.o.startDate&&e<=this.o.endDate},keydown:function(e){if(this.picker.is(":visible")){var t,n,i=!1,r=this.focusDate||this.viewDate;switch(e.keyCode){case 27:this.focusDate?(this.focusDate=null,this.viewDate=this.dates.get(-1)||this.viewDate,this.fill()):this.hide(),e.preventDefault(),e.stopPropagation();break;case 37:case 38:case 39:case 40:if(!this.o.keyboardNavigation||7===this.o.daysOfWeekDisabled.length)break;t=37===e.keyCode||38===e.keyCode?-1:1,0===this.viewMode?e.ctrlKey?(n=this.moveAvailableDate(r,t,"moveYear"))&&this._trigger("changeYear",this.viewDate):e.shiftKey?(n=this.moveAvailableDate(r,t,"moveMonth"))&&this._trigger("changeMonth",this.viewDate):37===e.keyCode||39===e.keyCode?n=this.moveAvailableDate(r,t,"moveDay"):this.weekOfDateIsDisabled(r)||(n=this.moveAvailableDate(r,t,"moveWeek")):1===this.viewMode?((38===e.keyCode||40===e.keyCode)&&(t*=4),n=this.moveAvailableDate(r,t,"moveMonth")):2===this.viewMode&&((38===e.keyCode||40===e.keyCode)&&(t*=4),n=this.moveAvailableDate(r,t,"moveYear")),n&&(this.focusDate=this.viewDate=n,this.setValue(),this.fill(),e.preventDefault());break;case 13:if(!this.o.forceParse)break;r=this.focusDate||this.dates.get(-1)||this.viewDate,this.o.keyboardNavigation&&(this._toggle_multidate(r),i=!0),this.focusDate=null,this.viewDate=this.dates.get(-1)||this.viewDate,this.setValue(),this.fill(),this.picker.is(":visible")&&(e.preventDefault(),e.stopPropagation(),this.o.autoclose&&this.hide());break;case 9:this.focusDate=null,this.viewDate=this.dates.get(-1)||this.viewDate,this.fill(),this.hide()}i&&(this.dates.length?this._trigger("changeDate"):this._trigger("clearDate"),this.inputField&&this.inputField.change())}else(40===e.keyCode||27===e.keyCode)&&(this.show(),e.stopPropagation())},showMode:function(e){e&&(this.viewMode=Math.max(this.o.minViewMode,Math.min(this.o.maxViewMode,this.viewMode+e))),this.picker.children("div").hide().filter(".datepicker-"+m.modes[this.viewMode].clsName).show(),this.updateNavArrows()}};var u=function(t,n){e(t).data("datepicker",this),this.element=e(t),this.inputs=e.map(n.inputs,(function(e){return e.jquery?e[0]:e})),delete n.inputs,d.call(e(this.inputs),n).on("changeDate",e.proxy(this.dateUpdated,this)),this.pickers=e.map(this.inputs,(function(t){return e(t).data("datepicker")})),this.updateDates()};u.prototype={updateDates:function(){this.dates=e.map(this.pickers,(function(e){return e.getUTCDate()})),this.updateRanges()},updateRanges:function(){var t=e.map(this.dates,(function(e){return e.valueOf()}));e.each(this.pickers,(function(e,n){n.setRange(t)}))},dateUpdated:function(t){if(!this.updating){this.updating=!0;var n=e(t.target).data("datepicker");if(void 0!==n){var i=n.getUTCDate(),r=e.inArray(t.target,this.inputs),o=r-1,a=r+1,s=this.inputs.length;if(-1!==r){if(e.each(this.pickers,(function(e,t){t.getUTCDate()||t.setUTCDate(i)})),i<this.dates[o])for(;o>=0&&i<this.dates[o];)this.pickers[o--].setUTCDate(i);else if(i>this.dates[a])for(;s>a&&i>this.dates[a];)this.pickers[a++].setUTCDate(i);this.updateDates(),delete this.updating}}}},remove:function(){e.map(this.pickers,(function(e){e.remove()})),delete this.element.data().datepicker}};var c=e.fn.datepicker,d=function(n){var i,r=Array.apply(null,arguments);if(r.shift(),this.each((function(){var t=e(this),s=t.data("datepicker"),c="object"==typeof n&&n;if(!s){var d=o(this,"date"),h=a(e.extend({},f,d,c).language),p=e.extend({},f,h,d,c);t.hasClass("input-daterange")||p.inputs?(e.extend(p,{inputs:p.inputs||t.find("input").toArray()}),s=new u(this,p)):s=new l(this,p),t.data("datepicker",s)}"string"==typeof n&&"function"==typeof s[n]&&(i=s[n].apply(s,r))})),i===t||i instanceof l||i instanceof u)return this;if(this.length>1)throw new Error("Using only allowed for the collection of a single element ("+n+" function)");return i};e.fn.datepicker=d;var f=e.fn.datepicker.defaults={assumeNearbyYear:!1,autoclose:!1,beforeShowDay:e.noop,beforeShowMonth:e.noop,beforeShowYear:e.noop,beforeShowDecade:e.noop,beforeShowCentury:e.noop,calendarWeeks:!1,clearBtn:!1,toggleActive:!1,daysOfWeekDisabled:[],daysOfWeekHighlighted:[],datesDisabled:[],endDate:1/0,forceParse:!0,format:"mm/dd/yyyy",keyboardNavigation:!0,language:"en",minViewMode:0,maxViewMode:4,multidate:!1,multidateSeparator:",",orientation:"auto",rtl:!1,startDate:-1/0,startView:0,todayBtn:!1,todayHighlight:!1,weekStart:0,disableTouchKeyboard:!1,enableOnReadonly:!0,showOnFocus:!0,zIndexOffset:10,container:"body",immediateUpdates:!1,title:"",templates:{leftArrow:"&laquo;",rightArrow:"&raquo;"}},h=e.fn.datepicker.locale_opts=["format","rtl","weekStart"];e.fn.datepicker.Constructor=l;var p=e.fn.datepicker.dates={en:{days:["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"],daysShort:["Sun","Mon","Tue","Wed","Thu","Fri","Sat"],daysMin:["Su","Mo","Tu","We","Th","Fr","Sa"],months:["January","February","March","April","May","June","July","August","September","October","November","December"],monthsShort:["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],today:"Today",clear:"Clear",titleFormat:"MM yyyy"}},m={modes:[{clsName:"days",navFnc:"Month",navStep:1},{clsName:"months",navFnc:"FullYear",navStep:1},{clsName:"years",navFnc:"FullYear",navStep:10},{clsName:"decades",navFnc:"FullDecade",navStep:100},{clsName:"centuries",navFnc:"FullCentury",navStep:1e3}],isLeapYear:function(e){return e%4==0&&e%100!=0||e%400==0},getDaysInMonth:function(e,t){return[31,m.isLeapYear(e)?29:28,31,30,31,30,31,31,30,31,30,31][t]},validParts:/dd?|DD?|mm?|MM?|yy(?:yy)?/g,nonpunctuation:/[^ -\/:-@\u5e74\u6708\u65e5\[-`{-~\t\n\r]+/g,parseFormat:function(e){if("function"==typeof e.toValue&&"function"==typeof e.toDisplay)return e;var t=e.replace(this.validParts,"\0").split("\0"),n=e.match(this.validParts);if(!t||!t.length||!n||0===n.length)throw new Error("Invalid date format.");return{separators:t,parts:n}},parseDate:function(r,o,a,s){function u(e,t){return!0===t&&(t=10),100>e&&((e+=2e3)>(new Date).getFullYear()+t&&(e-=100)),e}function c(){var e=this.slice(0,y[h].length),t=y[h].slice(0,e.length);return e.toLowerCase()===t.toLowerCase()}if(!r)return t;if(r instanceof Date)return r;if("string"==typeof o&&(o=m.parseFormat(o)),o.toValue)return o.toValue(r,o,a);var d,f,h,g,v=/([\-+]\d+)([dmwy])/,y=r.match(/([\-+]\d+)([dmwy])/g),b={d:"moveDay",m:"moveMonth",w:"moveWeek",y:"moveYear"},_={yesterday:"-1d",today:"+0d",tomorrow:"+1d"};if(/^[\-+]\d+[dmwy]([\s,]+[\-+]\d+[dmwy])*$/.test(r)){for(r=new Date,h=0;h<y.length;h++)d=v.exec(y[h]),f=parseInt(d[1]),g=b[d[2]],r=l.prototype[g](r,f);return n(r.getUTCFullYear(),r.getUTCMonth(),r.getUTCDate())}if(void 0!==_[r]&&(y=(r=_[r]).match(/([\-+]\d+)([dmwy])/g),/^[\-+]\d+[dmwy]([\s,]+[\-+]\d+[dmwy])*$/.test(r))){for(r=new Date,h=0;h<y.length;h++)d=v.exec(y[h]),f=parseInt(d[1]),g=b[d[2]],r=l.prototype[g](r,f);return n(r.getUTCFullYear(),r.getUTCMonth(),r.getUTCDate())}y=r&&r.match(this.nonpunctuation)||[],r=new Date;var w,x,D={},T=["yyyy","yy","M","MM","m","mm","d","dd"],C={yyyy:function(e,t){return e.setUTCFullYear(s?u(t,s):t)},yy:function(e,t){return e.setUTCFullYear(s?u(t,s):t)},m:function(e,t){if(isNaN(e))return e;for(t-=1;0>t;)t+=12;for(t%=12,e.setUTCMonth(t);e.getUTCMonth()!==t;)e.setUTCDate(e.getUTCDate()-1);return e},d:function(e,t){return e.setUTCDate(t)}};C.M=C.MM=C.mm=C.m,C.dd=C.d,r=i();var k=o.parts.slice();if(y.length!==k.length&&(k=e(k).filter((function(t,n){return-1!==e.inArray(n,T)})).toArray()),y.length===k.length){var E,S,A;for(h=0,E=k.length;E>h;h++){if(w=parseInt(y[h],10),d=k[h],isNaN(w))switch(d){case"MM":x=e(p[a].months).filter(c),w=e.inArray(x[0],p[a].months)+1;break;case"M":x=e(p[a].monthsShort).filter(c),w=e.inArray(x[0],p[a].monthsShort)+1}D[d]=w}for(h=0;h<T.length;h++)(A=T[h])in D&&!isNaN(D[A])&&(S=new Date(r),C[A](S,D[A]),isNaN(S)||(r=S))}return r},formatDate:function(t,n,i){if(!t)return"";if("string"==typeof n&&(n=m.parseFormat(n)),n.toDisplay)return n.toDisplay(t,n,i);var r={d:t.getUTCDate(),D:p[i].daysShort[t.getUTCDay()],DD:p[i].days[t.getUTCDay()],m:t.getUTCMonth()+1,M:p[i].monthsShort[t.getUTCMonth()],MM:p[i].months[t.getUTCMonth()],yy:t.getUTCFullYear().toString().substring(2),yyyy:t.getUTCFullYear()};r.dd=(r.d<10?"0":"")+r.d,r.mm=(r.m<10?"0":"")+r.m,t=[];for(var o=e.extend([],n.separators),a=0,s=n.parts.length;s>=a;a++)o.length&&t.push(o.shift()),t.push(r[n.parts[a]]);return t.join("")},headTemplate:'<thead><tr><th colspan="7" class="datepicker-title"></th></tr><tr><th class="prev">&laquo;</th><th colspan="5" class="datepicker-switch"></th><th class="next">&raquo;</th></tr></thead>',contTemplate:'<tbody><tr><td colspan="7"></td></tr></tbody>',footTemplate:'<tfoot><tr><th colspan="7" class="today"></th></tr><tr><th colspan="7" class="clear"></th></tr></tfoot>'};m.template='<div class="datepicker"><div class="datepicker-days"><table class="table-condensed">'+m.headTemplate+"<tbody></tbody>"+m.footTemplate+'</table></div><div class="datepicker-months"><table class="table-condensed">'+m.headTemplate+m.contTemplate+m.footTemplate+'</table></div><div class="datepicker-years"><table class="table-condensed">'+m.headTemplate+m.contTemplate+m.footTemplate+'</table></div><div class="datepicker-decades"><table class="table-condensed">'+m.headTemplate+m.contTemplate+m.footTemplate+'</table></div><div class="datepicker-centuries"><table class="table-condensed">'+m.headTemplate+m.contTemplate+m.footTemplate+"</table></div></div>",e.fn.datepicker.DPGlobal=m,e.fn.datepicker.noConflict=function(){return e.fn.datepicker=c,this},e.fn.datepicker.version="1.6.4",e(document).on("focus.datepicker.data-api click.datepicker.data-api",'[data-provide="datepicker"]',(function(t){var n=e(this);n.data("datepicker")||(t.preventDefault(),d.call(n,"show"))})),e((function(){d.call(e('[data-provide="datepicker-inline"]'))}))})),function(e,t){"function"==typeof define&&define.amd?define(["exports","b"],t):"object"==typeof exports&&"string"!=typeof exports.nodeName?t(exports,require("b")):t(e.commonJsStrict={},e.b)}(this,(function(e,t){"function"!=typeof Promise&&function(){"use strict";function e(e){return"function"==typeof e}var t,n,i=Array.isArray?Array.isArray:function(e){return"[object Array]"===Object.prototype.toString.call(e)},r=0,o=function(e,t){v[r]=e,v[r+1]=t,2===(r+=2)&&(n?n(y):f())};var a="undefined"!=typeof window?window:void 0,s=a||{},l=s.MutationObserver||s.WebKitMutationObserver,u="undefined"!=typeof process&&"[object process]"==={}.toString.call(process),c="undefined"!=typeof Uint8ClampedArray&&"undefined"!=typeof importScripts&&"undefined"!=typeof MessageChannel;function d(){return function(){setTimeout(y,1)}}var f,h,p,m,g,v=new Array(1e3);function y(){for(var e=0;e<r;e+=2){(0,v[e])(v[e+1]),v[e]=void 0,v[e+1]=void 0}r=0}function b(){}u?f=function(){process.nextTick(y)}:l?(p=0,m=new l(y),g=document.createTextNode(""),m.observe(g,{characterData:!0}),f=function(){g.data=p=++p%2}):c?((h=new MessageChannel).port1.onmessage=y,f=function(){h.port2.postMessage(0)}):f=void 0===a&&"function"==typeof require?function(){try{var e=require("vertx");return t=e.runOnLoop||e.runOnContext,function(){t(y)}}catch(e){return d()}}():d();var _=void 0,w=new A;function x(t,n){if(n.constructor===t.constructor)!function(e,t){1===t._state?C(e,t._result):2===t._state?k(e,t._result):E(t,void 0,(function(t){D(e,t)}),(function(t){k(e,t)}))}(t,n);else{var i=function(e){try{return e.then}catch(e){return w.error=e,w}}(n);i===w?k(t,w.error):void 0===i?C(t,n):e(i)?function(e,t,n){o((function(e){var i=!1,r=function(e,t,n,i){try{e.call(t,n,i)}catch(e){return e}}(n,t,(function(n){i||(i=!0,t!==n?D(e,n):C(e,n))}),(function(t){i||(i=!0,k(e,t))}),e._label);!i&&r&&(i=!0,k(e,r))}),e)}(t,n,i):C(t,n)}}function D(e,t){var n;e===t?k(e,new TypeError("You cannot resolve a promise with itself")):"function"==typeof(n=t)||"object"==typeof n&&null!==n?x(e,t):C(e,t)}function T(e){e._onerror&&e._onerror(e._result),S(e)}function C(e,t){e._state===_&&(e._result=t,e._state=1,0!==e._subscribers.length&&o(S,e))}function k(e,t){e._state===_&&(e._state=2,e._result=t,o(T,e))}function E(e,t,n,i){var r=e._subscribers,a=r.length;e._onerror=null,r[a]=t,r[a+1]=n,r[a+2]=i,0===a&&e._state&&o(S,e)}function S(e){var t=e._subscribers,n=e._state;if(0!==t.length){for(var i,r,o=e._result,a=0;a<t.length;a+=3)i=t[a],r=t[a+n],i?M(n,i,r,o):r(o);e._subscribers.length=0}}function A(){this.error=null}var O=new A;function M(t,n,i,r){var o,a,s,l,u=e(i);if(u){if((o=function(e,t){try{return e(t)}catch(e){return O.error=e,O}}(i,r))===O?(l=!0,a=o.error,o=null):s=!0,n===o)return void k(n,new TypeError("A promises callback cannot return that same promise."))}else o=r,s=!0;n._state!==_||(u&&s?D(n,o):l?k(n,a):1===t?C(n,o):2===t&&k(n,o))}function N(e,t){var n=this;n._instanceConstructor=e,n.promise=new e(b),n._validateInput(t)?(n._input=t,n.length=t.length,n._remaining=t.length,n._init(),0===n.length?C(n.promise,n._result):(n.length=n.length||0,n._enumerate(),0===n._remaining&&C(n.promise,n._result))):k(n.promise,n._validationError())}N.prototype._validateInput=function(e){return i(e)},N.prototype._validationError=function(){return new Error("Array Methods must be provided an Array")},N.prototype._init=function(){this._result=new Array(this.length)};var I=N;N.prototype._enumerate=function(){for(var e=this,t=e.length,n=e.promise,i=e._input,r=0;n._state===_&&r<t;r++)e._eachEntry(i[r],r)},N.prototype._eachEntry=function(e,t){var n,i=this,r=i._instanceConstructor;"object"==typeof(n=e)&&null!==n?e.constructor===r&&e._state!==_?(e._onerror=null,i._settledAt(e._state,t,e._result)):i._willSettleAt(r.resolve(e),t):(i._remaining--,i._result[t]=e)},N.prototype._settledAt=function(e,t,n){var i=this,r=i.promise;r._state===_&&(i._remaining--,2===e?k(r,n):i._result[t]=n),0===i._remaining&&C(r,i._result)},N.prototype._willSettleAt=function(e,t){var n=this;E(e,void 0,(function(e){n._settledAt(1,t,e)}),(function(e){n._settledAt(2,t,e)}))};var L=function(e){return new I(this,e).promise};var j=function(e){var t=new this(b);if(!i(e))return k(t,new TypeError("You must pass an array to race.")),t;var n=e.length;function r(e){D(t,e)}function o(e){k(t,e)}for(var a=0;t._state===_&&a<n;a++)E(this.resolve(e[a]),void 0,r,o);return t};var F=function(e){if(e&&"object"==typeof e&&e.constructor===this)return e;var t=new this(b);return D(t,e),t};var P=function(e){var t=new this(b);return k(t,e),t},R=0;var U=Y;function Y(t){this._id=R++,this._state=void 0,this._result=void 0,this._subscribers=[],b!==t&&(e(t)||function(){throw new TypeError("You must pass a resolver function as the first argument to the promise constructor")}(),this instanceof Y||function(){throw new TypeError("Failed to construct 'Promise': Please use the 'new' operator, this object constructor cannot be called as a function.")}(),function(e,t){try{t((function(t){D(e,t)}),(function(t){k(e,t)}))}catch(t){k(e,t)}}(this,t))}Y.all=L,Y.race=j,Y.resolve=F,Y.reject=P,Y._setScheduler=function(e){n=e},Y._setAsap=function(e){o=e},Y._asap=o,Y.prototype={constructor:Y,then:function(e,t){var n=this,i=n._state;if(1===i&&!e||2===i&&!t)return this;var r=new this.constructor(b),a=n._result;if(i){var s=arguments[i-1];o((function(){M(i,r,s,a)}))}else E(n,r,e,t);return r},catch:function(e){return this.then(null,e)}};var H=function(){var e;if("undefined"!=typeof global)e=global;else if("undefined"!=typeof self)e=self;else try{e=Function("return this")()}catch(e){throw new Error("polyfill failed because global object is unavailable in this environment")}var t=e.Promise;t&&"[object Promise]"===Object.prototype.toString.call(t.resolve())&&!t.cast||(e.Promise=U)},W={Promise:U,polyfill:H};"function"==typeof define&&define.amd?define((function(){return W})):"undefined"!=typeof module&&module.exports?module.exports=W:void 0!==this&&(this.ES6Promise=W),H()}.call(this);var n,i,r,o=["Webkit","Moz","ms"],a=document.createElement("div").style;function s(e){if(e in a)return e;for(var t=e[0].toUpperCase()+e.slice(1),n=o.length;n--;)if((e=o[n]+t)in a)return e}function l(e){e=e||{};for(var t=1;t<arguments.length;t++){var n=arguments[t];if(n)for(var i in n)n.hasOwnProperty(i)&&("object"==typeof n[i]?e[i]=l({},n[i]):e[i]=n[i])}return e}function u(e){if("createEvent"in document){var t=document.createEvent("HTMLEvents");t.initEvent("change",!1,!0),e.dispatchEvent(t)}else e.fireEvent("onchange")}function c(e,t,n){if("string"==typeof t){var i=t;(t={})[i]=n}for(var r in t)e.style[r]=t[r]}function d(e,t){var n,i=t||new Image;return n=new Promise((function(e,t){i.setAttribute("crossOrigin","anonymous"),i.onload=function(){setTimeout((function(){e(i)}),1)}})),i.src=e,n}i=s("transform"),n=s("transformOrigin"),r=s("userSelect");var f="translate3d",h=function(e,t,n){this.x=parseFloat(e),this.y=parseFloat(t),this.scale=parseFloat(n)};h.parse=function(e){return e.style?h.parse(e.style[i]):e.indexOf("matrix")>-1||e.indexOf("none")>-1?h.fromMatrix(e):h.fromString(e)},h.fromMatrix=function(e){var t=e.substring(7).split(",");return t.length&&"none"!==e||(t=[1,0,0,1,0,0]),new h(parseInt(t[4],10),parseInt(t[5],10),parseFloat(t[0]))},h.fromString=function(e){var t=e.split(") "),n=t[0].substring(f.length+1).split(","),i=t.length>1?t[1].substring(6):1,r=n.length>1?n[0]:0,o=n.length>1?n[1]:0;return new h(r,o,i)},h.prototype.toString=function(){return"translate3d("+this.x+"px, "+this.y+"px, 0px) scale("+this.scale+")"};var p=function(e){if(!e||!e.style[n])return this.x=0,void(this.y=0);var t=e.style[n].split(" ");this.x=parseFloat(t[0]),this.y=parseFloat(t[1])};function m(){var e,t,n,i,r=this,o=r.options.viewport.type?"cr-vp-"+r.options.viewport.type:null;r.data={},r.elements={},e=r.elements.boundary=document.createElement("div"),n=r.elements.viewport=document.createElement("div"),t=r.elements.img=document.createElement("img"),i=r.elements.overlay=document.createElement("div"),e.classList.add("cr-boundary"),c(e,{width:r.options.boundary.width+"px",height:r.options.boundary.height+"px"}),n.classList.add("cr-viewport"),o&&n.classList.add(o),c(n,{width:r.options.viewport.width+"px",height:r.options.viewport.height+"px"}),t.classList.add("cr-image"),i.classList.add("cr-overlay"),r.element.appendChild(e),e.appendChild(t),e.appendChild(n),e.appendChild(i),r.element.classList.add(["croppie-container"]),r.options.customClass&&r.element.classList.add(r.options.customClass),w.call(this),r.options.showZoom&&v.call(r)}function g(e){this.options.showZoom&&(this.elements.zoomer.value=L(e))}function v(){var e,t,n,i=this,r=i.elements.zoomerWrap=document.createElement("div"),o=i.elements.zoomer=document.createElement("input");function a(){_.call(i),e=new p(i.elements.img),t=i.elements.viewport.getBoundingClientRect(),n=h.parse(i.elements.img)}function s(){y.call(i,{value:parseFloat(o.value),origin:e||new p(i.elements.img),viewportRect:t||i.elements.viewport.getBoundingClientRect(),transform:n||h.parse(i.elements.img)})}function l(e){var t=e.deltaY/-2e3,n=i._currentZoom+t;e.preventDefault(),a(),g.call(i,n),s()}r.classList.add("cr-slider-wrap"),o.type="range",o.classList.add("cr-slider"),o.step="0.01",o.value=1,i.element.appendChild(r),r.appendChild(o),i._currentZoom=1,i.elements.zoomer.addEventListener("mousedown",a),i.elements.zoomer.addEventListener("touchstart",a),i.elements.zoomer.addEventListener("input",s),i.elements.zoomer.addEventListener("change",s),i.options.mouseWheelZoom&&(i.elements.boundary.addEventListener("mousewheel",l),i.elements.boundary.addEventListener("DOMMouseScroll",l))}function y(e){var t=this,r=e.transform,o=e.viewportRect,a=e.origin;t._currentZoom=e.value,r.scale=t._currentZoom;var s=b.call(t,o),l=s.translate,u=s.origin;r.x>=l.maxX&&(a.x=u.minX,r.x=l.maxX),r.x<=l.minX&&(a.x=u.maxX,r.x=l.minX),r.y>=l.maxY&&(a.y=u.minY,r.y=l.maxY),r.y<=l.minY&&(a.y=u.maxY,r.y=l.minY);var d={};d[i]=r.toString(),d[n]=a.toString(),c(t.elements.img,d),E.call(t),S.call(t)}function b(e){var t=this,n=t._currentZoom,i=e.width,r=e.height,o=t.options.boundary.width/2,a=t.options.boundary.height/2,s=t._originalImageWidth*n,l=t._originalImageHeight*n,u=i/2,c=r/2,d=-1*(u/n-o),f=-1*(c/n-a),h=1/n*u,p=1/n*c;return{translate:{maxX:d,minX:d-(s*(1/n)-i*(1/n)),maxY:f,minY:f-(l*(1/n)-r*(1/n))},origin:{maxX:s*(1/n)-h,minX:h,maxY:l*(1/n)-p,minY:p}}}function _(){var e=this,t=e._currentZoom,r=e.elements.img.getBoundingClientRect(),o=e.elements.viewport.getBoundingClientRect(),a=h.parse(e.elements.img.style[i]),s=new p(e.elements.img),l=o.top-r.top+o.height/2,u=o.left-r.left+o.width/2,d={},f={};d.y=l/t,d.x=u/t,f.y=(d.y-s.y)*(1-t),f.x=(d.x-s.x)*(1-t),a.x-=f.x,a.y-=f.y;var m={};m[n]=d.x+"px "+d.y+"px",m[i]=a.toString(),c(e.elements.img,m)}function w(){var e,t,n,o,a=this,s=!1;function l(n){n.preventDefault(),s||(s=!0,e=n.pageX,t=n.pageY,transform=h.parse(a.elements.img),window.addEventListener("mousemove",d),window.addEventListener("touchmove",d),window.addEventListener("mouseup",f),window.addEventListener("touchend",f),document.body.style[r]="none",o=a.elements.viewport.getBoundingClientRect())}function d(r){r.preventDefault();var s=r.pageX||r.touches[0].pageX,l=r.pageY||r.touches[0].pageY,d=s-e,f=l-t,h=a.elements.img.getBoundingClientRect(),p=transform.y+f,m=transform.x+d,v={};if("touchmove"==r.type&&r.touches.length>1){var y=r.touches[0],b=r.touches[1],_=Math.sqrt((y.pageX-b.pageX)*(y.pageX-b.pageX)+(y.pageY-b.pageY)*(y.pageY-b.pageY));n||(n=_/a._currentZoom);var w=_/n;return g.call(a,w),void u(a.elements.zoomer)}o.top>h.top+f&&o.bottom<h.bottom+f&&(transform.y=p),o.left>h.left+d&&o.right<h.right+d&&(transform.x=m),v[i]=transform.toString(),c(a.elements.img,v),x.call(a),t=l,e=s}function f(){s=!1,window.removeEventListener("mousemove",d),window.removeEventListener("touchmove",d),window.removeEventListener("mouseup",f),window.removeEventListener("touchend",f),document.body.style[r]="",_.call(a),S.call(a),n=0}a.elements.overlay.addEventListener("mousedown",l),a.elements.overlay.addEventListener("touchstart",l)}function x(){var e=this,t=e.elements.boundary.getBoundingClientRect(),n=e.elements.img.getBoundingClientRect();c(e.elements.overlay,{width:n.width+"px",height:n.height+"px",top:n.top-t.top+"px",left:n.left-t.left+"px"})}p.prototype.toString=function(){return this.x+"px "+this.y+"px"};var D,T,C,k,E=(D=x,T=500,function(){var e=this,t=arguments,n=function(){k=null,C||D.apply(e,t)},i=C&&!k;clearTimeout(k),k=setTimeout(n,T),i&&D.apply(e,t)});function S(){var e=this;A.call(e)&&e.options.update.call(e,e.get())}function A(){return this.elements.img.offsetHeight>0&&this.elements.img.offsetWidth>0}function O(){var e,t,r,o,a,s=this,l=0,d=1.5,f=1,m={},v=s.elements.img,y=s.elements.zoomer,b=new h(0,0,f),_=new p;A.call(s)&&!s.data.bound&&(s.data.bound=!0,m[i]=b.toString(),m[n]=_.toString(),c(v,m),e=v.getBoundingClientRect(),t=s.elements.viewport.getBoundingClientRect(),r=s.elements.boundary.getBoundingClientRect(),s._originalImageWidth=e.width,s._originalImageHeight=e.height,s.options.showZoom&&(o=t.width/e.width,a=t.height/e.height,(l=Math.max(o,a))>d&&(d=l+1),y.min=L(l),y.max=L(d),f=Math.max(r.width/e.width,r.height/e.height),g.call(s,f),u(y)),s._currentZoom=b.scale=f,m[i]=b.toString(),c(v,m),s.data.points.length?M.call(s,s.data.points):N.call(s),x.call(s))}function M(e){if(4!=e.length)throw"Croppie - Invalid number of points supplied: "+e;var t=this,r=e[2]-e[0],o=t.elements.viewport.getBoundingClientRect(),a=t.elements.boundary.getBoundingClientRect(),s=o.left-a.left,l=o.top-a.top,u=o.width/r,d=e[1],f=e[0],p=-1*e[1]+l,m=-1*e[0]+s,v={};v[n]=f+"px "+d+"px",v[i]=new h(m,p,u).toString(),c(t.elements.img,v),g.call(t,u),t._currentZoom=u}function N(){var e=this,t=e.elements.img.getBoundingClientRect(),n=e.elements.viewport.getBoundingClientRect(),r=e.elements.boundary.getBoundingClientRect(),o=n.left-r.left,a=n.top-r.top,s=o-(t.width-n.width)/2,l=a-(t.height-n.height)/2,u=new h(s,l,e._currentZoom);c(e.elements.img,i,u.toString())}function I(e,t){var n,i=this,r=[];if("string"==typeof e)n=e,e={};else if(Array.isArray(e))r=e.slice();else{if(void 0===e&&i.data.url)return O.call(i),S.call(i),null;n=e.url,r=e.points||[]}i.data.bound=!1,i.data.url=n||i.data.url,i.data.points=(r||i.data.points).map((function(e){return parseFloat(e)}));var o=d(n,i.elements.img);return o.then((function(){O.call(i),S.call(i),t&&t()})),o}function L(e){return parseFloat(e).toFixed(2)}function j(){var e=this,t=e.elements.img.getBoundingClientRect(),n=e.elements.viewport.getBoundingClientRect(),i=n.left-t.left,r=n.top-t.top,o=i+n.width,a=r+n.height,s=e._currentZoom;return(s===1/0||isNaN(s))&&(s=1),i=Math.max(0,i/s),r=Math.max(0,r/s),o=Math.max(0,o/s),a=Math.max(0,a/s),{points:[L(i),L(r),L(o),L(a)],zoom:s}}function F(e){var t,n=this,i=j.call(n),r=e||{type:"canvas",size:"viewport"},o="string"==typeof r?r:r.type;return"viewport"===(r.size||"viewport")&&(t=n.elements.viewport.getBoundingClientRect(),i.outputWidth=t.width,i.outputHeight=t.height),i.circle="circle"===n.options.viewport.type,i.url=n.data.url,new Promise((function(e,t){"canvas"===o?d(i.url).then((function(t){e(function(e,t){var n=t.points,i=n[0],r=n[1],o=n[2]-n[0],a=n[3]-n[1],s=t.circle,l=document.createElement("canvas"),u=l.getContext("2d"),c=o,d=a;return t.outputWidth&&t.outputHeight&&(c=t.outputWidth,d=t.outputHeight),l.width=c,l.height=d,s&&(u.save(),u.beginPath(),u.arc(c/2,d/2,c/2,0,2*Math.PI,!0),u.closePath(),u.clip()),u.drawImage(e,i,r,o,a,0,0,c,d),l.toDataURL()}(t,i))})):e(function(e){var t=e.points,n=document.createElement("div"),i=document.createElement("img"),r=t[2]-t[0],o=t[3]-t[1];return n.classList.add("croppie-result"),n.appendChild(i),c(i,{left:-1*t[0]+"px",top:-1*t[1]+"px"}),i.src=e.url,c(n,{width:r+"px",height:o+"px"}),n}(i))}))}function P(){console.warn("Croppie.refresh() is deprecated.  Please use Croppie.bind() without any arguments instead.  refresh() will be removed in a later release."),O.call(this)}function R(){var e=this;e.element.removeChild(e.elements.boundary),e.options.showZoom&&e.element.removeChild(e.elements.zoomerWrap),delete e.elements}if(this.jQuery){var U=this.jQuery;U.fn.croppie=function(e){var t=typeof e;if("string"===t){var n=Array.prototype.slice.call(arguments,1),i=U(this).data("croppie");return"get"===e?i.get():"result"===e?i.result.apply(i,n):this.each((function(){var t=U(this).data("croppie");if(t){var i=t[e];if(!U.isFunction(i))throw"Croppie "+e+" method not found";i.apply(t,n),"destroy"===e&&U(this).removeData("croppie")}}))}return this.each((function(){var t=new Y(this,e);U(this).data("croppie",t)}))}}function Y(e,t){this.element=e,this.options=l({},Y.defaults,t),m.call(this)}Y.defaults={viewport:{width:100,height:100,type:"square"},boundary:{width:300,height:300},customClass:"",showZoom:!0,mouseWheelZoom:!0,update:function(){}},l(Y.prototype,{bind:function(e,t){return I.call(this,e,t)},get:function(){return j.call(this)},result:function(e){return F.call(this,e)},refresh:function(){return P.call(this)},destroy:function(){return R.call(this)}}),e.Croppie=window.Croppie=Y}));
/*! jQuery v3.3.1 | (c) JS Foundation and other contributors | jquery.org/license */
!function(e,t){"use strict";"object"==typeof module&&"object"==typeof module.exports?module.exports=e.document?t(e,!0):function(e){if(!e.document)throw new Error("jQuery requires a window with a document");return t(e)}:t(e)}("undefined"!=typeof window?window:this,function(e,t){"use strict";var n=[],r=e.document,i=Object.getPrototypeOf,o=n.slice,a=n.concat,s=n.push,u=n.indexOf,l={},c=l.toString,f=l.hasOwnProperty,p=f.toString,d=p.call(Object),h={},g=function e(t){return"function"==typeof t&&"number"!=typeof t.nodeType},y=function e(t){return null!=t&&t===t.window},v={type:!0,src:!0,noModule:!0};function m(e,t,n){var i,o=(t=t||r).createElement("script");if(o.text=e,n)for(i in v)n[i]&&(o[i]=n[i]);t.head.appendChild(o).parentNode.removeChild(o)}function x(e){return null==e?e+"":"object"==typeof e||"function"==typeof e?l[c.call(e)]||"object":typeof e}var b="3.3.1",w=function(e,t){return new w.fn.init(e,t)},T=/^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g;w.fn=w.prototype={jquery:"3.3.1",constructor:w,length:0,toArray:function(){return o.call(this)},get:function(e){return null==e?o.call(this):e<0?this[e+this.length]:this[e]},pushStack:function(e){var t=w.merge(this.constructor(),e);return t.prevObject=this,t},each:function(e){return w.each(this,e)},map:function(e){return this.pushStack(w.map(this,function(t,n){return e.call(t,n,t)}))},slice:function(){return this.pushStack(o.apply(this,arguments))},first:function(){return this.eq(0)},last:function(){return this.eq(-1)},eq:function(e){var t=this.length,n=+e+(e<0?t:0);return this.pushStack(n>=0&&n<t?[this[n]]:[])},end:function(){return this.prevObject||this.constructor()},push:s,sort:n.sort,splice:n.splice},w.extend=w.fn.extend=function(){var e,t,n,r,i,o,a=arguments[0]||{},s=1,u=arguments.length,l=!1;for("boolean"==typeof a&&(l=a,a=arguments[s]||{},s++),"object"==typeof a||g(a)||(a={}),s===u&&(a=this,s--);s<u;s++)if(null!=(e=arguments[s]))for(t in e)n=a[t],a!==(r=e[t])&&(l&&r&&(w.isPlainObject(r)||(i=Array.isArray(r)))?(i?(i=!1,o=n&&Array.isArray(n)?n:[]):o=n&&w.isPlainObject(n)?n:{},a[t]=w.extend(l,o,r)):void 0!==r&&(a[t]=r));return a},w.extend({expando:"jQuery"+("3.3.1"+Math.random()).replace(/\D/g,""),isReady:!0,error:function(e){throw new Error(e)},noop:function(){},isPlainObject:function(e){var t,n;return!(!e||"[object Object]"!==c.call(e))&&(!(t=i(e))||"function"==typeof(n=f.call(t,"constructor")&&t.constructor)&&p.call(n)===d)},isEmptyObject:function(e){var t;for(t in e)return!1;return!0},globalEval:function(e){m(e)},each:function(e,t){var n,r=0;if(C(e)){for(n=e.length;r<n;r++)if(!1===t.call(e[r],r,e[r]))break}else for(r in e)if(!1===t.call(e[r],r,e[r]))break;return e},trim:function(e){return null==e?"":(e+"").replace(T,"")},makeArray:function(e,t){var n=t||[];return null!=e&&(C(Object(e))?w.merge(n,"string"==typeof e?[e]:e):s.call(n,e)),n},inArray:function(e,t,n){return null==t?-1:u.call(t,e,n)},merge:function(e,t){for(var n=+t.length,r=0,i=e.length;r<n;r++)e[i++]=t[r];return e.length=i,e},grep:function(e,t,n){for(var r,i=[],o=0,a=e.length,s=!n;o<a;o++)(r=!t(e[o],o))!==s&&i.push(e[o]);return i},map:function(e,t,n){var r,i,o=0,s=[];if(C(e))for(r=e.length;o<r;o++)null!=(i=t(e[o],o,n))&&s.push(i);else for(o in e)null!=(i=t(e[o],o,n))&&s.push(i);return a.apply([],s)},guid:1,support:h}),"function"==typeof Symbol&&(w.fn[Symbol.iterator]=n[Symbol.iterator]),w.each("Boolean Number String Function Array Date RegExp Object Error Symbol".split(" "),function(e,t){l["[object "+t+"]"]=t.toLowerCase()});function C(e){var t=!!e&&"length"in e&&e.length,n=x(e);return!g(e)&&!y(e)&&("array"===n||0===t||"number"==typeof t&&t>0&&t-1 in e)}var E=function(e){var t,n,r,i,o,a,s,u,l,c,f,p,d,h,g,y,v,m,x,b="sizzle"+1*new Date,w=e.document,T=0,C=0,E=ae(),k=ae(),S=ae(),D=function(e,t){return e===t&&(f=!0),0},N={}.hasOwnProperty,A=[],j=A.pop,q=A.push,L=A.push,H=A.slice,O=function(e,t){for(var n=0,r=e.length;n<r;n++)if(e[n]===t)return n;return-1},P="checked|selected|async|autofocus|autoplay|controls|defer|disabled|hidden|ismap|loop|multiple|open|readonly|required|scoped",M="[\\x20\\t\\r\\n\\f]",R="(?:\\\\.|[\\w-]|[^\0-\\xa0])+",I="\\["+M+"*("+R+")(?:"+M+"*([*^$|!~]?=)"+M+"*(?:'((?:\\\\.|[^\\\\'])*)'|\"((?:\\\\.|[^\\\\\"])*)\"|("+R+"))|)"+M+"*\\]",W=":("+R+")(?:\\((('((?:\\\\.|[^\\\\'])*)'|\"((?:\\\\.|[^\\\\\"])*)\")|((?:\\\\.|[^\\\\()[\\]]|"+I+")*)|.*)\\)|)",$=new RegExp(M+"+","g"),B=new RegExp("^"+M+"+|((?:^|[^\\\\])(?:\\\\.)*)"+M+"+$","g"),F=new RegExp("^"+M+"*,"+M+"*"),_=new RegExp("^"+M+"*([>+~]|"+M+")"+M+"*"),z=new RegExp("="+M+"*([^\\]'\"]*?)"+M+"*\\]","g"),X=new RegExp(W),U=new RegExp("^"+R+"$"),V={ID:new RegExp("^#("+R+")"),CLASS:new RegExp("^\\.("+R+")"),TAG:new RegExp("^("+R+"|[*])"),ATTR:new RegExp("^"+I),PSEUDO:new RegExp("^"+W),CHILD:new RegExp("^:(only|first|last|nth|nth-last)-(child|of-type)(?:\\("+M+"*(even|odd|(([+-]|)(\\d*)n|)"+M+"*(?:([+-]|)"+M+"*(\\d+)|))"+M+"*\\)|)","i"),bool:new RegExp("^(?:"+P+")$","i"),needsContext:new RegExp("^"+M+"*[>+~]|:(even|odd|eq|gt|lt|nth|first|last)(?:\\("+M+"*((?:-\\d)?\\d*)"+M+"*\\)|)(?=[^-]|$)","i")},G=/^(?:input|select|textarea|button)$/i,Y=/^h\d$/i,Q=/^[^{]+\{\s*\[native \w/,J=/^(?:#([\w-]+)|(\w+)|\.([\w-]+))$/,K=/[+~]/,Z=new RegExp("\\\\([\\da-f]{1,6}"+M+"?|("+M+")|.)","ig"),ee=function(e,t,n){var r="0x"+t-65536;return r!==r||n?t:r<0?String.fromCharCode(r+65536):String.fromCharCode(r>>10|55296,1023&r|56320)},te=/([\0-\x1f\x7f]|^-?\d)|^-$|[^\0-\x1f\x7f-\uFFFF\w-]/g,ne=function(e,t){return t?"\0"===e?"\ufffd":e.slice(0,-1)+"\\"+e.charCodeAt(e.length-1).toString(16)+" ":"\\"+e},re=function(){p()},ie=me(function(e){return!0===e.disabled&&("form"in e||"label"in e)},{dir:"parentNode",next:"legend"});try{L.apply(A=H.call(w.childNodes),w.childNodes),A[w.childNodes.length].nodeType}catch(e){L={apply:A.length?function(e,t){q.apply(e,H.call(t))}:function(e,t){var n=e.length,r=0;while(e[n++]=t[r++]);e.length=n-1}}}function oe(e,t,r,i){var o,s,l,c,f,h,v,m=t&&t.ownerDocument,T=t?t.nodeType:9;if(r=r||[],"string"!=typeof e||!e||1!==T&&9!==T&&11!==T)return r;if(!i&&((t?t.ownerDocument||t:w)!==d&&p(t),t=t||d,g)){if(11!==T&&(f=J.exec(e)))if(o=f[1]){if(9===T){if(!(l=t.getElementById(o)))return r;if(l.id===o)return r.push(l),r}else if(m&&(l=m.getElementById(o))&&x(t,l)&&l.id===o)return r.push(l),r}else{if(f[2])return L.apply(r,t.getElementsByTagName(e)),r;if((o=f[3])&&n.getElementsByClassName&&t.getElementsByClassName)return L.apply(r,t.getElementsByClassName(o)),r}if(n.qsa&&!S[e+" "]&&(!y||!y.test(e))){if(1!==T)m=t,v=e;else if("object"!==t.nodeName.toLowerCase()){(c=t.getAttribute("id"))?c=c.replace(te,ne):t.setAttribute("id",c=b),s=(h=a(e)).length;while(s--)h[s]="#"+c+" "+ve(h[s]);v=h.join(","),m=K.test(e)&&ge(t.parentNode)||t}if(v)try{return L.apply(r,m.querySelectorAll(v)),r}catch(e){}finally{c===b&&t.removeAttribute("id")}}}return u(e.replace(B,"$1"),t,r,i)}function ae(){var e=[];function t(n,i){return e.push(n+" ")>r.cacheLength&&delete t[e.shift()],t[n+" "]=i}return t}function se(e){return e[b]=!0,e}function ue(e){var t=d.createElement("fieldset");try{return!!e(t)}catch(e){return!1}finally{t.parentNode&&t.parentNode.removeChild(t),t=null}}function le(e,t){var n=e.split("|"),i=n.length;while(i--)r.attrHandle[n[i]]=t}function ce(e,t){var n=t&&e,r=n&&1===e.nodeType&&1===t.nodeType&&e.sourceIndex-t.sourceIndex;if(r)return r;if(n)while(n=n.nextSibling)if(n===t)return-1;return e?1:-1}function fe(e){return function(t){return"input"===t.nodeName.toLowerCase()&&t.type===e}}function pe(e){return function(t){var n=t.nodeName.toLowerCase();return("input"===n||"button"===n)&&t.type===e}}function de(e){return function(t){return"form"in t?t.parentNode&&!1===t.disabled?"label"in t?"label"in t.parentNode?t.parentNode.disabled===e:t.disabled===e:t.isDisabled===e||t.isDisabled!==!e&&ie(t)===e:t.disabled===e:"label"in t&&t.disabled===e}}function he(e){return se(function(t){return t=+t,se(function(n,r){var i,o=e([],n.length,t),a=o.length;while(a--)n[i=o[a]]&&(n[i]=!(r[i]=n[i]))})})}function ge(e){return e&&"undefined"!=typeof e.getElementsByTagName&&e}n=oe.support={},o=oe.isXML=function(e){var t=e&&(e.ownerDocument||e).documentElement;return!!t&&"HTML"!==t.nodeName},p=oe.setDocument=function(e){var t,i,a=e?e.ownerDocument||e:w;return a!==d&&9===a.nodeType&&a.documentElement?(d=a,h=d.documentElement,g=!o(d),w!==d&&(i=d.defaultView)&&i.top!==i&&(i.addEventListener?i.addEventListener("unload",re,!1):i.attachEvent&&i.attachEvent("onunload",re)),n.attributes=ue(function(e){return e.className="i",!e.getAttribute("className")}),n.getElementsByTagName=ue(function(e){return e.appendChild(d.createComment("")),!e.getElementsByTagName("*").length}),n.getElementsByClassName=Q.test(d.getElementsByClassName),n.getById=ue(function(e){return h.appendChild(e).id=b,!d.getElementsByName||!d.getElementsByName(b).length}),n.getById?(r.filter.ID=function(e){var t=e.replace(Z,ee);return function(e){return e.getAttribute("id")===t}},r.find.ID=function(e,t){if("undefined"!=typeof t.getElementById&&g){var n=t.getElementById(e);return n?[n]:[]}}):(r.filter.ID=function(e){var t=e.replace(Z,ee);return function(e){var n="undefined"!=typeof e.getAttributeNode&&e.getAttributeNode("id");return n&&n.value===t}},r.find.ID=function(e,t){if("undefined"!=typeof t.getElementById&&g){var n,r,i,o=t.getElementById(e);if(o){if((n=o.getAttributeNode("id"))&&n.value===e)return[o];i=t.getElementsByName(e),r=0;while(o=i[r++])if((n=o.getAttributeNode("id"))&&n.value===e)return[o]}return[]}}),r.find.TAG=n.getElementsByTagName?function(e,t){return"undefined"!=typeof t.getElementsByTagName?t.getElementsByTagName(e):n.qsa?t.querySelectorAll(e):void 0}:function(e,t){var n,r=[],i=0,o=t.getElementsByTagName(e);if("*"===e){while(n=o[i++])1===n.nodeType&&r.push(n);return r}return o},r.find.CLASS=n.getElementsByClassName&&function(e,t){if("undefined"!=typeof t.getElementsByClassName&&g)return t.getElementsByClassName(e)},v=[],y=[],(n.qsa=Q.test(d.querySelectorAll))&&(ue(function(e){h.appendChild(e).innerHTML="<a id='"+b+"'></a><select id='"+b+"-\r\\' msallowcapture=''><option selected=''></option></select>",e.querySelectorAll("[msallowcapture^='']").length&&y.push("[*^$]="+M+"*(?:''|\"\")"),e.querySelectorAll("[selected]").length||y.push("\\["+M+"*(?:value|"+P+")"),e.querySelectorAll("[id~="+b+"-]").length||y.push("~="),e.querySelectorAll(":checked").length||y.push(":checked"),e.querySelectorAll("a#"+b+"+*").length||y.push(".#.+[+~]")}),ue(function(e){e.innerHTML="<a href='' disabled='disabled'></a><select disabled='disabled'><option/></select>";var t=d.createElement("input");t.setAttribute("type","hidden"),e.appendChild(t).setAttribute("name","D"),e.querySelectorAll("[name=d]").length&&y.push("name"+M+"*[*^$|!~]?="),2!==e.querySelectorAll(":enabled").length&&y.push(":enabled",":disabled"),h.appendChild(e).disabled=!0,2!==e.querySelectorAll(":disabled").length&&y.push(":enabled",":disabled"),e.querySelectorAll("*,:x"),y.push(",.*:")})),(n.matchesSelector=Q.test(m=h.matches||h.webkitMatchesSelector||h.mozMatchesSelector||h.oMatchesSelector||h.msMatchesSelector))&&ue(function(e){n.disconnectedMatch=m.call(e,"*"),m.call(e,"[s!='']:x"),v.push("!=",W)}),y=y.length&&new RegExp(y.join("|")),v=v.length&&new RegExp(v.join("|")),t=Q.test(h.compareDocumentPosition),x=t||Q.test(h.contains)?function(e,t){var n=9===e.nodeType?e.documentElement:e,r=t&&t.parentNode;return e===r||!(!r||1!==r.nodeType||!(n.contains?n.contains(r):e.compareDocumentPosition&&16&e.compareDocumentPosition(r)))}:function(e,t){if(t)while(t=t.parentNode)if(t===e)return!0;return!1},D=t?function(e,t){if(e===t)return f=!0,0;var r=!e.compareDocumentPosition-!t.compareDocumentPosition;return r||(1&(r=(e.ownerDocument||e)===(t.ownerDocument||t)?e.compareDocumentPosition(t):1)||!n.sortDetached&&t.compareDocumentPosition(e)===r?e===d||e.ownerDocument===w&&x(w,e)?-1:t===d||t.ownerDocument===w&&x(w,t)?1:c?O(c,e)-O(c,t):0:4&r?-1:1)}:function(e,t){if(e===t)return f=!0,0;var n,r=0,i=e.parentNode,o=t.parentNode,a=[e],s=[t];if(!i||!o)return e===d?-1:t===d?1:i?-1:o?1:c?O(c,e)-O(c,t):0;if(i===o)return ce(e,t);n=e;while(n=n.parentNode)a.unshift(n);n=t;while(n=n.parentNode)s.unshift(n);while(a[r]===s[r])r++;return r?ce(a[r],s[r]):a[r]===w?-1:s[r]===w?1:0},d):d},oe.matches=function(e,t){return oe(e,null,null,t)},oe.matchesSelector=function(e,t){if((e.ownerDocument||e)!==d&&p(e),t=t.replace(z,"='$1']"),n.matchesSelector&&g&&!S[t+" "]&&(!v||!v.test(t))&&(!y||!y.test(t)))try{var r=m.call(e,t);if(r||n.disconnectedMatch||e.document&&11!==e.document.nodeType)return r}catch(e){}return oe(t,d,null,[e]).length>0},oe.contains=function(e,t){return(e.ownerDocument||e)!==d&&p(e),x(e,t)},oe.attr=function(e,t){(e.ownerDocument||e)!==d&&p(e);var i=r.attrHandle[t.toLowerCase()],o=i&&N.call(r.attrHandle,t.toLowerCase())?i(e,t,!g):void 0;return void 0!==o?o:n.attributes||!g?e.getAttribute(t):(o=e.getAttributeNode(t))&&o.specified?o.value:null},oe.escape=function(e){return(e+"").replace(te,ne)},oe.error=function(e){throw new Error("Syntax error, unrecognized expression: "+e)},oe.uniqueSort=function(e){var t,r=[],i=0,o=0;if(f=!n.detectDuplicates,c=!n.sortStable&&e.slice(0),e.sort(D),f){while(t=e[o++])t===e[o]&&(i=r.push(o));while(i--)e.splice(r[i],1)}return c=null,e},i=oe.getText=function(e){var t,n="",r=0,o=e.nodeType;if(o){if(1===o||9===o||11===o){if("string"==typeof e.textContent)return e.textContent;for(e=e.firstChild;e;e=e.nextSibling)n+=i(e)}else if(3===o||4===o)return e.nodeValue}else while(t=e[r++])n+=i(t);return n},(r=oe.selectors={cacheLength:50,createPseudo:se,match:V,attrHandle:{},find:{},relative:{">":{dir:"parentNode",first:!0}," ":{dir:"parentNode"},"+":{dir:"previousSibling",first:!0},"~":{dir:"previousSibling"}},preFilter:{ATTR:function(e){return e[1]=e[1].replace(Z,ee),e[3]=(e[3]||e[4]||e[5]||"").replace(Z,ee),"~="===e[2]&&(e[3]=" "+e[3]+" "),e.slice(0,4)},CHILD:function(e){return e[1]=e[1].toLowerCase(),"nth"===e[1].slice(0,3)?(e[3]||oe.error(e[0]),e[4]=+(e[4]?e[5]+(e[6]||1):2*("even"===e[3]||"odd"===e[3])),e[5]=+(e[7]+e[8]||"odd"===e[3])):e[3]&&oe.error(e[0]),e},PSEUDO:function(e){var t,n=!e[6]&&e[2];return V.CHILD.test(e[0])?null:(e[3]?e[2]=e[4]||e[5]||"":n&&X.test(n)&&(t=a(n,!0))&&(t=n.indexOf(")",n.length-t)-n.length)&&(e[0]=e[0].slice(0,t),e[2]=n.slice(0,t)),e.slice(0,3))}},filter:{TAG:function(e){var t=e.replace(Z,ee).toLowerCase();return"*"===e?function(){return!0}:function(e){return e.nodeName&&e.nodeName.toLowerCase()===t}},CLASS:function(e){var t=E[e+" "];return t||(t=new RegExp("(^|"+M+")"+e+"("+M+"|$)"))&&E(e,function(e){return t.test("string"==typeof e.className&&e.className||"undefined"!=typeof e.getAttribute&&e.getAttribute("class")||"")})},ATTR:function(e,t,n){return function(r){var i=oe.attr(r,e);return null==i?"!="===t:!t||(i+="","="===t?i===n:"!="===t?i!==n:"^="===t?n&&0===i.indexOf(n):"*="===t?n&&i.indexOf(n)>-1:"$="===t?n&&i.slice(-n.length)===n:"~="===t?(" "+i.replace($," ")+" ").indexOf(n)>-1:"|="===t&&(i===n||i.slice(0,n.length+1)===n+"-"))}},CHILD:function(e,t,n,r,i){var o="nth"!==e.slice(0,3),a="last"!==e.slice(-4),s="of-type"===t;return 1===r&&0===i?function(e){return!!e.parentNode}:function(t,n,u){var l,c,f,p,d,h,g=o!==a?"nextSibling":"previousSibling",y=t.parentNode,v=s&&t.nodeName.toLowerCase(),m=!u&&!s,x=!1;if(y){if(o){while(g){p=t;while(p=p[g])if(s?p.nodeName.toLowerCase()===v:1===p.nodeType)return!1;h=g="only"===e&&!h&&"nextSibling"}return!0}if(h=[a?y.firstChild:y.lastChild],a&&m){x=(d=(l=(c=(f=(p=y)[b]||(p[b]={}))[p.uniqueID]||(f[p.uniqueID]={}))[e]||[])[0]===T&&l[1])&&l[2],p=d&&y.childNodes[d];while(p=++d&&p&&p[g]||(x=d=0)||h.pop())if(1===p.nodeType&&++x&&p===t){c[e]=[T,d,x];break}}else if(m&&(x=d=(l=(c=(f=(p=t)[b]||(p[b]={}))[p.uniqueID]||(f[p.uniqueID]={}))[e]||[])[0]===T&&l[1]),!1===x)while(p=++d&&p&&p[g]||(x=d=0)||h.pop())if((s?p.nodeName.toLowerCase()===v:1===p.nodeType)&&++x&&(m&&((c=(f=p[b]||(p[b]={}))[p.uniqueID]||(f[p.uniqueID]={}))[e]=[T,x]),p===t))break;return(x-=i)===r||x%r==0&&x/r>=0}}},PSEUDO:function(e,t){var n,i=r.pseudos[e]||r.setFilters[e.toLowerCase()]||oe.error("unsupported pseudo: "+e);return i[b]?i(t):i.length>1?(n=[e,e,"",t],r.setFilters.hasOwnProperty(e.toLowerCase())?se(function(e,n){var r,o=i(e,t),a=o.length;while(a--)e[r=O(e,o[a])]=!(n[r]=o[a])}):function(e){return i(e,0,n)}):i}},pseudos:{not:se(function(e){var t=[],n=[],r=s(e.replace(B,"$1"));return r[b]?se(function(e,t,n,i){var o,a=r(e,null,i,[]),s=e.length;while(s--)(o=a[s])&&(e[s]=!(t[s]=o))}):function(e,i,o){return t[0]=e,r(t,null,o,n),t[0]=null,!n.pop()}}),has:se(function(e){return function(t){return oe(e,t).length>0}}),contains:se(function(e){return e=e.replace(Z,ee),function(t){return(t.textContent||t.innerText||i(t)).indexOf(e)>-1}}),lang:se(function(e){return U.test(e||"")||oe.error("unsupported lang: "+e),e=e.replace(Z,ee).toLowerCase(),function(t){var n;do{if(n=g?t.lang:t.getAttribute("xml:lang")||t.getAttribute("lang"))return(n=n.toLowerCase())===e||0===n.indexOf(e+"-")}while((t=t.parentNode)&&1===t.nodeType);return!1}}),target:function(t){var n=e.location&&e.location.hash;return n&&n.slice(1)===t.id},root:function(e){return e===h},focus:function(e){return e===d.activeElement&&(!d.hasFocus||d.hasFocus())&&!!(e.type||e.href||~e.tabIndex)},enabled:de(!1),disabled:de(!0),checked:function(e){var t=e.nodeName.toLowerCase();return"input"===t&&!!e.checked||"option"===t&&!!e.selected},selected:function(e){return e.parentNode&&e.parentNode.selectedIndex,!0===e.selected},empty:function(e){for(e=e.firstChild;e;e=e.nextSibling)if(e.nodeType<6)return!1;return!0},parent:function(e){return!r.pseudos.empty(e)},header:function(e){return Y.test(e.nodeName)},input:function(e){return G.test(e.nodeName)},button:function(e){var t=e.nodeName.toLowerCase();return"input"===t&&"button"===e.type||"button"===t},text:function(e){var t;return"input"===e.nodeName.toLowerCase()&&"text"===e.type&&(null==(t=e.getAttribute("type"))||"text"===t.toLowerCase())},first:he(function(){return[0]}),last:he(function(e,t){return[t-1]}),eq:he(function(e,t,n){return[n<0?n+t:n]}),even:he(function(e,t){for(var n=0;n<t;n+=2)e.push(n);return e}),odd:he(function(e,t){for(var n=1;n<t;n+=2)e.push(n);return e}),lt:he(function(e,t,n){for(var r=n<0?n+t:n;--r>=0;)e.push(r);return e}),gt:he(function(e,t,n){for(var r=n<0?n+t:n;++r<t;)e.push(r);return e})}}).pseudos.nth=r.pseudos.eq;for(t in{radio:!0,checkbox:!0,file:!0,password:!0,image:!0})r.pseudos[t]=fe(t);for(t in{submit:!0,reset:!0})r.pseudos[t]=pe(t);function ye(){}ye.prototype=r.filters=r.pseudos,r.setFilters=new ye,a=oe.tokenize=function(e,t){var n,i,o,a,s,u,l,c=k[e+" "];if(c)return t?0:c.slice(0);s=e,u=[],l=r.preFilter;while(s){n&&!(i=F.exec(s))||(i&&(s=s.slice(i[0].length)||s),u.push(o=[])),n=!1,(i=_.exec(s))&&(n=i.shift(),o.push({value:n,type:i[0].replace(B," ")}),s=s.slice(n.length));for(a in r.filter)!(i=V[a].exec(s))||l[a]&&!(i=l[a](i))||(n=i.shift(),o.push({value:n,type:a,matches:i}),s=s.slice(n.length));if(!n)break}return t?s.length:s?oe.error(e):k(e,u).slice(0)};function ve(e){for(var t=0,n=e.length,r="";t<n;t++)r+=e[t].value;return r}function me(e,t,n){var r=t.dir,i=t.next,o=i||r,a=n&&"parentNode"===o,s=C++;return t.first?function(t,n,i){while(t=t[r])if(1===t.nodeType||a)return e(t,n,i);return!1}:function(t,n,u){var l,c,f,p=[T,s];if(u){while(t=t[r])if((1===t.nodeType||a)&&e(t,n,u))return!0}else while(t=t[r])if(1===t.nodeType||a)if(f=t[b]||(t[b]={}),c=f[t.uniqueID]||(f[t.uniqueID]={}),i&&i===t.nodeName.toLowerCase())t=t[r]||t;else{if((l=c[o])&&l[0]===T&&l[1]===s)return p[2]=l[2];if(c[o]=p,p[2]=e(t,n,u))return!0}return!1}}function xe(e){return e.length>1?function(t,n,r){var i=e.length;while(i--)if(!e[i](t,n,r))return!1;return!0}:e[0]}function be(e,t,n){for(var r=0,i=t.length;r<i;r++)oe(e,t[r],n);return n}function we(e,t,n,r,i){for(var o,a=[],s=0,u=e.length,l=null!=t;s<u;s++)(o=e[s])&&(n&&!n(o,r,i)||(a.push(o),l&&t.push(s)));return a}function Te(e,t,n,r,i,o){return r&&!r[b]&&(r=Te(r)),i&&!i[b]&&(i=Te(i,o)),se(function(o,a,s,u){var l,c,f,p=[],d=[],h=a.length,g=o||be(t||"*",s.nodeType?[s]:s,[]),y=!e||!o&&t?g:we(g,p,e,s,u),v=n?i||(o?e:h||r)?[]:a:y;if(n&&n(y,v,s,u),r){l=we(v,d),r(l,[],s,u),c=l.length;while(c--)(f=l[c])&&(v[d[c]]=!(y[d[c]]=f))}if(o){if(i||e){if(i){l=[],c=v.length;while(c--)(f=v[c])&&l.push(y[c]=f);i(null,v=[],l,u)}c=v.length;while(c--)(f=v[c])&&(l=i?O(o,f):p[c])>-1&&(o[l]=!(a[l]=f))}}else v=we(v===a?v.splice(h,v.length):v),i?i(null,a,v,u):L.apply(a,v)})}function Ce(e){for(var t,n,i,o=e.length,a=r.relative[e[0].type],s=a||r.relative[" "],u=a?1:0,c=me(function(e){return e===t},s,!0),f=me(function(e){return O(t,e)>-1},s,!0),p=[function(e,n,r){var i=!a&&(r||n!==l)||((t=n).nodeType?c(e,n,r):f(e,n,r));return t=null,i}];u<o;u++)if(n=r.relative[e[u].type])p=[me(xe(p),n)];else{if((n=r.filter[e[u].type].apply(null,e[u].matches))[b]){for(i=++u;i<o;i++)if(r.relative[e[i].type])break;return Te(u>1&&xe(p),u>1&&ve(e.slice(0,u-1).concat({value:" "===e[u-2].type?"*":""})).replace(B,"$1"),n,u<i&&Ce(e.slice(u,i)),i<o&&Ce(e=e.slice(i)),i<o&&ve(e))}p.push(n)}return xe(p)}function Ee(e,t){var n=t.length>0,i=e.length>0,o=function(o,a,s,u,c){var f,h,y,v=0,m="0",x=o&&[],b=[],w=l,C=o||i&&r.find.TAG("*",c),E=T+=null==w?1:Math.random()||.1,k=C.length;for(c&&(l=a===d||a||c);m!==k&&null!=(f=C[m]);m++){if(i&&f){h=0,a||f.ownerDocument===d||(p(f),s=!g);while(y=e[h++])if(y(f,a||d,s)){u.push(f);break}c&&(T=E)}n&&((f=!y&&f)&&v--,o&&x.push(f))}if(v+=m,n&&m!==v){h=0;while(y=t[h++])y(x,b,a,s);if(o){if(v>0)while(m--)x[m]||b[m]||(b[m]=j.call(u));b=we(b)}L.apply(u,b),c&&!o&&b.length>0&&v+t.length>1&&oe.uniqueSort(u)}return c&&(T=E,l=w),x};return n?se(o):o}return s=oe.compile=function(e,t){var n,r=[],i=[],o=S[e+" "];if(!o){t||(t=a(e)),n=t.length;while(n--)(o=Ce(t[n]))[b]?r.push(o):i.push(o);(o=S(e,Ee(i,r))).selector=e}return o},u=oe.select=function(e,t,n,i){var o,u,l,c,f,p="function"==typeof e&&e,d=!i&&a(e=p.selector||e);if(n=n||[],1===d.length){if((u=d[0]=d[0].slice(0)).length>2&&"ID"===(l=u[0]).type&&9===t.nodeType&&g&&r.relative[u[1].type]){if(!(t=(r.find.ID(l.matches[0].replace(Z,ee),t)||[])[0]))return n;p&&(t=t.parentNode),e=e.slice(u.shift().value.length)}o=V.needsContext.test(e)?0:u.length;while(o--){if(l=u[o],r.relative[c=l.type])break;if((f=r.find[c])&&(i=f(l.matches[0].replace(Z,ee),K.test(u[0].type)&&ge(t.parentNode)||t))){if(u.splice(o,1),!(e=i.length&&ve(u)))return L.apply(n,i),n;break}}}return(p||s(e,d))(i,t,!g,n,!t||K.test(e)&&ge(t.parentNode)||t),n},n.sortStable=b.split("").sort(D).join("")===b,n.detectDuplicates=!!f,p(),n.sortDetached=ue(function(e){return 1&e.compareDocumentPosition(d.createElement("fieldset"))}),ue(function(e){return e.innerHTML="<a href='#'></a>","#"===e.firstChild.getAttribute("href")})||le("type|href|height|width",function(e,t,n){if(!n)return e.getAttribute(t,"type"===t.toLowerCase()?1:2)}),n.attributes&&ue(function(e){return e.innerHTML="<input/>",e.firstChild.setAttribute("value",""),""===e.firstChild.getAttribute("value")})||le("value",function(e,t,n){if(!n&&"input"===e.nodeName.toLowerCase())return e.defaultValue}),ue(function(e){return null==e.getAttribute("disabled")})||le(P,function(e,t,n){var r;if(!n)return!0===e[t]?t.toLowerCase():(r=e.getAttributeNode(t))&&r.specified?r.value:null}),oe}(e);w.find=E,w.expr=E.selectors,w.expr[":"]=w.expr.pseudos,w.uniqueSort=w.unique=E.uniqueSort,w.text=E.getText,w.isXMLDoc=E.isXML,w.contains=E.contains,w.escapeSelector=E.escape;var k=function(e,t,n){var r=[],i=void 0!==n;while((e=e[t])&&9!==e.nodeType)if(1===e.nodeType){if(i&&w(e).is(n))break;r.push(e)}return r},S=function(e,t){for(var n=[];e;e=e.nextSibling)1===e.nodeType&&e!==t&&n.push(e);return n},D=w.expr.match.needsContext;function N(e,t){return e.nodeName&&e.nodeName.toLowerCase()===t.toLowerCase()}var A=/^<([a-z][^\/\0>:\x20\t\r\n\f]*)[\x20\t\r\n\f]*\/?>(?:<\/\1>|)$/i;function j(e,t,n){return g(t)?w.grep(e,function(e,r){return!!t.call(e,r,e)!==n}):t.nodeType?w.grep(e,function(e){return e===t!==n}):"string"!=typeof t?w.grep(e,function(e){return u.call(t,e)>-1!==n}):w.filter(t,e,n)}w.filter=function(e,t,n){var r=t[0];return n&&(e=":not("+e+")"),1===t.length&&1===r.nodeType?w.find.matchesSelector(r,e)?[r]:[]:w.find.matches(e,w.grep(t,function(e){return 1===e.nodeType}))},w.fn.extend({find:function(e){var t,n,r=this.length,i=this;if("string"!=typeof e)return this.pushStack(w(e).filter(function(){for(t=0;t<r;t++)if(w.contains(i[t],this))return!0}));for(n=this.pushStack([]),t=0;t<r;t++)w.find(e,i[t],n);return r>1?w.uniqueSort(n):n},filter:function(e){return this.pushStack(j(this,e||[],!1))},not:function(e){return this.pushStack(j(this,e||[],!0))},is:function(e){return!!j(this,"string"==typeof e&&D.test(e)?w(e):e||[],!1).length}});var q,L=/^(?:\s*(<[\w\W]+>)[^>]*|#([\w-]+))$/;(w.fn.init=function(e,t,n){var i,o;if(!e)return this;if(n=n||q,"string"==typeof e){if(!(i="<"===e[0]&&">"===e[e.length-1]&&e.length>=3?[null,e,null]:L.exec(e))||!i[1]&&t)return!t||t.jquery?(t||n).find(e):this.constructor(t).find(e);if(i[1]){if(t=t instanceof w?t[0]:t,w.merge(this,w.parseHTML(i[1],t&&t.nodeType?t.ownerDocument||t:r,!0)),A.test(i[1])&&w.isPlainObject(t))for(i in t)g(this[i])?this[i](t[i]):this.attr(i,t[i]);return this}return(o=r.getElementById(i[2]))&&(this[0]=o,this.length=1),this}return e.nodeType?(this[0]=e,this.length=1,this):g(e)?void 0!==n.ready?n.ready(e):e(w):w.makeArray(e,this)}).prototype=w.fn,q=w(r);var H=/^(?:parents|prev(?:Until|All))/,O={children:!0,contents:!0,next:!0,prev:!0};w.fn.extend({has:function(e){var t=w(e,this),n=t.length;return this.filter(function(){for(var e=0;e<n;e++)if(w.contains(this,t[e]))return!0})},closest:function(e,t){var n,r=0,i=this.length,o=[],a="string"!=typeof e&&w(e);if(!D.test(e))for(;r<i;r++)for(n=this[r];n&&n!==t;n=n.parentNode)if(n.nodeType<11&&(a?a.index(n)>-1:1===n.nodeType&&w.find.matchesSelector(n,e))){o.push(n);break}return this.pushStack(o.length>1?w.uniqueSort(o):o)},index:function(e){return e?"string"==typeof e?u.call(w(e),this[0]):u.call(this,e.jquery?e[0]:e):this[0]&&this[0].parentNode?this.first().prevAll().length:-1},add:function(e,t){return this.pushStack(w.uniqueSort(w.merge(this.get(),w(e,t))))},addBack:function(e){return this.add(null==e?this.prevObject:this.prevObject.filter(e))}});function P(e,t){while((e=e[t])&&1!==e.nodeType);return e}w.each({parent:function(e){var t=e.parentNode;return t&&11!==t.nodeType?t:null},parents:function(e){return k(e,"parentNode")},parentsUntil:function(e,t,n){return k(e,"parentNode",n)},next:function(e){return P(e,"nextSibling")},prev:function(e){return P(e,"previousSibling")},nextAll:function(e){return k(e,"nextSibling")},prevAll:function(e){return k(e,"previousSibling")},nextUntil:function(e,t,n){return k(e,"nextSibling",n)},prevUntil:function(e,t,n){return k(e,"previousSibling",n)},siblings:function(e){return S((e.parentNode||{}).firstChild,e)},children:function(e){return S(e.firstChild)},contents:function(e){return N(e,"iframe")?e.contentDocument:(N(e,"template")&&(e=e.content||e),w.merge([],e.childNodes))}},function(e,t){w.fn[e]=function(n,r){var i=w.map(this,t,n);return"Until"!==e.slice(-5)&&(r=n),r&&"string"==typeof r&&(i=w.filter(r,i)),this.length>1&&(O[e]||w.uniqueSort(i),H.test(e)&&i.reverse()),this.pushStack(i)}});var M=/[^\x20\t\r\n\f]+/g;function R(e){var t={};return w.each(e.match(M)||[],function(e,n){t[n]=!0}),t}w.Callbacks=function(e){e="string"==typeof e?R(e):w.extend({},e);var t,n,r,i,o=[],a=[],s=-1,u=function(){for(i=i||e.once,r=t=!0;a.length;s=-1){n=a.shift();while(++s<o.length)!1===o[s].apply(n[0],n[1])&&e.stopOnFalse&&(s=o.length,n=!1)}e.memory||(n=!1),t=!1,i&&(o=n?[]:"")},l={add:function(){return o&&(n&&!t&&(s=o.length-1,a.push(n)),function t(n){w.each(n,function(n,r){g(r)?e.unique&&l.has(r)||o.push(r):r&&r.length&&"string"!==x(r)&&t(r)})}(arguments),n&&!t&&u()),this},remove:function(){return w.each(arguments,function(e,t){var n;while((n=w.inArray(t,o,n))>-1)o.splice(n,1),n<=s&&s--}),this},has:function(e){return e?w.inArray(e,o)>-1:o.length>0},empty:function(){return o&&(o=[]),this},disable:function(){return i=a=[],o=n="",this},disabled:function(){return!o},lock:function(){return i=a=[],n||t||(o=n=""),this},locked:function(){return!!i},fireWith:function(e,n){return i||(n=[e,(n=n||[]).slice?n.slice():n],a.push(n),t||u()),this},fire:function(){return l.fireWith(this,arguments),this},fired:function(){return!!r}};return l};function I(e){return e}function W(e){throw e}function $(e,t,n,r){var i;try{e&&g(i=e.promise)?i.call(e).done(t).fail(n):e&&g(i=e.then)?i.call(e,t,n):t.apply(void 0,[e].slice(r))}catch(e){n.apply(void 0,[e])}}w.extend({Deferred:function(t){var n=[["notify","progress",w.Callbacks("memory"),w.Callbacks("memory"),2],["resolve","done",w.Callbacks("once memory"),w.Callbacks("once memory"),0,"resolved"],["reject","fail",w.Callbacks("once memory"),w.Callbacks("once memory"),1,"rejected"]],r="pending",i={state:function(){return r},always:function(){return o.done(arguments).fail(arguments),this},"catch":function(e){return i.then(null,e)},pipe:function(){var e=arguments;return w.Deferred(function(t){w.each(n,function(n,r){var i=g(e[r[4]])&&e[r[4]];o[r[1]](function(){var e=i&&i.apply(this,arguments);e&&g(e.promise)?e.promise().progress(t.notify).done(t.resolve).fail(t.reject):t[r[0]+"With"](this,i?[e]:arguments)})}),e=null}).promise()},then:function(t,r,i){var o=0;function a(t,n,r,i){return function(){var s=this,u=arguments,l=function(){var e,l;if(!(t<o)){if((e=r.apply(s,u))===n.promise())throw new TypeError("Thenable self-resolution");l=e&&("object"==typeof e||"function"==typeof e)&&e.then,g(l)?i?l.call(e,a(o,n,I,i),a(o,n,W,i)):(o++,l.call(e,a(o,n,I,i),a(o,n,W,i),a(o,n,I,n.notifyWith))):(r!==I&&(s=void 0,u=[e]),(i||n.resolveWith)(s,u))}},c=i?l:function(){try{l()}catch(e){w.Deferred.exceptionHook&&w.Deferred.exceptionHook(e,c.stackTrace),t+1>=o&&(r!==W&&(s=void 0,u=[e]),n.rejectWith(s,u))}};t?c():(w.Deferred.getStackHook&&(c.stackTrace=w.Deferred.getStackHook()),e.setTimeout(c))}}return w.Deferred(function(e){n[0][3].add(a(0,e,g(i)?i:I,e.notifyWith)),n[1][3].add(a(0,e,g(t)?t:I)),n[2][3].add(a(0,e,g(r)?r:W))}).promise()},promise:function(e){return null!=e?w.extend(e,i):i}},o={};return w.each(n,function(e,t){var a=t[2],s=t[5];i[t[1]]=a.add,s&&a.add(function(){r=s},n[3-e][2].disable,n[3-e][3].disable,n[0][2].lock,n[0][3].lock),a.add(t[3].fire),o[t[0]]=function(){return o[t[0]+"With"](this===o?void 0:this,arguments),this},o[t[0]+"With"]=a.fireWith}),i.promise(o),t&&t.call(o,o),o},when:function(e){var t=arguments.length,n=t,r=Array(n),i=o.call(arguments),a=w.Deferred(),s=function(e){return function(n){r[e]=this,i[e]=arguments.length>1?o.call(arguments):n,--t||a.resolveWith(r,i)}};if(t<=1&&($(e,a.done(s(n)).resolve,a.reject,!t),"pending"===a.state()||g(i[n]&&i[n].then)))return a.then();while(n--)$(i[n],s(n),a.reject);return a.promise()}});var B=/^(Eval|Internal|Range|Reference|Syntax|Type|URI)Error$/;w.Deferred.exceptionHook=function(t,n){e.console&&e.console.warn&&t&&B.test(t.name)&&e.console.warn("jQuery.Deferred exception: "+t.message,t.stack,n)},w.readyException=function(t){e.setTimeout(function(){throw t})};var F=w.Deferred();w.fn.ready=function(e){return F.then(e)["catch"](function(e){w.readyException(e)}),this},w.extend({isReady:!1,readyWait:1,ready:function(e){(!0===e?--w.readyWait:w.isReady)||(w.isReady=!0,!0!==e&&--w.readyWait>0||F.resolveWith(r,[w]))}}),w.ready.then=F.then;function _(){r.removeEventListener("DOMContentLoaded",_),e.removeEventListener("load",_),w.ready()}"complete"===r.readyState||"loading"!==r.readyState&&!r.documentElement.doScroll?e.setTimeout(w.ready):(r.addEventListener("DOMContentLoaded",_),e.addEventListener("load",_));var z=function(e,t,n,r,i,o,a){var s=0,u=e.length,l=null==n;if("object"===x(n)){i=!0;for(s in n)z(e,t,s,n[s],!0,o,a)}else if(void 0!==r&&(i=!0,g(r)||(a=!0),l&&(a?(t.call(e,r),t=null):(l=t,t=function(e,t,n){return l.call(w(e),n)})),t))for(;s<u;s++)t(e[s],n,a?r:r.call(e[s],s,t(e[s],n)));return i?e:l?t.call(e):u?t(e[0],n):o},X=/^-ms-/,U=/-([a-z])/g;function V(e,t){return t.toUpperCase()}function G(e){return e.replace(X,"ms-").replace(U,V)}var Y=function(e){return 1===e.nodeType||9===e.nodeType||!+e.nodeType};function Q(){this.expando=w.expando+Q.uid++}Q.uid=1,Q.prototype={cache:function(e){var t=e[this.expando];return t||(t={},Y(e)&&(e.nodeType?e[this.expando]=t:Object.defineProperty(e,this.expando,{value:t,configurable:!0}))),t},set:function(e,t,n){var r,i=this.cache(e);if("string"==typeof t)i[G(t)]=n;else for(r in t)i[G(r)]=t[r];return i},get:function(e,t){return void 0===t?this.cache(e):e[this.expando]&&e[this.expando][G(t)]},access:function(e,t,n){return void 0===t||t&&"string"==typeof t&&void 0===n?this.get(e,t):(this.set(e,t,n),void 0!==n?n:t)},remove:function(e,t){var n,r=e[this.expando];if(void 0!==r){if(void 0!==t){n=(t=Array.isArray(t)?t.map(G):(t=G(t))in r?[t]:t.match(M)||[]).length;while(n--)delete r[t[n]]}(void 0===t||w.isEmptyObject(r))&&(e.nodeType?e[this.expando]=void 0:delete e[this.expando])}},hasData:function(e){var t=e[this.expando];return void 0!==t&&!w.isEmptyObject(t)}};var J=new Q,K=new Q,Z=/^(?:\{[\w\W]*\}|\[[\w\W]*\])$/,ee=/[A-Z]/g;function te(e){return"true"===e||"false"!==e&&("null"===e?null:e===+e+""?+e:Z.test(e)?JSON.parse(e):e)}function ne(e,t,n){var r;if(void 0===n&&1===e.nodeType)if(r="data-"+t.replace(ee,"-$&").toLowerCase(),"string"==typeof(n=e.getAttribute(r))){try{n=te(n)}catch(e){}K.set(e,t,n)}else n=void 0;return n}w.extend({hasData:function(e){return K.hasData(e)||J.hasData(e)},data:function(e,t,n){return K.access(e,t,n)},removeData:function(e,t){K.remove(e,t)},_data:function(e,t,n){return J.access(e,t,n)},_removeData:function(e,t){J.remove(e,t)}}),w.fn.extend({data:function(e,t){var n,r,i,o=this[0],a=o&&o.attributes;if(void 0===e){if(this.length&&(i=K.get(o),1===o.nodeType&&!J.get(o,"hasDataAttrs"))){n=a.length;while(n--)a[n]&&0===(r=a[n].name).indexOf("data-")&&(r=G(r.slice(5)),ne(o,r,i[r]));J.set(o,"hasDataAttrs",!0)}return i}return"object"==typeof e?this.each(function(){K.set(this,e)}):z(this,function(t){var n;if(o&&void 0===t){if(void 0!==(n=K.get(o,e)))return n;if(void 0!==(n=ne(o,e)))return n}else this.each(function(){K.set(this,e,t)})},null,t,arguments.length>1,null,!0)},removeData:function(e){return this.each(function(){K.remove(this,e)})}}),w.extend({queue:function(e,t,n){var r;if(e)return t=(t||"fx")+"queue",r=J.get(e,t),n&&(!r||Array.isArray(n)?r=J.access(e,t,w.makeArray(n)):r.push(n)),r||[]},dequeue:function(e,t){t=t||"fx";var n=w.queue(e,t),r=n.length,i=n.shift(),o=w._queueHooks(e,t),a=function(){w.dequeue(e,t)};"inprogress"===i&&(i=n.shift(),r--),i&&("fx"===t&&n.unshift("inprogress"),delete o.stop,i.call(e,a,o)),!r&&o&&o.empty.fire()},_queueHooks:function(e,t){var n=t+"queueHooks";return J.get(e,n)||J.access(e,n,{empty:w.Callbacks("once memory").add(function(){J.remove(e,[t+"queue",n])})})}}),w.fn.extend({queue:function(e,t){var n=2;return"string"!=typeof e&&(t=e,e="fx",n--),arguments.length<n?w.queue(this[0],e):void 0===t?this:this.each(function(){var n=w.queue(this,e,t);w._queueHooks(this,e),"fx"===e&&"inprogress"!==n[0]&&w.dequeue(this,e)})},dequeue:function(e){return this.each(function(){w.dequeue(this,e)})},clearQueue:function(e){return this.queue(e||"fx",[])},promise:function(e,t){var n,r=1,i=w.Deferred(),o=this,a=this.length,s=function(){--r||i.resolveWith(o,[o])};"string"!=typeof e&&(t=e,e=void 0),e=e||"fx";while(a--)(n=J.get(o[a],e+"queueHooks"))&&n.empty&&(r++,n.empty.add(s));return s(),i.promise(t)}});var re=/[+-]?(?:\d*\.|)\d+(?:[eE][+-]?\d+|)/.source,ie=new RegExp("^(?:([+-])=|)("+re+")([a-z%]*)$","i"),oe=["Top","Right","Bottom","Left"],ae=function(e,t){return"none"===(e=t||e).style.display||""===e.style.display&&w.contains(e.ownerDocument,e)&&"none"===w.css(e,"display")},se=function(e,t,n,r){var i,o,a={};for(o in t)a[o]=e.style[o],e.style[o]=t[o];i=n.apply(e,r||[]);for(o in t)e.style[o]=a[o];return i};function ue(e,t,n,r){var i,o,a=20,s=r?function(){return r.cur()}:function(){return w.css(e,t,"")},u=s(),l=n&&n[3]||(w.cssNumber[t]?"":"px"),c=(w.cssNumber[t]||"px"!==l&&+u)&&ie.exec(w.css(e,t));if(c&&c[3]!==l){u/=2,l=l||c[3],c=+u||1;while(a--)w.style(e,t,c+l),(1-o)*(1-(o=s()/u||.5))<=0&&(a=0),c/=o;c*=2,w.style(e,t,c+l),n=n||[]}return n&&(c=+c||+u||0,i=n[1]?c+(n[1]+1)*n[2]:+n[2],r&&(r.unit=l,r.start=c,r.end=i)),i}var le={};function ce(e){var t,n=e.ownerDocument,r=e.nodeName,i=le[r];return i||(t=n.body.appendChild(n.createElement(r)),i=w.css(t,"display"),t.parentNode.removeChild(t),"none"===i&&(i="block"),le[r]=i,i)}function fe(e,t){for(var n,r,i=[],o=0,a=e.length;o<a;o++)(r=e[o]).style&&(n=r.style.display,t?("none"===n&&(i[o]=J.get(r,"display")||null,i[o]||(r.style.display="")),""===r.style.display&&ae(r)&&(i[o]=ce(r))):"none"!==n&&(i[o]="none",J.set(r,"display",n)));for(o=0;o<a;o++)null!=i[o]&&(e[o].style.display=i[o]);return e}w.fn.extend({show:function(){return fe(this,!0)},hide:function(){return fe(this)},toggle:function(e){return"boolean"==typeof e?e?this.show():this.hide():this.each(function(){ae(this)?w(this).show():w(this).hide()})}});var pe=/^(?:checkbox|radio)$/i,de=/<([a-z][^\/\0>\x20\t\r\n\f]+)/i,he=/^$|^module$|\/(?:java|ecma)script/i,ge={option:[1,"<select multiple='multiple'>","</select>"],thead:[1,"<table>","</table>"],col:[2,"<table><colgroup>","</colgroup></table>"],tr:[2,"<table><tbody>","</tbody></table>"],td:[3,"<table><tbody><tr>","</tr></tbody></table>"],_default:[0,"",""]};ge.optgroup=ge.option,ge.tbody=ge.tfoot=ge.colgroup=ge.caption=ge.thead,ge.th=ge.td;function ye(e,t){var n;return n="undefined"!=typeof e.getElementsByTagName?e.getElementsByTagName(t||"*"):"undefined"!=typeof e.querySelectorAll?e.querySelectorAll(t||"*"):[],void 0===t||t&&N(e,t)?w.merge([e],n):n}function ve(e,t){for(var n=0,r=e.length;n<r;n++)J.set(e[n],"globalEval",!t||J.get(t[n],"globalEval"))}var me=/<|&#?\w+;/;function xe(e,t,n,r,i){for(var o,a,s,u,l,c,f=t.createDocumentFragment(),p=[],d=0,h=e.length;d<h;d++)if((o=e[d])||0===o)if("object"===x(o))w.merge(p,o.nodeType?[o]:o);else if(me.test(o)){a=a||f.appendChild(t.createElement("div")),s=(de.exec(o)||["",""])[1].toLowerCase(),u=ge[s]||ge._default,a.innerHTML=u[1]+w.htmlPrefilter(o)+u[2],c=u[0];while(c--)a=a.lastChild;w.merge(p,a.childNodes),(a=f.firstChild).textContent=""}else p.push(t.createTextNode(o));f.textContent="",d=0;while(o=p[d++])if(r&&w.inArray(o,r)>-1)i&&i.push(o);else if(l=w.contains(o.ownerDocument,o),a=ye(f.appendChild(o),"script"),l&&ve(a),n){c=0;while(o=a[c++])he.test(o.type||"")&&n.push(o)}return f}!function(){var e=r.createDocumentFragment().appendChild(r.createElement("div")),t=r.createElement("input");t.setAttribute("type","radio"),t.setAttribute("checked","checked"),t.setAttribute("name","t"),e.appendChild(t),h.checkClone=e.cloneNode(!0).cloneNode(!0).lastChild.checked,e.innerHTML="<textarea>x</textarea>",h.noCloneChecked=!!e.cloneNode(!0).lastChild.defaultValue}();var be=r.documentElement,we=/^key/,Te=/^(?:mouse|pointer|contextmenu|drag|drop)|click/,Ce=/^([^.]*)(?:\.(.+)|)/;function Ee(){return!0}function ke(){return!1}function Se(){try{return r.activeElement}catch(e){}}function De(e,t,n,r,i,o){var a,s;if("object"==typeof t){"string"!=typeof n&&(r=r||n,n=void 0);for(s in t)De(e,s,n,r,t[s],o);return e}if(null==r&&null==i?(i=n,r=n=void 0):null==i&&("string"==typeof n?(i=r,r=void 0):(i=r,r=n,n=void 0)),!1===i)i=ke;else if(!i)return e;return 1===o&&(a=i,(i=function(e){return w().off(e),a.apply(this,arguments)}).guid=a.guid||(a.guid=w.guid++)),e.each(function(){w.event.add(this,t,i,r,n)})}w.event={global:{},add:function(e,t,n,r,i){var o,a,s,u,l,c,f,p,d,h,g,y=J.get(e);if(y){n.handler&&(n=(o=n).handler,i=o.selector),i&&w.find.matchesSelector(be,i),n.guid||(n.guid=w.guid++),(u=y.events)||(u=y.events={}),(a=y.handle)||(a=y.handle=function(t){return"undefined"!=typeof w&&w.event.triggered!==t.type?w.event.dispatch.apply(e,arguments):void 0}),l=(t=(t||"").match(M)||[""]).length;while(l--)d=g=(s=Ce.exec(t[l])||[])[1],h=(s[2]||"").split(".").sort(),d&&(f=w.event.special[d]||{},d=(i?f.delegateType:f.bindType)||d,f=w.event.special[d]||{},c=w.extend({type:d,origType:g,data:r,handler:n,guid:n.guid,selector:i,needsContext:i&&w.expr.match.needsContext.test(i),namespace:h.join(".")},o),(p=u[d])||((p=u[d]=[]).delegateCount=0,f.setup&&!1!==f.setup.call(e,r,h,a)||e.addEventListener&&e.addEventListener(d,a)),f.add&&(f.add.call(e,c),c.handler.guid||(c.handler.guid=n.guid)),i?p.splice(p.delegateCount++,0,c):p.push(c),w.event.global[d]=!0)}},remove:function(e,t,n,r,i){var o,a,s,u,l,c,f,p,d,h,g,y=J.hasData(e)&&J.get(e);if(y&&(u=y.events)){l=(t=(t||"").match(M)||[""]).length;while(l--)if(s=Ce.exec(t[l])||[],d=g=s[1],h=(s[2]||"").split(".").sort(),d){f=w.event.special[d]||{},p=u[d=(r?f.delegateType:f.bindType)||d]||[],s=s[2]&&new RegExp("(^|\\.)"+h.join("\\.(?:.*\\.|)")+"(\\.|$)"),a=o=p.length;while(o--)c=p[o],!i&&g!==c.origType||n&&n.guid!==c.guid||s&&!s.test(c.namespace)||r&&r!==c.selector&&("**"!==r||!c.selector)||(p.splice(o,1),c.selector&&p.delegateCount--,f.remove&&f.remove.call(e,c));a&&!p.length&&(f.teardown&&!1!==f.teardown.call(e,h,y.handle)||w.removeEvent(e,d,y.handle),delete u[d])}else for(d in u)w.event.remove(e,d+t[l],n,r,!0);w.isEmptyObject(u)&&J.remove(e,"handle events")}},dispatch:function(e){var t=w.event.fix(e),n,r,i,o,a,s,u=new Array(arguments.length),l=(J.get(this,"events")||{})[t.type]||[],c=w.event.special[t.type]||{};for(u[0]=t,n=1;n<arguments.length;n++)u[n]=arguments[n];if(t.delegateTarget=this,!c.preDispatch||!1!==c.preDispatch.call(this,t)){s=w.event.handlers.call(this,t,l),n=0;while((o=s[n++])&&!t.isPropagationStopped()){t.currentTarget=o.elem,r=0;while((a=o.handlers[r++])&&!t.isImmediatePropagationStopped())t.rnamespace&&!t.rnamespace.test(a.namespace)||(t.handleObj=a,t.data=a.data,void 0!==(i=((w.event.special[a.origType]||{}).handle||a.handler).apply(o.elem,u))&&!1===(t.result=i)&&(t.preventDefault(),t.stopPropagation()))}return c.postDispatch&&c.postDispatch.call(this,t),t.result}},handlers:function(e,t){var n,r,i,o,a,s=[],u=t.delegateCount,l=e.target;if(u&&l.nodeType&&!("click"===e.type&&e.button>=1))for(;l!==this;l=l.parentNode||this)if(1===l.nodeType&&("click"!==e.type||!0!==l.disabled)){for(o=[],a={},n=0;n<u;n++)void 0===a[i=(r=t[n]).selector+" "]&&(a[i]=r.needsContext?w(i,this).index(l)>-1:w.find(i,this,null,[l]).length),a[i]&&o.push(r);o.length&&s.push({elem:l,handlers:o})}return l=this,u<t.length&&s.push({elem:l,handlers:t.slice(u)}),s},addProp:function(e,t){Object.defineProperty(w.Event.prototype,e,{enumerable:!0,configurable:!0,get:g(t)?function(){if(this.originalEvent)return t(this.originalEvent)}:function(){if(this.originalEvent)return this.originalEvent[e]},set:function(t){Object.defineProperty(this,e,{enumerable:!0,configurable:!0,writable:!0,value:t})}})},fix:function(e){return e[w.expando]?e:new w.Event(e)},special:{load:{noBubble:!0},focus:{trigger:function(){if(this!==Se()&&this.focus)return this.focus(),!1},delegateType:"focusin"},blur:{trigger:function(){if(this===Se()&&this.blur)return this.blur(),!1},delegateType:"focusout"},click:{trigger:function(){if("checkbox"===this.type&&this.click&&N(this,"input"))return this.click(),!1},_default:function(e){return N(e.target,"a")}},beforeunload:{postDispatch:function(e){void 0!==e.result&&e.originalEvent&&(e.originalEvent.returnValue=e.result)}}}},w.removeEvent=function(e,t,n){e.removeEventListener&&e.removeEventListener(t,n)},w.Event=function(e,t){if(!(this instanceof w.Event))return new w.Event(e,t);e&&e.type?(this.originalEvent=e,this.type=e.type,this.isDefaultPrevented=e.defaultPrevented||void 0===e.defaultPrevented&&!1===e.returnValue?Ee:ke,this.target=e.target&&3===e.target.nodeType?e.target.parentNode:e.target,this.currentTarget=e.currentTarget,this.relatedTarget=e.relatedTarget):this.type=e,t&&w.extend(this,t),this.timeStamp=e&&e.timeStamp||Date.now(),this[w.expando]=!0},w.Event.prototype={constructor:w.Event,isDefaultPrevented:ke,isPropagationStopped:ke,isImmediatePropagationStopped:ke,isSimulated:!1,preventDefault:function(){var e=this.originalEvent;this.isDefaultPrevented=Ee,e&&!this.isSimulated&&e.preventDefault()},stopPropagation:function(){var e=this.originalEvent;this.isPropagationStopped=Ee,e&&!this.isSimulated&&e.stopPropagation()},stopImmediatePropagation:function(){var e=this.originalEvent;this.isImmediatePropagationStopped=Ee,e&&!this.isSimulated&&e.stopImmediatePropagation(),this.stopPropagation()}},w.each({altKey:!0,bubbles:!0,cancelable:!0,changedTouches:!0,ctrlKey:!0,detail:!0,eventPhase:!0,metaKey:!0,pageX:!0,pageY:!0,shiftKey:!0,view:!0,"char":!0,charCode:!0,key:!0,keyCode:!0,button:!0,buttons:!0,clientX:!0,clientY:!0,offsetX:!0,offsetY:!0,pointerId:!0,pointerType:!0,screenX:!0,screenY:!0,targetTouches:!0,toElement:!0,touches:!0,which:function(e){var t=e.button;return null==e.which&&we.test(e.type)?null!=e.charCode?e.charCode:e.keyCode:!e.which&&void 0!==t&&Te.test(e.type)?1&t?1:2&t?3:4&t?2:0:e.which}},w.event.addProp),w.each({mouseenter:"mouseover",mouseleave:"mouseout",pointerenter:"pointerover",pointerleave:"pointerout"},function(e,t){w.event.special[e]={delegateType:t,bindType:t,handle:function(e){var n,r=this,i=e.relatedTarget,o=e.handleObj;return i&&(i===r||w.contains(r,i))||(e.type=o.origType,n=o.handler.apply(this,arguments),e.type=t),n}}}),w.fn.extend({on:function(e,t,n,r){return De(this,e,t,n,r)},one:function(e,t,n,r){return De(this,e,t,n,r,1)},off:function(e,t,n){var r,i;if(e&&e.preventDefault&&e.handleObj)return r=e.handleObj,w(e.delegateTarget).off(r.namespace?r.origType+"."+r.namespace:r.origType,r.selector,r.handler),this;if("object"==typeof e){for(i in e)this.off(i,t,e[i]);return this}return!1!==t&&"function"!=typeof t||(n=t,t=void 0),!1===n&&(n=ke),this.each(function(){w.event.remove(this,e,n,t)})}});var Ne=/<(?!area|br|col|embed|hr|img|input|link|meta|param)(([a-z][^\/\0>\x20\t\r\n\f]*)[^>]*)\/>/gi,Ae=/<script|<style|<link/i,je=/checked\s*(?:[^=]|=\s*.checked.)/i,qe=/^\s*<!(?:\[CDATA\[|--)|(?:\]\]|--)>\s*$/g;function Le(e,t){return N(e,"table")&&N(11!==t.nodeType?t:t.firstChild,"tr")?w(e).children("tbody")[0]||e:e}function He(e){return e.type=(null!==e.getAttribute("type"))+"/"+e.type,e}function Oe(e){return"true/"===(e.type||"").slice(0,5)?e.type=e.type.slice(5):e.removeAttribute("type"),e}function Pe(e,t){var n,r,i,o,a,s,u,l;if(1===t.nodeType){if(J.hasData(e)&&(o=J.access(e),a=J.set(t,o),l=o.events)){delete a.handle,a.events={};for(i in l)for(n=0,r=l[i].length;n<r;n++)w.event.add(t,i,l[i][n])}K.hasData(e)&&(s=K.access(e),u=w.extend({},s),K.set(t,u))}}function Me(e,t){var n=t.nodeName.toLowerCase();"input"===n&&pe.test(e.type)?t.checked=e.checked:"input"!==n&&"textarea"!==n||(t.defaultValue=e.defaultValue)}function Re(e,t,n,r){t=a.apply([],t);var i,o,s,u,l,c,f=0,p=e.length,d=p-1,y=t[0],v=g(y);if(v||p>1&&"string"==typeof y&&!h.checkClone&&je.test(y))return e.each(function(i){var o=e.eq(i);v&&(t[0]=y.call(this,i,o.html())),Re(o,t,n,r)});if(p&&(i=xe(t,e[0].ownerDocument,!1,e,r),o=i.firstChild,1===i.childNodes.length&&(i=o),o||r)){for(u=(s=w.map(ye(i,"script"),He)).length;f<p;f++)l=i,f!==d&&(l=w.clone(l,!0,!0),u&&w.merge(s,ye(l,"script"))),n.call(e[f],l,f);if(u)for(c=s[s.length-1].ownerDocument,w.map(s,Oe),f=0;f<u;f++)l=s[f],he.test(l.type||"")&&!J.access(l,"globalEval")&&w.contains(c,l)&&(l.src&&"module"!==(l.type||"").toLowerCase()?w._evalUrl&&w._evalUrl(l.src):m(l.textContent.replace(qe,""),c,l))}return e}function Ie(e,t,n){for(var r,i=t?w.filter(t,e):e,o=0;null!=(r=i[o]);o++)n||1!==r.nodeType||w.cleanData(ye(r)),r.parentNode&&(n&&w.contains(r.ownerDocument,r)&&ve(ye(r,"script")),r.parentNode.removeChild(r));return e}w.extend({htmlPrefilter:function(e){return e.replace(Ne,"<$1></$2>")},clone:function(e,t,n){var r,i,o,a,s=e.cloneNode(!0),u=w.contains(e.ownerDocument,e);if(!(h.noCloneChecked||1!==e.nodeType&&11!==e.nodeType||w.isXMLDoc(e)))for(a=ye(s),r=0,i=(o=ye(e)).length;r<i;r++)Me(o[r],a[r]);if(t)if(n)for(o=o||ye(e),a=a||ye(s),r=0,i=o.length;r<i;r++)Pe(o[r],a[r]);else Pe(e,s);return(a=ye(s,"script")).length>0&&ve(a,!u&&ye(e,"script")),s},cleanData:function(e){for(var t,n,r,i=w.event.special,o=0;void 0!==(n=e[o]);o++)if(Y(n)){if(t=n[J.expando]){if(t.events)for(r in t.events)i[r]?w.event.remove(n,r):w.removeEvent(n,r,t.handle);n[J.expando]=void 0}n[K.expando]&&(n[K.expando]=void 0)}}}),w.fn.extend({detach:function(e){return Ie(this,e,!0)},remove:function(e){return Ie(this,e)},text:function(e){return z(this,function(e){return void 0===e?w.text(this):this.empty().each(function(){1!==this.nodeType&&11!==this.nodeType&&9!==this.nodeType||(this.textContent=e)})},null,e,arguments.length)},append:function(){return Re(this,arguments,function(e){1!==this.nodeType&&11!==this.nodeType&&9!==this.nodeType||Le(this,e).appendChild(e)})},prepend:function(){return Re(this,arguments,function(e){if(1===this.nodeType||11===this.nodeType||9===this.nodeType){var t=Le(this,e);t.insertBefore(e,t.firstChild)}})},before:function(){return Re(this,arguments,function(e){this.parentNode&&this.parentNode.insertBefore(e,this)})},after:function(){return Re(this,arguments,function(e){this.parentNode&&this.parentNode.insertBefore(e,this.nextSibling)})},empty:function(){for(var e,t=0;null!=(e=this[t]);t++)1===e.nodeType&&(w.cleanData(ye(e,!1)),e.textContent="");return this},clone:function(e,t){return e=null!=e&&e,t=null==t?e:t,this.map(function(){return w.clone(this,e,t)})},html:function(e){return z(this,function(e){var t=this[0]||{},n=0,r=this.length;if(void 0===e&&1===t.nodeType)return t.innerHTML;if("string"==typeof e&&!Ae.test(e)&&!ge[(de.exec(e)||["",""])[1].toLowerCase()]){e=w.htmlPrefilter(e);try{for(;n<r;n++)1===(t=this[n]||{}).nodeType&&(w.cleanData(ye(t,!1)),t.innerHTML=e);t=0}catch(e){}}t&&this.empty().append(e)},null,e,arguments.length)},replaceWith:function(){var e=[];return Re(this,arguments,function(t){var n=this.parentNode;w.inArray(this,e)<0&&(w.cleanData(ye(this)),n&&n.replaceChild(t,this))},e)}}),w.each({appendTo:"append",prependTo:"prepend",insertBefore:"before",insertAfter:"after",replaceAll:"replaceWith"},function(e,t){w.fn[e]=function(e){for(var n,r=[],i=w(e),o=i.length-1,a=0;a<=o;a++)n=a===o?this:this.clone(!0),w(i[a])[t](n),s.apply(r,n.get());return this.pushStack(r)}});var We=new RegExp("^("+re+")(?!px)[a-z%]+$","i"),$e=function(t){var n=t.ownerDocument.defaultView;return n&&n.opener||(n=e),n.getComputedStyle(t)},Be=new RegExp(oe.join("|"),"i");!function(){function t(){if(c){l.style.cssText="position:absolute;left:-11111px;width:60px;margin-top:1px;padding:0;border:0",c.style.cssText="position:relative;display:block;box-sizing:border-box;overflow:scroll;margin:auto;border:1px;padding:1px;width:60%;top:1%",be.appendChild(l).appendChild(c);var t=e.getComputedStyle(c);i="1%"!==t.top,u=12===n(t.marginLeft),c.style.right="60%",s=36===n(t.right),o=36===n(t.width),c.style.position="absolute",a=36===c.offsetWidth||"absolute",be.removeChild(l),c=null}}function n(e){return Math.round(parseFloat(e))}var i,o,a,s,u,l=r.createElement("div"),c=r.createElement("div");c.style&&(c.style.backgroundClip="content-box",c.cloneNode(!0).style.backgroundClip="",h.clearCloneStyle="content-box"===c.style.backgroundClip,w.extend(h,{boxSizingReliable:function(){return t(),o},pixelBoxStyles:function(){return t(),s},pixelPosition:function(){return t(),i},reliableMarginLeft:function(){return t(),u},scrollboxSize:function(){return t(),a}}))}();function Fe(e,t,n){var r,i,o,a,s=e.style;return(n=n||$e(e))&&(""!==(a=n.getPropertyValue(t)||n[t])||w.contains(e.ownerDocument,e)||(a=w.style(e,t)),!h.pixelBoxStyles()&&We.test(a)&&Be.test(t)&&(r=s.width,i=s.minWidth,o=s.maxWidth,s.minWidth=s.maxWidth=s.width=a,a=n.width,s.width=r,s.minWidth=i,s.maxWidth=o)),void 0!==a?a+"":a}function _e(e,t){return{get:function(){if(!e())return(this.get=t).apply(this,arguments);delete this.get}}}var ze=/^(none|table(?!-c[ea]).+)/,Xe=/^--/,Ue={position:"absolute",visibility:"hidden",display:"block"},Ve={letterSpacing:"0",fontWeight:"400"},Ge=["Webkit","Moz","ms"],Ye=r.createElement("div").style;function Qe(e){if(e in Ye)return e;var t=e[0].toUpperCase()+e.slice(1),n=Ge.length;while(n--)if((e=Ge[n]+t)in Ye)return e}function Je(e){var t=w.cssProps[e];return t||(t=w.cssProps[e]=Qe(e)||e),t}function Ke(e,t,n){var r=ie.exec(t);return r?Math.max(0,r[2]-(n||0))+(r[3]||"px"):t}function Ze(e,t,n,r,i,o){var a="width"===t?1:0,s=0,u=0;if(n===(r?"border":"content"))return 0;for(;a<4;a+=2)"margin"===n&&(u+=w.css(e,n+oe[a],!0,i)),r?("content"===n&&(u-=w.css(e,"padding"+oe[a],!0,i)),"margin"!==n&&(u-=w.css(e,"border"+oe[a]+"Width",!0,i))):(u+=w.css(e,"padding"+oe[a],!0,i),"padding"!==n?u+=w.css(e,"border"+oe[a]+"Width",!0,i):s+=w.css(e,"border"+oe[a]+"Width",!0,i));return!r&&o>=0&&(u+=Math.max(0,Math.ceil(e["offset"+t[0].toUpperCase()+t.slice(1)]-o-u-s-.5))),u}function et(e,t,n){var r=$e(e),i=Fe(e,t,r),o="border-box"===w.css(e,"boxSizing",!1,r),a=o;if(We.test(i)){if(!n)return i;i="auto"}return a=a&&(h.boxSizingReliable()||i===e.style[t]),("auto"===i||!parseFloat(i)&&"inline"===w.css(e,"display",!1,r))&&(i=e["offset"+t[0].toUpperCase()+t.slice(1)],a=!0),(i=parseFloat(i)||0)+Ze(e,t,n||(o?"border":"content"),a,r,i)+"px"}w.extend({cssHooks:{opacity:{get:function(e,t){if(t){var n=Fe(e,"opacity");return""===n?"1":n}}}},cssNumber:{animationIterationCount:!0,columnCount:!0,fillOpacity:!0,flexGrow:!0,flexShrink:!0,fontWeight:!0,lineHeight:!0,opacity:!0,order:!0,orphans:!0,widows:!0,zIndex:!0,zoom:!0},cssProps:{},style:function(e,t,n,r){if(e&&3!==e.nodeType&&8!==e.nodeType&&e.style){var i,o,a,s=G(t),u=Xe.test(t),l=e.style;if(u||(t=Je(s)),a=w.cssHooks[t]||w.cssHooks[s],void 0===n)return a&&"get"in a&&void 0!==(i=a.get(e,!1,r))?i:l[t];"string"==(o=typeof n)&&(i=ie.exec(n))&&i[1]&&(n=ue(e,t,i),o="number"),null!=n&&n===n&&("number"===o&&(n+=i&&i[3]||(w.cssNumber[s]?"":"px")),h.clearCloneStyle||""!==n||0!==t.indexOf("background")||(l[t]="inherit"),a&&"set"in a&&void 0===(n=a.set(e,n,r))||(u?l.setProperty(t,n):l[t]=n))}},css:function(e,t,n,r){var i,o,a,s=G(t);return Xe.test(t)||(t=Je(s)),(a=w.cssHooks[t]||w.cssHooks[s])&&"get"in a&&(i=a.get(e,!0,n)),void 0===i&&(i=Fe(e,t,r)),"normal"===i&&t in Ve&&(i=Ve[t]),""===n||n?(o=parseFloat(i),!0===n||isFinite(o)?o||0:i):i}}),w.each(["height","width"],function(e,t){w.cssHooks[t]={get:function(e,n,r){if(n)return!ze.test(w.css(e,"display"))||e.getClientRects().length&&e.getBoundingClientRect().width?et(e,t,r):se(e,Ue,function(){return et(e,t,r)})},set:function(e,n,r){var i,o=$e(e),a="border-box"===w.css(e,"boxSizing",!1,o),s=r&&Ze(e,t,r,a,o);return a&&h.scrollboxSize()===o.position&&(s-=Math.ceil(e["offset"+t[0].toUpperCase()+t.slice(1)]-parseFloat(o[t])-Ze(e,t,"border",!1,o)-.5)),s&&(i=ie.exec(n))&&"px"!==(i[3]||"px")&&(e.style[t]=n,n=w.css(e,t)),Ke(e,n,s)}}}),w.cssHooks.marginLeft=_e(h.reliableMarginLeft,function(e,t){if(t)return(parseFloat(Fe(e,"marginLeft"))||e.getBoundingClientRect().left-se(e,{marginLeft:0},function(){return e.getBoundingClientRect().left}))+"px"}),w.each({margin:"",padding:"",border:"Width"},function(e,t){w.cssHooks[e+t]={expand:function(n){for(var r=0,i={},o="string"==typeof n?n.split(" "):[n];r<4;r++)i[e+oe[r]+t]=o[r]||o[r-2]||o[0];return i}},"margin"!==e&&(w.cssHooks[e+t].set=Ke)}),w.fn.extend({css:function(e,t){return z(this,function(e,t,n){var r,i,o={},a=0;if(Array.isArray(t)){for(r=$e(e),i=t.length;a<i;a++)o[t[a]]=w.css(e,t[a],!1,r);return o}return void 0!==n?w.style(e,t,n):w.css(e,t)},e,t,arguments.length>1)}});function tt(e,t,n,r,i){return new tt.prototype.init(e,t,n,r,i)}w.Tween=tt,tt.prototype={constructor:tt,init:function(e,t,n,r,i,o){this.elem=e,this.prop=n,this.easing=i||w.easing._default,this.options=t,this.start=this.now=this.cur(),this.end=r,this.unit=o||(w.cssNumber[n]?"":"px")},cur:function(){var e=tt.propHooks[this.prop];return e&&e.get?e.get(this):tt.propHooks._default.get(this)},run:function(e){var t,n=tt.propHooks[this.prop];return this.options.duration?this.pos=t=w.easing[this.easing](e,this.options.duration*e,0,1,this.options.duration):this.pos=t=e,this.now=(this.end-this.start)*t+this.start,this.options.step&&this.options.step.call(this.elem,this.now,this),n&&n.set?n.set(this):tt.propHooks._default.set(this),this}},tt.prototype.init.prototype=tt.prototype,tt.propHooks={_default:{get:function(e){var t;return 1!==e.elem.nodeType||null!=e.elem[e.prop]&&null==e.elem.style[e.prop]?e.elem[e.prop]:(t=w.css(e.elem,e.prop,""))&&"auto"!==t?t:0},set:function(e){w.fx.step[e.prop]?w.fx.step[e.prop](e):1!==e.elem.nodeType||null==e.elem.style[w.cssProps[e.prop]]&&!w.cssHooks[e.prop]?e.elem[e.prop]=e.now:w.style(e.elem,e.prop,e.now+e.unit)}}},tt.propHooks.scrollTop=tt.propHooks.scrollLeft={set:function(e){e.elem.nodeType&&e.elem.parentNode&&(e.elem[e.prop]=e.now)}},w.easing={linear:function(e){return e},swing:function(e){return.5-Math.cos(e*Math.PI)/2},_default:"swing"},w.fx=tt.prototype.init,w.fx.step={};var nt,rt,it=/^(?:toggle|show|hide)$/,ot=/queueHooks$/;function at(){rt&&(!1===r.hidden&&e.requestAnimationFrame?e.requestAnimationFrame(at):e.setTimeout(at,w.fx.interval),w.fx.tick())}function st(){return e.setTimeout(function(){nt=void 0}),nt=Date.now()}function ut(e,t){var n,r=0,i={height:e};for(t=t?1:0;r<4;r+=2-t)i["margin"+(n=oe[r])]=i["padding"+n]=e;return t&&(i.opacity=i.width=e),i}function lt(e,t,n){for(var r,i=(pt.tweeners[t]||[]).concat(pt.tweeners["*"]),o=0,a=i.length;o<a;o++)if(r=i[o].call(n,t,e))return r}function ct(e,t,n){var r,i,o,a,s,u,l,c,f="width"in t||"height"in t,p=this,d={},h=e.style,g=e.nodeType&&ae(e),y=J.get(e,"fxshow");n.queue||(null==(a=w._queueHooks(e,"fx")).unqueued&&(a.unqueued=0,s=a.empty.fire,a.empty.fire=function(){a.unqueued||s()}),a.unqueued++,p.always(function(){p.always(function(){a.unqueued--,w.queue(e,"fx").length||a.empty.fire()})}));for(r in t)if(i=t[r],it.test(i)){if(delete t[r],o=o||"toggle"===i,i===(g?"hide":"show")){if("show"!==i||!y||void 0===y[r])continue;g=!0}d[r]=y&&y[r]||w.style(e,r)}if((u=!w.isEmptyObject(t))||!w.isEmptyObject(d)){f&&1===e.nodeType&&(n.overflow=[h.overflow,h.overflowX,h.overflowY],null==(l=y&&y.display)&&(l=J.get(e,"display")),"none"===(c=w.css(e,"display"))&&(l?c=l:(fe([e],!0),l=e.style.display||l,c=w.css(e,"display"),fe([e]))),("inline"===c||"inline-block"===c&&null!=l)&&"none"===w.css(e,"float")&&(u||(p.done(function(){h.display=l}),null==l&&(c=h.display,l="none"===c?"":c)),h.display="inline-block")),n.overflow&&(h.overflow="hidden",p.always(function(){h.overflow=n.overflow[0],h.overflowX=n.overflow[1],h.overflowY=n.overflow[2]})),u=!1;for(r in d)u||(y?"hidden"in y&&(g=y.hidden):y=J.access(e,"fxshow",{display:l}),o&&(y.hidden=!g),g&&fe([e],!0),p.done(function(){g||fe([e]),J.remove(e,"fxshow");for(r in d)w.style(e,r,d[r])})),u=lt(g?y[r]:0,r,p),r in y||(y[r]=u.start,g&&(u.end=u.start,u.start=0))}}function ft(e,t){var n,r,i,o,a;for(n in e)if(r=G(n),i=t[r],o=e[n],Array.isArray(o)&&(i=o[1],o=e[n]=o[0]),n!==r&&(e[r]=o,delete e[n]),(a=w.cssHooks[r])&&"expand"in a){o=a.expand(o),delete e[r];for(n in o)n in e||(e[n]=o[n],t[n]=i)}else t[r]=i}function pt(e,t,n){var r,i,o=0,a=pt.prefilters.length,s=w.Deferred().always(function(){delete u.elem}),u=function(){if(i)return!1;for(var t=nt||st(),n=Math.max(0,l.startTime+l.duration-t),r=1-(n/l.duration||0),o=0,a=l.tweens.length;o<a;o++)l.tweens[o].run(r);return s.notifyWith(e,[l,r,n]),r<1&&a?n:(a||s.notifyWith(e,[l,1,0]),s.resolveWith(e,[l]),!1)},l=s.promise({elem:e,props:w.extend({},t),opts:w.extend(!0,{specialEasing:{},easing:w.easing._default},n),originalProperties:t,originalOptions:n,startTime:nt||st(),duration:n.duration,tweens:[],createTween:function(t,n){var r=w.Tween(e,l.opts,t,n,l.opts.specialEasing[t]||l.opts.easing);return l.tweens.push(r),r},stop:function(t){var n=0,r=t?l.tweens.length:0;if(i)return this;for(i=!0;n<r;n++)l.tweens[n].run(1);return t?(s.notifyWith(e,[l,1,0]),s.resolveWith(e,[l,t])):s.rejectWith(e,[l,t]),this}}),c=l.props;for(ft(c,l.opts.specialEasing);o<a;o++)if(r=pt.prefilters[o].call(l,e,c,l.opts))return g(r.stop)&&(w._queueHooks(l.elem,l.opts.queue).stop=r.stop.bind(r)),r;return w.map(c,lt,l),g(l.opts.start)&&l.opts.start.call(e,l),l.progress(l.opts.progress).done(l.opts.done,l.opts.complete).fail(l.opts.fail).always(l.opts.always),w.fx.timer(w.extend(u,{elem:e,anim:l,queue:l.opts.queue})),l}w.Animation=w.extend(pt,{tweeners:{"*":[function(e,t){var n=this.createTween(e,t);return ue(n.elem,e,ie.exec(t),n),n}]},tweener:function(e,t){g(e)?(t=e,e=["*"]):e=e.match(M);for(var n,r=0,i=e.length;r<i;r++)n=e[r],pt.tweeners[n]=pt.tweeners[n]||[],pt.tweeners[n].unshift(t)},prefilters:[ct],prefilter:function(e,t){t?pt.prefilters.unshift(e):pt.prefilters.push(e)}}),w.speed=function(e,t,n){var r=e&&"object"==typeof e?w.extend({},e):{complete:n||!n&&t||g(e)&&e,duration:e,easing:n&&t||t&&!g(t)&&t};return w.fx.off?r.duration=0:"number"!=typeof r.duration&&(r.duration in w.fx.speeds?r.duration=w.fx.speeds[r.duration]:r.duration=w.fx.speeds._default),null!=r.queue&&!0!==r.queue||(r.queue="fx"),r.old=r.complete,r.complete=function(){g(r.old)&&r.old.call(this),r.queue&&w.dequeue(this,r.queue)},r},w.fn.extend({fadeTo:function(e,t,n,r){return this.filter(ae).css("opacity",0).show().end().animate({opacity:t},e,n,r)},animate:function(e,t,n,r){var i=w.isEmptyObject(e),o=w.speed(t,n,r),a=function(){var t=pt(this,w.extend({},e),o);(i||J.get(this,"finish"))&&t.stop(!0)};return a.finish=a,i||!1===o.queue?this.each(a):this.queue(o.queue,a)},stop:function(e,t,n){var r=function(e){var t=e.stop;delete e.stop,t(n)};return"string"!=typeof e&&(n=t,t=e,e=void 0),t&&!1!==e&&this.queue(e||"fx",[]),this.each(function(){var t=!0,i=null!=e&&e+"queueHooks",o=w.timers,a=J.get(this);if(i)a[i]&&a[i].stop&&r(a[i]);else for(i in a)a[i]&&a[i].stop&&ot.test(i)&&r(a[i]);for(i=o.length;i--;)o[i].elem!==this||null!=e&&o[i].queue!==e||(o[i].anim.stop(n),t=!1,o.splice(i,1));!t&&n||w.dequeue(this,e)})},finish:function(e){return!1!==e&&(e=e||"fx"),this.each(function(){var t,n=J.get(this),r=n[e+"queue"],i=n[e+"queueHooks"],o=w.timers,a=r?r.length:0;for(n.finish=!0,w.queue(this,e,[]),i&&i.stop&&i.stop.call(this,!0),t=o.length;t--;)o[t].elem===this&&o[t].queue===e&&(o[t].anim.stop(!0),o.splice(t,1));for(t=0;t<a;t++)r[t]&&r[t].finish&&r[t].finish.call(this);delete n.finish})}}),w.each(["toggle","show","hide"],function(e,t){var n=w.fn[t];w.fn[t]=function(e,r,i){return null==e||"boolean"==typeof e?n.apply(this,arguments):this.animate(ut(t,!0),e,r,i)}}),w.each({slideDown:ut("show"),slideUp:ut("hide"),slideToggle:ut("toggle"),fadeIn:{opacity:"show"},fadeOut:{opacity:"hide"},fadeToggle:{opacity:"toggle"}},function(e,t){w.fn[e]=function(e,n,r){return this.animate(t,e,n,r)}}),w.timers=[],w.fx.tick=function(){var e,t=0,n=w.timers;for(nt=Date.now();t<n.length;t++)(e=n[t])()||n[t]!==e||n.splice(t--,1);n.length||w.fx.stop(),nt=void 0},w.fx.timer=function(e){w.timers.push(e),w.fx.start()},w.fx.interval=13,w.fx.start=function(){rt||(rt=!0,at())},w.fx.stop=function(){rt=null},w.fx.speeds={slow:600,fast:200,_default:400},w.fn.delay=function(t,n){return t=w.fx?w.fx.speeds[t]||t:t,n=n||"fx",this.queue(n,function(n,r){var i=e.setTimeout(n,t);r.stop=function(){e.clearTimeout(i)}})},function(){var e=r.createElement("input"),t=r.createElement("select").appendChild(r.createElement("option"));e.type="checkbox",h.checkOn=""!==e.value,h.optSelected=t.selected,(e=r.createElement("input")).value="t",e.type="radio",h.radioValue="t"===e.value}();var dt,ht=w.expr.attrHandle;w.fn.extend({attr:function(e,t){return z(this,w.attr,e,t,arguments.length>1)},removeAttr:function(e){return this.each(function(){w.removeAttr(this,e)})}}),w.extend({attr:function(e,t,n){var r,i,o=e.nodeType;if(3!==o&&8!==o&&2!==o)return"undefined"==typeof e.getAttribute?w.prop(e,t,n):(1===o&&w.isXMLDoc(e)||(i=w.attrHooks[t.toLowerCase()]||(w.expr.match.bool.test(t)?dt:void 0)),void 0!==n?null===n?void w.removeAttr(e,t):i&&"set"in i&&void 0!==(r=i.set(e,n,t))?r:(e.setAttribute(t,n+""),n):i&&"get"in i&&null!==(r=i.get(e,t))?r:null==(r=w.find.attr(e,t))?void 0:r)},attrHooks:{type:{set:function(e,t){if(!h.radioValue&&"radio"===t&&N(e,"input")){var n=e.value;return e.setAttribute("type",t),n&&(e.value=n),t}}}},removeAttr:function(e,t){var n,r=0,i=t&&t.match(M);if(i&&1===e.nodeType)while(n=i[r++])e.removeAttribute(n)}}),dt={set:function(e,t,n){return!1===t?w.removeAttr(e,n):e.setAttribute(n,n),n}},w.each(w.expr.match.bool.source.match(/\w+/g),function(e,t){var n=ht[t]||w.find.attr;ht[t]=function(e,t,r){var i,o,a=t.toLowerCase();return r||(o=ht[a],ht[a]=i,i=null!=n(e,t,r)?a:null,ht[a]=o),i}});var gt=/^(?:input|select|textarea|button)$/i,yt=/^(?:a|area)$/i;w.fn.extend({prop:function(e,t){return z(this,w.prop,e,t,arguments.length>1)},removeProp:function(e){return this.each(function(){delete this[w.propFix[e]||e]})}}),w.extend({prop:function(e,t,n){var r,i,o=e.nodeType;if(3!==o&&8!==o&&2!==o)return 1===o&&w.isXMLDoc(e)||(t=w.propFix[t]||t,i=w.propHooks[t]),void 0!==n?i&&"set"in i&&void 0!==(r=i.set(e,n,t))?r:e[t]=n:i&&"get"in i&&null!==(r=i.get(e,t))?r:e[t]},propHooks:{tabIndex:{get:function(e){var t=w.find.attr(e,"tabindex");return t?parseInt(t,10):gt.test(e.nodeName)||yt.test(e.nodeName)&&e.href?0:-1}}},propFix:{"for":"htmlFor","class":"className"}}),h.optSelected||(w.propHooks.selected={get:function(e){var t=e.parentNode;return t&&t.parentNode&&t.parentNode.selectedIndex,null},set:function(e){var t=e.parentNode;t&&(t.selectedIndex,t.parentNode&&t.parentNode.selectedIndex)}}),w.each(["tabIndex","readOnly","maxLength","cellSpacing","cellPadding","rowSpan","colSpan","useMap","frameBorder","contentEditable"],function(){w.propFix[this.toLowerCase()]=this});function vt(e){return(e.match(M)||[]).join(" ")}function mt(e){return e.getAttribute&&e.getAttribute("class")||""}function xt(e){return Array.isArray(e)?e:"string"==typeof e?e.match(M)||[]:[]}w.fn.extend({addClass:function(e){var t,n,r,i,o,a,s,u=0;if(g(e))return this.each(function(t){w(this).addClass(e.call(this,t,mt(this)))});if((t=xt(e)).length)while(n=this[u++])if(i=mt(n),r=1===n.nodeType&&" "+vt(i)+" "){a=0;while(o=t[a++])r.indexOf(" "+o+" ")<0&&(r+=o+" ");i!==(s=vt(r))&&n.setAttribute("class",s)}return this},removeClass:function(e){var t,n,r,i,o,a,s,u=0;if(g(e))return this.each(function(t){w(this).removeClass(e.call(this,t,mt(this)))});if(!arguments.length)return this.attr("class","");if((t=xt(e)).length)while(n=this[u++])if(i=mt(n),r=1===n.nodeType&&" "+vt(i)+" "){a=0;while(o=t[a++])while(r.indexOf(" "+o+" ")>-1)r=r.replace(" "+o+" "," ");i!==(s=vt(r))&&n.setAttribute("class",s)}return this},toggleClass:function(e,t){var n=typeof e,r="string"===n||Array.isArray(e);return"boolean"==typeof t&&r?t?this.addClass(e):this.removeClass(e):g(e)?this.each(function(n){w(this).toggleClass(e.call(this,n,mt(this),t),t)}):this.each(function(){var t,i,o,a;if(r){i=0,o=w(this),a=xt(e);while(t=a[i++])o.hasClass(t)?o.removeClass(t):o.addClass(t)}else void 0!==e&&"boolean"!==n||((t=mt(this))&&J.set(this,"__className__",t),this.setAttribute&&this.setAttribute("class",t||!1===e?"":J.get(this,"__className__")||""))})},hasClass:function(e){var t,n,r=0;t=" "+e+" ";while(n=this[r++])if(1===n.nodeType&&(" "+vt(mt(n))+" ").indexOf(t)>-1)return!0;return!1}});var bt=/\r/g;w.fn.extend({val:function(e){var t,n,r,i=this[0];{if(arguments.length)return r=g(e),this.each(function(n){var i;1===this.nodeType&&(null==(i=r?e.call(this,n,w(this).val()):e)?i="":"number"==typeof i?i+="":Array.isArray(i)&&(i=w.map(i,function(e){return null==e?"":e+""})),(t=w.valHooks[this.type]||w.valHooks[this.nodeName.toLowerCase()])&&"set"in t&&void 0!==t.set(this,i,"value")||(this.value=i))});if(i)return(t=w.valHooks[i.type]||w.valHooks[i.nodeName.toLowerCase()])&&"get"in t&&void 0!==(n=t.get(i,"value"))?n:"string"==typeof(n=i.value)?n.replace(bt,""):null==n?"":n}}}),w.extend({valHooks:{option:{get:function(e){var t=w.find.attr(e,"value");return null!=t?t:vt(w.text(e))}},select:{get:function(e){var t,n,r,i=e.options,o=e.selectedIndex,a="select-one"===e.type,s=a?null:[],u=a?o+1:i.length;for(r=o<0?u:a?o:0;r<u;r++)if(((n=i[r]).selected||r===o)&&!n.disabled&&(!n.parentNode.disabled||!N(n.parentNode,"optgroup"))){if(t=w(n).val(),a)return t;s.push(t)}return s},set:function(e,t){var n,r,i=e.options,o=w.makeArray(t),a=i.length;while(a--)((r=i[a]).selected=w.inArray(w.valHooks.option.get(r),o)>-1)&&(n=!0);return n||(e.selectedIndex=-1),o}}}}),w.each(["radio","checkbox"],function(){w.valHooks[this]={set:function(e,t){if(Array.isArray(t))return e.checked=w.inArray(w(e).val(),t)>-1}},h.checkOn||(w.valHooks[this].get=function(e){return null===e.getAttribute("value")?"on":e.value})}),h.focusin="onfocusin"in e;var wt=/^(?:focusinfocus|focusoutblur)$/,Tt=function(e){e.stopPropagation()};w.extend(w.event,{trigger:function(t,n,i,o){var a,s,u,l,c,p,d,h,v=[i||r],m=f.call(t,"type")?t.type:t,x=f.call(t,"namespace")?t.namespace.split("."):[];if(s=h=u=i=i||r,3!==i.nodeType&&8!==i.nodeType&&!wt.test(m+w.event.triggered)&&(m.indexOf(".")>-1&&(m=(x=m.split(".")).shift(),x.sort()),c=m.indexOf(":")<0&&"on"+m,t=t[w.expando]?t:new w.Event(m,"object"==typeof t&&t),t.isTrigger=o?2:3,t.namespace=x.join("."),t.rnamespace=t.namespace?new RegExp("(^|\\.)"+x.join("\\.(?:.*\\.|)")+"(\\.|$)"):null,t.result=void 0,t.target||(t.target=i),n=null==n?[t]:w.makeArray(n,[t]),d=w.event.special[m]||{},o||!d.trigger||!1!==d.trigger.apply(i,n))){if(!o&&!d.noBubble&&!y(i)){for(l=d.delegateType||m,wt.test(l+m)||(s=s.parentNode);s;s=s.parentNode)v.push(s),u=s;u===(i.ownerDocument||r)&&v.push(u.defaultView||u.parentWindow||e)}a=0;while((s=v[a++])&&!t.isPropagationStopped())h=s,t.type=a>1?l:d.bindType||m,(p=(J.get(s,"events")||{})[t.type]&&J.get(s,"handle"))&&p.apply(s,n),(p=c&&s[c])&&p.apply&&Y(s)&&(t.result=p.apply(s,n),!1===t.result&&t.preventDefault());return t.type=m,o||t.isDefaultPrevented()||d._default&&!1!==d._default.apply(v.pop(),n)||!Y(i)||c&&g(i[m])&&!y(i)&&((u=i[c])&&(i[c]=null),w.event.triggered=m,t.isPropagationStopped()&&h.addEventListener(m,Tt),i[m](),t.isPropagationStopped()&&h.removeEventListener(m,Tt),w.event.triggered=void 0,u&&(i[c]=u)),t.result}},simulate:function(e,t,n){var r=w.extend(new w.Event,n,{type:e,isSimulated:!0});w.event.trigger(r,null,t)}}),w.fn.extend({trigger:function(e,t){return this.each(function(){w.event.trigger(e,t,this)})},triggerHandler:function(e,t){var n=this[0];if(n)return w.event.trigger(e,t,n,!0)}}),h.focusin||w.each({focus:"focusin",blur:"focusout"},function(e,t){var n=function(e){w.event.simulate(t,e.target,w.event.fix(e))};w.event.special[t]={setup:function(){var r=this.ownerDocument||this,i=J.access(r,t);i||r.addEventListener(e,n,!0),J.access(r,t,(i||0)+1)},teardown:function(){var r=this.ownerDocument||this,i=J.access(r,t)-1;i?J.access(r,t,i):(r.removeEventListener(e,n,!0),J.remove(r,t))}}});var Ct=e.location,Et=Date.now(),kt=/\?/;w.parseXML=function(t){var n;if(!t||"string"!=typeof t)return null;try{n=(new e.DOMParser).parseFromString(t,"text/xml")}catch(e){n=void 0}return n&&!n.getElementsByTagName("parsererror").length||w.error("Invalid XML: "+t),n};var St=/\[\]$/,Dt=/\r?\n/g,Nt=/^(?:submit|button|image|reset|file)$/i,At=/^(?:input|select|textarea|keygen)/i;function jt(e,t,n,r){var i;if(Array.isArray(t))w.each(t,function(t,i){n||St.test(e)?r(e,i):jt(e+"["+("object"==typeof i&&null!=i?t:"")+"]",i,n,r)});else if(n||"object"!==x(t))r(e,t);else for(i in t)jt(e+"["+i+"]",t[i],n,r)}w.param=function(e,t){var n,r=[],i=function(e,t){var n=g(t)?t():t;r[r.length]=encodeURIComponent(e)+"="+encodeURIComponent(null==n?"":n)};if(Array.isArray(e)||e.jquery&&!w.isPlainObject(e))w.each(e,function(){i(this.name,this.value)});else for(n in e)jt(n,e[n],t,i);return r.join("&")},w.fn.extend({serialize:function(){return w.param(this.serializeArray())},serializeArray:function(){return this.map(function(){var e=w.prop(this,"elements");return e?w.makeArray(e):this}).filter(function(){var e=this.type;return this.name&&!w(this).is(":disabled")&&At.test(this.nodeName)&&!Nt.test(e)&&(this.checked||!pe.test(e))}).map(function(e,t){var n=w(this).val();return null==n?null:Array.isArray(n)?w.map(n,function(e){return{name:t.name,value:e.replace(Dt,"\r\n")}}):{name:t.name,value:n.replace(Dt,"\r\n")}}).get()}});var qt=/%20/g,Lt=/#.*$/,Ht=/([?&])_=[^&]*/,Ot=/^(.*?):[ \t]*([^\r\n]*)$/gm,Pt=/^(?:about|app|app-storage|.+-extension|file|res|widget):$/,Mt=/^(?:GET|HEAD)$/,Rt=/^\/\//,It={},Wt={},$t="*/".concat("*"),Bt=r.createElement("a");Bt.href=Ct.href;function Ft(e){return function(t,n){"string"!=typeof t&&(n=t,t="*");var r,i=0,o=t.toLowerCase().match(M)||[];if(g(n))while(r=o[i++])"+"===r[0]?(r=r.slice(1)||"*",(e[r]=e[r]||[]).unshift(n)):(e[r]=e[r]||[]).push(n)}}function _t(e,t,n,r){var i={},o=e===Wt;function a(s){var u;return i[s]=!0,w.each(e[s]||[],function(e,s){var l=s(t,n,r);return"string"!=typeof l||o||i[l]?o?!(u=l):void 0:(t.dataTypes.unshift(l),a(l),!1)}),u}return a(t.dataTypes[0])||!i["*"]&&a("*")}function zt(e,t){var n,r,i=w.ajaxSettings.flatOptions||{};for(n in t)void 0!==t[n]&&((i[n]?e:r||(r={}))[n]=t[n]);return r&&w.extend(!0,e,r),e}function Xt(e,t,n){var r,i,o,a,s=e.contents,u=e.dataTypes;while("*"===u[0])u.shift(),void 0===r&&(r=e.mimeType||t.getResponseHeader("Content-Type"));if(r)for(i in s)if(s[i]&&s[i].test(r)){u.unshift(i);break}if(u[0]in n)o=u[0];else{for(i in n){if(!u[0]||e.converters[i+" "+u[0]]){o=i;break}a||(a=i)}o=o||a}if(o)return o!==u[0]&&u.unshift(o),n[o]}function Ut(e,t,n,r){var i,o,a,s,u,l={},c=e.dataTypes.slice();if(c[1])for(a in e.converters)l[a.toLowerCase()]=e.converters[a];o=c.shift();while(o)if(e.responseFields[o]&&(n[e.responseFields[o]]=t),!u&&r&&e.dataFilter&&(t=e.dataFilter(t,e.dataType)),u=o,o=c.shift())if("*"===o)o=u;else if("*"!==u&&u!==o){if(!(a=l[u+" "+o]||l["* "+o]))for(i in l)if((s=i.split(" "))[1]===o&&(a=l[u+" "+s[0]]||l["* "+s[0]])){!0===a?a=l[i]:!0!==l[i]&&(o=s[0],c.unshift(s[1]));break}if(!0!==a)if(a&&e["throws"])t=a(t);else try{t=a(t)}catch(e){return{state:"parsererror",error:a?e:"No conversion from "+u+" to "+o}}}return{state:"success",data:t}}w.extend({active:0,lastModified:{},etag:{},ajaxSettings:{url:Ct.href,type:"GET",isLocal:Pt.test(Ct.protocol),global:!0,processData:!0,async:!0,contentType:"application/x-www-form-urlencoded; charset=UTF-8",accepts:{"*":$t,text:"text/plain",html:"text/html",xml:"application/xml, text/xml",json:"application/json, text/javascript"},contents:{xml:/\bxml\b/,html:/\bhtml/,json:/\bjson\b/},responseFields:{xml:"responseXML",text:"responseText",json:"responseJSON"},converters:{"* text":String,"text html":!0,"text json":JSON.parse,"text xml":w.parseXML},flatOptions:{url:!0,context:!0}},ajaxSetup:function(e,t){return t?zt(zt(e,w.ajaxSettings),t):zt(w.ajaxSettings,e)},ajaxPrefilter:Ft(It),ajaxTransport:Ft(Wt),ajax:function(t,n){"object"==typeof t&&(n=t,t=void 0),n=n||{};var i,o,a,s,u,l,c,f,p,d,h=w.ajaxSetup({},n),g=h.context||h,y=h.context&&(g.nodeType||g.jquery)?w(g):w.event,v=w.Deferred(),m=w.Callbacks("once memory"),x=h.statusCode||{},b={},T={},C="canceled",E={readyState:0,getResponseHeader:function(e){var t;if(c){if(!s){s={};while(t=Ot.exec(a))s[t[1].toLowerCase()]=t[2]}t=s[e.toLowerCase()]}return null==t?null:t},getAllResponseHeaders:function(){return c?a:null},setRequestHeader:function(e,t){return null==c&&(e=T[e.toLowerCase()]=T[e.toLowerCase()]||e,b[e]=t),this},overrideMimeType:function(e){return null==c&&(h.mimeType=e),this},statusCode:function(e){var t;if(e)if(c)E.always(e[E.status]);else for(t in e)x[t]=[x[t],e[t]];return this},abort:function(e){var t=e||C;return i&&i.abort(t),k(0,t),this}};if(v.promise(E),h.url=((t||h.url||Ct.href)+"").replace(Rt,Ct.protocol+"//"),h.type=n.method||n.type||h.method||h.type,h.dataTypes=(h.dataType||"*").toLowerCase().match(M)||[""],null==h.crossDomain){l=r.createElement("a");try{l.href=h.url,l.href=l.href,h.crossDomain=Bt.protocol+"//"+Bt.host!=l.protocol+"//"+l.host}catch(e){h.crossDomain=!0}}if(h.data&&h.processData&&"string"!=typeof h.data&&(h.data=w.param(h.data,h.traditional)),_t(It,h,n,E),c)return E;(f=w.event&&h.global)&&0==w.active++&&w.event.trigger("ajaxStart"),h.type=h.type.toUpperCase(),h.hasContent=!Mt.test(h.type),o=h.url.replace(Lt,""),h.hasContent?h.data&&h.processData&&0===(h.contentType||"").indexOf("application/x-www-form-urlencoded")&&(h.data=h.data.replace(qt,"+")):(d=h.url.slice(o.length),h.data&&(h.processData||"string"==typeof h.data)&&(o+=(kt.test(o)?"&":"?")+h.data,delete h.data),!1===h.cache&&(o=o.replace(Ht,"$1"),d=(kt.test(o)?"&":"?")+"_="+Et+++d),h.url=o+d),h.ifModified&&(w.lastModified[o]&&E.setRequestHeader("If-Modified-Since",w.lastModified[o]),w.etag[o]&&E.setRequestHeader("If-None-Match",w.etag[o])),(h.data&&h.hasContent&&!1!==h.contentType||n.contentType)&&E.setRequestHeader("Content-Type",h.contentType),E.setRequestHeader("Accept",h.dataTypes[0]&&h.accepts[h.dataTypes[0]]?h.accepts[h.dataTypes[0]]+("*"!==h.dataTypes[0]?", "+$t+"; q=0.01":""):h.accepts["*"]);for(p in h.headers)E.setRequestHeader(p,h.headers[p]);if(h.beforeSend&&(!1===h.beforeSend.call(g,E,h)||c))return E.abort();if(C="abort",m.add(h.complete),E.done(h.success),E.fail(h.error),i=_t(Wt,h,n,E)){if(E.readyState=1,f&&y.trigger("ajaxSend",[E,h]),c)return E;h.async&&h.timeout>0&&(u=e.setTimeout(function(){E.abort("timeout")},h.timeout));try{c=!1,i.send(b,k)}catch(e){if(c)throw e;k(-1,e)}}else k(-1,"No Transport");function k(t,n,r,s){var l,p,d,b,T,C=n;c||(c=!0,u&&e.clearTimeout(u),i=void 0,a=s||"",E.readyState=t>0?4:0,l=t>=200&&t<300||304===t,r&&(b=Xt(h,E,r)),b=Ut(h,b,E,l),l?(h.ifModified&&((T=E.getResponseHeader("Last-Modified"))&&(w.lastModified[o]=T),(T=E.getResponseHeader("etag"))&&(w.etag[o]=T)),204===t||"HEAD"===h.type?C="nocontent":304===t?C="notmodified":(C=b.state,p=b.data,l=!(d=b.error))):(d=C,!t&&C||(C="error",t<0&&(t=0))),E.status=t,E.statusText=(n||C)+"",l?v.resolveWith(g,[p,C,E]):v.rejectWith(g,[E,C,d]),E.statusCode(x),x=void 0,f&&y.trigger(l?"ajaxSuccess":"ajaxError",[E,h,l?p:d]),m.fireWith(g,[E,C]),f&&(y.trigger("ajaxComplete",[E,h]),--w.active||w.event.trigger("ajaxStop")))}return E},getJSON:function(e,t,n){return w.get(e,t,n,"json")},getScript:function(e,t){return w.get(e,void 0,t,"script")}}),w.each(["get","post"],function(e,t){w[t]=function(e,n,r,i){return g(n)&&(i=i||r,r=n,n=void 0),w.ajax(w.extend({url:e,type:t,dataType:i,data:n,success:r},w.isPlainObject(e)&&e))}}),w._evalUrl=function(e){return w.ajax({url:e,type:"GET",dataType:"script",cache:!0,async:!1,global:!1,"throws":!0})},w.fn.extend({wrapAll:function(e){var t;return this[0]&&(g(e)&&(e=e.call(this[0])),t=w(e,this[0].ownerDocument).eq(0).clone(!0),this[0].parentNode&&t.insertBefore(this[0]),t.map(function(){var e=this;while(e.firstElementChild)e=e.firstElementChild;return e}).append(this)),this},wrapInner:function(e){return g(e)?this.each(function(t){w(this).wrapInner(e.call(this,t))}):this.each(function(){var t=w(this),n=t.contents();n.length?n.wrapAll(e):t.append(e)})},wrap:function(e){var t=g(e);return this.each(function(n){w(this).wrapAll(t?e.call(this,n):e)})},unwrap:function(e){return this.parent(e).not("body").each(function(){w(this).replaceWith(this.childNodes)}),this}}),w.expr.pseudos.hidden=function(e){return!w.expr.pseudos.visible(e)},w.expr.pseudos.visible=function(e){return!!(e.offsetWidth||e.offsetHeight||e.getClientRects().length)},w.ajaxSettings.xhr=function(){try{return new e.XMLHttpRequest}catch(e){}};var Vt={0:200,1223:204},Gt=w.ajaxSettings.xhr();h.cors=!!Gt&&"withCredentials"in Gt,h.ajax=Gt=!!Gt,w.ajaxTransport(function(t){var n,r;if(h.cors||Gt&&!t.crossDomain)return{send:function(i,o){var a,s=t.xhr();if(s.open(t.type,t.url,t.async,t.username,t.password),t.xhrFields)for(a in t.xhrFields)s[a]=t.xhrFields[a];t.mimeType&&s.overrideMimeType&&s.overrideMimeType(t.mimeType),t.crossDomain||i["X-Requested-With"]||(i["X-Requested-With"]="XMLHttpRequest");for(a in i)s.setRequestHeader(a,i[a]);n=function(e){return function(){n&&(n=r=s.onload=s.onerror=s.onabort=s.ontimeout=s.onreadystatechange=null,"abort"===e?s.abort():"error"===e?"number"!=typeof s.status?o(0,"error"):o(s.status,s.statusText):o(Vt[s.status]||s.status,s.statusText,"text"!==(s.responseType||"text")||"string"!=typeof s.responseText?{binary:s.response}:{text:s.responseText},s.getAllResponseHeaders()))}},s.onload=n(),r=s.onerror=s.ontimeout=n("error"),void 0!==s.onabort?s.onabort=r:s.onreadystatechange=function(){4===s.readyState&&e.setTimeout(function(){n&&r()})},n=n("abort");try{s.send(t.hasContent&&t.data||null)}catch(e){if(n)throw e}},abort:function(){n&&n()}}}),w.ajaxPrefilter(function(e){e.crossDomain&&(e.contents.script=!1)}),w.ajaxSetup({accepts:{script:"text/javascript, application/javascript, application/ecmascript, application/x-ecmascript"},contents:{script:/\b(?:java|ecma)script\b/},converters:{"text script":function(e){return w.globalEval(e),e}}}),w.ajaxPrefilter("script",function(e){void 0===e.cache&&(e.cache=!1),e.crossDomain&&(e.type="GET")}),w.ajaxTransport("script",function(e){if(e.crossDomain){var t,n;return{send:function(i,o){t=w("<script>").prop({charset:e.scriptCharset,src:e.url}).on("load error",n=function(e){t.remove(),n=null,e&&o("error"===e.type?404:200,e.type)}),r.head.appendChild(t[0])},abort:function(){n&&n()}}}});var Yt=[],Qt=/(=)\?(?=&|$)|\?\?/;w.ajaxSetup({jsonp:"callback",jsonpCallback:function(){var e=Yt.pop()||w.expando+"_"+Et++;return this[e]=!0,e}}),w.ajaxPrefilter("json jsonp",function(t,n,r){var i,o,a,s=!1!==t.jsonp&&(Qt.test(t.url)?"url":"string"==typeof t.data&&0===(t.contentType||"").indexOf("application/x-www-form-urlencoded")&&Qt.test(t.data)&&"data");if(s||"jsonp"===t.dataTypes[0])return i=t.jsonpCallback=g(t.jsonpCallback)?t.jsonpCallback():t.jsonpCallback,s?t[s]=t[s].replace(Qt,"$1"+i):!1!==t.jsonp&&(t.url+=(kt.test(t.url)?"&":"?")+t.jsonp+"="+i),t.converters["script json"]=function(){return a||w.error(i+" was not called"),a[0]},t.dataTypes[0]="json",o=e[i],e[i]=function(){a=arguments},r.always(function(){void 0===o?w(e).removeProp(i):e[i]=o,t[i]&&(t.jsonpCallback=n.jsonpCallback,Yt.push(i)),a&&g(o)&&o(a[0]),a=o=void 0}),"script"}),h.createHTMLDocument=function(){var e=r.implementation.createHTMLDocument("").body;return e.innerHTML="<form></form><form></form>",2===e.childNodes.length}(),w.parseHTML=function(e,t,n){if("string"!=typeof e)return[];"boolean"==typeof t&&(n=t,t=!1);var i,o,a;return t||(h.createHTMLDocument?((i=(t=r.implementation.createHTMLDocument("")).createElement("base")).href=r.location.href,t.head.appendChild(i)):t=r),o=A.exec(e),a=!n&&[],o?[t.createElement(o[1])]:(o=xe([e],t,a),a&&a.length&&w(a).remove(),w.merge([],o.childNodes))},w.fn.load=function(e,t,n){var r,i,o,a=this,s=e.indexOf(" ");return s>-1&&(r=vt(e.slice(s)),e=e.slice(0,s)),g(t)?(n=t,t=void 0):t&&"object"==typeof t&&(i="POST"),a.length>0&&w.ajax({url:e,type:i||"GET",dataType:"html",data:t}).done(function(e){o=arguments,a.html(r?w("<div>").append(w.parseHTML(e)).find(r):e)}).always(n&&function(e,t){a.each(function(){n.apply(this,o||[e.responseText,t,e])})}),this},w.each(["ajaxStart","ajaxStop","ajaxComplete","ajaxError","ajaxSuccess","ajaxSend"],function(e,t){w.fn[t]=function(e){return this.on(t,e)}}),w.expr.pseudos.animated=function(e){return w.grep(w.timers,function(t){return e===t.elem}).length},w.offset={setOffset:function(e,t,n){var r,i,o,a,s,u,l,c=w.css(e,"position"),f=w(e),p={};"static"===c&&(e.style.position="relative"),s=f.offset(),o=w.css(e,"top"),u=w.css(e,"left"),(l=("absolute"===c||"fixed"===c)&&(o+u).indexOf("auto")>-1)?(a=(r=f.position()).top,i=r.left):(a=parseFloat(o)||0,i=parseFloat(u)||0),g(t)&&(t=t.call(e,n,w.extend({},s))),null!=t.top&&(p.top=t.top-s.top+a),null!=t.left&&(p.left=t.left-s.left+i),"using"in t?t.using.call(e,p):f.css(p)}},w.fn.extend({offset:function(e){if(arguments.length)return void 0===e?this:this.each(function(t){w.offset.setOffset(this,e,t)});var t,n,r=this[0];if(r)return r.getClientRects().length?(t=r.getBoundingClientRect(),n=r.ownerDocument.defaultView,{top:t.top+n.pageYOffset,left:t.left+n.pageXOffset}):{top:0,left:0}},position:function(){if(this[0]){var e,t,n,r=this[0],i={top:0,left:0};if("fixed"===w.css(r,"position"))t=r.getBoundingClientRect();else{t=this.offset(),n=r.ownerDocument,e=r.offsetParent||n.documentElement;while(e&&(e===n.body||e===n.documentElement)&&"static"===w.css(e,"position"))e=e.parentNode;e&&e!==r&&1===e.nodeType&&((i=w(e).offset()).top+=w.css(e,"borderTopWidth",!0),i.left+=w.css(e,"borderLeftWidth",!0))}return{top:t.top-i.top-w.css(r,"marginTop",!0),left:t.left-i.left-w.css(r,"marginLeft",!0)}}},offsetParent:function(){return this.map(function(){var e=this.offsetParent;while(e&&"static"===w.css(e,"position"))e=e.offsetParent;return e||be})}}),w.each({scrollLeft:"pageXOffset",scrollTop:"pageYOffset"},function(e,t){var n="pageYOffset"===t;w.fn[e]=function(r){return z(this,function(e,r,i){var o;if(y(e)?o=e:9===e.nodeType&&(o=e.defaultView),void 0===i)return o?o[t]:e[r];o?o.scrollTo(n?o.pageXOffset:i,n?i:o.pageYOffset):e[r]=i},e,r,arguments.length)}}),w.each(["top","left"],function(e,t){w.cssHooks[t]=_e(h.pixelPosition,function(e,n){if(n)return n=Fe(e,t),We.test(n)?w(e).position()[t]+"px":n})}),w.each({Height:"height",Width:"width"},function(e,t){w.each({padding:"inner"+e,content:t,"":"outer"+e},function(n,r){w.fn[r]=function(i,o){var a=arguments.length&&(n||"boolean"!=typeof i),s=n||(!0===i||!0===o?"margin":"border");return z(this,function(t,n,i){var o;return y(t)?0===r.indexOf("outer")?t["inner"+e]:t.document.documentElement["client"+e]:9===t.nodeType?(o=t.documentElement,Math.max(t.body["scroll"+e],o["scroll"+e],t.body["offset"+e],o["offset"+e],o["client"+e])):void 0===i?w.css(t,n,s):w.style(t,n,i,s)},t,a?i:void 0,a)}})}),w.each("blur focus focusin focusout resize scroll click dblclick mousedown mouseup mousemove mouseover mouseout mouseenter mouseleave change select submit keydown keypress keyup contextmenu".split(" "),function(e,t){w.fn[t]=function(e,n){return arguments.length>0?this.on(t,null,e,n):this.trigger(t)}}),w.fn.extend({hover:function(e,t){return this.mouseenter(e).mouseleave(t||e)}}),w.fn.extend({bind:function(e,t,n){return this.on(e,null,t,n)},unbind:function(e,t){return this.off(e,null,t)},delegate:function(e,t,n,r){return this.on(t,e,n,r)},undelegate:function(e,t,n){return 1===arguments.length?this.off(e,"**"):this.off(t,e||"**",n)}}),w.proxy=function(e,t){var n,r,i;if("string"==typeof t&&(n=e[t],t=e,e=n),g(e))return r=o.call(arguments,2),i=function(){return e.apply(t||this,r.concat(o.call(arguments)))},i.guid=e.guid=e.guid||w.guid++,i},w.holdReady=function(e){e?w.readyWait++:w.ready(!0)},w.isArray=Array.isArray,w.parseJSON=JSON.parse,w.nodeName=N,w.isFunction=g,w.isWindow=y,w.camelCase=G,w.type=x,w.now=Date.now,w.isNumeric=function(e){var t=w.type(e);return("number"===t||"string"===t)&&!isNaN(e-parseFloat(e))},"function"==typeof define&&define.amd&&define("jquery",[],function(){return w});var Jt=e.jQuery,Kt=e.$;return w.noConflict=function(t){return e.$===w&&(e.$=Kt),t&&e.jQuery===w&&(e.jQuery=Jt),w},t||(e.jQuery=e.$=w),w});
/*
 Copyright (C) Federico Zivolo 2018
 Distributed under the MIT License (license terms are at http://opensource.org/licenses/MIT).
 */(function(e,t){'object'==typeof exports&&'undefined'!=typeof module?module.exports=t():'function'==typeof define&&define.amd?define(t):e.Popper=t()})(this,function(){'use strict';function e(e){return e&&'[object Function]'==={}.toString.call(e)}function t(e,t){if(1!==e.nodeType)return[];var o=getComputedStyle(e,null);return t?o[t]:o}function o(e){return'HTML'===e.nodeName?e:e.parentNode||e.host}function n(e){if(!e)return document.body;switch(e.nodeName){case'HTML':case'BODY':return e.ownerDocument.body;case'#document':return e.body;}var i=t(e),r=i.overflow,p=i.overflowX,s=i.overflowY;return /(auto|scroll|overlay)/.test(r+s+p)?e:n(o(e))}function r(e){return 11===e?re:10===e?pe:re||pe}function p(e){if(!e)return document.documentElement;for(var o=r(10)?document.body:null,n=e.offsetParent;n===o&&e.nextElementSibling;)n=(e=e.nextElementSibling).offsetParent;var i=n&&n.nodeName;return i&&'BODY'!==i&&'HTML'!==i?-1!==['TD','TABLE'].indexOf(n.nodeName)&&'static'===t(n,'position')?p(n):n:e?e.ownerDocument.documentElement:document.documentElement}function s(e){var t=e.nodeName;return'BODY'!==t&&('HTML'===t||p(e.firstElementChild)===e)}function d(e){return null===e.parentNode?e:d(e.parentNode)}function a(e,t){if(!e||!e.nodeType||!t||!t.nodeType)return document.documentElement;var o=e.compareDocumentPosition(t)&Node.DOCUMENT_POSITION_FOLLOWING,n=o?e:t,i=o?t:e,r=document.createRange();r.setStart(n,0),r.setEnd(i,0);var l=r.commonAncestorContainer;if(e!==l&&t!==l||n.contains(i))return s(l)?l:p(l);var f=d(e);return f.host?a(f.host,t):a(e,d(t).host)}function l(e){var t=1<arguments.length&&void 0!==arguments[1]?arguments[1]:'top',o='top'===t?'scrollTop':'scrollLeft',n=e.nodeName;if('BODY'===n||'HTML'===n){var i=e.ownerDocument.documentElement,r=e.ownerDocument.scrollingElement||i;return r[o]}return e[o]}function f(e,t){var o=2<arguments.length&&void 0!==arguments[2]&&arguments[2],n=l(t,'top'),i=l(t,'left'),r=o?-1:1;return e.top+=n*r,e.bottom+=n*r,e.left+=i*r,e.right+=i*r,e}function m(e,t){var o='x'===t?'Left':'Top',n='Left'==o?'Right':'Bottom';return parseFloat(e['border'+o+'Width'],10)+parseFloat(e['border'+n+'Width'],10)}function h(e,t,o,n){return $(t['offset'+e],t['scroll'+e],o['client'+e],o['offset'+e],o['scroll'+e],r(10)?o['offset'+e]+n['margin'+('Height'===e?'Top':'Left')]+n['margin'+('Height'===e?'Bottom':'Right')]:0)}function c(){var e=document.body,t=document.documentElement,o=r(10)&&getComputedStyle(t);return{height:h('Height',e,t,o),width:h('Width',e,t,o)}}function g(e){return le({},e,{right:e.left+e.width,bottom:e.top+e.height})}function u(e){var o={};try{if(r(10)){o=e.getBoundingClientRect();var n=l(e,'top'),i=l(e,'left');o.top+=n,o.left+=i,o.bottom+=n,o.right+=i}else o=e.getBoundingClientRect()}catch(t){}var p={left:o.left,top:o.top,width:o.right-o.left,height:o.bottom-o.top},s='HTML'===e.nodeName?c():{},d=s.width||e.clientWidth||p.right-p.left,a=s.height||e.clientHeight||p.bottom-p.top,f=e.offsetWidth-d,h=e.offsetHeight-a;if(f||h){var u=t(e);f-=m(u,'x'),h-=m(u,'y'),p.width-=f,p.height-=h}return g(p)}function b(e,o){var i=2<arguments.length&&void 0!==arguments[2]&&arguments[2],p=r(10),s='HTML'===o.nodeName,d=u(e),a=u(o),l=n(e),m=t(o),h=parseFloat(m.borderTopWidth,10),c=parseFloat(m.borderLeftWidth,10);i&&'HTML'===o.nodeName&&(a.top=$(a.top,0),a.left=$(a.left,0));var b=g({top:d.top-a.top-h,left:d.left-a.left-c,width:d.width,height:d.height});if(b.marginTop=0,b.marginLeft=0,!p&&s){var y=parseFloat(m.marginTop,10),w=parseFloat(m.marginLeft,10);b.top-=h-y,b.bottom-=h-y,b.left-=c-w,b.right-=c-w,b.marginTop=y,b.marginLeft=w}return(p&&!i?o.contains(l):o===l&&'BODY'!==l.nodeName)&&(b=f(b,o)),b}function y(e){var t=1<arguments.length&&void 0!==arguments[1]&&arguments[1],o=e.ownerDocument.documentElement,n=b(e,o),i=$(o.clientWidth,window.innerWidth||0),r=$(o.clientHeight,window.innerHeight||0),p=t?0:l(o),s=t?0:l(o,'left'),d={top:p-n.top+n.marginTop,left:s-n.left+n.marginLeft,width:i,height:r};return g(d)}function w(e){var n=e.nodeName;return'BODY'===n||'HTML'===n?!1:'fixed'===t(e,'position')||w(o(e))}function E(e){if(!e||!e.parentElement||r())return document.documentElement;for(var o=e.parentElement;o&&'none'===t(o,'transform');)o=o.parentElement;return o||document.documentElement}function v(e,t,i,r){var p=4<arguments.length&&void 0!==arguments[4]&&arguments[4],s={top:0,left:0},d=p?E(e):a(e,t);if('viewport'===r)s=y(d,p);else{var l;'scrollParent'===r?(l=n(o(t)),'BODY'===l.nodeName&&(l=e.ownerDocument.documentElement)):'window'===r?l=e.ownerDocument.documentElement:l=r;var f=b(l,d,p);if('HTML'===l.nodeName&&!w(d)){var m=c(),h=m.height,g=m.width;s.top+=f.top-f.marginTop,s.bottom=h+f.top,s.left+=f.left-f.marginLeft,s.right=g+f.left}else s=f}return s.left+=i,s.top+=i,s.right-=i,s.bottom-=i,s}function x(e){var t=e.width,o=e.height;return t*o}function O(e,t,o,n,i){var r=5<arguments.length&&void 0!==arguments[5]?arguments[5]:0;if(-1===e.indexOf('auto'))return e;var p=v(o,n,r,i),s={top:{width:p.width,height:t.top-p.top},right:{width:p.right-t.right,height:p.height},bottom:{width:p.width,height:p.bottom-t.bottom},left:{width:t.left-p.left,height:p.height}},d=Object.keys(s).map(function(e){return le({key:e},s[e],{area:x(s[e])})}).sort(function(e,t){return t.area-e.area}),a=d.filter(function(e){var t=e.width,n=e.height;return t>=o.clientWidth&&n>=o.clientHeight}),l=0<a.length?a[0].key:d[0].key,f=e.split('-')[1];return l+(f?'-'+f:'')}function L(e,t,o){var n=3<arguments.length&&void 0!==arguments[3]?arguments[3]:null,i=n?E(t):a(t,o);return b(o,i,n)}function S(e){var t=getComputedStyle(e),o=parseFloat(t.marginTop)+parseFloat(t.marginBottom),n=parseFloat(t.marginLeft)+parseFloat(t.marginRight),i={width:e.offsetWidth+n,height:e.offsetHeight+o};return i}function T(e){var t={left:'right',right:'left',bottom:'top',top:'bottom'};return e.replace(/left|right|bottom|top/g,function(e){return t[e]})}function C(e,t,o){o=o.split('-')[0];var n=S(e),i={width:n.width,height:n.height},r=-1!==['right','left'].indexOf(o),p=r?'top':'left',s=r?'left':'top',d=r?'height':'width',a=r?'width':'height';return i[p]=t[p]+t[d]/2-n[d]/2,i[s]=o===s?t[s]-n[a]:t[T(s)],i}function D(e,t){return Array.prototype.find?e.find(t):e.filter(t)[0]}function N(e,t,o){if(Array.prototype.findIndex)return e.findIndex(function(e){return e[t]===o});var n=D(e,function(e){return e[t]===o});return e.indexOf(n)}function P(t,o,n){var i=void 0===n?t:t.slice(0,N(t,'name',n));return i.forEach(function(t){t['function']&&console.warn('`modifier.function` is deprecated, use `modifier.fn`!');var n=t['function']||t.fn;t.enabled&&e(n)&&(o.offsets.popper=g(o.offsets.popper),o.offsets.reference=g(o.offsets.reference),o=n(o,t))}),o}function k(){if(!this.state.isDestroyed){var e={instance:this,styles:{},arrowStyles:{},attributes:{},flipped:!1,offsets:{}};e.offsets.reference=L(this.state,this.popper,this.reference,this.options.positionFixed),e.placement=O(this.options.placement,e.offsets.reference,this.popper,this.reference,this.options.modifiers.flip.boundariesElement,this.options.modifiers.flip.padding),e.originalPlacement=e.placement,e.positionFixed=this.options.positionFixed,e.offsets.popper=C(this.popper,e.offsets.reference,e.placement),e.offsets.popper.position=this.options.positionFixed?'fixed':'absolute',e=P(this.modifiers,e),this.state.isCreated?this.options.onUpdate(e):(this.state.isCreated=!0,this.options.onCreate(e))}}function W(e,t){return e.some(function(e){var o=e.name,n=e.enabled;return n&&o===t})}function B(e){for(var t=[!1,'ms','Webkit','Moz','O'],o=e.charAt(0).toUpperCase()+e.slice(1),n=0;n<t.length;n++){var i=t[n],r=i?''+i+o:e;if('undefined'!=typeof document.body.style[r])return r}return null}function H(){return this.state.isDestroyed=!0,W(this.modifiers,'applyStyle')&&(this.popper.removeAttribute('x-placement'),this.popper.style.position='',this.popper.style.top='',this.popper.style.left='',this.popper.style.right='',this.popper.style.bottom='',this.popper.style.willChange='',this.popper.style[B('transform')]=''),this.disableEventListeners(),this.options.removeOnDestroy&&this.popper.parentNode.removeChild(this.popper),this}function A(e){var t=e.ownerDocument;return t?t.defaultView:window}function M(e,t,o,i){var r='BODY'===e.nodeName,p=r?e.ownerDocument.defaultView:e;p.addEventListener(t,o,{passive:!0}),r||M(n(p.parentNode),t,o,i),i.push(p)}function I(e,t,o,i){o.updateBound=i,A(e).addEventListener('resize',o.updateBound,{passive:!0});var r=n(e);return M(r,'scroll',o.updateBound,o.scrollParents),o.scrollElement=r,o.eventsEnabled=!0,o}function F(){this.state.eventsEnabled||(this.state=I(this.reference,this.options,this.state,this.scheduleUpdate))}function R(e,t){return A(e).removeEventListener('resize',t.updateBound),t.scrollParents.forEach(function(e){e.removeEventListener('scroll',t.updateBound)}),t.updateBound=null,t.scrollParents=[],t.scrollElement=null,t.eventsEnabled=!1,t}function U(){this.state.eventsEnabled&&(cancelAnimationFrame(this.scheduleUpdate),this.state=R(this.reference,this.state))}function Y(e){return''!==e&&!isNaN(parseFloat(e))&&isFinite(e)}function j(e,t){Object.keys(t).forEach(function(o){var n='';-1!==['width','height','top','right','bottom','left'].indexOf(o)&&Y(t[o])&&(n='px'),e.style[o]=t[o]+n})}function K(e,t){Object.keys(t).forEach(function(o){var n=t[o];!1===n?e.removeAttribute(o):e.setAttribute(o,t[o])})}function q(e,t,o){var n=D(e,function(e){var o=e.name;return o===t}),i=!!n&&e.some(function(e){return e.name===o&&e.enabled&&e.order<n.order});if(!i){var r='`'+t+'`';console.warn('`'+o+'`'+' modifier is required by '+r+' modifier in order to work, be sure to include it before '+r+'!')}return i}function G(e){return'end'===e?'start':'start'===e?'end':e}function z(e){var t=1<arguments.length&&void 0!==arguments[1]&&arguments[1],o=me.indexOf(e),n=me.slice(o+1).concat(me.slice(0,o));return t?n.reverse():n}function V(e,t,o,n){var i=e.match(/((?:\-|\+)?\d*\.?\d*)(.*)/),r=+i[1],p=i[2];if(!r)return e;if(0===p.indexOf('%')){var s;switch(p){case'%p':s=o;break;case'%':case'%r':default:s=n;}var d=g(s);return d[t]/100*r}if('vh'===p||'vw'===p){var a;return a='vh'===p?$(document.documentElement.clientHeight,window.innerHeight||0):$(document.documentElement.clientWidth,window.innerWidth||0),a/100*r}return r}function _(e,t,o,n){var i=[0,0],r=-1!==['right','left'].indexOf(n),p=e.split(/(\+|\-)/).map(function(e){return e.trim()}),s=p.indexOf(D(p,function(e){return-1!==e.search(/,|\s/)}));p[s]&&-1===p[s].indexOf(',')&&console.warn('Offsets separated by white space(s) are deprecated, use a comma (,) instead.');var d=/\s*,\s*|\s+/,a=-1===s?[p]:[p.slice(0,s).concat([p[s].split(d)[0]]),[p[s].split(d)[1]].concat(p.slice(s+1))];return a=a.map(function(e,n){var i=(1===n?!r:r)?'height':'width',p=!1;return e.reduce(function(e,t){return''===e[e.length-1]&&-1!==['+','-'].indexOf(t)?(e[e.length-1]=t,p=!0,e):p?(e[e.length-1]+=t,p=!1,e):e.concat(t)},[]).map(function(e){return V(e,i,t,o)})}),a.forEach(function(e,t){e.forEach(function(o,n){Y(o)&&(i[t]+=o*('-'===e[n-1]?-1:1))})}),i}function X(e,t){var o,n=t.offset,i=e.placement,r=e.offsets,p=r.popper,s=r.reference,d=i.split('-')[0];return o=Y(+n)?[+n,0]:_(n,p,s,d),'left'===d?(p.top+=o[0],p.left-=o[1]):'right'===d?(p.top+=o[0],p.left+=o[1]):'top'===d?(p.left+=o[0],p.top-=o[1]):'bottom'===d&&(p.left+=o[0],p.top+=o[1]),e.popper=p,e}for(var J=Math.min,Q=Math.round,Z=Math.floor,$=Math.max,ee='undefined'!=typeof window&&'undefined'!=typeof document,te=['Edge','Trident','Firefox'],oe=0,ne=0;ne<te.length;ne+=1)if(ee&&0<=navigator.userAgent.indexOf(te[ne])){oe=1;break}var i=ee&&window.Promise,ie=i?function(e){var t=!1;return function(){t||(t=!0,window.Promise.resolve().then(function(){t=!1,e()}))}}:function(e){var t=!1;return function(){t||(t=!0,setTimeout(function(){t=!1,e()},oe))}},re=ee&&!!(window.MSInputMethodContext&&document.documentMode),pe=ee&&/MSIE 10/.test(navigator.userAgent),se=function(e,t){if(!(e instanceof t))throw new TypeError('Cannot call a class as a function')},de=function(){function e(e,t){for(var o,n=0;n<t.length;n++)o=t[n],o.enumerable=o.enumerable||!1,o.configurable=!0,'value'in o&&(o.writable=!0),Object.defineProperty(e,o.key,o)}return function(t,o,n){return o&&e(t.prototype,o),n&&e(t,n),t}}(),ae=function(e,t,o){return t in e?Object.defineProperty(e,t,{value:o,enumerable:!0,configurable:!0,writable:!0}):e[t]=o,e},le=Object.assign||function(e){for(var t,o=1;o<arguments.length;o++)for(var n in t=arguments[o],t)Object.prototype.hasOwnProperty.call(t,n)&&(e[n]=t[n]);return e},fe=['auto-start','auto','auto-end','top-start','top','top-end','right-start','right','right-end','bottom-end','bottom','bottom-start','left-end','left','left-start'],me=fe.slice(3),he={FLIP:'flip',CLOCKWISE:'clockwise',COUNTERCLOCKWISE:'counterclockwise'},ce=function(){function t(o,n){var i=this,r=2<arguments.length&&void 0!==arguments[2]?arguments[2]:{};se(this,t),this.scheduleUpdate=function(){return requestAnimationFrame(i.update)},this.update=ie(this.update.bind(this)),this.options=le({},t.Defaults,r),this.state={isDestroyed:!1,isCreated:!1,scrollParents:[]},this.reference=o&&o.jquery?o[0]:o,this.popper=n&&n.jquery?n[0]:n,this.options.modifiers={},Object.keys(le({},t.Defaults.modifiers,r.modifiers)).forEach(function(e){i.options.modifiers[e]=le({},t.Defaults.modifiers[e]||{},r.modifiers?r.modifiers[e]:{})}),this.modifiers=Object.keys(this.options.modifiers).map(function(e){return le({name:e},i.options.modifiers[e])}).sort(function(e,t){return e.order-t.order}),this.modifiers.forEach(function(t){t.enabled&&e(t.onLoad)&&t.onLoad(i.reference,i.popper,i.options,t,i.state)}),this.update();var p=this.options.eventsEnabled;p&&this.enableEventListeners(),this.state.eventsEnabled=p}return de(t,[{key:'update',value:function(){return k.call(this)}},{key:'destroy',value:function(){return H.call(this)}},{key:'enableEventListeners',value:function(){return F.call(this)}},{key:'disableEventListeners',value:function(){return U.call(this)}}]),t}();return ce.Utils=('undefined'==typeof window?global:window).PopperUtils,ce.placements=fe,ce.Defaults={placement:'bottom',positionFixed:!1,eventsEnabled:!0,removeOnDestroy:!1,onCreate:function(){},onUpdate:function(){},modifiers:{shift:{order:100,enabled:!0,fn:function(e){var t=e.placement,o=t.split('-')[0],n=t.split('-')[1];if(n){var i=e.offsets,r=i.reference,p=i.popper,s=-1!==['bottom','top'].indexOf(o),d=s?'left':'top',a=s?'width':'height',l={start:ae({},d,r[d]),end:ae({},d,r[d]+r[a]-p[a])};e.offsets.popper=le({},p,l[n])}return e}},offset:{order:200,enabled:!0,fn:X,offset:0},preventOverflow:{order:300,enabled:!0,fn:function(e,t){var o=t.boundariesElement||p(e.instance.popper);e.instance.reference===o&&(o=p(o));var n=B('transform'),i=e.instance.popper.style,r=i.top,s=i.left,d=i[n];i.top='',i.left='',i[n]='';var a=v(e.instance.popper,e.instance.reference,t.padding,o,e.positionFixed);i.top=r,i.left=s,i[n]=d,t.boundaries=a;var l=t.priority,f=e.offsets.popper,m={primary:function(e){var o=f[e];return f[e]<a[e]&&!t.escapeWithReference&&(o=$(f[e],a[e])),ae({},e,o)},secondary:function(e){var o='right'===e?'left':'top',n=f[o];return f[e]>a[e]&&!t.escapeWithReference&&(n=J(f[o],a[e]-('right'===e?f.width:f.height))),ae({},o,n)}};return l.forEach(function(e){var t=-1===['left','top'].indexOf(e)?'secondary':'primary';f=le({},f,m[t](e))}),e.offsets.popper=f,e},priority:['left','right','top','bottom'],padding:5,boundariesElement:'scrollParent'},keepTogether:{order:400,enabled:!0,fn:function(e){var t=e.offsets,o=t.popper,n=t.reference,i=e.placement.split('-')[0],r=Z,p=-1!==['top','bottom'].indexOf(i),s=p?'right':'bottom',d=p?'left':'top',a=p?'width':'height';return o[s]<r(n[d])&&(e.offsets.popper[d]=r(n[d])-o[a]),o[d]>r(n[s])&&(e.offsets.popper[d]=r(n[s])),e}},arrow:{order:500,enabled:!0,fn:function(e,o){var n;if(!q(e.instance.modifiers,'arrow','keepTogether'))return e;var i=o.element;if('string'==typeof i){if(i=e.instance.popper.querySelector(i),!i)return e;}else if(!e.instance.popper.contains(i))return console.warn('WARNING: `arrow.element` must be child of its popper element!'),e;var r=e.placement.split('-')[0],p=e.offsets,s=p.popper,d=p.reference,a=-1!==['left','right'].indexOf(r),l=a?'height':'width',f=a?'Top':'Left',m=f.toLowerCase(),h=a?'left':'top',c=a?'bottom':'right',u=S(i)[l];d[c]-u<s[m]&&(e.offsets.popper[m]-=s[m]-(d[c]-u)),d[m]+u>s[c]&&(e.offsets.popper[m]+=d[m]+u-s[c]),e.offsets.popper=g(e.offsets.popper);var b=d[m]+d[l]/2-u/2,y=t(e.instance.popper),w=parseFloat(y['margin'+f],10),E=parseFloat(y['border'+f+'Width'],10),v=b-e.offsets.popper[m]-w-E;return v=$(J(s[l]-u,v),0),e.arrowElement=i,e.offsets.arrow=(n={},ae(n,m,Q(v)),ae(n,h,''),n),e},element:'[x-arrow]'},flip:{order:600,enabled:!0,fn:function(e,t){if(W(e.instance.modifiers,'inner'))return e;if(e.flipped&&e.placement===e.originalPlacement)return e;var o=v(e.instance.popper,e.instance.reference,t.padding,t.boundariesElement,e.positionFixed),n=e.placement.split('-')[0],i=T(n),r=e.placement.split('-')[1]||'',p=[];switch(t.behavior){case he.FLIP:p=[n,i];break;case he.CLOCKWISE:p=z(n);break;case he.COUNTERCLOCKWISE:p=z(n,!0);break;default:p=t.behavior;}return p.forEach(function(s,d){if(n!==s||p.length===d+1)return e;n=e.placement.split('-')[0],i=T(n);var a=e.offsets.popper,l=e.offsets.reference,f=Z,m='left'===n&&f(a.right)>f(l.left)||'right'===n&&f(a.left)<f(l.right)||'top'===n&&f(a.bottom)>f(l.top)||'bottom'===n&&f(a.top)<f(l.bottom),h=f(a.left)<f(o.left),c=f(a.right)>f(o.right),g=f(a.top)<f(o.top),u=f(a.bottom)>f(o.bottom),b='left'===n&&h||'right'===n&&c||'top'===n&&g||'bottom'===n&&u,y=-1!==['top','bottom'].indexOf(n),w=!!t.flipVariations&&(y&&'start'===r&&h||y&&'end'===r&&c||!y&&'start'===r&&g||!y&&'end'===r&&u);(m||b||w)&&(e.flipped=!0,(m||b)&&(n=p[d+1]),w&&(r=G(r)),e.placement=n+(r?'-'+r:''),e.offsets.popper=le({},e.offsets.popper,C(e.instance.popper,e.offsets.reference,e.placement)),e=P(e.instance.modifiers,e,'flip'))}),e},behavior:'flip',padding:5,boundariesElement:'viewport'},inner:{order:700,enabled:!1,fn:function(e){var t=e.placement,o=t.split('-')[0],n=e.offsets,i=n.popper,r=n.reference,p=-1!==['left','right'].indexOf(o),s=-1===['top','left'].indexOf(o);return i[p?'left':'top']=r[o]-(s?i[p?'width':'height']:0),e.placement=T(t),e.offsets.popper=g(i),e}},hide:{order:800,enabled:!0,fn:function(e){if(!q(e.instance.modifiers,'hide','preventOverflow'))return e;var t=e.offsets.reference,o=D(e.instance.modifiers,function(e){return'preventOverflow'===e.name}).boundaries;if(t.bottom<o.top||t.left>o.right||t.top>o.bottom||t.right<o.left){if(!0===e.hide)return e;e.hide=!0,e.attributes['x-out-of-boundaries']=''}else{if(!1===e.hide)return e;e.hide=!1,e.attributes['x-out-of-boundaries']=!1}return e}},computeStyle:{order:850,enabled:!0,fn:function(e,t){var o=t.x,n=t.y,i=e.offsets.popper,r=D(e.instance.modifiers,function(e){return'applyStyle'===e.name}).gpuAcceleration;void 0!==r&&console.warn('WARNING: `gpuAcceleration` option moved to `computeStyle` modifier and will not be supported in future versions of Popper.js!');var s,d,a=void 0===r?t.gpuAcceleration:r,l=p(e.instance.popper),f=u(l),m={position:i.position},h={left:Z(i.left),top:Q(i.top),bottom:Q(i.bottom),right:Z(i.right)},c='bottom'===o?'top':'bottom',g='right'===n?'left':'right',b=B('transform');if(d='bottom'==c?-f.height+h.bottom:h.top,s='right'==g?-f.width+h.right:h.left,a&&b)m[b]='translate3d('+s+'px, '+d+'px, 0)',m[c]=0,m[g]=0,m.willChange='transform';else{var y='bottom'==c?-1:1,w='right'==g?-1:1;m[c]=d*y,m[g]=s*w,m.willChange=c+', '+g}var E={"x-placement":e.placement};return e.attributes=le({},E,e.attributes),e.styles=le({},m,e.styles),e.arrowStyles=le({},e.offsets.arrow,e.arrowStyles),e},gpuAcceleration:!0,x:'bottom',y:'right'},applyStyle:{order:900,enabled:!0,fn:function(e){return j(e.instance.popper,e.styles),K(e.instance.popper,e.attributes),e.arrowElement&&Object.keys(e.arrowStyles).length&&j(e.arrowElement,e.arrowStyles),e},onLoad:function(e,t,o,n,i){var r=L(i,t,e,o.positionFixed),p=O(o.placement,r,t,e,o.modifiers.flip.boundariesElement,o.modifiers.flip.padding);return t.setAttribute('x-placement',p),j(t,{position:o.positionFixed?'fixed':'absolute'}),o},gpuAcceleration:void 0}}},ce});
//# sourceMappingURL=popper.min.js.map

/*!
  * Bootstrap v4.1.1 (https://getbootstrap.com/)
  * Copyright 2011-2018 The Bootstrap Authors (https://github.com/twbs/bootstrap/graphs/contributors)
  * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
  */
!function(t,e){"object"==typeof exports&&"undefined"!=typeof module?e(exports,require("jquery"),require("popper.js")):"function"==typeof define&&define.amd?define(["exports","jquery","popper.js"],e):e(t.bootstrap={},t.jQuery,t.Popper)}(this,function(t,e,c){"use strict";function i(t,e){for(var n=0;n<e.length;n++){var i=e[n];i.enumerable=i.enumerable||!1,i.configurable=!0,"value"in i&&(i.writable=!0),Object.defineProperty(t,i.key,i)}}function o(t,e,n){return e&&i(t.prototype,e),n&&i(t,n),t}function h(r){for(var t=1;t<arguments.length;t++){var s=null!=arguments[t]?arguments[t]:{},e=Object.keys(s);"function"==typeof Object.getOwnPropertySymbols&&(e=e.concat(Object.getOwnPropertySymbols(s).filter(function(t){return Object.getOwnPropertyDescriptor(s,t).enumerable}))),e.forEach(function(t){var e,n,i;e=r,i=s[n=t],n in e?Object.defineProperty(e,n,{value:i,enumerable:!0,configurable:!0,writable:!0}):e[n]=i})}return r}e=e&&e.hasOwnProperty("default")?e.default:e,c=c&&c.hasOwnProperty("default")?c.default:c;var r,n,s,a,l,u,f,d,_,g,m,p,v,E,y,T,C,I,A,D,b,S,w,N,O,k,P,L,j,R,H,W,M,x,U,K,F,V,Q,B,Y,G,q,z,X,J,Z,$,tt,et,nt,it,rt,st,ot,at,lt,ht,ct,ut,ft,dt,_t,gt,mt,pt,vt,Et,yt,Tt,Ct,It,At,Dt,bt,St,wt,Nt,Ot,kt,Pt,Lt,jt,Rt,Ht,Wt,Mt,xt,Ut,Kt,Ft,Vt,Qt,Bt,Yt,Gt,qt,zt,Xt,Jt,Zt,$t,te,ee,ne,ie,re,se,oe,ae,le,he,ce,ue,fe,de,_e,ge,me,pe,ve,Ee,ye,Te,Ce,Ie,Ae,De,be,Se,we,Ne,Oe,ke,Pe,Le,je,Re,He,We,Me,xe,Ue,Ke,Fe,Ve,Qe,Be,Ye,Ge,qe,ze,Xe,Je,Ze,$e,tn,en,nn,rn,sn,on,an,ln,hn,cn,un,fn,dn,_n,gn,mn,pn,vn,En,yn,Tn,Cn=function(i){var e="transitionend";function t(t){var e=this,n=!1;return i(this).one(l.TRANSITION_END,function(){n=!0}),setTimeout(function(){n||l.triggerTransitionEnd(e)},t),this}var l={TRANSITION_END:"bsTransitionEnd",getUID:function(t){for(;t+=~~(1e6*Math.random()),document.getElementById(t););return t},getSelectorFromElement:function(t){var e=t.getAttribute("data-target");e&&"#"!==e||(e=t.getAttribute("href")||"");try{return 0<i(document).find(e).length?e:null}catch(t){return null}},getTransitionDurationFromElement:function(t){if(!t)return 0;var e=i(t).css("transition-duration");return parseFloat(e)?(e=e.split(",")[0],1e3*parseFloat(e)):0},reflow:function(t){return t.offsetHeight},triggerTransitionEnd:function(t){i(t).trigger(e)},supportsTransitionEnd:function(){return Boolean(e)},isElement:function(t){return(t[0]||t).nodeType},typeCheckConfig:function(t,e,n){for(var i in n)if(Object.prototype.hasOwnProperty.call(n,i)){var r=n[i],s=e[i],o=s&&l.isElement(s)?"element":(a=s,{}.toString.call(a).match(/\s([a-z]+)/i)[1].toLowerCase());if(!new RegExp(r).test(o))throw new Error(t.toUpperCase()+': Option "'+i+'" provided type "'+o+'" but expected type "'+r+'".')}var a}};return i.fn.emulateTransitionEnd=t,i.event.special[l.TRANSITION_END]={bindType:e,delegateType:e,handle:function(t){if(i(t.target).is(this))return t.handleObj.handler.apply(this,arguments)}},l}(e),In=(n="alert",a="."+(s="bs.alert"),l=(r=e).fn[n],u={CLOSE:"close"+a,CLOSED:"closed"+a,CLICK_DATA_API:"click"+a+".data-api"},f="alert",d="fade",_="show",g=function(){function i(t){this._element=t}var t=i.prototype;return t.close=function(t){var e=this._element;t&&(e=this._getRootElement(t)),this._triggerCloseEvent(e).isDefaultPrevented()||this._removeElement(e)},t.dispose=function(){r.removeData(this._element,s),this._element=null},t._getRootElement=function(t){var e=Cn.getSelectorFromElement(t),n=!1;return e&&(n=r(e)[0]),n||(n=r(t).closest("."+f)[0]),n},t._triggerCloseEvent=function(t){var e=r.Event(u.CLOSE);return r(t).trigger(e),e},t._removeElement=function(e){var n=this;if(r(e).removeClass(_),r(e).hasClass(d)){var t=Cn.getTransitionDurationFromElement(e);r(e).one(Cn.TRANSITION_END,function(t){return n._destroyElement(e,t)}).emulateTransitionEnd(t)}else this._destroyElement(e)},t._destroyElement=function(t){r(t).detach().trigger(u.CLOSED).remove()},i._jQueryInterface=function(n){return this.each(function(){var t=r(this),e=t.data(s);e||(e=new i(this),t.data(s,e)),"close"===n&&e[n](this)})},i._handleDismiss=function(e){return function(t){t&&t.preventDefault(),e.close(this)}},o(i,null,[{key:"VERSION",get:function(){return"4.1.1"}}]),i}(),r(document).on(u.CLICK_DATA_API,'[data-dismiss="alert"]',g._handleDismiss(new g)),r.fn[n]=g._jQueryInterface,r.fn[n].Constructor=g,r.fn[n].noConflict=function(){return r.fn[n]=l,g._jQueryInterface},g),An=(p="button",E="."+(v="bs.button"),y=".data-api",T=(m=e).fn[p],C="active",I="btn",D='[data-toggle^="button"]',b='[data-toggle="buttons"]',S="input",w=".active",N=".btn",O={CLICK_DATA_API:"click"+E+y,FOCUS_BLUR_DATA_API:(A="focus")+E+y+" blur"+E+y},k=function(){function n(t){this._element=t}var t=n.prototype;return t.toggle=function(){var t=!0,e=!0,n=m(this._element).closest(b)[0];if(n){var i=m(this._element).find(S)[0];if(i){if("radio"===i.type)if(i.checked&&m(this._element).hasClass(C))t=!1;else{var r=m(n).find(w)[0];r&&m(r).removeClass(C)}if(t){if(i.hasAttribute("disabled")||n.hasAttribute("disabled")||i.classList.contains("disabled")||n.classList.contains("disabled"))return;i.checked=!m(this._element).hasClass(C),m(i).trigger("change")}i.focus(),e=!1}}e&&this._element.setAttribute("aria-pressed",!m(this._element).hasClass(C)),t&&m(this._element).toggleClass(C)},t.dispose=function(){m.removeData(this._element,v),this._element=null},n._jQueryInterface=function(e){return this.each(function(){var t=m(this).data(v);t||(t=new n(this),m(this).data(v,t)),"toggle"===e&&t[e]()})},o(n,null,[{key:"VERSION",get:function(){return"4.1.1"}}]),n}(),m(document).on(O.CLICK_DATA_API,D,function(t){t.preventDefault();var e=t.target;m(e).hasClass(I)||(e=m(e).closest(N)),k._jQueryInterface.call(m(e),"toggle")}).on(O.FOCUS_BLUR_DATA_API,D,function(t){var e=m(t.target).closest(N)[0];m(e).toggleClass(A,/^focus(in)?$/.test(t.type))}),m.fn[p]=k._jQueryInterface,m.fn[p].Constructor=k,m.fn[p].noConflict=function(){return m.fn[p]=T,k._jQueryInterface},k),Dn=(L="carousel",R="."+(j="bs.carousel"),H=".data-api",W=(P=e).fn[L],M={interval:5e3,keyboard:!0,slide:!1,pause:"hover",wrap:!0},x={interval:"(number|boolean)",keyboard:"boolean",slide:"(boolean|string)",pause:"(string|boolean)",wrap:"boolean"},U="next",K="prev",F="left",V="right",Q={SLIDE:"slide"+R,SLID:"slid"+R,KEYDOWN:"keydown"+R,MOUSEENTER:"mouseenter"+R,MOUSELEAVE:"mouseleave"+R,TOUCHEND:"touchend"+R,LOAD_DATA_API:"load"+R+H,CLICK_DATA_API:"click"+R+H},B="carousel",Y="active",G="slide",q="carousel-item-right",z="carousel-item-left",X="carousel-item-next",J="carousel-item-prev",Z={ACTIVE:".active",ACTIVE_ITEM:".active.carousel-item",ITEM:".carousel-item",NEXT_PREV:".carousel-item-next, .carousel-item-prev",INDICATORS:".carousel-indicators",DATA_SLIDE:"[data-slide], [data-slide-to]",DATA_RIDE:'[data-ride="carousel"]'},$=function(){function s(t,e){this._items=null,this._interval=null,this._activeElement=null,this._isPaused=!1,this._isSliding=!1,this.touchTimeout=null,this._config=this._getConfig(e),this._element=P(t)[0],this._indicatorsElement=P(this._element).find(Z.INDICATORS)[0],this._addEventListeners()}var t=s.prototype;return t.next=function(){this._isSliding||this._slide(U)},t.nextWhenVisible=function(){!document.hidden&&P(this._element).is(":visible")&&"hidden"!==P(this._element).css("visibility")&&this.next()},t.prev=function(){this._isSliding||this._slide(K)},t.pause=function(t){t||(this._isPaused=!0),P(this._element).find(Z.NEXT_PREV)[0]&&(Cn.triggerTransitionEnd(this._element),this.cycle(!0)),clearInterval(this._interval),this._interval=null},t.cycle=function(t){t||(this._isPaused=!1),this._interval&&(clearInterval(this._interval),this._interval=null),this._config.interval&&!this._isPaused&&(this._interval=setInterval((document.visibilityState?this.nextWhenVisible:this.next).bind(this),this._config.interval))},t.to=function(t){var e=this;this._activeElement=P(this._element).find(Z.ACTIVE_ITEM)[0];var n=this._getItemIndex(this._activeElement);if(!(t>this._items.length-1||t<0))if(this._isSliding)P(this._element).one(Q.SLID,function(){return e.to(t)});else{if(n===t)return this.pause(),void this.cycle();var i=n<t?U:K;this._slide(i,this._items[t])}},t.dispose=function(){P(this._element).off(R),P.removeData(this._element,j),this._items=null,this._config=null,this._element=null,this._interval=null,this._isPaused=null,this._isSliding=null,this._activeElement=null,this._indicatorsElement=null},t._getConfig=function(t){return t=h({},M,t),Cn.typeCheckConfig(L,t,x),t},t._addEventListeners=function(){var e=this;this._config.keyboard&&P(this._element).on(Q.KEYDOWN,function(t){return e._keydown(t)}),"hover"===this._config.pause&&(P(this._element).on(Q.MOUSEENTER,function(t){return e.pause(t)}).on(Q.MOUSELEAVE,function(t){return e.cycle(t)}),"ontouchstart"in document.documentElement&&P(this._element).on(Q.TOUCHEND,function(){e.pause(),e.touchTimeout&&clearTimeout(e.touchTimeout),e.touchTimeout=setTimeout(function(t){return e.cycle(t)},500+e._config.interval)}))},t._keydown=function(t){if(!/input|textarea/i.test(t.target.tagName))switch(t.which){case 37:t.preventDefault(),this.prev();break;case 39:t.preventDefault(),this.next()}},t._getItemIndex=function(t){return this._items=P.makeArray(P(t).parent().find(Z.ITEM)),this._items.indexOf(t)},t._getItemByDirection=function(t,e){var n=t===U,i=t===K,r=this._getItemIndex(e),s=this._items.length-1;if((i&&0===r||n&&r===s)&&!this._config.wrap)return e;var o=(r+(t===K?-1:1))%this._items.length;return-1===o?this._items[this._items.length-1]:this._items[o]},t._triggerSlideEvent=function(t,e){var n=this._getItemIndex(t),i=this._getItemIndex(P(this._element).find(Z.ACTIVE_ITEM)[0]),r=P.Event(Q.SLIDE,{relatedTarget:t,direction:e,from:i,to:n});return P(this._element).trigger(r),r},t._setActiveIndicatorElement=function(t){if(this._indicatorsElement){P(this._indicatorsElement).find(Z.ACTIVE).removeClass(Y);var e=this._indicatorsElement.children[this._getItemIndex(t)];e&&P(e).addClass(Y)}},t._slide=function(t,e){var n,i,r,s=this,o=P(this._element).find(Z.ACTIVE_ITEM)[0],a=this._getItemIndex(o),l=e||o&&this._getItemByDirection(t,o),h=this._getItemIndex(l),c=Boolean(this._interval);if(t===U?(n=z,i=X,r=F):(n=q,i=J,r=V),l&&P(l).hasClass(Y))this._isSliding=!1;else if(!this._triggerSlideEvent(l,r).isDefaultPrevented()&&o&&l){this._isSliding=!0,c&&this.pause(),this._setActiveIndicatorElement(l);var u=P.Event(Q.SLID,{relatedTarget:l,direction:r,from:a,to:h});if(P(this._element).hasClass(G)){P(l).addClass(i),Cn.reflow(l),P(o).addClass(n),P(l).addClass(n);var f=Cn.getTransitionDurationFromElement(o);P(o).one(Cn.TRANSITION_END,function(){P(l).removeClass(n+" "+i).addClass(Y),P(o).removeClass(Y+" "+i+" "+n),s._isSliding=!1,setTimeout(function(){return P(s._element).trigger(u)},0)}).emulateTransitionEnd(f)}else P(o).removeClass(Y),P(l).addClass(Y),this._isSliding=!1,P(this._element).trigger(u);c&&this.cycle()}},s._jQueryInterface=function(i){return this.each(function(){var t=P(this).data(j),e=h({},M,P(this).data());"object"==typeof i&&(e=h({},e,i));var n="string"==typeof i?i:e.slide;if(t||(t=new s(this,e),P(this).data(j,t)),"number"==typeof i)t.to(i);else if("string"==typeof n){if("undefined"==typeof t[n])throw new TypeError('No method named "'+n+'"');t[n]()}else e.interval&&(t.pause(),t.cycle())})},s._dataApiClickHandler=function(t){var e=Cn.getSelectorFromElement(this);if(e){var n=P(e)[0];if(n&&P(n).hasClass(B)){var i=h({},P(n).data(),P(this).data()),r=this.getAttribute("data-slide-to");r&&(i.interval=!1),s._jQueryInterface.call(P(n),i),r&&P(n).data(j).to(r),t.preventDefault()}}},o(s,null,[{key:"VERSION",get:function(){return"4.1.1"}},{key:"Default",get:function(){return M}}]),s}(),P(document).on(Q.CLICK_DATA_API,Z.DATA_SLIDE,$._dataApiClickHandler),P(window).on(Q.LOAD_DATA_API,function(){P(Z.DATA_RIDE).each(function(){var t=P(this);$._jQueryInterface.call(t,t.data())})}),P.fn[L]=$._jQueryInterface,P.fn[L].Constructor=$,P.fn[L].noConflict=function(){return P.fn[L]=W,$._jQueryInterface},$),bn=(et="collapse",it="."+(nt="bs.collapse"),rt=(tt=e).fn[et],st={toggle:!0,parent:""},ot={toggle:"boolean",parent:"(string|element)"},at={SHOW:"show"+it,SHOWN:"shown"+it,HIDE:"hide"+it,HIDDEN:"hidden"+it,CLICK_DATA_API:"click"+it+".data-api"},lt="show",ht="collapse",ct="collapsing",ut="collapsed",ft="width",dt="height",_t={ACTIVES:".show, .collapsing",DATA_TOGGLE:'[data-toggle="collapse"]'},gt=function(){function a(t,e){this._isTransitioning=!1,this._element=t,this._config=this._getConfig(e),this._triggerArray=tt.makeArray(tt('[data-toggle="collapse"][href="#'+t.id+'"],[data-toggle="collapse"][data-target="#'+t.id+'"]'));for(var n=tt(_t.DATA_TOGGLE),i=0;i<n.length;i++){var r=n[i],s=Cn.getSelectorFromElement(r);null!==s&&0<tt(s).filter(t).length&&(this._selector=s,this._triggerArray.push(r))}this._parent=this._config.parent?this._getParent():null,this._config.parent||this._addAriaAndCollapsedClass(this._element,this._triggerArray),this._config.toggle&&this.toggle()}var t=a.prototype;return t.toggle=function(){tt(this._element).hasClass(lt)?this.hide():this.show()},t.show=function(){var t,e,n=this;if(!this._isTransitioning&&!tt(this._element).hasClass(lt)&&(this._parent&&0===(t=tt.makeArray(tt(this._parent).find(_t.ACTIVES).filter('[data-parent="'+this._config.parent+'"]'))).length&&(t=null),!(t&&(e=tt(t).not(this._selector).data(nt))&&e._isTransitioning))){var i=tt.Event(at.SHOW);if(tt(this._element).trigger(i),!i.isDefaultPrevented()){t&&(a._jQueryInterface.call(tt(t).not(this._selector),"hide"),e||tt(t).data(nt,null));var r=this._getDimension();tt(this._element).removeClass(ht).addClass(ct),(this._element.style[r]=0)<this._triggerArray.length&&tt(this._triggerArray).removeClass(ut).attr("aria-expanded",!0),this.setTransitioning(!0);var s="scroll"+(r[0].toUpperCase()+r.slice(1)),o=Cn.getTransitionDurationFromElement(this._element);tt(this._element).one(Cn.TRANSITION_END,function(){tt(n._element).removeClass(ct).addClass(ht).addClass(lt),n._element.style[r]="",n.setTransitioning(!1),tt(n._element).trigger(at.SHOWN)}).emulateTransitionEnd(o),this._element.style[r]=this._element[s]+"px"}}},t.hide=function(){var t=this;if(!this._isTransitioning&&tt(this._element).hasClass(lt)){var e=tt.Event(at.HIDE);if(tt(this._element).trigger(e),!e.isDefaultPrevented()){var n=this._getDimension();if(this._element.style[n]=this._element.getBoundingClientRect()[n]+"px",Cn.reflow(this._element),tt(this._element).addClass(ct).removeClass(ht).removeClass(lt),0<this._triggerArray.length)for(var i=0;i<this._triggerArray.length;i++){var r=this._triggerArray[i],s=Cn.getSelectorFromElement(r);if(null!==s)tt(s).hasClass(lt)||tt(r).addClass(ut).attr("aria-expanded",!1)}this.setTransitioning(!0);this._element.style[n]="";var o=Cn.getTransitionDurationFromElement(this._element);tt(this._element).one(Cn.TRANSITION_END,function(){t.setTransitioning(!1),tt(t._element).removeClass(ct).addClass(ht).trigger(at.HIDDEN)}).emulateTransitionEnd(o)}}},t.setTransitioning=function(t){this._isTransitioning=t},t.dispose=function(){tt.removeData(this._element,nt),this._config=null,this._parent=null,this._element=null,this._triggerArray=null,this._isTransitioning=null},t._getConfig=function(t){return(t=h({},st,t)).toggle=Boolean(t.toggle),Cn.typeCheckConfig(et,t,ot),t},t._getDimension=function(){return tt(this._element).hasClass(ft)?ft:dt},t._getParent=function(){var n=this,t=null;Cn.isElement(this._config.parent)?(t=this._config.parent,"undefined"!=typeof this._config.parent.jquery&&(t=this._config.parent[0])):t=tt(this._config.parent)[0];var e='[data-toggle="collapse"][data-parent="'+this._config.parent+'"]';return tt(t).find(e).each(function(t,e){n._addAriaAndCollapsedClass(a._getTargetFromElement(e),[e])}),t},t._addAriaAndCollapsedClass=function(t,e){if(t){var n=tt(t).hasClass(lt);0<e.length&&tt(e).toggleClass(ut,!n).attr("aria-expanded",n)}},a._getTargetFromElement=function(t){var e=Cn.getSelectorFromElement(t);return e?tt(e)[0]:null},a._jQueryInterface=function(i){return this.each(function(){var t=tt(this),e=t.data(nt),n=h({},st,t.data(),"object"==typeof i&&i?i:{});if(!e&&n.toggle&&/show|hide/.test(i)&&(n.toggle=!1),e||(e=new a(this,n),t.data(nt,e)),"string"==typeof i){if("undefined"==typeof e[i])throw new TypeError('No method named "'+i+'"');e[i]()}})},o(a,null,[{key:"VERSION",get:function(){return"4.1.1"}},{key:"Default",get:function(){return st}}]),a}(),tt(document).on(at.CLICK_DATA_API,_t.DATA_TOGGLE,function(t){"A"===t.currentTarget.tagName&&t.preventDefault();var n=tt(this),e=Cn.getSelectorFromElement(this);tt(e).each(function(){var t=tt(this),e=t.data(nt)?"toggle":n.data();gt._jQueryInterface.call(t,e)})}),tt.fn[et]=gt._jQueryInterface,tt.fn[et].Constructor=gt,tt.fn[et].noConflict=function(){return tt.fn[et]=rt,gt._jQueryInterface},gt),Sn=(pt="dropdown",Et="."+(vt="bs.dropdown"),yt=".data-api",Tt=(mt=e).fn[pt],Ct=new RegExp("38|40|27"),It={HIDE:"hide"+Et,HIDDEN:"hidden"+Et,SHOW:"show"+Et,SHOWN:"shown"+Et,CLICK:"click"+Et,CLICK_DATA_API:"click"+Et+yt,KEYDOWN_DATA_API:"keydown"+Et+yt,KEYUP_DATA_API:"keyup"+Et+yt},At="disabled",Dt="show",bt="dropup",St="dropright",wt="dropleft",Nt="dropdown-menu-right",Ot="position-static",kt='[data-toggle="dropdown"]',Pt=".dropdown form",Lt=".dropdown-menu",jt=".navbar-nav",Rt=".dropdown-menu .dropdown-item:not(.disabled):not(:disabled)",Ht="top-start",Wt="top-end",Mt="bottom-start",xt="bottom-end",Ut="right-start",Kt="left-start",Ft={offset:0,flip:!0,boundary:"scrollParent",reference:"toggle",display:"dynamic"},Vt={offset:"(number|string|function)",flip:"boolean",boundary:"(string|element)",reference:"(string|element)",display:"string"},Qt=function(){function l(t,e){this._element=t,this._popper=null,this._config=this._getConfig(e),this._menu=this._getMenuElement(),this._inNavbar=this._detectNavbar(),this._addEventListeners()}var t=l.prototype;return t.toggle=function(){if(!this._element.disabled&&!mt(this._element).hasClass(At)){var t=l._getParentFromElement(this._element),e=mt(this._menu).hasClass(Dt);if(l._clearMenus(),!e){var n={relatedTarget:this._element},i=mt.Event(It.SHOW,n);if(mt(t).trigger(i),!i.isDefaultPrevented()){if(!this._inNavbar){if("undefined"==typeof c)throw new TypeError("Bootstrap dropdown require Popper.js (https://popper.js.org)");var r=this._element;"parent"===this._config.reference?r=t:Cn.isElement(this._config.reference)&&(r=this._config.reference,"undefined"!=typeof this._config.reference.jquery&&(r=this._config.reference[0])),"scrollParent"!==this._config.boundary&&mt(t).addClass(Ot),this._popper=new c(r,this._menu,this._getPopperConfig())}"ontouchstart"in document.documentElement&&0===mt(t).closest(jt).length&&mt(document.body).children().on("mouseover",null,mt.noop),this._element.focus(),this._element.setAttribute("aria-expanded",!0),mt(this._menu).toggleClass(Dt),mt(t).toggleClass(Dt).trigger(mt.Event(It.SHOWN,n))}}}},t.dispose=function(){mt.removeData(this._element,vt),mt(this._element).off(Et),this._element=null,(this._menu=null)!==this._popper&&(this._popper.destroy(),this._popper=null)},t.update=function(){this._inNavbar=this._detectNavbar(),null!==this._popper&&this._popper.scheduleUpdate()},t._addEventListeners=function(){var e=this;mt(this._element).on(It.CLICK,function(t){t.preventDefault(),t.stopPropagation(),e.toggle()})},t._getConfig=function(t){return t=h({},this.constructor.Default,mt(this._element).data(),t),Cn.typeCheckConfig(pt,t,this.constructor.DefaultType),t},t._getMenuElement=function(){if(!this._menu){var t=l._getParentFromElement(this._element);this._menu=mt(t).find(Lt)[0]}return this._menu},t._getPlacement=function(){var t=mt(this._element).parent(),e=Mt;return t.hasClass(bt)?(e=Ht,mt(this._menu).hasClass(Nt)&&(e=Wt)):t.hasClass(St)?e=Ut:t.hasClass(wt)?e=Kt:mt(this._menu).hasClass(Nt)&&(e=xt),e},t._detectNavbar=function(){return 0<mt(this._element).closest(".navbar").length},t._getPopperConfig=function(){var e=this,t={};"function"==typeof this._config.offset?t.fn=function(t){return t.offsets=h({},t.offsets,e._config.offset(t.offsets)||{}),t}:t.offset=this._config.offset;var n={placement:this._getPlacement(),modifiers:{offset:t,flip:{enabled:this._config.flip},preventOverflow:{boundariesElement:this._config.boundary}}};return"static"===this._config.display&&(n.modifiers.applyStyle={enabled:!1}),n},l._jQueryInterface=function(e){return this.each(function(){var t=mt(this).data(vt);if(t||(t=new l(this,"object"==typeof e?e:null),mt(this).data(vt,t)),"string"==typeof e){if("undefined"==typeof t[e])throw new TypeError('No method named "'+e+'"');t[e]()}})},l._clearMenus=function(t){if(!t||3!==t.which&&("keyup"!==t.type||9===t.which))for(var e=mt.makeArray(mt(kt)),n=0;n<e.length;n++){var i=l._getParentFromElement(e[n]),r=mt(e[n]).data(vt),s={relatedTarget:e[n]};if(r){var o=r._menu;if(mt(i).hasClass(Dt)&&!(t&&("click"===t.type&&/input|textarea/i.test(t.target.tagName)||"keyup"===t.type&&9===t.which)&&mt.contains(i,t.target))){var a=mt.Event(It.HIDE,s);mt(i).trigger(a),a.isDefaultPrevented()||("ontouchstart"in document.documentElement&&mt(document.body).children().off("mouseover",null,mt.noop),e[n].setAttribute("aria-expanded","false"),mt(o).removeClass(Dt),mt(i).removeClass(Dt).trigger(mt.Event(It.HIDDEN,s)))}}}},l._getParentFromElement=function(t){var e,n=Cn.getSelectorFromElement(t);return n&&(e=mt(n)[0]),e||t.parentNode},l._dataApiKeydownHandler=function(t){if((/input|textarea/i.test(t.target.tagName)?!(32===t.which||27!==t.which&&(40!==t.which&&38!==t.which||mt(t.target).closest(Lt).length)):Ct.test(t.which))&&(t.preventDefault(),t.stopPropagation(),!this.disabled&&!mt(this).hasClass(At))){var e=l._getParentFromElement(this),n=mt(e).hasClass(Dt);if((n||27===t.which&&32===t.which)&&(!n||27!==t.which&&32!==t.which)){var i=mt(e).find(Rt).get();if(0!==i.length){var r=i.indexOf(t.target);38===t.which&&0<r&&r--,40===t.which&&r<i.length-1&&r++,r<0&&(r=0),i[r].focus()}}else{if(27===t.which){var s=mt(e).find(kt)[0];mt(s).trigger("focus")}mt(this).trigger("click")}}},o(l,null,[{key:"VERSION",get:function(){return"4.1.1"}},{key:"Default",get:function(){return Ft}},{key:"DefaultType",get:function(){return Vt}}]),l}(),mt(document).on(It.KEYDOWN_DATA_API,kt,Qt._dataApiKeydownHandler).on(It.KEYDOWN_DATA_API,Lt,Qt._dataApiKeydownHandler).on(It.CLICK_DATA_API+" "+It.KEYUP_DATA_API,Qt._clearMenus).on(It.CLICK_DATA_API,kt,function(t){t.preventDefault(),t.stopPropagation(),Qt._jQueryInterface.call(mt(this),"toggle")}).on(It.CLICK_DATA_API,Pt,function(t){t.stopPropagation()}),mt.fn[pt]=Qt._jQueryInterface,mt.fn[pt].Constructor=Qt,mt.fn[pt].noConflict=function(){return mt.fn[pt]=Tt,Qt._jQueryInterface},Qt),wn=(Yt="modal",qt="."+(Gt="bs.modal"),zt=(Bt=e).fn[Yt],Xt={backdrop:!0,keyboard:!0,focus:!0,show:!0},Jt={backdrop:"(boolean|string)",keyboard:"boolean",focus:"boolean",show:"boolean"},Zt={HIDE:"hide"+qt,HIDDEN:"hidden"+qt,SHOW:"show"+qt,SHOWN:"shown"+qt,FOCUSIN:"focusin"+qt,RESIZE:"resize"+qt,CLICK_DISMISS:"click.dismiss"+qt,KEYDOWN_DISMISS:"keydown.dismiss"+qt,MOUSEUP_DISMISS:"mouseup.dismiss"+qt,MOUSEDOWN_DISMISS:"mousedown.dismiss"+qt,CLICK_DATA_API:"click"+qt+".data-api"},$t="modal-scrollbar-measure",te="modal-backdrop",ee="modal-open",ne="fade",ie="show",re={DIALOG:".modal-dialog",DATA_TOGGLE:'[data-toggle="modal"]',DATA_DISMISS:'[data-dismiss="modal"]',FIXED_CONTENT:".fixed-top, .fixed-bottom, .is-fixed, .sticky-top",STICKY_CONTENT:".sticky-top",NAVBAR_TOGGLER:".navbar-toggler"},se=function(){function r(t,e){this._config=this._getConfig(e),this._element=t,this._dialog=Bt(t).find(re.DIALOG)[0],this._backdrop=null,this._isShown=!1,this._isBodyOverflowing=!1,this._ignoreBackdropClick=!1,this._scrollbarWidth=0}var t=r.prototype;return t.toggle=function(t){return this._isShown?this.hide():this.show(t)},t.show=function(t){var e=this;if(!this._isTransitioning&&!this._isShown){Bt(this._element).hasClass(ne)&&(this._isTransitioning=!0);var n=Bt.Event(Zt.SHOW,{relatedTarget:t});Bt(this._element).trigger(n),this._isShown||n.isDefaultPrevented()||(this._isShown=!0,this._checkScrollbar(),this._setScrollbar(),this._adjustDialog(),Bt(document.body).addClass(ee),this._setEscapeEvent(),this._setResizeEvent(),Bt(this._element).on(Zt.CLICK_DISMISS,re.DATA_DISMISS,function(t){return e.hide(t)}),Bt(this._dialog).on(Zt.MOUSEDOWN_DISMISS,function(){Bt(e._element).one(Zt.MOUSEUP_DISMISS,function(t){Bt(t.target).is(e._element)&&(e._ignoreBackdropClick=!0)})}),this._showBackdrop(function(){return e._showElement(t)}))}},t.hide=function(t){var e=this;if(t&&t.preventDefault(),!this._isTransitioning&&this._isShown){var n=Bt.Event(Zt.HIDE);if(Bt(this._element).trigger(n),this._isShown&&!n.isDefaultPrevented()){this._isShown=!1;var i=Bt(this._element).hasClass(ne);if(i&&(this._isTransitioning=!0),this._setEscapeEvent(),this._setResizeEvent(),Bt(document).off(Zt.FOCUSIN),Bt(this._element).removeClass(ie),Bt(this._element).off(Zt.CLICK_DISMISS),Bt(this._dialog).off(Zt.MOUSEDOWN_DISMISS),i){var r=Cn.getTransitionDurationFromElement(this._element);Bt(this._element).one(Cn.TRANSITION_END,function(t){return e._hideModal(t)}).emulateTransitionEnd(r)}else this._hideModal()}}},t.dispose=function(){Bt.removeData(this._element,Gt),Bt(window,document,this._element,this._backdrop).off(qt),this._config=null,this._element=null,this._dialog=null,this._backdrop=null,this._isShown=null,this._isBodyOverflowing=null,this._ignoreBackdropClick=null,this._scrollbarWidth=null},t.handleUpdate=function(){this._adjustDialog()},t._getConfig=function(t){return t=h({},Xt,t),Cn.typeCheckConfig(Yt,t,Jt),t},t._showElement=function(t){var e=this,n=Bt(this._element).hasClass(ne);this._element.parentNode&&this._element.parentNode.nodeType===Node.ELEMENT_NODE||document.body.appendChild(this._element),this._element.style.display="block",this._element.removeAttribute("aria-hidden"),this._element.scrollTop=0,n&&Cn.reflow(this._element),Bt(this._element).addClass(ie),this._config.focus&&this._enforceFocus();var i=Bt.Event(Zt.SHOWN,{relatedTarget:t}),r=function(){e._config.focus&&e._element.focus(),e._isTransitioning=!1,Bt(e._element).trigger(i)};if(n){var s=Cn.getTransitionDurationFromElement(this._element);Bt(this._dialog).one(Cn.TRANSITION_END,r).emulateTransitionEnd(s)}else r()},t._enforceFocus=function(){var e=this;Bt(document).off(Zt.FOCUSIN).on(Zt.FOCUSIN,function(t){document!==t.target&&e._element!==t.target&&0===Bt(e._element).has(t.target).length&&e._element.focus()})},t._setEscapeEvent=function(){var e=this;this._isShown&&this._config.keyboard?Bt(this._element).on(Zt.KEYDOWN_DISMISS,function(t){27===t.which&&(t.preventDefault(),e.hide())}):this._isShown||Bt(this._element).off(Zt.KEYDOWN_DISMISS)},t._setResizeEvent=function(){var e=this;this._isShown?Bt(window).on(Zt.RESIZE,function(t){return e.handleUpdate(t)}):Bt(window).off(Zt.RESIZE)},t._hideModal=function(){var t=this;this._element.style.display="none",this._element.setAttribute("aria-hidden",!0),this._isTransitioning=!1,this._showBackdrop(function(){Bt(document.body).removeClass(ee),t._resetAdjustments(),t._resetScrollbar(),Bt(t._element).trigger(Zt.HIDDEN)})},t._removeBackdrop=function(){this._backdrop&&(Bt(this._backdrop).remove(),this._backdrop=null)},t._showBackdrop=function(t){var e=this,n=Bt(this._element).hasClass(ne)?ne:"";if(this._isShown&&this._config.backdrop){if(this._backdrop=document.createElement("div"),this._backdrop.className=te,n&&Bt(this._backdrop).addClass(n),Bt(this._backdrop).appendTo(document.body),Bt(this._element).on(Zt.CLICK_DISMISS,function(t){e._ignoreBackdropClick?e._ignoreBackdropClick=!1:t.target===t.currentTarget&&("static"===e._config.backdrop?e._element.focus():e.hide())}),n&&Cn.reflow(this._backdrop),Bt(this._backdrop).addClass(ie),!t)return;if(!n)return void t();var i=Cn.getTransitionDurationFromElement(this._backdrop);Bt(this._backdrop).one(Cn.TRANSITION_END,t).emulateTransitionEnd(i)}else if(!this._isShown&&this._backdrop){Bt(this._backdrop).removeClass(ie);var r=function(){e._removeBackdrop(),t&&t()};if(Bt(this._element).hasClass(ne)){var s=Cn.getTransitionDurationFromElement(this._backdrop);Bt(this._backdrop).one(Cn.TRANSITION_END,r).emulateTransitionEnd(s)}else r()}else t&&t()},t._adjustDialog=function(){var t=this._element.scrollHeight>document.documentElement.clientHeight;!this._isBodyOverflowing&&t&&(this._element.style.paddingLeft=this._scrollbarWidth+"px"),this._isBodyOverflowing&&!t&&(this._element.style.paddingRight=this._scrollbarWidth+"px")},t._resetAdjustments=function(){this._element.style.paddingLeft="",this._element.style.paddingRight=""},t._checkScrollbar=function(){var t=document.body.getBoundingClientRect();this._isBodyOverflowing=t.left+t.right<window.innerWidth,this._scrollbarWidth=this._getScrollbarWidth()},t._setScrollbar=function(){var r=this;if(this._isBodyOverflowing){Bt(re.FIXED_CONTENT).each(function(t,e){var n=Bt(e)[0].style.paddingRight,i=Bt(e).css("padding-right");Bt(e).data("padding-right",n).css("padding-right",parseFloat(i)+r._scrollbarWidth+"px")}),Bt(re.STICKY_CONTENT).each(function(t,e){var n=Bt(e)[0].style.marginRight,i=Bt(e).css("margin-right");Bt(e).data("margin-right",n).css("margin-right",parseFloat(i)-r._scrollbarWidth+"px")}),Bt(re.NAVBAR_TOGGLER).each(function(t,e){var n=Bt(e)[0].style.marginRight,i=Bt(e).css("margin-right");Bt(e).data("margin-right",n).css("margin-right",parseFloat(i)+r._scrollbarWidth+"px")});var t=document.body.style.paddingRight,e=Bt(document.body).css("padding-right");Bt(document.body).data("padding-right",t).css("padding-right",parseFloat(e)+this._scrollbarWidth+"px")}},t._resetScrollbar=function(){Bt(re.FIXED_CONTENT).each(function(t,e){var n=Bt(e).data("padding-right");"undefined"!=typeof n&&Bt(e).css("padding-right",n).removeData("padding-right")}),Bt(re.STICKY_CONTENT+", "+re.NAVBAR_TOGGLER).each(function(t,e){var n=Bt(e).data("margin-right");"undefined"!=typeof n&&Bt(e).css("margin-right",n).removeData("margin-right")});var t=Bt(document.body).data("padding-right");"undefined"!=typeof t&&Bt(document.body).css("padding-right",t).removeData("padding-right")},t._getScrollbarWidth=function(){var t=document.createElement("div");t.className=$t,document.body.appendChild(t);var e=t.getBoundingClientRect().width-t.clientWidth;return document.body.removeChild(t),e},r._jQueryInterface=function(n,i){return this.each(function(){var t=Bt(this).data(Gt),e=h({},Xt,Bt(this).data(),"object"==typeof n&&n?n:{});if(t||(t=new r(this,e),Bt(this).data(Gt,t)),"string"==typeof n){if("undefined"==typeof t[n])throw new TypeError('No method named "'+n+'"');t[n](i)}else e.show&&t.show(i)})},o(r,null,[{key:"VERSION",get:function(){return"4.1.1"}},{key:"Default",get:function(){return Xt}}]),r}(),Bt(document).on(Zt.CLICK_DATA_API,re.DATA_TOGGLE,function(t){var e,n=this,i=Cn.getSelectorFromElement(this);i&&(e=Bt(i)[0]);var r=Bt(e).data(Gt)?"toggle":h({},Bt(e).data(),Bt(this).data());"A"!==this.tagName&&"AREA"!==this.tagName||t.preventDefault();var s=Bt(e).one(Zt.SHOW,function(t){t.isDefaultPrevented()||s.one(Zt.HIDDEN,function(){Bt(n).is(":visible")&&n.focus()})});se._jQueryInterface.call(Bt(e),r,this)}),Bt.fn[Yt]=se._jQueryInterface,Bt.fn[Yt].Constructor=se,Bt.fn[Yt].noConflict=function(){return Bt.fn[Yt]=zt,se._jQueryInterface},se),Nn=(ae="tooltip",he="."+(le="bs.tooltip"),ce=(oe=e).fn[ae],ue="bs-tooltip",fe=new RegExp("(^|\\s)"+ue+"\\S+","g"),ge={animation:!0,template:'<div class="tooltip" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>',trigger:"hover focus",title:"",delay:0,html:!(_e={AUTO:"auto",TOP:"top",RIGHT:"right",BOTTOM:"bottom",LEFT:"left"}),selector:!(de={animation:"boolean",template:"string",title:"(string|element|function)",trigger:"string",delay:"(number|object)",html:"boolean",selector:"(string|boolean)",placement:"(string|function)",offset:"(number|string)",container:"(string|element|boolean)",fallbackPlacement:"(string|array)",boundary:"(string|element)"}),placement:"top",offset:0,container:!1,fallbackPlacement:"flip",boundary:"scrollParent"},pe="out",ve={HIDE:"hide"+he,HIDDEN:"hidden"+he,SHOW:(me="show")+he,SHOWN:"shown"+he,INSERTED:"inserted"+he,CLICK:"click"+he,FOCUSIN:"focusin"+he,FOCUSOUT:"focusout"+he,MOUSEENTER:"mouseenter"+he,MOUSELEAVE:"mouseleave"+he},Ee="fade",ye="show",Te=".tooltip-inner",Ce=".arrow",Ie="hover",Ae="focus",De="click",be="manual",Se=function(){function i(t,e){if("undefined"==typeof c)throw new TypeError("Bootstrap tooltips require Popper.js (https://popper.js.org)");this._isEnabled=!0,this._timeout=0,this._hoverState="",this._activeTrigger={},this._popper=null,this.element=t,this.config=this._getConfig(e),this.tip=null,this._setListeners()}var t=i.prototype;return t.enable=function(){this._isEnabled=!0},t.disable=function(){this._isEnabled=!1},t.toggleEnabled=function(){this._isEnabled=!this._isEnabled},t.toggle=function(t){if(this._isEnabled)if(t){var e=this.constructor.DATA_KEY,n=oe(t.currentTarget).data(e);n||(n=new this.constructor(t.currentTarget,this._getDelegateConfig()),oe(t.currentTarget).data(e,n)),n._activeTrigger.click=!n._activeTrigger.click,n._isWithActiveTrigger()?n._enter(null,n):n._leave(null,n)}else{if(oe(this.getTipElement()).hasClass(ye))return void this._leave(null,this);this._enter(null,this)}},t.dispose=function(){clearTimeout(this._timeout),oe.removeData(this.element,this.constructor.DATA_KEY),oe(this.element).off(this.constructor.EVENT_KEY),oe(this.element).closest(".modal").off("hide.bs.modal"),this.tip&&oe(this.tip).remove(),this._isEnabled=null,this._timeout=null,this._hoverState=null,(this._activeTrigger=null)!==this._popper&&this._popper.destroy(),this._popper=null,this.element=null,this.config=null,this.tip=null},t.show=function(){var e=this;if("none"===oe(this.element).css("display"))throw new Error("Please use show on visible elements");var t=oe.Event(this.constructor.Event.SHOW);if(this.isWithContent()&&this._isEnabled){oe(this.element).trigger(t);var n=oe.contains(this.element.ownerDocument.documentElement,this.element);if(t.isDefaultPrevented()||!n)return;var i=this.getTipElement(),r=Cn.getUID(this.constructor.NAME);i.setAttribute("id",r),this.element.setAttribute("aria-describedby",r),this.setContent(),this.config.animation&&oe(i).addClass(Ee);var s="function"==typeof this.config.placement?this.config.placement.call(this,i,this.element):this.config.placement,o=this._getAttachment(s);this.addAttachmentClass(o);var a=!1===this.config.container?document.body:oe(this.config.container);oe(i).data(this.constructor.DATA_KEY,this),oe.contains(this.element.ownerDocument.documentElement,this.tip)||oe(i).appendTo(a),oe(this.element).trigger(this.constructor.Event.INSERTED),this._popper=new c(this.element,i,{placement:o,modifiers:{offset:{offset:this.config.offset},flip:{behavior:this.config.fallbackPlacement},arrow:{element:Ce},preventOverflow:{boundariesElement:this.config.boundary}},onCreate:function(t){t.originalPlacement!==t.placement&&e._handlePopperPlacementChange(t)},onUpdate:function(t){e._handlePopperPlacementChange(t)}}),oe(i).addClass(ye),"ontouchstart"in document.documentElement&&oe(document.body).children().on("mouseover",null,oe.noop);var l=function(){e.config.animation&&e._fixTransition();var t=e._hoverState;e._hoverState=null,oe(e.element).trigger(e.constructor.Event.SHOWN),t===pe&&e._leave(null,e)};if(oe(this.tip).hasClass(Ee)){var h=Cn.getTransitionDurationFromElement(this.tip);oe(this.tip).one(Cn.TRANSITION_END,l).emulateTransitionEnd(h)}else l()}},t.hide=function(t){var e=this,n=this.getTipElement(),i=oe.Event(this.constructor.Event.HIDE),r=function(){e._hoverState!==me&&n.parentNode&&n.parentNode.removeChild(n),e._cleanTipClass(),e.element.removeAttribute("aria-describedby"),oe(e.element).trigger(e.constructor.Event.HIDDEN),null!==e._popper&&e._popper.destroy(),t&&t()};if(oe(this.element).trigger(i),!i.isDefaultPrevented()){if(oe(n).removeClass(ye),"ontouchstart"in document.documentElement&&oe(document.body).children().off("mouseover",null,oe.noop),this._activeTrigger[De]=!1,this._activeTrigger[Ae]=!1,this._activeTrigger[Ie]=!1,oe(this.tip).hasClass(Ee)){var s=Cn.getTransitionDurationFromElement(n);oe(n).one(Cn.TRANSITION_END,r).emulateTransitionEnd(s)}else r();this._hoverState=""}},t.update=function(){null!==this._popper&&this._popper.scheduleUpdate()},t.isWithContent=function(){return Boolean(this.getTitle())},t.addAttachmentClass=function(t){oe(this.getTipElement()).addClass(ue+"-"+t)},t.getTipElement=function(){return this.tip=this.tip||oe(this.config.template)[0],this.tip},t.setContent=function(){var t=oe(this.getTipElement());this.setElementContent(t.find(Te),this.getTitle()),t.removeClass(Ee+" "+ye)},t.setElementContent=function(t,e){var n=this.config.html;"object"==typeof e&&(e.nodeType||e.jquery)?n?oe(e).parent().is(t)||t.empty().append(e):t.text(oe(e).text()):t[n?"html":"text"](e)},t.getTitle=function(){var t=this.element.getAttribute("data-original-title");return t||(t="function"==typeof this.config.title?this.config.title.call(this.element):this.config.title),t},t._getAttachment=function(t){return _e[t.toUpperCase()]},t._setListeners=function(){var i=this;this.config.trigger.split(" ").forEach(function(t){if("click"===t)oe(i.element).on(i.constructor.Event.CLICK,i.config.selector,function(t){return i.toggle(t)});else if(t!==be){var e=t===Ie?i.constructor.Event.MOUSEENTER:i.constructor.Event.FOCUSIN,n=t===Ie?i.constructor.Event.MOUSELEAVE:i.constructor.Event.FOCUSOUT;oe(i.element).on(e,i.config.selector,function(t){return i._enter(t)}).on(n,i.config.selector,function(t){return i._leave(t)})}oe(i.element).closest(".modal").on("hide.bs.modal",function(){return i.hide()})}),this.config.selector?this.config=h({},this.config,{trigger:"manual",selector:""}):this._fixTitle()},t._fixTitle=function(){var t=typeof this.element.getAttribute("data-original-title");(this.element.getAttribute("title")||"string"!==t)&&(this.element.setAttribute("data-original-title",this.element.getAttribute("title")||""),this.element.setAttribute("title",""))},t._enter=function(t,e){var n=this.constructor.DATA_KEY;(e=e||oe(t.currentTarget).data(n))||(e=new this.constructor(t.currentTarget,this._getDelegateConfig()),oe(t.currentTarget).data(n,e)),t&&(e._activeTrigger["focusin"===t.type?Ae:Ie]=!0),oe(e.getTipElement()).hasClass(ye)||e._hoverState===me?e._hoverState=me:(clearTimeout(e._timeout),e._hoverState=me,e.config.delay&&e.config.delay.show?e._timeout=setTimeout(function(){e._hoverState===me&&e.show()},e.config.delay.show):e.show())},t._leave=function(t,e){var n=this.constructor.DATA_KEY;(e=e||oe(t.currentTarget).data(n))||(e=new this.constructor(t.currentTarget,this._getDelegateConfig()),oe(t.currentTarget).data(n,e)),t&&(e._activeTrigger["focusout"===t.type?Ae:Ie]=!1),e._isWithActiveTrigger()||(clearTimeout(e._timeout),e._hoverState=pe,e.config.delay&&e.config.delay.hide?e._timeout=setTimeout(function(){e._hoverState===pe&&e.hide()},e.config.delay.hide):e.hide())},t._isWithActiveTrigger=function(){for(var t in this._activeTrigger)if(this._activeTrigger[t])return!0;return!1},t._getConfig=function(t){return"number"==typeof(t=h({},this.constructor.Default,oe(this.element).data(),"object"==typeof t&&t?t:{})).delay&&(t.delay={show:t.delay,hide:t.delay}),"number"==typeof t.title&&(t.title=t.title.toString()),"number"==typeof t.content&&(t.content=t.content.toString()),Cn.typeCheckConfig(ae,t,this.constructor.DefaultType),t},t._getDelegateConfig=function(){var t={};if(this.config)for(var e in this.config)this.constructor.Default[e]!==this.config[e]&&(t[e]=this.config[e]);return t},t._cleanTipClass=function(){var t=oe(this.getTipElement()),e=t.attr("class").match(fe);null!==e&&0<e.length&&t.removeClass(e.join(""))},t._handlePopperPlacementChange=function(t){this._cleanTipClass(),this.addAttachmentClass(this._getAttachment(t.placement))},t._fixTransition=function(){var t=this.getTipElement(),e=this.config.animation;null===t.getAttribute("x-placement")&&(oe(t).removeClass(Ee),this.config.animation=!1,this.hide(),this.show(),this.config.animation=e)},i._jQueryInterface=function(n){return this.each(function(){var t=oe(this).data(le),e="object"==typeof n&&n;if((t||!/dispose|hide/.test(n))&&(t||(t=new i(this,e),oe(this).data(le,t)),"string"==typeof n)){if("undefined"==typeof t[n])throw new TypeError('No method named "'+n+'"');t[n]()}})},o(i,null,[{key:"VERSION",get:function(){return"4.1.1"}},{key:"Default",get:function(){return ge}},{key:"NAME",get:function(){return ae}},{key:"DATA_KEY",get:function(){return le}},{key:"Event",get:function(){return ve}},{key:"EVENT_KEY",get:function(){return he}},{key:"DefaultType",get:function(){return de}}]),i}(),oe.fn[ae]=Se._jQueryInterface,oe.fn[ae].Constructor=Se,oe.fn[ae].noConflict=function(){return oe.fn[ae]=ce,Se._jQueryInterface},Se),On=(Ne="popover",ke="."+(Oe="bs.popover"),Pe=(we=e).fn[Ne],Le="bs-popover",je=new RegExp("(^|\\s)"+Le+"\\S+","g"),Re=h({},Nn.Default,{placement:"right",trigger:"click",content:"",template:'<div class="popover" role="tooltip"><div class="arrow"></div><h3 class="popover-header"></h3><div class="popover-body"></div></div>'}),He=h({},Nn.DefaultType,{content:"(string|element|function)"}),We="fade",xe=".popover-header",Ue=".popover-body",Ke={HIDE:"hide"+ke,HIDDEN:"hidden"+ke,SHOW:(Me="show")+ke,SHOWN:"shown"+ke,INSERTED:"inserted"+ke,CLICK:"click"+ke,FOCUSIN:"focusin"+ke,FOCUSOUT:"focusout"+ke,MOUSEENTER:"mouseenter"+ke,MOUSELEAVE:"mouseleave"+ke},Fe=function(t){var e,n;function i(){return t.apply(this,arguments)||this}n=t,(e=i).prototype=Object.create(n.prototype),(e.prototype.constructor=e).__proto__=n;var r=i.prototype;return r.isWithContent=function(){return this.getTitle()||this._getContent()},r.addAttachmentClass=function(t){we(this.getTipElement()).addClass(Le+"-"+t)},r.getTipElement=function(){return this.tip=this.tip||we(this.config.template)[0],this.tip},r.setContent=function(){var t=we(this.getTipElement());this.setElementContent(t.find(xe),this.getTitle());var e=this._getContent();"function"==typeof e&&(e=e.call(this.element)),this.setElementContent(t.find(Ue),e),t.removeClass(We+" "+Me)},r._getContent=function(){return this.element.getAttribute("data-content")||this.config.content},r._cleanTipClass=function(){var t=we(this.getTipElement()),e=t.attr("class").match(je);null!==e&&0<e.length&&t.removeClass(e.join(""))},i._jQueryInterface=function(n){return this.each(function(){var t=we(this).data(Oe),e="object"==typeof n?n:null;if((t||!/destroy|hide/.test(n))&&(t||(t=new i(this,e),we(this).data(Oe,t)),"string"==typeof n)){if("undefined"==typeof t[n])throw new TypeError('No method named "'+n+'"');t[n]()}})},o(i,null,[{key:"VERSION",get:function(){return"4.1.1"}},{key:"Default",get:function(){return Re}},{key:"NAME",get:function(){return Ne}},{key:"DATA_KEY",get:function(){return Oe}},{key:"Event",get:function(){return Ke}},{key:"EVENT_KEY",get:function(){return ke}},{key:"DefaultType",get:function(){return He}}]),i}(Nn),we.fn[Ne]=Fe._jQueryInterface,we.fn[Ne].Constructor=Fe,we.fn[Ne].noConflict=function(){return we.fn[Ne]=Pe,Fe._jQueryInterface},Fe),kn=(Qe="scrollspy",Ye="."+(Be="bs.scrollspy"),Ge=(Ve=e).fn[Qe],qe={offset:10,method:"auto",target:""},ze={offset:"number",method:"string",target:"(string|element)"},Xe={ACTIVATE:"activate"+Ye,SCROLL:"scroll"+Ye,LOAD_DATA_API:"load"+Ye+".data-api"},Je="dropdown-item",Ze="active",$e={DATA_SPY:'[data-spy="scroll"]',ACTIVE:".active",NAV_LIST_GROUP:".nav, .list-group",NAV_LINKS:".nav-link",NAV_ITEMS:".nav-item",LIST_ITEMS:".list-group-item",DROPDOWN:".dropdown",DROPDOWN_ITEMS:".dropdown-item",DROPDOWN_TOGGLE:".dropdown-toggle"},tn="offset",en="position",nn=function(){function n(t,e){var n=this;this._element=t,this._scrollElement="BODY"===t.tagName?window:t,this._config=this._getConfig(e),this._selector=this._config.target+" "+$e.NAV_LINKS+","+this._config.target+" "+$e.LIST_ITEMS+","+this._config.target+" "+$e.DROPDOWN_ITEMS,this._offsets=[],this._targets=[],this._activeTarget=null,this._scrollHeight=0,Ve(this._scrollElement).on(Xe.SCROLL,function(t){return n._process(t)}),this.refresh(),this._process()}var t=n.prototype;return t.refresh=function(){var e=this,t=this._scrollElement===this._scrollElement.window?tn:en,r="auto"===this._config.method?t:this._config.method,s=r===en?this._getScrollTop():0;this._offsets=[],this._targets=[],this._scrollHeight=this._getScrollHeight(),Ve.makeArray(Ve(this._selector)).map(function(t){var e,n=Cn.getSelectorFromElement(t);if(n&&(e=Ve(n)[0]),e){var i=e.getBoundingClientRect();if(i.width||i.height)return[Ve(e)[r]().top+s,n]}return null}).filter(function(t){return t}).sort(function(t,e){return t[0]-e[0]}).forEach(function(t){e._offsets.push(t[0]),e._targets.push(t[1])})},t.dispose=function(){Ve.removeData(this._element,Be),Ve(this._scrollElement).off(Ye),this._element=null,this._scrollElement=null,this._config=null,this._selector=null,this._offsets=null,this._targets=null,this._activeTarget=null,this._scrollHeight=null},t._getConfig=function(t){if("string"!=typeof(t=h({},qe,"object"==typeof t&&t?t:{})).target){var e=Ve(t.target).attr("id");e||(e=Cn.getUID(Qe),Ve(t.target).attr("id",e)),t.target="#"+e}return Cn.typeCheckConfig(Qe,t,ze),t},t._getScrollTop=function(){return this._scrollElement===window?this._scrollElement.pageYOffset:this._scrollElement.scrollTop},t._getScrollHeight=function(){return this._scrollElement.scrollHeight||Math.max(document.body.scrollHeight,document.documentElement.scrollHeight)},t._getOffsetHeight=function(){return this._scrollElement===window?window.innerHeight:this._scrollElement.getBoundingClientRect().height},t._process=function(){var t=this._getScrollTop()+this._config.offset,e=this._getScrollHeight(),n=this._config.offset+e-this._getOffsetHeight();if(this._scrollHeight!==e&&this.refresh(),n<=t){var i=this._targets[this._targets.length-1];this._activeTarget!==i&&this._activate(i)}else{if(this._activeTarget&&t<this._offsets[0]&&0<this._offsets[0])return this._activeTarget=null,void this._clear();for(var r=this._offsets.length;r--;){this._activeTarget!==this._targets[r]&&t>=this._offsets[r]&&("undefined"==typeof this._offsets[r+1]||t<this._offsets[r+1])&&this._activate(this._targets[r])}}},t._activate=function(e){this._activeTarget=e,this._clear();var t=this._selector.split(",");t=t.map(function(t){return t+'[data-target="'+e+'"],'+t+'[href="'+e+'"]'});var n=Ve(t.join(","));n.hasClass(Je)?(n.closest($e.DROPDOWN).find($e.DROPDOWN_TOGGLE).addClass(Ze),n.addClass(Ze)):(n.addClass(Ze),n.parents($e.NAV_LIST_GROUP).prev($e.NAV_LINKS+", "+$e.LIST_ITEMS).addClass(Ze),n.parents($e.NAV_LIST_GROUP).prev($e.NAV_ITEMS).children($e.NAV_LINKS).addClass(Ze)),Ve(this._scrollElement).trigger(Xe.ACTIVATE,{relatedTarget:e})},t._clear=function(){Ve(this._selector).filter($e.ACTIVE).removeClass(Ze)},n._jQueryInterface=function(e){return this.each(function(){var t=Ve(this).data(Be);if(t||(t=new n(this,"object"==typeof e&&e),Ve(this).data(Be,t)),"string"==typeof e){if("undefined"==typeof t[e])throw new TypeError('No method named "'+e+'"');t[e]()}})},o(n,null,[{key:"VERSION",get:function(){return"4.1.1"}},{key:"Default",get:function(){return qe}}]),n}(),Ve(window).on(Xe.LOAD_DATA_API,function(){for(var t=Ve.makeArray(Ve($e.DATA_SPY)),e=t.length;e--;){var n=Ve(t[e]);nn._jQueryInterface.call(n,n.data())}}),Ve.fn[Qe]=nn._jQueryInterface,Ve.fn[Qe].Constructor=nn,Ve.fn[Qe].noConflict=function(){return Ve.fn[Qe]=Ge,nn._jQueryInterface},nn),Pn=(on="."+(sn="bs.tab"),an=(rn=e).fn.tab,ln={HIDE:"hide"+on,HIDDEN:"hidden"+on,SHOW:"show"+on,SHOWN:"shown"+on,CLICK_DATA_API:"click"+on+".data-api"},hn="dropdown-menu",cn="active",un="disabled",fn="fade",dn="show",_n=".dropdown",gn=".nav, .list-group",mn=".active",pn="> li > .active",vn='[data-toggle="tab"], [data-toggle="pill"], [data-toggle="list"]',En=".dropdown-toggle",yn="> .dropdown-menu .active",Tn=function(){function i(t){this._element=t}var t=i.prototype;return t.show=function(){var n=this;if(!(this._element.parentNode&&this._element.parentNode.nodeType===Node.ELEMENT_NODE&&rn(this._element).hasClass(cn)||rn(this._element).hasClass(un))){var t,i,e=rn(this._element).closest(gn)[0],r=Cn.getSelectorFromElement(this._element);if(e){var s="UL"===e.nodeName?pn:mn;i=(i=rn.makeArray(rn(e).find(s)))[i.length-1]}var o=rn.Event(ln.HIDE,{relatedTarget:this._element}),a=rn.Event(ln.SHOW,{relatedTarget:i});if(i&&rn(i).trigger(o),rn(this._element).trigger(a),!a.isDefaultPrevented()&&!o.isDefaultPrevented()){r&&(t=rn(r)[0]),this._activate(this._element,e);var l=function(){var t=rn.Event(ln.HIDDEN,{relatedTarget:n._element}),e=rn.Event(ln.SHOWN,{relatedTarget:i});rn(i).trigger(t),rn(n._element).trigger(e)};t?this._activate(t,t.parentNode,l):l()}}},t.dispose=function(){rn.removeData(this._element,sn),this._element=null},t._activate=function(t,e,n){var i=this,r=("UL"===e.nodeName?rn(e).find(pn):rn(e).children(mn))[0],s=n&&r&&rn(r).hasClass(fn),o=function(){return i._transitionComplete(t,r,n)};if(r&&s){var a=Cn.getTransitionDurationFromElement(r);rn(r).one(Cn.TRANSITION_END,o).emulateTransitionEnd(a)}else o()},t._transitionComplete=function(t,e,n){if(e){rn(e).removeClass(dn+" "+cn);var i=rn(e.parentNode).find(yn)[0];i&&rn(i).removeClass(cn),"tab"===e.getAttribute("role")&&e.setAttribute("aria-selected",!1)}if(rn(t).addClass(cn),"tab"===t.getAttribute("role")&&t.setAttribute("aria-selected",!0),Cn.reflow(t),rn(t).addClass(dn),t.parentNode&&rn(t.parentNode).hasClass(hn)){var r=rn(t).closest(_n)[0];r&&rn(r).find(En).addClass(cn),t.setAttribute("aria-expanded",!0)}n&&n()},i._jQueryInterface=function(n){return this.each(function(){var t=rn(this),e=t.data(sn);if(e||(e=new i(this),t.data(sn,e)),"string"==typeof n){if("undefined"==typeof e[n])throw new TypeError('No method named "'+n+'"');e[n]()}})},o(i,null,[{key:"VERSION",get:function(){return"4.1.1"}}]),i}(),rn(document).on(ln.CLICK_DATA_API,vn,function(t){t.preventDefault(),Tn._jQueryInterface.call(rn(this),"show")}),rn.fn.tab=Tn._jQueryInterface,rn.fn.tab.Constructor=Tn,rn.fn.tab.noConflict=function(){return rn.fn.tab=an,Tn._jQueryInterface},Tn);!function(t){if("undefined"==typeof t)throw new TypeError("Bootstrap's JavaScript requires jQuery. jQuery must be included before Bootstrap's JavaScript.");var e=t.fn.jquery.split(" ")[0].split(".");if(e[0]<2&&e[1]<9||1===e[0]&&9===e[1]&&e[2]<1||4<=e[0])throw new Error("Bootstrap's JavaScript requires at least jQuery v1.9.1 but less than v4.0.0")}(e),t.Util=Cn,t.Alert=In,t.Button=An,t.Carousel=Dn,t.Collapse=bn,t.Dropdown=Sn,t.Modal=wn,t.Popover=On,t.Scrollspy=kn,t.Tab=Pn,t.Tooltip=Nn,Object.defineProperty(t,"__esModule",{value:!0})});
//# sourceMappingURL=bootstrap.min.js.map
//! moment.js
//! version : 2.10.6
//! authors : Tim Wood, Iskren Chernev, Moment.js contributors
//! license : MIT
//! momentjs.com
!function(a,b){"object"==typeof exports&&"undefined"!=typeof module?module.exports=b():"function"==typeof define&&define.amd?define(b):a.moment=b()}(this,function(){"use strict";function a(){return Hc.apply(null,arguments)}function b(a){Hc=a}function c(a){return"[object Array]"===Object.prototype.toString.call(a)}function d(a){return a instanceof Date||"[object Date]"===Object.prototype.toString.call(a)}function e(a,b){var c,d=[];for(c=0;c<a.length;++c)d.push(b(a[c],c));return d}function f(a,b){return Object.prototype.hasOwnProperty.call(a,b)}function g(a,b){for(var c in b)f(b,c)&&(a[c]=b[c]);return f(b,"toString")&&(a.toString=b.toString),f(b,"valueOf")&&(a.valueOf=b.valueOf),a}function h(a,b,c,d){return Ca(a,b,c,d,!0).utc()}function i(){return{empty:!1,unusedTokens:[],unusedInput:[],overflow:-2,charsLeftOver:0,nullInput:!1,invalidMonth:null,invalidFormat:!1,userInvalidated:!1,iso:!1}}function j(a){return null==a._pf&&(a._pf=i()),a._pf}function k(a){if(null==a._isValid){var b=j(a);a._isValid=!(isNaN(a._d.getTime())||!(b.overflow<0)||b.empty||b.invalidMonth||b.invalidWeekday||b.nullInput||b.invalidFormat||b.userInvalidated),a._strict&&(a._isValid=a._isValid&&0===b.charsLeftOver&&0===b.unusedTokens.length&&void 0===b.bigHour)}return a._isValid}function l(a){var b=h(NaN);return null!=a?g(j(b),a):j(b).userInvalidated=!0,b}function m(a,b){var c,d,e;if("undefined"!=typeof b._isAMomentObject&&(a._isAMomentObject=b._isAMomentObject),"undefined"!=typeof b._i&&(a._i=b._i),"undefined"!=typeof b._f&&(a._f=b._f),"undefined"!=typeof b._l&&(a._l=b._l),"undefined"!=typeof b._strict&&(a._strict=b._strict),"undefined"!=typeof b._tzm&&(a._tzm=b._tzm),"undefined"!=typeof b._isUTC&&(a._isUTC=b._isUTC),"undefined"!=typeof b._offset&&(a._offset=b._offset),"undefined"!=typeof b._pf&&(a._pf=j(b)),"undefined"!=typeof b._locale&&(a._locale=b._locale),Jc.length>0)for(c in Jc)d=Jc[c],e=b[d],"undefined"!=typeof e&&(a[d]=e);return a}function n(b){m(this,b),this._d=new Date(null!=b._d?b._d.getTime():NaN),Kc===!1&&(Kc=!0,a.updateOffset(this),Kc=!1)}function o(a){return a instanceof n||null!=a&&null!=a._isAMomentObject}function p(a){return 0>a?Math.ceil(a):Math.floor(a)}function q(a){var b=+a,c=0;return 0!==b&&isFinite(b)&&(c=p(b)),c}function r(a,b,c){var d,e=Math.min(a.length,b.length),f=Math.abs(a.length-b.length),g=0;for(d=0;e>d;d++)(c&&a[d]!==b[d]||!c&&q(a[d])!==q(b[d]))&&g++;return g+f}function s(){}function t(a){return a?a.toLowerCase().replace("_","-"):a}function u(a){for(var b,c,d,e,f=0;f<a.length;){for(e=t(a[f]).split("-"),b=e.length,c=t(a[f+1]),c=c?c.split("-"):null;b>0;){if(d=v(e.slice(0,b).join("-")))return d;if(c&&c.length>=b&&r(e,c,!0)>=b-1)break;b--}f++}return null}function v(a){var b=null;if(!Lc[a]&&"undefined"!=typeof module&&module&&module.exports)try{b=Ic._abbr,require("./locale/"+a),w(b)}catch(c){}return Lc[a]}function w(a,b){var c;return a&&(c="undefined"==typeof b?y(a):x(a,b),c&&(Ic=c)),Ic._abbr}function x(a,b){return null!==b?(b.abbr=a,Lc[a]=Lc[a]||new s,Lc[a].set(b),w(a),Lc[a]):(delete Lc[a],null)}function y(a){var b;if(a&&a._locale&&a._locale._abbr&&(a=a._locale._abbr),!a)return Ic;if(!c(a)){if(b=v(a))return b;a=[a]}return u(a)}function z(a,b){var c=a.toLowerCase();Mc[c]=Mc[c+"s"]=Mc[b]=a}function A(a){return"string"==typeof a?Mc[a]||Mc[a.toLowerCase()]:void 0}function B(a){var b,c,d={};for(c in a)f(a,c)&&(b=A(c),b&&(d[b]=a[c]));return d}function C(b,c){return function(d){return null!=d?(E(this,b,d),a.updateOffset(this,c),this):D(this,b)}}function D(a,b){return a._d["get"+(a._isUTC?"UTC":"")+b]()}function E(a,b,c){return a._d["set"+(a._isUTC?"UTC":"")+b](c)}function F(a,b){var c;if("object"==typeof a)for(c in a)this.set(c,a[c]);else if(a=A(a),"function"==typeof this[a])return this[a](b);return this}function G(a,b,c){var d=""+Math.abs(a),e=b-d.length,f=a>=0;return(f?c?"+":"":"-")+Math.pow(10,Math.max(0,e)).toString().substr(1)+d}function H(a,b,c,d){var e=d;"string"==typeof d&&(e=function(){return this[d]()}),a&&(Qc[a]=e),b&&(Qc[b[0]]=function(){return G(e.apply(this,arguments),b[1],b[2])}),c&&(Qc[c]=function(){return this.localeData().ordinal(e.apply(this,arguments),a)})}function I(a){return a.match(/\[[\s\S]/)?a.replace(/^\[|\]$/g,""):a.replace(/\\/g,"")}function J(a){var b,c,d=a.match(Nc);for(b=0,c=d.length;c>b;b++)Qc[d[b]]?d[b]=Qc[d[b]]:d[b]=I(d[b]);return function(e){var f="";for(b=0;c>b;b++)f+=d[b]instanceof Function?d[b].call(e,a):d[b];return f}}function K(a,b){return a.isValid()?(b=L(b,a.localeData()),Pc[b]=Pc[b]||J(b),Pc[b](a)):a.localeData().invalidDate()}function L(a,b){function c(a){return b.longDateFormat(a)||a}var d=5;for(Oc.lastIndex=0;d>=0&&Oc.test(a);)a=a.replace(Oc,c),Oc.lastIndex=0,d-=1;return a}function M(a){return"function"==typeof a&&"[object Function]"===Object.prototype.toString.call(a)}function N(a,b,c){dd[a]=M(b)?b:function(a){return a&&c?c:b}}function O(a,b){return f(dd,a)?dd[a](b._strict,b._locale):new RegExp(P(a))}function P(a){return a.replace("\\","").replace(/\\(\[)|\\(\])|\[([^\]\[]*)\]|\\(.)/g,function(a,b,c,d,e){return b||c||d||e}).replace(/[-\/\\^$*+?.()|[\]{}]/g,"\\$&")}function Q(a,b){var c,d=b;for("string"==typeof a&&(a=[a]),"number"==typeof b&&(d=function(a,c){c[b]=q(a)}),c=0;c<a.length;c++)ed[a[c]]=d}function R(a,b){Q(a,function(a,c,d,e){d._w=d._w||{},b(a,d._w,d,e)})}function S(a,b,c){null!=b&&f(ed,a)&&ed[a](b,c._a,c,a)}function T(a,b){return new Date(Date.UTC(a,b+1,0)).getUTCDate()}function U(a){return this._months[a.month()]}function V(a){return this._monthsShort[a.month()]}function W(a,b,c){var d,e,f;for(this._monthsParse||(this._monthsParse=[],this._longMonthsParse=[],this._shortMonthsParse=[]),d=0;12>d;d++){if(e=h([2e3,d]),c&&!this._longMonthsParse[d]&&(this._longMonthsParse[d]=new RegExp("^"+this.months(e,"").replace(".","")+"$","i"),this._shortMonthsParse[d]=new RegExp("^"+this.monthsShort(e,"").replace(".","")+"$","i")),c||this._monthsParse[d]||(f="^"+this.months(e,"")+"|^"+this.monthsShort(e,""),this._monthsParse[d]=new RegExp(f.replace(".",""),"i")),c&&"MMMM"===b&&this._longMonthsParse[d].test(a))return d;if(c&&"MMM"===b&&this._shortMonthsParse[d].test(a))return d;if(!c&&this._monthsParse[d].test(a))return d}}function X(a,b){var c;return"string"==typeof b&&(b=a.localeData().monthsParse(b),"number"!=typeof b)?a:(c=Math.min(a.date(),T(a.year(),b)),a._d["set"+(a._isUTC?"UTC":"")+"Month"](b,c),a)}function Y(b){return null!=b?(X(this,b),a.updateOffset(this,!0),this):D(this,"Month")}function Z(){return T(this.year(),this.month())}function $(a){var b,c=a._a;return c&&-2===j(a).overflow&&(b=c[gd]<0||c[gd]>11?gd:c[hd]<1||c[hd]>T(c[fd],c[gd])?hd:c[id]<0||c[id]>24||24===c[id]&&(0!==c[jd]||0!==c[kd]||0!==c[ld])?id:c[jd]<0||c[jd]>59?jd:c[kd]<0||c[kd]>59?kd:c[ld]<0||c[ld]>999?ld:-1,j(a)._overflowDayOfYear&&(fd>b||b>hd)&&(b=hd),j(a).overflow=b),a}function _(b){a.suppressDeprecationWarnings===!1&&"undefined"!=typeof console&&console.warn&&console.warn("Deprecation warning: "+b)}function aa(a,b){var c=!0;return g(function(){return c&&(_(a+"\n"+(new Error).stack),c=!1),b.apply(this,arguments)},b)}function ba(a,b){od[a]||(_(b),od[a]=!0)}function ca(a){var b,c,d=a._i,e=pd.exec(d);if(e){for(j(a).iso=!0,b=0,c=qd.length;c>b;b++)if(qd[b][1].exec(d)){a._f=qd[b][0];break}for(b=0,c=rd.length;c>b;b++)if(rd[b][1].exec(d)){a._f+=(e[6]||" ")+rd[b][0];break}d.match(ad)&&(a._f+="Z"),va(a)}else a._isValid=!1}function da(b){var c=sd.exec(b._i);return null!==c?void(b._d=new Date(+c[1])):(ca(b),void(b._isValid===!1&&(delete b._isValid,a.createFromInputFallback(b))))}function ea(a,b,c,d,e,f,g){var h=new Date(a,b,c,d,e,f,g);return 1970>a&&h.setFullYear(a),h}function fa(a){var b=new Date(Date.UTC.apply(null,arguments));return 1970>a&&b.setUTCFullYear(a),b}function ga(a){return ha(a)?366:365}function ha(a){return a%4===0&&a%100!==0||a%400===0}function ia(){return ha(this.year())}function ja(a,b,c){var d,e=c-b,f=c-a.day();return f>e&&(f-=7),e-7>f&&(f+=7),d=Da(a).add(f,"d"),{week:Math.ceil(d.dayOfYear()/7),year:d.year()}}function ka(a){return ja(a,this._week.dow,this._week.doy).week}function la(){return this._week.dow}function ma(){return this._week.doy}function na(a){var b=this.localeData().week(this);return null==a?b:this.add(7*(a-b),"d")}function oa(a){var b=ja(this,1,4).week;return null==a?b:this.add(7*(a-b),"d")}function pa(a,b,c,d,e){var f,g=6+e-d,h=fa(a,0,1+g),i=h.getUTCDay();return e>i&&(i+=7),c=null!=c?1*c:e,f=1+g+7*(b-1)-i+c,{year:f>0?a:a-1,dayOfYear:f>0?f:ga(a-1)+f}}function qa(a){var b=Math.round((this.clone().startOf("day")-this.clone().startOf("year"))/864e5)+1;return null==a?b:this.add(a-b,"d")}function ra(a,b,c){return null!=a?a:null!=b?b:c}function sa(a){var b=new Date;return a._useUTC?[b.getUTCFullYear(),b.getUTCMonth(),b.getUTCDate()]:[b.getFullYear(),b.getMonth(),b.getDate()]}function ta(a){var b,c,d,e,f=[];if(!a._d){for(d=sa(a),a._w&&null==a._a[hd]&&null==a._a[gd]&&ua(a),a._dayOfYear&&(e=ra(a._a[fd],d[fd]),a._dayOfYear>ga(e)&&(j(a)._overflowDayOfYear=!0),c=fa(e,0,a._dayOfYear),a._a[gd]=c.getUTCMonth(),a._a[hd]=c.getUTCDate()),b=0;3>b&&null==a._a[b];++b)a._a[b]=f[b]=d[b];for(;7>b;b++)a._a[b]=f[b]=null==a._a[b]?2===b?1:0:a._a[b];24===a._a[id]&&0===a._a[jd]&&0===a._a[kd]&&0===a._a[ld]&&(a._nextDay=!0,a._a[id]=0),a._d=(a._useUTC?fa:ea).apply(null,f),null!=a._tzm&&a._d.setUTCMinutes(a._d.getUTCMinutes()-a._tzm),a._nextDay&&(a._a[id]=24)}}function ua(a){var b,c,d,e,f,g,h;b=a._w,null!=b.GG||null!=b.W||null!=b.E?(f=1,g=4,c=ra(b.GG,a._a[fd],ja(Da(),1,4).year),d=ra(b.W,1),e=ra(b.E,1)):(f=a._locale._week.dow,g=a._locale._week.doy,c=ra(b.gg,a._a[fd],ja(Da(),f,g).year),d=ra(b.w,1),null!=b.d?(e=b.d,f>e&&++d):e=null!=b.e?b.e+f:f),h=pa(c,d,e,g,f),a._a[fd]=h.year,a._dayOfYear=h.dayOfYear}function va(b){if(b._f===a.ISO_8601)return void ca(b);b._a=[],j(b).empty=!0;var c,d,e,f,g,h=""+b._i,i=h.length,k=0;for(e=L(b._f,b._locale).match(Nc)||[],c=0;c<e.length;c++)f=e[c],d=(h.match(O(f,b))||[])[0],d&&(g=h.substr(0,h.indexOf(d)),g.length>0&&j(b).unusedInput.push(g),h=h.slice(h.indexOf(d)+d.length),k+=d.length),Qc[f]?(d?j(b).empty=!1:j(b).unusedTokens.push(f),S(f,d,b)):b._strict&&!d&&j(b).unusedTokens.push(f);j(b).charsLeftOver=i-k,h.length>0&&j(b).unusedInput.push(h),j(b).bigHour===!0&&b._a[id]<=12&&b._a[id]>0&&(j(b).bigHour=void 0),b._a[id]=wa(b._locale,b._a[id],b._meridiem),ta(b),$(b)}function wa(a,b,c){var d;return null==c?b:null!=a.meridiemHour?a.meridiemHour(b,c):null!=a.isPM?(d=a.isPM(c),d&&12>b&&(b+=12),d||12!==b||(b=0),b):b}function xa(a){var b,c,d,e,f;if(0===a._f.length)return j(a).invalidFormat=!0,void(a._d=new Date(NaN));for(e=0;e<a._f.length;e++)f=0,b=m({},a),null!=a._useUTC&&(b._useUTC=a._useUTC),b._f=a._f[e],va(b),k(b)&&(f+=j(b).charsLeftOver,f+=10*j(b).unusedTokens.length,j(b).score=f,(null==d||d>f)&&(d=f,c=b));g(a,c||b)}function ya(a){if(!a._d){var b=B(a._i);a._a=[b.year,b.month,b.day||b.date,b.hour,b.minute,b.second,b.millisecond],ta(a)}}function za(a){var b=new n($(Aa(a)));return b._nextDay&&(b.add(1,"d"),b._nextDay=void 0),b}function Aa(a){var b=a._i,e=a._f;return a._locale=a._locale||y(a._l),null===b||void 0===e&&""===b?l({nullInput:!0}):("string"==typeof b&&(a._i=b=a._locale.preparse(b)),o(b)?new n($(b)):(c(e)?xa(a):e?va(a):d(b)?a._d=b:Ba(a),a))}function Ba(b){var f=b._i;void 0===f?b._d=new Date:d(f)?b._d=new Date(+f):"string"==typeof f?da(b):c(f)?(b._a=e(f.slice(0),function(a){return parseInt(a,10)}),ta(b)):"object"==typeof f?ya(b):"number"==typeof f?b._d=new Date(f):a.createFromInputFallback(b)}function Ca(a,b,c,d,e){var f={};return"boolean"==typeof c&&(d=c,c=void 0),f._isAMomentObject=!0,f._useUTC=f._isUTC=e,f._l=c,f._i=a,f._f=b,f._strict=d,za(f)}function Da(a,b,c,d){return Ca(a,b,c,d,!1)}function Ea(a,b){var d,e;if(1===b.length&&c(b[0])&&(b=b[0]),!b.length)return Da();for(d=b[0],e=1;e<b.length;++e)(!b[e].isValid()||b[e][a](d))&&(d=b[e]);return d}function Fa(){var a=[].slice.call(arguments,0);return Ea("isBefore",a)}function Ga(){var a=[].slice.call(arguments,0);return Ea("isAfter",a)}function Ha(a){var b=B(a),c=b.year||0,d=b.quarter||0,e=b.month||0,f=b.week||0,g=b.day||0,h=b.hour||0,i=b.minute||0,j=b.second||0,k=b.millisecond||0;this._milliseconds=+k+1e3*j+6e4*i+36e5*h,this._days=+g+7*f,this._months=+e+3*d+12*c,this._data={},this._locale=y(),this._bubble()}function Ia(a){return a instanceof Ha}function Ja(a,b){H(a,0,0,function(){var a=this.utcOffset(),c="+";return 0>a&&(a=-a,c="-"),c+G(~~(a/60),2)+b+G(~~a%60,2)})}function Ka(a){var b=(a||"").match(ad)||[],c=b[b.length-1]||[],d=(c+"").match(xd)||["-",0,0],e=+(60*d[1])+q(d[2]);return"+"===d[0]?e:-e}function La(b,c){var e,f;return c._isUTC?(e=c.clone(),f=(o(b)||d(b)?+b:+Da(b))-+e,e._d.setTime(+e._d+f),a.updateOffset(e,!1),e):Da(b).local()}function Ma(a){return 15*-Math.round(a._d.getTimezoneOffset()/15)}function Na(b,c){var d,e=this._offset||0;return null!=b?("string"==typeof b&&(b=Ka(b)),Math.abs(b)<16&&(b=60*b),!this._isUTC&&c&&(d=Ma(this)),this._offset=b,this._isUTC=!0,null!=d&&this.add(d,"m"),e!==b&&(!c||this._changeInProgress?bb(this,Ya(b-e,"m"),1,!1):this._changeInProgress||(this._changeInProgress=!0,a.updateOffset(this,!0),this._changeInProgress=null)),this):this._isUTC?e:Ma(this)}function Oa(a,b){return null!=a?("string"!=typeof a&&(a=-a),this.utcOffset(a,b),this):-this.utcOffset()}function Pa(a){return this.utcOffset(0,a)}function Qa(a){return this._isUTC&&(this.utcOffset(0,a),this._isUTC=!1,a&&this.subtract(Ma(this),"m")),this}function Ra(){return this._tzm?this.utcOffset(this._tzm):"string"==typeof this._i&&this.utcOffset(Ka(this._i)),this}function Sa(a){return a=a?Da(a).utcOffset():0,(this.utcOffset()-a)%60===0}function Ta(){return this.utcOffset()>this.clone().month(0).utcOffset()||this.utcOffset()>this.clone().month(5).utcOffset()}function Ua(){if("undefined"!=typeof this._isDSTShifted)return this._isDSTShifted;var a={};if(m(a,this),a=Aa(a),a._a){var b=a._isUTC?h(a._a):Da(a._a);this._isDSTShifted=this.isValid()&&r(a._a,b.toArray())>0}else this._isDSTShifted=!1;return this._isDSTShifted}function Va(){return!this._isUTC}function Wa(){return this._isUTC}function Xa(){return this._isUTC&&0===this._offset}function Ya(a,b){var c,d,e,g=a,h=null;return Ia(a)?g={ms:a._milliseconds,d:a._days,M:a._months}:"number"==typeof a?(g={},b?g[b]=a:g.milliseconds=a):(h=yd.exec(a))?(c="-"===h[1]?-1:1,g={y:0,d:q(h[hd])*c,h:q(h[id])*c,m:q(h[jd])*c,s:q(h[kd])*c,ms:q(h[ld])*c}):(h=zd.exec(a))?(c="-"===h[1]?-1:1,g={y:Za(h[2],c),M:Za(h[3],c),d:Za(h[4],c),h:Za(h[5],c),m:Za(h[6],c),s:Za(h[7],c),w:Za(h[8],c)}):null==g?g={}:"object"==typeof g&&("from"in g||"to"in g)&&(e=_a(Da(g.from),Da(g.to)),g={},g.ms=e.milliseconds,g.M=e.months),d=new Ha(g),Ia(a)&&f(a,"_locale")&&(d._locale=a._locale),d}function Za(a,b){var c=a&&parseFloat(a.replace(",","."));return(isNaN(c)?0:c)*b}function $a(a,b){var c={milliseconds:0,months:0};return c.months=b.month()-a.month()+12*(b.year()-a.year()),a.clone().add(c.months,"M").isAfter(b)&&--c.months,c.milliseconds=+b-+a.clone().add(c.months,"M"),c}function _a(a,b){var c;return b=La(b,a),a.isBefore(b)?c=$a(a,b):(c=$a(b,a),c.milliseconds=-c.milliseconds,c.months=-c.months),c}function ab(a,b){return function(c,d){var e,f;return null===d||isNaN(+d)||(ba(b,"moment()."+b+"(period, number) is deprecated. Please use moment()."+b+"(number, period)."),f=c,c=d,d=f),c="string"==typeof c?+c:c,e=Ya(c,d),bb(this,e,a),this}}function bb(b,c,d,e){var f=c._milliseconds,g=c._days,h=c._months;e=null==e?!0:e,f&&b._d.setTime(+b._d+f*d),g&&E(b,"Date",D(b,"Date")+g*d),h&&X(b,D(b,"Month")+h*d),e&&a.updateOffset(b,g||h)}function cb(a,b){var c=a||Da(),d=La(c,this).startOf("day"),e=this.diff(d,"days",!0),f=-6>e?"sameElse":-1>e?"lastWeek":0>e?"lastDay":1>e?"sameDay":2>e?"nextDay":7>e?"nextWeek":"sameElse";return this.format(b&&b[f]||this.localeData().calendar(f,this,Da(c)))}function db(){return new n(this)}function eb(a,b){var c;return b=A("undefined"!=typeof b?b:"millisecond"),"millisecond"===b?(a=o(a)?a:Da(a),+this>+a):(c=o(a)?+a:+Da(a),c<+this.clone().startOf(b))}function fb(a,b){var c;return b=A("undefined"!=typeof b?b:"millisecond"),"millisecond"===b?(a=o(a)?a:Da(a),+a>+this):(c=o(a)?+a:+Da(a),+this.clone().endOf(b)<c)}function gb(a,b,c){return this.isAfter(a,c)&&this.isBefore(b,c)}function hb(a,b){var c;return b=A(b||"millisecond"),"millisecond"===b?(a=o(a)?a:Da(a),+this===+a):(c=+Da(a),+this.clone().startOf(b)<=c&&c<=+this.clone().endOf(b))}function ib(a,b,c){var d,e,f=La(a,this),g=6e4*(f.utcOffset()-this.utcOffset());return b=A(b),"year"===b||"month"===b||"quarter"===b?(e=jb(this,f),"quarter"===b?e/=3:"year"===b&&(e/=12)):(d=this-f,e="second"===b?d/1e3:"minute"===b?d/6e4:"hour"===b?d/36e5:"day"===b?(d-g)/864e5:"week"===b?(d-g)/6048e5:d),c?e:p(e)}function jb(a,b){var c,d,e=12*(b.year()-a.year())+(b.month()-a.month()),f=a.clone().add(e,"months");return 0>b-f?(c=a.clone().add(e-1,"months"),d=(b-f)/(f-c)):(c=a.clone().add(e+1,"months"),d=(b-f)/(c-f)),-(e+d)}function kb(){return this.clone().locale("en").format("ddd MMM DD YYYY HH:mm:ss [GMT]ZZ")}function lb(){var a=this.clone().utc();return 0<a.year()&&a.year()<=9999?"function"==typeof Date.prototype.toISOString?this.toDate().toISOString():K(a,"YYYY-MM-DD[T]HH:mm:ss.SSS[Z]"):K(a,"YYYYYY-MM-DD[T]HH:mm:ss.SSS[Z]")}function mb(b){var c=K(this,b||a.defaultFormat);return this.localeData().postformat(c)}function nb(a,b){return this.isValid()?Ya({to:this,from:a}).locale(this.locale()).humanize(!b):this.localeData().invalidDate()}function ob(a){return this.from(Da(),a)}function pb(a,b){return this.isValid()?Ya({from:this,to:a}).locale(this.locale()).humanize(!b):this.localeData().invalidDate()}function qb(a){return this.to(Da(),a)}function rb(a){var b;return void 0===a?this._locale._abbr:(b=y(a),null!=b&&(this._locale=b),this)}function sb(){return this._locale}function tb(a){switch(a=A(a)){case"year":this.month(0);case"quarter":case"month":this.date(1);case"week":case"isoWeek":case"day":this.hours(0);case"hour":this.minutes(0);case"minute":this.seconds(0);case"second":this.milliseconds(0)}return"week"===a&&this.weekday(0),"isoWeek"===a&&this.isoWeekday(1),"quarter"===a&&this.month(3*Math.floor(this.month()/3)),this}function ub(a){return a=A(a),void 0===a||"millisecond"===a?this:this.startOf(a).add(1,"isoWeek"===a?"week":a).subtract(1,"ms")}function vb(){return+this._d-6e4*(this._offset||0)}function wb(){return Math.floor(+this/1e3)}function xb(){return this._offset?new Date(+this):this._d}function yb(){var a=this;return[a.year(),a.month(),a.date(),a.hour(),a.minute(),a.second(),a.millisecond()]}function zb(){var a=this;return{years:a.year(),months:a.month(),date:a.date(),hours:a.hours(),minutes:a.minutes(),seconds:a.seconds(),milliseconds:a.milliseconds()}}function Ab(){return k(this)}function Bb(){return g({},j(this))}function Cb(){return j(this).overflow}function Db(a,b){H(0,[a,a.length],0,b)}function Eb(a,b,c){return ja(Da([a,11,31+b-c]),b,c).week}function Fb(a){var b=ja(this,this.localeData()._week.dow,this.localeData()._week.doy).year;return null==a?b:this.add(a-b,"y")}function Gb(a){var b=ja(this,1,4).year;return null==a?b:this.add(a-b,"y")}function Hb(){return Eb(this.year(),1,4)}function Ib(){var a=this.localeData()._week;return Eb(this.year(),a.dow,a.doy)}function Jb(a){return null==a?Math.ceil((this.month()+1)/3):this.month(3*(a-1)+this.month()%3)}function Kb(a,b){return"string"!=typeof a?a:isNaN(a)?(a=b.weekdaysParse(a),"number"==typeof a?a:null):parseInt(a,10)}function Lb(a){return this._weekdays[a.day()]}function Mb(a){return this._weekdaysShort[a.day()]}function Nb(a){return this._weekdaysMin[a.day()]}function Ob(a){var b,c,d;for(this._weekdaysParse=this._weekdaysParse||[],b=0;7>b;b++)if(this._weekdaysParse[b]||(c=Da([2e3,1]).day(b),d="^"+this.weekdays(c,"")+"|^"+this.weekdaysShort(c,"")+"|^"+this.weekdaysMin(c,""),this._weekdaysParse[b]=new RegExp(d.replace(".",""),"i")),this._weekdaysParse[b].test(a))return b}function Pb(a){var b=this._isUTC?this._d.getUTCDay():this._d.getDay();return null!=a?(a=Kb(a,this.localeData()),this.add(a-b,"d")):b}function Qb(a){var b=(this.day()+7-this.localeData()._week.dow)%7;return null==a?b:this.add(a-b,"d")}function Rb(a){return null==a?this.day()||7:this.day(this.day()%7?a:a-7)}function Sb(a,b){H(a,0,0,function(){return this.localeData().meridiem(this.hours(),this.minutes(),b)})}function Tb(a,b){return b._meridiemParse}function Ub(a){return"p"===(a+"").toLowerCase().charAt(0)}function Vb(a,b,c){return a>11?c?"pm":"PM":c?"am":"AM"}function Wb(a,b){b[ld]=q(1e3*("0."+a))}function Xb(){return this._isUTC?"UTC":""}function Yb(){return this._isUTC?"Coordinated Universal Time":""}function Zb(a){return Da(1e3*a)}function $b(){return Da.apply(null,arguments).parseZone()}function _b(a,b,c){var d=this._calendar[a];return"function"==typeof d?d.call(b,c):d}function ac(a){var b=this._longDateFormat[a],c=this._longDateFormat[a.toUpperCase()];return b||!c?b:(this._longDateFormat[a]=c.replace(/MMMM|MM|DD|dddd/g,function(a){return a.slice(1)}),this._longDateFormat[a])}function bc(){return this._invalidDate}function cc(a){return this._ordinal.replace("%d",a)}function dc(a){return a}function ec(a,b,c,d){var e=this._relativeTime[c];return"function"==typeof e?e(a,b,c,d):e.replace(/%d/i,a)}function fc(a,b){var c=this._relativeTime[a>0?"future":"past"];return"function"==typeof c?c(b):c.replace(/%s/i,b)}function gc(a){var b,c;for(c in a)b=a[c],"function"==typeof b?this[c]=b:this["_"+c]=b;this._ordinalParseLenient=new RegExp(this._ordinalParse.source+"|"+/\d{1,2}/.source)}function hc(a,b,c,d){var e=y(),f=h().set(d,b);return e[c](f,a)}function ic(a,b,c,d,e){if("number"==typeof a&&(b=a,a=void 0),a=a||"",null!=b)return hc(a,b,c,e);var f,g=[];for(f=0;d>f;f++)g[f]=hc(a,f,c,e);return g}function jc(a,b){return ic(a,b,"months",12,"month")}function kc(a,b){return ic(a,b,"monthsShort",12,"month")}function lc(a,b){return ic(a,b,"weekdays",7,"day")}function mc(a,b){return ic(a,b,"weekdaysShort",7,"day")}function nc(a,b){return ic(a,b,"weekdaysMin",7,"day")}function oc(){var a=this._data;return this._milliseconds=Wd(this._milliseconds),this._days=Wd(this._days),this._months=Wd(this._months),a.milliseconds=Wd(a.milliseconds),a.seconds=Wd(a.seconds),a.minutes=Wd(a.minutes),a.hours=Wd(a.hours),a.months=Wd(a.months),a.years=Wd(a.years),this}function pc(a,b,c,d){var e=Ya(b,c);return a._milliseconds+=d*e._milliseconds,a._days+=d*e._days,a._months+=d*e._months,a._bubble()}function qc(a,b){return pc(this,a,b,1)}function rc(a,b){return pc(this,a,b,-1)}function sc(a){return 0>a?Math.floor(a):Math.ceil(a)}function tc(){var a,b,c,d,e,f=this._milliseconds,g=this._days,h=this._months,i=this._data;return f>=0&&g>=0&&h>=0||0>=f&&0>=g&&0>=h||(f+=864e5*sc(vc(h)+g),g=0,h=0),i.milliseconds=f%1e3,a=p(f/1e3),i.seconds=a%60,b=p(a/60),i.minutes=b%60,c=p(b/60),i.hours=c%24,g+=p(c/24),e=p(uc(g)),h+=e,g-=sc(vc(e)),d=p(h/12),h%=12,i.days=g,i.months=h,i.years=d,this}function uc(a){return 4800*a/146097}function vc(a){return 146097*a/4800}function wc(a){var b,c,d=this._milliseconds;if(a=A(a),"month"===a||"year"===a)return b=this._days+d/864e5,c=this._months+uc(b),"month"===a?c:c/12;switch(b=this._days+Math.round(vc(this._months)),a){case"week":return b/7+d/6048e5;case"day":return b+d/864e5;case"hour":return 24*b+d/36e5;case"minute":return 1440*b+d/6e4;case"second":return 86400*b+d/1e3;case"millisecond":return Math.floor(864e5*b)+d;default:throw new Error("Unknown unit "+a)}}function xc(){return this._milliseconds+864e5*this._days+this._months%12*2592e6+31536e6*q(this._months/12)}function yc(a){return function(){return this.as(a)}}function zc(a){return a=A(a),this[a+"s"]()}function Ac(a){return function(){return this._data[a]}}function Bc(){return p(this.days()/7)}function Cc(a,b,c,d,e){return e.relativeTime(b||1,!!c,a,d)}function Dc(a,b,c){var d=Ya(a).abs(),e=ke(d.as("s")),f=ke(d.as("m")),g=ke(d.as("h")),h=ke(d.as("d")),i=ke(d.as("M")),j=ke(d.as("y")),k=e<le.s&&["s",e]||1===f&&["m"]||f<le.m&&["mm",f]||1===g&&["h"]||g<le.h&&["hh",g]||1===h&&["d"]||h<le.d&&["dd",h]||1===i&&["M"]||i<le.M&&["MM",i]||1===j&&["y"]||["yy",j];return k[2]=b,k[3]=+a>0,k[4]=c,Cc.apply(null,k)}function Ec(a,b){return void 0===le[a]?!1:void 0===b?le[a]:(le[a]=b,!0)}function Fc(a){var b=this.localeData(),c=Dc(this,!a,b);return a&&(c=b.pastFuture(+this,c)),b.postformat(c)}function Gc(){var a,b,c,d=me(this._milliseconds)/1e3,e=me(this._days),f=me(this._months);a=p(d/60),b=p(a/60),d%=60,a%=60,c=p(f/12),f%=12;var g=c,h=f,i=e,j=b,k=a,l=d,m=this.asSeconds();return m?(0>m?"-":"")+"P"+(g?g+"Y":"")+(h?h+"M":"")+(i?i+"D":"")+(j||k||l?"T":"")+(j?j+"H":"")+(k?k+"M":"")+(l?l+"S":""):"P0D"}var Hc,Ic,Jc=a.momentProperties=[],Kc=!1,Lc={},Mc={},Nc=/(\[[^\[]*\])|(\\)?(Mo|MM?M?M?|Do|DDDo|DD?D?D?|ddd?d?|do?|w[o|w]?|W[o|W]?|Q|YYYYYY|YYYYY|YYYY|YY|gg(ggg?)?|GG(GGG?)?|e|E|a|A|hh?|HH?|mm?|ss?|S{1,9}|x|X|zz?|ZZ?|.)/g,Oc=/(\[[^\[]*\])|(\\)?(LTS|LT|LL?L?L?|l{1,4})/g,Pc={},Qc={},Rc=/\d/,Sc=/\d\d/,Tc=/\d{3}/,Uc=/\d{4}/,Vc=/[+-]?\d{6}/,Wc=/\d\d?/,Xc=/\d{1,3}/,Yc=/\d{1,4}/,Zc=/[+-]?\d{1,6}/,$c=/\d+/,_c=/[+-]?\d+/,ad=/Z|[+-]\d\d:?\d\d/gi,bd=/[+-]?\d+(\.\d{1,3})?/,cd=/[0-9]*['a-z\u00A0-\u05FF\u0700-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+|[\u0600-\u06FF\/]+(\s*?[\u0600-\u06FF]+){1,2}/i,dd={},ed={},fd=0,gd=1,hd=2,id=3,jd=4,kd=5,ld=6;H("M",["MM",2],"Mo",function(){return this.month()+1}),H("MMM",0,0,function(a){return this.localeData().monthsShort(this,a)}),H("MMMM",0,0,function(a){return this.localeData().months(this,a)}),z("month","M"),N("M",Wc),N("MM",Wc,Sc),N("MMM",cd),N("MMMM",cd),Q(["M","MM"],function(a,b){b[gd]=q(a)-1}),Q(["MMM","MMMM"],function(a,b,c,d){var e=c._locale.monthsParse(a,d,c._strict);null!=e?b[gd]=e:j(c).invalidMonth=a});var md="January_February_March_April_May_June_July_August_September_October_November_December".split("_"),nd="Jan_Feb_Mar_Apr_May_Jun_Jul_Aug_Sep_Oct_Nov_Dec".split("_"),od={};a.suppressDeprecationWarnings=!1;var pd=/^\s*(?:[+-]\d{6}|\d{4})-(?:(\d\d-\d\d)|(W\d\d$)|(W\d\d-\d)|(\d\d\d))((T| )(\d\d(:\d\d(:\d\d(\.\d+)?)?)?)?([\+\-]\d\d(?::?\d\d)?|\s*Z)?)?$/,qd=[["YYYYYY-MM-DD",/[+-]\d{6}-\d{2}-\d{2}/],["YYYY-MM-DD",/\d{4}-\d{2}-\d{2}/],["GGGG-[W]WW-E",/\d{4}-W\d{2}-\d/],["GGGG-[W]WW",/\d{4}-W\d{2}/],["YYYY-DDD",/\d{4}-\d{3}/]],rd=[["HH:mm:ss.SSSS",/(T| )\d\d:\d\d:\d\d\.\d+/],["HH:mm:ss",/(T| )\d\d:\d\d:\d\d/],["HH:mm",/(T| )\d\d:\d\d/],["HH",/(T| )\d\d/]],sd=/^\/?Date\((\-?\d+)/i;a.createFromInputFallback=aa("moment construction falls back to js Date. This is discouraged and will be removed in upcoming major release. Please refer to https://github.com/moment/moment/issues/1407 for more info.",function(a){a._d=new Date(a._i+(a._useUTC?" UTC":""))}),H(0,["YY",2],0,function(){return this.year()%100}),H(0,["YYYY",4],0,"year"),H(0,["YYYYY",5],0,"year"),H(0,["YYYYYY",6,!0],0,"year"),z("year","y"),N("Y",_c),N("YY",Wc,Sc),N("YYYY",Yc,Uc),N("YYYYY",Zc,Vc),N("YYYYYY",Zc,Vc),Q(["YYYYY","YYYYYY"],fd),Q("YYYY",function(b,c){c[fd]=2===b.length?a.parseTwoDigitYear(b):q(b)}),Q("YY",function(b,c){c[fd]=a.parseTwoDigitYear(b)}),a.parseTwoDigitYear=function(a){return q(a)+(q(a)>68?1900:2e3)};var td=C("FullYear",!1);H("w",["ww",2],"wo","week"),H("W",["WW",2],"Wo","isoWeek"),z("week","w"),z("isoWeek","W"),N("w",Wc),N("ww",Wc,Sc),N("W",Wc),N("WW",Wc,Sc),R(["w","ww","W","WW"],function(a,b,c,d){b[d.substr(0,1)]=q(a)});var ud={dow:0,doy:6};H("DDD",["DDDD",3],"DDDo","dayOfYear"),z("dayOfYear","DDD"),N("DDD",Xc),N("DDDD",Tc),Q(["DDD","DDDD"],function(a,b,c){c._dayOfYear=q(a)}),a.ISO_8601=function(){};var vd=aa("moment().min is deprecated, use moment.min instead. https://github.com/moment/moment/issues/1548",function(){var a=Da.apply(null,arguments);return this>a?this:a}),wd=aa("moment().max is deprecated, use moment.max instead. https://github.com/moment/moment/issues/1548",function(){var a=Da.apply(null,arguments);return a>this?this:a});Ja("Z",":"),Ja("ZZ",""),N("Z",ad),N("ZZ",ad),Q(["Z","ZZ"],function(a,b,c){c._useUTC=!0,c._tzm=Ka(a)});var xd=/([\+\-]|\d\d)/gi;a.updateOffset=function(){};var yd=/(\-)?(?:(\d*)\.)?(\d+)\:(\d+)(?:\:(\d+)\.?(\d{3})?)?/,zd=/^(-)?P(?:(?:([0-9,.]*)Y)?(?:([0-9,.]*)M)?(?:([0-9,.]*)D)?(?:T(?:([0-9,.]*)H)?(?:([0-9,.]*)M)?(?:([0-9,.]*)S)?)?|([0-9,.]*)W)$/;Ya.fn=Ha.prototype;var Ad=ab(1,"add"),Bd=ab(-1,"subtract");a.defaultFormat="YYYY-MM-DDTHH:mm:ssZ";var Cd=aa("moment().lang() is deprecated. Instead, use moment().localeData() to get the language configuration. Use moment().locale() to change languages.",function(a){return void 0===a?this.localeData():this.locale(a)});H(0,["gg",2],0,function(){return this.weekYear()%100}),H(0,["GG",2],0,function(){return this.isoWeekYear()%100}),Db("gggg","weekYear"),Db("ggggg","weekYear"),Db("GGGG","isoWeekYear"),Db("GGGGG","isoWeekYear"),z("weekYear","gg"),z("isoWeekYear","GG"),N("G",_c),N("g",_c),N("GG",Wc,Sc),N("gg",Wc,Sc),N("GGGG",Yc,Uc),N("gggg",Yc,Uc),N("GGGGG",Zc,Vc),N("ggggg",Zc,Vc),R(["gggg","ggggg","GGGG","GGGGG"],function(a,b,c,d){b[d.substr(0,2)]=q(a)}),R(["gg","GG"],function(b,c,d,e){c[e]=a.parseTwoDigitYear(b)}),H("Q",0,0,"quarter"),z("quarter","Q"),N("Q",Rc),Q("Q",function(a,b){b[gd]=3*(q(a)-1)}),H("D",["DD",2],"Do","date"),z("date","D"),N("D",Wc),N("DD",Wc,Sc),N("Do",function(a,b){return a?b._ordinalParse:b._ordinalParseLenient}),Q(["D","DD"],hd),Q("Do",function(a,b){b[hd]=q(a.match(Wc)[0],10)});var Dd=C("Date",!0);H("d",0,"do","day"),H("dd",0,0,function(a){return this.localeData().weekdaysMin(this,a)}),H("ddd",0,0,function(a){return this.localeData().weekdaysShort(this,a)}),H("dddd",0,0,function(a){return this.localeData().weekdays(this,a)}),H("e",0,0,"weekday"),H("E",0,0,"isoWeekday"),z("day","d"),z("weekday","e"),z("isoWeekday","E"),N("d",Wc),N("e",Wc),N("E",Wc),N("dd",cd),N("ddd",cd),N("dddd",cd),R(["dd","ddd","dddd"],function(a,b,c){var d=c._locale.weekdaysParse(a);null!=d?b.d=d:j(c).invalidWeekday=a}),R(["d","e","E"],function(a,b,c,d){b[d]=q(a)});var Ed="Sunday_Monday_Tuesday_Wednesday_Thursday_Friday_Saturday".split("_"),Fd="Sun_Mon_Tue_Wed_Thu_Fri_Sat".split("_"),Gd="Su_Mo_Tu_We_Th_Fr_Sa".split("_");H("H",["HH",2],0,"hour"),H("h",["hh",2],0,function(){return this.hours()%12||12}),Sb("a",!0),Sb("A",!1),z("hour","h"),N("a",Tb),N("A",Tb),N("H",Wc),N("h",Wc),N("HH",Wc,Sc),N("hh",Wc,Sc),Q(["H","HH"],id),Q(["a","A"],function(a,b,c){c._isPm=c._locale.isPM(a),c._meridiem=a}),Q(["h","hh"],function(a,b,c){b[id]=q(a),j(c).bigHour=!0});var Hd=/[ap]\.?m?\.?/i,Id=C("Hours",!0);H("m",["mm",2],0,"minute"),z("minute","m"),N("m",Wc),N("mm",Wc,Sc),Q(["m","mm"],jd);var Jd=C("Minutes",!1);H("s",["ss",2],0,"second"),z("second","s"),N("s",Wc),N("ss",Wc,Sc),Q(["s","ss"],kd);var Kd=C("Seconds",!1);H("S",0,0,function(){return~~(this.millisecond()/100)}),H(0,["SS",2],0,function(){return~~(this.millisecond()/10)}),H(0,["SSS",3],0,"millisecond"),H(0,["SSSS",4],0,function(){return 10*this.millisecond()}),H(0,["SSSSS",5],0,function(){return 100*this.millisecond()}),H(0,["SSSSSS",6],0,function(){return 1e3*this.millisecond()}),H(0,["SSSSSSS",7],0,function(){return 1e4*this.millisecond()}),H(0,["SSSSSSSS",8],0,function(){return 1e5*this.millisecond()}),H(0,["SSSSSSSSS",9],0,function(){return 1e6*this.millisecond()}),z("millisecond","ms"),N("S",Xc,Rc),N("SS",Xc,Sc),N("SSS",Xc,Tc);var Ld;for(Ld="SSSS";Ld.length<=9;Ld+="S")N(Ld,$c);for(Ld="S";Ld.length<=9;Ld+="S")Q(Ld,Wb);var Md=C("Milliseconds",!1);H("z",0,0,"zoneAbbr"),H("zz",0,0,"zoneName");var Nd=n.prototype;Nd.add=Ad,Nd.calendar=cb,Nd.clone=db,Nd.diff=ib,Nd.endOf=ub,Nd.format=mb,Nd.from=nb,Nd.fromNow=ob,Nd.to=pb,Nd.toNow=qb,Nd.get=F,Nd.invalidAt=Cb,Nd.isAfter=eb,Nd.isBefore=fb,Nd.isBetween=gb,Nd.isSame=hb,Nd.isValid=Ab,Nd.lang=Cd,Nd.locale=rb,Nd.localeData=sb,Nd.max=wd,Nd.min=vd,Nd.parsingFlags=Bb,Nd.set=F,Nd.startOf=tb,Nd.subtract=Bd,Nd.toArray=yb,Nd.toObject=zb,Nd.toDate=xb,Nd.toISOString=lb,Nd.toJSON=lb,Nd.toString=kb,Nd.unix=wb,Nd.valueOf=vb,Nd.year=td,Nd.isLeapYear=ia,Nd.weekYear=Fb,Nd.isoWeekYear=Gb,Nd.quarter=Nd.quarters=Jb,Nd.month=Y,Nd.daysInMonth=Z,Nd.week=Nd.weeks=na,Nd.isoWeek=Nd.isoWeeks=oa,Nd.weeksInYear=Ib,Nd.isoWeeksInYear=Hb,Nd.date=Dd,Nd.day=Nd.days=Pb,Nd.weekday=Qb,Nd.isoWeekday=Rb,Nd.dayOfYear=qa,Nd.hour=Nd.hours=Id,Nd.minute=Nd.minutes=Jd,Nd.second=Nd.seconds=Kd,
Nd.millisecond=Nd.milliseconds=Md,Nd.utcOffset=Na,Nd.utc=Pa,Nd.local=Qa,Nd.parseZone=Ra,Nd.hasAlignedHourOffset=Sa,Nd.isDST=Ta,Nd.isDSTShifted=Ua,Nd.isLocal=Va,Nd.isUtcOffset=Wa,Nd.isUtc=Xa,Nd.isUTC=Xa,Nd.zoneAbbr=Xb,Nd.zoneName=Yb,Nd.dates=aa("dates accessor is deprecated. Use date instead.",Dd),Nd.months=aa("months accessor is deprecated. Use month instead",Y),Nd.years=aa("years accessor is deprecated. Use year instead",td),Nd.zone=aa("moment().zone is deprecated, use moment().utcOffset instead. https://github.com/moment/moment/issues/1779",Oa);var Od=Nd,Pd={sameDay:"[Today at] LT",nextDay:"[Tomorrow at] LT",nextWeek:"dddd [at] LT",lastDay:"[Yesterday at] LT",lastWeek:"[Last] dddd [at] LT",sameElse:"L"},Qd={LTS:"h:mm:ss A",LT:"h:mm A",L:"MM/DD/YYYY",LL:"MMMM D, YYYY",LLL:"MMMM D, YYYY h:mm A",LLLL:"dddd, MMMM D, YYYY h:mm A"},Rd="Invalid date",Sd="%d",Td=/\d{1,2}/,Ud={future:"in %s",past:"%s ago",s:"a few seconds",m:"a minute",mm:"%d minutes",h:"an hour",hh:"%d hours",d:"a day",dd:"%d days",M:"a month",MM:"%d months",y:"a year",yy:"%d years"},Vd=s.prototype;Vd._calendar=Pd,Vd.calendar=_b,Vd._longDateFormat=Qd,Vd.longDateFormat=ac,Vd._invalidDate=Rd,Vd.invalidDate=bc,Vd._ordinal=Sd,Vd.ordinal=cc,Vd._ordinalParse=Td,Vd.preparse=dc,Vd.postformat=dc,Vd._relativeTime=Ud,Vd.relativeTime=ec,Vd.pastFuture=fc,Vd.set=gc,Vd.months=U,Vd._months=md,Vd.monthsShort=V,Vd._monthsShort=nd,Vd.monthsParse=W,Vd.week=ka,Vd._week=ud,Vd.firstDayOfYear=ma,Vd.firstDayOfWeek=la,Vd.weekdays=Lb,Vd._weekdays=Ed,Vd.weekdaysMin=Nb,Vd._weekdaysMin=Gd,Vd.weekdaysShort=Mb,Vd._weekdaysShort=Fd,Vd.weekdaysParse=Ob,Vd.isPM=Ub,Vd._meridiemParse=Hd,Vd.meridiem=Vb,w("en",{ordinalParse:/\d{1,2}(th|st|nd|rd)/,ordinal:function(a){var b=a%10,c=1===q(a%100/10)?"th":1===b?"st":2===b?"nd":3===b?"rd":"th";return a+c}}),a.lang=aa("moment.lang is deprecated. Use moment.locale instead.",w),a.langData=aa("moment.langData is deprecated. Use moment.localeData instead.",y);var Wd=Math.abs,Xd=yc("ms"),Yd=yc("s"),Zd=yc("m"),$d=yc("h"),_d=yc("d"),ae=yc("w"),be=yc("M"),ce=yc("y"),de=Ac("milliseconds"),ee=Ac("seconds"),fe=Ac("minutes"),ge=Ac("hours"),he=Ac("days"),ie=Ac("months"),je=Ac("years"),ke=Math.round,le={s:45,m:45,h:22,d:26,M:11},me=Math.abs,ne=Ha.prototype;ne.abs=oc,ne.add=qc,ne.subtract=rc,ne.as=wc,ne.asMilliseconds=Xd,ne.asSeconds=Yd,ne.asMinutes=Zd,ne.asHours=$d,ne.asDays=_d,ne.asWeeks=ae,ne.asMonths=be,ne.asYears=ce,ne.valueOf=xc,ne._bubble=tc,ne.get=zc,ne.milliseconds=de,ne.seconds=ee,ne.minutes=fe,ne.hours=ge,ne.days=he,ne.weeks=Bc,ne.months=ie,ne.years=je,ne.humanize=Fc,ne.toISOString=Gc,ne.toString=Gc,ne.toJSON=Gc,ne.locale=rb,ne.localeData=sb,ne.toIsoString=aa("toIsoString() is deprecated. Please use toISOString() instead (notice the capitals)",Gc),ne.lang=Cd,H("X",0,0,"unix"),H("x",0,0,"valueOf"),N("x",_c),N("X",bd),Q("X",function(a,b,c){c._d=new Date(1e3*parseFloat(a,10))}),Q("x",function(a,b,c){c._d=new Date(q(a))}),a.version="2.10.6",b(Da),a.fn=Od,a.min=Fa,a.max=Ga,a.utc=h,a.unix=Zb,a.months=jc,a.isDate=d,a.locale=w,a.invalid=l,a.duration=Ya,a.isMoment=o,a.weekdays=lc,a.parseZone=$b,a.localeData=y,a.isDuration=Ia,a.monthsShort=kc,a.weekdaysMin=nc,a.defineLocale=x,a.weekdaysShort=mc,a.normalizeUnits=A,a.relativeTimeThreshold=Ec;var oe=a;return oe});
!function(t,e){"object"==typeof exports&&"object"==typeof module?module.exports=e():"function"==typeof define&&define.amd?define([],e):"object"==typeof exports?exports.swal=e():t.swal=e()}(this,function(){return function(t){function e(o){if(n[o])return n[o].exports;var r=n[o]={i:o,l:!1,exports:{}};return t[o].call(r.exports,r,r.exports,e),r.l=!0,r.exports}var n={};return e.m=t,e.c=n,e.d=function(t,n,o){e.o(t,n)||Object.defineProperty(t,n,{configurable:!1,enumerable:!0,get:o})},e.n=function(t){var n=t&&t.__esModule?function(){return t.default}:function(){return t};return e.d(n,"a",n),n},e.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},e.p="",e(e.s=8)}([function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var o="swal-button";e.CLASS_NAMES={MODAL:"swal-modal",OVERLAY:"swal-overlay",SHOW_MODAL:"swal-overlay--show-modal",MODAL_TITLE:"swal-title",MODAL_TEXT:"swal-text",ICON:"swal-icon",ICON_CUSTOM:"swal-icon--custom",CONTENT:"swal-content",FOOTER:"swal-footer",BUTTON_CONTAINER:"swal-button-container",BUTTON:o,CONFIRM_BUTTON:o+"--confirm",CANCEL_BUTTON:o+"--cancel",DANGER_BUTTON:o+"--danger",BUTTON_LOADING:o+"--loading",BUTTON_LOADER:o+"__loader"},e.default=e.CLASS_NAMES},function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.getNode=function(t){var e="."+t;return document.querySelector(e)},e.stringToNode=function(t){var e=document.createElement("div");return e.innerHTML=t.trim(),e.firstChild},e.insertAfter=function(t,e){var n=e.nextSibling;e.parentNode.insertBefore(t,n)},e.removeNode=function(t){t.parentElement.removeChild(t)},e.throwErr=function(t){throw t=t.replace(/ +(?= )/g,""),"SweetAlert: "+(t=t.trim())},e.isPlainObject=function(t){if("[object Object]"!==Object.prototype.toString.call(t))return!1;var e=Object.getPrototypeOf(t);return null===e||e===Object.prototype},e.ordinalSuffixOf=function(t){var e=t%10,n=t%100;return 1===e&&11!==n?t+"st":2===e&&12!==n?t+"nd":3===e&&13!==n?t+"rd":t+"th"}},function(t,e,n){"use strict";function o(t){for(var n in t)e.hasOwnProperty(n)||(e[n]=t[n])}Object.defineProperty(e,"__esModule",{value:!0}),o(n(25));var r=n(26);e.overlayMarkup=r.default,o(n(27)),o(n(28)),o(n(29));var i=n(0),a=i.default.MODAL_TITLE,s=i.default.MODAL_TEXT,c=i.default.ICON,l=i.default.FOOTER;e.iconMarkup='\n  <div class="'+c+'"></div>',e.titleMarkup='\n  <div class="'+a+'"></div>\n',e.textMarkup='\n  <div class="'+s+'"></div>',e.footerMarkup='\n  <div class="'+l+'"></div>\n'},function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var o=n(1);e.CONFIRM_KEY="confirm",e.CANCEL_KEY="cancel";var r={visible:!0,text:null,value:null,className:"",closeModal:!0},i=Object.assign({},r,{visible:!1,text:"Cancel",value:null}),a=Object.assign({},r,{text:"OK",value:!0});e.defaultButtonList={cancel:i,confirm:a};var s=function(t){switch(t){case e.CONFIRM_KEY:return a;case e.CANCEL_KEY:return i;default:var n=t.charAt(0).toUpperCase()+t.slice(1);return Object.assign({},r,{text:n,value:t})}},c=function(t,e){var n=s(t);return!0===e?Object.assign({},n,{visible:!0}):"string"==typeof e?Object.assign({},n,{visible:!0,text:e}):o.isPlainObject(e)?Object.assign({visible:!0},n,e):Object.assign({},n,{visible:!1})},l=function(t){for(var e={},n=0,o=Object.keys(t);n<o.length;n++){var r=o[n],a=t[r],s=c(r,a);e[r]=s}return e.cancel||(e.cancel=i),e},u=function(t){var n={};switch(t.length){case 1:n[e.CANCEL_KEY]=Object.assign({},i,{visible:!1});break;case 2:n[e.CANCEL_KEY]=c(e.CANCEL_KEY,t[0]),n[e.CONFIRM_KEY]=c(e.CONFIRM_KEY,t[1]);break;default:o.throwErr("Invalid number of 'buttons' in array ("+t.length+").\n      If you want more than 2 buttons, you need to use an object!")}return n};e.getButtonListOpts=function(t){var n=e.defaultButtonList;return"string"==typeof t?n[e.CONFIRM_KEY]=c(e.CONFIRM_KEY,t):Array.isArray(t)?n=u(t):o.isPlainObject(t)?n=l(t):!0===t?n=u([!0,!0]):!1===t?n=u([!1,!1]):void 0===t&&(n=e.defaultButtonList),n}},function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var o=n(1),r=n(2),i=n(0),a=i.default.MODAL,s=i.default.OVERLAY,c=n(30),l=n(31),u=n(32),f=n(33);e.injectElIntoModal=function(t){var e=o.getNode(a),n=o.stringToNode(t);return e.appendChild(n),n};var d=function(t){t.className=a,t.textContent=""},p=function(t,e){d(t);var n=e.className;n&&t.classList.add(n)};e.initModalContent=function(t){var e=o.getNode(a);p(e,t),c.default(t.icon),l.initTitle(t.title),l.initText(t.text),f.default(t.content),u.default(t.buttons,t.dangerMode)};var m=function(){var t=o.getNode(s),e=o.stringToNode(r.modalMarkup);t.appendChild(e)};e.default=m},function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var o=n(3),r={isOpen:!1,promise:null,actions:{},timer:null},i=Object.assign({},r);e.resetState=function(){i=Object.assign({},r)},e.setActionValue=function(t){if("string"==typeof t)return a(o.CONFIRM_KEY,t);for(var e in t)a(e,t[e])};var a=function(t,e){i.actions[t]||(i.actions[t]={}),Object.assign(i.actions[t],{value:e})};e.setActionOptionsFor=function(t,e){var n=(void 0===e?{}:e).closeModal,o=void 0===n||n;Object.assign(i.actions[t],{closeModal:o})},e.default=i},function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var o=n(1),r=n(3),i=n(0),a=i.default.OVERLAY,s=i.default.SHOW_MODAL,c=i.default.BUTTON,l=i.default.BUTTON_LOADING,u=n(5);e.openModal=function(){o.getNode(a).classList.add(s),u.default.isOpen=!0};var f=function(){o.getNode(a).classList.remove(s),u.default.isOpen=!1};e.onAction=function(t){void 0===t&&(t=r.CANCEL_KEY);var e=u.default.actions[t],n=e.value;if(!1===e.closeModal){var i=c+"--"+t;o.getNode(i).classList.add(l)}else f();u.default.promise.resolve(n)},e.getState=function(){var t=Object.assign({},u.default);return delete t.promise,delete t.timer,t},e.stopLoading=function(){for(var t=document.querySelectorAll("."+c),e=0;e<t.length;e++){t[e].classList.remove(l)}}},function(t,e){var n;n=function(){return this}();try{n=n||Function("return this")()||(0,eval)("this")}catch(t){"object"==typeof window&&(n=window)}t.exports=n},function(t,e,n){(function(e){t.exports=e.sweetAlert=n(9)}).call(e,n(7))},function(t,e,n){(function(e){t.exports=e.swal=n(10)}).call(e,n(7))},function(t,e,n){"undefined"!=typeof window&&n(11),n(16);var o=n(23).default;t.exports=o},function(t,e,n){var o=n(12);"string"==typeof o&&(o=[[t.i,o,""]]);var r={insertAt:"top"};r.transform=void 0;n(14)(o,r);o.locals&&(t.exports=o.locals)},function(t,e,n){e=t.exports=n(13)(void 0),e.push([t.i,'.swal-icon--error{border-color:#f27474;-webkit-animation:animateErrorIcon .5s;animation:animateErrorIcon .5s}.swal-icon--error__x-mark{position:relative;display:block;-webkit-animation:animateXMark .5s;animation:animateXMark .5s}.swal-icon--error__line{position:absolute;height:5px;width:47px;background-color:#f27474;display:block;top:37px;border-radius:2px}.swal-icon--error__line--left{-webkit-transform:rotate(45deg);transform:rotate(45deg);left:17px}.swal-icon--error__line--right{-webkit-transform:rotate(-45deg);transform:rotate(-45deg);right:16px}@-webkit-keyframes animateErrorIcon{0%{-webkit-transform:rotateX(100deg);transform:rotateX(100deg);opacity:0}to{-webkit-transform:rotateX(0deg);transform:rotateX(0deg);opacity:1}}@keyframes animateErrorIcon{0%{-webkit-transform:rotateX(100deg);transform:rotateX(100deg);opacity:0}to{-webkit-transform:rotateX(0deg);transform:rotateX(0deg);opacity:1}}@-webkit-keyframes animateXMark{0%{-webkit-transform:scale(.4);transform:scale(.4);margin-top:26px;opacity:0}50%{-webkit-transform:scale(.4);transform:scale(.4);margin-top:26px;opacity:0}80%{-webkit-transform:scale(1.15);transform:scale(1.15);margin-top:-6px}to{-webkit-transform:scale(1);transform:scale(1);margin-top:0;opacity:1}}@keyframes animateXMark{0%{-webkit-transform:scale(.4);transform:scale(.4);margin-top:26px;opacity:0}50%{-webkit-transform:scale(.4);transform:scale(.4);margin-top:26px;opacity:0}80%{-webkit-transform:scale(1.15);transform:scale(1.15);margin-top:-6px}to{-webkit-transform:scale(1);transform:scale(1);margin-top:0;opacity:1}}.swal-icon--warning{border-color:#f8bb86;-webkit-animation:pulseWarning .75s infinite alternate;animation:pulseWarning .75s infinite alternate}.swal-icon--warning__body{width:5px;height:47px;top:10px;border-radius:2px;margin-left:-2px}.swal-icon--warning__body,.swal-icon--warning__dot{position:absolute;left:50%;background-color:#f8bb86}.swal-icon--warning__dot{width:7px;height:7px;border-radius:50%;margin-left:-4px;bottom:-11px}@-webkit-keyframes pulseWarning{0%{border-color:#f8d486}to{border-color:#f8bb86}}@keyframes pulseWarning{0%{border-color:#f8d486}to{border-color:#f8bb86}}.swal-icon--success{border-color:#a5dc86}.swal-icon--success:after,.swal-icon--success:before{content:"";border-radius:50%;position:absolute;width:60px;height:120px;background:#fff;-webkit-transform:rotate(45deg);transform:rotate(45deg)}.swal-icon--success:before{border-radius:120px 0 0 120px;top:-7px;left:-33px;-webkit-transform:rotate(-45deg);transform:rotate(-45deg);-webkit-transform-origin:60px 60px;transform-origin:60px 60px}.swal-icon--success:after{border-radius:0 120px 120px 0;top:-11px;left:30px;-webkit-transform:rotate(-45deg);transform:rotate(-45deg);-webkit-transform-origin:0 60px;transform-origin:0 60px;-webkit-animation:rotatePlaceholder 4.25s ease-in;animation:rotatePlaceholder 4.25s ease-in}.swal-icon--success__ring{width:80px;height:80px;border:4px solid hsla(98,55%,69%,.2);border-radius:50%;box-sizing:content-box;position:absolute;left:-4px;top:-4px;z-index:2}.swal-icon--success__hide-corners{width:5px;height:90px;background-color:#fff;padding:1px;position:absolute;left:28px;top:8px;z-index:1;-webkit-transform:rotate(-45deg);transform:rotate(-45deg)}.swal-icon--success__line{height:5px;background-color:#a5dc86;display:block;border-radius:2px;position:absolute;z-index:2}.swal-icon--success__line--tip{width:25px;left:14px;top:46px;-webkit-transform:rotate(45deg);transform:rotate(45deg);-webkit-animation:animateSuccessTip .75s;animation:animateSuccessTip .75s}.swal-icon--success__line--long{width:47px;right:8px;top:38px;-webkit-transform:rotate(-45deg);transform:rotate(-45deg);-webkit-animation:animateSuccessLong .75s;animation:animateSuccessLong .75s}@-webkit-keyframes rotatePlaceholder{0%{-webkit-transform:rotate(-45deg);transform:rotate(-45deg)}5%{-webkit-transform:rotate(-45deg);transform:rotate(-45deg)}12%{-webkit-transform:rotate(-405deg);transform:rotate(-405deg)}to{-webkit-transform:rotate(-405deg);transform:rotate(-405deg)}}@keyframes rotatePlaceholder{0%{-webkit-transform:rotate(-45deg);transform:rotate(-45deg)}5%{-webkit-transform:rotate(-45deg);transform:rotate(-45deg)}12%{-webkit-transform:rotate(-405deg);transform:rotate(-405deg)}to{-webkit-transform:rotate(-405deg);transform:rotate(-405deg)}}@-webkit-keyframes animateSuccessTip{0%{width:0;left:1px;top:19px}54%{width:0;left:1px;top:19px}70%{width:50px;left:-8px;top:37px}84%{width:17px;left:21px;top:48px}to{width:25px;left:14px;top:45px}}@keyframes animateSuccessTip{0%{width:0;left:1px;top:19px}54%{width:0;left:1px;top:19px}70%{width:50px;left:-8px;top:37px}84%{width:17px;left:21px;top:48px}to{width:25px;left:14px;top:45px}}@-webkit-keyframes animateSuccessLong{0%{width:0;right:46px;top:54px}65%{width:0;right:46px;top:54px}84%{width:55px;right:0;top:35px}to{width:47px;right:8px;top:38px}}@keyframes animateSuccessLong{0%{width:0;right:46px;top:54px}65%{width:0;right:46px;top:54px}84%{width:55px;right:0;top:35px}to{width:47px;right:8px;top:38px}}.swal-icon--info{border-color:#c9dae1}.swal-icon--info:before{width:5px;height:29px;bottom:17px;border-radius:2px;margin-left:-2px}.swal-icon--info:after,.swal-icon--info:before{content:"";position:absolute;left:50%;background-color:#c9dae1}.swal-icon--info:after{width:7px;height:7px;border-radius:50%;margin-left:-3px;top:19px}.swal-icon{width:80px;height:80px;border-width:4px;border-style:solid;border-radius:50%;padding:0;position:relative;box-sizing:content-box;margin:20px auto}.swal-icon:first-child{margin-top:32px}.swal-icon--custom{width:auto;height:auto;max-width:100%;border:none;border-radius:0}.swal-icon img{max-width:100%;max-height:100%}.swal-title{color:rgba(0,0,0,.65);font-weight:600;text-transform:none;position:relative;display:block;padding:13px 16px;font-size:27px;line-height:normal;text-align:center;margin-bottom:0}.swal-title:first-child{margin-top:26px}.swal-title:not(:first-child){padding-bottom:0}.swal-title:not(:last-child){margin-bottom:13px}.swal-text{font-size:16px;position:relative;float:none;line-height:normal;vertical-align:top;text-align:left;display:inline-block;margin:0;padding:0 10px;font-weight:400;color:rgba(0,0,0,.64);max-width:calc(100% - 20px);overflow-wrap:break-word;box-sizing:border-box}.swal-text:first-child{margin-top:45px}.swal-text:last-child{margin-bottom:45px}.swal-footer{text-align:right;padding-top:13px;margin-top:13px;padding:13px 16px;border-radius:inherit;border-top-left-radius:0;border-top-right-radius:0}.swal-button-container{margin:5px;display:inline-block;position:relative}.swal-button{background-color:#7cd1f9;color:#fff;border:none;box-shadow:none;border-radius:5px;font-weight:600;font-size:14px;padding:10px 24px;margin:0;cursor:pointer}.swal-button[not:disabled]:hover{background-color:#78cbf2}.swal-button:active{background-color:#70bce0}.swal-button:focus{outline:none;box-shadow:0 0 0 1px #fff,0 0 0 3px rgba(43,114,165,.29)}.swal-button[disabled]{opacity:.5;cursor:default}.swal-button::-moz-focus-inner{border:0}.swal-button--cancel{color:#555;background-color:#efefef}.swal-button--cancel[not:disabled]:hover{background-color:#e8e8e8}.swal-button--cancel:active{background-color:#d7d7d7}.swal-button--cancel:focus{box-shadow:0 0 0 1px #fff,0 0 0 3px rgba(116,136,150,.29)}.swal-button--danger{background-color:#e64942}.swal-button--danger[not:disabled]:hover{background-color:#df4740}.swal-button--danger:active{background-color:#cf423b}.swal-button--danger:focus{box-shadow:0 0 0 1px #fff,0 0 0 3px rgba(165,43,43,.29)}.swal-content{padding:0 20px;margin-top:20px;font-size:medium}.swal-content:last-child{margin-bottom:20px}.swal-content__input,.swal-content__textarea{-webkit-appearance:none;background-color:#fff;border:none;font-size:14px;display:block;box-sizing:border-box;width:100%;border:1px solid rgba(0,0,0,.14);padding:10px 13px;border-radius:2px;transition:border-color .2s}.swal-content__input:focus,.swal-content__textarea:focus{outline:none;border-color:#6db8ff}.swal-content__textarea{resize:vertical}.swal-button--loading{color:transparent}.swal-button--loading~.swal-button__loader{opacity:1}.swal-button__loader{position:absolute;height:auto;width:43px;z-index:2;left:50%;top:50%;-webkit-transform:translateX(-50%) translateY(-50%);transform:translateX(-50%) translateY(-50%);text-align:center;pointer-events:none;opacity:0}.swal-button__loader div{display:inline-block;float:none;vertical-align:baseline;width:9px;height:9px;padding:0;border:none;margin:2px;opacity:.4;border-radius:7px;background-color:hsla(0,0%,100%,.9);transition:background .2s;-webkit-animation:swal-loading-anim 1s infinite;animation:swal-loading-anim 1s infinite}.swal-button__loader div:nth-child(3n+2){-webkit-animation-delay:.15s;animation-delay:.15s}.swal-button__loader div:nth-child(3n+3){-webkit-animation-delay:.3s;animation-delay:.3s}@-webkit-keyframes swal-loading-anim{0%{opacity:.4}20%{opacity:.4}50%{opacity:1}to{opacity:.4}}@keyframes swal-loading-anim{0%{opacity:.4}20%{opacity:.4}50%{opacity:1}to{opacity:.4}}.swal-overlay{position:fixed;top:0;bottom:0;left:0;right:0;text-align:center;font-size:0;overflow-y:auto;background-color:rgba(0,0,0,.4);z-index:10000;pointer-events:none;opacity:0;transition:opacity .3s}.swal-overlay:before{content:" ";display:inline-block;vertical-align:middle;height:100%}.swal-overlay--show-modal{opacity:1;pointer-events:auto}.swal-overlay--show-modal .swal-modal{opacity:1;pointer-events:auto;box-sizing:border-box;-webkit-animation:showSweetAlert .3s;animation:showSweetAlert .3s;will-change:transform}.swal-modal{width:478px;opacity:0;pointer-events:none;background-color:#fff;text-align:center;border-radius:5px;position:static;margin:20px auto;display:inline-block;vertical-align:middle;-webkit-transform:scale(1);transform:scale(1);-webkit-transform-origin:50% 50%;transform-origin:50% 50%;z-index:10001;transition:opacity .2s,-webkit-transform .3s;transition:transform .3s,opacity .2s;transition:transform .3s,opacity .2s,-webkit-transform .3s}@media (max-width:500px){.swal-modal{width:calc(100% - 20px)}}@-webkit-keyframes showSweetAlert{0%{-webkit-transform:scale(1);transform:scale(1)}1%{-webkit-transform:scale(.5);transform:scale(.5)}45%{-webkit-transform:scale(1.05);transform:scale(1.05)}80%{-webkit-transform:scale(.95);transform:scale(.95)}to{-webkit-transform:scale(1);transform:scale(1)}}@keyframes showSweetAlert{0%{-webkit-transform:scale(1);transform:scale(1)}1%{-webkit-transform:scale(.5);transform:scale(.5)}45%{-webkit-transform:scale(1.05);transform:scale(1.05)}80%{-webkit-transform:scale(.95);transform:scale(.95)}to{-webkit-transform:scale(1);transform:scale(1)}}',""])},function(t,e){function n(t,e){var n=t[1]||"",r=t[3];if(!r)return n;if(e&&"function"==typeof btoa){var i=o(r);return[n].concat(r.sources.map(function(t){return"/*# sourceURL="+r.sourceRoot+t+" */"})).concat([i]).join("\n")}return[n].join("\n")}function o(t){return"/*# sourceMappingURL=data:application/json;charset=utf-8;base64,"+btoa(unescape(encodeURIComponent(JSON.stringify(t))))+" */"}t.exports=function(t){var e=[];return e.toString=function(){return this.map(function(e){var o=n(e,t);return e[2]?"@media "+e[2]+"{"+o+"}":o}).join("")},e.i=function(t,n){"string"==typeof t&&(t=[[null,t,""]]);for(var o={},r=0;r<this.length;r++){var i=this[r][0];"number"==typeof i&&(o[i]=!0)}for(r=0;r<t.length;r++){var a=t[r];"number"==typeof a[0]&&o[a[0]]||(n&&!a[2]?a[2]=n:n&&(a[2]="("+a[2]+") and ("+n+")"),e.push(a))}},e}},function(t,e,n){function o(t,e){for(var n=0;n<t.length;n++){var o=t[n],r=m[o.id];if(r){r.refs++;for(var i=0;i<r.parts.length;i++)r.parts[i](o.parts[i]);for(;i<o.parts.length;i++)r.parts.push(u(o.parts[i],e))}else{for(var a=[],i=0;i<o.parts.length;i++)a.push(u(o.parts[i],e));m[o.id]={id:o.id,refs:1,parts:a}}}}function r(t,e){for(var n=[],o={},r=0;r<t.length;r++){var i=t[r],a=e.base?i[0]+e.base:i[0],s=i[1],c=i[2],l=i[3],u={css:s,media:c,sourceMap:l};o[a]?o[a].parts.push(u):n.push(o[a]={id:a,parts:[u]})}return n}function i(t,e){var n=v(t.insertInto);if(!n)throw new Error("Couldn't find a style target. This probably means that the value for the 'insertInto' parameter is invalid.");var o=w[w.length-1];if("top"===t.insertAt)o?o.nextSibling?n.insertBefore(e,o.nextSibling):n.appendChild(e):n.insertBefore(e,n.firstChild),w.push(e);else{if("bottom"!==t.insertAt)throw new Error("Invalid value for parameter 'insertAt'. Must be 'top' or 'bottom'.");n.appendChild(e)}}function a(t){if(null===t.parentNode)return!1;t.parentNode.removeChild(t);var e=w.indexOf(t);e>=0&&w.splice(e,1)}function s(t){var e=document.createElement("style");return t.attrs.type="text/css",l(e,t.attrs),i(t,e),e}function c(t){var e=document.createElement("link");return t.attrs.type="text/css",t.attrs.rel="stylesheet",l(e,t.attrs),i(t,e),e}function l(t,e){Object.keys(e).forEach(function(n){t.setAttribute(n,e[n])})}function u(t,e){var n,o,r,i;if(e.transform&&t.css){if(!(i=e.transform(t.css)))return function(){};t.css=i}if(e.singleton){var l=h++;n=g||(g=s(e)),o=f.bind(null,n,l,!1),r=f.bind(null,n,l,!0)}else t.sourceMap&&"function"==typeof URL&&"function"==typeof URL.createObjectURL&&"function"==typeof URL.revokeObjectURL&&"function"==typeof Blob&&"function"==typeof btoa?(n=c(e),o=p.bind(null,n,e),r=function(){a(n),n.href&&URL.revokeObjectURL(n.href)}):(n=s(e),o=d.bind(null,n),r=function(){a(n)});return o(t),function(e){if(e){if(e.css===t.css&&e.media===t.media&&e.sourceMap===t.sourceMap)return;o(t=e)}else r()}}function f(t,e,n,o){var r=n?"":o.css;if(t.styleSheet)t.styleSheet.cssText=x(e,r);else{var i=document.createTextNode(r),a=t.childNodes;a[e]&&t.removeChild(a[e]),a.length?t.insertBefore(i,a[e]):t.appendChild(i)}}function d(t,e){var n=e.css,o=e.media;if(o&&t.setAttribute("media",o),t.styleSheet)t.styleSheet.cssText=n;else{for(;t.firstChild;)t.removeChild(t.firstChild);t.appendChild(document.createTextNode(n))}}function p(t,e,n){var o=n.css,r=n.sourceMap,i=void 0===e.convertToAbsoluteUrls&&r;(e.convertToAbsoluteUrls||i)&&(o=y(o)),r&&(o+="\n/*# sourceMappingURL=data:application/json;base64,"+btoa(unescape(encodeURIComponent(JSON.stringify(r))))+" */");var a=new Blob([o],{type:"text/css"}),s=t.href;t.href=URL.createObjectURL(a),s&&URL.revokeObjectURL(s)}var m={},b=function(t){var e;return function(){return void 0===e&&(e=t.apply(this,arguments)),e}}(function(){return window&&document&&document.all&&!window.atob}),v=function(t){var e={};return function(n){return void 0===e[n]&&(e[n]=t.call(this,n)),e[n]}}(function(t){return document.querySelector(t)}),g=null,h=0,w=[],y=n(15);t.exports=function(t,e){if("undefined"!=typeof DEBUG&&DEBUG&&"object"!=typeof document)throw new Error("The style-loader cannot be used in a non-browser environment");e=e||{},e.attrs="object"==typeof e.attrs?e.attrs:{},e.singleton||(e.singleton=b()),e.insertInto||(e.insertInto="head"),e.insertAt||(e.insertAt="bottom");var n=r(t,e);return o(n,e),function(t){for(var i=[],a=0;a<n.length;a++){var s=n[a],c=m[s.id];c.refs--,i.push(c)}if(t){o(r(t,e),e)}for(var a=0;a<i.length;a++){var c=i[a];if(0===c.refs){for(var l=0;l<c.parts.length;l++)c.parts[l]();delete m[c.id]}}}};var x=function(){var t=[];return function(e,n){return t[e]=n,t.filter(Boolean).join("\n")}}()},function(t,e){t.exports=function(t){var e="undefined"!=typeof window&&window.location;if(!e)throw new Error("fixUrls requires window.location");if(!t||"string"!=typeof t)return t;var n=e.protocol+"//"+e.host,o=n+e.pathname.replace(/\/[^\/]*$/,"/");return t.replace(/url\s*\(((?:[^)(]|\((?:[^)(]+|\([^)(]*\))*\))*)\)/gi,function(t,e){var r=e.trim().replace(/^"(.*)"$/,function(t,e){return e}).replace(/^'(.*)'$/,function(t,e){return e});if(/^(#|data:|http:\/\/|https:\/\/|file:\/\/\/)/i.test(r))return t;var i;return i=0===r.indexOf("//")?r:0===r.indexOf("/")?n+r:o+r.replace(/^\.\//,""),"url("+JSON.stringify(i)+")"})}},function(t,e,n){var o=n(17);"undefined"==typeof window||window.Promise||(window.Promise=o),n(21),String.prototype.includes||(String.prototype.includes=function(t,e){"use strict";return"number"!=typeof e&&(e=0),!(e+t.length>this.length)&&-1!==this.indexOf(t,e)}),Array.prototype.includes||Object.defineProperty(Array.prototype,"includes",{value:function(t,e){if(null==this)throw new TypeError('"this" is null or not defined');var n=Object(this),o=n.length>>>0;if(0===o)return!1;for(var r=0|e,i=Math.max(r>=0?r:o-Math.abs(r),0);i<o;){if(function(t,e){return t===e||"number"==typeof t&&"number"==typeof e&&isNaN(t)&&isNaN(e)}(n[i],t))return!0;i++}return!1}}),"undefined"!=typeof window&&function(t){t.forEach(function(t){t.hasOwnProperty("remove")||Object.defineProperty(t,"remove",{configurable:!0,enumerable:!0,writable:!0,value:function(){this.parentNode.removeChild(this)}})})}([Element.prototype,CharacterData.prototype,DocumentType.prototype])},function(t,e,n){(function(e){!function(n){function o(){}function r(t,e){return function(){t.apply(e,arguments)}}function i(t){if("object"!=typeof this)throw new TypeError("Promises must be constructed via new");if("function"!=typeof t)throw new TypeError("not a function");this._state=0,this._handled=!1,this._value=void 0,this._deferreds=[],f(t,this)}function a(t,e){for(;3===t._state;)t=t._value;if(0===t._state)return void t._deferreds.push(e);t._handled=!0,i._immediateFn(function(){var n=1===t._state?e.onFulfilled:e.onRejected;if(null===n)return void(1===t._state?s:c)(e.promise,t._value);var o;try{o=n(t._value)}catch(t){return void c(e.promise,t)}s(e.promise,o)})}function s(t,e){try{if(e===t)throw new TypeError("A promise cannot be resolved with itself.");if(e&&("object"==typeof e||"function"==typeof e)){var n=e.then;if(e instanceof i)return t._state=3,t._value=e,void l(t);if("function"==typeof n)return void f(r(n,e),t)}t._state=1,t._value=e,l(t)}catch(e){c(t,e)}}function c(t,e){t._state=2,t._value=e,l(t)}function l(t){2===t._state&&0===t._deferreds.length&&i._immediateFn(function(){t._handled||i._unhandledRejectionFn(t._value)});for(var e=0,n=t._deferreds.length;e<n;e++)a(t,t._deferreds[e]);t._deferreds=null}function u(t,e,n){this.onFulfilled="function"==typeof t?t:null,this.onRejected="function"==typeof e?e:null,this.promise=n}function f(t,e){var n=!1;try{t(function(t){n||(n=!0,s(e,t))},function(t){n||(n=!0,c(e,t))})}catch(t){if(n)return;n=!0,c(e,t)}}var d=setTimeout;i.prototype.catch=function(t){return this.then(null,t)},i.prototype.then=function(t,e){var n=new this.constructor(o);return a(this,new u(t,e,n)),n},i.all=function(t){var e=Array.prototype.slice.call(t);return new i(function(t,n){function o(i,a){try{if(a&&("object"==typeof a||"function"==typeof a)){var s=a.then;if("function"==typeof s)return void s.call(a,function(t){o(i,t)},n)}e[i]=a,0==--r&&t(e)}catch(t){n(t)}}if(0===e.length)return t([]);for(var r=e.length,i=0;i<e.length;i++)o(i,e[i])})},i.resolve=function(t){return t&&"object"==typeof t&&t.constructor===i?t:new i(function(e){e(t)})},i.reject=function(t){return new i(function(e,n){n(t)})},i.race=function(t){return new i(function(e,n){for(var o=0,r=t.length;o<r;o++)t[o].then(e,n)})},i._immediateFn="function"==typeof e&&function(t){e(t)}||function(t){d(t,0)},i._unhandledRejectionFn=function(t){"undefined"!=typeof console&&console&&console.warn("Possible Unhandled Promise Rejection:",t)},i._setImmediateFn=function(t){i._immediateFn=t},i._setUnhandledRejectionFn=function(t){i._unhandledRejectionFn=t},void 0!==t&&t.exports?t.exports=i:n.Promise||(n.Promise=i)}(this)}).call(e,n(18).setImmediate)},function(t,e,n){function o(t,e){this._id=t,this._clearFn=e}var r=Function.prototype.apply;e.setTimeout=function(){return new o(r.call(setTimeout,window,arguments),clearTimeout)},e.setInterval=function(){return new o(r.call(setInterval,window,arguments),clearInterval)},e.clearTimeout=e.clearInterval=function(t){t&&t.close()},o.prototype.unref=o.prototype.ref=function(){},o.prototype.close=function(){this._clearFn.call(window,this._id)},e.enroll=function(t,e){clearTimeout(t._idleTimeoutId),t._idleTimeout=e},e.unenroll=function(t){clearTimeout(t._idleTimeoutId),t._idleTimeout=-1},e._unrefActive=e.active=function(t){clearTimeout(t._idleTimeoutId);var e=t._idleTimeout;e>=0&&(t._idleTimeoutId=setTimeout(function(){t._onTimeout&&t._onTimeout()},e))},n(19),e.setImmediate=setImmediate,e.clearImmediate=clearImmediate},function(t,e,n){(function(t,e){!function(t,n){"use strict";function o(t){"function"!=typeof t&&(t=new Function(""+t));for(var e=new Array(arguments.length-1),n=0;n<e.length;n++)e[n]=arguments[n+1];var o={callback:t,args:e};return l[c]=o,s(c),c++}function r(t){delete l[t]}function i(t){var e=t.callback,o=t.args;switch(o.length){case 0:e();break;case 1:e(o[0]);break;case 2:e(o[0],o[1]);break;case 3:e(o[0],o[1],o[2]);break;default:e.apply(n,o)}}function a(t){if(u)setTimeout(a,0,t);else{var e=l[t];if(e){u=!0;try{i(e)}finally{r(t),u=!1}}}}if(!t.setImmediate){var s,c=1,l={},u=!1,f=t.document,d=Object.getPrototypeOf&&Object.getPrototypeOf(t);d=d&&d.setTimeout?d:t,"[object process]"==={}.toString.call(t.process)?function(){s=function(t){e.nextTick(function(){a(t)})}}():function(){if(t.postMessage&&!t.importScripts){var e=!0,n=t.onmessage;return t.onmessage=function(){e=!1},t.postMessage("","*"),t.onmessage=n,e}}()?function(){var e="setImmediate$"+Math.random()+"$",n=function(n){n.source===t&&"string"==typeof n.data&&0===n.data.indexOf(e)&&a(+n.data.slice(e.length))};t.addEventListener?t.addEventListener("message",n,!1):t.attachEvent("onmessage",n),s=function(n){t.postMessage(e+n,"*")}}():t.MessageChannel?function(){var t=new MessageChannel;t.port1.onmessage=function(t){a(t.data)},s=function(e){t.port2.postMessage(e)}}():f&&"onreadystatechange"in f.createElement("script")?function(){var t=f.documentElement;s=function(e){var n=f.createElement("script");n.onreadystatechange=function(){a(e),n.onreadystatechange=null,t.removeChild(n),n=null},t.appendChild(n)}}():function(){s=function(t){setTimeout(a,0,t)}}(),d.setImmediate=o,d.clearImmediate=r}}("undefined"==typeof self?void 0===t?this:t:self)}).call(e,n(7),n(20))},function(t,e){function n(){throw new Error("setTimeout has not been defined")}function o(){throw new Error("clearTimeout has not been defined")}function r(t){if(u===setTimeout)return setTimeout(t,0);if((u===n||!u)&&setTimeout)return u=setTimeout,setTimeout(t,0);try{return u(t,0)}catch(e){try{return u.call(null,t,0)}catch(e){return u.call(this,t,0)}}}function i(t){if(f===clearTimeout)return clearTimeout(t);if((f===o||!f)&&clearTimeout)return f=clearTimeout,clearTimeout(t);try{return f(t)}catch(e){try{return f.call(null,t)}catch(e){return f.call(this,t)}}}function a(){b&&p&&(b=!1,p.length?m=p.concat(m):v=-1,m.length&&s())}function s(){if(!b){var t=r(a);b=!0;for(var e=m.length;e;){for(p=m,m=[];++v<e;)p&&p[v].run();v=-1,e=m.length}p=null,b=!1,i(t)}}function c(t,e){this.fun=t,this.array=e}function l(){}var u,f,d=t.exports={};!function(){try{u="function"==typeof setTimeout?setTimeout:n}catch(t){u=n}try{f="function"==typeof clearTimeout?clearTimeout:o}catch(t){f=o}}();var p,m=[],b=!1,v=-1;d.nextTick=function(t){var e=new Array(arguments.length-1);if(arguments.length>1)for(var n=1;n<arguments.length;n++)e[n-1]=arguments[n];m.push(new c(t,e)),1!==m.length||b||r(s)},c.prototype.run=function(){this.fun.apply(null,this.array)},d.title="browser",d.browser=!0,d.env={},d.argv=[],d.version="",d.versions={},d.on=l,d.addListener=l,d.once=l,d.off=l,d.removeListener=l,d.removeAllListeners=l,d.emit=l,d.prependListener=l,d.prependOnceListener=l,d.listeners=function(t){return[]},d.binding=function(t){throw new Error("process.binding is not supported")},d.cwd=function(){return"/"},d.chdir=function(t){throw new Error("process.chdir is not supported")},d.umask=function(){return 0}},function(t,e,n){"use strict";n(22).polyfill()},function(t,e,n){"use strict";function o(t,e){if(void 0===t||null===t)throw new TypeError("Cannot convert first argument to object");for(var n=Object(t),o=1;o<arguments.length;o++){var r=arguments[o];if(void 0!==r&&null!==r)for(var i=Object.keys(Object(r)),a=0,s=i.length;a<s;a++){var c=i[a],l=Object.getOwnPropertyDescriptor(r,c);void 0!==l&&l.enumerable&&(n[c]=r[c])}}return n}function r(){Object.assign||Object.defineProperty(Object,"assign",{enumerable:!1,configurable:!0,writable:!0,value:o})}t.exports={assign:o,polyfill:r}},function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var o=n(24),r=n(6),i=n(5),a=n(36),s=function(){for(var t=[],e=0;e<arguments.length;e++)t[e]=arguments[e];if("undefined"!=typeof window){var n=a.getOpts.apply(void 0,t);return new Promise(function(t,e){i.default.promise={resolve:t,reject:e},o.default(n),setTimeout(function(){r.openModal()})})}};s.close=r.onAction,s.getState=r.getState,s.setActionValue=i.setActionValue,s.stopLoading=r.stopLoading,s.setDefaults=a.setDefaults,e.default=s},function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var o=n(1),r=n(0),i=r.default.MODAL,a=n(4),s=n(34),c=n(35),l=n(1);e.init=function(t){o.getNode(i)||(document.body||l.throwErr("You can only use SweetAlert AFTER the DOM has loaded!"),s.default(),a.default()),a.initModalContent(t),c.default(t)},e.default=e.init},function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var o=n(0),r=o.default.MODAL;e.modalMarkup='\n  <div class="'+r+'" role="dialog" aria-modal="true"></div>',e.default=e.modalMarkup},function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var o=n(0),r=o.default.OVERLAY,i='<div \n    class="'+r+'"\n    tabIndex="-1">\n  </div>';e.default=i},function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var o=n(0),r=o.default.ICON;e.errorIconMarkup=function(){var t=r+"--error",e=t+"__line";return'\n    <div class="'+t+'__x-mark">\n      <span class="'+e+" "+e+'--left"></span>\n      <span class="'+e+" "+e+'--right"></span>\n    </div>\n  '},e.warningIconMarkup=function(){var t=r+"--warning";return'\n    <span class="'+t+'__body">\n      <span class="'+t+'__dot"></span>\n    </span>\n  '},e.successIconMarkup=function(){var t=r+"--success";return'\n    <span class="'+t+"__line "+t+'__line--long"></span>\n    <span class="'+t+"__line "+t+'__line--tip"></span>\n\n    <div class="'+t+'__ring"></div>\n    <div class="'+t+'__hide-corners"></div>\n  '}},function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var o=n(0),r=o.default.CONTENT;e.contentMarkup='\n  <div class="'+r+'">\n\n  </div>\n'},function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var o=n(0),r=o.default.BUTTON_CONTAINER,i=o.default.BUTTON,a=o.default.BUTTON_LOADER;e.buttonMarkup='\n  <div class="'+r+'">\n\n    <button\n      class="'+i+'"\n    ></button>\n\n    <div class="'+a+'">\n      <div></div>\n      <div></div>\n      <div></div>\n    </div>\n\n  </div>\n'},function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var o=n(4),r=n(2),i=n(0),a=i.default.ICON,s=i.default.ICON_CUSTOM,c=["error","warning","success","info"],l={error:r.errorIconMarkup(),warning:r.warningIconMarkup(),success:r.successIconMarkup()},u=function(t,e){var n=a+"--"+t;e.classList.add(n);var o=l[t];o&&(e.innerHTML=o)},f=function(t,e){e.classList.add(s);var n=document.createElement("img");n.src=t,e.appendChild(n)},d=function(t){if(t){var e=o.injectElIntoModal(r.iconMarkup);c.includes(t)?u(t,e):f(t,e)}};e.default=d},function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var o=n(2),r=n(4),i=function(t){navigator.userAgent.includes("AppleWebKit")&&(t.style.display="none",t.offsetHeight,t.style.display="")};e.initTitle=function(t){if(t){var e=r.injectElIntoModal(o.titleMarkup);e.textContent=t,i(e)}},e.initText=function(t){if(t){var e=document.createDocumentFragment();t.split("\n").forEach(function(t,n,o){e.appendChild(document.createTextNode(t)),n<o.length-1&&e.appendChild(document.createElement("br"))});var n=r.injectElIntoModal(o.textMarkup);n.appendChild(e),i(n)}}},function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var o=n(1),r=n(4),i=n(0),a=i.default.BUTTON,s=i.default.DANGER_BUTTON,c=n(3),l=n(2),u=n(6),f=n(5),d=function(t,e,n){var r=e.text,i=e.value,d=e.className,p=e.closeModal,m=o.stringToNode(l.buttonMarkup),b=m.querySelector("."+a),v=a+"--"+t;if(b.classList.add(v),d){(Array.isArray(d)?d:d.split(" ")).filter(function(t){return t.length>0}).forEach(function(t){b.classList.add(t)})}n&&t===c.CONFIRM_KEY&&b.classList.add(s),b.textContent=r;var g={};return g[t]=i,f.setActionValue(g),f.setActionOptionsFor(t,{closeModal:p}),b.addEventListener("click",function(){return u.onAction(t)}),m},p=function(t,e){var n=r.injectElIntoModal(l.footerMarkup);for(var o in t){var i=t[o],a=d(o,i,e);i.visible&&n.appendChild(a)}0===n.children.length&&n.remove()};e.default=p},function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var o=n(3),r=n(4),i=n(2),a=n(5),s=n(6),c=n(0),l=c.default.CONTENT,u=function(t){t.addEventListener("input",function(t){var e=t.target,n=e.value;a.setActionValue(n)}),t.addEventListener("keyup",function(t){if("Enter"===t.key)return s.onAction(o.CONFIRM_KEY)}),setTimeout(function(){t.focus(),a.setActionValue("")},0)},f=function(t,e,n){var o=document.createElement(e),r=l+"__"+e;o.classList.add(r);for(var i in n){var a=n[i];o[i]=a}"input"===e&&u(o),t.appendChild(o)},d=function(t){if(t){var e=r.injectElIntoModal(i.contentMarkup),n=t.element,o=t.attributes;"string"==typeof n?f(e,n,o):e.appendChild(n)}};e.default=d},function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var o=n(1),r=n(2),i=function(){var t=o.stringToNode(r.overlayMarkup);document.body.appendChild(t)};e.default=i},function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var o=n(5),r=n(6),i=n(1),a=n(3),s=n(0),c=s.default.MODAL,l=s.default.BUTTON,u=s.default.OVERLAY,f=function(t){t.preventDefault(),v()},d=function(t){t.preventDefault(),g()},p=function(t){if(o.default.isOpen)switch(t.key){case"Escape":return r.onAction(a.CANCEL_KEY)}},m=function(t){if(o.default.isOpen)switch(t.key){case"Tab":return f(t)}},b=function(t){if(o.default.isOpen)return"Tab"===t.key&&t.shiftKey?d(t):void 0},v=function(){var t=i.getNode(l);t&&(t.tabIndex=0,t.focus())},g=function(){var t=i.getNode(c),e=t.querySelectorAll("."+l),n=e.length-1,o=e[n];o&&o.focus()},h=function(t){t[t.length-1].addEventListener("keydown",m)},w=function(t){t[0].addEventListener("keydown",b)},y=function(){var t=i.getNode(c),e=t.querySelectorAll("."+l);e.length&&(h(e),w(e))},x=function(t){if(i.getNode(u)===t.target)return r.onAction(a.CANCEL_KEY)},_=function(t){var e=i.getNode(u);e.removeEventListener("click",x),t&&e.addEventListener("click",x)},k=function(t){o.default.timer&&clearTimeout(o.default.timer),t&&(o.default.timer=window.setTimeout(function(){return r.onAction(a.CANCEL_KEY)},t))},O=function(t){t.closeOnEsc?document.addEventListener("keyup",p):document.removeEventListener("keyup",p),t.dangerMode?v():g(),y(),_(t.closeOnClickOutside),k(t.timer)};e.default=O},function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var o=n(1),r=n(3),i=n(37),a=n(38),s={title:null,text:null,icon:null,buttons:r.defaultButtonList,content:null,className:null,closeOnClickOutside:!0,closeOnEsc:!0,dangerMode:!1,timer:null},c=Object.assign({},s);e.setDefaults=function(t){c=Object.assign({},s,t)};var l=function(t){var e=t&&t.button,n=t&&t.buttons;return void 0!==e&&void 0!==n&&o.throwErr("Cannot set both 'button' and 'buttons' options!"),void 0!==e?{confirm:e}:n},u=function(t){return o.ordinalSuffixOf(t+1)},f=function(t,e){o.throwErr(u(e)+" argument ('"+t+"') is invalid")},d=function(t,e){var n=t+1,r=e[n];o.isPlainObject(r)||void 0===r||o.throwErr("Expected "+u(n)+" argument ('"+r+"') to be a plain object")},p=function(t,e){var n=t+1,r=e[n];void 0!==r&&o.throwErr("Unexpected "+u(n)+" argument ("+r+")")},m=function(t,e,n,r){var i=typeof e,a="string"===i,s=e instanceof Element;if(a){if(0===n)return{text:e};if(1===n)return{text:e,title:r[0]};if(2===n)return d(n,r),{icon:e};f(e,n)}else{if(s&&0===n)return d(n,r),{content:e};if(o.isPlainObject(e))return p(n,r),e;f(e,n)}};e.getOpts=function(){for(var t=[],e=0;e<arguments.length;e++)t[e]=arguments[e];var n={};t.forEach(function(e,o){var r=m(0,e,o,t);Object.assign(n,r)});var o=l(n);n.buttons=r.getButtonListOpts(o),delete n.button,n.content=i.getContentOpts(n.content);var u=Object.assign({},s,c,n);return Object.keys(u).forEach(function(t){a.DEPRECATED_OPTS[t]&&a.logDeprecation(t)}),u}},function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var o=n(1),r={element:"input",attributes:{placeholder:""}};e.getContentOpts=function(t){var e={};return o.isPlainObject(t)?Object.assign(e,t):t instanceof Element?{element:t}:"input"===t?r:null}},function(t,e,n){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.logDeprecation=function(t){var n=e.DEPRECATED_OPTS[t],o=n.onlyRename,r=n.replacement,i=n.subOption,a=n.link,s=o?"renamed":"deprecated",c='SweetAlert warning: "'+t+'" option has been '+s+".";if(r){c+=" Please use"+(i?' "'+i+'" in ':" ")+'"'+r+'" instead.'}var l="https://sweetalert.js.org";c+=a?" More details: "+l+a:" More details: "+l+"/guides/#upgrading-from-1x",console.warn(c)},e.DEPRECATED_OPTS={type:{replacement:"icon",link:"/docs/#icon"},imageUrl:{replacement:"icon",link:"/docs/#icon"},customClass:{replacement:"className",onlyRename:!0,link:"/docs/#classname"},imageSize:{},showCancelButton:{replacement:"buttons",link:"/docs/#buttons"},showConfirmButton:{replacement:"button",link:"/docs/#button"},confirmButtonText:{replacement:"button",link:"/docs/#button"},confirmButtonColor:{},cancelButtonText:{replacement:"buttons",link:"/docs/#buttons"},closeOnConfirm:{replacement:"button",subOption:"closeModal",link:"/docs/#button"},closeOnCancel:{replacement:"buttons",subOption:"closeModal",link:"/docs/#buttons"},showLoaderOnConfirm:{replacement:"buttons"},animation:{},inputType:{replacement:"content",link:"/docs/#content"},inputValue:{replacement:"content",link:"/docs/#content"},inputPlaceholder:{replacement:"content",link:"/docs/#content"},html:{replacement:"content",link:"/docs/#content"},allowEscapeKey:{replacement:"closeOnEsc",onlyRename:!0,link:"/docs/#closeonesc"},allowClickOutside:{replacement:"closeOnClickOutside",onlyRename:!0,link:"/docs/#closeonclickoutside"}}}])});
(function() {

    var laravel = {
        initialize: function() {
            this.registerEvents();
        },

        registerEvents: function() {
            $('body').on('click', 'a[data-method]', this.handleMethod);
        },

        handleMethod: function(e) {
            var link = $(this);
            var httpMethod = link.data('method').toUpperCase();
            var form;

            // If the data-method attribute is not PUT or DELETE,
            // then we don't know what to do. Just ignore.
            if ( $.inArray(httpMethod, ['PUT', 'DELETE']) === - 1 ) {
                return;
            }

            // Allow user to optionally provide data-confirm="Are you sure?"
            if ( link.data('confirm-text') ) {
                laravel.verifyConfirm(link, function (t) {
                    if (! t) return false;

                    form = laravel.createForm(link);
                    form.submit();
                })
            }

            e.preventDefault();
        },

        verifyConfirm: function(link, callback) {
            swal({
                title: link.data('confirm-title'),
                text: link.data('confirm-text'),
                type: 'warning',
                button: link.data('confirm-delete') ? link.data('confirm-delete') : "Yes, delete it!",
            }).then(function (t) {
                callback(t)
            });
        },

        getCsrfToken: function () {
            return $('meta[name="csrf-token"]').attr('content');
        },

        createForm: function(link) {
            var form =
                $('<form>', {
                    'method': 'POST',
                    'action': link.attr('href')
                });

            var token =
                $('<input>', {
                    'name': '_token',
                    'type': 'hidden',
                    'value': laravel.getCsrfToken()
                });

            var hiddenInput =
                $('<input>', {
                    'name': '_method',
                    'type': 'hidden',
                    'value': link.data('method')
                });

            return form.append(token, hiddenInput)
                .appendTo('body');
        }
    };

    laravel.initialize();

})();
/*!
 * JavaScript Cookie v2.1.0
 * https://github.com/js-cookie/js-cookie
 *
 * Copyright 2006, 2015 Klaus Hartl & Fagner Brack
 * Released under the MIT license
 */
(function (factory) {
	if (typeof define === 'function' && define.amd) {
		define(factory);
	} else if (typeof exports === 'object') {
		module.exports = factory();
	} else {
		var _OldCookies = window.Cookies;
		var api = window.Cookies = factory();
		api.noConflict = function () {
			window.Cookies = _OldCookies;
			return api;
		};
	}
}(function () {
	function extend () {
		var i = 0;
		var result = {};
		for (; i < arguments.length; i++) {
			var attributes = arguments[ i ];
			for (var key in attributes) {
				result[key] = attributes[key];
			}
		}
		return result;
	}

	function init (converter) {
		function api (key, value, attributes) {
			var result;

			// Write

			if (arguments.length > 1) {
				attributes = extend({
					path: '/'
				}, api.defaults, attributes);

				if (typeof attributes.expires === 'number') {
					var expires = new Date();
					expires.setMilliseconds(expires.getMilliseconds() + attributes.expires * 864e+5);
					attributes.expires = expires;
				}

				try {
					result = JSON.stringify(value);
					if (/^[\{\[]/.test(result)) {
						value = result;
					}
				} catch (e) {}

				if (!converter.write) {
					value = encodeURIComponent(String(value))
						.replace(/%(23|24|26|2B|3A|3C|3E|3D|2F|3F|40|5B|5D|5E|60|7B|7D|7C)/g, decodeURIComponent);
				} else {
					value = converter.write(value, key);
				}

				key = encodeURIComponent(String(key));
				key = key.replace(/%(23|24|26|2B|5E|60|7C)/g, decodeURIComponent);
				key = key.replace(/[\(\)]/g, escape);

				return (document.cookie = [
					key, '=', value,
					attributes.expires && '; expires=' + attributes.expires.toUTCString(), // use expires attribute, max-age is not supported by IE
					attributes.path    && '; path=' + attributes.path,
					attributes.domain  && '; domain=' + attributes.domain,
					attributes.secure ? '; secure' : ''
				].join(''));
			}

			// Read

			if (!key) {
				result = {};
			}

			// To prevent the for loop in the first place assign an empty array
			// in case there are no cookies at all. Also prevents odd result when
			// calling "get()"
			var cookies = document.cookie ? document.cookie.split('; ') : [];
			var rdecode = /(%[0-9A-Z]{2})+/g;
			var i = 0;

			for (; i < cookies.length; i++) {
				var parts = cookies[i].split('=');
				var name = parts[0].replace(rdecode, decodeURIComponent);
				var cookie = parts.slice(1).join('=');

				if (cookie.charAt(0) === '"') {
					cookie = cookie.slice(1, -1);
				}

				try {
					cookie = converter.read ?
						converter.read(cookie, name) : converter(cookie, name) ||
						cookie.replace(rdecode, decodeURIComponent);

					if (this.json) {
						try {
							cookie = JSON.parse(cookie);
						} catch (e) {}
					}

					if (key === name) {
						result = cookie;
						break;
					}

					if (!key) {
						result[name] = cookie;
					}
				} catch (e) {}
			}

			return result;
		}

		api.get = api.set = api;
		api.getJSON = function () {
			return api.apply({
				json: true
			}, [].slice.call(arguments));
		};
		api.defaults = {};

		api.remove = function (key, attributes) {
			api(key, '', extend(attributes, {
				expires: -1
			}));
		};

		api.withConverter = init;

		return api;
	}

	return init(function () {});
}));

/*!
 * jQuery Validation Plugin v1.14.0
 *
 * http://jqueryvalidation.org/
 *
 * Copyright (c) 2015 Jörn Zaefferer
 * Released under the MIT license
 */
(function( factory ) {
	if ( typeof define === "function" && define.amd ) {
		define( ["jquery"], factory );
	} else {
		factory( jQuery );
	}
}(function( $ ) {

$.extend($.fn, {
	// http://jqueryvalidation.org/validate/
	validate: function( options ) {

		// if nothing is selected, return nothing; can't chain anyway
		if ( !this.length ) {
			if ( options && options.debug && window.console ) {
				console.warn( "Nothing selected, can't validate, returning nothing." );
			}
			return;
		}

		// check if a validator for this form was already created
		var validator = $.data( this[ 0 ], "validator" );
		if ( validator ) {
			return validator;
		}

		// Add novalidate tag if HTML5.
		this.attr( "novalidate", "novalidate" );

		validator = new $.validator( options, this[ 0 ] );
		$.data( this[ 0 ], "validator", validator );

		if ( validator.settings.onsubmit ) {

			this.on( "click.validate", ":submit", function( event ) {
				if ( validator.settings.submitHandler ) {
					validator.submitButton = event.target;
				}

				// allow suppressing validation by adding a cancel class to the submit button
				if ( $( this ).hasClass( "cancel" ) ) {
					validator.cancelSubmit = true;
				}

				// allow suppressing validation by adding the html5 formnovalidate attribute to the submit button
				if ( $( this ).attr( "formnovalidate" ) !== undefined ) {
					validator.cancelSubmit = true;
				}
			});

			// validate the form on submit
			this.on( "submit.validate", function( event ) {
				if ( validator.settings.debug ) {
					// prevent form submit to be able to see console output
					event.preventDefault();
				}
				function handle() {
					var hidden, result;
					if ( validator.settings.submitHandler ) {
						if ( validator.submitButton ) {
							// insert a hidden input as a replacement for the missing submit button
							hidden = $( "<input type='hidden'/>" )
								.attr( "name", validator.submitButton.name )
								.val( $( validator.submitButton ).val() )
								.appendTo( validator.currentForm );
						}
						result = validator.settings.submitHandler.call( validator, validator.currentForm, event );
						if ( validator.submitButton ) {
							// and clean up afterwards; thanks to no-block-scope, hidden can be referenced
							hidden.remove();
						}
						if ( result !== undefined ) {
							return result;
						}
						return false;
					}
					return true;
				}

				// prevent submit for invalid forms or custom submit handlers
				if ( validator.cancelSubmit ) {
					validator.cancelSubmit = false;
					return handle();
				}
				if ( validator.form() ) {
					if ( validator.pendingRequest ) {
						validator.formSubmitted = true;
						return false;
					}
					return handle();
				} else {
					validator.focusInvalid();
					return false;
				}
			});
		}

		return validator;
	},
	// http://jqueryvalidation.org/valid/
	valid: function() {
		var valid, validator, errorList;

		if ( $( this[ 0 ] ).is( "form" ) ) {
			valid = this.validate().form();
		} else {
			errorList = [];
			valid = true;
			validator = $( this[ 0 ].form ).validate();
			this.each( function() {
				valid = validator.element( this ) && valid;
				errorList = errorList.concat( validator.errorList );
			});
			validator.errorList = errorList;
		}
		return valid;
	},

	// http://jqueryvalidation.org/rules/
	rules: function( command, argument ) {
		var element = this[ 0 ],
			settings, staticRules, existingRules, data, param, filtered;

		if ( command ) {
			settings = $.data( element.form, "validator" ).settings;
			staticRules = settings.rules;
			existingRules = $.validator.staticRules( element );
			switch ( command ) {
			case "add":
				$.extend( existingRules, $.validator.normalizeRule( argument ) );
				// remove messages from rules, but allow them to be set separately
				delete existingRules.messages;
				staticRules[ element.name ] = existingRules;
				if ( argument.messages ) {
					settings.messages[ element.name ] = $.extend( settings.messages[ element.name ], argument.messages );
				}
				break;
			case "remove":
				if ( !argument ) {
					delete staticRules[ element.name ];
					return existingRules;
				}
				filtered = {};
				$.each( argument.split( /\s/ ), function( index, method ) {
					filtered[ method ] = existingRules[ method ];
					delete existingRules[ method ];
					if ( method === "required" ) {
						$( element ).removeAttr( "aria-required" );
					}
				});
				return filtered;
			}
		}

		data = $.validator.normalizeRules(
		$.extend(
			{},
			$.validator.classRules( element ),
			$.validator.attributeRules( element ),
			$.validator.dataRules( element ),
			$.validator.staticRules( element )
		), element );

		// make sure required is at front
		if ( data.required ) {
			param = data.required;
			delete data.required;
			data = $.extend( { required: param }, data );
			$( element ).attr( "aria-required", "true" );
		}

		// make sure remote is at back
		if ( data.remote ) {
			param = data.remote;
			delete data.remote;
			data = $.extend( data, { remote: param });
		}

		return data;
	}
});

// Custom selectors
$.extend( $.expr[ ":" ], {
	// http://jqueryvalidation.org/blank-selector/
	blank: function( a ) {
		return !$.trim( "" + $( a ).val() );
	},
	// http://jqueryvalidation.org/filled-selector/
	filled: function( a ) {
		return !!$.trim( "" + $( a ).val() );
	},
	// http://jqueryvalidation.org/unchecked-selector/
	unchecked: function( a ) {
		return !$( a ).prop( "checked" );
	}
});

// constructor for validator
$.validator = function( options, form ) {
	this.settings = $.extend( true, {}, $.validator.defaults, options );
	this.currentForm = form;
	this.init();
};

// http://jqueryvalidation.org/jQuery.validator.format/
$.validator.format = function( source, params ) {
	if ( arguments.length === 1 ) {
		return function() {
			var args = $.makeArray( arguments );
			args.unshift( source );
			return $.validator.format.apply( this, args );
		};
	}
	if ( arguments.length > 2 && params.constructor !== Array  ) {
		params = $.makeArray( arguments ).slice( 1 );
	}
	if ( params.constructor !== Array ) {
		params = [ params ];
	}
	$.each( params, function( i, n ) {
		source = source.replace( new RegExp( "\\{" + i + "\\}", "g" ), function() {
			return n;
		});
	});
	return source;
};

$.extend( $.validator, {

	defaults: {
		messages: {},
		groups: {},
		rules: {},
		errorClass: "error",
		validClass: "valid",
		errorElement: "label",
		focusCleanup: false,
		focusInvalid: true,
		errorContainer: $( [] ),
		errorLabelContainer: $( [] ),
		onsubmit: true,
		ignore: ":hidden",
		ignoreTitle: false,
		onfocusin: function( element ) {
			this.lastActive = element;

			// Hide error label and remove error class on focus if enabled
			if ( this.settings.focusCleanup ) {
				if ( this.settings.unhighlight ) {
					this.settings.unhighlight.call( this, element, this.settings.errorClass, this.settings.validClass );
				}
				this.hideThese( this.errorsFor( element ) );
			}
		},
		onfocusout: function( element ) {
			if ( !this.checkable( element ) && ( element.name in this.submitted || !this.optional( element ) ) ) {
				this.element( element );
			}
		},
		onkeyup: function( element, event ) {
			// Avoid revalidate the field when pressing one of the following keys
			// Shift       => 16
			// Ctrl        => 17
			// Alt         => 18
			// Caps lock   => 20
			// End         => 35
			// Home        => 36
			// Left arrow  => 37
			// Up arrow    => 38
			// Right arrow => 39
			// Down arrow  => 40
			// Insert      => 45
			// Num lock    => 144
			// AltGr key   => 225
			var excludedKeys = [
				16, 17, 18, 20, 35, 36, 37,
				38, 39, 40, 45, 144, 225
			];

			if ( event.which === 9 && this.elementValue( element ) === "" || $.inArray( event.keyCode, excludedKeys ) !== -1 ) {
				return;
			} else if ( element.name in this.submitted || element === this.lastElement ) {
				this.element( element );
			}
		},
		onclick: function( element ) {
			// click on selects, radiobuttons and checkboxes
			if ( element.name in this.submitted ) {
				this.element( element );

			// or option elements, check parent select in that case
			} else if ( element.parentNode.name in this.submitted ) {
				this.element( element.parentNode );
			}
		},
		highlight: function( element, errorClass, validClass ) {
			if ( element.type === "radio" ) {
				this.findByName( element.name ).addClass( errorClass ).removeClass( validClass );
			} else {
				$( element ).addClass( errorClass ).removeClass( validClass );
			}
		},
		unhighlight: function( element, errorClass, validClass ) {
			if ( element.type === "radio" ) {
				this.findByName( element.name ).removeClass( errorClass ).addClass( validClass );
			} else {
				$( element ).removeClass( errorClass ).addClass( validClass );
			}
		}
	},

	// http://jqueryvalidation.org/jQuery.validator.setDefaults/
	setDefaults: function( settings ) {
		$.extend( $.validator.defaults, settings );
	},

	messages: {
		required: "This field is required.",
		remote: "Please fix this field.",
		email: "Please enter a valid email address.",
		url: "Please enter a valid URL.",
		date: "Please enter a valid date.",
		dateISO: "Please enter a valid date ( ISO ).",
		number: "Please enter a valid number.",
		digits: "Please enter only digits.",
		creditcard: "Please enter a valid credit card number.",
		equalTo: "Please enter the same value again.",
		maxlength: $.validator.format( "Please enter no more than {0} characters." ),
		minlength: $.validator.format( "Please enter at least {0} characters." ),
		rangelength: $.validator.format( "Please enter a value between {0} and {1} characters long." ),
		range: $.validator.format( "Please enter a value between {0} and {1}." ),
		max: $.validator.format( "Please enter a value less than or equal to {0}." ),
		min: $.validator.format( "Please enter a value greater than or equal to {0}." )
	},

	autoCreateRanges: false,

	prototype: {

		init: function() {
			this.labelContainer = $( this.settings.errorLabelContainer );
			this.errorContext = this.labelContainer.length && this.labelContainer || $( this.currentForm );
			this.containers = $( this.settings.errorContainer ).add( this.settings.errorLabelContainer );
			this.submitted = {};
			this.valueCache = {};
			this.pendingRequest = 0;
			this.pending = {};
			this.invalid = {};
			this.reset();

			var groups = ( this.groups = {} ),
				rules;
			$.each( this.settings.groups, function( key, value ) {
				if ( typeof value === "string" ) {
					value = value.split( /\s/ );
				}
				$.each( value, function( index, name ) {
					groups[ name ] = key;
				});
			});
			rules = this.settings.rules;
			$.each( rules, function( key, value ) {
				rules[ key ] = $.validator.normalizeRule( value );
			});

			function delegate( event ) {
				var validator = $.data( this.form, "validator" ),
					eventType = "on" + event.type.replace( /^validate/, "" ),
					settings = validator.settings;
				if ( settings[ eventType ] && !$( this ).is( settings.ignore ) ) {
					settings[ eventType ].call( validator, this, event );
				}
			}

			$( this.currentForm )
				.on( "focusin.validate focusout.validate keyup.validate",
					":text, [type='password'], [type='file'], select, textarea, [type='number'], [type='search'], " +
					"[type='tel'], [type='url'], [type='email'], [type='datetime'], [type='date'], [type='month'], " +
					"[type='week'], [type='time'], [type='datetime-local'], [type='range'], [type='color'], " +
					"[type='radio'], [type='checkbox']", delegate)
				// Support: Chrome, oldIE
				// "select" is provided as event.target when clicking a option
				.on("click.validate", "select, option, [type='radio'], [type='checkbox']", delegate);

			if ( this.settings.invalidHandler ) {
				$( this.currentForm ).on( "invalid-form.validate", this.settings.invalidHandler );
			}

			// Add aria-required to any Static/Data/Class required fields before first validation
			// Screen readers require this attribute to be present before the initial submission http://www.w3.org/TR/WCAG-TECHS/ARIA2.html
			$( this.currentForm ).find( "[required], [data-rule-required], .required" ).attr( "aria-required", "true" );
		},

		// http://jqueryvalidation.org/Validator.form/
		form: function() {
			this.checkForm();
			$.extend( this.submitted, this.errorMap );
			this.invalid = $.extend({}, this.errorMap );
			if ( !this.valid() ) {
				$( this.currentForm ).triggerHandler( "invalid-form", [ this ]);
			}
			this.showErrors();
			return this.valid();
		},

		checkForm: function() {
			this.prepareForm();
			for ( var i = 0, elements = ( this.currentElements = this.elements() ); elements[ i ]; i++ ) {
				this.check( elements[ i ] );
			}
			return this.valid();
		},

		// http://jqueryvalidation.org/Validator.element/
		element: function( element ) {
			var cleanElement = this.clean( element ),
				checkElement = this.validationTargetFor( cleanElement ),
				result = true;

			this.lastElement = checkElement;

			if ( checkElement === undefined ) {
				delete this.invalid[ cleanElement.name ];
			} else {
				this.prepareElement( checkElement );
				this.currentElements = $( checkElement );

				result = this.check( checkElement ) !== false;
				if ( result ) {
					delete this.invalid[ checkElement.name ];
				} else {
					this.invalid[ checkElement.name ] = true;
				}
			}
			// Add aria-invalid status for screen readers
			$( element ).attr( "aria-invalid", !result );

			if ( !this.numberOfInvalids() ) {
				// Hide error containers on last error
				this.toHide = this.toHide.add( this.containers );
			}
			this.showErrors();
			return result;
		},

		// http://jqueryvalidation.org/Validator.showErrors/
		showErrors: function( errors ) {
			if ( errors ) {
				// add items to error list and map
				$.extend( this.errorMap, errors );
				this.errorList = [];
				for ( var name in errors ) {
					this.errorList.push({
						message: errors[ name ],
						element: this.findByName( name )[ 0 ]
					});
				}
				// remove items from success list
				this.successList = $.grep( this.successList, function( element ) {
					return !( element.name in errors );
				});
			}
			if ( this.settings.showErrors ) {
				this.settings.showErrors.call( this, this.errorMap, this.errorList );
			} else {
				this.defaultShowErrors();
			}
		},

		// http://jqueryvalidation.org/Validator.resetForm/
		resetForm: function() {
			if ( $.fn.resetForm ) {
				$( this.currentForm ).resetForm();
			}
			this.submitted = {};
			this.lastElement = null;
			this.prepareForm();
			this.hideErrors();
			var i, elements = this.elements()
				.removeData( "previousValue" )
				.removeAttr( "aria-invalid" );

			if ( this.settings.unhighlight ) {
				for ( i = 0; elements[ i ]; i++ ) {
					this.settings.unhighlight.call( this, elements[ i ],
						this.settings.errorClass, "" );
				}
			} else {
				elements.removeClass( this.settings.errorClass );
			}
		},

		numberOfInvalids: function() {
			return this.objectLength( this.invalid );
		},

		objectLength: function( obj ) {
			/* jshint unused: false */
			var count = 0,
				i;
			for ( i in obj ) {
				count++;
			}
			return count;
		},

		hideErrors: function() {
			this.hideThese( this.toHide );
		},

		hideThese: function( errors ) {
			errors.not( this.containers ).text( "" );
			this.addWrapper( errors ).hide();
		},

		valid: function() {
			return this.size() === 0;
		},

		size: function() {
			return this.errorList.length;
		},

		focusInvalid: function() {
			if ( this.settings.focusInvalid ) {
				try {
					$( this.findLastActive() || this.errorList.length && this.errorList[ 0 ].element || [])
					.filter( ":visible" )
					.focus()
					// manually trigger focusin event; without it, focusin handler isn't called, findLastActive won't have anything to find
					.trigger( "focusin" );
				} catch ( e ) {
					// ignore IE throwing errors when focusing hidden elements
				}
			}
		},

		findLastActive: function() {
			var lastActive = this.lastActive;
			return lastActive && $.grep( this.errorList, function( n ) {
				return n.element.name === lastActive.name;
			}).length === 1 && lastActive;
		},

		elements: function() {
			var validator = this,
				rulesCache = {};

			// select all valid inputs inside the form (no submit or reset buttons)
			return $( this.currentForm )
			.find( "input, select, textarea" )
			.not( ":submit, :reset, :image, :disabled" )
			.not( this.settings.ignore )
			.filter( function() {
				if ( !this.name && validator.settings.debug && window.console ) {
					console.error( "%o has no name assigned", this );
				}

				// select only the first element for each name, and only those with rules specified
				if ( this.name in rulesCache || !validator.objectLength( $( this ).rules() ) ) {
					return false;
				}

				rulesCache[ this.name ] = true;
				return true;
			});
		},

		clean: function( selector ) {
			return $( selector )[ 0 ];
		},

		errors: function() {
			var errorClass = this.settings.errorClass.split( " " ).join( "." );
			return $( this.settings.errorElement + "." + errorClass, this.errorContext );
		},

		reset: function() {
			this.successList = [];
			this.errorList = [];
			this.errorMap = {};
			this.toShow = $( [] );
			this.toHide = $( [] );
			this.currentElements = $( [] );
		},

		prepareForm: function() {
			this.reset();
			this.toHide = this.errors().add( this.containers );
		},

		prepareElement: function( element ) {
			this.reset();
			this.toHide = this.errorsFor( element );
		},

		elementValue: function( element ) {
			var val,
				$element = $( element ),
				type = element.type;

			if ( type === "radio" || type === "checkbox" ) {
				return this.findByName( element.name ).filter(":checked").val();
			} else if ( type === "number" && typeof element.validity !== "undefined" ) {
				return element.validity.badInput ? false : $element.val();
			}

			val = $element.val();
			if ( typeof val === "string" ) {
				return val.replace(/\r/g, "" );
			}
			return val;
		},

		check: function( element ) {
			element = this.validationTargetFor( this.clean( element ) );

			var rules = $( element ).rules(),
				rulesCount = $.map( rules, function( n, i ) {
					return i;
				}).length,
				dependencyMismatch = false,
				val = this.elementValue( element ),
				result, method, rule;

			for ( method in rules ) {
				rule = { method: method, parameters: rules[ method ] };
				try {

					result = $.validator.methods[ method ].call( this, val, element, rule.parameters );

					// if a method indicates that the field is optional and therefore valid,
					// don't mark it as valid when there are no other rules
					if ( result === "dependency-mismatch" && rulesCount === 1 ) {
						dependencyMismatch = true;
						continue;
					}
					dependencyMismatch = false;

					if ( result === "pending" ) {
						this.toHide = this.toHide.not( this.errorsFor( element ) );
						return;
					}

					if ( !result ) {
						this.formatAndAdd( element, rule );
						return false;
					}
				} catch ( e ) {
					if ( this.settings.debug && window.console ) {
						console.log( "Exception occurred when checking element " + element.id + ", check the '" + rule.method + "' method.", e );
					}
					if ( e instanceof TypeError ) {
						e.message += ".  Exception occurred when checking element " + element.id + ", check the '" + rule.method + "' method.";
					}

					throw e;
				}
			}
			if ( dependencyMismatch ) {
				return;
			}
			if ( this.objectLength( rules ) ) {
				this.successList.push( element );
			}
			return true;
		},

		// return the custom message for the given element and validation method
		// specified in the element's HTML5 data attribute
		// return the generic message if present and no method specific message is present
		customDataMessage: function( element, method ) {
			return $( element ).data( "msg" + method.charAt( 0 ).toUpperCase() +
				method.substring( 1 ).toLowerCase() ) || $( element ).data( "msg" );
		},

		// return the custom message for the given element name and validation method
		customMessage: function( name, method ) {
			var m = this.settings.messages[ name ];
			return m && ( m.constructor === String ? m : m[ method ]);
		},

		// return the first defined argument, allowing empty strings
		findDefined: function() {
			for ( var i = 0; i < arguments.length; i++) {
				if ( arguments[ i ] !== undefined ) {
					return arguments[ i ];
				}
			}
			return undefined;
		},

		defaultMessage: function( element, method ) {
			return this.findDefined(
				this.customMessage( element.name, method ),
				this.customDataMessage( element, method ),
				// title is never undefined, so handle empty string as undefined
				!this.settings.ignoreTitle && element.title || undefined,
				$.validator.messages[ method ],
				"<strong>Warning: No message defined for " + element.name + "</strong>"
			);
		},

		formatAndAdd: function( element, rule ) {
			var message = this.defaultMessage( element, rule.method ),
				theregex = /\$?\{(\d+)\}/g;
			if ( typeof message === "function" ) {
				message = message.call( this, rule.parameters, element );
			} else if ( theregex.test( message ) ) {
				message = $.validator.format( message.replace( theregex, "{$1}" ), rule.parameters );
			}
			this.errorList.push({
				message: message,
				element: element,
				method: rule.method
			});

			this.errorMap[ element.name ] = message;
			this.submitted[ element.name ] = message;
		},

		addWrapper: function( toToggle ) {
			if ( this.settings.wrapper ) {
				toToggle = toToggle.add( toToggle.parent( this.settings.wrapper ) );
			}
			return toToggle;
		},

		defaultShowErrors: function() {
			var i, elements, error;
			for ( i = 0; this.errorList[ i ]; i++ ) {
				error = this.errorList[ i ];
				if ( this.settings.highlight ) {
					this.settings.highlight.call( this, error.element, this.settings.errorClass, this.settings.validClass );
				}
				this.showLabel( error.element, error.message );
			}
			if ( this.errorList.length ) {
				this.toShow = this.toShow.add( this.containers );
			}
			if ( this.settings.success ) {
				for ( i = 0; this.successList[ i ]; i++ ) {
					this.showLabel( this.successList[ i ] );
				}
			}
			if ( this.settings.unhighlight ) {
				for ( i = 0, elements = this.validElements(); elements[ i ]; i++ ) {
					this.settings.unhighlight.call( this, elements[ i ], this.settings.errorClass, this.settings.validClass );
				}
			}
			this.toHide = this.toHide.not( this.toShow );
			this.hideErrors();
			this.addWrapper( this.toShow ).show();
		},

		validElements: function() {
			return this.currentElements.not( this.invalidElements() );
		},

		invalidElements: function() {
			return $( this.errorList ).map(function() {
				return this.element;
			});
		},

		showLabel: function( element, message ) {
			var place, group, errorID,
				error = this.errorsFor( element ),
				elementID = this.idOrName( element ),
				describedBy = $( element ).attr( "aria-describedby" );
			if ( error.length ) {
				// refresh error/success class
				error.removeClass( this.settings.validClass ).addClass( this.settings.errorClass );
				// replace message on existing label
				error.html( message );
			} else {
				// create error element
				error = $( "<" + this.settings.errorElement + ">" )
					.attr( "id", elementID + "-error" )
					.addClass( this.settings.errorClass )
					.html( message || "" );

				// Maintain reference to the element to be placed into the DOM
				place = error;
				if ( this.settings.wrapper ) {
					// make sure the element is visible, even in IE
					// actually showing the wrapped element is handled elsewhere
					place = error.hide().show().wrap( "<" + this.settings.wrapper + "/>" ).parent();
				}
				if ( this.labelContainer.length ) {
					this.labelContainer.append( place );
				} else if ( this.settings.errorPlacement ) {
					this.settings.errorPlacement( place, $( element ) );
				} else {
					place.insertAfter( element );
				}

				// Link error back to the element
				if ( error.is( "label" ) ) {
					// If the error is a label, then associate using 'for'
					error.attr( "for", elementID );
				} else if ( error.parents( "label[for='" + elementID + "']" ).length === 0 ) {
					// If the element is not a child of an associated label, then it's necessary
					// to explicitly apply aria-describedby

					errorID = error.attr( "id" ).replace( /(:|\.|\[|\]|\$)/g, "\\$1");
					// Respect existing non-error aria-describedby
					if ( !describedBy ) {
						describedBy = errorID;
					} else if ( !describedBy.match( new RegExp( "\\b" + errorID + "\\b" ) ) ) {
						// Add to end of list if not already present
						describedBy += " " + errorID;
					}
					$( element ).attr( "aria-describedby", describedBy );

					// If this element is grouped, then assign to all elements in the same group
					group = this.groups[ element.name ];
					if ( group ) {
						$.each( this.groups, function( name, testgroup ) {
							if ( testgroup === group ) {
								$( "[name='" + name + "']", this.currentForm )
									.attr( "aria-describedby", error.attr( "id" ) );
							}
						});
					}
				}
			}
			if ( !message && this.settings.success ) {
				error.text( "" );
				if ( typeof this.settings.success === "string" ) {
					error.addClass( this.settings.success );
				} else {
					this.settings.success( error, element );
				}
			}
			this.toShow = this.toShow.add( error );
		},

		errorsFor: function( element ) {
			var name = this.idOrName( element ),
				describer = $( element ).attr( "aria-describedby" ),
				selector = "label[for='" + name + "'], label[for='" + name + "'] *";

			// aria-describedby should directly reference the error element
			if ( describer ) {
				selector = selector + ", #" + describer.replace( /\s+/g, ", #" );
			}
			return this
				.errors()
				.filter( selector );
		},

		idOrName: function( element ) {
			return this.groups[ element.name ] || ( this.checkable( element ) ? element.name : element.id || element.name );
		},

		validationTargetFor: function( element ) {

			// If radio/checkbox, validate first element in group instead
			if ( this.checkable( element ) ) {
				element = this.findByName( element.name );
			}

			// Always apply ignore filter
			return $( element ).not( this.settings.ignore )[ 0 ];
		},

		checkable: function( element ) {
			return ( /radio|checkbox/i ).test( element.type );
		},

		findByName: function( name ) {
			return $( this.currentForm ).find( "[name='" + name + "']" );
		},

		getLength: function( value, element ) {
			switch ( element.nodeName.toLowerCase() ) {
			case "select":
				return $( "option:selected", element ).length;
			case "input":
				if ( this.checkable( element ) ) {
					return this.findByName( element.name ).filter( ":checked" ).length;
				}
			}
			return value.length;
		},

		depend: function( param, element ) {
			return this.dependTypes[typeof param] ? this.dependTypes[typeof param]( param, element ) : true;
		},

		dependTypes: {
			"boolean": function( param ) {
				return param;
			},
			"string": function( param, element ) {
				return !!$( param, element.form ).length;
			},
			"function": function( param, element ) {
				return param( element );
			}
		},

		optional: function( element ) {
			var val = this.elementValue( element );
			return !$.validator.methods.required.call( this, val, element ) && "dependency-mismatch";
		},

		startRequest: function( element ) {
			if ( !this.pending[ element.name ] ) {
				this.pendingRequest++;
				this.pending[ element.name ] = true;
			}
		},

		stopRequest: function( element, valid ) {
			this.pendingRequest--;
			// sometimes synchronization fails, make sure pendingRequest is never < 0
			if ( this.pendingRequest < 0 ) {
				this.pendingRequest = 0;
			}
			delete this.pending[ element.name ];
			if ( valid && this.pendingRequest === 0 && this.formSubmitted && this.form() ) {
				$( this.currentForm ).submit();
				this.formSubmitted = false;
			} else if (!valid && this.pendingRequest === 0 && this.formSubmitted ) {
				$( this.currentForm ).triggerHandler( "invalid-form", [ this ]);
				this.formSubmitted = false;
			}
		},

		previousValue: function( element ) {
			return $.data( element, "previousValue" ) || $.data( element, "previousValue", {
				old: null,
				valid: true,
				message: this.defaultMessage( element, "remote" )
			});
		},

		// cleans up all forms and elements, removes validator-specific events
		destroy: function() {
			this.resetForm();

			$( this.currentForm )
				.off( ".validate" )
				.removeData( "validator" );
		}

	},

	classRuleSettings: {
		required: { required: true },
		email: { email: true },
		url: { url: true },
		date: { date: true },
		dateISO: { dateISO: true },
		number: { number: true },
		digits: { digits: true },
		creditcard: { creditcard: true }
	},

	addClassRules: function( className, rules ) {
		if ( className.constructor === String ) {
			this.classRuleSettings[ className ] = rules;
		} else {
			$.extend( this.classRuleSettings, className );
		}
	},

	classRules: function( element ) {
		var rules = {},
			classes = $( element ).attr( "class" );

		if ( classes ) {
			$.each( classes.split( " " ), function() {
				if ( this in $.validator.classRuleSettings ) {
					$.extend( rules, $.validator.classRuleSettings[ this ]);
				}
			});
		}
		return rules;
	},

	normalizeAttributeRule: function( rules, type, method, value ) {

		// convert the value to a number for number inputs, and for text for backwards compability
		// allows type="date" and others to be compared as strings
		if ( /min|max/.test( method ) && ( type === null || /number|range|text/.test( type ) ) ) {
			value = Number( value );

			// Support Opera Mini, which returns NaN for undefined minlength
			if ( isNaN( value ) ) {
				value = undefined;
			}
		}

		if ( value || value === 0 ) {
			rules[ method ] = value;
		} else if ( type === method && type !== "range" ) {

			// exception: the jquery validate 'range' method
			// does not test for the html5 'range' type
			rules[ method ] = true;
		}
	},

	attributeRules: function( element ) {
		var rules = {},
			$element = $( element ),
			type = element.getAttribute( "type" ),
			method, value;

		for ( method in $.validator.methods ) {

			// support for <input required> in both html5 and older browsers
			if ( method === "required" ) {
				value = element.getAttribute( method );

				// Some browsers return an empty string for the required attribute
				// and non-HTML5 browsers might have required="" markup
				if ( value === "" ) {
					value = true;
				}

				// force non-HTML5 browsers to return bool
				value = !!value;
			} else {
				value = $element.attr( method );
			}

			this.normalizeAttributeRule( rules, type, method, value );
		}

		// maxlength may be returned as -1, 2147483647 ( IE ) and 524288 ( safari ) for text inputs
		if ( rules.maxlength && /-1|2147483647|524288/.test( rules.maxlength ) ) {
			delete rules.maxlength;
		}

		return rules;
	},

	dataRules: function( element ) {
		var rules = {},
			$element = $( element ),
			type = element.getAttribute( "type" ),
			method, value;

		for ( method in $.validator.methods ) {
			value = $element.data( "rule" + method.charAt( 0 ).toUpperCase() + method.substring( 1 ).toLowerCase() );
			this.normalizeAttributeRule( rules, type, method, value );
		}
		return rules;
	},

	staticRules: function( element ) {
		var rules = {},
			validator = $.data( element.form, "validator" );

		if ( validator.settings.rules ) {
			rules = $.validator.normalizeRule( validator.settings.rules[ element.name ] ) || {};
		}
		return rules;
	},

	normalizeRules: function( rules, element ) {
		// handle dependency check
		$.each( rules, function( prop, val ) {
			// ignore rule when param is explicitly false, eg. required:false
			if ( val === false ) {
				delete rules[ prop ];
				return;
			}
			if ( val.param || val.depends ) {
				var keepRule = true;
				switch ( typeof val.depends ) {
				case "string":
					keepRule = !!$( val.depends, element.form ).length;
					break;
				case "function":
					keepRule = val.depends.call( element, element );
					break;
				}
				if ( keepRule ) {
					rules[ prop ] = val.param !== undefined ? val.param : true;
				} else {
					delete rules[ prop ];
				}
			}
		});

		// evaluate parameters
		$.each( rules, function( rule, parameter ) {
			rules[ rule ] = $.isFunction( parameter ) ? parameter( element ) : parameter;
		});

		// clean number parameters
		$.each([ "minlength", "maxlength" ], function() {
			if ( rules[ this ] ) {
				rules[ this ] = Number( rules[ this ] );
			}
		});
		$.each([ "rangelength", "range" ], function() {
			var parts;
			if ( rules[ this ] ) {
				if ( $.isArray( rules[ this ] ) ) {
					rules[ this ] = [ Number( rules[ this ][ 0 ]), Number( rules[ this ][ 1 ] ) ];
				} else if ( typeof rules[ this ] === "string" ) {
					parts = rules[ this ].replace(/[\[\]]/g, "" ).split( /[\s,]+/ );
					rules[ this ] = [ Number( parts[ 0 ]), Number( parts[ 1 ] ) ];
				}
			}
		});

		if ( $.validator.autoCreateRanges ) {
			// auto-create ranges
			if ( rules.min != null && rules.max != null ) {
				rules.range = [ rules.min, rules.max ];
				delete rules.min;
				delete rules.max;
			}
			if ( rules.minlength != null && rules.maxlength != null ) {
				rules.rangelength = [ rules.minlength, rules.maxlength ];
				delete rules.minlength;
				delete rules.maxlength;
			}
		}

		return rules;
	},

	// Converts a simple string to a {string: true} rule, e.g., "required" to {required:true}
	normalizeRule: function( data ) {
		if ( typeof data === "string" ) {
			var transformed = {};
			$.each( data.split( /\s/ ), function() {
				transformed[ this ] = true;
			});
			data = transformed;
		}
		return data;
	},

	// http://jqueryvalidation.org/jQuery.validator.addMethod/
	addMethod: function( name, method, message ) {
		$.validator.methods[ name ] = method;
		$.validator.messages[ name ] = message !== undefined ? message : $.validator.messages[ name ];
		if ( method.length < 3 ) {
			$.validator.addClassRules( name, $.validator.normalizeRule( name ) );
		}
	},

	methods: {

		// http://jqueryvalidation.org/required-method/
		required: function( value, element, param ) {
			// check if dependency is met
			if ( !this.depend( param, element ) ) {
				return "dependency-mismatch";
			}
			if ( element.nodeName.toLowerCase() === "select" ) {
				// could be an array for select-multiple or a string, both are fine this way
				var val = $( element ).val();
				return val && val.length > 0;
			}
			if ( this.checkable( element ) ) {
				return this.getLength( value, element ) > 0;
			}
			return value.length > 0;
		},

		// http://jqueryvalidation.org/email-method/
		email: function( value, element ) {
			// From https://html.spec.whatwg.org/multipage/forms.html#valid-e-mail-address
			// Retrieved 2014-01-14
			// If you have a problem with this implementation, report a bug against the above spec
			// Or use custom methods to implement your own email validation
			return this.optional( element ) || /^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/.test( value );
		},

		// http://jqueryvalidation.org/url-method/
		url: function( value, element ) {

			// Copyright (c) 2010-2013 Diego Perini, MIT licensed
			// https://gist.github.com/dperini/729294
			// see also https://mathiasbynens.be/demo/url-regex
			// modified to allow protocol-relative URLs
			return this.optional( element ) || /^(?:(?:(?:https?|ftp):)?\/\/)(?:\S+(?::\S*)?@)?(?:(?!(?:10|127)(?:\.\d{1,3}){3})(?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})).?)(?::\d{2,5})?(?:[/?#]\S*)?$/i.test( value );
		},

		// http://jqueryvalidation.org/date-method/
		date: function( value, element ) {
			return this.optional( element ) || !/Invalid|NaN/.test( new Date( value ).toString() );
		},

		// http://jqueryvalidation.org/dateISO-method/
		dateISO: function( value, element ) {
			return this.optional( element ) || /^\d{4}[\/\-](0?[1-9]|1[012])[\/\-](0?[1-9]|[12][0-9]|3[01])$/.test( value );
		},

		// http://jqueryvalidation.org/number-method/
		number: function( value, element ) {
			return this.optional( element ) || /^(?:-?\d+|-?\d{1,3}(?:,\d{3})+)?(?:\.\d+)?$/.test( value );
		},

		// http://jqueryvalidation.org/digits-method/
		digits: function( value, element ) {
			return this.optional( element ) || /^\d+$/.test( value );
		},

		// http://jqueryvalidation.org/creditcard-method/
		// based on http://en.wikipedia.org/wiki/Luhn_algorithm
		creditcard: function( value, element ) {
			if ( this.optional( element ) ) {
				return "dependency-mismatch";
			}
			// accept only spaces, digits and dashes
			if ( /[^0-9 \-]+/.test( value ) ) {
				return false;
			}
			var nCheck = 0,
				nDigit = 0,
				bEven = false,
				n, cDigit;

			value = value.replace( /\D/g, "" );

			// Basing min and max length on
			// http://developer.ean.com/general_info/Valid_Credit_Card_Types
			if ( value.length < 13 || value.length > 19 ) {
				return false;
			}

			for ( n = value.length - 1; n >= 0; n--) {
				cDigit = value.charAt( n );
				nDigit = parseInt( cDigit, 10 );
				if ( bEven ) {
					if ( ( nDigit *= 2 ) > 9 ) {
						nDigit -= 9;
					}
				}
				nCheck += nDigit;
				bEven = !bEven;
			}

			return ( nCheck % 10 ) === 0;
		},

		// http://jqueryvalidation.org/minlength-method/
		minlength: function( value, element, param ) {
			var length = $.isArray( value ) ? value.length : this.getLength( value, element );
			return this.optional( element ) || length >= param;
		},

		// http://jqueryvalidation.org/maxlength-method/
		maxlength: function( value, element, param ) {
			var length = $.isArray( value ) ? value.length : this.getLength( value, element );
			return this.optional( element ) || length <= param;
		},

		// http://jqueryvalidation.org/rangelength-method/
		rangelength: function( value, element, param ) {
			var length = $.isArray( value ) ? value.length : this.getLength( value, element );
			return this.optional( element ) || ( length >= param[ 0 ] && length <= param[ 1 ] );
		},

		// http://jqueryvalidation.org/min-method/
		min: function( value, element, param ) {
			return this.optional( element ) || value >= param;
		},

		// http://jqueryvalidation.org/max-method/
		max: function( value, element, param ) {
			return this.optional( element ) || value <= param;
		},

		// http://jqueryvalidation.org/range-method/
		range: function( value, element, param ) {
			return this.optional( element ) || ( value >= param[ 0 ] && value <= param[ 1 ] );
		},

		// http://jqueryvalidation.org/equalTo-method/
		equalTo: function( value, element, param ) {
			// bind to the blur event of the target in order to revalidate whenever the target field is updated
			// TODO find a way to bind the event just once, avoiding the unbind-rebind overhead
			var target = $( param );
			if ( this.settings.onfocusout ) {
				target.off( ".validate-equalTo" ).on( "blur.validate-equalTo", function() {
					$( element ).valid();
				});
			}
			return value === target.val();
		},

		// http://jqueryvalidation.org/remote-method/
		remote: function( value, element, param ) {
			if ( this.optional( element ) ) {
				return "dependency-mismatch";
			}

			var previous = this.previousValue( element ),
				validator, data;

			if (!this.settings.messages[ element.name ] ) {
				this.settings.messages[ element.name ] = {};
			}
			previous.originalMessage = this.settings.messages[ element.name ].remote;
			this.settings.messages[ element.name ].remote = previous.message;

			param = typeof param === "string" && { url: param } || param;

			if ( previous.old === value ) {
				return previous.valid;
			}

			previous.old = value;
			validator = this;
			this.startRequest( element );
			data = {};
			data[ element.name ] = value;
			$.ajax( $.extend( true, {
				mode: "abort",
				port: "validate" + element.name,
				dataType: "json",
				data: data,
				context: validator.currentForm,
				success: function( response ) {
					var valid = response === true || response === "true",
						errors, message, submitted;

					validator.settings.messages[ element.name ].remote = previous.originalMessage;
					if ( valid ) {
						submitted = validator.formSubmitted;
						validator.prepareElement( element );
						validator.formSubmitted = submitted;
						validator.successList.push( element );
						delete validator.invalid[ element.name ];
						validator.showErrors();
					} else {
						errors = {};
						message = response || validator.defaultMessage( element, "remote" );
						errors[ element.name ] = previous.message = $.isFunction( message ) ? message( value ) : message;
						validator.invalid[ element.name ] = true;
						validator.showErrors( errors );
					}
					previous.valid = valid;
					validator.stopRequest( element, valid );
				}
			}, param ) );
			return "pending";
		}
	}

});

// ajax mode: abort
// usage: $.ajax({ mode: "abort"[, port: "uniqueport"]});
// if mode:"abort" is used, the previous request on that port (port can be undefined) is aborted via XMLHttpRequest.abort()

var pendingRequests = {},
	ajax;
// Use a prefilter if available (1.5+)
if ( $.ajaxPrefilter ) {
	$.ajaxPrefilter(function( settings, _, xhr ) {
		var port = settings.port;
		if ( settings.mode === "abort" ) {
			if ( pendingRequests[port] ) {
				pendingRequests[port].abort();
			}
			pendingRequests[port] = xhr;
		}
	});
} else {
	// Proxy ajax
	ajax = $.ajax;
	$.ajax = function( settings ) {
		var mode = ( "mode" in settings ? settings : $.ajaxSettings ).mode,
			port = ( "port" in settings ? settings : $.ajaxSettings ).port;
		if ( mode === "abort" ) {
			if ( pendingRequests[port] ) {
				pendingRequests[port].abort();
			}
			pendingRequests[port] = ajax.apply(this, arguments);
			return pendingRequests[port];
		}
		return ajax.apply(this, arguments);
	};
}

}));
function strlen (string) {
  //  discuss at: http://phpjs.org/functions/strlen/
  // original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // improved by: Sakimori
  // improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  //    input by: Kirk Strobeck
  // bugfixed by: Onno Marsman
  //  revised by: Brett Zamir (http://brett-zamir.me)
  //        note: May look like overkill, but in order to be truly faithful to handling all Unicode
  //        note: characters and to this function in PHP which does not count the number of bytes
  //        note: but counts the number of characters, something like this is really necessary.
  //   example 1: strlen('Kevin van Zonneveld');
  //   returns 1: 19
  //   example 2: ini_set('unicode.semantics', 'on');
  //   example 2: strlen('A\ud87e\udc04Z');
  //   returns 2: 3

  var str = string + ''
  var i = 0,
    chr = '',
    lgth = 0

  if (!this.php_js || !this.php_js.ini || !this.php_js.ini['unicode.semantics'] || this.php_js.ini[
      'unicode.semantics'].local_value.toLowerCase() !== 'on') {
    return string.length
  }

  var getWholeChar = function (str, i) {
    var code = str.charCodeAt(i)
    var next = '',
      prev = ''
    if (0xD800 <= code && code <= 0xDBFF) {
      // High surrogate (could change last hex to 0xDB7F to treat high private surrogates as single characters)
      if (str.length <= (i + 1)) {
        throw 'High surrogate without following low surrogate'
      }
      next = str.charCodeAt(i + 1)
      if (0xDC00 > next || next > 0xDFFF) {
        throw 'High surrogate without following low surrogate'
      }
      return str.charAt(i) + str.charAt(i + 1)
    } else if (0xDC00 <= code && code <= 0xDFFF) {
      // Low surrogate
      if (i === 0) {
        throw 'Low surrogate without preceding high surrogate'
      }
      prev = str.charCodeAt(i - 1)
      if (0xD800 > prev || prev > 0xDBFF) {
        // (could change last hex to 0xDB7F to treat high private surrogates as single characters)
        throw 'Low surrogate without preceding high surrogate'
      }
      // We can pass over low surrogates now as the second component in a pair which we have already processed
      return false
    }
    return str.charAt(i)
  }

  for (i = 0, lgth = 0; i < str.length; i++) {
    if ((chr = getWholeChar(str, i)) === false) {
      continue
    } // Adapt this line at the top of any loop, passing in the whole string and the current iteration and returning a variable to represent the individual character; purpose is to treat the first part of a surrogate pair as the whole character and then ignore the second part
    lgth++
  }
  return lgth
}

function array_diff (arr1) {
  //  discuss at: http://phpjs.org/functions/array_diff/
  // original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // improved by: Sanjoy Roy
  //  revised by: Brett Zamir (http://brett-zamir.me)
  //   example 1: array_diff(['Kevin', 'van', 'Zonneveld'], ['van', 'Zonneveld']);
  //   returns 1: {0:'Kevin'}

  var retArr = {},
    argl = arguments.length,
    k1 = '',
    i = 1,
    k = '',
    arr = {}

  arr1keys: for (k1 in arr1) {
    for (i = 1; i < argl; i++) {
      arr = arguments[i]
      for (k in arr) {
        if (arr[k] === arr1[k1]) {
          // If it reaches here, it was found in at least one array, so try next value
          continue arr1keys
        }
      }
      retArr[k1] = arr1[k1]
    }
  }

  return retArr
}

function strtotime (text, now) {
  //  discuss at: http://phpjs.org/functions/strtotime/
  //     version: 1109.2016
  // original by: Caio Ariede (http://caioariede.com)
  // improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // improved by: Caio Ariede (http://caioariede.com)
  // improved by: A. Matías Quezada (http://amatiasq.com)
  // improved by: preuter
  // improved by: Brett Zamir (http://brett-zamir.me)
  // improved by: Mirko Faber
  //    input by: David
  // bugfixed by: Wagner B. Soares
  // bugfixed by: Artur Tchernychev
  // bugfixed by: Stephan Bösch-Plepelits (http://github.com/plepe)
  //        note: Examples all have a fixed timestamp to prevent tests to fail because of variable time(zones)
  //   example 1: strtotime('+1 day', 1129633200);
  //   returns 1: 1129719600
  //   example 2: strtotime('+1 week 2 days 4 hours 2 seconds', 1129633200);
  //   returns 2: 1130425202
  //   example 3: strtotime('last month', 1129633200);
  //   returns 3: 1127041200
  //   example 4: strtotime('2009-05-04 08:30:00 GMT');
  //   returns 4: 1241425800
  //   example 5: strtotime('2009-05-04 08:30:00+00');
  //   returns 5: 1241425800
  //   example 6: strtotime('2009-05-04 08:30:00+02:00');
  //   returns 6: 1241418600
  //   example 7: strtotime('2009-05-04T08:30:00Z');
  //   returns 7: 1241425800

  var parsed, match, today, year, date, days, ranges, len, times, regex, i, fail = false

  if (!text) {
    return fail
  }

  // Unecessary spaces
  text = text.replace(/^\s+|\s+$/g, '')
    .replace(/\s{2,}/g, ' ')
    .replace(/[\t\r\n]/g, '')
    .toLowerCase()

  // in contrast to php, js Date.parse function interprets:
  // dates given as yyyy-mm-dd as in timezone: UTC,
  // dates with "." or "-" as MDY instead of DMY
  // dates with two-digit years differently
  // etc...etc...
  // ...therefore we manually parse lots of common date formats
  match = text.match(
    /^(\d{1,4})([\-\.\/\:])(\d{1,2})([\-\.\/\:])(\d{1,4})(?:\s(\d{1,2}):(\d{2})?:?(\d{2})?)?(?:\s([A-Z]+)?)?$/)

  if (match && match[2] === match[4]) {
    if (match[1] > 1901) {
      switch (match[2]) {
        case '-':
          {
          // YYYY-M-D
            if (match[3] > 12 || match[5] > 31) {
              return fail
            }

            return new Date(match[1], parseInt(match[3], 10) - 1, match[5],
            match[6] || 0, match[7] || 0, match[8] || 0, match[9] || 0) / 1000
          }
        case '.':
          {
          // YYYY.M.D is not parsed by strtotime()
            return fail
          }
        case '/':
          {
          // YYYY/M/D
            if (match[3] > 12 || match[5] > 31) {
              return fail
            }

            return new Date(match[1], parseInt(match[3], 10) - 1, match[5],
            match[6] || 0, match[7] || 0, match[8] || 0, match[9] || 0) / 1000
          }
      }
    } else if (match[5] > 1901) {
      switch (match[2]) {
        case '-':
          {
          // D-M-YYYY
            if (match[3] > 12 || match[1] > 31) {
              return fail
            }

            return new Date(match[5], parseInt(match[3], 10) - 1, match[1],
            match[6] || 0, match[7] || 0, match[8] || 0, match[9] || 0) / 1000
          }
        case '.':
          {
          // D.M.YYYY
            if (match[3] > 12 || match[1] > 31) {
              return fail
            }

            return new Date(match[5], parseInt(match[3], 10) - 1, match[1],
            match[6] || 0, match[7] || 0, match[8] || 0, match[9] || 0) / 1000
          }
        case '/':
          {
          // M/D/YYYY
            if (match[1] > 12 || match[3] > 31) {
              return fail
            }

            return new Date(match[5], parseInt(match[1], 10) - 1, match[3],
            match[6] || 0, match[7] || 0, match[8] || 0, match[9] || 0) / 1000
          }
      }
    } else {
      switch (match[2]) {
        case '-':
          {
          // YY-M-D
            if (match[3] > 12 || match[5] > 31 || (match[1] < 70 && match[1] > 38)) {
              return fail
            }

            year = match[1] >= 0 && match[1] <= 38 ? +match[1] + 2000 : match[1]
            return new Date(year, parseInt(match[3], 10) - 1, match[5],
            match[6] || 0, match[7] || 0, match[8] || 0, match[9] || 0) / 1000
          }
        case '.':
          {
          // D.M.YY or H.MM.SS
            if (match[5] >= 70) {
            // D.M.YY
              if (match[3] > 12 || match[1] > 31) {
                return fail
              }

              return new Date(match[5], parseInt(match[3], 10) - 1, match[1],
              match[6] || 0, match[7] || 0, match[8] || 0, match[9] || 0) / 1000
            }
            if (match[5] < 60 && !match[6]) {
            // H.MM.SS
              if (match[1] > 23 || match[3] > 59) {
                return fail
              }

              today = new Date()
              return new Date(today.getFullYear(), today.getMonth(), today.getDate(),
              match[1] || 0, match[3] || 0, match[5] || 0, match[9] || 0) / 1000
            }

          // invalid format, cannot be parsed
            return fail
          }
        case '/':
          {
          // M/D/YY
            if (match[1] > 12 || match[3] > 31 || (match[5] < 70 && match[5] > 38)) {
              return fail
            }

            year = match[5] >= 0 && match[5] <= 38 ? +match[5] + 2000 : match[5]
            return new Date(year, parseInt(match[1], 10) - 1, match[3],
            match[6] || 0, match[7] || 0, match[8] || 0, match[9] || 0) / 1000
          }
        case ':':
          {
          // HH:MM:SS
            if (match[1] > 23 || match[3] > 59 || match[5] > 59) {
              return fail
            }

            today = new Date()
            return new Date(today.getFullYear(), today.getMonth(), today.getDate(),
            match[1] || 0, match[3] || 0, match[5] || 0) / 1000
          }
      }
    }
  }

  // other formats and "now" should be parsed by Date.parse()
  if (text === 'now') {
    return now === null || isNaN(now) ? new Date()
      .getTime() / 1000 | 0 : now | 0
  }
  if (!isNaN(parsed = Date.parse(text))) {
    return parsed / 1000 | 0
  }
  // Browsers != Chrome have problems parsing ISO 8601 date strings, as they do
  // not accept lower case characters, space, or shortened time zones.
  // Therefore, fix these problems and try again.
  // Examples:
  //   2015-04-15 20:33:59+02
  //   2015-04-15 20:33:59z
  //   2015-04-15t20:33:59+02:00
  if (match = text.match(
      /^([0-9]{4}-[0-9]{2}-[0-9]{2})[ t]([0-9]{2}:[0-9]{2}:[0-9]{2}(\.[0-9]+)?)([\+-][0-9]{2}(:[0-9]{2})?|z)/)) {
    // fix time zone information
    if (match[4] == 'z') {
      match[4] = 'Z'
    } else if (match[4].match(/^([\+-][0-9]{2})$/)) {
      match[4] = match[4] + ':00'
    }

    if (!isNaN(parsed = Date.parse(match[1] + 'T' + match[2] + match[4]))) {
      return parsed / 1000 | 0
    }
  }

  date = now ? new Date(now * 1000) : new Date()
  days = {
    'sun': 0,
    'mon': 1,
    'tue': 2,
    'wed': 3,
    'thu': 4,
    'fri': 5,
    'sat': 6
  }
  ranges = {
    'yea': 'FullYear',
    'mon': 'Month',
    'day': 'Date',
    'hou': 'Hours',
    'min': 'Minutes',
    'sec': 'Seconds'
  }

  function lastNext (type, range, modifier) {
    var diff, day = days[range]

    if (typeof day !== 'undefined') {
      diff = day - date.getDay()

      if (diff === 0) {
        diff = 7 * modifier
      } else if (diff > 0 && type === 'last') {
        diff -= 7
      } else if (diff < 0 && type === 'next') {
        diff += 7
      }

      date.setDate(date.getDate() + diff)
    }
  }

  function process (val) {
    var splt = val.split(' '), // Todo: Reconcile this with regex using \s, taking into account browser issues with split and regexes
      type = splt[0],
      range = splt[1].substring(0, 3),
      typeIsNumber = /\d+/.test(type),
      ago = splt[2] === 'ago',
      num = (type === 'last' ? -1 : 1) * (ago ? -1 : 1)

    if (typeIsNumber) {
      num *= parseInt(type, 10)
    }

    if (ranges.hasOwnProperty(range) && !splt[1].match(/^mon(day|\.)?$/i)) {
      return date['set' + ranges[range]](date['get' + ranges[range]]() + num)
    }

    if (range === 'wee') {
      return date.setDate(date.getDate() + (num * 7))
    }

    if (type === 'next' || type === 'last') {
      lastNext(type, range, num)
    } else if (!typeIsNumber) {
      return false
    }

    return true
  }

  times = '(years?|months?|weeks?|days?|hours?|minutes?|min|seconds?|sec' +
    '|sunday|sun\\.?|monday|mon\\.?|tuesday|tue\\.?|wednesday|wed\\.?' +
    '|thursday|thu\\.?|friday|fri\\.?|saturday|sat\\.?)'
  regex = '([+-]?\\d+\\s' + times + '|' + '(last|next)\\s' + times + ')(\\sago)?'

  match = text.match(new RegExp(regex, 'gi'))
  if (!match) {
    return fail
  }

  for (i = 0, len = match.length; i < len; i++) {
    if (!process(match[i])) {
      return fail
    }
  }

  // ECMAScript 5 only
  // if (!match.every(process))
  //    return false;

  return (date.getTime() / 1000)
}

function is_numeric (mixed_var) {
  //  discuss at: http://phpjs.org/functions/is_numeric/
  // original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // improved by: David
  // improved by: taith
  // bugfixed by: Tim de Koning
  // bugfixed by: WebDevHobo (http://webdevhobo.blogspot.com/)
  // bugfixed by: Brett Zamir (http://brett-zamir.me)
  // bugfixed by: Denis Chenu (http://shnoulle.net)
  //   example 1: is_numeric(186.31);
  //   returns 1: true
  //   example 2: is_numeric('Kevin van Zonneveld');
  //   returns 2: false
  //   example 3: is_numeric(' +186.31e2');
  //   returns 3: true
  //   example 4: is_numeric('');
  //   returns 4: false
  //   example 5: is_numeric([]);
  //   returns 5: false
  //   example 6: is_numeric('1 ');
  //   returns 6: false

  var whitespace =
    ' \n\r\t\f\x0b\xa0\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u200b\u2028\u2029\u3000'
  return (typeof mixed_var === 'number' || (typeof mixed_var === 'string' && whitespace.indexOf(mixed_var.slice(-1)) ===
    -1)) && mixed_var !== '' && !isNaN(mixed_var)
}

/*!
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2014 - 2017
 * @version 1.3.4
 *
 * Date formatter utility library that allows formatting date/time variables or Date objects using PHP DateTime format.
 * This library is a standalone javascript library and does not depend on other libraries or plugins like jQuery.
 * 
 * @see http://php.net/manual/en/function.date.php
 *
 * For more JQuery plugins visit http://plugins.krajee.com
 * For more Yii related demos visit http://demos.krajee.com
 */
var DateFormatter;
(function () {
    "use strict";

    var _compare, _lpad, _extend, _indexOf, defaultSettings, DAY, HOUR;
    DAY = 1000 * 60 * 60 * 24;
    HOUR = 3600;

    _compare = function (str1, str2) {
        return typeof(str1) === 'string' && typeof(str2) === 'string' && str1.toLowerCase() === str2.toLowerCase();
    };
    _lpad = function (value, length, chr) {
        var val = value.toString();
        chr = chr || '0';
        return val.length < length ? _lpad(chr + val, length) : val;
    };
    _extend = function (out) {
        var i, obj;
        out = out || {};
        for (i = 1; i < arguments.length; i++) {
            obj = arguments[i];
            if (!obj) {
                continue;
            }
            for (var key in obj) {
                if (obj.hasOwnProperty(key)) {
                    if (typeof obj[key] === 'object') {
                        _extend(out[key], obj[key]);
                    } else {
                        out[key] = obj[key];
                    }
                }
            }
        }
        return out;
    };
    _indexOf = function (val, arr) {
        for (var i = 0; i < arr.length; i++) {
            if (arr[i].toLowerCase() === val.toLowerCase()) {
                return i;
            }
        }
        return -1;
    };
    defaultSettings = {
        dateSettings: {
            days: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
            daysShort: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
            months: [
                'January', 'February', 'March', 'April', 'May', 'June', 'July',
                'August', 'September', 'October', 'November', 'December'
            ],
            monthsShort: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            meridiem: ['AM', 'PM'],
            ordinal: function (number) {
                var n = number % 10, suffixes = {1: 'st', 2: 'nd', 3: 'rd'};
                return Math.floor(number % 100 / 10) === 1 || !suffixes[n] ? 'th' : suffixes[n];
            }
        },
        separators: /[ \-+\/\.T:@]/g,
        validParts: /[dDjlNSwzWFmMntLoYyaABgGhHisueTIOPZcrU]/g,
        intParts: /[djwNzmnyYhHgGis]/g,
        tzParts: /\b(?:[PMCEA][SDP]T|(?:Pacific|Mountain|Central|Eastern|Atlantic) (?:Standard|Daylight|Prevailing) Time|(?:GMT|UTC)(?:[-+]\d{4})?)\b/g,
        tzClip: /[^-+\dA-Z]/g
    };

    DateFormatter = function (options) {
        var self = this, config = _extend(defaultSettings, options);
        self.dateSettings = config.dateSettings;
        self.separators = config.separators;
        self.validParts = config.validParts;
        self.intParts = config.intParts;
        self.tzParts = config.tzParts;
        self.tzClip = config.tzClip;
    };

    DateFormatter.prototype = {
        constructor: DateFormatter,
        getMonth: function (val) {
            var self = this, i;
            i = _indexOf(val, self.dateSettings.monthsShort) + 1;
            if (i === 0) {
                i = _indexOf(val, self.dateSettings.months) + 1;
            }
            return i;
        },
        parseDate: function (vDate, vFormat) {
            var self = this, vFormatParts, vDateParts, i, vDateFlag = false, vTimeFlag = false, vDatePart, iDatePart,
                vSettings = self.dateSettings, vMonth, vMeriIndex, vMeriOffset, len, mer,
                out = {date: null, year: null, month: null, day: null, hour: 0, min: 0, sec: 0};
            if (!vDate) {
                return null;
            }
            if (vDate instanceof Date) {
                return vDate;
            }
            if (vFormat === 'U') {
                i = parseInt(vDate);
                return i ? new Date(i * 1000) : vDate;
            }
            switch (typeof vDate) {
                case 'number':
                    return new Date(vDate);
                case 'string':
                    break;
                default:
                    return null;
            }
            vFormatParts = vFormat.match(self.validParts);
            if (!vFormatParts || vFormatParts.length === 0) {
                throw new Error("Invalid date format definition.");
            }
            vDateParts = vDate.replace(self.separators, '\0').split('\0');
            for (i = 0; i < vDateParts.length; i++) {
                vDatePart = vDateParts[i];
                iDatePart = parseInt(vDatePart);
                switch (vFormatParts[i]) {
                    case 'y':
                    case 'Y':
                        if (iDatePart) {
                            len = vDatePart.length;
                            out.year = len === 2 ? parseInt((iDatePart < 70 ? '20' : '19') + vDatePart) : iDatePart;
                        } else {
                            return null;
                        }
                        vDateFlag = true;
                        break;
                    case 'm':
                    case 'n':
                    case 'M':
                    case 'F':
                        if (isNaN(iDatePart)) {
                            vMonth = self.getMonth(vDatePart);
                            if (vMonth > 0) {
                                out.month = vMonth;
                            } else {
                                return null;
                            }
                        } else {
                            if (iDatePart >= 1 && iDatePart <= 12) {
                                out.month = iDatePart;
                            } else {
                                return null;
                            }
                        }
                        vDateFlag = true;
                        break;
                    case 'd':
                    case 'j':
                        if (iDatePart >= 1 && iDatePart <= 31) {
                            out.day = iDatePart;
                        } else {
                            return null;
                        }
                        vDateFlag = true;
                        break;
                    case 'g':
                    case 'h':
                        vMeriIndex = (vFormatParts.indexOf('a') > -1) ? vFormatParts.indexOf('a') :
                            (vFormatParts.indexOf('A') > -1) ? vFormatParts.indexOf('A') : -1;
                        mer = vDateParts[vMeriIndex];
                        if (vMeriIndex !== -1) {
                            vMeriOffset = _compare(mer, vSettings.meridiem[0]) ? 0 :
                                (_compare(mer, vSettings.meridiem[1]) ? 12 : -1);
                            if (iDatePart >= 1 && iDatePart <= 12 && vMeriOffset !== -1) {
                                out.hour = iDatePart % 12 === 0 ? vMeriOffset : iDatePart + vMeriOffset;
                            } else {
                                if (iDatePart >= 0 && iDatePart <= 23) {
                                    out.hour = iDatePart;
                                }
                            }
                        } else {
                            if (iDatePart >= 0 && iDatePart <= 23) {
                                out.hour = iDatePart;
                            } else {
                                return null;
                            }
                        }
                        vTimeFlag = true;
                        break;
                    case 'G':
                    case 'H':
                        if (iDatePart >= 0 && iDatePart <= 23) {
                            out.hour = iDatePart;
                        } else {
                            return null;
                        }
                        vTimeFlag = true;
                        break;
                    case 'i':
                        if (iDatePart >= 0 && iDatePart <= 59) {
                            out.min = iDatePart;
                        } else {
                            return null;
                        }
                        vTimeFlag = true;
                        break;
                    case 's':
                        if (iDatePart >= 0 && iDatePart <= 59) {
                            out.sec = iDatePart;
                        } else {
                            return null;
                        }
                        vTimeFlag = true;
                        break;
                }
            }
            if (vDateFlag === true && out.year && out.month && out.day) {
                out.date = new Date(out.year, out.month - 1, out.day, out.hour, out.min, out.sec, 0);
            } else {
                if (vTimeFlag !== true) {
                    return null;
                }
                out.date = new Date(0, 0, 0, out.hour, out.min, out.sec, 0);
            }
            return out.date;
        },
        guessDate: function (vDateStr, vFormat) {
            if (typeof vDateStr !== 'string') {
                return vDateStr;
            }
            var self = this, vParts = vDateStr.replace(self.separators, '\0').split('\0'), vPattern = /^[djmn]/g, len,
                vFormatParts = vFormat.match(self.validParts), vDate = new Date(), vDigit = 0, vYear, i, n, iPart, iSec;

            if (!vPattern.test(vFormatParts[0])) {
                return vDateStr;
            }

            for (i = 0; i < vParts.length; i++) {
                vDigit = 2;
                iPart = vParts[i];
                iSec = parseInt(iPart.substr(0, 2));
                if (isNaN(iSec)) {
                    return null;
                }
                switch (i) {
                    case 0:
                        if (vFormatParts[0] === 'm' || vFormatParts[0] === 'n') {
                            vDate.setMonth(iSec - 1);
                        } else {
                            vDate.setDate(iSec);
                        }
                        break;
                    case 1:
                        if (vFormatParts[0] === 'm' || vFormatParts[0] === 'n') {
                            vDate.setDate(iSec);
                        } else {
                            vDate.setMonth(iSec - 1);
                        }
                        break;
                    case 2:
                        vYear = vDate.getFullYear();
                        len = iPart.length;
                        vDigit = len < 4 ? len : 4;
                        vYear = parseInt(len < 4 ? vYear.toString().substr(0, 4 - len) + iPart : iPart.substr(0, 4));
                        if (!vYear) {
                            return null;
                        }
                        vDate.setFullYear(vYear);
                        break;
                    case 3:
                        vDate.setHours(iSec);
                        break;
                    case 4:
                        vDate.setMinutes(iSec);
                        break;
                    case 5:
                        vDate.setSeconds(iSec);
                        break;
                }
                n = iPart.substr(vDigit);
                if (n.length > 0) {
                    vParts.splice(i + 1, 0, n);
                }
            }
            return vDate;
        },
        parseFormat: function (vChar, vDate) {
            var self = this, vSettings = self.dateSettings, fmt, backslash = /\\?(.?)/gi, doFormat = function (t, s) {
                return fmt[t] ? fmt[t]() : s;
            };
            fmt = {
                /////////
                // DAY //
                /////////
                /**
                 * Day of month with leading 0: `01..31`
                 * @return {string}
                 */
                d: function () {
                    return _lpad(fmt.j(), 2);
                },
                /**
                 * Shorthand day name: `Mon...Sun`
                 * @return {string}
                 */
                D: function () {
                    return vSettings.daysShort[fmt.w()];
                },
                /**
                 * Day of month: `1..31`
                 * @return {number}
                 */
                j: function () {
                    return vDate.getDate();
                },
                /**
                 * Full day name: `Monday...Sunday`
                 * @return {number}
                 */
                l: function () {
                    return vSettings.days[fmt.w()];
                },
                /**
                 * ISO-8601 day of week: `1[Mon]..7[Sun]`
                 * @return {number}
                 */
                N: function () {
                    return fmt.w() || 7;
                },
                /**
                 * Day of week: `0[Sun]..6[Sat]`
                 * @return {number}
                 */
                w: function () {
                    return vDate.getDay();
                },
                /**
                 * Day of year: `0..365`
                 * @return {number}
                 */
                z: function () {
                    var a = new Date(fmt.Y(), fmt.n() - 1, fmt.j()), b = new Date(fmt.Y(), 0, 1);
                    return Math.round((a - b) / DAY);
                },

                //////////
                // WEEK //
                //////////
                /**
                 * ISO-8601 week number
                 * @return {number}
                 */
                W: function () {
                    var a = new Date(fmt.Y(), fmt.n() - 1, fmt.j() - fmt.N() + 3), b = new Date(a.getFullYear(), 0, 4);
                    return _lpad(1 + Math.round((a - b) / DAY / 7), 2);
                },

                ///////////
                // MONTH //
                ///////////
                /**
                 * Full month name: `January...December`
                 * @return {string}
                 */
                F: function () {
                    return vSettings.months[vDate.getMonth()];
                },
                /**
                 * Month w/leading 0: `01..12`
                 * @return {string}
                 */
                m: function () {
                    return _lpad(fmt.n(), 2);
                },
                /**
                 * Shorthand month name; `Jan...Dec`
                 * @return {string}
                 */
                M: function () {
                    return vSettings.monthsShort[vDate.getMonth()];
                },
                /**
                 * Month: `1...12`
                 * @return {number}
                 */
                n: function () {
                    return vDate.getMonth() + 1;
                },
                /**
                 * Days in month: `28...31`
                 * @return {number}
                 */
                t: function () {
                    return (new Date(fmt.Y(), fmt.n(), 0)).getDate();
                },

                //////////
                // YEAR //
                //////////
                /**
                 * Is leap year? `0 or 1`
                 * @return {number}
                 */
                L: function () {
                    var Y = fmt.Y();
                    return (Y % 4 === 0 && Y % 100 !== 0 || Y % 400 === 0) ? 1 : 0;
                },
                /**
                 * ISO-8601 year
                 * @return {number}
                 */
                o: function () {
                    var n = fmt.n(), W = fmt.W(), Y = fmt.Y();
                    return Y + (n === 12 && W < 9 ? 1 : n === 1 && W > 9 ? -1 : 0);
                },
                /**
                 * Full year: `e.g. 1980...2010`
                 * @return {number}
                 */
                Y: function () {
                    return vDate.getFullYear();
                },
                /**
                 * Last two digits of year: `00...99`
                 * @return {string}
                 */
                y: function () {
                    return fmt.Y().toString().slice(-2);
                },

                //////////
                // TIME //
                //////////
                /**
                 * Meridian lower: `am or pm`
                 * @return {string}
                 */
                a: function () {
                    return fmt.A().toLowerCase();
                },
                /**
                 * Meridian upper: `AM or PM`
                 * @return {string}
                 */
                A: function () {
                    var n = fmt.G() < 12 ? 0 : 1;
                    return vSettings.meridiem[n];
                },
                /**
                 * Swatch Internet time: `000..999`
                 * @return {string}
                 */
                B: function () {
                    var H = vDate.getUTCHours() * HOUR, i = vDate.getUTCMinutes() * 60, s = vDate.getUTCSeconds();
                    return _lpad(Math.floor((H + i + s + HOUR) / 86.4) % 1000, 3);
                },
                /**
                 * 12-Hours: `1..12`
                 * @return {number}
                 */
                g: function () {
                    return fmt.G() % 12 || 12;
                },
                /**
                 * 24-Hours: `0..23`
                 * @return {number}
                 */
                G: function () {
                    return vDate.getHours();
                },
                /**
                 * 12-Hours with leading 0: `01..12`
                 * @return {string}
                 */
                h: function () {
                    return _lpad(fmt.g(), 2);
                },
                /**
                 * 24-Hours w/leading 0: `00..23`
                 * @return {string}
                 */
                H: function () {
                    return _lpad(fmt.G(), 2);
                },
                /**
                 * Minutes w/leading 0: `00..59`
                 * @return {string}
                 */
                i: function () {
                    return _lpad(vDate.getMinutes(), 2);
                },
                /**
                 * Seconds w/leading 0: `00..59`
                 * @return {string}
                 */
                s: function () {
                    return _lpad(vDate.getSeconds(), 2);
                },
                /**
                 * Microseconds: `000000-999000`
                 * @return {string}
                 */
                u: function () {
                    return _lpad(vDate.getMilliseconds() * 1000, 6);
                },

                //////////////
                // TIMEZONE //
                //////////////
                /**
                 * Timezone identifier: `e.g. Atlantic/Azores, ...`
                 * @return {string}
                 */
                e: function () {
                    var str = /\((.*)\)/.exec(String(vDate))[1];
                    return str || 'Coordinated Universal Time';
                },
                /**
                 * DST observed? `0 or 1`
                 * @return {number}
                 */
                I: function () {
                    var a = new Date(fmt.Y(), 0), c = Date.UTC(fmt.Y(), 0),
                        b = new Date(fmt.Y(), 6), d = Date.UTC(fmt.Y(), 6);
                    return ((a - c) !== (b - d)) ? 1 : 0;
                },
                /**
                 * Difference to GMT in hour format: `e.g. +0200`
                 * @return {string}
                 */
                O: function () {
                    var tzo = vDate.getTimezoneOffset(), a = Math.abs(tzo);
                    return (tzo > 0 ? '-' : '+') + _lpad(Math.floor(a / 60) * 100 + a % 60, 4);
                },
                /**
                 * Difference to GMT with colon: `e.g. +02:00`
                 * @return {string}
                 */
                P: function () {
                    var O = fmt.O();
                    return (O.substr(0, 3) + ':' + O.substr(3, 2));
                },
                /**
                 * Timezone abbreviation: `e.g. EST, MDT, ...`
                 * @return {string}
                 */
                T: function () {
                    var str = (String(vDate).match(self.tzParts) || [""]).pop().replace(self.tzClip, "");
                    return str || 'UTC';
                },
                /**
                 * Timezone offset in seconds: `-43200...50400`
                 * @return {number}
                 */
                Z: function () {
                    return -vDate.getTimezoneOffset() * 60;
                },

                ////////////////////
                // FULL DATE TIME //
                ////////////////////
                /**
                 * ISO-8601 date
                 * @return {string}
                 */
                c: function () {
                    return 'Y-m-d\\TH:i:sP'.replace(backslash, doFormat);
                },
                /**
                 * RFC 2822 date
                 * @return {string}
                 */
                r: function () {
                    return 'D, d M Y H:i:s O'.replace(backslash, doFormat);
                },
                /**
                 * Seconds since UNIX epoch
                 * @return {number}
                 */
                U: function () {
                    return vDate.getTime() / 1000 || 0;
                }
            };
            return doFormat(vChar, vChar);
        },
        formatDate: function (vDate, vFormat) {
            var self = this, i, n, len, str, vChar, vDateStr = '', BACKSLASH = '\\';
            if (typeof vDate === 'string') {
                vDate = self.parseDate(vDate, vFormat);
                if (!vDate) {
                    return null;
                }
            }
            if (vDate instanceof Date) {
                len = vFormat.length;
                for (i = 0; i < len; i++) {
                    vChar = vFormat.charAt(i);
                    if (vChar === 'S' || vChar === BACKSLASH) {
                        continue;
                    }
                    if (i > 0 && vFormat.charAt(i - 1) === BACKSLASH) {
                        vDateStr += vChar;
                        continue;
                    }
                    str = self.parseFormat(vChar, vDate);
                    if (i !== (len - 1) && self.intParts.test(vChar) && vFormat.charAt(i + 1) === 'S') {
                        n = parseInt(str) || 0;
                        str += self.dateSettings.ordinal(n);
                    }
                    vDateStr += str;
                }
                return vDateStr;
            }
            return '';
        }
    };
})();
/*!
 * Laravel Javascript Validation
 *
 * https://github.com/proengsoft/laravel-jsvalidation
 *
 * Copyright (c) 2017 Proengsoft
 * Released under the MIT license
 */

var laravelValidation;
laravelValidation = {

    implicitRules: ['Required','Confirmed'],

    /**
     * Initialize laravel validations.
     */
    init: function () {

        // Disable class rules and attribute rules
        $.validator.classRuleSettings = {};
        $.validator.attributeRules = function () {
            this.rules = {}
        };

        $.validator.dataRules = this.arrayRules;
        $.validator.prototype.arrayRulesCache = {};
        // Register validations methods
        this.setupValidations();
    },

    arrayRules: function(element) {

        var rules = {},
            validator = $.data( element.form, "validator"),
            cache = validator.arrayRulesCache;

        // Is not an Array
        if (element.name.indexOf('[') === -1 ) {
            return rules;
        }

        if (! (element.name in cache) ) {
            cache[element.name]={};
        }

        $.each(validator.settings.rules, function(name, tmpRules){
            if (name in cache[element.name]) {
                $.extend(rules, cache[element.name][name]);
            } else {
                cache[element.name][name]={};
                var nameRegExp = laravelValidation.helpers.regexFromWildcard(name);
                if (element.name.match(nameRegExp)) {
                    var newRules = $.validator.normalizeRule( tmpRules ) || {};
                    cache[element.name][name]=newRules;
                    $.extend(rules, newRules);
                }
            }
        });

        return rules;
    },

    setupValidations: function () {

        /**
         * Create JQueryValidation check to validate Laravel rules.
         */

        $.validator.addMethod("laravelValidation", function (value, element, params) {
            var validator = this;
            var validated = true;
            var previous = this.previousValue( element );

            // put Implicit rules in front
            var rules=[];
            $.each(params, function (i, param) {
                if (param[3] || laravelValidation.implicitRules.indexOf(param[0])!== -1) {
                    rules.unshift(param);
                } else {
                    rules.push(param);
                }
            });

            $.each(rules, function (i, param) {
                var implicit = param[3] || laravelValidation.implicitRules.indexOf(param[0])!== -1;
                var rule = param[0];
                var message = param[2];

                if ( !implicit && validator.optional( element ) ) {
                    validated="dependency-mismatch";
                    return false;
                }

                if (laravelValidation.methods[rule]!==undefined) {
                    validated = laravelValidation.methods[rule].call(validator, value, element, param[1], function(valid) {
                        validator.settings.messages[ element.name ].laravelValidationRemote = previous.originalMessage;
                        if ( valid ) {
                            var submitted = validator.formSubmitted;
                            validator.prepareElement( element );
                            validator.formSubmitted = submitted;
                            validator.successList.push( element );
                            delete validator.invalid[ element.name ];
                            validator.showErrors();
                        } else {
                            var errors = {};
                            errors[ element.name ] = previous.message = $.isFunction( message ) ? message( value ) : message;
                            validator.invalid[ element.name ] = true;
                            validator.showErrors( errors );
                        }
                        validator.showErrors(validator.errorMap);
                        previous.valid = valid;
                    });
                } else {
                    validated=false;
                }

                if (validated !== true) {
                    if (!validator.settings.messages[ element.name ] ) {
                        validator.settings.messages[ element.name ] = {};
                    }
                    validator.settings.messages[element.name].laravelValidation= message;
                    return false;
                }

            });
            return validated;

        }, '');


        /**
         * Create JQueryValidation check to validate Remote Laravel rules.
         */
        $.validator.addMethod("laravelValidationRemote", function (value, element, params) {

            var implicit = false,
                check = params[0][1],
                attribute = element.name,
                token = check[1],
                validateAll = check[2];

            $.each(params, function (i, parameters) {
                implicit = implicit || parameters[3];
            });


            if ( !implicit && this.optional( element ) ) {
                return "dependency-mismatch";
            }

            var previous = this.previousValue( element ),
                validator, data;

            if (!this.settings.messages[ element.name ] ) {
                this.settings.messages[ element.name ] = {};
            }
            previous.originalMessage = this.settings.messages[ element.name ].laravelValidationRemote;
            this.settings.messages[ element.name ].laravelValidationRemote = previous.message;

            var param = typeof param === "string" && { url: param } || param;

            if ( previous.old === value ) {
                return previous.valid;
            }

            previous.old = value;
            validator = this;
            this.startRequest( element );

            data = $(validator.currentForm).serializeArray();

            data.push({
                'name': '_jsvalidation',
                'value': attribute
            });

            data.push({
                'name': '_jsvalidation_validate_all',
                'value': validateAll
            });

            var formMethod = $(validator.currentForm).attr('method');
            if($(validator.currentForm).find('input[name="_method"]').length) {
                formMethod = $(validator.currentForm).find('input[name="_method"]').val();
            }

            $.ajax( $.extend( true, {
                mode: "abort",
                port: "validate" + element.name,
                dataType: "json",
                data: data,
                context: validator.currentForm,
                url: $(validator.currentForm).attr('action'),
                type: formMethod,

                beforeSend: function (xhr) {
                    if ($(validator.currentForm).attr('method').toLowerCase() !== 'get' && token) {
                        return xhr.setRequestHeader('X-XSRF-TOKEN', token);
                    }
                }
            }, param )
            ).always(function( response, textStatus ) {
                    var errors, message, submitted, valid;

                    if (textStatus === 'error') {
                        valid = false;
                        response = laravelValidation.helpers.parseErrorResponse(response);
                    } else if (textStatus === 'success') {
                        valid = response === true || response === "true";
                    } else {
                        return;
                    }

                    validator.settings.messages[ element.name ].laravelValidationRemote = previous.originalMessage;

                    if ( valid ) {
                        submitted = validator.formSubmitted;
                        validator.prepareElement( element );
                        validator.formSubmitted = submitted;
                        validator.successList.push( element );
                        delete validator.invalid[ element.name ];
                        validator.showErrors();
                    } else {
                        errors = {};
                        message = response || validator.defaultMessage( element, "remote" );
                        errors[ element.name ] = previous.message = $.isFunction( message ) ? message( value ) : message[0];
                        validator.invalid[ element.name ] = true;
                        validator.showErrors( errors );
                    }
                    validator.showErrors(validator.errorMap);
                    previous.valid = valid;
                    validator.stopRequest( element, valid );
                }
            );
            return "pending";
        }, '');
    }
};

$(function() {
    laravelValidation.init();
});

/*!
 * Laravel Javascript Validation
 *
 * https://github.com/proengsoft/laravel-jsvalidation
 *
 * Helper functions used by validators
 *
 * Copyright (c) 2017 Proengsoft
 * Released under the MIT license
 */

$.extend(true, laravelValidation, {

    helpers: {

        /**
         * Numeric rules
         */
        numericRules: ['Integer', 'Numeric'],

        /**
         * Gets the file information from file input.
         *
         * @param fieldObj
         * @param index
         * @returns {{file: *, extension: string, size: number}}
         */
        fileinfo: function (fieldObj, index) {
            var FileName = fieldObj.value;
            index = typeof index !== 'undefined' ? index : 0;
            if ( fieldObj.files !== null ) {
                if (typeof fieldObj.files[index] !== 'undefined') {
                    return {
                        file: FileName,
                        extension: FileName.substr(FileName.lastIndexOf('.') + 1),
                        size: fieldObj.files[index].size / 1024,
                        type: fieldObj.files[index].type
                    };
                }
            }
            return false;
        },


        /**
         * Gets the selectors for th specified field names.
         *
         * @param names
         * @returns {string}
         */
        selector: function (names) {
            var selector = [];
            if (!$.isArray(names))  {
                names = [names];
            }
            for (var i = 0; i < names.length; i++) {
                selector.push("[name='" + names[i] + "']");
            }
            return selector.join();
        },


        /**
         * Check if element has numeric rules.
         *
         * @param element
         * @returns {boolean}
         */
        hasNumericRules: function (element) {
            return this.hasRules(element, this.numericRules);
        },

        /**
         * Check if element has passed rules.
         *
         * @param element
         * @param rules
         * @returns {boolean}
         */
        hasRules: function (element, rules) {

            var found = false;
            if (typeof rules === 'string') {
                rules = [rules];
            }

            var validator = $.data(element.form, "validator");
            var listRules = [];
            var cache = validator.arrayRulesCache;
            if (element.name in cache) {
                $.each(cache[element.name], function (index, arrayRule) {
                    listRules.push(arrayRule);
                });
            }
            if (element.name in validator.settings.rules) {
                listRules.push(validator.settings.rules[element.name]);
            }
            $.each(listRules, function(index,objRules){
                if ('laravelValidation' in objRules) {
                    var _rules=objRules.laravelValidation;
                    for (var i = 0; i < _rules.length; i++) {
                        if ($.inArray(_rules[i][0],rules) !== -1) {
                            found = true;
                            return false;
                        }
                    }
                }
            });

            return found;
        },

        /**
         * Return the string length using PHP function.
         * http://php.net/manual/en/function.strlen.php
         * http://phpjs.org/functions/strlen/
         *
         * @param string
         */
        strlen: function (string) {
            return strlen(string);
        },

        /**
         * Get the size of the object depending of his type.
         *
         * @param obj
         * @param element
         * @param value
         * @returns int
         */
        getSize: function getSize(obj, element, value) {

            if (this.hasNumericRules(element) && this.is_numeric(value)) {
                return parseFloat(value);
            } else if ($.isArray(value)) {
                return parseFloat(value.length);
            } else if (element.type === 'file') {
                return parseFloat(Math.floor(this.fileinfo(element).size));
            }

            return parseFloat(this.strlen(value));
        },


        /**
         * Return specified rule from element.
         *
         * @param rule
         * @param element
         * @returns object
         */
        getLaravelValidation: function(rule, element) {

            var found = undefined;
            $.each($.validator.staticRules(element), function(key, rules) {
                if (key==="laravelValidation") {
                    $.each(rules, function (i, value) {
                        if (value[0]===rule) {
                            found=value;
                        }
                    });
                }
            });

            return found;
        },

        /**
         * Return he timestamp of value passed using format or default format in element.
         *
         * @param value
         * @param format
         * @returns {boolean|int}
         */
        parseTime: function (value, format) {

            var timeValue = false;
            var fmt = new DateFormatter();

            if ($.type(format) === 'object') {
                var dateRule=this.getLaravelValidation('DateFormat', format);
                if (dateRule !== undefined) {
                    format = dateRule[1][0];
                } else {
                    format = null;
                }
            }

            if (format == null) {
                timeValue = this.strtotime(value);
            } else {
                timeValue = fmt.parseDate(value, format);
                if (timeValue) {
                    timeValue = Math.round((timeValue.getTime() / 1000));
                }
            }

            return timeValue;
        },

        /**
         * This method allows you to intelligently guess the date by closely matching the specific format.
         *
         * @param value
         * @param format
         * @returns {Date}
         */
        guessDate: function (value, format) {
            var fmt = new DateFormatter();
            return fmt.guessDate(value, format)
        },

        /**
         * Returns Unix timestamp based on PHP function strototime.
         * http://php.net/manual/es/function.strtotime.php
         * http://phpjs.org/functions/strtotime/
         *
         * @param text
         * @param now
         * @returns {*}
         */
        strtotime: function (text, now) {
            return strtotime(text, now)
        },

        /**
         * Returns if value is numeric.
         * http://php.net/manual/es/var.is_numeric.php
         * http://phpjs.org/functions/is_numeric/
         *
         * @param mixed_var
         * @returns {*}
         */
        is_numeric: function (mixed_var) {
            return is_numeric(mixed_var)
        },

        /**
         * Returns Array diff based on PHP function array_diff.
         * http://php.net/manual/es/function.array_diff.php
         * http://phpjs.org/functions/array_diff/
         *
         * @param arr1
         * @param arr2
         * @returns {*}
         */
        arrayDiff: function (arr1, arr2) {
            return array_diff(arr1, arr2);
        },

        /**
         * Makes element dependant from other.
         *
         * @param validator
         * @param element
         * @param name
         * @returns {*}
         */
        dependentElement: function(validator, element, name) {

            var el=validator.findByName(name);

            if ( el[0]!==undefined  && validator.settings.onfocusout ) {
                var event = 'blur';
                if (el[0].tagName === 'SELECT' ||
                    el[0].tagName === 'OPTION' ||
                    el[0].type === 'checkbox' ||
                    el[0].type === 'radio'
                ) {
                    event = 'click';
                }

                var ruleName = '.validate-laravelValidation';
                el.off( ruleName )
                    .off(event + ruleName + '-' + element.name)
                    .on( event + ruleName + '-' + element.name, function() {
                        $( element ).valid();
                    });
            }

            return el[0];
        },

        /**
         * Parses error Ajax response and gets the message.
         *
         * @param response
         * @returns {string[]}
         */
        parseErrorResponse: function (response) {
            var newResponse = ['Whoops, looks like something went wrong.'];
            if ('responseText' in response) {
                var errorMsg = response.responseText.match(/<h1\s*>(.*)<\/h1\s*>/i);
                if ($.isArray(errorMsg)) {
                    newResponse = [errorMsg[1]];
                }
            }
            return newResponse;
        },

        /**
         * Escape string to use as Regular Expression.
         *
         * @param str
         * @returns string
         */
        escapeRegExp: function (str) {
            return str.replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, "\\$&");
        },

        /**
         * Generate RegExp from wildcard attributes.
         *
         * @param name
         * @returns {RegExp}
         */
        regexFromWildcard: function(name) {
            var nameParts = name.split("[*]");
            if (nameParts.length === 1) {
                nameParts.push('');
            }
            var regexpParts = nameParts.map(function(currentValue, index) {
                if (index % 2 === 0) {
                    currentValue = currentValue + '[';
                } else {
                    currentValue = ']' +currentValue;
                }

                return laravelValidation.helpers.escapeRegExp(currentValue);
            });

            return new RegExp('^'+regexpParts.join('.*')+'$');
        }
    }
});

/*!
 * Laravel Javascript Validation
 *
 * https://github.com/proengsoft/laravel-jsvalidation
 *
 * Timezone Helper functions used by validators
 *
 * Copyright (c) 2017 Proengsoft
 * Released under the MIT license
 */

$.extend(true, laravelValidation, {

    helpers: {

        /**
         * Check if the specified timezone is valid.
         *
         * @param value
         * @returns {boolean}
         */
        isTimezone: function (value) {

            var timezones={
                "africa": [
                    "abidjan",
                    "accra",
                    "addis_ababa",
                    "algiers",
                    "asmara",
                    "bamako",
                    "bangui",
                    "banjul",
                    "bissau",
                    "blantyre",
                    "brazzaville",
                    "bujumbura",
                    "cairo",
                    "casablanca",
                    "ceuta",
                    "conakry",
                    "dakar",
                    "dar_es_salaam",
                    "djibouti",
                    "douala",
                    "el_aaiun",
                    "freetown",
                    "gaborone",
                    "harare",
                    "johannesburg",
                    "juba",
                    "kampala",
                    "khartoum",
                    "kigali",
                    "kinshasa",
                    "lagos",
                    "libreville",
                    "lome",
                    "luanda",
                    "lubumbashi",
                    "lusaka",
                    "malabo",
                    "maputo",
                    "maseru",
                    "mbabane",
                    "mogadishu",
                    "monrovia",
                    "nairobi",
                    "ndjamena",
                    "niamey",
                    "nouakchott",
                    "ouagadougou",
                    "porto-novo",
                    "sao_tome",
                    "tripoli",
                    "tunis",
                    "windhoek"
                ],
                "america": [
                    "adak",
                    "anchorage",
                    "anguilla",
                    "antigua",
                    "araguaina",
                    "argentina\/buenos_aires",
                    "argentina\/catamarca",
                    "argentina\/cordoba",
                    "argentina\/jujuy",
                    "argentina\/la_rioja",
                    "argentina\/mendoza",
                    "argentina\/rio_gallegos",
                    "argentina\/salta",
                    "argentina\/san_juan",
                    "argentina\/san_luis",
                    "argentina\/tucuman",
                    "argentina\/ushuaia",
                    "aruba",
                    "asuncion",
                    "atikokan",
                    "bahia",
                    "bahia_banderas",
                    "barbados",
                    "belem",
                    "belize",
                    "blanc-sablon",
                    "boa_vista",
                    "bogota",
                    "boise",
                    "cambridge_bay",
                    "campo_grande",
                    "cancun",
                    "caracas",
                    "cayenne",
                    "cayman",
                    "chicago",
                    "chihuahua",
                    "costa_rica",
                    "creston",
                    "cuiaba",
                    "curacao",
                    "danmarkshavn",
                    "dawson",
                    "dawson_creek",
                    "denver",
                    "detroit",
                    "dominica",
                    "edmonton",
                    "eirunepe",
                    "el_salvador",
                    "fortaleza",
                    "glace_bay",
                    "godthab",
                    "goose_bay",
                    "grand_turk",
                    "grenada",
                    "guadeloupe",
                    "guatemala",
                    "guayaquil",
                    "guyana",
                    "halifax",
                    "havana",
                    "hermosillo",
                    "indiana\/indianapolis",
                    "indiana\/knox",
                    "indiana\/marengo",
                    "indiana\/petersburg",
                    "indiana\/tell_city",
                    "indiana\/vevay",
                    "indiana\/vincennes",
                    "indiana\/winamac",
                    "inuvik",
                    "iqaluit",
                    "jamaica",
                    "juneau",
                    "kentucky\/louisville",
                    "kentucky\/monticello",
                    "kralendijk",
                    "la_paz",
                    "lima",
                    "los_angeles",
                    "lower_princes",
                    "maceio",
                    "managua",
                    "manaus",
                    "marigot",
                    "martinique",
                    "matamoros",
                    "mazatlan",
                    "menominee",
                    "merida",
                    "metlakatla",
                    "mexico_city",
                    "miquelon",
                    "moncton",
                    "monterrey",
                    "montevideo",
                    "montreal",
                    "montserrat",
                    "nassau",
                    "new_york",
                    "nipigon",
                    "nome",
                    "noronha",
                    "north_dakota\/beulah",
                    "north_dakota\/center",
                    "north_dakota\/new_salem",
                    "ojinaga",
                    "panama",
                    "pangnirtung",
                    "paramaribo",
                    "phoenix",
                    "port-au-prince",
                    "port_of_spain",
                    "porto_velho",
                    "puerto_rico",
                    "rainy_river",
                    "rankin_inlet",
                    "recife",
                    "regina",
                    "resolute",
                    "rio_branco",
                    "santa_isabel",
                    "santarem",
                    "santiago",
                    "santo_domingo",
                    "sao_paulo",
                    "scoresbysund",
                    "shiprock",
                    "sitka",
                    "st_barthelemy",
                    "st_johns",
                    "st_kitts",
                    "st_lucia",
                    "st_thomas",
                    "st_vincent",
                    "swift_current",
                    "tegucigalpa",
                    "thule",
                    "thunder_bay",
                    "tijuana",
                    "toronto",
                    "tortola",
                    "vancouver",
                    "whitehorse",
                    "winnipeg",
                    "yakutat",
                    "yellowknife"
                ],
                "antarctica": [
                    "casey",
                    "davis",
                    "dumontdurville",
                    "macquarie",
                    "mawson",
                    "mcmurdo",
                    "palmer",
                    "rothera",
                    "south_pole",
                    "syowa",
                    "vostok"
                ],
                "arctic": [
                    "longyearbyen"
                ],
                "asia": [
                    "aden",
                    "almaty",
                    "amman",
                    "anadyr",
                    "aqtau",
                    "aqtobe",
                    "ashgabat",
                    "baghdad",
                    "bahrain",
                    "baku",
                    "bangkok",
                    "beirut",
                    "bishkek",
                    "brunei",
                    "choibalsan",
                    "chongqing",
                    "colombo",
                    "damascus",
                    "dhaka",
                    "dili",
                    "dubai",
                    "dushanbe",
                    "gaza",
                    "harbin",
                    "hebron",
                    "ho_chi_minh",
                    "hong_kong",
                    "hovd",
                    "irkutsk",
                    "jakarta",
                    "jayapura",
                    "jerusalem",
                    "kabul",
                    "kamchatka",
                    "karachi",
                    "kashgar",
                    "kathmandu",
                    "khandyga",
                    "kolkata",
                    "krasnoyarsk",
                    "kuala_lumpur",
                    "kuching",
                    "kuwait",
                    "macau",
                    "magadan",
                    "makassar",
                    "manila",
                    "muscat",
                    "nicosia",
                    "novokuznetsk",
                    "novosibirsk",
                    "omsk",
                    "oral",
                    "phnom_penh",
                    "pontianak",
                    "pyongyang",
                    "qatar",
                    "qyzylorda",
                    "rangoon",
                    "riyadh",
                    "sakhalin",
                    "samarkand",
                    "seoul",
                    "shanghai",
                    "singapore",
                    "taipei",
                    "tashkent",
                    "tbilisi",
                    "tehran",
                    "thimphu",
                    "tokyo",
                    "ulaanbaatar",
                    "urumqi",
                    "ust-nera",
                    "vientiane",
                    "vladivostok",
                    "yakutsk",
                    "yekaterinburg",
                    "yerevan"
                ],
                "atlantic": [
                    "azores",
                    "bermuda",
                    "canary",
                    "cape_verde",
                    "faroe",
                    "madeira",
                    "reykjavik",
                    "south_georgia",
                    "st_helena",
                    "stanley"
                ],
                "australia": [
                    "adelaide",
                    "brisbane",
                    "broken_hill",
                    "currie",
                    "darwin",
                    "eucla",
                    "hobart",
                    "lindeman",
                    "lord_howe",
                    "melbourne",
                    "perth",
                    "sydney"
                ],
                "europe": [
                    "amsterdam",
                    "andorra",
                    "athens",
                    "belgrade",
                    "berlin",
                    "bratislava",
                    "brussels",
                    "bucharest",
                    "budapest",
                    "busingen",
                    "chisinau",
                    "copenhagen",
                    "dublin",
                    "gibraltar",
                    "guernsey",
                    "helsinki",
                    "isle_of_man",
                    "istanbul",
                    "jersey",
                    "kaliningrad",
                    "kiev",
                    "lisbon",
                    "ljubljana",
                    "london",
                    "luxembourg",
                    "madrid",
                    "malta",
                    "mariehamn",
                    "minsk",
                    "monaco",
                    "moscow",
                    "oslo",
                    "paris",
                    "podgorica",
                    "prague",
                    "riga",
                    "rome",
                    "samara",
                    "san_marino",
                    "sarajevo",
                    "simferopol",
                    "skopje",
                    "sofia",
                    "stockholm",
                    "tallinn",
                    "tirane",
                    "uzhgorod",
                    "vaduz",
                    "vatican",
                    "vienna",
                    "vilnius",
                    "volgograd",
                    "warsaw",
                    "zagreb",
                    "zaporozhye",
                    "zurich"
                ],
                "indian": [
                    "antananarivo",
                    "chagos",
                    "christmas",
                    "cocos",
                    "comoro",
                    "kerguelen",
                    "mahe",
                    "maldives",
                    "mauritius",
                    "mayotte",
                    "reunion"
                ],
                "pacific": [
                    "apia",
                    "auckland",
                    "chatham",
                    "chuuk",
                    "easter",
                    "efate",
                    "enderbury",
                    "fakaofo",
                    "fiji",
                    "funafuti",
                    "galapagos",
                    "gambier",
                    "guadalcanal",
                    "guam",
                    "honolulu",
                    "johnston",
                    "kiritimati",
                    "kosrae",
                    "kwajalein",
                    "majuro",
                    "marquesas",
                    "midway",
                    "nauru",
                    "niue",
                    "norfolk",
                    "noumea",
                    "pago_pago",
                    "palau",
                    "pitcairn",
                    "pohnpei",
                    "port_moresby",
                    "rarotonga",
                    "saipan",
                    "tahiti",
                    "tarawa",
                    "tongatapu",
                    "wake",
                    "wallis"
                ],
                "utc": [
                    ""
                ]
            };

            var tzparts= value.split('/',2);
            var continent=tzparts[0].toLowerCase();
            var city='';
            if (tzparts[1]) {
                city=tzparts[1].toLowerCase();
            }

            return (continent in timezones && ( timezones[continent].length===0 || timezones[continent].indexOf(city)!==-1))
        }
    }
});

/*!
 * Laravel Javascript Validation
 *
 * https://github.com/proengsoft/laravel-jsvalidation
 *
 * Methods that implement Laravel Validations
 *
 * Copyright (c) 2017 Proengsoft
 * Released under the MIT license
 */

$.extend(true, laravelValidation, {

    methods:{

        helpers: laravelValidation.helpers,

        jsRemoteTimer:0,

        /**
         * "Validate" optional attributes.
         * Always returns true, just lets us put sometimes in rules.
         *
         * @return {boolean}
         */
        Sometimes: function() {
            return true;
        },

        /**
         * Bail This is the default behaivour os JSValidation.
         * Always returns true, just lets us put sometimes in rules.
         *
         * @return {boolean}
         */
        Bail: function() {
            return true;
        },

        /**
         * "Indicate" validation should pass if value is null.
         * Always returns true, just lets us put "nullable" in rules.
         *
         * @return {boolean}
         */
        Nullable: function() {
            return true;
        },

        /**
         * Validate the given attribute is filled if it is present.
         */
        Filled: function(value, element) {
            return $.validator.methods.required.call(this, value, element, true);
        },


        /**
         * Validate that a required attribute exists.
         */
        Required: function(value, element) {
            return  $.validator.methods.required.call(this, value, element);
        },

        /**
         * Validate that an attribute exists when any other attribute exists.
         *
         * @return {boolean}
         */
        RequiredWith: function(value, element, params) {
            var validator=this,
                required=false;
            var currentObject=this;

            $.each(params,function(i,param) {
                var target=laravelValidation.helpers.dependentElement(
                    currentObject, element, param
                );
                required=required || (
                    target!==undefined &&
                    $.validator.methods.required.call(
                        validator,
                        currentObject.elementValue(target),
                        target,true
                    ));
            });

            if (required) {
                return  $.validator.methods.required.call(this, value, element, true);
            }
            return true;
        },

        /**
         * Validate that an attribute exists when all other attribute exists.
         *
         * @return {boolean}
         */
        RequiredWithAll: function(value, element, params) {
            var validator=this,
                required=true;
            var currentObject=this;

            $.each(params,function(i,param) {
                var target=laravelValidation.helpers.dependentElement(
                    currentObject, element, param
                );
                required = required && (
                      target!==undefined &&
                      $.validator.methods.required.call(
                          validator,
                          currentObject.elementValue(target),
                          target,true
                      ));
            });

            if (required) {
                return  $.validator.methods.required.call(this, value, element, true);
            }
            return true;
        },

        /**
         * Validate that an attribute exists when any other attribute does not exists.
         *
         * @return {boolean}
         */
        RequiredWithout: function(value, element, params) {
            var validator=this,
                required=false;
            var currentObject=this;

            $.each(params,function(i,param) {
                var target=laravelValidation.helpers.dependentElement(
                    currentObject, element, param
                );
                required = required ||
                    target===undefined||
                    !$.validator.methods.required.call(
                        validator,
                        currentObject.elementValue(target),
                        target,true
                    );
            });

            if (required) {
                return  $.validator.methods.required.call(this, value, element, true);
            }
            return true;
        },

        /**
         * Validate that an attribute exists when all other attribute does not exists.
         *
         * @return {boolean}
         */
        RequiredWithoutAll: function(value, element, params) {
            var validator=this,
                required=true,
                currentObject=this;

            $.each(params,function(i, param) {
                var target=laravelValidation.helpers.dependentElement(
                    currentObject, element, param
                );
                required = required && (
                    target===undefined ||
                    !$.validator.methods.required.call(
                        validator,
                        currentObject.elementValue(target),
                        target,true
                    ));
            });

            if (required) {
                return  $.validator.methods.required.call(this, value, element, true);
            }
            return true;
        },

        /**
         * Validate that an attribute exists when another attribute has a given value.
         *
         * @return {boolean}
         */
        RequiredIf: function(value, element, params) {

            var target=laravelValidation.helpers.dependentElement(
                this, element, params[0]
            );

            if (target!==undefined) {
                var val=String(this.elementValue(target));
                if (typeof val !== 'undefined') {
                    var data = params.slice(1);
                    if ($.inArray(val, data) !== -1) {
                        return $.validator.methods.required.call(
                            this, value, element, true
                        );
                    }
                }
            }

            return true;
        },

        /**
         * Validate that an attribute exists when another
         * attribute does not have a given value.
         *
         * @return {boolean}
         */
        RequiredUnless: function(value, element, params) {

            var target=laravelValidation.helpers.dependentElement(
                this, element, params[0]
            );

            if (target!==undefined) {
                var val=String(this.elementValue(target));
                if (typeof val !== 'undefined') {
                    var data = params.slice(1);
                    if ($.inArray(val, data) !== -1) {
                        return true;
                    }
                }
            }

            return $.validator.methods.required.call(
                this, value, element, true
            );

        },

        /**
         * Validate that an attribute has a matching confirmation.
         *
         * @return {boolean}
         */
        Confirmed: function(value, element, params) {
            return laravelValidation.methods.Same.call(this,value, element, params);
        },

        /**
         * Validate that two attributes match.
         *
         * @return {boolean}
         */
        Same: function(value, element, params) {

            var target=laravelValidation.helpers.dependentElement(
                this, element, params[0]
            );

            if (target!==undefined) {
                return String(value) === String(this.elementValue(target));
            }
            return false;
        },

        /**
         * Validate that the values of an attribute is in another attribute.
         *
         * @param value
         * @param element
         * @param params
         * @returns {boolean}
         * @constructor
         */
        InArray: function (value, element, params) {
            if (typeof params[0] === 'undefined') {
                return false;
            }
            var elements = this.elements();
            var found = false;
            var nameRegExp = laravelValidation.helpers.regexFromWildcard(params[0]);

            for ( var i = 0; i < elements.length ; i++ ) {
                var targetName = elements[i].name;
                if (targetName.match(nameRegExp)) {
                    var equals = laravelValidation.methods.Same.call(this,value, element, [targetName]);
                    found = found || equals;
                }
            }

            return found;
        },

        /**
         * Validate an attribute is unique among other values.
         *
         * @param value
         * @param element
         * @param params
         * @returns {boolean}
         */
        Distinct: function (value, element, params) {
            if (typeof params[0] === 'undefined') {
                return false;
            }

            var elements = this.elements();
            var found = false;
            var nameRegExp = laravelValidation.helpers.regexFromWildcard(params[0]);

            for ( var i = 0; i < elements.length ; i++ ) {
                var targetName = elements[i].name;
                if (targetName !== element.name && targetName.match(nameRegExp)) {
                    var equals = laravelValidation.methods.Same.call(this,value, element, [targetName]);
                    found = found || equals;
                }
            }

            return !found;
        },


        /**
         * Validate that an attribute is different from another attribute.
         *
         * @return {boolean}
         */
        Different: function(value, element, params) {
            return ! laravelValidation.methods.Same.call(this,value, element, params);
        },

        /**
         * Validate that an attribute was "accepted".
         * This validation rule implies the attribute is "required".
         *
         * @return {boolean}
         */
        Accepted: function(value) {
            var regex = new RegExp("^(?:(yes|on|1|true))$",'i');
            return regex.test(value);
        },

        /**
         * Validate that an attribute is an array.
         */
        Array: function(value) {
            return $.isArray(value);
        },

        /**
         * Validate that an attribute is a boolean.
         *
         * @return {boolean}
         */
        Boolean: function(value) {
            var regex= new RegExp("^(?:(true|false|1|0))$",'i');
            return  regex.test(value);
        },

        /**
         * Validate that an attribute is an integer.
         *
         * @return {boolean}
         */
        Integer: function(value) {
            var regex= new RegExp("^(?:-?\\d+)$",'i');
            return  regex.test(value);
        },

        /**
         * Validate that an attribute is numeric.
         */
        Numeric: function(value, element) {
            return $.validator.methods.number.call(this, value, element, true);
        },

        /**
         * Validate that an attribute is a string.
         *
         * @return {boolean}
         */
        String: function(value) {
            return typeof value === 'string';
        },

        /**
         * The field under validation must be numeric and must have an exact length of value.
         */
        Digits: function(value, element, params) {
            return (
                $.validator.methods.number.call(this, value, element, true) &&
                value.length === parseInt(params, 10)
            );
        },

        /**
         * The field under validation must have a length between the given min and max.
         */
        DigitsBetween: function(value, element, params) {
            return ($.validator.methods.number.call(this, value, element, true)
                && value.length>=parseFloat(params[0]) && value.length<=parseFloat(params[1]));
        },

        /**
         * Validate the size of an attribute.
         *
         * @return {boolean}
         */
        Size: function(value, element, params) {
            return laravelValidation.helpers.getSize(this, element,value) === parseFloat(params[0]);
        },

        /**
         * Validate the size of an attribute is between a set of values.
         *
         * @return {boolean}
         */
        Between: function(value, element, params) {
            return ( laravelValidation.helpers.getSize(this, element,value) >= parseFloat(params[0]) &&
                laravelValidation.helpers.getSize(this,element,value) <= parseFloat(params[1]));
        },

        /**
         * Validate the size of an attribute is greater than a minimum value.
         *
         * @return {boolean}
         */
        Min: function(value, element, params) {
            return laravelValidation.helpers.getSize(this, element,value) >= parseFloat(params[0]);
        },

        /**
         * Validate the size of an attribute is less than a maximum value.
         *
         * @return {boolean}
         */
        Max: function(value, element, params) {
            return laravelValidation.helpers.getSize(this, element,value) <= parseFloat(params[0]);
        },

        /**
         * Validate an attribute is contained within a list of values.
         *
         * @return {boolean}
         */
        In: function(value, element, params) {
            if ($.isArray(value) && laravelValidation.helpers.hasRules(element, "Array")) {
                var diff = laravelValidation.helpers.arrayDiff(value, params);
                return Object.keys(diff).length === 0;
            }
            return params.indexOf(value.toString()) !== -1;
        },

        /**
         * Validate an attribute is not contained within a list of values.
         *
         * @return {boolean}
         */
        NotIn: function(value, element, params) {
            return params.indexOf(value.toString()) === -1;
        },

        /**
         * Validate that an attribute is a valid IP.
         *
         * @return {boolean}
         */
        Ip: function(value) {
            return /^(25[0-5]|2[0-4]\d|[01]?\d\d?)\.(25[0-5]|2[0-4]\d|[01]?\d\d?)\.(25[0-5]|2[0-4]\d|[01]?\d\d?)\.(25[0-5]|2[0-4]\d|[01]?\d\d?)$/i.test(value) ||
                /^((([0-9A-Fa-f]{1,4}:){7}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){6}:[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){5}:([0-9A-Fa-f]{1,4}:)?[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){4}:([0-9A-Fa-f]{1,4}:){0,2}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){3}:([0-9A-Fa-f]{1,4}:){0,3}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){2}:([0-9A-Fa-f]{1,4}:){0,4}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){6}((\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b)\.){3}(\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b))|(([0-9A-Fa-f]{1,4}:){0,5}:((\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b)\.){3}(\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b))|(::([0-9A-Fa-f]{1,4}:){0,5}((\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b)\.){3}(\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b))|([0-9A-Fa-f]{1,4}::([0-9A-Fa-f]{1,4}:){0,5}[0-9A-Fa-f]{1,4})|(::([0-9A-Fa-f]{1,4}:){0,6}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){1,7}:))$/i.test(value);
        },

        /**
         * Validate that an attribute is a valid e-mail address.
         */
        Email: function(value, element) {
            return $.validator.methods.email.call(this, value, element, true);
        },

        /**
         * Validate that an attribute is a valid URL.
         */
        Url: function(value, element) {
            return $.validator.methods.url.call(this, value, element, true);
        },

        /**
         * The field under validation must be a successfully uploaded file.
         *
         * @return {boolean}
         */
        File: function(value, element) {
            if (!window.File || !window.FileReader || !window.FileList || !window.Blob) {
                return true;
            }
            if ('files' in element ) {
                return (element.files.length > 0);
            }
            return false;
        },

        /**
         * Validate the MIME type of a file upload attribute is in a set of MIME types.
         *
         * @return {boolean}
         */
        Mimes: function(value, element, params) {
            if (!window.File || !window.FileReader || !window.FileList || !window.Blob) {
                return true;
            }
            var lowerParams = $.map(params, function(item) {
                return item.toLowerCase();
            });

            var fileinfo = laravelValidation.helpers.fileinfo(element);
            return (fileinfo !== false && lowerParams.indexOf(fileinfo.extension.toLowerCase())!==-1);
        },

        /**
         * The file under validation must match one of the given MIME types.
         *
         * @return {boolean}
         */
        Mimetypes: function(value, element, params) {
            if (!window.File || !window.FileReader || !window.FileList || !window.Blob) {
                return true;
            }
            var lowerParams = $.map(params, function(item) {
                return item.toLowerCase();
            });

            var fileinfo = laravelValidation.helpers.fileinfo(element);

            if (fileinfo === false) {
                return false;
            }
            return (lowerParams.indexOf(fileinfo.type.toLowerCase())!==-1);
        },

        /**
         * Validate the MIME type of a file upload attribute is in a set of MIME types.
         */
        Image: function(value, element) {
            return laravelValidation.methods.Mimes.call(this, value, element, [
                'jpg', 'png', 'gif', 'bmp', 'svg', 'jpeg'
            ]);
        },

        /**
         * Validate dimensions of Image.
         *
         * @return {boolean|string}
         */
        Dimensions: function(value, element, params, callback) {
            if (!window.File || !window.FileReader || !window.FileList || !window.Blob) {
                return true;
            }
            if (element.files === null || typeof element.files[0] === 'undefined') {
                return false;
            }

            var fr = new FileReader;
            fr.onload = function () {
                var img = new Image();
                img.onload = function () {
                    var height = parseFloat(img.naturalHeight);
                    var width = parseFloat(img.naturalWidth);
                    var ratio = width / height;
                    var notValid = ((params['width']) && parseFloat(params['width'] !== width)) ||
                        ((params['min_width']) && parseFloat(params['min_width']) > width) ||
                        ((params['max_width']) && parseFloat(params['max_width']) < width) ||
                        ((params['height']) && parseFloat(params['height']) !== height) ||
                        ((params['min_height']) && parseFloat(params['min_height']) > height) ||
                        ((params['max_height']) && parseFloat(params['max_height']) < height) ||
                        ((params['ratio']) && ratio !== parseFloat(eval(params['ratio']))
                        );
                    callback(! notValid);
                };
                img.onerror = function() {
                    callback(false);
                };
                img.src = fr.result;
            };
            fr.readAsDataURL(element.files[0]);

            return 'pending';
        },

        /**
         * Validate that an attribute contains only alphabetic characters.
         *
         * @return {boolean}
         */
        Alpha: function(value) {
            if (typeof  value !== 'string') {
                return false;
            }

            var regex = new RegExp("^(?:^[a-z\u00E0-\u00FC]+$)$",'i');
            return  regex.test(value);

        },

        /**
         * Validate that an attribute contains only alpha-numeric characters.
         *
         * @return {boolean}
         */
        AlphaNum: function(value) {
            if (typeof  value !== 'string') {
                return false;
            }
            var regex = new RegExp("^(?:^[a-z0-9\u00E0-\u00FC]+$)$",'i');
            return regex.test(value);
        },

        /**
         * Validate that an attribute contains only alphabetic characters.
         *
         * @return {boolean}
         */
        AlphaDash: function(value) {
            if (typeof  value !== 'string') {
                return false;
            }
            var regex = new RegExp("^(?:^[a-z0-9\u00E0-\u00FC_-]+$)$",'i');
            return regex.test(value);
        },

        /**
         * Validate that an attribute passes a regular expression check.
         *
         * @return {boolean}
         */
        Regex: function(value, element, params) {
            var invalidModifiers=['x','s','u','X','U','A'];
            // Converting php regular expression
            var phpReg= new RegExp('^(?:\/)(.*\\\/?[^\/]*|[^\/]*)(?:\/)([gmixXsuUAJ]*)?$');
            var matches=params[0].match(phpReg);
            if (matches === null) {
                return false;
            }
            // checking modifiers
            var php_modifiers=[];
            if (matches[2]!==undefined) {
                php_modifiers=matches[2].split('');
                for (var i=0; i<php_modifiers.length<i ;i++) {
                    if (invalidModifiers.indexOf(php_modifiers[i])!==-1) {
                        return true;
                    }
                }
            }
            var regex = new RegExp("^(?:"+matches[1]+")$",php_modifiers.join());
            return   regex.test(value);
        },

        /**
         * Validate that an attribute is a valid date.
         *
         * @return {boolean}
         */
        Date: function(value) {
            return (laravelValidation.helpers.strtotime(value)!==false);
        },

        /**
         * Validate that an attribute matches a date format.
         *
         * @return {boolean}
         */
        DateFormat: function(value, element, params) {
            return laravelValidation.helpers.parseTime(value,params[0])!==false;
        },

        /**
         * Validate the date is before a given date.
         *
         * @return {boolean}
         */
        Before: function(value, element, params) {

            var timeCompare=parseFloat(params);
            if (isNaN(timeCompare)) {
                var target=laravelValidation.helpers.dependentElement(this, element, params);
                if (target===undefined) {
                    return false;
                }
                timeCompare= laravelValidation.helpers.parseTime(this.elementValue(target), target);
            }

            var timeValue=laravelValidation.helpers.parseTime(value, element);
            return  (timeValue !==false && timeValue < timeCompare);

        },

        /**
         * Validate the date is after a given date.
         *
         * @return {boolean}
         */
        After: function(value, element, params) {
            var timeCompare=parseFloat(params);
            if (isNaN(timeCompare)) {
                var target=laravelValidation.helpers.dependentElement(this, element, params);
                if (target===undefined) {
                    return false;
                }
                timeCompare= laravelValidation.helpers.parseTime(this.elementValue(target), target);
            }

            var timeValue=laravelValidation.helpers.parseTime(value, element);
            return  (timeValue !==false && timeValue > timeCompare);

        },


        /**
         * Validate that an attribute is a valid date.
         */
        Timezone: function(value) {
            return  laravelValidation.helpers.isTimezone(value);
        },


        /**
         * Validate the attribute is a valid JSON string.
         *
         * @param  value
         * @return bool
         */
        Json: function(value) {
            var result = true;
            try {
                JSON.parse(value);
            } catch (e) {
                result = false;
            }
            return result;
        }
    }
});

//# sourceMappingURL=jsvalidation.js.map

/*!
 * Datepicker for Bootstrap v1.6.4 (https://github.com/eternicode/bootstrap-datepicker)
 *
 * Copyright 2012 Stefan Petre
 * Improvements by Andrew Rowls
 * Licensed under the Apache License v2.0 (http://www.apache.org/licenses/LICENSE-2.0)
 */
!function(a){"function"==typeof define&&define.amd?define(["jquery"],a):a("object"==typeof exports?require("jquery"):jQuery)}(function(a,b){function c(){return new Date(Date.UTC.apply(Date,arguments))}function d(){var a=new Date;return c(a.getFullYear(),a.getMonth(),a.getDate())}function e(a,b){return a.getUTCFullYear()===b.getUTCFullYear()&&a.getUTCMonth()===b.getUTCMonth()&&a.getUTCDate()===b.getUTCDate()}function f(a){return function(){return this[a].apply(this,arguments)}}function g(a){return a&&!isNaN(a.getTime())}function h(b,c){function d(a,b){return b.toLowerCase()}var e,f=a(b).data(),g={},h=new RegExp("^"+c.toLowerCase()+"([A-Z])");c=new RegExp("^"+c.toLowerCase());for(var i in f)c.test(i)&&(e=i.replace(h,d),g[e]=f[i]);return g}function i(b){var c={};if(q[b]||(b=b.split("-")[0],q[b])){var d=q[b];return a.each(p,function(a,b){b in d&&(c[b]=d[b])}),c}}var j=function(){var b={get:function(a){return this.slice(a)[0]},contains:function(a){for(var b=a&&a.valueOf(),c=0,d=this.length;d>c;c++)if(this[c].valueOf()===b)return c;return-1},remove:function(a){this.splice(a,1)},replace:function(b){b&&(a.isArray(b)||(b=[b]),this.clear(),this.push.apply(this,b))},clear:function(){this.length=0},copy:function(){var a=new j;return a.replace(this),a}};return function(){var c=[];return c.push.apply(c,arguments),a.extend(c,b),c}}(),k=function(b,c){a(b).data("datepicker",this),this._process_options(c),this.dates=new j,this.viewDate=this.o.defaultViewDate,this.focusDate=null,this.element=a(b),this.isInput=this.element.is("input"),this.inputField=this.isInput?this.element:this.element.find("input"),this.component=this.element.hasClass("date")?this.element.find(".add-on, .input-group-addon, .btn"):!1,this.hasInput=this.component&&this.inputField.length,this.component&&0===this.component.length&&(this.component=!1),this.isInline=!this.component&&this.element.is("div"),this.picker=a(r.template),this._check_template(this.o.templates.leftArrow)&&this.picker.find(".prev").html(this.o.templates.leftArrow),this._check_template(this.o.templates.rightArrow)&&this.picker.find(".next").html(this.o.templates.rightArrow),this._buildEvents(),this._attachEvents(),this.isInline?this.picker.addClass("datepicker-inline").appendTo(this.element):this.picker.addClass("datepicker-dropdown dropdown-menu"),this.o.rtl&&this.picker.addClass("datepicker-rtl"),this.viewMode=this.o.startView,this.o.calendarWeeks&&this.picker.find("thead .datepicker-title, tfoot .today, tfoot .clear").attr("colspan",function(a,b){return parseInt(b)+1}),this._allow_update=!1,this.setStartDate(this._o.startDate),this.setEndDate(this._o.endDate),this.setDaysOfWeekDisabled(this.o.daysOfWeekDisabled),this.setDaysOfWeekHighlighted(this.o.daysOfWeekHighlighted),this.setDatesDisabled(this.o.datesDisabled),this.fillDow(),this.fillMonths(),this._allow_update=!0,this.update(),this.showMode(),this.isInline&&this.show()};k.prototype={constructor:k,_resolveViewName:function(a,c){return 0===a||"days"===a||"month"===a?0:1===a||"months"===a||"year"===a?1:2===a||"years"===a||"decade"===a?2:3===a||"decades"===a||"century"===a?3:4===a||"centuries"===a||"millennium"===a?4:c===b?!1:c},_check_template:function(c){try{if(c===b||""===c)return!1;if((c.match(/[<>]/g)||[]).length<=0)return!0;var d=a(c);return d.length>0}catch(e){return!1}},_process_options:function(b){this._o=a.extend({},this._o,b);var e=this.o=a.extend({},this._o),f=e.language;q[f]||(f=f.split("-")[0],q[f]||(f=o.language)),e.language=f,e.startView=this._resolveViewName(e.startView,0),e.minViewMode=this._resolveViewName(e.minViewMode,0),e.maxViewMode=this._resolveViewName(e.maxViewMode,4),e.startView=Math.min(e.startView,e.maxViewMode),e.startView=Math.max(e.startView,e.minViewMode),e.multidate!==!0&&(e.multidate=Number(e.multidate)||!1,e.multidate!==!1&&(e.multidate=Math.max(0,e.multidate))),e.multidateSeparator=String(e.multidateSeparator),e.weekStart%=7,e.weekEnd=(e.weekStart+6)%7;var g=r.parseFormat(e.format);e.startDate!==-(1/0)&&(e.startDate?e.startDate instanceof Date?e.startDate=this._local_to_utc(this._zero_time(e.startDate)):e.startDate=r.parseDate(e.startDate,g,e.language,e.assumeNearbyYear):e.startDate=-(1/0)),e.endDate!==1/0&&(e.endDate?e.endDate instanceof Date?e.endDate=this._local_to_utc(this._zero_time(e.endDate)):e.endDate=r.parseDate(e.endDate,g,e.language,e.assumeNearbyYear):e.endDate=1/0),e.daysOfWeekDisabled=e.daysOfWeekDisabled||[],a.isArray(e.daysOfWeekDisabled)||(e.daysOfWeekDisabled=e.daysOfWeekDisabled.split(/[,\s]*/)),e.daysOfWeekDisabled=a.map(e.daysOfWeekDisabled,function(a){return parseInt(a,10)}),e.daysOfWeekHighlighted=e.daysOfWeekHighlighted||[],a.isArray(e.daysOfWeekHighlighted)||(e.daysOfWeekHighlighted=e.daysOfWeekHighlighted.split(/[,\s]*/)),e.daysOfWeekHighlighted=a.map(e.daysOfWeekHighlighted,function(a){return parseInt(a,10)}),e.datesDisabled=e.datesDisabled||[],a.isArray(e.datesDisabled)||(e.datesDisabled=[e.datesDisabled]),e.datesDisabled=a.map(e.datesDisabled,function(a){return r.parseDate(a,g,e.language,e.assumeNearbyYear)});var h=String(e.orientation).toLowerCase().split(/\s+/g),i=e.orientation.toLowerCase();if(h=a.grep(h,function(a){return/^auto|left|right|top|bottom$/.test(a)}),e.orientation={x:"auto",y:"auto"},i&&"auto"!==i)if(1===h.length)switch(h[0]){case"top":case"bottom":e.orientation.y=h[0];break;case"left":case"right":e.orientation.x=h[0]}else i=a.grep(h,function(a){return/^left|right$/.test(a)}),e.orientation.x=i[0]||"auto",i=a.grep(h,function(a){return/^top|bottom$/.test(a)}),e.orientation.y=i[0]||"auto";else;if(e.defaultViewDate){var j=e.defaultViewDate.year||(new Date).getFullYear(),k=e.defaultViewDate.month||0,l=e.defaultViewDate.day||1;e.defaultViewDate=c(j,k,l)}else e.defaultViewDate=d()},_events:[],_secondaryEvents:[],_applyEvents:function(a){for(var c,d,e,f=0;f<a.length;f++)c=a[f][0],2===a[f].length?(d=b,e=a[f][1]):3===a[f].length&&(d=a[f][1],e=a[f][2]),c.on(e,d)},_unapplyEvents:function(a){for(var c,d,e,f=0;f<a.length;f++)c=a[f][0],2===a[f].length?(e=b,d=a[f][1]):3===a[f].length&&(e=a[f][1],d=a[f][2]),c.off(d,e)},_buildEvents:function(){var b={keyup:a.proxy(function(b){-1===a.inArray(b.keyCode,[27,37,39,38,40,32,13,9])&&this.update()},this),keydown:a.proxy(this.keydown,this),paste:a.proxy(this.paste,this)};this.o.showOnFocus===!0&&(b.focus=a.proxy(this.show,this)),this.isInput?this._events=[[this.element,b]]:this.component&&this.hasInput?this._events=[[this.inputField,b],[this.component,{click:a.proxy(this.show,this)}]]:this._events=[[this.element,{click:a.proxy(this.show,this),keydown:a.proxy(this.keydown,this)}]],this._events.push([this.element,"*",{blur:a.proxy(function(a){this._focused_from=a.target},this)}],[this.element,{blur:a.proxy(function(a){this._focused_from=a.target},this)}]),this.o.immediateUpdates&&this._events.push([this.element,{"changeYear changeMonth":a.proxy(function(a){this.update(a.date)},this)}]),this._secondaryEvents=[[this.picker,{click:a.proxy(this.click,this)}],[a(window),{resize:a.proxy(this.place,this)}],[a(document),{mousedown:a.proxy(function(a){this.element.is(a.target)||this.element.find(a.target).length||this.picker.is(a.target)||this.picker.find(a.target).length||this.isInline||this.hide()},this)}]]},_attachEvents:function(){this._detachEvents(),this._applyEvents(this._events)},_detachEvents:function(){this._unapplyEvents(this._events)},_attachSecondaryEvents:function(){this._detachSecondaryEvents(),this._applyEvents(this._secondaryEvents)},_detachSecondaryEvents:function(){this._unapplyEvents(this._secondaryEvents)},_trigger:function(b,c){var d=c||this.dates.get(-1),e=this._utc_to_local(d);this.element.trigger({type:b,date:e,dates:a.map(this.dates,this._utc_to_local),format:a.proxy(function(a,b){0===arguments.length?(a=this.dates.length-1,b=this.o.format):"string"==typeof a&&(b=a,a=this.dates.length-1),b=b||this.o.format;var c=this.dates.get(a);return r.formatDate(c,b,this.o.language)},this)})},show:function(){return this.inputField.prop("disabled")||this.inputField.prop("readonly")&&this.o.enableOnReadonly===!1?void 0:(this.isInline||this.picker.appendTo(this.o.container),this.place(),this.picker.show(),this._attachSecondaryEvents(),this._trigger("show"),(window.navigator.msMaxTouchPoints||"ontouchstart"in document)&&this.o.disableTouchKeyboard&&a(this.element).blur(),this)},hide:function(){return this.isInline||!this.picker.is(":visible")?this:(this.focusDate=null,this.picker.hide().detach(),this._detachSecondaryEvents(),this.viewMode=this.o.startView,this.showMode(),this.o.forceParse&&this.inputField.val()&&this.setValue(),this._trigger("hide"),this)},destroy:function(){return this.hide(),this._detachEvents(),this._detachSecondaryEvents(),this.picker.remove(),delete this.element.data().datepicker,this.isInput||delete this.element.data().date,this},paste:function(b){var c;if(b.originalEvent.clipboardData&&b.originalEvent.clipboardData.types&&-1!==a.inArray("text/plain",b.originalEvent.clipboardData.types))c=b.originalEvent.clipboardData.getData("text/plain");else{if(!window.clipboardData)return;c=window.clipboardData.getData("Text")}this.setDate(c),this.update(),b.preventDefault()},_utc_to_local:function(a){return a&&new Date(a.getTime()+6e4*a.getTimezoneOffset())},_local_to_utc:function(a){return a&&new Date(a.getTime()-6e4*a.getTimezoneOffset())},_zero_time:function(a){return a&&new Date(a.getFullYear(),a.getMonth(),a.getDate())},_zero_utc_time:function(a){return a&&new Date(Date.UTC(a.getUTCFullYear(),a.getUTCMonth(),a.getUTCDate()))},getDates:function(){return a.map(this.dates,this._utc_to_local)},getUTCDates:function(){return a.map(this.dates,function(a){return new Date(a)})},getDate:function(){return this._utc_to_local(this.getUTCDate())},getUTCDate:function(){var a=this.dates.get(-1);return"undefined"!=typeof a?new Date(a):null},clearDates:function(){this.inputField&&this.inputField.val(""),this.update(),this._trigger("changeDate"),this.o.autoclose&&this.hide()},setDates:function(){var b=a.isArray(arguments[0])?arguments[0]:arguments;return this.update.apply(this,b),this._trigger("changeDate"),this.setValue(),this},setUTCDates:function(){var b=a.isArray(arguments[0])?arguments[0]:arguments;return this.update.apply(this,a.map(b,this._utc_to_local)),this._trigger("changeDate"),this.setValue(),this},setDate:f("setDates"),setUTCDate:f("setUTCDates"),remove:f("destroy"),setValue:function(){var a=this.getFormattedDate();return this.inputField.val(a),this},getFormattedDate:function(c){c===b&&(c=this.o.format);var d=this.o.language;return a.map(this.dates,function(a){return r.formatDate(a,c,d)}).join(this.o.multidateSeparator)},getStartDate:function(){return this.o.startDate},setStartDate:function(a){return this._process_options({startDate:a}),this.update(),this.updateNavArrows(),this},getEndDate:function(){return this.o.endDate},setEndDate:function(a){return this._process_options({endDate:a}),this.update(),this.updateNavArrows(),this},setDaysOfWeekDisabled:function(a){return this._process_options({daysOfWeekDisabled:a}),this.update(),this.updateNavArrows(),this},setDaysOfWeekHighlighted:function(a){return this._process_options({daysOfWeekHighlighted:a}),this.update(),this},setDatesDisabled:function(a){this._process_options({datesDisabled:a}),this.update(),this.updateNavArrows()},place:function(){if(this.isInline)return this;var b=this.picker.outerWidth(),c=this.picker.outerHeight(),d=10,e=a(this.o.container),f=e.width(),g="body"===this.o.container?a(document).scrollTop():e.scrollTop(),h=e.offset(),i=[];this.element.parents().each(function(){var b=a(this).css("z-index");"auto"!==b&&0!==b&&i.push(parseInt(b))});var j=Math.max.apply(Math,i)+this.o.zIndexOffset,k=this.component?this.component.parent().offset():this.element.offset(),l=this.component?this.component.outerHeight(!0):this.element.outerHeight(!1),m=this.component?this.component.outerWidth(!0):this.element.outerWidth(!1),n=k.left-h.left,o=k.top-h.top;"body"!==this.o.container&&(o+=g),this.picker.removeClass("datepicker-orient-top datepicker-orient-bottom datepicker-orient-right datepicker-orient-left"),"auto"!==this.o.orientation.x?(this.picker.addClass("datepicker-orient-"+this.o.orientation.x),"right"===this.o.orientation.x&&(n-=b-m)):k.left<0?(this.picker.addClass("datepicker-orient-left"),n-=k.left-d):n+b>f?(this.picker.addClass("datepicker-orient-right"),n+=m-b):this.picker.addClass("datepicker-orient-left");var p,q=this.o.orientation.y;if("auto"===q&&(p=-g+o-c,q=0>p?"bottom":"top"),this.picker.addClass("datepicker-orient-"+q),"top"===q?o-=c+parseInt(this.picker.css("padding-top")):o+=l,this.o.rtl){var r=f-(n+m);this.picker.css({top:o,right:r,zIndex:j})}else this.picker.css({top:o,left:n,zIndex:j});return this},_allow_update:!0,update:function(){if(!this._allow_update)return this;var b=this.dates.copy(),c=[],d=!1;return arguments.length?(a.each(arguments,a.proxy(function(a,b){b instanceof Date&&(b=this._local_to_utc(b)),c.push(b)},this)),d=!0):(c=this.isInput?this.element.val():this.element.data("date")||this.inputField.val(),c=c&&this.o.multidate?c.split(this.o.multidateSeparator):[c],delete this.element.data().date),c=a.map(c,a.proxy(function(a){return r.parseDate(a,this.o.format,this.o.language,this.o.assumeNearbyYear)},this)),c=a.grep(c,a.proxy(function(a){return!this.dateWithinRange(a)||!a},this),!0),this.dates.replace(c),this.dates.length?this.viewDate=new Date(this.dates.get(-1)):this.viewDate<this.o.startDate?this.viewDate=new Date(this.o.startDate):this.viewDate>this.o.endDate?this.viewDate=new Date(this.o.endDate):this.viewDate=this.o.defaultViewDate,d?this.setValue():c.length&&String(b)!==String(this.dates)&&this._trigger("changeDate"),!this.dates.length&&b.length&&this._trigger("clearDate"),this.fill(),this.element.change(),this},fillDow:function(){var b=this.o.weekStart,c="<tr>";for(this.o.calendarWeeks&&(this.picker.find(".datepicker-days .datepicker-switch").attr("colspan",function(a,b){return parseInt(b)+1}),c+='<th class="cw">&#160;</th>');b<this.o.weekStart+7;)c+='<th class="dow',a.inArray(b,this.o.daysOfWeekDisabled)>-1&&(c+=" disabled"),c+='">'+q[this.o.language].daysMin[b++%7]+"</th>";c+="</tr>",this.picker.find(".datepicker-days thead").append(c)},fillMonths:function(){for(var a=this._utc_to_local(this.viewDate),b="",c=0;12>c;){var d=a&&a.getMonth()===c?" focused":"";b+='<span class="month'+d+'">'+q[this.o.language].monthsShort[c++]+"</span>"}this.picker.find(".datepicker-months td").html(b)},setRange:function(b){b&&b.length?this.range=a.map(b,function(a){return a.valueOf()}):delete this.range,this.fill()},getClassNames:function(b){var c=[],d=this.viewDate.getUTCFullYear(),e=this.viewDate.getUTCMonth(),f=new Date;return b.getUTCFullYear()<d||b.getUTCFullYear()===d&&b.getUTCMonth()<e?c.push("old"):(b.getUTCFullYear()>d||b.getUTCFullYear()===d&&b.getUTCMonth()>e)&&c.push("new"),this.focusDate&&b.valueOf()===this.focusDate.valueOf()&&c.push("focused"),this.o.todayHighlight&&b.getUTCFullYear()===f.getFullYear()&&b.getUTCMonth()===f.getMonth()&&b.getUTCDate()===f.getDate()&&c.push("today"),-1!==this.dates.contains(b)&&c.push("active"),this.dateWithinRange(b)||c.push("disabled"),this.dateIsDisabled(b)&&c.push("disabled","disabled-date"),-1!==a.inArray(b.getUTCDay(),this.o.daysOfWeekHighlighted)&&c.push("highlighted"),this.range&&(b>this.range[0]&&b<this.range[this.range.length-1]&&c.push("range"),-1!==a.inArray(b.valueOf(),this.range)&&c.push("selected"),b.valueOf()===this.range[0]&&c.push("range-start"),b.valueOf()===this.range[this.range.length-1]&&c.push("range-end")),c},_fill_yearsView:function(c,d,e,f,g,h,i,j){var k,l,m,n,o,p,q,r,s,t,u;for(k="",l=this.picker.find(c),m=parseInt(g/e,10)*e,o=parseInt(h/f,10)*f,p=parseInt(i/f,10)*f,n=a.map(this.dates,function(a){return parseInt(a.getUTCFullYear()/f,10)*f}),l.find(".datepicker-switch").text(m+"-"+(m+9*f)),q=m-f,r=-1;11>r;r+=1)s=[d],t=null,-1===r?s.push("old"):10===r&&s.push("new"),-1!==a.inArray(q,n)&&s.push("active"),(o>q||q>p)&&s.push("disabled"),q===this.viewDate.getFullYear()&&s.push("focused"),j!==a.noop&&(u=j(new Date(q,0,1)),u===b?u={}:"boolean"==typeof u?u={enabled:u}:"string"==typeof u&&(u={classes:u}),u.enabled===!1&&s.push("disabled"),u.classes&&(s=s.concat(u.classes.split(/\s+/))),u.tooltip&&(t=u.tooltip)),k+='<span class="'+s.join(" ")+'"'+(t?' title="'+t+'"':"")+">"+q+"</span>",q+=f;l.find("td").html(k)},fill:function(){var d,e,f=new Date(this.viewDate),g=f.getUTCFullYear(),h=f.getUTCMonth(),i=this.o.startDate!==-(1/0)?this.o.startDate.getUTCFullYear():-(1/0),j=this.o.startDate!==-(1/0)?this.o.startDate.getUTCMonth():-(1/0),k=this.o.endDate!==1/0?this.o.endDate.getUTCFullYear():1/0,l=this.o.endDate!==1/0?this.o.endDate.getUTCMonth():1/0,m=q[this.o.language].today||q.en.today||"",n=q[this.o.language].clear||q.en.clear||"",o=q[this.o.language].titleFormat||q.en.titleFormat;if(!isNaN(g)&&!isNaN(h)){this.picker.find(".datepicker-days .datepicker-switch").text(r.formatDate(f,o,this.o.language)),this.picker.find("tfoot .today").text(m).toggle(this.o.todayBtn!==!1),this.picker.find("tfoot .clear").text(n).toggle(this.o.clearBtn!==!1),this.picker.find("thead .datepicker-title").text(this.o.title).toggle(""!==this.o.title),this.updateNavArrows(),this.fillMonths();var p=c(g,h-1,28),s=r.getDaysInMonth(p.getUTCFullYear(),p.getUTCMonth());p.setUTCDate(s),p.setUTCDate(s-(p.getUTCDay()-this.o.weekStart+7)%7);var t=new Date(p);p.getUTCFullYear()<100&&t.setUTCFullYear(p.getUTCFullYear()),t.setUTCDate(t.getUTCDate()+42),t=t.valueOf();for(var u,v=[];p.valueOf()<t;){if(p.getUTCDay()===this.o.weekStart&&(v.push("<tr>"),this.o.calendarWeeks)){var w=new Date(+p+(this.o.weekStart-p.getUTCDay()-7)%7*864e5),x=new Date(Number(w)+(11-w.getUTCDay())%7*864e5),y=new Date(Number(y=c(x.getUTCFullYear(),0,1))+(11-y.getUTCDay())%7*864e5),z=(x-y)/864e5/7+1;v.push('<td class="cw">'+z+"</td>")}u=this.getClassNames(p),u.push("day"),this.o.beforeShowDay!==a.noop&&(e=this.o.beforeShowDay(this._utc_to_local(p)),e===b?e={}:"boolean"==typeof e?e={enabled:e}:"string"==typeof e&&(e={classes:e}),e.enabled===!1&&u.push("disabled"),e.classes&&(u=u.concat(e.classes.split(/\s+/))),e.tooltip&&(d=e.tooltip)),u=a.isFunction(a.uniqueSort)?a.uniqueSort(u):a.unique(u),v.push('<td class="'+u.join(" ")+'"'+(d?' title="'+d+'"':"")+">"+p.getUTCDate()+"</td>"),d=null,p.getUTCDay()===this.o.weekEnd&&v.push("</tr>"),p.setUTCDate(p.getUTCDate()+1)}this.picker.find(".datepicker-days tbody").empty().append(v.join(""));var A=q[this.o.language].monthsTitle||q.en.monthsTitle||"Months",B=this.picker.find(".datepicker-months").find(".datepicker-switch").text(this.o.maxViewMode<2?A:g).end().find("span").removeClass("active");if(a.each(this.dates,function(a,b){b.getUTCFullYear()===g&&B.eq(b.getUTCMonth()).addClass("active")}),(i>g||g>k)&&B.addClass("disabled"),g===i&&B.slice(0,j).addClass("disabled"),g===k&&B.slice(l+1).addClass("disabled"),this.o.beforeShowMonth!==a.noop){var C=this;a.each(B,function(c,d){var e=new Date(g,c,1),f=C.o.beforeShowMonth(e);f===b?f={}:"boolean"==typeof f?f={enabled:f}:"string"==typeof f&&(f={classes:f}),f.enabled!==!1||a(d).hasClass("disabled")||a(d).addClass("disabled"),f.classes&&a(d).addClass(f.classes),f.tooltip&&a(d).prop("title",f.tooltip)})}this._fill_yearsView(".datepicker-years","year",10,1,g,i,k,this.o.beforeShowYear),this._fill_yearsView(".datepicker-decades","decade",100,10,g,i,k,this.o.beforeShowDecade),this._fill_yearsView(".datepicker-centuries","century",1e3,100,g,i,k,this.o.beforeShowCentury)}},updateNavArrows:function(){if(this._allow_update){var a=new Date(this.viewDate),b=a.getUTCFullYear(),c=a.getUTCMonth();switch(this.viewMode){case 0:this.o.startDate!==-(1/0)&&b<=this.o.startDate.getUTCFullYear()&&c<=this.o.startDate.getUTCMonth()?this.picker.find(".prev").css({visibility:"hidden"}):this.picker.find(".prev").css({visibility:"visible"}),this.o.endDate!==1/0&&b>=this.o.endDate.getUTCFullYear()&&c>=this.o.endDate.getUTCMonth()?this.picker.find(".next").css({visibility:"hidden"}):this.picker.find(".next").css({visibility:"visible"});break;case 1:case 2:case 3:case 4:this.o.startDate!==-(1/0)&&b<=this.o.startDate.getUTCFullYear()||this.o.maxViewMode<2?this.picker.find(".prev").css({visibility:"hidden"}):this.picker.find(".prev").css({visibility:"visible"}),this.o.endDate!==1/0&&b>=this.o.endDate.getUTCFullYear()||this.o.maxViewMode<2?this.picker.find(".next").css({visibility:"hidden"}):this.picker.find(".next").css({visibility:"visible"})}}},click:function(b){b.preventDefault(),b.stopPropagation();var e,f,g,h,i,j,k;e=a(b.target),e.hasClass("datepicker-switch")&&this.showMode(1);var l=e.closest(".prev, .next");l.length>0&&(f=r.modes[this.viewMode].navStep*(l.hasClass("prev")?-1:1),0===this.viewMode?(this.viewDate=this.moveMonth(this.viewDate,f),this._trigger("changeMonth",this.viewDate)):(this.viewDate=this.moveYear(this.viewDate,f),1===this.viewMode&&this._trigger("changeYear",this.viewDate)),this.fill()),e.hasClass("today")&&!e.hasClass("day")&&(this.showMode(-2),this._setDate(d(),"linked"===this.o.todayBtn?null:"view")),e.hasClass("clear")&&this.clearDates(),e.hasClass("disabled")||(e.hasClass("day")&&(g=parseInt(e.text(),10)||1,h=this.viewDate.getUTCFullYear(),i=this.viewDate.getUTCMonth(),e.hasClass("old")&&(0===i?(i=11,h-=1,j=!0,k=!0):(i-=1,j=!0)),e.hasClass("new")&&(11===i?(i=0,h+=1,j=!0,k=!0):(i+=1,j=!0)),this._setDate(c(h,i,g)),k&&this._trigger("changeYear",this.viewDate),j&&this._trigger("changeMonth",this.viewDate)),e.hasClass("month")&&(this.viewDate.setUTCDate(1),g=1,i=e.parent().find("span").index(e),h=this.viewDate.getUTCFullYear(),this.viewDate.setUTCMonth(i),this._trigger("changeMonth",this.viewDate),1===this.o.minViewMode?(this._setDate(c(h,i,g)),this.showMode()):this.showMode(-1),this.fill()),(e.hasClass("year")||e.hasClass("decade")||e.hasClass("century"))&&(this.viewDate.setUTCDate(1),g=1,i=0,h=parseInt(e.text(),10)||0,this.viewDate.setUTCFullYear(h),e.hasClass("year")&&(this._trigger("changeYear",this.viewDate),2===this.o.minViewMode&&this._setDate(c(h,i,g))),e.hasClass("decade")&&(this._trigger("changeDecade",this.viewDate),3===this.o.minViewMode&&this._setDate(c(h,i,g))),e.hasClass("century")&&(this._trigger("changeCentury",this.viewDate),4===this.o.minViewMode&&this._setDate(c(h,i,g))),this.showMode(-1),this.fill())),this.picker.is(":visible")&&this._focused_from&&a(this._focused_from).focus(),delete this._focused_from},_toggle_multidate:function(a){var b=this.dates.contains(a);if(a||this.dates.clear(),-1!==b?(this.o.multidate===!0||this.o.multidate>1||this.o.toggleActive)&&this.dates.remove(b):this.o.multidate===!1?(this.dates.clear(),this.dates.push(a)):this.dates.push(a),"number"==typeof this.o.multidate)for(;this.dates.length>this.o.multidate;)this.dates.remove(0)},_setDate:function(a,b){b&&"date"!==b||this._toggle_multidate(a&&new Date(a)),b&&"view"!==b||(this.viewDate=a&&new Date(a)),this.fill(),this.setValue(),b&&"view"===b||this._trigger("changeDate"),this.inputField&&this.inputField.change(),!this.o.autoclose||b&&"date"!==b||this.hide()},moveDay:function(a,b){var c=new Date(a);return c.setUTCDate(a.getUTCDate()+b),c},moveWeek:function(a,b){return this.moveDay(a,7*b)},moveMonth:function(a,b){if(!g(a))return this.o.defaultViewDate;if(!b)return a;var c,d,e=new Date(a.valueOf()),f=e.getUTCDate(),h=e.getUTCMonth(),i=Math.abs(b);if(b=b>0?1:-1,1===i)d=-1===b?function(){return e.getUTCMonth()===h}:function(){return e.getUTCMonth()!==c},c=h+b,e.setUTCMonth(c),(0>c||c>11)&&(c=(c+12)%12);else{for(var j=0;i>j;j++)e=this.moveMonth(e,b);c=e.getUTCMonth(),e.setUTCDate(f),d=function(){return c!==e.getUTCMonth()}}for(;d();)e.setUTCDate(--f),e.setUTCMonth(c);return e},moveYear:function(a,b){return this.moveMonth(a,12*b)},moveAvailableDate:function(a,b,c){do{if(a=this[c](a,b),!this.dateWithinRange(a))return!1;c="moveDay"}while(this.dateIsDisabled(a));return a},weekOfDateIsDisabled:function(b){return-1!==a.inArray(b.getUTCDay(),this.o.daysOfWeekDisabled)},dateIsDisabled:function(b){return this.weekOfDateIsDisabled(b)||a.grep(this.o.datesDisabled,function(a){return e(b,a)}).length>0},dateWithinRange:function(a){return a>=this.o.startDate&&a<=this.o.endDate},keydown:function(a){if(!this.picker.is(":visible"))return void((40===a.keyCode||27===a.keyCode)&&(this.show(),a.stopPropagation()));var b,c,d=!1,e=this.focusDate||this.viewDate;switch(a.keyCode){case 27:this.focusDate?(this.focusDate=null,this.viewDate=this.dates.get(-1)||this.viewDate,this.fill()):this.hide(),a.preventDefault(),a.stopPropagation();break;case 37:case 38:case 39:case 40:if(!this.o.keyboardNavigation||7===this.o.daysOfWeekDisabled.length)break;b=37===a.keyCode||38===a.keyCode?-1:1,0===this.viewMode?a.ctrlKey?(c=this.moveAvailableDate(e,b,"moveYear"),c&&this._trigger("changeYear",this.viewDate)):a.shiftKey?(c=this.moveAvailableDate(e,b,"moveMonth"),c&&this._trigger("changeMonth",this.viewDate)):37===a.keyCode||39===a.keyCode?c=this.moveAvailableDate(e,b,"moveDay"):this.weekOfDateIsDisabled(e)||(c=this.moveAvailableDate(e,b,"moveWeek")):1===this.viewMode?((38===a.keyCode||40===a.keyCode)&&(b=4*b),c=this.moveAvailableDate(e,b,"moveMonth")):2===this.viewMode&&((38===a.keyCode||40===a.keyCode)&&(b=4*b),c=this.moveAvailableDate(e,b,"moveYear")),c&&(this.focusDate=this.viewDate=c,this.setValue(),this.fill(),a.preventDefault());break;case 13:if(!this.o.forceParse)break;e=this.focusDate||this.dates.get(-1)||this.viewDate,this.o.keyboardNavigation&&(this._toggle_multidate(e),d=!0),this.focusDate=null,this.viewDate=this.dates.get(-1)||this.viewDate,this.setValue(),this.fill(),this.picker.is(":visible")&&(a.preventDefault(),a.stopPropagation(),this.o.autoclose&&this.hide());break;case 9:this.focusDate=null,this.viewDate=this.dates.get(-1)||this.viewDate,this.fill(),this.hide()}d&&(this.dates.length?this._trigger("changeDate"):this._trigger("clearDate"),this.inputField&&this.inputField.change())},showMode:function(a){a&&(this.viewMode=Math.max(this.o.minViewMode,Math.min(this.o.maxViewMode,this.viewMode+a))),this.picker.children("div").hide().filter(".datepicker-"+r.modes[this.viewMode].clsName).show(),this.updateNavArrows()}};var l=function(b,c){a(b).data("datepicker",this),this.element=a(b),this.inputs=a.map(c.inputs,function(a){return a.jquery?a[0]:a}),delete c.inputs,n.call(a(this.inputs),c).on("changeDate",a.proxy(this.dateUpdated,this)),this.pickers=a.map(this.inputs,function(b){return a(b).data("datepicker")}),this.updateDates()};l.prototype={updateDates:function(){this.dates=a.map(this.pickers,function(a){return a.getUTCDate()}),this.updateRanges()},updateRanges:function(){var b=a.map(this.dates,function(a){return a.valueOf()});a.each(this.pickers,function(a,c){c.setRange(b)})},dateUpdated:function(b){if(!this.updating){this.updating=!0;var c=a(b.target).data("datepicker");if("undefined"!=typeof c){var d=c.getUTCDate(),e=a.inArray(b.target,this.inputs),f=e-1,g=e+1,h=this.inputs.length;if(-1!==e){if(a.each(this.pickers,function(a,b){b.getUTCDate()||b.setUTCDate(d)}),d<this.dates[f])for(;f>=0&&d<this.dates[f];)this.pickers[f--].setUTCDate(d);else if(d>this.dates[g])for(;h>g&&d>this.dates[g];)this.pickers[g++].setUTCDate(d);this.updateDates(),delete this.updating}}}},remove:function(){a.map(this.pickers,function(a){a.remove()}),delete this.element.data().datepicker}};var m=a.fn.datepicker,n=function(c){var d=Array.apply(null,arguments);d.shift();var e;if(this.each(function(){var b=a(this),f=b.data("datepicker"),g="object"==typeof c&&c;if(!f){var j=h(this,"date"),m=a.extend({},o,j,g),n=i(m.language),p=a.extend({},o,n,j,g);b.hasClass("input-daterange")||p.inputs?(a.extend(p,{inputs:p.inputs||b.find("input").toArray()}),f=new l(this,p)):f=new k(this,p),b.data("datepicker",f)}"string"==typeof c&&"function"==typeof f[c]&&(e=f[c].apply(f,d))}),e===b||e instanceof k||e instanceof l)return this;if(this.length>1)throw new Error("Using only allowed for the collection of a single element ("+c+" function)");return e};a.fn.datepicker=n;var o=a.fn.datepicker.defaults={assumeNearbyYear:!1,autoclose:!1,beforeShowDay:a.noop,beforeShowMonth:a.noop,beforeShowYear:a.noop,beforeShowDecade:a.noop,beforeShowCentury:a.noop,calendarWeeks:!1,clearBtn:!1,toggleActive:!1,daysOfWeekDisabled:[],daysOfWeekHighlighted:[],datesDisabled:[],endDate:1/0,forceParse:!0,format:"mm/dd/yyyy",keyboardNavigation:!0,language:"en",minViewMode:0,maxViewMode:4,multidate:!1,multidateSeparator:",",orientation:"auto",rtl:!1,startDate:-(1/0),startView:0,todayBtn:!1,todayHighlight:!1,weekStart:0,disableTouchKeyboard:!1,enableOnReadonly:!0,showOnFocus:!0,zIndexOffset:10,container:"body",immediateUpdates:!1,title:"",templates:{leftArrow:"&laquo;",rightArrow:"&raquo;"}},p=a.fn.datepicker.locale_opts=["format","rtl","weekStart"];a.fn.datepicker.Constructor=k;var q=a.fn.datepicker.dates={en:{days:["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"],daysShort:["Sun","Mon","Tue","Wed","Thu","Fri","Sat"],daysMin:["Su","Mo","Tu","We","Th","Fr","Sa"],months:["January","February","March","April","May","June","July","August","September","October","November","December"],monthsShort:["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],today:"Today",clear:"Clear",titleFormat:"MM yyyy"}},r={modes:[{clsName:"days",navFnc:"Month",navStep:1},{clsName:"months",navFnc:"FullYear",navStep:1},{clsName:"years",navFnc:"FullYear",navStep:10},{clsName:"decades",navFnc:"FullDecade",navStep:100},{clsName:"centuries",navFnc:"FullCentury",navStep:1e3}],isLeapYear:function(a){return a%4===0&&a%100!==0||a%400===0},getDaysInMonth:function(a,b){return[31,r.isLeapYear(a)?29:28,31,30,31,30,31,31,30,31,30,31][b]},validParts:/dd?|DD?|mm?|MM?|yy(?:yy)?/g,nonpunctuation:/[^ -\/:-@\u5e74\u6708\u65e5\[-`{-~\t\n\r]+/g,parseFormat:function(a){if("function"==typeof a.toValue&&"function"==typeof a.toDisplay)return a;var b=a.replace(this.validParts,"\x00").split("\x00"),c=a.match(this.validParts);if(!b||!b.length||!c||0===c.length)throw new Error("Invalid date format.");return{separators:b,parts:c}},parseDate:function(e,f,g,h){function i(a,b){return b===!0&&(b=10),100>a&&(a+=2e3,a>(new Date).getFullYear()+b&&(a-=100)),a}function j(){var a=this.slice(0,s[n].length),b=s[n].slice(0,a.length);return a.toLowerCase()===b.toLowerCase()}if(!e)return b;if(e instanceof Date)return e;if("string"==typeof f&&(f=r.parseFormat(f)),f.toValue)return f.toValue(e,f,g);var l,m,n,o,p=/([\-+]\d+)([dmwy])/,s=e.match(/([\-+]\d+)([dmwy])/g),t={d:"moveDay",m:"moveMonth",w:"moveWeek",y:"moveYear"},u={yesterday:"-1d",today:"+0d",tomorrow:"+1d"};if(/^[\-+]\d+[dmwy]([\s,]+[\-+]\d+[dmwy])*$/.test(e)){for(e=new Date,n=0;n<s.length;n++)l=p.exec(s[n]),m=parseInt(l[1]),o=t[l[2]],e=k.prototype[o](e,m);return c(e.getUTCFullYear(),e.getUTCMonth(),e.getUTCDate())}if("undefined"!=typeof u[e]&&(e=u[e],s=e.match(/([\-+]\d+)([dmwy])/g),/^[\-+]\d+[dmwy]([\s,]+[\-+]\d+[dmwy])*$/.test(e))){for(e=new Date,n=0;n<s.length;n++)l=p.exec(s[n]),m=parseInt(l[1]),o=t[l[2]],e=k.prototype[o](e,m);return c(e.getUTCFullYear(),e.getUTCMonth(),e.getUTCDate())}s=e&&e.match(this.nonpunctuation)||[],e=new Date;var v,w,x={},y=["yyyy","yy","M","MM","m","mm","d","dd"],z={yyyy:function(a,b){return a.setUTCFullYear(h?i(b,h):b)},yy:function(a,b){return a.setUTCFullYear(h?i(b,h):b)},m:function(a,b){if(isNaN(a))return a;for(b-=1;0>b;)b+=12;for(b%=12,a.setUTCMonth(b);a.getUTCMonth()!==b;)a.setUTCDate(a.getUTCDate()-1);return a},d:function(a,b){return a.setUTCDate(b)}};z.M=z.MM=z.mm=z.m,z.dd=z.d,e=d();var A=f.parts.slice();if(s.length!==A.length&&(A=a(A).filter(function(b,c){return-1!==a.inArray(c,y)}).toArray()),s.length===A.length){var B;for(n=0,B=A.length;B>n;n++){if(v=parseInt(s[n],10),l=A[n],isNaN(v))switch(l){case"MM":w=a(q[g].months).filter(j),v=a.inArray(w[0],q[g].months)+1;break;case"M":w=a(q[g].monthsShort).filter(j),v=a.inArray(w[0],q[g].monthsShort)+1}x[l]=v}var C,D;for(n=0;n<y.length;n++)D=y[n],D in x&&!isNaN(x[D])&&(C=new Date(e),z[D](C,x[D]),isNaN(C)||(e=C))}return e},formatDate:function(b,c,d){if(!b)return"";if("string"==typeof c&&(c=r.parseFormat(c)),
c.toDisplay)return c.toDisplay(b,c,d);var e={d:b.getUTCDate(),D:q[d].daysShort[b.getUTCDay()],DD:q[d].days[b.getUTCDay()],m:b.getUTCMonth()+1,M:q[d].monthsShort[b.getUTCMonth()],MM:q[d].months[b.getUTCMonth()],yy:b.getUTCFullYear().toString().substring(2),yyyy:b.getUTCFullYear()};e.dd=(e.d<10?"0":"")+e.d,e.mm=(e.m<10?"0":"")+e.m,b=[];for(var f=a.extend([],c.separators),g=0,h=c.parts.length;h>=g;g++)f.length&&b.push(f.shift()),b.push(e[c.parts[g]]);return b.join("")},headTemplate:'<thead><tr><th colspan="7" class="datepicker-title"></th></tr><tr><th class="prev">&laquo;</th><th colspan="5" class="datepicker-switch"></th><th class="next">&raquo;</th></tr></thead>',contTemplate:'<tbody><tr><td colspan="7"></td></tr></tbody>',footTemplate:'<tfoot><tr><th colspan="7" class="today"></th></tr><tr><th colspan="7" class="clear"></th></tr></tfoot>'};r.template='<div class="datepicker"><div class="datepicker-days"><table class="table-condensed">'+r.headTemplate+"<tbody></tbody>"+r.footTemplate+'</table></div><div class="datepicker-months"><table class="table-condensed">'+r.headTemplate+r.contTemplate+r.footTemplate+'</table></div><div class="datepicker-years"><table class="table-condensed">'+r.headTemplate+r.contTemplate+r.footTemplate+'</table></div><div class="datepicker-decades"><table class="table-condensed">'+r.headTemplate+r.contTemplate+r.footTemplate+'</table></div><div class="datepicker-centuries"><table class="table-condensed">'+r.headTemplate+r.contTemplate+r.footTemplate+"</table></div></div>",a.fn.datepicker.DPGlobal=r,a.fn.datepicker.noConflict=function(){return a.fn.datepicker=m,this},a.fn.datepicker.version="1.6.4",a(document).on("focus.datepicker.data-api click.datepicker.data-api",'[data-provide="datepicker"]',function(b){var c=a(this);c.data("datepicker")||(b.preventDefault(),n.call(c,"show"))}),a(function(){n.call(a('[data-provide="datepicker-inline"]'))})});
/*************************
 * Croppie
 * Copyright 2015
 * Foliotek
 * Version: 1.0.3
 *************************/
(function (root, factory) {
    if (typeof define === 'function' && define.amd) {
        // AMD. Register as an anonymous module.
        define(['exports', 'b'], factory);
    } else if (typeof exports === 'object' && typeof exports.nodeName !== 'string') {
        // CommonJS
        factory(exports, require('b'));
    } else {
        // Browser globals
        factory((root.commonJsStrict = {}), root.b);
    }
}(this, function (exports, b) {

    if (typeof Promise !== 'function') {
        /*!
         * @overview es6-promise - a tiny implementation of Promises/A+.
         * @copyright Copyright (c) 2014 Yehuda Katz, Tom Dale, Stefan Penner and contributors (Conversion to ES6 API by Jake Archibald)
         * @license   Licensed under MIT license
         *            See https://raw.githubusercontent.com/jakearchibald/es6-promise/master/LICENSE
         * @version   3.0.2
         */
        (function () { "use strict"; function lib$es6$promise$utils$$objectOrFunction(x) { return typeof x === "function" || typeof x === "object" && x !== null } function lib$es6$promise$utils$$isFunction(x) { return typeof x === "function" } function lib$es6$promise$utils$$isMaybeThenable(x) { return typeof x === "object" && x !== null } var lib$es6$promise$utils$$_isArray; if (!Array.isArray) { lib$es6$promise$utils$$_isArray = function (x) { return Object.prototype.toString.call(x) === "[object Array]" } } else { lib$es6$promise$utils$$_isArray = Array.isArray } var lib$es6$promise$utils$$isArray = lib$es6$promise$utils$$_isArray; var lib$es6$promise$asap$$len = 0; var lib$es6$promise$asap$$toString = {}.toString; var lib$es6$promise$asap$$vertxNext; var lib$es6$promise$asap$$customSchedulerFn; var lib$es6$promise$asap$$asap = function asap(callback, arg) { lib$es6$promise$asap$$queue[lib$es6$promise$asap$$len] = callback; lib$es6$promise$asap$$queue[lib$es6$promise$asap$$len + 1] = arg; lib$es6$promise$asap$$len += 2; if (lib$es6$promise$asap$$len === 2) { if (lib$es6$promise$asap$$customSchedulerFn) { lib$es6$promise$asap$$customSchedulerFn(lib$es6$promise$asap$$flush) } else { lib$es6$promise$asap$$scheduleFlush() } } }; function lib$es6$promise$asap$$setScheduler(scheduleFn) { lib$es6$promise$asap$$customSchedulerFn = scheduleFn } function lib$es6$promise$asap$$setAsap(asapFn) { lib$es6$promise$asap$$asap = asapFn } var lib$es6$promise$asap$$browserWindow = typeof window !== "undefined" ? window : undefined; var lib$es6$promise$asap$$browserGlobal = lib$es6$promise$asap$$browserWindow || {}; var lib$es6$promise$asap$$BrowserMutationObserver = lib$es6$promise$asap$$browserGlobal.MutationObserver || lib$es6$promise$asap$$browserGlobal.WebKitMutationObserver; var lib$es6$promise$asap$$isNode = typeof process !== "undefined" && {}.toString.call(process) === "[object process]"; var lib$es6$promise$asap$$isWorker = typeof Uint8ClampedArray !== "undefined" && typeof importScripts !== "undefined" && typeof MessageChannel !== "undefined"; function lib$es6$promise$asap$$useNextTick() { return function () { process.nextTick(lib$es6$promise$asap$$flush) } } function lib$es6$promise$asap$$useVertxTimer() { return function () { lib$es6$promise$asap$$vertxNext(lib$es6$promise$asap$$flush) } } function lib$es6$promise$asap$$useMutationObserver() { var iterations = 0; var observer = new lib$es6$promise$asap$$BrowserMutationObserver(lib$es6$promise$asap$$flush); var node = document.createTextNode(""); observer.observe(node, { characterData: true }); return function () { node.data = iterations = ++iterations % 2 } } function lib$es6$promise$asap$$useMessageChannel() { var channel = new MessageChannel; channel.port1.onmessage = lib$es6$promise$asap$$flush; return function () { channel.port2.postMessage(0) } } function lib$es6$promise$asap$$useSetTimeout() { return function () { setTimeout(lib$es6$promise$asap$$flush, 1) } } var lib$es6$promise$asap$$queue = new Array(1e3); function lib$es6$promise$asap$$flush() { for (var i = 0; i < lib$es6$promise$asap$$len; i += 2) { var callback = lib$es6$promise$asap$$queue[i]; var arg = lib$es6$promise$asap$$queue[i + 1]; callback(arg); lib$es6$promise$asap$$queue[i] = undefined; lib$es6$promise$asap$$queue[i + 1] = undefined } lib$es6$promise$asap$$len = 0 } function lib$es6$promise$asap$$attemptVertx() { try { var r = require; var vertx = r("vertx"); lib$es6$promise$asap$$vertxNext = vertx.runOnLoop || vertx.runOnContext; return lib$es6$promise$asap$$useVertxTimer() } catch (e) { return lib$es6$promise$asap$$useSetTimeout() } } var lib$es6$promise$asap$$scheduleFlush; if (lib$es6$promise$asap$$isNode) { lib$es6$promise$asap$$scheduleFlush = lib$es6$promise$asap$$useNextTick() } else if (lib$es6$promise$asap$$BrowserMutationObserver) { lib$es6$promise$asap$$scheduleFlush = lib$es6$promise$asap$$useMutationObserver() } else if (lib$es6$promise$asap$$isWorker) { lib$es6$promise$asap$$scheduleFlush = lib$es6$promise$asap$$useMessageChannel() } else if (lib$es6$promise$asap$$browserWindow === undefined && typeof require === "function") { lib$es6$promise$asap$$scheduleFlush = lib$es6$promise$asap$$attemptVertx() } else { lib$es6$promise$asap$$scheduleFlush = lib$es6$promise$asap$$useSetTimeout() } function lib$es6$promise$$internal$$noop() { } var lib$es6$promise$$internal$$PENDING = void 0; var lib$es6$promise$$internal$$FULFILLED = 1; var lib$es6$promise$$internal$$REJECTED = 2; var lib$es6$promise$$internal$$GET_THEN_ERROR = new lib$es6$promise$$internal$$ErrorObject; function lib$es6$promise$$internal$$selfFulfillment() { return new TypeError("You cannot resolve a promise with itself") } function lib$es6$promise$$internal$$cannotReturnOwn() { return new TypeError("A promises callback cannot return that same promise.") } function lib$es6$promise$$internal$$getThen(promise) { try { return promise.then } catch (error) { lib$es6$promise$$internal$$GET_THEN_ERROR.error = error; return lib$es6$promise$$internal$$GET_THEN_ERROR } } function lib$es6$promise$$internal$$tryThen(then, value, fulfillmentHandler, rejectionHandler) { try { then.call(value, fulfillmentHandler, rejectionHandler) } catch (e) { return e } } function lib$es6$promise$$internal$$handleForeignThenable(promise, thenable, then) { lib$es6$promise$asap$$asap(function (promise) { var sealed = false; var error = lib$es6$promise$$internal$$tryThen(then, thenable, function (value) { if (sealed) { return } sealed = true; if (thenable !== value) { lib$es6$promise$$internal$$resolve(promise, value) } else { lib$es6$promise$$internal$$fulfill(promise, value) } }, function (reason) { if (sealed) { return } sealed = true; lib$es6$promise$$internal$$reject(promise, reason) }, "Settle: " + (promise._label || " unknown promise")); if (!sealed && error) { sealed = true; lib$es6$promise$$internal$$reject(promise, error) } }, promise) } function lib$es6$promise$$internal$$handleOwnThenable(promise, thenable) { if (thenable._state === lib$es6$promise$$internal$$FULFILLED) { lib$es6$promise$$internal$$fulfill(promise, thenable._result) } else if (thenable._state === lib$es6$promise$$internal$$REJECTED) { lib$es6$promise$$internal$$reject(promise, thenable._result) } else { lib$es6$promise$$internal$$subscribe(thenable, undefined, function (value) { lib$es6$promise$$internal$$resolve(promise, value) }, function (reason) { lib$es6$promise$$internal$$reject(promise, reason) }) } } function lib$es6$promise$$internal$$handleMaybeThenable(promise, maybeThenable) { if (maybeThenable.constructor === promise.constructor) { lib$es6$promise$$internal$$handleOwnThenable(promise, maybeThenable) } else { var then = lib$es6$promise$$internal$$getThen(maybeThenable); if (then === lib$es6$promise$$internal$$GET_THEN_ERROR) { lib$es6$promise$$internal$$reject(promise, lib$es6$promise$$internal$$GET_THEN_ERROR.error) } else if (then === undefined) { lib$es6$promise$$internal$$fulfill(promise, maybeThenable) } else if (lib$es6$promise$utils$$isFunction(then)) { lib$es6$promise$$internal$$handleForeignThenable(promise, maybeThenable, then) } else { lib$es6$promise$$internal$$fulfill(promise, maybeThenable) } } } function lib$es6$promise$$internal$$resolve(promise, value) { if (promise === value) { lib$es6$promise$$internal$$reject(promise, lib$es6$promise$$internal$$selfFulfillment()) } else if (lib$es6$promise$utils$$objectOrFunction(value)) { lib$es6$promise$$internal$$handleMaybeThenable(promise, value) } else { lib$es6$promise$$internal$$fulfill(promise, value) } } function lib$es6$promise$$internal$$publishRejection(promise) { if (promise._onerror) { promise._onerror(promise._result) } lib$es6$promise$$internal$$publish(promise) } function lib$es6$promise$$internal$$fulfill(promise, value) { if (promise._state !== lib$es6$promise$$internal$$PENDING) { return } promise._result = value; promise._state = lib$es6$promise$$internal$$FULFILLED; if (promise._subscribers.length !== 0) { lib$es6$promise$asap$$asap(lib$es6$promise$$internal$$publish, promise) } } function lib$es6$promise$$internal$$reject(promise, reason) { if (promise._state !== lib$es6$promise$$internal$$PENDING) { return } promise._state = lib$es6$promise$$internal$$REJECTED; promise._result = reason; lib$es6$promise$asap$$asap(lib$es6$promise$$internal$$publishRejection, promise) } function lib$es6$promise$$internal$$subscribe(parent, child, onFulfillment, onRejection) { var subscribers = parent._subscribers; var length = subscribers.length; parent._onerror = null; subscribers[length] = child; subscribers[length + lib$es6$promise$$internal$$FULFILLED] = onFulfillment; subscribers[length + lib$es6$promise$$internal$$REJECTED] = onRejection; if (length === 0 && parent._state) { lib$es6$promise$asap$$asap(lib$es6$promise$$internal$$publish, parent) } } function lib$es6$promise$$internal$$publish(promise) { var subscribers = promise._subscribers; var settled = promise._state; if (subscribers.length === 0) { return } var child, callback, detail = promise._result; for (var i = 0; i < subscribers.length; i += 3) { child = subscribers[i]; callback = subscribers[i + settled]; if (child) { lib$es6$promise$$internal$$invokeCallback(settled, child, callback, detail) } else { callback(detail) } } promise._subscribers.length = 0 } function lib$es6$promise$$internal$$ErrorObject() { this.error = null } var lib$es6$promise$$internal$$TRY_CATCH_ERROR = new lib$es6$promise$$internal$$ErrorObject; function lib$es6$promise$$internal$$tryCatch(callback, detail) { try { return callback(detail) } catch (e) { lib$es6$promise$$internal$$TRY_CATCH_ERROR.error = e; return lib$es6$promise$$internal$$TRY_CATCH_ERROR } } function lib$es6$promise$$internal$$invokeCallback(settled, promise, callback, detail) { var hasCallback = lib$es6$promise$utils$$isFunction(callback), value, error, succeeded, failed; if (hasCallback) { value = lib$es6$promise$$internal$$tryCatch(callback, detail); if (value === lib$es6$promise$$internal$$TRY_CATCH_ERROR) { failed = true; error = value.error; value = null } else { succeeded = true } if (promise === value) { lib$es6$promise$$internal$$reject(promise, lib$es6$promise$$internal$$cannotReturnOwn()); return } } else { value = detail; succeeded = true } if (promise._state !== lib$es6$promise$$internal$$PENDING) { } else if (hasCallback && succeeded) { lib$es6$promise$$internal$$resolve(promise, value) } else if (failed) { lib$es6$promise$$internal$$reject(promise, error) } else if (settled === lib$es6$promise$$internal$$FULFILLED) { lib$es6$promise$$internal$$fulfill(promise, value) } else if (settled === lib$es6$promise$$internal$$REJECTED) { lib$es6$promise$$internal$$reject(promise, value) } } function lib$es6$promise$$internal$$initializePromise(promise, resolver) { try { resolver(function resolvePromise(value) { lib$es6$promise$$internal$$resolve(promise, value) }, function rejectPromise(reason) { lib$es6$promise$$internal$$reject(promise, reason) }) } catch (e) { lib$es6$promise$$internal$$reject(promise, e) } } function lib$es6$promise$enumerator$$Enumerator(Constructor, input) { var enumerator = this; enumerator._instanceConstructor = Constructor; enumerator.promise = new Constructor(lib$es6$promise$$internal$$noop); if (enumerator._validateInput(input)) { enumerator._input = input; enumerator.length = input.length; enumerator._remaining = input.length; enumerator._init(); if (enumerator.length === 0) { lib$es6$promise$$internal$$fulfill(enumerator.promise, enumerator._result) } else { enumerator.length = enumerator.length || 0; enumerator._enumerate(); if (enumerator._remaining === 0) { lib$es6$promise$$internal$$fulfill(enumerator.promise, enumerator._result) } } } else { lib$es6$promise$$internal$$reject(enumerator.promise, enumerator._validationError()) } } lib$es6$promise$enumerator$$Enumerator.prototype._validateInput = function (input) { return lib$es6$promise$utils$$isArray(input) }; lib$es6$promise$enumerator$$Enumerator.prototype._validationError = function () { return new Error("Array Methods must be provided an Array") }; lib$es6$promise$enumerator$$Enumerator.prototype._init = function () { this._result = new Array(this.length) }; var lib$es6$promise$enumerator$$default = lib$es6$promise$enumerator$$Enumerator; lib$es6$promise$enumerator$$Enumerator.prototype._enumerate = function () { var enumerator = this; var length = enumerator.length; var promise = enumerator.promise; var input = enumerator._input; for (var i = 0; promise._state === lib$es6$promise$$internal$$PENDING && i < length; i++) { enumerator._eachEntry(input[i], i) } }; lib$es6$promise$enumerator$$Enumerator.prototype._eachEntry = function (entry, i) { var enumerator = this; var c = enumerator._instanceConstructor; if (lib$es6$promise$utils$$isMaybeThenable(entry)) { if (entry.constructor === c && entry._state !== lib$es6$promise$$internal$$PENDING) { entry._onerror = null; enumerator._settledAt(entry._state, i, entry._result) } else { enumerator._willSettleAt(c.resolve(entry), i) } } else { enumerator._remaining--; enumerator._result[i] = entry } }; lib$es6$promise$enumerator$$Enumerator.prototype._settledAt = function (state, i, value) { var enumerator = this; var promise = enumerator.promise; if (promise._state === lib$es6$promise$$internal$$PENDING) { enumerator._remaining--; if (state === lib$es6$promise$$internal$$REJECTED) { lib$es6$promise$$internal$$reject(promise, value) } else { enumerator._result[i] = value } } if (enumerator._remaining === 0) { lib$es6$promise$$internal$$fulfill(promise, enumerator._result) } }; lib$es6$promise$enumerator$$Enumerator.prototype._willSettleAt = function (promise, i) { var enumerator = this; lib$es6$promise$$internal$$subscribe(promise, undefined, function (value) { enumerator._settledAt(lib$es6$promise$$internal$$FULFILLED, i, value) }, function (reason) { enumerator._settledAt(lib$es6$promise$$internal$$REJECTED, i, reason) }) }; function lib$es6$promise$promise$all$$all(entries) { return new lib$es6$promise$enumerator$$default(this, entries).promise } var lib$es6$promise$promise$all$$default = lib$es6$promise$promise$all$$all; function lib$es6$promise$promise$race$$race(entries) { var Constructor = this; var promise = new Constructor(lib$es6$promise$$internal$$noop); if (!lib$es6$promise$utils$$isArray(entries)) { lib$es6$promise$$internal$$reject(promise, new TypeError("You must pass an array to race.")); return promise } var length = entries.length; function onFulfillment(value) { lib$es6$promise$$internal$$resolve(promise, value) } function onRejection(reason) { lib$es6$promise$$internal$$reject(promise, reason) } for (var i = 0; promise._state === lib$es6$promise$$internal$$PENDING && i < length; i++) { lib$es6$promise$$internal$$subscribe(Constructor.resolve(entries[i]), undefined, onFulfillment, onRejection) } return promise } var lib$es6$promise$promise$race$$default = lib$es6$promise$promise$race$$race; function lib$es6$promise$promise$resolve$$resolve(object) { var Constructor = this; if (object && typeof object === "object" && object.constructor === Constructor) { return object } var promise = new Constructor(lib$es6$promise$$internal$$noop); lib$es6$promise$$internal$$resolve(promise, object); return promise } var lib$es6$promise$promise$resolve$$default = lib$es6$promise$promise$resolve$$resolve; function lib$es6$promise$promise$reject$$reject(reason) { var Constructor = this; var promise = new Constructor(lib$es6$promise$$internal$$noop); lib$es6$promise$$internal$$reject(promise, reason); return promise } var lib$es6$promise$promise$reject$$default = lib$es6$promise$promise$reject$$reject; var lib$es6$promise$promise$$counter = 0; function lib$es6$promise$promise$$needsResolver() { throw new TypeError("You must pass a resolver function as the first argument to the promise constructor") } function lib$es6$promise$promise$$needsNew() { throw new TypeError("Failed to construct 'Promise': Please use the 'new' operator, this object constructor cannot be called as a function.") } var lib$es6$promise$promise$$default = lib$es6$promise$promise$$Promise; function lib$es6$promise$promise$$Promise(resolver) { this._id = lib$es6$promise$promise$$counter++; this._state = undefined; this._result = undefined; this._subscribers = []; if (lib$es6$promise$$internal$$noop !== resolver) { if (!lib$es6$promise$utils$$isFunction(resolver)) { lib$es6$promise$promise$$needsResolver() } if (!(this instanceof lib$es6$promise$promise$$Promise)) { lib$es6$promise$promise$$needsNew() } lib$es6$promise$$internal$$initializePromise(this, resolver) } } lib$es6$promise$promise$$Promise.all = lib$es6$promise$promise$all$$default; lib$es6$promise$promise$$Promise.race = lib$es6$promise$promise$race$$default; lib$es6$promise$promise$$Promise.resolve = lib$es6$promise$promise$resolve$$default; lib$es6$promise$promise$$Promise.reject = lib$es6$promise$promise$reject$$default; lib$es6$promise$promise$$Promise._setScheduler = lib$es6$promise$asap$$setScheduler; lib$es6$promise$promise$$Promise._setAsap = lib$es6$promise$asap$$setAsap; lib$es6$promise$promise$$Promise._asap = lib$es6$promise$asap$$asap; lib$es6$promise$promise$$Promise.prototype = { constructor: lib$es6$promise$promise$$Promise, then: function (onFulfillment, onRejection) { var parent = this; var state = parent._state; if (state === lib$es6$promise$$internal$$FULFILLED && !onFulfillment || state === lib$es6$promise$$internal$$REJECTED && !onRejection) { return this } var child = new this.constructor(lib$es6$promise$$internal$$noop); var result = parent._result; if (state) { var callback = arguments[state - 1]; lib$es6$promise$asap$$asap(function () { lib$es6$promise$$internal$$invokeCallback(state, child, callback, result) }) } else { lib$es6$promise$$internal$$subscribe(parent, child, onFulfillment, onRejection) } return child }, "catch": function (onRejection) { return this.then(null, onRejection) } }; function lib$es6$promise$polyfill$$polyfill() { var local; if (typeof global !== "undefined") { local = global } else if (typeof self !== "undefined") { local = self } else { try { local = Function("return this")() } catch (e) { throw new Error("polyfill failed because global object is unavailable in this environment") } } var P = local.Promise; if (P && Object.prototype.toString.call(P.resolve()) === "[object Promise]" && !P.cast) { return } local.Promise = lib$es6$promise$promise$$default } var lib$es6$promise$polyfill$$default = lib$es6$promise$polyfill$$polyfill; var lib$es6$promise$umd$$ES6Promise = { Promise: lib$es6$promise$promise$$default, polyfill: lib$es6$promise$polyfill$$default }; if (typeof define === "function" && define["amd"]) { define(function () { return lib$es6$promise$umd$$ES6Promise }) } else if (typeof module !== "undefined" && module["exports"]) { module["exports"] = lib$es6$promise$umd$$ES6Promise } else if (typeof this !== "undefined") { this["ES6Promise"] = lib$es6$promise$umd$$ES6Promise } lib$es6$promise$polyfill$$default() }).call(this);
    }

    var cssPrefixes = ['Webkit', 'Moz', 'ms'],
        emptyStyles = document.createElement('div').style,
        CSS_TRANS_ORG,
        CSS_TRANSFORM,
        CSS_USERSELECT;

    function vendorPrefix(prop) {
        if (prop in emptyStyles) {
            return prop;
        }

        var capProp = prop[0].toUpperCase() + prop.slice(1),
            i = cssPrefixes.length;

        while (i--) {
            prop = cssPrefixes[i] + capProp;
            if (prop in emptyStyles) {
                return prop;
            }
        }
    }

    CSS_TRANSFORM = vendorPrefix('transform');
    CSS_TRANS_ORG = vendorPrefix('transformOrigin');
    CSS_USERSELECT = vendorPrefix('userSelect');


    function deepExtend(out) {
        out = out || {};

        for (var i = 1; i < arguments.length; i++) {
            var obj = arguments[i];

            if (!obj)
                continue;

            for (var key in obj) {
                if (obj.hasOwnProperty(key)) {
                    if (typeof obj[key] === 'object')
                        out[key] = deepExtend({}, obj[key]);
                    else
                        out[key] = obj[key];
                }
            }
        }
        return out;
    }

    function debounce(func, wait, immediate) {
        var timeout;
        return function () {
            var context = this, args = arguments;
            var later = function () {
                timeout = null;
                if (!immediate) func.apply(context, args);
            };
            var callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) func.apply(context, args);
        };
    }

    function dispatchChange(element) {
        if ("createEvent" in document) {
            var evt = document.createEvent("HTMLEvents");
            evt.initEvent("change", false, true);
            element.dispatchEvent(evt);
        }
        else {
            element.fireEvent("onchange");
        }
    }

    //http://jsperf.com/vanilla-css
    function css(el, styles, val) {
        if (typeof (styles) === 'string') {
            var tmp = styles;
            styles = {};
            styles[tmp] = val;
        }

        for (var prop in styles) {
            el.style[prop] = styles[prop];
        }
    }

    /* Image Drawing Functions */
    function getHtmlImage(data) {
        var points = data.points,
            div = document.createElement('div'),
            img = document.createElement('img'),
            width = points[2] - points[0],
            height = points[3] - points[1];
        // scale = data.zoom;

        div.classList.add('croppie-result');
        div.appendChild(img);
        css(img, {
            left: (-1 * points[0]) + 'px',
            top: (-1 * points[1]) + 'px'
            // transform: 'scale(' + scale + ')'
        })
        img.src = data.url;
        css(div, {
            width: width + 'px',
            height: height + 'px'
        });

        return div;
    }

    function getCanvasImage(img, data) {
        var points = data.points,
            left = points[0],
            top = points[1],
            width = (points[2] - points[0]),
            height = (points[3] - points[1]),
            circle = data.circle,
            canvas = document.createElement('canvas'),
            ctx = canvas.getContext('2d'),
            outWidth = width,
            outHeight = height;

        if (data.outputWidth && data.outputHeight) {
            outWidth = data.outputWidth;
            outHeight = data.outputHeight;
        }

        canvas.width = outWidth;
        canvas.height = outHeight;

        if (circle) {
            ctx.save();
            ctx.beginPath();
            ctx.arc(outWidth / 2, outHeight / 2, outWidth / 2, 0, Math.PI * 2, true);
            ctx.closePath();
            ctx.clip();
        }

        ctx.drawImage(img, left, top, width, height, 0, 0, outWidth, outHeight);

        return canvas.toDataURL();
    }

    /* Utilities */
    function loadImage(src, imageEl) {
        var img = imageEl || new Image(),
            prom;

        prom = new Promise(function (resolve, reject) {
            img.setAttribute('crossOrigin', 'anonymous');
            img.onload = function () {
                setTimeout(function () {
                    resolve(img);
                }, 1);
            };
        });

        img.src = src;
        return prom;
    }

    /* CSS Transform Prototype */
    var _TRANSLATE = 'translate3d',
        _TRANSLATE_SUFFIX = ', 0px';
    var Transform = function (x, y, scale) {
        this.x = parseFloat(x);
        this.y = parseFloat(y);
        this.scale = parseFloat(scale);
    };

    Transform.parse = function (v) {
        if (v.style) {
            return Transform.parse(v.style[CSS_TRANSFORM]);
        }
        else if (v.indexOf('matrix') > -1 || v.indexOf('none') > -1) {
            return Transform.fromMatrix(v);
        }
        else {
            return Transform.fromString(v);
        }
    };

    Transform.fromMatrix = function (v) {
        var vals = v.substring(7).split(',');
        if (!vals.length || v === 'none') {
            vals = [1, 0, 0, 1, 0, 0];
        }

        return new Transform(parseInt(vals[4], 10), parseInt(vals[5], 10), parseFloat(vals[0]));
    };

    Transform.fromString = function (v) {
        var values = v.split(') '),
            translate = values[0].substring(_TRANSLATE.length + 1).split(','),
            scale = values.length > 1 ? values[1].substring(6) : 1,
            x = translate.length > 1 ? translate[0] : 0,
            y = translate.length > 1 ? translate[1] : 0;

        return new Transform(x, y, scale);
    }

    Transform.prototype.toString = function () {
        return _TRANSLATE + '(' + this.x + 'px, ' + this.y + 'px' + _TRANSLATE_SUFFIX + ') scale(' + this.scale + ')';
    };

    var TransformOrigin = function (el) {
        if (!el || !el.style[CSS_TRANS_ORG]) {
            this.x = 0;
            this.y = 0;
            return;
        }
        var css = el.style[CSS_TRANS_ORG].split(' ');
        this.x = parseFloat(css[0]);
        this.y = parseFloat(css[1]);
    };

    TransformOrigin.prototype.toString = function () {
        return this.x + 'px ' + this.y + 'px';
    };

    /* Private Methods */
    function _create() {
        var self = this,
            contClass = ['croppie-container'],
            customViewportClass = self.options.viewport.type ? 'cr-vp-' + self.options.viewport.type : null,
            boundary, img, viewport, overlay;

        // Properties on class
        self.data = {};
        self.elements = {};

        // Generating Markup
        boundary = self.elements.boundary = document.createElement('div');
        viewport = self.elements.viewport = document.createElement('div');
        img = self.elements.img = document.createElement('img');
        overlay = self.elements.overlay = document.createElement('div');

        boundary.classList.add('cr-boundary');
        css(boundary, {
            width: self.options.boundary.width + 'px',
            height: self.options.boundary.height + 'px'
        });

        viewport.classList.add('cr-viewport');
        if (customViewportClass) {
            viewport.classList.add(customViewportClass);
        }
        css(viewport, {
            width: self.options.viewport.width + 'px',
            height: self.options.viewport.height + 'px'
        });

        img.classList.add('cr-image');
        overlay.classList.add('cr-overlay');

        self.element.appendChild(boundary);
        boundary.appendChild(img);
        boundary.appendChild(viewport);
        boundary.appendChild(overlay);

        self.element.classList.add(contClass);
        if (self.options.customClass) {
            self.element.classList.add(self.options.customClass);
        }

        // Initialize drag & zoom
        _initDraggable.call(this);

        if (self.options.showZoom) {
            _initializeZoom.call(self);
        }
    }

    function _setZoomerVal(v) {
        if (this.options.showZoom) {
            this.elements.zoomer.value = fix(v);
        }
    }

    function _initializeZoom() {
        var self = this,
            wrap = self.elements.zoomerWrap = document.createElement('div'),
            zoomer = self.elements.zoomer = document.createElement('input'),
            origin,
            viewportRect,
            transform;

        wrap.classList.add('cr-slider-wrap');
        zoomer.type = 'range';
        zoomer.classList.add('cr-slider');
        zoomer.step = '0.01';
        zoomer.value = 1;

        self.element.appendChild(wrap);
        wrap.appendChild(zoomer);

        self._currentZoom = 1;
        function start() {
            _updateCenterPoint.call(self);
            origin = new TransformOrigin(self.elements.img);
            viewportRect = self.elements.viewport.getBoundingClientRect();
            transform = Transform.parse(self.elements.img);
        }

        function change() {
            _onZoom.call(self, {
                value: parseFloat(zoomer.value),
                origin: origin || new TransformOrigin(self.elements.img),
                viewportRect: viewportRect || self.elements.viewport.getBoundingClientRect(),
                transform: transform || Transform.parse(self.elements.img)
            });
        }

        function scroll(ev) {
            var delta = ev.deltaY / -2000, // denominator is arbitrary - might consider changing based on image size
                targetZoom = self._currentZoom + delta;

            ev.preventDefault();
            start();
            _setZoomerVal.call(self, targetZoom);
            change();
        }

        self.elements.zoomer.addEventListener('mousedown', start);
        self.elements.zoomer.addEventListener('touchstart', start);

        self.elements.zoomer.addEventListener('input', change);// this is being fired twice on keypress
        self.elements.zoomer.addEventListener('change', change);

        if (self.options.mouseWheelZoom) {
            self.elements.boundary.addEventListener('mousewheel', scroll);
            self.elements.boundary.addEventListener('DOMMouseScroll', scroll);
        }
    }

    function _onZoom(ui) {
        var self = this,
            transform = ui.transform,
            vpRect = ui.viewportRect,
            origin = ui.origin;

        self._currentZoom = ui.value;
        transform.scale = self._currentZoom;

        var boundaries = _getVirtualBoundaries.call(self, vpRect),
            transBoundaries = boundaries.translate,
            oBoundaries = boundaries.origin;

        if (transform.x >= transBoundaries.maxX) {
            origin.x = oBoundaries.minX;
            transform.x = transBoundaries.maxX;
        }

        if (transform.x <= transBoundaries.minX) {
            origin.x = oBoundaries.maxX;
            transform.x = transBoundaries.minX;
        }

        if (transform.y >= transBoundaries.maxY) {
            origin.y = oBoundaries.minY;
            transform.y = transBoundaries.maxY;
        }

        if (transform.y <= transBoundaries.minY) {
            origin.y = oBoundaries.maxY;
            transform.y = transBoundaries.minY;
        }

        var transCss = {};
        transCss[CSS_TRANSFORM] = transform.toString();
        transCss[CSS_TRANS_ORG] = origin.toString();
        css(self.elements.img, transCss);

        _debouncedOverlay.call(self);
        _triggerUpdate.call(self);
    }

    function _getVirtualBoundaries(viewport) {
        var self = this,
            scale = self._currentZoom,
            vpWidth = viewport.width,
            vpHeight = viewport.height,
            centerFromBoundaryX = self.options.boundary.width / 2,
            centerFromBoundaryY = self.options.boundary.height / 2,
            originalImgWidth = self._originalImageWidth,
            originalImgHeight = self._originalImageHeight,
            curImgWidth = originalImgWidth * scale,
            curImgHeight = originalImgHeight * scale,
            halfWidth = vpWidth / 2,
            halfHeight = vpHeight / 2;


        var maxX = ((halfWidth / scale) - centerFromBoundaryX) * -1;
        var minX = maxX - ((curImgWidth * (1 / scale)) - (vpWidth * (1 / scale)));

        var maxY = ((halfHeight / scale) - centerFromBoundaryY) * -1;
        var minY = maxY - ((curImgHeight * (1 / scale)) - (vpHeight * (1 / scale)));

        var originMinX = (1 / scale) * halfWidth;
        var originMaxX = (curImgWidth * (1 / scale)) - originMinX;

        var originMinY = (1 / scale) * halfHeight;
        var originMaxY = (curImgHeight * (1 / scale)) - originMinY;

        return {
            translate: {
                maxX: maxX,
                minX: minX,
                maxY: maxY,
                minY: minY
            },
            origin: {
                maxX: originMaxX,
                minX: originMinX,
                maxY: originMaxY,
                minY: originMinY
            }
        };
    }

    function _updateCenterPoint() {
        var self = this,
            scale = self._currentZoom,
            data = self.elements.img.getBoundingClientRect(),
            vpData = self.elements.viewport.getBoundingClientRect(),
            transform = Transform.parse(self.elements.img.style[CSS_TRANSFORM]),
            pc = new TransformOrigin(self.elements.img),
            top = (vpData.top - data.top) + (vpData.height / 2),
            left = (vpData.left - data.left) + (vpData.width / 2),
            center = {},
            adj = {};

        center.y = top / scale;
        center.x = left / scale;

        adj.y = (center.y - pc.y) * (1 - scale);
        adj.x = (center.x - pc.x) * (1 - scale);

        transform.x -= adj.x;
        transform.y -= adj.y;

        var newCss = {};
        newCss[CSS_TRANS_ORG] = center.x + 'px ' + center.y + 'px';
        newCss[CSS_TRANSFORM] = transform.toString();
        css(self.elements.img, newCss);
    }

    function _initDraggable() {
        var self = this,
            isDragging = false,
            originalX,
            originalY,
            originalDistance,
            vpRect;

        function mouseDown(ev) {
            ev.preventDefault();
            if (isDragging) return;
            isDragging = true;
            originalX = ev.pageX;
            originalY = ev.pageY;
            transform = Transform.parse(self.elements.img);
            window.addEventListener('mousemove', mouseMove);
            window.addEventListener('touchmove', mouseMove);
            window.addEventListener('mouseup', mouseUp);
            window.addEventListener('touchend', mouseUp);
            document.body.style[CSS_USERSELECT] = 'none';
            vpRect = self.elements.viewport.getBoundingClientRect();
        }

        function mouseMove(ev) {
            ev.preventDefault();
            var pageX = ev.pageX || ev.touches[0].pageX,
                pageY = ev.pageY || ev.touches[0].pageY,
                deltaX = pageX - originalX,
                deltaY = pageY - originalY,
                imgRect = self.elements.img.getBoundingClientRect(),
                top = transform.y + deltaY,
                left = transform.x + deltaX,
                newCss = {};

            if (ev.type == 'touchmove') {
                if (ev.touches.length > 1) {
                    var touch1 = ev.touches[0];
                    var touch2 = ev.touches[1];
                    var dist = Math.sqrt((touch1.pageX - touch2.pageX) * (touch1.pageX - touch2.pageX) + (touch1.pageY - touch2.pageY) * (touch1.pageY - touch2.pageY));

                    if (!originalDistance) {
                        originalDistance = dist / self._currentZoom;
                    }

                    var scale = dist / originalDistance;

                    _setZoomerVal.call(self, scale);
                    dispatchChange(self.elements.zoomer);
                    return;
                }
            }

            if (vpRect.top > imgRect.top + deltaY && vpRect.bottom < imgRect.bottom + deltaY) {
                transform.y = top;
            }

            if (vpRect.left > imgRect.left + deltaX && vpRect.right < imgRect.right + deltaX) {
                transform.x = left;
            }

            newCss[CSS_TRANSFORM] = transform.toString();
            css(self.elements.img, newCss);
            _updateOverlay.call(self);
            originalY = pageY;
            originalX = pageX;
        }

        function mouseUp() {
            isDragging = false;
            window.removeEventListener('mousemove', mouseMove);
            window.removeEventListener('touchmove', mouseMove);
            window.removeEventListener('mouseup', mouseUp);
            window.removeEventListener('touchend', mouseUp);
            document.body.style[CSS_USERSELECT] = '';
            _updateCenterPoint.call(self);
            _triggerUpdate.call(self);
            originalDistance = 0;
        }

        self.elements.overlay.addEventListener('mousedown', mouseDown);
        self.elements.overlay.addEventListener('touchstart', mouseDown);
    }

    function _updateOverlay() {
        var self = this,
            boundRect = self.elements.boundary.getBoundingClientRect(),
            imgData = self.elements.img.getBoundingClientRect();

        css(self.elements.overlay, {
            width: imgData.width + 'px',
            height: imgData.height + 'px',
            top: (imgData.top - boundRect.top) + 'px',
            left: (imgData.left - boundRect.left) + 'px'
        });
    }
    var _debouncedOverlay = debounce(_updateOverlay, 500);

    function _triggerUpdate() {
        var self = this;
        if (_isVisible.call(self)) {
            self.options.update.call(self, self.get());
        }
    }

    function _isVisible() {
        return this.elements.img.offsetHeight > 0 && this.elements.img.offsetWidth > 0;
    }

    function _updatePropertiesFromImage() {
        var self = this,
            minZoom = 0,
            maxZoom = 1.5,
            initialZoom = 1,
            cssReset = {},
            img = self.elements.img,
            zoomer = self.elements.zoomer,
            transformReset = new Transform(0, 0, initialZoom),
            originReset = new TransformOrigin(),
            isVisible = _isVisible.call(self),
            imgData,
            vpData,
            boundaryData,
            minW,
            minH;

        if (!isVisible || self.data.bound) {
            // if the croppie isn't visible or it doesn't need binding
            return;
        }

        self.data.bound = true;
        cssReset[CSS_TRANSFORM] = transformReset.toString();
        cssReset[CSS_TRANS_ORG] = originReset.toString();
        css(img, cssReset);

        imgData = img.getBoundingClientRect();
        vpData = self.elements.viewport.getBoundingClientRect();
        boundaryData = self.elements.boundary.getBoundingClientRect();
        self._originalImageWidth = imgData.width;
        self._originalImageHeight = imgData.height;

        if (self.options.showZoom) {
            minW = vpData.width / imgData.width;
            minH = vpData.height / imgData.height;
            minZoom = Math.max(minW, minH);

            if (minZoom > maxZoom) {
                maxZoom = minZoom + 1;
            }

            zoomer.min = fix(minZoom)
            zoomer.max = fix(maxZoom);
            initialZoom = Math.max((boundaryData.width / imgData.width), (boundaryData.height / imgData.height));
            _setZoomerVal.call(self, initialZoom);
            dispatchChange(zoomer);
        }

        self._currentZoom = transformReset.scale = initialZoom;
        cssReset[CSS_TRANSFORM] = transformReset.toString();
        css(img, cssReset)

        if (self.data.points.length) {
            _bindPoints.call(self, self.data.points);
        }
        else {
            _centerImage.call(self);
        }

        
        _updateOverlay.call(self);
    }

    function _bindPoints(points) {
        if (points.length != 4) {
            throw "Croppie - Invalid number of points supplied: " + points;
        }
        var self = this,
            pointsWidth = points[2] - points[0],
            // pointsHeight = points[3] - points[1],
            vpData = self.elements.viewport.getBoundingClientRect(),
            boundRect = self.elements.boundary.getBoundingClientRect(),
            vpOffset = {
                left: vpData.left - boundRect.left,
                top: vpData.top - boundRect.top
            },
            scale = vpData.width / pointsWidth,
            originTop = points[1],
            originLeft = points[0],
            transformTop = (-1 * points[1]) + vpOffset.top,
            transformLeft = (-1 * points[0]) + vpOffset.left,
            newCss = {};

        newCss[CSS_TRANS_ORG] = originLeft + 'px ' + originTop + 'px';
        newCss[CSS_TRANSFORM] = new Transform(transformLeft, transformTop, scale).toString();
        css(self.elements.img, newCss);

        _setZoomerVal.call(self, scale);
        self._currentZoom = scale;
    }

    function _centerImage() {
        var self = this,
            imgDim = self.elements.img.getBoundingClientRect(),
            vpDim = self.elements.viewport.getBoundingClientRect(),
            boundDim = self.elements.boundary.getBoundingClientRect(),
            vpLeft = vpDim.left - boundDim.left,
            vpTop = vpDim.top - boundDim.top,
            w = vpLeft - ((imgDim.width - vpDim.width) / 2),
            h = vpTop - ((imgDim.height - vpDim.height) / 2),
            transform = new Transform(w, h, self._currentZoom);

        css(self.elements.img, CSS_TRANSFORM, transform.toString());
    }

    function _bind(options, cb) {
        var self = this,
            url,
            points = [];

        if (typeof (options) === 'string') {
            url = options;
            options = {};
        }
        else if (Array.isArray(options)) {
            points = options.slice();
        }
        else if (typeof (options) == 'undefined' && self.data.url) { //refreshing
            _updatePropertiesFromImage.call(self);
            _triggerUpdate.call(self);
            return null;
        }
        else {
            url = options.url;
            points = options.points || [];
        }

        self.data.bound = false;
        self.data.url = url || self.data.url;
        self.data.points = (points || self.data.points).map(function (p) {
            return parseFloat(p);
        });
        var prom = loadImage(url, self.elements.img);
        prom.then(function () {
            _updatePropertiesFromImage.call(self);
            _triggerUpdate.call(self);
            if (cb) {
                cb();
            }
        });
        return prom;
    }

    function fix(v) {
        return parseFloat(v).toFixed(2);
    }

    function _get() {
        var self = this,
            imgData = self.elements.img.getBoundingClientRect(),
            vpData = self.elements.viewport.getBoundingClientRect(),
            x1 = vpData.left - imgData.left,
            y1 = vpData.top - imgData.top,
            x2 = x1 + vpData.width,
            y2 = y1 + vpData.height,
            scale = self._currentZoom;

        if (scale === Infinity || isNaN(scale)) {
            scale = 1;
        }

        x1 = Math.max(0, x1 / scale);
        y1 = Math.max(0, y1 / scale);
        x2 = Math.max(0, x2 / scale);
        y2 = Math.max(0, y2 / scale);
       
        return {
            points: [fix(x1), fix(y1), fix(x2), fix(y2)],
            zoom: scale
        };
    }

    function _result(options) {
        var self = this,
            data = _get.call(self),
            opts = options || { type: 'canvas', size: 'viewport' },
            type = (typeof (opts) === 'string' ? opts : opts.type),
            size = opts.size || 'viewport',
            vpRect,
            prom;

        if (size === 'viewport') {
            vpRect = self.elements.viewport.getBoundingClientRect();
            data.outputWidth = vpRect.width;
            data.outputHeight = vpRect.height;
        }

        data.circle = self.options.viewport.type === 'circle';
        data.url = self.data.url;

        prom = new Promise(function (resolve, reject) {
            if (type === 'canvas') {
                loadImage(data.url).then(function (img) {
                    resolve(getCanvasImage(img, data));
                });
            }
            else {
                resolve(getHtmlImage(data));
            }
        });
        return prom;
    }

    function _refresh() {
        console.warn("Croppie.refresh() is deprecated.  Please use Croppie.bind() without any arguments instead.  refresh() will be removed in a later release.");
        _updatePropertiesFromImage.call(this);
    }

    function _destroy() {
        var self = this;
        self.element.removeChild(self.elements.boundary);
        if (self.options.showZoom) {
            self.element.removeChild(self.elements.zoomerWrap);
        }
        delete self.elements;
    }

    if (this.jQuery) {
        var $ = this.jQuery;
        $.fn.croppie = function (opts) {
            var ot = typeof opts;

            if (ot === 'string') {
                var args = Array.prototype.slice.call(arguments, 1);
                var singleInst = $(this).data('croppie');

                if (opts === 'get') {
                    return singleInst.get();
                }
                else if (opts === 'result') {
                    return singleInst.result.apply(singleInst, args);
                }

                return this.each(function () {
                    var i = $(this).data('croppie');
                    if (!i) return;

                    var method = i[opts];
                    if ($.isFunction(method)) {
                        method.apply(i, args);
                        if (opts === 'destroy') {
                            $(this).removeData('croppie');
                        }
                    }
                    else {
                        throw 'Croppie ' + opts + ' method not found';
                    }
                });
            }
            else {
                return this.each(function () {
                    var i = new Croppie(this, opts);
                    $(this).data('croppie', i);
                });
            }
        };
    }

    function Croppie(element, opts) {
        this.element = element;
        this.options = deepExtend({}, Croppie.defaults, opts);

        _create.call(this);
    }

    Croppie.defaults = {
        viewport: {
            width: 100,
            height: 100,
            type: 'square'
        },
        boundary: {
            width: 300,
            height: 300
        },
        customClass: '',
        showZoom: true,
        mouseWheelZoom: true,
        update: function () { }
    };

    deepExtend(Croppie.prototype, {
        bind: function (options, cb) {
            return _bind.call(this, options, cb);
        },
        get: function () {
            return _get.call(this);
        },
        result: function (type) {
            return _result.call(this, type);
        },
        refresh: function () {
            return _refresh.call(this);
        },
        destroy: function () {
            return _destroy.call(this);
        }
    });

    exports.Croppie = window.Croppie = Croppie;
}));
