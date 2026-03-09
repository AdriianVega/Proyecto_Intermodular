'use client';

import { useState } from 'react';
import { useRouter } from 'next/navigation';
import styles from '@/app/assets/scss/admin/Login.module.scss';

export default function Login() {
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const [error, setError] = useState('');
    const router = useRouter();

    const handleSubmit = async (e) => {
        e.preventDefault();
        setError('');

        try {
            const res = await fetch('/backend/api/auth/login.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ email, password }),
            });
            const data = await res.json();

            if (data.success) {
                localStorage.setItem('user_session', JSON.stringify(data.user_session));

                router.push('/admin/dashboard');
            } else {
                setError(data.message);
            }
        } catch (err) {
            setError('Error de conexión con el servidor');
        }
    };

    return (
        <div className={styles.loginContainer}>
            <div className={styles.loginCard}>
                <div className={styles.header}>
                    <img src="/img/web/logo_tierra.png" alt="Logo" />
                    <h1>PANEL DE CONTROL</h1>
                </div>

                {error && <div className={styles.errorAlert}>{error}</div>}

                <form onSubmit={handleSubmit}>
                    <div className={styles.inputGroup}>
                        <label>Email</label>
                        <input 
                            type="email" 
                            value={email} 
                            onChange={(e) => setEmail(e.target.value)} 
                            placeholder="admin@goblenews.com" 
                            required 
                        />
                    </div>
                    <div className={styles.inputGroup}>
                        <label>Contraseña</label>
                        <input 
                            type="password" 
                            value={password} 
                            onChange={(e) => setPassword(e.target.value)} 
                            placeholder="••••••••" 
                            required 
                        />
                    </div>
                    <button type="submit" className={styles.loginBtn}>Acceder</button>
                </form>
            </div>
        </div>
    );
}