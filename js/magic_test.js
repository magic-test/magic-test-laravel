function initializeStorage() {
    if (sessionStorage.getItem("testingOutput") == null) {
        sessionStorage.setItem("testingOutput", JSON.stringify([]));
    }
}

$(function () {
    console.log("Magic Test started");
    initializeStorage();
});

function clickFunction(event) {
    var tagName = event.currentTarget.tagName;
    var classList = event.currentTarget.classList;
    var action = "";
    var target = "";
    var options = "";
    var path = '';
    if (
        tagName == "BUTTON" ||
        tagName == "A" ||
        (tagName == "INPUT" && event.currentTarget.type == "submit")
    ) {
        action = "click";
        var target = event.currentTarget.value || event.currentTarget.text;
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
        action = `click(${path}`;
    } else {
        return;
    }

    MagicTest.addData({
        action: action,
        path: path,
        target: target,
        options: options,
        classList: classList,
        tag: tagName.toLowerCase(),

    });
}

$(document).on("click", "*", function (event) {
    clickFunction(event);
});

window.MagicTest = {
    getData() {
        return sessionStorage.getItem("testingOutput") || {};
    },
    addData(data) {
      let testingOutput = JSON.parse(sessionStorage.getItem("testingOutput"));

      testingOutput.push(data);
      
      sessionStorage.setItem("testingOutput", JSON.stringify(testingOutput));
    }
};
