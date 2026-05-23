if (typeof Pusher !== "undefined" && typeof Echo !== "undefined") {
    window.Pusher = Pusher;

    window.Echo = new Echo({
        broadcaster: "pusher",
        key: PUSHER_APP_KEY,
        cluster: PUSHER_APP_CLUSTER,
        forceTLS: true,

        enabledTransports: ["ws", "wss", "xhr_streaming", "xhr_polling"],
    });
    Pusher.logToConsole = true;
    if (USER_ID) {
        let notification_container = document.getElementById(
            "notification-container",
        );
        Echo.private(`App.Models.User.${USER_ID}`).notification(
            (notification) => {
                let div = document.createElement("div");
                let span = document.createElement("span");
                div.className =
                    "text-success mt-3 text-center small mail-success-notification";
                div.append(span);
                span.textContent = `${notification.title} ${notification.messageType}`;
                document.body.append(div);
                if (notification.creatorImage) {
                    var image = `
                                <div class="avatar-circle">
                                  <img src="http://127.0.0.1:8000/storage/${notification.creatorImage}"
                                            class="rounded-circle" width="40" height="40" alt="">
                                </div>
                    `;
                } else {
                    var image = `
                                <div class="avatar-circle">
                                    ${notification.title.slice(0, 2).toUpperCase()}
                                </div>
                                    `;
                }
                notification_container.insertAdjacentHTML(
                    "afterbegin",
                    `
                                            <li class="notification dropdown-item py-4">
                            <div class="d-flex gap-3 align-items-center">

                            ${image}
                                <div class="d-flex flex-column justify-content-start">
                                <div class="d-flex gap-4 align-items-center">
                                        <span class="fw-bold">
                                            <a href="http://127.0.0.1:8000/user/profile/${notification.userId}" class="text-decoration-none">
                                                 ${notification.title}
                                            </a>
                                        </span>
                                        <span class="fw-semibold text-danger"> ${notification.typeof} </span>
                                        <p class="text-muted m-0">
                                             ${notification.created_at} 
                                        </p>
                                        <button class="notificationDel border border-1 bg-transparent px-2 py-1 ms-auto"
                                            url="http://127.0.0.1:8000/notification/${notification.id}">
                                                Mark as Read
                                        </button>
                                    </div>
                                    <a href="${notification.url}?n=${notification.id}"
                                        class="link-animation text-decoration-none">
                                        <div class="fw-light m-0">
                                            <span class="fw-normal">
                                                 ${notification.messageType} 
                                            </span>
                                             "${notification.message}" 
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </li>
                    `,
                );
            },
        );
        Echo.private(`App.Models.User.${USER_ID}`).listen(
            ".counter.notification",
            (counterEvent) => {
                console.log(counterEvent);
                let notification_counter_container = document.querySelector(
                    ".notification-counter",
                );
                console.log(notification_counter_container);
                if (notification_counter_container)
                    notification_counter_container.innerHTML =
                        counterEvent.counter;
                else {
                    let notification = document.querySelector(`.notification`);
                    let counter = `<div class="notification-counter">${counterEvent.counter}</div>`;
                    notification.insertAdjacentHTML("afterbegin", counter);
                }
            },
        );
    }
}

const csrf_token = document.querySelector("meta[name='csrf-token']").content;

window.onpageshow = function (event) {
    if (event.persisted) {
        location.reload();
    }
};

const registerForm = document.getElementById("registerForm");
if (registerForm) {
    registerForm.addEventListener("submit", function (e) {
        e.preventDefault();
        const formData = new FormData(registerForm);
        const button = registerForm.querySelector("button[type='submit']");
        button.disabled = true;
        button.innerText = "Loading...";

        fetch("/register", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": csrf_token,
                Accept: "application/json",
            },
            body: formData,
        })
            .then((response) => response.json())
            .then((data) => {
                for (const [key] of formData.entries()) {
                    const el = document.getElementById(`error-${key}`);
                    if (el) el.innerText = "";
                }
                if (data.errors) {
                    for (const field in data.errors) {
                        const element = document.getElementById(
                            `error-${field}`,
                        );
                        if (element) element.innerText = data.errors[field][0];
                        button.disabled = false;
                        button.innerText = "Sign Up";
                        button.style.backgroundColor = "red";
                        button.style.border = "none";
                    }
                } else {
                    button.style.border = "none";
                    button.style.backgroundColor = "blue";
                    window.location.href = data.redirect;
                }
            })
            .catch((err) => console.error(err));
    });
}
const loginForm = document.getElementById("loginForm");
if (loginForm) {
    loginForm.addEventListener("submit", function (e) {
        e.preventDefault();
        const button = loginForm.querySelector("button[type='submit']");
        button.disabled = true;
        button.innerText = "Loading...";
        const formData = new FormData(loginForm);
        fetch("/login", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": csrf_token,
                Accept: "application/json",
            },
            body: formData,
        })
            .then((response) => response.json())
            .then((data) => {
                for (const [key] of formData.entries()) {
                    const el = document.getElementById(`error-${key}`);
                    if (el) el.innerText = "";
                }
                if (data.errors) {
                    for (const field in data.errors) {
                        const element = document.getElementById(
                            `error-${field}`,
                        );
                        if (element) element.innerText = data.errors[field][0];
                        button.disabled = false;
                        button.innerText = "Login";
                        button.style.backgroundColor = "red";
                        button.style.border = "none";
                    }
                } else {
                    button.style.border = "none";
                    button.style.backgroundColor = "blue";
                    window.location.href = data.redirect;
                }
            });
    });
}

const revealElements = document.querySelectorAll(
    ".reveal, .pencil-animate, .text-animate, .main-section, .sub-section",
);

const observer = new IntersectionObserver(
    (entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add("active");
            }
        });
    },
    { threshold: 0.2 },
);

revealElements.forEach((el) => observer.observe(el));

const Search = document.getElementById("Search");
const results = document.getElementById("results");
const hiddenInput = document.getElementById("categoryId");
if (Search) {
    Search.addEventListener("keyup", function () {
        const url = Search.getAttribute("url");
        let query = this.value;
        if (query.length < 2) {
            results.innerHTML = "";
            return;
        }
        fetch(`${url}?q=${query}`)
            .then((response) => response.json())
            .then((data) => {
                let html = ``;
                data.result.forEach((category) => {
                    html += `<button type="button" class="list-group-item list-group-item-action searchResult w-100" id="${category.id}">${category.name}</button>`;
                });
                results.innerHTML = html;
            });
    });
}
if (results) {
    results.addEventListener("click", function (e) {
        if (e.target.tagName === "BUTTON") {
            Search.value = e.target.textContent;
            hiddenInput.value = e.target.id;
            results.innerHTML = "";
        }
    });
}
const searchBar = document.getElementById("searchBar");
const resultsBar = document.getElementById("resultsBar");
if (searchBar) {
    searchBar.addEventListener("keyup", function () {
        let searchContainer = searchBar.closest(".search-container");

        searchContainer.addEventListener("focusout", (e) => {
            if (!searchContainer.contains(e.relatedTarget)) {
                resultsBar.innerHTML = "";
            }
        });
        const usersRoute = searchBar.getAttribute("usersRoute");
        const postsRoute = searchBar.getAttribute("postsRoute");
        const url = searchBar.getAttribute("url");
        let query = this.value;
        if (query.length < 2) {
            resultsBar.innerHTML = "";
            return;
        }
        fetch(`${url}?q=${query}`)
            .then((response) => response.json())
            .then((data) => {
                let html = ``;
                data[0].posts.forEach((post) => {
                    let postRoute = postsRoute.replace(":postId", post.id);
                    let imageSrc = post.image.startsWith("http")
                        ? post.image
                        : `http://127.0.0.1:8000/storage/${post.image}`;

                    html += `
                        <a href="${postRoute}" 
                        class="search-item d-flex align-items-center text-decoration-none" tabindex="0">
                        
                        <img src="${imageSrc}" class="search-img">
                        
                        <div class="ms-3">
                        <div class="search-title">${post.title}</div>
                        <div class="search-sub">View Post</div>
                        </div>
                        
                        </a>`;
                });
                data[0].users.forEach((user) => {
                    let userRoute = usersRoute.replace(":userId", user.id);
                    let imageSrc = user.image
                        ? user.image.startsWith("http")
                            ? user.image
                            : `http://127.0.0.1:8000/storage/${user.image}`
                        : null;
                    let image =
                        imageSrc == null
                            ? ` <div class="profile-circle" style="width: 40px !important; height:40px !important">
                                ${user.name.slice(0, 2).toUpperCase()}
                                </div>`
                            : `<img src="${imageSrc}" class="search-img rounded-circle" alt="${user.name}">`;

                    html += `
                        <a href="${userRoute}" 
                        class="search-item d-flex align-items-center text-decoration-none" tabindex="0">
                            ${image}
                            <div class="ms-3">
                                <div class="search-title">${user.name}</div>
                                <div class="search-sub">View User</div>
                            </div>
                            <i class="bi bi-person ms-auto text-muted me-3"></i>
                        </a>`;
                });
                resultsBar.innerHTML = html;
            });
    });
}
if (resultsBar) {
    resultsBar.addEventListener("click", function (e) {
        if (e.target.tagName === "BUTTON") {
            Search.value = e.target.textContent;
            resultsBar.innerHTML = "";
        }
    });
}

const form = document.getElementById("form");
if (form) {
    form.addEventListener("submit", function (e) {
        const button = form.querySelector("button[type='submit']");
        let url = form.getAttribute("url");
        const formData = new FormData(form);
        e.preventDefault();
        fetch(url, {
            method: `POST`,
            headers: {
                "X-CSRF-TOKEN": csrf_token,
                Accept: "application/json",
            },
            body: formData,
        })
            .then((response) => response.json())
            .then((data) => {
                for (const [key] of formData.entries()) {
                    const el = document.getElementById(`error-${key}`);
                    if (el) el.innerText = ``;
                }
                if (data.errors) {
                    for (field in data.errors) {
                        const element = document.getElementById(
                            `error-${field}`,
                        );
                        if (element) element.innerText = data.errors[field][0];
                    }
                } else {
                    button.disabled = true;
                    button.innerText = `loading..`;
                    window.location.href = data.redirect;
                    button.disabled = false;
                    button.innerText = `Done`;
                }
            })
            .catch((error) => {
                console.error(error);
            });
    });
}

const followBtns = document.querySelectorAll(".followBtn");
if (followBtns) {
    followBtns.forEach((followBtn) => {
        followBtn.addEventListener("click", function () {
            let url = followBtn.getAttribute("url");
            let followId = followBtn.dataset.id;
            fetch(`${url}?id=${followId}`, {
                headers: {
                    "X-CSRF-TOKEN": csrf_token,
                    Accept: "application/json",
                },
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.status === "success") {
                        console.log(data);
                        if (followBtn.innerText === "Following") {
                            followBtn.innerText = "Follow";
                        } else if (followBtn.innerText === "Follow") {
                            followBtn.innerText = "Following";
                        }
                    }
                });
        });
    });
}
document.addEventListener("click", function (e) {
    if (e.target.closest(".saveBtn")) {
        const saveBtn = e.target.closest(".saveBtn");
        let url = saveBtn.dataset.url;
        fetch(url, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": csrf_token,
                Accept: "application/json",
            },
        })
            .then((response) => response.json())
            .then((data) => {
                const icon = saveBtn.querySelector("i");
                if (icon) {
                    if (data.status == "saved") {
                        icon.classList.remove("bi-bookmark");
                        icon.classList.add("bi-bookmark-fill", "text-primary", "save-animation");
                    } else {
                        icon.classList.remove("bi-bookmark-fill", "text-primary", "save-animation");
                        icon.classList.add("bi-bookmark");
                    }
                }
            });
    }
});
document.addEventListener("click", function (e) {
    if (e.target.closest(".likeBtn")) {
        const likeBtn = e.target.closest(".likeBtn");
        let url = likeBtn.dataset.url;
        fetch(url, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": csrf_token,
                Accept: "application/json",
            },
        })
            .then((res) => res.json())
            .then((data) => {
                let postId = likeBtn.dataset.postId;
                let likeCounter = document.getElementById(`likeCounter-${postId}`);
                
                const icon = likeBtn.querySelector("i");
                if (icon) {
                    if (data.status === "liked") {
                        icon.classList.remove("bi-hand-thumbs-up", "normal");
                        icon.classList.add("bi-hand-thumbs-up-fill", "text-primary", "like-animation");
                    } else {
                        icon.classList.remove("bi-hand-thumbs-up-fill", "text-primary", "like-animation");
                        icon.classList.add("bi-hand-thumbs-up", "normal");
                    }
                }
                
                if (likeCounter) {
                    likeCounter.innerText = data.likes;
                }
                console.log(data);
            });
    }
});

// const likeBtn = document.getElementById("likeBtn");
// if (likeBtn) {
//     likeBtn.addEventListener("click", function () {
//         let url = likeBtn.getAttribute("url");
//         fetch(url, {
//             method: "POST",
//             headers: {
//                 "X-CSRF-TOKEN": csrf_token,
//                 Accept: "application/json",
//             },
//         })
//             .then((response) => response.json())
//             .then((data) => {
//                 let likeCounter = document.getElementById("likeCounter");
//                 if (data.status === "liked") {
//                     likeBtn.innerHTML = `<i class="bi bi-hand-thumbs-up-fill fs-3 ms-1"></i>`;
//                     likeCounter.innerText = data.likes;
//                 } else if (data.status === "unliked") {
//                     likeBtn.innerHTML = `<i class="bi bi-hand-thumbs-up normal fs-3 ms-1"></i>`;
//                     likeCounter.innerText = data.likes;
//                 }
//             })
//             .catch((error) => {
//                 console.error(error);
//             });
//     });
// }

const commentBtn = document.getElementById("commentBtn");
const commentSection = document.getElementById("commentSection");
if (commentBtn) {
    commentBtn.addEventListener("click", () => {
        commentSection.scrollIntoView({
            behavior: "smooth",
            block: "start",
        });
    });
}
const deleteForm = document.getElementById("deleteForm");

if (deleteForm) {
    deleteForm.addEventListener("submit", function (e) {
        e.preventDefault();
        let url = deleteForm.getAttribute("url");
        if (confirm("Do you want to delete this post?")) {
            fetch(url, {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": csrf_token,
                    Accept: "application/json",
                },
            })
                .then((response) => response.json())
                .then((data) => {
                    console.log(data);
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    }
                });
        }
    });
}

const pass_update_form = document.getElementById("pass_update");
if (pass_update_form) {
    pass_update_form.addEventListener("submit", function (e) {
        e.preventDefault();
        let url = pass_update_form.dataset.url;
        let formData = new FormData(pass_update_form);
        let button = pass_update_form.querySelector("button[type='submit']");
        button.textContent = "..Loading";
        button.disabled = true;
        setTimeout(() => {
            button.disabled = false;
            button.textContent = "Send Reset Link";
        }, 1500);
        fetch(url, {
            method: `POST`,
            headers: {
                "X-CSRF-TOKEN": csrf_token,
                Accept: "application/json",
            },
            body: formData,
        })
            .then((response) => response.json())
            .then((data) => {
                let error_email = document.getElementById(`error-email`);
                if (data.error) {
                    error_email.innerText = data.error;
                }
                if (data.errors) {
                    error_email.innerText = data.errors["email"][0];
                }
                if (data.email) {
                    error_email.innerText = "";
                    let div = document.createElement("div");
                    let span = document.createElement("span");
                    div.className =
                        "text-success mt-3 text-center small mail-success-notification";
                    div.append(span);
                    span.textContent = data.email;
                    document.body.append(div);
                }
            })
            .catch((error) => {
                console.log(error);
            });
    });
}
const commentForms = document.querySelectorAll(".commentForm");
if (commentForms) {
    commentForms.forEach((commentForm) => {
        commentForm.addEventListener("submit", function (e) {
            e.preventDefault();
            let url = commentForm.getAttribute("url");
            let formData = new FormData(commentForm);

            fetch(url, {
                method: `POST`,
                headers: {
                    "X-CSRF-TOKEN": csrf_token,
                    Accept: "application/json",
                },
                body: formData,
            })
                .then((response) => response.json())
                .then((data) => {
                    let deleteUrl = commentForm.dataset.deleteurl;
                    let postId = commentForm.getAttribute("postId");

                    const el = document.getElementById(`error-body-${postId}`);
                    if (el) el.innerText = ``;

                    if (data.errors) {
                        if (el) el.innerText = data.errors.body[0];
                    }

                    if (data.status === "success") {
                        let commentsContainer = document.getElementById(
                            `commentsContainer-${postId}`,
                        );
                        let commentStyle = commentForm.getAttribute('data-style');
                        let htmlContent = '';

                        if (commentStyle === 'chat') {
                            let chatImage = data.image ? `
                                <img src="/storage/${data.image}"
                                     class="rounded-circle shadow-sm object-fit-cover" width="38" height="38" alt="">
                            ` : `
                                <div class="profile-circle" style="width:38px !important; height:38px !important; font-size: 0.9rem;">
                                    ${data.name.slice(0, 2).toUpperCase()}
                                </div>
                            `;

                            htmlContent = `
                            <div class="d-flex gap-3" id="comment-${data.id}">
                                <a href="/user/profile/${data.userId}" class="text-decoration-none link-animation">
                                    ${chatImage}
                                </a>
                                <div class="bg-light p-3 rounded-4 rounded-top-0 border shadow-sm w-100">
                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                        <div>
                                            <a href="/user/profile/${data.userId}" class="fw-bold text-dark text-decoration-none link-animation">${data.name}</a>
                                            <small class="text-muted ms-2" style="font-size:0.8rem;">${data.date}</small>
                                        </div>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-link text-muted p-0 border-0" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3">
                                                <form url="${deleteUrl}${data.id}" data-id="${data.id}" class="deleteComment">
                                                    <li><button class="dropdown-item text-danger" type="submit"><i class="bi bi-trash me-2"></i> Delete</button></li>
                                                </form>
                                            </ul>
                                        </div>
                                    </div>
                                    <p class="mb-0 text-dark" style="font-size:1rem;">${data.body}</p>
                                </div>
                            </div>
                            `;
                        } else {
                            let standardImage = data.image ? `
                                <div class="avatar-circle">
                                  <img src="http://127.0.0.1:8000/storage/${data.image}"
                                            class="rounded-circle" width="40" height="40" alt="">
                                </div>
                            ` : `
                                <div class="profile-circle" style="width:45px !important; height:45px !important">
                                    ${data.name.slice(0, 2).toUpperCase()}
                                </div>
                            `;

                            htmlContent = `
                            <div class="comment" id="comment-${data.id}">
                                <div class="d-flex gap-2">
                                    <a href="http://127.0.0.1:8000/user/profile/${data.userId}" class="text-decoration-none text-dark">
                                        ${standardImage}
                                    </a>
                                    <div>
                                        <a href="http://127.0.0.1:8000/user/profile/${data.userId}" class="text-decoration-none link-animation">
                                            <h5>${data.name}</h5>
                                        </a>
                                        <h6 class="text-muted">${data.date}</h6>
                                    </div>
                                    <div class="dropdown ms-auto">
                                        <button class="border-0 delete-hovering" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-three-dots"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <form url="${deleteUrl}${data.id}" data-id="${data.id}" class="deleteComment">
                                                <li><button class="dropdown-item text-danger" type="submit">Delete</button></li>
                                            </form>
                                        </ul>
                                    </div>
                                </div>
                                <div>
                                    <span>${data.body}</span>
                                </div>
                            </div>
                            `;
                        }

                        commentsContainer.insertAdjacentHTML("afterbegin", htmlContent);

                        commentForm.reset();
                    }
                });
        });
    });
}

document.addEventListener("submit", function (e) {
    if (e.target.classList.contains("deleteComment")) {
        e.preventDefault();

        let deleteComment = e.target;
        let url = deleteComment.getAttribute("url");
        let id = deleteComment.dataset.id;

        if (confirm("Do you want to delete this Comment?")) {
            fetch(url, {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": csrf_token,
                    Accept: "application/json",
                },
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.status === "success") {
                        document.getElementById(`comment-${id}`)?.remove();
                    }
                });
        }
    }
});

const bell = document.getElementById("bell");
if (bell) {
    bell.addEventListener("click", function (e) {
        document.querySelector(".notification-counter").remove();
    });
}

document.addEventListener("click", function (e) {
    const notification = e.target.closest(".notificationDel");

    if (!notification) return;

    let url = notification.getAttribute("url");

    fetch(url, {
        method: "POST",
        headers: {
            Accept: "application/json",
            "X-CSRF-TOKEN": csrf_token,
        },
    })
        .then((response) => response.json())
        .then((data) => {
            console.log(data);
            if (data.status === "success") {
                const item = notification.closest(".notification");
                if (item) item.remove();
            }
        })
        .catch((err) => console.log(err));
});



