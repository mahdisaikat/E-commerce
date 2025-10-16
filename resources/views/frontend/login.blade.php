<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #0d1117;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            overflow: hidden;
        }

        .contribution-grid {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: grid;
            grid-template-columns: repeat(auto-fill, 15px);
            grid-template-rows: repeat(auto-fill, 15px);
            gap: 3px;
            padding: 20px;
            box-sizing: border-box;
            z-index: 0;
        }

        .grid-cell {
            background-color: #161b22;
            border-radius: 2px;
        }

        .grid-cell.active {
            background-color: #0e4429;
        }

        .login-container {
            background-color: #161b22;
            padding: 40px;
            border-radius: 6px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.5);
            width: 320px;
            position: relative;
            z-index: 1;
            border: 1px solid #30363d;
        }

        .signin-title {
            color: #f0f6fc;
            margin-bottom: 30px;
            font-size: 24px;
            font-weight: 300;
            letter-spacing: 0.5px;
            text-align: center;
        }

        .login-form {
            display: flex;
            flex-direction: column;
        }

        .input-group {
            margin-bottom: 20px;
        }

        .input-group input {
            width: 100%;
            padding: 12px 0;
            background: transparent;
            border: none;
            border-bottom: 1px solid #30363d;
            color: #c9d1d9;
            font-size: 16px;
        }

        .input-group input:focus {
            outline: none;
            border-bottom-color: #58a6ff;
        }

        .input-group input::placeholder {
            color: #484f58;
        }

        .links {
            display: flex;
            justify-content: space-between;
            margin: 15px 0 30px;
        }

        .links a {
            color: #58a6ff;
            text-decoration: none;
            font-size: 13px;
        }

        .links a:hover {
            text-decoration: underline;
        }

        .login-button {
            background-color: #238636;
            color: white;
            border: none;
            padding: 12px;
            font-size: 14px;
            cursor: pointer;
            border-radius: 6px;
            font-weight: 500;
            transition: background-color 0.2s;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .login-button:hover {
            background-color: #2ea043;
        }

        /* Special button styling to match original image */
        .special-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }

        .special-left-btn {
            background: transparent;
            border: none;
            color: #8b949e;
            font-size: 14px;
            cursor: pointer;
            padding: 8px 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-radius: 6px;
            transition: background-color 0.2s;
        }

        .special-right-btn {
            background-color: #238636;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 14px;
            cursor: pointer;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-radius: 6px;
            font-weight: 500;
            transition: background-color 0.2s;
        }
    </style>
</head>
<body>
    <div class="contribution-grid" id="grid"></div>
    
    <div class="login-container">
        <div class="signin-title">SIGN IN</div>
        
        <form class="login-form">
            <div class="input-group">
                <input type="text" id="username" name="username" placeholder="Username" value="admin">
            </div>
            <div class="input-group">
                <input type="password" id="password" name="password" placeholder="Password" value="12356">
            </div>
            <div class="links">
                <a href="#" class="forgot-password">Forgot Password</a>
                <a href="#" class="signup">Signup</a>
            </div>
            
            <!-- Special buttons from original image -->
            <div class="special-buttons">
                <button type="button" class="special-left-btn"></button>
                <button type="submit" class="special-right-btn">LOGIN</button>
            </div>
        </form>
    </div>

    <script>
        // Create GitHub-like contribution grid
        const grid = document.getElementById('grid');
        const cells = [];
        const rows = Math.ceil(window.innerHeight / 18);
        const cols = Math.ceil(window.innerWidth / 18);
        
        for (let i = 0; i < rows * cols; i++) {
            const cell = document.createElement('div');
            cell.className = 'grid-cell';
            if (Math.random() > 0.7) {
                cell.classList.add('active');
            }
            grid.appendChild(cell);
            cells.push(cell);
        }
        
        // Make some cells more "active" (darker green)
        setInterval(() => {
            cells.forEach(cell => {
                if (Math.random() > 0.95) {
                    cell.classList.toggle('active');
                }
            });
        }, 1000);
    </script>
</body>
</html>