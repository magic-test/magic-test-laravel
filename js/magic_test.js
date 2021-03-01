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

  function keypressFunction(evt) {
      evt = evt || window.event;
      var charCode = evt.keyCode || evt.which;
      var name = evt.target.name;
      var tagName = '';
      var classList = '';
      var charStr = String.fromCharCode(charCode);
      if (!evt.target.labels) {
          return;
      }
      var label = evt.target.labels[0].textContent;
      var text = (evt.target.value + charStr).trim().replace("'", "\\'");
      var target = evt.target.labels[0].textContent;
      var options = {"text": `'${text}'` };
      var action = 'fill';
      var testingOutput = JSON.parse(sessionStorage.getItem("testingOutput"));
      var lastAction = testingOutput[testingOutput.length - 1];
      if (
          lastAction &&
          lastAction.action == action &&
          lastAction.target == "'" + name + "'"
      ) {
          lastAction.options = options;
      } else {
          testingOutput.push({
              action: action,
              path: '',
              target: `'${name}'`,
              options: options,
              classList: classList,
              tag: tagName.toLowerCase()
          });
      }
      sessionStorage.setItem("testingOutput", JSON.stringify(testingOutput));
  }



  document.addEventListener("keypress", function (e) {
      keypressFunction(e);
  });

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
