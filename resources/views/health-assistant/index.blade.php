@extends('layout.master')
@section('title', 'Assistant IA Personnel')

@section('content')
<div class="row">
  <!-- Statistiques -->
  <div class="col-lg-4 mb-4">
    <div class="card shadow-sm border-0 rounded-4 h-100">
      <div class="card-header bg-gradient-primary text-white rounded-top-4">
        <h5 class="mb-0">📊 Vos Statistiques</h5>
      </div>
      <div class="card-body">
        <div class="mb-3">
          <small class="text-muted">Total d'activités</small>
          <h3 class="text-primary">{{ $stats['total_activities'] }}</h3>
        </div>
        <div class="mb-3">
          <small class="text-muted">Calories brûlées</small>
          <h3 class="text-success">{{ number_format($stats['total_calories']) }} kcal</h3>
        </div>
        <div class="mb-3">
          <small class="text-muted">Durée totale</small>
          <h3 class="text-info">{{ number_format($stats['total_duration']) }} min</h3>
        </div>

        @if($stats['last_activity'])
        <hr>
        <div>
          <small class="text-muted">Dernière activité</small>
          <p class="mb-0 fw-bold">{{ $stats['last_activity']->activity->name }}</p>
          <small class="text-muted">{{ \Carbon\Carbon::parse($stats['last_activity']->date)->format('d/m/Y') }}</small>
        </div>
        @endif
      </div>
    </div>
  </div>

  <!-- Chatbot -->
  <div class="col-lg-8 mb-4">
    <div class="card shadow-sm border-0 rounded-4 h-100">
      <div class="card-header bg-gradient-primary text-white rounded-top-4 d-flex justify-content-between align-items-center">
        <h5 class="mb-0">🤖 Assistant IA Personnel de Santé</h5>
        <button class="btn btn-sm btn-light" onclick="clearChat()">Effacer</button>
      </div>
      <div class="card-body d-flex flex-column" style="height: 500px;">
        
        <!-- Zone de messages -->
        <div id="chatMessages" class="flex-grow-1 overflow-auto mb-3 p-3 bg-light rounded-3" style="max-height: 400px;">
          <div class="message-bot mb-3">
            <div class="d-flex align-items-start">
              <div class="avatar bg-primary text-white rounded-circle me-2" style="width: 40px; height: 40px; line-height: 40px; text-align: center;">
                🤖
              </div>
              <div class="message-content bg-white p-3 rounded-3 shadow-sm" style="max-width: 80%;">
                <p class="mb-0">Bonjour ! Je suis votre assistant personnel de santé. Je peux vous aider à :</p>
                <ul class="mb-0 mt-2">
                  <li>Analyser vos habitudes d'activité physique</li>
                  <li>Vous recommander des activités adaptées</li>
                  <li>Vous donner des conseils personnalisés</li>
                  <li>Répondre à vos questions sur votre santé</li>
                </ul>
                <small class="text-muted">{{ now()->format('H:i') }}</small>
              </div>
            </div>
          </div>
        </div>

        <!-- Zone de saisie -->
        <div class="input-group">
          <input 
            type="text" 
            id="userMessage" 
            class="form-control rounded-start-3" 
            placeholder="Posez votre question... (ex: Quelles activités me recommandes-tu ?)"
            onkeypress="handleKeyPress(event)"
          >
          <button class="btn btn-primary rounded-end-3" onclick="sendMessage()">
            <i class="bi bi-send-fill"></i> Envoyer
          </button>
        </div>

        <!-- Questions suggérées -->
        <div class="mt-2">
          <small class="text-muted">Questions suggérées :</small>
          <div class="d-flex flex-wrap gap-2 mt-1">
            <button class="btn btn-sm btn-outline-secondary rounded-pill" onclick="askQuestion('Quelles activités me recommandes-tu cette semaine ?')">
              💪 Recommandations
            </button>
            <button class="btn btn-sm btn-outline-secondary rounded-pill" onclick="askQuestion('Analyse mes habitudes du mois dernier')">
              📊 Analyse mensuelle
            </button>
            <button class="btn btn-sm btn-outline-secondary rounded-pill" onclick="askQuestion('Comment améliorer ma routine ?')">
              🎯 Améliorer routine
            </button>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

<style>
.bg-gradient-primary {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

#chatMessages {
  scroll-behavior: smooth;
}

.message-bot, .message-user {
  animation: fadeIn 0.3s ease-in;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}

.avatar {
  flex-shrink: 0;
}
</style>

<script>
const chatMessages = document.getElementById('chatMessages');
const userMessageInput = document.getElementById('userMessage');

function handleKeyPress(event) {
  if (event.key === 'Enter') {
    sendMessage();
  }
}

function askQuestion(question) {
  userMessageInput.value = question;
  sendMessage();
}

async function sendMessage() {
  const message = userMessageInput.value.trim();
  
  if (!message) {
    Swal.fire({
      icon: 'warning',
      title: 'Message vide',
      text: 'Veuillez saisir une question.',
      timer: 2000,
      showConfirmButton: false
    });
    return;
  }

  // Afficher le message utilisateur
  addUserMessage(message);
  userMessageInput.value = '';

  // Afficher loader
  addLoaderMessage();

  try {
    const response = await fetch('{{ route("health_assistant.chat") }}', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      body: JSON.stringify({ message: message })
    });

    const data = await response.json();

    // Retirer le loader
    removeLoaderMessage();

    if (data.success) {
      addBotMessage(data.response, data.timestamp);
    } else {
      addBotMessage('Désolé, une erreur s\'est produite. Veuillez réessayer.', new Date().toLocaleTimeString('fr-FR', {hour: '2-digit', minute:'2-digit'}));
    }

  } catch (error) {
    removeLoaderMessage();
    addBotMessage('Erreur de connexion. Vérifiez votre connexion internet.', new Date().toLocaleTimeString('fr-FR', {hour: '2-digit', minute:'2-digit'}));
  }
}

function addUserMessage(message) {
  const timestamp = new Date().toLocaleTimeString('fr-FR', {hour: '2-digit', minute:'2-digit'});
  const messageHTML = `
    <div class="message-user mb-3">
      <div class="d-flex align-items-start justify-content-end">
        <div class="message-content bg-primary text-white p-3 rounded-3 shadow-sm" style="max-width: 80%;">
          <p class="mb-0">${escapeHtml(message)}</p>
          <small class="opacity-75">${timestamp}</small>
        </div>
        <div class="avatar bg-secondary text-white rounded-circle ms-2" style="width: 40px; height: 40px; line-height: 40px; text-align: center;">
          👤
        </div>
      </div>
    </div>
  `;
  chatMessages.insertAdjacentHTML('beforeend', messageHTML);
  chatMessages.scrollTop = chatMessages.scrollHeight;
}

function addBotMessage(message, timestamp) {
  const messageHTML = `
    <div class="message-bot mb-3">
      <div class="d-flex align-items-start">
        <div class="avatar bg-primary text-white rounded-circle me-2" style="width: 40px; height: 40px; line-height: 40px; text-align: center;">
          🤖
        </div>
        <div class="message-content bg-white p-3 rounded-3 shadow-sm" style="max-width: 80%;">
          <p class="mb-0">${formatMessage(message)}</p>
          <small class="text-muted">${timestamp}</small>
        </div>
      </div>
    </div>
  `;
  chatMessages.insertAdjacentHTML('beforeend', messageHTML);
  chatMessages.scrollTop = chatMessages.scrollHeight;
}

function addLoaderMessage() {
  const loaderHTML = `
    <div class="message-bot mb-3" id="loaderMessage">
      <div class="d-flex align-items-start">
        <div class="avatar bg-primary text-white rounded-circle me-2" style="width: 40px; height: 40px; line-height: 40px; text-align: center;">
          🤖
        </div>
        <div class="message-content bg-white p-3 rounded-3 shadow-sm">
          <div class="spinner-border spinner-border-sm text-primary" role="status">
            <span class="visually-hidden">Chargement...</span>
          </div>
          <span class="ms-2">Je réfléchis...</span>
        </div>
      </div>
    </div>
  `;
  chatMessages.insertAdjacentHTML('beforeend', loaderHTML);
  chatMessages.scrollTop = chatMessages.scrollHeight;
}

function removeLoaderMessage() {
  const loader = document.getElementById('loaderMessage');
  if (loader) loader.remove();
}

function clearChat() {
  Swal.fire({
    title: 'Effacer la conversation ?',
    icon: 'question',
    showCancelButton: true,
    confirmButtonColor: '#667eea',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Oui, effacer',
    cancelButtonText: 'Annuler'
  }).then((result) => {
    if (result.isConfirmed) {
      chatMessages.innerHTML = `
        <div class="message-bot mb-3">
          <div class="d-flex align-items-start">
            <div class="avatar bg-primary text-white rounded-circle me-2" style="width: 40px; height: 40px; line-height: 40px; text-align: center;">
              🤖
            </div>
            <div class="message-content bg-white p-3 rounded-3 shadow-sm" style="max-width: 80%;">
              <p class="mb-0">Conversation effacée ! Posez-moi une nouvelle question.</p>
              <small class="text-muted">${new Date().toLocaleTimeString('fr-FR', {hour: '2-digit', minute:'2-digit'})}</small>
            </div>
          </div>
        </div>
      `;
    }
  });
}

function escapeHtml(text) {
  const div = document.createElement('div');
  div.textContent = text;
  return div.innerHTML;
}

function formatMessage(text) {
  // Convertir markdown simple en HTML
  text = escapeHtml(text);
  text = text.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
  text = text.replace(/\n/g, '<br>');
  return text;
}
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection