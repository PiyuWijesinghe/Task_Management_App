// Quick debug script to test auth state
(async function debugAuth() {
    const token = localStorage.getItem('authToken');
    console.log('🔑 Token from localStorage:', token);
    
    if (!token) {
        console.log('❌ No token found');
        return;
    }
    
    try {
        const response = await fetch('http://127.0.0.1:8000/api/v1/debug/auth', {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`,
            },
        });
        
        const data = await response.json();
        console.log('🔍 Auth debug data:', data);
        
        // Test with a file upload to see what data is sent
        if (data.auth_check) {
            console.log('✅ User authenticated');
            console.log('🆔 Auth ID:', data.auth_id, 'Type:', data.auth_id_type);
            console.log('👤 User object:', data.user_object);
        } else {
            console.log('❌ User not authenticated');
        }
        
    } catch (error) {
        console.error('❌ Debug auth failed:', error);
    }
})();