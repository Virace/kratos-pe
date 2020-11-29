/* NProgress, (c) 2013, 2014 Rico Sta. Cruz - http://ricostacruz.com/nprogress
 * @license MIT */
!function(a,b){"function"==typeof define&&define.amd?define(b):"object"==typeof exports?module.exports=b():a.NProgress=b()}(this,function(){function c(a){return"object"==typeof HTMLElement?a instanceof HTMLElement:a&&"object"==typeof a&&1===a.nodeType&&"string"==typeof a.nodeName}function d(a,b,c){return b>a?b:a>c?c:a}function e(a){return 100*(-1+a)}function f(a,c,d){var f;return f="translate3d"===b.positionUsing?{transform:"translate3d("+e(a)+"%,0,0)"}:"translate"===b.positionUsing?{transform:"translate("+e(a)+"%,0)"}:{"margin-left":e(a)+"%"},f.transition="all "+c+"ms "+d,f}function i(a,b){var c="string"==typeof a?a:l(a);return c.indexOf(" "+b+" ")>=0}function j(a,b){var c=l(a),d=c+b;i(c,b)||(a.className=d.substring(1))}function k(a,b){var d,c=l(a);i(a,b)&&(d=c.replace(" "+b+" "," "),a.className=d.substring(1,d.length-1))}function l(a){return(" "+(a&&a.className||"")+" ").replace(/\s+/gi," ")}function m(a){a&&a.parentNode&&a.parentNode.removeChild(a)}var b,g,h,a={};return a.version="0.2.0",b=a.settings={minimum:.08,easing:"linear",positionUsing:"",speed:200,trickle:!0,trickleSpeed:200,showSpinner:!0,barSelector:'[role="bar"]',spinnerSelector:'[role="spinner"]',parent:"body",template:'<div class="bar" role="bar"><div class="peg"></div></div><div class="spinner" role="spinner"><div class="spinner-icon"></div></div>'},a.configure=function(a){var c,d;for(c in a)d=a[c],void 0!==d&&a.hasOwnProperty(c)&&(b[c]=d);return this},a.status=null,a.set=function(c){var i,j,k,l,e=a.isStarted();return c=d(c,b.minimum,1),a.status=1===c?null:c,i=a.render(!e),j=i.querySelector(b.barSelector),k=b.speed,l=b.easing,i.offsetWidth,g(function(d){""===b.positionUsing&&(b.positionUsing=a.getPositioningCSS()),h(j,f(c,k,l)),1===c?(h(i,{transition:"none",opacity:1}),i.offsetWidth,setTimeout(function(){h(i,{transition:"all "+k+"ms linear",opacity:0}),setTimeout(function(){a.remove(),d()},k)},k)):setTimeout(d,k)}),this},a.isStarted=function(){return"number"==typeof a.status},a.start=function(){a.status||a.set(0);var c=function(){setTimeout(function(){a.status&&(a.trickle(),c())},b.trickleSpeed)};return b.trickle&&c(),this},a.done=function(b){return b||a.status?a.inc(.3+.5*Math.random()).set(1):this},a.inc=function(b){var c=a.status;return c?c>1?void 0:("number"!=typeof b&&(b=c>=0&&.2>c?.1:c>=.2&&.5>c?.04:c>=.5&&.8>c?.02:c>=.8&&.99>c?.005:0),c=d(c+b,0,.994),a.set(c)):a.start()},a.trickle=function(){return a.inc()},function(){var b=0,c=0;a.promise=function(d){return d&&"resolved"!==d.state()?(0===c&&a.start(),b++,c++,d.always(function(){c--,0===c?(b=0,a.done()):a.set((b-c)/b)}),this):this}}(),a.render=function(d){var f,l,g,i,k;return a.isRendered()?document.getElementById("nprogress"):(j(document.documentElement,"nprogress-busy"),f=document.createElement("div"),f.id="nprogress",f.innerHTML=b.template,g=f.querySelector(b.barSelector),i=d?"-100":e(a.status||0),k=c(b.parent)?b.parent:document.querySelector(b.parent),h(g,{transition:"all 0 linear",transform:"translate3d("+i+"%,0,0)"}),b.showSpinner||(l=f.querySelector(b.spinnerSelector),l&&m(l)),k!=document.body&&j(k,"nprogress-custom-parent"),k.appendChild(f),f)},a.remove=function(){var a,d;k(document.documentElement,"nprogress-busy"),a=c(b.parent)?b.parent:document.querySelector(b.parent),k(a,"nprogress-custom-parent"),d=document.getElementById("nprogress"),d&&m(d)},a.isRendered=function(){return!!document.getElementById("nprogress")},a.getPositioningCSS=function(){var a=document.body.style,b="WebkitTransform"in a?"Webkit":"MozTransform"in a?"Moz":"msTransform"in a?"ms":"OTransform"in a?"O":"";return b+"Perspective"in a?"translate3d":b+"Transform"in a?"translate":"margin"},g=function(){function b(){var c=a.shift();c&&c(b)}var a=[];return function(c){a.push(c),1==a.length&&b()}}(),h=function(){function c(a){return a.replace(/^-ms-/,"ms-").replace(/-([\da-z])/gi,function(a,b){return b.toUpperCase()})}function d(b){var f,d,e,c=document.body.style;if(b in c)return b;for(d=a.length,e=b.charAt(0).toUpperCase()+b.slice(1);d--;)if(f=a[d]+e,f in c)return f;return b}function e(a){return a=c(a),b[a]||(b[a]=d(a))}function f(a,b,c){b=e(b),a.style[b]=c}var a=["Webkit","O","Moz","ms"],b={};return function(a,b){var d,e,c=arguments;if(2==c.length)for(d in b)e=b[d],void 0!==e&&b.hasOwnProperty(d)&&f(a,d,e);else f(a,c[1],c[2])}}(),a});

/*!
  * Bootstrap v4.5.3 (https://getbootstrap.com/)
  * Copyright 2011-2020 The Bootstrap Authors (https://github.com/twbs/bootstrap/graphs/contributors)
  * Licensed under MIT (https://github.com/twbs/bootstrap/blob/main/LICENSE)
  */
!function(t,e){"object"==typeof exports&&"undefined"!=typeof module?e(exports,require("jquery")):"function"==typeof define&&define.amd?define(["exports","jquery"],e):e((t="undefined"!=typeof globalThis?globalThis:t||self).bootstrap={},t.jQuery)}(this,(function(t,e){"use strict";function r(t){return t&&"object"==typeof t&&"default"in t?t:{default:t}}var i=r(e);function s(){return(s=Object.assign||function(t){for(var e=1;e<arguments.length;e++){var r=arguments[e];for(var i in r)Object.prototype.hasOwnProperty.call(r,i)&&(t[i]=r[i])}return t}).apply(this,arguments)}var o={TRANSITION_END:"bsTransitionEnd",getSelectorFromElement:function(t){var e=t.getAttribute("data-target");if(!e||"#"===e){var r=t.getAttribute("href");e=r&&"#"!==r?r.trim():""}try{return document.querySelector(e)?e:null}catch(t){return null}},isElement:function(t){return(t[0]||t).nodeType},typeCheckConfig:function(t,e,r){for(var i in r)if(Object.prototype.hasOwnProperty.call(r,i)){var s=r[i],n=e[i],l=n&&o.isElement(n)?"element":null===(a=n)||"undefined"==typeof a?""+a:{}.toString.call(a).match(/\s([a-z]+)/i)[1].toLowerCase();if(!new RegExp(s).test(l))throw new Error(t.toUpperCase()+': Option "'+i+'" provided type "'+l+'" but expected type "'+s+'".')}var a},jQueryDetection:function(){if("undefined"==typeof i.default)throw new TypeError("Bootstrap's JavaScript requires jQuery. jQuery must be included before Bootstrap's JavaScript.");var t=i.default.fn.jquery.split(" ")[0].split(".");if(t[0]<2&&t[1]<9||1===t[0]&&9===t[1]&&t[2]<1||t[0]>=4)throw new Error("Bootstrap's JavaScript requires at least jQuery v1.9.1 but less than v4.0.0")}};o.jQueryDetection(),i.default.event.special[o.TRANSITION_END]={bindType:"transitionend",delegateType:"transitionend"};var n="scrollspy",l={offset:10,method:"auto",target:""},a={offset:"number",method:"string",target:"(string|element)"},c=function(){function t(t,e){var r=this;this._element=t,this._scrollElement="BODY"===t.tagName?window:t,this._config=this._getConfig(e),this._selector=this._config.target+" .nav-link,"+this._config.target+" .list-group-item,"+this._config.target+" .dropdown-item",this._offsets=[],this._targets=[],this._activeTarget=null,this._scrollHeight=0,i.default(this._scrollElement).on("scroll.bs.scrollspy",(function(t){return r._process(t)})),this.refresh(),this._process()}var e=t.prototype;return e.refresh=function(){var t=this,e=this._scrollElement===this._scrollElement.window?"offset":"position",r="auto"===this._config.method?e:this._config.method,s="position"===r?this._getScrollTop():0;this._offsets=[],this._targets=[],this._scrollHeight=this._getScrollHeight(),[].slice.call(document.querySelectorAll(this._selector)).map((function(t){var e,n=o.getSelectorFromElement(t);if(n&&(e=document.querySelector(n)),e){var l=e.getBoundingClientRect();if(l.width||l.height)return[i.default(e)[r]().top+s,n]}return null})).filter((function(t){return t})).sort((function(t,e){return t[0]-e[0]})).forEach((function(e){t._offsets.push(e[0]),t._targets.push(e[1])}))},e._getConfig=function(t){if("string"!=typeof(t=s({},l,"object"==typeof t&&t?t:{})).target&&o.isElement(t.target)){var e=i.default(t.target).attr("id");e||(e=o.getUID(n),i.default(t.target).attr("id",e)),t.target="#"+e}return o.typeCheckConfig(n,t,a),t},e._getScrollTop=function(){return this._scrollElement===window?this._scrollElement.pageYOffset:this._scrollElement.scrollTop},e._getScrollHeight=function(){return this._scrollElement.scrollHeight||Math.max(document.body.scrollHeight,document.documentElement.scrollHeight)},e._getOffsetHeight=function(){return this._scrollElement===window?window.innerHeight:this._scrollElement.getBoundingClientRect().height},e._process=function(){var t=this._getScrollTop()+this._config.offset,e=this._getScrollHeight(),r=this._config.offset+e-this._getOffsetHeight();if(this._scrollHeight!==e&&this.refresh(),t>=r){var i=this._targets[this._targets.length-1];this._activeTarget!==i&&this._activate(i)}else{if(this._activeTarget&&t<this._offsets[0]&&this._offsets[0]>0)return this._activeTarget=null,void this._clear();for(var s=this._offsets.length;s--;){this._activeTarget!==this._targets[s]&&t>=this._offsets[s]&&("undefined"==typeof this._offsets[s+1]||t<this._offsets[s+1])&&this._activate(this._targets[s])}}},e._activate=function(t){this._activeTarget=t,this._clear();var e=this._selector.split(",").map((function(e){return e+'[data-target="'+t+'"],'+e+'[href="'+t+'"]'})),r=i.default([].slice.call(document.querySelectorAll(e.join(","))));r.hasClass("dropdown-item")?(r.closest(".dropdown").find(".dropdown-toggle").addClass("active"),r.addClass("active")):(r.addClass("active"),r.parents(".nav, .list-group").prev(".nav-link, .list-group-item").addClass("active"),r.parents(".nav, .list-group").prev(".nav-item").children(".nav-link").addClass("active")),i.default(this._scrollElement).trigger("activate.bs.scrollspy",{relatedTarget:t})},e._clear=function(){[].slice.call(document.querySelectorAll(this._selector)).filter((function(t){return t.classList.contains("active")})).forEach((function(t){return t.classList.remove("active")}))},t._jQueryInterface=function(e){return this.each((function(){var r=i.default(this).data("bs.scrollspy");if(r||(r=new t(this,"object"==typeof e&&e),i.default(this).data("bs.scrollspy",r)),"string"==typeof e){if("undefined"==typeof r[e])throw new TypeError('No method named "'+e+'"');r[e]()}}))},t}();i.default(window).on("load.bs.scrollspy.data-api",(function(){for(var t=[].slice.call(document.querySelectorAll('[data-spy="scroll"]')),e=t.length;e--;){var r=i.default(t[e]);c._jQueryInterface.call(r,r.data())}})),i.default.fn[n]=c._jQueryInterface,i.default.fn[n].Constructor=c,t.Scrollspy=c,t.Util=o,Object.defineProperty(t,"__esModule",{value:!0})}));

/*
 *  Bootstrap Auto-Hiding Navbar - v4.0.0
 *  An extension for Bootstrap's fixed navbar which hides the navbar while the page is scrolling downwards and shows it the other way. The plugin is able to show/hide the navbar programmatically as well.
 *  http://www.virtuosoft.eu/code/bootstrap-autohidingnavbar/
 *
 *  Made by István Ujj-Mészáros
 *  Under Apache License v2.0 License
 */

!function(s,t,e,o){var n,h="autoHidingNavbar",a=s(t),r=s(e),i=null,u=null,l=70,f=0,d=null,c=a.height(),g=!0,m={disableAutohide:!1,showOnUpscroll:!0,showOnBottom:!0,hideOffset:"auto",animationDuration:200,navbarOffset:0};function p(t,e){this.element=s(t),this.settings=s.extend({},m,e),this._defaults=m,this._name=h,this.init()}function w(t){if(g){t.element.addClass("navbar-hidden").animate({top:-1*parseInt(t.element.css("height"),10)+t.settings.navbarOffset},{queue:!1,duration:t.settings.animationDuration});try{s(".dropdown.open .dropdown-toggle, .dropdown.show .dropdown-toggle",t.element).dropdown("toggle")}catch(t){}g=!1,t.element.trigger("hide.autoHidingNavbar")}}function O(t){g||(t.element.removeClass("navbar-hidden").animate({top:0},{queue:!1,duration:t.settings.animationDuration}),g=!0,t.element.trigger("show.autoHidingNavbar"))}function b(t){t.settings.disableAutohide||(f=(new Date).getTime(),function(t){var e=a.scrollTop(),i=e-d;if(d=e,i<0){if(g)return;(t.settings.showOnUpscroll||e<=n)&&O(t)}else if(0<i){if(!g)return t.settings.showOnBottom&&e+c===r.height()&&O(t);n<=e&&w(t)}}(t))}p.prototype={init:function(){var t;return this.elements={navbar:this.element},this.setDisableAutohide(this.settings.disableAutohide),this.setShowOnUpscroll(this.settings.showOnUpscroll),this.setShowOnBottom(this.settings.showOnBottom),this.setHideOffset(this.settings.hideOffset),this.setAnimationDuration(this.settings.animationDuration),n="auto"===this.settings.hideOffset?parseInt(this.element.css("height"),10):this.settings.hideOffset,t=this,r.on("scroll."+h,function(){(new Date).getTime()-f>l?b(t):(clearTimeout(i),i=setTimeout(function(){b(t)},l))}),a.on("resize."+h,function(){clearTimeout(u),u=setTimeout(function(){c=a.height()},l)}),this.element},setDisableAutohide:function(t){return this.settings.disableAutohide=t,this.element},setShowOnUpscroll:function(t){return this.settings.showOnUpscroll=t,this.element},setShowOnBottom:function(t){return this.settings.showOnBottom=t,this.element},setHideOffset:function(t){return this.settings.hideOffset=t,this.element},setAnimationDuration:function(t){return this.settings.animationDuration=t,this.element},show:function(){return O(this),this.element},hide:function(){return w(this),this.element},destroy:function(){return r.off("."+h),a.off("."+h),O(this),s.data(this,"plugin_"+h,null),this.element}},s.fn[h]=function(e){var i,n=arguments;return e===o||"object"==typeof e?this.each(function(){s.data(this,"plugin_"+h)||s.data(this,"plugin_"+h,new p(this,e))}):"string"==typeof e&&"_"!==e[0]&&"init"!==e?(this.each(function(){var t=s.data(this,"plugin_"+h);t instanceof p&&"function"==typeof t[e]&&(i=t[e].apply(t,Array.prototype.slice.call(n,1)))}),i!==o?i:this):void 0}}(jQuery,window,document);

(()=>{const m=["data:","/wp-","/rss","/feed","/sitemap.xml","/sitemap.html","#respond","#toc_i-","#toc-","javascript:",".pdf",".zip",".rar",".7z",".jpg",".png",".gif",".bmp","#nav"],a="main",s={click:{"*:not(#comments-nav)>a[target!=_blank]":function(t){for(let n in m)if(t.indexOf(m[n])>=0)return!0;return l(t,"pagelink"),!1},"#comments-nav a, a.comment-reply-link":function(t){return l(t,"comtpagenav"),!1},".comment-content-link > a[target!=_blank]":function(t){return l(t,"comment"),!1}},submit:{"#searchform":function(){return l(this.action+"?s="+$(this).find("#search").val(),"search"),!1}}};for(let t in s)for(let n in s[t])$(document.body).on(t,n,function(i){i.preventDefault(),console.log(t,n);const e=$(this).attr("href");return e?s[t][n](e):!1});function d(t,n){const i=$("body,html");let e=$(".navbar")[0].offsetHeight;if(n==="comtpagenav")i.animate({scrollTop:$("#comments").offset().top-e},1e3);else if(n==="pagelink"||n==="search")t.indexOf("#")===-1&&i.animate({scrollTop:$(a).offset().top-e},1e3);else if(n==="comment"){const o=/#comment-\d+/.exec(t);if(o.length>0){const r=$(o[0]).offset().top;$("html,body").animate({scrollTop:r-e-5},1e3)}}}$(function(){window.addEventListener("popstate",function(t){t.state&&(document.title=t.state.title,$(a).html(t.state.html),window.load=window.pjax_reload())})});function c(t,n){return t.filter(n).add(t.find(n))}function u(t){return $.parseHTML(t,document,!0)}function h(t){return $(u(t))}function l(t,n,i){NProgress.start(),$.ajax({url:t,type:"GET",data:i,beforeSend:function(){const e=window.document;history.replaceState({url:e.location.href,title:e.title,html:$(e).find(a).html()},e.title,e.location.href)},success:function(e){let o=h(e);const r=c(o,a).html();if(document.title=c(o,"title").text(),window.history.pushState({url:t,title:c(o,"title").text(),html:r},c(o,"title").text(),t),$(".introduce").html(c(o,".introduce").html()),n==="pagelink"){$(a).html(r);const f=window.location.hash.substring(location.hash.indexOf("#")+1);f&&$("body,html").animate({scrollTop:$("#"+f).offset().top-61},600)}else n==="search"?($(a).html(r),$("#searchform input").val("")):n==="comtpagenav"&&$("#comments").html(c(o,"#comments").html());window.load=window.pjax_reload(),NProgress.done(),d(t,n)},error:function(e){location.href=t}})}})();
/*!
 * Kratos-ajax
 * Virace <Virace@aliyun.com>
 */


(()=>{(function(){"use strict";const a="1.0.1",t=function(){const e=$(".dropdown-menu");function s(){const r=window.scrollY,d=$(".k-nav.navbar");r>0?(d.addClass("navbar-scrolled"),e.addClass("navbar-scrolled")):(d.removeClass("navbar-scrolled"),e.removeClass("navbar-scrolled"))}$(window).scroll(s),$(document).ready(s),$(".auto-hiding-navbar").autoHidingNavbar({animationDuration:500,showOnBottom:!1,showOnUpscroll:!0});const n=$(".dropdown"),l=$(".dropdown-toggle");$(window).on("load resize",function(){if(this.matchMedia("(min-width: 992px)").matches){n.hover(function(){const d=$(this);d.find(e).stop().fadeIn("fast"),d.find(l).attr("aria-expanded","true")},function(){const d=$(this);d.find(e).stop().fadeOut("fast"),d.find(l).attr("aria-expanded","false")});let r=$(".navbar")[0].offsetHeight+75;$("body").scrollspy({target:"#toc",offset:r})}else n.off("mouseenter mouseleave")});const o=$("#navbarResponsive").clone();o.removeAttr("id"),o.attr({class:"mobi-navbar"}),o.find("*").removeAttr("id"),o.find("*:not(.dropdown)").removeAttr("class"),o.find("*").removeAttr("role"),o.find("*").removeAttr("aria-current"),o.find("*").removeAttr("aria-haspopup"),o.find("*").removeAttr("aria-expanded"),o.find("*").removeAttr("data-toggle"),o.find("*").removeAttr("aria-labelledby"),o.find("> ul").attr({class:"mobi-menu"}),o.find("li > ul").attr({class:"mobi-menu-sub"}),o.find(".dropdown").attr({class:"dropdown"}),$("header.k-header").prepend(o);const h=$(".mobi-navbar li.dropdown");for(let r=0;r<h.length;r++){const d=$(h[r]);d.find(".m-dropdown").length===0&&d.append('<div class="m-dropdown"><span class="vicon i-down"></span></div>')}$("#navbutton").on("click",function(r){$("body").toggleClass("menu-on"),$(".navbar-on-shadow").length===0&&$("#content").append('<div class="navbar-on-shadow"></div>')}),$(".m-dropdown").on("click",function(r){const d=$(this).parent();d.find("> .mobi-menu-sub").slideToggle("fast"),d.toggleClass("menu-open")}),$("body").on("click",".navbar-on-shadow",function(){$("#navbutton").trigger("click")}),$(".mobi-menu").on("click","a",function(){$("#navbutton").trigger("click")})},c=function(){const e=$(".gotop"),s=$(".navbar")[0].offsetHeight+75,n=$(window);n.scroll(function(){n.scrollTop()>s?e.fadeIn(300):e.fadeOut(300)}),e.on("click",function(l){return l.preventDefault(),$("html, body").animate({scrollTop:$("html").offset().top},500),!1})},i=function(){const e=$(".wechat .i-wechat"),s=$(".wechat-pic");e.hover(function(){s.stop().fadeIn("fast")},function(){s.stop().fadeOut("fast")})},f=function(){$(document.body).on("click",".nav-tabs > a.nav-item.nav-link",function(e){$(".nav-tabs > a.nav-item.nav-link").removeClass("active"),$(this).addClass("active");const s=$($(this).attr("href"));let n=T(s);$(".tab-content .tab-pane").removeClass("show"),setTimeout(()=>{s.siblings().removeClass("active"),s.addClass("active")},n),setTimeout(()=>{s.addClass("show")},n*2-50),e.preventDefault()})},m=function(){$(document.body).on("click",'.btn[data-toggle="modal"]',function(e){const s=$(this).attr("data-target");let n=$(s);const l=$("#content .container");if(n){n.removeClass("hide").addClass("show"),l.removeClass("hide").addClass("show"),$(document.body).addClass("modal-active"),n.find(".modal-content>*").on("click",function(h){h.stopPropagation()});const o=function(){n.addClass("hide"),l.addClass("hide").removeClass("show"),$(document.body).removeClass("modal-active")};n.find(".btn-close").one("click",o),n.one("click",o)}else return 0})},g=function(){const e=$(".smile");function s(){e.hasClass("show")||($(".smile img").each(function(){const n=$(this);n.attr("src",n.attr("data-src")),n.removeAttr("data-src")}),e.addClass("show"))}$("#addsmile").on("click",function(n){return e.fadeToggle(300),s(),$(document).one("click",function(){e.fadeToggle(300)}),n.stopPropagation(),!1})},u=function(){$.fn.postLike=function(){if($(this).hasClass("done"))return C.msg(kratos.repeat),!1;$(this).addClass("done"),C.msg(kratos.thanks);const e=$(this).data("id"),s=$(this).data("action"),n={action:"love",um_id:e,um_action:s};return $.post(kratos.site+"/wp-admin/admin-ajax.php",n,function(l){}),!1},$(document).on("click",".btn-thumbs",function(){$(this).postLike()})},v=function(){$(".choose-pay input[type='radio']").on("click",function(){const e=$(this).attr("id");e==="alipay"&&($(".qr-pay #alipay_qr").removeClass("d-none"),$(".qr-pay #wechat_qr").addClass("d-none")),e==="wechatpay"&&($(".qr-pay #alipay_qr").addClass("d-none"),$(".qr-pay #wechat_qr").removeClass("d-none"))})},k=function(){$(document).on("click",".acheader",function(e){const s=$(this);s.closest(".accordion").find(".contents").slideToggle(300),s.closest(".accordion").toggleClass("active")})},j=function(){console.log("Kratos-pe v"+a+"https://github.com/Virace/kratos-pe")},b=function(){const e=document.getElementById("content");function s(){lightGallery(e,{mode:"lg-slide-circular",selector:"figure.wp-block-image, div.wp-block-image > figure, .blocks-gallery-item figure"})}const n=$(".article .content"),l=$(".article .content img");if(l.length>=1&&!n.hasClass("lightgallery"))if(n.addClass("lightgallery"),l.each(function(o){const h=$(this.parentNode);h.attr("data-src",this.src);const r=h.find("figcaption");r.length&&h.attr("data-sub-html","<h4>"+r.text()+"</h4>")}),typeof lightGallery=="undefined"){const o=kratos.directory,h=[o+"/assets/css/lightgallery.js/lightgallery.min.css",o+"/assets/css/lightgallery.js/lg-transitions.min.css"],r=[o+"/assets/js/lightgallery.js/lightgallery.min.js",o+"/assets/js/lightgallery.js/lg-thumbnail.min.js"];p.LoadCSS(h,"").then(),p.LoadJS(r).then(s)}else{try{window.lgData[e.getAttribute("lg-uid")].destroy(!0)}catch(o){}s()}},y=function(){const e=$(".article .content img"),s=$(".article .content"),n=kratos.loading;if(e.length>=1&&!s.hasClass("photoLazy")){s.addClass("photoLazy"),e.each(function(){const o=$(this),h=this.src;o.attr("data-lazy",h),o.attr("src",n)});const l=1e3;setTimeout(function(){e.each(function(){const o=$(this);o.attr("src",o.attr("data-lazy")),o.removeAttr("data-lazy")})},l)}},w=function(e){function s(){document.querySelectorAll("pre code").forEach(n=>{hljs.highlightBlock(n),hljs.lineNumbersBlock(n),hljs.addCopyButton(n)})}if($("pre code").length>=1)if(typeof hljs=="undefined"){const n=kratos.directory;p.LoadCSS([n+"/assets/css/highlight/style.min.css"],"").then(o=>{});let l=[n+"/assets/js/highlight/highlight.pack.js",n+"/assets/js/highlight/highlightjs-copy-button.min.js",n+"/assets/js/highlight/highlightjs-line-numbers.min.js"];p.LoadJS(l,"").then(s)}else s()};window.pjax_reload=function(){i(),g(),u(),v(),w(),b(),m(),f(),y()},$(function(){k(),t(),m(),f(),c(),i(),g(),u(),v(),j(),b(),w(),y()})})();const p={_load:function(a,t,c){let i={};switch(a){case"css":{i={tag:"link",attr:{type:"text/css",rel:"stylesheet"}};break}case"js":{i={tag:"script",attr:{type:"text/javascript"}};break}}return new Promise(function(f){t.forEach(function(m,g){let u=document.createElement(i.tag);Object.assign(u,i.attr),a==="css"?u.href=(c||"")+m:u.src=(c||"")+m,u.async=!1,g===t.length-1&&(u.onreadystatechange=f,u.onload=f),document.body.appendChild(u)})})},LoadJS:function(a,t){return this._load("js",a,t)},LoadCSS:function(a,t){return this._load("css",a,t)}},C={_initEvent:function(a){const t=document.createElement("div");return t.setAttribute("class","toast"),t.style.display="none",t.innerHTML='<div class="toast-body text-center">'+a+"</div>",document.body.appendChild(t),$(t)},msg:function(a,t=2e3,c=null){const i=this._initEvent(a,t);i.css("left",document.documentElement.clientWidth/2-i.innerWidth()/2),i.css("top",document.documentElement.clientHeight/2-i.innerHeight()/2),i.fadeIn(300),setTimeout(function(){typeof c=="function"&&c(),i.fadeOut(300),setTimeout(function(){i.remove()},300)},t)}};function T(a){if(!a)return 0;let t=$(a).css("transition-duration"),c=$(a).css("transition-delay");const i=parseFloat(t),f=parseFloat(c);return!i&&!f?0:(t=t.split(",")[0],c=c.split(",")[0],(parseFloat(t)+parseFloat(c))*1e3)}window.grin=function(a){let t;if(a=" "+a+" ",document.getElementById("comment")&&document.getElementById("comment").type=="textarea")t=document.getElementById("comment");else return!1;if(document.selection)t.focus(),sel=document.selection.createRange(),sel.text=a,t.focus();else if(t.selectionStart||t.selectionStart=="0"){var c=t.selectionStart,i=t.selectionEnd,f=i;t.value=t.value.substring(0,c)+a+t.value.substring(i,t.value.length),f+=a.length,t.focus(),t.selectionStart=f,t.selectionEnd=f}else t.value+=a,t.focus()};$("body").on("click",'a[href^="#toc"],area[href^="#toc"]',function(){if(location.pathname.replace(/^\//,"")===this.pathname.replace(/^\//,"")&&location.hostname===this.hostname){let a=$(this.hash),t=$(".navbar")[0].offsetHeight;if(a=a.length&&a||$("[name="+this.hash.slice(1)+"]"),a.length){const c=a.offset().top;return $("html,body").stop().animate({scrollTop:c-t-5},1e3),!1}}});})();
/*!
 * Kratos
 * Seaton Jiang <seaton@vtrois.com> and Virace <Virace@aliyun.com>
 */


