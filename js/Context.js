export function enableKeyboardShortcuts() {
    function keydown(event) {
        if (event.ctrlKey && event.shiftKey && event.key === 'A') {
            event.preventDefault();
            generateAssertion();
        }
    }

    document.addEventListener('keydown', keydown, false);
    
    function generateAssertion() {
        let text = selectedText();
        if (text.trim().length > 0) {
            MagicTest.addData({
                action: 'see',
                attributes: [],
                parent: [],
                tag: null,
                meta: {
                    text: text
                }
            });
            alert("Generated an assertion for \"" + selectedText() + "\". Type `ok` in the debugger console to add it to your test file.");
        }
    }

    function selectedText() {
        var text = "";
        if (window.getSelection) {
            text = window.getSelection().toString();
        } else if (document.selection && document.selection.type != "Control") {
            text = document.selection.createRange().text;
        }
        return text;
    }
}