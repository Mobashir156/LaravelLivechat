@extends('layouts.app')

@section('content')
        <div class="row justify-content-center">
            <div class="col-md-8">
                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <div class="wrapper">
                            <section class="chat-area">
                                <div class="chat-box"></div>
                                <form action="#" class="typing-area">
                                    <input type="text" class="incoming_id" name="incoming_id" value="{{ $in_id->id }}"
                                        hidden>
                                    <input type="text" name="message" class="input-field"
                                        placeholder="Type a message here..." autocomplete="off">
                                    <button><i class="fab fa-telegram-plane"></i></button>
                                </form>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    <script>
        const form = document.querySelector(".typing-area"),
            incoming_id = form.querySelector(".incoming_id").value,
            inputField = form.querySelector(".input-field"),
            sendBtn = form.querySelector("button"),
            chatBox = document.querySelector(".chat-box"),
            csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content'); // CSRF Token

        form.onsubmit = (e) => {
            e.preventDefault();
        }

        inputField.focus();
        inputField.onkeyup = () => {
            if (inputField.value != "") {
                sendBtn.classList.add("active");
            } else {
                sendBtn.classList.remove("active");
            }
        }

        sendBtn.onclick = () => {
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "/insert-chat", true);

            // Set the CSRF token in the request headers
            xhr.setRequestHeader("X-CSRF-TOKEN", csrfToken);

            xhr.onload = () => {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        inputField.value = "";
                        scrollToBottom();
                    }
                }
            }

            let formData = new FormData(form);
            xhr.send(formData);
        }

        chatBox.onmouseenter = () => {
            chatBox.classList.add("active");
        }

        chatBox.onmouseleave = () => {
            chatBox.classList.remove("active");
        }

        setInterval(() => {
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "/get-chat", true);
            xhr.setRequestHeader("X-CSRF-TOKEN", csrfToken);
            xhr.onload = () => {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        let data = JSON.parse(xhr.responseText); // Parse the JSON response
                        let messageContainer = document.createElement('div');
                        messageContainer.innerHTML = data.html; // Append the message HTML

                        chatBox.innerHTML = ''; // Clear the chat box
                        chatBox.appendChild(messageContainer); // Append the new messages
                        if (!chatBox.classList.contains("active")) {
                            scrollToBottom();
                        }
                    }
                }
            }
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.send("incoming_id=" + incoming_id);
        }, 500);

        function scrollToBottom() {
            chatBox.scrollTop = chatBox.scrollHeight;
        }
    </script>
@endsection
