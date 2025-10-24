@extends('layout.master')

@section('title', 'Chatbot Assistant')

@section('content')
<div class="row justify-content-center mt-4">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-header bg-primary text-white d-flex align-items-center">
                <i class="mdi mdi-robot-outline me-2"></i>
                <h5 class="mb-0">Chatbot Assistant</h5>
            </div>
            <div class="card-body p-0" style="height:500px; display:flex; flex-direction:column;">
                <div id="chat-box" style="flex:1; padding:15px; overflow-y:auto; background:#f5f5f5;"></div>
                <div class="input-group p-2 border-top">
                    <input id="user-input" type="text" class="form-control" placeholder="Posez une question...">
                    <button id="send-btn" class="btn btn-primary">Envoyer</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('custom-scripts')
<style>
/* Bulles de chat */
.chat-message { display:flex; margin-bottom:10px; }
.chat-message.user { justify-content:flex-end; }
.chat-message.bot { justify-content:flex-start; }
.chat-bubble { max-width:70%; padding:10px 15px; border-radius:20px; word-wrap:break-word; }
.chat-bubble.user { background-color:#007bff; color:white; border-bottom-right-radius:0; }
.chat-bubble.bot { background-color:#e0e0e0; color:black; border-bottom-left-radius:0; }

/* Avatars */
.avatar { width:35px; height:35px; border-radius:50%; margin:0 10px; }
</style>

<script>
function appendMessage(sender, message) {
    let chatBox = document.getElementById('chat-box');

    let msgDiv = document.createElement('div');
    msgDiv.className = 'chat-message ' + sender;

    let bubble = document.createElement('div');
    bubble.className = 'chat-bubble ' + sender;
    bubble.innerHTML = message;

    // Ajout avatar
    let avatar = document.createElement('img');
    avatar.className = 'avatar';
    avatar.src = sender === 'bot'
        ? '/assets/images/bot-avatar.png'
        : '/assets/images/user-avatar.png';

    if(sender === 'user'){
        msgDiv.appendChild(bubble);
        msgDiv.appendChild(avatar);
    } else {
        msgDiv.appendChild(avatar);
        msgDiv.appendChild(bubble);
    }

    chatBox.appendChild(msgDiv);
    chatBox.scrollTop = chatBox.scrollHeight;
}

document.getElementById('send-btn').addEventListener('click', function () {
    let input = document.getElementById('user-input');
    let question = input.value.trim();
    if (!question) return;

    appendMessage('user', question);

    fetch("{{ route('chatbot.response') }}?question=" + encodeURIComponent(question))
        .then(response => response.json())
        .then(data => {
            appendMessage('bot', data.response);
            input.value = '';
        })
        .catch(error => appendMessage('bot', 'Erreur : ' + error));
});

document.getElementById('user-input').addEventListener('keypress', function(e){
    if(e.key === 'Enter') document.getElementById('send-btn').click();
});
</script>
@endpush
