$(document).ready(function () {

    CKEDITOR.config.defaultLanguage = SunlightVars.pluginWysiwyg.systemLang;
    CKEDITOR.config.height = 400;
    CKEDITOR.config.ignoreEmptyParagraph = true;
    CKEDITOR.config.entities = false;
    CKEDITOR.config.preset = 'basic';
    CKEDITOR.config.plugins = 'about,basicstyles,clipboard,colorbutton,enterkey,entities,format,floatingspace,removeformat,toolbar,undo,wysiwygarea';
    CKEDITOR.config.toolbarGroups = [
        {name: 'document', groups: ['mode', 'document', 'doctools']},
        {name: 'clipboard', groups: ['clipboard', 'undo']},
        {name: 'editing', groups: ['find', 'selection', 'spellchecker']},
        {name: 'forms'},
        {name: 'basicstyles', groups: ['basicstyles', 'cleanup']},
        {name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align', 'bidi']},
        {name: 'links'},
        {name: 'insert'},
        {name: 'styles'},
        {name: 'colors'},
        {name: 'tools'},
        {name: 'others'},
        {name: 'about'}
    ];
    // The default plugins included in the basic setup define some buttons that
    // are not needed in a basic editor. They are removed here.
    CKEDITOR.config.removeButtons = 'Cut,Copy,Paste,Undo,Redo,Anchor,Underline,Strike,Subscript,Superscript';
    // Dialog windows are also simplified.
    CKEDITOR.config.removeDialogTabs = 'link:advanced';

    $('textarea.editor:not([name=perex])').each(function () {
        CKEDITOR.replace(this);
    });
});