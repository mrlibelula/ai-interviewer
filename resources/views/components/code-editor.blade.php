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
    ></iframe>

    <x-editor-nav class=" border-t border-gray-300 dark:border-gray-600" />

    <script>
        var iframe = document.getElementById('codeIframe')
        var fullscreenIcon = document.getElementById('fullscreenIcon')

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

        function sendEventToIframe(message = { getCode: false, runCode: false, saveCode: false }) {
            iframe.contentWindow.postMessage(message, '*')
        }

        // Add click event listener to the iframe
        fullscreenIcon.addEventListener('click', toggleFullScreen)

        document.addEventListener('analyze-code', () => {
            sendEventToIframe({ getCode: true, runCode: false, saveCode: false })
        })

        document.addEventListener('run-code', () => {
            sendEventToIframe({ getCode: false, runCode: true, saveCode: false })
        })

        document.addEventListener('get-code', () => {
            sendEventToIframe({ getCode: true, runCode: false, saveCode: true })
        })

        // listen for messages from the sandboxed iframe
        window.addEventListener('message', event => {
            if (event.source === iframe.contentWindow) {
                var codeFromIframe = event.data.code
                var saveCodeFromIframe = event.data.saveCode
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
        
    </script>
</div>