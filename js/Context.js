export function enableKeyboardShortcuts() {
    function keydown(event) {
        if (event.ctrlKey && event.shiftKey && event.key === 'A') {
            event.preventDefault();
            generateAssertion();
        }
    }

    document.addEventListener('keydown', keydown, false);
    
    function generateAssertion() {
        var text = selectedText();
        if (text.trim().length > 0) {
            var action = "see";
            var testingOutput = JSON.parse(sessionStorage.getItem("testingOutput"));
            var target = `'${text.replace("'", "\\\'")}'`;
            var options = "";
            MagicTest.addData({
                action: action,
                path: '',
                target: target,
                options: options,
                classList: [],
                tag: '',
            });
            alert("Generated an assertion for \"" + selectedText() + "\". Type `ok()` in the debugger console to add it to your test file.");
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