export function getPathTo(element) {
    if (element.tagName == 'HTML') {
        return '/HTML[1]';
    }
    if (element === document.body) {
        return '/HTML[1]/BODY[1]';
    }
    var ix = 0;
    var siblings = element.parentNode.childNodes;
    for (var i = 0; i < siblings.length; i++) {
        var sibling = siblings[i];
        if (sibling === element) {
            return getPathTo(element.parentNode) + '/' + element.tagName + '[' + (ix + 1) + ']';
        }
        if (sibling.nodeType === 1 && sibling.tagName === element.tagName) {
            ix++;
        }
    }
}

// Chrome doesn't respond to the jQuery :visible selector properly so we have to do this:
export function visibleFilter() {
    return $(this).css('display') != 'none' && $(this).css('visibility') != 'hidden';
}


export function finderForElement(element) {
    // Try to find just using the element tagName
    var tagName = element.tagName.toLowerCase();
    if ($(tagName).length == 1) {
        return `find('${tagName}')`;
    }
    // Try adding in the classes of the element
    var classList = [].slice.apply(element.classList)
    var classString = classList.length ? "." + classList.join('.') : "";
    if (classList.length && $(tagName + classList).length == 1) {
        return `find('${tagName}${classString}')`;
    }
    // Try adding in the text of the element
    var text = element.textContent.trim();
    if (text && $(tagName + classString + `:contains(${text}):visible`).filter(visibleFilter).length == 1) {
        return `find('${tagName}${classString}', text: '${text}')`;
    }
    // use the xpath to the element
    return `find(:xpath, '${getPathTo(element)}')`;
}