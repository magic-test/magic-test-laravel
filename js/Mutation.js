export function initializeMutationObserver(){
    window.mutationObserver = new MutationObserver(function(mutations) {
      console.log("Mutation observed")
      if (!window.target) {
        console.log("There is no window.target element. Quitting the mutation callback function");
        return;
      }
      var options = "";
      var targetClass = window.target.classList[0] ? `.${window.target.classList[0]}` : ""
      var text = window.target.innerText ? `', text: '${window.target.innerText}` : ""
      var action = `${finderForElement(window.target)}.hover`;
      // var action = `find('${window.target.localName}${targetClass}${text}').hover`;
      var target = "";
      var testingOutput = JSON.parse(sessionStorage.getItem("testingOutput"));
      testingOutput.push({action: action, target: target, options: options});
      sessionStorage.setItem("testingOutput", JSON.stringify(testingOutput));
    });
    
    
    
  }

export function mutationStart(evt) {
    window.target = evt.target;
    const opts = {attributes: true, characterData: true, childList: true, subtree: true}
    window.mutationObserver.observe(document.documentElement, opts);
  }

export function mutationEnd () {
    window.mutationObserver.disconnect();
  }