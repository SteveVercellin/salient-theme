// source --> https://reinnovame.com/wp-content/plugins/ewww-image-optimizer/includes/lazysizes.min.js?ver=600 
var ewww_webp_supported=!1;function lazysizesWebP(e,t){var a=new Image;a.onload=function(){ewww_webp_supported=0<a.width&&0<a.height,t()},a.onerror=function(){t()},a.src="data:image/webp;base64,"+{alpha:"UklGRkoAAABXRUJQVlA4WAoAAAAQAAAAAAAAAAAAQUxQSAwAAAARBxAR/Q9ERP8DAABWUDggGAAAABQBAJ0BKgEAAQAAAP4AAA3AAP7mtQAAAA==",animation:"UklGRlIAAABXRUJQVlA4WAoAAAASAAAAAAAAAAAAQU5JTQYAAAD/////AABBTk1GJgAAAAAAAAAAAAAAAAAAAGQAAABWUDhMDQAAAC8AAAAQBxAREYiI/gcA"}[e]}function shouldAutoScale(e){if(1==eio_lazy_vars.skip_autoscale)return!1;if(e.hasAttributes())for(var t=e.attributes,a=/skip-autoscale/,i=t.length-1;0<=i;i--){if(a.test(t[i].name))return!1;if(a.test(t[i].value))return!1}return!0}function constrainSrc(e,t,a,i){if(null===e)return e;var r=/w=(\d+)/,n=/fit=(\d+),(\d+)/,o=/resize=(\d+),(\d+)/,s=decodeURIComponent(e);if("undefined"==typeof eio_lazy_vars&&(eio_lazy_vars={exactdn_domain:".exactdn.com"}),0<e.search("\\?")&&0<e.search(eio_lazy_vars.exactdn_domain)){var l=o.exec(s);if(l&&t<l[1])return s.replace(o,"resize="+t+","+a);var c=r.exec(e);if(c&&t<=c[1]){if("bg-cover"!==i&&"img-crop"!==i)return e.replace(r,"w="+t);var d=c[1]-t;return 20<d||a<1080?e.replace(r,"resize="+t+","+a):e}var u=n.exec(s);if(u&&t<u[1]){if("bg-cover"!==i&&"img-crop"!==i)return s.replace(n,"fit="+t+","+a);var f=u[1]-t,A=u[2]-a;return 20<f||20<A?e.replace(r,"resize="+t+","+a):e}if(!c&&!u&&!l)return"img"===i?e+"&fit="+t+","+a:"bg-cover"===i||"img-crop"===i?e+"?resize="+t+","+a:t<a?e+"&h="+a:e+"&w="+t}return-1==e.search("\\?")&&0<e.search(eio_lazy_vars.exactdn_domain)?"img"===i?e+"?fit="+t+","+a:"bg-cover"===i||"img-crop"===i?e+"?resize="+t+","+a:t<a?e+"?h="+a:e+"?w="+t:e}window.lazySizesConfig=window.lazySizesConfig||{},window.lazySizesConfig.init=!1,function(e,t){var a=function(i,A,n){"use strict";var g,h;if(function(){var e,t={lazyClass:"lazyload",loadedClass:"lazyloaded",loadingClass:"lazyloading",preloadClass:"lazypreload",errorClass:"lazyerror",autosizesClass:"lazyautosizes",srcAttr:"data-src",srcsetAttr:"data-srcset",sizesAttr:"data-sizes",minSize:40,customMedia:{},init:!0,expFactor:1.5,hFac:.8,loadMode:2,loadHidden:!0,ricTimeout:0,throttleDelay:125};for(e in h=i.lazySizesConfig||i.lazysizesConfig||{},t)e in h||(h[e]=t[e])}(),!A||!A.getElementsByClassName)return{init:function(){},cfg:h,noSupport:!0};var z=A.documentElement,r=i.HTMLPictureElement,o="addEventListener",v="getAttribute",e=i[o].bind(i),u=i.setTimeout,a=i.requestAnimationFrame||u,s=i.requestIdleCallback,f=/^picture$/i,l=["load","error","lazyincluded","_lazyloaded"],c={},p=Array.prototype.forEach,d=function(e,t){return c[t]||(c[t]=new RegExp("(\\s|^)"+t+"(\\s|$)")),c[t].test(e[v]("class")||"")&&c[t]},m=function(e,t){d(e,t)||e.setAttribute("class",(e[v]("class")||"").trim()+" "+t)},y=function(e,t){var a;(a=d(e,t))&&e.setAttribute("class",(e[v]("class")||"").replace(a," "))},b=function(t,a,e){var i=e?o:"removeEventListener";e&&b(t,a),l.forEach(function(e){t[i](e,a)})},w=function(e,t,a,i,r){var n=A.createEvent("Event");return a||(a={}),a.instance=g,n.initEvent(t,!i,!r),n.detail=a,e.dispatchEvent(n),n},_=function(e,t){var a;!r&&(a=i.picturefill||h.pf)?(t&&t.src&&!e[v]("srcset")&&e.setAttribute("srcset",t.src),a({reevaluate:!0,elements:[e]})):t&&t.src&&(e.src=t.src)},C=function(e,t){return(getComputedStyle(e,null)||{})[t]},E=function(e,t,a){for(a=a||e.offsetWidth;a<h.minSize&&t&&!e._lazysizesWidth;)a=t.offsetWidth,t=t.parentNode;return a},S=(we=[],_e=[],Ce=we,Ee=function(){var e=Ce;for(Ce=we.length?_e:we,be=!(ye=!0);e.length;)e.shift()();ye=!1},Se=function(e,t){ye&&!t?e.apply(this,arguments):(Ce.push(e),be||(be=!0,(A.hidden?u:a)(Ee)))},Se._lsFlush=Ee,Se),t=function(a,e){return e?function(){S(a)}:function(){var e=this,t=arguments;S(function(){a.apply(e,t)})}},W=function(e){var t,a,i=function(){t=null,e()},r=function(){var e=n.now()-a;e<99?u(r,99-e):(s||i)(i)};return function(){a=n.now(),t||(t=u(r,99))}},x=(ee=/^img$/i,te=/^iframe$/i,ae="onscroll"in i&&!/(gle|ing)bot/.test(navigator.userAgent),ie=0,re=0,ne=-1,oe=function(e){re--,(!e||re<0||!e.target)&&(re=0)},se=function(e){return null==$&&($="hidden"==C(A.body,"visibility")),$||!("hidden"==C(e.parentNode,"visibility")&&"hidden"==C(e,"visibility"))},le=function(e,t){var a,i=e,r=se(e);for(I-=t,G+=t,J-=t,O+=t;r&&(i=i.offsetParent)&&i!=A.body&&i!=z;)(r=0<(C(i,"opacity")||1))&&"visible"!=C(i,"overflow")&&(a=i.getBoundingClientRect(),r=O>a.left&&J<a.right&&G>a.top-1&&I<a.bottom+1);return r},ce=function(){var e,t,a,i,r,n,o,s,l,c,d,u,f=g.elements;if((H=h.loadMode)&&re<8&&(e=f.length)){for(t=0,ne++;t<e;t++)if(f[t]&&!f[t]._lazyRace)if(!ae||g.prematureUnveil&&g.prematureUnveil(f[t]))ze(f[t]);else if((s=f[t][v]("data-expand"))&&(n=1*s)||(n=ie),c||(c=!h.expand||h.expand<1?500<z.clientHeight&&500<z.clientWidth?500:370:h.expand,g._defEx=c,d=c*h.expFactor,u=h.hFac,$=null,ie<d&&re<1&&2<ne&&2<H&&!A.hidden?(ie=d,ne=0):ie=1<H&&1<ne&&re<6?c:0),l!==n&&(U=innerWidth+n*u,F=innerHeight+n,o=-1*n,l=n),a=f[t].getBoundingClientRect(),(G=a.bottom)>=o&&(I=a.top)<=F&&(O=a.right)>=o*u&&(J=a.left)<=U&&(G||O||J||I)&&(h.loadHidden||se(f[t]))&&(P&&re<3&&!s&&(H<3||ne<4)||le(f[t],n))){if(ze(f[t]),r=!0,9<re)break}else!r&&P&&!i&&re<4&&ne<4&&2<H&&(k[0]||h.preloadAfterLoad)&&(k[0]||!s&&(G||O||J||I||"auto"!=f[t][v](h.sizesAttr)))&&(i=k[0]||f[t]);i&&!r&&ze(i)}},q=ce,V=0,X=h.throttleDelay,Y=h.ricTimeout,K=function(){j=!1,V=n.now(),q()},Z=s&&49<Y?function(){s(K,{timeout:Y}),Y!==h.ricTimeout&&(Y=h.ricTimeout)}:t(function(){u(K)},!0),de=function(e){var t;(e=!0===e)&&(Y=33),j||(j=!0,(t=X-(n.now()-V))<0&&(t=0),e||t<9?Z():u(Z,t))},ue=function(e){var t=e.target;t._lazyCache?delete t._lazyCache:(oe(e),m(t,h.loadedClass),y(t,h.loadingClass),b(t,Ae),w(t,"lazyloaded"))},fe=t(ue),Ae=function(e){fe({target:e.target})},ge=function(e){var t,a=e[v](h.srcsetAttr);(t=h.customMedia[e[v]("data-media")||e[v]("media")])&&e.setAttribute("media",t),a&&e.setAttribute("srcset",a)},he=t(function(t,e,a,i,r){var n,o,s,l,c,d;(c=w(t,"lazybeforeunveil",e)).defaultPrevented||(i&&(a?m(t,h.autosizesClass):t.setAttribute("sizes",i)),o=t[v](h.srcsetAttr),n=t[v](h.srcAttr),r&&(s=t.parentNode,l=s&&f.test(s.nodeName||"")),d=e.firesLoad||"src"in t&&(o||n||l),c={target:t},m(t,h.loadingClass),d&&(clearTimeout(D),D=u(oe,2500),b(t,Ae,!0)),l&&p.call(s.getElementsByTagName("source"),ge),o?t.setAttribute("srcset",o):n&&!l&&(te.test(t.nodeName)?function(t,a){try{t.contentWindow.location.replace(a)}catch(e){t.src=a}}(t,n):t.src=n),r&&(o||l)&&_(t,{src:n})),t._lazyRace&&delete t._lazyRace,y(t,h.lazyClass),S(function(){var e=t.complete&&1<t.naturalWidth;d&&!e||(e&&m(t,"ls-is-cached"),ue(c),t._lazyCache=!0,u(function(){"_lazyCache"in t&&delete t._lazyCache},9)),"lazy"==t.loading&&re--},!0)}),ze=function(e){if(!e._lazyRace){var t,a=ee.test(e.nodeName),i=a&&(e[v](h.sizesAttr)||e[v]("sizes")),r="auto"==i;(!r&&P||!a||!e[v]("src")&&!e.srcset||e.complete||d(e,h.errorClass)||!d(e,h.lazyClass))&&(t=w(e,"lazyunveilread").detail,r&&M.updateElem(e,!0,e.offsetWidth),e._lazyRace=!0,re++,he(e,t,r,i,a))}},ve=W(function(){h.loadMode=3,de()}),pe=function(){3==h.loadMode&&(h.loadMode=2),ve()},me=function(){P||(n.now()-T<999?u(me,999):(P=!0,h.loadMode=3,de(),e("scroll",pe,!0)))},{_:function(){T=n.now(),g.elements=A.getElementsByClassName(h.lazyClass),k=A.getElementsByClassName(h.lazyClass+" "+h.preloadClass),e("scroll",de,!0),e("resize",de,!0),e("pageshow",function(e){if(e.persisted){var t=A.querySelectorAll("."+h.loadingClass);t.length&&t.forEach&&a(function(){t.forEach(function(e){e.complete&&ze(e)})})}}),i.MutationObserver?new MutationObserver(de).observe(z,{childList:!0,subtree:!0,attributes:!0}):(z[o]("DOMNodeInserted",de,!0),z[o]("DOMAttrModified",de,!0),setInterval(de,999)),e("hashchange",de,!0),["focus","mouseover","click","load","transitionend","animationend"].forEach(function(e){A[o](e,de,!0)}),/d$|^c/.test(A.readyState)?me():(e("load",me),A[o]("DOMContentLoaded",de),u(me,2e4)),g.elements.length?(ce(),S._lsFlush()):de()},checkElems:de,unveil:ze,_aLSL:pe}),M=(R=t(function(e,t,a,i){var r,n,o;if(e._lazysizesWidth=i,i+="px",e.setAttribute("sizes",i),f.test(t.nodeName||""))for(r=t.getElementsByTagName("source"),n=0,o=r.length;n<o;n++)r[n].setAttribute("sizes",i);a.detail.dataAttr||_(e,a.detail)}),L=function(e,t,a){var i,r=e.parentNode;r&&(a=E(e,r,a),(i=w(e,"lazybeforesizes",{width:a,dataAttr:!!t})).defaultPrevented||(a=i.detail.width)&&a!==e._lazysizesWidth&&R(e,r,i,a))},Q=W(function(){var e,t=N.length;if(t)for(e=0;e<t;e++)L(N[e])}),{_:function(){N=A.getElementsByClassName(h.autosizesClass),e("resize",Q)},checkElems:Q,updateElem:L}),B=function(){!B.i&&A.getElementsByClassName&&(B.i=!0,M._(),x._())};var N,R,L,Q;var k,P,D,H,T,U,F,I,J,O,G,$,q,j,V,X,Y,K,Z,ee,te,ae,ie,re,ne,oe,se,le,ce,de,ue,fe,Ae,ge,he,ze,ve,pe,me;var ye,be,we,_e,Ce,Ee,Se;return u(function(){h.init&&B()}),g={cfg:h,autoSizer:M,loader:x,init:B,uP:_,aC:m,rC:y,hC:d,fire:w,gW:E,rAF:S}}(e,e.document,Date);e.lazySizes=a,"object"==typeof module&&module.exports&&(module.exports=a)}("undefined"!=typeof window?window:{}),lazysizesWebP("alpha",lazySizes.init),document.addEventListener("lazybeforesizes",function(e){void 0!==e.target._lazysizesWidth&&e.detail.width<e.target._lazysizesWidth&&(e.detail.width=e.target._lazysizesWidth)}),document.addEventListener("lazybeforeunveil",function(e){var t=e.target,a=t.getAttribute("data-srcset");if(t.naturalWidth&&1<t.naturalWidth&&1<t.naturalHeight){var i=window.devicePixelRatio||1,r=t.clientWidth&&1.25*t.clientWidth<t.naturalWidth,n=t.clientHeight&&1.25*t.clientHeight<t.naturalHeight;if(r||n){var o=Math.round(t.offsetWidth*i),s=Math.round(t.offsetHeight*i),l=t.getAttribute("data-src"),c=t.getAttribute("data-src-webp");if(ewww_webp_supported&&c&&-1==l.search("webp=1")&&(l=c),shouldAutoScale(t)&&shouldAutoScale(t.parentNode))if(window.lazySizes.hC(t,"et_pb_jt_filterable_grid_item_image")||window.lazySizes.hC(t,"ss-foreground-image"))d=constrainSrc(l,o,s,"img-crop");else d=constrainSrc(l,o,s,"img");else var d=!1;d&&l!=d&&t.setAttribute("data-src",d)}}if(ewww_webp_supported){if(a){var u=t.getAttribute("data-srcset-webp");u&&t.setAttribute("data-srcset",u)}if(!(c=t.getAttribute("data-src-webp")))return;t.setAttribute("data-src",c)}}),function(e,t){var a=function(){t(e.lazySizes),e.removeEventListener("lazyunveilread",a,!0)};t=t.bind(null,e,e.document),"object"==typeof module&&module.exports?t(require("lazysizes")):e.lazySizes?a():e.addEventListener("lazyunveilread",a,!0)}(window,function(o,e,s){"use strict";var l;e.addEventListener&&(l=/\(|\)|\s|'/,addEventListener("lazybeforeunveil",function(e){var t,a;if(e.detail.instance==s&&(!e.defaultPrevented&&("none"==e.target.preload&&(e.target.preload="auto"),t=e.target.getAttribute("data-bg")))){ewww_webp_supported&&(a=e.target.getAttribute("data-bg-webp"))&&(t=a);var i=o.devicePixelRatio||1,r=Math.round(e.target.offsetWidth*i),n=Math.round(e.target.offsetHeight*i);shouldAutoScale(e.target)&&shouldAutoScale(e.target.parentNode)&&(t=o.lazySizes.hC(e.target,"wp-block-cover")?(o.lazySizes.hC(e.target,"has-parallax")&&(r=Math.round(o.screen.width*i),n=Math.round(o.screen.height*i)),constrainSrc(t,r,n,"bg-cover")):o.lazySizes.hC(e.target,"elementor-bg")?constrainSrc(t,r,n,"bg-cover"):constrainSrc(t,r,n,"bg")),e.target.style.backgroundImage="url("+(l.test(t)?JSON.stringify(t):t)+")"}},!1))});