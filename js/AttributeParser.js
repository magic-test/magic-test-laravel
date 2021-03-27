export default function AttributeParser(attributes, element = 'input') {
    if (element === 'form') {
        return;
    }

    let isUnique = (attribute) => {
        if (attribute.name == 'class') {
            return document.getElementsByClassName(attribute.value).length === 1;
        }

        let attributeName = attribute.name;

        if (attributeName.includes(':')) {
            let split = attribute.name.split(':');
            attributeName = `${split[0]}\\:${split[1]}`;
        }

        let selector = `${element}[${attributeName}=${attribute.value}]`;


        try {
            return document.querySelectorAll(selector).length === 1;
        } catch(e) {

        }
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