import AttributeParser from './../AttributeParser';
import { isSameArray } from './../Helpers';

export default function keypress(event) {
    event = event || window.event;
    console.log(event);
    let tagName = event.target.tagName.toLowerCase();
    let charCode = event.keyCode || event.which;
    let charStr = String.fromCharCode(charCode);
    let attributes = event.target.attributes;


    // let isLivewire = Array.from(attributes).filter((attribute) => attribute.nodeName.includes('wire:')).length > 0;


    const parsedAttributes = AttributeParser(attributes);

    const parent = {
        tag: event.target.parent?.tagName.toLowerCase() || null
    };

    let text = (event.target.value + charStr).trim().replace("'", "\\'");    

    let finalObject = {
        action: 'fill',
        attributes: parsedAttributes,
        parent: parent,
        tag: tagName,
        meta: {
            text: text
        }
    };

    const isSame = (firstObject, secondObject) => {
        return firstObject.tag == secondObject.tag && isSameArray(firstObject.attributes, secondObject.attributes);
    };

    var testingOutput = JSON.parse(sessionStorage.getItem("testingOutput"));
    var lastAction = testingOutput[testingOutput.length - 1];

    if (lastAction && isSame(lastAction, finalObject)) {
        lastAction.meta = finalObject.meta;
    } else {
        testingOutput.push(finalObject);
    }

    sessionStorage.setItem("testingOutput", JSON.stringify(testingOutput));
}