<script src="{{ asset('vendor/laravel-filemanager/js/stand-alone-button.js') }}"></script>
<script>
    $(document).ready(function() {
        var route_prefix = "{{ route('unisharp.lfm.show') }}";

        function lfm(id, previewId, type, input) {
            $('#' + id).filemanager(type, {
                prefix: route_prefix
            });
            $('#' + input).on('change', function() {
                var urls = $('#' + input).val();
                var previewElements = '';

                if (urls) {
                    urls = urls.split(',').map(url => url.trim());

                    urls.forEach(function(url) {
                        if (url) {
                            var extension = url.split('.').pop().toLowerCase();
                            var previewElement;
                            if (['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'].includes(
                                    extension)) {
                                previewElement =
                                    '<div class="preview-image col-12 col-md-6" id="preview-image-' +
                                    id + '"><img loading="lazy" src="' +
                                    url + '" alt=""></div>';
                            } else if (['mp4', 'mkv', 'webm', 'ogg', 'avi'].includes(
                                    extension)) {
                                previewElement =
                                    '<div class="preview-video col-12 col-md-6"><video width="100%" height="240" controls><source src="' +
                                    url + '" type="video/' + extension +
                                    '">Your browser does not support the video tag.</video></div>';
                            } else {
                                previewElement = 'File type not supported for preview.';
                            }
                            previewElements += previewElement;
                        }
                    });
                } else {
                    previewElements = 'No files selected.';
                }

                $('#' + previewId).empty();
                $('#' + previewId).append(previewElements);
            });
        }

        // Function to store initial values and preview images
        function storeInitialValuesAndPreviews() {
            $('[id^=thumbnail]').each(function() {
                $(this).data('initial-value', $(this).val());
            });

            $('[id^=holder]').each(function() {
                $(this).data('initial-preview', $(this).html());
            });
        }

        // Call the storeInitialValuesAndPreviews function to store initial values and previews
        storeInitialValuesAndPreviews();

        // Loop through elements that match the pattern
        $('[id^=lfm]').each(function(index) {
            var lfmButtonId = $(this).attr('id');
            var holderId = 'holder' + (index + 1);
            var thumbnailId = 'thumbnail' + (index + 1);

            lfm(lfmButtonId, holderId, 'file', thumbnailId);
        });

        $('button[type="reset"]').click(function(event) {

            // Reset all thumbnail inputs and holders to their initial values and previews
            $('[id^=thumbnail]').each(function() {
                var initialValue = $(this).data('initial-value');
                $(this).val(initialValue); // Set to initial value
            });

            $('[id^=holder]').each(function() {
                var initialPreview = $(this).data('initial-preview');
                $(this).html(initialPreview); // Set to initial preview
            });
        });

        lfm('lfm1', 'holder1', 'file', 'thumbnail1');
        lfm('lfm2', 'holder2', 'file', 'thumbnail2');
        lfm('lfm3', 'holder3', 'file', 'thumbnail3');
        lfm('lfm4', 'holder4', 'file', 'thumbnail4');
        lfm('lfm5', 'holder5', 'file', 'thumbnail5');
        lfm('lfm6', 'holder6', 'file', 'thumbnail6');
    });
</script>
