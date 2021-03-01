import ClickFunction from './Events/Click';
import KeypressFunction from './Events/Keypress';
import { enableKeyboardShortcuts } from './Context';

function initializeStorage() {
    if (sessionStorage.getItem("testingOutput") == null) {
        MagicTest.clear();
    }
}

$(function () {
    console.log("Magic Test started");
    initializeStorage();
});


document.addEventListener("keypress", KeypressFunction);

$(document).on("click", "*", ClickFunction);

$(document).ready(function() {
    enableKeyboardShortcuts();
  });

window.MagicTest = {
    getData() {
        return sessionStorage.getItem("testingOutput") || {};
    },
    addData(data) {
      let testingOutput = JSON.parse(sessionStorage.getItem("testingOutput"));

      testingOutput.push(data);
      
      sessionStorage.setItem("testingOutput", JSON.stringify(testingOutput));
    },
    clear() {
        sessionStorage.setItem("testingOutput", JSON.stringify([]));
    }
};
