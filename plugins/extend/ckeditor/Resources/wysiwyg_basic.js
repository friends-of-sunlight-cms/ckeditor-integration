$(document).ready(function () {

    CKEDITOR.config.defaultLanguage = SunlightVars.pluginWysiwyg.systemLang;
    CKEDITOR.config.height = 400;
    CKEDITOR.config.ignoreEmptyParagraph = true;
    CKEDITOR.config.entities = false;
    CKEDITOR.config.preset = 'standard';

    CKEDITOR.config.plugins = 'a11yhelp,about,basicstyles,blockquote,colorbutton,contextmenu,elementspath,enterkey,entities,filebrowser,floatingspace,format,horizontalrule,htmlwriter,image,indentlist,link,list,magicline,maximize,pastefromword,pastetext,removeformat,resize,scayt,showborders,sourcearea,specialchar,stylescombo,tab,table,tableselection,tabletools,toolbar,undo,uploadimage,wsc,wysiwygarea';
    CKEDITOR.config.toolbarGroups = [
        {name: 'clipboard', groups: ['clipboard', 'undo']},
        {name: 'editing', groups: ['find', 'selection', 'spellchecker']},
        {name: 'links'},
        {name: 'insert'},
        {name: 'forms'},
        {name: 'tools'},
        {name: 'document', groups: ['mode', 'document', 'doctools']},
        {name: 'others'},
        '/',
        {name: 'basicstyles', groups: ['basicstyles', 'cleanup']},
        {name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align', 'bidi']},
        {name: 'styles'},
        {name: 'colors'},
        {name: 'about'}
    ];
    // The default plugins included in the basic setup define some buttons that
    // are not needed in a basic editor. They are removed here.
    CKEDITOR.config.removeButtons = 'Underline,Subscript,Superscript';
    CKEDITOR.config.format_tags = 'p;h1;h2;h3;pre';
    // Dialog windows are also simplified.
    CKEDITOR.config.removeDialogTabs = 'image:advanced;link:advanced';

    $('textarea.editor:not([name=perex])').each(function () {
        CKEDITOR.replace(this);
    });
});