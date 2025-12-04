
    @push('scripts')
        
        <script src="https://cdn.ckeditor.com/ckeditor5/47.3.0/ckeditor5.umd.js"></script>
        
        <script>
            const {
                ClassicEditor,
                Essentials,
                Bold,
                Italic,
                Font,
                Paragraph
            } = CKEDITOR;
            const { FormatPainter } = CKEDITOR_PREMIUM_FEATURES;

            ClassicEditor
                .create( document.querySelector( '#description' ), {
                    licenseKey: 'eyJhbGciOiJFUzI1NiJ9.eyJleHAiOjE3OTY0Mjg3OTksImp0aSI6IjA3Y2MyMzgxLTI3MGYtNGQ2Ny04NDIyLWNkOThhOWY0NmMwNyIsImxpY2Vuc2VkSG9zdHMiOlsiMTI3LjAuMC4xIiwibG9jYWxob3N0IiwiMTkyLjE2OC4qLioiLCIxMC4qLiouKiIsIjE3Mi4qLiouKiIsIioudGVzdCIsIioubG9jYWxob3N0IiwiKi5sb2NhbCJdLCJ1c2FnZUVuZHBvaW50IjoiaHR0cHM6Ly9wcm94eS1ldmVudC5ja2VkaXRvci5jb20iLCJkaXN0cmlidXRpb25DaGFubmVsIjpbImNsb3VkIiwiZHJ1cGFsIl0sImxpY2Vuc2VUeXBlIjoiZGV2ZWxvcG1lbnQiLCJmZWF0dXJlcyI6WyJEUlVQIiwiRTJQIiwiRTJXIl0sInJlbW92ZUZlYXR1cmVzIjpbIlBCIiwiUkYiLCJTQ0giLCJUQ1AiLCJUTCIsIlRDUiIsIklSIiwiU1VBIiwiQjY0QSIsIkxQIiwiSEUiLCJSRUQiLCJQRk8iLCJXQyIsIkZBUiIsIkJLTSIsIkZQSCIsIk1SRSJdLCJ2YyI6ImY5ZDI0NzJhIn0.pryARMLx4mKBqBpY79TyzGH0vsts1yj-XgyaFOAPbDzTU6j-66VEdq598g3kMHah7RxqaI0kfIo9ZrrhB2TZqg',
                    plugins: [ Essentials, Bold, Italic, Font, Paragraph, FormatPainter ],
                    toolbar: [
                        'undo', 'redo', '|', 'bold', 'italic', '|',
                        'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', '|',
                        'formatPainter'
                    ]
                } )
                .then( /* ... */ )
                .catch( /* ... */ );
        </script>
    @endpush