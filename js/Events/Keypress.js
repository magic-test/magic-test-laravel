export default function keypress(event) {
    event = event || window.event;
    console.log(event);
    let tagName = event.target.tagName.toLowerCase();
    let charCode = event.keyCode || event.which;
    let charStr = String.fromCharCode(charCode);
    let attributes = event.target.attributes;


    // let isLivewire = Array.from(attributes).filter((attribute) => attribute.nodeName.includes('wire:')).length > 0;


    let isUnique = (attribute) => {
        if (attribute.name == 'class') {
            return document.getElementsByClassName(attribute.value).length === 1;
        }

        let selector = `input[${attribute.name}=${attribute.value}]`;
        
        return document.querySelectorAll(selector).length === 1;
    };

    const parsedAttributes = Array.from(attributes).map(function(attribute) {
        return {
            name: attribute.name,
            value: attribute.value,
            isUnique: isUnique(attribute)
        }
    });

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
        return firstObject.tag == secondObject.tag &&
            JSON.stringify(firstObject.attributes) === JSON.stringify(secondObject.attributes);
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