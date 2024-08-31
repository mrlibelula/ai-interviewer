@props(['divId' => 'chat-01', 'avatar' => '🤖', 'user' => 'Chatbot', 'color' => 'sky', 'role' => '', 'content' => '', 'speed' => 50, 'animate' => true])
@php
    $tw_colors = [
        'sky' => 'text-sky-700 dark:text-sky-400',
        'yellow' => 'text-yellow-700 dark:text-yellow-400',
        'emerald' => 'text-emerald-700 dark:text-emerald-400',
        'rose' => 'text-rose-700 dark:text-rose-400',
        'orange' => 'text-orange-700 dark:text-orange-400',
        'fuchsia' => 'text-fuchsia-700 dark:text-fuchsia-400',
        'violet' => 'text-violet-700 dark:text-violet-400',
        'purple' => 'text-purple-700 dark:text-violet-400',
        'pink' => 'text-pink-700 dark:text-violet-400',
        'green' => 'text-green-700 dark:text-green-400',
    ];
    $tw_color = $tw_colors[$color] ?? $tw_colors['green'];
@endphp
<div class="flex flex-col gap-y-1">
    <span class="{{ $tw_color }} font-semibold">{{ $avatar }} {{ $user }}:</span>
    <div class="tracking-wide leading-normal " id="{{ $divId }}" 
        @if ($animate)
        x-init="slowTextDisplay('{{ \App\Tool::prepareAiAnswerString($content) }}', {{ (int)$speed }}, '{{ $divId }}')"
        @else
        x-init="textDisplay('{{ \App\Tool::prepareAiAnswerString($content) }}', '{{ $divId }}')"
        @endif
    ></div>

    <script>
        var intervalId;
    
        function textDisplay(text, divId) {
            const chatElement = document.getElementById(divId);
            const lines = text.split(/\n/); // Split text by line breaks
    
            lines.forEach((line, lineIndex) => {
                if (lineIndex > 0) {
                    chatElement.appendChild(document.createElement("br"));
                }
    
                const parts = line.split(/(\s+)/); // Split line into words and spaces
                parts.forEach(part => {
                    const splitPart = part.split('??');
                    if (splitPart.length > 1) {
                        chatElement.appendChild(document.createTextNode(splitPart[0]));
                        chatElement.appendChild(document.createElement("br"));
                        chatElement.appendChild(document.createElement("br"));
                        let key = 1;
                        while (key < splitPart.length && splitPart[key] === '') key++;
                        if (key < splitPart.length) {
                            chatElement.appendChild(document.createTextNode(capitalizeFirstLetter(splitPart[key])));
                        }
                    } else {
                        chatElement.appendChild(document.createTextNode(decodeHTML(part)));
                    }
                });
            });
    
            return text;
        }
    
        function slowTextDisplay(text, delay = 100, elementId = 'chat--1') {
            const chatElement = document.getElementById(elementId);
            const lines = text.split(/\n/); // Split text by line breaks
            let lineIndex = 0;
            let wordIndex = 0;
            let words = lines[0].split(/(\s+)/);
    
            intervalId = setInterval(function() {
                if (wordIndex < words.length) {
                    const part = words[wordIndex];
                    const splitPart = part.split('??');
                    if (splitPart.length > 1) {
                        chatElement.appendChild(document.createTextNode(splitPart[0]));
                        chatElement.appendChild(document.createElement("br"));
                        chatElement.appendChild(document.createElement("br"));
                        let key = 1;
                        while (key < splitPart.length && splitPart[key] === '') key++;
                        if (key < splitPart.length) {
                            chatElement.appendChild(document.createTextNode(capitalizeFirstLetter(splitPart[key])));
                        }
                    } else {
                        chatElement.appendChild(document.createTextNode(decodeHTML(part)));
                    }
                    wordIndex++;
                } else if (lineIndex < lines.length - 1) {
                    chatElement.appendChild(document.createElement("br"));
                    lineIndex++;
                    words = lines[lineIndex].split(/(\s+)/);
                    wordIndex = 0;
                } else {
                    clearInterval(intervalId);
                }
            }, delay);
    
            return text;
        }
    
        function capitalizeFirstLetter(str) {
            return str.charAt(0).toUpperCase() + str.slice(1);
        }
    
        function decodeHTML(html) {
            var txt = document.createElement("textarea");
            txt.innerHTML = html;
            return txt.value;
        }
    </script>
    
    
</div>