import ClickFunction from './Events/Click';
import KeypressFunction from './Events/Keypress';
import { enableKeyboardShortcuts } from './Context';
import { initializeMutationObserver, mutationStart, mutationEnd } from './Mutation';

function initializeStorage() {
    if (sessionStorage.getItem("testingOutput") == null) {
        MagicTest.clear();
    }
}

$(function () {
    console.log("Magic Test started");
    initializeStorage();
});


window.MagicTest = {
    running: false,

    start()
    {
        this.running = true;

        document.addEventListener("keypress", KeypressFunction);
        document.addEventListener('mouseover', mutationStart, true);
        document.addEventListener('mouseover', mutationEnd, false);   
        $(document).on("click", "*", ClickFunction);
        enableKeyboardShortcuts();
        initializeMutationObserver();
    },
    stop() {
        this.running = false;
    },

    getData() {
        return sessionStorage.getItem("testingOutput") || {};
    },
    formattedData()
    {
        return JSON.parse(this.getData());
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
