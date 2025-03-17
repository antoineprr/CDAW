"use strict";

function modify(e) {
    let parent = e.currentTarget.parentNode;
    let currentP = parent.querySelector("p");
    let commentText = currentP.textContent;
    
    let form = document.getElementById("myForm");
    let textarea = document.getElementById("editTextarea");
    let submitBtn = form.querySelector("input[type='submit']");
    
    textarea.value = commentText;
    
    form.style.display = "block";
    submitBtn.disabled = false;
    
    form.dataset.targetUserId = parent.id;
}

function deleter(e)
{
    e.currentTarget.parentNode.remove();
}

document.getElementById("myForm").addEventListener("submit", function(e) {
    e.preventDefault(); 
    
    let textarea = document.getElementById("editTextarea");
    let newComment = textarea.value;
    
    if (!newComment.trim()) {
        alert("Comment cannot be empty!");
        return;
    }
    
    let userId = this.dataset.targetUserId;
    let targetP = document.querySelector(`#${userId} p`);
    targetP.textContent = newComment;
    
    this.style.display = "none";
    this.querySelector("input[type='submit']").disabled = true;
});

document.getElementById("addNew").addEventListener("click", addComment);

function addComment(e){
    e.currentTarget.textContent = "Add";
    e.currentTarget.removeEventListener("click", addComment);
    e.currentTarget.addEventListener("click", saveAdd);
    let users = document.getElementById("users");
    let previousNumber = users.lastElementChild.id.substring(users.lastElementChild.id.length-1); 
    
    let newUserInput = document.createElement("div");
    newUserInput.id = `user${parseInt(previousNumber)+1}`;
    newUserInput.innerHTML = `
        <textarea id="userText" style="width: 20%; margin-top: 10px">New User</textarea>
        <textarea id="commentText" style="width: 90%; margin-top: 10px">New comment</textarea>
    `;
    users.appendChild(newUserInput);
    

    function saveAdd(e){
        let newUser = document.createElement("div");
        newUser.id = `user${parseInt(previousNumber)+1}`;
            newUser.innerHTML = `
            <h4>${newUserInput.querySelector("#userText").value}</h4>
            <p>${newUserInput.querySelector("#commentText").value}</p>
            <button class="modify">Modify Comment</button>
            <button class="remove">Remove Comment</button>
        `;
        newUser.querySelector(".modify").addEventListener("click", modify);
        newUser.querySelector(".remove").addEventListener("click", deleter);
        users.replaceChild(newUser, newUserInput);
        e.currentTarget.textContent = "Add new Comment";
        e.currentTarget.removeEventListener("click", saveAdd);
        e.currentTarget.addEventListener("click", addComment);
    };
};

let modifiers = document.getElementsByClassName("modify");
Array.from(modifiers).forEach(m => m.addEventListener("click",modify));

let remover = document.getElementsByClassName("remove");
Array.from(remover).forEach(m => m.addEventListener("click",deleter));