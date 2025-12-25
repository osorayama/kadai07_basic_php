<?php
session_start();

// メッセージ・フォーム値の初期化
$error = '';
$success = '';
$date = '';
$mountain = '';
$elevation = '';
$weather = '';
$content = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = isset($_POST['date']) ? trim($_POST['date']) : '';
    $mountain = isset($_POST['mountain']) ? trim($_POST['mountain']) : '';
    $elevation = isset($_POST['elevation']) ? trim($_POST['elevation']) : '';
    $weather = isset($_POST['weather']) ? trim($_POST['weather']) : '';
    $content = isset($_POST['content']) ? $_POST['content'] : '';

    // 改行を空白に置換（1行保存）
    $content = str_replace(["\r\n", "\r", "\n"], ' ', $content);

    // データ作成と保存
    $line = $date . '|' . $mountain . '|' . $elevation . '|' . $weather . '|' . $content . "\n";
    $file_path = './data/data.txt';
    if (file_put_contents($file_path, $line, FILE_APPEND | LOCK_EX) !== false) {
        $_SESSION['success'] = '登山日記を登録しました！';
        header('Location: ' . $_SERVER['REQUEST_URI']);
        exit;
    } else {
        $error = '保存に失敗しました。';
    }
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>登山日記</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Hiragino Kaku Gothic ProN', 'メイリオ', sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
            padding: 20px 0;
            border-bottom: 3px solid #4CAF50;
        }

        .form-container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 40px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }

        input[type="text"],
        textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            font-family: inherit;
        }

        textarea {
            resize: vertical;
            min-height: 120px;
        }

        input[type="text"]:focus,
        textarea:focus {
            outline: none;
            border-color: #4CAF50;
        }

        .submit-btn {
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .submit-btn:hover {
            background-color: #45a049;
        }

        .error-message {
            background-color: #ffebee;
            color: #c62828;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #c62828;
        }

        .success-message {
            background-color: #e8f5e9;
            color: #2e7d32;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #2e7d32;
        }

        .diary-list {
            margin-top: 40px;
        }

        .diary-list h2 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 2px solid #4CAF50;
        }

        .diary-item {
            background-color: white;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .diary-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }

        .diary-name {
            font-weight: bold;
            color: #4CAF50;
            font-size: 16px;
        }

        .diary-date {
            color: #999;
            font-size: 14px;
        }

        .diary-content {
            color: #333;
            line-height: 1.6;
            white-space: pre-wrap;
            word-wrap: break-word;
        }

        .no-data {
            text-align: center;
            padding: 40px;
            color: #999;
            background-color: white;
            border-radius: 8px;
        }

    </style>
</head>

<body>
    <div class="container">
        <h1>登山日記</h1>

        <?php
        // メッセージの表示
        if (!empty($error)) {
            echo '<div class="error-message">' . htmlspecialchars($error, ENT_QUOTES, 'UTF-8') . '</div>';
        }
        if (isset($_SESSION['success'])) {
            echo '<div class="success-message">' . htmlspecialchars($_SESSION['success'], ENT_QUOTES, 'UTF-8') . '</div>';
            unset($_SESSION['success']);
        }
        ?>

        <div class="form-container">
            <form method="post" action="">
                <div class="form-group">
                    <label>日付</label>
                    <input type="date" name="date" value="<?php echo isset($date) ? htmlspecialchars($date, ENT_QUOTES, 'UTF-8') : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label>山名</label>
                    <input type="text" name="mountain" value="<?php echo isset($mountain) ? htmlspecialchars($mountain, ENT_QUOTES, 'UTF-8') : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label>標高（m）</label>
                    <input type="number" name="elevation" min="0" max="10000" step="1" value="<?php echo isset($elevation) ? htmlspecialchars($elevation, ENT_QUOTES, 'UTF-8') : ''; ?>" >
                </div>
                <div class="form-group">
                    <label>天気</label>
                    <select name="weather">
                        <option value="晴れ" <?php echo (isset($weather) && $weather==='晴れ') ? 'selected' : ''; ?>>晴れ</option>
                        <option value="くもり" <?php echo (isset($weather) && $weather==='くもり') ? 'selected' : ''; ?>>くもり</option>
                        <option value="雨" <?php echo (isset($weather) && $weather==='雨') ? 'selected' : ''; ?>>雨</option>
                        <option value="雪" <?php echo (isset($weather) && $weather==='雪') ? 'selected' : ''; ?>>雪</option>
                        <option value="強風" <?php echo (isset($weather) && $weather==='強風') ? 'selected' : ''; ?>>強風</option>
                        <option value="霧" <?php echo (isset($weather) && $weather==='霧') ? 'selected' : ''; ?>>霧</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>内容</label>
                    <textarea name="content" placeholder="登山の感想や状況を書いてください..." ><?php echo isset($content) ? htmlspecialchars($content, ENT_QUOTES, 'UTF-8') : ''; ?></textarea>
                </div>
                <button type="submit" class="submit-btn">登録する</button>
            </form>
        </div>

        <div class="diary-list">
            <h2>登山日記一覧</h2>
            <?php
            $file_path = './data/data.txt';
            if (file_exists($file_path)) {
                $lines = file($file_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                if (!empty($lines)) {
                    // 最新順に並び替え（新しいものが先頭）
                    $lines = array_reverse($lines);
                    foreach ($lines as $line) {
                        $parts = explode('|', $line, 5);
                        if (count($parts) === 5) {
                            $date = htmlspecialchars($parts[0], ENT_QUOTES, 'UTF-8');
                            $mountain = htmlspecialchars($parts[1], ENT_QUOTES, 'UTF-8');
                            $elevation = htmlspecialchars($parts[2], ENT_QUOTES, 'UTF-8');
                            $weather = htmlspecialchars($parts[3], ENT_QUOTES, 'UTF-8');
                            $content = htmlspecialchars($parts[4], ENT_QUOTES, 'UTF-8');
                            echo '<div class="diary-item">';
                            echo '<div class="diary-header">';
                            echo '<span class="diary-name">' . $mountain . '（' . $elevation . 'm）</span>';
                            echo '<span class="diary-date">' . $date . '｜' . $weather . '</span>';
                            echo '</div>';
                            echo '<div class="diary-content">' . $content . '</div>';
                            echo '</div>';
                        }
                    }
                } else {
                    echo '<div class="no-data">まだ日記が登録されていません。</div>';
                }
            } else {
                echo '<div class="no-data">まだ日記が登録されていません。</div>';
            }
            ?>
        </div>
    </div>
</body>

</html>
