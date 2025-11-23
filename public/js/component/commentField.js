document.addEventListener("DOMContentLoaded", function () {
    const commentForm = document.getElementById("comment-form");
    const commentsList = document.getElementById("comments-list");

    // Handle toggle replies button
    commentsList.addEventListener("click", function (e) {
        if (
            e.target.classList.contains("toggle-replies-btn") ||
            e.target.closest(".toggle-replies-btn")
        ) {
            const btn = e.target.classList.contains("toggle-replies-btn")
                ? e.target
                : e.target.closest(".toggle-replies-btn");
            const commentId = btn.getAttribute("data-comment-id");
            const repliesList = commentsList.querySelector(
                `.replies-list[data-comment-id="${commentId}"]`
            );
            const icon = btn.querySelector("i");
            const text = btn.querySelector(".replies-text");

            if (repliesList) {
                if (repliesList.classList.contains("hidden")) {
                    // Show replies
                    repliesList.classList.remove("hidden");
                    icon.className = "bi bi-chevron-up";
                    text.textContent = text.textContent.replace("Show", "Hide");
                } else {
                    // Hide replies
                    repliesList.classList.add("hidden");
                    icon.className = "bi bi-chevron-down";
                    text.textContent = text.textContent.replace("Hide", "Show");
                }
            }
        }
    });

    // Handle comment form submission
    if (commentForm) {
        commentForm.addEventListener("submit", function (e) {
            e.preventDefault();

            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;

            submitBtn.disabled = true;
            submitBtn.textContent = "Posting...";

            fetch(this.action, {
                method: "POST",
                body: formData,
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN":
                        document
                            .querySelector('meta[name="csrf-token"]')
                            ?.getAttribute("content") || formData.get("_token"),
                },
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        // Add new comment to the list
                        const commentHtml = createCommentHtml(data.comment);
                        if (commentsList.querySelector(".text-center")) {
                            commentsList.innerHTML = commentHtml;
                        } else {
                            commentsList.insertAdjacentHTML(
                                "afterbegin",
                                commentHtml
                            );
                        }

                        // Clear form
                        document.getElementById("comment-content").value = "";

                        // Update comment count
                        const countElement = document.querySelector(
                            "#comments-section h2"
                        );
                        const currentCount = parseInt(
                            countElement.textContent.match(/\d+/)[0]
                        );
                        countElement.textContent = `Comments (${
                            currentCount + 1
                        })`;

                        showMessage(data.message, "success");
                    } else {
                        showMessage("Failed to post comment", "error");
                    }
                })
                .catch((error) => {
                    console.error("Error:", error);
                    showMessage("An error occurred", "error");
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalText;
                });
        });
    }

    // Handle edit comments
    commentsList.addEventListener("click", function (e) {
        if (e.target.classList.contains("edit-comment-btn")) {
            const commentId = e.target.getAttribute("data-comment-id");
            const commentItem = e.target.closest(".comment-item, .reply-item");
            const contentDiv = commentItem.querySelector(".comment-content");
            const currentContent = contentDiv.textContent.trim();

            // Create edit form
            const editForm = document.createElement("div");
            editForm.className = "mt-2";
            editForm.innerHTML = `
                <textarea class="edit-textarea w-full p-3 border border-gray-300 dark:border-gray-600 rounded-lg resize-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100" 
                          rows="3">${currentContent}</textarea>
                <div class="flex gap-2 mt-2">
                    <button class="save-edit-btn px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition-colors">
                        Save
                    </button>
                    <button class="cancel-edit-btn px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg text-sm font-medium transition-colors">
                        Cancel
                    </button>
                </div>
            `;

            // Hide original content and show edit form
            contentDiv.style.display = "none";
            contentDiv.parentNode.appendChild(editForm);
            e.target.style.display = "none";

            // Handle save
            editForm
                .querySelector(".save-edit-btn")
                .addEventListener("click", function () {
                    const newContent = editForm
                        .querySelector(".edit-textarea")
                        .value.trim();
                    if (newContent.length < 3) {
                        showMessage(
                            "Comment must be at least 3 characters",
                            "error"
                        );
                        return;
                    }
                    updateComment(
                        commentId,
                        newContent,
                        contentDiv,
                        editForm,
                        e.target
                    );
                });

            // Handle cancel
            editForm
                .querySelector(".cancel-edit-btn")
                .addEventListener("click", function () {
                    contentDiv.style.display = "block";
                    e.target.style.display = "inline";
                    editForm.remove();
                });
        }
    });

    // Handle reply button
    commentsList.addEventListener("click", function (e) {
        if (
            e.target.classList.contains("reply-btn") ||
            e.target.closest(".reply-btn")
        ) {
            const replyBtn = e.target.classList.contains("reply-btn")
                ? e.target
                : e.target.closest(".reply-btn");
            const parentId = replyBtn.getAttribute("data-comment-id");
            const userName = replyBtn.getAttribute("data-user-name");
            const replyTo = replyBtn.getAttribute("data-reply-to");
            const commentItem = replyBtn.closest(".comment-item");

            // Remove any existing reply forms
            const existingForm = commentItem.querySelector(
                ".reply-form-container"
            );
            if (existingForm) {
                existingForm.remove();
                return;
            }

            // Create reply form with mention if replying to a reply
            const mentionText = replyTo ? `@${replyTo} ` : "";
            const replyingToText = replyTo ? replyTo : userName;

            const replyFormContainer = document.createElement("div");
            replyFormContainer.className =
                "reply-form-container mt-3 ml-8 border-l-2 border-blue-500 pl-4";
            replyFormContainer.innerHTML = `
                <p class="text-xs text-gray-500 mb-2">Replying to <span class="font-medium">${replyingToText}</span></p>
                <div class="flex gap-2">
                    <textarea class="reply-textarea flex-1 p-2 border border-gray-300 dark:border-gray-600 rounded-lg resize-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 text-sm" 
                              rows="2" 
                              placeholder="Write your reply..."
                              data-parent-id="${parentId}">${mentionText}</textarea>
                </div>
                <div class="flex gap-2 mt-2">
                    <button class="post-reply-btn px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition-colors">
                        Post Reply
                    </button>
                    <button class="cancel-reply-btn px-3 py-1.5 bg-gray-500 hover:bg-gray-600 text-white rounded-lg text-sm font-medium transition-colors">
                        Cancel
                    </button>
                </div>
            `;

            commentItem
                .querySelector(".flex-1")
                .appendChild(replyFormContainer);
            const textarea =
                replyFormContainer.querySelector(".reply-textarea");
            textarea.focus();
            // Place cursor at end of text (after mention)
            textarea.setSelectionRange(
                textarea.value.length,
                textarea.value.length
            );

            // Handle post reply
            replyFormContainer
                .querySelector(".post-reply-btn")
                .addEventListener("click", function () {
                    const textarea =
                        replyFormContainer.querySelector(".reply-textarea");
                    const content = textarea.value.trim();

                    if (content.length < 3) {
                        showMessage(
                            "Reply must be at least 3 characters",
                            "error"
                        );
                        return;
                    }

                    postReply(
                        parentId,
                        content,
                        replyFormContainer,
                        commentItem
                    );
                });

            // Handle cancel
            replyFormContainer
                .querySelector(".cancel-reply-btn")
                .addEventListener("click", function () {
                    replyFormContainer.remove();
                });
        }
    });

    // Handle delete comments
    commentsList.addEventListener("click", function (e) {
        if (e.target.classList.contains("delete-comment-btn")) {
            const commentId = e.target.getAttribute("data-comment-id");

            if (confirm("Are you sure you want to delete this comment?")) {
                deleteComment(commentId);
            }
        }
    });

    function createCommentHtml(comment) {
        return `
            <div class="comment-item bg-gray-50 dark:bg-gray-800 p-4 rounded-lg" data-comment-id="${comment.id}">
                <div class="flex gap-3">
                    <img src="${comment.user.avatar_url}" alt="${comment.user.name}" 
                         class="w-10 h-10 rounded-full object-cover">
                    <div class="flex-1">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="font-medium text-gray-900 dark:text-gray-100">${comment.user.name}</h4>
                                <p class="text-xs text-gray-500">${comment.created_at}</p>
                            </div>
                            <div class="flex gap-2">
                                <button class="edit-comment-btn text-blue-600 hover:text-blue-700 text-sm" 
                                        data-comment-id="${comment.id}">
                                    Edit
                                </button>
                                <button class="delete-comment-btn text-red-600 hover:text-red-700 text-sm" 
                                        data-comment-id="${comment.id}">
                                    Delete
                                </button>
                            </div>
                        </div>
                        <div class="comment-content mt-2 text-gray-700 dark:text-gray-300">
                            ${comment.content}
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    function updateComment(
        commentId,
        newContent,
        contentDiv,
        editForm,
        editBtn
    ) {
        const saveBtn = editForm.querySelector(".save-edit-btn");
        const originalText = saveBtn.textContent;
        saveBtn.disabled = true;
        saveBtn.textContent = "Saving...";

        fetch(`/comments/${commentId}`, {
            method: "PUT",
            headers: {
                "Content-Type": "application/json",
                "X-Requested-With": "XMLHttpRequest",
                "X-CSRF-TOKEN":
                    document
                        .querySelector('meta[name="csrf-token"]')
                        ?.getAttribute("content") || "{{ csrf_token() }}",
            },
            body: JSON.stringify({
                content: newContent,
            }),
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    contentDiv.textContent = newContent;
                    contentDiv.style.display = "block";
                    editBtn.style.display = "inline";
                    editForm.remove();
                    showMessage(data.message, "success");
                } else {
                    showMessage("Failed to update comment", "error");
                    saveBtn.disabled = false;
                    saveBtn.textContent = originalText;
                }
            })
            .catch((error) => {
                console.error("Error:", error);
                showMessage("An error occurred", "error");
                saveBtn.disabled = false;
                saveBtn.textContent = originalText;
            });
    }

    function deleteComment(commentId) {
        fetch(`/comments/${commentId}`, {
            method: "DELETE",
            headers: {
                "X-Requested-With": "XMLHttpRequest",
                "X-CSRF-TOKEN":
                    document
                        .querySelector('meta[name="csrf-token"]')
                        ?.getAttribute("content") || "{{ csrf_token() }}",
            },
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    // Remove comment from DOM
                    const commentElement = document.querySelector(
                        `[data-comment-id="${commentId}"]`
                    );
                    if (commentElement) {
                        commentElement.remove();

                        // Update comment count
                        const countElement = document.querySelector(
                            "#comments-section h2"
                        );
                        const currentCount = parseInt(
                            countElement.textContent.match(/\d+/)[0]
                        );
                        countElement.textContent = `Comments (${
                            currentCount - 1
                        })`;

                        // Show empty state if no comments
                        if (commentsList.children.length === 0) {
                            commentsList.innerHTML = `
                            <div class="text-center text-gray-500 py-8">
                                <p>No comments yet. Be the first to comment!</p>
                            </div>
                        `;
                        }
                    }
                    showMessage(data.message, "success");
                } else {
                    showMessage("Failed to delete comment", "error");
                }
            })
            .catch((error) => {
                console.error("Error:", error);
                showMessage("An error occurred", "error");
            });
    }

    function postReply(parentId, content, replyFormContainer, commentItem) {
        const postBtn = replyFormContainer.querySelector(".post-reply-btn");
        const originalText = postBtn.textContent;
        postBtn.disabled = true;
        postBtn.textContent = "Posting...";

        const formData = new FormData();
        formData.append("content", content);
        formData.append("parent_id", parentId);
        formData.append(
            "_token",
            document
                .querySelector('meta[name="csrf-token"]')
                ?.getAttribute("content")
        );

        fetch(commentForm.action, {
            method: "POST",
            body: formData,
            headers: {
                "X-Requested-With": "XMLHttpRequest",
            },
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    // Create reply HTML
                    const replyHtml = createReplyHtml(data.comment);

                    // Find or create replies list
                    let repliesList =
                        commentItem.querySelector(".replies-list");
                    if (!repliesList) {
                        repliesList = document.createElement("div");
                        repliesList.className =
                            "replies-list mt-4 ml-8 space-y-3 border-l-2 border-gray-300 dark:border-gray-600 pl-4";
                        commentItem
                            .querySelector(".flex-1")
                            .appendChild(repliesList);
                    }

                    repliesList.insertAdjacentHTML("beforeend", replyHtml);
                    replyFormContainer.remove();

                    showMessage(data.message, "success");
                } else {
                    showMessage("Failed to post reply", "error");
                    postBtn.disabled = false;
                    postBtn.textContent = originalText;
                }
            })
            .catch((error) => {
                console.error("Error:", error);
                showMessage("An error occurred", "error");
                postBtn.disabled = false;
                postBtn.textContent = originalText;
            });
    }

    function createReplyHtml(reply) {
        // Extract mention from content if exists
        const mentionMatch = reply.content.match(/^@(\w+)\s/);
        const hasMention = mentionMatch !== null;
        const displayContent = reply.content;

        return `
            <div class="reply-item bg-white dark:bg-gray-900 p-3 rounded-lg" data-comment-id="${
                reply.id
            }">
                <div class="flex gap-3">
                    <img src="${reply.user.avatar_url}" alt="${reply.user.name}"
                        class="w-8 h-8 rounded-full object-cover">
                    <div class="flex-1">
                        <div class="flex items-center justify-between">
                            <div>
                                <h5 class="font-medium text-sm text-gray-900 dark:text-gray-100">
                                    ${reply.user.name}</h5>
                                <p class="text-xs text-gray-500">
                                    ${reply.created_at}</p>
                            </div>
                            <div class="flex gap-2">
                                <button class="edit-comment-btn text-blue-600 hover:text-blue-700 text-xs"
                                    data-comment-id="${reply.id}">
                                    Edit
                                </button>
                                <button class="delete-comment-btn text-red-600 hover:text-red-700 text-xs"
                                    data-comment-id="${reply.id}">
                                    Delete
                                </button>
                            </div>
                        </div>
                        <div class="comment-content mt-1 text-sm text-gray-700 dark:text-gray-300">
                            ${displayContent}
                        </div>
                        <button class="reply-btn text-sm text-gray-600 hover:text-blue-600 mt-2 font-medium"
                            data-comment-id="${reply.parent_id || reply.id}"
                            data-user-name="${reply.user.name}"
                            data-reply-to="${reply.user.name}">
                            <i class="bi bi-reply"></i> Reply
                        </button>
                    </div>
                </div>
            </div>
        `;
    }

    function showMessage(message, type) {
        // Create a simple toast message
        const toast = document.createElement("div");
        toast.className = `fixed top-4 right-4 px-6 py-3 rounded-lg text-white z-50 ${
            type === "success" ? "bg-green-500" : "bg-red-500"
        }`;
        toast.textContent = message;
        document.body.appendChild(toast);

        setTimeout(() => {
            toast.remove();
        }, 3000);
    }
});
