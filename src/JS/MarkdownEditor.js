



function createMarkdownEditor(easyMDESettings){
    let editor = new EasyMDE(easyMDESettings)

    // Enforce textarea maxLength by attaching a function to the
    // CodeMirror beforeChange event.
    let maxLength = editor.element.maxLength
    if (maxLength > 0) {
        editor.codemirror.on("beforeChange", function (cm, change) {
            if (change.update) {
                let str = change.text.join("\n")

                let delta = str.length - (cm.indexFromPos(change.to) - cm.indexFromPos(change.from))

                if (delta <= 0) { return true }

                delta = cm.getValue().length + delta - maxLength

                if (delta > 0) {
                    str = str.substr(0, str.length - delta)
                    change.update(change.from, change.to, str.split("\n"))
                }
            }
            return true
        })
    }
    return editor
}