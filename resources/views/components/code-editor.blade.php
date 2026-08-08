@props(['solverCode' => false])
<div class="code-editor-root flex flex-col min-h-0 w-full">

    <x-editor-nav class="code-editor-toolbar code-editor-toolbar-top shrink-0 border-b border-gray-300 dark:border-gray-600/80 px-2" />

    <iframe :dark-mode="darkMode" :solver-code="'{{ \App\Tool::encode($solverCode) }}'"
        class="code-editor-iframe bg-white dark:bg-black/80 border-gray-300 dark:border-gray-800 overflow-hidden overflow-y-hidden w-full min-h-0"
        src="{{ route('embed-editor', ['v' => 'fill-100']) }}"
        width="100%"
        frameborder="0"
        allowfullscreen
        id="codeIframe"
        scrolling="no"
    ></iframe>

    <x-editor-nav class="code-editor-toolbar code-editor-toolbar-bottom shrink-0 border-t border-gray-300 dark:border-gray-600/80 px-2" />

    <div class="code-editor-terminal relative shrink-0 overflow-hidden border-t border-gray-300 dark:border-gray-700/70 bg-black/90 dark:bg-black/70">
        <div class="absolute z-10 left-0 top-0 px-3 py-1.5 text-gray-500 dark:text-gray-500 font-mono text-xs tracking-wider uppercase pointer-events-none">
            Output
        </div>
        <iframe id="output-frame" class="absolute inset-0 w-full h-full bg-black" frameborder="0" width="100%" height="100%"></iframe>
    </div>

    <script>
        var iframe = document.getElementById('codeIframe')
        var fullscreenIcons = document.querySelectorAll('.fullscreen-icon')
        var codeFromIframe

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

        function sendEventToIframe(message = { getCode: false, runCode: false, saveCode: false, complexity: false, analyze: false }) {
            iframe.contentWindow.postMessage(message, '*')
        }

        fullscreenIcons.forEach(function (el) {
            el.addEventListener('click', toggleFullScreen)
        })

        document.addEventListener('analyze-code', () => {
            sendEventToIframe({ getCode: true, runCode: false, saveCode: false, complexity: false, analyze: false })
        })

        document.addEventListener('run-code', () => {
            sendEventToIframe({ getCode: false, runCode: true, saveCode: false, complexity: false, analyze: false })
        })

        document.addEventListener('run-and-analyze', () => {
            sendEventToIframe({ getCode: false, runCode: true, saveCode: false, complexity: false, analyze: true })
        })

        document.addEventListener('complexity', () => {
            sendEventToIframe({ getCode: true, runCode: false, saveCode: true, complexity: true, analyze: false })
        })

        document.addEventListener('get-code', () => {
            sendEventToIframe({ getCode: true, runCode: false, saveCode: true, complexity: false, analyze: false })
        })

        document.addEventListener('save-code', () => {
            sendEventToIframe({ getCode: false, runCode: false, saveCode: true, complexity: false, analyze: false })
        })

        window.addEventListener('message', event => {
            if (event.source === iframe.contentWindow) {
                codeFromIframe = event.data.code
                if (event.data.hasOwnProperty('runCode') && event.data.runCode) {
                    runJsCode(codeFromIframe)
                    if (event.data.analyze) {
                        sendCode(codeFromIframe, 'userCode')
                    }
                    return
                }
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

            frameWindow.console.log = function () {
                var parts = Array.prototype.map.call(arguments, formatLogArg)
                outputDiv.textContent += parts.join(' ') + '\n'
            }

            try {
                ;(new frameWindow.Function(code))()
            } catch (error) {
                outputDiv.textContent = "Error: " + error.message
            }
        }

    </script>
</div>
