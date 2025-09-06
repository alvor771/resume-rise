import axios from 'axios'

const API_URL = 'http://localhost:8000/api/resume' // Update with your actual API endpoint

const resumeService = {
  async getTemplates() {
    try {
      const response = await axios.get(`${API_URL}/templates`)
      return response.data
    } catch (error) {
      console.error('Error fetching templates:', error)
      throw error
    }
  },

  async saveResume(resumeData) {
    try {
      const response = await axios.post(`${API_URL}/save`, resumeData)
      return response.data
    } catch (error) {
      console.error('Error saving resume:', error)
      throw error
    }
  },

  async exportResume(resumeId, format = 'pdf') {
    try {
      const response = await axios.get(`${API_URL}/export/${resumeId}`, {
        params: { format },
        responseType: 'blob'
      })
      return response.data
    } catch (error) {
      console.error('Error exporting resume:', error)
      throw error
    }
  },

  async getResume(resumeId) {
    try {
      const response = await axios.get(`${API_URL}/${resumeId}`)
      return response.data
    } catch (error) {
      console.error('Error fetching resume:', error)
      throw error
    }
  }
}

export default resumeService
