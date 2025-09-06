import api from '@/services/api';

export default {
  // Get user profile
  async getProfile() {
    try {
      const response = await api.get('/user');
      return response.data;
    } catch (error) {
      console.error('Error fetching user profile:', error);
      throw error;
    }
  },

  // Update user profile
  async updateProfile(profileData) {
    try {
      const response = await api.put('/user/profile', profileData);
      return response.data;
    } catch (error) {
      console.error('Error updating profile:', error);
      throw error;
    }
  },

  // Update user skills
  async updateSkills(skills) {
    try {
      const response = await api.put('/user/skills', { skills });
      return response.data;
    } catch (error) {
      console.error('Error updating skills:', error);
      throw error;
    }
  },

  // Update work experience
  async updateExperience(experience) {
    try {
      const response = await api.put('/user/experience', { experience });
      return response.data;
    } catch (error) {
      console.error('Error updating experience:', error);
      throw error;
    }
  }
};
