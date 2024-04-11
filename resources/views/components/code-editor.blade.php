<div class="flex flex-col gap-y-2 items-end">
    
    <iframe :dark-mode="darkMode" 
        class=" h-[50vh] border bg-white dark:bg-black border-gray-300 dark:border-gray-800 rounded-lg overflow-hidden shadow " 
        src="{{ route('embed-editor') }}" 
        width="100%" 
        frameborder="0" 
        allowfullscreen
        id="codeIframe"
    ></iframe>

    <div id="fullscreenIcon" class="p-1 cursor-pointer group w-fit flex items-center gap-x-2">
        <span class="text-base group-hover:dark:text-emerald-400 smooth-300">Fullscreen</span>
        <x-icon-fullscreen class="w-6 h-6 dark:text-gray-500 group-hover:dark:text-gray-200 smooth-300" />
    </div>

    <script>
        var iframe = document.getElementById('codeIframe');
        var fullscreenIcon = document.getElementById('fullscreenIcon');

        // Function to make the iframe fullscreen
        function toggleFullScreen() {
            if (!document.fullscreenElement) {
                iframe.requestFullscreen().catch(err => {
                    console.log(`Error attempting to enable full-screen mode: ${err.message} (${err.name})`);
                });
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                }
            }
        }

        function sendEventToIframe(message = { getCode: false, runCode: false }) {
            iframe.contentWindow.postMessage(message, '*')
        }

        // Add click event listener to the iframe
        fullscreenIcon.addEventListener('click', toggleFullScreen);

        document.addEventListener('analyze-code', () => {
            sendEventToIframe({ getCode: true, runCode: false });
        })

        document.addEventListener('run-code', () => {
            sendEventToIframe({ getCode: false, runCode: true });
        })

        // listen for messages from the iframe
        window.addEventListener('message', event => {
            if (event.source === iframe.contentWindow) {
                // Access the user-code sent from the iframe
                var codeFromIframe = event.data.code;
                sendCode(codeFromIframe)
            }
        })

        function sendCode(code) {
            // send code to backend for revision
            const event = new CustomEvent('userCode', { detail: { code } })
            window.dispatchEvent(event)
        }
        
    </script>
</div>