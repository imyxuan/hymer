ace.config.set('basePath', $('meta[name="assets-path"]').attr('content')+"?path=libs/ace-1.32.6")
ace.config.set('modePath', $('meta[name="assets-path"]').attr('content')+"?path=libs/ace-1.32.6")
ace.config.set('themePath', $('meta[name="assets-path"]').attr('content')+"?path=libs/ace-1.32.6")
ace.config.set('workerPath', $('meta[name="assets-path"]').attr('content')+"?path=libs/ace-1.32.6")

const ace_editor_element = document.getElementsByClassName("ace-editor")

// For each ace editor element on the page
for(let i = 0; i < ace_editor_element.length; i++) {

    // Create an ace editor instance
    const ace_editor = ace.edit(ace_editor_element[i].id)

    // Get the corresponding text area associated with the ace editor
    let ace_editor_textarea = document.getElementById(ace_editor_element[i].id + '_textarea')

    if(ace_editor_element[i].getAttribute('data-theme')){
        ace_editor.setTheme("ace/theme/" + ace_editor_element[i].getAttribute('data-theme'))
    }

    if(ace_editor_element[i].getAttribute('data-language')){
        ace_editor.getSession()
            .setMode("ace/mode/" + ace_editor_element[i].getAttribute('data-language'))
    }

    ace_editor.on('change', function(event, el) {
        let ace_editor_id = el.container.id;
        ace_editor_textarea = document.getElementById(ace_editor_id + '_textarea')
        let ace_editor_instance = ace.edit(ace_editor_id)
        ace_editor_textarea.value = ace_editor_instance.getValue()
    })
}
