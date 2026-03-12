@extends('layouts.app')

@section('title', 'Chat AI - SI SAKTI')

@section('content')
<div class="content-area">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-white mb-2">
                <i class="fas fa-robot mr-3 text-indigo-400"></i>Chat AI
            </h1>
            <p class="text-gray-400">Asisten cerdas untuk membantu dalam audit ketataan instansi</p>
        </div>

        <!-- Chat Container -->
        <div
            class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-xl rounded-2xl border border-slate-700/50 shadow-2xl overflow-hidden">
            <!-- Chat Header -->
            <div class="bg-gradient-to-r from-indigo-600/20 to-purple-600/20 border-b border-slate-700/50 p-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                            <i class="fas fa-robot text-white"></i>
                        </div>
                        <div>
                            <h2 class="text-white font-semibold">Asisten AI</h2>
                            <p class="text-gray-400 text-sm">Online - Siap membantu</p>
                        </div>
                    </div>
                    <button onclick="clearChat()"
                        class="px-4 py-2 bg-slate-700/50 hover:bg-slate-600/50 text-gray-300 rounded-lg transition-all duration-300 flex items-center gap-2 text-sm">
                        <i class="fas fa-trash-alt"></i>
                        Hapus Percakapan
                    </button>
                </div>
            </div>

            <!-- Chat Messages -->
            <div id="chatMessages" class="h-[600px] overflow-y-auto p-6 space-y-4">
                <!-- Welcome Message -->
                <div class="flex gap-3">
                    <div
                        class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-robot text-white text-xs"></i>
                    </div>
                    <div class="flex-1">
                        <div class="bg-slate-700/50 rounded-2xl rounded-tl-none p-4 max-w-3xl">
                            <p class="text-gray-200">Halo! Saya adalah asisten AI untuk SI SAKTI. Saya siap membantu
                                Anda dengan:</p>
                            <ul class="text-gray-300 mt-2 space-y-1 text-sm">
                                <li>• Pertanyaan seputar audit ketataan instansi</li>
                                <li>• Analisis keuangan dan anggaran</li>
                                <li>• Aturan dan regulasi</li>
                                <li>• Laporan dan dokumentasi</li>
                            </ul>
                            <p class="text-gray-200 mt-2">Silakan tanyakan apa yang Anda butuhkan!</p>
                        </div>
                        <p class="text-gray-500 text-xs mt-1">AI • Sekarang</p>
                    </div>
                </div>
            </div>

            <!-- Chat Input -->
            <div class="border-t border-slate-700/50 p-4 bg-slate-800/30">
                <form id="chatForm" class="flex gap-3">
                    <div class="flex-1 relative">
                        <textarea id="messageInput" rows="1" placeholder="Ketik pesan Anda di sini..."
                            class="w-full bg-slate-700/50 border border-slate-600/50 rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent resize-none transition-all duration-300"
                            onkeydown="handleKeyDown(event)"></textarea>
                    </div>
                    <button type="submit" id="sendButton"
                        class="px-6 py-3 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white rounded-xl font-semibold transition-all duration-300 flex items-center gap-2 shadow-lg hover:shadow-indigo-500/25">
                        <i class="fas fa-paper-plane"></i>
                        <span>Kirim</span>
                    </button>
                </form>
                <p class="text-gray-500 text-xs mt-2 text-center">
                    <i class="fas fa-info-circle mr-1"></i>
                    Asisten AI menggunakan OpenAI API untuk memberikan respons
                </p>
            </div>
        </div>

        <!-- Loading Overlay -->
        <div id="loadingOverlay" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
            <div class="bg-slate-800 rounded-2xl p-6 flex items-center gap-4">
                <div class="w-8 h-8 border-2 border-indigo-500 border-t-transparent rounded-full animate-spin"></div>
                <p class="text-white">Sedang memproses pesan...</p>
            </div>
        </div>
    </div>
</div>

<style>
    /* Custom scrollbar for chat messages */
    #chatMessages::-webkit-scrollbar {
        width: 6px;
    }

    #chatMessages::-webkit-scrollbar-track {
        background: rgba(30, 41, 59, 0.5);
    }

    #chatMessages::-webkit-scrollbar-thumb {
        background: rgba(99, 102, 241, 0.5);
        border-radius: 3px;
    }

    #chatMessages::-webkit-scrollbar-thumb:hover {
        background: rgba(99, 102, 241, 0.7);
    }

    /* Message animations */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .message-animate {
        animation: fadeIn 0.3s ease-out;
    }

    /* Typing indicator */
    .typing-indicator {
        display: flex;
        gap: 4px;
        padding: 8px;
    }

    .typing-indicator span {
        width: 8px;
        height: 8px;
        background: #6366f1;
        border-radius: 50%;
        animation: typing 1.4s infinite;
    }

    .typing-indicator span:nth-child(2) {
        animation-delay: 0.2s;
    }

    .typing-indicator span:nth-child(3) {
        animation-delay: 0.4s;
    }

    @keyframes typing {

        0%,
        60%,
        100% {
            transform: translateY(0);
        }

        30% {
            transform: translateY(-10px);
        }
    }
</style>

<script>
    let conversationHistory = [];

    // Auto-resize textarea
    const messageInput = document.getElementById('messageInput');
    messageInput.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = Math.min(this.scrollHeight, 150) + 'px';
    });

    // Handle Enter key
    function handleKeyDown(event) {
        if (event.key === 'Enter' && !event.shiftKey) {
            event.preventDefault();
            document.getElementById('chatForm').dispatchEvent(new Event('submit'));
        }
    }

    // Handle form submission
    document.getElementById('chatForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const message = messageInput.value.trim();
        if (!message) return;

        // Clear input
        messageInput.value = '';
        messageInput.style.height = 'auto';

        // Add user message to chat
        addMessage(message, 'user');
        conversationHistory.push({ role: 'user', content: message });

        // Show loading
        document.getElementById('loadingOverlay').classList.remove('hidden');

        try {
            const response = await fetch('/superadmin/chat/send', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    message: message,
                    conversation: conversationHistory
                })
            });

            const data = await response.json();

            if (data.success) {
                // Add AI response to chat
                addMessage(data.message, 'assistant');
                conversationHistory.push({ role: 'assistant', content: data.message });
            } else {
                addMessage('Error: ' + (data.error || 'Terjadi kesalahan'), 'error');
            }
        } catch (error) {
            addMessage('Error: Terjadi kesalahan koneksi', 'error');
            console.error('Error:', error);
        } finally {
            // Hide loading
            document.getElementById('loadingOverlay').classList.add('hidden');
        }
    });

    // Add message to chat
    function addMessage(content, type) {
        const chatMessages = document.getElementById('chatMessages');
        const messageDiv = document.createElement('div');
        messageDiv.className = 'flex gap-3 message-animate';

        const now = new Date();
        const timeString = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });

        if (type === 'user') {
            messageDiv.innerHTML = `
                <div class="flex-1"></div>
                <div class="max-w-3xl">
                    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl rounded-tr-none p-4">
                        <p class="text-white">${escapeHtml(content)}</p>
                    </div>
                    <p class="text-gray-500 text-xs mt-1 text-right">Anda • ${timeString}</p>
                </div>
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-user text-white text-xs"></i>
                </div>
            `;
        } else if (type === 'assistant') {
            messageDiv.innerHTML = `
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-robot text-white text-xs"></i>
                </div>
                <div class="flex-1">
                    <div class="bg-slate-700/50 rounded-2xl rounded-tl-none p-4 max-w-3xl">
                        <p class="text-gray-200 whitespace-pre-wrap">${escapeHtml(content)}</p>
                    </div>
                    <p class="text-gray-500 text-xs mt-1">AI • ${timeString}</p>
                </div>
            `;
        } else if (type === 'error') {
            messageDiv.innerHTML = `
                <div class="w-8 h-8 rounded-lg bg-red-500 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-exclamation text-white text-xs"></i>
                </div>
                <div class="flex-1">
                    <div class="bg-red-900/30 border border-red-500/30 rounded-2xl rounded-tl-none p-4 max-w-3xl">
                        <p class="text-red-300">${escapeHtml(content)}</p>
                    </div>
                    <p class="text-gray-500 text-xs mt-1">Error • ${timeString}</p>
                </div>
            `;
        }

        chatMessages.appendChild(messageDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    // Escape HTML to prevent XSS
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Clear chat
    function clearChat() {
        if (confirm('Apakah Anda yakin ingin menghapus semua percakapan?')) {
            const chatMessages = document.getElementById('chatMessages');
            
            // Keep only the welcome message
            chatMessages.innerHTML = `
                <div class="flex gap-3">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-robot text-white text-xs"></i>
                    </div>
                    <div class="flex-1">
                        <div class="bg-slate-700/50 rounded-2xl rounded-tl-none p-4 max-w-3xl">
                            <p class="text-gray-200">Halo! Saya adalah asisten AI untuk SI SAKTI. Saya siap membantu Anda dengan:</p>
                            <ul class="text-gray-300 mt-2 space-y-1 text-sm">
                                <li>• Pertanyaan seputar audit ketataan instansi</li>
                                <li>• Analisis keuangan dan anggaran</li>
                                <li>• Aturan dan regulasi</li>
                                <li>• Laporan dan dokumentasi</li>
                            </ul>
                            <p class="text-gray-200 mt-2">Silakan tanyakan apa yang Anda butuhkan!</p>
                        </div>
                        <p class="text-gray-500 text-xs mt-1">AI • Sekarang</p>
                    </div>
                </div>
            `;
            
            conversationHistory = [];
        }
    }

    // Add typing indicator
    function showTypingIndicator() {
        const chatMessages = document.getElementById('chatMessages');
        const typingDiv = document.createElement('div');
        typingDiv.id = 'typingIndicator';
        typingDiv.className = 'flex gap-3';
        typingDiv.innerHTML = `
            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center flex-shrink-0">
                <i class="fas fa-robot text-white text-xs"></i>
            </div>
            <div class="flex-1">
                <div class="bg-slate-700/50 rounded-2xl rounded-tl-none p-4 max-w-3xl">
                    <div class="typing-indicator">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>
            </div>
        `;
        chatMessages.appendChild(typingDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    function hideTypingIndicator() {
        const typingIndicator = document.getElementById('typingIndicator');
        if (typingIndicator) {
            typingIndicator.remove();
        }
    }
</script>
@endsection