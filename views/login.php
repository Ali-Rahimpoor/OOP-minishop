<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>ورود ادمین | Mini Shop</title>
    <style>
        /* استایل کلی فرم */
        form {
            max-width: 380px;
            margin: 50px auto;
            padding: 40px 35px;
            background: linear-gradient(145deg, #ffffff, #f5f7fa);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1), 
                        0 1px 8px rgba(0, 0, 0, 0.06);    
            direction: rtl;
            transition: all 0.3s ease;
        }

        form:hover {
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }

        /* استایل دیوهای فرم */
        form div {
            margin-bottom: 22px;
        }

        /* استایل لیبل‌ها */
        form label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 6px;
            letter-spacing: 0.3px;
        }

        /* استایل اینپوت‌ها */
        form input[type="text"],
        form input[type="password"] {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 15px;
            color: #2d3748;
            background: #f7fafc;
            transition: all 0.3s ease;
            box-sizing: border-box;
            font-family: inherit;
        }

        form input[type="text"]:focus,
        form input[type="password"]:focus {
            outline: none;
            border-color: #667eea;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.15);
        }

        form input[type="text"]::placeholder,
        form input[type="password"]::placeholder {
            color: #a0aec0;
            font-size: 14px;
        }

        /* استایل دکمه */
        form button[type="submit"] {
            width: 100%;
            padding: 14px 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            letter-spacing: 0.5px;
            margin-top: 8px;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        form button[type="submit"]:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.5);
        }

        form button[type="submit"]:active {
            transform: translateY(0);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        /* استایل برای نمایش پیام خطا (اختیاری) */
        form .error {
            color: #e53e3e;
            font-size: 13px;
            margin-top: 5px;
            display: none;
        }

        /* ریسپانسیو برای موبایل */
        @media (max-width: 480px) {
            form {
                margin: 20px 15px;
                padding: 30px 20px;
            }
            
            form input[type="text"],
            form input[type="password"] {
                padding: 10px 14px;
                font-size: 14px;
            }
            
            form button[type="submit"] {
                padding: 12px 16px;
                font-size: 15px;
            }
        }
    </style>
</head>
<body>

    <?php if (!empty($error)): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" action="<?= site_url('login'); ?>">
        <div>
            <label>نام کاربری</label><br>
            <input type="text" name="username">
        </div>
        <div>
            <label>رمز عبور</label><br>
            <input type="password" name="password">
        </div>
        <button type="submit">ورود</button>
    </form>

</body>
</html>