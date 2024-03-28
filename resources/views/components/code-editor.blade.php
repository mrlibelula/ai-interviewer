<div class=" rounded-xl overflow-hidden">
    <iframe :dark-mode="darkMode" 
        class=" h-[80vh] -mt-12" 
        src="{{ route('embed-editor') }}" 
        width="100%" 
        frameborder="0" 
        allowfullscreen
        id="codeIframe"
    ></iframe>
    {{-- <script>
        const iframe = document.getElementById('codeIframe');
        iframe.contentWindow.postMessage(JSON.stringify(darkMode), '*');
    </script> --}}
</div>