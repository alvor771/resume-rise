<template>
  <div class="flex h-screen bg-gray-100 dark:bg-gray-900 overflow-hidden">
    <!-- Left Panel - Form -->
    <div class="w-1/2 h-full overflow-y-auto p-6 bg-white dark:bg-gray-800 shadow-lg">
      <div class="max-w-3xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">
          {{ $t('resumeBuilder.title') }}
        </h1>
        
        <!-- Personal Information -->
        <div class="mb-8 bg-white dark:bg-gray-700 rounded-lg p-6 shadow">
          <SectionHeader 
            :title="$t('resumeBuilder.personalInfo')"
            title-class="text-xl font-semibold text-gray-800 dark:text-white"
          />
          
          <!-- Photo and Name Row -->
          <div class="flex flex-col md:flex-row gap-6 mb-6">
            <!-- Photo Upload Area -->
            <div class="w-full md:w-48 flex-shrink-0">
              <PhotoUpload
                v-model="resumeData.photo"
                :label="$t('resumeBuilder.photo')"
                :upload-text="$t('resumeBuilder.uploadPhoto')"
                :drag-drop-text="$t('resumeBuilder.dragDrop')"
                :file-types-text="$t('resumeBuilder.fileTypes')"
                :remove-text="$t('resumeBuilder.removePhoto')"
                :alt-text="$t('resumeBuilder.profilePhoto')"
                height="200px"
                @update:modelValue="handlePhotoChange"
              />
            </div>
            
            <!-- Name and Contact Info -->
            <div class="flex-1 space-y-4">
              <BaseInput
                v-model="resumeData.fullName"
                :label="$t('resumeBuilder.fullName')"
                :placeholder="$t('resumeBuilder.fullNamePlaceholder')"
                required
              />
              
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <BaseInput
                  v-model="resumeData.profession"
                  :label="$t('resumeBuilder.profession')"
                  :placeholder="$t('resumeBuilder.professionPlaceholder')"
                />
                
                <BaseInput
                  v-model="resumeData.email"
                  type="email"
                  :label="$t('resumeBuilder.email')"
                  placeholder="your.email@example.com"
                />
                
                <BaseInput
                  v-model="resumeData.phone"
                  type="tel"
                  :label="$t('resumeBuilder.phone')"
                  :placeholder="$t('resumeBuilder.phonePlaceholder')"
                />
              </div>
            </div>
          </div>
          
          <!-- Summary -->
          <BaseTextarea
            v-model="resumeData.summary"
            :label="$t('resumeBuilder.summary')"
            :placeholder="$t('resumeBuilder.summaryPlaceholder')"
            :rows="3"
          />
        </div>

        <!-- Work Experience -->
        <div class="mb-8 bg-white dark:bg-gray-700 rounded-lg p-6 shadow">
          <SectionHeader :title="$t('resumeBuilder.workExperience')">
            <template #actions>
              <BaseButton 
                @click="addWorkExperience"
                size="sm"
                variant="primary"
                class="inline-flex items-center"
              >
                <svg class="-ml-0.5 mr-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                {{ $t('common.add') }}
              </BaseButton>
            </template>
          </SectionHeader>
          
          <div 
            v-for="(exp, index) in resumeData.experience" 
            :key="index" 
            class="mb-6 pb-6 border-b border-gray-200 dark:border-gray-600 last:border-0 last:mb-0 last:pb-0"
          >
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
              <BaseInput
                v-model="exp.position"
                :label="$t('resumeBuilder.position')"
                :placeholder="$t('resumeBuilder.positionPlaceholder')"
                required
              />
              
              <BaseInput
                v-model="exp.company"
                :label="$t('resumeBuilder.company')"
                :placeholder="$t('resumeBuilder.companyPlaceholder')"
                required
              />
              
              <DatePicker
                v-model="exp.startDate"
                :label="$t('resumeBuilder.startDate')"
                type="month"
                required
              />
              
              <DatePicker
                v-model="exp.endDate"
                :label="$t('resumeBuilder.endDate')"
                type="month"
                :min="exp.startDate"
              />
            </div>
            
            <BaseTextarea
              v-model="exp.responsibilities"
              :label="$t('resumeBuilder.responsibilities')"
              :placeholder="$t('resumeBuilder.responsibilitiesPlaceholder')"
              :rows="3"
              required
            />
            
            <div class="flex justify-end mt-2">
              <Button 
                @click="removeWorkExperience(index)"
                variant="ghost"
                size="sm"
                class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300"
              >
                {{ $t('common.remove') }}
              </Button>
            </div>
          </div>
        </div>

        <!-- Education -->
        <div class="mb-8 bg-white dark:bg-gray-700 rounded-lg p-6 shadow">
          <SectionHeader :title="$t('resumeBuilder.education')">
            <template #actions>
              <Button 
                @click="addEducation"
                size="sm"
                variant="primary"
                class="inline-flex items-center"
              >
                <svg class="-ml-0.5 mr-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                {{ $t('common.add') }}
              </Button>
            </template>
          </SectionHeader>
          
          <div 
            v-for="(edu, index) in resumeData.education" 
            :key="index"
            class="mb-6 pb-6 border-b border-gray-200 dark:border-gray-600 last:border-0 last:mb-0 last:pb-0"
          >
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <BaseInput
                v-model="edu.degree"
                :label="$t('resumeBuilder.degree')"
                :placeholder="$t('resumeBuilder.degreePlaceholder')"
                required
              />
              
              <BaseInput
                v-model="edu.institution"
                :label="$t('resumeBuilder.institution')"
                :placeholder="$t('resumeBuilder.institutionPlaceholder')"
                required
              />
              
              <DatePicker
                v-model="edu.year"
                :label="$t('resumeBuilder.year')"
                :placeholder="$t('resumeBuilder.yearPlaceholder')"
                type="month"
                required
              />
            </div>
            
            <div class="flex justify-end mt-2">
              <Button 
                @click="removeEducation(index)"
                variant="ghost"
                size="sm"
                class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300"
              >
                {{ $t('common.remove') }}
              </Button>
            </div>
          </div>
        </div>

        
        <!-- Skills -->
        <div class="mb-8 bg-white dark:bg-gray-700 rounded-lg p-6 shadow">
          <SectionHeader :title="$t('resumeBuilder.skills')" />
          
          <ChipInput
            v-model="resumeData.skills"
            :placeholder="$t('resumeBuilder.skillsPlaceholder')"
            :add-button-text="$t('common.add')"
            :max-items="20"
            class="mb-4"
          />
          
          <p v-if="resumeData.skills.length === 0" class="text-sm text-gray-500 dark:text-gray-400">
            {{ $t('resumeBuilder.noSkillsAdded') }}
          </p>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row justify-end gap-3 mt-8">
          <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
            <Button 
              @click="resetForm"
              variant="outline"
              class="w-full sm:w-auto"
            >
              {{ $t('common.reset') }}
            </Button>
            
            <Button 
              @click="saveDraft"
              variant="secondary"
              class="w-full sm:w-auto"
            >
              {{ $t('common.saveDraft') }}
            </Button>
          </div>
          
          <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
            <Button 
              @click="downloadPDF"
              variant="primary"
              class="w-full sm:w-auto"
            >
              <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
              </svg>
              {{ $t('common.downloadPDF') }}
            </Button>
            
            <Button 
              @click="downloadDOCX"
              variant="primary"
              class="w-full sm:w-auto"
            >
              <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
              </svg>
              {{ $t('common.downloadDOCX') }}
            </Button>
          </div>
        </div>
      </div>
    </div>

    <!-- Right Panel - Preview -->
    <div class="w-1/2 h-full overflow-y-auto bg-gray-50 dark:bg-gray-900 p-8">
      <div class="max-w-2xl mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
        <!-- Header with Photo -->
        <div class="p-8 bg-indigo-700 dark:bg-indigo-900 text-white">
          <div class="flex flex-col md:flex-row items-center gap-6">
            <!-- Photo -->
            <div v-if="resumeData.photo" class="w-32 h-32 md:w-40 md:h-40 flex-shrink-0 overflow-hidden rounded-full border-4 border-white dark:border-indigo-200 shadow-md">
              <img :src="resumeData.photo" alt="Profile" class="w-full h-full object-cover" />
            </div>
            <!-- Name and Title -->
            <div class="text-center md:text-left">
              <h1 class="text-3xl font-bold">{{ resumeData.fullName || t('resumeBuilder.yourName') }}</h1>
              <p class="text-lg text-indigo-100 dark:text-indigo-200">{{ resumeData.profession || t('resumeBuilder.yourProfession') }}</p>
              
              <div class="mt-4 flex flex-wrap gap-4 text-sm text-indigo-100">
                <div v-if="resumeData.email" class="flex items-center">
                  <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                  </svg>
                  {{ resumeData.email }}
                </div>
                <div v-if="resumeData.phone" class="flex items-center">
                  <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                  </svg>
                  {{ resumeData.phone }}
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Preview Content -->
        <div class="p-8">
          <!-- Summary -->
          <div v-if="resumeData.summary" class="mb-8">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-2 mb-4">
              {{ $t('resumeBuilder.summary') }}
            </h2>
            <p class="text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ resumeData.summary }}</p>
          </div>

          <!-- Work Experience -->
          <div v-if="resumeData.experience && resumeData.experience.length > 0" class="mb-8">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-2 mb-4">
              {{ $t('resumeBuilder.workExperience') }}
            </h2>
            <div v-for="(exp, index) in resumeData.experience" :key="'exp-'+index" class="mb-6">
              <div class="flex justify-between">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ exp.position || t('resumeBuilder.jobTitlePlaceholder') }}</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                  {{ exp.startDate || t('common.start') }} - {{ exp.endDate ? exp.endDate : t('resumeBuilder.present') }}
                </p>
              </div>
              <p class="text-indigo-700 dark:text-indigo-400 font-medium">{{ exp.company || t('resumeBuilder.companyPlaceholder') }}</p>
              <p class="text-gray-700 dark:text-gray-300 whitespace-pre-line mt-2">{{ exp.responsibilities || t('resumeBuilder.responsibilitiesPlaceholder') }}</p>
            </div>
          </div>

          <!-- Education -->
          <div v-if="resumeData.education && resumeData.education.length > 0" class="mb-8">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-2 mb-4">
              {{ $t('resumeBuilder.education') }}
            </h2>
            <div v-for="(edu, index) in resumeData.education" :key="'edu-'+index" class="mb-4">
              <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ edu.degree || t('resumeBuilder.degreePlaceholder') }}</h3>
              <p class="text-indigo-700 dark:text-indigo-400 font-medium">{{ edu.institution || t('resumeBuilder.institutionPlaceholder') }}</p>
              <p class="text-sm text-gray-500 dark:text-gray-400">{{ edu.year || t('common.year') }}</p>
            </div>
          </div>

          <!-- Languages -->
          <div v-if="resumeData.languages && resumeData.languages.length > 0" class="mb-8">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-2 mb-4">
              {{ $t('resumeBuilder.languages') }}
            </h2>
            <div class="space-y-2">
              <div v-for="(language, index) in resumeData.languages" :key="index" class="flex justify-between items-center">
                <span class="font-medium text-gray-800 dark:text-gray-200">{{ language.name }}</span>
                <span class="text-sm text-gray-600 dark:text-gray-400">
                  {{ $t(`resumeBuilder.proficiencyLevels.${language.proficiency}`) }}
                </span>
              </div>
            </div>
          </div>

          <!-- Skills -->
          <div v-if="resumeData.skills.length > 0" class="mb-8">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-2 mb-4">
              {{ $t('resumeBuilder.skills') }}
            </h2>
            <div class="flex flex-wrap gap-2">
              <span 
                v-for="(skill, index) in resumeData.skills" 
                :key="index"
                class="inline-block bg-gray-100 dark:bg-gray-700 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 dark:text-gray-200"
              >
                {{ skill }}
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref } from 'vue';
import { useI18n } from 'vue-i18n';
import html2pdf from 'html2pdf.js';
import { Document, Packer, Paragraph, HeadingLevel, AlignmentType } from 'docx';
import { saveAs } from 'file-saver';

// Import components
import BaseInput from '@/components/ui/BaseInput.vue';
import BaseTextarea from '@/components/ui/BaseTextarea.vue';
import BaseButton from '@/components/ui/Button.vue';
import ChipInput from '@/components/ui/ChipInput.vue';
import DatePicker from '@/components/ui/DatePicker.vue';
import PhotoUpload from '@/components/ui/PhotoUpload.vue';
import SectionHeader from '@/components/ui/SectionHeader.vue';

export default {
  name: 'ResumeBuilderView',
  components: {
    BaseInput,
    BaseTextarea,
    BaseButton,
    ChipInput,
    DatePicker,
    PhotoUpload,
    SectionHeader,
  },
  setup() {
    const { t } = useI18n();
    const newSkill = ref('');
    const fileInput = ref(null);
    const isDragging = ref(false);
    
    const resumeData = ref({
      photo: null,
      fullName: '',
      profession: '',
      email: '',
      phone: '',
      address: '',
      summary: '',
      workExperience: [],
      education: [{
        degree: '',
        institution: '',
        year: ''
      }],
      skills: [],
      languages: [],
      projects: [],
      certifications: []
    });
    
    // Handle photo change from PhotoUpload component
    const handlePhotoChange = (file) => {
      if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
          resumeData.value.photo = e.target.result;
        };
        reader.readAsDataURL(file);
      } else {
        resumeData.value.photo = null;
      }
    };

    const addWorkExperience = () => {
      resumeData.value.experience.push({
        position: '',
        company: '',
        startDate: '',
        endDate: '',
        responsibilities: ''
      });
    };

    const removeWorkExperience = (index) => {
      if (resumeData.value.experience.length > 1) {
        resumeData.value.experience.splice(index, 1);
      }
    };

    const addSkill = (skill) => {
      if (skill && !resumeData.value.skills.includes(skill)) {
        resumeData.value.skills.push(skill);
      }
    };

    const removeSkill = (skillToRemove) => {
      resumeData.value.skills = resumeData.value.skills.filter(skill => skill !== skillToRemove);
    };

    const handleFileSelect = (event) => {
      const file = event.target.files[0];
      if (file) {
        handlePhotoChange(file);
      }
    };

    const handleDrop = (event) => {
      event.preventDefault();
      isDragging.value = false;
      const file = event.dataTransfer.files[0];
      if (file) {
        handlePhotoChange(file);
      }
    };

    const addEducation = () => {
      resumeData.value.education.push({
        degree: '',
        institution: '',
        year: ''
      });
    };

    const removeEducation = (index) => {
      if (resumeData.value.education.length > 1) {
        resumeData.value.education.splice(index, 1);
      } else {
        // Reset the form if it's the last item
        resumeData.value.education[0] = {
          degree: '',
          institution: '',
          year: ''
        };
      }
    };

    const resetForm = () => {
      if (confirm(t('common.confirmReset'))) {
        resumeData.value = {
          photo: null,
          fullName: '',
          profession: '',
          email: '',
          phone: '',
          summary: '',
          experience: [
            {
              position: '',
              company: '',
              startDate: '',
              endDate: '',
              responsibilities: ''
            }
          ],
          education: [
            {
              degree: '',
              institution: '',
              year: ''
            }
          ],
          skills: []
        };
        newSkill.value = '';
      }
    };

    // Commented out as it's not currently used
    // const saveDraft = async () => {
    //   try {
    //     // Save to local storage or API
    //     localStorage.setItem('resumeDraft', JSON.stringify(resumeData.value));
    //     alert(t('common.draftSaved'));
    //   } catch (error) {
    //     console.error('Error saving draft:', error);
    //     alert(t('common.errorSavingDraft'));
    //   }
    // };

    const generatePDF = async () => {
      const element = document.getElementById('resume-preview');
      const opt = {
        margin: 10,
        filename: `${resumeData.value.fullName || 'resume'}.pdf`,
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { scale: 2 },
        jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
      };

      // Generate the PDF
      await html2pdf().set(opt).from(element).save();
    };

    const generateDOCX = async () => {
      // Helper function to create a section header
      const createSectionHeader = (text) => {
        return new Paragraph({
          text: text,
          heading: HeadingLevel.HEADING_3,
          spacing: { before: 600, after: 200 },
          border: { 
            bottom: { 
              color: "#000000",
              space: 1,
              style: "single",
              size: 6
            }
          }
        });
      };

      // Create a new document
      const doc = new Document({
        sections: [{
          properties: {},
          children: []
        }]
      });

      // Add name and title
      if (resumeData.value.fullName) {
        doc.sections[0].children.push(
          new Paragraph({
            text: resumeData.value.fullName,
            heading: HeadingLevel.HEADING_1,
            alignment: AlignmentType.CENTER,
            spacing: { after: 200 }
          })
        );
      }

      if (resumeData.value.profession) {
        doc.sections[0].children.push(
          new Paragraph({
            text: resumeData.value.profession,
            heading: HeadingLevel.HEADING_2,
            alignment: AlignmentType.CENTER,
            spacing: { after: 400 }
          })
        );
      }

      // Add contact information
      const contactInfo = [];
      if (resumeData.value.email) contactInfo.push(resumeData.value.email);
      if (resumeData.value.phone) contactInfo.push(resumeData.value.phone);

      if (contactInfo.length > 0) {
        doc.sections[0].children.push(
          new Paragraph({
            text: contactInfo.join(' | '),
            alignment: AlignmentType.CENTER,
            spacing: { after: 600 }
          })
        );
      }

      // Add summary
      if (resumeData.value.summary) {
        doc.sections[0].children.push(
          createSectionHeader('SUMMARY'),
          new Paragraph({
            text: resumeData.value.summary,
            spacing: { after: 400 }
          })
        );
      }
      
      // Add work experience
      if (resumeData.value.experience?.length > 0) {
        doc.sections[0].children.push(createSectionHeader('EXPERIENCE'));
        
        resumeData.value.experience.forEach(exp => {
          if (exp.position || exp.company) {
            const expItems = [];
            if (exp.position) expItems.push(exp.position);
            if (exp.company) expItems.push(exp.company);
            
            doc.sections[0].children.push(
              new Paragraph({
                text: expItems.join(' at '),
                bold: true,
                spacing: { before: 200 }
              })
            );
            
            const dateItems = [];
            if (exp.startDate) dateItems.push(exp.startDate);
            if (exp.endDate) dateItems.push(exp.endDate);
            
            if (dateItems.length > 0) {
              doc.sections[0].children.push(
                new Paragraph({
                  text: dateItems.join(' - '),
                  italics: true,
                  spacing: { after: 100 }
                })
              );
            }
            
            if (exp.responsibilities) {
              doc.sections[0].children.push(
                new Paragraph({
                  text: exp.responsibilities,
                  spacing: { after: 200 }
                })
              );
            }
          }
        });
      }

      // Add education
      if (resumeData.value.education?.length > 0) {
        doc.sections[0].children.push(createSectionHeader('EDUCATION'));
        
        resumeData.value.education.forEach(edu => {
          if (edu.degree || edu.institution) {
            const eduItems = [];
            if (edu.degree) eduItems.push(edu.degree);
            if (edu.institution) eduItems.push(edu.institution);
            
            doc.sections[0].children.push(
              new Paragraph({
                text: eduItems.join(', '),
                bold: true,
                spacing: { before: 200 }
              })
            );
            
            if (edu.year) {
              doc.sections[0].children.push(
                new Paragraph({
                  text: edu.year,
                  italics: true,
                  spacing: { after: 200 }
                })
              );
            }
          }
        });
      }

      // Add skills
      if (resumeData.value.skills?.length > 0) {
        doc.sections[0].children.push(
          createSectionHeader('SKILLS'),
          new Paragraph({
            text: resumeData.value.skills.join(', '),
            spacing: { before: 200, after: 200 }
          })
        );
      }

      // Generate the document
      const blob = await Packer.toBlob(doc);
      
      // Save the file
      saveAs(blob, `${resumeData.value.fullName || 'resume'}.docx`);
    };

    const downloadPDF = async () => {
      try {
        await generatePDF();
      } catch (error) {
        console.error('PDF download failed:', error);
        alert(t('errors.pdfDownloadFailed'));
      }
    };

    const downloadDOCX = async () => {
      try {
        await generateDOCX();
      } catch (error) {
        console.error('DOCX download failed:', error);
        alert(t('errors.docxDownloadFailed'));
      }
    };

    return {
      resumeData,
      newSkill,
      fileInput,
      isDragging,
      addWorkExperience,
      removeWorkExperience,
      addEducation,
      removeEducation,
      addSkill,
      removeSkill,
      resetForm,
      downloadPDF,
      downloadDOCX,
      t,
      handleFileSelect,
      handleDrop,
      handlePhotoChange
    };
  }
};
</script>

<style scoped>
/* Custom scrollbar */
::-webkit-scrollbar {
  width: 8px;
  height: 8px;
}

::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 4px;
}

::-webkit-scrollbar-thumb {
  background: #888;
  border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
  background: #555;
}

/* Dark mode scrollbar */
.dark ::-webkit-scrollbar-track {
  background: #374151;
}

.dark ::-webkit-scrollbar-thumb {
  background: #4b5563;
}

.dark ::-webkit-scrollbar-thumb:hover {
  background: #6b7280;
}
</style>