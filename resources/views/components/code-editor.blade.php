@props(['solverCode' => false])
<div class="code-editor-root flex flex-col min-h-0 w-full">

    <x-editor-nav class="code-editor-toolbar code-editor-toolbar-top shrink-0 border-b border-gray-300 dark:border-gray-600/80 px-2" />

    <iframe :dark-mode="darkMode" :solver-code="'{{ \App\Tool::encode($solverCode) }}'"
        class="code-editor-iframe bg-white dark:bg-black/80 border-gray-300 dark:border-gray-800 overflow-hidden overflow-y-hidden w-full min-h-0"
        src="{{ route('embed-editor', ['v' => 'theme-sync-1']) }}"
        width="100%"
        frameborder="0"
        allowfullscreen
        id="codeIframe"
        scrolling="no"
    ></iframe>

    <x-editor-nav class="code-editor-toolbar code-editor-toolbar-bottom shrink-0 border-t border-gray-300 dark:border-gray-600/80 px-2" />

    <div class="code-editor-terminal relative shrink-0 overflow-hidden border-t border-gray-300 dark:border-gray-700/70 bg-gray-200 dark:bg-black/70">
        <div class="absolute z-10 left-0 top-0 px-3 py-1.5 text-gray-600 dark:text-gray-500 font-mono text-xs tracking-wider uppercase pointer-events-none">
            Output
        </div>
        <iframe id="output-frame" class="absolute inset-0 w-full h-full bg-gray-200 dark:bg-black" frameborder="0" width="100%" height="100%"></iframe>
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

        function syncEditorTheme() {
            const isDark = document.documentElement.classList.contains('dark')
                || localStorage.getItem('dark') === 'true'
            if (iframe && iframe.contentWindow) {
                iframe.contentWindow.postMessage({ type: 'theme', darkMode: isDark }, '*')
            }
            restyleOutputFrame(isDark)
        }

        /** Monaco / challenge / chat scrollbar thumbs — must live inside the iframe */
        function outputScrollbarCss(isDark) {
            var thumb = isDark ? '#313d47' : '#c1c1c0'
            var thumbHover = isDark ? '#3d4d59' : '#a8a8a7'
            var scheme = isDark ? 'dark' : 'light'
            var bodyBg = isDark ? '#000' : '#e5e7eb'
            return `
html, body {
    margin: 0;
    height: 100%;
    background: ${bodyBg};
    color-scheme: ${scheme};
    overflow: hidden;
}
#output-shell {
    height: 100%;
    overflow-y: auto;
    overflow-x: hidden;
    box-sizing: border-box;
    scrollbar-width: thin;
    scrollbar-color: ${thumb} transparent;
}
#output-shell::-webkit-scrollbar { width: 12px; height: 12px; }
#output-shell::-webkit-scrollbar-track { background: transparent; }
#output-shell::-webkit-scrollbar-thumb {
    background-color: ${thumb};
    border-radius: 6px;
    border: 3px solid transparent;
    background-clip: padding-box;
}
#output-shell::-webkit-scrollbar-thumb:hover { background-color: ${thumbHover}; }
`
        }

        function restyleOutputFrame(isDark) {
            var outputFrame = document.getElementById('output-frame')
            if (!outputFrame) return
            try {
                var doc = outputFrame.contentDocument
                if (!doc || !doc.body) return
                var shell = doc.getElementById('output-shell')
                if (!shell) return
                shell.className = isDark
                    ? 'w-full h-full py-9 px-4 bg-black text-gray-300 font-mono'
                    : 'w-full h-full py-9 px-4 bg-gray-200 text-gray-800 font-mono'
                doc.body.style.background = isDark ? '#000' : '#e5e7eb'
                var styleEl = doc.getElementById('output-scroll-theme')
                if (styleEl) {
                    styleEl.textContent = outputScrollbarCss(isDark)
                }
                doc.documentElement.style.colorScheme = isDark ? 'dark' : 'light'
            } catch (e) {}
        }

        window.addEventListener('theme-changed', () => {
            requestAnimationFrame(syncEditorTheme)
        })

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
            var isDark = document.documentElement.classList.contains('dark')
                || localStorage.getItem('dark') === 'true'
            var shellClass = isDark
                ? 'w-full h-full py-9 px-4 bg-black text-gray-300 font-mono'
                : 'w-full h-full py-9 px-4 bg-gray-200 text-gray-800 font-mono'

            var outputFrame = document.getElementById('output-frame')
            outputFrame.contentDocument.open()
            outputFrame.contentDocument.write(`<!DOCTYPE html>
<html lang="en" style="color-scheme: ${isDark ? 'dark' : 'light'}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sandboxed Script</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.3/dist/tailwind.min.css">
    <style id="output-scroll-theme">${outputScrollbarCss(isDark)}</style>
</head>
<body>
    <div id="output-shell" class="${shellClass}">
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
