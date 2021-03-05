import { getPathTo } from './../Finders';

export default function click(event) {
    console.log(event);
    var tagName = event.currentTarget.tagName;
    var classList = event.currentTarget.classList;
    var action = "";
    var target = "";
    var options = "";
    var path = '';
    var targetMeta = {
        type: event.target.type || null
    };

    if (
        tagName == "BUTTON" ||
        tagName == "A" ||
        (tagName == "INPUT" && event.currentTarget.type == "submit")
    ) {
        action = "click";
        var target = event.currentTarget.value || event.currentTarget.text || event.currentTarget.innerText;
        if (!target) {
            return;
        }
        target = "'" + target.trim().replace("'", "\\'") + "'";
    } else if (tagName == 'SELECT') {
        action = "click";
        var target = event.currentTarget.name;

        target = "'" + target.trim().replace("'", "\\'") + "'";

    } else if (tagName == "INPUT") {
        let ignoreType = [
            "text",
            "password",
            "date",
            "email",
            "month",
            "number",
            "search",
        ];
        if (ignoreType.includes(event.currentTarget.type)) {
            return;
        }
        var path = getPathTo(event.currentTarget);
        target = event.currentTarget.name;
        action = `click`;
    } else {
        return;
    }

    if (tagName === 'SELECT') {
        targetMeta.label = event.currentTarget.value;

        var testingOutput = JSON.parse(sessionStorage.getItem("testingOutput"));
        var lastAction = testingOutput[testingOutput.length - 1];

        // In case the latest action was the same select, we don't want to add a new one,
        // just change the target meta on the previous one.
        if (lastAction && lastAction.tag == tagName.toLowerCase() && lastAction.target == target) {
            lastAction.targetMeta = targetMeta;

            sessionStorage.setItem("testingOutput", JSON.stringify(testingOutput));

            return;
        }

    } else {
        let label = event.target.labels?.[0];

        if (label) {
            targetMeta.label = label.innerText;
        }
    }


    MagicTest.addData({
        action: action,
        path: path,
        target: target,
        options: options,
        classList: classList,
        tag: tagName.toLowerCase(),
        targetMeta: targetMeta
    });
}