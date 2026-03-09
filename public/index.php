<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gemini AI Portal</title>
    <link rel="stylesheet" href="/output.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .chat-container::-webkit-scrollbar,
        #user-input::-webkit-scrollbar {
            width: 4px;
        }

        .chat-container::-webkit-scrollbar-track,
        #user-input::-webkit-scrollbar-track {
            background: transparent;
        }

        .chat-container::-webkit-scrollbar-thumb,
        #user-input::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 20px;
        }

        .chat-container::-webkit-scrollbar-thumb:hover,
        #user-input::-webkit-scrollbar-thumb:hover {
            background: #cbd5e1;
        }
    </style>
</head>

<body class="h-full flex items-center justify-center p-4">
    <div class="w-full max-w-2xl glass-card rounded-3xl shadow-2xl overflow-hidden flex flex-col h-[600px]">
        <!-- Header -->
        <div class="px-6 py-4 bg-white/50 border-b border-slate-200 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-200">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="font-bold text-slate-800 text-lg leading-tight">Gemini AI</h1>
                    <p class="text-xs text-slate-500 flex items-center">
                        <span class="w-2 h-2 bg-green-500 rounded-full mr-1.5 animate-pulse"></span>
                        Online & Ready
                    </p>
                </div>
            </div>
        </div>

        <!-- Chat History -->
        <div id="chat-history" class="chat-container flex-1 overflow-y-auto p-6 space-y-6 bg-slate-50/30">
            <!-- Example AI Message -->
            <div class="flex items-start">
                <div class="w-8 h-8 rounded-lg bg-blue-600 flex-shrink-0 flex items-center justify-center text-white text-xs font-bold mr-3">G</div>
                <div class="bg-white px-4 py-3 rounded-2xl rounded-tl-none shadow-sm border border-slate-100 max-w-[80%]">
                    <p class="text-slate-700 text-sm leading-relaxed">Hello! How can I help you today?</p>
                </div>
            </div>
        </div>

        <!-- Input Area -->
        <div class="p-6 bg-white/50 border-t border-slate-200">
            <form id="chat-form" class="relative">
                <div class="w-full bg-white border border-slate-200 rounded-2xl overflow-hidden focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-transparent transition-all">
                    <textarea
                        id="user-input"
                        class="w-full pl-4 pr-12 py-3 bg-transparent border-none resize-none outline-none text-slate-700 text-sm block"
                        placeholder="Ask me anything..."
                        rows="2"></textarea>
                </div>
                <button
                    type="submit"
                    id="send-btn"
                    class="absolute right-2 bottom-2 p-2 bg-blue-600 text-white rounded-xl shadow-lg shadow-blue-200 hover:bg-blue-700 active:scale-95 transition-all disabled:opacity-50 disabled:scale-100 disabled:cursor-not-allowed">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                </button>
            </form>
        </div>
    </div>

    <script>
        const chatForm = document.getElementById('chat-form');
        const userInput = document.getElementById('user-input');
        const chatHistory = document.getElementById('chat-history');
        const sendBtn = document.getElementById('send-btn');

        function appendMessage(text, isUser = false) {
            const div = document.createElement('div');
            div.className = isUser ? 'flex items-start justify-end' : 'flex items-start';

            const bgColor = isUser ? 'bg-blue-600 text-white' : 'bg-white text-slate-700 border border-slate-100';
            const rounded = isUser ? 'rounded-2xl rounded-tr-none' : 'rounded-2xl rounded-tl-none';
            const avatar = isUser ? '' : '<div class="w-8 h-8 rounded-lg bg-blue-600 flex-shrink-0 flex items-center justify-center text-white text-xs font-bold mr-3">G</div>';

            div.innerHTML = `
                ${avatar}
                <div class="${bgColor} px-4 py-3 ${rounded} shadow-sm max-w-[80%]">
                    <p class="text-sm leading-relaxed whitespace-pre-wrap">${text}</p>
                </div>
            `;

            chatHistory.appendChild(div);
            chatHistory.scrollTop = chatHistory.scrollHeight;
        }

        chatForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const prompt = userInput.value.trim();
            if (!prompt) return;

            // Add user message to UI
            appendMessage(prompt, true);
            userInput.value = '';

            // Loading state
            sendBtn.disabled = true;
            const loadingDiv = document.createElement('div');
            loadingDiv.className = 'flex items-start';
            loadingDiv.innerHTML = `
                <div class="w-8 h-8 rounded-lg bg-blue-600 flex-shrink-0 flex items-center justify-center text-white text-xs font-bold mr-3">G</div>
                <div class="bg-white px-4 py-3 rounded-2xl rounded-tl-none shadow-sm border border-slate-100 max-w-[80%] flex items-center space-x-1">
                    <span class="w-1.5 h-1.5 bg-slate-400 rounded-full animate-bounce"></span>
                    <span class="w-1.5 h-1.5 bg-slate-400 rounded-full animate-bounce [animation-delay:0.2s]"></span>
                    <span class="w-1.5 h-1.5 bg-slate-400 rounded-full animate-bounce [animation-delay:0.4s]"></span>
                </div>
            `;
            chatHistory.appendChild(loadingDiv);
            chatHistory.scrollTop = chatHistory.scrollHeight;

            try {
                const response = await fetch('/api/chat.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        prompt
                    })
                });

                const data = await response.json();

                loadingDiv.remove();

                if (data.error) {
                    appendMessage("Error: " + (typeof data.error === 'object' ? JSON.stringify(data.error) : data.error));
                } else if (data.text) {
                    appendMessage(data.text);
                }
            } catch (err) {
                loadingDiv.remove();
                appendMessage("Failed to connect to server. Please try again.");
                console.error(err);
            } finally {
                sendBtn.disabled = false;
            }
        });

        // Allow Enter key to submit (Shift+Enter for new line)
        userInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                chatForm.dispatchEvent(new Event('submit'));
            }
        });
    </script>
</body>

</html>