/**
 * @version		$Id: editor.js 88 2011-02-21 19:12:24Z happy_noodle_boy $
 * @package      JCE
 * @copyright    Copyright (C) 2005 - 2009 Ryan Demmer. All rights reserved.
 * @author		Ryan Demmer
 * @license      GNU/GPL
 * JCE is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */

/**
 * Widget Factory Editor
 */
var JContentEditor = {
    _bookmark : {},

    /**
     * Initialise JContentEditor
     * @param {Object} options Editor Options
     * @param {Object} settings TinyMCE Settings
     * @param {Boolean} gzip Load using gzip
     */
    init : function(settings, gzip) {     	    	
        // remove submit triggers
        tinymce.extend(settings, {
        	mode 					: 'textareas',
            editor_selector			: 'mceEditor',
            editor_deselector		: 'mceNoEditor',
            urlconverter_callback	: 'JContentEditor.convertURL'
        });

        this.settings = settings;

        if (this.settings) {
            try {            	
            	// mark javascript files loaded                   
                if (gzip) {
                    this._markLoaded();
                }
            	
                JContentEditor.create();

            } catch (e) {
                alert('Unable to initialize TinyMCE : ' + e);
            }
        }
    },

    _markLoaded : function() {
        var self = this, s = this.settings, each = tinymce.each, ln = s.language.split(',');

        var suffix = s.suffix || '';

        function load(u) {
            tinymce.ScriptLoader.markDone(tinyMCE.baseURI.toAbsolute(u));
        }

        // Add core languages
        each(ln, function(c) {
            if (c) {
                load('langs/' + c + '.js');
            }
        });

        // Add themes with languages
        each(s.theme.split(','), function(n) {
            if (n) {
                load('themes/' + n + '/editor_template' + suffix + '.js');

                each (ln, function(c) {
                    if (c) {
                        load('themes/' + n + '/langs/' + c + '.js');
                    }
                });

            }
        });

        // Add plugins with languages
        each(s.plugins.split(','), function(n) {
            if (n) {
                load('plugins/' + n + '/editor_plugin' + suffix + '.js');

                each(ln, function(c) {
                    if (c) {
                        load('plugins/' + n + '/langs/' + c + '.js');
                    }
                });

            }
        });

    },

    setBookmark : function(ed) {
        var self = this, DOM = tinymce.DOM, Event = tinymce.dom.Event;

        Event.add(document.body, 'mousedown', function(e) {
            var el = e.target, ta = ed.getElement(), toggle = ta.previousSibling;

            if (DOM.getParent(el, 'span.mceEditor') || el == ta) {
                return;
            }

            if (ed.selection) {
                self._bookmark[ed.id] = ed.selection.getBookmark();
            }
        });

    },

    /**
     * Create Editors on domloaded
     */
    create : function(elements) {
        var self = this, Event = tinymce.dom.Event, s = this.settings;

        if (!Event.domLoaded) {
            Event.add(document, 'init', function() {
                self.create();
            });

            return;
        } else {
            tinyMCE.onAddEditor.add( function(mgr, ed) {
                if (tinymce.isIE) {
                    self.setBookmark(ed);
                }
            });

            if (elements) {
                s.mode 		= 'exact';
                s.elements 	= elements;
            }

            try {
                tinyMCE.init(s);
            } catch (e) {
                alert(e);
            }
        }
    },

    /**
     * Set the editor content
     * @param {String} id The editor id
     * @param {String} html The html content to set
     */
    setContent: function(id, html) {
        var ed = tinyMCE.get(id);
        if (ed) {
            ed.setContent(html);
        } else {
            document.getElementById(id).value = html;
        }
    },

    /**
     * Get the editor content
     * @param {String} id The editor id
     */
    getContent: function(id) {
        var ed = tinyMCE.get(id);
        if (ed) {
            return ed.getContent();
        }
        return document.getElementById(id).value;
    },

    /**
     * Save the editor content
     * @param {String} id The editor id
     */
    save: function(id) {
        tinyMCE.triggerSave();
    },

    /**
     * Insert content into the editor. This function is provided for editor-xtd buttons and includes methods for inserting into textareas
     * @param {String} el The editor id
     * @param {String} v The text to insert
     */
    insert: function(el, v) {
        var bm, ed;
        if (typeof el == 'string') {
            el = document.getElementById(el);
        }
        if (/mceEditor/.test(el.className)) {
            ed = tinyMCE.get(el.id);
            if (tinymce.isIE) {
                if (window.parent.tinymce) {
                    var ed = window.parent.tinyMCE.get(el.id);

                    if (ed) {
                        if (this._bookmark[ed.id]) {
                            ed.selection.moveToBookmark(this._bookmark[ed.id]);
                        }
                    }
                }
            }
            ed.execCommand('mceInsertContent', false, v, true);
        } else {
            this.insertIntoTextarea(el, v);
        }
    },

    insertIntoTextarea : function(el, v) {
        // IE
        if (document.selection) {
            el.focus();
            var s = document.selection.createRange();
            s.text = v;
            // Mozilla / Netscape
        } else {
            if (el.selectionStart || el.selectionStart == '0') {
                var startPos = el.selectionStart;
                var endPos = el.selectionEnd;
                el.value = el.value.substring(0, startPos) + v + el.value.substring(endPos, el.value.length);
                // Other
            } else {
                el.value += v;
            }
        }
    },

    convertURL : function(u, e, save) {
        var ed = tinymce.EditorManager.activeEditor, s = tinymce.settings, base = s.document_base_url;

        // Don't convert link href since thats the CSS files that gets loaded into the editor also skip local file URLs
        if (!s.convert_urls || (e && e.nodeName == 'LINK') || u.indexOf('file:') === 0)
            return u;
        
        if (u == base || u == base.substring(0, base.length - 1) || u.charAt(0) == '/') {
            return u;
        }

        // Convert to relative
        if (s.relative_urls)
            return ed.documentBaseURI.toRelative(u);

        // Convert to absolute
        u = ed.documentBaseURI.toAbsolute(u, s.remove_script_host);

        return u;
    }
};