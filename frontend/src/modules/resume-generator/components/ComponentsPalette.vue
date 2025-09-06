<template>
  <div class="p-3 border rounded bg-white space-y-3">
    <h3 class="text-sm font-semibold text-gray-700">Components Palette</h3>

    <div class="space-y-2">
      <div class="text-xs uppercase text-gray-500">Text</div>
      <div class="grid grid-cols-2 gap-2">
        <button class="btn btn-outline flex items-center justify-center gap-1.5" @click="addHeading">
          <svg class="h-4 w-4 text-gray-500" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M19.5 14.25V11.625C19.5 9.76104 17.989 8.25 16.125 8.25H14.625C14.0037 8.25 13.5 7.74632 13.5 7.125V5.625C13.5 3.76104 11.989 2.25 10.125 2.25H8.25M8.25 15H15.75M8.25 18H12M10.5 2.25H5.625C5.00368 2.25 4.5 2.75368 4.5 3.375V20.625C4.5 21.2463 5.00368 21.75 5.625 21.75H18.375C18.9963 21.75 19.5 21.2463 19.5 20.625V11.25C19.5 6.27944 15.4706 2.25 10.5 2.25Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          Heading
        </button>
        <button class="btn btn-outline flex items-center justify-center gap-1.5" @click="addSubheading">
          <svg class="h-4 w-4 text-gray-500" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M19.5 14.25V11.625C19.5 9.76104 17.989 8.25 16.125 8.25H14.625C14.0037 8.25 13.5 7.74632 13.5 7.125V5.625C13.5 3.76104 11.989 2.25 10.125 2.25H8.25M10.5 2.25H5.625C5.00368 2.25 4.5 2.75368 4.5 3.375V20.625C4.5 21.2463 5.00368 21.75 5.625 21.75H18.375C18.9963 21.75 19.5 21.2463 19.5 20.625V11.25C19.5 6.27944 15.4706 2.25 10.5 2.25Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          Subheading
        </button>
        <button class="btn btn-outline flex items-center justify-center gap-1.5" @click="addParagraph">
          <svg class="h-4 w-4 text-gray-500" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M3.75 6.75H20.25M3.75 12H20.25M3.75 17.25H20.25" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          Paragraph
        </button>
        <button class="btn btn-outline flex items-center justify-center gap-1.5" @click="addSectionTitle">
          <svg class="h-4 w-4 text-gray-500" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M17.5933 3.32241C18.6939 3.45014 19.5 4.399 19.5 5.50699V21L12 17.25L4.5 21V5.50699C4.5 4.399 5.30608 3.45014 6.40668 3.32241C8.24156 3.10947 10.108 3 12 3C13.892 3 15.7584 3.10947 17.5933 3.32241Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          Section Title
        </button>
      </div>
    </div>

    <div class="space-y-2">
      <div class="text-xs uppercase text-gray-500">Layout</div>
      <div class="grid grid-cols-2 gap-2">
        <button class="btn btn-outline flex items-center justify-center gap-1.5" @click="addDivider">
          <svg class="h-4 w-4 text-gray-500" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M5 12H19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          Divider
        </button>
        <button class="btn btn-outline flex items-center justify-center gap-1.5" @click="addSidebarBlock">
          <svg class="h-4 w-4 text-gray-500" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M9 4.5V19.5M15 4.5V19.5M4.125 19.5H19.875C20.4963 19.5 21 18.9963 21 18.375V5.625C21 5.00368 20.4963 4.5 19.875 4.5H4.125C3.50368 4.5 3 5.00368 3 5.625V18.375C3 18.9963 3.50368 19.5 4.125 19.5Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          Sidebar Block
        </button>
        <button class="btn btn-outline flex items-center justify-center gap-1.5" @click="addTwoColumns">
          <svg class="h-4 w-4 text-gray-500" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M2.25 7.125C2.25 6.50368 2.75368 6 3.375 6H9.375C9.99632 6 10.5 6.50368 10.5 7.125V10.875C10.5 11.4963 9.99632 12 9.375 12H3.375C2.75368 12 2.25 11.4963 2.25 10.875V7.125Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M14.25 8.625C14.25 8.00368 14.7537 7.5 15.375 7.5H20.625C21.2463 7.5 21.75 8.00368 21.75 8.625V16.875C21.75 17.4963 21.2463 18 20.625 18H15.375C14.7537 18 14.25 17.4963 14.25 16.875V8.625Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M3.75 16.125C3.75 15.5037 4.25368 15 4.875 15H10.125C10.7463 15 11.25 15.5037 11.25 16.125V18.375C11.25 18.9963 10.7463 19.5 10.125 19.5H4.875C4.25368 19.5 3.75 18.9963 3.75 18.375V16.125Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          Two Columns
        </button>
      </div>
    </div>

    <div class="space-y-2">
      <div class="text-xs uppercase text-gray-500">Media</div>
      <div class="grid grid-cols-2 gap-2">
        <button class="btn btn-outline flex items-center justify-center gap-1.5" @click="addAvatar">
          <svg class="h-4 w-4 text-gray-500" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M17.9815 18.7248C16.6121 16.9175 14.4424 15.75 12 15.75C9.55761 15.75 7.38789 16.9175 6.01846 18.7248M17.9815 18.7248C19.8335 17.0763 21 14.6744 21 12C21 7.02944 16.9706 3 12 3C7.02944 3 3 7.02944 3 12C3 14.6744 4.1665 17.0763 6.01846 18.7248M17.9815 18.7248C16.3915 20.1401 14.2962 21 12 21C9.70383 21 7.60851 20.1401 6.01846 18.7248M15 9.75C15 11.4069 13.6569 12.75 12 12.75C10.3431 12.75 9 11.4069 9 9.75C9 8.09315 10.3431 6.75 12 6.75C13.6569 6.75 15 8.09315 15 9.75Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          Avatar
        </button>
      </div>
    </div>

    <div class="space-y-2">
      <div class="text-xs uppercase text-gray-500">Blocks</div>
      <div class="grid grid-cols-2 gap-2">
        <button class="btn btn-outline flex items-center justify-center gap-1.5" @click="addExperienceCard">
          <svg class="h-4 w-4 text-gray-500" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M20.25 14.1499V18.4C20.25 19.4944 19.4631 20.4359 18.3782 20.58C16.2915 20.857 14.1624 21 12 21C9.83757 21 7.70854 20.857 5.62185 20.58C4.5369 20.4359 3.75 19.4944 3.75 18.4V14.1499M20.25 14.1499C20.7219 13.7476 21 13.1389 21 12.4889V8.70569C21 7.62475 20.2321 6.69082 19.1631 6.53086C18.0377 6.36247 16.8995 6.23315 15.75 6.14432M20.25 14.1499C20.0564 14.315 19.8302 14.4453 19.5771 14.5294C17.1953 15.3212 14.6477 15.75 12 15.75C8.8381 15.75 5.86662 15.6847 3.2843 14.2529M3.75 14.1499C3.27808 13.7476 3 13.1389 3 12.4889V8.70569C3 7.62475 3.7679 6.69082 4.83694 6.53086C5.96233 6.36247 7.10049 6.23315 8.25 6.14432M15.75 6.14432V5.25C15.75 4.00736 14.7426 3 13.5 3H10.5C9.25736 3 8.25 4.00736 8.25 5.25V6.14432M15.75 6.14432C14.5126 6.0487 13.262 6 12 6C10.738 6 9.48744 6.0487 8.25 6.14432M12 12.75H12.0075V12.7575H12V12.75Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          Experience
        </button>
        <button class="btn btn-outline flex items-center justify-center gap-1.5" @click="addEducationCard">
          <svg class="h-4 w-4 text-gray-500" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M4.25933 10.1466C3.98688 12.2307 3.82139 14.3483 3.76853 16.494C6.66451 17.703 9.41893 19.1835 12 20.9036C14.5811 19.1835 17.3355 17.703 20.2315 16.494C20.1786 14.3484 20.0131 12.2307 19.7407 10.1467M4.25933 10.1466C3.38362 9.8523 2.49729 9.58107 1.60107 9.3337C4.84646 7.05887 8.32741 5.0972 12 3.49255C15.6727 5.0972 19.1536 7.05888 22.399 9.33371C21.5028 9.58109 20.6164 9.85233 19.7407 10.1467M4.25933 10.1466C6.94656 11.0499 9.5338 12.1709 12.0001 13.4886C14.4663 12.1709 17.0535 11.0499 19.7407 10.1467M6.75 15C7.16421 15 7.5 14.6642 7.5 14.25C7.5 13.8358 7.16421 13.5 6.75 13.5C6.33579 13.5 6 13.8358 6 14.25C6 14.6642 6.33579 15 6.75 15ZM6.75 15V11.3245C8.44147 10.2735 10.1936 9.31094 12 8.44329M4.99264 19.9926C6.16421 18.8211 6.75 17.2855 6.75 15.75V14.25" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          Education
        </button>
        <button class="btn btn-outline flex items-center justify-center gap-1.5" @click="addSkillsChips">
          <svg class="h-4 w-4 text-gray-500" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M11.4806 3.4987C11.6728 3.03673 12.3272 3.03673 12.5193 3.4987L14.6453 8.61016C14.7263 8.80492 14.9095 8.93799 15.1197 8.95485L20.638 9.39724C21.1367 9.43722 21.339 10.0596 20.959 10.3851L16.7546 13.9866C16.5945 14.1238 16.5245 14.3391 16.5734 14.5443L17.8579 19.9292C17.974 20.4159 17.4446 20.8005 17.0176 20.5397L12.2932 17.6541C12.1132 17.5441 11.8868 17.5441 11.7068 17.6541L6.98238 20.5397C6.55539 20.8005 6.02594 20.4159 6.14203 19.9292L7.42652 14.5443C7.47546 14.3391 7.4055 14.1238 7.24531 13.9866L3.04099 10.3851C2.661 10.0596 2.86323 9.43722 3.36197 9.39724L8.88022 8.95485C9.09048 8.93799 9.27363 8.80492 9.35464 8.61016L11.4806 3.4987Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          Skills Row
        </button>
        <button class="btn btn-outline flex items-center justify-center gap-1.5" @click="addContactRow">
          <svg class="h-4 w-4 text-gray-500" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M21.75 6.75V17.25C21.75 18.4926 20.7426 19.5 19.5 19.5H4.5C3.25736 19.5 2.25 18.4926 2.25 17.25V6.75M21.75 6.75C21.75 5.50736 20.7426 4.5 19.5 4.5H4.5C3.25736 4.5 2.25 5.50736 2.25 6.75M21.75 6.75V6.99271C21.75 7.77405 21.3447 8.49945 20.6792 8.90894L13.1792 13.5243C12.4561 13.9694 11.5439 13.9694 10.8208 13.5243L3.32078 8.90894C2.65535 8.49945 2.25 7.77405 2.25 6.99271V6.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          Contacts
        </button>
      </div>
    </div>

    <!-- Presets: Headers -->
    <div class="space-y-2">
      <div class="text-xs uppercase text-gray-500">Headers (Presets)</div>
      <div class="grid grid-cols-4 gap-2">
        <button v-for="i in 7" :key="'hdr'+i" class="btn btn-outline flex items-center justify-center gap-1" @click="addHeaderPreset(i)">
          <svg class="h-4 w-4 text-gray-500" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M3 8.25V18C3 19.2426 4.00736 20.25 5.25 20.25H18.75C19.9926 20.25 21 19.2426 21 18V8.25M3 8.25V6C3 4.75736 4.00736 3.75 5.25 3.75H18.75C19.9926 3.75 21 4.75736 21 6V8.25M3 8.25H21M5.25 6H5.2575V6.0075H5.25V6ZM7.5 6H7.5075V6.0075H7.5V6ZM9.75 6H9.7575V6.0075H9.75V6Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          H{{ i }}
        </button>
      </div>
    </div>

    <!-- Presets: Experience -->
    <div class="space-y-2">
      <div class="text-xs uppercase text-gray-500">Experience (Presets)</div>
      <div class="grid grid-cols-4 gap-2">
        <button v-for="i in 7" :key="'exp'+i" class="btn btn-outline flex items-center justify-center gap-1" @click="addExperiencePreset(i)">
          <svg class="h-4 w-4 text-gray-500" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M20.25 14.1499V18.4C20.25 19.4944 19.4631 20.4359 18.3782 20.58C16.2915 20.857 14.1624 21 12 21C9.83757 21 7.70854 20.857 5.62185 20.58C4.5369 20.4359 3.75 19.4944 3.75 18.4V14.1499M20.25 14.1499C20.7219 13.7476 21 13.1389 21 12.4889V8.70569C21 7.62475 20.2321 6.69082 19.1631 6.53086C18.0377 6.36247 16.8995 6.23315 15.75 6.14432M20.25 14.1499C20.0564 14.315 19.8302 14.4453 19.5771 14.5294C17.1953 15.3212 14.6477 15.75 12 15.75C8.8381 15.75 5.86662 15.6847 3.2843 14.2529M3.75 14.1499C3.27808 13.7476 3 13.1389 3 12.4889V8.70569C3 7.62475 3.7679 6.69082 4.83694 6.53086C5.96233 6.36247 7.10049 6.23315 8.25 6.14432M15.75 6.14432V5.25C15.75 4.00736 14.7426 3 13.5 3H10.5C9.25736 3 8.25 4.00736 8.25 5.25V6.14432M15.75 6.14432C14.5126 6.0487 13.262 6 12 6C10.738 6 9.48744 6.0487 8.25 6.14432M12 12.75H12.0075V12.7575H12V12.75Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          E{{ i }}
        </button>
      </div>
    </div>

    <!-- Presets: Education -->
    <div class="space-y-2">
      <div class="text-xs uppercase text-gray-500">Education (Presets)</div>
      <div class="grid grid-cols-4 gap-2">
        <button v-for="i in 7" :key="'edu'+i" class="btn btn-outline flex items-center justify-center gap-1" @click="addEducationPreset(i)">
          <svg class="h-4 w-4 text-gray-500" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M4.25933 10.1466C3.98688 12.2307 3.82139 14.3483 3.76853 16.494C6.66451 17.703 9.41893 19.1835 12 20.9036C14.5811 19.1835 17.3355 17.703 20.2315 16.494C20.1786 14.3484 20.0131 12.2307 19.7407 10.1467M4.25933 10.1466C3.38362 9.8523 2.49729 9.58107 1.60107 9.3337C4.84646 7.05887 8.32741 5.0972 12 3.49255C15.6727 5.0972 19.1536 7.05888 22.399 9.33371C21.5028 9.58109 20.6164 9.85233 19.7407 10.1467M4.25933 10.1466C6.94656 11.0499 9.5338 12.1709 12.0001 13.4886C14.4663 12.1709 17.0535 11.0499 19.7407 10.1467M6.75 15C7.16421 15 7.5 14.6642 7.5 14.25C7.5 13.8358 7.16421 13.5 6.75 13.5C6.33579 13.5 6 13.8358 6 14.25C6 14.6642 6.33579 15 6.75 15ZM6.75 15V11.3245C8.44147 10.2735 10.1936 9.31094 12 8.44329M4.99264 19.9926C6.16421 18.8211 6.75 17.2855 6.75 15.75V14.25" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          Ed{{ i }}
        </button>
      </div>
    </div>

    <!-- Presets: Skills -->
    <div class="space-y-2">
      <div class="text-xs uppercase text-gray-500">Skills (Presets)</div>
      <div class="grid grid-cols-4 gap-2">
        <button v-for="i in 7" :key="'skl'+i" class="btn btn-outline flex items-center justify-center gap-1" @click="addSkillsPreset(i)">
          <svg class="h-4 w-4 text-gray-500" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M11.4806 3.4987C11.6728 3.03673 12.3272 3.03673 12.5193 3.4987L14.6453 8.61016C14.7263 8.80492 14.9095 8.93799 15.1197 8.95485L20.638 9.39724C21.1367 9.43722 21.339 10.0596 20.959 10.3851L16.7546 13.9866C16.5945 14.1238 16.5245 14.3391 16.5734 14.5443L17.8579 19.9292C17.974 20.4159 17.4446 20.8005 17.0176 20.5397L12.2932 17.6541C12.1132 17.5441 11.8868 17.5441 11.7068 17.6541L6.98238 20.5397C6.55539 20.8005 6.02594 20.4159 6.14203 19.9292L7.42652 14.5443C7.47546 14.3391 7.4055 14.1238 7.24531 13.9866L3.04099 10.3851C2.661 10.0596 2.86323 9.43722 3.36197 9.39724L8.88022 8.95485C9.09048 8.93799 9.27363 8.80492 9.35464 8.61016L11.4806 3.4987Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          S{{ i }}
        </button>
      </div>
    </div>

    <!-- Presets: Projects -->
    <div class="space-y-2">
      <div class="text-xs uppercase text-gray-500">Projects (Presets)</div>
      <div class="grid grid-cols-4 gap-2">
        <button v-for="i in 7" :key="'prj'+i" class="btn btn-outline flex items-center justify-center gap-1" @click="addProjectsPreset(i)">
          <svg class="h-4 w-4 text-gray-500" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M17.25 6.75L22.5 12L17.25 17.25M6.75 17.25L1.5 12L6.75 6.75M14.25 3.75L9.75 20.25" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          P{{ i }}
        </button>
      </div>
    </div>

    <!-- Presets: Languages / Certifications / Achievements / Interests / References -->
    <div class="space-y-2">
      <div class="text-xs uppercase text-gray-500">Languages</div>
      <div class="grid grid-cols-3 gap-2">
        <button class="btn btn-outline flex items-center justify-center gap-1" @click="addLanguagesPreset('compact')">
          <svg class="h-4 w-4 text-gray-500" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M12 21C16.1926 21 19.7156 18.1332 20.7157 14.2529M12 21C7.80742 21 4.28442 18.1332 3.2843 14.2529M12 21C14.4853 21 16.5 16.9706 16.5 12C16.5 7.02944 14.4853 3 12 3M12 21C9.51472 21 7.5 16.9706 7.5 12C7.5 7.02944 9.51472 3 12 3M12 3C15.3652 3 18.299 4.84694 19.8431 7.58245M12 3C8.63481 3 5.70099 4.84694 4.15692 7.58245M19.8431 7.58245C17.7397 9.40039 14.9983 10.5 12 10.5C9.00172 10.5 6.26027 9.40039 4.15692 7.58245M19.8431 7.58245C20.5797 8.88743 21 10.3946 21 12C21 12.778 20.9013 13.5329 20.7157 14.2529M20.7157 14.2529C18.1334 15.6847 15.1619 16.5 12 16.5C8.8381 16.5 5.86662 15.6847 3.2843 14.2529M3.2843 14.2529C3.09871 13.5329 3 12.778 3 12C3 10.3946 3.42032 8.88743 4.15692 7.58245" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          RU/EN/PL (Compact)
        </button>
        <button class="btn btn-outline flex items-center justify-center gap-1" @click="addLanguagesPreset('bars')">
          <svg class="h-4 w-4 text-gray-500" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M12 21C16.1926 21 19.7156 18.1332 20.7157 14.2529M12 21C7.80742 21 4.28442 18.1332 3.2843 14.2529M12 21C14.4853 21 16.5 16.9706 16.5 12C16.5 7.02944 14.4853 3 12 3M12 21C9.51472 21 7.5 16.9706 7.5 12C7.5 7.02944 9.51472 3 12 3M12 3C15.3652 3 18.299 4.84694 19.8431 7.58245M12 3C8.63481 3 5.70099 4.84694 4.15692 7.58245M19.8431 7.58245C17.7397 9.40039 14.9983 10.5 12 10.5C9.00172 10.5 6.26027 9.40039 4.15692 7.58245M19.8431 7.58245C20.5797 8.88743 21 10.3946 21 12C21 12.778 20.9013 13.5329 20.7157 14.2529M20.7157 14.2529C18.1334 15.6847 15.1619 16.5 12 16.5C8.8381 16.5 5.86662 15.6847 3.2843 14.2529M3.2843 14.2529C3.09871 13.5329 3 12.778 3 12C3 10.3946 3.42032 8.88743 4.15692 7.58245" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          RU/EN/PL (Bars)
        </button>
        <button class="btn btn-outline flex items-center justify-center gap-1" @click="addLanguagesPreset('list')">
          <svg class="h-4 w-4 text-gray-500" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M12 21C16.1926 21 19.7156 18.1332 20.7157 14.2529M12 21C7.80742 21 4.28442 18.1332 3.2843 14.2529M12 21C14.4853 21 16.5 16.9706 16.5 12C16.5 7.02944 14.4853 3 12 3M12 21C9.51472 21 7.5 16.9706 7.5 12C7.5 7.02944 9.51472 3 12 3M12 3C15.3652 3 18.299 4.84694 19.8431 7.58245M12 3C8.63481 3 5.70099 4.84694 4.15692 7.58245M19.8431 7.58245C17.7397 9.40039 14.9983 10.5 12 10.5C9.00172 10.5 6.26027 9.40039 4.15692 7.58245M19.8431 7.58245C20.5797 8.88743 21 10.3946 21 12C21 12.778 20.9013 13.5329 20.7157 14.2529M20.7157 14.2529C18.1334 15.6847 15.1619 16.5 12 16.5C8.8381 16.5 5.86662 15.6847 3.2843 14.2529M3.2843 14.2529C3.09871 13.5329 3 12.778 3 12C3 10.3946 3.42032 8.88743 4.15692 7.58245" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          RU/EN/PL (List)
        </button>
      </div>
    </div>

    <div class="space-y-2">
      <div class="text-xs uppercase text-gray-500">Certifications</div>
      <div class="grid grid-cols-3 gap-2">
        <button class="btn btn-outline flex items-center justify-center gap-1" @click="addCertificationsPreset(1)">
          <svg class="h-4 w-4 text-gray-500" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M9 12.7498L11.25 14.9998L15 9.74985M12 2.71411C9.8495 4.75073 6.94563 5.99986 3.75 5.99986C3.69922 5.99986 3.64852 5.99955 3.59789 5.99892C3.2099 7.17903 3 8.43995 3 9.74991C3 15.3414 6.82432 20.0397 12 21.3719C17.1757 20.0397 21 15.3414 21 9.74991C21 8.43995 20.7901 7.17903 20.4021 5.99892C20.3515 5.99955 20.3008 5.99986 20.25 5.99986C17.0544 5.99986 14.1505 4.75073 12 2.71411Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          C1
        </button>
        <button class="btn btn-outline flex items-center justify-center gap-1" @click="addCertificationsPreset(2)">
          <svg class="h-4 w-4 text-gray-500" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M9 12.7498L11.25 14.9998L15 9.74985M12 2.71411C9.8495 4.75073 6.94563 5.99986 3.75 5.99986C3.69922 5.99986 3.64852 5.99955 3.59789 5.99892C3.2099 7.17903 3 8.43995 3 9.74991C3 15.3414 6.82432 20.0397 12 21.3719C17.1757 20.0397 21 15.3414 21 9.74991C21 8.43995 20.7901 7.17903 20.4021 5.99892C20.3515 5.99955 20.3008 5.99986 20.25 5.99986C17.0544 5.99986 14.1505 4.75073 12 2.71411Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          C2
        </button>
        <button class="btn btn-outline flex items-center justify-center gap-1" @click="addCertificationsPreset(3)">
          <svg class="h-4 w-4 text-gray-500" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M9 12.7498L11.25 14.9998L15 9.74985M12 2.71411C9.8495 4.75073 6.94563 5.99986 3.75 5.99986C3.69922 5.99986 3.64852 5.99955 3.59789 5.99892C3.2099 7.17903 3 8.43995 3 9.74991C3 15.3414 6.82432 20.0397 12 21.3719C17.1757 20.0397 21 15.3414 21 9.74991C21 8.43995 20.7901 7.17903 20.4021 5.99892C20.3515 5.99955 20.3008 5.99986 20.25 5.99986C17.0544 5.99986 14.1505 4.75073 12 2.71411Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          C3
        </button>
      </div>
    </div>

    <div class="space-y-2">
      <div class="text-xs uppercase text-gray-500">Achievements</div>
      <div class="grid grid-cols-3 gap-2">
        <button class="btn btn-outline flex items-center justify-center gap-1" @click="addAchievementsPreset(1)">
          <svg class="h-4 w-4 text-gray-500" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M16.5003 18.75H7.50026M16.5003 18.75C18.1571 18.75 19.5003 20.0931 19.5003 21.75H4.50026C4.50026 20.0931 5.8434 18.75 7.50026 18.75M16.5003 18.75V15.375C16.5003 14.7537 15.9966 14.25 15.3753 14.25H14.5036M7.50026 18.75V15.375C7.50026 14.7537 8.00394 14.25 8.62526 14.25H9.49689M14.5036 14.25H9.49689M14.5036 14.25C13.9563 13.3038 13.6097 12.227 13.5222 11.0777M9.49689 14.25C10.0442 13.3038 10.3908 12.227 10.4783 11.0777M5.25026 4.23636C4.26796 4.3792 3.29561 4.55275 2.33423 4.75601C2.78454 7.42349 4.99518 9.49282 7.72991 9.72775M5.25026 4.23636V4.5C5.25026 6.60778 6.21636 8.48992 7.72991 9.72775M5.25026 4.23636V2.72089C7.45568 2.41051 9.70922 2.25 12.0003 2.25C14.2913 2.25 16.5448 2.41051 18.7503 2.72089V4.23636M7.72991 9.72775C8.51748 10.3719 9.45329 10.8415 10.4783 11.0777M18.7503 4.23636V4.5C18.7503 6.60778 17.7842 8.48992 16.2706 9.72775M18.7503 4.23636C19.7326 4.3792 20.7049 4.55275 21.6663 4.75601C21.216 7.42349 19.0053 9.49282 16.2706 9.72775M16.2706 9.72775C15.483 10.3719 14.5472 10.8415 13.5222 11.0777M13.5222 11.0777C13.0331 11.1904 12.5236 11.25 12.0003 11.25C11.4769 11.25 10.9675 11.1904 10.4783 11.0777" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          A1
        </button>
        <button class="btn btn-outline flex items-center justify-center gap-1" @click="addAchievementsPreset(2)">
          <svg class="h-4 w-4 text-gray-500" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M16.5003 18.75H7.50026M16.5003 18.75C18.1571 18.75 19.5003 20.0931 19.5003 21.75H4.50026C4.50026 20.0931 5.8434 18.75 7.50026 18.75M16.5003 18.75V15.375C16.5003 14.7537 15.9966 14.25 15.3753 14.25H14.5036M7.50026 18.75V15.375C7.50026 14.7537 8.00394 14.25 8.62526 14.25H9.49689M14.5036 14.25H9.49689M14.5036 14.25C13.9563 13.3038 13.6097 12.227 13.5222 11.0777M9.49689 14.25C10.0442 13.3038 10.3908 12.227 10.4783 11.0777M5.25026 4.23636C4.26796 4.3792 3.29561 4.55275 2.33423 4.75601C2.78454 7.42349 4.99518 9.49282 7.72991 9.72775M5.25026 4.23636V4.5C5.25026 6.60778 6.21636 8.48992 7.72991 9.72775M5.25026 4.23636V2.72089C7.45568 2.41051 9.70922 2.25 12.0003 2.25C14.2913 2.25 16.5448 2.41051 18.7503 2.72089V4.23636M7.72991 9.72775C8.51748 10.3719 9.45329 10.8415 10.4783 11.0777M18.7503 4.23636V4.5C18.7503 6.60778 17.7842 8.48992 16.2706 9.72775M18.7503 4.23636C19.7326 4.3792 20.7049 4.55275 21.6663 4.75601C21.216 7.42349 19.0053 9.49282 16.2706 9.72775M16.2706 9.72775C15.483 10.3719 14.5472 10.8415 13.5222 11.0777M13.5222 11.0777C13.0331 11.1904 12.5236 11.25 12.0003 11.25C11.4769 11.25 10.9675 11.1904 10.4783 11.0777" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          A2
        </button>
        <button class="btn btn-outline flex items-center justify-center gap-1" @click="addAchievementsPreset(3)">
          <svg class="h-4 w-4 text-gray-500" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M16.5003 18.75H7.50026M16.5003 18.75C18.1571 18.75 19.5003 20.0931 19.5003 21.75H4.50026C4.50026 20.0931 5.8434 18.75 7.50026 18.75M16.5003 18.75V15.375C16.5003 14.7537 15.9966 14.25 15.3753 14.25H14.5036M7.50026 18.75V15.375C7.50026 14.7537 8.00394 14.25 8.62526 14.25H9.49689M14.5036 14.25H9.49689M14.5036 14.25C13.9563 13.3038 13.6097 12.227 13.5222 11.0777M9.49689 14.25C10.0442 13.3038 10.3908 12.227 10.4783 11.0777M5.25026 4.23636C4.26796 4.3792 3.29561 4.55275 2.33423 4.75601C2.78454 7.42349 4.99518 9.49282 7.72991 9.72775M5.25026 4.23636V4.5C5.25026 6.60778 6.21636 8.48992 7.72991 9.72775M5.25026 4.23636V2.72089C7.45568 2.41051 9.70922 2.25 12.0003 2.25C14.2913 2.25 16.5448 2.41051 18.7503 2.72089V4.23636M7.72991 9.72775C8.51748 10.3719 9.45329 10.8415 10.4783 11.0777M18.7503 4.23636V4.5C18.7503 6.60778 17.7842 8.48992 16.2706 9.72775M18.7503 4.23636C19.7326 4.3792 20.7049 4.55275 21.6663 4.75601C21.216 7.42349 19.0053 9.49282 16.2706 9.72775M16.2706 9.72775C15.483 10.3719 14.5472 10.8415 13.5222 11.0777M13.5222 11.0777C13.0331 11.1904 12.5236 11.25 12.0003 11.25C11.4769 11.25 10.9675 11.1904 10.4783 11.0777" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          A3
        </button>
      </div>
    </div>

    <div class="space-y-2">
      <div class="text-xs uppercase text-gray-500">Interests</div>
      <div class="grid grid-cols-3 gap-2">
        <button class="btn btn-outline flex items-center justify-center gap-1" @click="addInterestsPreset(1)">
          <svg class="h-4 w-4 text-gray-500" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M21 8.25C21 5.76472 18.9013 3.75 16.3125 3.75C14.3769 3.75 12.7153 4.87628 12 6.48342C11.2847 4.87628 9.62312 3.75 7.6875 3.75C5.09867 3.75 3 5.76472 3 8.25C3 15.4706 12 20.25 12 20.25C12 20.25 21 15.4706 21 8.25Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          I1
        </button>
        <button class="btn btn-outline flex items-center justify-center gap-1" @click="addInterestsPreset(2)">
          <svg class="h-4 w-4 text-gray-500" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M21 8.25C21 5.76472 18.9013 3.75 16.3125 3.75C14.3769 3.75 12.7153 4.87628 12 6.48342C11.2847 4.87628 9.62312 3.75 7.6875 3.75C5.09867 3.75 3 5.76472 3 8.25C3 15.4706 12 20.25 12 20.25C12 20.25 21 15.4706 21 8.25Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          I2
        </button>
        <button class="btn btn-outline flex items-center justify-center gap-1" @click="addInterestsPreset(3)">
          <svg class="h-4 w-4 text-gray-500" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M21 8.25C21 5.76472 18.9013 3.75 16.3125 3.75C14.3769 3.75 12.7153 4.87628 12 6.48342C11.2847 4.87628 9.62312 3.75 7.6875 3.75C5.09867 3.75 3 5.76472 3 8.25C3 15.4706 12 20.25 12 20.25C12 20.25 21 15.4706 21 8.25Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          I3
        </button>
      </div>
    </div>

    <div class="space-y-2">
      <div class="text-xs uppercase text-gray-500">References</div>
      <div class="grid grid-cols-3 gap-2">
        <button class="btn btn-outline flex items-center justify-center gap-1" @click="addReferencesPreset(1)">
          <svg class="h-4 w-4 text-gray-500" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M15 19.1276C15.8329 19.37 16.7138 19.5 17.625 19.5C19.1037 19.5 20.5025 19.1576 21.7464 18.5478C21.7488 18.4905 21.75 18.4329 21.75 18.375C21.75 16.0968 19.9031 14.25 17.625 14.25C16.2069 14.25 14.956 14.9655 14.2136 16.0552M15 19.1276V19.125C15 18.0121 14.7148 16.9658 14.2136 16.0552M15 19.1276C15 19.1632 14.9997 19.1988 14.9991 19.2343C13.1374 20.3552 10.9565 21 8.625 21C6.29353 21 4.11264 20.3552 2.25092 19.2343C2.25031 19.198 2.25 19.1615 2.25 19.125C2.25 15.6042 5.10418 12.75 8.625 12.75C11.0329 12.75 13.129 14.085 14.2136 16.0552M12 6.375C12 8.23896 10.489 9.75 8.625 9.75C6.76104 9.75 5.25 8.23896 5.25 6.375C5.25 4.51104 6.76104 3 8.625 3C10.489 3 12 4.51104 12 6.375ZM20.25 8.625C20.25 10.0747 19.0747 11.25 17.625 11.25C16.1753 11.25 15 10.0747 15 8.625C15 7.17525 16.1753 6 17.625 6C19.0747 6 20.25 7.17525 20.25 8.625Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          R1
        </button>
        <button class="btn btn-outline flex items-center justify-center gap-1" @click="addReferencesPreset(2)">
          <svg class="h-4 w-4 text-gray-500" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M15 19.1276C15.8329 19.37 16.7138 19.5 17.625 19.5C19.1037 19.5 20.5025 19.1576 21.7464 18.5478C21.7488 18.4905 21.75 18.4329 21.75 18.375C21.75 16.0968 19.9031 14.25 17.625 14.25C16.2069 14.25 14.956 14.9655 14.2136 16.0552M15 19.1276V19.125C15 18.0121 14.7148 16.9658 14.2136 16.0552M15 19.1276C15 19.1632 14.9997 19.1988 14.9991 19.2343C13.1374 20.3552 10.9565 21 8.625 21C6.29353 21 4.11264 20.3552 2.25092 19.2343C2.25031 19.198 2.25 19.1615 2.25 19.125C2.25 15.6042 5.10418 12.75 8.625 12.75C11.0329 12.75 13.129 14.085 14.2136 16.0552M12 6.375C12 8.23896 10.489 9.75 8.625 9.75C6.76104 9.75 5.25 8.23896 5.25 6.375C5.25 4.51104 6.76104 3 8.625 3C10.489 3 12 4.51104 12 6.375ZM20.25 8.625C20.25 10.0747 19.0747 11.25 17.625 11.25C16.1753 11.25 15 10.0747 15 8.625C15 7.17525 16.1753 6 17.625 6C19.0747 6 20.25 7.17525 20.25 8.625Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          R2
        </button>
        <button class="btn btn-outline flex items-center justify-center gap-1" @click="addReferencesPreset(3)">
          <svg class="h-4 w-4 text-gray-500" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M15 19.1276C15.8329 19.37 16.7138 19.5 17.625 19.5C19.1037 19.5 20.5025 19.1576 21.7464 18.5478C21.7488 18.4905 21.75 18.4329 21.75 18.375C21.75 16.0968 19.9031 14.25 17.625 14.25C16.2069 14.25 14.956 14.9655 14.2136 16.0552M15 19.1276V19.125C15 18.0121 14.7148 16.9658 14.2136 16.0552M15 19.1276C15 19.1632 14.9997 19.1988 14.9991 19.2343C13.1374 20.3552 10.9565 21 8.625 21C6.29353 21 4.11264 20.3552 2.25092 19.2343C2.25031 19.198 2.25 19.1615 2.25 19.125C2.25 15.6042 5.10418 12.75 8.625 12.75C11.0329 12.75 13.129 14.085 14.2136 16.0552M12 6.375C12 8.23896 10.489 9.75 8.625 9.75C6.76104 9.75 5.25 8.23896 5.25 6.375C5.25 4.51104 6.76104 3 8.625 3C10.489 3 12 4.51104 12 6.375ZM20.25 8.625C20.25 10.0747 19.0747 11.25 17.625 11.25C16.1753 11.25 15 10.0747 15 8.625C15 7.17525 16.1753 6 17.625 6C19.0747 6 20.25 7.17525 20.25 8.625Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          R3
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useResumeStore } from '../store'

const store = useResumeStore()

function centerX(width) { return Math.max(20, Math.floor((store.canvas.width - width) / 2)) }

// ===== NEW PRESETS =====
function addLanguagesPreset(variant = 'compact') {
  const y = nextY(200)
  const x = 80
  const cw = store.canvas.width - 160
  const ids = []
  ids.push(store.addElement({ type: 'text', x, y, width: cw, height: 28, z: 1,
    props: { text: 'Languages / Языки / Języki', fontSize: 16, fill: '#111827', fontStyle: 'bold' } }))
  const rowY = y + 36
  if (variant === 'compact' || variant === 'list') {
    const items = [
      { code: 'RU', label: 'Русский', level: 'Native' },
      { code: 'EN', label: 'English', level: 'C1' },
      { code: 'PL', label: 'Polski', level: 'B2' },
    ]
    const gap = 220
    items.forEach((it, i) => {
      const bx = x + i * gap
      ids.push(store.addElement({ type: 'text', x: bx, y: rowY, width: 200, height: 22, z: 1,
        props: { text: `${it.code} — ${it.label}`, fontSize: 13, fill: '#374151' } }))
      ids.push(store.addElement({ type: 'text', x: bx, y: rowY + 18, width: 200, height: 20, z: 1,
        props: { text: `Level: ${it.level}`, fontSize: 12, fill: '#6B7280' } }))
    })
  }
  if (variant === 'bars') {
    const items = [
      { label: 'Русский', pct: 100 },
      { label: 'English', pct: 85 },
      { label: 'Polski', pct: 70 },
    ]
    const barW = Math.min(420, cw)
    const barH = 8
    items.forEach((it, i) => {
      const yy = rowY + i * 34
      ids.push(store.addElement({ type: 'text', x, y: yy - 6, width: 120, height: 20, z: 1,
        props: { text: it.label, fontSize: 12, fill: '#374151' } }))
      ids.push(store.addElement({ type: 'rect', x: x + 130, y: yy, width: barW, height: barH, z: 1,
        props: { fill: '#E5E7EB', radius: 4 } }))
      ids.push(store.addElement({ type: 'rect', x: x + 130, y: yy, width: Math.round(barW * it.pct / 100), height: barH, z: 2,
        props: { fill: '#60A5FA', radius: 4 } }))
    })
  }
  store.createGroup(ids, { label: 'Languages' })
}

function addCertificationsPreset(i = 1) {
  const y = nextY(200)
  const x = 80
  const cw = store.canvas.width - 160
  const ids = []
  ids.push(store.addElement({ type: 'text', x, y, width: cw, height: 26, z: 1,
    props: { text: 'Certifications', fontSize: 16, fill: '#111827', fontStyle: 'bold' } }))
  const items = i === 1
    ? ['AWS Certified Solutions Architect', 'Scrum Master (PSM I)']
    : i === 2
      ? ['Google Professional Cloud Architect', 'IELTS Academic']
      : ['Kubernetes CKA', 'Oracle Java SE 11']
  const gy = 22
  items.forEach((t, k) => {
    ids.push(store.addElement({ type: 'text', x, y: y + 30 + k * gy, width: cw, height: 20, z: 1,
      props: { text: `• ${t}`, fontSize: 13, fill: '#374151' } }))
  })
  store.createGroup(ids, { label: 'Certifications' })
}

function addAchievementsPreset(i = 1) {
  const y = nextY(200)
  const x = 80
  const cw = store.canvas.width - 160
  const ids = []
  ids.push(store.addElement({ type: 'text', x, y, width: cw, height: 26, z: 1,
    props: { text: 'Achievements', fontSize: 16, fill: '#111827', fontStyle: 'bold' } }))
  const items = i === 1
    ? ['Optimized build time by 40%', 'Cut infra costs by 25%']
    : i === 2
      ? ['Top-3 in internal hackathon', 'Opened 50+ PRs to OSS']
      : ['Launched app to 100k MAU', 'NPS 70+ for product']
  const gy = 22
  items.forEach((t, k) => {
    ids.push(store.addElement({ type: 'text', x, y: y + 30 + k * gy, width: cw, height: 20, z: 1,
      props: { text: `• ${t}`, fontSize: 13, fill: '#374151' } }))
  })
  store.createGroup(ids, { label: 'Achievements' })
}

function addInterestsPreset(i = 1) {
  const y = nextY(200)
  const x = 80
  const cw = store.canvas.width - 160
  const ids = []
  ids.push(store.addElement({ type: 'text', x, y, width: cw, height: 26, z: 1,
    props: { text: 'Interests', fontSize: 16, fill: '#111827', fontStyle: 'bold' } }))
  const chips = i === 1 ? ['Travel', 'Photography', 'Running', 'Chess']
    : i === 2 ? ['Design', 'Music', 'Cooking', 'Hiking']
    : ['Reading', 'Video Games', 'Blogging', 'Cycling']
  let cx = x
  const gap = 10
  chips.forEach((label) => {
    const w = 10 * label.length + 24
    ids.push(store.addElement({ type: 'rect', x: cx, y: y + 34, width: w, height: 28, z: 1, props: { fill: '#EEF2FF', radius: 14 } }))
    ids.push(store.addElement({ type: 'text', x: cx + 12, y: y + 40, width: w - 24, height: 16, z: 2, props: { text: label, fontSize: 12, fill: '#3730A3' } }))
    cx += w + gap
  })
  store.createGroup(ids, { label: 'Interests' })
}

function addReferencesPreset(i = 1) {
  const y = nextY(200)
  const x = 80
  const cw = store.canvas.width - 160
  const ids = []
  ids.push(store.addElement({ type: 'text', x, y, width: cw, height: 26, z: 1,
    props: { text: 'References', fontSize: 16, fill: '#111827', fontStyle: 'bold' } }))
  const cards = i === 1
    ? [
      { name: 'Jane Doe', title: 'CTO, TechCorp', email: 'jane@corp.com', phone: '+1 555 111 2222' },
      { name: 'John Smith', title: 'Lead, InnovateX', email: 'john@innovatex.com', phone: '+1 555 333 4444' },
    ]
    : i === 2
      ? [
        { name: 'Anna Kowalska', title: 'Manager, Firma', email: 'anna@firma.pl', phone: '+48 600 000 000' },
        { name: 'Ivan Petrov', title: 'Team Lead, Soft', email: 'ivan@soft.ru', phone: '+7 900 000 00 00' },
      ]
      : [
        { name: 'Alice', title: 'PM, Startup', email: 'alice@startup.io', phone: '+1 555 777 8888' },
      ]
  const cardW = Math.min(360, cw)
  const gap = 20
  cards.forEach((c, k) => {
    const bx = x + k * (cardW + gap)
    ids.push(store.addElement({ type: 'rect', x: bx, y: y + 30, width: cardW, height: 110, z: 0, props: { fill: '#F9FAFB', radius: 10 } }))
    ids.push(store.addElement({ type: 'text', x: bx + 12, y: y + 40, width: cardW - 24, height: 22, z: 1, props: { text: c.name, fontSize: 14, fill: '#111827', fontStyle: 'bold' } }))
    ids.push(store.addElement({ type: 'text', x: bx + 12, y: y + 60, width: cardW - 24, height: 20, z: 1, props: { text: c.title, fontSize: 12, fill: '#6B7280' } }))
    ids.push(store.addElement({ type: 'text', x: bx + 12, y: y + 78, width: cardW - 24, height: 20, z: 1, props: { text: c.email, fontSize: 12, fill: '#4B5563' } }))
    ids.push(store.addElement({ type: 'text', x: bx + 12, y: y + 96, width: cardW - 24, height: 20, z: 1, props: { text: c.phone, fontSize: 12, fill: '#4B5563' } }))
  })
  store.createGroup(ids, { label: 'References' })
}
function nextY(defaultY = 80, margin = 24) {
  const els = store.canvas.elements
  if (!els.length) return defaultY
  const bottom = Math.max(...els.map(e => (e.y || 0) + (e.height || 0)))
  return bottom + margin
}

// ===== PRESETS =====
function addHeaderPreset(i) {
  const y = nextY(60)
  const cw = store.canvas.width
  if (i === 1) {
    // Name left, title left, contact row right
    const ids = []
    ids.push(store.addElement({ type: 'text', x: 60, y, width: cw * 0.6, height: 48, z: 1, props: { text: 'Your Name', fontSize: 40, fill: '#111827', fontStyle: 'bold' } }))
    ids.push(store.addElement({ type: 'text', x: 60, y: y + 44, width: cw * 0.5, height: 28, z: 1, props: { text: 'Senior Software Engineer', fontSize: 16, fill: '#374151' } }))
    ids.push(store.addElement({ type: 'text', x: cw - 360, y, width: 300, height: 20, z: 1, props: { text: 'email@domain.com', fontSize: 12, fill: '#4B5563', align: 'right' } }))
    ids.push(store.addElement({ type: 'text', x: cw - 360, y: y + 20, width: 300, height: 20, z: 1, props: { text: '+1 555 555 555', fontSize: 12, fill: '#4B5563', align: 'right' } }))
    store.createGroup(ids, { label: `Header H${i}` })
  } else if (i === 2) {
    // Centered with divider
    const ids = []
    ids.push(store.addElement({ type: 'text', x: centerX(800), y, width: 800, height: 48, z: 1, props: { text: 'Your Name', fontSize: 38, fill: '#111827', fontStyle: 'bold', align: 'center' } }))
    ids.push(store.addElement({ type: 'text', x: centerX(600), y: y + 42, width: 600, height: 24, z: 1, props: { text: 'Software Engineer', fontSize: 14, fill: '#6B7280', align: 'center' } }))
    ids.push(store.addElement({ type: 'rect', x: centerX(200), y: y + 74, width: 200, height: 2, z: 1, props: { fill: '#E5E7EB' } }))
    store.createGroup(ids, { label: `Header H${i}` })
  } else if (i === 3) {
    // Avatar + name block
    const ids = []
    ids.push(store.addElement({ type: 'image', x: 60, y, width: 96, height: 96, z: 2, props: { src: '', objectFit: 'cover', radius: 999 } }))
    ids.push(store.addElement({ type: 'text', x: 60 + 112, y: y + 8, width: cw - 200, height: 40, z: 1, props: { text: 'Your Name', fontSize: 36, fill: '#111827', fontStyle: 'bold' } }))
    ids.push(store.addElement({ type: 'text', x: 60 + 112, y: y + 44, width: cw - 200, height: 24, z: 1, props: { text: 'Full‑Stack Developer', fontSize: 14, fill: '#6B7280' } }))
    store.createGroup(ids, { label: `Header H${i}` })
  } else if (i === 4) {
    // Accent band header
    const bandH = 120
    const ids = []
    ids.push(store.addElement({ type: 'rect', x: 0, y, width: cw, height: bandH, z: 0, props: { fill: '#EEF2FF' } }))
    ids.push(store.addElement({ type: 'text', x: 60, y: y + 28, width: cw - 120, height: 44, z: 1, props: { text: 'Your Name', fontSize: 38, fill: '#1F2937', fontStyle: 'bold' } }))
    ids.push(store.addElement({ type: 'text', x: 60, y: y + 70, width: cw - 120, height: 24, z: 1, props: { text: 'Product Designer', fontSize: 14, fill: '#374151' } }))
    store.createGroup(ids, { label: `Header H${i}` })
  } else if (i === 5) {
    // Left column sidebar header
    const sideW = 320
    const ids = []
    ids.push(store.addElement({ type: 'rect', x: 0, y, width: sideW, height: 180, z: 0, props: { fill: '#F3F4F6' } }))
    ids.push(store.addElement({ type: 'text', x: 24, y: y + 24, width: sideW - 48, height: 40, z: 1, props: { text: 'Your Name', fontSize: 30, fill: '#111827', fontStyle: 'bold' } }))
    ids.push(store.addElement({ type: 'text', x: 24, y: y + 64, width: sideW - 48, height: 24, z: 1, props: { text: 'Role', fontSize: 14, fill: '#6B7280' } }))
    store.createGroup(ids, { label: `Header H${i}` })
  } else if (i === 6) {
    // Name large with thin subtitle centered
    const ids = []
    ids.push(store.addElement({ type: 'text', x: centerX(900), y, width: 900, height: 60, z: 1, props: { text: 'Your Name', fontSize: 48, fill: '#111827', fontStyle: 'bold', align: 'center' } }))
    ids.push(store.addElement({ type: 'text', x: centerX(700), y: y + 54, width: 700, height: 24, z: 1, props: { text: 'Data Scientist', fontSize: 13, fill: '#6B7280', align: 'center' } }))
    store.createGroup(ids, { label: `Header H${i}` })
  } else if (i === 7) {
    // Name right aligned
    const ids = []
    ids.push(store.addElement({ type: 'text', x: cw - 620, y, width: 560, height: 48, z: 1, props: { text: 'Your Name', fontSize: 40, fill: '#111827', fontStyle: 'bold', align: 'right' } }))
    ids.push(store.addElement({ type: 'text', x: cw - 620, y: y + 44, width: 560, height: 24, z: 1, props: { text: 'Mobile Engineer', fontSize: 14, fill: '#6B7280', align: 'right' } }))
    store.createGroup(ids, { label: `Header H${i}` })
  }
}

function addExperiencePreset(i) {
  const x = 80
  const w = store.canvas.width - 160
  const y = nextY(260)
  const dateW = 180
  if (i === 1) {
    // Title + company + bullets
    const ids = []
    ids.push(store.addElement({ type: 'text', x, y, width: w - dateW, height: 26, z: 1, props: { text: 'Senior Engineer — Company', fontSize: 16, fill: '#111827', fontStyle: 'bold' } }))
    ids.push(store.addElement({ type: 'text', x: x + w - dateW, y, width: dateW, height: 22, z: 1, props: { text: '2019 — Present', fontSize: 12, fill: '#6B7280', align: 'right' } }))
    const by = y + 30
    const g = 18
    const bullet = '• '
    ;['Built scalable services', 'Cut infra cost by 25%', 'Led migration to Vue 3'].forEach((t, k) => {
      ids.push(store.addElement({ type: 'text', x, y: by + g * k, width: w, height: 20, z: 1, props: { text: bullet + t, fontSize: 13, fill: '#4B5563' } }))
    })
    store.createGroup(ids, { label: `Experience E${i}` })
  } else if (i === 2) {
    // Card background
    const h = 120
    const ids = []
    ids.push(store.addElement({ type: 'rect', x, y, width: w, height: h, z: 0, props: { fill: '#F9FAFB', radius: 10 } }))
    ids.push(store.addElement({ type: 'text', x: x + 16, y: y + 14, width: w - 32, height: 24, z: 1, props: { text: 'Backend Engineer — Org', fontSize: 15, fill: '#111827', fontStyle: 'bold' } }))
    ids.push(store.addElement({ type: 'text', x: x + 16, y: y + 38, width: w - 32, height: 22, z: 1, props: { text: '2017 — 2019 · City', fontSize: 12, fill: '#6B7280' } }))
    ids.push(store.addElement({ type: 'text', x: x + 16, y: y + 62, width: w - 32, height: 20, z: 1, props: { text: 'Maintained APIs and DBs', fontSize: 13, fill: '#4B5563' } }))
    store.createGroup(ids, { label: `Experience E${i}` })
  }
 else if (i === 3) {
    // Left date column
    store.addElement({ type: 'text', x, y, width: 120, height: 24, z: 1, props: { text: '2015 — 2017', fontSize: 12, fill: '#6B7280' } })
    store.addElement({ type: 'text', x: x + 140, y, width: w - 140, height: 24, z: 1, props: { text: 'Frontend Engineer — Studio', fontSize: 15, fill: '#111827', fontStyle: 'bold' } })
    store.addElement({ type: 'text', x: x + 140, y: y + 24, width: w - 140, height: 20, z: 1, props: { text: 'Worked on UI systems and design library', fontSize: 13, fill: '#4B5563' } })
  } else if (i === 4) {
    // Compact row
    store.addElement({ type: 'text', x, y, width: w, height: 22, z: 1, props: { text: 'Company — Role', fontSize: 14, fill: '#111827', fontStyle: 'bold' } })
    store.addElement({ type: 'text', x, y: y + 20, width: w, height: 20, z: 1, props: { text: 'Achievements, stack, responsibilities', fontSize: 12, fill: '#6B7280' } })
  } else if (i === 5) {
    // Emphasis divider
    store.addElement({ type: 'rect', x, y, width: 2, height: 80, z: 0, props: { fill: '#A78BFA' } })
    store.addElement({ type: 'text', x: x + 16, y, width: w - 16, height: 24, z: 1, props: { text: 'Lead Engineer — Brand', fontSize: 15, fill: '#111827', fontStyle: 'bold' } })
    store.addElement({ type: 'text', x: x + 16, y: y + 24, width: w - 16, height: 20, z: 1, props: { text: '2019 — Present · Remote', fontSize: 12, fill: '#6B7280' } })
  } else if (i === 6) {
    // Two-column job + bullets right
    store.addElement({ type: 'text', x, y, width: w * 0.45, height: 24, z: 1, props: { text: 'Role — Company', fontSize: 15, fill: '#111827', fontStyle: 'bold' } })
    const bx = x + w * 0.5
    ;['Improved DX', 'Reduced bugs', 'Delivered features'].forEach((t, k) => {
      store.addElement({ type: 'text', x: bx, y: y + k * 18, width: w * 0.5, height: 20, z: 1, props: { text: '• ' + t, fontSize: 12, fill: '#4B5563' } })
    })
  } else if (i === 7) {
    // Experience card with subtle bg
    const h = 140
    store.addElement({ type: 'rect', x: x, y, width: w, height: h, z: 0, props: { fill: '#F3F4F6', radius: 6 } })
    store.addElement({ type: 'text', x: x + 14, y: y + 12, width: w - 28, height: 24, z: 1, props: { text: 'Engineer — Company', fontSize: 15, fill: '#111827', fontStyle: 'bold' } })
    store.addElement({ type: 'text', x: x + 14, y: y + 36, width: w - 28, height: 20, z: 1, props: { text: 'Key results and scope', fontSize: 12, fill: '#4B5563' } })
  }
}

function addEducationPreset(i) {
  const x = 80
  const w = store.canvas.width - 160
  const y = nextY(260)
  if (i === 1) {
    store.addElement({ type: 'text', x, y, width: w * 0.7, height: 22, z: 1, props: { text: 'B.Sc. Computer Science', fontSize: 14, fill: '#111827', fontStyle: 'bold' } })
    store.addElement({ type: 'text', x: x + w * 0.72, y, width: w * 0.28, height: 20, z: 1, props: { text: '2014 — 2018', fontSize: 12, fill: '#6B7280', align: 'right' } })
    store.addElement({ type: 'text', x, y: y + 22, width: w, height: 20, z: 1, props: { text: 'University, City', fontSize: 12, fill: '#4B5563' } })
  } else if (i === 2) {
    const h = 90
    store.addElement({ type: 'rect', x, y, width: w, height: h, z: 0, props: { fill: '#F9FAFB', radius: 8 } })
    store.addElement({ type: 'text', x: x + 14, y: y + 12, width: w - 28, height: 22, z: 1, props: { text: 'M.Sc. Data Science', fontSize: 14, fill: '#111827', fontStyle: 'bold' } })
    store.addElement({ type: 'text', x: x + 14, y: y + 34, width: w - 28, height: 20, z: 1, props: { text: 'Institute, 2018 — 2020', fontSize: 12, fill: '#6B7280' } })
  } else if (i === 3) {
    // Left degree badge
    store.addElement({ type: 'rect', x, y, width: 6, height: 60, z: 0, props: { fill: '#60A5FA' } })
    store.addElement({ type: 'text', x: x + 18, y, width: w - 18, height: 22, z: 1, props: { text: 'Diploma in UX', fontSize: 14, fill: '#111827', fontStyle: 'bold' } })
    store.addElement({ type: 'text', x: x + 18, y: y + 22, width: w - 18, height: 20, z: 1, props: { text: 'Design School, 2016', fontSize: 12, fill: '#6B7280' } })
  } else if (i === 4) {
    // Two-column: degree left, school right
    store.addElement({ type: 'text', x, y, width: w * 0.5, height: 22, z: 1, props: { text: 'B.A. Economics', fontSize: 14, fill: '#111827', fontStyle: 'bold' } })
    store.addElement({ type: 'text', x: x + w * 0.52, y, width: w * 0.48, height: 20, z: 1, props: { text: 'University — 2012 — 2016', fontSize: 12, fill: '#6B7280', align: 'right' } })
  } else if (i === 5) {
    // Single-line condensed
    store.addElement({ type: 'text', x, y, width: w, height: 22, z: 1, props: { text: 'Course: Algorithms — Coursera (2021)', fontSize: 13, fill: '#111827' } })
  } else if (i === 6) {
    // Badge style container
    store.addElement({ type: 'rect', x, y, width: w, height: 60, z: 0, props: { fill: '#EEF2FF', radius: 10 } })
    store.addElement({ type: 'text', x: x + 14, y: y + 18, width: w - 28, height: 22, z: 1, props: { text: 'Certificate — Kubernetes', fontSize: 14, fill: '#1F2937' } })
  } else if (i === 7) {
    // Timeline marker
    store.addElement({ type: 'rect', x: x + 8, y, width: 2, height: 80, z: 0, props: { fill: '#D1D5DB' } })
    store.addElement({ type: 'text', x: x + 24, y, width: w - 24, height: 22, z: 1, props: { text: 'Ph.D. — Research Area', fontSize: 14, fill: '#111827', fontStyle: 'bold' } })
    store.addElement({ type: 'text', x: x + 24, y: y + 22, width: w - 24, height: 20, z: 1, props: { text: 'University, 2020 — 2024', fontSize: 12, fill: '#6B7280' } })
  }
}

function addSkillsPreset(i) {
  const x = 80
  const y = nextY(240)
  const w = store.canvas.width - 160
  if (i === 1) {
    // Chips row (uses existing helper for idea, but custom content)
    const items = ['JS', 'TS', 'Vue', 'Node', 'SQL', 'Docker']
    let cx = x
    const gap = 10, h = 28
    items.forEach(lbl => {
      const tw = 10 * lbl.length + 24
      store.addElement({ type: 'rect', x: cx, y, width: tw, height: h, z: 1, props: { fill: '#EEF2FF', radius: 14 } })
      store.addElement({ type: 'text', x: cx + 12, y: y + 6, width: tw - 24, height: 16, z: 2, props: { text: lbl, fontSize: 12, fill: '#3730A3' } })
      cx += tw + gap
    })
  } else if (i === 2) {
    // Two-column list
    const col = (arr, colIdx) => arr.filter((_, i) => i % 2 === colIdx)
    const items = ['React', 'Vue', 'Svelte', 'Node', 'Go', 'Python', 'SQL', 'K8s']
    col(items, 0).forEach((t, k) => store.addElement({ type: 'text', x, y: y + k * 18, width: w * 0.48, height: 18, z: 1, props: { text: '• ' + t, fontSize: 12, fill: '#4B5563' } }))
    col(items, 1).forEach((t, k) => store.addElement({ type: 'text', x: x + w * 0.52, y: y + k * 18, width: w * 0.48, height: 18, z: 1, props: { text: '• ' + t, fontSize: 12, fill: '#4B5563' } }))
  } else if (i === 3) {
    // Bar ratings (visual only)
    const skills = [ ['JavaScript', 90], ['Python', 80], ['SQL', 70] ]
    skills.forEach((s, k) => {
      const [name, pct] = s
      const yy = y + k * 26
      store.addElement({ type: 'text', x, y: yy, width: 160, height: 18, z: 1, props: { text: name, fontSize: 12, fill: '#374151' } })
      store.addElement({ type: 'rect', x: x + 170, y: yy + 6, width: w - 200, height: 6, z: 0, props: { fill: '#E5E7EB', radius: 4 } })
      store.addElement({ type: 'rect', x: x + 170, y: yy + 6, width: Math.floor((w - 200) * pct / 100), height: 6, z: 1, props: { fill: '#60A5FA', radius: 4 } })
    })
  } else if (i === 4) {
    // Category blocks
    const cats = ['Frontend', 'Backend', 'DevOps']
    cats.forEach((c, k) => {
      const yy = y + k * 70
      store.addElement({ type: 'text', x, y: yy, width: w, height: 20, z: 1, props: { text: c, fontSize: 13, fill: '#111827', fontStyle: 'bold' } })
      store.addElement({ type: 'text', x, y: yy + 18, width: w, height: 18, z: 1, props: { text: 'HTML, CSS, JS, Vue', fontSize: 12, fill: '#4B5563' } })
    })
  } else if (i === 5) {
    // Grid badges
    const items = ['JS', 'TS', 'Vue', 'React', 'Node', 'Express', 'Mongo', 'SQL']
    const cols = 4, gap = 10, cellW = (w - gap * (cols - 1)) / cols, cellH = 28
    items.forEach((t, idx) => {
      const row = Math.floor(idx / cols), col = idx % cols
      const cx = x + col * (cellW + gap), cy = y + row * (cellH + gap)
      store.addElement({ type: 'rect', x: cx, y: cy, width: cellW, height: cellH, z: 1, props: { fill: '#F3F4F6', radius: 8 } })
      store.addElement({ type: 'text', x: cx, y: cy + 6, width: cellW, height: 16, z: 2, props: { text: t, fontSize: 12, fill: '#374151', align: 'center' } })
    })
  } else if (i === 6) {
    // Inline comma list
    store.addElement({ type: 'text', x, y, width: w, height: 20, z: 1, props: { text: 'JavaScript, TypeScript, Vue, Node, GraphQL, Docker', fontSize: 13, fill: '#4B5563' } })
  } else if (i === 7) {
    // Icon placeholders row
    const items = ['JS', 'TS', 'VUE', 'NODE']
    const size = 28, gap = 12
    let cx = x
    items.forEach(t => {
      store.addElement({ type: 'rect', x: cx, y, width: size, height: size, z: 1, props: { fill: '#E5E7EB', radius: 6 } })
      store.addElement({ type: 'text', x: cx, y: y + 6, width: size, height: 16, z: 2, props: { text: t, fontSize: 10, fill: '#6B7280', align: 'center' } })
      cx += size + gap
    })
  }
}

function addProjectsPreset(i) {
  const x = 80
  const w = store.canvas.width - 160
  const y = nextY(260)
  if (i === 1) {
    // Simple project with link
    store.addElement({ type: 'text', x, y, width: w, height: 22, z: 1, props: { text: 'Project Name — Short description', fontSize: 14, fill: '#111827', fontStyle: 'bold' } })
    store.addElement({ type: 'text', x, y: y + 20, width: w, height: 20, z: 1, props: { text: 'github.com/username/repo', fontSize: 12, fill: '#2563EB' } })
  } else if (i === 2) {
    // Two-column projects
    const w2 = (w - 24) / 2
    ;[0,1].forEach(col => {
      const cx = x + col * (w2 + 24)
      store.addElement({ type: 'text', x: cx, y, width: w2, height: 22, z: 1, props: { text: 'Project ' + (col+1), fontSize: 14, fill: '#111827', fontStyle: 'bold' } })
      store.addElement({ type: 'text', x: cx, y: y + 20, width: w2, height: 36, z: 1, props: { text: 'Description of the project, tech stack, role.', fontSize: 12, fill: '#4B5563' } })
    })
  } else if (i === 3) {
    // Project card
    const h = 110
    store.addElement({ type: 'rect', x, y, width: w, height: h, z: 0, props: { fill: '#F9FAFB', radius: 10 } })
    store.addElement({ type: 'text', x: x + 14, y: y + 12, width: w - 28, height: 22, z: 1, props: { text: 'Project Card', fontSize: 14, fill: '#111827', fontStyle: 'bold' } })
    store.addElement({ type: 'text', x: x + 14, y: y + 34, width: w - 28, height: 56, z: 1, props: { text: 'Key outcomes and impact summary over two lines.', fontSize: 12, fill: '#4B5563' } })
  } else if (i === 4) {
    // Title + tags row
    store.addElement({ type: 'text', x, y, width: w, height: 22, z: 1, props: { text: 'Realtime Dashboard', fontSize: 14, fill: '#111827', fontStyle: 'bold' } })
    const tags = ['Vue', 'Node', 'WebSocket']
    let cx = x, gap = 8, h = 22
    tags.forEach(t => {
      const tw = Math.max(40, 9 * t.length + 16)
      store.addElement({ type: 'rect', x: cx, y: y + 22 + 6, width: tw, height: h, z: 1, props: { fill: '#E5E7EB', radius: 6 } })
      store.addElement({ type: 'text', x: cx, y: y + 22 + 10, width: tw, height: 16, z: 2, props: { text: t, fontSize: 10, fill: '#374151', align: 'center' } })
      cx += tw + gap
    })
  } else if (i === 5) {
    // Project with metrics
    store.addElement({ type: 'text', x, y, width: w, height: 22, z: 1, props: { text: 'Growth Analytics', fontSize: 14, fill: '#111827', fontStyle: 'bold' } })
    const metrics = ['10k MAU', '99.9% uptime', '2x conversion']
    let cx = x
    metrics.forEach(m => {
      const tw = 9 * m.length + 20
      store.addElement({ type: 'rect', x: cx, y: y + 24, width: tw, height: 22, z: 1, props: { fill: '#EEF2FF', radius: 6 } })
      store.addElement({ type: 'text', x: cx, y: y + 28, width: tw, height: 16, z: 2, props: { text: m, fontSize: 10, fill: '#3730A3', align: 'center' } })
      cx += tw + 8
    })
  } else if (i === 6) {
    // Left thumbnail placeholder
    store.addElement({ type: 'rect', x, y, width: 80, height: 60, z: 1, props: { fill: '#E5E7EB', radius: 8 } })
    store.addElement({ type: 'text', x: x + 96, y, width: w - 96, height: 22, z: 1, props: { text: 'Portfolio Item', fontSize: 14, fill: '#111827', fontStyle: 'bold' } })
    store.addElement({ type: 'text', x: x + 96, y: y + 20, width: w - 96, height: 36, z: 1, props: { text: 'Short description of the project and your contribution.', fontSize: 12, fill: '#4B5563' } })
  } else if (i === 7) {
    // Compact single-line project
    store.addElement({ type: 'text', x, y, width: w, height: 20, z: 1, props: { text: 'Project — URL — One-liner', fontSize: 12, fill: '#111827' } })
  }
}

function addHeading() {
  store.addElement({ type: 'text', x: centerX(800), y: 80, width: 800, height: 60, z: 1,
    props: { text: 'Your Name', fontSize: 42, fontFamily: 'Inter', fill: '#111827', align: 'left', fontStyle: 'bold' } })
}
function addSubheading() {
  store.addElement({ type: 'text', x: centerX(600), y: 150, width: 600, height: 40, z: 1,
    props: { text: 'Job Title / Position', fontSize: 24, fontFamily: 'Inter', fill: '#374151', align: 'left' } })
}
function addParagraph() {
  store.addElement({ type: 'text', x: 80, y: 220, width: 1080, height: 120, z: 1,
    props: { text: 'Professional summary goes here. A concise, impactful description of your experience and value.', fontSize: 14, fontFamily: 'Inter', fill: '#4B5563', align: 'left' } })
}
function addSectionTitle() {
  store.addElement({ type: 'text', x: 80, y: 360, width: 400, height: 40, z: 1,
    props: { text: 'Experience', fontSize: 18, fontFamily: 'Inter', fill: '#111827', align: 'left', fontStyle: 'bold' } })
}
function addDivider() {
  store.addElement({ type: 'rect', x: 80, y: nextY(410, 16), width: store.canvas.width - 160, height: 2, z: 1,
    props: { fill: '#E5E7EB' } })
}
function addSidebarBlock() {
  store.addElement({ type: 'rect', x: 0, y: 0, width: 360, height: store.canvas.height, z: 0,
    props: { fill: '#F3F4F6' } })
}
function addAvatar() {
  store.addElement({ type: 'image', x: 80, y: 80, width: 120, height: 120, z: 2,
    props: { src: '', objectFit: 'cover', radius: 999 } })
}

function addTwoColumns() {
  const gutter = 24
  const leftW = Math.floor((store.canvas.width - 80 - gutter) * 0.35)
  const rightW = store.canvas.width - 80 - gutter - leftW
  const y = nextY(140)
  // left column background block
  store.addElement({ type: 'rect', x: 40, y, width: leftW, height: 600, z: 0, props: { fill: '#F9FAFB' } })
  // right column anchor title
  store.addElement({ type: 'text', x: 40 + leftW + gutter, y, width: rightW, height: 40, z: 1,
    props: { text: 'Section Title', fontSize: 18, fontFamily: 'Inter', fill: '#111827', fontStyle: 'bold' } })
}

function addExperienceCard() {
  const y = nextY(360)
  const x = 80
  const w = store.canvas.width - 160
  // Title line
  store.addElement({ type: 'text', x, y, width: w * 0.7, height: 28, z: 1,
    props: { text: 'Senior Software Engineer', fontSize: 16, fill: '#111827', fontStyle: 'bold' } })
  store.addElement({ type: 'text', x: x + w * 0.72, y, width: w * 0.28, height: 24, z: 1,
    props: { text: '2019 — Present', fontSize: 14, fill: '#6B7280', align: 'right' } })
  // Company
  store.addElement({ type: 'text', x, y: y + 26, width: w, height: 24, z: 1,
    props: { text: 'Awesome Company · Remote', fontSize: 14, fill: '#374151' } })
  // Bullets (simple lines)
  const by = y + 56
  const bulletGap = 18
  const bullet = '• '
  store.addElement({ type: 'text', x, y: by, width: w, height: 20, z: 1, props: { text: bullet + 'Led development of key features.', fontSize: 13, fill: '#4B5563' } })
  store.addElement({ type: 'text', x, y: by + bulletGap, width: w, height: 20, z: 1, props: { text: bullet + 'Improved performance by 30%.', fontSize: 13, fill: '#4B5563' } })
  store.addElement({ type: 'text', x, y: by + bulletGap * 2, width: w, height: 20, z: 1, props: { text: bullet + 'Mentored team of 4 engineers.', fontSize: 13, fill: '#4B5563' } })
}

function addEducationCard() {
  const y = nextY(360)
  const x = 80
  const w = store.canvas.width - 160
  store.addElement({ type: 'text', x, y, width: w * 0.7, height: 24, z: 1, props: { text: 'B.Sc. in Computer Science', fontSize: 15, fill: '#111827', fontStyle: 'bold' } })
  store.addElement({ type: 'text', x: x + w * 0.72, y, width: w * 0.28, height: 24, z: 1, props: { text: '2014 — 2018', fontSize: 14, fill: '#6B7280', align: 'right' } })
  store.addElement({ type: 'text', x, y: y + 24, width: w, height: 22, z: 1, props: { text: 'Tech University, City', fontSize: 14, fill: '#374151' } })
}

function addSkillsChips() {
  const y = nextY(360)
  const x = 80
  const gap = 10
  const chipH = 28
  const chips = ['JavaScript', 'Vue', 'TypeScript', 'Node.js', 'Tailwind']
  let cx = x
  chips.forEach((label) => {
    const textW = 10 * label.length + 24 // rough width
    store.addElement({ type: 'rect', x: cx, y, width: textW, height: chipH, z: 1, props: { fill: '#EEF2FF', radius: 14 } })
    store.addElement({ type: 'text', x: cx + 12, y: y + 6, width: textW - 24, height: 16, z: 2, props: { text: label, fontSize: 12, fill: '#3730A3' } })
    cx += textW + gap
  })
}

function addContactRow() {
  const y = nextY(120)
  const x = 80
  const lineH = 22
  const gap = 24
  // Use simple separators; icons can be added later as image elements
  const items = ['email@domain.com', '+1 555 555 555', 'City, Country', 'linkedin.com/in/username']
  let cx = x
  items.forEach((txt) => {
    const w = Math.max(140, 8 * txt.length)
    store.addElement({ type: 'text', x: cx, y, width: w, height: lineH, z: 1, props: { text: txt, fontSize: 12, fill: '#4B5563' } })
    cx += w + gap
  })
}
</script>

<style scoped>
.btn { @apply px-2 py-1 text-xs border rounded hover:bg-gray-50 flex items-center justify-center gap-1.5; }
.btn-outline { @apply border-gray-300 text-gray-700; }
</style>