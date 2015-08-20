tinyMCEPreInit = {
    baseURL: "http://plugins.php.hgv.dev/wp-includes/js/tinymce",
    suffix: "",
    dragDropUpload: true,
    mceInit: {
        'content': {
            theme: "modern",
            skin: "lightgray",
            language: "en",
            formats: {
                alignleft: [{
                    selector: "p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li",
                    styles: {textAlign: "left"}
                }, {selector: "img,table,dl.wp-caption", classes: "alignleft"}],
                aligncenter: [{
                    selector: "p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li",
                    styles: {textAlign: "center"}
                }, {selector: "img,table,dl.wp-caption", classes: "aligncenter"}],
                alignright: [{
                    selector: "p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li",
                    styles: {textAlign: "right"}
                }, {selector: "img,table,dl.wp-caption", classes: "alignright"}],
                strikethrough: {inline: "del"}
            },
            relative_urls: false,
            remove_script_host: false,
            convert_urls: false,
            browser_spellcheck: true,
            fix_list_elements: true,
            entities: "38,amp,60,lt,62,gt",
            entity_encoding: "raw",
            keep_styles: false,
            cache_suffix: "wp-mce-4203-20150730",
            preview_styles: "font-family font-size font-weight font-style text-decoration text-transform",
            end_container_on_empty_block: true,
            wpeditimage_disable_captions: false,
            wpeditimage_html5_captions: true,
            plugins: "charmap,colorpicker,hr,lists,media,paste,tabfocus,textcolor,fullscreen,wordpress,wpautoresize,wpeditimage,wpemoji,wpgallery,wplink,wpdialogs,wptextpattern,wpview",
            content_css: "http://plugins.php.hgv.dev/wp-includes/css/dashicons.css?ver=4.3,http://plugins.php.hgv.dev/wp-includes/js/tinymce/skins/wordpress/wp-content.css?ver=4.3,https://fonts.googleapis.com/css?family=Noto+Sans%3A400italic%2C700italic%2C400%2C700%7CNoto+Serif%3A400italic%2C700italic%2C400%2C700%7CInconsolata%3A400%2C700&subset=latin%2Clatin-ext,http://plugins.php.hgv.dev/wp-content/themes/twentyfifteen/css/editor-style.css,http://plugins.php.hgv.dev/wp-content/themes/twentyfifteen/genericons/genericons.css",
            selector: "#content",
            resize: false,
            menubar: false,
            wpautop: true,
            indent: false,
            toolbar1: "bold,italic,strikethrough,bullist,numlist,blockquote,hr,alignleft,aligncenter,alignright,link,unlink,wp_more,spellchecker,dfw,wp_adv",
            toolbar2: "formatselect,underline,alignjustify,forecolor,pastetext,removeformat,charmap,outdent,indent,undo,redo,wp_help",
            toolbar3: "",
            toolbar4: "",
            tabfocus_elements: "content-html,save-post",
            body_class: "content post-type-page post-status-publish locale-en-us",
            wp_autoresize_on: true,
            add_unload_trigger: false
        },
        'mcs-section-editor-4': {
            theme: "modern",
            skin: "lightgray",
            language: "en",
            formats: {
                alignleft: [{
                    selector: "p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li",
                    styles: {textAlign: "left"}
                }, {selector: "img,table,dl.wp-caption", classes: "alignleft"}],
                aligncenter: [{
                    selector: "p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li",
                    styles: {textAlign: "center"}
                }, {selector: "img,table,dl.wp-caption", classes: "aligncenter"}],
                alignright: [{
                    selector: "p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li",
                    styles: {textAlign: "right"}
                }, {selector: "img,table,dl.wp-caption", classes: "alignright"}],
                strikethrough: {inline: "del"}
            },
            relative_urls: false,
            remove_script_host: false,
            convert_urls: false,
            browser_spellcheck: true,
            fix_list_elements: true,
            entities: "38,amp,60,lt,62,gt",
            entity_encoding: "raw",
            keep_styles: false,
            cache_suffix: "wp-mce-4203-20150730",
            preview_styles: "font-family font-size font-weight font-style text-decoration text-transform",
            end_container_on_empty_block: true,
            wpeditimage_disable_captions: false,
            wpeditimage_html5_captions: true,
            plugins: "charmap,colorpicker,hr,lists,media,paste,tabfocus,textcolor,fullscreen,wordpress,wpautoresize,wpeditimage,wpemoji,wpgallery,wplink,wpdialogs,wptextpattern,wpview",
            content_css: "http://plugins.php.hgv.dev/wp-includes/css/dashicons.css?ver=4.3,http://plugins.php.hgv.dev/wp-includes/js/tinymce/skins/wordpress/wp-content.css?ver=4.3,https://fonts.googleapis.com/css?family=Noto+Sans%3A400italic%2C700italic%2C400%2C700%7CNoto+Serif%3A400italic%2C700italic%2C400%2C700%7CInconsolata%3A400%2C700&subset=latin%2Clatin-ext,http://plugins.php.hgv.dev/wp-content/themes/twentyfifteen/css/editor-style.css,http://plugins.php.hgv.dev/wp-content/themes/twentyfifteen/genericons/genericons.css",
            selector: "#mcs-section-editor-4",
            resize: "vertical",
            menubar: false,
            wpautop: true,
            indent: false,
            toolbar1: "bold,italic,strikethrough,bullist,numlist,blockquote,hr,alignleft,aligncenter,alignright,link,unlink,wp_more,spellchecker,fullscreen,wp_adv",
            toolbar2: "formatselect,underline,alignjustify,forecolor,pastetext,removeformat,charmap,outdent,indent,undo,redo,wp_help",
            toolbar3: "",
            toolbar4: "",
            tabfocus_elements: ":prev,:next",
            body_class: "mcs-section-editor-4 post-type-page post-status-publish locale-en-us"
        },
        'mcs-section-editor-8': {
            theme: "modern",
            skin: "lightgray",
            language: "en",
            formats: {
                alignleft: [{
                    selector: "p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li",
                    styles: {textAlign: "left"}
                }, {selector: "img,table,dl.wp-caption", classes: "alignleft"}],
                aligncenter: [{
                    selector: "p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li",
                    styles: {textAlign: "center"}
                }, {selector: "img,table,dl.wp-caption", classes: "aligncenter"}],
                alignright: [{
                    selector: "p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li",
                    styles: {textAlign: "right"}
                }, {selector: "img,table,dl.wp-caption", classes: "alignright"}],
                strikethrough: {inline: "del"}
            },
            relative_urls: false,
            remove_script_host: false,
            convert_urls: false,
            browser_spellcheck: true,
            fix_list_elements: true,
            entities: "38,amp,60,lt,62,gt",
            entity_encoding: "raw",
            keep_styles: false,
            cache_suffix: "wp-mce-4203-20150730",
            preview_styles: "font-family font-size font-weight font-style text-decoration text-transform",
            end_container_on_empty_block: true,
            wpeditimage_disable_captions: false,
            wpeditimage_html5_captions: true,
            plugins: "charmap,colorpicker,hr,lists,media,paste,tabfocus,textcolor,fullscreen,wordpress,wpautoresize,wpeditimage,wpemoji,wpgallery,wplink,wpdialogs,wptextpattern,wpview",
            content_css: "http://plugins.php.hgv.dev/wp-includes/css/dashicons.css?ver=4.3,http://plugins.php.hgv.dev/wp-includes/js/tinymce/skins/wordpress/wp-content.css?ver=4.3,https://fonts.googleapis.com/css?family=Noto+Sans%3A400italic%2C700italic%2C400%2C700%7CNoto+Serif%3A400italic%2C700italic%2C400%2C700%7CInconsolata%3A400%2C700&subset=latin%2Clatin-ext,http://plugins.php.hgv.dev/wp-content/themes/twentyfifteen/css/editor-style.css,http://plugins.php.hgv.dev/wp-content/themes/twentyfifteen/genericons/genericons.css",
            selector: "#mcs-section-editor-8",
            resize: "vertical",
            menubar: false,
            wpautop: true,
            indent: false,
            toolbar1: "bold,italic,strikethrough,bullist,numlist,blockquote,hr,alignleft,aligncenter,alignright,link,unlink,wp_more,spellchecker,fullscreen,wp_adv",
            toolbar2: "formatselect,underline,alignjustify,forecolor,pastetext,removeformat,charmap,outdent,indent,undo,redo,wp_help",
            toolbar3: "",
            toolbar4: "",
            tabfocus_elements: ":prev,:next",
            body_class: "mcs-section-editor-8 post-type-page post-status-publish locale-en-us"
        }
    },
    qtInit: {
        'content': {id: "content", buttons: "strong,em,link,block,del,ins,img,ul,ol,li,code,more,close,dfw"},
        'mcs-section-editor-4': {
            id: "mcs-section-editor-4",
            buttons: "strong,em,link,block,del,ins,img,ul,ol,li,code,more,close"
        },
        'mcs-section-editor-8': {
            id: "mcs-section-editor-8",
            buttons: "strong,em,link,block,del,ins,img,ul,ol,li,code,more,close"
        },
        'replycontent': {id: "replycontent", buttons: "strong,em,link,block,del,ins,img,ul,ol,li,code,close"}
    },
    ref: {
        plugins: "charmap,colorpicker,hr,lists,media,paste,tabfocus,textcolor,fullscreen,wordpress,wpautoresize,wpeditimage,wpemoji,wpgallery,wplink,wpdialogs,wptextpattern,wpview",
        theme: "modern",
        language: "en"
    },
    load_ext: function (url, lang) {
        var sl = tinymce.ScriptLoader;
        sl.markDone(url + '/langs/' + lang + '.js');
        sl.markDone(url + '/langs/' + lang + '_dlg.js');
    }
};