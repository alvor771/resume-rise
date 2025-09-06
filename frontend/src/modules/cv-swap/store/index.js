import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
// import { useAuthStore } from '@/stores/auth';

// Mock data - replace with actual API calls
const mockCompanies = [
  {
    id: 1,
    name: 'TechCorp',
    logo: 'https://via.placeholder.com/150',
    industry: 'Information Technology',
    location: 'San Francisco, CA',
    description: 'Leading technology company specializing in AI solutions.',
    culture: ['Innovation', 'Work-life balance', 'Remote-friendly'],
    matchPercentage: 87,
    about: 'TechCorp is a leading technology company that specializes in artificial intelligence and machine learning solutions for businesses worldwide. We are committed to innovation and excellence in everything we do.',
    website: 'https://techcorp.example.com',
    founded: '2015',
    size: '250',
    specialties: ['AI', 'Machine Learning', 'Cloud Computing', 'Big Data'],
    openPositions: [
      {
        id: 1,
        title: 'Senior Frontend Developer',
        type: 'Full-time',
        location: 'Remote',
        salary: '$120,000 - $150,000',
        description: 'We are looking for an experienced Frontend Developer to join our team.'
      }
    ]
  },
  // Add more mock companies as needed
];

const mockCandidates = [
  {
    id: 1,
    name: 'Alex Johnson',
    title: 'Senior Frontend Developer',
    photo: 'https://randomuser.me/api/portraits/men/32.jpg',
    experience: '5+ years',
    skills: ['Vue.js', 'React', 'TypeScript', 'Node.js', 'JavaScript', 'HTML5', 'CSS3', 'Git'],
    matchPercentage: 92,
    about: 'Passionate frontend developer with 5+ years of experience building responsive and accessible web applications.',
    location: 'San Francisco, CA',
    education: [
      {
        degree: 'B.Sc. in Computer Science',
        institution: 'Stanford University',
        year: '2018'
      }
    ],
    workExperience: [
      {
        title: 'Senior Frontend Developer',
        company: 'TechCorp Inc.',
        duration: '2020 - Present',
        description: 'Leading a team of frontend developers to build and maintain customer-facing applications.'
      },
      {
        title: 'Frontend Developer',
        company: 'WebSolutions Ltd.',
        duration: '2018 - 2020',
        description: 'Developed and maintained multiple client websites using Vue.js and React.'
      }
    ]
  },
  // Add more mock candidates as needed
];

// Mock templates data
const mockTemplates = [
  {
    id: 1,
    name: 'Professional',
    category: 'Professional',
    thumbnail: 'https://via.placeholder.com/300x400?text=Professional',
    price: 29.99,
    isPremium: true,
    tags: ['Minimalist', 'Clean', 'Modern']
  },
  {
    id: 2,
    name: 'Creative',
    category: 'Creative',
    thumbnail: 'https://via.placeholder.com/300x400?text=Creative',
    price: 0,
    isPremium: false,
    tags: ['Colorful', 'Modern', 'Creative']
  },
  {
    id: 3,
    name: 'Executive',
    category: 'Professional',
    thumbnail: 'https://via.placeholder.com/300x400?text=Executive',
    price: 39.99,
    isPremium: true,
    tags: ['Elegant', 'Professional', 'Corporate']
  },
  {
    id: 4,
    name: 'Minimal',
    category: 'Minimalist',
    thumbnail: 'https://via.placeholder.com/300x400?text=Minimal',
    price: 19.99,
    isPremium: true,
    tags: ['Simple', 'Clean', 'Minimalist']
  }
];

// Mock messages data
const mockMessages = [
  {
    id: 1,
    senderId: 1,
    receiverId: 2,
    content: 'Hi there! I came across your profile and was impressed by your experience with Vue.js',
    timestamp: '2023-04-01T10:00:00',
    read: true
  },
  {
    id: 2,
    senderId: 2,
    receiverId: 1,
    content: 'Hello! Thank you for reaching out. Yes, I have extensive experience with Vue.js',
    timestamp: '2023-04-01T10:05:00',
    read: true
  },
  {
    id: 3,
    senderId: 1,
    receiverId: 2,
    content: 'That\'s great! Would you be available for an interview next week?',
    timestamp: '2023-04-01T10:10:00',
    read: false
  }
];

export const useCvSwapStore = defineStore('cv-swap', () => {
//   const authStore = useAuthStore();
  
  // State
  const companies = ref([...mockCompanies]);
  const candidates = ref([...mockCandidates]);
  const matches = ref([]);
  const likedItems = ref([]);
  const currentUserProfile = ref(null);
  const isLoading = ref(false);
  const error = ref(null);
  const templates = ref([...mockTemplates]);
  const messages = ref([...mockMessages]);
  const activeConversationId = ref(null);
  const notifications = ref([]);

  // Getters
  const getCompanyById = computed(() => (id) => {
    return companies.value.find(company => company.id === parseInt(id));
  });

  const getCandidateById = computed(() => (id) => {
    return candidates.value.find(candidate => candidate.id === parseInt(id));
  });

  const getTemplateById = computed(() => (id) => {
    return templates.value.find(template => template.id === parseInt(id));
  });

  const getConversations = computed(() => {
    // Group messages by conversation
    const conversations = [];
    const participants = new Set();
    
    if (!currentUserProfile.value) return [];
    
    messages.value.forEach(msg => {
      const otherUserId = msg.senderId === currentUserProfile.value.id 
        ? msg.receiverId 
        : msg.senderId;
      
      if (!participants.has(otherUserId)) {
        participants.add(otherUserId);
        const user = candidates.value.find(u => u.id === otherUserId) || 
                    companies.value.find(u => u.id === otherUserId);
        
        if (user) {
          const conversationMsgs = messages.value
            .filter(m => 
              (m.senderId === otherUserId && m.receiverId === currentUserProfile.value.id) ||
              (m.senderId === currentUserProfile.value.id && m.receiverId === otherUserId)
            )
            .sort((a, b) => new Date(b.timestamp) - new Date(a.timestamp));
          
          if (conversationMsgs.length > 0) {
            conversations.push({
              id: otherUserId,
              user,
              lastMessage: conversationMsgs[0],
              unreadCount: conversationMsgs.filter(m => 
                !m.read && m.senderId === otherUserId
              ).length
            });
          }
        }
      }
    });
    
    return conversations.sort((a, b) => {
      if (!a.lastMessage || !b.lastMessage) return 0;
      return new Date(b.lastMessage.timestamp) - new Date(a.lastMessage.timestamp);
    });
  });

  const getMatchById = computed(() => (id) => {
    return matches.value.find(match => match.id === parseInt(id));
  });

  // Actions
  const fetchCompanies = async () => {
    try {
      isLoading.value = true;
      // TODO: Replace with actual API call
      // const response = await api.get('/cv-swap/companies');
      // companies.value = response.data;
    } catch (err) {
      error.value = 'Failed to fetch companies';
      console.error('Error fetching companies:', err);
    } finally {
      isLoading.value = false;
    }
  };

  const fetchCandidates = async () => {
    try {
      isLoading.value = true;
      // TODO: Replace with actual API call
      // const response = await api.get('/cv-swap/candidates');
      // candidates.value = response.data;
    } catch (err) {
      error.value = 'Failed to fetch candidates';
      console.error('Error fetching candidates:', err);
    } finally {
      isLoading.value = false;
    }
  };

  const fetchMatches = async () => {
    try {
      isLoading.value = true;
      // TODO: Replace with actual API call
      // const response = await api.get('/cv-swap/matches');
      // matches.value = response.data;
    } catch (err) {
      error.value = 'Failed to fetch matches';
      console.error('Error fetching matches:', err);
    } finally {
      isLoading.value = false;
    }
  };

  const likeCompany = async (companyId) => {
    try {
        likedItems.value.push({
            id: companyId,
            type: 'company',
            timestamp: new Date().toISOString()
          });
    
    } catch (err) {
      error.value = 'Failed to like company';
      console.error('Error liking company:', err);
    }
  };

  const likeCandidate = async (candidateId) => {
    try {
        likedItems.value.push({
            id: candidateId,
            type: 'candidate',
            timestamp: new Date().toISOString()
        });
    } catch (err) {
      error.value = 'Failed to like candidate';
      console.error('Error liking candidate:', err);
    }
  };

  const likeItem = async (item, type) => {
    try {
      // Add to liked items
      likedItems.value.push({
        id: item.id,
        type, // 'company' or 'candidate'
        timestamp: new Date().toISOString()
      });

      // Check for match
      // TODO: Implement actual match logic with API
      
      return { success: true };
    } catch (err) {
      error.value = 'Failed to process like';
      console.error('Error processing like:', err);
      return { success: false, error: err.message };
    }
  };

  const passItem = (itemId, type) => {
    // Simply remove from the current list
    if (type === 'company') {
      companies.value = companies.value.filter(company => company.id !== itemId);
    } else {
      candidates.value = candidates.value.filter(candidate => candidate.id !== itemId);
    }
  };

  const calculateMatchPercentage = (userProfile, item) => {
    // Simple matching algorithm - can be enhanced
    let score = 0;
    const maxScore = 10; // Adjust based on your criteria
    
    // Example: Compare skills/interests
    if (userProfile.skills && item.skills) {
      const commonSkills = userProfile.skills.filter(skill => 
        item.skills.includes(skill)
      );
      score += (commonSkills.length / Math.max(userProfile.skills.length, 1)) * 5;
    }
    
    // Example: Compare location preferences
    if (userProfile.location === item.location) {
      score += 2;
    }
    
    // Example: Compare work preferences
    if (userProfile.workType === item.workType) {
      score += 3;
    }
    
    // Ensure score is between 0 and 100
    return Math.min(Math.round((score / maxScore) * 100), 100);
  };

  const sendMessage = (content, receiverId) => {
    const newMessage = {
      id: messages.value.length + 1,
      senderId: currentUserProfile.value.id,
      receiverId,
      content,
      timestamp: new Date().toISOString(),
      read: false
    };
    
    messages.value.push(newMessage);
    return newMessage;
  };

  // Initialize store
  const init = async () => {
    try {
      isLoading.value = true;
      // Fetch initial data here
      await Promise.all([
        fetchCompanies(),
        fetchCandidates(),
        fetchMatches(),
      ]);
    } catch (err) {
      error.value = err.message;
    } finally {
      isLoading.value = false;
    }
  };

  return {
    // State
    companies,
    candidates,
    matches,
    likedItems,
    currentUserProfile,
    isLoading,
    error,
    templates,
    messages,
    activeConversationId,
    notifications,
    
    // Getters
    getCompanyById,
    getCandidateById,
    getMatchById,
    getTemplateById,
    getConversations,
    
    // Actions
    likeCompany,
    likeCandidate,
    fetchCompanies,
    fetchCandidates,
    fetchMatches,
    likeItem,
    passItem,
    calculateMatchPercentage,
    sendMessage,
    init
  };
});
