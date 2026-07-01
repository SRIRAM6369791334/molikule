@props([
    'name' => 'description',
    'id' => null,
    'value' => '',
    'rows' => 4,
    'placeholder' => 'Enter description...',
    'required' => false,
    'error' => null
])

@php
    $editorId = $id ?? $name;
    $hasError = $errors->has($name) || $error;
@endphp

<textarea 
    class="form-control ckeditor @error($name) is-invalid @enderror" 
    id="{{ $editorId }}" 
    name="{{ $name }}" 
    rows="{{ $rows }}"
    placeholder="{{ $placeholder }}"
    @if($required) required @endif
>{{ old($name, $value) }}</textarea>

@error($name)
    <div class="invalid-feedback">{{ $message }}</div>
@enderror

@once
    @push('styles')
    <style>
        /* CKEditor custom styling to match admin theme */
        .ck-editor__editable {
            min-height: 200px;
        }
        .ck.ck-editor__main>.ck-editor__editable {
            background-color: #fff;
            border-color: #ced4da;
        }
        .ck.ck-editor__main>.ck-editor__editable:focus {
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }
        .ck.ck-toolbar {
            background-color: #f8f9fa;
            border-color: #ced4da;
        }
        /* Error state styling */
        .is-invalid + .ck-editor .ck-editor__editable {
            border-color: #dc3545;
        }
    </style>
    @endpush

    @push('scripts')
    <!-- CKEditor 5 Classic Build CDN -->
    <script src="https://cdn.ckeditor.com/ckeditor5/40.2.0/classic/ckeditor.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize CKEditor on all textareas with class 'ckeditor'
            const editorElements = document.querySelectorAll('.ckeditor');
            
            editorElements.forEach(function(element) {
                ClassicEditor
                    .create(element, {
                        toolbar: {
                            items: [
                                'heading',
                                '|',
                                'bold',
                                'italic',
                                '|',
                                'numberedList',
                                'bulletedList',
                                '|',
                                'indent',
                                'outdent',
                                '|',
                                'link',
                                'blockQuote',
                                'insertTable',
                                '|',
                                'undo',
                                'redo'
                            ],
                            shouldNotGroupWhenFull: true
                        },
                        heading: {
                            options: [
                                { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                                { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                                { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                                { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' },
                                { model: 'heading4', view: 'h4', title: 'Heading 4', class: 'ck-heading_heading4' }
                            ]
                        },
                        fontSize: {
                            options: [
                                'tiny',
                                'small',
                                'default',
                                'big',
                                'huge'
                            ]
                        },
                        link: {
                            decorators: {
                                openInNewTab: {
                                    mode: 'manual',
                                    label: 'Open in a new tab',
                                    attributes: {
                                        target: '_blank',
                                        rel: 'noopener noreferrer'
                                    }
                                }
                            }
                        },
                        table: {
                            contentToolbar: [
                                'tableColumn',
                                'tableRow',
                                'mergeTableCells'
                            ]
                        }
                    })
                    .then(editor => {
                        // Store editor instance for later use
                        element.ckeditorInstance = editor;
                        
                        // Handle form validation errors
                        if (element.classList.contains('is-invalid')) {
                            const editorElement = editor.ui.view.editable.element;
                            editorElement.classList.add('is-invalid');
                        }
                        
                        // Update textarea on editor change for form submission
                        editor.model.document.on('change:data', () => {
                            element.value = editor.getData();
                        });
                    })
                    .catch(error => {
                        console.error('CKEditor initialization error:', error);
                    });
            });
        });
    </script>
    @endpush
@endonce

