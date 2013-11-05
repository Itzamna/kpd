/*
Copyright (c) 2008-2009 Yahoo! Inc. All rights reserved.
The copyrights embodied in the content of this file are licensed by
Yahoo! Inc. under the BSD (revised) open source license

@author Dan Vlad Dascalescu <dandv@yahoo-inc.com>

Based on parsehtmlmixed.js by Marijn Haverbeke.
*/
var PHPHTMLMixedParser=Editor.Parser=function(){var p=["<?php"];if(!(PHPParser&&CSSParser&&JSParser&&XMLParser))throw Error("PHP, CSS, JS, and XML parsers must be loaded for PHP+HTML mixed mode to work.");XMLParser.configure({useHTMLKludges:true});return{make:function(e){function q(){var a=m.next();if(a.content=="<")c=true;else if(a.style=="xml-tagname"&&c===true)c=a.content.toLowerCase();else if(a.style=="xml-attname")i=a.content;else if(a.type=="xml-processing")for(var d=0;d<p.length;d++){if(p[d]==
a.content){f.next=n(PHPParser,"?>");break}}else if(a.style=="xml-attribute"&&a.content=='"php"'&&c=="script"&&i=="language")c="script/php";else if(a.content==">"){if(c=="script/php")f.next=n(PHPParser,"<\/script>");else if(c=="script")f.next=n(JSParser,"<\/script");else if(c=="style")f.next=n(CSSParser,"</style");i=null;c=false}return a}function n(a,d){var o=m.indentation();h=a==PHPParser&&j?j(e):a.make(e,o+indentUnit);return function(){if(e.lookAhead(d,false,false,true)){if(a==PHPParser)j=h.copy();
h=null;f.next=q;return q()}var b=h.next(),g=b.value.lastIndexOf("<"),k=Math.min(b.value.length-g,d.length);if(g!=-1&&b.value.slice(g,g+k).toLowerCase()==d.slice(0,k)&&e.lookAhead(d.slice(k),false,false,true)){e.push(b.value.slice(g));b.value=b.value.slice(0,g)}if(b.indentation){var l=b.indentation;b.indentation=function(r){return r=="</"?o:l(r)}}return b}}var m=XMLParser.make(e),h=null,c=false,i=null,j=null,f={next:q,copy:function(){var a=m.copy(),d=h&&h.copy(),o=f.next,b=c,g=i,k=j;return function(l){e=
l;m=a(l);h=d&&d(l);j=k;f.next=o;c=b;i=g;return f}}};return f},electricChars:"{}/:",configure:function(e){if(e.opening!=null)p=e.opening}}}();
