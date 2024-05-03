@props(['divId' => 'chat-01', 'avatar' => '🤖', 'user' => 'Chatbot', 'color' => 'sky', 'role' => '', 'content' => '', 'speed' => 50, 'animate' => true])
@php
    $tw_colors = [
        'sky' => 'text-sky-700 dark:text-sky-400',
        'rose' => 'text-rose-700 dark:text-rose-400',
        'orange' => 'text-orange-700 dark:text-orange-400',
        'fuchsia' => 'text-fuchsia-700 dark:text-fuchsia-400',
        'green' => 'text-green-700 dark:text-green-400',
    ];
    $tw_color = $tw_colors[$color] ?? $tw_colors['green'];
@endphp
<div class="flex flex-col gap-y-1">
    <span class="{{ $tw_color }} font-semibold">{{ $avatar }} {{ $user }}:</span>
    <div class=" font-mono" id="{{ $divId }}" 
        @if ($animate)
        x-init="slowTextDisplay('{{ \App\Tool::prepareAiAnswerString($content) }}', {{ (int)$speed }}, '{{ $divId }}')"
        @else
        x-init="textDisplay('{{ \App\Tool::prepareAiAnswerString($content) }}', '{{ $divId }}')"
        @endif
    ></div>

    <script>
        var intervalId
        
        function textDisplay(text, divId) {
            const originalText = text
            const parts = text.split(/(\s+)/)
            let index = 0

            parts.forEach(part => {
                if (index < parts.length) {
                    // var part = parts[index]
                    const chatElement = document.getElementById(divId)
                    part = decodeHTML(part)
                    var splits = part.split('??')
                    if (splits.length > 1) {
                        chatElement.appendChild(document.createTextNode(splits[0]))
                        chatElement.appendChild(document.createElement("br"))
                        chatElement.appendChild(document.createElement("br"))
                        let key = 1
                        if (splits[key] === '') key++
                        if (splits[key] === '') key++
                        if (splits[key] === '') key++
                        if (splits[key] === '') key++
                        chatElement.appendChild(document.createTextNode(capitalizeFirstLetter(splits[key])))
                    } else {
                        chatElement.appendChild(document.createTextNode(splits[0]))
                    }
                    index++
                }
            })

            return originalText
        }

        function slowTextDisplay(text, delay = 100, elementId = 'chat--1') {
            const originalText = text
            const parts = text.split(/(\s+)/)
            let index = 0

            intervalId = setInterval(function() {
                if (index < parts.length) {
                    var part = parts[index]
                    const chatElement = document.getElementById(elementId)
                    part = decodeHTML(part)
                    var splits = part.split('??')
                    if (splits.length > 1) {
                        //console.log('splits', splits)
                        chatElement.appendChild(document.createTextNode(splits[0]))
                        chatElement.appendChild(document.createElement("br"))
                        chatElement.appendChild(document.createElement("br"))
                        let key = 1
                        if (splits[key] === '') key++
                        if (splits[key] === '') key++
                        if (splits[key] === '') key++
                        if (splits[key] === '') key++
                        chatElement.appendChild(document.createTextNode(capitalizeFirstLetter(splits[key])))
                    } else {
                        chatElement.appendChild(document.createTextNode(splits[0]))
                    }
                    index++
                } else {
                    clearInterval(intervalId) // Clear the interval once all parts are displayed
                }
            }, delay)

            return originalText
        }

        function capitalizeFirstLetter(str) {
            return str.charAt(0).toUpperCase() + str.slice(1)
        }

        function decodeHTML(html) {
            var txt = document.createElement("textarea")
            txt.innerHTML = html
            return txt.value
        }
        
    </script>
</div>