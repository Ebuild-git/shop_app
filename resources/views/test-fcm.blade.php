<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>FCM Token - shopin-6ba94</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
        }
        .container {
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 5px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin: 10px 5px 10px 0;
        }
        button:hover {
            background-color: #45a049;
        }
        #token {
            margin-top: 20px;
            padding: 15px;
            background-color: #f0f0f0;
            border-radius: 4px;
            word-break: break-all;
            min-height: 100px;
            font-family: monospace;
            font-size: 13px;
        }
        .status {
            margin: 15px 0;
            padding: 12px;
            border-radius: 4px;
            display: none;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
        .info {
            background-color: #d1ecf1;
            color: #0c5460;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔥 Firebase FCM Token Generator</h1>
        <p>Project: shopin-6ba94</p>

        <div id="status" class="status"></div>

        <button onclick="generateToken()">📱 Get FCM Token</button>
        <button onclick="copyToken()">📋 Copy Token</button>

        <div id="token">
            <strong>Token will appear here...</strong>
        </div>

        <button onclick="sendToBackend()" style="background-color: #2196F3;">🚀 Send to Laravel Backend</button>
    </div>

    <!-- Modern Firebase SDK v9+ -->
    <script type="module">
        import { initializeApp } from "https://www.gstatic.com/firebasejs/12.9.0/firebase-app.js";
        import { getMessaging, getToken, onMessage } from "https://www.gstatic.com/firebasejs/12.9.0/firebase-messaging.js";

        const firebaseConfig = {
            apiKey: "AIzaSyCRmIOBE30eYr6aPl8baYWFtBYzdGwKjZ4",
            authDomain: "shopin-6ba94.firebaseapp.com",
            projectId: "shopin-6ba94",
            storageBucket: "shopin-6ba94.firebasestorage.app",
            messagingSenderId: "65690731582",
            appId: "1:65690731582:web:ae5be9c4c8185675733d6f",
            measurementId: "G-GNYSJDZ3VE"
        };

        const VAPID_KEY = "BEbFNQlgQ5BwiSTUgY634kb158lH99DJArQkpARFxYgxokc8IGzLBmBa6kGfCOhg8CTkyYPKkS8NS99nQTR4cEo";

        const app = initializeApp(firebaseConfig);
        const messaging = getMessaging(app);

        let fcmToken = null;

        function showStatus(message, type = 'info') {
            const statusDiv = document.getElementById('status');
            statusDiv.textContent = message;
            statusDiv.className = `status ${type}`;
            statusDiv.style.display = 'block';
        }

        window.generateToken = async function() {
            try {
                showStatus('⏳ Requesting notification permission...', 'info');

                const permission = await Notification.requestPermission();

                if (permission !== 'granted') {
                    showStatus('❌ Notification permission denied', 'error');
                    return;
                }

                showStatus('⏳ Generating token...', 'info');

                fcmToken = await getToken(messaging, {
                    vapidKey: VAPID_KEY
                });

                if (fcmToken) {
                    document.getElementById('token').innerHTML = `
                        <strong>✅ Your FCM Token:</strong><br><br>
                        ${fcmToken}
                    `;
                    showStatus('✅ Token generated successfully!', 'success');
                    console.log('FCM Token:', fcmToken);
                } else {
                    showStatus('❌ Failed to generate token', 'error');
                }
            } catch (error) {
                showStatus(`❌ Error: ${error.message}`, 'error');
                console.error('Error:', error);
            }
        };

        window.copyToken = function() {
            if (!fcmToken) {
                showStatus('❌ Generate a token first!', 'error');
                return;
            }

            navigator.clipboard.writeText(fcmToken).then(() => {
                showStatus('✅ Token copied to clipboard!', 'success');
            }).catch(() => {
                showStatus('❌ Failed to copy', 'error');
            });
        };

        window.sendToBackend = async function() {
            if (!fcmToken) {
                showStatus('❌ Generate a token first!', 'error');
                return;
            }

            try {
                showStatus('⏳ Sending token to backend...', 'info');

                const response = await fetch('/api/fcm/register-token', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': getCookie('XSRF-TOKEN') || '',
                        'Accept': 'application/json'
                    },
                    credentials: 'include',
                    body: JSON.stringify({
                        fcm_token: fcmToken
                    })
                });

                const data = await response.json();

                if (data.success) {
                    showStatus('✅ Token registered successfully!', 'success');
                } else {
                    showStatus(`❌ Error: ${data.message}`, 'error');
                }
            } catch (error) {
                showStatus(`❌ Error: ${error.message}`, 'error');
            }
        };

        function getCookie(name) {
            const nameEQ = name + "=";
            const ca = document.cookie.split(';');
            for (let i = 0; i < ca.length; i++) {
                let c = ca[i].trim();
                if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length);
            }
            return '';
        }

        // Listen for foreground messages
        onMessage(messaging, (payload) => {
            console.log('📬 Message received:', payload);
            showStatus(`📬 ${payload.notification.title}`, 'info');
        });

        console.log('✅ Firebase initialized!');
    </script>
</body>
</html>
