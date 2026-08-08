@props(['solverCode' => false])
<div class="flex flex-col">

    <x-editor-nav class=" border-b border-gray-300 dark:border-gray-600" />

    <iframe :dark-mode="darkMode" :solver-code="'{{ \App\Tool::encode($solverCode) }}'"
        class=" h-[33vh] bg-white dark:bg-black border-gray-300 dark:border-gray-800 overflow-hidden overflow-y-hidden" 
        src="{{ route('embed-editor') }}" 
        width="100%" 
        frameborder="0" 
        allowfullscreen
        id="codeIframe"
        scrolling="no"
    ></iframe>

    <x-editor-nav class=" border-t border-gray-300 dark:border-gray-600" />

    <div class="relative">
        <iframe id="output-frame" class=" absolute bg-black rounded-md" frameborder="0" width="100%"></iframe>
        <div class=" absolute px-4 py-2 text-gray-500 font-mono text-base">
            Output terminal
        </div>
    </div>

    <script>
        var iframe = document.getElementById('codeIframe')
        var fullscreenIcon = document.getElementById('fullscreenIcon')
        var codeFromIframe

        // Function to make the iframe fullscreen
        function toggleFullScreen() {
            if (!document.fullscreenElement) {
                iframe.requestFullscreen().catch(err => {
                    console.log(`Error attempting to enable full-screen mode: ${err.message} (${err.name})`)
                });
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen()
                }
            }
        }

        function sendEventToIframe(message = { getCode: false, runCode: false, saveCode: false, complexity: false }) {
            iframe.contentWindow.postMessage(message, '*')
        }

        // Add click event listener to the iframe
        fullscreenIcon.addEventListener('click', toggleFullScreen)

        document.addEventListener('analyze-code', () => {
            sendEventToIframe({ getCode: true, runCode: false, saveCode: false, complexity: false })
        })

        document.addEventListener('run-code', () => {
            sendEventToIframe({ getCode: false, runCode: true, saveCode: false, complexity: false })
        })

        document.addEventListener('complexity', () => {
            sendEventToIframe({ getCode: true, runCode: false, saveCode: true, complexity: true })
        })

        document.addEventListener('get-code', () => {
            sendEventToIframe({ getCode: true, runCode: false, saveCode: true, complexity: false })
        })

        document.addEventListener('save-code', () => {
            sendEventToIframe({ getCode: false, runCode: false, saveCode: true, complexity: false })
        })

        // listen for messages from the sandboxed iframe
        window.addEventListener('message', event => {
            if (event.source === iframe.contentWindow) {
                codeFromIframe = event.data.code
                if (event.data.hasOwnProperty('runCode')) { if (event.data.runCode) runJsCode(codeFromIframe) }
                var saveCodeFromIframe = event.data.saveCode ?? null
                if (event.data.hasOwnProperty('complexity')) {
                    sendCodeForComplexity(codeFromIframe)
                    return
                }
                saveCodeFromIframe
                    ? sendCode(codeFromIframe, 'saveUserCode')
                    : sendCode(codeFromIframe, 'userCode')
            }
        })

        function sendCode(code, eventName) {
            // send user code to backend
            const event = new CustomEvent(eventName, { detail: { code } })
            window.dispatchEvent(event)
        }

        function sendCodeForComplexity(code, eventName = 'complexityCode') {
            const event = new CustomEvent(eventName, { detail: { code } })
            window.dispatchEvent(event)
        }

        function runJsCode(code) {
            var outputFrame = document.getElementById('output-frame')
            outputFrame.contentDocument.open()
            outputFrame.contentDocument.write(`<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sandboxed Script</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.3/dist/tailwind.min.css">
</head>
<body>
    <div class=" w-full h-full py-9 px-4 bg-black rounded-md text-gray-300 font-mono">
        <div id="output"></div>
    </div>
</body>
</html>`)
            outputFrame.contentDocument.close()

            var frameWindow = outputFrame.contentWindow
            var outputDiv = outputFrame.contentDocument.getElementById('output')

            function formatLogArg(arg) {
                if (typeof arg === 'string') return arg
                if (typeof arg === 'undefined') return 'undefined'
                if (typeof arg === 'object' && arg !== null) {
                    try { return JSON.stringify(arg) } catch (e) { return String(arg) }
                }
                return String(arg)
            }

            // Capture console output inside the sandbox iframe only (do not print eval return values —
            // appending those is what produced a trailing "undefined", especially when trailing
            // // comments swallowed an injected "return ''").
            frameWindow.console.log = function () {
                var parts = Array.prototype.map.call(arguments, formatLogArg)
                outputDiv.textContent += parts.join(' ') + '\n'
            }

            try {
                // Run in the iframe scope; ignore the return value so "undefined" is never echoed.
                ;(new frameWindow.Function(code))()
            } catch (error) {
                outputDiv.textContent = "Error: " + error.message
            }
        }
        
    </script>
</div>