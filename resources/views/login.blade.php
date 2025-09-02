<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Incident Management System - Login</title>
    <style>
        :root {
            --primary: #5B307E;
            --primary-hover: #7B4A9E;
            --error: #D32F2F;
            --error-bg: #FFEBEE;
            --background: #FFFFFF;
            --input-bg: #F8FAFC;
            --text: #1E293B;
            --border: #E2E8F0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        body {
            background: var(--background);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        html, body {
            height: 100%;
        }

        body::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle at 30% 30%, rgba(91, 48, 126, 0.1), transparent 70%);
            z-index: -2;
            animation: gradientShift 20s ease-in-out infinite;
        }

        @keyframes gradientShift {
            0% { transform: translate(0, 0) rotate(0deg); }
            50% { transform: translate(10%, 10%) rotate(45deg); }
            100% { transform: translate(0, 0) rotate(0deg); }
        }

        .login-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 2.5rem;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08), 0 0 0 1px rgba(91, 48, 126, 0.1);
            width: 100%;
            max-width: 440px;
            position: relative;
            animation: slideIn 0.7s cubic-bezier(0.4, 0, 0.2, 1);
            transition: transform 0.3s ease;
            margin: 0 auto;
        }

        .login-container:hover {
            transform: translateY(-4px) scale(1.01);
        }

        .login-logo {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 2.7rem;
            padding-top: 10px;
            padding-bottom: 10px;
        }

        .login-logo img {
            max-width: 180px;
            width: 100%;
            display: block;
            margin: 0 auto;
            filter: drop-shadow(0 6px 18px rgba(91,48,126,0.13));
            background: #fff;
            border-radius: 12px;
            padding: 8px 0 8px 0;
            box-sizing: border-box;
            transition: transform 0.3s cubic-bezier(.4,0,.2,1);
        }

        .login-logo img:hover {
            transform: scale(1.04) rotate(-2deg);
        }

        .login-container h1 {
            color: var(--primary);
            text-align: center;
            margin: 5rem 0 2.5rem;
            font-size: 2rem;
            font-weight: 700;
            letter-spacing: -0.02rem;
            line-height: 1.2;
            background: linear-gradient(45deg, var(--primary), var(--primary-hover));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .login-container form {
            display: flex;
            flex-direction: column;
            gap: 1.8rem;
        }

        .form-group {
            position: relative;
        }

        .login-container label {
            color: var(--text);
            font-size: 0.9rem;
            font-weight: 500;
            margin-bottom: 0.5rem;
            display: block;
            transition: color 0.2s ease;
        }

        .login-container input {
            width: 100%;
            padding: 1rem 1.2rem;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 1rem;
            background: var(--input-bg);
            color: var(--text);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        .login-container input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(91, 48, 126, 0.1), 0 2px 8px rgba(91, 48, 126, 0.15);
            transform: translateY(-1px);
        }

        .login-container input::placeholder {
            color: #94A3B8;
        }

        .login-container button {
            background: var(--primary);
            color: var(--background);
            padding: 1.1rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .login-container button::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.6s ease, height 0.6s ease;
        }

        .login-container button:hover {
            background: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(91, 48, 126, 0.2);
        }

        .login-container button:hover::after {
            width: 300px;
            height: 300px;
        }

        .error-message {
            color: var(--error);
            font-size: 0.85rem;
            text-align: center;
            margin-top: 1rem;
            padding: 0.75rem;
            background: var(--error-bg);
            border-radius: 6px;
            border: 1px solid rgba(211, 47, 47, 0.2);
            display: none;
            animation: shake 0.4s cubic-bezier(0.36, 0.07, 0.19, 0.97) both;
            transform: translate3d(0, 0, 0);
            backface-visibility: hidden;
        }

        .input-error {
            border-color: var(--error) !important;
            box-shadow: 0 0 0 3px rgba(211, 47, 47, 0.1) !important;
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateY(20px) scale(0.95); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        @keyframes shake {
            10%, 90% { transform: translate3d(-1px, 0, 0); }
            20%, 80% { transform: translate3d(2px, 0, 0); }
            30%, 50%, 70% { transform: translate3d(-4px, 0, 0); }
            40%, 60% { transform: translate3d(4px, 0, 0); }
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 2rem;
                margin: 1rem auto;
                max-width: 95vw;
            }

            .login-container h1 {
                font-size: 1.7rem;
                margin: 4.5rem 0 2rem;
            }

            .login-logo img {
                max-width: 110px;
            }

            .login-container input {
                padding: 0.9rem 1rem;
            }

            .login-container button {
                padding: 1rem;
            }

            .form-group label {
                font-size: 0.85rem;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .login-container, .login-container input, .login-container button, .error-message {
                animation: none;
                transition: none;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div style="display:flex;justify-content:center;align-items:center;margin-bottom:2rem;">
            <a href="https://dxc.com" target="_blank" rel="noopener noreferrer" aria-label="Visit DXC Technology website" style="display:block;width:100%;text-align:center;">
                <img src="{{ asset('images/DXC-Logo.png') }}" alt="DXC Logo" style="max-width:140px;display:block;margin:auto;">
            </a>
        </div>
        <h1>Incident Management System</h1>
        <form id="loginForm" action="{{ route('login') }}" method="POST" novalidate>
            @csrf
            <div class="form-group">
                <label for="email" aria-label="Email address">Email</label>
                <input type="email" id="email" name="email" required aria-required="true" placeholder="e.g., user@example.com">
            </div>
            <div class="form-group">
                <label for="password" aria-label="Password">Password</label>
                <input type="password" id="password" name="password" required aria-required="true" placeholder="Enter your password">
            </div>
            <button type="submit" aria-label="Log in">Log In</button>
            @if ($errors->any())
                <div class="error-message" style="display: block;">
                    {{ $errors->first() }}
                </div>
            @endif
        </form>
    </div>

    <script>
        const form = document.getElementById('loginForm');
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');
        const errorMessage = document.querySelector('.error-message');

        form.addEventListener('submit', function(e) {
            let hasErrors = false;
            [emailInput, passwordInput].forEach(input => {
                input.classList.remove('input-error');
                if (!input.value) {
                    input.classList.add('input-error');
                    hasErrors = true;
                }
            });

            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailInput.value)) {
                emailInput.classList.add('input-error');
                hasErrors = true;
            }

            if (hasErrors) {
                e.preventDefault();
                errorMessage.style.display = 'block';
                errorMessage.textContent = 'Please fill in all fields correctly.';
            }
        });

        [emailInput, passwordInput].forEach(input => {
            input.addEventListener('input', function() {
                input.classList.remove('input-error');
                errorMessage.style.display = 'none';
            });
        });
    </script>
</body>
</html>