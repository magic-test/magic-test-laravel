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

    let label = event.target.labels?.[0];

    if (label) {
        targetMeta.label = label.innerText;
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