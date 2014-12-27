/* NicEdit - Micro Inline WYSIWYG
 * Copyright 2007-2008 Brian Kirchoff
 *
 * NicEdit is distributed under the terms of the MIT license
 * For more information visit http://nicedit.com/
 * Do not remove this copyright message
 */
var bkExtend = function() {
    var a = arguments;
    if (1 == a.length) a = [this, a[0]];
    for (var b in a[1]) a[0][b] = a[1][b];
    return a[0];
};

function bkClass() {}

bkClass.prototype.construct = function() {};

bkClass.extend = function(a) {
    var b = function() {
        if (arguments[0] !== bkClass) return this.construct.apply(this, arguments);
    };
    var c = new this(bkClass);
    bkExtend(c, a);
    b.prototype = c;
    b.extend = this.extend;
    return b;
};

var bkElement = bkClass.extend({
    construct: function(a, b) {
        if ("string" == typeof a) a = (b || document).createElement(a);
        a = $BK(a);
        return a;
    },
    appendTo: function(a) {
        a.appendChild(this);
        return this;
    },
    appendBefore: function(a) {
        a.parentNode.insertBefore(this, a);
        return this;
    },
    addEvent: function(a, b) {
        bkLib.addEvent(this, a, b);
        return this;
    },
    setContent: function(a) {
        this.innerHTML = a;
        return this;
    },
    pos: function() {
        var a = curtop = 0;
        var b = obj = this;
        if (obj.offsetParent)
            do {
                a += obj.offsetLeft;
                curtop += obj.offsetTop;
            } while (obj = obj.offsetParent);
        var c = !window.opera ? parseInt(this.getStyle("border-width") || this.style.border) || 0 : 0;
        return [a + c, curtop + c + this.offsetHeight];
    },
    noSelect: function() {
        bkLib.noSelect(this);
        return this;
    },
    parentTag: function(a) {
        var b = this;
        do {
            if (b && b.nodeName && b.nodeName.toUpperCase() == a) return b;
            b = b.parentNode;
        } while (b);
        return false;
    },
    hasClass: function(a) {
        return this.className.match(new RegExp("(\\s|^)nicEdit-" + a + "(\\s|$)"));
    },
    addClass: function(a) {
        if (!this.hasClass(a)) this.className += " nicEdit-" + a;
        return this;
    },
    removeClass: function(a) {
        if (this.hasClass(a)) this.className = this.className.replace(new RegExp("(\\s|^)nicEdit-" + a + "(\\s|$)"), " ");
        return this;
    },
    setStyle: function(a) {
        var b = this.style;
        for (var c in a) switch (c) {
            case "float":
                b.cssFloat = b.styleFloat = a[c];
                break;

            case "opacity":
                b.opacity = a[c];
                b.filter = "alpha(opacity=" + Math.round(100 * a[c]) + ")";
                break;

            case "className":
                this.className = a[c];
                break;

            default:
                b[c] = a[c];
        }
        return this;
    },
    getStyle: function(a, b) {
        var c = !b ? document.defaultView : b;
        if (1 == this.nodeType) return c && c.getComputedStyle ? c.getComputedStyle(this, null).getPropertyValue(a) : this.currentStyle[bkLib.camelize(a)];
    },
    remove: function() {
        this.parentNode.removeChild(this);
        return this;
    },
    setAttributes: function(a) {
        for (var b in a) this[b] = a[b];
        return this;
    }
});

var bkLib = {
    isMSIE: navigator.appVersion.indexOf("MSIE") != -1,
    addEvent: function(a, b, c) {
        a.addEventListener ? a.addEventListener(b, c, false) : a.attachEvent("on" + b, c);
    },
    toArray: function(a) {
        var b = a.length,
            c = new Array(b);
        while (b--) c[b] = a[b];
        return c;
    },
    noSelect: function(a) {
        if (a.setAttribute && "input" != a.nodeName.toLowerCase() && "textarea" != a.nodeName.toLowerCase()) a.setAttribute("unselectable", "on");
        for (var b = 0; b < a.childNodes.length; b++) bkLib.noSelect(a.childNodes[b]);
    },
    camelize: function(a) {
        return a.replace(/\-(.)/g, function(a, b) {
            return b.toUpperCase();
        });
    },
    inArray: function(a, b) {
        return null != bkLib.search(a, b);
    },
    search: function(a, b) {
        for (var c = 0; c < a.length; c++)
            if (a[c] == b) return c;
        return null;
    },
    cancelEvent: function(a) {
        a = a || window.event;
        if (a.preventDefault && a.stopPropagation) {
            a.preventDefault();
            a.stopPropagation();
        }
        return false;
    },
    domLoad: [],
    domLoaded: function() {
        if (arguments.callee.done) return;
        arguments.callee.done = true;
        for (i = 0; i < bkLib.domLoad.length; i++) bkLib.domLoad[i]();
    },
    onDomLoaded: function(a) {
        this.domLoad.push(a);
        if (document.addEventListener) document.addEventListener("DOMContentLoaded", bkLib.domLoaded, null);
        else if (bkLib.isMSIE) {
            document.write("<style>.nicEdit-main p { margin: 0; }</style><script id=__ie_onload defer " + ("https:" == location.protocol ? "src='javascript:void(0)'" : "src=//0") + "></script>");
            $BK("__ie_onload").onreadystatechange = function() {
                if ("complete" == this.readyState) bkLib.domLoaded();
            };
        }
        window.onload = bkLib.domLoaded;
    }
};

function $BK(a) {
    if ("string" == typeof a) a = document.getElementById(a);
    return a && !a.appendTo ? bkExtend(a, bkElement.prototype) : a;
}

var bkEvent = {
    addEvent: function(a, b) {
        if (b) {
            this.eventList = this.eventList || {};
            this.eventList[a] = this.eventList[a] || [];
            this.eventList[a].push(b);
        }
        return this;
    },
    fireEvent: function() {
        var a = bkLib.toArray(arguments),
            b = a.shift();
        if (this.eventList && this.eventList[b])
            for (var c = 0; c < this.eventList[b].length; c++) this.eventList[b][c].apply(this, a);
    }
};

function __(a) {
    return a;
}

Function.prototype.closure = function() {
    var a = this,
        b = bkLib.toArray(arguments),
        c = b.shift();
    return function() {
        if ("undefined" != typeof bkLib) return a.apply(c, b.concat(bkLib.toArray(arguments)));
    };
};

Function.prototype.closureListener = function() {
    var a = this,
        b = bkLib.toArray(arguments),
        c = b.shift();
    return function(d) {
        d = d || window.event;
        if (d.target) var e = d.target;
        else var e = d.srcElement;
        return a.apply(c, [d, e].concat(b));
    };
};

var nicEditorConfig = bkClass.extend({
    buttons: {
        bold: {
            name: __("Click to Bold"),
            command: "Bold",
            tags: ["B", "STRONG"],
            css: {
                "font-weight": "bold"
            },
            key: "b"
        },
        italic: {
            name: __("Click to Italic"),
            command: "Italic",
            tags: ["EM", "I"],
            css: {
                "font-style": "italic"
            },
            key: "i"
        },
        underline: {
            name: __("Click to Underline"),
            command: "Underline",
            tags: ["U"],
            css: {
                "text-decoration": "underline"
            },
            key: "u"
        },
        left: {
            name: __("Left Align"),
            command: "justifyleft",
            noActive: true
        },
        center: {
            name: __("Center Align"),
            command: "justifycenter",
            noActive: true
        },
        right: {
            name: __("Right Align"),
            command: "justifyright",
            noActive: true
        },
        justify: {
            name: __("Justify Align"),
            command: "justifyfull",
            noActive: true
        },
        ol: {
            name: __("Insert Ordered List"),
            command: "insertorderedlist",
            tags: ["OL"]
        },
        ul: {
            name: __("Insert Unordered List"),
            command: "insertunorderedlist",
            tags: ["UL"]
        },
        subscript: {
            name: __("Click to Subscript"),
            command: "subscript",
            tags: ["SUB"]
        },
        superscript: {
            name: __("Click to Superscript"),
            command: "superscript",
            tags: ["SUP"]
        },
        strikethrough: {
            name: __("Click to Strike Through"),
            command: "strikeThrough",
            css: {
                "text-decoration": "line-through"
            }
        },
        removeformat: {
            name: __("Remove Formatting"),
            command: "removeformat",
            noActive: true
        },
        indent: {
            name: __("Indent Text"),
            command: "indent",
            noActive: true
        },
        outdent: {
            name: __("Remove Indent"),
            command: "outdent",
            noActive: true
        },
        hr: {
            name: __("Horizontal Rule"),
            command: "insertHorizontalRule",
            noActive: true
        }
    },
    iconsPath: "images/nicEditorIcons.gif",
    buttonList: ["save", "bold", "italic", "underline", "left", "center", "right", "justify", "ol", "ul", "fontSize", "fontFamily", "fontFormat", "indent", "outdent", "image", "upload", "link", "unlink", "forecolor", "bgcolor", "removeformat"],
    iconList: {
        xhtml: 1,
        bgcolor: 2,
        forecolor: 3,
        bold: 4,
        center: 5,
        hr: 6,
        indent: 7,
        italic: 8,
        justify: 9,
        left: 10,
        ol: 11,
        outdent: 12,
        removeformat: 13,
        right: 14,
        save: 25,
        strikethrough: 16,
        subscript: 17,
        superscript: 18,
        ul: 19,
        underline: 20,
        image: 21,
        link: 22,
        unlink: 23,
        close: 24,
        arrow: 26,
        upload: 27
    }
});

var nicEditors = {
    nicPlugins: [],
    editors: [],
    registerPlugin: function(a, b) {
        this.nicPlugins.push({
            p: a,
            o: b
        });
    },
    allTextAreas: function(a) {
        var b = document.getElementsByTagName("textarea");
        for (var c = 0; c < b.length; c++) nicEditors.editors.push(new nicEditor(a).panelInstance(b[c]));
        return nicEditors.editors;
    },
    findEditor: function(a) {
        var b = nicEditors.editors;
        for (var c = 0; c < b.length; c++)
            if (b[c].instanceById(a)) return b[c].instanceById(a);
    }
};

var nicEditor = bkClass.extend({
    construct: function(a) {
        this.options = new nicEditorConfig();
        bkExtend(this.options, a);
        this.nicInstances = new Array();
        this.loadedPlugins = new Array();
        var b = nicEditors.nicPlugins;
        for (var c = 0; c < b.length; c++) this.loadedPlugins.push(new b[c].p(this, b[c].o));
        nicEditors.editors.push(this);
        bkLib.addEvent(document.body, "mousedown", this.selectCheck.closureListener(this));
    },
    panelInstance: function(a, b) {
        a = this.checkReplace($BK(a));
        var c = new bkElement("DIV").setStyle({
            width: (parseInt(a.getStyle("width")) || a.clientWidth) + "px"
        }).appendBefore(a);
        this.setPanel(c);
        return this.addInstance(a, b);
    },
    checkReplace: function(a) {
        var b = nicEditors.findEditor(a);
        if (b) {
            b.removeInstance(a);
            b.removePanel();
        }
        return a;
    },
    addInstance: function(a, b) {
        a = this.checkReplace($BK(a));
        if (a.contentEditable || !!window.opera) var c = new nicEditorInstance(a, b, this);
        else var c = new nicEditorIFrameInstance(a, b, this);
        this.nicInstances.push(c);
        return this;
    },
    removeInstance: function(a) {
        a = $BK(a);
        var b = this.nicInstances;
        for (var c = 0; c < b.length; c++)
            if (b[c].e == a) {
                b[c].remove();
                this.nicInstances.splice(c, 1);
            }
    },
    removePanel: function(a) {
        if (this.nicPanel) {
            this.nicPanel.remove();
            this.nicPanel = null;
        }
    },
    instanceById: function(a) {
        a = $BK(a);
        var b = this.nicInstances;
        for (var c = 0; c < b.length; c++)
            if (b[c].e == a) return b[c];
    },
    setPanel: function(a) {
        this.nicPanel = new nicEditorPanel($BK(a), this.options, this);
        this.fireEvent("panel", this.nicPanel);
        return this;
    },
    nicCommand: function(a, b) {
        if (this.selectedInstance) this.selectedInstance.nicCommand(a, b);
    },
    getIcon: function(a, b) {
        var c = this.options.iconList[a];
        var d = b.iconFiles ? b.iconFiles[a] : "";
        return {
            backgroundImage: "url('" + (c ? this.options.iconsPath : d) + "')",
            backgroundPosition: (c ? (c - 1) * -18 : 0) + "px 0px"
        };
    },
    selectCheck: function(a, b) {
        var c = false;
        do
            if (b.className && b.className.indexOf("nicEdit") != -1) return false;
        while (b = b.parentNode);
        this.fireEvent("blur", this.selectedInstance, b);
        this.lastSelectedInstance = this.selectedInstance;
        this.selectedInstance = null;
        return false;
    }
});

nicEditor = nicEditor.extend(bkEvent);

var nicEditorInstance = bkClass.extend({
    isSelected: false,
    construct: function(a, b, c) {
        this.ne = c;
        this.elm = this.e = a;
        this.options = b || {};
        newX = parseInt(a.getStyle("width")) || a.clientWidth;
        newY = parseInt(a.getStyle("height")) || a.clientHeight;
        this.initialHeight = newY - 2;
        var d = "textarea" == a.nodeName.toLowerCase();
        if (d || this.options.hasPanel) {
            var e = bkLib.isMSIE && !("undefined" != typeof document.body.style.maxHeight && "CSS1Compat" == document.compatMode);
            var f = {
                width: newX - 6 + "px",
                border: "1px solid gray",
                backgroundColor: "gray",
                borderTop: 0,
                overflowY: "auto",
                overflowX: "hidden"
            };
            f[e ? "height" : "maxHeight"] = this.ne.options.maxHeight ? this.ne.options.maxHeight + "px" : null;
            this.editorContain = new bkElement("DIV").setStyle(f).appendBefore(a);
            var g = new bkElement("DIV").setStyle({
                outline: "none",
                width: newX - 8 + "px",
                margin: "1px",
                minHeight: newY + "px"
            }).addClass("main").appendTo(this.editorContain);
            a.setStyle({
                display: "none"
            });
            g.innerHTML = a.innerHTML;
            if (d) {
                g.setContent(a.value);
                this.copyElm = a;
                var h = a.parentTag("FORM");
                if (h) bkLib.addEvent(h, "submit", this.saveContent.closure(this));
            }
            g.setStyle(e ? {
                height: newY + "px"
            } : {
                overflow: "hidden"
            });
            this.elm = g;
        }
        this.ne.addEvent("blur", this.blur.closure(this));
        this.init();
        this.blur();
    },
    init: function() {
        this.elm.setAttribute("contentEditable", "true");
        if ("" == this.getContent()) this.setContent("<br />");
        this.instanceDoc = document.defaultView;
        this.elm.addEvent("mousedown", this.selected.closureListener(this)).addEvent("keypress", this.keyDown.closureListener(this)).addEvent("focus", this.selected.closure(this)).addEvent("blur", this.blur.closure(this)).addEvent("keyup", this.selected.closure(this));
        this.ne.fireEvent("add", this);
    },
    remove: function() {
        this.saveContent();
        if (this.copyElm || this.options.hasPanel) {
            this.editorContain.remove();
            this.e.setStyle({
                display: "block"
            });
            this.ne.removePanel();
        }
        this.disable();
        this.ne.fireEvent("remove", this);
    },
    disable: function() {
        this.elm.setAttribute("contentEditable", "false");
    },
    getSel: function() {
        return window.getSelection ? window.getSelection() : document.selection;
    },
    getRng: function() {
        var a = this.getSel();
        if (!a || 0 === a.rangeCount) return;
        return a.rangeCount > 0 ? a.getRangeAt(0) : a.createRange();
    },
    selRng: function(a, b) {
        if (window.getSelection) {
            b.removeAllRanges();
            b.addRange(a);
        } else a.select();
    },
    selElm: function() {
        var a = this.getRng();
        if (!a) return;
        if (a.startContainer) {
            var b = a.startContainer;
            if (1 == a.cloneContents().childNodes.length)
                for (var c = 0; c < b.childNodes.length; c++) {
                    var d = b.childNodes[c].ownerDocument.createRange();
                    d.selectNode(b.childNodes[c]);
                    if (1 != a.compareBoundaryPoints(Range.START_TO_START, d) && a.compareBoundaryPoints(Range.END_TO_END, d) != -1) return $BK(b.childNodes[c]);
                }
            return $BK(b);
        } else return $BK("Control" == this.getSel().type ? a.item(0) : a.parentElement());
    },
    saveRng: function() {
        this.savedRange = this.getRng();
        this.savedSel = this.getSel();
    },
    restoreRng: function() {
        if (this.savedRange) this.selRng(this.savedRange, this.savedSel);
    },
    keyDown: function(a, b) {
        if (a.ctrlKey) this.ne.fireEvent("key", this, a);
    },
    selected: function(a, b) {
        if (!b && !(b = this.selElm)) b = this.selElm();
        if (!a.ctrlKey) {
            var c = this.ne.selectedInstance;
            if (c != this) {
                if (c) this.ne.fireEvent("blur", c, b);
                this.ne.selectedInstance = this;
                this.ne.fireEvent("focus", c, b);
            }
            this.ne.fireEvent("selected", c, b);
            this.isFocused = true;
            this.elm.addClass("selected");
            this.elm.style.background = "#D9F4C3";
        }
        return false;
    },
    blur: function() {
        this.isFocused = false;
        this.elm.removeClass("selected");
        this.elm.style.background = "white";
    },
    saveContent: function() {
        if (this.copyElm || this.options.hasPanel) {
            this.ne.fireEvent("save", this);
            this.copyElm ? this.copyElm.value = this.getContent() : this.e.innerHTML = this.getContent();
        }
    },
    getElm: function() {
        return this.elm;
    },
    getContent: function() {
        this.content = this.getElm().innerHTML;
        this.ne.fireEvent("get", this);
        return this.content;
    },
    setContent: function(a) {
        this.content = a;
        this.ne.fireEvent("set", this);
        this.elm.innerHTML = this.content;
    },
    nicCommand: function(a, b) {
        document.execCommand(a, false, b);
    }
});

var nicEditorIFrameInstance = nicEditorInstance.extend({
    savedStyles: [],
    init: function() {
        var a = this.elm.innerHTML.replace(/^\s+|\s+$/g, "");
        this.elm.innerHTML = "";
        !a ? a = "<br />" : a;
        this.initialContent = a;
        this.elmFrame = new bkElement("iframe").setAttributes({
            src: "javascript:;",
            frameBorder: 0,
            allowTransparency: "true",
            scrolling: "no"
        }).setStyle({
            height: "100px",
            width: "100%"
        }).addClass("frame").appendTo(this.elm);
        if (this.copyElm) this.elmFrame.setStyle({
            width: this.elm.offsetWidth - 4 + "px"
        });
        var b = ["font-size", "font-family", "font-weight", "color"];
        for (itm in b) this.savedStyles[bkLib.camelize(itm)] = this.elm.getStyle(itm);
        setTimeout(this.initFrame.closure(this), 50);
    },
    disable: function() {
        this.elm.innerHTML = this.getContent();
    },
    initFrame: function() {
        var a = $BK(this.elmFrame.contentWindow.document);
        a.designMode = "on";
        a.open();
        var b = this.ne.options.externalCSS;
        a.write("<html><head>" + (b ? '<link href="' + b + '" rel="stylesheet" type="text/css" />' : "") + '</head><body id="nicEditContent" style="margin: 0 !important; background-color: transparent !important;">' + this.initialContent + "</body></html>");
        a.close();
        this.frameDoc = a;
        this.frameWin = $BK(this.elmFrame.contentWindow);
        this.frameContent = $BK(this.frameWin.document.body).setStyle(this.savedStyles);
        this.instanceDoc = this.frameWin.document.defaultView;
        this.heightUpdate();
        this.frameDoc.addEvent("mousedown", this.selected.closureListener(this)).addEvent("keyup", this.heightUpdate.closureListener(this)).addEvent("keydown", this.keyDown.closureListener(this)).addEvent("keyup", this.selected.closure(this));
        this.ne.fireEvent("add", this);
    },
    getElm: function() {
        return this.frameContent;
    },
    setContent: function(a) {
        this.content = a;
        this.ne.fireEvent("set", this);
        this.frameContent.innerHTML = this.content;
        this.heightUpdate();
    },
    getSel: function() {
        return this.frameWin ? this.frameWin.getSelection() : this.frameDoc.selection;
    },
    heightUpdate: function() {
        this.elmFrame.style.height = Math.max(this.frameContent.offsetHeight, this.initialHeight) + "px";
    },
    nicCommand: function(a, b) {
        this.frameDoc.execCommand(a, false, b);
        setTimeout(this.heightUpdate.closure(this), 100);
    }
});

var nicEditorPanel = bkClass.extend({
    construct: function(a, b, c) {
        this.elm = a;
        this.options = b;
        this.ne = c;
        this.panelButtons = new Array();
        this.buttonList = bkExtend([], this.ne.options.buttonList);
        this.panelContain = new bkElement("DIV").setStyle({
            overflow: "hidden",
            width: "100%",
            border: "0px solid #cccccc"
        }).addClass("panelContain");
        this.panelElm = new bkElement("DIV").setStyle({
            margin: "2px",
            marginTop: "0px",
            zoom: 1,
            overflow: "hidden"
        }).addClass("panel").appendTo(this.panelContain);
        this.panelContain.appendTo(a);
        var d = this.ne.options;
        var e = d.buttons;
        for (button in e) this.addButton(button, d, true);
        this.reorder();
        a.noSelect();
    },
    addButton: function(buttonName, options, noOrder) {
        var button = options.buttons[buttonName];
        var type = button.type ? eval("(typeof(" + button.type + ') == "undefined") ? null : ' + button.type + ";") : nicEditorButton;
        var hasButton = bkLib.inArray(this.buttonList, buttonName);
        if (type && (hasButton || this.ne.options.fullPanel)) {
            this.panelButtons.push(new type(this.panelElm, buttonName, options, this.ne));
            if (!hasButton) this.buttonList.push(buttonName);
        }
    },
    findButton: function(a) {
        for (var b = 0; b < this.panelButtons.length; b++)
            if (this.panelButtons[b].name == a) return this.panelButtons[b];
    },
    reorder: function() {
        var a = this.buttonList;
        for (var b = 0; b < a.length; b++) {
            var c = this.findButton(a[b]);
            if (c) this.panelElm.appendChild(c.margin);
        }
    },
    remove: function() {
        this.elm.remove();
    }
});

var nicEditorButton = bkClass.extend({
    construct: function(a, b, c, d) {
        this.options = c.buttons[b];
        this.name = b;
        this.ne = d;
        this.elm = a;
        this.margin = new bkElement("DIV").setStyle({
            "float": "left",
            marginTop: "2px"
        }).appendTo(a);
        this.contain = new bkElement("DIV").setStyle({
            width: "20px",
            height: "20px"
        }).addClass("buttonContain").appendTo(this.margin);
        this.border = new bkElement("DIV").setStyle({
            border: "1px solid #efefef"
        }).appendTo(this.contain);
        this.button = new bkElement("DIV").setStyle({
            width: "18px",
            height: "18px",
            overflow: "hidden",
            zoom: 1,
            cursor: "pointer"
        }).addClass("button").setStyle(this.ne.getIcon(b, c)).appendTo(this.border);
        this.button.addEvent("mouseover", this.hoverOn.closure(this)).addEvent("mouseout", this.hoverOff.closure(this)).addEvent("mousedown", this.mouseClick.closure(this)).noSelect();
        if (!window.opera) this.button.onmousedown = this.button.onclick = bkLib.cancelEvent;
        d.addEvent("selected", this.enable.closure(this)).addEvent("blur", this.disable.closure(this)).addEvent("key", this.key.closure(this));
        this.disable();
        this.init();
    },
    init: function() {},
    hide: function() {
        this.contain.setStyle({
            display: "none"
        });
    },
    updateState: function() {
        if (this.isDisabled) this.setBg();
        else if (this.isHover) this.setBg("hover");
        else if (this.isActive) this.setBg("active");
        else this.setBg();
    },
    setBg: function(a) {
        switch (a) {
            case "hover":
                var b = {
                    border: "0px solid #666"
                };
                break;

            case "active":
                var b = {
                    border: "0px solid #666"
                };
                break;

            default:
                var b = {
                    border: "0px solid #efefef"
                };
        }
        this.border.setStyle(b).addClass("button-" + a);
    },
    checkNodes: function(a) {
        var b = a;
        do
            if (this.options.tags && bkLib.inArray(this.options.tags, b.nodeName)) {
                this.activate();
                return true;
            }
        while (b = b.parentNode && "nicEdit" != b.className);
        b = $BK(a);
        while (3 == b.nodeType) b = $BK(b.parentNode);
        if (this.options.css)
            for (itm in this.options.css)
                if (b.getStyle(itm, this.ne.selectedInstance.instanceDoc) == this.options.css[itm]) {
                    this.activate();
                    return true;
                }
        this.deactivate();
        return false;
    },
    activate: function() {
        if (!this.isDisabled) {
            this.isActive = true;
            this.updateState();
            this.ne.fireEvent("buttonActivate", this);
        }
    },
    deactivate: function() {
        this.isActive = false;
        this.updateState();
        if (!this.isDisabled) this.ne.fireEvent("buttonDeactivate", this);
    },
    enable: function(a, b) {
        this.isDisabled = false;
        this.contain.setStyle({
            opacity: 1
        }).addClass("buttonEnabled");
        this.updateState();
        this.checkNodes(b);
    },
    disable: function(a, b) {
        this.isDisabled = true;
        this.contain.setStyle({
            opacity: .6
        }).removeClass("buttonEnabled");
        this.updateState();
    },
    toggleActive: function() {
        this.isActive ? this.deactivate() : this.activate();
    },
    hoverOn: function() {
        if (!this.isDisabled) {
            this.isHover = true;
            this.updateState();
            this.ne.fireEvent("buttonOver", this);
        }
    },
    hoverOff: function() {
        this.isHover = false;
        this.updateState();
        this.ne.fireEvent("buttonOut", this);
    },
    mouseClick: function() {
        if (this.options.command) {
            this.ne.nicCommand(this.options.command, this.options.commandArgs);
            if (!this.options.noActive) this.toggleActive();
        }
        this.ne.fireEvent("buttonClick", this);
    },
    key: function(a, b) {
        if (this.options.key && b.ctrlKey && String.fromCharCode(b.keyCode || b.charCode).toLowerCase() == this.options.key) {
            this.mouseClick();
            if (b.preventDefault) b.preventDefault();
        }
    }
});

var nicPlugin = bkClass.extend({
    construct: function(a, b) {
        this.options = b;
        this.ne = a;
        this.ne.addEvent("panel", this.loadPanel.closure(this));
        this.init();
    },
    loadPanel: function(a) {
        var b = this.options.buttons;
        for (var c in b) a.addButton(c, this.options);
        a.reorder();
    },
    init: function() {}
});

var nicPaneOptions = {};

var nicEditorPane = bkClass.extend({
    construct: function(a, b, c, d) {
        this.ne = b;
        this.elm = a;
        this.pos = a.pos();
        this.contain = new bkElement("div").setStyle({
            zIndex: "99999",
            overflow: "hidden",
            position: "absolute",
            left: this.pos[0] + "px",
            top: this.pos[1] + "px"
        });
        this.pane = new bkElement("div").setStyle({
            fontSize: "12px",
            border: "1px solid #ccc",
            backgroundColor: "#ccc",
            overflow: "hidden",
            padding: "4px",
            textAlign: "left"
        }).addClass("pane").setStyle(c).appendTo(this.contain);
        if (d && !d.options.noClose) this.close = new bkElement("div").setStyle({
            "float": "right",
            height: "16px",
            width: "16px",
            cursor: "pointer"
        }).setStyle(this.ne.getIcon("close", nicPaneOptions)).addEvent("mousedown", d.removePane.closure(this)).appendTo(this.pane);
        this.contain.noSelect().appendTo(document.body);
        this.position();
        this.init();
    },
    init: function() {},
    position: function() {
        if (this.ne.nicPanel) {
            var a = this.ne.nicPanel.elm;
            var b = a.pos();
            var c = b[0] + parseInt(a.getStyle("width")) - (parseInt(this.pane.getStyle("width")) + 4);
            if (c < this.pos[0]) this.contain.setStyle({
                left: c + "px"
            });
        }
    },
    toggle: function() {
        this.isVisible = !this.isVisible;
        this.contain.setStyle({
            display: this.isVisible ? "block" : "none"
        });
    },
    remove: function() {
        if (this.contain) {
            this.contain.remove();
            this.contain = null;
        }
    },
    append: function(a) {
        a.appendTo(this.pane);
    },
    setContent: function(a) {
        this.pane.setContent(a);
    }
});

var nicSelectOptions = {
    buttons: {
        fontSize: {
            name: "Select Font Size",
            type: "nicEditorFontSizeSelect",
            command: "fontsize"
        },
        fontFamily: {
            name: "Select Font Family",
            type: "nicEditorFontFamilySelect",
            command: "fontname"
        },
        fontFormat: {
            name: "Select Font Format",
            type: "nicEditorFontFormatSelect",
            command: "formatBlock"
        }
    }
};

var nicEditorSelect = bkClass.extend({
    construct: function(a, b, c, d) {
        this.options = c.buttons[b];
        this.elm = a;
        this.ne = d;
        this.name = b;
        this.selOptions = new Array();
        this.margin = new bkElement("div").setStyle({
            "float": "left",
            margin: "2px 1px 0 1px"
        }).appendTo(this.elm);
        this.contain = new bkElement("div").setStyle({
            width: "90px",
            height: "20px",
            cursor: "pointer",
            overflow: "hidden"
        }).addClass("selectContain").addEvent("click", this.toggle.closure(this)).appendTo(this.margin);
        this.items = new bkElement("div").setStyle({
            overflow: "hidden",
            zoom: 1,
            border: "0px solid #ccc",
            paddingLeft: "3px"
        }).appendTo(this.contain);
        this.control = new bkElement("div").setStyle({
            overflow: "hidden",
            "float": "right",
            height: "18px",
            width: "16px"
        }).addClass("selectControl").setStyle(this.ne.getIcon("arrow", c)).appendTo(this.items);
        this.txt = new bkElement("div").setStyle({
            color: "gray",
            overflow: "hidden",
            "float": "left",
            width: "66px",
            height: "14px",
            marginTop: "1px",
            fontFamily: "sans-serif",
            textAlign: "center",
            fontSize: "12px"
        }).addClass("selectTxt").appendTo(this.items);
        if (!window.opera) this.contain.onmousedown = this.control.onmousedown = this.txt.onmousedown = bkLib.cancelEvent;
        this.margin.noSelect();
        this.ne.addEvent("selected", this.enable.closure(this)).addEvent("blur", this.disable.closure(this));
        this.disable();
        this.init();
    },
    disable: function() {
        this.isDisabled = true;
        this.close();
        this.contain.setStyle({
            opacity: .6
        });
    },
    enable: function(a) {
        this.isDisabled = false;
        this.close();
        this.contain.setStyle({
            opacity: 1
        });
    },
    setDisplay: function(a) {
        this.txt.setContent(a);
    },
    toggle: function() {
        if (!this.isDisabled) this.pane ? this.close() : this.open();
    },
    open: function() {
        this.pane = new nicEditorPane(this.items, this.ne, {
            width: "88px",
            padding: "0px",
            borderTop: 0,
            borderLeft: "1px solid #ccc",
            borderRight: "1px solid #ccc",
            borderBottom: "0px"
        });
        for (var a = 0; a < this.selOptions.length; a++) {
            var b = this.selOptions[a];
            var c = new bkElement("div").setStyle({
                overflow: "hidden",
                borderBottom: "1px solid #ccc",
                width: "88px",
                textAlign: "left",
                overflow: "hidden",
                cursor: "pointer"
            });
            var d = new bkElement("div").setStyle({
                padding: "0px 4px"
            }).setContent(b[1]).appendTo(c).noSelect();
            d.addEvent("click", this.update.closure(this, b[0])).addEvent("mouseover", this.over.closure(this, d)).addEvent("mouseout", this.out.closure(this, d)).setAttributes("id", b[0]);
            this.pane.append(c);
            if (!window.opera) d.onmousedown = bkLib.cancelEvent;
        }
    },
    close: function() {
        if (this.pane) this.pane = this.pane.remove();
    },
    over: function(a) {
        a.setStyle({});
    },
    out: function(a) {
        a.setStyle({});
    },
    add: function(a, b) {
        this.selOptions.push(new Array(a, b));
    },
    update: function(a) {
        this.ne.nicCommand(this.options.command, a);
        this.close();
    }
});

var nicEditorFontSizeSelect = nicEditorSelect.extend({
    sel: {
        1: "1&nbsp;(8pt)",
        2: "2&nbsp;(10pt)",
        3: "3&nbsp;(12pt)",
        4: "4&nbsp;(14pt)",
        5: "5&nbsp;(18pt)",
        6: "6&nbsp;(24pt)"
    },
    init: function() {
        this.setDisplay("Font&nbsp;Size...");
        for (itm in this.sel) this.add(itm, '<font size="' + itm + '">' + this.sel[itm] + "</font>");
    }
});

var nicEditorFontFamilySelect = nicEditorSelect.extend({
    sel: {
        Acidic: "Acidic",
        Aight: "Aight",
        AaronHand: "AaronHand",
        Ballpark: "Ballpark",
        Zealot: "Zealot",
        Spinc: "Spinc",
        Spaceage: "Spaceage",
        Hardrock: "Hardrock",
        Blomster: "Blomster",
        Barbie: "Barbie",
        Walk: "Walk",
        Graffiti: "Graffiti",
        Thickhead: "Thickhead",
        StreetWriters: "StreetWriters",
        streetsoul: "streetsoul",
        Nosed: "Nosed",
        LydiaPuente: "LydiaPuente",
        DJGross: "DJGross",
        Whoa: "Whoa",
        FontBubble: "FontBubble",
        Amsterdam: "Amsterdam",
        Masterplan: "Masterplan",
        Tosca: "Tosca",
        Abite: "Abite",
        GraffitiPaintBrush: "GrfPaintBrush",
        Graffito: "Graffito",
        SubwayGr: "SubwayGr",
        Chunkfive: "Chunkfive",
        arial: "Arial",
        "comic sans ms": "Comic Sans",
        "courier new": "Courier New",
        georgia: "Georgia",
        helvetica: "Helvetica",
        impact: "Impact",
        "times new roman": "Times",
        "trebuchet ms": "Trebuchet",
        verdana: "Verdana"
    },
    init: function() {
        this.setDisplay("Font&nbsp;Family...");
        for (itm in this.sel) this.add(itm, '<font face="' + itm + '">' + this.sel[itm] + "</font>");
    }
});

var nicEditorFontFormatSelect = nicEditorSelect.extend({
    sel: {
        p: "Paragraph",
        pre: "Pre",
        h6: "Heading&nbsp;6",
        h5: "Heading&nbsp;5",
        h4: "Heading&nbsp;4",
        h3: "Heading&nbsp;3",
        h2: "Heading&nbsp;2",
        h1: "Heading&nbsp;1"
    },
    init: function() {
        this.setDisplay("Font&nbsp;Format...");
        for (itm in this.sel) {
            var a = itm.toUpperCase();
            this.add("<" + a + ">", "<" + itm + ' style="padding: 0px; margin: 0px;">' + this.sel[itm] + "</" + a + ">");
        }
    }
});

nicEditors.registerPlugin(nicPlugin, nicSelectOptions);

var nicButtonTips = bkClass.extend({
    construct: function(a) {
        this.ne = a;
        a.addEvent("buttonOver", this.show.closure(this)).addEvent("buttonOut", this.hide.closure(this));
    },
    show: function(a) {
        this.timer = setTimeout(this.create.closure(this, a), 400);
    },
    create: function(a) {
        this.timer = null;
        if (!this.pane) {
            this.pane = new nicEditorPane(a.button, this.ne, {
                fontSize: "12px",
                marginTop: "5px"
            });
            this.pane.setContent(a.options.name);
        }
    },
    hide: function(a) {
        if (this.timer) clearTimeout(this.timer);
        if (this.pane) this.pane = this.pane.remove();
    }
});

nicEditors.registerPlugin(nicButtonTips);

var nicEditorAdvancedButton = nicEditorButton.extend({
    init: function() {
        this.ne.addEvent("selected", this.removePane.closure(this)).addEvent("blur", this.removePane.closure(this));
    },
    mouseClick: function() {
        if (!this.isDisabled)
            if (this.pane && this.pane.pane) this.removePane();
            else {
                this.pane = new nicEditorPane(this.contain, this.ne, {
                    width: this.width || "270px"
                }, this);
                this.addPane();
                this.ne.selectedInstance.saveRng();
            }
    },
    addForm: function(a, b) {
        this.form = new bkElement("form").addEvent("submit", this.submit.closureListener(this));
        this.pane.append(this.form);
        this.inputs = {};
        for (itm in a) {
            var c = a[itm];
            var d = "";
            if (b) d = b.getAttribute(itm);
            if (!d) d = c.value || "";
            var e = a[itm].type;
            if ("title" == e) new bkElement("div").setContent(c.txt).setStyle({
                fontSize: "14px",
                fontWeight: "bold",
                padding: "0px",
                margin: "2px 0"
            }).appendTo(this.form);
            else {
                var f = new bkElement("div").setStyle({
                    overflow: "hidden",
                    clear: "both"
                }).appendTo(this.form);
                if (c.txt) new bkElement("label").setAttributes({
                    "for": itm
                }).setContent(c.txt).setStyle({
                    margin: "2px 4px",
                    fontSize: "13px",
                    width: "50px",
                    lineHeight: "20px",
                    textAlign: "right",
                    "float": "left"
                }).appendTo(f);
                switch (e) {
                    case "text":
                        this.inputs[itm] = new bkElement("input").setAttributes({
                            id: itm,
                            value: d,
                            type: "text"
                        }).setStyle({
                            margin: "2px 0",
                            fontSize: "13px",
                            "float": "left",
                            height: "20px",
                            border: "0px solid #ccc",
                            overflow: "hidden"
                        }).setStyle(c.style).appendTo(f);
                        break;

                    case "select":
                        this.inputs[itm] = new bkElement("select").setAttributes({
                            id: itm
                        }).setStyle({
                            border: "0px solid #ccc",
                            "float": "left",
                            margin: "2px 0"
                        }).appendTo(f);
                        for (opt in c.options) var g = new bkElement("option").setAttributes({
                            value: opt,
                            selected: opt == d ? "selected" : ""
                        }).setContent(c.options[opt]).appendTo(this.inputs[itm]);
                        break;

                    case "content":
                        this.inputs[itm] = new bkElement("textarea").setAttributes({
                            id: itm
                        }).setStyle({
                            border: "0px solid #ccc",
                            "float": "left"
                        }).setStyle(c.style).appendTo(f);
                        this.inputs[itm].value = d;
                }
            }
        }
        new bkElement("input").setAttributes({
            type: "submit"
        }).setStyle({
            border: "0px solid #ccc",
            margin: "3px 0",
            "float": "left",
            clear: "both"
        }).appendTo(this.form);
        this.form.onsubmit = bkLib.cancelEvent;
    },
    submit: function() {},
    findElm: function(a, b, c) {
        var d = this.ne.selectedInstance.getElm().getElementsByTagName(a);
        for (var e = 0; e < d.length; e++)
            if (d[e].getAttribute(b) == c) return $BK(d[e]);
    },
    removePane: function() {
        if (this.pane) {
            this.pane.remove();
            this.pane = null;
            this.ne.selectedInstance.restoreRng();
        }
    }
});

var nicLinkOptions = {
    buttons: {
        link: {
            name: "Add Link",
            type: "nicLinkButton",
            tags: ["A"]
        },
        unlink: {
            name: "Remove Link",
            command: "unlink",
            noActive: true
        }
    }
};

var nicLinkButton = nicEditorAdvancedButton.extend({
    addPane: function() {
        this.ln = this.ne.selectedInstance.selElm().parentTag("A");
        this.addForm({
            "": {
                type: "title",
                txt: "Add/Edit Link"
            },
            href: {
                type: "text",
                txt: "URL",
                value: "http://",
                style: {
                    width: "150px"
                }
            },
            title: {
                type: "text",
                txt: "Title"
            },
            target: {
                type: "select",
                txt: "Open In",
                options: {
                    "": "Current Window",
                    _blank: "New Window"
                },
                style: {
                    width: "100px"
                }
            }
        }, this.ln);
    },
    submit: function(a) {
        var b = this.inputs.href.value;
        if ("http://" == b || "" == b) {
            alert("You must enter a URL to Create a Link");
            return false;
        }
        this.removePane();
        if (!this.ln) {
            var c = "javascript:nicTemp();";
            this.ne.nicCommand("createlink", c);
            this.ln = this.findElm("A", "href", c);
        }
        if (this.ln) this.ln.setAttributes({
            href: this.inputs.href.value,
            title: this.inputs.title.value,
            target: this.inputs.target.options[this.inputs.target.selectedIndex].value
        });
    }
});

nicEditors.registerPlugin(nicPlugin, nicLinkOptions);

var nicColorOptions = {
    buttons: {
        forecolor: {
            name: __("Change Text Color"),
            type: "nicEditorColorButton",
            noClose: true
        },
        bgcolor: {
            name: __("Change Background Color"),
            type: "nicEditorBgColorButton",
            noClose: true
        }
    }
};

var nicEditorColorButton = nicEditorAdvancedButton.extend({
    addPane: function() {
        var a = {
            0: "00",
            1: "33",
            2: "66",
            3: "99",
            4: "CC",
            5: "FF"
        };
        var b = new bkElement("DIV").setStyle({
            width: "270px"
        });
        for (var c in a)
            for (var d in a)
                for (var e in a) {
                    var f = "#" + a[c] + a[e] + a[d];
                    var g = new bkElement("DIV").setStyle({
                        cursor: "pointer",
                        height: "15px",
                        "float": "left"
                    }).appendTo(b);
                    var h = new bkElement("DIV").setStyle({
                        border: "2px solid " + f
                    }).appendTo(g);
                    var i = new bkElement("DIV").setStyle({
                        backgroundColor: f,
                        overflow: "hidden",
                        width: "11px",
                        height: "11px"
                    }).addEvent("click", this.colorSelect.closure(this, f)).addEvent("mouseover", this.on.closure(this, h)).addEvent("mouseout", this.off.closure(this, h, f)).appendTo(h);
                    if (!window.opera) g.onmousedown = i.onmousedown = bkLib.cancelEvent;
                }
        this.pane.append(b.noSelect());
    },
    colorSelect: function(a) {
        this.ne.nicCommand("foreColor", a);
        this.removePane();
    },
    on: function(a) {
        a.setStyle({
            border: "2px solid #FFF"
        });
    },
    off: function(a, b) {
        a.setStyle({
            border: "2px solid " + b
        });
    }
});

var nicEditorBgColorButton = nicEditorColorButton.extend({
    colorSelect: function(a) {
        this.ne.nicCommand("hiliteColor", a);
        this.removePane();
    }
});

nicEditors.registerPlugin(nicPlugin, nicColorOptions);

var nicImageOptions = {
    buttons: {
        image: {
            name: "Add Image",
            type: "nicImageButton",
            tags: ["IMG"]
        }
    }
};

var nicImageButton = nicEditorAdvancedButton.extend({
    addPane: function() {
        this.im = this.ne.selectedInstance.selElm().parentTag("IMG");
        this.addForm({
            "": {
                type: "title",
                txt: "Add/Edit Image"
            },
            src: {
                type: "text",
                txt: "URL",
                value: "http://",
                style: {
                    width: "150px"
                }
            },
            alt: {
                type: "text",
                txt: "Alt Text",
                style: {
                    width: "100px"
                }
            },
            align: {
                type: "select",
                txt: "Align",
                options: {
                    none: "Default",
                    left: "Left",
                    right: "Right"
                }
            }
        }, this.im);
    },
    submit: function(a) {
        var b = this.inputs.src.value;
        if ("" == b || "http://" == b) {
            alert("You must enter a Image URL to insert");
            return false;
        }
        this.removePane();
        if (!this.im) {
            var c = "javascript:nicImTemp();";
            this.ne.nicCommand("insertImage", c);
            this.im = this.findElm("IMG", "src", c);
        }
        if (this.im) this.im.setAttributes({
            src: this.inputs.src.value,
            alt: this.inputs.alt.value,
            align: this.inputs.align.value
        });
    }
});

nicEditors.registerPlugin(nicPlugin, nicImageOptions);

var nicSaveOptions = {
    buttons: {
        save: {
            name: __("Save this content"),
            type: "nicEditorSaveButton"
        }
    }
};

var nicEditorSaveButton = nicEditorButton.extend({
    init: function() {
        if (!this.ne.options.onSave) this.margin.setStyle({
            display: "none"
        });
    },
    mouseClick: function() {
        var a = this.ne.options.onSave;
        var b = this.ne.selectedInstance;
        a(b.getContent(), b.elm.id, b);
    }
});

nicEditors.registerPlugin(nicPlugin, nicSaveOptions);

var nicXHTML = bkClass.extend({
    stripAttributes: ["_moz_dirty", "_moz_resizing", "_extended"],
    noShort: ["style", "title", "script", "textarea", "a"],
    cssReplace: {
        "font-weight:bold;": "strong",
        "font-style:italic;": "em"
    },
    sizes: {
        1: "xx-small",
        2: "x-small",
        3: "small",
        4: "medium",
        5: "large",
        6: "x-large"
    },
    construct: function(a) {
        this.ne = a;
        if (this.ne.options.xhtml) a.addEvent("get", this.cleanup.closure(this));
    },
    cleanup: function(a) {
        var b = a.getElm();
        var c = this.toXHTML(b);
        a.content = c;
    },
    toXHTML: function(a, b, c) {
        var d = "";
        var e = "";
        var f = "";
        var g = a.nodeType;
        var h = a.nodeName.toLowerCase();
        var i = a.hasChildNodes && a.hasChildNodes();
        var j = new Array();
        switch (g) {
            case 1:
                var k = a.attributes;
                switch (h) {
                    case "b":
                        h = "strong";
                        break;

                    case "i":
                        h = "em";
                        break;

                    case "font":
                        h = "span";
                }
                if (b) {
                    for (var l = 0; l < k.length; l++) {
                        var m = k[l];
                        var n = m.nodeName.toLowerCase();
                        var o = m.nodeValue;
                        if (!m.specified || !o || bkLib.inArray(this.stripAttributes, n) || "function" == typeof o) continue;
                        switch (n) {
                            case "style":
                                var p = o.replace(/ /g, "");
                                for (itm in this.cssReplace)
                                    if (p.indexOf(itm) != -1) {
                                        j.push(this.cssReplace[itm]);
                                        p = p.replace(itm, "");
                                    }
                                f += p;
                                o = "";
                                break;

                            case "class":
                                o = o.replace("Apple-style-span", "");
                                break;

                            case "size":
                                f += "font-size:" + this.sizes[o] + ";";
                                o = "";
                        }
                        if (o) e += " " + n + '="' + o + '"';
                    }
                    if (f) e += ' style="' + f + '"';
                    for (var l = 0; l < j.length; l++) d += "<" + j[l] + ">";
                    if ("" == e && "span" == h) b = false;
                    if (b) {
                        d += "<" + h;
                        if ("br" != h) d += e;
                    }
                }
                if (!i && !bkLib.inArray(this.noShort, n)) {
                    if (b) d += " />";
                } else {
                    if (b) d += ">";
                    for (var l = 0; l < a.childNodes.length; l++) {
                        var q = this.toXHTML(a.childNodes[l], true, true);
                        if (q) d += q;
                    }
                }
                if (b && i) d += "</" + h + ">";
                for (var l = 0; l < j.length; l++) d += "</" + j[l] + ">";
                break;

            case 3:
                d += a.nodeValue;
        }
        return d;
    }
});

nicEditors.registerPlugin(nicXHTML);

var nicCodeOptions = {
    buttons: {
        xhtml: {
            name: "Edit HTML",
            type: "nicCodeButton"
        }
    }
};

var nicCodeButton = nicEditorAdvancedButton.extend({
    width: "350px",
    addPane: function() {
        this.addForm({
            "": {
                type: "title",
                txt: "Edit HTML"
            },
            code: {
                type: "content",
                value: this.ne.selectedInstance.getContent(),
                style: {
                    width: "340px",
                    height: "200px"
                }
            }
        });
    },
    submit: function(a) {
        var b = this.inputs.code.value;
        this.ne.selectedInstance.setContent(b);
        this.removePane();
    }
});

nicEditors.registerPlugin(nicPlugin, nicCodeOptions);

var nicBBCode = bkClass.extend({
    construct: function(a) {
        this.ne = a;
        if (this.ne.options.bbCode) {
            a.addEvent("get", this.bbGet.closure(this));
            a.addEvent("set", this.bbSet.closure(this));
            var b = this.ne.loadedPlugins;
            for (itm in b)
                if (b[itm].toXHTML) this.xhtml = b[itm];
        }
    },
    bbGet: function(a) {
        var b = this.xhtml.toXHTML(a.getElm());
        a.content = this.toBBCode(b);
    },
    bbSet: function(a) {
        a.content = this.fromBBCode(a.content);
    },
    toBBCode: function(a) {
        function b(b, c) {
            a = a.replace(b, c);
        }
        b(/\n/gi, "");
        b(/<strong>(.*?)<\/strong>/gi, "[b]$1[/b]");
        b(/<em>(.*?)<\/em>/gi, "[i]$1[/i]");
        b(/<span.*?style="text-decoration:underline;">(.*?)<\/span>/gi, "[u]$1[/u]");
        b(/<ul>(.*?)<\/ul>/gi, "[list]$1[/list]");
        b(/<li>(.*?)<\/li>/gi, "[*]$1[]");
        b(/<ol>(.*?)<\/ol>/gi, "[list=1]$1[/list]");
        b(/<img.*?src="(.*?)".*?>/gi, "[img]$1[/img]");
        b(/<a.*?href="(.*?)".*?>(.*?)<\/a>/gi, "[url=$1]$2[/url]");
        b(/<br.*?>/gi, "\n");
        b(/<.*?>.*?<\/.*?>/gi, "");
        return a;
    },
    fromBBCode: function(a) {
        function b(b, c) {
            a = a.replace(b, c);
        }
        b(/\[b\](.*?)\[\/b\]/gi, "<strong>$1</strong>");
        b(/\[i\](.*?)\[\/i\]/gi, "<em>$1</em>");
        b(/\[u\](.*?)\[\/u\]/gi, '<span style="text-decoration:underline;">$1</span>');
        b(/\[list\](.*?)\[\/list\]/gi, "<ul>$1</ul>");
        b(/\[list=1\](.*?)\[\/list\]/gi, "<ol>$1</ol>");
        b(/\[\*\](.*?)\[\/\*\]/gi, "<li>$1</li>");
        b(/\[img\](.*?)\[\/img\]/gi, '<img src="$1" />');
        b(/\[url=(.*?)\](.*?)\[\/url\]/gi, '<a href="$1">$2</a>');
        b(/\n/gi, "<br />");
        return a;
    }
});

nicEditors.registerPlugin(nicBBCode);

var nicUploadOptions = {
    buttons: {
        upload: {
            name: "Upload Image",
            type: "nicUploadButton"
        }
    }
};

var nicUploadButton = nicEditorAdvancedButton.extend({
    nicURI: "http://api.imgur.com/2/upload.json",
    errorText: "Failed to upload image",
    addPane: function() {
        if ("undefined" === typeof window.FormData) return this.onError("Image uploads are not supported in this browser, use Chrome, Firefox, or Safari instead.");
        this.im = this.ne.selectedInstance.selElm().parentTag("IMG");
        var a = new bkElement("div").setStyle({
            padding: "10px"
        }).appendTo(this.pane.pane);
        new bkElement("div").setStyle({
            fontSize: "14px",
            fontWeight: "bold",
            paddingBottom: "5px"
        }).setContent("Insert an Image").appendTo(a);
        this.fileInput = new bkElement("input").setAttributes({
            type: "file"
        }).appendTo(a);
        this.progress = new bkElement("progress").setStyle({
            width: "100%",
            display: "none"
        }).setAttributes("max", 100).appendTo(a);
        this.fileInput.onchange = this.uploadFile.closure(this);
    },
    onError: function(a) {
        this.removePane();
        alert(a || "Failed to upload image");
    },
    uploadFile: function() {
        var a = this.fileInput.files[0];
        if (!a || !a.type.match(/image.*/)) {
            this.onError("Only image files can be uploaded");
            return;
        }
        this.fileInput.setStyle({
            display: "none"
        });
        this.setProgress(0);
        var b = new FormData();
        b.append("image", a);
        b.append("key", "b7ea18a4ecbda8e92203fa4968d10660");
        var c = new XMLHttpRequest();
        c.open("POST", this.ne.options.uploadURI || this.nicURI);
        c.onload = function() {
            try {
                var a = JSON.parse(c.responseText);
            } catch (b) {
                return this.onError();
            }
            this.onUploaded(a.upload);
        }.closure(this);
        c.onerror = this.onError.closure(this);
        c.upload.onprogress = function(a) {
            this.setProgress(a.loaded / a.total);
        }.closure(this);
        c.send(b);
    },
    setProgress: function(a) {
        this.progress.setStyle({
            display: "block"
        });
        if (a < .98) this.progress.value = a;
        else this.progress.removeAttribute("value");
    },
    onUploaded: function(a) {
        this.removePane();
        var b = a.links.original;
        if (!this.im) {
            this.ne.selectedInstance.restoreRng();
            var c = "javascript:nicImTemp();";
            this.ne.nicCommand("insertImage", b);
            this.im = this.findElm("IMG", "src", b);
        }
        var d = parseInt(this.ne.selectedInstance.elm.getStyle("width"));
        if (this.im) this.im.setAttributes({
            src: b,
            width: d && a.image.width ? Math.min(d, a.image.width) : ""
        });
    }
});

nicEditors.registerPlugin(nicPlugin, nicUploadOptions);