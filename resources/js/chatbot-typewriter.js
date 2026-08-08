/**
 * Chatbot message typewriter helpers (global once — never inline in Livewire morph HTML).
 */
function capitalizeFirstLetter(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
}

function decodeHTML(html) {
    const txt = document.createElement('textarea');
    txt.innerHTML = html;
    return txt.value;
}

function appendChatPart(chatElement, part) {
    const splitPart = part.split('??');
    if (splitPart.length > 1) {
        chatElement.appendChild(document.createTextNode(splitPart[0]));
        chatElement.appendChild(document.createElement('br'));
        chatElement.appendChild(document.createElement('br'));
        let key = 1;
        while (key < splitPart.length && splitPart[key] === '') key++;
        if (key < splitPart.length) {
            chatElement.appendChild(document.createTextNode(capitalizeFirstLetter(splitPart[key])));
        }
        return;
    }
    chatElement.appendChild(document.createTextNode(decodeHTML(part)));
}

window.textDisplay = function textDisplay(text, divId) {
    const chatElement = document.getElementById(divId);
    if (!chatElement) return text;

    const lines = String(text ?? '').split(/\n/);
    lines.forEach((line, lineIndex) => {
        if (lineIndex > 0) chatElement.appendChild(document.createElement('br'));
        line.split(/(\s+)/).forEach((part) => appendChatPart(chatElement, part));
    });
    return text;
};

window.slowTextDisplay = function slowTextDisplay(text, delay = 100, elementId = 'chat--1') {
    const chatElement = document.getElementById(elementId);
    if (!chatElement) return text;

    const lines = String(text ?? '').split(/\n/);
    let lineIndex = 0;
    let wordIndex = 0;
    let words = (lines[0] ?? '').split(/(\s+)/);

    const intervalId = setInterval(() => {
        if (wordIndex < words.length) {
            appendChatPart(chatElement, words[wordIndex]);
            wordIndex++;
            return;
        }
        if (lineIndex < lines.length - 1) {
            chatElement.appendChild(document.createElement('br'));
            lineIndex++;
            words = lines[lineIndex].split(/(\s+)/);
            wordIndex = 0;
            return;
        }
        clearInterval(intervalId);
    }, delay);

    return text;
};
