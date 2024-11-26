"use client"; // Enable client-side rendering
import { useState } from 'react';
import axios from 'axios';
import { useRouter } from 'next/navigation'; // 🚀 Import useRouter for navigation

export default function Home() {
    const [email, setEmail] = useState(''); // 📧 User email
    const [password, setPassword]  = useState(''); // 🔒 User password
    const router = useRouter(); // 🛤️ Initialize the router for navigation

    const login = async () => {
        try {
            // 🚀 Send login request to the backend
            const response = await axios.post('http://secure-jwt-backend.test/api/login', {
                email,
                password
            }, { withCredentials: true }); // 🍪 Include cookies in the request

            // 🛡️ Store the CSRF token for future requests
            const csrfToken = response.data.csrf_token;
            if (csrfToken) {
                localStorage.setItem('csrf_token', csrfToken); // Store CSRF token in localStorage
            } else {
                throw new Error("CSRF token not received");
            }

            // 🔀 Navigate to the Posts page after successful login
            router.push('/posts'); // Navigate to /posts page
        } catch (error) {
            console.error('Login failed 😞', error);
            alert('Login failed! Please try again.');
        }
    };

    return (
        <div style={{ padding: '20px', textAlign: 'center' }}>
            <h1>Login Page 🚪</h1>
            <input
                type="email"
                placeholder="Email"
                value={email}
                onChange={(e) => setEmail(e.target.value)}
                style={{ margin: '5px', padding: '10px' }}
            />
            <input
                type="password"
                placeholder="Password"
                value={password}
                onChange={(e) => setPassword(e.target.value)}
                style={{ margin: '5px', padding: '10px' }}
            />
            <button onClick={login} style={{ padding: '10px 20px', cursor: 'pointer' }}>
                Login 🔑
            </button>
        </div>
    );
}
