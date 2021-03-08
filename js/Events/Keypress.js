export default function keypress(event) {
    console.log(event);
    event = event || window.event;
    var charCode = event.keyCode || event.which;
    var name = event.target.name;
    var tagName = '';
    var classList = '';
    var charStr = String.fromCharCode(charCode);
    let attributes = event.target.attributes;

    // console.log(attributes, Object.values(attributes));
    let isLivewire = Array.from(attributes).filter((attribute) => attribute.nodeName.includes('wire:')).length > 0;

    if (!event.target.labels) {
        return;
    }
    var label = event.target.labels[0].textContent;
    var text = (event.target.value + charStr).trim().replace("'", "\\'");
    var target = event.target.labels[0].textContent;
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
            tag: tagName.toLowerCase(),
            targetMeta: {
                isLivewire: isLivewire
            }
        });
    }
    sessionStorage.setItem("testingOutput", JSON.stringify(testingOutput));
}