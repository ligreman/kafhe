/**
 * @license MIT
 * @fileOverview Favico animations
 * @author Miroslav Magda, http://blog.ejci.net
 * @version 0.3.0
 */
!function(){var t=function(t){"use strict";function e(t){if(t.paused||t.ended||g)return!1;try{s.clearRect(0,0,h,a),s.drawImage(t,0,0,h,a)}catch(o){}setTimeout(e,L.duration,t),E.setIcon(c)}function o(t){var e=/^#?([a-f\d])([a-f\d])([a-f\d])$/i;t=t.replace(e,function(t,e,o,n){return e+e+o+o+n+n});var o=/^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(t);return o?{r:parseInt(o[1],16),g:parseInt(o[2],16),b:parseInt(o[3],16)}:!1}function n(t,e){var o,n={};for(o in t)n[o]=t[o];for(o in e)n[o]=e[o];return n}t=t?t:{};var r,i,a,h,c,s,l,f,u,d,y,g,w,x={bgColor:"#d00",textColor:"#fff",fontFamily:"sans-serif",fontStyle:"bold",type:"circle",position:"down",animation:"slide",elementId:!1},m=[];y=function(){},f=g=!1;var p=function(){if(r=n(x,t),r.bgColor=o(r.bgColor),r.textColor=o(r.textColor),r.position=r.position.toLowerCase(),r.animation=L.types[""+r.animation]?r.animation:x.animation,"up"===r.position)for(var e=0;e<L.types[""+r.animation].length;e++){var f=L.types[""+r.animation][e];f.y=f.y<.6?f.y-.4:f.y-2*f.y+(1-f.w),L.types[""+r.animation][e]=f}r.type=b[""+r.type]?r.type:x.type;try{i=E.getIcon(),c=document.createElement("canvas"),l=document.createElement("img"),l.setAttribute("src",i.getAttribute("href")),l.onload=function(){a=l.height>0?l.height:32,h=l.width>0?l.width:32,c.height=a,c.width=h,s=c.getContext("2d"),v.ready()},w={},w.ff=/firefox/i.test(navigator.userAgent.toLowerCase()),w.chrome=/chrome/i.test(navigator.userAgent.toLowerCase()),w.opera=/opera/i.test(navigator.userAgent.toLowerCase()),w.ie=/msie/i.test(navigator.userAgent.toLowerCase())||/trident/i.test(navigator.userAgent.toLowerCase()),w.supported=w.chrome||w.ff||w.opera}catch(u){throw"Error initializing favico..."}},v={};v.ready=function(){f=!0,v.reset(),y()},v.reset=function(){m=[],u=!1,s.clearRect(0,0,h,a),s.drawImage(l,0,0,h,a),E.setIcon(c)},v.start=function(){if(f&&!d){var t=function(){u=m[0],d=!1,m.length>0&&(m.shift(),v.start())};m.length>0&&(d=!0,u?L.run(u.options,function(){L.run(m[0].options,function(){t()},!1)},!0):L.run(m[0].options,function(){t()},!1))}};var b={},C=function(t){return t.n=Math.abs(t.n),t.x=h*t.x,t.y=a*t.y,t.w=h*t.w,t.h=a*t.h,t};b.circle=function(t){t=C(t);var e=t.n>9&&t.n<100;e&&(t.x=t.x-.4*t.w,t.w=1.4*t.w),s.clearRect(0,0,h,a),s.drawImage(l,0,0,h,a),s.beginPath(),s.font=r.fontStyle+" "+Math.floor(t.h)+"px "+r.fontFamily,s.textAlign="center",e?(s.moveTo(t.x+t.w/2,t.y),s.lineTo(t.x+t.w-t.h/2,t.y),s.quadraticCurveTo(t.x+t.w,t.y,t.x+t.w,t.y+t.h/2),s.lineTo(t.x+t.w,t.y+t.h-t.h/2),s.quadraticCurveTo(t.x+t.w,t.y+t.h,t.x+t.w-t.h/2,t.y+t.h),s.lineTo(t.x+t.h/2,t.y+t.h),s.quadraticCurveTo(t.x,t.y+t.h,t.x,t.y+t.h-t.h/2),s.lineTo(t.x,t.y+t.h/2),s.quadraticCurveTo(t.x,t.y,t.x+t.h/2,t.y)):s.arc(t.x+t.w/2,t.y+t.h/2,t.h/2,0,2*Math.PI),s.fillStyle="rgba("+r.bgColor.r+","+r.bgColor.g+","+r.bgColor.b+","+t.o+")",s.fill(),s.closePath(),s.beginPath(),s.stroke(),s.fillStyle="rgba("+r.textColor.r+","+r.textColor.g+","+r.textColor.b+","+t.o+")",t.n>99?s.fillText("∞",Math.floor(t.x+t.w/2),Math.floor(t.y+t.h-.15*t.h)):s.fillText(t.n,Math.floor(t.x+t.w/2),Math.floor(t.y+t.h-.15*t.h)),s.closePath()},b.rectangle=function(t){t=C(t);var e=t.n>9&&t.n<100;e&&(t.x=Math.floor(t.x-.4*t.w),t.w=Math.floor(1.4*t.w)),s.clearRect(0,0,h,a),s.drawImage(l,0,0,h,a),s.beginPath(),s.font="bold "+Math.floor(t.h)+"px sans-serif",s.textAlign="center",s.fillStyle="rgba("+r.bgColor.r+","+r.bgColor.g+","+r.bgColor.b+","+t.o+")",s.fillRect(t.x,t.y,t.w,t.h),s.fillStyle="rgba("+r.textColor.r+","+r.textColor.g+","+r.textColor.b+","+t.o+")",t.n>99?s.fillText("∞",Math.floor(t.x+t.w/2),Math.floor(t.y+t.h-.15*t.h)):s.fillText(t.n,Math.floor(t.x+t.w/2),Math.floor(t.y+t.h-.15*t.h)),s.closePath()};var I=function(t,e){y=function(){try{if(t>0){if(L.types[""+e]&&(r.animation=e),m.push({type:"badge",options:{n:t}}),m.length>100)throw"Too many badges requests in queue...";v.start()}else v.reset()}catch(o){throw"Error setting badge..."}},f&&y()},A=function(t){y=function(){try{var e=t.width,o=t.height,n=document.createElement("img"),r=o/a>e/h?e/h:o/a;n.setAttribute("src",t.getAttribute("src")),n.height=o/r,n.width=e/r,s.clearRect(0,0,h,a),s.drawImage(n,0,0,h,a),E.setIcon(c)}catch(i){throw"Error setting image..."}},f&&y()},M=function(t){y=function(){try{if("stop"===t)return g=!0,v.reset(),g=!1,void 0;t.addEventListener("play",function(){e(this)},!1)}catch(o){throw"Error setting video..."}},f&&y()},T=function(t){if(window.URL&&window.URL.createObjectURL||(window.URL=window.URL||{},window.URL.createObjectURL=function(t){return t}),w.supported){var o=!1;navigator.getUserMedia=navigator.getUserMedia||navigator.oGetUserMedia||navigator.msGetUserMedia||navigator.mozGetUserMedia||navigator.webkitGetUserMedia,y=function(){try{if("stop"===t)return g=!0,v.reset(),g=!1,void 0;o=document.createElement("video"),o.width=h,o.height=a,navigator.getUserMedia({video:!0,audio:!1},function(t){o.src=URL.createObjectURL(t),o.play(),e(o)},function(){})}catch(n){throw"Error setting webcam..."}},f&&y()}},E={};E.getIcon=function(){var t=!1,e=function(){for(var t=document.getElementsByTagName("head")[0].getElementsByTagName("link"),e=t.length,o=e-1;o>=0;o--)if(/icon/i.test(t[o].getAttribute("rel")))return t[o];return!1};return r.elementId?(t=document.getElementById(r.elementId),t.setAttribute("href",t.getAttribute("src"))):(t=e(),t===!1&&(t=document.createElement("link"),t.setAttribute("rel","icon"),document.getElementsByTagName("head")[0].appendChild(t))),t.setAttribute("type","image/png"),t},E.setIcon=function(t){var e=t.toDataURL("image/png");if(r.elementId)document.getElementById(r.elementId).setAttribute("src",e);else if(w.ff||w.opera){var o=i;i=document.createElement("link"),w.opera&&i.setAttribute("rel","icon"),i.setAttribute("rel","icon"),i.setAttribute("type","image/png"),document.getElementsByTagName("head")[0].appendChild(i),i.setAttribute("href",e),o.parentNode&&o.parentNode.removeChild(o)}else i.setAttribute("href",e)};var L={};return L.duration=40,L.types={},L.types.fade=[{x:.4,y:.4,w:.6,h:.6,o:0},{x:.4,y:.4,w:.6,h:.6,o:.1},{x:.4,y:.4,w:.6,h:.6,o:.2},{x:.4,y:.4,w:.6,h:.6,o:.3},{x:.4,y:.4,w:.6,h:.6,o:.4},{x:.4,y:.4,w:.6,h:.6,o:.5},{x:.4,y:.4,w:.6,h:.6,o:.6},{x:.4,y:.4,w:.6,h:.6,o:.7},{x:.4,y:.4,w:.6,h:.6,o:.8},{x:.4,y:.4,w:.6,h:.6,o:.9},{x:.4,y:.4,w:.6,h:.6,o:1}],L.types.none=[{x:.4,y:.4,w:.6,h:.6,o:1}],L.types.pop=[{x:1,y:1,w:0,h:0,o:1},{x:.9,y:.9,w:.1,h:.1,o:1},{x:.8,y:.8,w:.2,h:.2,o:1},{x:.7,y:.7,w:.3,h:.3,o:1},{x:.6,y:.6,w:.4,h:.4,o:1},{x:.5,y:.5,w:.5,h:.5,o:1},{x:.4,y:.4,w:.6,h:.6,o:1}],L.types.popFade=[{x:.75,y:.75,w:0,h:0,o:0},{x:.65,y:.65,w:.1,h:.1,o:.2},{x:.6,y:.6,w:.2,h:.2,o:.4},{x:.55,y:.55,w:.3,h:.3,o:.6},{x:.5,y:.5,w:.4,h:.4,o:.8},{x:.45,y:.45,w:.5,h:.5,o:.9},{x:.4,y:.4,w:.6,h:.6,o:1}],L.types.slide=[{x:.4,y:1,w:.6,h:.6,o:1},{x:.4,y:.9,w:.6,h:.6,o:1},{x:.4,y:.9,w:.6,h:.6,o:1},{x:.4,y:.8,w:.6,h:.6,o:1},{x:.4,y:.7,w:.6,h:.6,o:1},{x:.4,y:.6,w:.6,h:.6,o:1},{x:.4,y:.5,w:.6,h:.6,o:1},{x:.4,y:.4,w:.6,h:.6,o:1}],L.run=function(t,e,o,i){var a=L.types[r.animation];return i=o===!0?"undefined"!=typeof i?i:a.length-1:"undefined"!=typeof i?i:0,e=e?e:function(){},i<a.length&&i>=0?(b[r.type](n(t,a[i])),setTimeout(function(){o?i-=1:i+=1,L.run(t,e,o,i)},L.duration),E.setIcon(c),void 0):(e(),void 0)},p(),{badge:I,video:M,image:A,webcam:T,reset:v.reset}};"undefined"!=typeof define&&define.amd?define([],function(){return t}):"undefined"!=typeof module&&module.exports?module.exports=t:this.Favico=t}();