<!-- Init Trumbowyg -->
<script>
        $('#content').trumbowyg({
            lang: 'fr',
            btnsDef: {
            // Create a new dropdown
            image: {
                dropdown: ['insertImage'],
                ico: 'insertImage'
            }
        },
        // Redefine the button panel
        btns: [
            ['viewHTML'],
            ['formatting'],
            ['strong', 'em', 'del'],
            ['superscript', 'subscript'],
            ['link'],
            ['image'],
            ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
            ['unorderedList', 'orderedList'],
            ['horizontalRule'],
            ['removeformat'],
            ['fullscreen']
        ],
        plugins: {
            // 
            upload: {
                
            },
        }});
    </script>
    <!-- Textarea article Trumbowyg -->
    <script>
        $('#content-article').trumbowyg({
        btns: [
        ],
        //autogrow: true // To have the textarea as long as the text
        });
    </script>   
    <!-- Bootstrap -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/js/bootstrap.bundle.min.js" integrity="sha512-i9cEfJwUwViEPFKdC1enz4ZRGBj8YQo6QByFTF92YXHi7waCqyexvRD75S5NVTsSiTv7rKWqG9Y5eFxmRsOn0A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- Custom JS -->
    <script src="/js/script.js"></script>
</body>
</html>