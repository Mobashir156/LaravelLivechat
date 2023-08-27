@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="search">
                        <span class="text">Select an user to start chat</span>
                        <input type="text" placeholder="Enter name to search...">
                        <button><i class="fas fa-search"></i></button>
                      </div>
                      <div class="users-list">
                  
                      </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const searchBar = document.querySelector(".search input"),
    searchIcon = document.querySelector(".search button"),
    usersList = document.querySelector(".users-list"),
    csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

searchIcon.onclick = () => {
    searchBar.classList.toggle("show");
    searchIcon.classList.toggle("active");
    searchBar.focus();
    if (searchBar.classList.contains("active")) {
        searchBar.value = "";
        searchBar.classList.remove("active");
    }
}

searchIcon.onclick = () => {
    searchBar.classList.toggle("show");
    searchIcon.classList.toggle("active");
    searchBar.focus();
    if (searchBar.classList.contains("active")) {
        searchBar.value = "";
        searchBar.classList.remove("active");
    }
}

searchBar.onkeyup = () => {
    let searchTerm = searchBar.value;
    if (searchTerm != "") {
        searchBar.classList.add("active");
    } else {
        searchBar.classList.remove("active");
    }
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "/search", true);
    xhr.setRequestHeader("X-CSRF-TOKEN", csrfToken);
    xhr.onload = () => {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                let data = xhr.response;
                usersList.innerHTML = data;
            }
        }
    }
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send("searchTerm=" + searchTerm);
}

setInterval(() => {
    let xhr = new XMLHttpRequest();
    xhr.open("GET", "/users", true);
    xhr.setRequestHeader("X-CSRF-TOKEN", csrfToken);
    xhr.onload = () => {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                let data = xhr.response;
                if (!searchBar.classList.contains("active")) {
                    usersList.innerHTML = data;
                }
            }
        }
    }
    xhr.send();
}, 500);

// ... Rest of your JavaScript code ...


</script>


@endsection
