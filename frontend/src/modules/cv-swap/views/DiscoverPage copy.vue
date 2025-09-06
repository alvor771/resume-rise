<template>
  <div
    class="min-h-screen bg-gray-900 text-gray-100 py-12 px-4 sm:px-6 lg:px-8"
  >
    <div class="max-w-7xl mx-auto">
      <!-- Header -->
      <div class="text-center mb-12">
        <h1
          class="text-4xl font-extrabold tracking-tight text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-purple-300 sm:text-5xl"
        >
          Discover Talent
        </h1>
        <p class="mt-3 max-w-2xl mx-auto text-xl text-gray-300">
          Swipe to find your perfect career match
        </p>
      </div>

      <div class="relative h-[600px] max-w-xl mx-auto">
        <!-- Empty State -->
        <div
          v-if="profiles.length === 0"
          class="text-center p-12 bg-gray-800/50 backdrop-blur-sm rounded-2xl border border-gray-700/50"
        >
          <div
            class="mx-auto flex items-center justify-center h-24 w-24 rounded-full bg-purple-900/30 mb-6"
          >
            <UserGroupIcon class="h-12 w-12 text-purple-400" />
          </div>
          <h3 class="text-2xl font-bold text-white mb-2">No more profiles</h3>
          <p class="text-gray-400 mb-6 max-w-md mx-auto">
            Check back later for new candidates or adjust your search filters
          </p>
          <button
            @click="loadMoreProfiles"
            class="px-6 py-3 bg-gradient-to-r from-purple-600 to-purple-700 text-white font-medium rounded-lg hover:opacity-90 transition-opacity flex items-center mx-auto"
          >
            <RefreshIcon class="h-5 w-5 mr-2" />
            Refresh Profiles
          </button>
        </div>

        <!-- Profile Cards -->
        <div
          v-for="(profile, index) in visibleProfiles"
          :key="profile.id"
          class="absolute inset-0 bg-gray-800/80 backdrop-blur-sm rounded-2xl overflow-hidden transition-all duration-300 border border-gray-700/50 flex flex-col"
          :style="getCardStyle(index)"
        >
          <!-- Profile Image -->
          <div class="relative h-80 overflow-hidden">
            <img
              :src="profile.image"
              :alt="profile.name"
              class="w-full h-full object-cover"
            />
            <div
              class="absolute inset-0 bg-gradient-to-t from-gray-900/80 to-transparent"
            ></div>
            <div class="absolute bottom-0 left-0 right-0 p-6">
              <div class="flex items-center">
                <div class="flex-1">
                  <h2 class="text-2xl font-bold text-white">
                    {{ profile.name }}, {{ profile.age }}
                  </h2>
                  <p class="text-purple-300">{{ profile.title }}</p>
                </div>
                <span
                  class="px-3 py-1 bg-purple-900/50 text-purple-300 text-sm rounded-full border border-purple-800"
                >
                  {{ profile.distance }} km
                </span>
              </div>
            </div>
          </div>

          <!-- Profile Details -->
          <div class="p-6 flex-1 flex flex-col">
            <div class="flex items-center text-sm text-gray-400 mb-4">
              <MapPinIcon class="h-4 w-4 mr-1 text-purple-400" />
              <span>{{ profile.location }}</span>
              <span class="mx-2">•</span>
              <BriefcaseIcon class="h-4 w-4 mr-1 text-purple-400" />
              <span>{{ profile.experience }} years exp</span>
            </div>

            <p class="text-gray-300 mb-6">{{ profile.bio }}</p>

            <div class="mt-auto">
              <h4 class="text-sm font-medium text-gray-400 mb-2">SKILLS</h4>
              <div class="flex flex-wrap gap-2">
                <span
                  v-for="(skill, i) in profile.skills"
                  :key="i"
                  class="text-xs bg-purple-900/50 text-purple-300 px-3 py-1.5 rounded-full border border-purple-800/50"
                >
                  {{ skill }}
                </span>
              </div>
            </div>
          </div>
        </div>

        <!-- Swipe Buttons -->
        <div class="flex justify-center gap-8 mt-12">
          <button
            @click="swipe('left')"
            class="p-5 bg-gray-800 hover:bg-gray-700 rounded-full shadow-lg text-red-400 hover:text-red-300 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
            :disabled="profiles.length === 0"
            :class="{ 'transform hover:scale-110': profiles.length > 0 }"
          >
            <XMarkIcon class="w-10 h-10" />
          </button>

          <button
            @click="swipe('right')"
            class="p-5 bg-gradient-to-br from-purple-600 to-purple-700 rounded-full shadow-lg text-white hover:opacity-90 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
            :disabled="profiles.length === 0"
            :class="{ 'transform hover:scale-110': profiles.length > 0 }"
          >
            <HeartIcon class="w-10 h-10" />
          </button>
        </div>

        <!-- Match Indicator -->
        <div
          v-if="showMatch"
          class="fixed inset-0 bg-black/80 flex items-center justify-center z-50"
        >
          <div class="bg-gray-800 p-8 rounded-2xl text-center max-w-sm">
            <div
              class="w-24 h-24 bg-gradient-to-br from-purple-600 to-pink-500 rounded-full flex items-center justify-center mx-auto mb-6"
            >
              <HeartIcon class="h-12 w-12 text-white" />
            </div>
            <h3 class="text-2xl font-bold text-white mb-2">It's a Match!</h3>
            <p class="text-gray-300 mb-6">
              You and {{ matchName }} have liked each other
            </p>
            <button
              @click="showMatch = false"
              class="w-full py-3 bg-purple-600 text-white rounded-lg font-medium hover:bg-purple-700 transition-colors"
            >
              Send Message
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

</template>

<script>
import { ref, computed, onMounted } from "vue";
// import {
//   XMarkIcon,
//   HeartIcon,
//   UserGroupIcon,
//   MapPinIcon,
//   BriefcaseIcon,
//   ArrowPathIcon as RefreshIcon,
// } from "@heroicons/vue/24/outline";

export default {
  name: "DiscoverPage",
  setup() {
    const showMatch = ref(false);
    const matchName = ref("");

    const profiles = ref([
      {
        id: 1,
        name: "Alex Johnson",
        age: 28,
        title: "Senior UX Designer",
        location: "San Francisco, CA",
        distance: 5.2,
        experience: 6,
        bio: "Passionate about creating intuitive user experiences and solving complex design challenges. Love working in collaborative environments where design thinking is valued.",
        skills: [
          "UI/UX Design",
          "Figma",
          "User Research",
          "Prototyping",
          "Design Systems",
        ],
        image:
          "https://images.unsplash.com/photo-1560250097-0b93528c311a?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=687&q=80",
      },
      {
        id: 2,
        name: "Sarah Williams",
        age: 32,
        title: "Product Manager",
        location: "New York, NY",
        distance: 3.7,
        experience: 8,
        bio: "Strategic product leader with a track record of bringing successful digital products to market. Focused on user-centered design and data-driven decision making.",
        skills: [
          "Product Strategy",
          "Agile",
          "User Stories",
          "Roadmapping",
          "Market Research",
        ],
        image:
          "https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=688&q=80",
      },
      {
        id: 3,
        name: "Michael Chen",
        age: 29,
        title: "Full Stack Developer",
        location: "Seattle, WA",
        distance: 8.1,
        experience: 5,
        bio: "Versatile developer with expertise in both frontend and backend technologies. Enjoy building scalable applications with clean, maintainable code.",
        skills: ["JavaScript", "React", "Node.js", "TypeScript", "AWS"],
        image:
          "https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=687&q=80",
      },
    ]);

    const visibleProfiles = computed(() => profiles.value.slice(0, 3));

    const swipe = (direction) => {
      if (profiles.value.length === 0) return;

      const currentProfile = profiles.value.shift();
      console.log(
        `${direction === "right" ? "Liked" : "Passed on"}:`,
        currentProfile.name
      );

      if (direction === "right" && Math.random() > 0.7) {
        matchName.value = currentProfile.name.split(" ")[0];
        showMatch.value = true;
      }

      // Haptic feedback on mobile
      if (navigator.vibrate) {
        navigator.vibrate(50);
      }
    };

    const loadMoreProfiles = () => {
      profiles.value.push({
        id: Date.now(),
        name: "New Profile",
        age: 30,
        title: "Job Title",
        location: "Location",
        skills: ["Skill 1", "Skill 2"],
        image: "https://via.placeholder.com/150",
      });
    };

    const getCardStyle = (index) => ({
      zIndex: 10 - index,
      transform: `translateY(${index * 15}px) scale(${1 - index * 0.04})`,
      opacity: 1 - index * 0.2,
      boxShadow:
        "0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)",
    });

    // Add keyboard navigation
    onMounted(() => {
      const handleKeyDown = (e) => {
        if (e.key === "ArrowLeft") {
          swipe("left");
        } else if (e.key === "ArrowRight") {
          swipe("right");
        }
      };

      window.addEventListener("keydown", handleKeyDown);
      return () => window.removeEventListener("keydown", handleKeyDown);
    });

    return {
      profiles,
      visibleProfiles,
      swipe,
      loadMoreProfiles,
      getCardStyle,
      showMatch,
      matchName,
    };
  },
};
</script>
