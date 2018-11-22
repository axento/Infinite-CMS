tinymce.init({
    selector: "textarea.mceEditor",
    theme: "modern",
    height: 400,
    plugins: [
        "advlist autolink responsivefilemanager lists charmap preview hr anchor",
        "wordcount visualblocks visualchars insertdatetime nonbreaking",
        "table contextmenu paste textcolor code"
    ],
    content_css : "/css/main.css?" + new Date().getTime(),
    toolbar: "styleselect | fontsizeselect | forecolor backcolor | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | responsivefilemanager | preview code",
    style_formats: [
	    {title: 'Paragraph', format: 'p'},
        {title: 'Hoofding 1', block: 'h1'},
        {title: 'Hoofding 2', block: 'h2'},
        {title: 'Hoofding 3', block: 'h3'},
        {title: 'Hoofding 4', block: 'h4'},
        {title: 'Hoofding 5', block: 'h5'},
        {title: 'Hoofding 6', block: 'h6'}
    ],
    language : 'nl',
    external_filemanager_path:"/filemanager/",
    filemanager_title:"Filemanager" ,
    external_plugins: { "filemanager" : "/filemanager/plugin.min.js"}
});