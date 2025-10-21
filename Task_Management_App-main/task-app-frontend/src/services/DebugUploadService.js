// Debug File Upload Service
class DebugUploadService {
  static baseURL = 'http://localhost:8000';

  static async testUpload(file) {
    const formData = new FormData();
    formData.append('attachment', file);

    try {
      const response = await fetch(`${this.baseURL}/debug-upload.php`, {
        method: 'POST',
        body: formData,
      });

      const data = await response.json();
      
      if (!response.ok) {
        throw new Error(data.message || 'Debug upload failed');
      }
      
      return data;
    } catch (error) {
      throw new Error(`Debug upload error: ${error.message}`);
    }
  }
}

export default DebugUploadService;