<template>
  <div class="flex h-screen bg-gray-100">
    <!-- Sidebar -->
    <div class="w-1/3 border-r bg-white">
      <div class="p-4 border-b">
        <h1 class="text-xl font-bold mb-4">Messages</h1>
        <input
          v-model="searchQuery"
          type="text"
          placeholder="Search conversations..."
          class="w-full p-2 border rounded"
        >
      </div>
      
      <!-- Conversation List -->
      <div class="overflow-y-auto h-[calc(100vh-120px)]">
        <div 
          v-for="convo in filteredConversations" 
          :key="convo.id"
          @click="selectConversation(convo.id)"
          class="p-4 border-b hover:bg-gray-50 cursor-pointer"
          :class="{ 'bg-blue-50': selectedId === convo.id }"
        >
          <div class="flex items-center">
            <img 
              :src="convo.avatar" 
              class="w-10 h-10 rounded-full mr-3"
            >
            <div class="flex-1">
              <div class="flex justify-between">
                <h3 class="font-medium">{{ convo.name }}</h3>
                <span class="text-xs text-gray-500">{{ convo.time }}</span>
              </div>
              <p class="text-sm text-gray-600 truncate">{{ convo.lastMessage }}</p>
            </div>
            <span 
              v-if="convo.unread"
              class="bg-blue-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center"
            >
              {{ convo.unread }}
            </span>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Chat Area -->
    <div class="flex-1 flex flex-col">
      <!-- Chat Header -->
      <div v-if="currentChat" class="p-4 border-b bg-white flex items-center">
        <img 
          :src="currentChat.avatar" 
          class="w-10 h-10 rounded-full mr-3"
        >
        <div>
          <h2 class="font-semibold">{{ currentChat.name }}</h2>
          <p class="text-xs text-gray-500">
            {{ currentChat.online ? 'Online' : 'Last seen recently' }}
          </p>
        </div>
      </div>
      
      <!-- Messages -->
      <div 
        v-if="currentChat" 
        class="flex-1 p-4 overflow-y-auto"
        ref="messagesContainer"
      >
        <div v-for="(msg, index) in currentChat.messages" :key="index" class="mb-4">
          <div 
            :class="{
              'flex justify-end': msg.sender === 'me',
              'flex': msg.sender !== 'me'
            }"
          >
            <div 
              :class="{
                'bg-blue-500 text-white rounded-l-lg rounded-br-lg': msg.sender === 'me',
                'bg-gray-200 text-gray-800 rounded-r-lg rounded-bl-lg': msg.sender !== 'me'
              }"
              class="px-4 py-2 max-w-xs lg:max-w-md"
            >
              {{ msg.text }}
              <div class="text-xs mt-1 opacity-70 text-right">
                {{ formatTime(msg.time) }}
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Message Input -->
      <div v-if="currentChat" class="p-4 border-t bg-white">
        <form @submit.prevent="sendMessage" class="flex gap-2">
          <input
            v-model="newMessage"
            type="text"
            placeholder="Type a message..."
            class="flex-1 p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
          >
          <button 
            type="submit"
            class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600"
          >
            Send
          </button>
        </form>
      </div>
      
      <!-- Empty State -->
      <div v-else class="flex-1 flex items-center justify-center text-gray-500">
        <div class="text-center">
          <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
          </svg>
          <h3 class="text-lg font-medium">Select a conversation</h3>
          <p class="mt-1">Choose an existing chat or start a new one</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed, watch, nextTick } from 'vue';

export default {
  name: 'MessagesPage',
  setup() {
    const searchQuery = ref('');
    const newMessage = ref('');
    const selectedId = ref(1);
    const messagesContainer = ref(null);
    
    const conversations = [
      {
        id: 1,
        name: 'Alex Johnson',
        avatar: 'https://randomuser.me/api/portraits/men/32.jpg',
        lastMessage: 'Looking forward to our interview tomorrow!',
        time: '10:30 AM',
        unread: 2,
        online: true,
        messages: [
          { text: 'Hi there!', sender: 'them', time: '2023-04-01T10:00:00' },
          { text: 'Hello! How are you?', sender: 'me', time: '2023-04-01T10:02:00' },
          { text: 'Looking forward to our interview tomorrow!', sender: 'them', time: '2023-04-01T10:30:00' }
        ]
      },
      {
        id: 2,
        name: 'Sarah Williams',
        avatar: 'https://randomuser.me/api/portraits/women/44.jpg',
        lastMessage: 'The project deadline has been extended',
        time: 'Yesterday',
        unread: 0,
        online: false,
        messages: [
          { text: 'Hi Sarah, any updates on the project?', sender: 'me', time: '2023-04-02T09:00:00' },
          { text: 'Yes, the deadline has been extended to next Friday', sender: 'them', time: '2023-04-02T09:30:00' },
          { text: 'Great news! Thanks for letting me know.', sender: 'me', time: '2023-04-02T10:00:00' }
        ]
      },
      {
        id: 3,
        name: 'Tech Recruiter',
        avatar: 'https://randomuser.me/api/portraits/men/1.jpg',
        lastMessage: 'We would like to schedule an interview',
        time: 'Mon',
        unread: 0,
        online: true,
        messages: [
          { text: 'Hello! We reviewed your application and would like to schedule an interview', sender: 'them', time: '2023-03-30T14:00:00' },
          { text: 'Thank you! I would be happy to schedule an interview.', sender: 'me', time: '2023-03-30T15:30:00' }
        ]
      }
    ];

    const filteredConversations = computed(() => {
      if (!searchQuery.value) return conversations;
      const query = searchQuery.value.toLowerCase();
      return conversations.filter(convo => 
        convo.name.toLowerCase().includes(query) ||
        convo.lastMessage.toLowerCase().includes(query)
      );
    });

    const currentChat = computed(() => {
      return conversations.find(convo => convo.id === selectedId.value);
    });

    const selectConversation = (id) => {
      selectedId.value = id;
      // Mark as read
      const convo = conversations.find(c => c.id === id);
      if (convo) convo.unread = 0;
    };

    const sendMessage = () => {
      if (!newMessage.value.trim() || !currentChat.value) return;
      
      const message = {
        text: newMessage.value,
        sender: 'me',
        time: new Date().toISOString()
      };
      
      currentChat.value.messages.push(message);
      currentChat.value.lastMessage = message.text;
      currentChat.value.time = 'Just now';
      
      newMessage.value = '';
      
      // Auto-reply (simulated)
      setTimeout(() => {
        const replies = [
          'Thanks for your message!',
          'I will get back to you soon.',
          'That sounds great!',
          'Let me check my schedule and get back to you.',
          'Thanks for the update!'
        ];
        const reply = {
          text: replies[Math.floor(Math.random() * replies.length)],
          sender: 'them',
          time: new Date().toISOString()
        };
        currentChat.value.messages.push(reply);
        currentChat.value.lastMessage = reply.text;
        currentChat.value.time = 'Just now';
      }, 1000 + Math.random() * 2000);
    };

    const formatTime = (dateString) => {
      const date = new Date(dateString);
      return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    };

    // Auto-scroll to bottom when messages change
    watch(() => currentChat.value?.messages?.length, async () => {
      await nextTick();
      if (messagesContainer.value) {
        messagesContainer.value.scrollTop = messagesContainer.value.scrollHeight;
      }
    }, { immediate: true });

    return {
      searchQuery,
      newMessage,
      selectedId,
      currentChat,
      messagesContainer,
      filteredConversations,
      selectConversation,
      sendMessage,
      formatTime
    };
  }
};
</script>
