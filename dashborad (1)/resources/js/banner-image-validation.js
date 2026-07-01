/**
 * Banner Image Validation Module
 * Handles client-side validation for banner image uploads
 * 
 * Requirements:
 * - Banner Image: 1500x700px, max 5MB
 * - Min Image: 1024x1024px, max 5MB
 * - Supported formats: JPEG, PNG, GIF, WebP
 */

const BannerImageValidator = {
    // Configuration
    config: {
        image: {
            requiredWidth: 1521,
            requiredHeight: 580,
            maxSize: 5 * 1024 * 1024, // 5MB
            name: 'Banner Image'
        },
        minimage: {
            requiredWidth: 1024,
            requiredHeight: 1024,
            maxSize: 5 * 1024 * 1024, // 5MB
            name: 'Min Image'
        },
        allowedFormats: ['image/jpeg', 'image/png', 'image/gif', 'image/webp']
    },

    /**
     * Validate image file
     */
    validateImageFile(file, config) {
        const errors = [];

        // Check if file exists
        if (!file) {
            return { valid: false, errors: ['No file selected'] };
        }

        // Check file type
        if (!this.config.allowedFormats.includes(file.type)) {
            errors.push(`Invalid file format. Allowed: JPEG, PNG, GIF, WebP`);
        }

        // Check file size
        if (file.size > config.maxSize) {
            const maxSizeMB = config.maxSize / (1024 * 1024);
            const fileSizeMB = (file.size / (1024 * 1024)).toFixed(2);
            errors.push(`File size (${fileSizeMB}MB) exceeds maximum allowed (${maxSizeMB}MB)`);
        }

        return {
            valid: errors.length === 0,
            errors: errors
        };
    },

    /**
     * Validate image dimensions
     */
    validateImageDimensions(img, config) {
        const errors = [];

        if (img.width !== config.requiredWidth) {
            errors.push(`Width must be exactly ${config.requiredWidth}px (current: ${img.width}px)`);
        }

        if (img.height !== config.requiredHeight) {
            errors.push(`Height must be exactly ${config.requiredHeight}px (current: ${img.height}px)`);
        }

        return {
            valid: errors.length === 0,
            errors: errors
        };
    },

    /**
     * Preview and validate banner image
     */
    previewImage(input) {
        const preview = document.getElementById('imagePreview');
        const previewImg = document.getElementById('previewImg');
        const formGroup = input.closest('.mb-3');
        const feedbackDiv = formGroup?.querySelector('.invalid-feedback') || this.createFeedbackDiv(input);

        if (input.files && input.files[0]) {
            const file = input.files[0];

            // Validate file properties
            const fileValidation = this.validateImageFile(file, this.config.image);
            if (!fileValidation.valid) {
                this.showError(input, feedbackDiv, fileValidation.errors);
                input.value = '';
                preview.style.display = 'none';
                return;
            }

            const reader = new FileReader();

            reader.onload = (e) => {
                const img = new Image();
                img.onload = () => {
                    const dimensionValidation = this.validateImageDimensions(img, this.config.image);

                    if (!dimensionValidation.valid) {
                        this.showError(input, feedbackDiv, dimensionValidation.errors);
                        input.value = '';
                        preview.style.display = 'none';
                        return;
                    }

                    // All validations passed
                    this.clearError(input, feedbackDiv);
                    previewImg.src = e.target.result;
                    preview.style.display = 'block';
                    input.classList.remove('is-invalid');
                    input.classList.add('is-valid');
                };

                img.onerror = () => {
                    this.showError(input, feedbackDiv, ['Error reading image. Please try again.']);
                    input.value = '';
                    preview.style.display = 'none';
                };

                img.src = e.target.result;
            };

            reader.onerror = () => {
                this.showError(input, feedbackDiv, ['Error reading file. Please try again.']);
                input.value = '';
                preview.style.display = 'none';
            };

            reader.readAsDataURL(file);
        } else {
            preview.style.display = 'none';
            this.clearError(input, feedbackDiv);
        }
    },

    /**
     * Preview and validate min image
     */
    previewMinImage(input) {
        const preview = document.getElementById('minImagePreview');
        const previewImg = document.getElementById('previewMinImg');
        const formGroup = input.closest('.mb-3');
        const feedbackDiv = formGroup?.querySelector('.invalid-feedback') || this.createFeedbackDiv(input);

        if (input.files && input.files[0]) {
            const file = input.files[0];

            // Validate file properties
            const fileValidation = this.validateImageFile(file, this.config.minimage);
            if (!fileValidation.valid) {
                this.showError(input, feedbackDiv, fileValidation.errors);
                input.value = '';
                preview.style.display = 'none';
                return;
            }

            const reader = new FileReader();

            reader.onload = (e) => {
                const img = new Image();
                img.onload = () => {
                    const dimensionValidation = this.validateImageDimensions(img, this.config.minimage);

                    if (!dimensionValidation.valid) {
                        this.showError(input, feedbackDiv, dimensionValidation.errors);
                        input.value = '';
                        preview.style.display = 'none';
                        return;
                    }

                    // All validations passed
                    this.clearError(input, feedbackDiv);
                    previewImg.src = e.target.result;
                    preview.style.display = 'block';
                    input.classList.remove('is-invalid');
                    input.classList.add('is-valid');
                };

                img.onerror = () => {
                    this.showError(input, feedbackDiv, ['Error reading image. Please try again.']);
                    input.value = '';
                    preview.style.display = 'none';
                };

                img.src = e.target.result;
            };

            reader.onerror = () => {
                this.showError(input, feedbackDiv, ['Error reading file. Please try again.']);
                input.value = '';
                preview.style.display = 'none';
            };

            reader.readAsDataURL(file);
        } else {
            preview.style.display = 'none';
            this.clearError(input, feedbackDiv);
        }
    },

    /**
     * Show validation errors
     */
    showError(input, feedbackDiv, errors) {
        input.classList.remove('is-valid');
        input.classList.add('is-invalid');
        feedbackDiv.innerHTML = errors.join('<br>');
        feedbackDiv.style.display = 'block';
    },

    /**
     * Clear validation errors
     */
    clearError(input, feedbackDiv) {
        input.classList.remove('is-invalid');
        if (feedbackDiv) {
            feedbackDiv.style.display = 'none';
            feedbackDiv.innerHTML = '';
        }
    },

    /**
     * Create feedback div if not exists
     */
    createFeedbackDiv(input) {
        let feedbackDiv = input.nextElementSibling;
        if (!feedbackDiv || !feedbackDiv.classList.contains('invalid-feedback')) {
            feedbackDiv = document.createElement('div');
            feedbackDiv.className = 'invalid-feedback d-block';
            input.parentNode.insertBefore(feedbackDiv, input.nextSibling);
        }
        return feedbackDiv;
    },

    /**
     * Validate form before submission
     */
    validateForm(formId) {
        const form = document.getElementById(formId);
        if (!form) return false;

        const imageInput = form.querySelector('#image');
        const minImageInput = form.querySelector('#minimage');
        const titleInput = form.querySelector('#title');
        const subtitleInput = form.querySelector('#subtitle');
        const descriptionTextarea = form.querySelector('textarea[name="description"]');

        let isValid = true;
        const errors = [];

        // Validate Title
        if (!titleInput || !titleInput.value.trim()) {
            errors.push('Title is required');
            isValid = false;
        }

        // Validate Subtitle
        if (!subtitleInput || !subtitleInput.value.trim()) {
            errors.push('Subtitle is required');
            isValid = false;
        }

        // Validate Description (CKEditor)
        if (descriptionTextarea) {
            const descContent = descriptionTextarea.value.trim();
            if (!descContent) {
                errors.push('Description is required');
                isValid = false;
            }
        }

        // Validate Banner Image
        if (!imageInput || !imageInput.files || imageInput.files.length === 0) {
            errors.push('Banner Image is required (1521x580px, max 5MB)');
            isValid = false;
        }

        // Validate Min Image
        if (!minImageInput || !minImageInput.files || minImageInput.files.length === 0) {
            errors.push('Min Image is required (1024x1024px, max 5MB)');
            isValid = false;
        }

        if (!isValid) {
            alert('Please fix the following errors:\n\n' + errors.join('\n'));
        }

        return isValid;
    },

    /**
     * Initialize event listeners
     */
    init() {
        const imageInput = document.getElementById('image');
        const minImageInput = document.getElementById('minimage');
        const form = document.getElementById('createBannerForm');

        if (imageInput) {
            imageInput.addEventListener('change', () => this.previewImage(imageInput));
        }

        if (minImageInput) {
            minImageInput.addEventListener('change', () => this.previewMinImage(minImageInput));
        }

        if (form) {
            form.addEventListener('submit', (e) => {
                if (!this.validateForm('createBannerForm')) {
                    e.preventDefault();
                }
            });
        }
    }
};

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    BannerImageValidator.init();
});

export { BannerImageValidator };
