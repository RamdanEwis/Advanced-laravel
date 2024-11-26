"use client"; // Enable client-side rendering
import { useEffect, useState } from 'react';
import axios from 'axios';

export default function Posts() {
    const [posts, setPosts] = useState([]); // 📝 List of user posts

    useEffect(() => {
        const fetchPosts = async () => {
            try {
                const csrfToken = localStorage.getItem('csrf_token'); // Get CSRF token
                if (!csrfToken) throw new Error("CSRF token is missing");

                const response = await axios.get('http://secure-jwt-backend.test/api/user-posts', {
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    withCredentials: true, // Include cookies in the request
                });

                console.log(response.data);
                setPosts(response.data);
            } catch (error) {
                console.error('Error fetching posts:', error);
            }
        };

        fetchPosts();
    }, []);

    return (
        <div style={{ padding: '20px' }}>
            <h1>User Posts 📝</h1>
            {posts.length === 0 ? (
                <p>No posts found 😢</p>
            ) : (
                posts.map((post: any, index: number) => (
                    <div key={index} style={{ border: '1px solid #ddd', margin: '10px', padding: '10px' }}>
                        <h2>{post.title}</h2>
                        <p>{post.content}</p>
                    </div>
                ))
            )}
        </div>
    );
}
