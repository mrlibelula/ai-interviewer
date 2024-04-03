<div class=" rounded-xl overflow-hidden">
    <iframe :dark-mode="darkMode" 
        class=" h-[40vh] -mt-12" 
        src="{{ route('embed-editor') }}" 
        width="100%" 
        frameborder="0" 
        allowfullscreen
        id="codeIframe"
    ></iframe>
</div>