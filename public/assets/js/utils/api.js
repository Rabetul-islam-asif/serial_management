/* Fetch API wrapper with auto CSRF injection and unified error handling */

class ApiService {
    static async fetch(url, options = {}) {
        const defaultHeaders = {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        };

        // Extract CSRF token from DOM meta/inputs
        const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
        const csrfToken = csrfTokenMeta ? csrfTokenMeta.getAttribute('content') : '';

        if (csrfToken) {
            defaultHeaders['X-CSRF-TOKEN'] = csrfToken;
        }

        if (options.body && !(options.body instanceof FormData) && typeof options.body === 'object') {
            defaultHeaders['Content-Type'] = 'application/json';
            options.body = JSON.stringify(options.body);
        }

        options.headers = {
            ...defaultHeaders,
            ...options.headers
        };

        try {
            const response = await fetch(url, options);
            const data = await response.json().catch(() => ({}));

            if (!response.ok) {
                // Handle session expiry redirects
                if (response.status === 401) {
                    window.location.reload();
                    return;
                }
                
                const errorMessage = data.error || `Request failed with status ${response.status}`;
                throw new Error(errorMessage);
            }

            return data;
        } catch (error) {
            console.error('API Error:', error);
            throw error;
        }
    }

    static get(url, headers = {}) {
        return this.fetch(url, { method: 'GET', headers });
    }

    static post(url, body = {}, headers = {}) {
        return this.fetch(url, { method: 'POST', body, headers });
    }

    static put(url, body = {}, headers = {}) {
        return this.fetch(url, { method: 'PUT', body, headers });
    }

    static delete(url, headers = {}) {
        return this.fetch(url, { method: 'DELETE', headers });
    }
}

window.API = ApiService;
