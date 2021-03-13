export default function AttributeParser(attributes, element = 'input') {
    if (element === 'form') {
        return;
    }

    let isUnique = (attribute) => {
        if (attribute.name == 'class') {
            return document.getElementsByClassName(attribute.value).length === 1;
        }

        let selector = `${element}[${attribute.name}=${attribute.value}]`;

        
        return document.querySelectorAll(selector).length === 1;
    };

    const parsedAttributes = Array.from(attributes).map(function(attribute) {
        return {
            name: attribute.name,
            value: attribute.value,
            isUnique: isUnique(attribute)
        }
    });

    return parsedAttributes;
}