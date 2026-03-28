// Modern Document Upload Interface JavaScript

// Global variables for file handling
let kycFiles = [];
let additionalFiles = [];

// Initialize document interface
function initDocumentInterface() {
    setupDragAndDrop();
    setupFileInputs();
    addFadeInAnimation();
}

// Add fade-in animation to cards
function addFadeInAnimation() {
    const cards = document.querySelectorAll(".document-card-modern");
    cards.forEach((card, index) => {
        setTimeout(() => {
            card.classList.add("fade-in");
        }, index * 200);
    });
}

// Setup drag and drop functionality
function setupDragAndDrop() {
    const kycDropzone = document.getElementById("kycDropzone");
    const additionalDropzone = document.getElementById("additionalDropzone");

    if (kycDropzone) {
        setupDropzone(kycDropzone, "kyc");
    }

    if (additionalDropzone) {
        setupDropzone(additionalDropzone, "additional");
    }
}

// Setup individual dropzone
function setupDropzone(dropzone, type) {
    dropzone.addEventListener("dragenter", dragEnterHandler);
    dropzone.addEventListener("dragleave", dragLeaveHandler);
    dropzone.addEventListener("dragover", dragOverHandler);
    dropzone.addEventListener("drop", (e) => dropHandler(e, type));
}

// Drag and drop event handlers
function dragEnterHandler(e) {
    e.preventDefault();
    e.currentTarget?.classList.add("drag-enter");
}

function dragLeaveHandler(e) {
    e.preventDefault();
    e.currentTarget?.classList.remove("drag-enter");
    e.currentTarget?.classList.add("drag-leave");
    setTimeout(() => {
        e.currentTarget?.classList.remove("drag-leave");
    }, 300);
}

function dragOverHandler(e) {
    e.preventDefault();
}

function dropHandler(e, type) {
    e.preventDefault();
    e.currentTarget.classList.remove("drag-enter", "drag-leave");

    const files = e.dataTransfer.files;
    handleFiles(files, type);
}

// Handle file selection
function handleFileSelect(event, type) {    
    const files = event.target?.files;
    if(files){
        handleFiles(files, type);
    }
}

// Process files
function handleFiles(files, type) {
    const fileArray = Array.from(files);

    // Validate files
    const validFiles = fileArray.filter((file) => validateFile(file, type));

    if (validFiles.length === 0) {
        showNotification("No valid files selected.", "error");
        return;
    }

    // Add files to appropriate array
    if (type === "kyc") {
        const maxFiles = 5; // Limit KYC files

        if (fileArray.length > maxFiles) {
            showUploadMessage(
                "error",
                `You can only upload up to ${maxFiles} KYC documents.`
            );
            return;
        }

        if (validFiles.length > 0) {
            uploadFiles(validFiles, "kyc");

            kycFiles = [...kycFiles, ...validFiles];
            updateFilesList("kyc");
        }
    } else {
        if (validFiles.length > 0) {
            uploadFiles(validFiles, "other");
            
            additionalFiles = [...additionalFiles, ...validFiles];
            updateFilesList("additional");
        }
    }

    showNotification(
        `${validFiles.length} file(s) added successfully.`,
        "success"
    );
}

// Validate file
function validateFile(file, type) {
    const maxSize = 10 * 1024 * 1024; // 10MB

    // Check file size
    if (file.size > maxSize) {
        showNotification(
            `File "${file.name}" is too large. Maximum size is 10MB.`,
            "error"
        );
        return false;
    }

    // Check file type
    const allowedTypes =
        type === "kyc"
            ? ["application/pdf", "image/jpeg", "image/jpg", "image/png"]
            : [
                  "application/pdf",
                  "image/jpeg",
                  "image/jpg",
                  "image/png",
                  "application/msword",
                  "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
              ];

    if (!allowedTypes.includes(file.type)) {
        showNotification(
            `File "${file.name}" has an unsupported format.`,
            "error"
        );
        return false;
    }

    return true;
}

// Update files list display
function updateFilesList(type) {
    const files = type === "kyc" ? kycFiles : additionalFiles;
    const listId = type === "kyc" ? "kycFilesList" : "additionalFilesList";
    const containerId =
        type === "kyc" ? "kycFilesContainer" : "additionalFilesContainer";
    const countId = type === "kyc" ? "kycFilesCount" : "additionalFilesCount";

    const filesList = document.getElementById(listId);
    const container = document.getElementById(containerId);
    const countElement = document.getElementById(countId);

    if (!filesList || !container) return;

    // Show/hide files list
    if (files.length > 0) {
        filesList.style.display = "block";
        container.innerHTML = "";

        files.forEach((file, index) => {
            const fileItem = createFileItem(file, index, type);
            container.appendChild(fileItem);
        });

        if (countElement) {
            countElement.textContent = `${files.length} file${
                files.length !== 1 ? "s" : ""
            }`;
        }
    } else {
        filesList.style.display = "none";
    }
}

// Create file item element
function createFileItem(file, index, type) {
    if (type == null) {
        type = index
        removeFunc = `deleteFile('${file.id}', '${type}')`;
        downloadBtn = `<button class="btn-download-file" onclick="downloadFile('${
                file.id
            }')">
                <i class="bi bi-download"></i>
            </button>`;
        
    }else{
        removeFunc = `removeFile(${index}, '${type}')`;
        downloadBtn = ``;
    }
    const fileItem = document.createElement("div");
    fileItem.className = "file-item-modern";
    fileItem.innerHTML = `
        <div class="file-preview">
            <i class="bi bi-${getFileIcon(file.type)}"></i>
        </div>
        <div class="file-info">
            <div class="file-name">${file.name}</div>
            <div class="file-size">${formatFileSize(file.size)}</div>
        </div>
        ${downloadBtn}
        <button type="button" class="btn-remove-file" onclick="${removeFunc}">
            <i class="bi bi-x"></i>
        </button>
    `;
    return fileItem;
}

// Get file icon based on type
function getFileIcon(mimeType) {
    if (mimeType.includes("pdf")) return "file-pdf";
    if (mimeType.includes("image")) return "image";
    if (mimeType.includes("word")) return "file-word";
    return "file-text";
}

// Format file size
function formatFileSize(bytes) {
    if (bytes === 0) return "0 Bytes";
    const k = 1024;
    const sizes = ["Bytes", "KB", "MB", "GB"];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + " " + sizes[i];
}

// Remove file from list
function removeFile(index, type) {
    if (type === "kyc") {
        kycFiles.splice(index, 1);
        updateFilesList("kyc");
    } else {
        additionalFiles.splice(index, 1);
        updateFilesList("additional");
    }

    showNotification("File removed successfully.", "info");
}

// Trigger file input
function triggerKycFileInput() {
    const input = document.getElementById("kycFileInput");
    if (input) input.click();
}

function triggerAdditionalFileInput() {
    const input = document.getElementById("additionalFileInput");
    if (input) input.click();
}

// Legacy function names for compatibility
function triggerOtherDocsFileInput() {
    triggerAdditionalFileInput();
}

// Show notification
function showNotification(message, type = "info") {
    const messagesContainer = document.getElementById("uploadMessages");
    if (!messagesContainer) return;

    const notification = document.createElement("div");
    notification.className = `alert alert-${
        type === "error" ? "danger" : type
    } alert-dismissible fade show`;
    notification.innerHTML = `
        <i class="bi bi-${getNotificationIcon(type)} me-2"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

    messagesContainer.appendChild(notification);

    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 5000);
}

// Get notification icon
function getNotificationIcon(type) {
    switch (type) {
        case "success":
            return "check-circle";
        case "error":
            return "exclamation-circle";
        case "warning":
            return "exclamation-triangle";
        default:
            return "info-circle";
    }
}
function uploadFiles(files, type) {
    const formData = new FormData();
    const progressBar = document.getElementById("progressBar");
    const progressText = document.getElementById("progressText");
    const uploadProgress = document.getElementById("uploadProgress");

    // Show progress bar
    if (uploadProgress) {
        uploadProgress.style.display = "block";
    }

    // Add files to form data
    files.forEach((file, index) => {
        formData.append(`files[${index}]`, file);
    });
    formData.append("type", type);
    formData.append(
        "_token",
        document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute("content")
    );

    // Create XMLHttpRequest to track upload progress
    const xhr = new XMLHttpRequest();

    // Upload progress tracking
    xhr.upload.addEventListener("progress", (e) => {
        if (e.lengthComputable) {
            const percentComplete = (e.loaded / e.total) * 100;
            if (progressBar) {
                progressBar.style.width = percentComplete + "%";
            }
            if (progressText) {
                progressText.textContent = Math.round(percentComplete) + "%";
            }
        }
    });

    // Upload complete
    xhr.addEventListener("load", () => {
        if (uploadProgress) {
            uploadProgress.style.display = "none";
        }

        try {
            const response = JSON.parse(xhr.responseText);
            if (response.success) {
                showUploadMessage(
                    "success",
                    response.message || "Files uploaded successfully!"
                );
                displayUploadedFiles(response.files, type);

                // Clear file inputs
                const kycFileInput = document.getElementById("kycFileInput");
                const otherDocsFileInput =
                    document.getElementById("otherDocsFileInput");
                if (kycFileInput) kycFileInput.value = "";
                if (otherDocsFileInput) otherDocsFileInput.value = "";
            } else {
                showUploadMessage(
                    "error",
                    response.message || "Upload failed. Please try again."
                );
            }
        } catch (error) {
            showUploadMessage("error", "Upload failed. Please try again.");
        }
    });

    // Upload error
    xhr.addEventListener("error", () => {
        if (uploadProgress) {
            uploadProgress.style.display = "none";
        }
        showUploadMessage(
            "error",
            "Upload failed. Please check your connection and try again."
        );
    });

    // Send the request
    xhr.open("POST", "/client/upload-documents", true);
    xhr.send(formData);
}

/*
// Upload files (mock function - integrate with your backend)
function uploadFiles() {
    if (kycFiles.length === 0 && additionalFiles.length === 0) {
        showNotification("Please select files to upload.", "warning");
        return;
    }

    showUploadProgress();
    // Mock upload process
    simulateUpload(() => {
        hideUploadProgress();
        showNotification("Files uploaded successfully!", "success");

        // Clear files after successful upload
        kycFiles = [];
        additionalFiles = [];
        updateFilesList("kyc");
        updateFilesList("additional");
    });
}
*/
// Show upload progress
function showUploadProgress() {
    const progressSection = document.getElementById("uploadProgressSection");
    if (progressSection) {
        progressSection.style.display = "block";

        // Scroll to progress section
        progressSection.scrollIntoView({ behavior: "smooth", block: "center" });
    }
}

// Hide upload progress
function hideUploadProgress() {
    const progressSection = document.getElementById("uploadProgressSection");
    if (progressSection) {
        setTimeout(() => {
            progressSection.style.display = "none";
        }, 1000);
    }
}

// Simulate upload progress
function simulateUpload(callback) {
    const progressBar = document.getElementById("progressBar");
    const progressText = document.getElementById("progressText");
    const progressDescription = document.getElementById("progressDescription");

    let progress = 0;
    const interval = setInterval(() => {
        progress += Math.random() * 15;
        if (progress > 100) progress = 100;

        if (progressBar) {
            progressBar.style.width = progress + "%";
        }

        if (progressText) {
            progressText.textContent = Math.round(progress) + "%";
        }

        if (progressDescription) {
            if (progress < 30) {
                progressDescription.textContent = "Validating files...";
            } else if (progress < 60) {
                progressDescription.textContent = "Uploading files...";
            } else if (progress < 90) {
                progressDescription.textContent = "Processing files...";
            } else {
                progressDescription.textContent = "Finalizing upload...";
            }
        }

        if (progress >= 100) {
            clearInterval(interval);
            if (progressDescription) {
                progressDescription.textContent = "Upload completed!";
            }
            setTimeout(callback, 500);
        }
    }, 200);
}

// Setup file input functionality
function setupFileInputs() {
    // KYC file inputs
    const kycFileInput = document.getElementById("kycFileInput");
    const kycDropzone = document.getElementById("kycDropzone");

    if (kycFileInput && kycDropzone) {
        kycDropzone.addEventListener("click", () => {
            kycFileInput.click();
        });

        kycFileInput.addEventListener("change", (e) => {
            if (e.target.files.length > 0) {
                handleFileSelect(e.target.files, "kyc");
            }
        });
    }

    // Additional documents file inputs
    const additionalFileInput = document.getElementById("additionalFileInput");
    const additionalDropzone = document.getElementById("additionalDropzone");

    if (additionalFileInput && additionalDropzone) {
        additionalDropzone.addEventListener("click", () => {
            additionalFileInput.click();
        });

        additionalFileInput.addEventListener("change", (e) => {
            if (e.target.files.length > 0) {
                handleFileSelect(e.target.files, "additional");
            }
        });
    }
}

// Initialize when DOM is loaded
document.addEventListener("DOMContentLoaded", function () {
    // Initialize document interface if on the document page
    if (document.getElementById("uploadDocumentInterface")) {
        initDocumentInterface();
    }
});

// Add file item styles dynamically
const fileItemStyles = `
<style>
.file-item-modern {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: linear-gradient(135deg, rgba(15, 23, 42, 0.8) 0%, rgba(30, 41, 59, 0.8) 100%);
    border: 1px solid rgba(71, 85, 105, 0.3);
    border-radius: 12px;
    transition: all 0.3s ease;
}

.file-item-modern:hover {
    background: linear-gradient(135deg, rgba(30, 41, 59, 0.9) 0%, rgba(51, 65, 85, 0.9) 100%);
    border-color: rgba(71, 85, 105, 0.5);
}

.file-preview {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
    flex-shrink: 0;
}

.file-info {
    flex: 1;
    min-width: 0;
}

.file-name {
    color: #f8fafc;
    font-weight: 500;
    font-size: 0.9rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    margin-bottom: 0.25rem;
}

.file-size {
    color: #94a3b8;
    font-size: 0.8rem;
}

.btn-remove-file {
    background: rgba(239, 68, 68, 0.1);
    border: 1px solid rgba(239, 68, 68, 0.3);
    color: #ef4444;
    width: 30px;
    height: 30px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    cursor: pointer;
    flex-shrink: 0;
}

.btn-remove-file:hover {
    background: rgba(239, 68, 68, 0.2);
    border-color: rgba(239, 68, 68, 0.5);
    color: #dc2626;
}

.btn-download-file {
    background: rgba(13, 110, 253, 0.1);
    border: 1px solid rgba(13, 110, 253, 0.3);
    color: #0d6efd;
    width: 30px;
    height: 30px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    cursor: pointer;
    flex-shrink: 0;
}

.btn-download-file:hover {
    background: rgba(13, 110, 253, 0.2);
    border-color: rgba(13, 110, 253, 0.5);
    color: #0b52bd;
}
</style>
`;

// Add styles to document head
if (document.head && !document.getElementById("file-item-styles")) {
    const styleElement = document.createElement("div");
    styleElement.id = "file-item-styles";
    styleElement.innerHTML = fileItemStyles;
    document.head.appendChild(styleElement);
}
