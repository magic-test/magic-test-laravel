export function keyDown(event){
    if (event.target.labels) {
      return;
    }
    event = event || window.event;
    var charCode = event.keyCode || event.which;
    var charStr = capybaraFromCharCode(charCode);
    var letter = event.key == "'" ? "\\\'" : event.key;
    var tagName = event.target.tagName.toLowerCase();
    var action = finderForElement(event.target) + "." // `find('${tagName}').`;
    var target = ""
    if (charStr) {
      target = `send_keys(${charStr})`;
    } else {
      target = `send_keys('${letter}')`;
    }
    var options = "";
    var testingOutput = JSON.parse(sessionStorage.getItem("testingOutput"));
    var lastAction = testingOutput[testingOutput.length - 1];
    // If the last key pressed was enter, always start a new action (otherwise with trix mentions, it happens too fast and we don't actually select the mentioned user correctly.)
    if (lastAction && lastAction.target.substr(lastAction.target.length - 7 , 6) == ":enter") {
      lastAction = null;
    }
    if (lastAction && lastAction.action == action && lastAction.target.substr(0,9) == 'send_keys') {
      if (charStr) {
        lastAction.target = lastAction.target.substr(0, lastAction.target.length - 1) + ', ' + charStr + ')'
      } else {
        if (lastAction.target.substr(lastAction.target.length - 2, 1) == "'") {
          lastAction.target = lastAction.target.substr(0, lastAction.target.length - 2) + letter + '\'' + ')'
        } else {
          lastAction.target = lastAction.target.substr(0, lastAction.target.length - 1) + ', \'' + letter + '\'' + ')'
        }
      }
    } else {
      testingOutput.push({action: action, target: target, options: options});
    }
    sessionStorage.setItem("testingOutput", JSON.stringify(testingOutput));
  }

export function keyUp(event){
    if (event.target.labels) {
      return;
    }

    event = event || window.event;
    var charCode = event.keyCode || event.which;
    var charStr = capybaraKeyUpFromCharCode(charCode);
    if (!charStr) {
      return;
    }
    var letter = String.fromCharCode(charCode);
    var tagName = event.target.tagName.toLowerCase();
    var action = `find('${tagName}').`;
    var target = ""
    if (charStr) {
      target = `send_keys(${charStr})`;
    } else {
      target = `send_keys('${letter}')`;
    }
    var options = "";
    var testingOutput = JSON.parse(sessionStorage.getItem("testingOutput"));
    var lastAction = testingOutput[testingOutput.length - 1];
    if (lastAction && lastAction.action == action && lastAction.target.substr(0,9) == 'send_keys') {
      if (charStr) {
        lastAction.target = lastAction.target.substr(0, lastAction.target.length - 1) + ', ' + charStr + '' + ')'
      } else {
        lastAction.target = lastAction.target.substr(0, lastAction.target.length - 1) + ', \'' + letter + '\'' + ')'
      }
    } else {
      testingOutput.push({action: action, target: target, options: options});
    }
    sessionStorage.setItem("testingOutput", JSON.stringify(testingOutput));
  }